@extends('layout.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Music Sheets</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Music Sheets</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
        <div class="form-group breadcrumb-right">
            <a href="{{ route('dashboard.music-sheets.create') }}" class="btn btn-primary">
                <i data-feather="plus"></i> Add Music Sheet
            </a>
        </div>
    </div>
</div>
<div class="content-body">
    <div class="row">
        <div class="col-12">
            @include('partials.alert')
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">List of Songs with Music Sheets</h4>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Sheet</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($songs as $song)
                            <tr>
                                <td>{{ $song->title }}</td>
                                <td>{{ $song->author }}</td>
                                <td>{{ $song->category }}</td>
                                <td>
                                    @if($song->music_sheet)
                                        <a href="{{ asset(str_replace('public/', 'storage/', $song->music_sheet)) }}" target="_blank" class="btn btn-sm btn-info">View</a>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('dashboard.music-sheets.edit', $song->id) }}" class="btn btn-sm btn-primary">
                                        <i data-feather="edit"></i>
                                    </a>
                                    <form action="{{ route('dashboard.music-sheets.destroy', $song->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove the music sheet from this song?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No music sheets found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $songs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
