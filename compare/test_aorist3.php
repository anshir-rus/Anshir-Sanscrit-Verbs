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

//Правило 16 не нужно, если есть чередование?
   
/*
$mool_array=array("ṛ","n̥c","Øs","m̥̄");
$answer_double_array=array("ār","ānn̥c","ās","ām");
$answer_suff_array=array("āra","ānañca","āsa","āma");
$var_array=array(0,0,0,0);
*/
$mool_array=array("ṛdh","und","ṛh","m̥̄","akṣ","īkṣ","ad",
"suøp","rakṣ","hras","uød","dø̄ 1","dhø̄ 2","pø̄","mø̄ 3","sø̄","hØ̄ 1","ślāgh","rādh",
"veṣṭ","ceṣṭ","ji 1","sru","jū","luṭh","pṝ","sphṝ","vṛj","jvar","mṛ","hval","phal 1","km̥̄","bhrm̥̄","vm̥̄","krand","dhvan","svan","srn̥s","hn̥","skn̥d");

$mool_id=array(591,"",593,706,2,344,6,143,99,150,15,170,179,184,197,223,230,222,208,
248,238,298,525,542,497,670,678,642,572,631,698,688,707,714,716,725,736,760,804,806,799);

$answer_suff_array=array("ardidh","ondid","arjih","аmam","аcikṣ","ecikṣ","adid",
"sūsuØp","rarakṣ","jihras","uīuØd","dīdad","dadhØ̄","pīpī","mīme","sīṣe","jījah","śaślāgh","riradh,rīradh",
"vaveṣṭ","caceṣṭ","jīje","sisro","jījo","luloṭh,lūluṭh","pūpṝ","pusphur","vavarj,vīvṛj","ajijvarat","mīmar","jihval","pīphal",
"cakam","bibhram","vīvam","cikrad","didhvan","sisvan","sisrn̥s","jīghan","caskand");


echo '<h2>Сравнение с примерами И.Толчельникова из "Руководства"</h2>';
echo "<h6><a href='/compare/test_method.php' class='link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover'>Перфект</a> 
<a href='/compare/test_onv3.php' class='link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover'>О.н.в. 3 класса</a> 
<a href='/compare/test_intensive.php' class='link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover'>Интенсив</a> 
<a href='/compare/test_aorist3.php' class='link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover'>Аорист 3 класса</a> </h6>";

echo '<table class="table table-bordered">';
echo '<thead><tr><th scope="col">Корень</th><th scope="col" style="background: #ffc107;">Генерация алгоритмом<br>(без сандхи)</th><th scope="col" style="background: #20c997;">Результат из примера</th><th scope="col">Совпадение</th></tr></thead>';

for($i=0;$i<count($mool_array);$i++)
{
    
    $query = "SELECT * FROM verbs WHERE id='".$mool_id[$i]."'";
    $result = mysqli_query($connection, $query);
    
    if (mysqli_num_rows($result) > 0) {
        while ($res = mysqli_fetch_array($result)) {

            $verb_id=$res['id'];
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

        }
    }

    $command="VR-AoS";
    $aos=get_aos3($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,$debug);
    
    $aos_string="";
    unset($aos_massive);
    for($iaos=0;$iaos<6;$iaos++)
    {
        if($aos['model'][$iaos])
        {
            $aos_massive[]=$aos['model'][$iaos];
        }
    }
    $aos_string=implode(",",$aos_massive);
    $aos_string=str_replace("|","",$aos_string);
    
    $compare_string=$aos_string;

    //print_r($aos_string);
    //echo "<BR>";

   
   if($compare_string==$answer_suff_array[$i])
   {
       $compare="Да";
       $class='';

   }
   else
   {
       $flag_yes=0;
       for($kc2=0;$kc2<count($answer_suff_array);$kc2++)
       {
           if(mb_strpos($compare_string,$answer_suff_array[$kc2])>-1||mb_strpos($compare_string2,$answer_suff_array[$kc2])>-1)
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

    echo "<tr $class>";
    echo "<td><a href='/generator2.php?id=".$verb_id."&command=$command&lico=3&chislo=1&pada=$verb_pada' class='link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover'>".$mool_array[$i]."</a></td>
    <td>".$aos_string."</td><td>".$answer_suff_array[$i]."</td><td>$compare</td>";	
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