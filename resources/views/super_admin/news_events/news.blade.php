@extends('layouts.super-admin')
@section('page-title', 'News Settings')
@section('active-news-settings', 'active')
@section('page-name', 'News and Events')

@section('content')

<section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5">
    <div class="container-fluid my-3">

        <livewire:components.alert-status-message :message="session()->get('success')" />

        <div class="row justify-content-center g-0">
            <div class="col-11 sub-container-box pt-3 pb-md-1 pb-2">
                <div class="row gy-1 align-items-center">
                    <div class="col-6">
                        <h3>News Settings</h3>
                    </div>
                    <div class="col-6 text-end">
                        <button class="btn btn-primary fs-7" data-bs-toggle="collapse" data-bs-target="#addAnnouncementCollapse">Create Post</button>
                    </div>
                    <div class="col-12">
                        <div class="collapse mt-3" id="addAnnouncementCollapse">
                            <div>
                                <form action="{{ route("superAdmin.postNews") }}" enctype="multipart/form-data" method="POST" class="row">
                                    @csrf
                                    <div class="col-12 col-md-6 mb-3">
                                        <label class="form-label">News Image:</label>
                                        <input type="file" class="fs-7 form-control @error('news_image') border border-danger @enderror" name="news_image">
                                        <span class="text-danger error-message">@error('news_image') {{$message}} @enderror</span>
                                    </div>
                                    <div class="col-12 col-md-6 mb-3">
                                        <label class="form-label">News Title:</label>
                                        <input type="text" class="form-control @error('news_title') border border-danger @enderror" name="news_title">
                                        <span class="text-danger error-message">@error('news_title') {{$message}} @enderror</span>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">News:</label>
                                        <textarea class="form-control @error('news_text') border border-danger @enderror" rows="5" name="news_text" id="rte_news" hidden></textarea>
                                        <span class="text-danger error-message">@error('news_text') {{$message}} @enderror</span>
                                        <trix-editor input="rte_news" class="fs-7 trix-content"></trix-editor>
                                    </div>
                                    <div class="mb-2 text-end">
                                        <button class="btn btn-success fs-7" type="submit">Add <i class="fa-solid fa-plus"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-11 mt-3 px-3">
                <div class="row">
                    @if (count($news) == 0)
                        <div class="col-12">
                            <div class="alert alert-warning mb-0" role="alert">
                                There is no news posted yet.
                            </div>
                        </div>
                    @else
                        @foreach ($news as $new)
                            <div class="sub-container-box mb-3 py-4 ps-0">
                                <div class="row align-items-center m-0">
                                    <div class="col-12 mb-1">
                                        <div class="row justify-content-between">
                                            <div class="col-1 text-end">
                                                <img src="{{ asset('Uploads/Profiles/user-no-profile.png') }}" class="career-post-profile">
                                            </div>
                                            <div class="col-10 col-sm-10 col-md-11 col-lg-11 col-xl-11 d-flex justify-content-between">
                                                <div>
                                                    <p class="fs-7 mb-0 fw-bold">Admin News <i class="fa-solid fa-bullhorn me-2"></i></p>
                                                    <p class="mb-0 career-date">{{ date('F d, Y') }}</p>
                                                </div>
                                                <div class="text-center">
                                                    <button class="btn btn-danger fs-7" data-bs-toggle="modal" data-bs-target="#deleteNews{{$new->news_id}}"><i class="fa-solid fa-trash-can"></i></button>
                                                    @include("super_admin.components.delete-news")
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-12 col-lg-11 offset-lg-1 col-xl-11 offset-xl-1 mt-2 mb-0 d-flex align-items-center justify-content-between">
                                        <h5 class="mb-1">{{ $new->news_title }}</h5>
                                        <a class="fs-7 text-decoration-none collapse show" id="showMoreNews{{ $new->news_id }}" type="button" data-bs-toggle="collapse" data-bs-target="#showMoreNews{{ $new->news_id }}">Read more</a>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-11 offset-lg-1 col-xl-11 offset-xl-1 mt-0 mb-1">
                                        <div class="collapse" id="showMoreNews{{ $new->news_id }}">
                                            <div hidden>{{ $text = $new->news_text }}</div>
                                            <div class="fs-7" style="white-space: pre-wrap;">@php echo $text @endphp</div>
                                            <a class="fs-7 text-decoration-none mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#showMoreNews{{ $new->news_id }}">Read less</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center text-start m-0">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-11 offset-lg-1 col-xl-11 offset-xl-1 mt-2 mt-sm-2 mt-md-2 mt-lg-1 mt-xl-0">
                                        <!-- Button trigger modal -->
                                        <a type="button" class="w-100" data-bs-toggle="modal" data-bs-target="#newsModal{{ $new->news_id }}">
                                            <div class="career-post-image-box">
                                                <img src="{{ asset('Uploads/NewsAnnouncements/' . $new->news_image) }}" alt="" class="career-post-image">
                                            </div>
                                        </a>

                                        <!-- Modal -->
                                        <div class="modal fade" id="newsModal{{ $new->news_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-md">
                                                <div class="modal-content bg-transparent border-0">
                                                    <div class="row justify-content-center">
                                                        <div class="col-12">
                                                            <button type="button" class="btn btn-dark fw-bold mb-2 px-3" data-bs-dismiss="modal" style="position: fixed; top: 0; right: 0;">x</button>
                                                        </div>
                                                        <div class="col-12 text-center">
                                                            <img src="{{ asset('Uploads/NewsAnnouncements/' . $new->news_image) }}" alt="Job Image" class="career-img-full">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
