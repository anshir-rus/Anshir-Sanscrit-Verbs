

    <? include "header.php"; ?>

    <div class="container mt-5" style="max-width: 1255px">

<h3>Ваши достижения</h3>
<br>

<?php
echo "<h5>Тренажёр Devanāgarī</h5>";
$query = "SELECT * FROM users_trainings WHERE userid=".$_COOKIE['id']." AND typet='devanagari' ORDER BY level asc";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
while ($res = mysqli_fetch_array($result)) {

    $total_number[]=$res['total_number'];
    $good_number[]=$res['good_number'];
    $good_result[]=$res['good_result'];
    $bad_result[]=$res['bad_result'];
    $level[]=$res['level'];
    $level_name[]=$res['level_name'];
}
}

if($level)
{

    for($i=0;$i<count($level);$i++)
    {
        echo "<u>Уровень сложности:</u> ".$level_name[$i]."<BR>Упражнений пройдено: ".$total_number[$i]."<BR>Верных ответов: ".$good_number[$i]."<BR><BR>";
        
        if($bad_result[$i])
        {
            $bad_massive=explode(",",$bad_result[$i]);

            for($j=0;$j<count($bad_massive);$j++)
            {
                if(explode(":",$bad_massive[$j])[0])
                {
                    $bad2[]=explode(":",$bad_massive[$j])[0];
                }
            }
            

            $bad_result_string=implode(",",$bad2);
            unset($bad_massive);
            unset($bad2);
            
            echo "Возникли сложности: ".$bad_result_string."<BR><BR>";
        }
    }

}
else
{
    echo "Вы ещё не проходили тренировки!";
}

?>

<?php
echo "<BR><BR><h5>Тренажёр Memory</h5>";
$query = "SELECT * FROM users_trainings WHERE userid=".$_COOKIE['id']." AND typet='memory' ORDER BY level asc";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
while ($res = mysqli_fetch_array($result)) {

    $total_number[]=$res['total_number'];
    $good_number[]=$res['good_number'];
    $good_result[]=$res['good_result'];
    $bad_result[]=$res['bad_result'];
    $level[]=$res['level'];
    $level_name[]=$res['level_name'];
}
}

if($level)
{

    for($i=0;$i<count($level);$i++)
    {
        echo "<u>Уровень сложности:</u> ".$level_name[$i]."<BR>Упражнений пройдено: ".$total_number[$i]."<BR>Верных ответов: ".$good_number[$i]."<BR><BR>";
        
        if($bad_result[$i])
        {
            $bad_massive=explode(",",$bad_result[$i]);

            for($j=0;$j<count($bad_massive);$j++)
            {
                if(explode(":",$bad_massive[$j])[0])
                {
                    $bad2[]=explode(":",$bad_massive[$j])[0];
                }
            }
            

            $bad_result_string=implode(",",$bad2);
            unset($bad_massive);
            unset($bad2);
            
            echo "Возникли сложности: ".$bad_result_string."<BR><BR>";
        }
    }

}
else
{
    echo "Вы ещё не проходили тренировки!";
}

?>

</div>
<? include "footer.php"; ?>

</body>
</html>
