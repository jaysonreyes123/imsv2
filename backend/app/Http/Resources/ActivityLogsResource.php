<?php

namespace App\Http\Resources;

use App\Http\Helpers\ModuleHelpers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $fields = [];
        if($this->status == 2){
            if(count($this->activity_details)){
                foreach($this->activity_details as $field_){
                    $oldvalue = $field_->oldvalue;
                    $newvalue = $field_->newvalue;
                    $sub_field = [];
                    $sub_field['label'] =   $field_->field;
                    $sub_field['oldvalue'] = $oldvalue;
                    $sub_field['newvalue'] = $newvalue;
                    $fields[] = $sub_field; 
                }
            }
        }
        else if($this->status == 4 || $this->status == 5){
            $related_id = $this->activity_relations->related_item_id;
            $related_module = $this->activity_relations->related_module;
            $related_module_details = ModuleHelpers::module_details_id($related_module);
            $related_model = ModuleHelpers::find($related_id,$related_module_details->name);
            $columns = explode(",",$related_module_details->entityname);
            $entityname = "";
            foreach($columns as $column){
                $entityname = $related_model->$column ." ";
            }
            $fields = array("module" => $related_module_details->label,"entityname" => $entityname);
        }
        return [
            'status' => $this->status,
            'action' => $this->status($this->status),
            'whodid' => $this->whodid_->firstname,
            'created_at' => $this->created_at,
            'fields' => $fields
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
