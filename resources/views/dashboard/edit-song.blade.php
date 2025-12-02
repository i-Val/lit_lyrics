@extends('layout.dashboard')
@section('content')
@include('partials.create-blog-header')
@include('partials.sidebar')

<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Lyric Edit</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="/lyrics">Lyrics</a></li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="blog-edit-wrapper">
                <div class="row">
                    <div class="col-12">
                        @include('partials.alert')
                        <div class="card">
                            <div class="card-body">
                                <div class="media">
                                    <div class="avatar mr-75">
                                        <img src="{{asset('app-assets/images/portrait/small/avatar-s-9.jpg')}}" width="38" height="38" alt="Avatar" />
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mb-25">@if(Auth::user()){{Auth::user()->name}}@endif</h6>
                                        <p class="card-text">{{Date('M-d-Y')}}</p>
                                    </div>
                                </div>
                                <!-- Form -->
                                <form action="{{ route('dashboard.lyric.update', $song->id) }}" method="post" enctype="multipart/form-data" class="mt-2" id="lyrics">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group mb-2">
                                                <label for="blog-edit-title">Title</label>
                                                <input type="text" name="title" id="blog-edit-title" class="form-control" value="{{ old('title', $song->title) }}" placeholder="Title..." />
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group mb-2">
                                                <label for="blog-edit-category">Tag</label>
                                                <input type="text" name="category" id="blog-edit-category" class="form-control" value="{{ old('category', $song->category) }}" placeholder="Part of mass..." />
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group mb-2">
                                                <label for="blog-edit-author">Author</label>
                                                <input type="text" id="blog-edit-author" name="author" class="form-control" value="{{ old('author', $song->author) }}" placeholder="Author..." />
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group mb-2">
                                                <label>Verses</label>
                                                <div id="blog-editor-wrapper">
                                                    <div id="blog-editor-container">
                                                        <div class="editor">
                                                            {!! old('verses', $song->verses) !!}
                                                        </div>
                                                        <input id="verses" type="hidden" name="verses" value="{{ old('verses', $song->verses) }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <div class="border rounded p-2">
                                                <h4 class="mb-1">Featured Image</h4>
                                                <div class="media flex-column flex-md-row">
                                                    @if(!empty($song->music_sheet))
                                                        <img src="{{ asset(str_replace('public/', 'storage/', $song->music_sheet)) }}" id="blog-feature-image" class="rounded mr-2 mb-1 mb-md-0" width="170" height="110" alt="Music Sheet" />
                                                    @else
                                                        <img src="{{asset('app-assets/images/slider/03.jpg')}}" id="blog-feature-image" class="rounded mr-2 mb-1 mb-md-0" width="170" height="110" alt="Blog Featured Image" />
                                                    @endif
                                                    <div class="media-body">
                                                        <small class="text-muted">Optional. Image size up to 10mb.</small>
                                                        <div class="d-inline-block">
                                                            <div class="form-group mb-0">
                                                                <div class="custom-file">
                                                                    <input type="file" name="score" class="custom-file-input" id="blogCustomFile" accept="file/*" />
                                                                    <label class="custom-file-label" for="blogCustomFile">Choose file</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-50">
                                            <button type="submit" class="btn btn-primary mr-1">Update Song</button>
                                            <a href="{{ route('dashboard.lyric.list') }}" class="btn btn-outline-secondary">Back to List</a>
                                        </div>
                                    </div>
                                </form>
                                <!--/ Form -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- END: Content-->

@include('partials.footer')
@include('partials.create-blog-scripts')
@endsection