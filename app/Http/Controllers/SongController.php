<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Throwable;
use App\Models\Song;
use App\Models\Lyric;
use App\Models\MusicSheet;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Cache;

class SongController extends Controller
{
    public function index()
    {
        $songs = Song::where('id', '>', 0)->get();
        return view('dashboard/lyrics-table', compact('songs'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('dashboard/add-song', compact('categories'));
    }
    //add song + lyrics
    public function addSong(Request $request)
    {
        try {

            //save song title and author
            $song = new Song;

            if ($request->file('score') != null) {

                $image_name = $request->file('score')->getClientOriginalName();

                $data = [
                    'title' => $request->title,
                    'author' => $request->author,
                    'verses' => $request->verses,
                    'music_sheet' => "public/music-sheets/$image_name",
                    'category' => $request->category,
                ];
            } else {
                $data = [
                    'title' => $request->title,
                    'author' => $request->author,
                    'verses' => $request->verses,
                    'category' => $request->category,
                ];
            }



            $saved_song = $song->create($data);
            //prepare verses
            $verses = explode('<p><br></p>', $request->verse);

            if ($saved_song && $request->file('score') != null) {
                $request->file('score')->storeAs(
                    'public/music-sheets',
                    $image_name
                );
            }

            return back()->with('success', 'lyrics added successfully!');
            // return $verses;
            // array_shift($verses);
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }
    //edit song
    public function editSong($id)
    {
        $song = Song::findOrFail($id);
        return view('dashboard.edit-song', compact('song'));
    }
    //update song + lyrics
    public function updateSong(Request $request, $id)
    {
        try {
            $song = Song::findOrFail($id);

            $data = [
                'title' => $request->title,
                'author' => $request->author,
                'category' => $request->category,
                'verses' => $request->verses,
            ];

            if ($request->file('score') != null) {
                $image_name = $request->file('score')->getClientOriginalName();
                $request->file('score')->storeAs('public/music-sheets', $image_name);
                $data['music_sheet'] = "public/music-sheets/$image_name";
            }

            $song->update($data);

            return redirect()->route('dashboard.lyric.edit', $song->id)->with('success', 'Song updated successfully!');
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }
    //download lyric docx

    public function download()
    {

        $song = Song::where('id', 1)->first();

        // return htmlspecialchars($song->lyrics[0]->lyric);
        //Creating new document...
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        /* Note: any element you append to a document must reside inside of a Section. */

        // Adding an empty Section to the document...
        $section = $phpWord->addSection();
        // Adding Text element to the Section having font styled by default...
        $section->addText(
            $song->title,
            array('name' => 'Tahoma', 'size' => 20, 'bold' => true)
        );
        $section->addHTML(
            $song->verses,
            array('name' => 'Tahoma', 'size' => 20)
        );

        // Saving the document as OOXML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        return $objWriter->save('wow.docx');

        // Saving the document as HTML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
        $objWriter->save('see.html');

        /*$data = "this is amazing";

        $filename = "amazing.docx";

        $header = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" .
                  "<w:wordDocument xmlns:w=\"http://schemas.microsoft.com/office/word/2003/wordml\">" . 
                  "<w:body>";

        $footer = "</w:body></w:wordDocument>" ;

        $content = "<w:p><w:r><w:t>$data</w:t></w:r></w:p>";

        $wordContent = $header . $content . $footer;

        header("Content-Type: application/vnd.ms-word");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        echo $wordContent;*/
    }

    public function single_download($id)
    {
        $song = Song::where('id', $id)->first();

        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=.$song->title.docx");
        header("Pragma: no-cache");
        header("Express: 0");
        echo '<!DOCTYPE html>';
        echo '<html lang="en">';
        echo '<head>';
        echo  '<meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<title>Document</title>';
        echo '</head>';
        echo '<body>';
        echo '<h1>' . $song->title . '</h1>';

        echo $song->verses . '</br> </br></br>';
        echo '</body>';
        echo '</html>';

        $filePath = 'word_export.doc';

        //Save the content to the Word file
        file_put_contents($filePath, $wordContent);

    }

    public function collection_download(Request $request)
    {
        // return 'this feature is under construction. stay tuned!';

        $entrance = Song::where('title', $request->entrance)->first();
        $kyrie = Song::where('title', $request->kyrie)->first();
        $gloria = Song::where('title', $request->gloria)->first();
        $creed = Song::where('title', $request->creed)->first();
        $offertory = Song::where('title', $request->offertory)->first();
        $communion = Song::where('title', $request->communion)->first();
        $consecration = Song::where('title', $request->consecration)->first();
        $dismissal = Song::where('title', $request->dismissal)->first();

        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=Selection for Sunday.doc");
        header("Pragma: no-cache");
        header("Express: 0");
        echo '<!DOCTYPE html>';
        echo '<html lang="en">';
        echo '<head>';
        echo  '<meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<title>Document</title>';
        echo '</head>';
        echo '<body>';
        if ($entrance != null) {
            echo '<h1>Entrance:</h1>';
            echo '<h1>' . $entrance->title . '</h1>';

            echo $entrance->verses . '</br> </br></br>';
        } else {
            echo '<h1>Entrance:Record Not Found</h1>';
        }
        if ($kyrie != null) {
            echo '<h1>Kyrie:</h1>';
            echo '<h1>' . $kyrie->title . '</h1>';

            echo $kyrie->verses . '</br> </br></br>';
        } else {
            echo '<h1>Kyrie:Record Not Found</h1>';
        }
        if ($gloria != null) {
            echo '<h1>Gloria:</h1>';
            echo '<h1>' . $gloria->title . '</h1>';

            echo $gloria->verses . '</br> </br></br>';
        } else {
            echo '<h1>Gloria:Record Not Found</h1>';
        }
        if ($creed != null) {
            echo '<h1>Creed:</h1>';
            echo '<h1>' . $creed->title . '</h1>';

            echo $creed->verses . '</br> </br></br>';
        } else {
            echo '<h1>Creed:Record Not Found</h1>';
        }
        if ($offertory != null) {
            echo '<h1>Offertory:</h1>';
            echo '<h1>' . $offertory->title . '</h1>';

            echo $offertory->verses . '</br> </br></br>';
        } else {
            echo '<h1>Offertory:Record Not Found</h1>';
        }
        if ($consecration != null) {
            echo '<h1>Consecration:</h1>';
            echo '<h1>' . $consecration->title . '</h1>';

            echo $consecration->verses . '</br> </br></br>';
        } else {
            echo '<h1>Consecration:Record Not Found</h1>';
        }
        if ($communion != null) {
            echo '<h1>Communion:</h1>';
            echo '<h1>' . $communion->title . '</h1>';

            echo $communion->verses . '</br> </br></br>';
        } else {
            echo '<h1>Communion:Record Not Found</h1>';
        }
        if ($dismissal != null) {
            echo '<h1>Dismissal:</h1>';
            echo '<h1>' . $dismissal->title . '</h1>';

            echo $dismissal->verses . '</br> </br></br>';
        } else {
            echo '<h1>Dismissal:Record Not Found</h1>';
        }
        echo '</body>';
        echo '</html>';
    }

    public function collection_form()
    {
        return view('collection-form');
    }

    //delete song + lyrics
    public function delete($id)
    {
        try {
            $song = Song::where('id', $id);
            $song->delete();
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }

    //upload music sheet
    public function upload_music_sheet(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $name = $request->name . '.' . $request->image->extension();

        $music_sheet = new MusicSheet;

        $data = [
            'name' => $name,
            'author' => $request->author
        ];

        $save_music_sheet = $music_sheet->create($data);

        if ($save_music_sheet) {
            // Public Folder
            $request->image->move(public_path('music-sheets'), $name);


            return back()->with('success', 'Image uploaded Successfully!')
                ->with('image', $name);
        }
    }

    //update music sheet
    public function update_music_sheet(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $name = $request->name . '.' . $request->image->extension();

        $music_sheet = new MusicSheet;

        $data = [
            'name' => $name,
            'author' => $request->author
        ];

        $save_music_sheet = $music_sheet->update($data);

        if ($save_music_sheet) {
            // Public Folder
            $request->image->move(public_path('music-sheets'), $name);


            return back()->with('success', 'Image updated Successfully!')
                ->with('image', $name);
        }
    }

    public function getLyricsFile() {}

    public function extractFromTxt()
    {
        $path = storage_path('app/selection.txt');

        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $result = [];
        $currentCategory = null;

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip the heading or empty lines
            if (empty($line) || stripos($line, 'SELECTION FOR') === 0) {
                continue;
            }

            // Detect category headers (assume they don't contain ":" or are in title case)
            if (preg_match('/^[A-Z][a-z\s]*:?$/', $line)) {
                $currentCategory = rtrim($line, ':');
                $result[$currentCategory] = [];
            } else {
                // Add song to current category
                if ($currentCategory) {
                    $result[$currentCategory][] = $line;
                }
            }
        }

        return response()->json($result);
    }


    public function parseDocxSongs()
    {
        $path = storage_path('app/selections.docx'); // adjust as needed

        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        $phpWord = IOFactory::load($path);
        $text = '';

        foreach ($phpWord->getSections() as $section) {
            $elements = $section->getElements();
            foreach ($elements as $element) {
                if (method_exists($element, 'getText')) {
                    $text .= $element->getText() . "\n";
                }
            }
        }

        // Now parse the text (same logic as before)
        $lines = explode("\n", $text);
        $result = [];
        $currentCategory = null;

        foreach ($lines as $line) {
            $line = trim($line);

            if (empty($line) || stripos($line, 'SELECTION FOR') === 0) {
                continue;
            }

            if (preg_match('/^[A-Z][a-z\s]*:?$/', $line)) {
                $currentCategory = rtrim($line, ':');
                $result[$currentCategory] = [];
            } else {
                if ($currentCategory) {
                    $result[$currentCategory][] = $line;
                }
            }
        }

        return response()->json($result);
    }
}
