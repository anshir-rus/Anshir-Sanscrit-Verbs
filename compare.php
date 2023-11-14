<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <style>
    table.table-fit {
  width: auto !important;
  table-layout: auto !important;
}
table.table-fit thead th,
table.table-fit tbody td,
table.table-fit tfoot th,
table.table-fit tfoot td {
  width: auto !important;
}
</style><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <style>
    table.table-fit {
  width: auto !important;
  table-layout: auto !important;
}
table.table-fit thead th,
table.table-fit tbody td,
table.table-fit tfoot th,
table.table-fit tfoot td {
  width: auto !important;
}
</style>
</head>

<body>
<div class="container mt-5" style="max-width: 1555px">
<?php

require_once "db.php";
require_once "functions.php";

    $query = "SELECT * FROM verbs";
    $result = mysqli_query($connection, $query);
	  
    $j=0;
    if (mysqli_num_rows($result) > 0) {
        while ($res = mysqli_fetch_array($result)) {
       
            $array_alg[$j][0]=$res['omonim'];
            $array_alg[$j][1]=$res['name'];
            
        	$verb_type_lat=$res['type'];
    		
			switch($verb_type_lat)
			{
				case "I":$array_alg[$j][2]=1;break; //verb_type
				case "II":$array_alg[$j][2]=2;break;
				case "III":$array_alg[$j][2]=3;break;
				case "IV":$array_alg[$j][2]=4;break;
				default:$array_alg[$j][2]=0;break;
			}
			
			$array_alg[$j][3]=$res['element'];
			$array_alg[$j][4]=$res['ryad'];
            $array_alg[$j][5]=$res['setnost'];  
            $array_alg[$j][6]=$res['id'];    
            $array_alg[$j][7]=$res['pada'];        
            
            $j++;

      }
	  
	 
	  
    }

//print_r($array_alg);

$filename = 'compare/kochergina.txt';

if (($fp = fopen($filename, "r")) !== FALSE) {
    while (($data = fgetcsv($fp, 0, ";")) !== FALSE) {
        $array[] = $data;
    }
    }
    
fclose($fp);

$k=0;
for($i=0;$i<count($array);$i++)
{
    if($array[$i][0])
    {
    $one_line_array[$k]=explode("	",$array[$i][0]);
    $k++;
    
   
    }
}

// 0 - омоним
// 1 - корень в слабой ступени
// 2 - корень у Кочергиной
// 3 - Залог (P/A/U)
// 4 - Класс
// 5 - настоящее время
// 6 - будущее время
// 7 - перфект
// 8 - пассив
// 9 - каузатив
// 10 - дезидератив
// 11 - прич.страд. залога прош

echo "<h2>Будущее время (сравнение с учебником В.А.Кочергиной)</h2>";

echo '<table class="table table-bordered"><thead><tr><th scope="col">Корень</th><th scope="col">Корень у Кочергиной</th><th scope="col">Класс</th><th scope="col">U/A (К)</th><th scope="col">U/A (Т)</th><th scope="col">Ряд</th><th scope="col">Тип</th><th scope="col">Кочергина</th><th scope="col">Алгоритм</th>
<th scope="col">Совпадение</th></tr></thead><tbody>';

function computeDiff($from, $to)
{
    $diffValues = array();
    $diffMask = array();

    $dm = array();
    $n1 = count($from);
    $n2 = count($to);

    for ($j = -1; $j < $n2; $j++) $dm[-1][$j] = 0;
    for ($i = -1; $i < $n1; $i++) $dm[$i][-1] = 0;
    for ($i = 0; $i < $n1; $i++)
    {
        for ($j = 0; $j < $n2; $j++)
        {
            if ($from[$i] == $to[$j])
            {
                $ad = $dm[$i - 1][$j - 1];
                $dm[$i][$j] = $ad + 1;
            }
            else
            {
                $a1 = $dm[$i - 1][$j];
                $a2 = $dm[$i][$j - 1];
                $dm[$i][$j] = max($a1, $a2);
            }
        }
    }

    $i = $n1 - 1;
    $j = $n2 - 1;
    while (($i > -1) || ($j > -1))
    {
        if ($j > -1)
        {
            if ($dm[$i][$j - 1] == $dm[$i][$j])
            {
                $diffValues[] = $to[$j];
                $diffMask[] = 1;
                $j--;  
                continue;              
            }
        }
        if ($i > -1)
        {
            if ($dm[$i - 1][$j] == $dm[$i][$j])
            {
                $diffValues[] = $from[$i];
                $diffMask[] = -1;
                $i--;
                continue;              
            }
        }
        {
            $diffValues[] = $from[$i];
            $diffMask[] = 0;
            $i--;
            $j--;
        }
    }    

    $diffValues = array_reverse($diffValues);
    $diffMask = array_reverse($diffMask);

    return array('values' => $diffValues, 'mask' => $diffMask);
}

