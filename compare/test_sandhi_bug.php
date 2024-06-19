<? include "../header.php"; ?>

<body>
<div class="container mt-5" style="max-width: 1255px">
<?

require_once "../db.php";
include "../functions.php";

$all = ["|", "k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s", "h", "ṃ", 
"ḥ", "a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "ai", "o", "au", "Ø̄", "Ø", "m̥̄", "m̥", "n̥", "n̥̄"];

//Правило 16 не нужно, если есть чередование?
   
$emeno_example=array(3,1,2,3,4,4,5,5,5,5,6,6,7,8,9,10,11,11,12,13,14,15,17,18,18,18,19,19,19,19,20,21,21,22,22,23,23,24,24,25,25,26,26,26,26,28,28,29,29,30,30,31,31,32,32,33,33,34,35,36,36,37,37,37,38,38,39,39,40);

$mool_array=array("nāu","deva","bhava","agne","strī","bhū","pṝ","pṝ","kṝ","kṝ","pitṛ","ju|hu","a","suhārd","bharant","gach","agam","agam","acchids","havis","vas","śās","nah","duh","duh","duh","lih","vah","lih","madhulih",
"jakṣ","takṣ","takṣ","prach","pṛch","vraśc","vṛśc","dviṣ","dviṣ","viś","diś","yaj","parivrāj","mṛj","ṛtvij",
"vac","uc","yuj","yuj","budh","labh","vid","budh","bandh","śak","diś","citralikh","budh","man","agni","vāk","narān","nṝn","brahman","dviṣ","īḍ","yun","yun","yaj"
);

$suff_array=array("ā","au","īt","as","ā","ā","ā","bhyām","ati","yate","os","ati","asīt","s","s","ati","va","ma","ta","su","syati","dhi","syati","syati","dhi","ta","ta","ta","syati","su",
"ta","syati","ta","syati","tvā","syati","tvā","syati","ta","su","su","syati","s","tvā","su",
"syati","ta","syati","ta","ta","tṛ","si","syate","syate","dhi","bhyas","s","bhis","syate","su","su","ām","ām","ya","ta","te","jmas","gdhi","na"
);

$answer_array=array("nāvā","devāu","bhavet","agnayas","striyā","bhuvā","purā","pūrbhyām","kirati","kīryate","pitros","juhvati","āsīt","suhārt","bharan","gacchati","aganva","aganma","acchitta","haviḥṣu","vatsyati",
"śādhi","natsyati","dhukṣyati","dugdhi","dugdha","līḍha","voḍha","likṣyati","madhuliṭsu","jagdha","takṣyati","taṣṭa","prakṣyati","pṛṣṭvā","vrakṣyati","vṛṣṭvā","dvikṣyati","dviṣṭa","viṭsu","dikṣu","yakṣyati",
"parivrāṭ","mṛṣṭvā","ṛtvikṣu",
"vakṣyati","ukta","yukṣyati","yukta","buddha","labdhṛ","vitsi","bhutsyate","bhantsyate","śagdhi","digbhyas","citralik","bhudbhis","maṃsyate","agniṣu","vākṣu","narāṇām","nṝṇām","brahmaṇya","dviṣṭa","īṭṭe","yuñjmas","yuṅgdhi","yajña"
);

$glagol_array=array(0,0,1,0,0,1,0,0,1,1,0,1,1,0,0,1,1,1,1,0,1,1,1,1,1,1,1,1,1,0,1,1,1,1,0,1,0,1,0,0,0,1,0,1,0,
1,0,1,0,0,0,1,1,1,1,0,0,0,1,0,0,0,0,0,0,1,1,1,0);

$padezh_array=array(0,0,1,0,0,1,0,0,1,1,0,1,1,0,0,1,1,1,1,1,0,0,0,1,1,1,1,1,1,0,1,1,1,1,0,1,0,1,0,0,0,1,0,1,0,
1,0,1,0,0,0,1,1,1,1,0,0,0,1,0,0,0,0,0,0,1,1,1,0);

echo '<table class="table table-bordered">';
echo '<thead><tr><th scope="col">Корень или морфема</th><th scope="col">Морфемы</th><th scope="col">Глаг. (1) /<br> Именн. (0)</th><th scope="col">Алгоритм</th><th scope="col">Правила алгоритма</th>
<th scope="col">Правильный ответ по Эмено (до чередования!)</th><th scope="col">Пример из правила</th><th scope="col">Совпадение</th></tr></thead>';

for($i=0;$i<count($mool_array);$i++)
{
    if(test_sandhi($mool_array[$i]."|".$suff_array[$i],$mool_array[$i],$glagol_array[$i],$padezh_array[$i],0)[0]==$answer_array[$i])
    {
        $compare="Да";
        $class='';
    }
    else
    {
        $compare="Нет";
        $class='class="table-danger"';
    }

    echo "<tr $class>";
    echo "<td>".$mool_array[$i]."</td><td>".$suff_array[$i]."</td><td>".$glagol_array[$i]."</td><td><b>".test_sandhi($mool_array[$i]."|".$suff_array[$i],$mool_array[$i],$glagol_array[$i],$padezh_array[$i],0)[0]."</b></td>
    <td>".test_sandhi($mool_array[$i]."|".$suff_array[$i],$mool_array[$i],$glagol_array[$i],$padezh_array[$i],0)[1]."</td><td>".$answer_array[$i]."</td><td>".$emeno_example[$i]."</td><td>$compare</td>";	
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