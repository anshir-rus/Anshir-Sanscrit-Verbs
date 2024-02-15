<?
/* 
Based on method of Ivan Tolchelnikov
Programming by Andrei Shirobokov 2023 
*/
?>
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

require_once "../db.php";
require_once "../functions.php";

$refresh=$_REQUEST["refresh"];

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
            $array_alg[$j]['prs']=$res['prs'];  
           		$j++;

      }
	  
	 
	  
    }

    $query = "SELECT * FROM kochergina";
    $result = mysqli_query($connection, $query);
	  
    $j=0;
    if (mysqli_num_rows($result) > 0) {
        while ($res = mysqli_fetch_array($result)) {
       
            $one_line_array[$j][0]=$res['omonim'];
            $one_line_array[$j][1]=$res['name_ivan'];
            $one_line_array[$j][2]=$res['name_koch'];
        	$one_line_array[$j][3]=$res['pada'];
			$one_line_array[$j][4]=$res['class'];
            $one_line_array[$j][5]=$res['present'];  
            $one_line_array[$j][6]=$res['future'];    
            $one_line_array[$j][7]=$res['perfect'];  
            $one_line_array[$j][8]=$res['passive']; 
            $one_line_array[$j][9]=$res['causative']; 
            $one_line_array[$j][10]=$res['desiderative']; 
            $one_line_array[$j][11]=$res['prich']; 
            $one_line_array[$j][12]=$res['infinitive'];
            $one_line_array[$j][13]=$res['algo'];
            $one_line_array[$j][14]=$res['comments'];
            $one_line_array[$j][15]=$res['id'];
            $one_line_array[$j][16]=$res['algo_present'];
            $one_line_array[$j][17]=$res['comments_present'];

           	$j++;

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

include "compare_header.php";

?>
<a href="/compare/compare_present.php?refresh=1">Обновить данные</a>
<?

echo '<table class="table table-bordered"><thead><tr><th scope="col">Корень</th><th scope="col">Корень у Кочергиной</th><th scope="col">Класс (К)</th><th scope="col">Класс (Т)</th><th scope="col">U/A (К)</th><th scope="col">U/A (Т)</th><th scope="col">Ряд</th><th scope="col">Тип</th><th scope="col">Кочергина</th><th scope="col">Алгоритм</th>
<th scope="col">Совпадение</th></tr></thead><tbody>';

$command="VR-PrS-Pr";


for($i=0;$i<count($one_line_array);$i++)
{
    for($j=0;$j<count($array_alg);$j++)
    {
       
        if($one_line_array[$i][0]==$array_alg[$j][0]&&$one_line_array[$i][1]==$array_alg[$j][1])
        {
            
            $kochergina=$one_line_array[$i][5];
            if($kochergina=="0")
            {
                $kochergina="Нет формы";
            }

         
            $verb_type=$array_alg[$j][2];
            $verb_change=$array_alg[$j][3];
            $verb_ryad=$array_alg[$j][4];
            $verb_setnost=$array_alg[$j][5];
            $verb_id=$array_alg[$j][6];
            $verb_pada=$array_alg[$j][7];
            $verb_prs=$array_alg[$j]['prs'];

            $flag_u=0;

            if($verb_pada=="0")
            {
                $verb_pada=$one_line_array[$i][3];
            }

            if($refresh==1)
            {
                
                if($array_alg[$j][0])
                {
                    $omonim=$array_alg[$j][0];
                }
                else
                {
                    $omonim="";
                }

            if($verb_pada=="P")
            {
               $sg3=forms($verb_id,$command,3,1,"P",0);
               $algorithm=implode(",",$sg3[4]);

               $compare_string=$algorithm;
            }
            elseif($verb_pada=="A"||$verb_pada=="Ā")
            {
                $sg3=forms($verb_id,$command,3,1,"A",0);
                $algorithm=implode(",",$sg3[4]);

                $compare_string=$algorithm;
            }
            elseif($verb_pada=="U")
            {
                $sg3_p=forms($verb_id,$command,3,1,"P",0);
                $algorithm_p=implode(",",$sg3_p[4]);
                
                $sg3_a=forms($verb_id,$command,3,1,"A",0);
                $algorithm_a=implode(",",$sg3_a[4]);
                
                $compare_string=$algorithm_p."/".$algorithm_a;

            }

            $url="/generator2.php?id=$verb_id&command=$command&lico=3&chislo=1&pada=$verb_pada";
                
                //Запись Дифтонгов
                $kochergina=str_replace("au","āu",$kochergina);
                $kochergina=str_replace("ai","āi",$kochergina);
         
                $kochergina_array=array();
                $koch_array=explode("/",$kochergina);
                for($kc=0;$kc<count($koch_array);$kc++)
                {
                    if($koch_array[0])
                    {
                        $koch_array2=explode(",",$koch_array[$kc]);
                        for($kc2=0;$kc2<count($koch_array2);$kc2++)
                        {
                            if($koch_array2[$kc2])
                            {
                                $kochergina_array[]=$koch_array2[$kc2];
                            }
                        }
                    }
                }

                if($kochergina==$compare_string)
                {
                    $compare="Совпало";
                    $class='';
                }
                else
                {
                    $flag_yes=0;
                    for($kc2=0;$kc2<count($kochergina_array);$kc2++)
                    {
                        if($kochergina_array[$kc2]==$compare_string||$kochergina_array[$kc2]==$compare_string2||mb_strpos($compare_string,$kochergina_array[$kc2])>-1||mb_strpos($compare_string2,$kochergina_array[$kc2])>-1)
                        {
                                $flag_yes=1;
                        }
                    }

                    if($flag_yes)
                    {
                        $compare="Совпало с одной из форм";
                        $class='class="table-secondary"';
                    }
                    else
                    {
                        $compare="Нет";
                        $class='class="table-danger"';
                    }

                }


                    $string=$compare_string;


                    echo '<tr '.$class.'><td>'.$one_line_array[$i][1]." ".$omonim.'</td><td>'.$one_line_array[$i][2].'</td><td>'.$one_line_array[$i][4].'</td><td>'.$verb_prs.'</td><td>'.$one_line_array[$i][3].'</td><td>'.$verb_pada.'</td><td>'.$verb_ryad.'</td>           <td>'.$verb_type.'</td><td>'.$kochergina.'</td><td><a href="'.$url.'" class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">'.$string.'</a></td>
                    <td>'.$compare.'</td></tr>';
                

                $query = "UPDATE kochergina SET algo_present=?,comments_present=? WHERE id=?";
                $statement = $connection->prepare($query);
                $statement->bind_param('ssi',$string,$compare,$one_line_array[$i][15]);
                $statement->execute();
                $statement->close();
                
                
            }
            else
            {
                echo '<tr><td>'.$one_line_array[$i][1]." ".$omonim.'</td><td>'.$one_line_array[$i][2].'</td><td>'.$one_line_array[$i][4].'</td><td>'.$verb_prs.'</td><td>'.$one_line_array[$i][3].'</td><td>'.$verb_pada.'</td><td>'.$verb_ryad.'</td>           
                        <td>'.$verb_type.'</td><td>'.$kochergina.'</td><td><a href="/generator.php'.$url.'">'.$one_line_array[$i][16].'</a></td><td>'.$one_line_array[$i][17].'</td></tr>';
            }
        
        } 


    }

  

        if($one_line_array[$i][1]=="-".$one_line_array[$i][2])
        {
            $string="Нет в базе";
            $class='class="table-warning"';

            if($refresh==1)
            {
                echo '<tr '.$class.'><td>-</td><td>'.$one_line_array[$i][2].'</td><td>'.$one_line_array[$i][4].'</td><td>'.$verb_prs.'</td><td>'.$one_line_array[$i][3].'</td><td>'.$verb_pada.'</td><td>-</td><td>-</td><td>'.$one_line_array[$i][6].'</td><td>'.$string.'</td>
                    <td>'.$string.'</td></tr>';

                $query = "UPDATE kochergina SET algo_present=?,comments_present=? WHERE id=?";
                $statement = $connection->prepare($query);
                $statement->bind_param('ssi',$string,$compare,$one_line_array[$i][15]);
                $statement->execute();
                $statement->close();
            }
            else
            {
                echo '<tr '.$class.'><td>-</td><td>'.$one_line_array[$i][2].'</td><td>'.$one_line_array[$i][4].'</td><td>'.$one_line_array[$i][3].'</td><td>'.$verb_pada.'</td><td>-</td><td>-</td><td>'.$one_line_array[$i][6].'</td><td>'.$string.'</td>
                <td>'.$string.'</td></tr>';

            }
        }
   
    
    
}



echo "</tbody></table>";

?>
</div>

<BR><BR>
<?
	include "footer.php";
	?>
</body>
</html>