<?
/* 
Based on method of Ivan Tolchelnikov
Programming by Andrei Shirobokov 2023 
*/

require_once "db.php";
header('Content-Type: text/html; charset=utf-8');

$query = "SELECT * FROM pronouns";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
while ($res = mysqli_fetch_array($result)) {

$id=$res['id'];

$answer.="<tr>
<td>".$res['name']. "</td>
<td>".$res['padezh']. "</td>
<td>".$res['steam']. "</td>
<td>".$res['translate']. "</td>
<td>".$res['short_translate']. "</td>
<td>".$res['source']. "</td>
</tr>";
}

$itog='<table class="table table-bordered">
<thead><tr><th scope="col">Местоимение</th><th scope="col">Падеж</th><th scope="col">Основа</th><th scope="col">Перевод</th><th scope="col">Краткий перевод</th><th scope="col">Источник</th></tr></thead>
<tbody>'.$answer.'</tbody></table>';


} else {
$itog= "
<div class='alert alert-danger mt-3 text-center' role='alert'>
Not found
</div>
";
}



?>

    <? include "header.php"; ?>


        <div class="container mt-5" style="max-width: 1255px">
  
            <div class="card-header alert alert-warning text-center mb-3">
                <h2>Местоимения</h2>
                <h6>Источник: таблица М.Ю.Гасунса, парсинг В.Леонченко, данные Жерар Юэ</h6>
                <h6>Алгоритмизация и программирование: <a class="link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href='http://anshir.ru'>Широбоков А.П.</a></h6>
            </div>

            <input type="text" class="form-control" name="live_search" id="live_search" autocomplete="off"
                   placeholder="Поиск по местоимению ...">

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
                        url: '/search/ajax-live-search-pronounse.php',
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