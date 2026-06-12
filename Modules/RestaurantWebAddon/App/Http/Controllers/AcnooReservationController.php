<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Table;
use App\Models\Option;
use App\Models\TimeSlot;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\ReservationTable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AcnooReservationController extends Controller
{

    public function index()
    {
        $reservations = Reservation::with('tables:id,name')
                                    ->where('business_id', auth()->user()->business_id)
                                    ->latest()
                                    ->paginate(10);
        return view('restaurantwebaddon::reservations.index', compact('reservations'));
    }

     public function acnooFilter(Request $request)
    {
        $businessId = auth()->user()->business_id;
        $search = $request->input('search');

        $filter = $request->custom_days;
        $startDate = null;
        $endDate = null;

        switch ($filter) {

            case 'today':
                $startDate = Carbon::today()->format('Y-m-d');
                $endDate   = Carbon::today()->format('Y-m-d');
                break;

            case 'next_week':
                $startDate = Carbon::now()->startOfWeek()->addWeek()->format('Y-m-d');
                $endDate   = Carbon::now()->endOfWeek()->addWeek()->format('Y-m-d');
                break;

            case 'current_week':
                $startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
                $endDate   = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;

            case 'last_week':
                $startDate = Carbon::now()->startOfWeek()->subWeek()->format('Y-m-d');
                $endDate   = Carbon::now()->endOfWeek()->subWeek()->format('Y-m-d');
                break;

            case 'current_month':
                $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $endDate   = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;

            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
                $endDate   = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
                break;

            case 'current_year':
                $startDate = Carbon::now()->startOfYear()->format('Y-m-d');
                $endDate   = Carbon::now()->endOfYear()->format('Y-m-d');
                break;

            case 'last_year':
                $startDate = Carbon::now()->subYear()->startOfYear()->format('Y-m-d');
                $endDate   = Carbon::now()->subYear()->endOfYear()->format('Y-m-d');
                break;

            default:
                // fallback: today
                $startDate = Carbon::today()->format('Y-m-d');
                $endDate   = Carbon::today()->format('Y-m-d');
                break;
        }

        $reservations = Reservation::where('business_id', $businessId)
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::reservations.datas', compact('reservations'))->render()
            ]);
        }

        return redirect(url()->previous());
    }

    public function create()
    {
        $reservation_setting = Option::where('key', 'reservation-setting')
                                        ->whereJsonContains('value->business_id', auth()->user()->business_id)
                                        ->first();
        return view('restaurantwebaddon::reservations.create', compact('reservation_setting'));
    }

    public function getTimeSlots(Request $request)
    {
        $date = $request->date;
        $type = $request->type;

        $dayName = Carbon::parse($date)->format('l');

        $slot = TimeSlot::where('business_id', auth()->user()->business_id)
                        ->where('slot_type', $type)
                        ->where('day', $dayName)
                        ->where('is_available', 1)
                        ->first();
        if (!$slot) {
          return response()->json([]);
        }

        $start  = Carbon::parse($slot->start_time);
        $end    = Carbon::parse($slot->end_time);
        $gap    = (int)$slot->time_difference;

        $slots = [];

        while ($start <= $end) {
            $slots[] = [
                 'display' => $start->format('h:i A'),
                 'value'   => $start->format('H:i:s'),
            ];
            $start->addMinutes($gap);
        }

        return response()->json($slots);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'guest' => 'required|integer',
            'type' => 'required|string',
            'time' => 'required|string',
        ]);

        $business_id = auth()->user()->business_id;

        $setting = Option::where('key', 'reservation-setting')
                                    ->whereJsonContains('value->business_id', $business_id)
                                    ->first();
        $minSize = $setting?->value['minimum_party_size'] ?? 0;

        if($request->guest < $minSize) {
            return response()->json(['message' => 'Minimum guest size not met'], 422);
        }

        Reservation::create($request->all() + [
            'business_id' => $business_id
        ]);

        return response()->json([
            'message' => 'Reservation added successfully',
            'redirect' => route('business.reservations.index')
        ]);
    }

   public function getTables(Request $request)
    {
        $reservation = Reservation::findOrFail($request->reservation_id);
        $assignedTableIds = $reservation->tables()->pluck('tables.id')->toArray();
        $conflictingReservationIds = Reservation::where('date', $reservation->date)
                        ->where('time', $reservation->time)
                        ->where('id', '!=', $reservation->id)
                        ->pluck('id')
                        ->toArray();

        $busyTableIds = DB::table('reservation_tables')
                        ->whereIn('reservation_id', $conflictingReservationIds)
                        ->pluck('table_id')
                        ->toArray();

        $tables = Table::where('business_id', auth()->user()->business_id)->get();

        return view('restaurantwebaddon::reservations.table-list',
        compact('tables', 'reservation', 'busyTableIds', 'assignedTableIds'))->render();
    }


    public function assignTable(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|integer',
            'table_id' => 'required|integer',
        ]);

        $reservation = Reservation::findOrFail($request->reservation_id);
        $conflict = Reservation::where('date', $reservation->date)
                    ->where('time', $reservation->time)
                    ->where('id', '!=', $reservation->id)
                    ->whereHas('tables', function ($q) use ($request) {
                        $q->where('table_id', $request->table_id);
                    })
                    ->exists();

        if ($conflict) {
            return response()->json([
                'success' => false,
                'message' => 'This table is already booked for this time.'
            ], 422);
        }
        $table = Table::findOrFail($request->table_id);
        $reservation->tables()->sync([$table->id]);
        return response()->json([
            'success' => true,
            'table_name' => $table->name,
        ]);
    }


    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation_setting = Option::where('key', 'reservation-setting')
                                ->whereJsonContains('value->business_id', auth()->user()->business_id)
                                ->first();
        return view('restaurantwebaddon::reservations.edit', compact('reservation', 'reservation_setting'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'guest' => 'required|integer',
            'type' => 'required|string',
            'time' => 'required|string',
        ]);

        $business_id = auth()->user()->business_id;
        $reservation = Reservation::findOrFail($id);

        $setting = Option::where('key', 'reservation-setting')
                                    ->whereJsonContains('value->business_id', $business_id)
                                    ->first();
        $minSize = $setting?->value['minimum_party_size'] ?? 0;

        if($request->guest < $minSize) {
            return response()->json(['message' => 'Minimum guest size not met'], 422);
        }

        $reservation->update($request->all() + [
            'business_id' => $business_id
        ]);

        return response()->json([
            'message' => 'Reservation updated successfully',
            'redirect' => route('business.reservations.index')
        ]);
    }

    public function updateStatus(Request $request, $id) {
        $reservation = Reservation::findOrFail($id);

        $reservation->update([
            'status'=> $request->status
        ]);

        return response()->json([
            'message' => 'Status updated sucessfully'
        ]);
    }

    public function destroy($id)
    {
        Reservation::where('id', $id)->delete();
        return response()->json([
            'message' => 'Reservation deleted successfully',
            'redirect'=> route('business.reservations.index')
         ]);
    }

    public function deleteAll(Request $request) {

        Reservation::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => __('Selected reservations deleted successfully'),
            'redirect' => route('business.reservations.index')
        ]);
    }
}
