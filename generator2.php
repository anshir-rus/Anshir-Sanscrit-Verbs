<?
/* 
Based on method of Ivan Tolchelnikov
Programming by Andrei Shirobokov 2023 
*/
?>

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
<body class="d-flex flex-column min-vh-100">
<? include "header.php"; ?>
<div class="container mt-5" style="max-width: 1255px">
    
<?php
require_once "db.php";
include "functions.php";

$id=$_REQUEST['id'];
$command=$_REQUEST['command'];
$chislo=$_REQUEST['chislo'];
$lico=$_REQUEST['lico'];
$pada=$_REQUEST['pada'];
$debug=$_REQUEST['debug'];


       
        $query = "SELECT * FROM verbs WHERE id=$id";
        $result = mysqli_query($connection, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    while ($res = mysqli_fetch_array($result)) {
                        $verb_name=$res['name'];
                        $verb_omonim=$res['omonim'];
                        $verb_type_lat=$res['type'];
                        $verb_setnost=$res['setnost'];
                        $verb_pada=$res['pada'];
                        
                        switch($verb_type_lat)
                        {
                            case "I":$verb_type=1;break;
                            case "II":$verb_type=2;break;
                            case "III":$verb_type=3;break;
                            case "IV":$verb_type=4;break;
                            default:$verb_type=0;break;
                        }
                        
                        $verb_change=$res['element'];
                        $verb_ryad=$res['ryad'];
                        
                        $omonim=$res['omonim'];
                    
                    $omonim_text="";
                    if($omonim)
                    {
                        $omonim_text=" $omonim ";
                    }
                    
                    $adhoc=$res['adhoc'];
                    $adhoc_text="";
                    if($adhoc)
                    {
                        $adhoc_text="<BR>несам.: $adhoc ";
                    }
                        
                        $answer="<tr><td>".$res['name']. "$omonim_text $adhoc_text</td><td>".$res['ryad']. "</td><td>".$res['whitney']. "</td><td>".$res['setnost']. "</td><td>".$res['type']. "</td><td>".$res['pada']. "</td><td>".$res['prs']. "</td><td>".$res['aos']. "</td><td>".$res['translate']. " ".$res['comments']. "</td></tr>";
                        $itog='<table class="table table-bordered"><thead><tr><th scope="col">Корень</th><th scope="col">Ряд</th><th scope="col">Корень по Whitney</th><th scope="col">seṭ-aniṭ</th><th scope="col">Тип</th>
                    <th scope="col">P/Ā</th><th scope="col">PrS</th><th scope="col">AoS</th><th scope="col">Перевод / Комментарии</th></tr></thead><tbody>'.$answer.'</tbody></table>';
                
                        
                    }
                }
           
        echo $itog;


        $source_verb=VerbCache::search_in_db($id,"verbs",1);

        echo "<h5>Код формы: $command (".tranlate_command_to_russian($command).")</h5><h6> Лицо: $lico Число: $chislo Pada: $pada</h6><BR>";

        if($pada=="U")
        {
            $pada='';
        }

        

        $chered=AllChered($id,$command,$lico,$chislo,$pada,$debug,$debug,1);

        //print_r($chered);
/*
        for($i=0;$i<count($chered['string']);$i++)
        {
            //echo "Последовательность: ".$chered['string'][$i]."";
        }
        //echo "<BR>";
        for($i=0;$i<count($chered['nosandhi']);$i++)
        {
            echo "<BR>Без сандхи: <b>".$chered['nosandhi'][$i]."</b>";
        }
        echo "<BR>";
        for($i=0;$i<count($chered['sandhi']);$i++)
        {
            echo "<BR>После сандхи: <b>".$chered['sandhi'][$i]."</b>";
        }
        echo "<BR>";
*/

?>
<BR>
<?
echo '<a href="/generator2.php?id='.$id.'&command='.$command.'&lico='.$lico.'&chislo='.$chislo.'&pada='.$pada.'&debug=1">Больше Debug информации</a>';
echo "<BR>";
echo '<a href="/generator2.php?id='.$id.'&command='.$command.'&lico='.$lico.'&chislo='.$chislo.'&pada='.$pada.'&debug=0">Меньше Debug информации</a>';
?>
<BR><BR>

<a href="/">Назад к списку</a>

<BR><BR>
</div>

<?
	include "footer.php";
	?>
</body>
</html>