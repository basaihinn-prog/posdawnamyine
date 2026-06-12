<div class="modal fade" id="product-view" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content item-modal">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold">{{__('Item Details')}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="d-flex align-items-start gap-3">
          <img id="view_image" src=""
               alt="Chicken Burger" class="item-img" />
          <div>
            <h6 class="fw-bold mb-1" id="view_name"></h6>
            <p class="text-muted small mb-1" id="view_category_food"></p>
            <p class="small mb-1" id="view_menu"></p>
            <p class="small mb-1">
              {{__('Preparation Time')}}: <span class="fw-bold" id="view_preparation_time"></span>
            </p>
            <p class="small mb-0 text-muted" id="view_description">
              <a href="#" class="read-more">{{__('Read More')}}</a>
            </p>
          </div>
        </div>

        <div class="section mt-3">
          <h6 class="fw-bold">{{__('Variations')}}</h6>
          <div id="view-variation">
            {{-- dynamic variation come here --}}
          </div>
        </div>

        <div class="section mt-3">
            <div id="view-modifier-group">
                {{-- dynamic group and option come here --}}
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
