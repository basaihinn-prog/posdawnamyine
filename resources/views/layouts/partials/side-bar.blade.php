<div class="sidebar-container">
    <nav class="side-bar">
        <div class="side-bar-logo">
            <a href="{{ route('admin.dashboard.index') }}">
                <img src="{{ asset(get_option('general')['admin_logo'] ?? 'assets/images/logo/backend_logo.svg') }}" alt="Logo">
            </a>
            <button class="close-btn"><i class="fal fa-times"></i></button>
        </div>
        <div class="side-bar-manu">
            <ul>
                @can('dashboard-read')
                    <li class="{{ Request::routeIs('admin.dashboard.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard.index') }}" class="active">
                            <span class="sidebar-icon">

                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.63673 9.45808L3.33335 9.63225L3.71407 13.4171C3.92919 15.5557 4.03675 16.6249 4.75085 17.2708C5.46496 17.9167 6.53963 17.9167 8.68894 17.9167H11.3111C13.4604 17.9167 14.5351 17.9167 15.2492 17.2708C15.9633 16.6249 16.0709 15.5557 16.2859 13.4171L16.6667 9.63225L17.3634 9.45808C17.9334 9.31558 18.3334 8.80333 18.3334 8.21568C18.3334 7.79779 18.1294 7.40618 17.7871 7.16654L10.9558 2.38462C10.3819 1.98291 9.6181 1.98291 9.04427 2.38462L2.21293 7.16654C1.87058 7.40618 1.66669 7.79779 1.66669 8.21568C1.66669 8.80333 2.06663 9.31558 2.63673 9.45808Z" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10 14.1667C11.1506 14.1667 12.0834 13.2339 12.0834 12.0833C12.0834 10.9327 11.1506 10 10 10C8.84943 10 7.91669 10.9327 7.91669 12.0833C7.91669 13.2339 8.84943 14.1667 10 14.1667Z" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>

                            </span>
                            {{ __('Dashboard') }}
                        </a>
                    </li>
                @endcan
                @can('business-read')
                <li class="{{ Request::routeIs('admin.business.index', 'admin.business.create', 'admin.business.edit') ? 'active' : '' }}">
                    <a href="{{ route('admin.business.index') }}" class="active">
                        <span class="sidebar-icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_2338_722)">
                                <path d="M2.5 9.15591V12.9104C2.5 15.2702 2.5 16.4502 3.23223 17.1833C3.96447 17.9165 5.14297 17.9165 7.5 17.9165H12.5C14.857 17.9165 16.0355 17.9165 16.7677 17.1833C17.5 16.4502 17.5 15.2702 17.5 12.9104V9.15591" stroke="white" stroke-width="1.25"/>
                                <path d="M12.5 14.1473C11.9299 14.6533 11.0223 14.9807 9.99998 14.9807C8.97764 14.9807 8.07008 14.6533 7.5 14.1473" stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                                <path d="M14.8297 2.08579L5.12488 2.11002C3.6764 2.0354 3.30502 3.15216 3.30502 3.69807C3.30502 4.18633 3.24214 4.89812 2.35438 6.23592C1.46663 7.57372 1.53334 7.97114 2.03394 8.89725C2.44942 9.66592 3.50619 9.96617 4.0572 10.0167C5.80738 10.0565 6.65889 8.54309 6.65889 7.47936C7.52711 10.1521 9.99633 10.1521 11.0965 9.84642C12.1988 9.54025 13.1431 8.44425 13.3659 7.47936C13.4958 8.6785 13.8902 9.37817 15.0552 9.859C16.2621 10.357 17.2999 9.59584 17.8207 9.10784C18.3414 8.61992 18.6756 7.53668 17.7473 6.34609C17.1072 5.52501 16.8403 4.7515 16.7527 3.94981C16.7019 3.48529 16.6573 2.98614 16.331 2.6685C15.854 2.2043 15.1697 2.06345 14.8297 2.08579Z" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_2338_722">
                                <rect width="20" height="20" fill="white"/>
                                </clipPath>
                                </defs>
                                </svg>


                        </span>
                        {{ __('Store List') }}
                    </a>
                </li>
                @endcan

                @can('business-categories-read')
                  <li class="{{ Request::routeIs('admin.business-categories.index', 'admin.business-categories.create', 'admin.business-categories.edit') ? 'active' : '' }}">
                <a href="{{ route('admin.business-categories.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.16669 4.58331H17.5" stroke="white" stroke-width="1.25"
                                stroke-linecap="round" />
                            <path
                                d="M4.5 14.0772C5.38889 14.6725 5.83333 14.9702 5.83333 15.4167C5.83333 15.8632 5.38889 16.1608 4.5 16.7562C3.61111 17.3515 3.16667 17.6491 2.83333 17.4259C2.5 17.2027 2.5 16.6073 2.5 15.4167C2.5 14.226 2.5 13.6307 2.83333 13.4074C3.16667 13.1842 3.61111 13.4818 4.5 14.0772Z"
                                stroke="white" stroke-width="1.25" stroke-linecap="round" />
                            <path
                                d="M4.5 3.24386C5.38889 3.83918 5.83333 4.13684 5.83333 4.58333C5.83333 5.02982 5.38889 5.32748 4.5 5.92281C3.61111 6.51813 3.16667 6.81579 2.83333 6.59254C2.5 6.3693 2.5 5.77397 2.5 4.58333C2.5 3.39269 2.5 2.79737 2.83333 2.57412C3.16667 2.35087 3.61111 2.64853 4.5 3.24386Z"
                                stroke="white" stroke-width="1.25" stroke-linecap="round" />
                            <path d="M9.16669 10H17.5" stroke="white" stroke-width="1.25" stroke-linecap="round" />
                            <path d="M9.16669 15.4167H17.5" stroke="white" stroke-width="1.25"
                                stroke-linecap="round" />
                        </svg>

                    </span>
                    {{ __('Category List') }}
                </a>
               </li>
            @endcan
            @can('subscription-read')
            <li class="{{ Request::routeIs('admin.subscription.index') ? 'active' : '' }}">
                <a href="{{ route('admin.subscription.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.91663 7.80701C2.91663 4.91241 2.91663 3.46512 3.7709 2.56589C4.62517 1.66666 6.0001 1.66666 8.74996 1.66666H11.25C13.9998 1.66666 15.3747 1.66666 16.229 2.56589C17.0833 3.46512 17.0833 4.91241 17.0833 7.80701V12.193C17.0833 15.0876 17.0833 16.5348 16.229 17.4341C15.3747 18.3333 13.9998 18.3333 11.25 18.3333H8.74996C6.0001 18.3333 4.62517 18.3333 3.7709 17.4341C2.91663 16.5348 2.91663 15.0876 2.91663 12.193V7.80701Z" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M11.25 9.16666H14.1667" stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                        <path d="M5.83337 10C5.83337 10 6.25004 10 6.66671 10.8333C6.66671 10.8333 7.99024 8.75001 9.16671 8.33334" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M11.25 14.1667H14.1667" stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                        <path d="M6.66663 1.66666L6.73513 2.07766C6.90141 3.07536 6.98455 3.57421 7.33423 3.87043C7.68392 4.16666 8.18964 4.16666 9.20113 4.16666H10.7988C11.8103 4.16666 12.316 4.16666 12.6657 3.87043C13.0154 3.57421 13.0985 3.07536 13.2648 2.07766L13.3333 1.66666" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6.66663 14.1667H7.49996" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                    </span>
                    {{ __('Subscription List') }}
                </a>
            </li>
            @endcan
            @canany(['plans-read', 'plans-create'])
                <li
                    class="dropdown {{ Route::is('admin.plans.index', 'admin.plans.create', 'admin.plans.edit') ? 'active' : '' }}">
                    <a href="#">
                        <span class="sidebar-icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.08331 1.66666V8.74999M12.9166 1.66666V8.74999" stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                                <path d="M14.9302 1.67638H5.06983C4.31072 1.67638 3.3058 1.54519 2.78665 2.24668C2.5 2.63399 2.5 3.19444 2.5 4.31536C2.5 5.27103 2.5 5.74886 2.69328 6.15144C3.02339 6.83903 3.76148 7.13771 4.39409 7.46253L7.48442 9.04924C8.71883 9.68308 9.33608 9.99999 10 9.99999C10.6639 9.99999 11.2812 9.68308 12.5156 9.04924L15.6059 7.46253C16.2385 7.13771 16.9766 6.83903 17.3068 6.15144C17.5 5.74886 17.5 5.27103 17.5 4.31536C17.5 3.19444 17.5 2.63399 17.2133 2.24668C16.6942 1.54519 15.6893 1.67638 14.9302 1.67638Z" stroke="white" stroke-width="1.25"/>
                                <path d="M8.77435 11.3647C9.37319 11.0104 9.6726 10.8333 10 10.8333C10.3274 10.8333 10.6269 11.0104 11.2257 11.3647L12.059 11.8577C12.6806 12.2253 12.9914 12.4092 13.1624 12.7167C13.3334 13.0241 13.3334 13.3991 13.3334 14.1492V15.0175C13.3334 15.7676 13.3334 16.1426 13.1624 16.45C12.9914 16.7575 12.6806 16.9413 12.059 17.309L11.2257 17.802C10.6269 18.1563 10.3274 18.3333 10 18.3333C9.6726 18.3333 9.37319 18.1563 8.77435 17.802L7.94104 17.309C7.31945 16.9413 7.00866 16.7575 6.83768 16.45C6.66669 16.1426 6.66669 15.7676 6.66669 15.0175V14.1492C6.66669 13.3991 6.66669 13.0241 6.83768 12.7167C7.00866 12.4092 7.31945 12.2253 7.94104 11.8577L8.77435 11.3647Z" stroke="white" stroke-width="1.25" stroke-linejoin="round"/>
                                </svg>
                        </span>
                        {{ __('Subscription Plan') }}
                    </a>
                    <ul>
                        @can('plans-create')
                            <li><a class="{{ Route::is('admin.plans.create') ? 'active' : '' }}"
                                    href="{{ route('admin.plans.create') }}">{{ __('Create Plan') }}</a></li>
                        @endcan
                        @can('plans-read')
                            <li><a class="{{ Route::is('admin.plans.index', 'admin.plans.edit') ? 'active' : '' }}"
                                    href="{{ route('admin.plans.index') }}">{{ __('Manage Plans') }}</a></li>
                        @endcan
                    </ul>
                </li>
            @endcanany


            @if (moduleCheck('CustomDomainAddon'))
            <li class="{{ Request::routeIs('admin.domains.index', 'admin.domains.create', 'admin.domains.edit') ? 'active' : '' }}">
                <a href="{{ route('admin.domains.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 18.3333C5.39765 18.3333 1.66669 14.6023 1.66669 9.99999C1.66669 7.67287 2.62056 5.56855 4.15866 4.05667M10 18.3333C9.19752 17.7387 9.3256 17.0462 9.72819 16.3539C10.3472 15.2895 10.3472 15.2895 10.3472 13.8703C10.3472 12.4512 11.1905 11.7857 14.1667 12.3809C15.5039 12.6484 16.4784 10.8007 18.2144 11.4108M10 18.3333C14.1215 18.3333 17.5442 15.3413 18.2144 11.4108M4.15866 4.05667C4.8664 4.13136 5.26266 4.51052 5.92082 5.20596C7.17035 6.52626 8.41985 6.63642 9.25294 6.19632C10.5024 5.53618 9.45244 4.46691 10.9189 3.88581C11.818 3.52958 11.9894 2.59665 11.5639 1.81315M4.15866 4.05667C5.66248 2.5785 7.72476 1.66666 10 1.66666C10.5345 1.66666 11.0573 1.71698 11.5639 1.81315M18.2144 11.4108C18.2926 10.9522 18.3334 10.4809 18.3334 9.99999C18.3334 5.93213 15.4187 2.54503 11.5639 1.81315" stroke="#5B5B5B" stroke-width="1.25" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    {{ __('Domains List') }}
                </a>
            </li>
            @endif

                @canany(['subscription-reports-read','manual-payment-reports-read','active-store-reports-read','expired-store-reports-read'])
                    <li class="dropdown {{ Route::is('admin.subscription-reports.index','admin.manual-payments.index', 'admin.expired-business.index', 'admin.active-stores.index') ? 'active' : '' }}">
                        <a href="#">
                            <span class="sidebar-icon">
                                <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.83331 14.6867V11.3533" stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                                    <path d="M10 14.6867V6.35333" stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                                    <path d="M14.1667 14.6866V9.68665" stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                                    <path d="M2.08331 10.52C2.08331 6.78805 2.08331 4.92207 3.24268 3.7627C4.40205 2.60333 6.26803 2.60333 9.99998 2.60333C13.7319 2.60333 15.5979 2.60333 16.7573 3.7627C17.9166 4.92207 17.9166 6.78805 17.9166 10.52C17.9166 14.2519 17.9166 16.1179 16.7573 17.2773C15.5979 18.4367 13.7319 18.4367 9.99998 18.4367C6.26803 18.4367 4.40205 18.4367 3.24268 17.2773C2.08331 16.1179 2.08331 14.2519 2.08331 10.52Z" stroke="white" stroke-width="1.25" stroke-linejoin="round"/>
                                    </svg>
                            </span>
                            {{ __('Reports') }}
                        </a>
                        <ul>
                            @can('subscription-reports-read')
                                <li><a class="{{ Route::is('admin.subscription-reports.index') ? 'active' : '' }}"
                                        href="{{ route('admin.subscription-reports.index') }}">{{ __('Subscription Report') }}</a>
                                </li>
                            @endcan

                            @can('manual-payment-reports-read')
                            <li><a class="{{ Route::is('admin.manual-payments.index') ? 'active' : '' }}"
                                href="{{ route('admin.manual-payments.index') }}">{{ __('Manual Payment') }}</a>
                            </li>
                            @endcan

                            @can('active-store-reports-read')
                            <li><a class="{{ Route::is('admin.active-stores.index') ? 'active' : '' }}"
                                href="{{ route('admin.active-stores.index') }}">{{ __('Active Store') }}</a>
                            </li>
                            @endcan

                            @can('expired-store-reports-read')
                            <li><a class="{{ Route::is('admin.expired-business.index') ? 'active' : '' }}"
                                href="{{ route('admin.expired-business.index') }}">{{ __('Expired Store') }}</a>
                            </li>
                            @endcan

                        </ul>
                    </li>
                @endcanany

                    <li class="dropdown {{ Route::is('admin.messages.index') ? 'active' : '' }}">
                        <a href="#">
                            <span class="sidebar-icon mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                </svg>
                            </span>
                            {{ __('Messages') }}
                        </a>
                        <ul>
                            <li><a class="{{ Route::is('admin.messages.index') ? 'active' : '' }}"
                                    href="{{ route('admin.messages.index') }}">{{ __('Contact Messages') }}</a>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown {{ Route::is('admin.term-conditions.index', 'admin.privacy-policy.index', 'admin.about-us.index', 'admin.contact-us.index', 'admin.testimonials.index', 'admin.testimonials.edit', 'admin.testimonials.create', 'admin.features.index', 'admin.features.create', 'admin.features.edit', 'admin.interfaces.index', 'admin.interfaces.create', 'admin.interfaces.edit', 'admin.blogs.index', 'admin.blogs.create', 'admin.blogs.edit', 'admin.blogs.filter.comment', 'admin.website-settings.index', 'admin.faqs.index', 'admin.faqs.create', 'admin.faqs.edit') ? 'active' : '' }}">
                        <a href="#">
                            <span class="sidebar-icon mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                                </svg>
                            </span>
                            {{ __('Front CMS') }}
                        </a>
                        <ul>
                             @can('manage-pages-read')
                            <li>
                                <a class="{{ Request::routeIs('admin.website-settings.index') ? 'active' : '' }}"
                                    href="{{ route('admin.website-settings.index') }}">
                                    {{ __('Manage Pages') }}
                                </a>
                            </li>
                            @endcan

                            <li>
                                <a class="{{ Request::routeIs('admin.term-conditions.index') ? 'active' : '' }}"
                                    href="{{ route('admin.term-conditions.index') }}">
                                    {{ __('Terms & Conditions') }}
                                </a>
                            </li>

                            <li>
                                <a class="{{ Request::routeIs('admin.privacy-policy.index') ? 'active' : '' }}"
                                    href="{{ route('admin.privacy-policy.index') }}">
                                    {{ __('Privacy & Policy') }}
                                </a>
                            </li>

                                <li>
                                    <a class="{{ Request::routeIs('admin.testimonials.index', 'admin.testimonials.create', 'admin.testimonials.edit') ? 'active' : '' }}"
                                        href="{{ route('admin.testimonials.index') }}">
                                        {{ __('Testimonials') }}
                                    </a>
                                </li>

                                <li>
                                    <a class="{{ Request::routeIs('admin.features.index', 'admin.features.create', 'admin.features.edit') ? 'active' : '' }}"
                                        href="{{ route('admin.features.index') }}">
                                        {{ __('Features') }}
                                    </a>
                                </li>

                                <li>
                                    <a class="{{ Request::routeIs('admin.interfaces.index', 'admin.interfaces.create', 'admin.interfaces.edit') ? 'active' : '' }}"
                                        href="{{ route('admin.interfaces.index') }}">
                                        {{ __('Interface') }}
                                    </a>
                                </li>

                                <li>
                                    <a class="{{ Request::routeIs('admin.blogs.index', 'admin.blogs.create', 'admin.blogs.edit', 'admin.blogs.filter.comment') ? 'active' : '' }}"
                                        href="{{ route('admin.blogs.index') }}">
                                        {{ __('Blogs') }}
                                    </a>
                                </li>

                                <li>
                                    <a class="{{ Request::routeIs('admin.faqs.index', 'admin.faqs.create', 'admin.faqs.edit') ? 'active' : '' }}"
                                        href="{{ route('admin.faqs.index') }}">
                                        {{ __('Faq') }}
                                    </a>
                                </li>

                        </ul>
                    </li>

                @can('users-read')
                    <li
                        class="dropdown {{ Request::routeIs('admin.users.index', 'admin.users.create', 'admin.users.edit') ? 'active' : '' }}">
                        <a href="#">
                            <span class="sidebar-icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.5134 16.6667H15.9219C16.8801 16.6667 17.6423 16.2301 18.3266 15.6197C20.065 14.0688 15.9784 12.5 14.5834 12.5M12.9167 4.22396C13.1059 4.18643 13.3024 4.16666 13.504 4.16666C15.0206 4.16666 16.25 5.28595 16.25 6.66666C16.25 8.04736 15.0206 9.16666 13.504 9.16666C13.3024 9.16666 13.1059 9.14691 12.9167 9.10932" stroke="white" stroke-width="1.25" stroke-linecap="round"/>
                                    <path d="M3.73443 13.426C2.75195 13.9525 0.175949 15.0276 1.7449 16.3728C2.51133 17.03 3.36493 17.5 4.4381 17.5H10.5619C11.6351 17.5 12.4887 17.03 13.2551 16.3728C14.8241 15.0276 12.2481 13.9525 11.2656 13.426C8.96167 12.1913 6.03833 12.1913 3.73443 13.426Z" stroke="white" stroke-width="1.25"/>
                                    <path d="M10.8334 6.24999C10.8334 8.09094 9.34094 9.58332 7.50002 9.58332C5.65907 9.58332 4.16669 8.09094 4.16669 6.24999C4.16669 4.40904 5.65907 2.91666 7.50002 2.91666C9.34094 2.91666 10.8334 4.40904 10.8334 6.24999Z" stroke="white" stroke-width="1.25"/>
                                    </svg>
                            </span>
                            {{ __('Staff Manage') }} </a>
                        <ul>
                            <li><a class="{{ Request::routeIs('admin.users.create') ? 'active' : '' }}"
                                    href="{{ route('admin.users.create') }}">{{ __('Create Staff') }}</a></li>

                            <li><a class="{{ Request::routeIs('admin.users.index', 'admin.users.edit') ? 'active' : '' }}"
                                    href="{{ route('admin.users.index') }}">{{ __('Manage Staff') }}</a></li>
                        </ul>
                    </li>
                @endcan

                @canany(['roles-read', 'permissions-read'])
                    <li
                        class="dropdown {{ Request::routeIs('admin.roles.index', 'admin.roles.create', 'admin.roles.edit', 'admin.permissions.index') ? 'active' : '' }}">
                        <a href="#">
                            <span class="sidebar-icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_2632_6629)">
                                        <path d="M10 18.3333L8.33335 13.3333H1.66669L3.33335 18.3333H10ZM10 18.3333H13.3334"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M9.99998 10.8333V10.4166C9.99998 8.84531 9.99998 8.05962 9.51181 7.57147C9.02365 7.08331 8.238 7.08331 6.66665 7.08331C5.0953 7.08331 4.30962 7.08331 3.82147 7.57147C3.33331 8.05962 3.33331 8.84531 3.33331 10.4166V10.8333"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M15.8333 10.8334C15.8333 11.7539 15.0872 12.5 14.1667 12.5C13.2462 12.5 12.5 11.7539 12.5 10.8334C12.5 9.91285 13.2462 9.16669 14.1667 9.16669C15.0872 9.16669 15.8333 9.91285 15.8333 10.8334Z"
                                            stroke="white" stroke-width="1.5" />
                                        <path
                                            d="M8.33333 3.33335C8.33333 4.25383 7.58714 5.00002 6.66667 5.00002C5.74619 5.00002 5 4.25383 5 3.33335C5 2.41288 5.74619 1.66669 6.66667 1.66669C7.58714 1.66669 8.33333 2.41288 8.33333 3.33335Z"
                                            stroke="white" stroke-width="1.5" />
                                        <path
                                            d="M11.6667 14.5833H16.6667C17.5872 14.5833 18.3334 15.3295 18.3334 16.25V16.6666C18.3334 17.5871 17.5872 18.3333 16.6667 18.3333H15.8334"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_2632_6629">
                                            <rect width="20" height="20" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>

                            </span>
                            {{ __('Roles & Permissions') }}
                        </a>
                        <ul>
                            @can('roles-read')
                                <li>
                                    <a class="{{ Request::routeIs('admin.roles.index', 'admin.roles.create', 'admin.roles.edit') ? 'active' : '' }}"
                                        href="{{ route('admin.roles.index') }}">
                                        {{ __('Roles') }}
                                    </a>
                                </li>
                            @endcan

                            @can('permissions-read')
                                <li>
                                    <a class="{{ Request::routeIs('admin.permissions.index') ? 'active' : '' }}"
                                        href="{{ route('admin.permissions.index') }}">
                                        {{ __('Permissions') }}
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @can('subscription-read')
                <li class="{{ Request::routeIs('admin.addons.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.addons.index') }}" class="active">
                        <span class="sidebar-icon">
                       <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_6720_71975)">
                        <path d="M8.33341 1.66675C6.95271 1.66675 5.83341 2.78604 5.83341 4.16675V5.00008H2.50008C2.03985 5.00008 1.66675 5.37318 1.66675 5.83341V9.16675H2.50008C3.88079 9.16675 5.00008 10.286 5.00008 11.6667C5.00008 13.0475 3.88079 14.1667 2.50008 14.1667H1.66675V17.5001C1.66675 17.9603 2.03985 18.3334 2.50008 18.3334H5.83341V17.5001C5.83341 16.1193 6.95271 15.0001 8.33341 15.0001C9.71416 15.0001 10.8334 16.1193 10.8334 17.5001V18.3334H14.1667C14.627 18.3334 15.0001 17.9603 15.0001 17.5001V14.1667H15.8334C17.2142 14.1667 18.3334 13.0475 18.3334 11.6667C18.3334 10.286 17.2142 9.16675 15.8334 9.16675H15.0001V5.83341C15.0001 5.37318 14.627 5.00008 14.1667 5.00008H10.8334V4.16675C10.8334 2.78604 9.71416 1.66675 8.33341 1.66675Z" stroke="#5B5B5B" stroke-width="1.5" stroke-linejoin="round"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_6720_71975">
                        <rect width="20" height="20" fill="white"/>
                        </clipPath>
                        </defs>
                        </svg>
                        </span>
                        {{ __('Add-ons') }}
                        <sup class="badge bg-warning position-absolute side-bar-addon">{{ __('New') }}</sup>
                    </a>
                </li>
               @endcan

                @canany(['settings-read', 'notifications-read', 'currencies-read', 'gateways-read'])
                    <li
                        class="dropdown {{ Request::routeIs('admin.settings.index', 'admin.notifications.index', 'admin.system-settings.index', 'admin.currencies.index', 'admin.currencies.create', 'admin.currencies.edit', 'admin.sms-settings.index', 'admin.gateways.index', 'admin.domain-settings.index') ? 'active' : '' }}">
                        <a href="#">
                            <span class="sidebar-icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12.9166 9.99998C12.9166 11.6108 11.6108 12.9166 9.99998 12.9166C8.38915 12.9166 7.08331 11.6108 7.08331 9.99998C7.08331 8.38915 8.38915 7.08331 9.99998 7.08331C11.6108 7.08331 12.9166 8.38915 12.9166 9.99998Z"
                                        stroke="white" stroke-width="1.5" />
                                    <path
                                        d="M17.5092 11.7471C17.9441 11.6298 18.1616 11.5712 18.2474 11.459C18.3334 11.3469 18.3334 11.1665 18.3334 10.8058V9.19435C18.3334 8.8336 18.3334 8.65318 18.2474 8.5411C18.1615 8.42893 17.9441 8.37026 17.5092 8.253C15.8839 7.81467 14.8666 6.11544 15.2861 4.50074C15.4014 4.05667 15.4591 3.83465 15.404 3.70442C15.3489 3.5742 15.1909 3.48446 14.8748 3.30498L13.4375 2.48896C13.1274 2.31284 12.9723 2.22478 12.8331 2.24353C12.6939 2.26228 12.5369 2.41896 12.2227 2.73229C11.0067 3.94541 8.99469 3.94536 7.77864 2.73221C7.46455 2.41887 7.3075 2.26221 7.16829 2.24345C7.02909 2.2247 6.874 2.31276 6.5638 2.48887L5.12655 3.30491C4.81046 3.48437 4.65241 3.57411 4.59734 3.70431C4.54225 3.83451 4.59991 4.05657 4.71523 4.50068C5.1345 6.11543 4.11645 7.81471 2.49087 8.25301C2.05595 8.37026 1.8385 8.42893 1.75259 8.54101C1.66669 8.65318 1.66669 8.8336 1.66669 9.19435V10.8058C1.66669 11.1665 1.66669 11.3469 1.75259 11.459C1.83848 11.5712 2.05595 11.6298 2.49087 11.7471C4.11619 12.1854 5.13342 13.8847 4.71395 15.4993C4.5986 15.9434 4.54091 16.1654 4.59599 16.2957C4.65107 16.4259 4.80912 16.5157 5.12523 16.6951L6.56248 17.5112C6.8727 17.6873 7.0278 17.7753 7.16702 17.7566C7.30624 17.7378 7.46325 17.5811 7.77728 17.2678C8.99394 16.0537 11.0074 16.0536 12.2241 17.2677C12.5381 17.5811 12.6951 17.7378 12.8344 17.7565C12.9735 17.7753 13.1287 17.6872 13.4389 17.5111L14.8761 16.695C15.1923 16.5156 15.3504 16.4258 15.4054 16.2956C15.4604 16.1653 15.4028 15.9433 15.2874 15.4993C14.8677 13.8847 15.8841 12.1855 17.5092 11.7471Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" />
                                </svg>

                            </span>
                            {{ __('Settings') }}
                        </a>
                        <ul>
                            @can('currencies-read')
                                <li><a class="{{ Request::routeIs('admin.currencies.index', 'admin.currencies.create', 'admin.currencies.edit') ? 'active' : '' }}"
                                        href="{{ route('admin.currencies.index') }}">{{ __('Currencies') }}</a></li>
                            @endcan

                            @can('notifications-read')
                                <li>
                                    <a class="{{ Request::routeIs('admin.notifications.index') ? 'active' : '' }}"
                                        href="{{ route('admin.notifications.index') }}">
                                        {{ __('Notifications') }}
                                    </a>
                                </li>
                            @endcan

                            @can('gateways-read')
                                <li>
                                    <a class="{{ Request::routeIs('admin.gateways.index') ? 'active' : '' }}"
                                        href="{{ route('admin.gateways.index') }}">
                                        {{ __('Payment Gateway') }}
                                    </a>
                                </li>
                            @endcan

                            @can('settings-read')
                                <li>
                                    <a class="{{ Request::routeIs('admin.system-settings.index') ? 'active' : '' }}"
                                        href="{{ route('admin.system-settings.index') }}">{{ __('System Settings') }}</a>
                                </li>
                                <li>
                                    <a class="{{ Request::routeIs('admin.settings.index') ? 'active' : '' }}"
                                        href="{{ route('admin.settings.index') }}">{{ __('General Settings') }}</a>
                                </li>
                            @endcan
                            @if (moduleCheck('CustomDomainAddon'))
                                <li>
                                    <a class="{{ Request::routeIs('admin.domain-settings.index') ? 'active' : '' }}"
                                        href="{{ route('admin.domain-settings.index') }}">{{ __('Domain Settings') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endcanany
            </ul>
        </div>

        <div class="text-center mt-4 mb-2 version-text">{{ __('Version') }} : <span class="fw-bold">{{ !empty(get_option('version')) ? get_option('version') : '3.0' }}</span></div>
    </nav>
</div>
