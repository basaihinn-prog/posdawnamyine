@foreach($booked_tables as $table)
    <tr>
        <td>{{ $booked_tables->firstItem() + $loop->index }}</td>
        <td class="text-center">{{ $table->name }}</td>
        <td class="text-center">{{ $table->capacity }}</td>
        <td>
            @if ($table->is_booked == '1')
                <div class="expired-status">
                    {{ __('Booked') }}
                </div>
            @else
                <div class="available-status">
                    {{ __('Available') }}
                </div>
            @endif
        </td>
    </tr>
@endforeach
