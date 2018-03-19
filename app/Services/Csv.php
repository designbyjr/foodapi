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
		$this->csvWriter = Writer::createFromPath('../data.csv','w+');
	}


	/*
	** Test this update function
	*/	
	public function CreateRows(collection $collection, array $rows)
	{	
		$body = $collection->push($rows);

		return $this->writeToCsv($body);
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

	public function test()
	{	$test = collect($this->fixColsToMlTime(collect($this->reader->fetchOne(3))));
		return $this->CreateRows($this->getAllResults(),$test->all());
	}

	//fix this
	private function fixColsToDateTime(collection $collection)
	{	
		$keys = $this->getKeys();

		return $collection->transform(function($item,$key) use ($keys){
				$array = array_combine($keys,$item);
				$array["created_at"] = Date('d/m/Y H:i:s',substr($array["created_at"],0,10));
				$array["updated_at"] = Date('d/m/Y H:i:s',substr($array["updated_at"],0,10));
				return $array;
			});


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
	public function readCuisine(array $cuisine)
	{
		$collection = $this->getAllResults();
		$collection = $collection->whereIn('recipe_cuisine',$cuisine);
		return $collection;
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
		return !isset($id) ? $this->getAllResults() : $this->fixColsToMlTime( collect( $this->reader->fetchOne($id) ) );	
	}

	/*
	** Test this update function
	*/	
	public function UpdateRows(collection $collection, array $rows)
	{
		$body = $collection->transform(function($item,$key) use ($rows){
				if( array_key_exists( $key, array_keys($rows) ) )
				{
					return $rows[$key];
				}
				return $item;
			});

		return $this->writeToCsv($body);
	}

	/*
	** This writes the csv file with headers and body.
	*/
	private function writeToCsv(collection $body)
	{
		$header = $this->getKeys();
		$body = $this->fixColsToDateTime($body);
		$body->prepend($header);

		//we insert the CSV header
		$this->csvWriter->insertAll($body->all());
	}

}