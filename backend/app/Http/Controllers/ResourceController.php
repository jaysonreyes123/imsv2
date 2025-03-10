<?php

namespace App\Http\Controllers;

use App\Http\Traits\HttpResponse;
use App\Http\Traits\RelatedEntries;
use App\Http\Traits\Table;
use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use HttpResponse,RelatedEntries,Table;
    public function related_list(Request $request,$module,$related_module,$id){
        $model = Resource::query();
        return $this->related_list_function($id,$model,$module,$related_module,$request);
    }
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
