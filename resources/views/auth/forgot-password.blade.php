@extends('layout.landing')
@section('content')
    <div class="row clearfix" style="padding:2rem;">
        <div class="col-3">
            <div class="section-heading">
                <h2 class="section-title">Forgot Password</h2>
            </div>
            @if (session('status'))
                <div class="icon-block-description">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="icon-block-description">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group mb-2">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="form-control" />
                </div>
                <button type="submit" class="button">Send Password Reset Link</button>
            </form>
        </div>
    </div>
@endsection