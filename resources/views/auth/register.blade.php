@extends('layout.landing')
@section('content')
    <div class="row clearfix" style="padding:2rem;">
        <div class="col-3">
            <div class="section-heading">
                <h2 class="section-title">Register</h2>
            </div>
            @if($errors->any())
                <div class="icon-block-description">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group mb-2">
                    <label>First name</label>
                    <input type="text" name="firstname" value="{{ old('firstname') }}" required class="form-control" />
                </div>
                <div class="form-group mb-2">
                    <label>Last name</label>
                    <input type="text" name="lastname" value="{{ old('lastname') }}" required class="form-control" />
                </div>
                <div class="form-group mb-2">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="form-control" />
                </div>
                <div class="form-group mb-2">
                    <label>Password</label>
                    <input type="password" name="password" required class="form-control" />
                </div>
                <div class="form-group mb-2">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="form-control" />
                </div>
                <button type="submit" class="button">Create Account</button>
            </form>
            <p><a href="{{ route('login') }}">Already have an account? Login</a></p>
        </div>
    </div>
@endsection