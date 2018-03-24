<?php

namespace App\Services;

use League\Csv;
use League\Csv\Reader;
use Illuminate\Support\Collection;
use League\Csv\Writer;

class MyCsv {

	private $reader;
	private $csvWriter;

	public function __construct()
	{
		$this->reader = Reader::createFromPath('../recipe-data.csv', 'r+');
		$this->csvWriter = Writer::createFromPath('../ecipe-data.csv','w+');
	}


	/*
	** Test this update function
	*/	
	public function CreateRows($rows)
	{	
		
		$collection = $this->getAllResults();
		$body = $collection->push($rows);
		
		$this->writeToCsv($body);
		return $body;
	}

	/*
	** Test this update function
	*/	
	public function CreateColumn(string $column)
	{	
		$collection = $this->getAllResults();
		//get current columns as keys
		$keys = collect($this->getKeys());
		$header = $keys->push($column)->all();

		//transform collection to have extra coloumn on each row
		$body = $collection->transform(function($item,$key) use ($column){
			$item[$column] = null;
			return $item;
		});
		// //add new columns
		$body->prepend($header);

		//we insert the CSV header
		$this->csvWriter->insertAll($body->all());

		return $body;
	}

	public function checkKeys(array $array)
	{
		$cols = array_flip($this->getKeys());
		foreach ($array as $key => $value) {
           if(!array_key_exists($key, $cols)) return abort(400);
        }
	}

	/*
	** This gets fixes the results to have millsecond time and fixes array keys with coloumns.
	*/
	private function fixColsToMlTime(collection $collection)
	{	
		$keys = $this->getKeys();
		$singleCount = count($keys) - 1;

		// For mutlidimensional arrays, single count checks if count matches number of items
		// in single array vs mutlidimensional arrays.

		if(!array_key_exists($singleCount, $collection->all())) {
			return $collection->transform(function($item,$key) use ($keys){
				$array = array_combine($keys,$item);
				$array["created_at"] = strtotime(str_replace("/", "-",$array["created_at"]));
				$array["updated_at"] = strtotime(str_replace("/", "-",$array["updated_at"]));
				return $array;
			});
		}

		// For single array collection

			$array = $collection->all();
			$array = array_combine($keys,$array);
			$array["created_at"] = strtotime(str_replace("/", "-",$array["created_at"]));
			$array["updated_at"] = strtotime(str_replace("/", "-",$array["updated_at"]));
			return $array;


	}

	public function test($json)
	{	
		return $this->fixColsToDateTime( collect(json_decode($json)));
	}

	//fix this
	private function fixColsToDateTime(collection $collection)
	{	
		$keys = $this->getKeys();
		$singleCount = count($keys) - 1;

		if(!array_key_exists($singleCount, $collection->all())) {
			
			return $collection->transform(function($item,$key) use ($keys){
					$array = array_combine($keys,$item);
					$array["created_at"] = Date('d/m/Y H:i:s',(int)substr((string)$array["created_at"],0,10));
					$array["updated_at"] = Date('d/m/Y H:i:s',(int)substr((string)$array["updated_at"],0,10));
					return $array;
				});
		}

		// For single array collection

		$array = $collection->all();
		$array = array_combine($keys,$array);
		$array["created_at"] = Date('d/m/Y H:i:s',(int)substr((string)$array["created_at"],0,10));
		$array["updated_at"] = Date('d/m/Y H:i:s',(int)substr((string)$array["updated_at"],0,10));
		return $array;

	}

	public function getCount()
	{
		return count($this->readOne());
	}

	/*
	** This gets the keys from the first row, allowing order to be interchangeable.
	** Thereby futureproofing the order in which coloums are displayed.
	*/
	private function getKeys()
	{	
		$data = $this->reader->fetchAll();
		return array_shift($data);
	}

	/*
	** This gets all the results, and shifts the first row.
	*/
	private function getAllResults()
	{	
		$data = $this->reader->fetchAll();
		array_shift($data);
		$collection = collect($data);
		return $this->fixColsToMlTime($collection);
	}

	/*
	** This reads one or more results by cuisine name and returns it.
	*/
	public function readCuisine($cuisine)
	{
		$collection = $this->getAllResults();
		$result = $collection->whereIn('recipe_cuisine',$cuisine);
		return $result;
	}

	/*
	** This reads more than one result by id and returns it.
	*/
	public function readMany(array $ids)
	{
		$collection = $this->getAllResults();
		$collection = $collection->whereIn('id',$ids);
		return $collection;
	}

	/*
	** This reads one result by ID and returns it, if null it will return all.
	*/
	public function readOne( int $id = null )
	{
		return !isset($id) ? $this->getAllResults() : collect($this->fixColsToMlTime( collect( $this->reader->fetchOne($id) ) ) );	
	}

	/*
	** Test this update function
	*/	
	public function UpdateRows($collection, array $rows, string $id)
	{	

		$body = $collection->map(function($item,$key) use ($rows,$id){
				if( $item['id'] == $id )
				{
					foreach ($rows as $key => $value) {
						$item[$key] = $rows[$key];
					}
					
					return $item;
				}
				return $item;
		});

		$this->writeToCsv($body);
		
		return $body;
	}

	/*
	** This writes the csv file with headers and body.
	*/
	private function writeToCsv($body)
	{
		$header = $this->getKeys();
		$body = $this->fixColsToDateTime(collect($body));
		$body->prepend($header);

		//we insert the CSV header
		$this->csvWriter->insertAll($body->all());
		return true;
	}

}