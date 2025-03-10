<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ActivityLogsHelpers;
use App\Http\Traits\HttpResponse;
use App\Http\Traits\RelatedEntries;
use App\Http\Traits\Table;
use App\Models\Media;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    //
    use HttpResponse,RelatedEntries,Table;
    public function related_list(Request $request,$module,$related_module,$id){
        $model = Media::with('assigned_to');
        return $this->related_list_function($id,$model,$module,$related_module,$request);
    }
    public function save(Request $request){
        $date = Carbon::now();
        $file_path = $date->year."/".$date->month."/files";
        if($request->hasFile('files')){
            $id = $request->id;
            $files = $request->file('files');
            foreach($files as $file){
                if($path = $file->store($file_path,["disk" => "public"])){
                    $model = new Media();
                    $filename = $file->getClientOriginalName();
                    $filename = explode(".",$filename)[0];
                    $extension = $file->getClientOriginalExtension();
                    $filetype = $file->getClientMimeType();
                    $model->filename = $filename;
                    $model->extension = $extension;
                    $model->filetype = $filetype;
                    $model->path = asset('storage')."/".$path;
                    $model->filetitle = $request->filetitle;
                    $model->note = $request->note;
                    $model->assigned_to = $request->assigned_to;
                    $model->save();
                    $this->save_related_entries($id,$request->module,$model->id,'media');
                    ActivityLogsHelpers::log($id,$request->module,$status = 4,related_module:'media',related_item_id:$model->id);
                }
            }
        }
        return $this->response([]);
    }
}
