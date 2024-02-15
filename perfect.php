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

    $id=364;
    $command=array("VR-PeS-PeF","VR-pPeS-PeF","VR-DS-pPeS-PeF");
   
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

   // $source_verb=search_in_db($id,"verbs",1);

   // $pada="P";

for($c=0;$c<count($command);$c++)  
{

   // echo '<p class="h4">'.$source_verb[0].'</p>';
    echo '<table width="100%" align="center" class="table table-bordered table-fit">';
    echo "<tr align=center><td colspan=2><b>".$command[$c]."</b></td><tr>";


    //$verb_pada=$res['pada'];

    

    if($verb_pada=="U")
    {
        $pada[0]="P";
        $pada[1]="A";
    }
    elseif($verb_pada=="Ā")
    {
        $pada[0]="A";
    }
    else
    {
        $pada[0]=$verb_pada;
    }


   

    echo "<tr align=center>";

    for($i=0;$i<count($pada);$i++)
    {
    echo "<td>".$pada[$i].".</td>";
    }

    echo '</tr><tr>';

   

    for($i=0;$i<count($pada);$i++)
    {

        $sg1=forms($id,$command[$c],1,1,$pada[$i],0);
        $sg2=forms($id,$command[$c],1,2,$pada[$i],0);
        $sg3=forms($id,$command[$c],1,3,$pada[$i],0);

        $du1=forms($id,$command[$c],2,1,$pada[$i],0);
        $du2=forms($id,$command[$c],2,2,$pada[$i],0);
        $du3=forms($id,$command[$c],2,3,$pada[$i],0);

        $pl1=forms($id,$command[$c],3,1,$pada[$i],0);
        $pl2=forms($id,$command[$c],3,2,$pada[$i],0);
        $pl3=forms($id,$command[$c],3,3,$pada[$i],0);


        echo '<td><table width="10%" class="table table-bordered table-fit">';
        echo "<tr><th></th><th>sg.</th><th>du.</th><th>pl.</th></tr>";
        echo "<tr>
        <td>1</td>
        <td>".$sg1[0]."</td>
        <td>".$sg2[0]."</td>
        <td>".$sg3[0]."</td>
        </tr>";

        echo "<tr>
        <td>2</td>
        <td>".$du1[0]."</td>
        <td>".$du2[0]."</td>
        <td>".$du3[0]."</td>
        </tr>";

        echo "<tr>
        <td>3</td>
        <td>".$pl1[0]."</td>
        <td>".$pl2[0]."</td>
        <td>".$pl3[0]."</td>
        </tr>";
    
        echo '</table></td>';
    }

    echo '</tr></table>';

}

    function mts($array_big,$array_small,$id,$command,$lico,$chislo,$pada)
    {
            for($i=0;$i<count($array_big);$i++)
            {
                $string.="<p>".$array_big[$i]."</p><p><small><a href='/generator2.php?id=$id&command=$command&lico=$lico&chislo=$chislo&pada=$pada'>".$array_small[$i]."</a></small></p>";
               // $string.=read_write_corpus($array_big[$i],$id,$command,$lico,$chislo,$pada);
            }

            return $string;
    }

    function forms($id,$command,$lico,$chislo,$pada)
    {
        $chered=AllChered($id,$command,$lico,$chislo,$pada,0,0);

        $result[0]=mts($chered['sandhi'],$chered['string'],$id,$command,$lico,$chislo,$pada);
        $result[1]=AllChered($id,$command,$lico,$chislo,$pada,0,0)['sandhi'][0];
        $result[2]=$chislo;
        $result[3]=$lico;

        return $result;
    }

?>

</div>
</body>
</html>