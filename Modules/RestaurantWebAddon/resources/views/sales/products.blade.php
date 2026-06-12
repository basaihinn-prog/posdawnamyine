<div id="products-list">
 @if ($products->count() > 0)
    <div class="items-card-container custom-padding">
        @foreach ($products as $product)
            @php
                $modifierGroups = $product->modifiers->map(function($modifier) {
                    return [
                        "modifier_id" => $modifier->id,
                        "id" => $modifier->modifier_group_id,
                        "is_required" => $modifier->is_required,
                        "is_multiple" => $modifier->is_multiple,
                        "name" => $modifier->modifier_group->name,
                        "options" => $modifier->modifier_group->modifier_group_option->map(function($option) {
                            return [
                                "id" => $option->id,
                                "is_available" => $option->is_available,
                                "name" => $option->name,
                                "price" => $option->price,
                            ];
                        }),
                    ];
                })->unique("name");

                $hasVariations = $product->variations && $product->variations->count() > 0;
                $hasModifiers = $modifierGroups->count() > 0;
            @endphp

            <div class="items-card-content {{ $hasVariations || $hasModifiers ? 'variation-product' : 'single-product' }}"
                data-product-id="{{ $product->id }}"
                data-product-name="{{ $product->productName }}"
                data-sales-price="{{ $product->sales_price }}"
                data-category="{{ $product->category->categoryName ?? '' }}"
                data-food-type="{{ $product->food_type }}"
                data-preparation-time="{{ $product->preparation_time }}"
                data-image="{{ asset($product->images[0] ?? 'assets/img/icon/no-image.svg') }}"
                data-description="{{ $product->description }}"
                data-variations='@json($product->variations)'
                data-modifier-groups-option='@json($modifierGroups)'
                data-route="{{ route('business.sale-carts.store') }}">

                <img src="{{ asset($product->images[0] ?? 'assets/img/icon/box.svg') }}" alt="" srcset="">
                <div class="p-2">
                    <p class="items">{{ $product->productName }}</p>
                    @if ($product->variations && $product->variations->count() > 0)
                        <p class="price">{{ currency_format($product->variations->min('price'), currency: business_currency()) }}</p>
                    @else
                        <p class="price">{{ currency_format($product->sales_price, currency: business_currency()) }}</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="no-products">
        <img src="{{ asset('assets/images/icons/no-found.svg') }}" alt="" srcset="">
        <p>{{__('Item Not Found')}}</p>
    </div>
@endif
</div>
