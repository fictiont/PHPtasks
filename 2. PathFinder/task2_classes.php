<?php
class search
{
    private $size;
    private $matrix;
    private $step;

    function searchWay($x, $y, $x_to, $y_to, $map, $mapSize)
    {
        try
        {
            //заполнение данных
            $this->size['y'] = $mapSize['height'];
            $this->size['x'] = $mapSize['width'];
            $this->matrix[$this->size['x']][$this->size['y']][3];// в 1-й ячейке значение, во 2-й и 3-й координаты предыдущего хода
            $added = true;
            $this->result = true;
            for($i = 0; $i < $this->size['y']; ++$i) //копируем массив map
                for($j = 0; $j < $this->size['x']; ++$j)
                    $this->matrix[$i][$j][0] = $map[$i][$j];
            $this->matrix[$x_to][$y_to][0]= 0;// До финиша 0 шагов - от него идут волны
            $this->step = 0;
          
            //обработка
            // Пока есть путь и не найден старт
            while($added && $this->matrix[$x][$y][0]==-1)
            {

                $added = false;

                for($i = 0; $i < $this->size['y']; ++$i)
                {
                    for($j = 0; $j < $this->size['x']; ++$j)
                    {   
                        if($this->matrix[$i][$j][0] == $this->step) //выбираем шаги с нужным маркером
                        {
                            $i2;
                            $j2;

                            $i2 = $i + 1; $j2 = $j; //низ
                            $added = ($this->processStep($i, $j, $i2, $j2)) ? true : $added;
                            $i2 = $i - 1; $j2 = $j; //верх
                            $added = ($this->processStep($i, $j, $i2, $j2)) ? true : $added;
                            $i2 = $i; $j2 = $j + 1; //право
                            $added = ($this->processStep($i, $j, $i2, $j2)) ? true : $added;
                            $i2 = $i; $j2 = $j - 1; //лево
                            $added = ($this->processStep($i, $j, $i2, $j2)) ? true : $added;
                        }
                    }
                }
                ++$this->step; 
            }

            if($this->matrix[$x][$y][0] != -1) //если есть путь
            {
                $i2=$x;
                $j2=$y;

                while($this->matrix[$i2][$j2][0] != 0)
                {    

                    $iMem = $this->matrix[$i2][$j2][1]; //координата x следующей ячейки
                    $j2 = $this->matrix[$i2][$j2][2]; //координата y следующей ячейки

                    $i2=$iMem;

                    $map[$i2][$j2] = 1; //1 - маркер пути для отображения
                }
                $map[$x][$y] = 1;
            }

            return $map;
        }
        catch(exception $ex)
        {
            throw $ex;
        }
    }

    function processStep($i, $j, $i2, $j2)
    {
        try
        {
            if($i2*$j2 >= 0 && $i2 < $this->size['y'] && $j2 < $this->size['x'])
            {   
                // Если ($i2, $j2) проходимо, то обрабатываем 
                if($this->matrix[$i2][$j2][0] == -1)
                 {
                     $this->matrix[$i2][$j2][0] = $this->step + 1; //маркер следующего шага
                     $this->matrix[$i2][$j2][1] = $i;
                     $this->matrix[$i2][$j2][2] = $j;
                     return true;
                 }
            }
        }
        catch(exception $ex)
        {
            throw $ex;
        }
    }

    function convertMapToRes($map, $mapSize) //преобразует обозначения в более понятные
    {
        try
        {
            for($i = 0; $i < $mapSize['height']; ++$i)
            {
                for($j = 0; $j < $mapSize['width']; ++$j)
                {
                    switch ($map[$i][$j])
                    {
                        case 1:
                            $map[$i][$j] = '*';
                        break;
                        case -2:
                             $map[$i][$j] = 1;
                        break;
                        default:
                             $map[$i][$j] = 0;
                        break;
                    }
                }
            }
            return $map;
        }
        catch (exception $ex)
        {
            throw $ex;
        }
    }
}
?>