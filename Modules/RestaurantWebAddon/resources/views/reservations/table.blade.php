<div class="modal fade common-validation-modal" id="reservation-table-modal">
    <div class="modal-dialog modal-dialog-centered modal-xl tables-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ __('Table ') }}</h1>
                <button type="button" class="btn-close modal-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="personal-info" id="tableListAdd">
                    {{-- dynamic table list come from ajax call --}}
                </div>
                 <form id="tableAssignForm" action="{{ route('business.reservations.assignTable') }}" method="POST">
                    @csrf
                    <input type="hidden" name="table_id" id="hiddenTableId">
                    <input type="hidden" name="reservation_id" id="hiddenReservationId">
                </form>
            </div>
        </div>
    </div>
</div>
