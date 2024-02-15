<?
/* 
Based on method of Ivan Tolchelnikov
Programming by Andrei Shirobokov 2023 
*/

require_once "db.php";
header('Content-Type: text/html; charset=utf-8');

$query = "SELECT * FROM suffixes WHERE (comments!='Тестовый' AND name!='|' AND name!='-') order BY lemma ";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
while ($res = mysqli_fetch_array($result)) {

$id=$res['id'];

$answer.="<tr>
<td>".$res['name']. "</td>
<td>".$res['query']." ".$res['transform']. "</td>

<td>".$res['ryad']. "</td>
<td>".$res['type']. "</td>


<td>".$res['lemma']. "</td>
<td>".$res['name_ru']. "</td>


<td>".$res['source']. "</td>
</tr>";
}

$itog='<table class="table table-bordered">
<thead><tr><th scope="col">Cуффикс</th><th scope="col">Запрос МП</th><th scope="col">Ряд</th>
<th scope="col">Тип</th>

<th scope="col">Код формы</th>
<th scope="col">Словоформа</th>

<th scope="col">Перевод</th></tr></thead>
<tbody>'.$answer.'</tbody></table>';


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

    <? include "header.php"; ?>


        <div class="container mt-5" style="max-width: 1255px">
  
            <div class="card-header alert alert-warning text-center mb-3">
                <h2>Суффиксы</h2>
                <h6>Источник: Толчельников И.Е. <a href='https://samskrtam.ru/aazmorpho/'>"Санскритская морфология: руководство v 1.5.2"</a></h6>
                <h6>Алгоритмизация и программирование: <a class="link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href='http://anshir.ru'>Широбоков А.П.</a></h6>
            </div>

            <input type="text" class="form-control" name="live_search" id="live_search" autocomplete="off"
                   placeholder="Поиск по суффиксу ...">

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
                        url: 'ajax-live-search-suffixies.php',
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