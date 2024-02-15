<!DOCTYPE html>
<html>
    <head>
        <title>Глагольные корни</title>
        <meta charset="utf-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </head>
    <body class="d-flex flex-column min-vh-100">
    <div class="container mt-5" style="max-width: 1400px">

<?php
require_once "db.php";
include "functions.php";

$page=$_REQUEST['page'];
$lico=$_REQUEST['lico'];
$chislo=$_REQUEST['chislo'];
$pada=$_REQUEST['pada'];

$count_verbs=814;

$first=1+50*($page-1);
$second=10+50*($page-1);

$query = "SELECT * FROM verbs";
$result = mysqli_query($connection, $query);

$i=0;

if (mysqli_num_rows($result) > 0) {
while ($res = mysqli_fetch_array($result)) {
    $verb[$i]['name']=$res['name'];
    $verb[$i]['id']=$res['id'];
    $verb[$i]['omonim']=$res['omonim'];

        if($verb[$i]['omonim'])
        {
            $verb[$i]['name']=$verb[$i]['name']." ".$verb[$i]['omonim'];
        }

    $i++;
    }
}


function search_name($massive,$search_id)
{
    for($i=0;$i<count($massive);$i++)
    {
        if($massive[$i]['id']==$search_id)
        {
            $name=$massive[$i]['name'];
        }
    }
    return $name;
}

if($lico&&$chislo&&$page)
{

    for($j=$first;$j<=$second;$j++)
    {
        $sg1=forms($j,"VR-PeS-PeF",$lico,$chislo,$pada,0);
        $verb_info=search_name($verb,$j);
        $answer.= '<tr><td>'. $verb_info . '</td><td>'. $sg1[0] . '</td></tr>';
    }


    for($j=$first+10;$j<=$second+10;$j++)
    {
        $sg1=forms($j,"VR-PeS-PeF",$lico,$chislo,$pada,0);
        $verb_info=search_name($verb,$j);
        $answer2.= '<tr><td>'. $verb_info . '</td><td>'. $sg1[0] . '</td></tr>';
    }



    for($j=$first+20;$j<=$second+20;$j++)
    {
        $sg1=forms($j,"VR-PeS-PeF",$lico,$chislo,$pada,0);
        $verb_info=search_name($verb,$j);
        $answer3.= '<tr><td>'. $verb_info . '</td><td>'. $sg1[0] . '</td></tr>';
    }

    for($j=$first+30;$j<=$second+30;$j++)
    {
        $sg1=forms($j,"VR-PeS-PeF",$lico,$chislo,$pada,0);
        $verb_info=search_name($verb,$j);
        $answer4.= '<tr><td>'. $verb_info . '</td><td>'. $sg1[0] . '</td></tr>';
    }


    for($j=$first+40;$j<=$second+40;$j++)
    {
        $sg1=forms($j,"VR-PeS-PeF",$lico,$chislo,$pada,0);
        $verb_info=search_name($verb,$j);
        $answer5.= '<tr><td>'. $verb_info . '</td><td>'. $sg1[0] . '</td></tr>';
    }

}

$itog='<table class="table table-bordered">

<tr>

<td>
<table class="table table-bordered">
<thead><th>Корень</th><th>Лицо: '.$lico.' Число: '.$chislo.' '.$pada.'</th></thead>
'.$answer.'
</table>
</td>

<td>
<table class="table table-bordered">
<thead><th>Корень</th><th>Лицо: '.$lico.' Число: '.$chislo.' '.$pada.'</th></thead>
'.$answer2.'
</table>
</td>

<td>
<table class="table table-bordered">
<thead><th>Корень</th><th>Лицо: '.$lico.' Число: '.$chislo.' '.$pada.'</th></thead>
'.$answer3.'
</table>
</td>

<td>
<table class="table table-bordered">
<thead><th>Корень</th><th>Лицо: '.$lico.' Число: '.$chislo.' '.$pada.'</th></thead>
'.$answer4.'
</table>
</td>

<td>
<table class="table table-bordered">
<thead><th>Корень</th><th>Лицо: '.$lico.' Число: '.$chislo.' '.$pada.'</th></thead>
'.$answer5.'
</table>
</td>

</tr>

</table>';

?>
<h1>Perfect</h1>
<form action="all_perfect.php?page=<? echo $page; ?>">

<select name="command" id="command-select">
<option value="">--Perfect--</option>
<option value="VR-PeS-PeF" <? if($command=="VR-PeS-PeF"){echo "selected";} ?>>Perfect</option>
</select>

<select name="lico" id="lico-select">
<option value="">--Лицо--</option>
<option value="1" <? if($lico==1){echo "selected";} ?>>1</option>
<option value="2" <? if($lico==2){echo "selected";} ?>>2</option>
<option value="3" <? if($lico==3){echo "selected";} ?>>3</option>
</select>


<select name="chislo" id="chislo-select">
<option value="">--Число--</option>
<option value="1" <? if($chislo==1){echo "selected";} ?>>sg</option>
<option value="2" <? if($chislo==2){echo "selected";} ?>>du-</option>
<option value="3" <? if($chislo==3){echo "selected";} ?>>pl</option>
</select>


<select name="pada" id="pada-select">
<option value="P">--Pada--</option>
<option value="P"<? if($pada=="P"){echo "selected";} ?>>P</option>
<option value="A"<? if($pada=="A"){echo "selected";} ?>>A</option>
</select>

<input type="hidden" name="page" value="1">

<input type="submit" value="Сгенерировать"></p> 
</form>
<?

echo $itog;
echo "<div align='center'>";

for($i=1;$i<=82;$i++)
{
echo "<a href='/all_perfect.php?lico=$lico&chislo=$chislo&pada=$pada&page=$i'>$i</a> ";
}


echo "<div>";

?>
</div>
</body>
</html>