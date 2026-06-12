<table>
    <thead>
        <tr>
            <th>{{ __('SL') }}.</th>
            <th>{{ __('Name') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($expense_categories as $category)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $category->categoryName }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
