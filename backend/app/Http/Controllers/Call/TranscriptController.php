<?php

namespace App\Http\Controllers\Call;

use App\Http\Controllers\Controller;
use App\Http\Resources\TranscriptResource;
use App\Http\Traits\HttpResponse;
use App\Models\RelatedEntry;
use App\Models\Transcript;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class TranscriptController extends Controller
{
    //
    use HttpResponse;
    public function get(string $id){
        $related_id = 1;
        $model = null;
        $related_entry = RelatedEntry::where('module_id',$id)
        ->where('related_id',$related_id)
        ->where('module',1)
        ->where('related_module',14)
        ->first();
        if($related_entry){
            $model = Transcript::find($related_entry->related_id);
        }
        return $this->response(new TranscriptResource($model));
    }
    public function index($id){
        ini_set('max_execution_time', '600');//10 minutes
        // $url =  base_path('vosk-api/transcript.py');
        // $url2 = "https://microbizone.com/imsapi/testwav.wav";
        // $test = shell_exec("py $url $url2");
        // return $test;
        // $test2 = json_decode($test);
        // return $test2;
        $final_filename = "incident_$id.txt";
        $path = Storage::disk('public')->path("transcript/$final_filename");
        if(File::exists($path)){
            return response()->download($path);
        }
        $dir = 'storage/transcript';
        $audio = "https://audio-samples.github.io/samples/mp3/blizzard_unconditional/sample-0.mp3";
        $filename_parse = explode("/",$audio);
        $filename = end($filename_parse);
        $filename = explode(".",$filename)[0];
        $filename = "$filename.json";
        $script = escapeshellcmd("whisper $audio --model tiny --fp16 False --output_format json --output_dir $dir ");
        exec($script,$output,$status);
        if($status == 0){
           $output = json_decode(file_get_contents("$dir/$filename"),true);
           $temp_file = Storage::disk('public')->path("transcript/$filename");
           if(FIle::exists($temp_file)){
                $storage = Storage::disk("public")->put("transcript/$final_filename",$output["text"]);
                if($storage){
                    File::delete($temp_file);
                }
           }
           $path = Storage::disk("public")->path("transcript/$final_filename");
           return response()->download($path);
        }
        else{
           return  $this->response("An unexpected error occurred. Please try again.",422);
        }
    }
}
