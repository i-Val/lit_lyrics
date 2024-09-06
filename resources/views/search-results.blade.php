@extends('layout.landing')
@section('content')
    </header>

    <!--Main Content Area-->
    <main id="content">

        <!--Introduction-->
        <section id="about" class="introduction scrollto">

            <div class="row clearfix">

                <div class="col-3">
                    <div class="section-heading">
                        <h3>SUCCESS</h3>
                        <h2 class="section-title">How We Help You To Sell Your Product</h2>
                        <p class="section-subtitle">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                            eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam!</p>
                    </div>

                </div>

                <div class="col-2-3">

                @foreach($songs as $song)
                    <!--Icon Block-->
                    <div class="col-2 icon-block icon-top wow fadeInUp" data-wow-delay="0.1s">
                        <!--Icon-->
                        <div class="icon">
                            <i class="fa fa-html5 fa-2x"></i>
                        </div>
                        <!--Icon Block Description-->
                        <div class="icon-block-description">
                            <h4><a href="/lyric/{{$song->id}}">{{$song->title}}</a></h4>
                            <p>{{$song->author}}</p>
                        </div>
                    </div>
                    <!--End of Icon Block-->
                @endforeach

                </div>

            </div>


        </section>
        <!--End of Introduction-->
    </main>
    <!--End Main Content Area-->
  
@endsection