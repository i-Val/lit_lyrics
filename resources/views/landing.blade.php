@extends('layout.landing')
@section('content')
        <!--Banner Content-->
        <div id="banner-content" class="row clearfix">

            <div class="col-38">

                <div class="section-heading">
                    <h1>LIT LYRICS REPOSITORY</h1>
                    <h2>Namari is a free landing page template you can use for your projects. It is free to use for your
                        personal and commercial projects, enjoy!</h2>
                        <form action="/lyrics/search" method="POST">
                            @csrf
                            <input type="text" name="search_query" id="">
                            <button type="submit" class="button">SEARCH</button>
                        </form>
                </div>

            </div>

        </div><!--End of Row-->
    </header>

    <!--Main Content Area-->
   
    <!--End Main Content Area-->
  
@endsection