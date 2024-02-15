<?
/* 
Based on method of Ivan Tolchelnikov
Programming by Andrei Shirobokov 2023 
*/
?>
<html class="h-100">
<head>
    <title>Глагольные корни</title>
    	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
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

<body class="d-flex flex-column min-vh-100">
<div class="container mt-5" style="max-width: 1255px">
    
<?php
require_once "db.php";
include "functions.php";

/*
0 => name               'raj'   'sya'
1 => omonim             '1'     '1'
2 => type (I-IV)        'I'     'I?'
3 => change element     'a'     'a'
4 => row (A1-N2)        'A1'    'A1'
5 => glagol or imenn    '1'     '2'        // 1 - glagol, 0 -imenn, 2 - suffix
6 => setnost            '1'     '1'
7 => query              '0'     '2'
8 => transform          ''      ''
//11 => double VR       ''      '' (in functions)
*/

        echo "-----------<BR>";

        //$commandline=CommandLine(16,"VR-FuS-PaFuAS",1,1);

		//$id=13;
        $id=780;
       
		//$id=783;
        $command="VR-FuS-Fu";
        $chislo=3;
        $lico=1;
        $pada="P";

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
        
        $source_verb=search_in_db($id,"verbs",1);

        echo "Глагол: ".$source_verb[0]." Лицо: $lico Число: $chislo Pada:$pada<BR><BR>";

       // AllChered($id,$command,$lico,$chislo,'P',1,1);

        $chered=AllChered($id,$command,$lico,$chislo,$pada,1,1,1);
				//echo $chered['string'];
		

        for($i=0;$i<count($chered['string']);$i++)
        {
            echo "<BR>Запись: ".$chered['string'][$i]."";
        }
        //echo "<BR>";
        for($i=0;$i<count($chered['nosandhi']);$i++)
        {
            echo "<BR>Без сандхи: <b>".$chered['nosandhi'][$i]."</b>";
        }
        //echo "<BR>";
        for($i=0;$i<count($chered['sandhi']);$i++)
        {
            echo "<BR>После сандхи: <b>".$chered['sandhi'][$i]."</b>";
        }
		for($i=0;$i<count($chered['rules']);$i++)
        {
            echo "<BR>Применили правила Эмено: <b>".$chered['rules'][$i]."</b>";
        }
        echo "<BR>";

		
?>
</div>
</body>
</html>