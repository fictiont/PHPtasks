<?php
    require_once "task2_classes.php";
//Настройки
$inputFile = "map.dat";
$pathToOutput = "mapResult.dat";
file_put_contents($pathToOutput, '');

//Подготовка данных
if (!file_exists($inputFile))
{
    exit("Указан неправильный файл");
}
$mapFile = file($inputFile);
if (!isset($mapFile) || empty($mapFile))
{
    exit("Указан неправильный или пустой файл");
}
$mapSize = array('height'=>count($mapFile),
                'width'=>strlen(trim($mapFile[0])), 
                'max'=>max(count($mapFile), strlen($mapFile[0])-2)
              );
$mapArray[$mapSize['height']][$mapSize['width']];
$mapStart = array('x'=>0,'y'=>0);
$mapFinish = array('x'=>0,'y'=>0);

//Парсинг файла
for($i = 0; $i < $mapSize['height']; ++$i)
{
    for($j = 0; $j < $mapSize['width']; ++$j)
    {
        switch ($mapFile[$i][$j])
        {
            case 'S':
                $mapStart['x'] = $i; 
                $mapStart['y'] = $j;
                $mapArray[$i][$j] = -1;
            break;
            case 'F':
                $mapFinish['x'] = $i; 
                $mapFinish['y'] = $j;
                $mapArray[$i][$j] = -1;
            break;
            case '0':
                $mapArray[$i][$j] = -1;
            break;
            default:
                 $mapArray[$i][$j] = -2;
        }

    }
}

//отображение начальных данных

for($i = 0; $i < $mapSize['height']; ++$i)
{
    echo "<br>";
    for($j = 0; $j < $mapSize['width']; ++$j)
    {
        echo $mapFile[$i][$j], "\t";
    }
}
echo "<br><br>";

//обработка
try
{
    $searchEngine = new search();
    $map2 = $searchEngine->SearchWay($mapStart['x'], $mapStart['y'], $mapFinish['x'], $mapFinish['y'], $mapArray, $mapSize);
    $map2 = $searchEngine->convertMapToRes($map2, $mapSize);
}
catch(exception $ex)
{
    echo "ОШИБКА: ", $ex->getMessage();
}

//отображение результата и сохранение в файл
for($i = 0; $i < $mapSize['height']; ++$i)
{
    echo "<br>";
    if ($i != 0)
        file_put_contents($pathToOutput, "\n", FILE_APPEND);
    for($j = 0; $j < $mapSize['width']; ++$j)
    {
        echo $map2[$i][$j], "\t";
        file_put_contents($pathToOutput, $map2[$i][$j], FILE_APPEND);
    }
}

//сохраняем в файл


?> 
