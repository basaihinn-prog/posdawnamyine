<table>
    <thead>
        <tr>
            <th>{{ __('SL') }}.</th>
            <th>{{ __('Group Name') }}</th>
            <th>{{ __('Description') }}</th>
            <th>{{ __('Name') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($modifier_groups as $modifier_group)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $modifier_group->name }}</td>
                <td>{{ Str::words($modifier_group->description, 3, '...' ?? '') }}</td>
                <td>
                    @foreach ($modifier_group->modifier_group_option as $option)
                        <span>
                            {{ $option->name }}: {{ currency_format($option->price) }}
                        </span>
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
