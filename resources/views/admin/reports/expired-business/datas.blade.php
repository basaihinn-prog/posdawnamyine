@foreach ($expired_businesses as $business)
    <tr class="table-content">
        <td class="table-single-content text-start">{{ $expired_businesses->perPage() * ($expired_businesses->currentPage() - 1) + $loop->iteration }} <i class="{{ request('id') == $business->id ? 'fas fa-bell text-red' : '' }}"></i></td>
        <td class="table-single-content">{{ $business->companyName }}</td>
        <td class="table-single-content">{{ $business->category->name ?? '' }}</td>
        <td class="table-single-content">{{ $business->phoneNumber }}</td>
        <td class="table-single-content">{{ $business->enrolled_plan?->plan?->subscriptionName }}</td>
        <td class="table-single-content">{{ formatted_date($business->subscriptionDate) }}</td>
        <td class="table-single-content">{{ formatted_date($business->will_expire) }}</td>
    </tr>
@endforeach
