@extends('layout.landing')
@section('content')
    <div class="row clearfix" style="padding:2rem;">
        <div class="col-3">
            <div class="section-heading">
                <h2 class="section-title">Verify Your Email</h2>
                <p class="section-subtitle">Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you. If you didn't receive the email, we will gladly send you another.</p>
            </div>
            @include('partials.alert')
            @if (session('new_api_key'))
                <div class="icon-block-description" style="margin-top:1rem;">
                    <div><strong>Your API key (copy and keep it safe):</strong></div>
                    <div style="word-break:break-all; padding:0.75rem; background:#fff; border:1px solid #e5e7eb; border-radius:6px; margin-top:0.5rem;">
                        {{ session('new_api_key') }}
                    </div>
                    <div style="margin-top:0.5rem; font-size:0.9rem;">
                        This key will not be shown again. You can reset it from your Profile dashboard.
                    </div>
                </div>
            @endif
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="button">Resend Verification Email</button>
            </form>
        </div>
    </div>
@endsection
