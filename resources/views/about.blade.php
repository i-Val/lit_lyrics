@extends('layout.landing')

@section('content')
<div id="banner-content" class="row clearfix">
    <div class="col-38">
        <div class="section-heading">
            <h1>About Us</h1>
            <h2>Lit Lyrics</h2>
        </div>
        <div class="section-content" style="max-width: 800px; margin: 0 auto; text-align: left; font-size: 1.1em; line-height: 1.6;">
            <p>
                Welcome to Lit Lyrics, your premier destination for liturgical music resources. 
                Our mission is to simplify the preparation of music for mass and other liturgical services by providing easy access to lyrics, chords, and sheet music.
            </p>
            <br>
            <h3>Our Mission</h3>
            <p>
                We strive to empower choir directors, musicians, and parishioners with tools that make selecting and organizing hymns seamless. 
                Whether you are looking for a specific song or building a full mass lineup, Lit Lyrics is here to help.
            </p>
            <br>
            <h3>Features</h3>
            <ul style="list-style-type: disc; margin-left: 20px;">
                <li>Comprehensive database of liturgical songs</li>
                <li>Lyric Builder for creating mass lineups</li>
                <li>Easy-to-use search functionality</li>
                <li>Downloadable resources in multiple formats</li>
            </ul>
            <br>
            <p>
                Thank you for being a part of our community. Together, let's make every celebration more meaningful through music.
            </p>
            <br>
            <h3>Contact Us</h3>
            <p>
                Have questions, feedback, or need support? Reach out to us:
            </p>
            @php
                $contactEmail = \App\Models\Setting::get('contact_email');
                $contactPhone = \App\Models\Setting::get('contact_phone');
                $contactAddress = \App\Models\Setting::get('contact_address');
            @endphp
            <ul style="list-style-type: none; margin-left: 0; padding-left: 0; line-height: 1.8;">
                @if($contactEmail)
                    <li><strong>Email:</strong> <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></li>
                @endif
                @if($contactPhone)
                    <li><strong>Phone:</strong> {{ $contactPhone }}</li>
                @endif
                @if($contactAddress)
                    <li><strong>Address:</strong> {!! nl2br(e($contactAddress)) !!}</li>
                @endif
            </ul>
        </div>
    </div>
</div>
@endsection
