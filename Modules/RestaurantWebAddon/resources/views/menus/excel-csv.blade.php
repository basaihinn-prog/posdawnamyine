<table>
    <thead>
        <tr>
            <th>{{ __('SL') }}.</th>
            <th>{{ __('Menu Name') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($menus as $menu)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $menu->name }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
