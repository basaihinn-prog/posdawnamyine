@foreach ($active_stores as $store)
<tr class="table-content">
    <td class="table-single-content text-start">{{ $active_stores->perPage() * ($active_stores->currentPage() - 1) + $loop->iteration }} <i class="{{ request('id') == $store->id ? 'fas fa-bell text-red' : '' }}"></i></td>
    <td class="table-single-content">{{ $store->companyName }}</td>
    <td class="table-single-content">{{ $store->category->name ?? '' }}</td>
    <td class="table-single-content">{{ $store->phoneNumber }}</td>
    <td class="table-single-content">{{ $store->enrolled_plan?->plan?->subscriptionName }}</td>
    <td class="table-single-content">{{ formatted_date($store->subscriptionDate) }}</td>
    <td class="table-single-content">{{ formatted_date($store->will_expire) }}</td>
</tr>
@endforeach


