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
                        <h3>Search Results For</h3>
                        <h2 class="section-title">"{{$search_term}}"</h2>
                        <p class="section-subtitle"></p>
                    </div>

                </div>

                <div class="col-2-3">
                @if($songs->count() == 0)
                    <!--Icon Block-->
                <div class="col-2 icon-block icon-top wow fadeInUp" data-wow-delay="0.1s">
                    <!--Icon-->
                    <div class="icon">
                        <i class="fa fa-bulb fa-2x"></i>
                    </div>
                    <!--Icon Block Description-->
                    <div class="icon-block-description">
                        <h4>Oops!!</h4>
                        <p>No records found...</p>
                    </div>
                </div>
                <!--End of Icon Block-->
                @endif

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