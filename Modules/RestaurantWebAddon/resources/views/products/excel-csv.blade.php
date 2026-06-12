<table>
    <thead>
        <tr>
            <th>{{ __('SL') }}.</th>
            <th> {{ __('Item Name') }} </th>
            <th> {{ __('Menu') }} </th>
            <th> {{ __('Category') }} </th>
            <th> {{ __('Variation') }} </th>
            <th> {{ __('Sale price') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $product->productName }}</td>
                <td>{{ $product->menu->name ?? '' }}</td>
                <td>{{ $product->category->categoryName ?? '' }}</td>
                <td>{{ $product->variations_count ?? '' }}</td>
                <td>{{ currency_format($product->sales_price, currency: business_currency()) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
