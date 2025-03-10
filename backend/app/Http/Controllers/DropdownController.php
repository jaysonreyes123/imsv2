<?php

namespace App\Http\Controllers;

use App\Http\Resources\DropdownResource;
use App\Http\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DropdownController extends Controller
{
    //
    use HttpResponse;
    public function get_dropdown($field){
        $model = [];
        try {
            $model = DB::table($field);
            if($field == 'users'){
                $model->orderBy('firstname');
            }
            else{
                $model->orderBy('label');
            }
            $model = $model->get();
           
        } catch (\Throwable $th) {
            //throw $th;
        }
        if($field == 'users'){

        }
        else{

        }
        return $this->response(DropdownResource::collection($model)->additional(['field'=>$field]));
    }
}
