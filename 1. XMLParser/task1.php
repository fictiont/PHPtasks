<?php	
	$start = microtime(true); //засекаем время
	require_once "task1_classes.php";

	define (LIBXML_PARSEHUGE, 1); //чтобы убрать ограничение на вложенность рекурсии

	//настройки и проверка файлов 
	$inputFile = "input.txt";
	$pathToOutput = "task1.xml";
	if (!file_exists($inputFile))
	{
	    exit("Указан неправильный файл");
	}
	$mapFile = file($inputFile);
	if (!isset($mapFile) || empty($mapFile))
	{
	    exit("Указан неправильный или пустой файл");
	}
	
	//подготовка данных
	$inputFileData = array_unique(file($inputFile));
	//shuffle($inputFileData);  //для проверки алгоритма можно перемешивать данные из файла
	$myTable = new myTable();

	try
	{
		//обработка данных
		foreach ($inputFileData as $string)
		{
			$nodes = explode('.',$string);
			$lastNode = $myTable->return_main_row(); //указатель на последний добавленный узел

			process_node($nodes, 0);
		}

		//сохраняем в файл
		if (!$myTable->saveToFile($pathToOutput))
			exit("Error when saving file, check the output path");

		$time = microtime(true) - $start;
		printf('Скрипт выполнялся %.4F сек.', $time);
	}
	catch(exception $ex)
	{
		echo "ОШИБКА: ",$ex->getMessage();
	}

	function process_node($nodes, $i)
	{
		global $myTable, $lastNode, $rows;
		$newCleanedNode = trim($nodes[$i]); //очищаем от всяких системных символов

		if (!empty($newCleanedNode)) 		//проверяем на пустоту
		{
			$flag = false; //индикатор поиска
			if ($lastNode->hasChildNodes())
				foreach($lastNode->childNodes as $childNode)
					if ($childNode->nodeName == "row")
						foreach($childNode->childNodes as $cell) 
						{
							if ($cell->textContent == $newCleanedNode) //проверяем все ячейки и находим нужную
							{
								$lastNode = $childNode;//перемещаем указатель, т.к. элемент уже есть
								$flag = true;          //элемент найден
							}							
						}
			if ($flag == false) //если ячейка не была найдена, то добавляем
			{
				$newCell = $myTable->add_cell($newCleanedNode, $lastNode);
				$lastNode = (isset($newCell)) ? $newCell : $lastNode;
			}
		}
		if ($i < count($nodes)-1) //условие выхода
			process_node($nodes, ++$i);
	}
?>