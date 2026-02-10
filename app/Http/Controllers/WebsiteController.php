<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WebsiteController extends Controller
{
    public function home()
    {
        return view('landing');
    }

    public function about()
    {
        return view('about');
    }

    public function searchSong(Request $request) {
        $songs = Song::where('title', 'LIKE', '%' . $request->search_query . '%')->get();
        $search_term = $request->query('search');
        return view('search-results', compact('songs', 'search_term'));
    }

    public function viewSong($id) {
         try {
            if (Cache::has($id)) {
                $song = Cache::get($id);
                return view('lyric-details', compact('song'));
            } else {
                $song = Song::where('id', $id)->first();

                if ($song) {
                    // Store in cache for 60 minutes
                    Cache::put($id, $song, 60);
                    return view('lyric-details', compact('song'));
                }
            }
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }

    public function lyricBuilder()
    {
        return view('lyric-builder');
    }

    public function searchSongJson(Request $request)
    {
        $query = $request->query('q');
        if (!$query) {
            return response()->json([]);
        }
        $songs = Song::where('title', 'LIKE', '%' . $query . '%')
            ->select('id', 'title', 'author') // Optimize selection
            ->limit(10)
            ->get();
        return response()->json($songs);
    }
}
