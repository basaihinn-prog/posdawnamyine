@foreach ($messages as $message)
    <tr class="table-content">
        <td class="w-60 checkbox table-single-content d-print-none">
            <label class="table-custom-checkbox">
                <input type="checkbox" name="ids[]" class="table-hidden-checkbox checkbox-item delete-checkbox-item multi-delete"
                    value="{{ $message->id }}">
                <span class="table-custom-checkmark custom-checkmark"></span>
            </label>
        </td>
        <td class="table-single-content text-start">{{ $messages->perPage() * ($messages->currentPage() - 1) + $loop->iteration }}</td>
        <td class="table-single-content">{{ $message->name ?? '' }}</td>
        <td class="table-single-content">{{ $message->phone ?? '' }}</td>
        <td class="table-single-content">{{ $message->email ?? '' }}</td>
        <td class="table-single-content">{{ $message->company_name ?? 'Unknown' }}</td>
        <td class="table-single-content">{{ Str::words($message->message ?? '', 3, '...') }}</td>
        <td class="print-d-none table-single-content d-print-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#message-view-modal" class="message-view" data-bs-toggle="modal"
                            data-name="{{ $message->name ?? '' }}"
                            data-phone="{{ $message->phone ?? '' }}"
                            data-email="{{ $message->email ?? '' }}"
                            data-company-name="{{ $message->company_name ?? 'Unknown' }}"
                            data-message="{{ $message->message ?? '' }}"
                            >
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.696 8.44836C16.6019 6.61586 14.1459 3.54175 10.0001 3.54175C5.85428 3.54175 3.39836 6.61586 2.30419 8.44836C1.73169 9.40503 1.73169 10.5943 2.30419 11.5518C3.39836 13.3843 5.85428 16.4584 10.0001 16.4584C14.1459 16.4584 16.6019 13.3843 17.696 11.5518C18.2685 10.5943 18.2685 9.40586 17.696 8.44836ZM16.6234 10.9101C15.6651 12.5151 13.5293 15.2084 10.0001 15.2084C6.47095 15.2084 4.33512 12.5159 3.37678 10.9101C3.04178 10.3484 3.04178 9.6509 3.37678 9.08923C4.33512 7.48423 6.47095 4.79093 10.0001 4.79093C13.5293 4.79093 15.6651 7.4834 16.6234 9.08923C16.9593 9.65173 16.9593 10.3484 16.6234 10.9101ZM10.0001 6.45841C8.04678 6.45841 6.45845 8.04758 6.45845 10.0001C6.45845 11.9526 8.04678 13.5417 10.0001 13.5417C11.9534 13.5417 13.5418 11.9526 13.5418 10.0001C13.5418 8.04758 11.9534 6.45841 10.0001 6.45841ZM10.0001 12.2917C8.73595 12.2917 7.70845 11.2642 7.70845 10.0001C7.70845 8.73592 8.73595 7.70842 10.0001 7.70842C11.2643 7.70842 12.2918 8.73592 12.2918 10.0001C12.2918 11.2642 11.2643 12.2917 10.0001 12.2917Z" fill="#4A4A52"/>
                            </svg>
                            {{ __('View') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.messages.destroy', $message->id) }}" class="confirm-action"
                            data-method="DELETE">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.5 4.375H14.5342C13.7833 4.375 13.7517 4.28 13.5458 3.66333L13.3775 3.1575C13.1217 2.39083 12.4075 1.875 11.5992 1.875H8.40083C7.59249 1.875 6.8775 2.39 6.6225 3.1575L6.45417 3.66333C6.24834 4.28083 6.21666 4.375 5.46583 4.375H2.5C2.155 4.375 1.875 4.655 1.875 5C1.875 5.345 2.155 5.625 2.5 5.625H3.58166L4.22084 15.2075C4.34417 17.0617 5.48084 18.125 7.33917 18.125H12.6617C14.5192 18.125 15.6558 17.0617 15.78 15.2075L16.4192 5.625H17.5C17.845 5.625 18.125 5.345 18.125 5C18.125 4.655 17.845 4.375 17.5 4.375ZM7.80833 3.5525C7.89416 3.29667 8.13166 3.125 8.40083 3.125H11.5992C11.8683 3.125 12.1067 3.29667 12.1917 3.5525L12.36 4.05833C12.3967 4.1675 12.4333 4.27333 12.4733 4.375H7.525C7.565 4.2725 7.60251 4.16666 7.63917 4.05833L7.80833 3.5525ZM14.5317 15.1242C14.4525 16.3183 13.8575 16.875 12.6608 16.875H7.33833C6.14167 16.875 5.5475 16.3192 5.4675 15.1242L4.83417 5.625H5.465C5.56917 5.625 5.65583 5.61417 5.74917 5.6075C5.7775 5.61167 5.80333 5.625 5.8325 5.625H14.1658C14.1958 5.625 14.2208 5.61167 14.2492 5.6075C14.3425 5.61417 14.4292 5.625 14.5333 5.625H15.1642L14.5317 15.1242ZM12.2917 9.16667V13.3333C12.2917 13.6783 12.0117 13.9583 11.6667 13.9583C11.3217 13.9583 11.0417 13.6783 11.0417 13.3333V9.16667C11.0417 8.82167 11.3217 8.54167 11.6667 8.54167C12.0117 8.54167 12.2917 8.82167 12.2917 9.16667ZM8.95833 9.16667V13.3333C8.95833 13.6783 8.67833 13.9583 8.33333 13.9583C7.98833 13.9583 7.70833 13.6783 7.70833 13.3333V9.16667C7.70833 8.82167 7.98833 8.54167 8.33333 8.54167C8.67833 8.54167 8.95833 8.82167 8.95833 9.16667Z" fill="#4A4A52"/>
                            </svg>
                            {{ __('Delete') }}
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
