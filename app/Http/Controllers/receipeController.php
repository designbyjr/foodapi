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
    public function create()
    {
        //
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
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|min:1|array'
        ]);

        if ($validator->fails()) {
            return response()->withErrors($validator);

        }

        return "{'yes':'pass'}";
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
