<?php

namespace App\Services;

use Illuminate\Http\Request;

class JSONMapper {

	private $data;
	private $items;

	private function getMap($collection)
	{	
			return	[
						"id" => $collection->get('id'),
						"created_at" => $collection->get('created_at'),
						"updated_at" => $collection->get('updated_at'),
						"recipe_cuisine" => $collection->get('recipe_cuisine'),
						"box_type" => $collection->get('box_type'),
						"gousto_reference" => $collection->get('gousto_reference'),
						"season" => $collection->get('season'),
						"rating" => rand(1,5),
						"ingredients" => [
							"in_your_box" => $collection->get('in_your_box'),
							"recipe_diet_type_id" => $collection->get('recipe_diet_type_id'),
							"base" => $collection->get('base')
						],
					"content" => [
						"title" => $collection->get('title'),
						"slug" => $collection->get('slug'),
						"short_title" => $collection->get('short_title'),
						"marketing_description" => $collection->get('marketing_description'),
						"bulletpoint1" => $collection->get('bulletpoint1'),
						"bulletpoint2" => $collection->get('bulletpoint2'),
						"bulletpoint3" => $collection->get('bulletpoint3'),
						"preparation_time_minutes" => $collection->get('preparation_time_minutes'),
						"shelf_life_days" => $collection->get('shelf_life_days'),
						"equipment_needed" => $collection->get('equipment_needed'),
						"origin_country" => $collection->get('origin_country'),
					],
					"nutrition" => [
						"calories_kcal" => $collection->get('calories_kcal'),
						"protein_grams" => $collection->get('protein_grams'),
						"fat_grams" => $collection->get('fat_grams'),
						"carbs_grams" => $collection->get('carbs_grams'),
						"protein_source" => $collection->get('protein_source')
					]
				];

		//end of get map
	}

	private function getLinks($request,int $pagenumber = null, int $lastpage = null, $cuisine = null)
	{


		if(isset($cuisine))
		{
			$patharray = explode('/', $request->path());
			$path = url('/')."/".$patharray[0]."/".$patharray[1]."/";
			$nextpage = ($pagenumber + 1) <= $lastpage ?  $path .($pagenumber + 1) : null ;
			$urlcount = count($patharray);

			return [
				"self" => $request->url(),
				"first" => $urlcount == 3 ? $request->url() : $path."1",
				"last" => $urlcount == 2 ? null : $path.$lastpage,
				"prev" => ($pagenumber - 1) == 0 || is_null($pagenumber) ? null : $path . $pagenumber,
				"next" => $nextpage
			];	
		}

		return [ "self" => $request->url() ];	
		
	}

	public function mapCheck($collection, Request $request, $cuisine = null, $pagenumber = null)
	{
		
		$count = count($collection->keys()) - 1;

		if(array_key_exists($count, $collection->all()))
		{
			$this->data = $collection->transform(function($item,$key) {

				return $this->getMap(collect($item));
			});

			$this->items = $collection->count();
		}
		else
		{
			$this->data = $this->getMap($collection);
			$this->items = 1;
		}

		$links = $this->getLinks($request,$pagenumber,$this->items,$cuisine);
		
		$map =[	"response" => 200,
				"links" => $links,
				"data" => [ 
							$this->data
							]
			];

		return response()->json([$map],200);
	}



}