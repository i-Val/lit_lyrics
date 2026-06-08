@extends('layout.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Manage API Client</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.api-clients.index') }}">API Clients</a></li>
                        <li class="breadcrumb-item active">Manage</li>
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
                    @if(session('new_api_key'))
                        <div class="alert alert-warning">
                            <div class="mb-50">New API Key (shown once):</div>
                            <div><code>{{ session('new_api_key') }}</code></div>
                        </div>
                    @endif

                    <div class="mb-2">
                        <div><strong>Name:</strong> {{ $apiClient->name }}</div>
                        <div><strong>Email:</strong> {{ $apiClient->email }}</div>
                        <div><strong>Created:</strong> {{ $apiClient->created_at?->format('Y-m-d H:i') }}</div>
                        <div><strong>Last Used:</strong> {{ $apiClient->last_used_at ? $apiClient->last_used_at->format('Y-m-d H:i').' ('.$apiClient->last_used_ip.')' : '-' }}</div>
                    </div>

                    <form method="POST" action="{{ route('dashboard.api-clients.update', $apiClient) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Status</label>
                            <div class="custom-control custom-switch custom-switch-primary">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ $apiClient->is_active ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">
                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="subscription_plan">Subscription Plan</label>
                            <input type="text" class="form-control" id="subscription_plan" name="subscription_plan" value="{{ old('subscription_plan', $apiClient->subscription_plan) }}" placeholder="e.g. Basic, Pro">
                        </div>

                        <div class="form-group">
                            <label for="subscription_expires_at">Subscription Expires At</label>
                            <input type="date" class="form-control" id="subscription_expires_at" name="subscription_expires_at" value="{{ old('subscription_expires_at', $apiClient->subscription_expires_at?->format('Y-m-d')) }}">
                            <small class="text-muted">Leave empty to make subscription inactive.</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dashboard.api-clients.index') }}" class="btn btn-outline-secondary">Back</a>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <form method="POST" action="{{ route('dashboard.api-clients.resetKey', $apiClient) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning" onclick="return confirm('Reset API key? The old key will stop working.');">Reset API Key</button>
                        </form>

                        <form method="POST" action="{{ route('dashboard.api-clients.destroy', $apiClient) }}" onsubmit="return confirm('Delete this API client?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Delete Client</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

