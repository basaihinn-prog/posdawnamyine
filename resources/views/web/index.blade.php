@extends('layouts.web.master')

@section('title')
    {{ __('Home') }}
@endsection

@section('main_content')
    <section class="home-banner-section">
        <div class="container">
             {{-- hero-container --}}
            <div class="row align-items-center pb-5 py-lg-5">
                <div class="col-lg-6 order-2 order-lg-1 mt-lg-0 hero-content">
                    <div data-aos="fade-right" class="banner-content">
                        <h1 data-aos="fade-up" data-aos-delay="100">
                            {{ $page_data['headings']['slider_title'] ?? '' }}
                            <span
                                data-aos="fade-up"
                                data-aos-delay="300"
                                data-typer-targets='{"targets": [
                                    @foreach ($page_data['headings']['silder_shop_text'] ?? [] as $key => $shop)
                                        "{{ $shop }}"@if (!$loop->last),@endif
                                    @endforeach
                                ]}'>
                            </span>
                        </h1>

                        <p data-aos="fade-up" data-aos-delay="500">
                            {{ $page_data['headings']['slider_description'] ?? '' }}
                        </p>

                        <div class="demo-btn-group mb-3" data-aos="fade-up" data-aos-delay="700">
                            <a href="{{ url($page_data['headings']['slider_btn1_link'] ?? '') }}"
                            class="try-demo-button custom-primary-btn">
                                {{ $page_data['headings']['slider_btn1'] ?? '' }}
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12.5 9.99996C12.5 10.4602 12.1269 10.8333 11.6667 10.8333H3.33333C2.87308 10.8333 2.5 10.4602 2.5 9.99996C2.5 9.53971 2.87308 9.16663 3.33333 9.16663H11.6667C12.1269 9.16663 12.5 9.53971 12.5 9.99996Z" fill="#fff"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M11.2734 5.93173C11.0024 6.07674 10.8333 6.35913 10.8333 6.66647V13.3332C10.8333 13.6404 11.0024 13.9228 11.2733 14.0678C11.5443 14.2128 11.8731 14.197 12.1288 14.0265L17.1288 10.6935C17.3607 10.539 17.4999 10.2787 17.4999 10.0002C17.5 9.7215 17.3607 9.46133 17.1289 9.30675L12.1289 5.97311C11.8732 5.80262 11.5444 5.78672 11.2734 5.93173Z" fill="#fff"/>
                                </svg>
                            </a>


                            <a class="d-flex align-items-center justify-content-center gap-2"  data-bs-toggle="modal" data-bs-target="#watch-video-modal"  href="#">
                                <div  class="video-button">
                                <span class="pulse-ring"></span>
                                <span class="pulse-ring"></span>
                                <span class="pulse-ring"></span>

                                <span class="play-icon">
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.58203 4.58167C4.58203 3.7905 5.45728 3.31266 6.12279 3.74049L16.1069 10.1588C16.7192 10.5525 16.7192 11.4475 16.1069 11.8412L6.12279 18.2595C5.45728 18.6873 4.58203 18.2095 4.58203 17.4183V4.58167Z" fill="white"/>
                                    </svg>
                                </span>

                                </div>
                                <p class="hero-watch m-0">{{ $page_data['headings']['slider_btn2'] ?? '' }}</p>
                            </a>
                        </div>
                    </div>

                </div>
                <div class="col-lg-6 order-1 order-lg-2 p-0">
                    <div class="banner-img text-center  move-image">
                        <div data-aos="fade-up" data-aos-delay="100" class="position-relative">
                            <img src="{{ asset('assets/images/icons/hero-bg-shape.svg') }}"
                                alt="banner-img" class="hero-bg-shape" />
                            <img src="{{ asset_with_fallback($page_data['slider_image'] ?? null, 'assets/web/images/banner/hero-banner.svg') }}"
                            alt="banner-img" class="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal modal-custom-design" id="watch-video-modal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe width="100%" height="400px" src="{{ $page_data['headings']['slider_btn2_link'] ?? '' }}"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

    {{-- Feature Code Start --}}

    @include('web.components.feature')

    {{-- Interface Code Start --}}

    <section>
        <div class="container">
            <div  class="section-title text-center">
                <h2 data-aos="fade-up" data-aos-delay="100">{{ $page_data['headings']['interface_start_title'] ?? '' }} <span class="highlight-title">{{ $page_data['headings']['interface_end_title'] ?? '' }}</span> </h2>
                <p data-aos="fade-up" data-aos-delay="300" class="max-w-600 mx-auto section-description">
                    {{ $page_data['headings']['interface_description'] ?? '' }}
                </p>
            </div>
                <div class="swiper mySwiper app-slider-section">
                    <div class="swiper-wrapper">
                        @foreach ($interfaces as $interface)
                            <div class="swiper-slide d-flex align-items-center justify-content-center p-2">
                                <img src="{{ asset_with_fallback($interface->image, 'assets/web/images/banner/app-banner.svg') }}" alt="phone" />
                            </div>
                        @endforeach
                    </div>

                    <div class="swiper-button-prev custom-nav">
                      <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19.4979 7.74975C19.4979 8.16375 19.1619 8.49975 18.7479 8.49975H2.559L8.2789 14.2198C8.5719 14.5128 8.5719 14.9877 8.2789 15.2807C8.1329 15.4267 7.9409 15.5008 7.7489 15.5008C7.5569 15.5008 7.3649 15.4277 7.2189 15.2807L0.2189 8.28075C0.1499 8.21175 0.095 8.12885 0.057 8.03685C-0.019 7.85385 -0.019 7.64685 0.057 7.46385C0.095 7.37185 0.1499 7.28875 0.2189 7.21975L7.2189 0.21975C7.5119 -0.07325 7.9869 -0.07325 8.2799 0.21975C8.5729 0.51275 8.5729 0.987751 8.2799 1.28075L2.5599 7.00075H18.7479C19.1619 6.99975 19.4979 7.33575 19.4979 7.74975Z" fill="#979797"/>
                        </svg>
                    </div>

                    <div class="swiper-button-next custom-nav">
                        <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.25013 7.99978C0.25013 8.41378 0.58613 8.74978 1.00013 8.74978H17.1891L11.4691 14.4698C11.1761 14.7628 11.1761 15.2378 11.4691 15.5308C11.6151 15.6768 11.8072 15.7508 11.9992 15.7508C12.1912 15.7508 12.3832 15.6778 12.5292 15.5308L19.5292 8.53079C19.5982 8.46179 19.653 8.37889 19.691 8.28689C19.767 8.10389 19.767 7.89689 19.691 7.71389C19.653 7.62189 19.5982 7.53875 19.5292 7.46975L12.5292 0.46975C12.2362 0.17675 11.7611 0.17675 11.4681 0.46975C11.1751 0.76275 11.1751 1.23779 11.4681 1.53079L17.1881 7.25076H1.00013C0.58613 7.24976 0.25013 7.58578 0.25013 7.99978Z" fill="#979797"/>
                        </svg>
                    </div>
                </div>
        </div>
    </section>


    <div class="get-app-container">
        <div class="section-title m-0 container">
            <h2 data-aos="fade-up" data-aos-delay="100"> {{ $page_data['headings']['get_app_title'] ?? '' }} </h2>
            <p data-aos="fade-up" data-aos-delay="300"> {{ $page_data['headings']['get_app_description'] ?? '' }} </p>
        <div data-aos="fade-up" data-aos-delay="500" class="google-app-store">
            <a href="{{ url($page_data['headings']['get_app_apple_store_link'] ?? '') }}">
                <img class="" src="{{ asset_with_fallback($page_data['get_app_apple_store_img'] ?? null, 'assets/web/images/banner/app-store.svg') }}" alt="" srcset="">
            </a>
            <a href="{{ url($page_data['headings']['get_app_play_store_link'] ?? '') }}">
                <img class="" src="{{ asset_with_fallback($page_data['get_app_play_store_img'] ?? null, 'assets/web/images/banner/google-play.svg') }}" alt="" srcset="">
            </a>
        </div>
        </div>
    </div>

    {{-- Watch demo Code Start --}}
    <section class="watch-demo-section watch-demo-two bg-FFFFFF">
        <div class="container">
            <div class="row align-items-center justify-content-center g-5">
                <div class="col-lg-5 m-0">
                    <div data-aos="fade-right" data-aos-delay="100" class="video-wrapper ">
                        <img src="{{ asset_with_fallback($page_data['watch_image'] ?? null, 'assets/web/images/banner/app-banner.svg') }}" alt="watch" />
                        <a href="#" class="play-btn" data-bs-toggle="modal" data-bs-target="#play-video-modal">
                            <i class="fa fa-play" aria-hidden="true"></i>
                        </a>
                       <a type="button" data-bs-toggle="modal" data-bs-target="#play-video-modal" class="video-button position-absolute top-50 start-50 translate-middle">
                                <span class="pulse-ring-2"></span>
                                <span class="pulse-ring-2"></span>
                                <span class="pulse-ring-2"></span>
                                <span class="play-icon-2">
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.58203 4.58167C4.58203 3.7905 5.45728 3.31266 6.12279 3.74049L16.1069 10.1588C16.7192 10.5525 16.7192 11.4475 16.1069 11.8412L6.12279 18.2595C5.45728 18.6873 4.58203 18.2095 4.58203 17.4183V4.58167Z" fill="white"/>
                                    </svg>
                                </span>
                        </a>
                    </div>
                </div>
                <div class="section-title m-0 text-start col-lg-7 watch-video-content">
                    <h2 data-aos="fade-up" data-aos-delay="100">
                        {{ $page_data['headings']['watch_start_title'] ?? '' }}
                        <span class="highlight-title">{{ $page_data['headings']['watch_mid_title'] ?? '' }}</span>
                        {{ $page_data['headings']['watch_end_title'] ?? '' }}
                    </h2>
                    <p data-aos="fade-up" data-aos-delay="300" class="section-description mt-2">
                        {{ $page_data['headings']['watch_description'] ?? '' }}
                    </p>
                    <a data-aos="fade-up" data-aos-delay="500" href="{{ $page_data['headings']['watch_btn_link'] ?? '' }}" class="custom-btn custom-primary-btn mt-4"
                        data-bs-toggle="modal" data-bs-target="#play-video-modal">
                        {{ $page_data['headings']['watch_btn_text'] ?? 'Watch Video' }}
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 16.5C13.1421 16.5 16.5 13.1421 16.5 9C16.5 4.85786 13.1421 1.5 9 1.5C4.85786 1.5 1.5 4.85786 1.5 9C1.5 13.1421 4.85786 16.5 9 16.5Z" stroke="white"/>
                            <path d="M7.125 8.39985V9.60015C7.125 10.7396 7.125 11.3093 7.46682 11.5397C7.80862 11.7699 8.2761 11.5151 9.21112 11.0056L10.3123 10.4054C11.4374 9.79215 12 9.48555 12 9C12 8.51445 11.4374 8.20785 10.3123 7.59465L9.21112 6.99446C8.2761 6.48488 7.80862 6.23009 7.46682 6.46037C7.125 6.69065 7.125 7.26038 7.125 8.39985Z" fill="white"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Modal -->
    @include('web.components.play-video')

    {{-- Pricing-Plan-section demo Code Start --}}
    @include('web.components.plan')

    {{-- faq secton code start --}}

    <div class="faq-main-container">
        <div class="container">
            <div class="section-title ">
                <h2 data-aos="fade-up" data-aos-delay="100"> {{ $page_data['headings']['faq_title_one'] ?? '' }} </h2>
                <p data-aos="fade-up" data-aos-delay="300" class="mt-2">{{ $page_data['headings']['faq_description_one'] ?? '' }}</p>
            </div>
            <div class="faq-section-container">
                <div class="">
                    <div class="faq-get-in-content">
                        <h4>{{ $page_data['headings']['faq_title_two'] ?? '' }}</h4>
                        <p>{{ $page_data['headings']['faq_description_two'] ?? '' }}</p>
                        <a href="{{ url($page_data['headings']['faq_btn_link'] ?? '') }}" class="get-in-touch-btn">
                            {{ $page_data['headings']['faq_btn_text'] ?? 'Get in Touch' }}
                        </a>
                    </div>
                </div>
                <div class="">
                    <div class="custom-accordion">
                        @foreach ($faqs as $index => $faq)
                            <div class="custom-accordion-item {{ $index == 0 ? 'active' : '' }}">
                                <div class="custom-accordion-header">
                                    {{ $faq->question }}
                                    <span class="custom-accordion-icon"></span>
                                </div>
                                <div class="custom-accordion-content">
                                    {{ $faq->answer }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- faq secton code end --}}

    {{-- Testimonial Section Start --}}
    <section class="customer-section">
        <div class="container mb-4">
            <div class="section-title text-center">
                <h2 data-aos="fade-up" data-aos-delay="100">{{ $page_data['headings']['testimonial_start_title'] ?? '' }} <span class="highlight-title">{{ $page_data['headings']['testimonial_end_title'] ?? '' }}</span> </h2>
            </div>

            <div class="customer-slider-section">
              <div class="swiper testimonialSwiper">
                    <div class="swiper-wrapper">
                        @foreach ($testimonials as $index => $testimonial)
                            <div class="swiper-slide" data-aos="zoom-in" data-aos-delay="{{ $index * 100 }}">
                                <div class="customer-card">
                                    <div class="w-100 pt-3 d-flex flex-column">
                                        <ul class="d-flex align-items-center gap-2">
                                            @for ($i = 0; $i < 5; $i++)
                                                @if($testimonial->star > $i)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-muted"></i>
                                                @endif
                                            @endfor
                                        </ul>
                                    </div>
                                    <p>{{ $testimonial->text }}</p>

                                    <div>
                                        <img class="serperate" src="{{ asset('assets/images/admin-dashboard/seperate.svg') }}" alt="">
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <img class="profile-img" src="{{ asset_with_fallback($testimonial->client_image, 'assets/images/profile/avatar.svg') }}" alt="">
                                        <div>
                                            <h5 class="m-0">{{ $testimonial->client_name }}</h5>
                                            <small>{{ $testimonial->work_at }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Blogs Section Code Start --}}

    <section class="blogs-section ">
        <div class="container">
            <div class="section-title d-flex align-items-center justify-content-between flex-wrap">
                <h2 data-aos="fade-up" data-aos-delay="100">{{ $page_data['headings']['blog_start_title'] ?? '' }} <span class="highlight-title">{{ $page_data['headings']['blog_end_title'] ?? '' }}</span> </h2>
                <a data-aos="fade-up" data-aos-delay="300" href="{{ url($page_data['headings']['blog_view_all_btn_link'] ?? '') }}"
                    class="custom-btn custom-primary-btn">
                    {{ $page_data['headings']['blog_view_all_btn_text'] ?? '' }}
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.5 9.99996C12.5 10.4602 12.1269 10.8333 11.6667 10.8333H3.33333C2.87308 10.8333 2.5 10.4602 2.5 9.99996C2.5 9.53971 2.87308 9.16663 3.33333 9.16663H11.6667C12.1269 9.16663 12.5 9.53971 12.5 9.99996Z" fill="white"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.2734 5.93173C11.0024 6.07674 10.8333 6.35913 10.8333 6.66647V13.3332C10.8333 13.6404 11.0024 13.9228 11.2733 14.0678C11.5443 14.2128 11.8731 14.197 12.1288 14.0265L17.1288 10.6935C17.3607 10.539 17.4999 10.2787 17.4999 10.0002C17.5 9.7215 17.3607 9.46133 17.1289 9.30675L12.1289 5.97311C11.8732 5.80262 11.5444 5.78672 11.2734 5.93173Z" fill="white"/>
                    </svg>
                </a>
            </div>
        </div>
        @include('web.components.blog')
    </section>

    @include('web.components.signup')
@endsection

<script>

 document.addEventListener("DOMContentLoaded", function () {
        var swiper = new Swiper(".testimonialSwiper", {
            slidesPerView: 3,
            centeredSlides: true,
            spaceBetween: 2,
            grabCursor: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 2,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 2,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 2,
                }
            }
        });
    });
</script>
