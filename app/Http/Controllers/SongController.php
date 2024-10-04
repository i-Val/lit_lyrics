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
    public function index() {
        $songs = Song::where('id', '>', 0)->get();
        return view('lyrics-table', compact('songs'));
    }

    public function create() {
        return view('add-song');
    }
    //add song + lyrics
    public function addSong(Request $request) {
        try{

            //save song title and author
            $song = new Song;

            if($request->file('score') != null){

             $image_name = $request->file('score')->getClientOriginalName();
            
            $data = [
            'title' => $request->title,
            'author' => $request->author,
            'verses' => $request->verses,
            'music_sheet' => "public/music-sheets/$image_name",
            'category' => $request->category,
            ];
            
            }else{
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
                    'public/music-sheets', $image_name
                );
            }

            return back()->with('success', 'lyrics added successfully!');
            // return $verses;
            // array_shift($verses);
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }

    //search song 
    public function searchSong(Request $request) {
         $songs = Song::where('title', 'LIKE', '%'.$request->search_query.'%')->get();
         $search_term = $request->search_query;
        return view('search-results', compact('songs', 'search_term'));
    }

    //view song + lyrics
    public function viewSong($id) {
        $song = Song::where('id', $id)->first();
        return view('lyric-details', compact('song'));
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

        $song = Song::where('id', 1)->first();

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
        $section->addHTML($song->verses,
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

    public function single_download ($id) {
        $song = Song::where('id', $id)->first();

        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=.$song->title.doc");
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
            
            echo $song->verses .'</br> </br></br>';
        echo '</body>';
        echo '</html>';

        // $filePath = 'word_export.doc';

// Save the content to the Word file
    // file_put_contents($filePath, $wordContent);
        
    }

    public function collection_download(Request $request) {
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
            if($entrance!=null){
                echo'<h1>Entrance:</h1>';
                echo'<h1>'.$entrance->title.'</h1>';
                
                echo $entrance->verses .'</br> </br></br>';
            }else{
                echo'<h1>Entrance:Record Not Found</h1>';
            }
            if($kyrie!=null){
                echo'<h1>Kyrie:</h1>';
                echo'<h1>'.$kyrie->title.'</h1>';
                
                echo $kyrie->verses .'</br> </br></br>';
            }else{
                echo'<h1>Kyrie:Record Not Found</h1>';
            }
            if($gloria!=null){
                echo'<h1>Gloria:</h1>';
                echo'<h1>'.$gloria->title.'</h1>';
                
                echo $gloria->verses .'</br> </br></br>';
            }else{
                echo'<h1>Gloria:Record Not Found</h1>';
            }
            if($creed!=null){
                echo'<h1>Creed:</h1>';
                echo'<h1>'.$creed->title.'</h1>';
                
                echo $creed->verses .'</br> </br></br>';
            }else{
                echo'<h1>Creed:Record Not Found</h1>';
            }
            if($offertory!=null){
                echo'<h1>Offertory:</h1>';
                echo'<h1>'.$offertory->title.'</h1>';
                
                echo $offertory->verses .'</br> </br></br>';
            }else{
                echo'<h1>Offertory:Record Not Found</h1>';
            }
            if($consecration!=null){
                echo'<h1>Consecration:</h1>';
                echo'<h1>'.$consecration->title.'</h1>';
                
                echo $consecration->verses .'</br> </br></br>';
            }else{
                echo'<h1>Consecration:Record Not Found</h1>';
            }
            if($communion!=null){
                echo'<h1>Communion:</h1>';
                echo'<h1>'.$communion->title.'</h1>';
                
                echo $communion->verses .'</br> </br></br>';
            }else{
                echo'<h1>Communion:Record Not Found</h1>';
            }
            if($dismissal!=null){
                echo'<h1>Dismissal:</h1>';
                echo'<h1>'.$dismissal->title.'</h1>';
                
                echo $dismissal->verses .'</br> </br></br>';
            }else{
                echo'<h1>Dismissal:Record Not Found</h1>';
            }
            echo '</body>';
            echo '</html>';


       
           
    }

    public function collection_form () {
        return view('collection-form');
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
