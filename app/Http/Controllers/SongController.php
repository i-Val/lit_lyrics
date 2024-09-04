<?php

namespace App\Http\Controllers;

use App\Models\Lyric;
use App\Models\MusicSheet;
use App\Models\Song;
use Exception;
use Illuminate\Http\Request;
use Throwable;

class SongController extends Controller
{
    //add song + lyrics
    public function addSong(Request $request) {
        try{

            //save song title and author
            $song = new Song;
            $info = [
            'title' => $request->title,
            'author' => $request->author
            ];

             $saved_song = $song->create($info);
            //prepare verses
            $verses = explode('<p><br></p>', $request->verse);
            // return $verses;
            // array_shift($verses);
                
            if ($saved_song) {
                $verse_number = 0;
                foreach($verses as $verse) {
                    $verse_number++;
                    $saved_song->verses()->create([
                        "verse"=> $verse_number,
                        "lyric"=> $verse
                    ]);
                }

                return "song and lyrics added!";

            }
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }

    //search song 
    public function searchSong(Request $request) {
        return $song = Song::where('title', 'LIKE', '%'.$request->search_query.'%')->get();
        return view('search-result', compact('song'));
    }

    //view song + lyrics
    public function viewSong($id) {
        return$song = Song::where('id', $id)->with('verses')->first();
        return view('view-song', compact('song'));
    }


    //edit song
    public function editSong($id) {
        $song = Song::where('id', $id)->get()->with('verses');
        return view('edit-song', compact('song'));
    }
    //update song + lyrics
    public function updateSong(Request $request, $id) {
        try {
        $song = Song::find($id);
        
        $info = [
            'title' => $request->title,
            'author' => $request->author,
        ];

        $updated_song = $song->update($info);

        $verses = $request->verses;

        if($updated_song){
            foreach($verses as $verse) {
                $updated_song->lyrics()->create($verse);
            }
        }
        return back()->with('succes', 'song updated successfully!');
    } catch (Throwable $exception) {
        return $exception->getMessage();
    }
    }
    //download lyric docx

    public function download() {

        $song = Song::where('id', 104)->with('lyrics')->first();

        // return htmlspecialchars($song->lyrics[0]->lyric);
        //Creating new document...
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        /* Note: any element you append to a document must reside inside of a Section. */

        // Adding an empty Section to the document...
        $section = $phpWord->addSection();
        // Adding Text element to the Section having font styled by default...
        $section->addText($song->title,
    array('name' => 'Tahoma', 'size' => 20, 'bold'=>true)
        );
        $section->addHTML($song->lyrics[0]->lyric,
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

    public function vanila_download ($id) {
        $song = Song::where('id', $id)->with('lyrics')->first();

        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=LYRIC.doc");
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
        echo'<h1>'.$song->title.'</h1>';
        foreach($song->lyrics as $verse){
            
            echo $verse->lyric .'</br> </br></br>';
        }
        echo $song->lyrics[0]->lyric;
        echo '</body>';
        echo '</html>';
        
    }

    //delete song + lyrics
    public function delete($id) {
        try{
        $song = Song::where('id', $id);
        $song->delete();
        }catch(Throwable $exception) {
            return $exception->getMessage();
        }
    }

    //upload music sheet
    public function upload_music_sheet(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $name = $request->name.'.'.$request->image->extension();

        $music_sheet= new MusicSheet;

        $data = [
            'name'=>$name,
            'author'=>$request->author
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

        $name = $request->name.'.'.$request->image->extension();

        $music_sheet= new MusicSheet;

        $data = [
            'name'=>$name,
            'author'=>$request->author
        ];

        $save_music_sheet = $music_sheet->update($data);

        if ($save_music_sheet) {
             // Public Folder
            $request->image->move(public_path('music-sheets'), $name);


            return back()->with('success', 'Image updated Successfully!')
            ->with('image', $name);
        }

       
    }

    public function getLyricsFile() {
        
    }
}
