<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "action" => $this->action,
            "comment" => $this->comment,
            "comment_id" => $this->comment_id,
            "comment_by" => $this->comment_by,
            "comment_by_name" => $this->users->firstname,
            "reply" => [],
            "reply_count" => Comment::where("comment_id",$this->id)->count(),
            "current_page" => 0,
            "timestamp" => $this->created_at->diffForHumans(),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at->format("F d Y h:i:s a")
        ];
    }
}
