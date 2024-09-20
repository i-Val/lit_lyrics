@extends('layout.landing')
@section('content')
    </header>

    <!--Main Content Area-->
    <main id="content">



        <!--Content Section-->
        <div id="services" class="scrollto clearfix">

            <div class="row no-padding-bottom clearfix">


                <!--Content Left Side-->
                <div class="col-3">
                    <!--User Testimonial-
                    <blockquote class="testimonial text-right bigtest">
                        <q>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore
                            et dolore magna aliqua</q>
                        <footer>— John Doe, Happy Customer</footer>
                    </blockquote>
                    <End of Testimonial-->

                </div>
                <!--End Content Left Side-->

                <!--Content of the Right Side-->
                <div class="col-3">
                    <div class="section-heading">
                        <!--<h3>BELIEVING</h3>-->
                        <h2 class="section-title">{{$song->title}}</h2>
                        <p class="section-subtitle">By {{$song->author}}</p>
                    </div>
                    {!!$song->verses!!}
                    <!-- Just replace the Video ID "UYJ5IjBRlW8" with the ID of your video on YouTube (Found within the URL) -->
                    <a href="/lyric/download/{{$song->id}}"  class="button  ">
                        DOWNLOAD DOC <i class="fa fa-play" aria-hidden="true"></i>
                    </a>
                </div>
                <!--End Content Right Side-->

                <div class="col-3">
                    <img src="/landing/images/dancer.jpg" alt="Dancer"/>
                </div>

            </div>


        </div>
        <!--End of Content Section-->



    </main>
    <!--End Main Content Area-->
  
@endsection