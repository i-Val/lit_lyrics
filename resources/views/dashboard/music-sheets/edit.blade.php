@extends('layout.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Edit Music Sheet</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.music-sheets.index') }}">Music Sheets</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                <div class="card-header">
                    <h4 class="card-title">Song: {{ $song->title }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.music-sheets.update', $song->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label>Current Music Sheet</label>
                            <div class="mb-1">
                                @if($song->music_sheet)
                                    <a href="{{ asset(str_replace('public/', 'storage/', $song->music_sheet)) }}" target="_blank" class="btn btn-outline-info">View Current Sheet</a>
                                @else
                                    <span class="text-warning">No music sheet uploaded.</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="music_sheet">Upload New Music Sheet (PDF, Image) <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="music_sheet" name="music_sheet" accept="image/*,application/pdf" required>
                                <label class="custom-file-label" for="music_sheet">Choose file</label>
                            </div>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary">Update Music Sheet</button>
                            <a href="{{ route('dashboard.music-sheets.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<script>
    $(document).ready(function() {
        // File input label update
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
        });
    });
</script>
@endsection
