<html class="h-100">
<head>
    <title>Глагольные корни</title>
    	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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
<div class="container mt-5" style="max-width: 1255px">

<?

require_once "../db.php";
include "../functions.php";

$all = ["|", "k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s", "h", "ṃ", 
"ḥ", "a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "ai", "o", "au", "Ø̄", "Ø", "m̥̄", "m̥", "n̥", "n̥̄"];


echo '<table class="table table-bordered">';
echo '<thead><tr><th scope="col">Генерация удвоенного корня (без сандхи)</th>
<th scope="col">C сандхи</th>
<th scope="col" style="background: #20c997;">3 sg.</th>
</tr></thead>';

//for($i=0;$i<count($mool_id);$i++)
//{
    
    $query = "SELECT * FROM verbs";
    $result = mysqli_query($connection, $query);
    
    if (mysqli_num_rows($result) > 0) {
        while ($res = mysqli_fetch_array($result)) {

            $verb_id[]=$res['id'];
            $verb_name[]=$res['name'];
            $verb_omonim[]=$res['omonim'];
            $verb_type_lat[]=$res['type'];
            $verb_setnost[]=$res['setnost'];
            $verb_pada[]=$res['pada'];
           
            $verb_change[]=$res['element'];
            $verb_ryad[]=$res['ryad'];
            $omonim[]=$res['omonim'];

        }
    }

    //print_r($verb_id);

for($i=0;$i<count($verb_id);$i++)
//for($i=0;$i<10;$i++)
{

 
            switch($verb_type_lat[$i])
            {
                case "I":$verb_type=1;break;
                case "II":$verb_type=2;break;
                case "III":$verb_type=3;break;
                case "IV":$verb_type=4;break;
                default:$verb_type=0;break;
            }
            

    $command="VR-PeS-PeF";

    //echo $verb_id[$i];

    if($verb_pada[$i]=="P")
    {
        $sg3=forms($verb_id[$i],$command,3,1,"P",0);
        $algorithm=implode(",",$sg3[4]);

        $compare_string=$algorithm;

          //                   get_perfect($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,$pada,$debug)

       // $double_mool_perfect=get_perfect($verb_name[$i],$verb_omonim[$i],$verb_type,$verb_change[$i],$verb_ryad[$i],$verb_pada[$i],"P",0)[0];
        
        $double_mool_perfect=get_perfect($verb_name[$i],$verb_omonim[$i],$verb_type,$verb_change[$i],$verb_ryad[$i],$verb_pada[$i],"P",0)[1];
        //$double_mool_perfect=str_replace("|","",$double_mool_perfect);

    }
    elseif($verb_pada[$i]=="A"||$verb_pada[$i]=="Ā")
    {
        $sg3=forms($verb_id[$i],$command,3,1,"A",0);
        $algorithm=implode(",",$sg3[4]);

        $compare_string=$algorithm;

        $double_mool_perfect=get_perfect($verb_name[$i],$verb_omonim[$i],$verb_type,$verb_change[$i],$verb_ryad[$i],$verb_pada[$i],"A",0)[1];
       // $double_mool_perfect=str_replace("|","",$double_mool_perfect);
    }
    elseif($verb_pada[$i]=="U")
    {
        $sg3_p=forms($verb_id[$i],$command,3,1,"P",0);
        $algorithm_p=implode(",",$sg3_p[4]);
        
        $sg3_a=forms($verb_id[$i],$command,3,1,"A",0);
        $algorithm_a=implode(",",$sg3_a[4]);

        $double_mool_perfect=get_perfect($verb_name[$i],$verb_omonim[$i],$verb_type,$verb_change[$i],$verb_ryad[$i],$verb_pada[$i],"U",0)[1];
      // $double_mool_perfect=str_replace("|","",$double_mool_perfect);
        
        $compare_string=$algorithm_p."/".$algorithm_a;

    }

    $perfect=$compare_string;
    //echo $perfect;

  
   
     

    echo "<tr $class>";
    echo "
  
    <td>".$double_mool_perfect."</td>
    <td>".simple_sandhi($double_mool_perfect."| ", $verb_name[$i],"",0)[0]."</td>
  
    <td><a href='/generator2.php?id=".$verb_id[$i]."&command=VR-PeS-PeF&lico=3&chislo=1&pada=".$verb_pada[$i]."' class='link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover'>$perfect</a></td>";	
    echo "</tr>";
}

echo "</table>";

?>
</div>
<BR><BR>
<?
	include "footer.php";
	?>
</body>

</html>