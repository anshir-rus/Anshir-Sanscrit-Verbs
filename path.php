<?php
/* 
Based on method of Ivan Tolchelnikov
Programming by Andrei Shirobokov 2023 
*/

include "db.php";
include "functions.php";

$start=$_REQUEST["start"];
$end=$_REQUEST["end"];

?>
<html>
<head>
</head>
<body>
<form action="path.php">
<label for="verb-select">По правилам: Выберите начальную и конечную точку:</label>
<br>
    <select name="start" id="start-select">
	<option value="">--Начало--</option>
    <?php
 
    if($start==999)
    {
        $selected1="selected";$selected2="";$selected3="";
    }
    
    if($start==1)
    {
        $selected2="selected";$selected1="";$selected3="";
    }

  // if($start==29)
  //  {
  //      $selected3="selected";$selected1="";$selected2="";
  //  }
   
    ?>
     <!--
    <option value="999" <? echo $selected1; ?> >Brahman</option>
  -->
    <option value="1" <? echo $selected2; ?>>VR</option>
    <!--
    <option value="29" <? echo $selected3; ?>>NON VS</option>
  -->
    </select>


	<select name="end" id="end-select">
	<option value="">--Конец--</option>

<?
       
        $query = "SELECT * FROM words";
		$result = mysqli_query($connection, $query);
        $i=0;
		if (mysqli_num_rows($result) > 0) {
			while ($res = mysqli_fetch_array($result)) {

                $suffix[$i][0]=$res['id'];
                $suffix[$i][1]=$res['name'];
                $suffix[$i][2]=$res['root'];
                $suffix[$i][3]=$res['root2'];
                $suffix[$i][4]=$res['final'];
                $suffix[$i][5]=$res['stop'];

                if($suffix[$i][4]==1)
                {
                    $final[]=$suffix[$i][0];

                    if($end==$suffix[$i][0])
                    {
                        $selected="selected";
                    }
                    else
                    {
                        $selected="";
                    }

                    if($suffix[$i][0]==$end)
                    {
                        $stop=$suffix[$i][5];
                        
                    }

                   

                    echo '<option value="'.$suffix[$i][0].'" '.$selected.'>'.$suffix[$i][1].'</option>';
                }

                $i++;

                

            }
        }
        

        ?>
    </select>
    <BR><BR>
	<input type="submit" value="Расчёт вариантов">
    </form>

    <form action="path.php">
<label for="verb-select">Для тестов: из любой точки в любую точку:</label>
<br>
    <select name="start" id="start-select">
	<option value="">--Начало--</option>

    <?php

    for($i=0;$i<count($suffix);$i++)
    {
        
        if($start==$suffix[$i][0])
        {
            $selected="selected";
        }
        else
        {
            $selected="";
        }
        
        echo '<option value="'.$suffix[$i][0].'" '.$selected.'>'.$suffix[$i][1].'</option>';
    }

    if($start==999)
    {
        $selected1="selected";
    }

?>
    <option value="1" <? echo $selected1; ?> >Brahman</option>
    </select>

    <select name="end" id="end-select">
	<option value="">--Конец--</option>

    <?php

    for($i=0;$i<count($suffix);$i++)
    {
        if($end==$suffix[$i][0])
        {
            $selected="selected";
        }
        else
        {
            $selected="";
        }
        
        echo '<option value="'.$suffix[$i][0].'" '.$selected.'>'.$suffix[$i][1].'</option>';
    }

    if($end==999)
    {
        $selected1="selected";
    }

?>
    <option value="999" <? echo $selected1; ?> >Brahman</option>
    </select>
    <BR><BR>
	<input type="submit" value="Расчёт вариантов">
    </form>


    </body>
    </html>
        <?

      //  echo check_yoga(9,4,$suffix)."<BR>";

      //  echo "STOP: ".$stop;

       if($start&&$end)
       {


        if($end==999)
        {
            $brahman=brahman($start,$final,$suffix,$stop);
            
            echo "Количество вариантов: ".$brahman[0]."<BR>";
            echo "Список вариантов: <BR>"; 

            for($i=0;$i<count($brahman[1]);$i++)
            {
                $string="";
                for($j=0;$j<count($brahman[1][$i]);$j++)
                {
                    $string.=find($brahman[1][$i][$j],$suffix)."-";
                }
                echo "$string";
                echo "<BR><BR>";
            }

        }
        else
        {

            $paths=paths($start,$end,$suffix,$stop);

            echo "Количество вариантов: ".count($paths)."<BR><BR>";

            for($i=0;$i<count($paths);$i++)
            {
                $string="";
                for($j=0;$j<count($paths[$i]);$j++)
                {
                    $string.=find($paths[$i][$j],$suffix)."-";
                }
                $string=str_replace("-S-","-",$string);
                echo "$string";
                echo "<BR><BR>";
            }

        }

    }

