@extends('layout.landing')
@section('content')
    <div class="row clearfix" style="padding:2rem;">
        <div class="col-3">
            <div class="section-heading">
                <h2 class="section-title">Reset Password</h2>
            </div>
            @if($errors->any())
                <div class="icon-block-description">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}" />
                <div class="form-group mb-2">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $email) }}" required class="form-control" />
                </div>
                <div class="form-group mb-2">
                    <label>New Password</label>
                    <input type="password" name="password" required class="form-control" />
                </div>
                <div class="form-group mb-2">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="form-control" />
                </div>
                <button type="submit" class="button">Reset Password</button>
            </form>
        </div>
    </div>
@endsection