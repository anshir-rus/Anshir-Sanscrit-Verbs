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
$mool_array=array("ūh 2","i","ṛ","aṭ","pat","dah","pad","kas","jap","gras","dhø̄ 2","rī","vic","rih","dyut","kūj","gm̥","ym̥","cal","gal","car","nṛt","bṛh","spṛdh","bhn̥j 2","bhrn̥ś","rn̥bh");
$mool_id=array(530,279,586,4,76,63,78,26,49,38,179,373,343,338,455,532,701,704,684,682,"",622,627,654,780,782,789);
$answer_suff_array=array("ūjuh","īyā","arṛ","aṭaṭ","panīpat","dandah","panīpad","canīkas","jañjap","jāgras","dādhØ̄","rāya","vevec","rerih","davidyut,dedyut","cokūj","gánīgm̥","yaṃym̥","cācal","galgal","carcūr","narīnṛt","barbṛh","pāspṛdh","bambhn̥j","banībhrn̥ś","rārn̥bh");
//$answer_suff_array=array();
$var_array=array(0,0,0,0,0);

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

    $command="VR-IS";
    $is=get_intensive($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$debug);
    $is=str_replace("|","",$is);
    $compare_string=$is[0];
    //$double_mool_desiderative=get_desiderative($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,$is_open_mool,$verb_setnost,0)[1];
   // print_r($is);
    
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
    <td>".$is[0]."</td><td>".$answer_suff_array[$i]."</td><td>$compare</td>";	
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