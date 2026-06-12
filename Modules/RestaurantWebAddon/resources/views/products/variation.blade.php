<div class="modal fade" id="itemVariationsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold">{{__('Item Variations')}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Item Details -->
        <div class="d-flex align-items-start gap-3 mb-3">
          <img class="modal-img" id="image" src="" alt="Burger" class="me-3 rounded">
          <div>
            <h6 class="fw-bold mb-0" id="name"></h6>
            <small class="text-muted" id="category-food"></small>
            <p class="mt-2 mb-0 text-muted" id="description">
              <a href="#" class="text-warning text-decoration-none">{{__('Read More...')}}</a>
            </p>
          </div>
        </div>

        <!-- Variations -->
        <h6 class="fw-bold mb-1">{{__('Variations')}}</h6>
        <div id="variation-list">

       </div>
      </div>
    </div>
  </div>
</div>
