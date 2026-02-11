@extends('layout.app')

@section('vendor-css')
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/select/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/editors/quill/katex.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/editors/quill/quill.snow.css')}}">
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/form-quill-editor.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/page-blog.css')}}">
@endsection

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Add Song</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Pages</a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Lyrics</a>
                        </li>
                        <li class="breadcrumb-item active">Create
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content-body">
    <!-- Blog Edit -->
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
                        <form action="/lyric" method="post" enctype="multipart/form-data" class="mt-2" id="lyrics">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-2">
                                        <label for="blog-edit-title">Title</label>
                                        <input type="text" name="title" id="blog-edit-title" class="form-control" placeholder="Title..." />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-2">
                                        <label for="blog-edit-category">Category</label>
                                        <select name="category" id="blog-edit-category" class="form-control">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->name }}" {{ old('category') == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-2">
                                        <label for="blog-edit-slug">Author</label>
                                        <input type="text" id="blog-edit-slug" name="author" class="form-control" placeholder="Author..." />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-2">
                                        <label>Verses</label>
                                        <div id="blog-editor-wrapper">
                                            <div id="blog-editor-container">
                                                <div class="editor">
                                                </div>
                                                <input id="verses" type="hidden" name="verses">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="border rounded p-2">
                                        <h4 class="mb-1">Music Sheet</h4>
                                        <div class="media flex-column flex-md-row">
                                            <img src="{{asset('app-assets/images/slider/03.jpg')}}" id="blog-feature-image" class="rounded mr-2 mb-1 mb-md-0" width="170" height="110" alt="Blog Featured Image" />
                                            <div class="media-body">
                                                <small class="text-muted">Upload an image or PDF file.</small>
                                                <p class="my-50">
                                                    <a href="javascript:void(0);" id="blog-image-text">C:\fakepath\banner.jpg</a>
                                                </p>
                                                <div class="d-inline-block">
                                                    <div class="form-group mb-0">
                                                        <div class="custom-file">
                                                            <input type="file" name="score" class="custom-file-input" id="blogCustomFile" accept="image/*,application/pdf" />
                                                            <label class="custom-file-label" for="blogCustomFile">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-50">
                                    <button type="submit" class="btn btn-primary mr-1">Save Changes</button>
                                </div>
                            </div>
                        </form>
                        <!--/ Form -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Blog Edit -->

</div>
@endsection

@section('page-scripts')
    <script src="{{asset('app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/editors/quill/katex.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/editors/quill/highlight.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/editors/quill/quill.min.js')}}"></script>
    <script src="{{asset('app-assets/js/scripts/pages/page-blog-edit.js')}}"></script>
    <script>
        // document.getElementById('ourform').addEventListener('submit', function(event){
        //     var content = document.getElementById('bogcontent')
        //     content.value = blogEditor.root.innerHTML
        //     console.log(blogEditor.root.innerHTML)
        // })

        $(document).ready(function() {
            $('#blogCustomFile').on('change', function(e) {
                var file = e.target.files[0];
                if (file && file.type === 'application/pdf') {
                    $('#blog-feature-image').addClass('d-none');
                    if ($('#pdf-selected-msg').length === 0) {
                        $('#blog-feature-image').after('<div id="pdf-selected-msg" class="alert alert-info mt-1 p-1 text-center">PDF file selected</div>');
                    }
                } else {
                    $('#blog-feature-image').removeClass('d-none');
                    $('#pdf-selected-msg').remove();
                }
            });
        });
        
    </script>
@endsection