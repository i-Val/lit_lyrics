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
                    <div id="lyrics-content" style="margin-bottom: 20px;">
                        {!!$song->verses!!}
                    </div>
                    <!-- Just replace the Video ID "UYJ5IjBRlW8" with the ID of your video on YouTube (Found within the URL) -->
                    <button id="btn-copy" class="button" style="cursor: pointer; margin-right: 5px;">
                        COPY <i class="fa fa-clipboard" aria-hidden="true"></i>
                    </button>
                    <a href="/lyric/download/{{$song->id}}?type=docx"  class="button  ">
                        DOCX <i class="fa fa-file-word-o" aria-hidden="true"></i>
                    </a>
                    <a href="/lyric/download/{{$song->id}}?type=txt"  class="button  ">
                        TXT <i class="fa fa-file-text-o" aria-hidden="true"></i>
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
  

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('btn-copy').addEventListener('click', function() {
                const lyricsContent = document.getElementById('lyrics-content');
                // Use innerText to get the text with line breaks but without HTML tags
                const lyrics = lyricsContent.innerText;
                
                navigator.clipboard.writeText(lyrics).then(() => {
                    const btn = this;
                    const originalHtml = btn.innerHTML;
                    btn.innerHTML = 'COPIED! <i class="fa fa-check" aria-hidden="true"></i>';
                    
                    setTimeout(() => {
                        btn.innerHTML = originalHtml;
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                    alert('Failed to copy lyrics. Please try selecting and copying manually.');
                });
            });
        });
    </script>
@endsection