<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MusicSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all songs that have a music sheet
        $songs = Song::whereNotNull('music_sheet')->where('music_sheet', '!=', '')->paginate(10);
        return view('dashboard.music-sheets.index', compact('songs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $songs = Song::orderBy('title')->get();
        $categories = Category::orderBy('name')->get();
        return view('dashboard.music-sheets.create', compact('songs', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'music_sheet' => 'required|file|mimes:pdf,jpeg,png,jpg,gif|max:10240', // 10MB max
            'song_selection' => 'required|in:existing,new',
            'song_id' => 'required_if:song_selection,existing|nullable|exists:songs,id',
            'title' => 'required_if:song_selection,new|nullable|string|max:255',
            'category' => 'required_if:song_selection,new|nullable|string|exists:categories,name',
            'author' => 'nullable|string|max:255',
        ]);

        try {
            // Handle file upload
            $file = $request->file('music_sheet');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/music-sheets', $filename);
            
            // In DB we store "public/music-sheets/filename" usually, based on SongController logic
            // Let's keep consistency. SongController uses: "public/music-sheets/$image_name"
            // But storeAs returns the path relative to storage/app, which is "public/music-sheets/filename"
            // So $path is correct.

            if ($request->song_selection === 'existing') {
                $song = Song::findOrFail($request->song_id);
                
                // If existing song has a sheet, maybe we should delete the old one?
                // For now, let's just overwrite the reference.
                if ($song->music_sheet && Storage::exists($song->music_sheet)) {
                    // Optional: delete old file. Let's not be too aggressive for now unless requested.
                }

                $song->music_sheet = $path;
                $song->save();

                return redirect()->route('dashboard.music-sheets.index')
                    ->with('success', 'Music sheet added to existing song successfully.');
            } else {
                // Create new song
                $song = new Song();
                $song->title = $request->title;
                $song->category = $request->category;
                $song->author = $request->author;
                $song->music_sheet = $path;
                $song->verses = $request->verses ?? '<p><br></p>'; // Default empty verses if not provided
                $song->save();

                return redirect()->route('dashboard.music-sheets.index')
                    ->with('success', 'New song created with music sheet successfully.');
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Error uploading music sheet: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Not really needed for now, maybe just redirect to edit
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $song = Song::findOrFail($id);
        // We are editing the music sheet for this song.
        // Actually, we are just re-uploading or removing it.
        return view('dashboard.music-sheets.edit', compact('song'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'music_sheet' => 'required|file|mimes:pdf,jpeg,png,jpg,gif|max:10240',
        ]);

        try {
            $song = Song::findOrFail($id);

            // Upload new file
            $file = $request->file('music_sheet');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/music-sheets', $filename);

            // Delete old file if exists
            if ($song->music_sheet && Storage::exists($song->music_sheet)) {
                Storage::delete($song->music_sheet);
            }

            $song->music_sheet = $path;
            $song->save();

            return redirect()->route('dashboard.music-sheets.index')
                ->with('success', 'Music sheet updated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error updating music sheet: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $song = Song::findOrFail($id);

            if ($song->music_sheet) {
                if (Storage::exists($song->music_sheet)) {
                    Storage::delete($song->music_sheet);
                }
                $song->music_sheet = null;
                $song->save();
            }

            return redirect()->route('dashboard.music-sheets.index')
                ->with('success', 'Music sheet removed successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error removing music sheet: ' . $e->getMessage());
        }
    }
}
