@extends('layout.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Users</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Users</a></li>
                        <li class="breadcrumb-item active">List</li>
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
            <div class="mb-1">
                <a href="{{ route('dashboard.users.create') }}" class="btn btn-primary">Create User</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->firstname }}</td>
                                        <td>{{ $user->lastname }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->role === 'super admin')
                                                <span class="badge badge-light-danger">Super Admin</span>
                                            @elseif($user->role === 'admin')
                                                <span class="badge badge-light-primary">Admin</span>
                                            @else
                                                <span class="badge badge-light-secondary">User</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at?->format('Y-m-d') }}</td>
                                        <td class="text-right">
                                            <div class="d-inline-flex align-items-center">
                                                <a href="{{ route('dashboard.users.edit', $user) }}" class="btn btn-icon btn-sm btn-outline-primary mr-50" title="Edit">
                                                    <i data-feather="edit"></i>
                                                </a>
                                                <form action="{{ route('dashboard.users.sendResetLink', $user) }}" method="POST" class="d-inline mr-50" title="Send Reset Link">
                                                    @csrf
                                                    <button type="submit" class="btn btn-icon btn-sm btn-outline-info">
                                                        <i data-feather="mail"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('dashboard.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');" title="Delete">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-icon btn-sm btn-outline-danger">
                                                        <i data-feather="trash-2"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div>
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
