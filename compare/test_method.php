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
$mool_array=array("ṛ","ṛdh","n̥c","Øs","m̥̄","īj","edh","aṭ","gṛØh","uØkṣ","uØc","viØc","viØdh","miØkṣ","śad 1","sthØ̄","jīØ̄","uØ̄" ,"vīØ̄","ci 1","ci 2","nī","veṣṭ","bhū","dhau 1","dhau 2","kup","kṛ" ,"tṝ" ,"kḷp" ,"bhn̥j","gm̥","iṣ","iØj");
$mool_id=array(586,591,762,1,706,346,235,4,37,12,13,118,119,92,127,226,166,153,216,293,294,364,248,553,393,394,410,594,668,680,780,701,283,11);
$answer_double_array=array("ār","ānṛdh","ānn̥c","ās","ām","Только описательный перфект","Только описательный перфект","āṭ","jagṛØh","vavakṣ","uØuØc","viviØc","viviØdh","mimiØkṣ","śāśad","tasthØ̄","jijīØ̄","uØuØ̄","vivīØ̄","ciki","ciki","ninī","viveṣṭ", "babhū","dadhau","dadhau","cukup","cakṛ","tatṝ","cakḷp","babhn̥j","jagm̥","iiṣ","iØiØj");
$answer_suff_array=array("āra","ānṛdhe","ānañca","āsa","āma","","","āṭa","jagrāha","vavakṣa","uvāca/ūce","vivyāca","vivyādha","mimyakṣa","śāśada","tasthau","jijyau","","vivyau","cikāya","cikāya","nināya","viveṣṭa","babhūva","dadhāva","dadhāva","cukopa","cakāra","tatāra","cakalpa","babhañja","jagāma","iyeṣa/īṣe","iyāja/īje");
$var_array=array(0,0,0,0,0);

echo '<h2>Сравнение с примерами И.Толчельникова из "Руководства"</h2>';
echo "<h6><a href='/compare/test_method.php' class='link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover'>Перфект</a> 
<a href='/compare/test_onv3.php' class='link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover'>О.н.в. 3 класса</a> 
<a href='/compare/test_intensive.php' class='link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover'>Интенсив</a> 
<a href='/compare/test_aorist3.php' class='link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover'>Аорист 3 класса</a> </h6>";



echo '<table class="table table-bordered">';
echo '<thead><tr><th scope="col">Корень</th><th scope="col">Генерация удвоенного корня (без сандхи)</th><th scope="col">Удвоенный корень из примера</th><th scope="col" style="background: #ffc107;">Генерация алгоритмом</th><th scope="col" style="background: #20c997;">Результат из примера</th><th scope="col">Совпадение</th></tr></thead>';

for($i=0;$i<count($mool_id);$i++)
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

    $command="VR-PeS-PeF";

    if($verb_pada=="P")
    {
        $sg3=forms($verb_id,$command,3,1,"P",0);
        $algorithm=implode(",",$sg3[4]);

        $compare_string=$algorithm;

          //                   get_perfect($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,$pada,$debug)

        $double_mool_perfect=get_perfect($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,"P",0)[1];
        $double_mool_perfect=str_replace("|","",$double_mool_perfect);

    }
    elseif($verb_pada=="A"||$verb_pada=="Ā")
    {
        $sg3=forms($verb_id,$command,3,1,"A",0);
        $algorithm=implode(",",$sg3[4]);

        $compare_string=$algorithm;

        $double_mool_perfect=get_perfect($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,"A",0)[1];
        $double_mool_perfect=str_replace("|","",$double_mool_perfect);
    }
    elseif($verb_pada=="U")
    {
        $sg3_p=forms($verb_id,$command,3,1,"P",0);
        $algorithm_p=implode(",",$sg3_p[4]);
        
        $sg3_a=forms($verb_id,$command,3,1,"A",0);
        $algorithm_a=implode(",",$sg3_a[4]);

        $double_mool_perfect=get_perfect($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,"U",0)[1];
        $double_mool_perfect=str_replace("|","",$double_mool_perfect);
        
        $compare_string=$algorithm_p."/".$algorithm_a;

    }

    $perfect=$compare_string;

  
    $answer_suff_array[$i]=str_replace("au","āu",$answer_suff_array[$i]);
    $answer_suff_array[$i]=str_replace("ai","āi",$answer_suff_array[$i]);
    
    if($perfect==$answer_suff_array[$i])
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
    echo "
    <td>".$mool_array[$i]."</td>
    <td>$double_mool_perfect</td>
    <td>".$answer_double_array[$i]."</td>
    <td><a href='/generator2.php?id=".$verb_id."&command=VR-PeS-PeF&lico=3&chislo=1&pada=$verb_pada' class='link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover'>$perfect</a></td>
    <td>".$answer_suff_array[$i]."</td>
    <td>$compare</td>";	
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