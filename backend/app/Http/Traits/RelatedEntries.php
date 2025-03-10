<?php

namespace App\Http\Traits;

use App\Http\Helpers\ModuleHelpers;
use App\Models\RelatedEntry;

trait RelatedEntries
{
    //
    protected function save_related_entries($id,$module,$related_id,$related_module){
        $module_id = ModuleHelpers::module_details_name($module)->id;
        $relation_module_id = ModuleHelpers::module_details_name($related_module)->id;
        $relation_entries = new RelatedEntry();
        $relation_entries->module_id = $id;
        $relation_entries->module = $module_id;
        $relation_entries->related_id = $related_id;
        $relation_entries->related_module = $relation_module_id;
        $relation_entries->save();
    }
}
