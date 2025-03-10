<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ActivityLogsHelpers;
use App\Http\Helpers\ModuleHelpers;
use App\Http\Resources\CommentResource;
use App\Http\Traits\HttpResponse;
use App\Http\Traits\RelatedEntries;
use App\Models\Comment;
use App\Models\RelatedEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    //
    use HttpResponse,RelatedEntries;
    public function index($module,$id){
        $module_details = ModuleHelpers::module_details_name($module);
        $comment_model = Comment::with('users')->where('module',$module_details->id)
        ->where("module_id",$id)
        ->where("comment_id",0)
        ->where('deleted',0)
        ->orderByDesc('created_at')
        ->paginate(10);
        return CommentResource::collection($comment_model);
    }
    public function comment_reply_index($commentid){
        $comment_model = Comment::where('comment_id',$commentid)->where('deleted',0)->orderByDesc('created_at')->paginate(10);
        return CommentResource::collection($comment_model);
    }
    public function delete(string $module,string $related_module,string $module_id,string $related_id){
        $model = Comment::find($related_id);
        $model->deleted = 1;
        if($model->save()){
            $module_details = ModuleHelpers::module_details_name($module);
            $model = RelatedEntry::where("module",$module_details->id)
            ->where("related_module",11)
            ->where("module_id",$module_id)
            ->where("related_id",$related_id)
            ->delete();

            ActivityLogsHelpers::log($module_id,$module,$status = 5,related_module:'comments',related_item_id:$related_id);
        }
        return $this->response([]);
    }
    public function save(Request $request){
       $module_details = ModuleHelpers::module_details_name($request->module);
       $comment_module_details = ModuleHelpers::module_details_name('comments');
       $module_id = $request->module_id;
       $comment = $request->comment;
       $comment_model = $request->action == 0 ?  new Comment() : Comment::find($request->comment_id) ;
       if($request->action == 0){
            $comment_model->module = $module_details->id;
            $comment_model->module_id = $module_id;
            $comment_model->comment = $comment;
            $comment_model->comment_id = $request->comment_id;
            $comment_model->action = $request->action;
            $comment_model->comment_by = Auth::id();
            if($comment_model->save()){
            $this->save_related_entries($module_id,$request->module,$comment_model->id,'comments');
            ActivityLogsHelpers::log($module_id,$request->module,$status = 4,related_module:'comments',related_item_id:$comment_model->id);
            }
       }
       else{
            $comment_model->comment = $request->comment;
            $comment_model->action = $request->action;
            $comment_model->save();
       }
       return $this->response(new CommentResource($comment_model));
    }
}
