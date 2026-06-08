@extends('layout.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">API Clients</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">API Clients</li>
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
                    <form method="GET" action="{{ route('dashboard.api-clients.index') }}" class="mb-2">
                        <div class="form-row align-items-center">
                            <div class="col-sm-8 my-1">
                                <input type="text" class="form-control" name="q" value="{{ $q }}" placeholder="Search by name or email">
                            </div>
                            <div class="col-sm-4 my-1 text-right">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <a href="{{ route('dashboard.api-clients.index') }}" class="btn btn-outline-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Subscription</th>
                                    <th>Last Used</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clients as $client)
                                    <tr>
                                        <td>{{ $client->id }}</td>
                                        <td>{{ $client->name }}</td>
                                        <td>{{ $client->email }}</td>
                                        <td>
                                            @if($client->is_active)
                                                <span class="badge badge-light-success">Active</span>
                                            @else
                                                <span class="badge badge-light-danger">Disabled</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($client->subscription_expires_at)
                                                {{ $client->subscription_plan ? $client->subscription_plan.' - ' : '' }}{{ $client->subscription_expires_at->format('Y-m-d') }}
                                            @else
                                                <span class="text-muted">None</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $client->last_used_at ? $client->last_used_at->format('Y-m-d H:i') : '-' }}
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('dashboard.api-clients.edit', $client) }}" class="btn btn-sm btn-outline-secondary">Manage</a>
                                            <form action="{{ route('dashboard.api-clients.destroy', $client) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this API client?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No API clients found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div>
                        {{ $clients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

