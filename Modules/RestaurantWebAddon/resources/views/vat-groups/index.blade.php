<div class="container-fluid">
    <div class="section-title mb-3 d-flex align-items-center justify-content-between flex-wrap">
        <h2> <a> {{__('Vat Groups')}} </a>
            <span>({{__('Combination Of Multiple Taxs')}})</span>
        </h2>

        <form action="{{ route('business.vat-groups.filter') }}" method="post" class="filter-form d-flex align-items-center gap-2" table="#vat-group-data">
            <div class="search-wrapper">
                <div class="custom-search-btn" id="searchBtn-2">
                    <div class="initial-search-icon">
                        <!-- SVG ICON -->
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_4034_56140)">
                                <path d="M11.6667 11.6641L14.6667 14.6641" stroke="#2F5A76" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                <path
                                    d="M13.3333 7.33594C13.3333 4.02223 10.647 1.33594 7.33325 1.33594C4.01955 1.33594 1.33325 4.02223 1.33325 7.33594C1.33325 10.6497 4.01955 13.3359 7.33325 13.3359C10.647 13.3359 13.3333 10.6497 13.3333 7.33594Z"
                                    stroke="#2F5A76" stroke-width="1.5" stroke-linejoin="round"></path>
                            </g>
                            <defs>
                                <clipPath id="clip0_4034_56140">
                                    <rect width="16" height="16" fill="white"></rect>
                                </clipPath>
                            </defs>
                        </svg>
                    </div>
                    <input type="text" name="search" placeholder="{{__('Search...')}}">
                </div>
            </div>
            @usercan('vat.create')
            <a href="{{ route('business.vats.create') }}" class="theme-btn print-btn text-light active">
                <i class="fas fa-plus-circle me-1"></i>{{ __('Add Vat Group') }}
            </a>
            @endusercan
        </form>
    </div>
    <div class="card shadow-sm">
        <div class="responsive-table">
            <table class="table" id="datatable">
                <thead>
                    <tr>
                        <th class="w-60">{{ __('SL') }}.</th>
                        <th class="text-center">{{ __('Name') }}</th>
                        <th class="text-center">{{ __('Rate') }}</th>
                        <th class="text-center">{{ __('Sub Taxs') }}</th>
                        <th class="text-center">{{ __('Status') }}</th>
                        <th class="text-center">{{ __('Vat On Sale') }}</th>
                        <th class="text-end">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody id="vat-group-data">
                    @include('restaurantwebaddon::vat-groups.datas')
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $vat_groups->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>
