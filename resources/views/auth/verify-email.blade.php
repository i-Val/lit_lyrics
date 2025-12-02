@extends('layout.landing')
@section('content')
    <div class="row clearfix" style="padding:2rem;">
        <div class="col-3">
            <div class="section-heading">
                <h2 class="section-title">Verify Your Email</h2>
                <p class="section-subtitle">Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you. If you didn't receive the email, we will gladly send you another.</p>
            </div>
            @if (session('status') == 'verification-link-sent')
                <div class="icon-block-description">A new verification link has been sent to your email address.</div>
            @endif
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="button">Resend Verification Email</button>
            </form>
        </div>
    </div>
@endsection