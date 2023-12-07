<?
/* 
Based on method of Ivan Tolchelnikov
Programming by Andrei Shirobokov 2023 
*/

require_once "db.php";
header('Content-Type: text/html; charset=utf-8');

$query = "SELECT * FROM verbs";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
while ($res = mysqli_fetch_array($result)) {
$omonim=$res['omonim'];

$omonim_text="";
if($omonim)
{
$omonim_text=" $omonim ";
}

$adhoc=$res['adhoc'];
$id=$res['id'];
$adhoc_text="";
if($adhoc)
{
$adhoc_text="<BR>несам.: $adhoc ";
}

$answer.="<tr><td><a href='/generator.php?verbs=$id' class='link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover'>".$res['name']. "$omonim_text $adhoc_text</a></td><td>".$res['ryad']. "</td><td>".$res['whitney']. "</td><td>".$res['setnost']. "</td><td>".$res['type']. "</td><td>".$res['pada']. "</td><td>".$res['prs']. "</td><td>".$res['aos']. "</td><td>".$res['translate']. " ".$res['comments']. "</td></tr>";
}

$itog='<table class="table table-bordered"><thead><tr><th scope="col">Корень</th><th scope="col">Ряд</th><th scope="col">Корень по Whitney</th><th scope="col">seṭ-aniṭ</th><th scope="col">Тип</th>
<th scope="col">P/Ā</th><th scope="col">PrS</th><th scope="col">AoS</th><th scope="col">Перевод / Комментарии</th></tr></thead><tbody>'.$answer.'</tbody></table>';
//echo $itog;

} else {
$itog= "
<div class='alert alert-danger mt-3 text-center' role='alert'>
Not found
</div>
";
}



?>


<!DOCTYPE html>
<html>
    <head>
        <title>Глагольные корни</title>
        <meta charset="utf-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </head>
    <body class="d-flex flex-column min-vh-100">

 


        <div class="container mt-5" style="max-width: 1255px">
            <div class="alert alert-danger" role="alert">
                Тестовая версия! Возможны опечатки, влияющие на конечный результат. 
                <BR>
                <a class="link-danger  link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href='compare.php'>Сравнение с таблицей В.А.Кочергиной</a>
                <BR>
                <a class="link-danger  link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href='test_sandhi.php'>Верификация сандхи по Эмено</a>
                <BR>
                <a class="link-danger  link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href='test_method.php'>Сравнение с примерами И.Толчельникова (v.1.2.1)</a>
            </div>
            <div class="card-header alert alert-warning text-center mb-3">
                <h2>Глагольные корни санскрита</h2>
                <h6>Источник: Толчельников И.Е. <a class="link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href='https://samskrtam.ru/aazmorpho/'>"Санскритская морфология: руководство v 1.1.0"</a></h6>
                <h6>Алгоритмизация и программирование: <a class="link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href='http://anshir.ru'>Широбоков А.П.</a></h6>
            </div>

            <input type="text" class="form-control" name="live_search" id="live_search" autocomplete="off"
                   placeholder="Поиск по корню ...">

            <br>
            <div id="search_result">
                <?
                echo $itog;
                ?>
            </div>


        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#live_search").keyup(function () {
                    var query = $(this).val();
                    // if (query != "") {
                    $.ajax({
                        url: 'ajax-live-search.php',
                        method: 'POST',
                        data: {
                            query: query
                        },
                        success: function (data) {
                            $('#search_result').html(data);
                            $('#search_result').css('display', 'block');
                            $("#live_search").focusout(function () {
                                $('#search_result').html(data);
                            });
                            $("#live_search").focusin(function () {
                                $('#search_result').css('display', 'block');
                            });
                        }
                    });
                    //  } else {
                    //      $('#search_result').html(data);
                    //   }
                });
            });
        </script>


        <? 
        include "footer.php";
        ?>

    </body>
</html>