@extends('layout.landing')
@section('content')
    <div class="row clearfix" style="padding:2rem;">
        <div class="col-3">
            <div class="section-heading">
                <h2 class="section-title">Login</h2>
            </div>
            @if(session('status'))
                <div class="icon-block-description">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="icon-block-description">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group mb-2">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="form-control" />
                </div>
                <div class="form-group mb-2">
                    <label>Password</label>
                    <input type="password" name="password" required class="form-control" />
                </div>
                <div class="form-group mb-2">
                    <label><input type="checkbox" name="remember" /> Remember me</label>
                </div>
                <button type="submit" class="button">Login</button>
            </form>
            <p><a href="{{ route('password.request') }}">Forgot your password?</a></p>
            <p><a href="{{ route('register') }}">Create an account</a></p>
        </div>
    </div>
@endsection