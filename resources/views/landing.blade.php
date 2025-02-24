@extends('layout.landing')
@section('content')
        <!--Banner Content-->
        <div id="banner-content" class="row clearfix">

            <div class="col-38">

                <div class="section-heading">
                    <h1>LITURGICAL LYRICS </h1>
                    <h2>An online repository for liturgical music lyrics.</h2>
                        <form class="box" action="/lyrics/search" method="POST">
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