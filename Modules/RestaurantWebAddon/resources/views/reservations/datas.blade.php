@foreach ($reservations as $reservation)
    <tr>
        @usercan('reservations.delete')
        <td class="w-60 checkbox">
            <input type="checkbox" name="ids[]" class="delete-checkbox-item multi-delete" value="{{ $reservation->id }}">
        </td>
        @endusercan
        <td class="text-start">{{ ($reservations->currentPage() - 1) * $reservations->perPage() + $loop->iteration }}</td>
        <td class="text-start">{{format_pretty_date($reservation->date)}}, {{ formatted_time($reservation->time) }}</td>
        <td class="text-center">{{ $reservation->name }}</td>
        <td class="text-center">{{ $reservation->phone }}</td>
        <td class="text-center">{{ $reservation->email }}</td>
        <td class="text-center">{{ $reservation->guest }}</td>
        @usercan('reservations.update')
        <td class="text-center">
            <div class="custom-select-wrapper-status border-0">
                <select class="custom-select-status border-0 status-change" data-id="{{ $reservation->id }}" data-url="{{ route('business.reservations.status', $reservation->id) }}">
                    <option value="pending" {{ $reservation->status === 'pending' ? 'selected' : '' }}>{{__('Pending')}}</option>
                    <option value="confirmed" {{ $reservation->status === 'confirmed' ? 'selected' : '' }}>{{__('Confirmed')}}</option>
                    <option value="cancelled" {{ $reservation->status === 'cancelled' ? 'selected' : '' }}>{{__('Cancelled')}}</option>
                    <option value="checked_in" {{ $reservation->status === 'checked_in' ? 'selected' : '' }}>{{__('Checked In')}}</option>
                </select>
            </div>
        </td>
        @endusercan
        <td class="text-center assigned-table-name-{{ $reservation->id }}">{{ $reservation->tables?->first()->name ?? '' }}</td>
        <td class="d-print-none">
            <div class="d-flex align-items-center justify-content-end">
                <div class="icon-buttons">
                    <a title="{{__('Table')}}" data-bs-placement="top" href="#reservation-table-modal" class="reservations-btn action-btn view" data-bs-toggle="modal"
                        data-reservation-id="{{ $reservation->id }}"
                        data-url="{{ route('business.reservations.getTables') }}">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_8361_76804)">
                            <path d="M2.43483 12.4813C2.0213 8.15881 1.39467 4.60222 1.38567 4.54883L0 4.78916C0.0137344 4.8695 1.40625 12.8916 1.40625 19.4346H2.8125C2.8125 18.0628 2.75156 16.6298 2.65777 15.2159H7.07812V19.4346H8.48438V13.8096C8.48438 12.5234 7.63584 12.4034 6.36403 12.4034L3.62002 12.4041C3.16828 12.4041 2.76956 12.4142 2.43483 12.4813Z" fill="#A7A7A7"/>
                            <path d="M11.2969 18.0283H9.89062V19.4346H14.1094V18.0283H12.7031V9.59082H18.3281V6.77832H5.67188V9.59082H11.2969V18.0283Z" fill="#A7A7A7"/>
                            <path d="M24 4.78916L22.6143 4.54883C22.6053 4.60222 21.9786 8.15877 21.565 12.4812C21.2303 12.4142 20.8317 12.404 20.38 12.404L17.636 12.4033C16.3641 12.4033 15.5156 12.5233 15.5156 13.8096V19.4346H16.9219V15.2154H21.3422C21.2484 16.6293 21.1875 18.0628 21.1875 19.4346H22.5938C22.5938 12.8916 23.9863 4.8695 24 4.78916Z" fill="#A7A7A7"/>
                            </g>
                            <defs>
                            <clipPath id="clip0_8361_76804">
                            <rect width="24" height="24" fill="white"/>
                            </clipPath>
                            </defs>
                        </svg>
                    </a>
                    <a title="{{__('Edit')}}" data-bs-placement="top" href="{{ route('business.reservations.edit', $reservation->id) }}" class="action-btn edit">
                        <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M11.6479 19.4575C11.6479 18.9869 12.0294 18.6055 12.5 18.6055H17.612C18.0825 18.6055 18.4641 18.9869 18.4641 19.4575C18.4641 19.9281 18.0825 20.3095 17.612 20.3095H12.5C12.0294 20.3095 11.6479 19.9281 11.6479 19.4575Z"
                                fill="#979797" />
                            <path
                                d="M18.5061 3.93895C17.8507 3.6141 17.081 3.6141 16.4255 3.93895C16.0785 4.11088 15.7751 4.41482 15.3831 4.80746L14.6299 5.5606L18.9418 9.87244L19.6949 9.11929C20.0875 8.7273 20.3915 8.42385 20.5634 8.0769C20.8883 7.42137 20.8883 6.65174 20.5634 5.99621C20.3915 5.64926 20.0875 5.3458 19.6949 4.95382L19.5486 4.80746C19.1566 4.41482 18.8531 4.11088 18.5061 3.93895Z"
                                fill="#979797" />
                            <path
                                d="M18.0381 10.7767L13.7264 6.46484L5.30538 14.8857C4.88409 15.3063 4.55068 15.639 4.37123 16.0722C4.19179 16.5054 4.19224 16.9765 4.19281 17.5717L4.19288 19.671C4.19288 20.024 4.47897 20.3101 4.83189 20.3101L6.93125 20.3101C7.52647 20.3107 7.99755 20.3112 8.43076 20.1317C8.86396 19.9523 9.19674 19.6189 9.61723 19.1976L18.0381 10.7767Z"
                                fill="#979797" />
                        </svg>
                    </a>
                    <a title="{{__('Delete')}}" data-bs-placement="top" href="{{ route('business.reservations.destroy', $reservation->id) }}"
                        class="confirm-action action-btn delete" data-method="DELETE">
                        <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M18.6273 15.5714C18.5653 16.5711 18.5161 17.3644 18.4154 17.998C18.3119 18.6479 18.1464 19.1891 17.8154 19.6625C17.5125 20.0955 17.1226 20.4611 16.6704 20.7357C16.1761 21.036 15.6242 21.1673 14.9672 21.2301L10.0171 21.23C9.35943 21.167 8.80689 21.0355 8.31224 20.7347C7.85974 20.4595 7.46969 20.0934 7.16693 19.6596C6.83598 19.1854 6.671 18.6435 6.56838 17.9927C6.46832 17.3581 6.42023 16.5636 6.35961 15.5625L5.83337 6.87109H19.1667L18.6273 15.5714ZM10.4798 17.4097C10.1451 17.4097 9.87378 17.1419 9.87378 16.8114V12.0251C9.87378 11.6947 10.1451 11.4268 10.4798 11.4268C10.8146 11.4268 11.0859 11.6947 11.0859 12.0251V16.8114C11.0859 17.1419 10.8146 17.4097 10.4798 17.4097ZM15.1263 12.0251C15.1263 11.6947 14.8549 11.4268 14.5202 11.4268C14.1855 11.4268 13.9142 11.6947 13.9142 12.0251V16.8114C13.9142 17.1419 14.1855 17.4097 14.5202 17.4097C14.8549 17.4097 15.1263 17.1419 15.1263 16.8114V12.0251Z"
                                fill="#979797" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M13.6746 2.79397C14.1673 2.83166 14.6303 2.96028 15.0279 3.21397C15.322 3.4016 15.5262 3.63127 15.7008 3.88001C15.8625 4.11051 16.0253 4.39789 16.21 4.72389L16.5821 5.38044H20.3462C20.8277 5.38044 21.218 5.71436 21.218 6.12627C21.218 6.53818 20.8277 6.8721 20.3462 6.8721C15.1153 6.8721 9.88483 6.8721 4.6539 6.8721C4.17242 6.8721 3.7821 6.53818 3.7821 6.12627C3.7821 5.71436 4.17242 5.38044 4.6539 5.38044H8.49831L8.80849 4.7983C8.98857 4.46029 9.14716 4.1626 9.30646 3.92373C9.4783 3.66606 9.68172 3.42762 9.97935 3.23228C10.3819 2.96811 10.8542 2.83419 11.3579 2.79495C11.7371 2.76542 12.1194 2.76948 12.5001 2.77001C12.9454 2.77062 13.3457 2.7688 13.6746 2.79397ZM10.4145 5.38044H14.6444C14.4468 5.03185 14.3204 4.81091 14.2058 4.64767C14.0382 4.40876 13.8373 4.30403 13.5193 4.27969C13.2932 4.2624 12.9986 4.2617 12.5301 4.2617C12.0499 4.2617 11.7477 4.26242 11.516 4.28046C11.1898 4.30586 10.9865 4.41458 10.8209 4.66293C10.7124 4.82568 10.5942 5.04353 10.4145 5.38044Z"
                                fill="#979797" />
                        </svg>
                    </a>
                </div>
            </div>
        </td>
    </tr>
@endforeach
