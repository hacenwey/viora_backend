@extends('frontend.layouts.master')

@section('title',settings()->get('app_name').' | '. $page->title)

@section('main-content')

	<!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h2>{{ $page->title }}</h2>
                    </div>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="theme-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('backend.home') }}">{{ trans('global.home') }}</a></li>
                            <li class="breadcrumb-item active">{{ $page->title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->

	<!-- about section start -->
    <section class="about-page section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="banner-section" style="max-height: 385px">
                        <img src="{{ $page->featured_image }}" class="img-fluid blur-up lazyload" alt="" width="100%">
                    </div>
                </div>
                <div class="col-sm-12">
                    <h4>
                        {{ settings('app_name') }}
                    </h4>
                    <blockquote>
                        {{ settings('short_des') }}
                    </blockquote>
                    <p>
                        {!! $page->page_text !!}
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- about section end -->


@endsection
