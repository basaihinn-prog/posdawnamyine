<div class="kot-card-container">
    @foreach ($kots as $kot)
        <div>
            <div class="kot-card">
                <div class="kot-header">
                    <div class="kot-left">
                        <p class="kitchen-kot"><span>{{__('KOT')}}:</span> {{ $kot->kot_ticket?->kot_number }}</p>
                        <p>{{__('Items')}}: {{ $kot->total_item }}</p>
                        <p>{{__('Table')}}: {{ $kot->table?->name }}</p>
                        @if ($kot->business_gateway_id)
                            <div class="dine-in">
                                <span class="dine-bullet"></span>
                                <span class="dine-text">{{__('Online')}}</span>
                            </div>
                        @endif

                    </div>
                    <div class="kot-right">
                        <p><span>{{__('Order')}}:</span> {{ $kot->invoiceNumber }}</p>
                        <p>{{ formatted_date($kot->saleDate, 'd/m/Y') }} {{ formatted_time($kot->saleDate) }}</p>
                        <p class="kot-user">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.6654 4.66667C10.6654 6.13943 9.47143 7.33333 7.9987 7.33333C6.52594 7.33333 5.33203 6.13943 5.33203 4.66667C5.33203 3.19391 6.52594 2 7.9987 2C9.47143 2 10.6654 3.19391 10.6654 4.66667Z"
                                    stroke="#1C1C1C" stroke-width="1.25" />
                                <path
                                    d="M9.33203 9.33398H6.66536C4.82442 9.33398 3.33203 10.8264 3.33203 12.6673C3.33203 13.4037 3.92898 14.0007 4.66536 14.0007H11.332C12.0684 14.0007 12.6654 13.4037 12.6654 12.6673C12.6654 10.8264 11.173 9.33398 9.33203 9.33398Z"
                                    stroke="#1C1C1C" stroke-width="1.25" stroke-linejoin="round" />
                            </svg>
                            {{ $kot->party?->name }}
                        </p>
                    </div>
                </div>
                <div class="kot-item-box">
                    <button class="kot-dropdown-btn" data-kot-toggle>
                        {{__('Item Name')}}
                        <span class="kot-arrow">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 7.5L10 12.5L15 7.5" stroke="#5B5B5B" stroke-width="1.25"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </button>

                    <div class="kot-dropdown-content">
                        @foreach ($kot->details as $item)
                            <div class="kot-item">
                                <div>
                                    <p class="kot-item-title">{{ $item->quantities }}x
                                        {{ $item->product?->productName }}</p>
                                    @if ($item->product?->food_type || $item->variation)
                                        <p class="kot-item-size">
                                            @if ($item->product?->food_type)
                                                {{ ucwords(str_replace('_', ' ', $item->product?->food_type)) }}
                                            @endif
                                            @if ($item->product?->food_type && $item->variation)
                                                 -
                                            @endif
                                            @if ($item->variation)
                                                {{ $item->variation?->name }}
                                            @endif
                                        </p>
                                    @endif

                                    @if ($item->detail_options && $item->detail_options->count() > 0)
                                        @php
                                            $grouped = [];
                                            foreach ($item->detail_options as $opt) {
                                                $groupName = $opt->modifier_group_option?->modifier_group?->name;
                                                $optionName = $opt->modifier_group_option?->name;

                                                if (!$groupName || !$optionName) {
                                                    continue;
                                                }

                                                if (!isset($grouped[$groupName])) {
                                                    $grouped[$groupName] = [];
                                                }
                                                $grouped[$groupName][] = $optionName;
                                            }
                                        @endphp

                                        @foreach ($grouped as $groupName => $options)
                                            <p class="kot-item-size">{{ $groupName }}:
                                                {{ implode(', ', $options) }}</p>
                                        @endforeach
                                    @endif
                                </div>

                                @usercan('kot.update')
                                    @if ($item->cooking_status === 'pending')
                                        <button class="item-action-btn kot-start-btn"
                                            data-url="{{ route('business.kots-cooking-status', $item->id) }}"
                                            data-id="{{ $item->id }}" data-status="start">
                                            {{__('Start Cooking')}}
                                        </button>
                                    @elseif ($item->cooking_status === 'start')
                                        <button class="item-action-btn kot-ready-btn"
                                            data-url="{{ route('business.kots-cooking-status', $item->id) }}"
                                            data-id="{{ $item->id }}" data-status="ready">
                                            ✔ {{__('Mark Ready')}}
                                        </button>
                                    @elseif ($item->cooking_status === 'ready')
                                        <button class="kot-ready-btn disabled" disabled>
                                            {{__('Ready')}}
                                        </button>
                                    @endif
                                @endusercan
                            </div>
                        @endforeach
                        @if ($kot->kot_ticket?->status == 'cancelled')
                            <div>
                                <div class="fw-bold">{{__('Cancelled Reason')}}</div>
                                <div>{{ $kot->kot_ticket?->cancel_reason?->reason }}</div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="kot-footer-btns">
                    @usercan('kot.update')
                        @if ($kot->kot_ticket?->status != 'cancelled')
                            <button class="kot-cancel-btn"
                                data-url="{{ route('business.kots-kot-status', $kot->kot_ticket?->id) }}">
                                {{__('Cancel')}}
                            </button>
                            @if ($kot->kot_ticket?->status === 'pending')
                                <button class="kot-food-ready-btn kot-action-btn"
                                    data-url="{{ route('business.kots-kot-status', $kot->kot_ticket?->id) }}"
                                    data-status="preparing">
                                    {{__('Food Is Preparing')}}
                                </button>
                            @elseif ($kot->kot_ticket?->status === 'preparing')
                                <button class="kot-food-ready-btn kot-action-btn"
                                    data-url="{{ route('business.kots-kot-status', $kot->kot_ticket?->id) }}"
                                    data-status="ready">
                                    {{__('Food Is Ready')}}
                                </button>
                            @elseif ($kot->kot_ticket?->status === 'ready')
                                <button class="kot-food-ready-btn kot-action-btn"
                                    data-url="{{ route('business.kots-kot-status', $kot->kot_ticket?->id) }}"
                                    data-status="served">
                                    {{__('Food Is Served')}}
                                </button>
                            @endif
                        @endif
                    @endusercan
                    <a href="{{ route('business.kots.get-ticket', $kot->id) }}" target="blank" class="kot-print-btn">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M6.1296 14.9993C4.36034 14.9993 3.4757 14.9993 2.84588 14.6217C2.43425 14.3748 2.10262 14.0219 1.8886 13.6028C1.56113 12.9616 1.64915 12.1138 1.8252 10.4181C1.97216 9.0026 2.04565 8.29481 2.407 7.77502C2.64425 7.43376 2.96513 7.15405 3.34128 6.96062C3.9142 6.66602 4.65267 6.66602 6.1296 6.66602H13.873C15.35 6.66602 16.0884 6.66602 16.6613 6.96062C17.0375 7.15405 17.3584 7.43376 17.5956 7.77502C17.957 8.29481 18.0305 9.0026 18.1774 10.4181C18.3535 12.1138 18.4415 12.9616 18.114 13.6028C17.9 14.0219 17.5684 14.3748 17.1567 14.6217C16.5269 14.9993 15.6423 14.9993 13.873 14.9993"
                                stroke="#6155F5" stroke-width="1.25" />
                            <path
                                d="M14.1654 6.66602V4.99935C14.1654 3.428 14.1654 2.64232 13.6772 2.15417C13.189 1.66602 12.4034 1.66602 10.832 1.66602H9.16536C7.59401 1.66602 6.80834 1.66602 6.32019 2.15417C5.83203 2.64232 5.83203 3.428 5.83203 4.99935V6.66602"
                                stroke="#6155F5" stroke-width="1.25" stroke-linejoin="round" />
                            <path
                                d="M11.6559 13.334H8.34145C7.77038 13.334 7.48484 13.334 7.24189 13.4247C6.91796 13.5457 6.64058 13.7807 6.45387 14.0922C6.31382 14.326 6.24457 14.6266 6.10607 15.2277C5.88964 16.1669 5.78143 16.6365 5.85502 17.0131C5.95316 17.5152 6.25901 17.9402 6.68413 18.1652C7.00297 18.334 7.44913 18.334 8.34145 18.334H11.6559C12.5483 18.334 12.9944 18.334 13.3133 18.1652C13.7384 17.9402 14.0443 17.5152 14.1424 17.0131C14.2159 16.6365 14.1078 16.1669 13.8914 15.2277C13.7529 14.6266 13.6835 14.326 13.5435 14.0922C13.3568 13.7807 13.0794 13.5457 12.7555 13.4247C12.5125 13.334 12.227 13.334 11.6559 13.334Z"
                                stroke="#6155F5" stroke-width="1.25" stroke-linejoin="round" />
                            <path d="M15 10H15.0075" stroke="#6155F5" stroke-width="1.25" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="mt-3">
    {{ $kots->links('vendor.pagination.bootstrap-5') }}
</div>
