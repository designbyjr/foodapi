<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Services\MyCsv;
use App\Services\JSONMapper;

class receipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MyCsv $Mycsv)
    {
        return response()->json($Mycsv->readOne());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Mycsv $Mycsv,JSONMapper $JSONMapper)
    {
        $data = $request->all();
        $validator = Validator::make($request->all(), [

            "created_at" => "min:10|required",
            "updated_at" => "min:10|required",
            "recipe_cuisine" => "min:3|required",
            "box_type" => "min:3|required",
            "gousto_reference" => "required|numeric",
            "season" => "required|min:3",
            "in_your_box" => "nullable",
            "recipe_diet_type_id" => "required",
            "base" => "nullable",
             "title" => "required",
             "slug" => "required",
             "short_title" => "nullable",
             "marketing_description" => "required",
             "bulletpoint1" => "nullable",
             "bulletpoint2" => "nullable",
             "bulletpoint3" => "nullable",
             "preparation_time_minutes" => "numeric|required",
             "shelf_life_days" => "numeric|required",
             "equipment_needed" => "required",
             "origin_country" => "required|min:4",
             "calories_kcal" => "numeric|required",
             "protein_grams" => "numeric|required",
             "fat_grams" => "numeric|required",
             "carbs_grams" => "numeric|required",
             "protein_source" => "required"

        ]);

        if ($validator->fails()) {
            abort(400);
        }
        //check keys match cols
        $Mycsv->checkKeys($request->all());

        $count = $Mycsv->getCount();
        //prepend id to array
        $array = ['id' => $count] + $data;

        return $JSONMapper->mapCheck($Mycsv->CreateRows($array),$request);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource id.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function showId(Request $request, MyCsv $Mycsv, JSONMapper $JSONMapper, string $data)
    {
        $idArr = explode(',',$data);

        if( is_numeric($data) ) return $JSONMapper->mapCheck( $Mycsv->readOne($data), $request);
        if(count($idArr) > 1 && is_numeric($idArr[0])) return $JSONMapper->mapCheck($Mycsv->readMany($idArr), $request);
        
        return abort(400);
    }
    

    /**
     * Display the specified cuisine resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function showCuisine(Request $request, MyCsv $Mycsv, JSONMapper $JSONMapper, string $data,  string $page = null)
    {
        $idArr = explode(',',$data);
        $cuisines = $Mycsv->readCuisine($data);
        $keys = $cuisines->keys();
        $count = $cuisines->count();
        if(!array_key_exists($count, $keys->all()) && $page >= $count) return abort(404);

        if( !is_numeric($data) && !empty($idArr) ) return $JSONMapper->mapCheck($cuisines,$request,1,$page); 

        return abort(400);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MyCsv $Mycsv, JSONMapper $JSONMapper)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|min:1',
            'modify' => 'array|min:1'
        ]);

        if ($validator->fails()) {
            abort(400);
        }

        $cols = $request->get('modify');
        $id = $request->get('id');
        //now check if keys are valid in modify obj
        $Mycsv->checkKeys($cols);

        //get all collection data
        $data = collect( $Mycsv->readOne() );

        // now update the rows
        $Mycsv->UpdateRows($data,$cols,$id);

        //now return updated row
        return $JSONMapper->mapCheck( $Mycsv->readOne($id), $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
