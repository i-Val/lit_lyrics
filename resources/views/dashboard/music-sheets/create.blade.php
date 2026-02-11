@extends('layout.app')

@section('vendor-css')
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/select/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/editors/quill/katex.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/editors/quill/quill.snow.css')}}">
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/form-quill-editor.css')}}">
@endsection

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Add Music Sheet</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.music-sheets.index') }}">Music Sheets</a></li>
                        <li class="breadcrumb-item active">Add</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content-body">
    <div class="row">
        <div class="col-12">
            @include('partials.alert')
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('dashboard.music-sheets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <label for="music_sheet">Music Sheet File (PDF, Image) <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="music_sheet" name="music_sheet" accept="image/*,application/pdf" required>
                                <label class="custom-file-label" for="music_sheet">Choose file</label>
                            </div>
                        </div>

                        <div class="divider divider-left">
                            <div class="divider-text">Song Selection</div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="select-existing" name="song_selection" class="custom-control-input" value="existing" checked>
                                <label class="custom-control-label" for="select-existing">Add to Existing Song</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="create-new" name="song_selection" class="custom-control-input" value="new">
                                <label class="custom-control-label" for="create-new">Create New Song</label>
                            </div>
                        </div>

                        <div id="existing-song-section">
                            <div class="form-group">
                                <label for="song_id">Select Song</label>
                                <select class="form-control select2" id="song_id" name="song_id">
                                    <option value="">Select a song...</option>
                                    @foreach($songs as $song)
                                        <option value="{{ $song->id }}">{{ $song->title }} ({{ $song->author }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="new-song-section" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="title" name="title" placeholder="Song Title">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="author">Author</label>
                                        <input type="text" class="form-control" id="author" name="author" placeholder="Song Author">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category">Category <span class="text-danger">*</span></label>
                                        <select class="form-control" id="category" name="category">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Verses</label>
                                        <div id="blog-editor-wrapper">
                                            <div id="blog-editor-container">
                                                <div class="editor">
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="verses" id="verses">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary">Upload Music Sheet</button>
                            <a href="{{ route('dashboard.music-sheets.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('vendor-scripts')
    <script src="{{asset('app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/editors/quill/katex.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/editors/quill/highlight.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/editors/quill/quill.min.js')}}"></script>
@endsection

@section('page-scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2();

        // Quill Editor Initialization
        var editorContainer = '#blog-editor-container .editor';
        if ($(editorContainer).length) {
            var Font = Quill.import('formats/font');
            Font.whitelist = ['sofia', 'slabo', 'roboto', 'inconsolata', 'ubuntu'];
            Quill.register(Font, true);

            var blogEditor = new Quill(editorContainer, {
                bounds: editorContainer,
                modules: {
                    formula: true,
                    syntax: true,
                    toolbar: [
                        [ { font: [] }, { size: [] } ],
                        [ 'bold', 'italic', 'underline', 'strike' ],
                        [ { color: [] }, { background: [] } ],
                        [ { script: 'super' }, { script: 'sub' } ],
                        [ { header: '1' }, { header: '2' }, 'blockquote', 'code-block' ],
                        [ { list: 'ordered' }, { list: 'bullet' }, { indent: '-1' }, { indent: '+1' } ],
                        [ 'direction', { align: [] } ],
                        [ 'link', 'image', 'video', 'formula' ],
                        [ 'clean' ]
                    ]
                },
                theme: 'snow'
            });

            // Sync content to hidden input on form submit
            $('form').on('submit', function() {
                var content = document.querySelector('#verses');
                content.value = blogEditor.root.innerHTML;
            });
        }

        $('input[name="song_selection"]').change(function() {
            if ($(this).val() === 'existing') {
                $('#existing-song-section').show();
                $('#new-song-section').hide();
                $('#song_id').prop('required', true);
                $('#title').prop('required', false);
                $('#category').prop('required', false);
            } else {
                $('#existing-song-section').hide();
                $('#new-song-section').show();
                $('#song_id').prop('required', false);
                $('#title').prop('required', true);
                $('#category').prop('required', true);
            }
        });
        
        // File input label update
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
        });
    });
</script>
@endsection
