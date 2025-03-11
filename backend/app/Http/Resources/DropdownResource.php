<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DropdownResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        if($request->field == "users"){
            return [
                "id" => $this->id ?? "",
                "label" => $this->firstname ?? ""
            ];
        }
        else{
            return [
                "id" => $this->id ?? "",
                "label" => $this->label ?? "",
                "parent_id" => $this->parent_id ?? ""
            ];
        }
    }
}
