@foreach ($currencies as $currency)
    <tr>
        <td>{{ ($currencies->currentPage() - 1) * $currencies->perPage() + $loop->iteration }}</td>
        <td>{{ $currency->name }}</td>
        <td>{{ $currency->country_name }}</td>
        <td>{{ $currency->code }}</td>
        <td>{{ $currency->symbol }}</td>
        <td>
            <div class="d-flex align-items-center justify-content-center">
                <div class="{{ ($user_currency && $currency->name == $user_currency->name) || (!$user_currency && $currency->is_default == 1) ? 'yes-badge' : 'no-badge'  }}">
                    {{ ($user_currency && $currency->name == $user_currency->name) || (!$user_currency && $currency->is_default == 1) ? 'Yes' : 'No' }}
                </div>
            </div>
        </td>
        <td class="d-print-none">
            <div class="d-flex align-items-center justify-content-end">
                <div class="icon-buttons">
                    @usercan('currency.update')
                    @if(!$user_currency || $user_currency->name != $currency->name)
                        <a data-bs-placement="top" title="{{__('Make Default')}}" href="{{ route('business.currencies.default', ['id' => $currency->id]) }}" class="action-btn default">
                            <i class="fas fa-adjust fs-6"></i>
                        </a>
                    @else
                        <span data-bs-placement="top" title="{{__('Default Currency')}}" class="action-btn default">
                            <i class="fas fa-check fs-6"></i>
                        </span>
                    @endif
                    @endusercan
                </div>
            </div>
        </td>
    </tr>
@endforeach
