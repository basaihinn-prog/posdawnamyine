<table>
    <thead>
        <tr>
            <th>{{ __('SL') }}.</th>
            <th>{{ __('Subscribe Date') }}</th>
            <th>{{ __('Package') }}</th>
            <th>{{ __('Started') }}</th>
            <th>{{ __('Ended') }}</th>
            <th>{{ __('Payment Method') }}</th>
            <th>{{ __('Status') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($subscribers as $subscriber)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ formatted_date($subscriber->created_at) }}</td>
                <td>{{ $subscriber->plan->subscriptionName ?? 'N/A' }}</td>
                <td>{{ formatted_date($subscriber->created_at) }}</td>
                <td>{{ $subscriber->created_at ? formatted_date($subscriber->created_at->addDays($subscriber->duration)) : '' }}</td>
                <td>{{ $subscriber->gateway->name ?? 'N/A' }}</td>
                <td>
                    {{ ucfirst($subscriber->payment_status) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
