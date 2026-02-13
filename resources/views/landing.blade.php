@extends('layout.landing')
@section('content')
        <!--Banner Content-->
        <div id="banner-content" class="row clearfix">

            <div class="col-38">

                <div class="section-heading">
                    <h1>LITURGICAL LYRICS </h1>
                    <h2>An online repository for liturgical music lyrics.</h2>
                    <div style="position: relative; max-width: 500px; margin: 0 auto;">
                        <form action="/lyrics/search" method="POST">
                            @csrf
                            <input type="text" name="search_query" id="home-search-input" placeholder="Search for songs..." autocomplete="off">
                            <button type="submit" class="button">SEARCH</button>
                        </form>
                        <div id="home-search-results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border-radius: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); display: none; z-index: 1000; text-align: left; max-height: 300px; overflow-y: auto;"></div>
                    </div>
                </div>

            </div>

        </div><!--End of Row-->
    </header>

    <!--Main Content Area-->
   
    <!--End Main Content Area-->
  
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('home-search-input');
            const resultsContainer = document.getElementById('home-search-results');
            let debounceTimer;

            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value;

                if (query.length < 2) {
                    resultsContainer.style.display = 'none';
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetch(`{{ route('api.songs.search') }}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            resultsContainer.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(song => {
                                    const item = document.createElement('div');
                                    item.style.padding = '10px 15px';
                                    item.style.borderBottom = '1px solid #eee';
                                    item.style.cursor = 'pointer';
                                    item.style.color = '#333';
                                    item.innerHTML = `<strong>${song.title}</strong> <br><small style="color: #666;">${song.author}</small>`;
                                    
                                    item.addEventListener('mouseover', () => {
                                        item.style.backgroundColor = '#f9f9f9';
                                    });
                                    item.addEventListener('mouseout', () => {
                                        item.style.backgroundColor = 'transparent';
                                    });
                                    
                                    item.addEventListener('click', () => {
                                        window.location.href = `/lyric/${song.id}`;
                                    });
                                    
                                    resultsContainer.appendChild(item);
                                });
                                resultsContainer.style.display = 'block';
                            } else {
                                const item = document.createElement('div');
                                item.style.padding = '10px 15px';
                                item.style.color = '#666';
                                item.textContent = 'No results found';
                                resultsContainer.appendChild(item);
                                resultsContainer.style.display = 'block';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching songs:', error);
                        });
                }, 300); // 300ms debounce
            });

            // Close results when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                    resultsContainer.style.display = 'none';
                }
            });
        });
    </script>
  
@endsection