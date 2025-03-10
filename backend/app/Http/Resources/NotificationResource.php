<?php

namespace App\Http\Resources;

use App\Http\Helpers\ModuleHelpers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $module_details = $this->module_;
        $columns = explode(",",$module_details->entityname);
        $model = ModuleHelpers::find($this->module_item_id,$module_details->name);
        $entityname = "";
        foreach($columns as $column){
            $entityname.=$model->$column." ";
        }
        return [
            "id" => $this->id,
            "module" => $this->module_->name,
            "itemid" => $this->module_item_id,
            "label" => $this->module_->label,
            "name" => $entityname,
            "status" => $this->status,
            "timestamp" => $this->created_at->diffForHumans() ?? "",
            "icon" => $this->module_->icon
        ];
    }
}
