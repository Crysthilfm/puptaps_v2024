@extends('layouts.user')
@section('page-title', 'Homepage')
@section('home-active', 'user-active')
@section('content')

<section class="mt-0 mt-sm-0 mt-md-0 mt-lg-5 mt-xl-5">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <!-- Start: Body -->
            <div class="col-11 col-sm-9 col-md-9 col-lg-9 col-xl-9">
                <div class="row g-0 g-sm-0 g-md-0 g-lg-3 g-xl-3">

                    <!-- Career Submenu - Left Side -->
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-3">
                        <livewire:homepage.homepage-submenu :query="$query" />
                    </div>

                    <!-- Career Contents -->
                    <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-9 animate__animated animate__fadeInDown animate__fast">
                        @if ($query == "Announcements" || $query == "All")
                            @if (empty($announcements) || count($announcements) == 0)
                                <div class="alert alert-warning fs-7 mt-0 mb-2" role="alert">
                                    There are no announcement posted yet.
                                </div>
                            @else
                                <livewire:homepage.homepage-image-announcement :announcements="$announcements" />
                            @endif
                        @endif
                        @if ($query == "News" || $query == "All")
                            @if (empty($news) || count($news) == 0)
                                <div class="alert alert-warning fs-7 my-2" role="alert">
                                    There are no news posted yet.
                                </div>
                            @else
                                <livewire:homepage.homepage-image-news :news="$news" />
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            <!-- End: Body -->
        </div>
    </div>
</section>

@endsection
