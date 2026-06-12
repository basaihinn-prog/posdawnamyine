<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\NewsLetter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AcnooNewsLetterController extends Controller
{

    public function index()
    {
        $newsletters = NewsLetter::with('user:id,name')->latest()->paginate(20);
        return view('restaurantwebaddon::newsletters.index', compact('newsletters'));
    }

    public function acnooFilter(Request $request)
    {
        $newsletters = NewsLetter::with('user:id,name')
            ->when(request('search'), function ($q) {
                $q->where(function ($q) {
                    $q->where('email', 'like', '%' . request('search') . '%')
                        ->orWhereHas('user', function($q)  {
                            $q->where('name', 'like', '%' .request('search') . '%' );
                        });
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::newsletters.datas', compact('newsletters'))->render()
            ]);
        }

        return redirect(url()->previous());
    }
}
