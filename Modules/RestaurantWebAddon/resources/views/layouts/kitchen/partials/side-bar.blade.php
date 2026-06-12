<nav class="side-bar">
    <div class="side-bar-logo">
        <a href="{{ route('business.dashboard.index') }}">
            <img src="{{ asset(get_option('general')['admin_logo'] ?? 'assets/images/logo/backend_logo.png') }}" alt="Logo">
        </a>
        <button class="close-btn"><i class="fal fa-times"></i></button>
    </div>
    <div class="side-bar-manu side-bar-manu-arrow">
    <div class="d-flex align-items-center justify-content-between mb-3  gap-3 sidebar-list-container">
            @usercan('sales.view')
            <a class="bill-list-btn w-100" href="{{ route('business.sales.index') }}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.75 14C13.75 14.414 13.414 14.75 13 14.75H7C6.586 14.75 6.25 14.414 6.25 14C6.25 13.586 6.586 13.25 7 13.25H13C13.414 13.25 13.75 13.586 13.75 14ZM10 16.25H7C6.586 16.25 6.25 16.586 6.25 17C6.25 17.414 6.586 17.75 7 17.75H10C10.414 17.75 10.75 17.414 10.75 17C10.75 16.586 10.414 16.25 10 16.25ZM21.75 11.5V19C21.75 20.517 20.517 21.75 19 21.75H6C3.582 21.75 2.25 20.418 2.25 18V6C2.25 3.582 3.582 2.25 6 2.25H14C16.418 2.25 17.75 3.582 17.75 6V9.25H19.5C20.741 9.25 21.75 10.259 21.75 11.5ZM16.551 20.25C16.359 19.875 16.25 19.45 16.25 19V6C16.25 4.423 15.577 3.75 14 3.75H6C4.423 3.75 3.75 4.423 3.75 6V18C3.75 19.577 4.423 20.25 6 20.25H16.551ZM20.25 11.5C20.25 11.086 19.914 10.75 19.5 10.75H17.75V19C17.75 19.689 18.311 20.25 19 20.25C19.689 20.25 20.25 19.689 20.25 19V11.5ZM7.633 9.10596L8.56201 9.34595C8.71501 9.38595 8.82202 9.53596 8.82202 9.70996C8.82202 9.84996 8.75403 9.94106 8.71503 9.98206C8.67103 10.0281 8.59501 10.083 8.48901 10.083H8.08398C7.91798 10.083 7.77299 9.93695 7.75299 9.75195C7.70799 9.33995 7.34303 9.04304 6.92603 9.08704C6.51403 9.13104 6.21699 9.50194 6.26099 9.91394C6.34199 10.6659 6.85201 11.2661 7.52301 11.4871V11.5C7.52301 11.914 7.85901 12.25 8.27301 12.25C8.68701 12.25 9.02301 11.914 9.02301 11.5V11.493C9.31001 11.403 9.57501 11.25 9.79401 11.024C10.134 10.67 10.321 10.2041 10.321 9.71106C10.321 8.85506 9.75403 8.10902 8.93903 7.89502L8.00897 7.65503C7.91897 7.63103 7.86097 7.57398 7.82397 7.52698C7.77697 7.46498 7.74902 7.37902 7.74902 7.29102C7.74902 7.08502 7.89797 6.91699 8.08197 6.91699H8.487C8.655 6.91699 8.79799 7.05905 8.81799 7.24805C8.86199 7.66005 9.22402 7.95396 9.64502 7.91296C10.057 7.86896 10.354 7.49806 10.31 7.08606C10.228 6.32506 9.70597 5.71998 9.02197 5.50598V5.50098C9.02197 5.08698 8.68597 4.75098 8.27197 4.75098C7.85797 4.75098 7.52197 5.08698 7.52197 5.50098V5.51599C6.78597 5.75899 6.24902 6.45802 6.24902 7.29102C6.24902 7.70602 6.38401 8.11203 6.62701 8.43103C6.87701 8.76203 7.233 9.00196 7.633 9.10596Z" fill="#EC8500"/>
                </svg>
                {{__('Order List')}}
            </a>
            @endusercan
            @usercan('tables.view')
            <a class="table-list-btn w-100" href="{{ route('business.tables.index') }}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.6019 6.24951C14.9869 6.24977 17.7497 9.02955 17.7504 12.4321V12.4341C17.7342 14.0614 17.0786 15.617 15.9271 16.7622C14.7756 17.9074 13.2206 18.5503 11.6 18.5503C9.97933 18.5503 8.42434 17.9074 7.27283 16.7622C6.12137 15.617 5.4658 14.0614 5.44958 12.4341V12.4312C5.45264 9.02778 8.21676 6.24951 11.6019 6.24951ZM11.6039 7.02881C8.6332 7.02492 6.21655 9.44413 6.21423 12.4224C6.21552 13.8562 6.78116 15.2311 7.78748 16.2476C8.79374 17.2639 10.1587 17.8399 11.5853 17.8491C14.5371 17.8631 16.9803 15.4227 16.9867 12.4536L16.9808 12.187C16.952 11.5665 16.8164 10.955 16.5804 10.3794C16.3108 9.72159 15.9153 9.1234 15.4154 8.61963C14.9154 8.11581 14.3212 7.71576 13.6674 7.44287C13.0135 7.16998 12.3119 7.02919 11.6039 7.02881Z" fill="#007DE9" stroke="#007DE9" stroke-width="0.3"/>
                    <path d="M3.54431 6.44189C3.89395 6.28192 4.29673 6.25583 4.66638 6.36865C5.03596 6.48153 5.3455 6.72558 5.52771 7.05029C5.71013 7.37545 5.75005 7.75547 5.63708 8.10791L5.63611 8.10889C5.2456 9.25532 4.84705 10.3996 4.43982 11.5405C4.2017 12.2046 3.48596 12.5961 2.80017 12.479C2.04356 12.352 1.51733 11.7948 1.50037 11.0884L1.49939 11.0796L1.50134 11.0708C1.52216 10.935 1.55093 10.8002 1.58826 10.6675L1.59021 10.6616C1.98569 9.51795 2.37561 8.37106 2.79138 7.23193V7.23096C2.92356 6.8849 3.19453 6.60195 3.54431 6.44189ZM4.36658 6.979C4.18035 6.94094 3.98584 6.96965 3.82166 7.05811C3.65753 7.1466 3.53684 7.28856 3.48083 7.45361L3.47986 7.45459C3.07407 8.58982 2.67206 9.73018 2.28943 10.8735C2.25875 10.99 2.25494 11.1109 2.27771 11.228L2.30994 11.3433L2.31091 11.3462H2.30994C2.40152 11.6275 2.684 11.7996 3.02771 11.7974H3.03064C3.19355 11.8003 3.35272 11.7527 3.48279 11.6636C3.6126 11.5745 3.70607 11.4487 3.75037 11.3071L3.75134 11.3042C4.15239 10.1715 4.54478 9.0366 4.93787 7.90088C4.95462 7.84619 4.96596 7.79029 4.97302 7.73389C4.97198 7.3331 4.72117 7.04613 4.36853 6.97998L4.36658 6.979Z" fill="#007DE9" stroke="#007DE9" stroke-width="0.2"/>
                    <path d="M19.0219 7.00146C18.8967 6.99659 18.7721 7.02016 18.6595 7.06982C18.5472 7.11944 18.4505 7.19353 18.3773 7.28467C18.3041 7.37597 18.256 7.48224 18.2377 7.59326C18.2194 7.70424 18.2309 7.81794 18.2709 7.92432L18.2719 7.92725C18.6576 9.05796 19.0537 10.1863 19.4594 11.312L19.5209 11.436C19.5951 11.5537 19.7054 11.6514 19.8412 11.7144C20.0226 11.7983 20.2334 11.8128 20.4271 11.7554C20.6206 11.698 20.7802 11.574 20.8734 11.4116C20.9661 11.25 20.9867 11.0619 20.932 10.8872C20.5433 9.74895 20.1406 8.61417 19.7347 7.48096L19.7338 7.47803C19.6883 7.34127 19.5953 7.22067 19.4681 7.13428C19.3408 7.04784 19.1862 7.00049 19.0267 7.00146H19.0219ZM21.6996 11.0356C21.6996 11.8 21.1666 12.3545 20.4047 12.481C19.7197 12.5942 19.0107 12.1958 18.7689 11.5366C18.4876 10.7658 18.2245 9.98806 17.9535 9.21631C17.889 9.03238 17.8211 8.84711 17.7543 8.66064L17.5629 8.09814C17.4492 7.74384 17.4911 7.36243 17.6791 7.0376C17.8669 6.7133 18.1845 6.47362 18.5599 6.3667V6.36572C19.3368 6.13908 20.1508 6.51497 20.4271 7.26221C20.7086 8.0246 20.9715 8.79431 21.2386 9.55908C21.3792 9.96092 21.5181 10.3637 21.6508 10.7681L21.6517 10.772C21.6755 10.8554 21.6917 10.9408 21.6996 11.0269L21.7006 11.0356H21.6996Z" fill="#007DE9" stroke="#007DE9" stroke-width="0.2"/>
                    <path d="M4.82556 16.3198C5.00396 16.2894 5.18717 16.2938 5.36365 16.3335C5.53986 16.3732 5.70631 16.4469 5.85291 16.5503L7.50134 17.7036C8.05006 18.0898 8.59756 18.4784 9.14099 18.8706C9.52871 19.1507 9.69227 19.5605 9.69958 20.0288V20.0347C9.67758 20.6031 9.41076 21.0536 8.88025 21.3218C8.35163 21.5889 7.79376 21.5581 7.29626 21.2153C6.53918 20.6943 5.79428 20.1597 5.04626 19.6323C4.74972 19.4234 4.44519 19.216 4.15076 19.0005C3.88399 18.8051 3.69203 18.5714 3.58826 18.3032C3.48422 18.0343 3.47211 17.7375 3.55408 17.4214C3.63605 17.1055 3.78943 16.8545 4.00818 16.6694C4.22614 16.4851 4.50317 16.3721 4.82556 16.3208V16.3198ZM5.04138 16.9956C4.67027 17.035 4.39212 17.2342 4.28259 17.5376L4.28162 17.5386C4.16131 17.863 4.26069 18.1857 4.57947 18.4126C5.61537 19.1507 6.65537 19.8847 7.69861 20.6147L7.77185 20.6606C8.14119 20.8713 8.5736 20.7817 8.81384 20.4585C9.05323 20.1362 9.00945 19.7138 8.6908 19.4399L8.62341 19.3872C7.59586 18.6528 6.56184 17.9251 5.52283 17.2036V17.2026C5.45643 17.1568 5.37825 17.1218 5.29041 17.0884C5.21516 17.0598 5.12422 17.0289 5.04138 16.9956Z" fill="#007DE9" stroke="#007DE9" stroke-width="0.2"/>
                    <path d="M17.3207 16.5688C18.0048 16.0992 18.9057 16.2658 19.3978 16.9087C19.6357 17.2165 19.74 17.6037 19.6859 17.9858C19.6318 18.3679 19.4237 18.7122 19.1097 18.9458L19.1107 18.9468C18.3823 19.49 17.6263 20.0028 16.8861 20.5249C16.5498 20.7622 16.2124 21.0162 15.8607 21.2427C15.3621 21.563 14.8288 21.5812 14.2992 21.3149V21.314C14.0539 21.1887 13.8486 20.9996 13.7074 20.7671C13.5662 20.5346 13.4946 20.2678 13.5004 19.9976C13.5079 19.5373 13.6852 19.1385 14.0726 18.8589C15.1478 18.0877 16.2275 17.3186 17.3207 16.5688ZM18.1244 17.02C17.9753 17.0286 17.8324 17.0798 17.7142 17.1675L17.7113 17.1694C16.6546 17.9082 15.6003 18.6518 14.5482 19.3999L14.5472 19.4009C14.4631 19.4586 14.391 19.5315 14.3363 19.6157C14.2817 19.6998 14.2452 19.7935 14.2279 19.8911C14.2106 19.9889 14.2132 20.0893 14.2357 20.186C14.2583 20.2828 14.3006 20.3742 14.3597 20.4556C14.419 20.5369 14.4943 20.6063 14.5814 20.6597C14.6686 20.713 14.7657 20.7501 14.8676 20.7671C14.9693 20.784 15.0735 20.7814 15.1742 20.7593C15.275 20.7371 15.3704 20.6959 15.4545 20.6382L17.059 19.5142C17.5923 19.1372 18.1248 18.759 18.6576 18.3804L18.6586 18.3794C18.8581 18.2395 18.9486 18.0384 18.9623 17.7651C18.9415 17.4929 18.8222 17.2634 18.5541 17.1216L18.5511 17.1206C18.4229 17.0466 18.2738 17.0115 18.1244 17.02Z" fill="#007DE9" stroke="#007DE9" stroke-width="0.2"/>
                    <path d="M13.308 2.30029C14.1022 2.30035 14.6988 3.02027 14.6996 3.89893C14.6996 4.76461 14.1199 5.48293 13.3324 5.49658H9.85388C9.11587 5.48297 8.58755 4.87232 8.5072 4.02979C8.43559 3.27546 8.88203 2.5337 9.55701 2.33838L9.55994 2.3374C9.66466 2.31038 9.77189 2.29907 9.87927 2.30127V2.30029H13.308ZM9.93201 3.05225L9.93298 3.05322C9.8253 3.05446 9.71758 3.07688 9.6156 3.12158L9.61658 3.12256C9.47163 3.18854 9.34634 3.31419 9.26599 3.48291C9.18565 3.65174 9.15635 3.85033 9.18494 4.04346L9.21912 4.18994C9.26386 4.33169 9.33887 4.45658 9.43591 4.55127C9.56481 4.67703 9.72399 4.7414 9.88416 4.73877H11.7006V4.74072C12.2204 4.74278 12.7302 4.75938 13.2386 4.73096L13.3793 4.71045C13.5184 4.67903 13.6524 4.61525 13.7738 4.52197C14.0002 4.3411 14.0779 3.97033 13.9828 3.63037C13.9291 3.44057 13.8393 3.29793 13.722 3.20264C13.6054 3.10794 13.4546 3.05421 13.2679 3.05322C12.156 3.04692 11.0439 3.04594 9.93201 3.05225Z" fill="#007DE9" stroke="#007DE9" stroke-width="0.2"/>
                </svg>
                {{__('Table List')}}
            </a>
            @endusercan
        </div>
        <div class="view-website-2">
            <a target="_blank" class="text-custom-primary" href="{{ get_online_store_url() }}">
                View Web
                <i class="fas fa-chevron-double-right"></i>
            </a>
        </div>
        <ul>
            @usercan('dashboard.view')
            <li class="{{ Request::routeIs('business.dashboard.index') ? 'active' : '' }}">
                <a href="{{ route('business.dashboard.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_3558_64064)">
                            <path d="M10.0002 15V12.5" stroke="#5B5B5B" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M1.95964 11.0112C1.66546 9.09679 1.51837 8.13965 1.88029 7.29111C2.24221 6.44256 3.04517 5.86198 4.6511 4.70084L5.85098 3.83329C7.84873 2.38885 8.84758 1.66663 10.0002 1.66663C11.1527 1.66663 12.1516 2.38885 14.1493 3.83329L15.3492 4.70084C16.9552 5.86198 17.7581 6.44256 18.12 7.29111C18.4819 8.13965 18.3348 9.09679 18.0407 11.0112L17.7898 12.6436C17.3728 15.3574 17.1643 16.7143 16.191 17.5238C15.2178 18.3333 13.7948 18.3333 10.9492 18.3333H9.05117C6.20543 18.3333 4.78258 18.3333 3.80932 17.5238C2.83606 16.7143 2.62754 15.3574 2.21051 12.6436L1.95964 11.0112Z" stroke="#5B5B5B" stroke-width="1.5" stroke-linejoin="round"/>
                            </g>
                            <defs>
                            <clipPath id="clip0_3558_64064">
                            <rect width="20" height="20" fill="white"/>
                            </clipPath>
                            </defs>
                        </svg>
                    </span>
                    {{ __('Dashboard') }}
                </a>
            </li>
            @endusercan

            <li class="{{ Request::routeIs('kitchen.kots.index') ? 'active' : '' }}">
                <a href="{{ route('kitchen.kots.index', ['status' => 'pending']) }}" class="active">
                    <span class="sidebar-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.66675 14.1666H18.3334" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round"/>
                            <path d="M10 5.83333C10 5.83333 11.25 4.97198 11.25 3.90946C11.25 2.03018 8.75 2.03018 8.75 3.90946C8.75 4.97198 10 5.83333 10 5.83333Z" stroke="#5B5B5B" stroke-width="1.25" stroke-linejoin="round"/>
                            <path d="M2.5 14.1666L3.01772 16.2375C3.20321 16.9795 3.86985 17.5 4.63463 17.5H15.3653C16.1302 17.5 16.7967 16.9795 16.9822 16.2375L17.5 14.1666" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round"/>
                            <path d="M17.0834 12.0834C16.6682 8.56404 13.6552 5.83337 10.0001 5.83337C6.34497 5.83337 3.33203 8.56404 2.91675 12.0834" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round"/>
                        </svg>
                    </span>
                    {{ __('Kot List') }}
                </a>
            </li>

            <li class="{{ Request::routeIs('kitchen.kot-reports.index') ? 'active' : '' }}">
                <a href="{{ route('kitchen.kot-reports.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.24968 2.91675C4.95318 2.95564 4.18019 3.09995 3.64532 3.63531C2.91309 4.36822 2.91309 5.54783 2.91309 7.90705V13.3287C2.91309 15.688 2.91309 16.8676 3.64532 17.6005C4.37755 18.3334 5.55607 18.3334 7.91309 18.3334H12.0798C14.4368 18.3334 15.6153 18.3334 16.3475 17.6005C17.0798 16.8676 17.0798 15.688 17.0798 13.3287V7.90705C17.0798 5.54783 17.0798 4.36822 16.3475 3.63531C15.8127 3.09995 15.0397 2.95564 13.7432 2.91675" stroke="#5B5B5B" stroke-width="1.25"/>
                            <path d="M6.24707 3.12508C6.24707 2.31966 6.9 1.66675 7.7054 1.66675H12.2887C13.0942 1.66675 13.7471 2.31966 13.7471 3.12508C13.7471 3.9305 13.0942 4.58341 12.2887 4.58341H7.7054C6.9 4.58341 6.24707 3.9305 6.24707 3.12508Z" stroke="#5B5B5B" stroke-width="1.25" stroke-linejoin="round"/>
                            <path d="M11.25 9.16663H14.1667" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round"/>
                            <path d="M5.83301 9.99992C5.83301 9.99992 6.24967 9.99992 6.66634 10.8333C6.66634 10.8333 7.98987 8.74992 9.16634 8.33325" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M11.25 14.1666H14.1667" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round"/>
                            <path d="M6.66699 14.1667H7.50033" stroke="#5B5B5B" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    {{ __('Kot Reports') }}
                </a>
            </li>

            @usercanany(['quotations.create', 'sales.create'])
            <li class="{{ Request::routeIs('business.sales.create') ? 'active' : '' }}">
                <a href="{{ route('business.sales.create') }}" class="active">
                    <span class="sidebar-icon">
                        <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.5833 4.68665C15.2736 4.68665 15.8333 5.24629 15.8333 5.93665C15.8333 6.627 15.2736 7.18665 14.5833 7.18665C13.8929 7.18665 13.3333 6.627 13.3333 5.93665C13.3333 5.24629 13.8929 4.68665 14.5833 4.68665Z" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2.31194 9.80656C1.47598 10.7402 1.458 12.1488 2.22522 13.1397C3.74768 15.1061 5.41403 16.7724 7.38036 18.2948C8.37125 19.0621 9.77983 19.0441 10.7135 18.2081C13.2483 15.9385 15.5697 13.5666 17.81 10.9599C18.0315 10.7022 18.17 10.3864 18.2011 10.048C18.3386 8.55163 18.6211 4.24054 17.4503 3.06976C16.2795 1.89899 11.9684 2.18145 10.4721 2.31895C10.1337 2.35005 9.81783 2.48859 9.56008 2.71007C6.95352 4.95036 4.58159 7.27174 2.31194 9.80656Z" stroke="#5B5B5B" stroke-width="1.25"/>
                            <path d="M11.4903 10.8255C11.508 10.4914 11.6018 9.88005 11.0937 9.41547M11.0937 9.41547C10.9364 9.27172 10.7216 9.14197 10.429 9.0388C9.38184 8.66975 8.09562 9.90505 9.0055 11.0358C9.49459 11.6435 9.87167 11.8305 9.83617 12.5207C9.81117 13.0063 9.33425 13.5135 8.70567 13.7068C8.15959 13.8746 7.55722 13.6524 7.17622 13.2266C6.71102 12.7069 6.758 12.2168 6.75402 12.0032M11.0937 9.41547L11.6671 8.84204M7.21768 13.2915L6.6731 13.836" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    {{ __('POS') }}
                </a>
            </li>
            @endusercanany

            @usercanany(['sales.view', 'quotations.view', 'tables.view'])
            <li
                class="dropdown {{ Request::routeIs('business.sales.index', 'business.sales.edit', 'business.quotations.index', 'business.quotations.edit', 'business.quotations.convert-sale', 'business.booked-tables.index') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon">
                        <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.66504 13.8532H9.99833M6.66504 9.68652H13.3317" stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                            <path d="M6.25 3.43652C4.9535 3.47542 4.18051 3.61972 3.64564 4.15508C2.91341 4.888 2.91341 6.06761 2.91341 8.42682V13.8485C2.91341 16.2078 2.91341 17.3874 3.64564 18.1203C4.37788 18.8532 5.55639 18.8532 7.91341 18.8532H12.0801C14.4371 18.8532 15.6156 18.8532 16.3478 18.1203C17.0801 17.3874 17.0801 16.2078 17.0801 13.8485V8.42682C17.0801 6.06761 17.0801 4.888 16.3478 4.15509C15.813 3.61972 15.04 3.47542 13.7435 3.43652" stroke="white" stroke-width="1.25"/>
                            <path d="M6.24674 3.64486C6.24674 2.83944 6.89967 2.18652 7.70508 2.18652H12.2884C13.0938 2.18652 13.7467 2.83944 13.7467 3.64486C13.7467 4.45027 13.0938 5.10319 12.2884 5.10319H7.70508C6.89967 5.10319 6.24674 4.45027 6.24674 3.64486Z" stroke="white" stroke-width="1.25" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    {{ __('Orders') }}</a>
                <ul>
                    @usercan('sales.view')
                    <li>
                        <a class="{{ (Request::routeIs('business.sales.index') || Request::routeIs('business.sales.edit')) && !request()->has('filter') && !request()->has('type') ? 'active' : '' }}"
                        href="{{ route('business.sales.index') }}">
                        {{ __('Order List') }}
                        </a>
                    </li>
                    @endusercan
                    @usercan('sales.view')
                    <li>
                        <a class="{{ (Request::routeIs('business.sales.index') || Request::routeIs('business.sales.edit')) && request('type') === 'online-sale' && !request()->has('filter') ? 'active' : '' }}"
                        href="{{ route('business.sales.index', ['type' => 'online-sale']) }}">
                        {{ __('Online Order') }}
                        </a>
                    </li>
                    @endusercan
                    @usercan('tables.view')
                    <li>
                        <a class="{{ Request::routeIs('business.booked-tables.index') ? 'active' : '' }}"
                            href="{{ route('business.booked-tables.index', ['is_booked' => 'booked']) }}">{{ __('Booked Table') }}</a>
                    </li>
                    @endusercan
                    @usercan('quotations.view')
                    <li>
                        <a class="{{ Request::routeIs('business.quotations.index', 'business.quotations.edit', 'business.quotations.convert-sale') ? 'active' : '' }}"
                            href="{{ route('business.quotations.index') }}">{{ __('Quotation List') }}</a>
                    </li>
                    @endusercan
                </ul>
            </li>
            @endusercanany
                        @if(Route::has('business.domains.index'))
                        <li class="{{ Request::routeIs('business.domains.index') ? 'active' : '' }}">
                            <a href="{{ route('business.domains.index') }}" class="active">
                            </a>
                        </li>
                        @endif

            @usercanany(['purchases.view', 'purchases.create', 'ingredients.view', 'units.view',])
            <li
                class="dropdown {{ Request::routeIs('business.purchases.index', 'business.purchases.create', 'business.purchases.edit', 'business.items.index', 'business.units.index',) ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon">
                        <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.58268 7.18799H16.8293C17.35 7.18799 17.6104 7.18799 17.8009 7.27202C18.644 7.64374 18.2671 8.58028 18.1253 9.25332C18.0998 9.37424 18.0169 9.47715 17.9007 9.53232C17.4188 9.76099 17.0812 10.1947 16.9926 10.6992L16.4988 13.5112C16.2815 14.7484 16.2073 16.5166 15.123 17.3882C14.3275 18.0213 13.1814 18.0213 10.8889 18.0213H9.10977C6.81737 18.0213 5.67118 18.0213 4.87568 17.3882C3.79145 16.5165 3.71717 14.7484 3.4999 13.5112L3.00608 10.6992C2.91749 10.1947 2.57996 9.76099 2.09803 9.53232C1.98182 9.47715 1.89895 9.37424 1.87347 9.25332C1.73163 8.58028 1.35468 7.64374 2.19778 7.27202C2.38838 7.18799 2.6487 7.18799 3.16933 7.18799H6.24935" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round"/>
                            <path d="M11.6673 10.52H8.33398" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5.41602 9.68669L8.33268 3.02002M12.4993 3.02002L14.5827 7.18669" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round"/>
                        </svg>
                    </span>
                    {{ __('Purchases') }}</a>
                <ul>
                    @usercan('purchases.create')
                    <li>
                        <a class="{{ Request::routeIs('business.purchases.create') ? 'active' : '' }}"
                            href="{{ route('business.purchases.create') }}">{{ __('Add Purchase') }}</a>
                    </li>
                    @endusercan
                    @usercan('purchases.view')
                    <li>
                        <a class="{{ Request::routeIs('business.purchases.index',  'business.purchases.edit') ? 'active' : '' }}"
                            href="{{ route('business.purchases.index') }}">{{ __('Purchase List') }}</a>
                    </li>
                    @endusercan
                    @usercan('ingredients.view')
                    <li>
                        <a class="{{ Request::routeIs('business.items.index') ? 'active' : '' }}"
                            href="{{ route('business.items.index') }}">{{ __('Inventory Item') }}</a>
                    </li>
                    @endusercan
                    @usercan('units.view')
                    <li>
                        <a class="{{ Request::routeIs('business.units.index') ? 'active' : '' }}"
                            href="{{ route('business.units.index') }}">{{ __('Units') }}</a>
                    </li>
                    @endusercan
                </ul>
            </li>
            @endusercanany

            @usercan('reservations.view')
            <li class="{{ Request::routeIs('business.reservations.index', 'business.reservations.create', 'business.reservations.edit') ? 'active' : '' }}">
                <a href="{{ route('business.reservations.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.6654 13.3327V6.66602C16.6654 4.30899 16.6654 3.13048 15.9331 2.39825C15.2009 1.66602 14.0224 1.66602 11.6654 1.66602H8.33203C5.97501 1.66602 4.7965 1.66602 4.06426 2.39825C3.33203 3.13048 3.33203 4.30899 3.33203 6.66602V13.3327C3.33203 15.6897 3.33203 16.8682 4.06426 17.6004C4.7965 18.3327 5.97501 18.3327 8.33203 18.3327H11.6654C14.0224 18.3327 15.2009 18.3327 15.9331 17.6004C16.6654 16.8682 16.6654 15.6897 16.6654 13.3327Z"stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12.9154 1.66602H7.08203C7.08203 2.84452 7.08203 3.43378 7.44815 3.7999C7.81426 4.16602 8.40353 4.16602 9.58203 4.16602H10.4154C11.5939 4.16602 12.1831 4.16602 12.5493 3.7999C12.9154 3.43378 12.9154 2.84452 12.9154 1.66602Z"stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.25 9.16602L7.08333 9.99935L8.75 7.91602" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10.832 14.166H13.332M10.832 9.16602H13.332" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7.07422 14.0566H7.08172" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    {{ __('Reservations') }}
                </a>
            </li>
            @endusercan

            @usercanany(['areas.view', 'tables.view'])
            <li
                class="dropdown {{ Request::routeIs('business.tables.index', 'business.areas.index', 'business.tables.show') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.66675 3.33337H18.3334" stroke="#5B5B5B" stroke-width="1.25" stroke-linejoin="round"/>
                            <path d="M16.2501 3.33337L18.3334 16.6667M3.75008 3.33337L1.66675 16.6667" stroke="#5B5B5B" stroke-width="1.25" stroke-linejoin="round"/>
                            <path d="M3.33325 7.5H16.6666" stroke="#5B5B5B" stroke-width="1.25" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    {{ __('Tables') }}</a>
                <ul>
                    @usercan('areas.view')
                    <li>
                        <a class="{{ Request::routeIs('business.areas.index') ? 'active' : '' }}" href="{{ route('business.areas.index') }}">{{ __('Areas ') }}</a>
                    </li>
                    @endusercan

                    @usercan('tables.view')
                    <li>
                        <a class="{{ Request::routeIs('business.tables.index', 'business.tables.show') ? 'active' : '' }}" href="{{ route('business.tables.index') }}">{{ __('Tables') }}</a>
                    </li>
                    @endusercan
                </ul>
            </li>
            @endusercanany

            @usercanany(['products.view', 'categories.view', 'menus.view', 'modifierGroups.view', 'itemModifiers.view'])
            <li
                class="dropdown {{ Request::routeIs('business.products.index', 'business.products.create', 'business.products.edit', 'business.categories.index', 'business.menus.index', 'business.modifiers.index', 'business.modifier-groups.index', 'business.modifier-groups.create', 'business.modifier-groups.edit') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon">
                        <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 18.8534C9.31817 18.8534 8.66683 18.5782 7.36411 18.028C4.12137 16.6581 2.5 15.9732 2.5 14.8211C2.5 14.4986 2.5 8.90714 2.5 6.35339M10 18.8534C10.6818 18.8534 11.3332 18.5782 12.6359 18.028C15.8787 16.6581 17.5 15.9732 17.5 14.8211V6.35339M10 18.8534V9.98239" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.93827 8.59613L4.50393 7.41819C3.16797 6.77173 2.5 6.4485 2.5 5.93665C2.5 5.42479 3.16797 5.10156 4.50393 4.4551L6.93827 3.27716C8.44067 2.55015 9.19192 2.18665 10 2.18665C10.8081 2.18665 11.5593 2.55015 13.0617 3.27716L15.4961 4.4551C16.832 5.10156 17.5 5.42479 17.5 5.93665C17.5 6.4485 16.832 6.77173 15.4961 7.41819L13.0617 8.59613C11.5593 9.32314 10.8081 9.68665 10 9.68665C9.19192 9.68665 8.44067 9.32314 6.93827 8.59613Z" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5 10.52L6.66667 11.3534" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14.1666 3.85339L5.83325 8.02006" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    {{ __('Menus') }}</a>
                <ul>
                    @usercan('products.view')
                    <li>
                        <a class="{{ Request::routeIs('business.products.index', 'business.products.create', 'business.products.edit') ? 'active' : '' }}"
                            href="{{ route('business.products.index') }}">{{ __('Items List') }}</a>
                    </li>
                    @endusercan
                    @usercan('categories.view')
                    <li>
                        <a class="{{ Request::routeIs('business.categories.index') ? 'active' : '' }}"
                            href="{{ route('business.categories.index') }}">{{ __('Category') }}</a>
                    </li>
                    @endusercan
                    @usercan('menus.view')
                    <li>
                        <a class="{{ Request::routeIs('business.menus.index') ? 'active' : '' }}"
                            href="{{ route('business.menus.index') }}">{{ __('Menu') }}</a>
                    </li>
                    @endusercan
                    @usercan('modifierGroups.view')
                    <li>
                        <a class="{{ Request::routeIs('business.modifier-groups.index', 'business.modifier-groups.create', 'business.modifier-groups.edit') ? 'active' : '' }}"
                            href="{{ route('business.modifier-groups.index') }}">{{ __('Modifier Groups') }}</a>
                    </li>
                    @endusercan
                    @usercan('itemModifiers.view')
                    <li>
                        <a class="{{ Request::routeIs('business.modifiers.index') ? 'active' : '' }}"
                            href="{{ route('business.modifiers.index') }}">{{ __('Item Modifiers') }}</a>
                    </li>
                    @endusercan
                </ul>
            </li>
            @endusercanany

            @usercan('parties.view')
            <li
                class="dropdown {{ Request::routeIs('business.parties.index','business.parties.create','business.parties.edit') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon">
                        <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.5 7.18665C12.5 8.56735 11.3807 9.68665 10 9.68665C8.61925 9.68665 7.5 8.56735 7.5 7.18665C7.5 5.80594 8.61925 4.68665 10 4.68665C11.3807 4.68665 12.5 5.80594 12.5 7.18665Z" stroke="#5B5B5B" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.3333 3.85339C14.7139 3.85339 15.8333 4.97269 15.8333 6.35339C15.8333 7.37264 15.2233 8.24942 14.3485 8.63864" stroke="#5B5B5B" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M11.4286 12.1866H8.57143C6.59898 12.1866 5 13.7856 5 15.7581C5 16.5471 5.63959 17.1866 6.42857 17.1866H13.5714C14.3604 17.1866 15 16.5471 15 15.7581C15 13.7856 13.401 12.1866 11.4286 12.1866Z" stroke="#5B5B5B" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14.762 11.3534C16.7344 11.3534 18.3334 12.9524 18.3334 14.9248C18.3334 15.7138 17.6938 16.3534 16.9048 16.3534" stroke="#5B5B5B" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.66675 3.85339C5.28604 3.85339 4.16675 4.97269 4.16675 6.35339C4.16675 7.37264 4.77669 8.24942 5.65148 8.63864" stroke="#5B5B5B" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3.09532 16.3534C2.30634 16.3534 1.66675 15.7138 1.66675 14.9248C1.66675 12.9524 3.26573 11.3534 5.23817 11.3534" stroke="#5B5B5B" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    {{ __('Parties') }}
                </a>
                <ul>
                    @usercan('parties.view')
                    <li>
                        <a class="{{ (Request::routeIs('business.parties.index','business.parties.create','business.parties.edit') && request('type') == 'customer') ? 'active' : '' }}"
                            href="{{ route('business.parties.index', ['type' => 'customer']) }}">
                            {{ __('Customer') }}
                        </a>
                    </li>
                    @endusercan
                    @usercan('parties.view')
                    <li>
                        <a class="{{ (Request::routeIs('business.parties.index','business.parties.create','business.parties.edit') && request('type') == 'supplier') ? 'active' : '' }}"
                            href="{{ route('business.parties.index', ['type' => 'supplier']) }}">
                            {{ __('Supplier') }}
                        </a>
                    </li>
                    @endusercan
                </ul>
            </li>
            @endusercan


            @usercan('dueCollection.view')
            <li
                class="dropdown {{ Request::routeIs('business.dues.index', 'business.collect.dues', 'business.walk-dues.index') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.33301 15.5375V6.71123C3.33301 4.33289 3.33301 3.14372 4.06524 2.40487C4.79747 1.66602 5.97598 1.66602 8.33301 1.66602H11.6663C14.0233 1.66602 15.2018 1.66602 15.9341 2.40487C16.6663 3.14372 16.6663 4.33289 16.6663 6.71123V15.5375C16.6663 16.7973 16.6663 17.4271 16.2813 17.675C15.6523 18.0803 14.6798 17.2305 14.1906 16.9221C13.7864 16.6672 13.5844 16.5398 13.3601 16.5324C13.1178 16.5244 12.9121 16.6467 12.4754 16.9221L10.883 17.9263C10.4534 18.1972 10.2387 18.3327 9.99967 18.3327C9.76067 18.3327 9.54592 18.1972 9.11634 17.9263L7.52395 16.9221C7.1198 16.6672 6.91772 16.5398 6.69345 16.5324C6.45111 16.5244 6.24545 16.6467 5.80873 16.9221C5.31963 17.2305 4.34707 18.0803 3.71797 17.675C3.33301 17.4271 3.33301 16.7973 3.33301 15.5375Z" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.3337 5H6.66699" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8.33366 8.33398H6.66699" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12.083 8.22917C11.3927 8.22917 10.833 8.71883 10.833 9.32292C10.833 9.927 11.3927 10.4167 12.083 10.4167C12.7733 10.4167 13.333 10.9063 13.333 11.5104C13.333 12.1145 12.7733 12.6042 12.083 12.6042M12.083 8.22917C12.6273 8.22917 13.0903 8.5335 13.2618 8.95833M12.083 8.22917V7.5M12.083 12.6042C11.5388 12.6042 11.0758 12.2998 10.9042 11.875M12.083 12.6042V13.3333" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round"/>
                        </svg>
                    </span>
                    {{ __('Due List') }}</a>
                <ul>
                    <li><a class="{{ Request::routeIs('business.dues.index', 'business.collect.dues') ? 'active' : '' }}" href="{{ route('business.dues.index') }}">{{ __('All Due') }}</a></li>
                    <li><a class="{{ Request::routeIs('business.walk-dues.index') ? 'active' : '' }}" href="{{ route('business.walk-dues.index') }}">{{ __('Cash Due') }}</a></li>
                </ul>
            </li>
            @endusercan

            @usercan('coupon.view')
            <li class="{{ Request::routeIs('business.coupons.index') ? 'active' : '' }}">
                <a href="{{ route('business.coupons.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2.05369 7.78642C1.84657 7.78642 1.65757 7.61853 1.66709 7.39908C1.72285 6.11402 1.87908 5.27744 2.31681 4.61566C2.56864 4.23493 2.88145 3.90378 3.2411 3.63719C4.2132 2.91663 5.58457 2.91663 8.32729 2.91663H11.6729C14.4156 2.91663 15.7869 2.91663 16.7591 3.63719C17.1187 3.90378 17.4315 4.23493 17.6834 4.61566C18.121 5.27737 18.2773 6.11383 18.333 7.39865C18.3426 7.61836 18.1534 7.78642 17.946 7.78642C16.7912 7.78642 15.855 8.77746 15.855 9.99996C15.855 11.2225 16.7912 12.2135 17.946 12.2135C18.1534 12.2135 18.3426 12.3815 18.333 12.6013C18.2773 13.8861 18.121 14.7225 17.6834 15.3843C17.4315 15.765 17.1187 16.0961 16.7591 16.3627C15.7869 17.0833 14.4156 17.0833 11.6729 17.0833H8.32729C5.58457 17.0833 4.2132 17.0833 3.2411 16.3627C2.88145 16.0961 2.56864 15.765 2.31681 15.3843C1.87908 14.7225 1.72285 13.8859 1.66709 12.6009C1.65757 12.3814 1.84657 12.2135 2.05369 12.2135C3.20851 12.2135 4.14468 11.2225 4.14468 9.99996C4.14468 8.77746 3.20851 7.78642 2.05369 7.78642Z" stroke="#5B5B5B" stroke-width="1.25" stroke-linejoin="round"/>
                            <path d="M7.91675 12.0833L12.0834 7.91663" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7.91675 7.91663H7.92611M12.074 12.0833H12.0834" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    {{ __('Coupon') }}
                </a>
            </li>
            @endusercan

            @usercanany(['income.view', 'incomeCategory.view'])
            <li
                class="dropdown {{ Request::routeIs('business.incomes.index', 'business.income-categories.index') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon">
                        <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.4522 14.5496C16.9053 11.2589 15.2027 8.8085 13.7225 7.36923C13.2917 6.95041 13.0764 6.741 12.6007 6.5472C12.1249 6.35339 11.716 6.35339 10.8982 6.35339H9.10183C8.28401 6.35339 7.87508 6.35339 7.39935 6.5472C6.92361 6.741 6.70826 6.95041 6.27753 7.36923C4.79735 8.8085 3.09467 11.2589 2.54772 14.5496C2.14077 16.9979 4.39939 18.8534 6.9236 18.8534H13.0764C15.6006 18.8534 17.8592 16.9979 17.4522 14.5496Z" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.0471 4.22237C5.87518 3.97213 5.62599 3.63247 6.14074 3.55502C6.66985 3.4754 7.21926 3.8376 7.75704 3.83015C8.24356 3.82342 8.49142 3.60764 8.75733 3.29955C9.03733 2.97512 9.47092 2.18665 9.99992 2.18665C10.5289 2.18665 10.9625 2.97512 11.2425 3.29955C11.5084 3.60764 11.7563 3.82342 12.2428 3.83015C12.7806 3.8376 13.33 3.4754 13.8591 3.55502C14.3738 3.63247 14.1247 3.97213 13.9528 4.22237L13.1753 5.35385C12.8428 5.83786 12.6766 6.07987 12.3286 6.2166C11.9807 6.35331 11.531 6.35331 10.6318 6.35331H9.36809C8.46884 6.35331 8.01917 6.35331 7.67122 6.2166C7.32327 6.07987 7.157 5.83786 6.82446 5.35385L6.0471 4.22237Z" stroke="#5B5B5B" stroke-width="1.25" stroke-linejoin="round"/>
                            <path d="M11.3557 11.2856C11.1755 10.6205 10.2585 10.0203 9.15775 10.4693C8.05699 10.9182 7.88214 12.3628 9.54717 12.5163C10.2997 12.5857 10.7903 12.4358 11.2395 12.8597C11.6887 13.2836 11.7722 14.4624 10.6238 14.7801C9.4755 15.0977 8.33842 14.6014 8.2146 13.8965M9.86817 9.68066V10.3143M9.86817 14.8778V15.514" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    {{ __('Incomes') }}</a>
                <ul>
                    @usercan('income.view')
                    <li>
                        <a class="{{ Request::routeIs('business.incomes.index') ? 'active' : '' }}"
                            href="{{ route('business.incomes.index') }}">{{ __('Income') }}</a>
                        </li>
                    @endusercan
                    @usercan('incomeCategory.view')
                    <li><a class="{{ Request::routeIs('business.income-categories.index') ? 'active' : '' }}"
                            href="{{ route('business.income-categories.index') }}">{{ __('Income Category') }}</a>
                    </li>
                    @endusercan
                </ul>
            </li>
            @endusercanany

            @usercanany(['expense.view', 'expenseCategory.view'])
            <li
                class="dropdown {{ Request::routeIs('business.expense-categories.index', 'business.expenses.index') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon">
                        <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.66675 4.27002H7.29788C7.96092 4.27002 8.59683 4.53341 9.06566 5.00225L11.6667 7.60335" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M4.16675 11.77H1.66675" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7.08341 6.77002L8.75008 8.43669C9.21033 8.89694 9.21033 9.6431 8.75008 10.1034C8.28985 10.5636 7.54366 10.5636 7.08341 10.1034L5.83341 8.85335C5.11617 9.5706 3.98067 9.65127 3.1692 9.04269L2.91675 8.85335" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M4.16675 9.68673V13.4367C4.16675 15.0081 4.16675 15.7937 4.65491 16.2819C5.14306 16.7701 5.92873 16.7701 7.50008 16.7701H15.0001C16.5714 16.7701 17.3571 16.7701 17.8452 16.2819C18.3334 15.7937 18.3334 15.0081 18.3334 13.4367V10.9367C18.3334 9.36539 18.3334 8.5797 17.8452 8.09155C17.3571 7.60339 16.5714 7.60339 15.0001 7.60339H7.91675" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12.7084 12.1867C12.7084 12.9921 12.0555 13.6451 11.2501 13.6451C10.4447 13.6451 9.79175 12.9921 9.79175 12.1867C9.79175 11.3813 10.4447 10.7284 11.2501 10.7284C12.0555 10.7284 12.7084 11.3813 12.7084 12.1867Z" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    {{ __('Expenses') }}</a>
                <ul>
                    @usercan('expense.view')
                    <li><a class="{{ Request::routeIs('business.expenses.index') ? 'active' : '' }}"
                            href="{{ route('business.expenses.index') }}">{{ __('Expense') }}</a>
                    </li>
                    @endusercan
                    @usercan('expenseCategory.view')
                    <li><a class="{{ Request::routeIs('business.expense-categories.index') ? 'active' : '' }}"
                            href="{{ route('business.expense-categories.index') }}">{{ __('Expense Category') }}</a>
                    </li>
                    @endusercan
                </ul>
            </li>
            @endusercanany

            @usercan('transactions.view')
            <li class="{{ Request::routeIs('business.transactions.index') ? 'active' : '' }}">
                <a href="{{ route('business.transactions.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.25 2.91797C4.9535 2.95686 4.18051 3.10117 3.64564 3.63653C2.91341 4.36944 2.91341 5.54905 2.91341 7.90827V13.33C2.91341 15.6892 2.91341 16.8688 3.64564 17.6017C4.37787 18.3346 5.55639 18.3346 7.91341 18.3346H12.0801C14.4371 18.3346 15.6156 18.3346 16.3478 17.6017C17.0801 16.8688 17.0801 15.6892 17.0801 13.33V7.90827C17.0801 5.54905 17.0801 4.36944 16.3478 3.63654C15.813 3.10117 15.04 2.95686 13.7435 2.91797" stroke="white" stroke-width="1.25"/>
                            <path d="M6.24674 3.1263C6.24674 2.32089 6.89967 1.66797 7.70508 1.66797H12.2884C13.0938 1.66797 13.7468 2.32089 13.7468 3.1263C13.7468 3.93172 13.0938 4.58464 12.2884 4.58464H7.70508C6.89967 4.58464 6.24674 3.93172 6.24674 3.1263Z" stroke="white" stroke-width="1.25" stroke-linejoin="round"/>
                            <path d="M11.25 9.16797H14.1667" stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                            <path d="M5.83334 9.9987C5.83334 9.9987 6.25 9.9987 6.66667 10.832C6.66667 10.832 7.9902 8.7487 9.16667 8.33203" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M11.25 14.168H14.1667" stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                            <path d="M6.66666 14.168H7.5" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    {{ __('Transaction') }}
                </a>
            </li>
            @endusercan

            @usercan('vat.view')
            <li class="{{ Request::routeIs('business.vats.index', 'business.vats.create', 'business.vats.edit') ? 'active' : '' }}">
                <a href="{{ route('business.vats.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.68 1.66663C15.7522 1.66663 15 3.9052 15 6.66663H16.68C17.4897 6.66663 17.8945 6.66663 18.1451 6.38708C18.3957 6.10753 18.3521 5.7394 18.2648 5.00313C18.0345 3.05948 17.4119 1.66663 16.68 1.66663Z" stroke="#5B5B5B" stroke-width="1.25"/>
                            <path d="M15.0001 6.71184V15.5381C15.0001 16.7979 15.0001 17.4277 14.6151 17.6756C13.986 18.0809 13.0135 17.2311 12.5243 16.9227C12.1202 16.6678 11.9182 16.5404 11.6938 16.533C11.4515 16.525 11.2458 16.6473 10.8092 16.9227L9.21675 17.927C8.78716 18.1978 8.57241 18.3333 8.33341 18.3333C8.09442 18.3333 7.87963 18.1978 7.45008 17.927L5.85769 16.9227C5.45354 16.6678 5.25146 16.5404 5.02719 16.533C4.78485 16.525 4.57919 16.6473 4.14247 16.9227C3.65337 17.2311 2.68081 18.0809 2.05171 17.6756C1.66675 17.4277 1.66675 16.7979 1.66675 15.5381V6.71184C1.66675 4.3335 1.66675 3.14433 2.39898 2.40548C3.13121 1.66663 4.30972 1.66663 6.66675 1.66663H16.6667" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8.33341 6.66671C7.41294 6.66671 6.66675 7.22635 6.66675 7.91671C6.66675 8.60704 7.41294 9.16671 8.33341 9.16671C9.25391 9.16671 10.0001 9.72637 10.0001 10.4167C10.0001 11.107 9.25391 11.6667 8.33341 11.6667M8.33341 6.66671C9.05908 6.66671 9.67641 7.01454 9.90525 7.50004M8.33341 6.66671V5.83337M8.33341 11.6667C7.60774 11.6667 6.99038 11.3189 6.76158 10.8334M8.33341 11.6667V12.5" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round"/>
                        </svg>
                    </span>
                    {{ __('VAT') }}
                </a>
            </li>
            @endusercan

            @usercan('staff.view')
            <li class="{{ Request::routeIs('business.staffs.index') ? 'active' : '' }}">
                <a href="{{ route('business.staffs.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.8334 6.35335C10.8334 8.1943 9.341 9.68669 7.50008 9.68669C5.65913 9.68669 4.16675 8.1943 4.16675 6.35335C4.16675 4.5124 5.65913 3.02002 7.50008 3.02002C9.341 3.02002 10.8334 4.5124 10.8334 6.35335Z" stroke="#5B5B5B" stroke-width="1.25"/>
                            <path d="M12.5 9.68669C14.3409 9.68669 15.8333 8.1943 15.8333 6.35335C15.8333 4.5124 14.3409 3.02002 12.5 3.02002" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.16675 12.1866H5.83341C3.53223 12.1866 1.66675 14.0521 1.66675 16.3533C1.66675 17.2738 2.41294 18.02 3.33341 18.02H11.6667C12.5872 18.02 13.3334 17.2738 13.3334 16.3533C13.3334 14.0521 11.4679 12.1866 9.16675 12.1866Z" stroke="#5B5B5B" stroke-width="1.25" stroke-linejoin="round"/>
                            <path d="M14.1667 12.1866C16.4679 12.1866 18.3334 14.0521 18.3334 16.3533C18.3334 17.2738 17.5872 18.02 16.6667 18.02H15.4167" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    {{ __('Staff') }}
                </a>
            </li>
            @endusercan

            @usercan('subscription.view')
            <li class="{{ Request::routeIs('business.subscriptions.index') ? 'active' : '' }}">
                <a href="{{ route('business.subscriptions.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.08333 1.66699V8.75033M12.9167 1.66699V8.75033" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round"/>
                            <path d="M14.9302 1.67671H5.06983C4.31072 1.67671 3.3058 1.54553 2.78665 2.24701C2.5 2.63433 2.5 3.19478 2.5 4.3157C2.5 5.27136 2.5 5.7492 2.69328 6.15178C3.02339 6.83936 3.76148 7.13805 4.39409 7.46286L7.48442 9.04958C8.71883 9.68341 9.33608 10.0003 10 10.0003C10.6639 10.0003 11.2812 9.68341 12.5156 9.04958L15.6059 7.46286C16.2385 7.13805 16.9766 6.83936 17.3068 6.15178C17.5 5.7492 17.5 5.27136 17.5 4.3157C17.5 3.19478 17.5 2.63433 17.2133 2.24701C16.6942 1.54553 15.6893 1.67671 14.9302 1.67671Z" stroke="#5B5B5B" stroke-width="1.25"/>
                            <path d="M8.77433 11.3643C9.37317 11.0101 9.67258 10.833 10 10.833C10.3274 10.833 10.6268 11.0101 11.2257 11.3643L12.059 11.8573C12.6806 12.225 12.9913 12.4088 13.1623 12.7163C13.3333 13.0238 13.3333 13.3988 13.3333 14.1488V15.0172C13.3333 15.7673 13.3333 16.1423 13.1623 16.4497C12.9913 16.7572 12.6806 16.941 12.059 17.3087L11.2257 17.8017C10.6268 18.1559 10.3274 18.333 10 18.333C9.67258 18.333 9.37317 18.1559 8.77433 17.8017L7.94102 17.3087C7.31943 16.941 7.00864 16.7572 6.83766 16.4497C6.66667 16.1423 6.66667 15.7673 6.66667 15.0172V14.1488C6.66667 13.3988 6.66667 13.0238 6.83766 12.7163C7.00864 12.4088 7.31943 12.225 7.94102 11.8573L8.77433 11.3643Z" stroke="#5B5B5B" stroke-width="1.25" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    {{ __('Subscriptions') }}
                </a>
            </li>
            @endusercan

            @usercan('paymentMethod.view')
            <li class="{{ Request::routeIs('business.payment-types.index') ? 'active' : '' }}">
                <a href="{{ route('business.payment-types.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.16667 17.9166H12.5C14.857 17.9166 16.0355 17.9166 16.7677 17.1844C17.5 16.4521 17.5 15.2736 17.5 12.9166V12.0833C17.5 9.72631 17.5 8.54781 16.7677 7.81555C16.0355 7.08331 14.857 7.08331 12.5 7.08331H2.5V12.9166" stroke="white" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12.5 7.08192V3.42523C12.5 2.68411 11.8992 2.08331 11.1581 2.08331C10.9447 2.08331 10.7343 2.13421 10.5445 2.2318L3.13525 6.04104C2.7452 6.24157 2.5 6.64334 2.5 7.08192" stroke="white" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14.5827 12.9166C14.8128 12.9166 14.9993 12.73 14.9993 12.4999C14.9993 12.2698 14.8128 12.0833 14.5827 12.0833M14.5827 12.9166C14.3526 12.9166 14.166 12.73 14.166 12.4999C14.166 12.2698 14.3526 12.0833 14.5827 12.0833M14.5827 12.9166V12.0833" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2.5 16.25C2.5 16.25 3.33333 16.25 4.16667 17.9167C4.16667 17.9167 6.81372 13.75 9.16667 12.9167" stroke="white" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    {{ __('Payment Type') }}
                </a>
            </li>
            @endusercan

                            @if (moduleCheck('CustomDomainAddon'))
                                @usercanany(['domains.read', 'domains.read'])
                                        @if(Route::has('business.domains.index'))
                                        <li class="{{ Request::routeIs('business.domains.index') ? 'active' : '' }}">
                                                <a href="{{ route('business.domains.index') }}" class="active">
                            <span class="sidebar-icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 18.3333C5.39765 18.3333 1.66669 14.6023 1.66669 9.99999C1.66669 7.67287 2.62056 5.56855 4.15866 4.05667M10 18.3333C9.19752 17.7387 9.3256 17.0462 9.72819 16.3539C10.3472 15.2895 10.3472 15.2895 10.3472 13.8703C10.3472 12.4512 11.1905 11.7857 14.1667 12.3809C15.5039 12.6484 16.4784 10.8007 18.2144 11.4108M10 18.3333C14.1215 18.3333 17.5442 15.3413 18.2144 11.4108M4.15866 4.05667C4.8664 4.13136 5.26266 4.51052 5.92082 5.20596C7.17035 6.52626 8.41985 6.63642 9.25294 6.19632C10.5024 5.53618 9.45244 4.46691 10.9189 3.88581C11.818 3.52958 11.9894 2.59665 11.5639 1.81315M4.15866 4.05667C5.66248 2.5785 7.72476 1.66666 10 1.66666C10.5345 1.66666 11.0573 1.71698 11.5639 1.81315M18.2144 11.4108C18.2926 10.9522 18.3334 10.4809 18.3334 9.99999C18.3334 5.93213 15.4187 2.54503 11.5639 1.81315" stroke="#5B5B5B" stroke-width="1.25" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            {{ __('My Domains') }}
                        </a>
                    </li>
                    @endif
                @endusercanany
            @endif

            @if (moduleCheck('RestaurantOnlineStore'))
                @usercan('messages.view')
                    <li class="{{ Request::routeIs('business.messages.index') ? 'active' : '' }}">
                        <a href="{{ route('business.messages.index') }}" class="active">
                            <span class="sidebar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                </svg>
                            </span>
                            {{ __('Messages') }}
                        </a>
                    </li>
                @endusercan
            @endif

            @if (moduleCheck('RestaurantOnlineStore'))
            @usercanany(['webSettings.view', 'terms.view', 'privacy.view', 'testimonials.view', 'blogs.view'])
            <li class="dropdown {{ Route::is('business.term-conditions.index', 'business.privacy-policy.index', 'business.testimonials.index', 'business.testimonials.edit', 'business.testimonials.create', 'business.blogs.index', 'business.blogs.create', 'business.blogs.edit', 'business.blogs.filter.comment', 'business.website-settings.index',) ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                        </svg>
                    </span>
                    {{ __('Web Settings') }}
                </a>
                <ul>
                    @usercan ('webSettings.view')
                        <li>
                            <a class="{{ Request::routeIs('business.website-settings.index') ? 'active' : '' }}"
                                href="{{ route('business.website-settings.index') }}">
                                {{ __('Manage Pages') }}
                            </a>
                        </li>
                    @endusercan

                    @usercan('terms.view')
                        <li>
                            <a class="{{ Request::routeIs('business.term-conditions.index') ? 'active' : '' }}"
                                href="{{ route('business.term-conditions.index') }}">
                                {{ __('Terms & Conditions') }}
                            </a>
                        </li>
                    @endusercan

                    @usercan('privacy.view')
                        <li>
                            <a class="{{ Request::routeIs('business.privacy-policy.index') ? 'active' : '' }}"
                                href="{{ route('business.privacy-policy.index') }}">
                                {{ __('Privacy & Policy') }}
                            </a>
                        </li>
                    @endusercan

                    @usercan('testimonials.view')
                        <li>
                            <a class="{{ Request::routeIs('business.testimonials.index', 'business.testimonials.create', 'business.testimonials.edit') ? 'active' : '' }}"
                                href="{{ route('business.testimonials.index') }}">
                                {{ __('Testimonials') }}
                            </a>
                        </li>
                    @endusercan

                    @usercan('blogs.view')
                        <li>
                            <a class="{{ Request::routeIs('business.blogs.index', 'business.blogs.create', 'business.blogs.edit', 'business.blogs.filter.comment') ? 'active' : '' }}"
                                href="{{ route('business.blogs.index') }}">
                                {{ __('Blogs') }}
                            </a>
                        </li>
                    @endusercan

                </ul>
            </li>
            @endusercanany
            @endif

            @usercanany(['salesReport.view', 'salesQuotationReport.view', 'purchaseReport.view', 'incomeReport.view', 'expenseReport.view', 'dueReport.view', 'vatReport.view', 'subscriptionReport.view'])
            <li
                class="dropdown {{ Request::routeIs('business.income-reports.index', 'business.expense-reports.index', 'business.sale-reports.index', 'business.purchase-reports.index', 'business.due-reports.index', 'business.supplier-due-reports.index', 'business.subscription-reports.index', 'business.vat-reports.index', 'business.quotation-reports.index', 'business.due-collect-reports.index') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon">
                        <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.83325 14.6867V11.3534" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round"/>
                            <path d="M10 14.6867V6.35339" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round"/>
                            <path d="M14.1667 14.6866V9.68665" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round"/>
                            <path d="M2.08325 10.5201C2.08325 6.78811 2.08325 4.92214 3.24262 3.76276C4.40199 2.60339 6.26797 2.60339 9.99992 2.60339C13.7318 2.60339 15.5978 2.60339 16.7573 3.76276C17.9166 4.92214 17.9166 6.78811 17.9166 10.5201C17.9166 14.252 17.9166 16.118 16.7573 17.2774C15.5978 18.4367 13.7318 18.4367 9.99992 18.4367C6.26797 18.4367 4.40199 18.4367 3.24262 17.2774C2.08325 16.118 2.08325 14.252 2.08325 10.5201Z" stroke="#5B5B5B" stroke-width="1.25" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    {{ __('Reports') }}</a>
                <ul>
                    @usercan('salesReport.view')
                    <li><a class="{{ Request::routeIs('business.sale-reports.index') && !request()->has('filter') ? 'active' : '' }}"
                            href="{{ route('business.sale-reports.index') }}">{{ __('All Order') }}</a>
                    </li>
                    @endusercan
                    @usercan('salesReport.view')
                    <li><a class="{{ (Request::routeIs('business.sale-reports.index') && request('filter') == 'todays') ? 'active' : '' }}"
                            href="{{ route('business.sale-reports.index', ['filter' => 'todays']) }}">{{ __('Today Order') }}</a>
                    </li>
                    @endusercan
                    @usercan('salesReport.view')
                    <li><a class="{{ (Request::routeIs('business.sale-reports.index') && request('filter') == 'yesterdays') ? 'active' : '' }}"
                            href="{{ route('business.sale-reports.index', ['filter' => 'yesterdays']) }}">{{ __('Yesterday Order') }}</a>
                    </li>
                    @endusercan
                    @usercan('salesReport.view')
                    <li><a class="{{ (Request::routeIs('business.sale-reports.index') && request('filter') == 'current_months') ? 'active' : '' }}"
                            href="{{ route('business.sale-reports.index', ['filter' => 'current_months']) }}">{{ __('Current Month') }}</a>
                    </li>
                    @endusercan
                    @usercan('salesQuotationReport.view')
                    <li><a class="{{ Request::routeIs('business.quotation-reports.index') ? 'active' : '' }}"
                            href="{{ route('business.quotation-reports.index') }}">{{ __('Quotation') }}</a>
                    </li>
                    @endusercan
                    @usercan('purchaseReport.view')
                    <li><a class="{{ Request::routeIs('business.purchase-reports.index') ? 'active' : '' }}"
                            href="{{ route('business.purchase-reports.index') }}">{{ __('Purchase') }}</a>
                    </li>
                    @endusercan
                    @usercan('vatReport.view')
                    <li><a class="{{ Request::routeIs('business.vat-reports.index') ? 'active' : '' }}"
                        href="{{ route('business.vat-reports.index') }}">{{ __('Vat Report') }}</a>
                    </li>
                    @endusercan
                    @usercan('incomeReport.view')
                    <li><a class="{{ Request::routeIs('business.income-reports.index') ? 'active' : '' }}"
                            href="{{ route('business.income-reports.index') }}">{{ __('Income') }}</a>
                    </li>
                    @endusercan
                    @usercan('expenseReport.view')
                    <li><a class="{{ Request::routeIs('business.expense-reports.index') ? 'active' : '' }}"
                            href="{{ route('business.expense-reports.index') }}">{{ __('Expense') }}</a>
                    </li>
                    @endusercan
                    @usercan('dueReport.view')
                    <li><a class="{{ Request::routeIs('business.due-reports.index') ? 'active' : '' }}"
                            href="{{ route('business.due-reports.index') }}">{{ __('Customer Due') }}</a>
                    </li>
                    @endusercan
                    @usercan('dueReport.view')
                    <li><a class="{{ Request::routeIs('business.supplier-due-reports.index') ? 'active' : '' }}"
                            href="{{ route('business.supplier-due-reports.index') }}">{{ __('Supplier Due') }}</a>
                    </li>
                    @endusercan
                    @usercan('dueCollectionReport.view')
                    <li><a class="{{ Request::routeIs('business.due-collect-reports.index') ? 'active' : '' }}"
                            href="{{ route('business.due-collect-reports.index') }}">{{ __('Due Collection') }}</a>
                    </li>
                    @endusercan
                    @usercan('subscriptionReport.view')
                    <li><a class="{{ Request::routeIs('business.subscription-reports.index') ? 'active' : '' }}"
                        href="{{ route('business.subscription-reports.index') }}">{{ __('Subscription') }}</a>
                    </li>
                    @endusercan
                </ul>
            </li>
            @endusercanany

            @usercanany(['settings.view'])
            <li class="{{ Request::routeIs('business.manage-settings.index', 'business.roles.index', 'business.roles.edit', 'business.roles.create', 'business.currencies.index', 'business.currencies.create', 'business.currencies.edit', 'business.notifications.index','business.settings.index', 'business.reservation-settting.index', 'business.order-type-setting.index', 'business.payment-gateway.index', 'business.delivery-charge.index', 'business.discount.index','business.cancel-reasons.index') ? 'active' : '' }}">
                <a href="{{ route('business.manage-settings.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.9173 10.5187C12.9173 12.1296 11.6115 13.4354 10.0007 13.4354C8.38982 13.4354 7.08398 12.1296 7.08398 10.5187C7.08398 8.90788 8.38982 7.60205 10.0007 7.60205C11.6115 7.60205 12.9173 8.90788 12.9173 10.5187Z" stroke="#5B5B5B" stroke-width="1.25"/>
                            <path d="M17.5085 12.2683C17.9434 12.1511 18.1609 12.0924 18.2468 11.9803C18.3327 11.8682 18.3327 11.6878 18.3327 11.327V9.71559C18.3327 9.35484 18.3327 9.17442 18.2468 9.06234C18.1608 8.95017 17.9434 8.8915 17.5085 8.77424C15.8832 8.33591 14.8659 6.63668 15.2854 5.02198C15.4008 4.57791 15.4584 4.35589 15.4033 4.22566C15.3483 4.09544 15.1903 4.00571 14.8741 3.82622L13.4368 3.0102C13.1267 2.83408 12.9716 2.74602 12.8324 2.76477C12.6932 2.78352 12.5362 2.9402 12.222 3.25353C11.006 4.46666 8.99402 4.46661 7.77797 3.25346C7.46387 2.94011 7.30683 2.78345 7.16762 2.76469C7.02842 2.74594 6.87332 2.834 6.56312 3.01011L5.12588 3.82615C4.80979 4.00561 4.65174 4.09535 4.59667 4.22555C4.54158 4.35575 4.59924 4.57781 4.71456 5.02192C5.13382 6.63667 4.11578 8.33595 2.4902 8.77425C2.05528 8.8915 1.83782 8.95017 1.75192 9.06225C1.66602 9.17442 1.66602 9.35484 1.66602 9.71559V11.327C1.66602 11.6878 1.66602 11.8682 1.75192 11.9803C1.83781 12.0924 2.05527 12.1511 2.4902 12.2683C4.11552 12.7067 5.13275 14.4059 4.71328 16.0206C4.59792 16.4647 4.54024 16.6867 4.59532 16.8169C4.6504 16.9472 4.80845 17.0369 5.12456 17.2163L6.56181 18.0324C6.87202 18.2085 7.02713 18.2966 7.16635 18.2778C7.30557 18.2591 7.46258 18.1023 7.77661 17.789C8.99327 16.5749 11.0067 16.5748 12.2234 17.7889C12.5374 18.1023 12.6944 18.259 12.8337 18.2778C12.9728 18.2965 13.128 18.2084 13.4382 18.0323L14.8754 17.2163C15.1916 17.0368 15.3497 16.9471 15.4047 16.8168C15.4598 16.6866 15.4021 16.4646 15.2867 16.0205C14.867 14.4059 15.8834 12.7068 17.5085 12.2683Z" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round"/>
                        </svg>
                    </span>
                    {{ __('Settings') }}
                </a>
            </li>
            @endusercanany

            @if (moduleCheck('RestaurantOnlineStore'))
            @usercanany(['newsletters.view'])
            <li class="{{ Request::routeIs('business.newsletters.index') ? 'active' : '' }}">
                <a href="{{ route('business.newsletters.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.9173 10.5187C12.9173 12.1296 11.6115 13.4354 10.0007 13.4354C8.38982 13.4354 7.08398 12.1296 7.08398 10.5187C7.08398 8.90788 8.38982 7.60205 10.0007 7.60205C11.6115 7.60205 12.9173 8.90788 12.9173 10.5187Z" stroke="#5B5B5B" stroke-width="1.25"/>
                            <path d="M17.5085 12.2683C17.9434 12.1511 18.1609 12.0924 18.2468 11.9803C18.3327 11.8682 18.3327 11.6878 18.3327 11.327V9.71559C18.3327 9.35484 18.3327 9.17442 18.2468 9.06234C18.1608 8.95017 17.9434 8.8915 17.5085 8.77424C15.8832 8.33591 14.8659 6.63668 15.2854 5.02198C15.4008 4.57791 15.4584 4.35589 15.4033 4.22566C15.3483 4.09544 15.1903 4.00571 14.8741 3.82622L13.4368 3.0102C13.1267 2.83408 12.9716 2.74602 12.8324 2.76477C12.6932 2.78352 12.5362 2.9402 12.222 3.25353C11.006 4.46666 8.99402 4.46661 7.77797 3.25346C7.46387 2.94011 7.30683 2.78345 7.16762 2.76469C7.02842 2.74594 6.87332 2.834 6.56312 3.01011L5.12588 3.82615C4.80979 4.00561 4.65174 4.09535 4.59667 4.22555C4.54158 4.35575 4.59924 4.57781 4.71456 5.02192C5.13382 6.63667 4.11578 8.33595 2.4902 8.77425C2.05528 8.8915 1.83782 8.95017 1.75192 9.06225C1.66602 9.17442 1.66602 9.35484 1.66602 9.71559V11.327C1.66602 11.6878 1.66602 11.8682 1.75192 11.9803C1.83781 12.0924 2.05527 12.1511 2.4902 12.2683C4.11552 12.7067 5.13275 14.4059 4.71328 16.0206C4.59792 16.4647 4.54024 16.6867 4.59532 16.8169C4.6504 16.9472 4.80845 17.0369 5.12456 17.2163L6.56181 18.0324C6.87202 18.2085 7.02713 18.2966 7.16635 18.2778C7.30557 18.2591 7.46258 18.1023 7.77661 17.789C8.99327 16.5749 11.0067 16.5748 12.2234 17.7889C12.5374 18.1023 12.6944 18.259 12.8337 18.2778C12.9728 18.2965 13.128 18.2084 13.4382 18.0323L14.8754 17.2163C15.1916 17.0368 15.3497 16.9471 15.4047 16.8168C15.4598 16.6866 15.4021 16.4646 15.2867 16.0205C14.867 14.4059 15.8834 12.7068 17.5085 12.2683Z" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round"/>
                        </svg>
                    </span>
                    {{ __('Newsletters') }}
                </a>
            </li>
            @endusercanany
            @endif

            {{-- @usercan('download-apk.view')
            <li>
                <a href="{{ get_option('general')['app_link'] ?? '' }}" target="_blank" class="active">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9.75v6.75m0 0-3-3m3 3 3-3m-8.25 6a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                        </svg>
                    </span>
                    {{ __('Download Apk') }}
                </a>
            </li>
            @endusercan --}}

            @usercan('subscriptions.view')
            <li>
                <div class="lg-sub-plan">
                    <div id="sidebar_plan" class=" sidebar-free-plan d-flex align-items-center justify-content-between p-3 flex-column">
                        <div class="text-center">
                            @if (plan_data() ?? false)

                                <h3>
                                    {{ plan_data()['plan']['subscriptionName'] ?? ''}}
                                </h3>
                                <h5>
                                    {{ __('Expired') }}: {{ formatted_date(plan_data()['will_expire'] ?? '') }}
                                </h5>
                                @else
                                <h3>{{ __('No Active Plan') }}</h3>
                                <h5>{{ __('Please subscribe to a plan') }}</h5>
                            @endif

                        </div>
                        @usercan('subscription.view')
                        <a href="{{ route('business.subscriptions.index') }}" class="btn upgrate-btn fw-bold">{{ __('Upgrade Now') }}</a>
                        @endusercan
                    </div>
                </div>
            </li>
            @endusercan
            <li>
                <div class="sub-plan">
                    <img src="{{ asset('assets/images/icons/plan.svg') }}">
                </div>
            </li>
        </ul>
    </div>
</nav>
