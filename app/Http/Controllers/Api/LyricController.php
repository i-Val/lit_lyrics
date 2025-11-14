<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class LyricController extends Controller
{
    public function searchSong(Request $request)
    {
        $search_term = $request->query('search_term');
        return$songs = Song::where('title', 'LIKE', '%' . $search_term . '%')->get();
    }

     //view song + lyrics
    public function viewSong($id)
    {
        try {
            if (Cache::has("song_{$id}")) {
                $song = Cache::get("song_{$id}");
            } else {
             $song = Song::where('id', $id)->first();

                if ($song) {
                    // Store in cache for 60 minutes
                    Cache::put("song_{$id}", $song, 60);
                    return $song;
                }
            }
        } catch (Throwable $exception) {
            Log::error('Lyrics detail fetch failed', $exception->getMessage());
            return $exception->getMessage();
        }
    }
}
