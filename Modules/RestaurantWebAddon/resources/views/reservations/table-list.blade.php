<div class="table-grid">
@foreach($tables as $table)
        @php
            $isAssigned = in_array($table->id, $assignedTableIds);
            $isBusy = in_array($table->id, $busyTableIds);
        @endphp
    <div class="table-card table-select-btn  {{ $isAssigned ? 'active' : '' }}"  @if($isBusy) disabled style="background-color:#ccc; cursor:not-allowed;" @endif
        data-reservation-id="{{ $reservation->id }}"
        data-table-id="{{ $table->id }}">
        <div class="top-row">
            <h3>{{ $table->name }}</h3>
        </div>
        <p class="capacity">Capacity: {{ $table->capacity }}</p>
    </div>
@endforeach
</div>
