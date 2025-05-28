<?php

namespace App\Observers;

use App\Events\NotificationEvent;
use App\Http\Helpers\ModuleHelpers;
use App\Models\Incident;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class IncidentObserver
{
    /**
     * Handle the Incident "created" event.
     */
    public function created(Incident $incident): void
    {
        //
        $module_detail  = ModuleHelpers::module_details_name('incidents');
        $model = new Notification();
        $model->module = $module_detail->id;
        $model->module_item_id = $incident->id;
        if($model->save()){
            // event(new NotificationEvent($model));
        }
    }

    /**
     * Handle the Incident "updated" event.
     */
    public function updated(Incident $incident): void
    {
        //
    }

    /**
     * Handle the Incident "deleted" event.
     */
    public function deleted(Incident $incident): void
    {
        //
    }

    /**
     * Handle the Incident "restored" event.
     */
    public function restored(Incident $incident): void
    {
        //
    }

    /**
     * Handle the Incident "force deleted" event.
     */
    public function forceDeleted(Incident $incident): void
    {
        //
    }
}
