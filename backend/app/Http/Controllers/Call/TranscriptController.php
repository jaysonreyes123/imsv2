<?php

namespace App\Http\Controllers\Call;

use App\Http\Controllers\Controller;
use App\Http\Resources\TranscriptResource;
use App\Http\Traits\HttpResponse;
use App\Models\RelatedEntry;
use App\Models\Transcript;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use OpenAI;

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
    public function view($id){
        return $this->index($id,'view');
    }
    public function download($id){
        return $this->index($id,'download');
    }
    public function index($id,$action){
        ini_set('max_execution_time', '600');//10 minutes
        $final_filename = "incident_$id.txt";
        $path = Storage::disk('public')->path("transcript/$final_filename");
        if(File::exists($path)){
            if($action == 'view'){
                $url = Storage::disk("public")->url("transcript/$final_filename");
                return $this->response($url);
            }
            else{
                return response()->download($path);
            }
            
        }
        $dir = 'storage/transcript';
        // $audio = "https://audio-samples.github.io/samples/mp3/blizzard_unconditional/sample-0.mp3";
        $audio = "https://voiceovers.asia/wp-content/uploads/2022/09/AYA_Tagalog_Voice_Sample.mp3";
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
           if($action == "view"){
                $url = Storage::disk("public")->url("transcript/$final_filename");
                return $this->response($url);
           }
           else{
                $path = Storage::disk("public")->path("transcript/$final_filename");
                return response()->download($path);
           }
        }
        else{
           return  $this->response("An unexpected error occurred. Please try again.",422);
        }
    }
    public function test(){
        $yourApiKey = "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";
        $headers = [
            'Authorization: Bearer ' . $yourApiKey,
        ];

        $ch = curl_init();

        // Set the URL
        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/audio/transcriptions');

        // Set the request method to POST
        curl_setopt($ch, CURLOPT_POST, 1);

        // Set the headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Prepare the request body with the file and model
        $data = [
            'file' => "https://voiceovers.asia/wp-content/uploads/2022/09/AYA_Tagalog_Voice_Sample.mp3",
            'model' => 'whisper-1',
        ];

        // Set the request body
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // Set option to return the result instead of outputting it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL request and get the response
        $response = curl_exec($ch);

        // Check if any error occurred during the request
        if(curl_errno($ch)){
            echo 'Request Error:' . curl_error($ch);
        }

        // Close the cURL session
        curl_close($ch);
        var_dump($response); 
        }
}
