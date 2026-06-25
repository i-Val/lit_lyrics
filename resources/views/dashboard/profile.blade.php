@extends('layout.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Profile</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content-body">
    @include('partials.alert')

    <div class="row">
        <!-- Profile Information Card -->
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Profile Information</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Profile Picture Upload -->
                        <div class="media mb-2">
                            <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->firstname . ' ' . $user->lastname) . '&color=7F9CF5&background=EBF4FF' }}" 
                                 alt="Profile Picture" 
                                 class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer" 
                                 height="90" width="90">
                            <div class="media-body mt-50">
                                <h4>{{ $user->firstname }} {{ $user->lastname }}</h4>
                                <div class="col-12 d-flex mt-1 px-0">
                                    <label class="btn btn-primary mr-75 mb-0" for="change-picture">
                                        <span class="d-none d-sm-block">Change</span>
                                        <input class="form-control" type="file" id="change-picture" hidden accept="image/*" name="profile_photo_path" />
                                        <span class="d-block d-sm-none">
                                            <i class="mr-0" data-feather="edit"></i>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="firstname">First Name</label>
                            <input type="text" id="firstname" name="firstname" class="form-control" value="{{ old('firstname', $user->firstname) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" id="lastname" name="lastname" class="form-control" value="{{ old('lastname', $user->lastname) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-1">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Password Card -->
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Update Password</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm New Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-1">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">API Access</h4>
                </div>
                <div class="card-body">
                    @if(session('new_api_key'))
                        <div class="alert alert-warning" role="alert">
                            <div class="alert-body">
                                <div><strong>Your new API key (copy it now):</strong></div>
                                <div style="word-break:break-all; padding:0.75rem; background:#fff; border:1px solid #e5e7eb; border-radius:6px; margin-top:0.5rem;">
                                    {{ session('new_api_key') }}
                                </div>
                                <div class="mt-1">This key will not be shown again. If you lose it, reset it here.</div>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-3 col-12">
                            <div class="mb-2">
                                <div class="text-muted">Email Verification</div>
                                <div>
                                    @if($user->email_verified_at)
                                        <span class="badge badge-light-success">Verified</span>
                                    @else
                                        <span class="badge badge-light-warning">Not Verified</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="mb-2">
                                <div class="text-muted">API Key Created</div>
                                <div>
                                    @if($apiClient && $apiClient->api_key_created_at)
                                        {{ $apiClient->api_key_created_at->format('Y-m-d H:i') }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="mb-2">
                                <div class="text-muted">API Requests</div>
                                <div>{{ $apiClient ? number_format((int) $apiClient->requests_count) : '0' }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="mb-2">
                                <div class="text-muted">Last API Use</div>
                                <div>
                                    @if($apiClient && $apiClient->last_used_at)
                                        {{ $apiClient->last_used_at->format('Y-m-d H:i') }}
                                        @if($apiClient->last_used_ip)
                                            ({{ $apiClient->last_used_ip }})
                                        @endif
                                    @else
                                        Never
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap">
                        <form action="{{ route('dashboard.profile.api-key.reset') }}" method="POST" class="mr-1 mb-1">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                {{ $apiClient ? 'Reset API Key' : 'Generate API Key' }}
                            </button>
                        </form>
                        <div class="mb-1 d-flex align-items-center text-muted">
                            Use your API key in the <strong class="ml-25 mr-25">X-API-Key</strong> header (or <strong class="ml-25">Authorization: Bearer</strong>).
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
