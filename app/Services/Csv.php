<?php

namespace App\Services;

use League\Csv;
use League\Csv\Reader;
use Illuminate\Support\Collection;
use Iterator;

class MyCsv {

	private $csvObj;
	private $reader;

	public function __construct()
	{
		$this->reader = Reader::createFromPath('../recipe-data.csv', 'r+');
	}

	
	
	public function read( int $id = 1 )
	{
		return $this->reader->fetchOne($id);	
	}

}