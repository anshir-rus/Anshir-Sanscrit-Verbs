<?
/* 
Based on method of Ivan Tolchelnikov
Programming by Andrei Shirobokov 2023 
*/
?>

<? include "header.php"; ?>
<div class="container mt-5" style="max-width: 1255px">
    
<?php
require_once "db.php";
include "functions.php";

$id=$_REQUEST['id'];

$query = "SELECT * FROM nouns WHERE id=$id";

		$result = mysqli_query($connection, $query);
				
				if (mysqli_num_rows($result) > 0) {
					while ($res = mysqli_fetch_array($result)) {
						$noun_name=$res['name'];
                        $noun_pol=$res['pol'];
                        $noun_element=$res['element'];
                        $noun_e_position=$res['e_position'];
                        $noun_ryad=$res['ryad'];
                        $noun_type=$res['type'];

                        switch($noun_type)
                        {
                            case "I":$noun_type_number=1;break;
                            case "II":$noun_type_number=2;break;
                            case "III":$noun_type_number=3;break;
                            case "IV":$noun_type_number=4;break;
                        }

                        $noun_sklonenie=$res['sklonenie'];
                        $noun_sklonenie_type=$res['sklonenie_type'];
                        $noun_translate=$res['translate'];

                        $answer="<tr><td>$noun_name</td><td>".$noun_pol. "</td><td>".$noun_ryad. "</td><td>".$noun_type. "</td><td>".$noun_sklonenie. "</td><td>".$noun_sklonenie_type. "</td><td>".$noun_translate. "</td></tr>";
                 
						$itog='<table class="table table-bordered"><thead><tr><th scope="col">Существительное</th><th scope="col">Пол</th>
                        <th scope="col">Ряд</th><th scope="col">Тип</th>
                        <th scope="col">Склонение</th><th scope="col">Тип склонения</th><th scope="col">Перевод</th></tr></thead><tbody>'.$answer.'</tbody></table>';
				
                    }
                }

    echo $itog;

    echo "<HR>";

    echo "<h3>Склонения</h3>";
    echo "<br><h5>Sg.</h5>";

    $n1=CommandLineSklonenie(1,"N",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,1,1);
    $acc1=CommandLineSklonenie(1,"Acc",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,1,1);
    $i1=CommandLineSklonenie(1,"I",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,1,1);
    $d1=CommandLineSklonenie(1,"D",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,1,1);
    $abl1=CommandLineSklonenie(1,"Abl",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,1,1);
    $g1=CommandLineSklonenie(1,"G",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,1,1);
    $l1=CommandLineSklonenie(1,"L",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,1,1);
    $v1=CommandLineSklonenie(1,"V",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,1,1);
  
    echo '<table class="table table-bordered"><thead><tr><th scope="col">Падеж</th><th scope="col">Словоформа</th></tr></thead>
    <tbody>
    <tr><td>N</td><td>'.$n1.'</td></tr>
    <tr><td>Acc</td><td>'.$acc1.'</td></tr>
    <tr><td>I</td><td>'.$i1.'</td></tr>
    <tr><td>D</td><td>'.$d1.'</td></tr>
    <tr><td>Abl</td><td>'.$abl1.'</td></tr>
    <tr><td>G</td><td>'.$g1.'</td></tr>
    <tr><td>L</td><td>'.$l1.'</td></tr>
    <tr><td>V</td><td>'.$v1.'</td></tr>
    </tbody></table>';

    echo "<br><h5>Du.</h5>";

    $n2=CommandLineSklonenie(1,"N",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,2,1);
    $acc2=CommandLineSklonenie(1,"Acc",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,2,1);
    $i2=CommandLineSklonenie(1,"I",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,2,1);
    $d2=CommandLineSklonenie(1,"D",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,2,1);
    $abl2=CommandLineSklonenie(1,"Abl",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,2,1);
    $g2=CommandLineSklonenie(1,"G",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,2,1);
    $l2=CommandLineSklonenie(1,"L",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,2,1);
    $v2=CommandLineSklonenie(1,"V",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,2,1);
  
    echo '<table class="table table-bordered"><thead><tr><th scope="col">Падеж</th><th scope="col">Словоформа</th></tr></thead>
    <tbody>
    <tr><td>N</td><td>'.$n2.'</td></tr>
    <tr><td>Acc</td><td>'.$acc2.'</td></tr>
    <tr><td>I</td><td>'.$i2.'</td></tr>
    <tr><td>D</td><td>'.$d2.'</td></tr>
    <tr><td>Abl</td><td>'.$abl2.'</td></tr>
    <tr><td>G</td><td>'.$g2.'</td></tr>
    <tr><td>L</td><td>'.$l2.'</td></tr>
    <tr><td>V</td><td>'.$v2.'</td></tr>
    </tbody></table>';

    echo "<br><h5>Pl.</h5>";

    $n3=CommandLineSklonenie(1,"N",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,3,1);
    $acc3=CommandLineSklonenie(1,"Acc",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,3,1);
    $i3=CommandLineSklonenie(1,"I",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,3,1);
    $d3=CommandLineSklonenie(1,"D",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,3,1);
    $abl3=CommandLineSklonenie(1,"Abl",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,3,1);
    $g3=CommandLineSklonenie(1,"G",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,3,1);
    $l3=CommandLineSklonenie(1,"L",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,3,1);
    $v3=CommandLineSklonenie(1,"V",$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$noun_sklonenie,$noun_sklonenie_type,$noun_pol,3,1);
  
    echo '<table class="table table-bordered"><thead><tr><th scope="col">Падеж</th><th scope="col">Словоформа</th></tr></thead>
    <tbody>
    <tr><td>N</td><td>'.$n3.'</td></tr>
    <tr><td>Acc</td><td>'.$acc3.'</td></tr>
    <tr><td>I</td><td>'.$i3.'</td></tr>
    <tr><td>D</td><td>'.$d3.'</td></tr>
    <tr><td>Abl</td><td>'.$abl3.'</td></tr>
    <tr><td>G</td><td>'.$g3.'</td></tr>
    <tr><td>L</td><td>'.$l3.'</td></tr>
    <tr><td>V</td><td>'.$v3.'</td></tr>
    </tbody></table>';

    //function CommandLineSklonenie($id,$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$string,$sklonenie,$sklonenie_type,$chislo,$debug)



    echo "<HR>";

    //echo get_element_simple("N1",2);

    echo "<HR>";

    

    
?>