function diffline($line1, $line2)
{
    $diff = computeDiff(str_split($line1), str_split($line2));
    $diffval = $diff['values'];
    $diffmask = $diff['mask'];

    $n = count($diffval);
    $pmc = 0;
    $result = '';
    for ($i = 0; $i < $n; $i++)
    {
        $mc = $diffmask[$i];
        if ($mc != $pmc)
        {
            switch ($pmc)
            {
                case -1: $result .= '</del>'; break;
                case 1: $result .= '</ins>'; break;
            }
            switch ($mc)
            {
                case -1: $result .= '<del>'; break;
                case 1: $result .= '<ins>'; break;
            }
        }
        $result .= $diffval[$i];

        $pmc = $mc;
    }
    switch ($pmc)
    {
        case -1: $result .= '</del>'; break;
        case 1: $result .= '</ins>'; break;
    }

    return $result;
}



for($i=0;$i<count($one_line_array);$i++)
{
    for($j=0;$j<count($array_alg);$j++)
    {
       
        if($one_line_array[$i][0]==$array_alg[$j][0]&&$one_line_array[$i][1]==$array_alg[$j][1])
        {
            
            $kochergina=$one_line_array[$i][6];
            if($kochergina=="0")
            {
                $kochergina="Нет формы";
            }

            $postfix_name[0]="sya";
            $postfix_name_u[0]="sya";



            $postfix_query[0]=2;
            $postfix_query[1]=2;
            $postfix_u_query[0]=2;
            $postfix_u_query[1]=2;
            $postfix_transform="";
            $verb_type=$array_alg[$j][2];
            $verb_change=$array_alg[$j][3];
            $verb_ryad=$array_alg[$j][4];
            $verb_setnost=$array_alg[$j][5];
            $verb_id=$array_alg[$j][6];
            $verb_pada=$array_alg[$j][7];

            $flag_u=0;$postfix_name[1]="";

            if($one_line_array[$i][3]=="P")
            {
                $postfix_name[1]="ti";
                $url="?verbs=$verb_id&suffixies=3&endings=3";
                $flag_u=0;
                
            }
            elseif($one_line_array[$i][3]=="Ā")
            {
                $postfix_name[1]="te";
                $url="?verbs=$verb_id&suffixies=3&endings=11";
                $flag_u=0;
            }
            elseif($one_line_array[$i][3]=="U")
            {
                $postfix_name[1]="ti";
                $postfix_name_u[1]="te";
                $flag_u=1;
                $url="?verbs=$verb_id&suffixies=3&endings=3";
            }

           
            
            if($array_alg[$j][0])
            {
                $omonim=$array_alg[$j][0];
            }
            else
            {
                $omonim="";
            }

                $algorithm=get_word($array_alg[$j][1],$array_alg[$j][0],$verb_type,$verb_change,$verb_ryad,$postfix_name,$postfix_query,$postfix_transform,"1",$verb_setnost,0);

               

            if($flag_u)
            {
               
               $algorithm_u=get_word($array_alg[$j][1],$array_alg[$j][0],$verb_type,$verb_change,$verb_ryad,$postfix_name_u,$postfix_u_query,$postfix_transform,"1",$verb_setnost,0);
            
            }

            if(!$flag_u)
            {
                $compare_string=trim($algorithm[0]);
                $compare_string2=trim($algorithm[1]);
            }
            else
            {
                $compare_string=$algorithm[0]."/".$algorithm_u[0];
                $compare_string2=$algorithm[1]."/".$algorithm_u[1];
            }

            if($kochergina==$compare_string)
            {
                
                if($algorithm[1])
                {
                    $compare="Совпало с первой формой";
                }
                else
                {
                    $compare="Совпало";
                }
            }
            elseif($kochergina==$compare_string2)
            {
                $compare="Совпало со второй формой";
            }
            elseif($kochergina==$algorithm[0].",".$algorithm[1])
            {
                $compare="Совпало";
            }
            else
            {
                
                $compare="Нет";

            }

            if($algorithm[1])
            {
                 
                echo '<tr><td>'.$one_line_array[$i][1]." ".$omonim.'</td><td>'.$one_line_array[$i][2].'</td><td>'.$one_line_array[$i][4].'</td><td>'.$one_line_array[$i][3].'</td><td>'.$verb_pada.'</td><td>'.$verb_ryad.'</td><td>'.$verb_type.'</td><td>'.$kochergina.'</td><td><a href="/generator.php'.$url.'">'.$compare_string.','.$compare_string2.'</a></td>
                <td>'.$compare.'</td></tr>';
            }
            else
            {
                echo '<tr><td>'.$one_line_array[$i][1]." ".$omonim.'</td><td>'.$one_line_array[$i][2].'</td><td>'.$one_line_array[$i][4].'</td><td>'.$one_line_array[$i][3].'</td><td>'.$verb_pada.'</td><td>'.$verb_ryad.'</td>           <td>'.$verb_type.'</td><td>'.$kochergina.'</td><td><a href="/generator.php'.$url.'">'.$compare_string.'</a></td>
                <td>'.$compare.'</td></tr>';
            }
	        
        }
        
        

    }

    if($one_line_array[$i][1]=="-".$one_line_array[$i][2])
        {
           
            echo '<tr><td>-</td><td>'.$one_line_array[$i][2].'</td><td>'.$one_line_array[$i][4].'</td><td>'.$one_line_array[$i][3].'</td><td>'.$verb_pada.'</td><td>-</td><td>-</td><td>'.$one_line_array[$i][6].'</td><td>Не существует у Уитни</td>
                <td>Не существует у Уитни</td></tr>';
        }
}

echo "</tbody></table>";

?>
</div>
</body>
</html>