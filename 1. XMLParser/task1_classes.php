<?php
	class myTable
	{
		private $dom;
		private $rows;

		function __construct()
		{
			try
			{
				$this->dom = new DOMDocument('1.0');
				$this->rows = $this->dom->appendChild($this->dom->createElement('rows')); 
			}
			catch(DOMException $ex)
			{
				throw $ex;
			}
		}

		function add_cell($name, $node)
		{
			try
			{
				$row = $node->appendChild($this->dom->createElement('row')); 
				$cell = $row->appendChild($this->dom->createElement('cell'));
				$cell->appendChild($this->dom->createTextNode($name)); 
				return $row;
			}
			catch(DOMException $ex)
			{
				throw $ex;
			}
				
		}

		function return_main_row()
		{
			return $this->rows;
		}

		function saveToFile($path)
		{
			try
			{
				$this->dom->formatOutput = true; // установка атрибута formatOutput
				$this->dom->saveXML(); // передача строки в test1 
				$this->dom->save($path); // сохранение файла 
				return true;
			}
			catch(exception $ex)
			{
				throw $ex;
			}
		}
	}
?>