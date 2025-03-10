<?php

namespace App\Http\Resources;

use App\Http\Helpers\ModuleHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardActivityLogsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $fields = [];
        $id = "";
        $module = "";
        $related_id = "";
        $related_model = "";
        $related_entityname = "";
        $entityname = "";
        if($this->status == 4 || $this->status == 5){
            $related_id = $this->activity_relations->related_item_id;
            $related_module = $this->activity_relations->related_module;
            $related_module_details = ModuleHelpers::module_details_id($related_module);
            $related_model = ModuleHelpers::find($related_id,$related_module_details->name);
            $columns = explode(",",$related_module_details->entityname);
            foreach($columns as $column){
                $related_entityname = $related_model->$column ." ";
            }
        }
        $module_details = ModuleHelpers::module_details_id($this->module);
        $module_model = ModuleHelpers::find($this->itemid,$module_details->name);
        $columns = explode(",",$module_details->entityname);
        foreach($columns as $column){
            $entityname = $module_model->$column ." ";
        }

        return [
            'status' => $this->status,
            'action' => $this->status($this->status),
            'whodid' => $this->whodid_->firstname,
            'created_at' => $this->created_at,
            'entityname' => $entityname,
            'related_entityname' => $related_entityname,
            'timestamp' => Carbon::parse($this->created_at)->diffForHumans(),
        ];
    }
    public function status($status){
        $action = "";
        switch ($status) {
            case 1:
                $action = 'Created';
                break;
            case 2:
                $action = 'Updated';
                break;
            case 3:
                $action = 'Deleted';
                break;
            case 4:
                $action = 'Linked';
                break;
            case 5:
                $action = 'Unlinked';
                break;
            case 6:
                $action = 'Imported';
                break;
            default:
                # code...
                break;
        }
        return $action;
    }
}
