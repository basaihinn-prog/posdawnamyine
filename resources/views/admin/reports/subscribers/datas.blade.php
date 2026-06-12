@foreach ($subscribers as $subscriber)
    <tr class="table-content">
        <td class="table-single-content text-start">{{ ($subscribers->perPage() * ($subscribers->currentPage() - 1)) + $loop->iteration }} <i class="{{ request('id') == $subscriber->id ? 'fas fa-bell text-red' : '' }}"> </td>
        <td class="table-single-content">{{ formatted_date($subscriber->created_at) }}</td>
        <td class="table-single-content">{{ $subscriber->business->companyName ?? 'N/A' }}</td>
        <td class="table-single-content">{{ optional($subscriber->business->category)->name ?? 'N/A' }}</td>
        <td class="table-single-content">{{ formatted_date($subscriber->created_at) }}</td>
        <td class="table-single-content">{{ $subscriber->created_at ? formatted_date($subscriber->created_at->addDays($subscriber->duration)) : '' }}</td>
        <td class="table-single-content">{{ $subscriber->plan->subscriptionName }}</td>
        <td class="table-single-content d-print-none">{{ $subscriber->gateway->name ?? 'N/A' }}</td>

        <td class="table-single-content d-print-none">
            <div class="text-{{
                $subscriber->payment_status == 'paid' ? 'success' :
                ($subscriber->payment_status == 'unpaid' ? 'warning' : 'danger')
            }}">
                {{ ucfirst($subscriber->payment_status) }}
            </div>
        </td>
    </tr>
@endforeach