function paths($start,$end,$suffix,$stop)
{

    $i=0;
    $cycle=[$end];

    while(($root!=999&&$root2!=999)||($root!=999&&$root2!=0))
   // while($i<2)
    {
        for($k=0;$k<count($cycle);$k++)  
        {

                for($j=0;$j<count($suffix);$j++)
                {
                    if($suffix[$j][0]==$cycle[$k])
                    {
                        $root=$suffix[$j][2];
                        $upper[]=$root;

                        $root2=explode(",",$suffix[$j][3]);

                       
                        if($root2[1])
                        {

                            for($m=0;$m<count($root2);$m++)
                            {
                                $upper[]=$root2[$m];
                                
                            }

                        }
                        else
                        {
                            $root2=$suffix[$j][3];
                        }

                    }

                    
                }

            }

            

            $upper=array_values(array_unique($upper));
            $cycle=$upper;


            $i++;
    }

    $upper=array_values(array_unique($upper));
    $upper[count($upper)]=$end;

    for($i=0;$i<count($upper);$i++)
    {
        if($upper[$i]==$stop)
        {
            unset($upper[$i]);
        }
    }
    
    $upper=array_values(array_unique($upper));

    /*
    echo "START:$start STOP: $stop UPPER:";
    print_r($upper);
    echo "<BR>";
    */

    $c=0;
    $cycle=$upper;

        for($i=0;$i<count($cycle);$i++)
        {
            $check=check_yoga($start,$cycle[$i],$suffix);
            if($check==1)
            {
                $new_massive[$i][0]=$start;
                $new_massive[$i][]=$cycle[$i];
            }


        }


    $new_massive=array_values($new_massive);

    $c=0;$f=0;

    while($flag!=1)
    {
        
        for($i=0;$i<count($new_massive);$i++)
        {
   
            $count=count($new_massive[$i]);
    
                for($j=0;$j<count($cycle);$j++)
                {
                    //echo "LAST: ".$new_massive[$i][$count-1]." & ".$cycle[$j]." ";
                    $check=check_yoga($new_massive[$i][$count-1],$cycle[$j],$suffix);
                    //echo $check."<BR>";

                    if($new_massive[$i][$count-1]==$end)
                    {
                        for($m=0;$m<count($new_massive[$i]);$m++)
                        {
                            $new2_massive[$f][$m]=$new_massive[$i][$m];
                        }

                        $f++;
                        break;
                    }

                    if($check==1)
                    {
                        
                        for($m=0;$m<count($new_massive[$i]);$m++)
                        {
                            $new2_massive[$f][$m]=$new_massive[$i][$m];
                        }

                        if($new_massive[$i][$count-1]!=$end)
                        {
                            $new2_massive[$f][$count]=$cycle[$j];
                        
                        }

                        $f++;

                    }
                }
        }

        $new_massive=$new2_massive;
        $new2_massive=array();
        $f=0;

        $flag=1;
        for($i=1;$i<count($new_massive);$i++)
        {
            $count=count($new_massive[$i]);
            
            if($new_massive[$i][$count-1]!=$end)
            {
                $flag=$flag*0;
            }
            else
            {
                $flag=$flag*1;
            }
        }

        $c++;
    }

    for($i=0;$i<count($new_massive);$i++)
    {
        $count=count($new_massive[$i]);
        
        if($new_massive[$i][$count-1]==$end&&$new_massive[$i][0]==$start)
        {
        $tog[$i]=$new_massive[$i];
        }
        
    }
   
  

    return $tog;
  
}

function check_yoga($id1,$id2,$array)
{
  
    $root=$array[$id2-1][2];
    
    $root2=$array[$id2-1][3];
    $root2_array=explode(",",$root2);

    $result=0;

    for($i=0;$i<count($root2_array);$i++)
    {
        if($id1==$root||$id1==$root2_array[$i])
        {
            $result=1;
        }
    }

   
    
    return $result;
}

function brahman($start,$final,$suffix)
{
   // echo "Final Forms:";
   // print_r($final);
   // echo "<BR><BR>";
   $massive=array();

    for($i=0;$i<count($final);$i++)
    {

        for($j=0;$j<count($suffix);$j++)
        {
            if($suffix[$j][0]==$final[$i])
            {
                $stop=$suffix[$j][5];
                
            }
        }
        
        $paths=paths($start,$final[$i],$suffix,$stop);
        $massive=array_merge($massive,$paths);

    }

    
    $count=count($massive);

    
    $result[0]=$count;
    $result[1]=$massive;
    return $result;


}

function find($id,$suffix)
{
    for($i=0;$i<count($suffix);$i++)
    {
        if($suffix[$i][0]==$id)
        {
            $name=$suffix[$i][1];
        }
    }

    return $name;
}

?>