<?php
error_reporting(E_ERROR | E_PARSE);
set_time_limit(0);

include "functions/setnost.php"; //Функции для работы с сетностью
include "functions/sandhi.php"; //Функции для работы с сандхи
include "functions/lemmas.php"; //Функции для работы с Формами
include "functions_nouns.php";
include "simplehtmldom/simple_html_dom.php";

/*
  Based on method of Ivan Tolchelnikov
  Programming by Andrei Shirobokov 2023
 */

function find_in_array($what, $array, $letter) {
    $result = "";
    for ($i = 0; $i < count($array); $i++) {
        if ($array[$i] == $what) {
            $result = $letter;
        }
    }

    return $result;
}

function read_write_corpus($word,$id,$command,$lico,$chislo,$pada)
{
    /*
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sanskrit";

    //echo "$word,$id,$command,$lico,$chislo,$pada<BR>";

    $connection = new mysqli($servername, $username, $password, $dbname);
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
     */

     include "db.php";

	$query_db = "SELECT * FROM corpus where (verb_id=$id AND form='$word' AND command='$command' AND lico=$lico AND chislo=$chislo AND pada='$pada') ";
    //echo $query_db;
 	$conn = mysqli_query($connection, $query_db);
     if (mysqli_num_rows($conn) > 0) {
        
        while ($res = mysqli_fetch_array($conn)) {
            $id=$res['id'];
            $url=$res['url'];
            $text=$res['text'];
        }

        if($text!='NODATA')
        {

            $modal='<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal'.$id.'">Пример</button>
        
        <!-- Модальное окно -->
        <div class="modal fade" id="exampleModal'.$id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Словоформа в тексте</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                '.$text.'
                <BR><BR>
                <a href="'.$url.'">Ссылка</a>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
            </div>
        </div>';
        }

     }
     else
     {
        $search=search_in_corpus($word);
        if($search[1])
        {
            $query_insert="INSERT INTO corpus values (0,$id,'$word','$command',$lico,$chislo,'$pada','".$search[0]."','".$search[1]."')";
            //echo $query_insert;
            $conn = mysqli_query($connection, $query_insert);

            $query_db = "SELECT * FROM corpus where (verb_id=$id AND form='$word' AND command='$command' AND lico=$lico AND chislo=$chislo AND pada='$pada') ";
            $conn = mysqli_query($connection, $query_db);
            if (mysqli_num_rows($conn) > 0) {
                
                while ($res = mysqli_fetch_array($conn)) {
                    $id=$res['id'];
                    $url=$res['url'];
                    $text=$res['text'];
                }
            }

            $modal='<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal'.$id.'">
            Пример
          </button>
          
          <!-- Модальное окно -->
          <div class="modal fade" id="exampleModal'.$id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Словоформа в тексте</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                '.$text.'
                  <BR><BR>
                <a href="'.$url.'">Ссылка</a>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
              </div>
            </div>
          </div>';

        }
        else
        {
            $query_insert="INSERT INTO corpus values (0,$id,'$word','$command',$lico,$chislo,'$pada','NODATA','NODATA')";
            //echo $query_insert;
            $conn = mysqli_query($connection, $query_insert);
        }


     }

     return $modal;
}

function search_in_corpus($word)
{

     //  $url = "https://samskrtam.ru/parallel-corpus/10_rigveda.html";
        $urls = array(
            "https://samskrtam.ru/parallel-corpus/01_rigveda.html",
            "https://samskrtam.ru/parallel-corpus/02_rigveda.html",
            "https://samskrtam.ru/parallel-corpus/03_rigveda.html",
            "https://samskrtam.ru/parallel-corpus/04_rigveda.html",
            "https://samskrtam.ru/parallel-corpus/05_rigveda.html",
            "https://samskrtam.ru/parallel-corpus/06_rigveda.html",
            "https://samskrtam.ru/parallel-corpus/07_rigveda.html",
            "https://samskrtam.ru/parallel-corpus/08_rigveda.html",
            "https://samskrtam.ru/parallel-corpus/09_rigveda.html",
            "https://samskrtam.ru/parallel-corpus/10_rigveda.html"
        );
		
		$headers = []; // создаем заголовки

        for($i=0;$i<count($urls);$i++)
        {
            //echo '<BR><BR>Смотрим в <a href="'.$urls[$i].'">'.$i.' мандале Ригведы</a> слово '.$word.' </b><BR>';
            $curl = curl_init(); // создаем экземпляр curl

            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_VERBOSE, 1); 
            curl_setopt($curl, CURLOPT_POST, false); // 
            curl_setopt($curl, CURLOPT_URL, $urls[$i]);

            $result = curl_exec($curl);

            $search=" ".$word." ";

            $search_in_corpus=mb_strpos($result,$search);

            //echo $search_in_corpus."<BR><BR>";

            if($search_in_corpus)
            {
                $last=$search_in_corpus+mb_strlen($word)+1;

                $how=mb_strlen($word)+600;

                $itog.=mb_substr($result,$search_in_corpus-310,310);
                $itog.=" <b>".mb_substr($result,$search_in_corpus+1,mb_strlen($word))."</b> ";
                $itog.=mb_substr($result,$last,$how);

                //echo $itog."<BR><BR>";

                $html=str_get_html($itog);

                foreach($html->find('div.chapter_block') as $e)
                $t.=$e->innertext."<BR>";

                foreach($html->find('div.range') as $e)
                $text[0]=$urls[$i]."#chapter_".$e->innertext;

                $text[1]=$t;
                
                break;
                
            }
            else
            {
                //echo "Не найдено";
            }

         }

         return $text;
}

function duplication_first($mool, $mool_number, $mool_type, $mool_change, $mool_type_change, $debug) {
    $dimensions = dimensions($mool, $mool_change, $mool, 1, 0, 0,"");
    $dimensions_array = dimensions_array($dimensions);

    $i = 0;
    $flag_big_e = 0;
    $f_mool = "";


    while ($dimensions_array[$i][1] != "E") {
        
        if($i==strlen($dimensions[1])||$i>1000){break;}
        $i++;
    }


    if ($dimensions_array[$i + 1][1] == "E") {
        $flag_big_e = 1;
        $i++;
    }

    for ($j = $i + 1; $j < count($dimensions_array); $j++) {

        $f_mool .= $dimensions_array[$j][0];
        $f_mool_array[] = $dimensions_array[$j][0];
    }



    if ($debug) {
        $debug_text.= "<b>Удвоение корня (первый шаг - определяем P') </b><BR><BR>";
        //echo "<b>Удвоение корня (первый шаг - определяем P') </b><BR><BR>";
    }

    if ($debug) {
        $debug_text.= dimensions_table($dimensions);
        //echo dimensions_table($dimensions);
    }

   

    if ($i == 1) {
        
        $debug_text.= "P пустое<BR>";

        $p_new = "";
        $p_mool = "";
        $model = $mool_change . $f_mool;
    } else {
      

        $debug_text.= "P не пустое<BR>";


        if (($i > 2 && $flag_big_e == 0) || ($i > 3 && $flag_big_e == 1)) {

            $x1[0] = $dimensions_array[1][0];
            $x1[1] = $dimensions_array[1][1];
            $x1[2] = $dimensions_array[1][2];

            $x2[0] = $dimensions_array[2][0];
            $x2[1] = $dimensions_array[2][1];
            $x2[2] = $dimensions_array[2][2];

            $p_mool = $x1[0] . $x2[0];
            $p_mool_array=array($x1[0],$x2[0]);

            if ($x1[2] . $x2[2] == "ST") {
                $p_new = $x2[0];
                $model = $p_new . "E'" . $p_mool . $mool_change . $f_mool;
                $comment= "остаётся шумная";
            } else {
                $p_new = $x1[0];
                $model = $p_new . "E'" . $p_mool . $mool_change . $f_mool;
                $comment= "остаётся первая";
            }
        } else {
            $x = $dimensions_array[1][0];
            $p_mool = $x;
            $p_mool_array=array($x);

            $p_new = $x;
            $f_mool = "";
            for ($j = $i + 1; $j < count($dimensions_array); $j++) {
                $f_mool .= $dimensions_array[$j][0];
            }



            $model = $p_new . "E'" . $p_mool . $mool_change . $f_mool;
            $comment= "ничего не происходит";
        }

       
       

        if ($x1) {
            $p_text = "P = $p_mool ('" . $x1[2] . $x2[2] . "') ";
        } else {
            $p_text = "P = $p_mool ";
        }

        $e_text = "E = $mool_change ";

        if ($f_mool == "") {
            $f_text = "F = Ø <BR>";
        } else {
            $f_text = "F = $f_mool <BR>";
        }

        if ($x1) {
            $pada1 = "x1='" . $x1[2] . "' x2='" . $x2[2] . "' <BR><BR>Шаг 1 удвоения: " . $model . " ($comment)<BR>";
        } else {
            $pada1 = "<BR>Шаг 1 удвоения: " . $model . " ($comment)<BR>";
        }

        $debug_text.= $p_text . $e_text . $f_text;
        $debug_text.= $pada1;

        $comment = "";

        switch ($p_new) {
            case "kh":$comment = " ( $p_new меняется на";
                $p_new = "k";
                $comment .= " $p_new )";
                break;
            case "ch":$comment = " ( $p_new меняется на";
                $p_new = "c";
                $comment .= " $p_new )";
                break;
            case "ṭh":$comment = " ( $p_new меняется на";
                $p_new = "ṭ";
                $comment .= " $p_new )";
                break;
            case "th":$comment = " ( $p_new меняется на";
                $p_new = "t";
                $comment .= " $p_new )";
                break;
            case "ph":$comment = " ( $p_new меняется на";
                $p_new = "p";
                $comment .= " $p_new )";
                break;
            case "gh":$comment = " ( $p_new меняется на";
                $p_new = "g";
                $comment .= " $p_new )";
                break;
            case "jh":$comment = " ( $p_new меняется на";
                $p_new = "j";
                $comment .= " $p_new )";
                break;
            case "ḍh":$comment = " ( $p_new меняется на";
                $p_new = "ḍ";
                $comment .= " $p_new )";
                break;
            case "dh":$comment = " ( $p_new меняется на";
                $p_new = "d";
                $comment .= " $p_new )";
                break;
            case "bh":$comment = " ( $p_new меняется на";
                $p_new = "b";
                $comment .= " $p_new )";
                break;
        }

        if (!$comment) {
            $comment = " (ничего не происходит) ";
        }
        $model = $p_new . "E'" . $p_mool . $mool_change . $f_mool;

        $debug_text.= "<BR>Шаг 2 удвоения: " . $model . " $comment <BR>";

        $comment = "";

        switch ($mool) {
            case "ji":$p_new = "j";
                $p_mool = "g";
                $comment = " (корень-исключение $mool)";
                break;
            case "cit":$p_new = $p_new;
                $p_mool = "k";
                $comment = " (корень-исключение $mool)";
                break;
            case "ci":
                if ($mool_number == 1) {
                    $p_new = $p_new;
                    $p_mool = "k";
                    $comment = " (корень-исключение $mool $mool_number)";
                } elseif ($mool_number == 2) {
                    $p_new = $p_new;
                    $p_mool = "k";
                    $comment = " (корень-исключение $mool $mool_number)";
                }
                break;
        }

        if (!$comment) {

            switch ($p_new) {
                case "h":
                    if ($mool == "hn̥" || $mool == "hi") {
                        $p_new = "j";
                        $p_mool = "gh";
                        $comment = " (корень-исключение $mool)";
                    } else {
                        $comment = " ( $p_new меняется на";
                        $p_new = "j";
                        $comment .= " $p_new)";
                    }
                    break;

                case "k": $comment = " ( $p_new меняется на";
                    $p_new = "c";
                    $comment .= " $p_new )";
                    break;
                case "g": $comment = " ( $p_new меняется на";
                    $p_new = "j";
                    $comment .= " $p_new )";
                    break;
            }
        }

        if (!$comment) {
            $comment = " (ничего не происходит) ";
        }
        $model = $p_new . "E'" . $p_mool . $mool_change . $f_mool;
        if ($debug) {
            $debug_text.= "<BR>Шаг 3 удвоения: " . $model . " $comment<BR>";
        }

        
    }

    /*
    if ($debug) {
        echo '<a class="btn btn-primary" data-bs-toggle="collapse" href="#collapse_des" role="button" aria-expanded="false" aria-controls="collapseExample">
        Алгоритм образования Дезидератива
        </a>
    
        </p>
        <div class="collapse" id="collapse_des">
        <div>';
        echo $debug_text;
        echo '</div></div>';
    }
    */

    $itog[0] = $model;
    $itog[1] = $p_new;
    $itog[2] = "E'";
    $itog[3] = $p_mool;
    $itog[4] = $mool_change;
    $itog[5] = $f_mool;
    $itog[6] = $f_mool_array;
    $itog[7] = $p_mool_array;
    $itog[8] = $debug_text;

    return $itog;
}

function duplication_p2($array, $mool, $mool_type, $mool_type_change, $omonim, $debug) {    //как чередуется ar ??

    $p_new = $array[1];

    $p_mool = $array[3];
    $mool_change = $array[4];
    $f_mool = $array[5];

    $p_before_mool='';

    $debug_text.="<BR><b>Подготовка корня для создания простого перфекта </b><BR>";
  

    if (!$array[1]) {

        if ($mool_type_change == "R1") {  ///Как чередуется ???
            if ($mool == "ṛ") {
                $model = "ār";
                $stop=1;
                $comment = "глагол-исключение $mool";
            } else {
                $model = "ān" . $array[4] . $array[5];
               // $prefix="ān";
                $comment = "Ряд $mool_type_change схема ānR1F";
            }
        } elseif ($mool_type_change == "N1") {
            $model = "ān" . $array[4] . $array[5];
            //$prefix="ān";
            $comment = "Ряд $mool_type_change ānN1F";
        } elseif ($mool == "Øs") {
            $model = "ās";
            //$prefix="ās";
            $stop=1;
            $comment = "глагол-исключение $mool";
        } elseif ($mool == "m̥̄") {
            $model = "ām";
            //$prefix = "ām";
            $stop=1;
            $comment = "глагол-исключение $mool";
        } elseif ($mool == "īj" || $mool == "edh") {
            $model = "Только описательный перфект";
            $comment = "глагол-исключение $mool";
        } else {

            $model = $mool_change ."|". $array[4] ."|". $array[5];

            $dimensions_p2=dimensions($mool_change ."|". $array[4] ,"","",0,0,0,"");
            $dimensions_p2[1]=str_replace("-","",$dimensions_p2[1]);
            $e_position=mb_strlen($dimensions_p2[1]);
            $change_later=array("E'",$mool_change,1, $e_position,'NEED_TWO');

            /*
            $first_chered=$array[4] ."|". $array[5];

            $new_e=$mool_change;

            $e_position=mb_strlen($new_e)+mb_strlen($mool_change)+1;

            $change_later=array("E'",$mool_change,1, $e_position,'NEED_TWO');

            //$prefix=$new_e;
            
            */
            
            $comment = "ветвь 5, Р = 0, корни удваиваются путем присоединения E в 1МП – по схеме E'EF";
        }
    } else {
        $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "ai", "o", "au"];

        if ($mool == "hū") {
            $model = "juhū";
            $prefix="ju";
            //$stop=1;
            $comment = " корень-исключение $mool ";
        } elseif ($mool == "hvṛ") {
            $model = "jahvṛ";
            $prefix="ja";
            //$stop=1;
            $comment = " корень-исключение $mool ";
        } elseif ($mool == "śū") {
            $model = "śuśū";
            $prefix="śu";
            //$stop=1;
            $comment = " корень-исключение $mool ";
        } elseif ($mool_type_change == "A1") {

            /*
                ветвь 1. (А1)

                a. Исключение:
                    p2√śad 1 = śā + śad = śāśad [śāśada]
                b. Сандхизируемые корни ряда A1 (кроме √uøkṣ, √śuøṣ), имеющие i или u в P, удваиваются по схеме PPA1F:
                    p2√uøc = u + uøc = uuøc [uvāca, ūce]
                    p2√viøc = vi + viøc = viviøc [vivyāca]
                c. Остальные корни ряда A1 удваиваются по схеме P’aPA1(F):
                    p2√gṛøh = ja + gṛøh = jagṛøh [jagrāha]
                    p2√uøkṣ = ua + uakṣ = vavakṣ [vavakṣa]
            */

           

            if ($mool == "śad" && $omonim == "1") {

                $model = "śāśad";
                $stop=1;
                $comment = " ветвь 1 непустого Р, корень-исключение $mool $omonim, А1, схема удвоения śā + śad ";
            }
            elseif((mb_strpos($p_mool, "i")!== false||mb_strpos($p_mool, "u")!== false) && ($mool != "uØkṣ" && $mool != "śuØṣ"))
            {
                $model = $p_mool."|".$p_mool."|".$mool_change."|".$f_mool;
                $p_before_mool=$p_mool."|";

                $comment = " Сандхизируемые корни ряда A1 (кроме √uøkṣ, √śuøṣ), имеющие i или u в P, удваиваются по схеме PPA1F ";
            }
            else
            {
                $model = $p_new ."|a|". $p_mool ."|". $mool_change ."|". $f_mool;
                $p_before_mool = $p_new ."|a|";

                $dimensions_p2=dimensions($p_new ."|a|". $p_mool ."|". $mool_change,"","",0,0,0,"");
                $dimensions_p2[1]=str_replace("-","",$dimensions_p2[1]);
                $e_position=mb_strlen($dimensions_p2[1]);
                $change_later=array("E'",$mool_change,'', $e_position);


                $comment = " Остальные корни ряда A1 удваиваются по схеме P’aPA1(F) ";
            }

        

        } elseif ($mool_type_change == "A2") {
            $flag_has_vowels = 0;

            for ($s = 0; $s < mb_strlen($mool); $s++) {
                $what = mb_substr($mool, $s, 1);
                $compare = find_in_array($what, $vowels, "V");
                if ($compare == "V") {
                    $flag_has_vowels = 1;
                }
            }

            if ($mool == "jīØ̄") {
                $model = "jijīØ̄";
               // $prefix = "ji";
                $comment = " корень-исключение $mool ";
            } elseif ($mool == "uØ̄") { /// ???
                //1 М П – ū [ūvus – 3 pl.]; 2 М П – vavā [vavau]  ?

                $model = "uØuØ̄";
                //$prefix = "uØ";
                $comment = " корень-исключение $mool ???";
            } elseif ($mool == "vīØ̄") {
                $model = "vivīØ̄";
                //$prefix = "vi";
                $comment = " корень-исключение $mool ";
           // } elseif ($flag_has_vowels == 0 || $mool_type == 2 || $mool == "śīØ̄" || $mool == "stīØ̄") {
            } else {
                //P’aPA2F

                $model = $p_new . "|a|" . $p_mool . $mool_change . $f_mool;
                $p_before_mool = $p_new . "|a|";

                //$prefix = $p_new . "|a|";
                $comment = " ветвь 2 непустого Р, А2, схема удвоения P’aPA2F ";
            }
        } elseif ($mool_type_change == "I0" || $mool_type_change == "I1" || $mool_type_change == "I2") {
            //Корни  рядов I удваиваются по схеме P’iPIF:

            

            $model =  $p_new . "|i|".$p_mool ."|". $mool_change ."|". $f_mool;
            $p_before_mool = $p_new . "|i|";

            $dimensions_p2=dimensions($p_new . "|i|" . $p_mool . "|". $mool_change,"","",0,0,0,"");
            $dimensions_p2[1]=str_replace("-","",$dimensions_p2[1]);
            $e_position=mb_strlen($dimensions_p2[1]);
            $change_later=array("E'",$mool_change,'', $e_position);

           // $prefix = $p_new . "|i|";
            $comment = " ветвь 3 непустого Р, ряд I, схема удвоения P’iPIF ";
        } elseif ($mool_type_change == "U0" || $mool_type_change == "U1" || $mool_type_change == "U2") {  // ????
            if ($mool == "bhū") {
                $model = "babhūv";
                $stop=1;
             //   $prefix = "ba";
                $comment = " p2√bhū = babhū [babhūva] (!) U2 не чередуется, в позиции EV принимает вид ūv";  // !!!
            } elseif ($mool == "dhau") {
                $model = "dadhau";
                //$prefix = "da";
                $comment = " корень-исключение $mool";
            } else {
                //P’uPUF

                $model = $p_new . "|u|" . $p_mool ."|". $mool_change ."|". $f_mool;
                $p_before_mool = $p_new . "|u|";

                $change_later=manual_e($p_new."|u|".$p_mool."|".$mool_change,$mool_change);
         
                $comment = " ветвь 4 непустого Р, ряд U, схема удвоения P’uPUF ";
            }
        } else {
            //P’aPRF P’aPLF  P’aPNF P’aPMF

            $model = $p_new . "|a|" . $p_mool . "|". $mool_change . "|". $f_mool;
            $p_before_mool = $p_new . "|a|";
            
            $dimensions_p2=dimensions($p_new . "|a|" . $p_mool . "|". $mool_change,"","",0,0,0,"");
            $dimensions_p2[1]=str_replace("-","",$dimensions_p2[1]);
  
           
            $change_later=array("E'",$mool_change,'', $e_position);

           
            $comment = " ветви 5-8 непустого Р, ряд $mool_type_change, схема удвоения P’aP(чередующийся элемент ряда)F ";
        }
    }

   
    $debug_text.= "<BR>Удвоенный корень для чередования без сандхи: $model ($comment)<BR><BR>";

    $result[0]=$model;
    $result[1]=$prefix;
    $result[2]=$stop;
    $result[3]=$model_sandhi;
    $result[4]=$change_later;
    $result['debug']=$debug_text;
    $result['p_before_mool']=$p_before_mool;


    //echo "DEBUG TEXT:".$debug_text;
   // print_r($result);

    return $result;

}

function duplication_pr2($array, $mool, $mool_type, $mool_type_change, $omonim, $debug) {   // c вариантами, есть корень, который переходит в III тип и потому не чередуется

    $p_new = $array[1];

    $p_mool = $array[3];
    $mool_change = $array[4];
    $f_mool = $array[5];

    

    if ($debug) {
        echo "<BR><b>Подготовка корня для создания о.н.в. 3 класса </b><BR>";
    }

    if (!$array[1]) {

        if($mool=="ṛ")
        {
            $model[]="iyṛ";
            $p_before_mool[]='';

            $prefix[]="iy";
            $comment[]=" корень-исключеие $mool ";
        }
        else
        {
            $model[]="-";
            $p_before_mool[]='';
            $prefix[]="";
            $comment[]=" похоже это не корень 3 класса ";
        }
        
    }
    else
    {

        if ($mool_type_change == "A1") {   /// вар

            if($mool=="sac")
            {
                $model[]="siṣac";
                $p_before_mool[]='si';
                $prefix[]="si";
                $comment[]=" 1 вариант о.н.в. 3 класса для корня-исключения $mool";
            }
            
            if($mool_type==1)
            {
                $model[]=$p_new."|i|".$p_mool."|".$mool_change."|".$f_mool;
                $prefix[]=$p_new."|i|";
                $comment[]=" Корни ряда A1, чередующиеся по I типу, удваиваются по схеме P’iPA1F";
            }
            elseif($mool_type==2)
            {
                $model[]=$p_new."|a|".$p_mool."|".$mool_change."|".$f_mool;
                $prefix[]=$p_new."|a|";
                $comment[]=" Корни ряда A1, чередующиеся по II типу, удваиваются по схеме P’aPA1F";
            }

        }
        elseif ($mool_type_change == "A2") {   /// с вар.

            if($mool=="rā")
            {
                $model[]=$p_new."a"."|rā";
                $prefix[]=$p_new."|a|";
                $comment[]=" вариант о.н.в. 3 класса для корня-исключения $mool удваивается по схеме P’aPA2F";
            }
            
            if(($mool=="dØ̄"&&$omonim==1)||($mool=="dhØ̄"&&$omonim==1)||($mool=="hØ̄"&&$omonim==1))
            {
                $model[]=$p_new."|a|".$p_mool."|".$mool_change."|".$f_mool;
                $prefix[]=$p_new."|a|";
                $comment[]=" √dØ̄ 1, √dhØ̄ 1, √hØ̄ 1 удваиваются по схеме P’aPA2F";
            }
            else
            {
                $model[]=$p_new."|i|".$p_mool."|".$mool_change."|".$f_mool;
                $prefix[]=$p_new."|i|";
                $comment[]=" удваиваются по схеме P’iPA2F";
            }

        }
        elseif ($mool_type_change == "I0"||$mool_type_change == "I1"||$mool_type_change == "I2") {

            if($mool=="dhī")
            {
                $model[]="dīdhī";
                $prefix[]="dī";
                $comment[]=" корень-исключеие $mool ряда $mool_type_change";
            }
            elseif($mool=="pī")
            {
                $model[]="pīpī";
                $prefix[]="pī";
                $comment[]=" корень-исключеие $mool ряда $mool_type_change";
            }
            else
            {
                $model[]=$p_new."|i|".$p_mool."|".$mool_change."|".$f_mool;
                $prefix[]=$p_new."|i|";
                $comment[]=" корень ряда I удваивается по схеме P’iPIF";
            }

        }
        elseif ($mool_type_change == "U0"||$mool_type_change == "U1"||$mool_type_change == "U2") {

           
                $model[]=$p_new."|u|".$p_mool."|".$mool_change."|".$f_mool;
                $prefix[]=$p_new."|u|";
                $comment[]=" корень ряда U удваивается по схеме P’uPUF";
       

        }
        elseif ($mool_type_change == "R0"||$mool_type_change == "R1"||$mool_type_change == "R2") {

            if($mool=="vṛt")
            {
                $model[]="vavṛt";
                $prefix[]="va";
                $comment=" корень-исключеие $mool ряда $mool_type_change";
            }
            else
            {
                $model[]=$p_new."|i|".$p_mool."|".$mool_change."|".$f_mool;
                $prefix[]=$p_new."|i|";
                $comment[]=" корень ряда R удваивается по схеме P’iPRF";
            }

        }
        elseif ($mool_type_change == "N0"||$mool_type_change == "N1"||$mool_type_change == "N2"||$mool_type_change == "L") {


                $model[]=$p_new."|a|".$p_mool."|".$mool_change."|".$f_mool;
                $prefix[]=$p_new."|a|";
                $comment[]=" корни ряда N-L удваиваются по схеме P’aPNF - P’aPLF";

        }
        elseif ($mool_type_change == "M0"||$mool_type_change == "M1"||$mool_type_change == "M2")
        {
            $model[]=$p_mool."|".$mool_change."|".$f_mool;
            $prefix[]="";
            $comment[]="не засвидетельсвовано";
        }
        else
        {
            if($mool=="hū")
            {
                $model[]="juhū";
                $prefix[]="ju";
                $comment[]=" корень-исключеие $mool ряда $mool_type_change";
            }

            if($mool=="hvṛ")
            {
                $model[]="juhur";
                $prefix[]="ju";
                $comment[]=" корень-исключеие $mool ряда $mool_type_change переходит в III тип и потому не чередуется";
            }
        }




    }

    if ($debug) {
        if($model[1])
        {
            echo "<BR>Удвоенный корень для чередования без сандхи.<BR>У этого корня есть два варианта <BR>1) ".$model[1]." (".$comment.")  <BR>2) ".$model[0]." (".$comment[0].") <BR><BR>";
        }
        else
        {
            echo "<BR>Удвоенный корень для чередования без сандхи: ".$model[0]." (".$comment[0].") <BR><BR>";
        }
    }

    $result[0]=$model;
    $result[1]=$prefix;
    $result[2]=$model_var;
    $result[3]=$prefix_var;
    $result['p_before_mool']=$prefix;

    return $result;

}


/*
function duplication_d2($array, $mool, $mool_type, $mool_type_change, $omonim, $verb_setnost, $stop, $debug) {   // Здесь пока МП определяется БЕЗ таблицы 4!

        
    // Инициализация переменных из массива
    $p_new = $array[1];
    $p_mool = $array[3];
    $mool_change = $array[4];
    $f_mool = $array[5];
    $f_mool_array = $array[6];
    $p_mool_array = $array[7];

    // Комбинирование корня и изменений
    $pef = $p_mool . $mool_change . $f_mool;

    // Определение сетности глагола
    switch ($verb_setnost) {
        case "s":
            $verb_setnost_des = "s";
            break;
        case "a":
            $verb_setnost_des = "a";
            break;
        case "v":
        case "v1":
            $verb_setnost_des = "v";
            break;
        case "v2":
        case "v3":
            $verb_setnost_des = "s";
            break;
        case "v4":
            $verb_setnost_des = "a";
            break;
        default:
            $verb_setnost_des = "0";
            break;
    }

    // Получение последней буквы корня, если он существует
    if ($p_mool) {
        $last_p_letter = $p_mool_array[count($p_mool_array) - 1];
    }

    // Определение открытого корня
    $is_open_mool = ($f_mool !== "" && $f_mool !== "Ø" && $f_mool !== "Ø̄") ? 0 : 1;

    // Определение значения переменной $setn в зависимости от сетности глагола
    if ($verb_setnost_des === "s") {
        $setn = "is";
    } elseif ($verb_setnost_des === "a") {
        $setn = "s";
    } elseif ($verb_setnost_des === "v") {
        $setn = "is";
        $e1[0] = get_e_mp_table4("", $mool, "is", $omonim, $mool_type, $mool_change, $mool_type_change, 1, "");
        $e1[1] = get_e_mp_table4("", $mool, "s", $omonim, $mool_type, $mool_change, $mool_type_change, 1, "");
    }

    // Если стоп не установлен, получение значений e1, e2, e3
    if ($stop != 1) {
        $e1 = get_e_mp_table4("", $mool, $setn, $omonim, $mool_type, $mool_change, $mool_type_change, 1, "");
        $e2 = get_e_mp_table4("", $mool, $setn, $omonim, $mool_type, $mool_change, $mool_type_change, 2, "");
        $e3 = get_e_mp_table4("", $mool, $setn, $omonim, $mool_type, $mool_change, $mool_type_change, 3, "");
    } else {
        $e1 = $mool_change;
        $e2 = $mool_change;
        $e3 = $mool_change;
    }

    // Вывод отладочной информации, если включен режим отладки
    if ($debug) {
        $debug_text .= "<br><b>Подготовка корня для создания дезидератива </b><br>";
        $debug_text .= "<br>Сетность корня для образования формы основы дезидератива DS: " . $verb_setnost_des . "<br>";
    }

    // Чередование корня, если array[1] не установлен
    $c = 0;
    if (!$array[1]) {
        if ($f_mool == "h") {
            $model[] = $e2 . "|" . "ji" . $f_mool;
            $comment[$c] = "F = h, E2jiF";
            $c++;
        } elseif ($mool == "i") {
            $model[] = "iyi";
            $model[] = "ayiyi";
            $comment[$c] = "Корень-исключение $mool 1 форма";
            $c++;
            $comment[$c] = "Корень-исключение $mool 2 форма";
            $c++;
            $stop = 1;
        } elseif ($mool == "akṣ") {
            $model[] = "ācikṣ";
            $comment[$c] = "Корень-исключение $mool";
            $c++;
        } elseif ($mool == "ṛ") {
            $model[] = "arir";
            $comment[$c] = "Корень-исключение $mool";
            $c++;
        } else {
            // Обработка корней, начинающихся на чередующийся элемент
            if (count($f_mool_array) == 2) {
                $model[] = $e2 . "|" . $f_mool . "|i|" . $f_mool_array[1];
                $comment[$c] = "Если в F два согласных (F = C1C2), то корень удваивается по схеме E2FiC2";
                $c++;
            } else {
                $model[] = $e2 . "|" . $f_mool . "|i|" . $f_mool;
                $comment[$c] = "Остальные корни, начинающиеся на чередующийся элемент, удваиваются по схеме E2FiF";
                $c++;
            }

            // Обработка корней с придыхательными согласными
            if (seeking_1_bukva($f_mool_array[0], 0)[3] == "H" || seeking_1_bukva($f_mool_array[1], 0)[3] == "H") {
                $comment_string = "";
                foreach ($f_mool_array as &$f_mool_elem) {
                    switch ($f_mool_elem) {
                        case "kh":
                            $comment_string .= " ( $f_mool_elem меняется на";
                            $f_mool_elem = "k";
                            $comment_string .= " $f_mool_elem )";
                            break;
                        case "ch":
                            $comment_string .= " ( $f_mool_elem меняется на";
                            $f_mool_elem = "c";
                            $comment_string .= " $f_mool_elem )";
                            break;
                        case "ṭh":
                            $comment_string .= " ( $f_mool_elem меняется на";
                            $f_mool_elem = "ṭ";
                            $comment_string .= " $f_mool_elem )";
                            break;
                        case "th":
                            $comment_string .= " ( $f_mool_elem меняется на";
                            $f_mool_elem = "t";
                            $comment_string .= " $f_mool_elem )";
                            break;
                        case "ph":
                            $comment_string .= " ( $f_mool_elem меняется на";
                            $f_mool_elem = "p";
                            $comment_string .= " $f_mool_elem )";
                            break;
                        case "gh":
                            $comment_string .= " ( $f_mool_elem меняется на";
                            $f_mool_elem = "g";
                            $comment_string .= " $f_mool_elem )";
                            break;
                        case "jh":
                            $comment_string .= " ( $f_mool_elem меняется на";
                            $f_mool_elem = "j";
                            $comment_string .= " $f_mool_elem )";
                            break;
                        case "ḍh":
                            $comment_string .= " ( $f_mool_elem меняется на";
                            $f_mool_elem = "ḍ";
                            $comment_string .= " $f_mool_elem )";
                            break;
                        case "dh":
                            $comment_string .= " ( $f_mool_elem меняется на";
                            $f_mool_elem = "d";
                            $comment_string .= " $f_mool_elem )";
                            break;
                        case "bh":
                            $comment_string .= " ( $f_mool_elem меняется на";
                            $f_mool_elem = "b";
                            $comment_string .= " $f_mool_elem )";
                            break;
                    }
                }
                unset($f_mool_elem);
                $comment[$c] = $comment_string;
                $c++;

                $f_new = implode("", $f_mool_array);
                $model[] = $e2 . "|" . $f_new . "|i|" . $f_mool;
                $comment[$c] = "Если F – содержит придыхательный согласный (F = (X)H), то он преобразуется в свою непридыхательную пару E2(X)С-iF";
                $c++;
            }
        }
    }
    else
    {
                
        // Обработка типа изменений A1
        if ($mool_type_change == "A1") {
            if ($mool == "suØp") {
                $model[] = "suṣu" . $e1 . "p";
                $comment[$c++] = " корень-исключение $mool ряда $mool_type_change";
            } else {
                switch ($mool) {
                    case "śak":
                        $model[] = "śiśik";
                        break;
                    case "pad":
                        $model[] = "pipād";
                        break;
                    case "pat":
                        $model[] = "pīpat";
                        break;
                    default:
                        $p_before_mool = $p_new . "|i|";
                        $model[] = $p_before_mool . $p_mool . "|" . $e2 . "|" . $f_mool;
                        $comment[$c++] = " Корни ряда A1 удваиваются по схеме P’iPE2F";
                        break;
                }
                $comment[$c++] = " корень-исключение $mool ряда $mool_type_change";
            }
        }
        // Обработка типа изменений A2
        elseif ($mool_type_change == "A2") {
            if ($mool == "dhØ̄" && $omonim == 1) {
                $model[] = "dadh" . $e1;
                $comment[$c++] = " корень-исключение $mool $omonim ряда $mool_type_change";
            } elseif ($mool == "pØ̄") {
                $model[] = "pip" . $e1;
                $comment[$c++] = " корень-исключение $mool ряда $mool_type_change";
            }
            $p_before_mool = $p_new . "|i|";
            $model[] = $p_before_mool . $p_mool . "|" . $e2 . "|" . $f_mool;
            $comment[$c++] = " Корни ряда A2, удваиваются по схеме P’iPE2(F)";
        }
        // Обработка типов изменений I0, I1, I2
        elseif (in_array($mool_type_change, ["I0", "I1", "I2"])) {
            if ($is_open_mool) {
                $p_before_mool = $p_new . "|i|";
                $model[] = $p_before_mool . $p_mool . "|ī";
                $comment[$c++] = " Открытые корни рядов I удваивается по схеме P’iPī";
            } else {
                if (in_array($verb_setnost_des, ["s", "v"])) {
                    $p_before_mool = $p_new . "|i|";
                    $model[] = $p_before_mool . $p_mool . "|" . $e1 . "|" . $f_mool;
                    $comment[$c++] = " Закрытые корни рядов I при образовании дезидератива со вставным -i- удваиваются по схемам P’iPE1F и P’iPE2F";
                    $model[] = $p_before_mool . $p_mool . "|" . $e2 . "|" . $f_mool;
                    $comment[$c++] = " Закрытые корни рядов I при образовании дезидератива со вставным -i- удваиваются по схемам P’iPE1F и P’iPE2F";
                } elseif ($verb_setnost_des == "a") {
                    $p_before_mool = $p_new . "|i|";
                    $model[] = $p_before_mool . $p_mool . "|" . $e1 . "|" . $f_mool;
                    $comment[$c++] = " Закрытые корни рядов I при образовании дезидератива без вставного -i- удваиваются по схеме P’iPE1F";
                } else {
                    $model[] = "-";
                    $comment[$c++] = " Нет сетности - видимо такой формы в языке не встречается";
                }
            }
        }
        // Обработка типов изменений U0, U1, U2
        elseif (in_array($mool_type_change, ["U0", "U1", "U2"])) {
            if ($is_open_mool) {
                $p_before_mool = $p_new . "|u|";
                $model[] = $p_before_mool . $p_mool . "|ū|";
                $comment[$c++] = " Открытые корни рядов U удваивается по схеме P’uPū";
            } else {
                if (in_array($verb_setnost_des, ["s", "v"])) {
                    $p_before_mool = $p_new . "|u|";
                    $model[] = $p_before_mool . $p_mool . "|" . $e1 . "|" . $f_mool;
                    $comment[$c++] = " Закрытые корни рядов U удваиваются по схемам P’uPE1F и P’uPE2F";
                    $model[] = $p_before_mool . $p_mool . "|" . $e2 . "|" . $f_mool;
                    $comment[$c++] = " Закрытые корни рядов U удваиваются по схемам P’uPE1F и P’uPE2F";
                } elseif ($verb_setnost_des == "a") {
                    $p_before_mool = $p_new . "|u|";
                    $model[] = $p_before_mool . $p_mool . "|" . $e1 . "|" . $f_mool;
                    $comment[$c++] = " Закрытые корни рядов U удваиваются по схеме P’uPE1F";
                } else {
                    $model[] = "-";
                    $comment[$c++] = " Нет сетности - видимо такой формы в языке не встречается";
                }
            }
        }
        // Обработка типов изменений R0, R1, R2
        elseif (in_array($mool_type_change, ["R0", "R1", "R2"])) {
            if ($is_open_mool) {
                if ($mool == "tṝ") {
                    $p_before_mool = $p_new . "|u|";
                    $model[] = $p_before_mool . $p_mool . "|ūr";
                    $comment[$c++] = " корень-исключение √tṝ удваивается по схеме P’uPūr";
                } elseif (seeking_1_bukva($last_p_letter, 0)[4] == "L") {
                    $p_before_mool = $p_new . "|u|";
                    $model[] = $p_before_mool . $p_mool . "|ūr";
                    $comment[$c++] = " Открытые корни рядов R, оканчивающиеся на звук губного ряда, удваиваются по схеме P’uPūr";
                } else {
                    $p_before_mool = $p_new . "|i|";
                    $model[] = $p_before_mool . $p_mool . "|īr";
                    $comment[$c++] = " Остальные открытые корни рядов R удваиваются по схеме P’iPīr";
                }
                if (in_array($verb_setnost_des, ["s", "v"])) {
                    $p_before_mool = $p_new . "|i|";
                    $model[] = $p_before_mool . $p_mool . "|" . $e2;
                    $comment[$c++] = " Открытые корни рядов R при образовании дезидератива со вставным -i- удваиваются по схеме P’iPE2";
                }
            } else {
                if (in_array($verb_setnost_des, ["s", "v"])) {
                    $p_before_mool = $p_new . "|i|";
                    $model[] = $p_before_mool . $p_mool . "|" . $e1 . "|" . $f_mool;
                    $model[] = $p_before_mool . $p_mool . "|" . $e2 . "|" . $f_mool;
                    $comment[$c++] = " Закрытые корни рядов R удваиваются по схемам P’iPE1F и P’iPE2F";
                } elseif ($verb_setnost_des == "a") {
                    $p_before_mool = $p_new . "|i|";
                    $model[] = $p_before_mool . $p_mool . "|" . $e1 . "|" . $f_mool;
                    $comment[$c++] = " Закрытые корни рядов R удваиваются по схеме P’iPE1F";
                } else {
                    $model[] = "-";
                    $comment[$c++] = " Нет сетности - видимо такой формы в языке не встречается";
                }
            }
        }
        // Обработка типа изменений L
        elseif ($mool_type_change == "L") {
            $p_before_mool = $p_new . "|i|";
            $model[] = $p_before_mool . $p_mool . "|" . $e2 . "|" . $f_mool;
            $comment[$c++] = " Корни ряда L удваиваются по схеме P’iPE2(F)";
        }
        // Обработка типов изменений M0, M1, M2
        elseif (in_array($mool_type_change, ["M0", "M1", "M2"])) {
            $p_before_mool = $p_new . "|i|";
            $model[] = $p_before_mool . $p_mool . "|" . $e2;
            $comment[$c++] = " Корни рядов M удваиваются по схеме P’iPE2";
        }
        // Обработка типов изменений N0, N1, N2
        elseif (in_array($mool_type_change, ["N0", "N1", "N2"])) {
            if (in_array($mool, ["hn̥", "mn̥"])) {
                $p_before_mool = $p_new . "|i|";
                $model[] = $p_before_mool . $p_mool . "|" . $e3;
                $comment[$c++] = " √hn̥, √mn̥ удваиваются по схеме P’iPE3";
            } elseif (in_array($mool, ["vn̥̄", "rn̥dh", "mn̥d"])) {
                $p_before_mool = $p_new . "|i|";
                $model[] = $p_before_mool . $p_mool . "|" . $e4;
                $comment[$c++] = " √vn̥̄, √rn̥dh, √mn̥d удваиваются по схеме P’iPE4";
            } else {
                $p_before_mool = $p_new . "|i|";
                $model[] = $p_before_mool . $p_mool . "|" . $e2;
                $comment[$c++] = " корни рядов N удваиваются по схеме P’iPE2";
            }
        }



    }

    
    // Исключения от каузатива для корня √dhū
    if ($stop == 1 && $mool == "dhū") {
        $model[] = "dudhūn";
        $comment[] = "Из таблицы CaS: √dhū (вар.) → dhūn (III тип)";
    }
    
    // Подсчет количества вариантов модели
    $count = count($model);
    
    if ($debug) {
        // Если вариантов больше одного, добавляем информацию о количестве вариантов
        if ($count > 1) {
            $debug_text .= "<br><br>Удвоенный корень для чередования без сандхи.<br><u>У этого корня есть " . $count . " варианта</u>";
        }
    }
    
    if ($debug) {
        // Вывод удвоенных корней и комментариев в debug
        $debug_text .= "<br><br><b>После удвоения: </b><br>";
        foreach ($model as $value) {
            $debug_text .= $value . " ";
        }
    
        $debug_text .= "<br><br><b>Комментарий:</b> ";
        foreach ($comment as $value) {
            $debug_text .= $value . " ";
        }
    
        $debug_text .= "<br><br>";
    }
    
    // Инициализация результата
    $result = [
        0 => $model,
        'debug' => $debug_text
    ];
    
    return $result;
    
    

}
*/

function duplication_d2($array, $mool, $mool_type, $mool_type_change, $omonim, $verb_setnost, $stop, $debug) {   // Здесь пока МП определяется БЕЗ таблицы 4!

    
    $p_new = $array[1];

    $p_mool = $array[3];
    $mool_change = $array[4];
    $f_mool = $array[5];
    $f_mool_array = $array[6];
    $p_mool_array = $array[7];

    $pef=$p_mool.$mool_change.$f_mool;

    

    switch($verb_setnost)
		{
			case "s";$verb_setnost_des="s";break;
			case "a";$verb_setnost_des="a";break;
			case "v";$verb_setnost_des="v";break;
			case "v1";$verb_setnost_des="v";break;
			case "v2";$verb_setnost_des="s";break;
			case "v3";$verb_setnost_des="s";break;
			case "v4";$verb_setnost_des="a";break;
			default:$verb_setnost_des="0";break;
		}


    if($p_mool)
    {    
        $last_p_letter=$p_mool_array[count($p_mool_array)-1];
        //echo $last_p_letter;
    }

    if($f_mool!=""&&$f_mool!="Ø"&&$f_mool!="Ø̄")
    {
        $is_open_mool=0;
    }
    else
    {
        $is_open_mool=1;
    }

    if($verb_setnost_des=="s")
    {
        $setn="is";
    }

    if($verb_setnost_des=="a")
    {
        $setn="s";
    }

    if($verb_setnost_des=="v")
    {
        $setn="is";
        $e1[0]=get_e_mp_table4("",$mool,$setn,$omonim,$mool_type,$mool_change,$mool_type_change,1,"");
        $setn="s";
        $e1[1]=get_e_mp_table4("",$mool,$setn,$omonim,$mool_type,$mool_change,$mool_type_change,1,"");
    }

    //echo "STOP: $stop SETN: $setn<BR>";

    if($stop!=1)
    {
        $e1=get_e_mp_table4("",$mool,$setn,$omonim,$mool_type,$mool_change,$mool_type_change,1,"");
        
        //$e2=get_e_mp_simple($mool_type_change, $mool_type, 2);

        $e2=get_e_mp_table4("",$mool,$setn,$omonim,$mool_type,$mool_change,$mool_type_change,2,"");

        //echo "e2HERE: $e2<BR>";

        //$e3=get_e_mp_simple($mool_type_change, $mool_type, 3);

        $e3=get_e_mp_table4("",$mool,$setn,$omonim,$mool_type,$mool_change,$mool_type_change,3,"");
    }
    else
    {
        $e1 = $mool_change;
        $e2 = $mool_change;
        $e3 = $mool_change;
       
        //echo "E1: $e1";
    }


    if ($debug) {
        $debug_text.="<BR><b>Подготовка корня для создания дезидератива </b><BR>";
 
        $debug_text.= "<BR>Сетность корня для образования формы основы дезидератива DS: ".$verb_setnost_des."<BR>";

    }
    $c=0;
    if (!$array[1]) {   /// Как оно чередуется???

       

        if($f_mool=="h")
        {
      
            $model[]=$e2."|"."ji".$f_mool;
            $p_before_mool[]="";
            $comment[$c]="F = h, E2jiF";
            $c++;

        }
        elseif($mool=="i")
        {
            $model[]="iyi";
            $p_before_mool[]="";

            $model[]="ayiyi";
            $p_before_mool[]="";

            $comment[$c]=" корень-исключение $mool 1 форма";
            $c++;
            $comment[$c]=" корень-исключение $mool 2 форма";
            $c++;
            $stop=1;
        }
        elseif($mool=="akṣ")
        {
            $model[]="ācikṣ";
            $p_before_mool[]="";

            $comment[$c]=" корень-исключение $mool";
            $c++;
        }
        elseif($mool=="ṛ")
        {
            $model[]="arir";
            $p_before_mool[]="";

            $comment[$c]=" корень-исключение $mool";
            $c++;
        }
        else
        {
            
            //echo count($f_mool_array);

            if(count($f_mool_array)==2)
            {
                //E2FiC2

               // $e2=get_e_mp_simple($mool_type_change, $mool_type, 2);
                $model[]=$e2."|".$f_mool."|i|".$f_mool_array[1];
                $p_before_mool[]="";

                $comment[$c]=" Если в F два согласных (F = C1C2), то корень удваивается по схеме E2FiC2";
                $c++;
            }
            else
            {
                //Остальные корни, начинающиеся на чередующийся элемент, удваиваются по схеме E2FiF:
               // $e2=get_e_mp_simple($mool_type_change, $mool_type, 2);
                $model[]=$e2."|".$f_mool."|i|".$f_mool;
                $p_before_mool[]="";

                $comment[$c]=" Остальные корни, начинающиеся на чередующийся элемент, удваиваются по схеме E2FiF";
                $c++;
               //echo $comment;
            }

                    if(seeking_1_bukva($f_mool_array[0],0)[3]=="H"||seeking_1_bukva($f_mool_array[1],0)[3]=="H")
                    {
                        for($k=0;$k<count($f_mool_array);$k++)
                        {
                            switch ($f_mool_array[$k]) {
                            case "kh":
                                $comment_string.= " ( ".$f_mool_array[$k]." меняется на";
                                $f_mool_array[$k] = "k";
                                $comment_string.= " ".$f_mool_array[$k]." )";
                           
                                break;
                            case "ch":
                                $comment_string.= " ( ".$f_mool_array[$k]." меняется на";
                                $f_mool_array[$k] = "c";
                                $comment_string.= " ".$f_mool_array[$k]." )";
                                break;
                            case "ṭh":
                                $comment_string.= " ( ".$f_mool_array[$k]." меняется на";
                                $f_mool_array[$k] = "ṭ";
                                $comment_string.= " ".$f_mool_array[$k]." )";
                                break;
                            case "th":
                                $comment_string.= " ( ".$f_mool_array[$k]." меняется на";
                                $f_mool_array[$k] = "t";
                                $comment_string.= " ".$f_mool_array[$k]." )";
                                break;
                            case "ph":
                                $comment_string.= " ( ".$f_mool_array[$k]." меняется на";
                                $f_mool_array[$k] = "p";
                                $comment_string.= " ".$f_mool_array[$k]." )";
                                break;
                            case "gh":
                                $comment_string.= " ( ".$f_mool_array[$k]." меняется на";
                                $f_mool_array[$k]  = "g";
                                $comment_string.= " ".$f_mool_array[$k]." )";
                                break;
                            case "jh":$comment_string.= " ( ".$f_mool_array[$k]." меняется на";
                                $f_mool_array[$k] = "j";
                                $comment_string.= " ".$f_mool_array[$k]." )";
                                break;
                            case "ḍh":$comment_string.= " ( ".$f_mool_array[$k]." меняется на";
                                $f_mool_array[$k] = "ḍ";
                                $comment_string.= " ".$f_mool_array[$k]." )";
                                break;
                            case "dh":$comment_string.= " ( ".$f_mool_array[$k]." меняется на";
                                $f_mool_array[$k] = "d";
                                $comment_string.= " ".$f_mool_array[$k]." )";
                                break;
                            case "bh":$comment_string.= " ( ".$f_mool_array[$k]." меняется на";
                                $f_mool_array[$k] = "b";
                                $comment_string.= " ".$f_mool_array[$k]." )";
                                break;
                                                        
                                $comment[$c]=$comment_string;
                                $c++;
                            
                            }

                        $f_new=$f_mool_array[0].$f_mool_array[1];  
                        }
            
                       // $e2=get_e_mp_simple($mool_type_change, $mool_type, 2);
                        $model[]=$e2."|".$f_new."|i|".$f_mool;
                        $p_before_mool[]="";
                        $comment[$c]=" Если F – содержит придыхательный согласный (F = (X)H), то он преобразуется в свою непридыхательную пару E2(X)С-iF";
                        $c++;

                    }
        }

    }
    else
    {

        if ($mool_type_change == "A1") {  

            if($mool=="suØp")
            {
               // echo "here";
                $model[]="suṣu".$e1."p";
              //  echo "suṣu".$e1."p";
                $p_before_mool[]="";
                $comment[$c]=" корень-исключение $mool ряда $mool_type_change";
                $c++;
            }
            else
            {

                if($mool=="śak")
                {
                    $model[]="śiśik";
                    $p_before_mool[]="";
                    $comment[$c]=" корень-исключение $mool ряда $mool_type_change";
                    $c++;
                }
                elseif($mool=="pad")  //pipād [pipādiṣ-]  (вар.)   Во всех остальных случаях √pad ведет себя как aniṭ.
                {
                    $model[]="pipād";
                    $p_before_mool[]="";
                    $comment[$c]=" корень-исключение $mool ряда $mool_type_change";
                    $c++;
                }
                elseif($mool=="pat")
                {
                    $model[]="pīpat";
                    $p_before_mool[]="";
                    $comment[$c]=" корень-исключение $mool ряда $mool_type_change";
                    $c++;
                }

                //Корни ряда A1 удваиваются по схеме P’iPE2F:

               // $e2=get_e_mp_simple($mool_type_change, $mool_type, 2);
                $model[]=$p_new."|i|".$p_mool."|".$e2."|".$f_mool;
                $p_before_mool[]=$p_new."|i|";

                $comment[$c]=" Корни ряда A1 удваиваются по схеме P’iPE2F";
                $c++;

    
            
            }

        }
        elseif ($mool_type_change == "A2") {   ///////// (F) ????

            if($mool=="dhØ̄"&&$omonim==1)
            {
                $model[]="dadh".$e1;
                $p_before_mool[]="";
                $comment[$c]=" корень-исключение $mool $omonim ряда $mool_type_change";
                $c++;
            }

            if($mool=="pØ̄")
            {
                $model[]="pip".$e1;
                $p_before_mool[]="";
                $comment[$c]=" корень-исключение $mool ряда $mool_type_change";
                $c++;
            }
                //Корни ряда A2, удваиваются по схеме P’iPE2(F):

               // $e2=get_e_mp_simple($mool_type_change, $mool_type, 2);
                $model[]=$p_new."|i|".$p_mool."|".$e2."|".$f_mool;
                $p_before_mool[]=$p_new."|i|";
                $comment[$c]=" Корни ряда A2, удваиваются по схеме P’iPE2(F)";
                $c++;
   
        }
        elseif ($mool_type_change == "I0" || $mool_type_change == "I1" || $mool_type_change == "I2") {

            if($is_open_mool)
            {
                //Открытые корни рядов I удваивается по схеме P’iPī:
                $model[]=$p_new."|i|".$p_mool."|ī";
                $p_before_mool[]=$p_new."|i|";
                $comment[$c]=" Открытые корни рядов I удваивается по схеме P’iPī";
                $c++;
            }
            else
            {
                
                
                if($verb_setnost_des=="s"||$verb_setnost_des=="v")
                {
                    // Закрытые корни рядов I при образовании дезидератива со вставным -i- между d2√ и суффиксом (d2√)-s- (seṭ-форма) удваиваиваются по схемам P’iPE1F и P’iPE2F:
                    
                    $model[]=$p_new."|i|".$p_mool."|".$e1."|".$f_mool;
                    $p_before_mool[]=$p_new."|i|";
                    $comment[$c]=" Закрытые корни рядов I при образовании дезидератива со вставным -i- между d2√ и суффиксом (d2√)-s- (seṭ-форма) удваиваиваются по схемам P’iPE1F и P’iPE2F";
                    $c++;
                    $model[]=$p_new."|i|".$p_mool."|".$e2."|".$f_mool;
                    $p_before_mool[]=$p_new."|i|";
                    $comment[$c]=" Закрытые корни рядов I при образовании дезидератива со вставным -i- между d2√ и суффиксом (d2√)-s- (seṭ-форма) удваиваиваются по схемам P’iPE1F и P’iPE2F";
                    $c++;
                }
                elseif($verb_setnost_des=="a")
                {
                    //Закрытые корни рядов I при образовании дезидератива без вставного -i- между d2√ и суффиксом (d2√)-s- (aniṭ-форма) удваиваются по схеме P’iPE1F:

                    $model[]=$p_new."|i|".$p_mool."|".$e1."|".$f_mool;
                    $p_before_mool[]=$p_new."|i|";
                    $comment[$c]=" Закрытые корни рядов I при образовании дезидератива со вставным -i- между d2√ и суффиксом (d2√)-s- (seṭ-форма) удваиваиваются по схемам P’iPE1F и P’iPE2F";
                    $c++;
                }
                else
                {
                    $model[]="-";
                    $p_before_mool[]="";
                    $comment[$c]=" Нет сетности - видимо такой формы в языке не встречается";
                    $c++;
                }


            }

        }
        elseif ($mool_type_change == "U0" || $mool_type_change == "U1" || $mool_type_change == "U2") {

            if($is_open_mool)
            {
                
                //Открытые корни рядов U удваивается по схеме P’uPū:
                $model[]=$p_new."|u|".$p_mool."|ū|";
                $p_before_mool[]=$p_new."|u|";
                $comment[$c]=" Открытые корни рядов U удваивается по схеме P’uPū";
                $c++;


              

            }
            else
            {
                if($verb_setnost_des=="s"||$verb_setnost_des=="v")
                {
                    // Закрытые корни рядов I при образовании дезидератива со вставным -i- между d2√ и суффиксом (d2√)-s- (seṭ-форма) удваиваиваются по схемам P’iPE1F и P’iPE2F:
                    
                    $model[]=$p_new."|u|".$p_mool."|".$e1."|".$f_mool;
                    $p_before_mool[]=$p_new."|u|";
                    $comment[$c]=" Закрытые корни рядов U при образовании дезидератива со вставным -i- между d2√ и суффиксом (d2√)-s- (seṭ-форма) удваиваиваются по схемам P’uPE1F и P’uPE2F";
                    $c++;
                    
                    $model[]=$p_new."|u|".$p_mool."|".$e2."|".$f_mool;
                    $p_before_mool[]=$p_new."|u|";
                    $comment[$c]=" P’uPE2F";
                    $c++;
                }
                elseif($verb_setnost_des=="a")
                {
                    //Закрытые корни рядов I при образовании дезидератива без вставного -i- между d2√ и суффиксом (d2√)-s- (aniṭ-форма) удваиваются по схеме P’uPE1F:

                    $model[]=$p_new."|u|".$p_mool."|".$e1."|".$f_mool;
                    $p_before_mool[]=$p_new."|u|";
                    $comment[$c]=" Закрытые корни рядов U при образовании дезидератива без вставного -i- между d2√ и суффиксом (d2√)-s- (aniṭ-форма)  удваиваиваются по схеме P’uPE1F";
                    $c++;
                }
                else
                {
                    $model[]="-";
                    $p_before_mool[]="";
                    $comment[$c]=" Нет сетности - видимо такой формы в языке не встречается";
                    $c++;
                }


            }

        }
        elseif ($mool_type_change == "R0" || $mool_type_change == "R1" || $mool_type_change == "R2") {

            if($is_open_mool)
            {
                //a1.1. Открытые корни рядов R, чье P оканчивается на звук губного ряда, а также √tṝ (вар.) удваиваются по схеме P’uPūr: d2√mṛ = mu + mūr = mumūr [mumūrṣ-]
                if($mool=="tṝ")
                {
                    $model[]=$p_new."|u|".$p_mool."|ūr";
                    $p_before_mool[]=$p_new."|u|";
                    $comment[$c]=" корень-исключение √tṝ (вар.) удваивается по схеме P’uPūr";
                    $c++;
                }
                elseif(seeking_1_bukva($last_p_letter,0)[4]=="L")
                {
                    $model[]=$p_new."|u|".$p_mool."|ūr";
                    $p_before_mool[]=$p_new."|u|";
                    $comment[$c]=" Открытые корни рядов R, чье P оканчивается на звук губного ряда удваиваются по схеме P’uPūr";
                    $c++;
                }
                else
                {
                    $model[]=$p_new."|i|".$p_mool."|īr";
                    $p_before_mool[]=$p_new."|i|";
                    $comment[$c]=" Остальные открытые корни рядов R удваиваются по схеме: P’iPīr";
                    $c++;
                }

                if($verb_setnost_des=="s"||$verb_setnost_des=="v")
                {
                    //Открытые корни рядов R при образовании дезидератива со вставным -i- между d2√ и суффиксом (d2√)-s- (seṭ-форма) удваиваются по схеме P’iPE2:
                    //d2√jṝ = ji + jar = jijar [jijariṣ-]

                    $model[]=$p_new."|i|".$p_mool."|".$e2;
                    $p_before_mool[]=$p_new."|i|";
                    $comment[$c]=" Открытые корни рядов R при образовании дезидератива со вставным -i- между d2√ и суффиксом (d2√)-s- (seṭ-форма) удваиваются по схеме P’iPE2";
                    $c++;
                }
               
            }
            else
            {
               // Закрытые корни рядов R при образовании дезидератива со вставным -i- между d2√ и суффиксом (d2√)-s- (seṭ-форма) удваиваются по схемам P’iPE1F и P’iPE2F:
               if($verb_setnost_des=="s"||$verb_setnost_des=="v")
               {
                   $model[]=$p_new."|i|".$p_mool."|".$e1."|".$f_mool;
                   $p_before_mool[]=$p_new."|i|";

                   $model[]=$p_new."|i|".$p_mool."|".$e2."|".$f_mool;
                   $p_before_mool[]=$p_new."|i|";

                   $comment[$c]=" P’iPE1F Закрытые корни рядов R при образовании дезидератива со вставным -i- между d2√ и суффиксом (d2√)-s- (seṭ-форма) удваиваются по схемам P’iPE1F и P’iPE2F";
                   $c++;
                   $comment[$c]=" P’iPE2F";
                   $c++;
               }
               elseif($verb_setnost_des=="a")
               {
                //Закрытые корни рядов R при образовании дезидератива без вставного -i- между d2√ и суффиксом (d2√)-s- (aniṭ-форма) удваиваются по схеме P’iPE1F:
                //d2√dṛś = di + dṛś = didṛś [didṛkṣ-]
                $model[]=$p_new."|i|".$p_mool."|".$e1."|".$f_mool;
                $p_before_mool[]=$p_new."|i|";

                $comment[$c]=" Закрытые корни рядов R при образовании дезидератива без вставного -i- между d2√ и суффиксом (d2√)-s- (aniṭ-форма) удваиваются по схеме P’iPE1F";
                $c++;

               }
               else
               {
                    $model[]="-";
                    $comment[$c]=" Нет сетности - видимо такой формы в языке не встречается";
                    $c++;
               }

            }

        }
        elseif ($mool_type_change == "L")
        {
            //Корни ряда L удваиваются по схеме P’iPE2(F):
            //d2√cal = ci + cal = cical [cicaliṣ-]

            $model[]=$p_new."|i|".$p_mool."|".$e2."|".$f_mool;
            $p_before_mool[]=$p_new."|i|";

            $comment[$c]=" Корни ряда L удваиваются по схеме P’iPE2(F)";
            $c++;


        }
        elseif ($mool_type_change == "M0" || $mool_type_change == "M1" || $mool_type_change == "M2")
        {
            //Корни рядов M удваиваются по схеме P’iPE2:
            //d2√kṣm̥ = ci + kṣam = cikṣam [cikṣaṃs- & cikṣamiṣ-]

            //echo "e1: $e1 e2: $e2  e3: $e3<BR><BR>";

            $model[]=$p_new."|i|".$p_mool."|".$e2;
            $p_before_mool[]=$p_new."|i|";

            $comment[$c]=" Корни рядов M удваиваются по схеме P’iPE2";
            $c++;


        }
        elseif ($mool_type_change == "N0" || $mool_type_change == "N1" || $mool_type_change == "N2")
        {
            if($mool=="hn̥"||$mool=="mn̥")
            {
            //В рядах N некоторые корни, а именно: √hn̥, √mn̥ – удваиваются по схеме P’iPE3:
            //d2√hn̥ = ji + ghān = jighān [jighāṃs-]

            $model[]=$p_new."|i|".$p_mool."|".$e3;
            $p_before_mool[]=$p_new."|i|";

            $comment[$c]=" √hn̥, √mn̥ удваиваются по схеме P’iPE3";
            $c++;

            }
            else
            {
                
                //echo "mool: ".$mool."<BR>";

                if($mool=="vn̥̄"||$mool=="rn̥dh"||$mool=="mn̥d")
                {
                    //В рядах N некоторые корни, а именно: √sn̥̄ (вар.), √mn̥th (вар.), √vn̥̄, √rn̥dh, √mn̥d – удваиваются по схеме P’iPE1(F):
                    $model[]=$p_new."|i|".$p_mool."|".$e1."|".$f_mool;
                    $p_before_mool[]=$p_new."|i|";

                    $comment[$c]=" В рядах N некоторые корни, а именно: √sn̥̄ (вар.), √mn̥th (вар.), √vn̥̄, √rn̥dh, √mn̥d – удваиваются по схеме P’iPE1(F)";
                    $c++;
                }
                else
                {

                    if($mool=="sn̥̄"||$mool=="mn̥th")
                    {
                        //В рядах N некоторые корни, а именно: √sn̥̄ (вар.), √mn̥th (вар.), √vn̥̄, √rn̥dh, √mn̥d – удваиваются по схеме P’iPE1(F):
                        $model[]=$p_new."|i|".$p_mool."|".$e1."|".$f_mool;
                        $p_before_mool[]=$p_new."|i|";

                        $comment[$c]=" В рядах N некоторые корни, а именно: √sn̥̄ (вар.), √mn̥th (вар.), √vn̥̄, √rn̥dh, √mn̥d – удваиваются по схеме P’iPE1(F)";
                        $c++;
                    }

                    //Остальные корни рядов N удваиваются по схеме P’iPE2(F):
                    //d2√jn̥̄ = ji + jan = jijan [jijaniṣ-]

                    $model[]=$p_new."|i|".$p_mool."|".$e2."|".$f_mool;
                    $p_before_mool[]=$p_new."|i|";

                    $comment[$c]=" Остальные корни рядов N удваиваются по схеме P’iPE2(F)";
                    $c++;


                }

            }


        }


    }

      ///ИСКЛЮЧЕНИЯ ОТ КАУЗАТИВА!
      if($stop==1&&$mool=="dhū")
      {
          $model[]="dudhūn";
          $p_before_mool[]="";
          $comment[$c]=" Из таблицы CaS: √dhū (вар.) → dhūn (III тип) ";
          $c++;
      }
      
      $count=count($model);

      if ($debug) {
          if($count>1)
          {
              $debug_text.= "<BR><BR>Удвоенный корень для чередования без сандхи.<BR><u>У этого корня есть ".$count." варианта</u>";
          }
      }

    if ($debug) {
        $debug_text.= "<BR><BR><b>После удвоения: </b><BR>"; 

        for($c=0;$c<count($model);$c++)
        {
            $debug_text.=$model[$c]." ";
        }

        $debug_text.= "<BR><BR><b>Комментарий:</b> ";

        for($c=0;$c<count($comment);$c++)
        {
            $debug_text.=$comment[$c]." ";
        }

        $debug_text.= "<BR><BR>";
    }

    $j=0;$c=0;
    

    $result[0]=$model;
    $result['debug']=$debug_text;
    $result['p_before_mool']=$p_before_mool;
    

    //print_r($result);
  

    return $result;

}



function duplication_i2($array, $mool, $mool_type, $mool_type_change, $omonim, $debug){
    $p_new = $array[1];

    $p_mool = $array[3];
    $mool_change = $array[4];
    $f_mool = $array[5];
    $f_mool_array = $array[6];
    $p_mool_array = $array[7];

    $p_before_mool='';

    if($p_mool)
    {    
        $last_p_letter=$p_mool_array[count($p_mool_array)-1];
    }

    if($f_mool!=""&&$f_mool!="Ø"&&$f_mool!="Ø̄")
    {
        $is_open_mool=0;
    }
    else
    {
        $is_open_mool=1;
    }

    //$e1=get_e_mp_simple($mool_type_change, $mool_type, 1);
    //$e2=get_e_mp_simple($mool_type_change, $mool_type, 2);
    //$e3=get_e_mp_simple($mool_type_change, $mool_type, 3);

    $e1=get_e_mp_table4("",$mool,$setn,$omonim,$mool_type,$mool_change,$mool_type_change,1,"");
    $e2=get_e_mp_table4("",$mool,$setn,$omonim,$mool_type,$mool_change,$mool_type_change,2,"");
    $e3=get_e_mp_table4("",$mool,$setn,$omonim,$mool_type,$mool_change,$mool_type_change,3,"");


    if ($debug) {
        echo "<BR><b>Подготовка корня для создания Интенсива </b><BR>";
    }

    

    if (!$array[1]) {   

       

        if($f_mool=="h")
        {
          
            $model=$e2."|j|".$mool_change.$f_mool;
            $comment=" Если F = h, то корень удваивается по схеме E2jEF ";
        }
        elseif($mool=="i")
        {
            $model="īyā";
            $comment=" корень-исключение $mool";

        }
        else
        {

            if(count($f_mool_array)==2)
            {
                $new_f=$f_mool_array[1];
                $model=$e2."|".$f_mool."|".$mool_change."|".$new_f;
                $comment=" Если в F два согласных (F = C1C2), то корень удваивается по схеме E2FEC2";

            }
            else
            {
                $new_f=$f_mool;
                $model=$e2.$f_mool.$mool_change.$new_f;
                $comment=" Остальные корни, начинающиеся на чередующийся элемент, удваиваются по схеме E2(F)E(F):";
            }
  
                    if(seeking_1_bukva($f_mool_array[0],0)[3]=="H"||seeking_1_bukva($f_mool_array[1],0)[3]=="H")
                    {
                        for($k=0;$k<count($f_mool_array);$k++)
                        {
                            switch ($f_mool_array[$k]) {
                            case "kh":
                                $f_mool_array[$k] = "k";
                           
                                break;
                            case "ch":
                                $f_mool_array[$k] = "c";
                          
                                break;
                            case "ṭh":
                                $f_mool_array[$k] = "ṭ";
                               
                                break;
                            case "th":
                                $f_mool_array[$k] = "t";
                             
                                break;
                            case "ph":
                                $f_mool_array[$k] = "p";
                             
                                break;
                            case "gh":
                                $f_mool_array[$k]  = "g";
                           
                                break;
                            case "jh":
                                $f_mool_array[$k] = "j";
                              
                                break;
                            case "ḍh":
                                $f_mool_array[$k] = "ḍ";
                             
                                break;
                            case "dh":
                                $f_mool_array[$k] = "d";
                           
                                break;
                            case "bh":
                                $f_mool_array[$k] = "b";
                              
                                break;
                            }

                          
                        }

                        $f_without_h=$f_mool_array[0].$f_mool_array[1];  
                      
                        $model=$e2.$f_without_h.$mool_change.$new_f;
                        $comment.="<BR> Шаг 2. F – содержит придыхательный согласный (F = (X)H), то он преобразуется в свою непридыхательную пару E2(X)С-EF’";

                    }
                
        }

    }
    
    else
    {


            if ($mool_type_change=="A1")
            {
               
                if($mool=="pat")
                {
                    $model = "panīpat";
                    $comment = " Корень - исключение $mool";
                }
                elseif($mool=="dah")
                {
                    $model = "dandah";
                    $comment = " Корень - исключение $mool";
                }
                elseif($mool=="pad")
                {
                    $model = "panīpad";
                    $comment = " Корень - исключение $mool";
                }
                elseif($mool=="kas")
                {
                    $model = "canīkas";
                    $comment = " Корень - исключение $mool";
                }
                elseif($mool=="jap")
                {
                    $model = "jañjap";
                    $comment = " Корень - исключение $mool";
                }
                else
                {
                    $model = $p_new."|ā|".$p_mool."|".$mool_change."|".$f_mool;
                    $p_before_mool=$p_new."|ā|";
                    $comment = " Корни ряда А1 удваиваются по схеме P’āPEF";
                }
                

            }
            elseif ($mool_type_change == "A2")
            {
                    $model = $p_new."|"."ā"."|".$p_mool."|".$mool_change."|".$f_mool;
                    $p_before_mool=$p_new."|"."ā"."|";
                    $comment = " Корни ряда А2 удваиваются по схеме P’āPE(F)";
            }
            elseif ($mool_type_change == "I0" || $mool_type_change == "I1" || $mool_type_change == "I2")
            {
                if($mool=="rī")
                {
                    $model = "rāya";
                    $comment = " Корень - исключение $mool";
                }
                elseif($mool=="vic")
                {
                    $model = "vevec";
                    $comment = " Корень - исключение $mool";
                }
                else
                {
                
                    $model = $p_new."|"."e"."|".$p_mool."|".$mool_change."|".$f_mool;
                    $p_before_mool=$p_new."|"."e"."|";
                    $comment = " Корни рядов I удваиваются по схеме P’ePE(F)";
                }
            }
            elseif ($mool_type_change == "U0" || $mool_type_change == "U1" || $mool_type_change == "U2")
            {
                if($mool=="dyut")
                {
                    $model = "davidyut";
                    $model2 = "dedyut";
                    $comment = " Корень - исключение $mool";
                }
                else
                {
                
                    $model = $p_new."|"."o"."|".$p_mool."|".$mool_change."|".$f_mool;

                    $p_before_mool=$p_new."|"."o"."|";
                    $comment = " Корни рядов U удваиваются по схеме P’oPE(F):";
                }
            }
            elseif($mool_type_change == "M0" || $mool_type_change == "M1" || $mool_type_change == "M2")
            {
                
                    $model = $p_new."|"."am"."|".$p_mool."|".$mool_change;
                    $p_before_mool=$p_new."|"."am"."|";
                    $comment = " Корни рядов М удваиваются по схеме P’amPE ";

                    if($mool=="gm̥")
                    {
                        $model2 = "gánīgm̥";
                        $comment.= " Корень - исключение $mool (вар) ";
                    }
            }
            elseif ($mool_type_change == "L")
            {
                
                    $model = $p_new."|"."al"."|".$p_mool."|".$mool_change."|".$f_mool;
                    $p_before_mool=$p_new."|"."al"."|";
                    $comment = " Корни ряда L удваиваются по схеме P’alPE(F): ";

                    if($mool=="cal")
                    {
                        $model2 = "cācal";
                        $comment.= " Корень - исключение $mool , существует вариант образования i2√ по схеме для А1: сā + cal = cācal ";
                    }
            }
            elseif ($mool_type_change == "R0" || $mool_type_change == "R1" || $mool_type_change == "R2")
            {
                    if($mool=="car")
                    {
                        $model = "carcūr";
                        $comment = " Корень - исключение $mool ";
                    }
                    else
                    {
                        $model = $p_new."|arī|".$p_mool."|".$mool_change."|".$f_mool;
                        $p_before_mool=$p_new."|"."arī"."|";
                        $comment = " Все корни рядов R могут удваиваться по схеме P’arīPE(F) (со вставной -ī-)";

                        //Некоторые корни R, а именно: √kṛṣ, √dṛ 2, √dṛś, √dṛh, √bṛh, √dhṛ, √vṛ 1,  √vṛ 2, √vṛj, √vṛt, √sṛ, √hṛ 1, √hṛ 2, √kṝ 2, √gṝ 1, √dṝ, √tṝ – также способны удваиваться по схеме P’arPE(F) (без вставной -ī-):
                        if($mool=="kṛṣ"||($mool=="dṛ"&&$omonim==2)||$mool=="dṛś"||$mool=="dṛh"||$mool=="bṛh"||$mool=="dhṛ"||($mool=="vṛ"&&$omonim==1)||($mool=="vṛ"&&$omonim==2)||$mool=="vṛj"||$mool=="vṛt"||$mool=="sṛ"||($mool=="hṛ"&&$omonim==1)||($mool=="hṛ"&&$omonim==2)||($mool=="kṝ"&&$omonim==2)||($mool=="gṝ"&&$omonim==1)||$mool=="dṝ"||$mool=="tṝ")
                        {
                            $model2 = $p_new."|ar|".$p_mool."|".$mool_change."|".$f_mool;
                            $p_before_mool2=$p_new."|"."ar"."|";
                            $comment.= " Некоторые корни R, а именно: $mool также способны удваиваться по схеме P’arPE(F) (без вставной -ī-)";
                        }

                        //Некоторые корни R, а именно: √garh, √dhṛ, √mṛṣ, √śṛ, √spṛdh, √smṛ, √hvṛ, √kṝ 1, √jṝ, √jvar √tṝ, √pṝ, √śṝ, √stṝ, √svar, √tvar, √hvṛ– также могут удваиваться по схеме P’āPE(F):
                        if($mool=="garh"||$mool=="dhṛ"||$mool=="mṛṣ"||$mool=="śṛ"||$mool=="spṛdh"||$mool=="smṛ"||$mool=="hvṛ"||($mool=="kṝ"&&$omonim==1)||$mool=="jṝ"||$mool=="jvar"||$mool=="tṝ"||$mool=="pṝ"||$mool=="śṝ"||$mool=="stṝ"||$mool=="svar"||$mool=="tvar"||$mool=="hvṛ")
                        {
                            $model2 = $p_new."|ā|".$p_mool."|".$mool_change."|".$f_mool;
                            $p_before_mool2=$p_new."|ā|";
                            $comment.= " Некоторые корни R, а именно: $mool также могут удваиваться по схеме P’āPE(F)";
                        }

                    }
            }
            elseif ($mool_type_change == "N0" || $mool_type_change == "N1" || $mool_type_change == "N2")
            {
                $model = $p_new."|an|".$p_mool."|".$mool_change."|".$f_mool;
                $p_before_mool=$p_new."|an|";
                $comment = " Все корни рядов N могут удваиваться по схеме P’anPE(F) (без вставной -ī-)";

                if($is_open_mool==0||$mool=="phan"||$mool=="pn̥"||$mool=="vn̥̄"||$mool=="svan")
                {
                    $model2 = $p_new."|anī|".$p_mool."|".$mool_change."|".$f_mool;
                    $p_before_mool2=$p_new."|anī|";
                    $comment.= " Закрытые корни рядов N, а также некоторые корни, а именно: √phan, √pn̥, √vn̥̄, √svan – также могут удваиваться по схеме P’anīPE(F)";
                }

                if($is_open_mool==0||$mool=="khn̥̄"||$mool=="jn̥̄"||$mool=="sn̥̄")
                {
                    $model3 = $p_new."|ā|".$p_mool."|".$mool_change."|".$f_mool;
                    $p_before_mool3=$p_new."|ā|";
                    $comment.= " Закрытые корни рядов N и некоторые другие корни, а именно: √khn̥̄, √jn̥̄, √sn̥̄ – также могут удваиваться по схеме P’āPE(F)";
                }



            }


    }

    if ($debug) {
        echo "<BR>$model<BR>$comment<BR>";
    }

    $result[0]=$model;
    $p_before_mool_array[]=$p_before_mool;
    
    if($model2)
    {
        $result[0]= $result[0].",".$model2;
        $p_before_mool_array[]=$p_before_mool2;
    }
    if($model3)
    {
        $result[0]=$result[0].",".$model3;
        $p_before_mool_array[]=$p_before_mool3;
    }

    $result[1]=$comment;
    $result['p_before_mool']=$p_before_mool_array;

    return $result;
}

function duplication_a2($array, $mool, $mool_type, $mool_type_change, $omonim, $debug)
{
   
    $p_new = $array[1];

    $p_mool = $array[3];
    $mool_change = $array[4];
    $f_mool = $array[5];
    $f_mool_array = $array[6];
    $p_mool_array = $array[7];
    $e="|".$mool_change."|";
    

    if($p_mool)
    {    
        $last_p_letter=$p_mool_array[count($p_mool_array)-1];
    }

    if($f_mool!=""&&$f_mool!="Ø"&&$f_mool!="Ø̄")
    {
        $is_open_mool=0;
    }
    else
    {
        $is_open_mool=1;
    }

    /*
        $e1=get_e_mp_table4("",$mool,$setn,$omonim,$mool_type,$mool_change,$mool_type_change,1,"");
        $e2=get_e_mp_table4("",$mool,$setn,$omonim,$mool_type,$mool_change,$mool_type_change,2,"");
        $e3=get_e_mp_table4("",$mool,$setn,$omonim,$mool_type,$mool_change,$mool_type_change,3,"");
    */


    if (!$array[1]) {   

        if($mool_type==3)
        {
            $mool_type=1;
        }


        if($f_mool=="h")
        {
            //$e_new="ji";
            $e_new="|i|";
            $model=$e."j".$e_new.$f_mool;

            $comment=" Если F’ = h, то корень удваивается по схеме E'jiF ";
         
        }
        elseif($mool=="m̥̄")
        {
            $model="|am|am";

            //$e_new="|a|";
            $comment=" корень-исключение $mool";

        }
        elseif($mool=="akṣ")
        {
            $model="аcikṣ";
            $e_new="|a|";
            $comment=" корень-исключение $mool";

        }
        elseif($mool=="īkṣ")
        {
            $model="ecikṣ";
            $e_new="|ī|";
            $comment=" корень-исключение $mool";

        }
        else
        {

            if(count($f_mool_array)==2)
            {
                $e_new="|i|";
                $new_f=$f_mool_array[1];
                $model=$e.$f_mool.$e_new.$new_f;
                $change_later[]=manual_e($e,$mool_change);

                $comment=" Если F’ > одного согласного (F’ = C1C2), то корень удваивается по схеме EFiC2";

            }
            else
            {
                $e_new="|i|";
                $new_f=$f_mool;
                $model=$e.$f_mool.$e_new.$f_mool;
                $change_later[]=manual_e($e,$mool_change);

                $comment=" Остальные корни этого вида удваиваются по схеме EFiF ";
            }
  
                    if(seeking_1_bukva($f_mool_array[0],0)[3]=="H"||seeking_1_bukva($f_mool_array[1],0)[3]=="H")
                    {
                        for($k=0;$k<count($f_mool_array);$k++)
                        {
                            switch ($f_mool_array[$k]) {
                            case "kh":
                                $f_mool_array[$k] = "k";
                           
                                break;
                            case "ch":
                                $f_mool_array[$k] = "c";
                          
                                break;
                            case "ṭh":
                                $f_mool_array[$k] = "ṭ";
                               
                                break;
                            case "th":
                                $f_mool_array[$k] = "t";
                             
                                break;
                            case "ph":
                                $f_mool_array[$k] = "p";
                             
                                break;
                            case "gh":
                                $f_mool_array[$k]  = "g";
                           
                                break;
                            case "jh":
                                $f_mool_array[$k] = "j";
                              
                                break;
                            case "ḍh":
                                $f_mool_array[$k] = "ḍ";
                             
                                break;
                            case "dh":
                                $f_mool_array[$k] = "d";
                           
                                break;
                            case "bh":
                                $f_mool_array[$k] = "b";
                              
                                break;
                            }

                          
                        }

                        $e_new="|i|";
                        $f_without_h=$f_mool_array[0].$f_mool_array[1];  
                      
                        $model=$e.$f_without_h.$e_new.$new_f;
                        $change_later[]=manual_e($e,$mool_change);

                        $comment.="<BR> Если F – придыхательный согласный (F = H), то он преобразуется в свою непридыхательную пару E’С-HiF";

                    }
                
        }

    }
    else
    {
        if($mool_type_change=="A1")
        {
            if($mool=="suØp")
            {
                $model="sūsuØp";
                //$stop[]=1;
                $comment.=" Исключение: a2√suØp (вар.) = sū + suØp = sūsuØp ";
            }
            else
            {
                $e_new="|i|";
                $e_new2="|ī|";
                $model=$p_new.$e_new.$p_mool.$e.$f_mool;
                $p_before_mool=$p_new.$e_new;
                $change_later[]=manual_e($p_new.$e_new.$p_mool.$e,$mool_change);

                $model2=$p_new.$e_new2.$p_mool.$e.$f_mool;
                $p_before_mool2=$p_new.$e_new2;
                $change_later[]=manual_e($p_new.$e_new2.$p_mool.$e,$mool_change);
                $comment.=" Все корни ряда А1 могут удваиваться по схемам P’iPA1F и P’īPA1F ";
            }

            if($is_open_mool==0&&$mool_type==2)
            {
                $e_new3="|a|";
                $model3=$p_new.$e_new3.$p_mool.$e.$f_mool;
                $p_before_mool3=$p_new.$e_new3;
                $change_later[]=manual_e($p_new.$e_new3.$p_mool.$e,$mool_change);
                $comment.=" Закрытые корни ряда А1 II типа также могут удваиваться по схеме P’aPA1F ";
            }
        }
        elseif($mool_type_change=="A2")
        {
            if($mool=="dØ̄"&&$omonim==1)
            {
                $model="dīdad";
                $e_new="|ī|";
                //$stop[]=1;
                $comment.=" Открытые корни А2 составляют класс исключений $mool $omonim ";
            }
            elseif($mool=="dhØ̄"&&$omonim==2)
            {
                $model="dadhØ̄";
                $e_new="|a|";
                //$stop[]=1;
                $comment.=" Открытые корни А2 составляют класс исключений $mool $omonim ";
            }
            elseif($mool=="pØ̄")
            {
                $model="pīpī";
                $e_new3="|ī|";
                $comment.=" Открытые корни А2 составляют класс исключений $mool ";
            }
            elseif($mool=="mØ̄"&&$omonim==3)
            {
                $model="mīme";
                $e_new3="|ī|";
                $comment.=" Открытые корни А2 составляют класс исключений $mool $omonim ";
            }
            elseif($mool=="sØ̄")
            {
                $model="sīṣe";
                $e_new3="|ī|";
                $comment.=" Открытые корни А2 составляют класс исключений $mool $omonim ";
            }
            elseif($mool=="hØ̄"&&$omonim==1)
            {
                $model="jījah";
                $e_new3="|ī|";
                $comment.=" Открытые корни А2 составляют класс исключений $mool $omonim ";
            }
            elseif($is_open_mool==0)
            {
                $e_new="|a|";
                $e_new2="|i|";
                $e_new3="|ī|";
                
                $model=$p_new.$e_new.$p_mool.$e.$f_mool;
                $p_before_mool=$p_new.$e_new;
                $change_later[]=manual_e($p_new.$e_new.$p_mool.$e,$mool_change);

                $model2=$p_new.$e_new2.$p_mool."|a|".$f_mool;
                $p_before_mool2=$p_new.$e_new2;
                $change_later[]=manual_e($p_new.$e_new2.$p_mool.$e,$mool_change);

                $model3=$p_new.$e_new3.$p_mool."|a|".$f_mool;
                $p_before_mool3=$p_new.$e_new3;
                $change_later[]=manual_e($p_new.$e_new3.$p_mool.$e,$mool_change);

                $comment.=" Закрытые корни А2 удваиваются по схеме P’aPA2F, а также по схемам (c межрядовой трансформацией [A2↦A1]) P’iPA1F и P’īPA1F: ";

               // echo $model3;
            }
            else
            {
                $model=" видимо это аорист не 3 класса ";
                $comment=" видимо это аорист не 3 класса ";
            }
        }
        elseif ($mool_type_change == "I0" || $mool_type_change == "I1" || $mool_type_change == "I2")
        {
            
                if($mool=="veṣṭ")
                {
                    $e_new="|a|";
                    $model="vaveṣṭ";
                    $comment.=" Исключение $mool $omonim a2√veṣṭ (вар.) = va + veṣṭ= vaveṣṭ ";
                }
                
                if($mool=="ceṣṭ")
                {
                    $e_new="|a|";
                    $model="caceṣṭ";
                    $comment.=" Исключение $mool $omonim a2√ceṣṭ (вар.) = ca + ceṣṭ = caceṣṭ ";
                }

                $e_new2="|i|";
                $e_new3="|ī|";
                
                $model2=$p_new.$e_new2.$p_mool.$e.$f_mool;
                $p_before_mool2=$p_new.$e_new2;
                $change_later[]=manual_e($p_new.$e_new2.$p_mool.$e,$mool_change);

                $model3=$p_new.$e_new3.$p_mool.$e.$f_mool;
                $p_before_mool3=$p_new.$e_new3;
                $change_later[]=manual_e($p_new.$e_new3.$p_mool.$e,$mool_change);

                $comment.=" Остальные корни рядов I удваиваются по схемам P’iPI(F) и P’īPI(F): ";

            /*

            if(($mool=="ji"&&$omonim==1)||$mool=="jri"||$mool=="ḍī"||$mool=="bhī"||$mool=="rī"||$mool=="vī"||$mool=="śī"||$mool=="śrī")
            {
                $e_new="i";
                $e_new2="ī";
                
                $model=$p_new.$e_new.$p_mool.$e.$f_mool;
                $model2=$p_new.$e_new2.$p_mool.$e.$f_mool;
                $comment.=" Корни √ji 1, √jri, √ḍī, √bhī, √rī, √vī, √śī, √śrī, √hīḍ (вар.) удваиваются по схемам P’iPI2(F) и P’īPI2(F) ";
            }
            else
            {

                if($mool=="hīḍ")
                {
                    $model=$p_new.$e_new.$p_mool.$e.$f_mool;
                    $model2=$p_new.$e_new2.$p_mool.$e.$f_mool;
                    $comment.=" √hīḍ (вар.) удваивается по схемам P’iPI2(F) и P’īPI2(F) ";
                }

                if($mool=="veṣṭ")
                {
                    $model="vaveṣṭ";
                    $comment.=" Исключение $mool $omonim a2√veṣṭ (вар.) = va + veṣṭ= vaveṣṭ ";
                }
                
                if($mool=="ceṣṭ")
                {
                    $model="caceṣṭ";
                    $comment.=" Исключение $mool $omonim a2√ceṣṭ (вар.) = ca + ceṣṭ = caceṣṭ ";
                }

                $model3=$p_new."i".$p_mool.$e1.$f_mool;
                $model4=$p_new."ī".$p_mool.$e1.$f_mool;
                $comment.=" Остальные корни рядов I удваиваются по схемам P’iPI(F) и P’īPI(F): ";

            }
            */
        }
        elseif ($mool_type_change == "U0" || $mool_type_change == "U1" || $mool_type_change == "U2")
        {
            /*
            a. Все корни U могут удваиваться по схемам P’uPU1(F) и P’ūPU1(F):
            a2√luṭh = lu + loṭh = luloṭh [aluloṭhat] & = lū + luṭh = lūluṭh [alūluṭhat]
            b. Открытые корни U также могут удваиваться по схемам P’uPU2(F), P’ūPU2(F), P’iPU2(F) и P’īPU2(F):
            a2√sru (вар.) = si + sro = sisro [asisravat]
            a2√jū = jī + jo = jījo [ajījavat]
            */

            $e_new="|u|";
            $e_new2="|ū|";
            $e_new3="|i|";
            $e_new4="|ī|";

            $model=$p_new.$e_new.$p_mool.$e.$f_mool;
            $p_before_mool=$p_new.$e_new;
            $change_later[]=manual_e($p_new.$e_new.$p_mool.$e,$mool_change);

            $model2=$p_new.$e_new2.$p_mool.$e.$f_mool;
            $p_before_mool2=$p_new.$e_new2;
            $change_later[]=manual_e($p_new.$e_new2.$p_mool.$e,$mool_change);

            $comment.=" Все корни U могут удваиваться по схемам P’uPU(F) и P’ūPU(F): ";

            if($is_open_mool==1)
            {
                $model3=$p_new.$e_new3.$p_mool.$e.$f_mool;
                $p_before_mool3=$p_new.$e_new3;
                $change_later[]=manual_e($p_new.$e_new3.$p_mool.$e,$mool_change);

                $model4=$p_new.$e_new4.$p_mool.$e.$f_mool;
                $p_before_mool4=$p_new.$e_new4;
                $change_later[]=manual_e($p_new.$e_new4.$p_mool.$e,$mool_change);

                $comment.=" Открытые корни U могут удваиваться по схемам P’iPU(F) и P’īPU(F): ";
            }
          


        }
        elseif ($mool_type_change == "R0" || $mool_type_change == "R1" || $mool_type_change == "R2")
        {
 
            if($mool=="pṝ")
            {
                $model="pūpṝ";
                $e_new="|ū|";
                $comment.=" корень-исключение $mool ряда R ";
            }
            elseif($mool=="sphṝ")
            {
                $model="pusphur";
                $e_new="|u|";
                $comment.=" корень-исключение $mool ряда R ";
            }
            elseif($mool=="vṛ")
            {
                $e_new="|a|";
                $e_new2="|ī|";

                $model=$p_new.$e_new.$p_mool.$e.$f_mool;
                $change_later[]=manual_e($p_new.$e_new.$p_mool.$e,$mool_change);

                $model2=$p_new.$e_new2.$p_mool.$e.$f_mool;
                $change_later[]=manual_e($p_new.$e_new2.$p_mool.$e,$mool_change);

                $comment.=" √vṛ, √dṝ, √stṝ – также могут удваиваться по схемам P’aPR(F) и P’īPR(F) ";
            }
            elseif($mool=="dṝ")
            {
                $e_new="|a|";
                $e_new2="|ī|";

                $model=$p_new.$e_new.$p_mool.$e.$f_mool;
                $change_later[]=manual_e($p_new.$e_new.$p_mool.$e,$mool_change);

                $model2=$p_new.$e_new2.$p_mool.$e.$f_mool;
                $change_later[]=manual_e($p_new.$e_new2.$p_mool.$e,$mool_change);

                $comment.=" √vṛ, √dṝ, √stṝ – также могут удваиваться по схемам P’aPR2(F) и P’īPR1(F) ";
            }
            elseif($mool=="stṝ")
            {
                $e_new="|a|";
                $e_new2="|ī|";

                $model=$p_new.$e_new.$p_mool.$e.$f_mool;
                $change_later[]=manual_e($p_new.$e_new.$p_mool.$e,$mool_change);

                $model2=$p_new.$e_new2.$p_mool.$e.$f_mool;
                $change_later[]=manual_e($p_new.$e_new2.$p_mool.$e,$mool_change);
                $comment.=" √vṛ, √dṝ, √stṝ – также могут удваиваться по схемам P’aPR2(F) и P’īPR1(F) ";
            }
            else
            {
                if($is_open_mool==0)
                {
                    $e_new="|a|";
                    $e_new2="|ī|";

                    $model=$p_new.$e_new.$p_mool.$e.$f_mool;
                    $p_before_mool=$p_new.$e_new;
                    $change_later[]=manual_e($p_new.$e_new.$p_mool.$e,$mool_change);

                    $model2=$p_new.$e_new2.$p_mool.$e.$f_mool;
                    $p_before_mool2=$p_new.$e_new2;
                    $change_later[]=manual_e($p_new.$e_new2.$p_mool.$e,$mool_change);
                    $comment.=" Закрытые корни рядов R также могут удваиваться по схемам P’aPR(F) и P’īPR(F): ";
                    
                }
                else
                {
                    $e_new="|ī|";
                    $model=$p_new.$e_new.$p_mool.$e;
                    $p_before_mool=$p_new.$e_new;
                    $change_later[]=manual_e($p_new.$e_new.$p_mool.$e,$mool_change);

                    //print_r($change_later);

                    $comment.=" Открытые корни R удваиваются по схеме P’īPR ";
                }

                if($mool_type == 2)
                {
                    $e_new3="|i|";
                    $model3=$p_new.$e_new3.$p_mool.$e.$f_mool;
                    $p_before_mool3=$p_new.$e_new3;
                    $change_later[]=manual_e($p_new.$e_new3.$p_mool.$e,$mool_change);

                    $comment.=" Все корни рядов R II типа также могут удваиваться по схеме P’iPR1(F) ";
                }

           
            }

        }
        elseif($mool_type_change=="L")
        {
            $e_new="|i|";
            $e_new2="|ī|";
            
            $model=$p_new.$e_new.$p_mool.$e.$f_mool;
            $p_before_mool=$p_new.$e_new;
            $change_later[]=manual_e($p_new.$e_new.$p_mool.$e,$mool_change);

            $model2=$p_new.$e_new2.$p_mool.$e.$f_mool;
            $p_before_mool2=$p_new.$e_new2;
            $change_later[]=manual_e($p_new.$e_new2.$p_mool.$e,$mool_change);

            $comment.=" Корни ряда L удваиваются по схемам P’iPL(F) и P’īPL(F)";
        }
        elseif($mool_type_change == "M0" || $mool_type_change == "M1" || $mool_type_change == "M2")
        {
            if($mool=="kam")
            {
                $model="cakam";
                $comment.=" Исключение: а2√kam (вар.) = ca + kam = cakam  ";
            }
            
                $e_new2="|i|";
                $e_new3="|ī|";
                
                $model2=$p_new.$e_new2.$p_mool.$e.$f_mool;
                $p_before_mool2=$p_new.$e_new2;
                $change_later[]=manual_e($p_new.$e_new2.$p_mool.$e,$mool_change);

                $model3=$p_new.$e_new3.$p_mool.$e.$f_mool;
                $p_before_mool3=$p_new.$e_new3;
                $change_later[]=manual_e($p_new.$e_new3.$p_mool.$e,$mool_change);

                $comment.=" Корни рядов М удваиваются по схемам P’iPM и P’īPM";
            
        }
        elseif($mool_type_change == "N0" || $mool_type_change == "N1" || $mool_type_change == "N2")
        {
            /*
            a. Корни √chn̥d, √mn̥th (вар.), √rn̥j (вар.), √rn̥dh (вар.), √śvn̥c, √syn̥d, √srn̥s, √svn̥j удваиваются по схемам P’aPN1(F), P’iPN1(F) и P’īPN1(F)            a2√srn̥s = si + srn̥s = sisrn̥s [asisrasat]
            b. Исключения:
            a2√krand = ci + krn̥d = cikrad [acikradat]
            a2√dhvan = di + dhvan = didhvan [adidhvanat]
            a2√svan = si + svan = sisvan [asisvanat]
            с. Открыте корни N I типа удваиваются по схеме P’īPN2F:            a2√hn̥ = jī + ghan = jīghan [ajīghanat]
            d. Остальные корни N удваиваются по схеме P’aPN2(F):            a2√skn̥d = ca + skand = caskand [acaskandat]
            */

            if($mool=="chn̥d"||$mool=="śvn̥c"||$mool=="syn̥d"||$mool=="srn̥s"||$mool=="svn̥j")
            {
                $e_new="|a|";
                $e_new2="|i|";
                $e_new3="|ī|";
                
                $model=$p_new.$e_new.$p_mool.$e.$f_mool;
                $change_later[]=manual_e($p_new.$e_new.$p_mool.$e,$mool_change);

                $model2=$p_new.$e_new2.$p_mool.$e.$f_mool;
                $change_later[]=manual_e($p_new.$e_new2.$p_mool.$e,$mool_change);

                $model3=$p_new.$e_new3.$p_mool.$e.$f_mool;
                $change_later[]=manual_e($p_new.$e_new3.$p_mool.$e,$mool_change);

                $comment.=" Корни √chn̥d, √śvn̥c, √syn̥d, √srn̥s, √svn̥j удваиваются по схемам P’aPN(F), P’iPN(F) и P’īPN(F)";
            }
            elseif($mool=="krand")
            {
                $model="cikrad";
                $comment.=" Исключения: a2√krand = ci + krn̥d = cikrad ";
            }
            elseif($mool=="dhvan")
            {
                $model="didhvan";
                $comment.=" Исключения: a2√dhvan = di + dhvan = didhvan ";
            }
            elseif($mool=="svan")
            {
                $model="sisvan";
                $comment.=" Исключения: a2√svan = si + svan = sisvan ";
            }
            else
            {
                if($mool=="mn̥th"||$mool=="rn̥j"||$mool=="rn̥dh")
                {
                    $e_new="|a|";
                    $e_new2="|i|";
                    $e_new3="|ī|";
                    
                    $model=$p_new.$e_new.$p_mool.$e.$f_mool;
                    $p_before_mool=$p_new.$e_new;
                    $change_later[]=manual_e($p_new.$e_new.$p_mool.$e,$mool_change);

                    $model2=$p_new.$e_new2.$p_mool.$e.$f_mool;
                    $p_before_mool2=$p_new.$e_new2;
                    $change_later[]=manual_e($p_new.$e_new2.$p_mool.$e,$mool_change);

                    $model3=$p_new.$e_new3.$p_mool.$e.$f_mool;
                    $p_before_mool3=$p_new.$e_new3;
                    $change_later[]=manual_e($p_new.$e_new3.$p_mool.$e,$mool_change);

                    $comment.=" Корни √mn̥th (вар.), √rn̥j (вар.), √rn̥dh (вар.) удваиваются по схемам P’aPN1(F), P’iPN1(F) и P’īPN1(F) ";
                }

                if($is_open_mool==1&&$mool_type==1)
                {
                    //с. Открыте корни N I типа удваиваются по схеме P’īPN2F: a2√hn̥ = jī + ghan = jīghan [ajīghanat]
                    $e_new4="|ī|";
                    $model4=$p_new.$e_new4.$p_mool.$e.$f_mool;
                    $p_before_mool4=$p_new.$e_new4;
                    $change_later[]=manual_e($p_new.$e_new4.$p_mool.$e,$mool_change);

                    $comment.=" Открыте корни N I типа удваиваются по схеме P’īPNF ";
                }
                else
                {
                    $e_new4="|a|";
                    $model4=$p_new.$e_new4.$p_mool.$e.$f_mool;
                    $p_before_mool4=$p_new.$e_new4;
                    $change_later[]=manual_e($p_new.$e_new4.$p_mool.$e,$mool_change);

                    $comment.=" Остальные корни N удваиваются по схеме P’aPN2(F) ";
                }
            }

        }

    }

    
    if ($debug) {
        echo "<BR><b>Образование Аориста 3 класса: удвоение корней: $model $model2 $model3 $model4 </b><BR><BR>$comment<BR><BR>";
    }
    

    $result['model'][]=$model;
    $result['model'][]=$model2;
    $result['model'][]=$model3;
    $result['model'][]=$model4;

    $result['p_before_mool'][]=$p_before_mool;
    $result['p_before_mool'][]=$p_before_mool2;
    $result['p_before_mool'][]=$p_before_mool3;
    $result['p_before_mool'][]=$p_before_mool4;

    $result['enew'][]=$e_new;
    $result['enew'][]=$e_new2;
    $result['enew'][]=$e_new3;
    $result['enew'][]=$e_new4;

    $result['change_later']=$change_later;
    $result['comment']=$comment;

    //print_r($result);

    //echo "INFUNCTIONS<BR>";
    //print_r($change_later);
    //echo "<BR><BR>";

    return $result;
   
}


function get_perfect($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,$pada,$debug)
{
    
    $duplication_first=duplication_first($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$debug);

    $duplication_p2=duplication_p2($duplication_first,$verb_name,$verb_type,$verb_ryad,$verb_omonim,$debug);

    $duplication_p2_model=$duplication_p2[0];
    $duplication_p2_prefix=$duplication_p2[1];
    $stop=$duplication_p2[2];
    $duplication_p2_model_sandhi=$duplication_p2[3];
    $change_later=$duplication_p2[4];
    $p_before_mool=$duplication_p2['p_before_mool'];

   ////////STOP OR NOT/////////////////////
    if(!$stop)
    {
        $mool_after_duplication=$duplication_first[3].$duplication_first[4].$duplication_first[5];
    }
    else
    {
        $mool_after_duplication=$duplication_p2_model;
    }

    if($change_later[0]=="E'")
    {
        $flag_e=1;
    }

    if($change_later[4]=="NEED_TWO")
    {
        $need_two=1;
    }


  //  if($debug)
  //  {
 //      echo "<b> Удвоение корня для образования перфекта (без сандхи): ".$duplication_p2_model."</b><BR>";
  //  }

    $is_open_mool = 0;
    if (mb_substr($verb_name, -1, 1) == "Ø" || mb_substr($verb_name, -2, 2) == "Ø̄" || mb_substr($verb_name, -1, 1) == $verb_change || mb_substr($verb_name, -2, 2) == $verb_change || mb_substr($verb_name, -3, 3) == $verb_change ) {
        $is_open_mool = 1;
    }

   if($duplication_p2_model!="Только описательный перфект")
   {
    $result_string[0]=implode(",",$result);
   }
   else
   {
    $result_string[0]="";
   }

  

    $result_string[1]=$duplication_p2_model;
    $result_string[2]=$duplication_p2_model_sandhi;
    $result_string['prefix']=$duplication_p2_prefix;
    $result_string['mad']=$mool_after_duplication;
    $result_string['flag_e']=$flag_e;
    $result_string['need_two']=$need_two;
    $result_string['stop']=$stop;
    
    $result_string['source']=$verb_name;
    $result_string['change_later']=$change_later;
    $result_string['p_before_mool']=$p_before_mool;

    //echo "Result string:";
    //print_r($result_string);

    $debug_text=$duplication_first[8].$duplication_p2['debug'];

    if ($debug) {
        echo '<a class="btn btn-primary" data-bs-toggle="collapse" href="#collapse_des_'.$pada.'" role="button" aria-expanded="false" aria-controls="collapseExample">
        Алгоритм образования Перфекта
        </a>
    
        </p>
        <div class="collapse" id="collapse_des_'.$pada.'">
        <div>';
        echo $debug_text;
        echo '</div></div>';
    }
    
    return $result_string;


}

function get_onv3($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,$debug)
{
        $duplication_first=duplication_first($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$debug);

        $duplication_pr2=duplication_pr2($duplication_first,$verb_name,$verb_type,$verb_ryad,$verb_omonim,$debug);



		$duplication_pr2_model=$duplication_pr2[0];
		$duplication_pr2_prefix=$duplication_pr2[1];




        $model_string=implode(",",$duplication_pr2_model);

        $result[0]=$model_string;
        $result['p_before_mool']=$duplication_pr2['p_before_mool'];

        return $result;
}

function get_desiderative($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_setnost,$stop,$pada,$debug)
{

		$duplication_first=duplication_first($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$debug);
        $d2=duplication_d2($duplication_first,$verb_name,$verb_type,$verb_ryad,$verb_omonim,$verb_setnost,$stop,$debug);

		$duplication_d2_model=$d2[0];

        $debug_text=$duplication_first[8].$d2['debug'];
 
        if($duplication_d2_model)
        {
            $model_string=implode(",",$duplication_d2_model);
        }

        $result[0]=$model_string;
        $result['p_before_mool']=$d2['p_before_mool'];


        if ($debug) {
            echo '<a class="btn btn-primary" data-bs-toggle="collapse" href="#collapse_des_'.$pada.'" role="button" aria-expanded="false" aria-controls="collapseExample">
            Алгоритм образования Дезидератива
            </a>
        
            </p>
            <div class="collapse" id="collapse_des_'.$pada.'">
            <div>';
            echo $debug_text;
            echo '</div></div>';
        }

        //print_r($result);

        return $result;


}

function get_intensive($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$debug)
{

    $duplication_first=duplication_first($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$debug);

    $duplication_i2=duplication_i2($duplication_first,$verb_name,$verb_type,$verb_ryad,$verb_omonim,$debug);

    //print_r($duplication_i2);

    return $duplication_i2;


}

function get_aos3($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$pada,$debug)
{
   
    $duplication_first=duplication_first($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$debug);
    $duplication_a2=duplication_a2($duplication_first,$verb_name,$verb_type,$verb_ryad,$verb_omonim,$debug);

    $duplication_a2_model=$duplication_a2['model'];

    $change_later=$duplication_a2['change_later'];

    $debug_text=$duplication_first[8]."<BR>".$duplication_a2['comment'];

    if ($debug) {
            echo '<a class="btn btn-primary" data-bs-toggle="collapse" href="#collapse_aos_'.$pada.'" role="button" aria-expanded="false" aria-controls="collapseExample">
            Алгоритм образования Аориста 3 класса
            </a>
        
            </p>
            <div class="collapse" id="collapse_aos_'.$pada.'">
            <div>';
            echo $debug_text;
            echo '</div></div>';
    }

    for($i=0;$i<count($change_later);$i++)
    {

        if($change_later[$i][0]=="E'")
        {
            $flag_e[]=1;
        }

    }

    $result_string['model']=$duplication_a2_model;
    $result_string['enew']=$duplication_a2['enew'];
    $result_string['p_before_mool']=$duplication_a2['p_before_mool'];
    $result_string['change_later']=$change_later;
    $result_string['flag_e']=$flag_e;

    //print_r($result_string);

    return $result_string;
}

/////////////////////
function get_word($prefix,$source, $mool, $postfix, $mool_number, $mool_type, $mool_change, $mool_type_change, $suffix, $suffix_ask, $suffix_transform, 
$glagol_or_imennoy, $verb_setnost, $stop, $debug, $e_manual,$flag_e) {

        //echo "E_MANUAL: $e_manual FLAG_E:".$flag_e;

        $consonants = ["k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s", "h"];
        $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "ai", "o", "au"];
        $gubnye = ["p", "ph", "b", "bh", "m"];

        //echo "SOURCE:".$source."<BR>";

        if($flag_e)
        {

            //echo "AMA HERE";
        
        if(mb_substr($mool,$e_manual,1)=="|")
        {
                $e_manual_next=$e_manual+1;
                
        }
        else
        {
            $e_manual_next=$e_manual;
        }

                //echo "MBSUBSTR_FF:".mb_substr($mool,$e_manual_next,1)."<BR>";
                if(mb_substr($mool,$e_manual_next,1)=="|")
                {
                    $e_manual_next=$e_manual_next+2;
                    $e_manual++;
                }
            // echo "MBSUBSTR_SS:".mb_substr($mool,$e_manual_next,1)."<BR>";
        }

        if($stop!=1)
        {

        switch ($mool_type) {
        
            case "1":
                //$answer[0] = "No change";
                $answer[1] = "0";
                $answer[2] = "g";
                $answer[3] = "v";
                break;
            case "2":
                //$answer[0] = "No change";
                $answer[1] = "g";
                $answer[2] = "g";
                $answer[3] = "v";
                break;
            case "3":
            // $answer[0] = "No change";
                $answer[1] = "0";
                $answer[2] = "0";
                $answer[3] = "0";
                break;
            case "4":
            // $answer[0] = "No change";
                $answer[1] = "v";
                $answer[2] = "v";
                $answer[3] = "v";
                break;
        }

        if ($debug) {
            //$debug_text .= "<BR><b>Чередование</b><BR><BR>";
            $debug_text .= "<BR><b>".$mool . $postfix . " + ($suffix_ask)" . $suffix."</b><BR><BR>";

        // $debug_text .= "<br><br>";
        }

        $is_open_mool = 0;
        if (mb_substr($mool, -1, 1) == "Ø" || mb_substr($mool, -2, 2) == "Ø̄" || mb_substr($mool, -1, 1) == $mool_change || mb_substr($mool, -2, 2) == $mool_change || mb_substr($mool, -3, 3) == $mool_change ) {
            $is_open_mool = 1;
        }

        if ($mool_number) {
            $dop = "омоним номер $mool_number, ";
        }

        if ($debug&&$glagol_or_imennoy==1) {
            $debug_text .= "Verb root '$mool' $dop $mool_type type, suffix " . $suffix . " asking for " . $suffix_ask . " morphological position. Verb root '$mool' return '" . $answer[$suffix_ask] . "' <BR>";
        }

        $word_1 = $prefix.$mool.$postfix.$suffix;

        if ($debug&&$glagol_or_imennoy==1) {

            $debug_text .= "<br>";
            $debug_text .= "Searching algorythm for " . $answer[$suffix_ask] . "<BR>";
            $debug_text .= "<br>";
            $debug_text .= "Before: $word_1 <BR>";
        }


        $dimensions = dimensions($word_1, $mool_change, $mool, 1, 0, 0, $e_manual);
        $dimensions_array = dimensions_array($dimensions);

        if ($debug&&$glagol_or_imennoy==1) {
            $debug_text .= "<BR>" . dimensions_table($dimensions);
        }

    
        $ep = mb_strpos($word_1, $mool_change);

        if ($answer[$suffix_ask] == "0") {

           


            $line_2 = "E" . find_bukvi($dimensions[1], 1, "E", 1);

            //echo "FIND: ".find_bukvi($dimensions[1], 1, "E", 1);

            if ($line_2 == "EC") {

                $line_3 = ey_or_not($word_1, $ep, $mool_change);

                //echo $line_3;

                switch ($mool_type_change) {

                    case "A1": switch ($line_3) {
                            case "Eне-y":$itog = "Ø";
                                break;
                            case "Ey":$itog = "Ø";
                                break;
                        };
                        break;

                    case "A2": switch ($line_3) {
                            case "Eне-y":
                                $before = what_is_before($word_1, $ep);
                                if ($before[1] == "C") {
                                    $table5 = "Смотрим по таблице 5  Выбор i | ī в ряду А2. $mool";
                                    if ($mool == "chØ̄" || ($mool == "dØ̄" && $mool_number == 1) || ($mool == "dØ̄" && $mool_number == 2) || ($mool == "mØ̄" && $mool_number == 1) || ($mool == "mØ̄" && $mool_number == 2) || ($mool == "mØ̄" && $mool_number == 3) || ($mool == "mØ̄" && $mool_number == 4) || ($mool == "śØ̄" && $mool_number == 1) || ($mool == "sØ̄") || ($mool == "sthØ̄") || ($mool == "śØ̄s")) {
                                        $itog = "i";
                                        $table5 .= " Корень из ячейки i <BR>";
                                        break;
                                    } elseif ($mool == "gØ̄" || ($mool == "hØ̄" && $mool_number == 2) || ($mool == "dhØ̄" && $mool_number == 2) || $mool == "pØ̄" || $mool == "sphØ̄") {  // nØ̄- (PrS9)  29 - ????
                                        $itog = "ī";
                                        $table5 .= " Корень из ячейки ī <BR>";
                                        break;
                                    } elseif (($mool == "dØ̄" && $mool_number == 3) || ($mool == "dhØ̄" && $mool_number == 1) || ($mool == "mØ̄" && $mool_number == 5) || ($mool == "lØ̄") || ($mool == "hØ̄")) {
                                        $itog = "(i&ī)";
                                        $table5 .= " Корень из ячейки i&ī <BR>";
                                        break;
                                    } else {
                                        $table5 .= " Корня в табличке нет ¯\_(ツ)_/¯ <BR>";
                                        break;
                                    }
                                } elseif ($before[1] == "V") {
                                    $itog = "Ø";
                                    break;
                                }
                            case "Ey":
                                $before = what_is_before($word_1, $ep);
                                if ($before[1] == "C") {
                                    $itog = "i";
                                    break;
                                } elseif ($before[1] == "V") {
                                    $itog = "Ø";
                                    break;
                                }
                        };
                        break;

                    case "I1": switch ($line_3) {
                            case "Eне-y":$itog = "i";
                                break;
                            case "Ey":$itog = "ī";
                                break;
                        };
                        break;

                    case "I2": switch ($line_3) {
                            case "Eне-y":$itog = "ī";
                                break;
                            case "Ey":$itog = "ī";
                                break;
                        };
                        break;

                    case "U1": switch ($line_3) {
                            case "Eне-y":$itog = "u";
                                break;
                            case "Ey":$itog = "ū";
                                break;
                        };
                        break;

                    case "U2": $itog = "ū";
                        break;

                    case "R1": switch ($line_3) {
                            case "Eне-y":$itog = "ṛ";
                                break;
                            case "Ey":$itog = "ri";
                                break;
                        };
                        break;

                    case "R2": if (!$glagol_or_imennoy) {
                            $itog = "ṝ";
                        } else {

                            $gubn_string = find_bukvi($dimensions[3], 1, "E", -1);

                            if ($gubn_string == "L") {
                                $itog = "ūr";
                                $snoska27 = "Чередующийся элемент после губной согласной.";
                            } else {
                                $itog = "īr";
                                $snoska27 = "Чередующийся элемент стоит НЕ после губной согласной";
                            }
                            break;
                        }
                        break;

                    case "L": $itog = "ḷ";
                        break;

                    case "M1": switch ($line_3) {
                            case "Ey":$itog = "am";
                                break;
                            case "Eне-y":
                                $line_3_1 = ev_or_not($word_1, $ep, $mool_change);
                                $line_3_2 = em_or_not($word_1, $ep, $mool_change);
                               
                                $text_for_m_n = "($line_3_1 , $line_3_2)";

                                if ($line_3_1 == "Ev" || $line_3_2 == "Em") {
                                    $itog = "an";
                                    break;
                                } else {
                                    $itog = "a";
                                    break;
                                }
                        };
                        break;

                    case "M2": $itog = "ām";
                        break;

                    case "N1": switch ($line_3) {
                            case "Ey":$itog = "an";
                                break;
                            case "Eне-y":
                                $line_3_1 = ev_or_not($word_1, $ep, $mool_change);
                                $line_3_2 = em_or_not($word_1, $ep, $mool_change);

                                // echo "HERE";

                                $text_for_m_n = "($line_3_1 , $line_3_2)";
                                if ($line_3_1 == "Ev" || $line_3_2 == "Em") {
                                    $itog = "an";
                                    break;
                                } else {
                                    $itog = "a";
                                    break;
                                }
                        };
                        break;

                    case "N2": $itog = "ā";
                        break;
                }
            }

        

            if ($line_2 == "EV") {

        
                    $line_3 = find_bukvi($dimensions[1], 1, "E", -1) . "E";

                    if ($line_3 == "#E" || $line_3 == "VE") {
                        
                    } else {
                        $line_3 = find_bukvi($dimensions[1], 2, "E", -1) . "E";
                    }

    
                $cons_string = find_bukvi($dimensions[1], 1, "E", -1);
                $word_string = find_bukvi($word_1, 1, $mool_change, -1);
                $line_4 = find_bukvi($dimensions[1], 2, "E", -1) . "E";

                //echo "LINE4: ".$line_4;
            
                // echo "DIMENS: ".$dimensions[0]." E_MANUAL_NEXT: $e_manual_next MBSUBSTR_HERE:".mb_substr($dimensions[0],$e_manual_next,1)."<BR>";

                if(mb_substr($dimensions[0],$e_manual_next,1)=="|")
                {
                    $e_manual_next++;
                }

                //echo "FLAG E: $flag_e $mool_type_change E_MANUAL:$e_manual NEXT:$e_manual_next ";

                switch ($mool_type_change) {

                    case "A1": $itog = "Ø";
                        break;
                    case "A2":

                        switch ($cons_string) {
                            case "C":$line_4 = "Перед Ø̄ согласная <BR>";
                                $itog = "Ø̄";
                                break;
                            case "V":
                                // echo $word_string;
                                if ($word_string == "i" || $word_string == "ī") {
                                    $line_4 = "Перед Ø̄ гласная i или ī <BR>";
                                    $mool_change = $word_string . $mool_change;
                                    $itog = "yØ̄";
                                }
                                if ($word_string == "u" || $word_string == "ū") {
                                    $line_4 = "Перед Ø̄ гласная u или ū <BR>";
                                    $mool_change = $word_string . $mool_change;
                                    $itog = "uvØ̄";
                                }
                                break;
                        };
                        break;

                    case "I1": 
                    
                        if(!$flag_e)
                        {
                            switch ($line_3) {
                                case "CCE":$itog = "iy";
                                    break;
                                case "#CE":$itog = "iy";
                                    break;
                                case "#E": $itog = "iy";
                                    break;
                                case "VCE":$itog = "y";
                                    break;
                                case "VE": $itog = "y";
                                    break;
                            };
                        }
                        else
                        {
                

                            if(mb_substr($dimensions[0],$e_manual_next,1)=="i"||mb_substr($dimensions[0],$e_manual_next,1)=="ī")
                            {
                                $itog = "i";
                            }
                            else
                            {
                                switch ($line_3) {
                                    case "CCE":$itog = "iy";
                                        break;
                                    case "#CE":$itog = "iy";
                                        break;
                                    case "#E": $itog = "iy";
                                        break;
                                    case "VCE":$itog = "y";
                                        break;
                                    case "VE": $itog = "y";
                                        break;
                                };
                            }
                        }
                        break;

                    case "I2": 
                        
                        if(!$flag_e)
                        {
                            switch ($line_3) {
                                case "CCE":$itog = "iy";
                                    break;
                                case "#CE":$itog = "iy";
                                    break;
                                case "#E": $itog = "y";
                                    break;
                                case "VCE":$itog = "y";
                                    break;
                                case "VE": $itog = "y";
                                    break;
                            };
                        }
                        else
                        {
                            if(mb_substr($dimensions[0],$e_manual_next,1)=="i"||mb_substr($dimensions[0],$e_manual_next,1)=="ī")
                            {
                                $itog = "i";
                            }
                            else
                            {
                                switch ($line_3) {
                                    case "CCE":$itog = "iy";
                                        break;
                                    case "#CE":$itog = "iy";
                                        break;
                                    case "#E": $itog = "y";
                                        break;
                                    case "VCE":$itog = "y";
                                        break;
                                    case "VE": $itog = "y";
                                        break;
                                };
                            }
                        }
                        break;

                    case "U1": 
                        if(!$flag_e)
                        {
                        
                            switch ($line_3) {
                                case "CCE":$itog = "uv";
                                    break;
                                case "#CE":$itog = "uv";
                                    break;
                                case "#E": $itog = "v";
                                    break;
                                case "VCE":$itog = "v";
                                    break;
                                case "VE": $itog = "v";
                                    break;
                            };

                        }
                        else
                        {
                            if(mb_substr($dimensions[0],$e_manual_next,1)=="u"||mb_substr($dimensions[0],$e_manual_next,1)=="ū")
                            {
                                $itog = "u";
                            }
                            else
                            {
                                switch ($line_3) {
                                    case "CCE":$itog = "uv";
                                        break;
                                    case "#CE":$itog = "uv";
                                        break;
                                    case "#E": $itog = "v";
                                        break;
                                    case "VCE":$itog = "v";
                                        break;
                                    case "VE": $itog = "v";
                                        break;
                                };
                            }
                        }

                        break;

                    case "U2":   
                    
                        if(!$flag_e)
                        {
                        
                            switch ($line_3) {
                                case "CCE":$itog = "uv";
                                    break;
                                case "#CE":$itog = "uv";
                                    break;
                                case "#E": $itog = "v";
                                    break;
                                case "VCE":$itog = "v";
                                    break;
                                case "VE": $itog = "v";
                                    break;
                            };

                        }
                        else
                        {
                            
                            if(mb_substr($dimensions[0],$e_manual_next,1)=="u"||mb_substr($dimensions[0],$e_manual_next,1)=="ū")
                            {
                                $itog = "u";
                            }
                            else
                            {
                                switch ($line_3) {
                                    case "CCE":$itog = "uv";
                                        break;
                                    case "#CE":$itog = "uv";
                                        break;
                                    case "#E": $itog = "v";
                                        break;
                                    case "VCE":$itog = "v";
                                        break;
                                    case "VE": $itog = "v";
                                        break;
                                };
                            }
                        }

                        break;

                    case "R1": switch ($line_3) {
                            case "CCE":$itog = "ar";
                                break;
                            case "#CE":

                                $gubn_string = find_bukvi($dimensions[3], 1, "E", -1);
                                if ($gubn_string == "L") {
                                    $itog = "ur";
                                    $snoska27 = "Чередующийся элемент после губной согласной.";
                                } else {
                                    $itog = "ir";
                                    $snoska27 = "Чередующийся элемент стоит НЕ после губной согласной";
                                }
                                break;

                            case "#E": $itog = "ar";
                                break;
                            case "VCE":$itog = "r";
                                break;
                            case "VE": $itog = "r";
                                break;
                        };
                        break;

                    case "R2": switch ($line_3) {
                            case "CCE":$itog = "ar";
                                break;
                            case "#CE":

                                $gubn_string = find_bukvi($dimensions[3], 1, "E", -1);
                                if ($gubn_string == "L") {
                                    $itog = "ur";
                                    $snoska27 = "Чередующийся элемент после губной согласной.";
                                } else {
                                    $itog = "ir";
                                    $snoska27 = "Чередующийся элемент стоит НЕ после губной согласной";
                                }
                                break;

                            case "#E": $itog = "r";
                                break;
                            case "VCE":$itog = "r";
                                break;
                            case "VE": $itog = "r";
                                break;
                        };
                        break;

                    case "M1": switch ($line_4) {

                            case "CCE":
                                $itog = "am";
                                break;
                            case "VCE":
                            
                                if($source=="km̥̄"||$source=="vm̥̄")
                                {
                                    $itog = "am";
                                }
                                else
                                {
                                    $itog = "m";
                                }
                                break;

                            case "#CE": 
                                $itog = "am";
                                break;
                        };
                        break;

                    case "M2": switch ($line_4) {
                   
                        case "CCE":
                            $itog = "am";
                            break;
                        case "VCE":
                            //echo $mool;
                            if($source=="km̥̄"||$source=="vm̥̄")
                            {
                                $itog = "am";
                            }
                            else
                            {
                                $itog = "m";
                            }
                            break;

                        case "#CE": 
                            $itog = "am";
                            break;

                        };
                        break;

                    case "N1": switch ($line_4) {

                            case "CCE":
                                $itog = "an";
                                break;
                            case "VCE":
                                $itog = "n";
                                break;
                            case "#CE": 
                                $itog = "an";
                                break;

                        };
                        break;

                        

                    case "N2": switch ($line_4) {

                        case "CCE":
                            $itog = "an";
                            break;
                        case "VCE":
                            $itog = "n";
                            break;
                        case "#CE": 
                            $itog = "an";
                            break;
                        };
                        break;
                }
            }

        } elseif ($answer[$suffix_ask] == "g") {
            $line_2 = "g";
            $line_3 = ey_or_not($word_1, $ep, $mool_change);

            switch ($mool_type_change) {

                case "A1": $itog = "a";
                    break;
                case "A2": $itog = "ā";
                    break;

                case "I0": switch ($line_3) {
                        case "Eне-y":$itog = "e";
                            break;
                        case "Ey":$itog = "ay";
                            break;
                    };
                    break;

                case "I1": switch ($line_3) {
                        case "Eне-y":$itog = "e";
                            break;
                        case "Ey":$itog = "ay";
                            break;
                    };
                    break;

                case "I2": switch ($line_3) {
                        case "Eне-y":$itog = "e";
                            break;
                        case "Ey":$itog = "ay";
                            break;
                    };
                    break;

                case "U0": switch ($line_3) {
                        case "Eне-y":$itog = "o";
                            break;
                        case "Ey":$itog = "av";
                            break;
                    };
                    break;

                case "U1": switch ($line_3) {
                        case "Eне-y":$itog = "o";
                            break;
                        case "Ey":$itog = "av";
                            break;
                    };
                    break;

                case "U2": switch ($line_3) {
                        case "Eне-y":$itog = "o";
                            break;
                        case "Ey":$itog = "av";
                            break;
                    };
                    break;

                case "R0": $itog = "ar";
                    break;

                case "R1": $itog = "ar";
                    break;

                case "R2": $itog = "ar";
                    break;

                case "L": $itog = "al";
                    break;

                case "M0": $itog = "am";
                    break;

                case "M1": $itog = "am";
                    break;

                case "M2": $itog = "am";
                    break;

                case "N0": $itog = "an";
                    break;

                case "N1": $itog = "an";
                    break;

                case "N2": $itog = "an";
                    break;
            }
        } elseif ($answer[$suffix_ask] == "v") {

            $line_2 = "v";
            $line_3 = ey_or_not($word_1, $ep, $mool_change);

            switch ($mool_type_change) {

                case "A1": $itog = "ā";
                    break;
                case "A2": $itog = "ā";
                    break;

                case "I0": switch ($line_3) {
                        case "Eне-y":$itog = "āi";
                            break;
                        case "Ey":$itog = "āy";
                            break;
                    };
                    break;

                case "I1": switch ($line_3) {
                        case "Eне-y":$itog = "āi";
                            break;
                        case "Ey":$itog = "āy";
                            break;
                    };
                    break;

                case "I2": switch ($line_3) {
                        case "Eне-y":$itog = "āi";
                            break;
                        case "Ey":$itog = "āy";
                            break;
                    };
                    break;

                case "U0": switch ($line_3) {
                        case "Eне-y":$itog = "āu";
                            break;
                        case "Ey":$itog = "āv";
                            break;
                    };
                    break;

                case "U1": switch ($line_3) {
                        case "Eне-y":$itog = "āu";
                            break;
                        case "Ey":$itog = "āv";
                            break;
                    };
                    break;

                case "U2": switch ($line_3) {
                        case "Eне-y":$itog = "āu";
                            break;
                        case "Ey":$itog = "āv";
                            break;
                    };
                    break;

                case "R0": $itog = "ār";
                    break;

                case "R1": $itog = "ār";
                    break;

                case "R2": $itog = "ār";
                    break;

                case "L": $itog = "āl";
                    break;

                case "M0": $itog = "ām";
                    break;

                case "M1": $itog = "ām";
                    break;

                case "M2": $itog = "ām";
                    break;

                case "N0": $itog = "ān";
                    break;

                case "N1": $itog = "ān";
                    break;

                case "N2": $itog = "ān";
                    break;
            }
        }

        if ($line_2 == "") {
            $line_2 = "<font color=red><b>Can`t alternate</b></font>";
        }


        if ($debug&&$glagol_or_imennoy==1) {
            $debug_text .= "Type for second line: $line_2";
            $debug_text .= "<BR>Type for third line: ";

            if ($line_3_1 == "Ev") {
                $debug_text .= $line_3_1;
            } elseif ($line_3_2 == "Em") {
                $debug_text .= $line_3_2;
            } else {
                $debug_text .= $line_3;
            }
            $debug_text .= "$text_for_m_n<BR>";

            $debug_text .= "Alternation series: $mool_type_change<BR>";

            if ($line_4 && ( $mool_type_change == "M1" || $mool_type_change == "M2" || $mool_type_change == "N1" || $mool_type_change == "N2" )) {
                $debug_text .= "Для рядов M и N: " . $line_4 . "<BR>";
            }

            //echo "<BR>BEFORE: ".$before[1];

            if ($before) {
                $debug_text .= "Перед чередующимся элементом: " . $before[1] . "<BR>";
            }

            //echo $line_4; // 5-я ячейка для EV  А2

            $debug_text .= $table5;
            if ($snoska27) {
                $debug_text .= $snoska27 . "<BR>";
            } //Губные или нет для рядов R2 (EC) , R1,R2 (EV)
            //echo $mool_change;
        }

        for ($i = 0; $i < strlen($mool); $i++) {
            if ($mool_change == "m̥̄" || $mool_change == "n̥̄") {
                $count_change = -1;
            } elseif ($mool_change == "Ø̄" || $mool_change == "m̥" || $mool_change == "n̥") {
                $count_change = 0;
            } else {
                $count_change = 1;
                $e_pos=1;
            }

            if($e_manual=="")
            {
                $new_word = str_replace($mool_change, $itog, $mool);
                $new_word_sandhi = str_replace($mool_change, "|" . $itog . "|", $mool);
                $new_word_sandhi2 = str_replace($mool_change, "|" . $itog . "|", $mool);

            
            }
            else
            {
                /*
                if(mb_substr($mool,$e_manual-1,1)=="|")
                {
                        $e_manual++;
                }*/
                //echo "EMANUAL: ".$e_manual."<BR><BR>";
            
                //echo "MOOL: $mool ITOG: $itog E_MANUAL $e_manual<BR>";

                //В корне нет записи с #
                //$e_manual=$e_manual-1;
                
                $new_word = mb_substr_replace($mool,$itog,$e_manual-1,mb_strlen($mool_change));


                //echo "NEW WORD:".$new_word;

                $new_word_sandhi = mb_substr_replace($mool,"|" . $itog . "|",$e_manual-1,mb_strlen($mool_change));
                $new_word_sandhi2 = mb_substr_replace($mool,"|" . $itog . "|",$e_manual-1,mb_strlen($mool_change));

            
            }

        
            
        }

        if ($debug) {
            $debug_text .= "Alternation: $mool -> $new_word";
            if($itog)
            { 
                $debug_text .= " ($mool_change меняется на $itog) <BR>";
            }
        }

        //echo "<BR>ITOG: $itog<BR>";
        //Убираем нули?
        $new_word = str_replace("Ø̄", "", $new_word);
        $new_word = str_replace("Ø", "", $new_word);
        $new_word = str_replace("|̥|", "", $new_word);
       
        $new_word = str_replace("¯", "", $new_word);


        if($suffix_transform)
        {

            if ($suffix_transform == "~" || $suffix_transform == "^") {
                
                if ($debug&&$glagol_or_imennoy==1) {
                    $debug_text .= "Series transformation: [" . $suffix_transform . "] : ";
                }

                switch ($suffix_transform) {
                    case "~":
                        switch ($itog) {
                            case "a":$itog_transform = "ā";
                                break;
                            case "i":$itog_transform = "ī";
                                break;
                            case "u":$itog_transform = "ū";
                                break;
                            case "ṛ":$itog_transform = "ṝ";
                                break;
                            case "ḷ":$itog_transform = "ḹ";
                                break;
                            case "m̥":$itog_transform = "m̥̄";
                                break;
                            case "n̥":$itog_transform = "n̥̄";
                                break;
                            default:$itog_transform = $itog;
                                break;
                        }
                        break;

                    case "^":
                        switch ($itog) {
                            case "ā":$itog_transform = "a";
                                break;
                            case "ī":$itog_transform = "i";
                                break;
                            case "ū":$itog_transform = "u";
                                break;
                            case "ṝ":$itog_transform = "ṛ";
                                break;
                            case "ḹ":$itog_transform = "ḷ";
                                break;
                            case "m̥̄":$itog_transform = "m̥";
                                break;
                            case "n̥̄":$itog_transform = "n̥";
                                break;
                            default:$itog_transform = $itog;
                                break;
                        }
                        break;

                    default: $itog_transform = $itog;
                }


                if ($debug&&$glagol_or_imennoy==1) {
                    $debug_text .= "Replace $itog by $itog_transform<BR><BR>";
                }
            }

            $have_arrow=mb_strpos($suffix_transform,"↦");

            if($have_arrow)
            {
                $find_arrow = explode("↦", $suffix_transform);

                if ($find_arrow[0] == substr($mool_type_change, 0, 1) || $find_arrow[0] == substr($mool_type_change, 0, 1) . "Ø" || $find_arrow[0] == $mool_type_change || $find_arrow[0] == $mool_type_change . "Ø") {
                    if ($debug) {
                        $debug_text .= "Series transformation: [" . $suffix_transform . "] : ";
                    }

                    $find_zero = mb_strpos($find_arrow[0], "Ø");

                    if ($debug&&$glagol_or_imennoy==1) {
                        if ($find_zero == 0) {
                            echo "трансформация только для открытых корней.";
                        }
                    }

                    if ($debug&&$glagol_or_imennoy==1) {

                        if ($is_open_mool) {
                            $debug_text .= "$mool корень открытый. ";
                        } else {
                            $debug_text .= "$mool корень закрытый, трансформация не производится. ";
                        }
                    }

                    if (!$find_zero || ($find_zero != 0 && $is_open_mool != 0)) {

                        $new_mool_type_change = $find_arrow[1];

                        switch ($new_mool_type_change) {
                            case "A1":
                                switch ($answer[$suffix_ask]) {
                                    case "0":$itog_transform = "a";
                                        break;
                                    case "g":$itog_transform = "a";
                                        break;
                                    case "v":$itog_transform = "ā";
                                        break;
                                }
                                break;

                            case "A2":
                                switch ($answer[$suffix_ask]) {
                                    case "0":$itog_transform = "ā";
                                        break;
                                    case "g":$itog_transform = "ā";
                                        break;
                                    case "v":$itog_transform = "ā";
                                        break;
                                }
                                break;

                                //$vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "āi", "o", "āu"];
                            case "I":
                                ///??????////
                                switch ($answer[$suffix_ask]) {
                                    case "0":
                                        /*
                                        if(mb_substr($find_arrow[0],1,1)==2)
                                        {
                                            $itog_transform="ī";
                                        }
                                        else
                                        {
                                            $itog_transform="i";
                                        }
                                        */
                                        $itog_transform = "e";
                                        break;
                                    case "g":$itog_transform = "e";
                                        break;
                                    case "v":$itog_transform = "ai";
                                        break;
                                }
                                break;

                            case "U":
                                ///??????////
                                switch ($answer[$suffix_ask]) {
                                    case "0":$itog_transform="o";break;
                                    case "g":$itog_transform = "o";
                                        break;
                                    case "v":$itog_transform = "au";
                                        break;
                                }
                                break;

                            case "R":
                                ///??????////
                                switch ($answer[$suffix_ask]) {
                                    case "0":$itog_transform="ar";break;
                                    case "g":$itog_transform = "ar";
                                        break;
                                    case "v":$itog_transform = "ār";
                                        break;
                                }
                                break;

                            case "L":
                                ///??????////
                                switch ($answer[$suffix_ask]) {
                                    case "0":$itog_transform="al";break;
                                    case "g":$itog_transform = "al";
                                        break;
                                    case "v":$itog_transform = "āl";
                                        break;
                                }
                                break;

                            case "M":
                                ///??????////
                                switch ($answer[$suffix_ask]) {
                                    case "0":$itog_transform="am";break;
                                    case "g":$itog_transform = "am";
                                        break;
                                    case "v":$itog_transform = "ām";
                                        break;
                                }
                                break;

                            case "N":

                                ///??????////
                                switch ($answer[$suffix_ask]) {
                                    case "0":$itog_transform="an";break;
                                    case "g":$itog_transform = "an";
                                        break;
                                    case "v":$itog_transform = "ān";
                                        break;
                                }
                                break;

                            default: $itog_transform = $itog;
                        }

                        if ($debug) {
                            $debug_text .= "Replace $itog by $itog_transform<BR>";
                        }
                    } else {
                        $itog_transform = $itog;
                    }
                } else {
                    $itog_transform = $itog;
                }

            }


            //echo "BEFORE: $new_word";

            $new_word = str_replace($itog, $itog_transform, $new_word);

            //echo "AFTER: $new_word";

            $new_word_sandhi = str_replace($itog, $itog_transform, $new_word_sandhi);
        }
        else
        {
            $itog_transform=$itog;
        }

        
    

        
        /*
        
        
        $new_word_string_sandhi = str_replace("Ø̄", "", $new_word_string_sandhi);
        $new_word_string_sandhi = str_replace("Ø", "", $new_word_string_sandhi);

        $new_word_string_sandhi = str_replace("||", "|", $new_word_string_sandhi);
        $new_word_string_sandhi = str_replace("||", "|", $new_word_string_sandhi);

        */

        $prefix = str_replace("Ø̄", "", $prefix);
        $prefix = str_replace("Ø", "", $prefix);
        $final_string = str_replace("|̥|", "", $new_word);
        $final_string = str_replace("¯", "", $new_word);
       


        if ($debug) {
            $debug_text .= "Result of alternation (without sandhi): " . $prefix.$final_string;
            $debug_text .= "<BR><BR>";
        }

    
        $new_word_string_sandhi=$prefix."|".$new_word_string_sandhi;

        $new_word_string_sandhi = str_replace("||", "|", $new_word_string_sandhi);
        $new_word_string_sandhi = str_replace("|̄|", "|", $new_word_string_sandhi);
        $new_word_string_sandhi = str_replace("|̄|", "|", $new_word_string_sandhi);
        $new_word_string_sandhi = str_replace("|̥|", "|", $new_word_string_sandhi);
        $new_word_string_sandhi = str_replace("Ø̄", "", $new_word_string_sandhi);
        
        //echo "Строчка для сандхи: ".$new_word_string_sandhi; echo "<BR><BR>";
        //echo "Строчка для сандхи2: ".$new_word_string_sandhi2; echo "<BR><BR>";

        //SANDHI HERE1
        //$sandhi = simple_sandhi($new_word_string_sandhi,$mool,"",0);
        
        $result1=$sandhi[0];
        
        }
        
    
        $result[0] = $result1;
        $result[1] = $result2;
        $result[2] = $itog_transform;
        $result[3] = $new_word_string_sandhi;
        $result[4] = $new_word_string_sandhi2;
        $result[5] = "Применили правила Эмено: ".$sandhi[1];

        if($suffix_ask!=0)
        {
            $result[9]=$prefix.$new_word;
            $result[10]=$prefix.$new_word_sandhi;
        }
        else
        {
            $result[9]=$prefix.$mool;
            $result[10]=$prefix.$mool;
        }
        
        if($suffix_ask!=0)
        {

            if ($debug) {
                echo $debug_text;
            }

        }
        else
        {
            if ($debug) {
                echo "<BR><b>".$mool . $postfix . " + ($suffix_ask)" . $suffix."</b><BR><BR>";
                echo "<b>Не чередуется</b><BR>";
            }
        }

        return $result;
    }

    function ey_or_not($word_1, $ep, $mool_change) {
        
        
        //$word_1=str_replace("|","",$word_1);
    
    if ($mool_change == "m̥̄" || $mool_change == "n̥̄") {
        $count = 3;
    } elseif ($mool_change == "m̥" || $mool_change == "n̥" || $mool_change == "Ø̄") {
        $count = 2;
    } else {
        $count = 1;
    }

    //echo "$count<BR>";

    if(mb_substr($word_1, $ep + $count, 1)=="|")
    {
        $count++;
    }
   
    //echo mb_substr($word_1, $ep + $count, 1);
    //echo "<BR>count2: $count<BR>";

    $next_bukva = mb_substr($word_1, $ep + $count, 1);

    //echo "<BR>$word_1 $ep NEXT(y):".$next_bukva."<BR><BR>";

    if ($next_bukva == "y") {
        $line_3 = "Ey";
    } else {
        $line_3 = "Eне-y";
    }

    //echo "EY$next_bukva<BR>$line_3";

    return $line_3;
}

function ev_or_not($word_1, $ep, $mool_change) {

    //$word_1=str_replace("|","",$word_1);

    if ($mool_change == "m̥̄" || $mool_change == "n̥̄") {
        $count = 3;
    } elseif ($mool_change == "m̥" || $mool_change == "n̥" || $mool_change == "Ø̄") {
        $count = 2;
    } else {
        $count = 1;
    }

    if(mb_substr($word_1, $ep + $count, 1)=="|")
    {
        $count++;
    }

    $next_bukva = mb_substr($word_1, $ep + $count, 1);

    //echo "<BR>$word_1 $ep NEXT:".$next_bukva."<BR><BR>";

    if ($next_bukva == "v") {
        $line_3 = "Ev";
    } else {
        $line_3 = "Eне-v";
    }
    //echo "EV$next_bukva<BR>$line_3";
    return $line_3;
}

function em_or_not($word_1, $ep, $mool_change) {

    //$word_1=str_replace("|","",$word_1);

    if ($mool_change == "m̥̄" || $mool_change == "n̥̄") {
        $count = 3;
    } elseif ($mool_change == "m̥" || $mool_change == "n̥" || $mool_change == "Ø̄") {
        $count = 2;
    } else {
        $count = 1;
    }

    if(mb_substr($word_1, $ep + $count, 1)=="|")
    {
        $count++;
    }

    $next_bukva = mb_substr($word_1, $ep + $count, 1);

    if ($next_bukva == "m") {
        $line_3 = "Em";
    } else {
        $line_3 = "Eне-m";
    }
    // echo "<BR>$word_1 EM $next_bukva  $line_3 <BR>";
    return $line_3;
}

function what_is_before($word_1, $ep) {

  

    $word_1=str_replace("|","",$word_1);

    $consonants = ["k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s", "h"];
    $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "āi", "o", "āu"];
    $gubnye = ["p", "ph", "b", "bh", "m"];

    $before_bukva = mb_substr($word_1, $ep - 1, 1);
    $before_2bukva = mb_substr($word_1, $ep - 2, 2);
    $before_3bukva = mb_substr($word_1, $ep - 3, 3);

   // echo "WIB:$word_1, $ep BB:  $before_bukva $before_2bukva<BR><BR>";

    $itog[0] = $before_bukva;
    $itog[1] = "";
    $itog[2] = "";

    for ($i = 0; $i < count($consonants); $i++) {
        if ($before_bukva == $consonants[$i] ) {
            $itog[1] = "C"; //Cons.
        }
    }

    for ($i = 0; $i < count($vowels); $i++) {
        if ($before_bukva == $vowels[$i] ) {
            $itog[1] = "V"; //Vow.
        }
    }

    for ($i = 0; $i < count($gubnye); $i++) {

        if ($before_bukva == $gubnye[$i] || $before_2bukva == $gubnye[$i]) {
            $itog[2] = "G"; //gubnye
        }
    }
 
    return $itog;
}

function aksharas($word_1, $mool_change, $mool, $need_e, $debug, $without_ai_au) {

    if (!$without_ai_au) {
        $all = ["|", "k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s", "h", "ṃ", "ḥ", "a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "āi", "o", "āu", "Ø̄", "Ø", "m̥̄", "m̥", "n̥", "n̥̄"];
    } else {
        $all = ["|", "k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s", "h", "ṃ", "ḥ", "a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "o", "Ø̄", "Ø", "m̥̄", "m̥", "n̥", "n̥̄"];
    }

    $special = ["|"];

    //$vowels=["a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","ai","o","au"];

    $mool_begin = mb_strpos($word_1, $mool);
    $mool_end = $mool_begin + mb_strlen($mool);

    if ($debug) {
        echo "Корень: " . $mool . " Чередуется: " . $mool_change . "<BR>";
    }


    $k = 0;
    for ($i = 0; $i < mb_strlen($word_1); $i++) {
        $bukva = mb_substr($word_1, $i, 1);

        if ($i != mb_strlen($word_1) - 1) {
            $bukva2 = mb_substr($word_1, $i, 2);
        } else {
            $bukva2 = mb_substr($word_1, $i, 2) . "#";
        }

        $bukva3 = mb_substr($word_1, $i, 3);

        if ($debug) {
            echo $bukva . " " . $bukva2 . " " . $bukva3 . " I:" . $i . " K:$k ";
        }

        if ($need_e) {

            if ($k == $mool_begin) {
                $vc[$k] = "#&";
                if ($debug) {
                    echo $vc[$k];
                }
                $k++;
            }
        }

        $cycle = seeking_2_bukva($all, $bukva3, $bukva2, $bukva, 0, $k, $i, $mool_begin, $mool_end, $mool_change, 0, $howmuche, $debug);

        $i = $cycle[2];
        $k = $cycle[1];
        $vc[$cycle[3]] = $cycle[0];

        if ($debug) {
            echo " K-end $k <BR>";
        }
    }



    $vc = array_values($vc);

    if ($debug) {
        print_r($vc);
    }

    for ($i = 0; $i < count($vc); $i++) {
        $string_vc = $string_vc . $vc[$i];
    }





    return $string_vc;
}

function cons_vow($word_1, $mool_change, $mool, $need_e, $debug, $without_ai_au) {
    
    $consonants = ["k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s", "h", "ṃ", "ḥ"];

    if (!$without_ai_au) {
        $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "āi", "o", "āu"];
    } else {
        $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "o"];
    }

    $nul = ["Ø̄", "Ø", "m̥̄", "m̥", "n̥", "n̥̄"];
    $special = ["|"];

    $mool_begin = mb_strpos($word_1, $mool);
    $mool_end = $mool_begin + mb_strlen($mool);

    if ($debug) {
        echo "Корень: " . $mool . " Чередуется: " . $mool_change . "<BR>";
    }


    $k = 0;
    $flag_E_change = 0;
    for ($i = 0; $i < mb_strlen($word_1); $i++) {
        $bukva = mb_substr($word_1, $i, 1);
        if ($i != mb_strlen($word_1) - 1) {
            $bukva2 = mb_substr($word_1, $i, 2);
        } else {
            $bukva2 = mb_substr($word_1, $i, 2) . "#";
        }

        $bukva3 = mb_substr($word_1, $i, 3);

        if ($debug) {
            echo $bukva . " " . $bukva2 . " " . $bukva3 . " I:" . $i . " K:$k ";
        }

        $flag_e = 0;

        if ($need_e) {
            if ($k == $mool_begin) {
                $vc[$k] = "#";
                if ($debug) {
                    echo $vc[$k];
                }
                $k++;
            }
        }
      
        if ($need_e) {
            
            if ($mool_change == "al" || $mool_change == "an" || $mool_change == "aṇ" || $mool_change == "añ" || $mool_change == "āñ" || $mool_change == "aṅ" || $mool_change == "āṅ" || $mool_change == "am" || $mool_change == "ar" || $mool_change == "ai" || $mool_change == "au") {
                if ($bukva2 == $mool_change && ($i >= $mool_begin && $i < $mool_end) && !$flag_e) {

                 
                    $vc[$k] = "E";
                    $vc[$k + 1] = "E";
                    if ($debug) {
                        echo $vc[$k];
                    }
                    $k = $k + 2;
                    $flag_e = 1;
                    $howmuche = 2;
                    $i++;
                }
            } else {

                if ($mool_change != "Ø̄" && $mool_change != "m̥̄" && $mool_change != "n̥̄" && $mool_change != "m̥" && $mool_change != "n̥" && $mool_change != "n̥") {
                    if ($bukva == $mool_change && ($i >= $mool_begin && $i < $mool_end) && !$flag_e) {
                        $vc[$k] = "E";
                        if ($debug) {
                            echo $vc[$k];
                        }
                        $k++;
                        $flag_e = 1;
                    }
                } elseif ($mool_change != "m̥̄" && $mool_change != "n̥̄") {
                    if ($bukva2 == $mool_change && ($i >= $mool_begin && $i < $mool_end) && !$flag_e) {
                        $vc[$k] = "E";
                        if ($debug) {
                            echo $vc[$k];
                        }
                        $k++;
                        $flag_e = 1;
                    }
                } else {

                    if ($bukva3 == $mool_change && ($i >= $mool_begin && $i < $mool_end) && !$flag_e) {
                        $vc[$k] = "E";
                        if ($debug) {
                            echo $vc[$k];
                        }
                        $k++;
                        $flag_e = 1;
                    }
                }
            }
        }

        if ($debug) {
            echo "Mool_end: $mool_end   Flag_e: $flag_e ";
        }
        if (!$flag_e) {

            $cycle = seeking_2_bukva($consonants, $bukva3, $bukva2, $bukva, "C", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            $cycle = seeking_2_bukva($vowels, $bukva3, $bukva2, $bukva, "V", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            $cycle = seeking_2_bukva($nul, $bukva3, $bukva2, $bukva, "-", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            $cycle = seeking_2_bukva($special, $bukva3, $bukva2, $bukva, "|", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];
        }

        if ($debug) {
            echo " K-end $k <BR>";
        }
    }



    $vc = array_values($vc);

    if ($debug) {
        print_r($vc);
    }

    for ($i = 0; $i < count($vc); $i++) {
        $string_vc = $string_vc . $vc[$i];
    }





    return $string_vc;
}

function shum_sibil($word_1, $mool_change, $mool, $need_e, $debug, $without_ai_au) {

    $consonants = ["k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "ś", "ṣ", "s", "h"];

    if (!$without_ai_au) {
        $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "āi", "o", "āu"];
    } else {
        $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "o"];
    }

    $semivowels = ["y", "r", "l", "v"];
    $nul = ["Ø̄", "Ø"];
    $special = ["|"];

    $gubnye = ["p", "ph", "b", "bh", "m", "v"];
    $sibils = ["ś", "ṣ", "s"];
    $nosovye = ["ṅ", "ñ", "ṇ", "n", "m"];
    $shumnye = ["k", "kh", "g", "gh", "c", "ch", "j", "jh", "ṭ", "ṭh", "ḍ", "ḍh", "t", "th", "d", "dh", "p", "ph", "b", "bh"];
    $anusvara = ["ṃ"];
    $visarga = ["ḥ"];
    $pridyhanye = ["h"];

    $mool_begin = mb_strpos($word_1, $mool);
    $mool_end = $mool_begin + mb_strlen($mool);
    $vc = [];
    $k = 0;

    if ($debug) {
        echo "<BR>";
    }

    for ($i = 0; $i < mb_strlen($word_1); $i++) {
        $bukva = mb_substr($word_1, $i, 1);

        if ($i != mb_strlen($word_1) - 1) {
            $bukva2 = mb_substr($word_1, $i, 2);
        } else {
            $bukva2 = mb_substr($word_1, $i, 2) . "#";
        }

        $bukva3 = mb_substr($word_1, $i, 3);

        if ($debug) {
            echo $bukva . " " . $bukva2 . " I:" . $i . " K:$k ";
        }
        $flag_e = 0;

        if ($need_e) {


            if ($k == $mool_begin) {
                $vc[$k] = "#";
                if ($debug) {
                    echo $vc[$k];
                }
                $k++;
            }

            if ($mool_change == "al" || $mool_change == "an" || $mool_change == "aṇ" || $mool_change == "añ" || $mool_change == "āñ" || $mool_change == "aṅ" || $mool_change == "āṅ" || $mool_change == "am" || $mool_change == "ar" || $mool_change == "ai" || $mool_change == "au") {
                if ($bukva2 == $mool_change && ($i >= $mool_begin && $i < $mool_end) && !$flag_e) {
                    $vc[$k] = "E";
                    $vc[$k + 1] = "E";
                    if ($debug) {
                        echo $vc[$k];
                    }
                    $k = $k + 2;
                    $flag_e = 1;
                    $howmuche = 2;
                    $i++;
                }
            } else {



                if ($mool_change != "Ø̄" && $mool_change != "m̥̄" && $mool_change != "n̥̄" && $mool_change != "m̥" && $mool_change != "n̥") {
                    if ($bukva == $mool_change && ($i >= $mool_begin && $i < $mool_end) && !$flag_e) {
                        $vc[$k] = "E";
                        if ($debug) {
                            echo $vc[$k];
                        }
                        $k++;
                        $flag_e = 1;
                    }
                } elseif ($mool_change != "m̥̄" && $mool_change != "n̥̄") {
                    if ($bukva2 == $mool_change && ($i >= $mool_begin && $i < $mool_end) && !$flag_e) {
                        $vc[$k] = "E";
                        if ($debug) {
                            echo $vc[$k];
                        }
                        $k++;
                        $flag_e = 1;
                    }
                } else {

                    if ($bukva3 == $mool_change && ($i >= $mool_begin && $i < $mool_end) && !$flag_e) {
                        $vc[$k] = "E";
                        if ($debug) {
                            echo $vc[$k];
                        }
                        $k++;
                        $flag_e = 1;
                    }
                }
            }
        }

        if ($debug) {
            echo " Flag_e $flag_e ";
        }
        if (!$flag_e) {

            $cycle = seeking_2_bukva($shumnye, $bukva3, $bukva2, $bukva, "T", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            // if($debug){echo " K-T: $k ";}

            $cycle = seeking_2_bukva($sibils, $bukva3, $bukva2, $bukva, "S", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            //  if($debug){echo " K-S: $k ";}

            $cycle = seeking_2_bukva($vowels, $bukva3, $bukva2, $bukva, "V", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            //  if($debug){echo " K-V: $k ";}

            $cycle = seeking_2_bukva($semivowels, $bukva3, $bukva2, $bukva, "v", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            //   if($debug){echo " K-v: $k ";}

            $cycle = seeking_2_bukva($nosovye, $bukva3, $bukva2, $bukva, "N", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            //   if($debug){echo " K-n: $k ";}

            $cycle = seeking_2_bukva($anusvara, $bukva3, $bukva2, $bukva, "a", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            $cycle = seeking_2_bukva($visarga, $bukva3, $bukva2, $bukva, "i", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            $cycle = seeking_2_bukva($pridyhanye, $bukva3, $bukva2, $bukva, "h", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            $cycle = seeking_2_bukva($nul, $bukva3, $bukva2, $bukva, "-", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            $cycle = seeking_2_bukva($special, $bukva3, $bukva2, $bukva, "|", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];
        }

        if ($debug) {
            echo " Kend: $k <BR>";
        }
    }

    if ($debug) {
        echo " K end after cycle: $k <BR>";
    }

    if ($debug) {
        print_r($vc);
    }

    for ($i = 0; $i < count($vc); $i++) {
        $string_vc = $string_vc . $vc[$i];
    }

    return $string_vc;
}

function gubnye($word_1, $mool_change, $mool, $need_e, $debug, $without_ai_au) {

    $consonants = ["k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s", "h"];

    $special = ["|"];

    /*
    if (!$without_ai_au) {
        $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "āi", "o", "āu"];
    } else {
        $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "o"];
    }
*/

    if (!$without_ai_au) {
        $gubnye = ["p", "ph", "b", "bh", "m", "v", "u", "ū", "o", "āu"];
    } else {
        $gubnye = ["p", "ph", "b", "bh", "m", "v", "u", "ū", "o"];
    }

    $velum = ["a", "ā", "k", "kh", "g", "gh", "ṅ"];

    if (!$without_ai_au) {
        $palatum = ["āi","i", "ī", "e", "c", "ch", "j", "jh", "ñ", "y", "ś"];
    } else {
        $palatum = ["i", "ī", "e", "c", "ch", "j", "jh", "ñ", "y", "ś"];
    }

   

    $retro = ["ṛ", "ṝ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "r", "ṣ"];
    $dental = ["ḷ", "t", "th", "d", "dh", "n", "l", "s"];
    $others = ["ṃ", "ḥ", "h"];

    $nul = ["Ø̄", "Ø"];

    $mool_begin = mb_strpos($word_1, $mool);
    $mool_end = $mool_begin + mb_strlen($mool);

    if ($debug) {
        echo "<BR>";
    }

    $k = 0;
    for ($i = 0; $i < mb_strlen($word_1) + 1; $i++) {
        $bukva = mb_substr($word_1, $i, 1);
        if ($i != mb_strlen($word_1) - 1) {
            $bukva2 = mb_substr($word_1, $i, 2);
        } else {
            $bukva2 = mb_substr($word_1, $i, 2) . "#";
        }

        if ($i != mb_strlen($word_1) - 2) {
            $bukva3 = mb_substr($word_1, $i, 3);
        } else {
            $bukva3 = mb_substr($word_1, $i, 3) . "#";
        }


        if ($debug) {
            echo $bukva . " " . $bukva2 . " " . $bukva3 . " I:" . $i . " K:$k ";
        }
        $flag_e = 0;

        if ($need_e) {
            if ($k == $mool_begin) {
                $vc[$k] = "#";
                if ($debug) {
                    echo $vc[$k];
                }
                $k++;
            }
            if ($mool_change == "al" || $mool_change == "an" || $mool_change == "aṇ" || $mool_change == "añ" || $mool_change == "āñ" || $mool_change == "aṅ" || $mool_change == "āṅ" || $mool_change == "am" || $mool_change == "ar" || $mool_change == "āi" || $mool_change == "āu" || $mool_change == "au" || $mool_change == "ai") {
                if ($bukva2 == $mool_change && ($i >= $mool_begin && $i < $mool_end) && !$flag_e) {
                    $vc[$k] = "E";
                    $vc[$k + 1] = "E";
                    if ($debug) {
                        echo $vc[$k];
                    }
                    $k = $k + 2;
                    $flag_e = 1;
                    $howmuche = 2;
                    $i++;
                }
            } else {

                if ($mool_change != "Ø̄" && $mool_change != "m̥̄" && $mool_change != "n̥̄" && $mool_change != "m̥" && $mool_change != "n̥" && $mool_change != "āi" && $mool_change != "āu") {
                    if ($bukva == $mool_change && ($i >= $mool_begin && $i < $mool_end) && !$flag_e) {
                        $vc[$k] = "E";
                        if ($debug) {
                            echo $vc[$k];
                        }
                        $k++;
                        $flag_e = 1;
                    }
                } elseif ($mool_change != "m̥̄" && $mool_change != "n̥̄") {
                    if ($bukva2 == $mool_change && ($i >= $mool_begin && $i < $mool_end) && !$flag_e) {
                        $vc[$k] = "E";
                        if ($debug) {
                            echo $vc[$k];
                        }
                        $k++;
                        $flag_e = 1;
                    }
                } else {

                    if ($bukva3 == $mool_change && ($i >= $mool_begin && $i < $mool_end) && !$flag_e) {
                        $vc[$k] = "E";
                        if ($debug) {
                            echo $vc[$k];
                        }
                        $k++;
                        $flag_e = 1;
                    }
                }
            }
        }

        if (!$flag_e) {
            $cycle = seeking_2_bukva($gubnye, $bukva3, $bukva2, $bukva, "L", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            $cycle = seeking_2_bukva($velum, $bukva3, $bukva2, $bukva, "V", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            $cycle = seeking_2_bukva($palatum, $bukva3, $bukva2, $bukva, "P", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            $cycle = seeking_2_bukva($retro, $bukva3, $bukva2, $bukva, "R", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            $cycle = seeking_2_bukva($dental, $bukva3, $bukva2, $bukva, "D", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            $cycle = seeking_2_bukva($others, $bukva3, $bukva2, $bukva, "O", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            $cycle = seeking_2_bukva($nul, $bukva3, $bukva2, $bukva, "-", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            $cycle = seeking_2_bukva($special, $bukva3, $bukva2, $bukva, "|", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];
        }

        if ($debug) {
            echo " K-end $k <BR>";
        }
    }

    if ($debug) {
        print_r($vc);
    }

    for ($i = 0; $i < count($vc); $i++) {
        $string_vc = $string_vc . $vc[$i];
    }

    return $string_vc;
}

function voice_deaf($word_1, $mool_change, $mool, $need_e, $debug, $without_ai_au) {

    $consonants = ["k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s", "h"];
    $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "āi", "o", "āu"];

    $gubnye = ["p", "ph", "b", "bh", "m", "v", "u", "ū", "o", "au"];
    $velum = ["a", "ā", "k", "kh", "g", "gh", "ṅ"];
    $palatum = ["i", "ī", "e", "ai", "c", "ch", "j", "jh", "ñ", "y", "ś"];
    $retro = ["ṛ", "ṝ", "e", "ai", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "r", "ṣ"];
    $dental = ["ḷ", "t", "th", "d", "dh", "n", "l", "s"];
    $others = ["ṃ", "ḥ", "h"];
    $special = ["|"];

    $nul = ["Ø̄", "Ø"];

    if (!$without_ai_au) {

        $voicedness = ["ṃ", "h", "a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "āi", "o", "āu", "g", "gh", "ṅ", "j", "jh", "ñ", "ḍ", "ḍh", "ṇ", "d", "dh", "n", "b", "bh", "m", "y", "r", "l", "v"];
    } else {

        $voicedness = ["ṃ", "h", "a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "o", "g", "gh", "ṅ", "j", "jh", "ñ", "ḍ", "ḍh", "ṇ", "d", "dh", "n", "b", "bh", "m", "y", "r", "l", "v"];
    }

    $deafness = ["ḥ", "k", "kh", "c", "ch", "ṭ", "ṭh", "t", "th", "p", "ph", "ś", "ṣ", "s"];

    $mool_begin = mb_strpos($word_1, $mool);
    $mool_end = $mool_begin + mb_strlen($mool);

    if ($debug) {
        echo "<BR>";
    }

    $k = 0;
    for ($i = 0; $i < mb_strlen($word_1) + 1; $i++) {
        $bukva = mb_substr($word_1, $i, 1);
        if ($i != mb_strlen($word_1) - 1) {
            $bukva2 = mb_substr($word_1, $i, 2);
        } else {
            $bukva2 = mb_substr($word_1, $i, 2) . "#";
        }
        $bukva3 = mb_substr($word_1, $i, 3);

        if ($debug) {
            echo $bukva . " " . $bukva2 . " I:" . $i . " K:$k ";
        }

        $flag_e = 0;

        if ($need_e) {

            if ($k == $mool_begin) {
                $vc[$k] = "#";
                if ($debug) {
                    echo $vc[$k];
                }
                $k++;
            }

            if ($mool_change == "al" || $mool_change == "an" || $mool_change == "aṇ" || $mool_change == "añ" || $mool_change == "āñ" || $mool_change == "aṅ" || $mool_change == "āṅ" || $mool_change == "am" || $mool_change == "ar" || $mool_change == "ai" || $mool_change == "au") {
                if ($bukva2 == $mool_change && ($i >= $mool_begin && $i < $mool_end) && !$flag_e) {
                    $vc[$k] = "E";
                    $vc[$k + 1] = "E";
                    if ($debug) {
                        echo $vc[$k];
                    }
                    $k = $k + 2;
                    $flag_e = 1;
                    $howmuche = 2;
                    $i++;
                }
            } else {

                if ($mool_change != "Ø̄" && $mool_change != "m̥̄" && $mool_change != "n̥̄" && $mool_change != "m̥" && $mool_change != "n̥") {
                    if ($bukva == $mool_change && ($i >= $mool_begin && $i < $mool_end) && !$flag_e) {
                        $vc[$k] = "E";
                        if ($debug) {
                            echo $vc[$k];
                        }
                        $k++;
                        $flag_e = 1;
                    }
                } elseif ($mool_change != "m̥̄" && $mool_change != "n̥̄") {
                    if ($bukva2 == $mool_change && ($i >= $mool_begin && $i < $mool_end) && !$flag_e) {
                        $vc[$k] = "E";
                        if ($debug) {
                            echo $vc[$k];
                        }
                        $k++;
                        $flag_e = 1;
                    }
                } else {

                    if ($bukva3 == $mool_change && ($i >= $mool_begin && $i < $mool_end) && !$flag_e) {
                        $vc[$k] = "E";
                        if ($debug) {
                            echo $vc[$k];
                        }
                        $k++;
                        $flag_e = 1;
                    }
                }
            }
        }

        if (!$flag_e) {
            $cycle = seeking_2_bukva($voicedness, $bukva3, $bukva2, $bukva, "V", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            $cycle = seeking_2_bukva($deafness, $bukva3, $bukva2, $bukva, "D", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            $cycle = seeking_2_bukva($nul, $bukva3, $bukva2, $bukva, "-", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];

            $cycle = seeking_2_bukva($special, $bukva3, $bukva2, $bukva, "|", $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug);

            $i = $cycle[2];
            $k = $cycle[1];
            $vc[$cycle[3]] = $cycle[0];
        }

        if ($debug) {
            echo " K-end $k <BR>";
        }
    }

    if ($debug) {
        print_r($vc);
    }

    for ($i = 0; $i < count($vc); $i++) {
        $string_vc = $string_vc . $vc[$i];
    }

    return $string_vc;
}

function find_bukvi($string, $howmany, $what, $napravlenie) {
   
    $string=str_replace("|","",$string);
   
    $e_begin = mb_strpos($string, $what);

    if ($napravlenie == 1) {
        $find = mb_substr($string, $e_begin + 1, $howmany);
    }

    if ($napravlenie == -1) {
        if ($howmany > $e_begin) {
            $howmany = $e_begin;
        }
        $find = mb_substr($string, $e_begin - $howmany, $howmany);
    }

    return $find;
}

function seeking_2_bukva($massive, $bukva3, $bukva2, $bukva, $label, $k, $i, $mool_begin, $mool_end, $mool_change, $need_e, $howmuche, $debug) {

        if ($howmuche == 2) {
            $k++;
        }

        $flag_find_2 = 0;
        $flag_find_3 = 0;
        $last_k = $k;

        // if($debug){echo " Inside Function:  last K: ". $last_k;}

        if ($bukva2 && $bukva && $bukva3) {

            for ($j = 0; $j < count($massive); $j++) {

                if ($bukva3 == $massive[$j]) {
                    if (($i <= $mool_begin || $i >= $mool_end) || $label == "0") {
                        if ($label != "0") {
                            $vc[$k] = $label;
                        } else {
                            $vc[$k] = $bukva3 . "&";
                        }

                        if ($debug) {
                            echo "three: $bukva3 " . $vc[$k];
                        }
                        $flag_find_3 = 1;
                        $k++;
                        $i++;
                    } else {

                        if ($bukva3 != $mool_change || $need_e == 0) {
                            if ($label != "0") {
                                $vc[$k] = $label;
                            } else {
                                $vc[$k] = $bukva3 . "&";
                            }
                            if ($debug) {
                                echo "three2: " . $vc[$k];
                            }
                            $k++;
                            $i++;
                        }
                    }
                }
            }

            if (!$flag_find_3) {

                for ($j = 0; $j < count($massive); $j++) {

                    if ($bukva2 == $massive[$j]) {
                        if (($i <= $mool_begin || $i >= $mool_end) || $label == "0") {
                            if ($label != "0") {
                                $vc[$k] = $label;
                            } else {
                                $vc[$k] = $bukva2 . "&";
                            }

                            if ($debug) {
                                echo "two: $bukva2 " . $vc[$k];
                            }
                            $flag_find_2 = 1;
                            $k++;
                            $i++;
                        } else {

                            if ($bukva2 != $mool_change || $need_e == 0) {
                                if ($label != "0") {
                                    $vc[$k] = $label;
                                } else {
                                    $vc[$k] = $bukva2 . "&";
                                }
                                if ($debug) {
                                    echo "two2: " . $vc[$k];
                                }
                                $k++;
                                $i++;
                            }
                        }
                    }
                }

                //  if($debug){echo "<BR> Перед проверкой на 1 букву j:$j Flag2: ".$flag_find_2." Flag3: ".$flag_find_3."<BR>";}

                if (!$flag_find_2) {
                    for ($j = 0; $j < count($massive); $j++) {

                        if ($bukva == $massive[$j]) {
                            if (($i <= $mool_begin || $i >= $mool_end) || $label == "0") {

                                if ($label != "0") {
                                    $vc[$k] = $label;
                                } else {
                                    $vc[$k] = $bukva . "&";
                                }
                                if ($debug) {
                                    echo "one: " . $vc[$k];
                                }
                                $k++;
                                $i = $i;
                            } else {

                                if ($bukva != $mool_change || $need_e == 0) {
                                    if ($label != "0") {
                                        $vc[$k] = $label;
                                    } else {
                                        $vc[$k] = $bukva . "&";
                                    }
                                    if ($debug) {
                                        echo "one2: " . $vc[$k];
                                    }
                                    $k++;
                                    $i = $i;
                                }
                            }
                        }
                    }
                }
            }
        }



        if ($k > $last_k) {
            if ($label != "0") {
                $itog[0] = $label;
            } else {
                $itog[0] = $vc[$last_k];
            }
        }

        $itog[1] = $k;
        $itog[2] = $i;
        $itog[3] = $last_k;

        return $itog;
    }

    function get_e_mp_simple($mool_type_change, $mool_type, $mp) {
        switch ($mool_type) {
            case "1":$answer[1] = "0";
                $answer[2] = "g";
                $answer[3] = "v";
                break;
            case "2":$answer[1] = "g";
                $answer[2] = "g";
                $answer[3] = "v";
                break;
            case "3":$answer[1] = "0";
                $answer[2] = "0";
                $answer[3] = "0";
                break;
            case "4":$answer[1] = "v";
                $answer[2] = "v";
                $answer[3] = "v";
                break;
        }


        switch ($mool_type_change) {
            case "A1":
                switch ($answer[$mp]) {
                    case "0":$itog_transform = "Ø";
                        break;
                    case "g":$itog_transform = "a";
                        break;
                    case "v":$itog_transform = "ā";
                        break;
                }
                break;

            case "A2":
                switch ($answer[$mp]) {
                    case "0":$itog_transform = "Ø̄";
                        break;
                    case "g":$itog_transform = "ā";
                        break;
                    case "v":$itog_transform = "ā";
                        break;
                }
                break;

            case "I0":

                switch ($answer[$mp]) {
                    case "0":$itog_transform = "i";
                        break;
                    case "g":$itog_transform = "e";
                        break;
                    case "v":$itog_transform = "ai";
                        break;
                }
                break;

            case "I1":

                switch ($answer[$mp]) {
                    case "0":$itog_transform = "i";
                        break;
                    case "g":$itog_transform = "e";
                        break;
                    case "v":$itog_transform = "ai";
                        break;
                }
                break;

            case "I2":

                switch ($answer[$mp]) {
                    case "0":$itog_transform = "ī";
                        break;
                    case "g":$itog_transform = "e";
                        break;
                    case "v":$itog_transform = "ai";
                        break;
                }
                break;

            case "U":

                switch ($answer[$mp]) {
                    case "0":$itog_transform = "u";
                        break;
                    case "g":$itog_transform = "o";
                        break;
                    case "v":$itog_transform = "au";
                        break;
                }
                break;

            case "U1":

                switch ($answer[$mp]) {
                    case "0":$itog_transform = "u";
                        break;
                    case "g":$itog_transform = "o";
                        break;
                    case "v":$itog_transform = "au";
                        break;
                }
                break;

            case "U2":

                switch ($answer[$mp]) {
                    case "0":$itog_transform = "ū";
                        break;
                    case "g":$itog_transform = "o";
                        break;
                    case "v":$itog_transform = "au";
                        break;
                }
                break;

            case "R0":

                switch ($answer[$mp]) {
                    case "0":$itog_transform = "ṛ";
                        break;
                    case "g":$itog_transform = "ar";
                        break;
                    case "v":$itog_transform = "ār";
                        break;
                }
                break;

            case "R1":

                switch ($answer[$mp]) {
                    case "0":$itog_transform = "ṛ";
                        break;
                    case "g":$itog_transform = "ar";
                        break;
                    case "v":$itog_transform = "ār";
                        break;
                }
                break;

            case "R2":

                switch ($answer[$mp]) {
                    case "0":$itog_transform = "ṝ";
                        break;
                    case "g":$itog_transform = "ar";
                        break;
                    case "v":$itog_transform = "ār";
                        break;
                }
                break;

            case "L":

                switch ($answer[$mp]) {
                    case "0":$itog_transform = "ḷ";
                        break;
                    case "g":$itog_transform = "al";
                        break;
                    case "v":$itog_transform = "āl";
                        break;
                }
                break;

            case "M0":

                switch ($answer[$mp]) {
                    case "0":$itog_transform = "m̥";
                        break;
                    case "g":$itog_transform = "am";
                        break;
                    case "v":$itog_transform = "ām";
                        break;
                }
                break;

            case "M1":

                switch ($answer[$mp]) {
                    case "0":$itog_transform = "m̥";
                        break;
                    case "g":$itog_transform = "am";
                        break;
                    case "v":$itog_transform = "ām";
                        break;
                }
                break;

            case "M2":

                switch ($answer[$mp]) {
                    case "0":$itog_transform = "m̥̄";
                        break;
                    case "g":$itog_transform = "am";
                        break;
                    case "v":$itog_transform = "ām";
                        break;
                }
                break;

            case "N0":

                switch ($answer[$mp]) {
                    case "0":$itog_transform = "Ø";
                        break;
                    case "g":$itog_transform = "an";
                        break;
                    case "v":$itog_transform = "ān";
                        break;
                }
                break;

            case "N1":

                switch ($answer[$mp]) {
                    case "0":$itog_transform = "n̥";
                        break;
                    case "g":$itog_transform = "an";
                        break;
                    case "v":$itog_transform = "ān";
                        break;
                }
                break;

            case "N1":

                switch ($answer[$mp]) {
                    case "0":$itog_transform = "n̥̄";
                        break;
                    case "g":$itog_transform = "an";
                        break;
                    case "v":$itog_transform = "ān";
                        break;
                }
                break;
        }

        return $itog_transform;
}

function get_e_mp_table4($prefix,$word,$postfix,$verb_omonim,$verb_type,$letter_e,$verb_ryad,$mp,$number_of_e)
{

    $result=get_word($prefix,'',$word,$postfix,$verb_omonim,$verb_type,$letter_e,$verb_ryad,'',$mp,'',"1",0,0,0,$number_of_e,0)[2];
    return $result;
}

function seeking_1_bukva($bukva, $without_ai_au) {

    $consonants = ["k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s", "h", "ṃ", "ḥ"];
    $pridyhatelnye = ["kh", "gh", "ch", "jh","ṭh", "ḍh", "th", "dh", "ph", "bh"];

    if (!$without_ai_au) {
        $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "āi", "o", "āu"];
    } else {
        $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "o"];
    }

    $semivowels = ["y", "r", "l", "v"];
    $nul = ["Ø̄", "Ø"];
    $special = ["|"];

    $gubnye = ["p", "ph", "b", "bh", "m", "v", "u", "o", "ū", "āu"];

    $velum = ["a", "ā", "k", "kh", "g", "gh", "ṅ"];

    if (!$without_ai_au) {
        $palatum = ["i", "ī", "e", "āi", "c", "ch", "j", "jh", "ñ", "y", "ś"];
    } else {
        $palatum = ["i", "ī", "e", "c", "ch", "j", "jh", "ñ", "y", "ś"];
    }

    $retro = ["ṛ", "ṝ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "r", "ṣ"];
    $dental = ["ḷ", "t", "th", "d", "dh", "n", "l", "s"];
    $others = ["ṃ", "ḥ", "h"];

    $nul = ["Ø̄", "Ø"];


    $sibils = ["ś", "ṣ", "s"];
    $nosovye = ["ṅ", "ñ", "ṇ", "n", "m"];
    $shumnye = ["k", "kh", "g", "gh", "c", "ch", "j", "jh", "ṭ", "ṭh", "ḍ", "ḍh", "t", "th", "d", "dh", "p", "ph", "b", "bh"];
    $anusvara = ["ṃ"];
    $visarga = ["ḥ"];

    $pridyhanye = ["h"];

    for ($i = 0; $i < count($consonants); $i++) {
        if ($bukva == $consonants[$i]) {
            $cons = "C";
        }
    }

    for ($i = 0; $i < count($vowels); $i++) {
        if ($bukva == $vowels[$i]) {
            $cons = "V";
        }
    }

    
    for ($i = 0; $i < count($gubnye); $i++) {
        if ($bukva == $gubnye[$i]) {
            $where = "L";
        }
    }

    for ($i = 0; $i < count($velum); $i++) {
        if ($bukva == $velum[$i]) {
            $where = "V";
        }
    }

    for ($i = 0; $i < count($palatum); $i++) {
        if ($bukva == $palatum[$i]) {
            $where = "P";
        }
    }

    for ($i = 0; $i < count($retro); $i++) {
        if ($bukva == $retro[$i]) {
            $where = "R";
        }
    }

    for ($i = 0; $i < count($dental); $i++) {
        if ($bukva == $dental[$i]) {
            $where = "D";
        }
    }
    

    for ($i = 0; $i < count($sibils); $i++) {
        if ($bukva == $sibils[$i]) {
            $vzryv = "S";
        }
    }

    for ($i = 0; $i < count($pridyhatelnye); $i++) {
        if ($bukva == $pridyhatelnye[$i]) {
            $prid = "H";
        }
    }

    $result[0] = $bukva;
    $result[1] = $cons;
    $result[2] = $vzryv;
    $result[3] = $prid;
    $result[4] = $where;

    return $result;
}

function mb_substr_replace($original, $replacement, $position, $length)
{
    $startString = mb_substr($original, 0, $position, "UTF-8");
    $endString = mb_substr($original, $position + $length, mb_strlen($original), "UTF-8");
    $out = $startString . $replacement . $endString;
    return $out;
}

function dimensions($word_1, $mool_change, $mool, $need_e, $debug, $without_ai_au, $e_manual) {

    //echo "Debug:".$word_1."<BR>";
    //echo "Without:".$without_ai_au."<BR>";
    //$word_1=str_replace("|","",$word_1);

    $string_vc = cons_vow($word_1, $mool_change, $mool, $need_e, $debug, $without_ai_au);

    if($e_manual!="")
    {
        $string_vc = "#".cons_vow($word_1, $mool_change, $mool, 0, $debug, $without_ai_au);
        
        $string_vc = mb_substr_replace($string_vc,"E",$e_manual,mb_strlen($mool_change));
        //$string_vc = str_replace($string_vc,"E",$e_manual,mb_strlen($mool_change));
    }
    else
    {
        $string_vc = cons_vow($word_1, $mool_change, $mool, $need_e, $debug, $without_ai_au);
    }

    //echo "<BR>Последовательность гласные-согласные: ";
    $new_vc = str_replace("E", "<b>E</b>", $string_vc);

   

    if($e_manual!="")
    {
        $string_ss = "#".shum_sibil($word_1, $mool_change, $mool, 0, $debug, $without_ai_au);
        $string_ss = mb_substr_replace($string_ss,"E",$e_manual,mb_strlen($mool_change));
    }
    else
    {
        $string_ss = shum_sibil($word_1, $mool_change, $mool, $need_e, $debug, $without_ai_au);
    }

    $new_ss = str_replace("E", "<b>E</b>", $string_ss);

   

    if($e_manual!="")
    {
        $string_gub = "#".gubnye($word_1, $mool_change, $mool, 0, $debug, $without_ai_au);
        $string_gub = mb_substr_replace($string_gub,"E",$e_manual,mb_strlen($mool_change));
    }
    else
    {
        $string_gub = gubnye($word_1, $mool_change, $mool, $need_e, $debug, 0);
    }

    $new_gub = str_replace("E", "<b>E</b>", $string_gub);

   

    if($e_manual!="")
    {
        $string_vd = "#".voice_deaf($word_1, $mool_change, $mool, 0, $debug, $without_ai_au);
        $string_vd = mb_substr_replace($string_vd,"E",$e_manual,mb_strlen($mool_change));
    }
    else
    {
        $string_vd = voice_deaf($word_1, $mool_change, $mool, $need_e, $debug, $without_ai_au);
    }

    $new_vd = str_replace("E", "<b>E</b>", $string_vd);

    $string_all = aksharas($word_1, $mool_change, $mool, $need_e, $debug, $without_ai_au);
    $string_all_array = explode("&", $string_all);
  
    $k = 0;
    for ($i = 0; $i < count($string_all_array); $i++) {
        if ($string_all_array[$i] != "") {
            $new_massive[$k] = $string_all_array[$i];
            $k++;
        }
    }

    $itog[0] = $word_1;
    $itog[1] = $string_vc;
    $itog[2] = $string_ss;
    $itog[3] = $string_gub;
    $itog[4] = $string_vd;
    $itog[5] = $string_all;
    $itog[6] = $new_massive;
  
    return $itog;
}

function dimensions_table($dimensions) {

        $begin = '<table width="10%" class="table table-bordered table-fit"><thead><tr>';

        for ($i = 0; $i < count($dimensions[6]); $i++) {
            if ($dimensions[6][$i]) {
                $head_table .= '<th scope="col">' . $dimensions[6][$i] . '</th>';
            }
        }

        $end_head_table = '</tr><thead>';

        $row1 = "<tr>";

        //$dimensions[1]=str_replace("E","<b>E</b>",$dimensions[1]);
        for ($i = 0; $i < mb_strlen($dimensions[1]); $i++) {
            if (mb_substr($dimensions[1], $i, 1) == "E") {
                $row1 .= '<td><b>' . mb_substr($dimensions[1], $i, 1) . '</b></td>';
            } else {
                $row1 .= '<td>' . mb_substr($dimensions[1], $i, 1) . '</td>';
            }
        }

        $row1 .= "</tr>";

        $row2 = "<tr>";

        for ($i = 0; $i < mb_strlen($dimensions[2]); $i++) {
            if (mb_substr($dimensions[2], $i, 1) == "E") {
                $row2 .= '<td><b>' . mb_substr($dimensions[2], $i, 1) . '</b></td>';
            } else {
                $row2 .= '<td>' . mb_substr($dimensions[2], $i, 1) . '</td>';
            }
        }

        $row2 .= "</tr>";

        $row3 = "<tr>";

        for ($i = 0; $i < mb_strlen($dimensions[3]); $i++) {
            if (mb_substr($dimensions[3], $i, 1) == "E") {
                $row3 .= '<td><b>' . mb_substr($dimensions[3], $i, 1) . '</b></td>';
            } else {
                $row3 .= '<td>' . mb_substr($dimensions[3], $i, 1) . '</td>';
            }
        }

        $row3 .= "</tr>";

        $row4 = "<tr>";

        for ($i = 0; $i < mb_strlen($dimensions[4]); $i++) {
            if (mb_substr($dimensions[4], $i, 1) == "E") {
                $row4 .= '<td><b>' . mb_substr($dimensions[4], $i, 1) . '</b></td>';
            } else {
                $row4 .= '<td>' . mb_substr($dimensions[4], $i, 1) . '</td>';
            }
        }

        $row4 .= "</tr>";

        $end_table = '</table>';

        $itog = $begin . $head_table . $end_head_table . $row1 . $row2 . $row3 . $row4 . $end_table;
        return $itog;
}

function dimensions_array($dimensions) {
        for ($i = 0; $i < count($dimensions[6]); $i++) {
            $array[$i][0] = $dimensions[6][$i];
            $array[$i][1] = mb_substr($dimensions[1], $i, 1);
            $array[$i][2] = mb_substr($dimensions[2], $i, 1);
            $array[$i][3] = mb_substr($dimensions[3], $i, 1);
            $array[$i][4] = mb_substr($dimensions[4], $i, 1);
        }


        return $array;
}



////CHEREDOVATEL3000////

function cheredovatel($id, $massive_search, $augment, $postgment, $source, $debug)
{
            // Инициализация флагов и переменных
            $FLAG_STOP = 0;
            $no_stop = 1;

            // Получение информации из базы данных
            $info_massive_before[0] = VerbCache::search_in_db($id, 'verbs', 1);
            $verb_setnost = $info_massive_before[0][6];
            $verb_ryad = $info_massive_before[0][4];

            // Добавление массивов поиска в массив перед обработкой
            foreach ($massive_search as $i => $search_item) {
                $info_massive_before[$i + 1] = $search_item;
            }

            // Проверка, если массивы поиска не пусты
            foreach ($massive_search as $search_item) {
                if ((empty($search_item[0]) && empty($search_item['endings']))) {
                    $no_stop = 0;
                    break;
                }
            }

            // Очистка массива поиска от ненужных элементов
            $massive_search_new = [];
            foreach ($massive_search as $search_item) {
                if ($search_item[0] !== "|" || $search_item[7] !== "-") {
                    $massive_search_new[] = $search_item;
                }
            }
            $massive_search = $massive_search_new;

            // Если массивы поиска пусты, устанавливаем флаг остановки
            if ($no_stop == 0) {
                $FLAG_STOP = 1;
            }

            if (!$FLAG_STOP) {
                // Генерация setnost
                $setnost = make_setnost($massive_search, $source);
                $info_massive_set = $setnost['set'];
                $info_massive_anit = $setnost['anit'];

                // Склейка "i" для чередования в set
                $info_massive_set_new = [];
                $c = 1;
                foreach ($info_massive_set as $i => $set_item) {
                    if ($set_item[0] == "|i|" || $set_item[0] == "|ī|") {
                        $info_massive_set_new[$i - $c][0] .= $set_item[0];
                        $c++;
                    } else {
                        $info_massive_set_new[] = $set_item;
                    }
                }
                $info_massive_set = $info_massive_set_new;

                // Склейка "i" для чередования в anit
                $info_massive_anit_new = [];
                $c = 1;
                foreach ($info_massive_anit as $i => $anit_item) {
                    if ($anit_item[0] == "|i|" || $anit_item[0] == "|ī|") {
                        $info_massive_anit_new[$i - $c][0] .= $anit_item[0];
                        $c++;
                    } else {
                        $info_massive_anit_new[] = $anit_item;
                    }
                }
                $info_massive_anit = $info_massive_anit_new;

                    // Объединение и чередование set
                    $combine_set = combine_massives($info_massive_set);
                    $alternate_set = make_alternate($combine_set, $augment, $source, $debug);
                    $combine_word_sandhi_set = $alternate_set['sandhi'];
                    $combine_word_nosandhi_set = $alternate_set['no_sandhi'];

                    // Объединение и чередование anit
                    $combine_anit = combine_massives($info_massive_anit);
                    $alternate_anit = make_alternate($combine_anit, $augment, $source, $debug);

                    $combine_word_sandhi_anit = $alternate_anit['sandhi'];
                    $combine_word_nosandhi_anit = $alternate_anit['no_sandhi'];

                    // Обработка info_massive_set2
                $ch = count($combine_word_sandhi_set);
                $info_massive_set2 = $massive_search;
                foreach ($info_massive_set2 as $i => $set_item) {
                    if (isset($combine_word_sandhi_set[$ch - 1])) {
                        if ($set_item != "|a|") {
                            $set_item[0] = $combine_word_sandhi_set[$ch - 1];
                        } else {
                            $ch++;
                            $set_item = ["|a|"];
                        }
                    }
                    $ch--;
                }
                $info_massive_set = $info_massive_set2;

                // Обработка info_massive_anit2
                $ch = count($combine_word_sandhi_anit);
                $info_massive_anit2 = $massive_search;
                foreach ($info_massive_anit2 as $i => $anit_item) {
                    if (isset($combine_word_sandhi_anit[$ch - 1])) {
                        if ($anit_item != "|a|") {
                            $anit_item[0] = $combine_word_sandhi_anit[$ch - 1];
                        } else {
                            $ch++;
                            $anit_item = ["|a|"];
                        }
                    }
                    $ch--;
                }
                $info_massive_anit = $info_massive_anit2;
            }
            

                // Обработка info_massive_set2
                $ch = count($combine_word_sandhi_set);
                $info_massive_set2 = $massive_search;

                for ($i = 0; $i < count($info_massive_set2); $i++) {
                    if (isset($combine_word_sandhi_set[$ch - 1])) {
                        if ($info_massive_set2[$i] != "|a|") {
                            $info_massive_set2[$i][0] = $combine_word_sandhi_set[$ch - 1];
                        } else {
                            $ch++;
                            $info_massive_set2[$i] = array("|a|");
                        }
                    }
                    $ch--;
                }
                $info_massive_set = $info_massive_set2;

                // Обработка info_massive_anit2
                $ch = count($combine_word_sandhi_anit);
                $info_massive_anit2 = $massive_search;

                for ($i = 0; $i < count($info_massive_anit2); $i++) {
                                        
                    if (isset($combine_word_sandhi_anit[$ch - 1])) {
                        
                        if ($info_massive_anit2[$i] != "|a|") {
                                
                            $info_massive_anit2[$i][0] = $combine_word_sandhi_anit[$ch - 1];
                                                        } 
                        else {
                            
                            $ch++;
                            $info_massive_anit2[$i] = array("|a|");
                                                        
                            }
                    }
                                        $ch--;
                }
                
                $info_massive_anit = $info_massive_anit2;

            //////////////////////SETNOST/////////////////////

                    // Изменение суффиксов
                        list($info_massive_set, $info_massive_set_rgveda, $comments_set) = Change_in_Suffixes($info_massive_before, $info_massive_set);
                        list($info_massive_anit, $info_massive_anit_rgveda, $comments_anit) = Change_in_Suffixes($info_massive_before, $info_massive_anit);


                        //////////////////////SANDHI/////////////////////
                        if (!$FLAG_STOP) {
                        // Генерация строк Sandhi
                        $sandhi_string_set = Sandhi_String_From_Massive($info_massive_set, $augment, $postgment);
                        if ($info_massive_set[0] == "|a|") {
                            $augment = "|a|";
                        }
                        $sandhi_string_anit = Sandhi_String_From_Massive($info_massive_anit, $augment, $postgment);
                        $sandhi_string_set_rgveda = Sandhi_String_From_Massive($info_massive_set_rgveda, $augment, $postgment);
                        $sandhi_string_anit_rgveda = Sandhi_String_From_Massive($info_massive_anit_rgveda, $augment, $postgment);
                
                        // Применение функции simple_sandhi и сохранение результатов
                        //echo "Debug sandhi<BR>";
                        //print_r($info_massive_before);

                        if($info_massive_before[1]!="|a|")
                        {
                            $p_before_mool=$info_massive_before[1]['p_before_mool'];
                        }
                        else
                        {
                            $p_before_mool=$info_massive_before[2]['p_before_mool'];
                        }

                        if ($sandhi_string_set) {
                            list($sandhi_combined_set, $sandhi_combined_set_emeno) = simple_sandhi($sandhi_string_set, $info_massive_before[0][0], "", 0, $p_before_mool);
                        }
                        if ($sandhi_string_anit && $sandhi_string_anit != $sandhi_string_set) {
                            list($sandhi_combined_anit, $sandhi_combined_anit_emeno) = simple_sandhi($sandhi_string_anit, $info_massive_before[0][0], "", 0, $p_before_mool);
                        }
                        if ($sandhi_string_set_rgveda) {
                            list($sandhi_combined_set_rgveda, $sandhi_combined_set_rgveda_emeno) = simple_sandhi($sandhi_string_set_rgveda, $info_massive_before[0][0], "", 0, $p_before_mool);
                        }
                        if ($sandhi_string_anit_rgveda && $sandhi_string_anit_rgveda != $sandhi_string_set_rgveda) {
                            list($sandhi_combined_anit_rgveda, $sandhi_combined_anit_rgveda_emeno) = simple_sandhi($sandhi_string_anit_rgveda, $info_massive_before[0][0], "", 0, $p_before_mool);
                        }
                
                        // Формирование результата
                        $result = [];
                
                        if (isset($sandhi_combined_set) && $sandhi_combined_set == $sandhi_combined_anit) {
                            $result[0] = [$sandhi_string_set, $sandhi_combined_set, $sandhi_combined_set_emeno];
                        } else {
                            $result[0] = [$sandhi_string_set, $sandhi_combined_set, $sandhi_combined_set_emeno];
                            if (isset($sandhi_combined_anit)) {
                                $result[1] = [$sandhi_string_anit, $sandhi_combined_anit, $sandhi_combined_anit_emeno];
                            }
                        }
                
                        if (isset($sandhi_combined_set_rgveda) || isset($sandhi_combined_anit_rgveda)) {
                            if (isset($sandhi_combined_set_rgveda) && $sandhi_combined_set_rgveda == $sandhi_combined_anit_rgveda) {
                                $result[2] = [$sandhi_string_set_rgveda, $sandhi_combined_set_rgveda, $sandhi_combined_set_rgveda_emeno];
                            } else {
                                $result[2] = [$sandhi_string_set_rgveda, $sandhi_combined_set_rgveda, $sandhi_combined_set_rgveda_emeno];
                                if (isset($sandhi_combined_anit_rgveda)) {
                                    $result[3] = [$sandhi_string_anit_rgveda, $sandhi_combined_anit_rgveda, $sandhi_combined_anit_rgveda_emeno];
                                }
                            }
                        }
                    
                        // Дополнение результата дополнительной информацией
                        $result[4] = [
                            $info_massive_before,
                            $info_massive,
                            $info_massive_set,
                            $info_massive_anit,
                            $info_massive_set_rgveda,
                            $info_massive_anit_rgveda,
                            $comments_set,
                            $comments_anit
                        ];
                    
                        $result['string'] = $str;

                    } else {
                        $result[0] = "STOP";
                    }
            
            return $result;

}


function make_alternate($combine,$augment,$source,$debug)
{
    $string_ch[]="(".$combine[0][1][7].")".$combine[0][1][0];

    for($i=0;$i<count($combine);$i++)
    {
        if($combine[$i][0][7]!=0)
        {
            $string_ch[]="(".$combine[$i][0][7].")".$combine[$i][0][0];
        }
        else
        {
            $string_ch[]=$combine[$i][0][0];
        }
    }

    
    if($augment)
    {
        $string_ch[]=$augment;
    }

    for($i=count($string_ch)-1;$i>=0;$i--)
    {
        $str.=$string_ch[$i];
        if($i!=0)
        {
            $str.="+";
        }
    }

    $str="<b>$str</b><BR>";

    if($debug)
    {
        echo "<HR><h5>Чередование справа налево:</h5><BR>";
    }

    $combine_word_sandhi[]=$combine[0][1][0];
    $combine_word_nosandhi[]=$combine[0][1][0];

    $combine_word_sandhi_2[]=$combine[0][1][0];
    $combine_word_nosandhi_2[]=$combine[0][1][0];


    for($i=0;$i<count($combine);$i++)
    {

        $prefix=$combine[$i][0]['prefix'];

        $mool=$combine[$i][0][0];
        $postfix='';
        $mool_number=$combine[$i][0][1];
        $mool_type=$combine[$i][0][2];
        $mool_change=$combine[$i][0][3];
        $mool_type_change=$combine[$i][0][4];
        $suffix=$combine[$i][1][0];
        $suffix_ask=$combine[$i][1][7];
        $suffix_transform=$combine[$i][1][8];
        $glagol_or_imennoy=$combine[$i][0][5];
        $verb_setnost=$combine[$i][0][6];

        $stop=$combine[$i][0]['stop'];
        $flag_e=$combine[$i][0]['flag_e'];
        $need_two=$combine[$i][0]['need_two'];
        $change_later=$combine[$i][0]['change_later'];

        $suffix_steam=$combine[$i][1][9];

        if(!$stop&&!$flag_e)
        {
          
            $cheredovatel=get_word($prefix, $source, $mool, $postfix, $mool_number, $mool_type, $mool_change, $mool_type_change, $suffix, $suffix_ask, $suffix_transform, $glagol_or_imennoy,$verb_setnost, $stop, $debug, '',$flag_e);
            
            $sandhi=$cheredovatel[10];
            $no_sandhi=$cheredovatel[9];
        }
        else
        {
            $sandhi=$prefix."|".$mool;
            $no_sandhi=$prefix."|".$mool;
        }

        if($flag_e&&$need_two)
        {
            $cheredovatel=get_word("",$source,$mool,"",$mool_number,$mool_type,$mool_change,$mool_type_change,$suffix,$suffix_ask,$suffix_transform,$glagol_or_imennoy,$verb_setnost,
            $stop,$debug,$change_later[3],0);

            $cheredovatel=get_word("",$source,$cheredovatel[9],"",$mool_number,$mool_type,$mool_change,$mool_type_change,'',1,'',"1",$verb_setnost,$stop,$debug,$change_later[2],$flag_e);

            $sandhi=$cheredovatel[10];
            $no_sandhi=$cheredovatel[9];

        }

        if($flag_e&&!$need_two)
        {
            //echo "AGAINNN";
            
            $cheredovatel=get_word("",$source,$mool,"",$mool_number,$mool_type,$mool_change,$mool_type_change,$suffix,$suffix_ask,$suffix_transform,$glagol_or_imennoy,$verb_setnost,
            $stop,$debug,$change_later[3],0);

            $sandhi=$cheredovatel[10];
            $no_sandhi=$cheredovatel[9];

        }
        

        //echo "<BR>NO SANDHI: ".$sandhi;
        
        //$setnost=setnost_letter($mool,$suffix,$verb_setnost,$suffix_steam,$suffix_ask);

        $combine_word_sandhi[]=$sandhi;
        $combine_word_nosandhi[]=$no_sandhi;

    }

    $result['sandhi']=$combine_word_sandhi;
    $result['no_sandhi']=$combine_word_nosandhi;

    return $result;

}


function combine_massives($massive)
{
    
    $j=0; $work_massive=array();
    for($i=count($massive)-1;$i>0;$i--)
    {
        $minus=$i-1;
        if($minus>=0)
        {
            $work_massive[$j][0]=$massive[$minus];
            $work_massive[$j][1]=$massive[$i];
            $j++;
        }
    }

    return $work_massive;

}

class VerbCache {
    private static $cache = [];

    public static function search_in_db($id, $where, $type) {
        // Создаем ключ для кэша
        $cacheKey = "$where-$id-$type";
        
        // Проверяем, есть ли результат в кэше
        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }

        include "db.php";

        // Выполняем запрос к базе данных
        $query_db = "SELECT * FROM $where WHERE id=$id";
        $conn = mysqli_query($connection, $query_db);

        if (mysqli_num_rows($conn) > 0) {
            $res = mysqli_fetch_array($conn);

            $verb_name = $res['name'];
            $verb_omonim = $res['omonim'];
            $verb_type_lat = $res['type'];
            $verb_setnost = $res['setnost'];
            $verb_pada = $res['pada'];
            $verb_prs = $res['prs'];
            $verb_aos = $res['aos'];
            $verb_change = $res['element'];
            $verb_ryad = $res['ryad'];
            $element = $res['element'];
            $omonim = $res['omonim'];
            $query = $res['query'];
            $transform = $res['transform'];
            $steam = $res['lemma'];
            $lemma = $res['lemma'];
            $rule = $res['rule'];
            $adhoc = $res['adhoc'];

            // Преобразуем латинский тип в числовой тип
            switch ($verb_type_lat) {
                case "I":
                    $verb_type = 1;
                    break;
                case "II":
                    $verb_type = 2;
                    break;
                case "III":
                    $verb_type = 3;
                    break;
                case "IV":
                    $verb_type = 4;
                    break;
                default:
                    $verb_type = 0;
            }

            $omonim_text = $omonim ? " $omonim " : "";
            $adhoc_text = $adhoc ? "<BR>несам.: $adhoc " : "";

        } else {
            echo "no result";
            return [];
        }

        // Обработка типа
        if ($type == 1) {
            $query = 0;
            $transform = '';
            $lemma = "VR";
        }

        if ($type == 2) {
            $verb_setnost = 's';
        }
                
        // Формирование результата
        $result = [
            $verb_name,   // 0
            $verb_omonim, // 1
            $verb_type,   // 2
            $verb_change, // 3
            $verb_ryad,   // 4
            $type,        // 5
            $verb_setnost,// 6
            $query,       // 7
            $transform,   // 8
            $steam,       // 9
            $verb_pada,   // 10
            $element,     // 11
            $verb_prs,    // 12
            $verb_aos,    // 13
            $lemma,       // 14
            $rule         // 15
        ];

        // Сохраняем результат в кэш
        self::$cache[$cacheKey] = $result;

        return $result;
    }
}


function Change_in_Suffixes($info_massive_before,$info_massive_set)
{
    $name=$info_massive_before[0][0];
    $omonim=$info_massive_before[0][1];
    $type=$info_massive_before[0][2];
    $element=$info_massive_before[0][11];
    $ryad=$info_massive_before[0][4];

    //print_r($info_massive_before);

    $dimensions=dimensions($name, $element, $name, 1, 0, 0, 0);
    $dimensions_array=dimensions_array($dimensions);
   // echo dimensions_table($dimensions);

    //print_r($dimensions_array);

    $c_string=$dimensions[1];
    if(mb_strpos("EE",$c_string))
    {
        $sdvig=2;
    }
    else
    {
        $sdvig=1;
    }

   // echo "$c_string SDVIG:".$sdvig;


    $c_string=str_replace("EE","E",$c_string);
    $e_position=mb_strpos($c_string,"E");

    $p="";
    for($k=1;$k<$e_position;$k++)
    {
        $p.=$dimensions_array[$k][0];
    }

    $f="";
    for($k=$e_position+$sdvig;$k<count($dimensions_array);$k++)
    {
        $f.=$dimensions_array[$k][0];
    }

    $e="";
    for($k=$e_position;$k<$e_position+$sdvig;$k++)
    {
        $e.=$dimensions_array[$k][0];
    }

    
    


    $ecc=mb_substr($c_string,$e_position,3);
    if($ecc=="E")
    {
        $is_open_mool=1;
    }
    else
    {
        $is_open_mool=0;    
    }

    for($i=0;$i<count($info_massive_set);$i++)
    {
       
        
        if($info_massive_set[$i][5]==1)
        {
            $massive_verb_id=$i;
        }
        

        $flag_v_end=0;
        if($info_massive_set[$i][5]==3)
        {
            $massive_end_id=$i;

            $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "o"];
            for($j=0;$j<count($vowels);$j++)
            {
                if(mb_substr($info_massive_set[$massive_end_id][0],0,1)==$vowels[$j])
                {
                    $flag_v_end=1;
                }
            }
        }
    }

    //echo "ENDING:".$info_massive_set[$massive_end_id][0];
   
    for($i=0;$i<count($info_massive_set);$i++)
    {
        if($info_massive_set[0][5]==1)
        {
            $rule=$info_massive_set[0][15];
        }
        else
        {
            $rule=$info_massive_set[1][15];
        }
       


        if($rule==2)
        {
            $long_i_id="";
            if($info_massive_set[$i+1][5]==3)
            {
                $ending=$info_massive_set[$i+1][0];
                $length=strlen($ending);
                if($length==1)
                {
                    $end_cons=seeking_1_bukva($ending,1)[1];
                    if($end_cons=="C")
                    {
                        $long_i_id=$i+1;
                    }   
                }
            }
            
        }
        
        if($rule==5)
        {
  
            
            if($is_open_mool&&($ryad=="R0"||$ryad=="R1"||$ryad=="R2"||$ryad=="I0"||$ryad=="I1"||$ryad=="I2"||$ryad=="U0"||$ryad=="U1"||$ryad=="U2"))
            {
               
                if($info_massive_set[$i][5]==3)
                {
                    
                    $first_letter=mb_substr($info_massive_set[$i][0],0,1);
                    
                    if(($first_letter=="m"||$first_letter=="v")&&($info_massive_set[$i][7]==1)&&$info_massive_set[$i-1][5]==2)
                    {
                        $info_massive_set[$i-1][0]=str_replace("u","",$info_massive_set[$i-1][0]);
                        $comments.="Перед окончаниями на -m, -v, требующих 1МП, «u» суффикса перестает отображаться на письме, если VR открытый рядов I, U, R<BR>";
                    }
                }
            }
            
        }
        
        if($rule==7)
        {
              //  echo "NMBERY 7: $p <BR> $e <BR> $f";

                if($info_massive_set[$i][5]==2)
                {
                    if($info_massive_set[$i-1][5]==1)
                    {
                        $info_massive_set[$i-1][0]=$p."|$e|".$info_massive_set[$i][0].$f;
                        $info_massive_set[$i][0]="|";
                    }

                }

        }

        if($rule==8)
        {
          
            if($name=="kṛ")
            {
               
               
                if($info_massive_set[$i][5]==2&&$info_massive_set[$i][0]=="|o|")
                {
                       $info_massive_set[$massive_verb_id][0]="kar";
                       $comments.="Исключения для √kṛ: Если (1)-о-, то √kṛ → kar(2)-o(2)-  <BR>";
                }

                if($info_massive_set[$i][5]==2&&($info_massive_set[$i][0]=="|u|"||$info_massive_set[$i][0]=="|ū|"||$info_massive_set[$i-1][0]=="|v|"))
                {
                       $info_massive_set[$massive_verb_id][0]="kur";
                       $comments.="Исключения для √kṛ: Если (1)-u-, то √kṛ → kur(1)-u(1)- <BR>";
                }

                

                if($info_massive_set[$i][5]==2)
                {
                    $first_letter=mb_substr($info_massive_set[$i+1][0],0,1);

                    if(($first_letter=="m"||$first_letter=="v"||$first_letter=="y")&&($info_massive_set[$i-1][0]=="|u|"||$info_massive_set[$i-1][0]=="|ū|"||$info_massive_set[$i-1][0]=="|v|")&&$info_massive_set[$i-1][5]==2)
                    {
                       
                        $info_massive_set[$i-1][0]=str_replace("|ū|","|",$info_massive_set[$i-1][0]);
                        $info_massive_set[$i-1][0]=str_replace("|u|","|",$info_massive_set[$i-1][0]);
                        
                        $comments.="Em, Ev, Ey в kur(1)-u- → kur(1)-/u/- <BR>";
                    }
                }

            }
        }


        switch($info_massive_set[$i][15])
        {
            case "1A":
                $rule="1A";
                break;
            case "2A":
                $rule="2A";
                break;
            case "3A":
                $rule="3A";
                break;
            case "4A":
                $rule="4A";
                break;
            case "5A":
                $rule="5A";
                break;
            case "6A":
                $rule="6A";
                break;
            case "7A":
                $rule="7A";
                break;
        }


        if($rule=="3A")
        {
            $next=$i+1;
            if($info_massive_set[$i][5]==1&&$info_massive_set[$next][14]=="CaS")
            {
                $info_massive_set[$next][0]=str_replace("pay","p",$info_massive_set[$next][0]);
            }
        }

        $rule_other=$info_massive_set[$i][15];

        if($rule_other=="PrO")
        {
           
            if($info_massive_set[$i][5]==2&&$info_massive_set[$i+1][5]==3&&$info_massive_set[$i+1][0]=="yur")
            {
                $info_massive_set[$i][0]="|";
                $comments.="При окончании 3 pl. P. (1)-yur суффикс отсутствует.";
            }

        }

        if($rule_other=="PaPrAS")
        {
          
            
           
            if($info_massive_set[$i][5]==2&&$info_massive_set[$i-1][5]==2&&($rule==1||$rule==4||$rule==6)&&$info_massive_before[$i+1][0]=="n̥t")
            {
                //echo $info_massive_before[$i+1][0];

                $info_massive_set[$i-1][0]=str_replace("|a|","|",$info_massive_set[$i-1][0]);
                $comments.="Перед (1)-n̥t- «а» в суффиксах тематических PrS на письме не отображается";
            }

        }

        if($rule_other=="PaFuAS")
        {
            if($info_massive_set[$i][5]==2&&$info_massive_set[$i-1][5]==2&&$info_massive_before[$i+1][0]=="n̥t")
            {

                $info_massive_set[$i-1][0]=str_replace("|a|","|",$info_massive_set[$i-1][0]);
                $comments.="Перед (1)-n̥t- «а» в суффиксах FuS на письме не отображается";
            }

        }



        if($rule_other=="PaPePS")
        {
 
            $previous=$i-1;
            if($info_massive_set[$i-1][5]!=2&&$info_massive_set[$i-1][0]=="|i|")
            {
                $previous=$i-2;
            }
       
            if(($info_massive_set[$i][0]=="ta"||$info_massive_set[$i][0]=="t|a|")&&$info_massive_set[$i][7]==1&&$info_massive_set[$i][5]==2&&$info_massive_set[$previous][5]==2&&$info_massive_set[$previous][14]=="CaS")
            {
                $info_massive_set[$previous][0]=str_replace("|a|y","|",$info_massive_set[$previous][0]);
                $comments.="Если (1)-ta- непосредственно следует за суффиксом CaS, то суффикс CaS (1/2/3)-ay-, а также часть «ау» в составе суффикса (2/3)-pay- на письме не отображается (-/ay/- и -p/ay/- соответственно).";
            }

        }

        if($rule_other=="PaFuP")
        {
            $previous=$i-1;

            if($info_massive_set[$i-1][5]!=2&&$info_massive_set[$i-1][0]=="|i|")
            {
                $previous=$i-2;
            }

            if($info_massive_set[$i][5]==2&&$info_massive_set[$previous][14]=="CaS")
            {
                $info_massive_set[$previous][0]=str_replace("|a|y","|",$info_massive_set[$previous][0]);
            }

        }

              
        if($rule=="1A"||$rule_other=="Ao1")
        {

            if($info_massive_set[$massive_verb_id][0]=="bhū"&&$flag_v_end)
            {
                $info_massive_set[$massive_verb_id][0]="bhūv";
            }
        }

        if($rule_other=="4A")
        {
            if($info_massive_set[$i+1][0]=="|i|")
            {
                $next=$i+2;
            }
            else
            {
                $next=$i+1;
            }

            if($info_massive_set[$next][15]=="3.sg.P."||$info_massive_set[$next][15]=="2.sg.P.")
            {
                if($info_massive_set[$i+1][0]=="|i|")
                {
                    $info_massive_set[$i+1][0]="|ī|";
                }
                else
                {
                    $info_massive_set[$next][0]="|ī|".$info_massive_set[$next][0];
                }
            }

        }

        if($rule_other=="5A")
        {
            
            
            if($info_massive_set[$i+1][0]=="|i|")
            {
                $info_massive_set[$i+1][0]="|ī|";
                $info_massive_set[$i][0]=str_replace("iṣ","|",$info_massive_set[$i][0]);
            }
            else
            {
                $info_massive_set[$i][0]=str_replace("iṣ","|ī|",$info_massive_set[$i][0]);
            }
        }

        if($rule_other=="6A")
        {
            if($info_massive_set[$i+1][0]=="|i|")
            {
                $next=$i+2;
            }
            else
            {
                $next=$i+1;
            }

            if($info_massive_set[$next][15]=="3.sg.P."||$info_massive_set[$next][15]=="2.sg.P.")
            {
                $info_massive_set[$i][0]=str_replace("siṣ","s",$info_massive_set[$i][0]);
                
                if($info_massive_set[$i+1][0]=="|i|")
                {
                    $info_massive_set[$i+1][0]="|ī|";
                }
                else
                {
                    $info_massive_set[$next][0]="|ī|".$info_massive_set[$next][0];
                }
            }

        }

        if($rule_other=="Ao1")
        {
            if($info_massive_set[$i][5]==3)
            {
                $last=str_replace("|","",$info_massive_set[$i-1][0]);

                $last_letter=mb_substr($last,mb_strlen($last)-1,1);
                if($last_letter!="ā")
                {
                    $info_massive_set[$i][0]=str_replace("y","",$info_massive_set[$i][0]);
                }
  
            }
        }

        if($rule_other=="PkA")
        {
          
            if($info_massive_set[$i][5]==2)
            {
                if($info_massive_set[$i+1][5]==3)
                {
                    if($info_massive_set[$i+1][0]=="thās"||$info_massive_set[$i+1][0]=="ta")
                    {
                        $info_massive_set[$i][0]="īṣ";
                    }
                }
            }
        }
        /*
        if($rule_other=="PaPeAS")
        {
            //echo "<BR><BR>BEFORE:";
           // print_r($info_massive_before);
            if($info_massive_set[0][4]=="N0"||$info_massive_set[0][4]=="N1"||$info_massive_set[0][4]=="N2"||$info_massive_set[0][4]=="M0"||$info_massive_set[0][4]=="M1"||$info_massive_set[0][4]=="M2")
            {
               if($info_massive_before[2][0]=="uØs")
               {
                 echo "Между корнями рядов M и N и (1)-uøs- возможна вставная -i- вне зависимости от характеристики seṭ-aniṭ корня";
               }
            }
        }
        */
    }

    
    if($long_i_id!="")
    {
        for($j=0;$j<count($info_massive_set);$j++)
        {
            $info_massive_rgveda[]=$info_massive_set[$j];
            if($long_i_id-1==$j)
            {
                $info_massive_rgveda[]=array("ī");
                $comments.="Перед окончаниями, состоящими из единичного соглас-ного, может быть вставная -ī- <BR>";
            }
        }
    }

    //echo "<BR><BR>RGVGD:";
    
    

    $result[0]=$info_massive_set;
    $result[1]=$info_massive_rgveda;
    $result[3]=$comments;
    return $result;
}

function mts($array_big,$array_small,$id,$command,$lico,$chislo,$pada,$full)
{
        for($i=0;$i<count($array_big);$i++)
        {
            if($full)
            {
                $string.="<p>".$array_big[$i]."</p><p><small><a href='/generator2.php?id=$id&command=$command&lico=$lico&chislo=$chislo&pada=$pada'>".$array_small[$i]."</a></small></p>";
            }
            else
            {
                $class='class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"';
                $string.="<p><a href='/generator2.php?id=$id&command=$command&lico=$lico&chislo=$chislo&pada=$pada' $class>".$array_big[$i]."</a></p>";
            }
            //$string.=read_write_corpus($array_big[$i],$id,$command,$lico,$chislo,$pada);
        }

        return $string;
}

function forms($id,$command,$lico,$chislo,$pada,$full)
{
    //echo $full;
    $chered=AllChered($id,$command,$lico,$chislo,$pada,0,0,0);
    $chered['string']=str_replace("</b>","",$chered['string']);
    $chered['string']=str_replace("<b>","",$chered['string']);

    $result[0]=mts($chered['sandhi'],$chered['string'],$id,$command,$lico,$chislo,$pada,$full);
    $result[1]=$chered['sandhi'][0];
    $result[2]=$chislo;
    $result[3]=$lico;
    $result[4]=$chered['sandhi'];

    return $result;
}

function command_arrays($final)
{
    switch($final)
    {
        case "pr":
            $command=array("VR-PrS-Pr","VR-PS-PrS-Pr","VR-DS-PrS-Pr","VR-DS-PS-PrS-Pr","VR-CaS-PrS-Pr","VR-CaS-PS-PrS-Pr","VR-CaS-DS-PrS-Pr","VR-CaS-DS-PS-PrS-Pr","VR-CaS-IS-PrS-Pr","VR-CaS-IS-PS-PrS-Pr","VR-IS-PrS-Pr","VR-IS-PS-PrS-Pr");
            break;
        case "pef":
            $command=array("VR-PeS-PeF","VR-PeS-PeOS-PeO","VR-PeS-PeIp","VR-PeS-PeSbS-PeSb","VR-PeS-PluPe");
            break;
        case "ppef":
            $command=array("VR-pPeS-PeF","VR-DS-pPeS-PeF","VR-CaS-pPeS-PeF","VR-CaS-DS-pPeS-PeF","VR-CaS-IS-pPeS-PeF","VR-IS-pPeS-PeF");
            break;
        case "fu":
            $command=array("VR-FuS-Fu","VR-DS-FuS-Fu","VR-CaS-FuS-Fu","VR-CaS-DS-FuS-Fu","VR-CaS-IS-FuS-Fu","VR-IS-FuS-Fu");       
            break; 
        case "prpr":
            $command=array("VR-PrS-OS-O","VR-PrS-Im","VR-PrS-Ip","VR-PrS-PrSbS-PrSb");
            break;
        case "co":
            $command=array("VR-FuS-Co","VR-DS-FuS-Co","VR-CaS-FuS-Co","VR-CaS-DS-FuS-Co","VR-CaS-IS-FuS-Co","VR-IS-FuS-Co");
            break;
        case "optative":
            $command=array("VR-PrS-OS-O","VR-PS-PrS-OS-O","VR-DS-PrS-OS-O","VR-DS-PS-PrS-OS-O","VR-CaS-PrS-OS-O","VR-CaS-PS-PrS-OS-O","VR-CaS-DS-PrS-OS-O","VR-CaS-DS-PS-PrS-OS-O","VR-CaS-IS-PrS-OS-O","VR-CaS-IS-PS-PrS-OS-O","VR-IS-PrS-OS-O","VR-IS-PS-PrS-OS-O");
            break;
        case "imperfect":
            $command=array("VR-PrS-Im","VR-PS-PrS-Im","VR-DS-PrS-Im","VR-DS-PS-PrS-Im","VR-CaS-PrS-Im","VR-CaS-PS-PrS-Im","VR-CaS-DS-PrS-Im","VR-CaS-DS-PS-PrS-Im","VR-CaS-IS-PrS-Im","VR-CaS-IS-PS-PrS-Im","VR-IS-PrS-Im","VR-IS-PS-PrS-Im");
            break;
        case "imperative":
            $command=array("VR-PrS-Ip","VR-PS-PrS-Ip","VR-DS-PrS-Ip","VR-DS-PS-PrS-Ip","VR-CaS-PrS-Ip","VR-CaS-PS-PrS-Ip","VR-CaS-DS-PrS-Ip","VR-CaS-DS-PS-PrS-Ip","VR-CaS-IS-PrS-Ip","VR-CaS-IS-PS-PrS-Ip","VR-IS-PrS-Ip","VR-IS-PS-PrS-Ip");
            break;
        case "sosl":
            $command=array("VR-PrS-PrSbS-PrSb","VR-PS-PrS-PrSbS-PrSb","VR-DS-PrS-PrSbS-PrSb","VR-DS-PS-PrS-PrSbS-PrSb","VR-CaS-PrS-PrSbS-PrSb","VR-CaS-PS-PrS-PrSbS-PrSb","VR-CaS-DS-PrS-PrSbS-PrSb","VR-CaS-DS-PS-PrS-PrSbS-PrSb","VR-CaS-IS-PrS-PrSbS-PrSb","VR-CaS-IS-PS-PrS-PrSbS-PrSb","VR-IS-PrS-PrSbS-PrSb","VR-IS-PS-PrS-PrSbS-PrSb");
            break;
        case "aorist":
            $command=array("VR-AoS-Ao","VR-DS-AoS-Ao","VR-CaS-AoS-Ao","VR-CaS-DS-AoS-Ao","VR-CaS-IS-AoS-Ao","VR-IS-AoS-Ao");
            break;
    
        case "aorist_imperative":
            $command=array("VR-AoS-AoIp","VR-DS-AoS-AoIp","VR-CaS-AoS-AoIp","VR-CaS-DS-AoS-AoIp","VR-CaS-IS-AoS-AoIp","VR-IS-AoS-AoIp");
            break;

        case "aorist_passive":
            $command=array("VR-AoS-AoP","VR-DS-AoS-AoP","VR-CaS-AoS-AoP","VR-CaS-DS-AoS-AoP","VR-CaS-IS-AoS-AoP","VR-IS-AoS-AoP");
            break;

        case "aorist_sosl":
            $command=array("VR-AoS-AoSbS-AoSb","VR-DS-AoS-AoSbS-AoSb","VR-CaS-AoS-AoSbS-AoSb","VR-CaS-DS-AoS-AoSbS-AoSb","VR-CaS-IS-AoS-AoSbS-AoSb","VR-IS-AoS-AoSbS-AoSb");
            break;
        
        case "aorist_prekative":
            $command=array("VR-AoS-PkS-Pk","VR-DS-AoS-PkS-Pk","VR-CaS-AoS-PkS-Pk","VR-CaS-DS-AoS-PkS-Pk","VR-CaS-IS-AoS-PkS-Pk","VR-IS-AoS-PkS-Pk");
            break;
        
        case "aorist_inqunctive":
            $command=array("VR-AoS-In","VR-DS-AoS-In","VR-CaS-AoS-In","VR-CaS-DS-AoS-In","VR-CaS-IS-AoS-In","VR-IS-AoS-In");
            break;
        case "deepr":
            $command=array("VR-G","VR-DS-G","VR-CaS-G","VR-CaS-DS-G","VR-CaS-IS-G","VR-IS-G");
            break;
    }

    $result[0]=$command;
    $result[1]=count($command);
    return $result;
}

function string_translate($array)
{
    for($i=0;$i<count($array);$i++)
    {
        $one[]=tranlate_command_to_russian($array[$i]);
    }
    
    $string=implode(", ",$one);
    return $string;
}

function tranlate_command_to_russian($command) 
{
    switch($command)
    {
        case "VR-PrS-Pr":
            //$name="Настоящее время";
            $name="Present tense";
            break;
        case "VR-PS-PrS-Pr":
            $name="Пассив настоящего времени";
            break;
        case "VR-DS-PrS-Pr":
            $name="Дезидератив настоящего времени";
            break;
        case "VR-DS-PS-PrS-Pr":
            $name="Дезидеративный пассив настоящего времени";
            break;
        case "VR-CaS-PrS-Pr":
            $name="Каузатив настоящего времени";
            break;   
        case "VR-CaS-PS-PrS-Pr":
            $name="Каузативный пассив настоящего времени";
            break;   
        case "VR-CaS-DS-PrS-Pr":
            $name="Каузативный дезидератив настоящего времени";
            break;   
        case "VR-CaS-DS-PS-PrS-Pr":
            $name="Каузативно-дезидеративный пассив настоящего времени";
            break; 
        case "VR-CaS-IS-PrS-Pr":
            $name="Каузативный интенсив настоящего времени";
            break;
        case "VR-CaS-IS-PS-PrS-Pr":
            $name="Каузативно-интенсивный пассив настоящего времени";
            break;
        case "VR-IS-PrS-Pr":
            $name="Интенсив настоящего времени";
            break;
        case "VR-IS-PS-PrS-Pr":
            $name="Интенсивный пассив настоящего времени";
            break;
        case "VR-PeS-PeF":
            $name="Перфект";
            break;
        case "VR-pPeS-PeF":
            $name="Описательный перфект";
            break;
        case "VR-DS-pPeS-PeF":
            $name="Дезидеративный описательный перфект";
            break;
        case "VR-CaS-pPeS-PeF":
            $name="Каузативный описательный перфект";
            break;
        case "VR-CaS-DS-pPeS-PeF":
            $name="Каузативно-дезидеративный описательный перфект";
            break;
        case "VR-CaS-IS-pPeS-PeF":
            $name="Каузативно-интенсивный описательный перфект";
            break;
        case "VR-IS-pPeS-PeF":
            $name="Интенсивный описательный перфект";
            break;
        case "VR-PeS-PeOS-PeO":
            $name="Оптатив перфекта";
            break;
        case "VR-PeS-PeIp":
            $name="Императив перфекта";
            break;
        case "VR-PeS-PeSbS-PeSb":
            $name="Сослагательный перфект";
            break;
        case "VR-PeS-PluPe":
            $name="Предпрошедшее время";
            break;
        case "VR-PrS-OS-O":
            $name="Оптатив настоящего времени";
            break;
        case "VR-PrS-Im":
            $name="Имперфект настоящего времени";
            break;
        case "VR-PrS-Ip":
            $name="Императив настоящего времени";
            break;
        case "VR-PrS-PrSbS-PrSb":
            $name="Сослагательное настоящее время";
            break;
        case "VR-PS-PrS-OS-O":
            $name="Пассивный оптатив настоящего времени";
            break;
        case "VR-DS-PrS-OS-O":
            $name="Дезидеративный оптатив настоящего времени";
            break;
        case "VR-DS-PS-PrS-OS-O":
            $name="Дезидеративный пассивный оптатив настоящего времени";
            break;
        case "VR-CaS-PrS-OS-O":
            $name="Каузативный оптатив настоящего времени";
            break;
        case "VR-CaS-PS-PrS-OS-O":
            $name="Каузативно-пассивный оптатив настоящего времени";
            break;
        case "VR-CaS-DS-PrS-OS-O":
            //$name="Каузативно-дезидеративный оптатив настоящего времени";
            $name="Causative-desiderative optative mood";
             break;
        case "VR-CaS-DS-PS-PrS-OS-O":
            //$name="Каузативно-дезидеративно-пассивный оптатив настоящего времени";
            $name="Causative-desiderative-passive optative mood";
            break;
        case "VR-CaS-IS-PrS-OS-O":
            $name="Каузативно-интенсивный оптатив настоящего времени";
            break;
        case "VR-CaS-IS-PS-PrS-OS-O":
            $name="Каузативно-интенсивно-пассивный оптатив настоящего времени";
            break;
        case "VR-IS-PrS-OS-O":
            $name="Интенсивный оптатив настоящего времени";
            break;
        case "VR-IS-PS-PrS-OS-O":
            $name="Интенсивно-пассивный оптатив настоящего времени";
            break;

        
        case "VR-PS-PrS-Im":
                $name="Пассивный имперфект настоящего времени";
                break;
        case "VR-DS-PrS-Im":
                $name="Дезидеративный имперфект настоящего времени";
                break;
        case "VR-DS-PS-PrS-Im":
                $name="Дезидеративный пассивный имперфект настоящего времени";
                break;
        case "VR-CaS-PrS-Im":
                $name="Каузативный имперфект настоящего времени";
                break;
        case "VR-CaS-PS-PrS-Im":
                $name="Каузативно-пассивный имперфект настоящего времени";
                break;
        case "VR-CaS-DS-PrS-Im":
                $name="Каузативно-дезидеративный имперфект настоящего времени";
                 break;
        case "VR-CaS-DS-PS-PrS-Im":
                $name="Каузативно-дезидеративно-пассивный имперфект настоящего времени";
                break;
        case "VR-CaS-IS-PrS-Im":
                $name="Каузативно-интенсивный имперфект настоящего времени";
                break;
        case "VR-CaS-IS-PS-PrS-Im":
                $name="Каузативно-интенсивно-пассивный имперфект настоящего времени";
                break;
        case "VR-IS-PrS-Im":
                $name="Интенсивный имперфект настоящего времени";
                break;
        case "VR-IS-PS-PrS-Im":
                $name="Интенсивно-пассивный имперфект настоящего времени";
                break;

        
        case "VR-PS-PrS-PrSbS-PrSb":
                    $name="Пассивное cослагательное настоящее время";
                    break;
        case "VR-DS-PrS-PrSbS-PrSb":
                    $name="Дезидеративное cослагательное настоящее время";
                    break;
        case "VR-DS-PS-PrS-PrSbS-PrSb":
                    $name="Дезидеративное пассивное cослагательное настоящее время ";
                    break;
        case "VR-CaS-PrS-PrSbS-PrSb":
                    $name="Каузативное cослагательное настоящее время";
                    break;
        case "VR-CaS-PS-PrS-PrSbS-PrSb":
                    $name="Каузативно-пассивное cослагательное настоящее времяи";
                    break;
        case "VR-CaS-DS-PrS-PrSbS-PrSb":
                    $name="Каузативно-дезидеративное cослагательное настоящее время";
                     break;
        case "VR-CaS-DS-PS-PrS-PrSbS-PrSb":
                    $name="Каузативно-дезидеративно-пассивное cослагательное настоящее время";
                    break;
        case "VR-CaS-IS-PrS-PrSbS-PrSb":
                    $name="Каузативно-интенсивное cослагательное настоящее время";
                    break;
        case "VR-CaS-IS-PS-PrS-PrSbS-PrSb":
                    $name="Каузативно-интенсивно-пассивное cослагательное настоящее время";
                    break;
        case "VR-IS-PrS-PrSbS-PrSb":
                    $name="Интенсивное cослагательное настоящее время";
                    break;
        case "VR-IS-PS-PrS-PrSbS-PrSb":
                    $name="Интенсивно-пассивное cослагательное настоящее время";
                    break;
        case "VR-PS-PrS-Ip":
                        $name="Пассивный императив настоящего времени";
                        break;
        case "VR-DS-PrS-Ip":
                        $name="Дезидеративный императив настоящего времени";
                        break;
        case "VR-DS-PS-PrS-Ip":
                        $name="Дезидеративный пассивный императив настоящего времени";
                        break;
        case "VR-CaS-PrS-Ip":
                        $name="Каузативный императив настоящего времени";
                        break;
        case "VR-CaS-PS-PrS-Ip":
                        $name="Каузативно-пассивный императив настоящего времени";
                        break;
        case "VR-CaS-DS-PrS-Ip":
                        $name="Каузативно-дезидеративный императив настоящего времени";
                         break;
        case "VR-CaS-DS-PS-PrS-Ip":
                        $name="Каузативно-дезидеративно-пассивный императив настоящего времени";
                        break;
        case "VR-CaS-IS-PrS-Ip":
                        $name="Каузативно-интенсивный императив настоящего времени";
                        break;
        case "VR-CaS-IS-PS-PrS-Ip":
                        $name="Каузативно-интенсивно-пассивный императив настоящего времени";
                        break;
        case "VR-IS-PrS-Ip":
                        $name="Интенсивный императив настоящего времени";
                        break;
        case "VR-IS-PS-PrS-Ip":
                        $name="Интенсивно-пассивный императив настоящего времени";
                        break;

        case "VR-FuS-Fu":
                $name="Будущее время";
                break;
                
        case "VR-DS-FuS-Fu":
                $name="Дезидератив будущего времени";
                break;

        case "VR-CaS-FuS-Fu":
                $name="Каузатив будущего времени";
                break;
    
        case "VR-CaS-DS-FuS-Fu":
                $name="Каузативно-дезидеративное будущее время";
                break;

        case "VR-CaS-IS-FuS-Fu":
                $name="Каузативно-интенсивное будущее время";
                break;

        case "VR-IS-FuS-Fu":
                $name="Интенсивное будущее время";
                break;   
                
        case "VR-FuS-Co":
                    $name="Кондиционалис";
                    break;
                    
        case "VR-DS-FuS-Co":
                    $name="Дезидеративный кондиционалис";
                    break;
    
        case "VR-CaS-FuS-Co":
                    $name="Каузативный кондиционалис";
                    break;
        
        case "VR-CaS-DS-FuS-Co":
                    $name="Каузативно-дезидеративный кондиционалис";
                    break;
    
        case "VR-CaS-IS-FuS-Co":
                    $name="Каузативно-интенсивный кондиционалис";
                    break;
    
        case "VR-IS-FuS-Co":
            $name="Интенсивный кондиционалис";
            break;  
        //"VR-AoS-Ao","VR-AoS-AoIp","VR-AoS-AoP","VR-AoS-AoSbS-AoSb","VR-AoS-PkS-Pk","VR-AoS-In" 
        case "VR-AoS-Ao":
            $name="Аорист";
            break;  
        //"VR-DS-AoS-Ao","VR-CaS-AoS-Ao","VR-CaS-DS-AoS-Ao","VR-CaS-IS-AoS-Ao","VR-IS-AoS-Ao"    
        case "VR-DS-AoS-Ao":
            $name="Дезидеративный аорист";
            break;  
        case "VR-CaS-AoS-Ao":
            $name="Каузативный аорист";
            break; 
        case "VR-CaS-DS-AoS-Ao":
            $name="Каузативно-дезидеративный аорист";
            break; 
        case "VR-CaS-IS-AoS-Ao":
            $name="Каузативно-интенсивный аорист";
            break; 
        case "VR-IS-AoS-Ao":
            $name="Интенсивный аорист";
            break; 

        case "VR-AoS-AoIp":
            $name="Императив аориста";
            break;  
            
        case "VR-DS-AoS-AoIp":
                $name="Дезидеративный императив аориста";
                break;  
        case "VR-CaS-AoS-AoIp":
                $name="Каузативный императив аориста";
                break; 
        case "VR-CaS-DS-AoS-AoIp":
                $name="Каузативно-дезидеративный императив аориста";
                break; 
        case "VR-CaS-IS-AoS-AoIp":
                $name="Каузативно-интенсивный императив аориста";
                break; 
        case "VR-IS-AoS-AoIp":
                $name="Интенсивный императив аориста";
                break; 

        case "VR-AoS-AoP":
            $name="Пассив аориста";
            break; 
        
        case "VR-DS-AoS-AoP":
                $name="Дезидеративный пассив аориста";
                break;  
        case "VR-CaS-AoS-AoP":
                $name="Каузативный пассив аориста";
                break; 
        case "VR-CaS-DS-AoS-AoP":
                $name="Каузативно-дезидеративный пассив аориста";
                break; 
        case "VR-CaS-IS-AoS-AoP":
                $name="Каузативно-интенсивный пассив аориста";
                break; 
        case "VR-IS-AoS-AoP":
                $name="Интенсивный пассив аориста";
                break; 
        
        
        
        case "VR-AoS-AoSbS-AoSb":
            $name="Сослагательный аорист";
            break;   
            
        case "VR-DS-AoS-AoSbS-AoSb":
                $name="Дезидеративный сослагательный аориста";
                break;  
        case "VR-CaS-AoS-AoSbS-AoSb":
                $name="Каузативный сослагательный аориста";
                break; 
        case "VR-CaS-DS-AoS-AoSbS-AoSb":
                $name="Каузативно-дезидеративный сослагательный аориста";
                break; 
        case "VR-CaS-IS-AoS-AoSbS-AoSb":
                $name="Каузативно-интенсивный сослагательный аориста";
                break; 
        case "VR-IS-AoS-AoSbS-AoSb":
                $name="Интенсивный сослагательный аориста";
                break;             


        case "VR-AoS-PkS-Pk":
            $name="Прекатив / бенедиктив";
            break; 
        
        case "VR-DS-AoS-PkS-Pk":
                $name="Дезидеративный прекатив / бенедиктив";
                break;  
        case "VR-CaS-AoS-PkS-Pk":
                $name="Каузативный прекатив / бенедиктив";
                break; 
        case "VR-CaS-DS-AoS-PkS-Pk":
                $name="Каузативно-дезидеративный прекатив / бенедиктив";
                break; 
        case "VR-CaS-IS-AoS-PkS-Pk":
                $name="Каузативно-интенсивный прекатив / бенедиктив";
                break; 
        case "VR-IS-AoS-PkS-Pk":
                $name="Интенсивный прекатив / бенедиктив";
                break; 
        
        
        case "VR-AoS-In":
            $name="Инъюнктив";
            break;         

        case "VR-DS-AoS-In":
                $name="Дезидеративный инъюнктив";
                break;  
        case "VR-CaS-AoS-In":
                $name="Каузативный инъюнктив";
                break; 
        case "VR-CaS-DS-AoS-In":
                $name="Каузативно-дезидеративный инъюнктив";
                break; 
        case "VR-CaS-IS-AoS-In":
                $name="Каузативно-интенсивный инъюнктив";
                break; 
        case "VR-IS-AoS-In":
            $name="Интенсивный инъюнктив";
            break; 

        case "VR-G":
            $name="Деепричастие";
            break; 
            
        case "VR-DS-G":
            $name="Дезидеративное деепричастие";
            break; 
            
        case "VR-CaS-G":
            $name="Каузативное деепричастие";
            break; 

        case "VR-CaS-DS-G":
            $name="Каузативно-дезидеративное деепричастие";
            break; 

        case "VR-CaS-IS-G":
            $name="Каузативно-интенсивное деепричастие";
            break; 

        case "VR-IS-G":
            $name="Интенсивное деепричастие";
            break; 

        default:
            $name=$command;
            break;
    }

    return $name;
}

function computeDiff($from, $to)
{
    $diffValues = array();
    $diffMask = array();

    $dm = array();
    $n1 = count($from);
    $n2 = count($to);

    for ($j = -1; $j < $n2; $j++) $dm[-1][$j] = 0;
    for ($i = -1; $i < $n1; $i++) $dm[$i][-1] = 0;
    for ($i = 0; $i < $n1; $i++)
    {
        for ($j = 0; $j < $n2; $j++)
        {
            if ($from[$i] == $to[$j])
            {
                $ad = $dm[$i - 1][$j - 1];
                $dm[$i][$j] = $ad + 1;
            }
            else
            {
                $a1 = $dm[$i - 1][$j];
                $a2 = $dm[$i][$j - 1];
                $dm[$i][$j] = max($a1, $a2);
            }
        }
    }

    $i = $n1 - 1;
    $j = $n2 - 1;
    while (($i > -1) || ($j > -1))
    {
        if ($j > -1)
        {
            if ($dm[$i][$j - 1] == $dm[$i][$j])
            {
                $diffValues[] = $to[$j];
                $diffMask[] = 1;
                $j--;  
                continue;              
            }
        }
        if ($i > -1)
        {
            if ($dm[$i - 1][$j] == $dm[$i][$j])
            {
                $diffValues[] = $from[$i];
                $diffMask[] = -1;
                $i--;
                continue;              
            }
        }
        {
            $diffValues[] = $from[$i];
            $diffMask[] = 0;
            $i--;
            $j--;
        }
    }    

    $diffValues = array_reverse($diffValues);
    $diffMask = array_reverse($diffMask);

    return array('values' => $diffValues, 'mask' => $diffMask);
}

function diffline($line1, $line2)
{
    $diff = computeDiff(str_split($line1), str_split($line2));
    $diffval = $diff['values'];
    $diffmask = $diff['mask'];

    $n = count($diffval);
    $pmc = 0;
    $result = '';
    for ($i = 0; $i < $n; $i++)
    {
        $mc = $diffmask[$i];
        if ($mc != $pmc)
        {
            switch ($pmc)
            {
                case -1: $result .= '</del>'; break;
                case 1: $result .= '</ins>'; break;
            }
            switch ($mc)
            {
                case -1: $result .= '<del>'; break;
                case 1: $result .= '<ins>'; break;
            }
        }
        $result .= $diffval[$i];

        $pmc = $mc;
    }
    switch ($pmc)
    {
        case -1: $result .= '</del>'; break;
        case 1: $result .= '</ins>'; break;
    }

    return $result;
}


function manual_e($string,$mool_change)
{
   // echo $string."<BR>";
    
    $dimensions_p2=dimensions($string,"","",0,0,0,"");
    $dimensions_p2[1]=str_replace("-","",$dimensions_p2[1]);
   // $dimensions_p2[1]=str_replace("|","",$dimensions_p2[1]);

    //echo "DIM p2:".$dimensions_p2[1]."<BR>";

    //$sdvig=1;
   

    $e_length=mb_strlen($mool_change);
    $e_position=mb_strlen($dimensions_p2[1])-$e_length;

    //echo "STRLEN:".mb_strlen($dimensions_p2[1])."<BR>";
   // echo "E_LENGTH:".$e_length."<BR>";
    //echo "EPOS:".$e_position."<BR>";



    ///?????///
    $e_2=$e_position;

    //echo "MB_SUBSTR:".mb_substr($dimensions_p2[1],$e_2,1);

    if(mb_substr($dimensions_p2[1],$e_2-1,1)=="|")
    {
        $e_2++;
    }

   // echo "EPOS2:".$e_2;


    $change_later=array("E'",$mool_change,'', $e_2);

    return $change_later;
}

function AllChered($id,$command,$lico,$chislo,$pada,$debug_command,$debug_chered,$debug_table)
{


    $commandline=CommandLine($id,$command,$lico,$chislo,$pada,$debug_command);

    if($debug_chered)
    {

        if($commandline[1])
        {
            echo "<BR>Залог Ubhayapada, есть несколько вариантов<BR>";
        }
    }

    $augment=$commandline['augment'];
    $augment_var=$commandline['augment_var'];
    $postgment=$commandline['postgment'];
    $source=$commandline['source'];

    for($j=0;$j<count($commandline[0]);$j++)
    {
        $chered=cheredovatel($id,$commandline[0][$j][0],$augment,$postgment,$source,$debug_chered);

        //echo "<HR>";
        //print_r($chered[4][2]);
        //echo "<HR>";
        
        
        if($chered[0]!="STOP")
        {

            if($debug_table)
            {
                echo $chered['string'];
                debug_table($chered[4][2]);

                if($chered[4][3]!=$chered[4][2])
                {
                    debug_table($chered[4][3]);
                }

            }

            for($i=0;$i<4;$i++)
            {
                if($chered[$i][0])
                {
                
                   // echo "<BR>".$chered[$i][1]."i-1:".$chered[$i-1][1]."<BR>";

                    if((mb_strpos($command,"IS")||mb_strpos($command,"DS"))&&!mb_strpos($command,"pPeS"))
                    {
                        $chered['string']="2√".$chered['string'];
                    }
                
                    $itog['string'][]=$chered['string'];
                    $itog['nosandhi'][]=$chered[$i][0];
                    $itog['sandhi'][]=$chered[$i][1];
                    $itog['rules'][]=$chered[$i][2];
            

                    if($debug_table)
                    {
                        
                        echo "<BR>Result (without sandhi): ".$chered[$i][0]."<BR>";
                        echo "Result (with sandhi): <b>".$chered[$i][1]."</b><BR>";
                        if($chered[$i][2])
                        {
                            echo "Using Emeneau`s sandhi rules: ".$chered[$i][2];
                        }
                        echo"<HR>";
                        //echo "Применили правила Эмено: ".$chered[4][7]."<HR>";
                    }

                }
            }

            if($augment_var)
            {
                $chered=cheredovatel($id,$commandline[0][$j][0],$augment_var,$postgment,$source,$debug_chered);
                $command_massive=explode("-",$command);

                if($debug_table)
                {
                    echo $chered['string'];
                    debug_table($chered[4][2]);
        
                    if($chered[4][3]!=$chered[4][2])
                    {
                        debug_table($chered[4][3]);
                    }

                }

             

                for($i=0;$i<4;$i++)
                {
                    if($chered[$i][0])
                    {
                        
                        if((mb_strpos($command,"IS")||mb_strpos($command,"DS"))&&!mb_strpos($command,"pPeS"))
                        {
                            $chered['string']="2√".$chered['string'];
                        }
                        
                        if(!in_array($chered[$i][1],$itog['sandhi']))
                        {
                            $itog['string'][]=$chered['string'];
                            $itog['nosandhi'][]=$chered[$i][0];
                            $itog['sandhi'][]=$chered[$i][1];
                            $itog['rules'][]=$chered[$i][2];
                        }
                        else
                        {
                            if($debug_table)
                            {
                                echo "Два одинаковых варианта объединим в один.<BR>";
                            }  
                        }


                        if($debug_table)
                        {
                            
                            echo "<BR>Итог без сандхи: ".$chered[$i][0]."<BR>";
                            echo "Итог c сандхи: <b>".$chered[$i][1]."</b><BR>";
                            if($chered[$i][2])
                            {
                                echo "Применили правила Эмено: ".$chered[$i][2];
                            }
                            echo"<HR>";
                            //echo "Применили правила Эмено: ".$chered[4][7]."<HR>";
                        }

                    }
                }
            }

        }
        else
        {
            $itog['string'][0]="Нет формы";
            $itog['nosandhi'][0]="Нет формы";
            $itog['sandhi'][0]="Нет формы";
            $itog['rules'][0]="Нет формы";
        }
        
    }

    if($commandline[1])
    {

        $augment=$commandline['augment'];
        $augment_var=$commandline['augment_var'];
        $postgment=$commandline['postgment'];
        for($j=0;$j<count($commandline[1]);$j++)
        {
            $chered=cheredovatel($id,$commandline[1][$j][1],$augment,$postgment,$source,$debug_chered);

            if($chered[0]!="STOP")
            {

                $command_massive=explode("-",$command);

                if($debug_table)
                {
                    echo $chered['string'];
                    debug_table($chered[4][2]);

                    if($chered[4][3]!=$chered[4][2])
                    {
                        debug_table($chered[4][3]);
                    }

                }

                for($i=0;$i<4;$i++)
                {
                    if($chered[$i][0])
                    {
                        
                        if((mb_strpos($command,"IS")||mb_strpos($command,"DS"))&&!mb_strpos($command,"pPeS"))
                        {
                            $chered['string']="2√".$chered['string'];
                        }
                        
                        if(!in_array($chered[$i][1],$itog['sandhi']))
                        {
                            $itog['string'][]=$chered['string'];
                            $itog['nosandhi'][]=$chered[$i][0];
                            $itog['sandhi'][]=$chered[$i][1];
                            $itog['rules'][]=$chered[$i][2];
                        }
                        else
                        {
                            if($debug_table)
                            {
                                echo "Два одинаковых варианта объединим в один.<BR>";
                            }  
                        }
        
                        if($debug_table)
                        {

                            echo "<BR>Result (without sandhi): ".$chered[$i][0]."<BR>";
                            echo "Result (with sandhi): <b>".$chered[$i][1]."</b><BR>";
                            if($chered[$i][2])
                            {
                                echo "Using Emeneau`s sandhi rules: ".$chered[$i][2];
                            }
                            echo "<HR>";
                        }

                    }
                }

                if($augment_var)
                {
                    $chered=cheredovatel($id,$commandline[1][$j][1],$augment_var,$postgment,$source,$debug_chered);
                    $command_massive=explode("-",$command);

                    if($debug_table)
                    {
                        echo $chered['string'];
                        debug_table($chered[4][2]);

                        if($chered[4][3]!=$chered[4][2])
                        {
                            debug_table($chered[4][3]);
                        }

                    }

                    for($i=0;$i<4;$i++)
                    {
                        if($chered[$i][0])
                        {
                        
                            if((mb_strpos($command,"IS")||mb_strpos($command,"DS"))&&!mb_strpos($command,"pPeS"))
                            {
                                $chered['string']="2√".$chered['string'];
                            }
                            
                            $itog['string'][]=$chered['string'];
                            $itog['nosandhi'][]=$chered[$i][0];
                            $itog['sandhi'][]=$chered[$i][1];
                            $itog['rules'][]=$chered[$i][2];
            
                            echo "<BR>Итог без сандхи: ".$chered[$i][0]."<BR>";
                            echo "Итог c сандхи: <b>".$chered[$i][1]."</b><BR>";
                            if($chered[$i][2])
                            {
                                echo "Применили правила Эмено: ".$chered[$i][2];
                            }
                            echo"<HR>";


                        }
                    }
                }

            }
            else
            {
                $itog['string'][0]="Нет формы";
                $itog['nosandhi'][0]="Нет формы";
                $itog['sandhi'][0]="Нет формы";
                $itog['rules'][0]="Нет формы";

            }
        }
    }


    $result['string']=$itog['string'];
    $result['nosandhi']=$itog['nosandhi'];
    $result['sandhi']=$itog['sandhi'];
    $result['rules']=$itog['rules'];

    return $result;

}


function CommandLine($id,$string,$lico,$chislo,$needpada,$debug)
{
    $massive=explode("-",$string);
    $verb_info=VerbCache::search_in_db($id,'verbs',1);

    //print_r($verb_info);

    if($verb_info[10]=="Ā")
    {
        $verb_info[10]="A";
    }
  

    if($needpada!='')
    {

        
        if(mb_strpos($string,"-PS-")!=0)
        {
            $pada[0]="A";
           
        }
        else
        {
            $pada[0]=$needpada;
          
        }

        if($pada[0]!=$verb_info[10]&&$verb_info[10]!="U")
        {
            $pada[0]="No form (Залог запроса ".$pada[0]." и залог корня ".$verb_info[10]." не совпадают)";
        }

    }
    else
    {

        if(mb_strpos($string,"-PS-")!=0)
        {
            $pada[0]="A";
        }
        else
        {

            if(VR($id)[10]=="U")
            {
                $pada[0]="P";
                $pada[1]="A";
            }
            else
            {
                $pada[0]=VR($id)[10];
            }

        }

    }

    //print_r($pada);

  
   

    for($j=0;$j<count($pada);$j++)
    {
        if($debug)
        {
            echo "Словоформа: <b>".$string."</b> Pada: <u>".$pada[$j]."</u><BR>";
        }
            
    
        for($i=0;$i<count($massive);$i++)
        {
                $ms=0;
           
               // echo "<hr>J:$j I:$i<hr>";
                
                switch($massive[$i])
                { 
                   
                    case "VR":
                        
                        $massive_search[$ms][$j][]=$verb_info;
                        
                        break;
                     
                    case "AoS":

                        //Пассив аориста образуется от AoS1 от любого корня
                        //Прекатив Р. образуется от AoS1 от любого корня
                        //Прекатив Ā. образуется от AoS4, AoS5, AoS6 (редко) класса от любого корня.
                        
                        if(mb_strpos($string,"AoP")||(mb_strpos($string,"Pk")&&$pada[$j]=="P"))
                        {
                            $verb_info[13]=1;
                        }

                        if((mb_strpos($string,"Pk")&&$pada[$j]=="A"))
                        {
                            $verb_info[13]="4,5,6";
                        }

                        $aos=AoS($massive[$i-1],$massive_search[0][$j][1][0],$verb_info[13],$massive_search[0][$j][0][0],$verb_info[1],$verb_info[2],$verb_info[3],$verb_info[4],$pada[$j],$debug);
                        
                        if(!$p_before_mool)
                        {
                            $p_before_mool=$aos['p_before_mool'];
                        }

                        //echo "P_BEFORE_MOOL AOS<BR>";
                        //print_r($p_before_mool);
                        //echo "<BR><BR>AAAAA:";
                        //print_r($aos);

                        if($aos[0])
                        {

                            $count_new_roots=0;
                            $count_new_roots=count($aos[0]);
                            //echo "COUNT NEW ROOTS: $count_new_roots<BR>";
                            $count_massive=count($massive_search);
                            //echo "COUNT MASSIVE SEARCH: $count_massive<BR>";
                            
                            $count_forms=0;
                            //$count_forms=count($aos[1]);

                            //print_r($massive_search);
                            
                            for($m=0;$m<$count_new_roots;$m++)
                            {
                                 $count_forms=$count_forms+count($aos[1][$m]);
                            }
                            
                            //echo "COUNT SUFFIXIES: $count_forms<BR>";
                        
                            $variants=$count_new_roots*$count_massive;


                            //echo "<BR>VARIANTS: $variants<BR><BR>";

                            //DUPLICATE MASSIVES
                            $counter=0;

                            for($k=0;$k<$variants;$k++)
                            {
                                    $massive_search_new[]=$massive_search[$counter];

                                    $counter++;
                                    if($counter>=$count_massive)
                                    {
                                        $counter=0;
                                    }
                            }
                            $massive_search=$massive_search_new;
                            unset($massive_search_new);

                            //CHANGE ROOTS & RULES
                            $counter=0;$counter_massive=0;$v=0;

                           

                            for($k=0;$k<$variants;$k++)
                            {
                                 
                                $massive_search[$counter_massive][$j][0][0]=$aos[0][$counter];

                               

                                if($aos[2][$counter]=="5A"&&$aos['ec']==1)
                                {
                                    $massive_search[$counter_massive][$j][0][2]="1";
                                }

                                //echo "COUNTER $counter<BR>";
                                //echo "Aos2:".$aos[2][$counter]."<BR>";
                                //echo "COUNTER MASSIVE $counter_massive<HR>";

                                $massive_search[$counter_massive][$j][0][15]=$aos[2][$counter];
                                $aos[1][$counter][0]['verb']=$aos[0][$counter];
                                if($aos[1][$counter][1])
                                {
                                    $aos[1][$counter][1]['verb']=$aos[0][$counter];
                                }

                               
                                //echo $aos['change_later'][$counter]."<BR>";

                                $massive_search[$counter_massive][$j][0]['no_udvoenie']=$aos['no_udvoenie'][$counter];
                                $massive_search[$counter_massive][$j][0]['stop']=$aos['stop'][$counter];
                                $massive_search[$counter_massive][$j][0]['flag_e']=$aos['flag_e'][$counter];
                                $massive_search[$counter_massive][$j][0]['need_two']=$aos['need_two'][$counter];
                                $massive_search[$counter_massive][$j][0]['change_later']=$aos['change_later'][$counter];

                                //P_Before_mool
                                //echo "COUNTER: $counter -->". $p_before_mool[$counter];
                                $massive_search[$counter_massive][$j][0]['p_before_mool']=$p_before_mool[$counter];

                                $v++;

                                $counter_massive++;

                                if($v>$count_massive-1)
                                {
                                    $counter++;
                                    $v=0;
                                }

                                if($counter>=$count_new_roots)
                                {
                                    $counter=0;
                                }
                            }

                           

                            //ADDING NEW MASSIVE IF SUFFIXIES FORM > 1
                            unset($aos_suffixies);
                            unset($aos_suffixies_new);
                            unset($massive_search_new);
                            $k2=0;
                            for($k=0;$k<count($aos[1]);$k++)
                            {
                                    $aos_suffixies[]=$aos[1][$k][0];
                                    
                                    if($aos[1][$k][1])
                                    {
                                        $aos_suffixies[]=$aos[1][$k][1];
                                    }
                              
                            }

                       

                            for($k=0;$k<$variants;$k++)
                            {
                               for($t=0;$t<count($aos_suffixies);$t++)
                               {
                                    $verb_m=$massive_search[$k][$j][0][0];
                                    $verb_a=$aos_suffixies[$t]['verb'];
                                    $query_a=$aos_suffixies[$t][7];
                                    $name_a=$aos_suffixies[$t][0];

                                    //print_r($aos_suffixies);

                                    
                                    if($aos_suffixies[$t][15]=="2A"||$aos_suffixies[$t][15]=="3A"||$aos_suffixies[$t][15]=="7A")
                                    {
                                        $tematic[$t]=1;
                                        $aos_suffixies[$t]['tematic']=1;
                                    }
                                    else
                                    {
                                        $tematic[$t]=0;
                                        $aos_suffixies[$t]['tematic']=0;
                                    }
                                    
                                    if($verb_m==$verb_a)
                                    {
                                        $flag_noexist_suff=1;

                                        //SEARCH FOR DUPLICATES
                                        for($n=0;$n<count($aos_suffixies_new);$n++)
                                        {
                                            if($aos_suffixies_new[$n][7]==$query_a&&$aos_suffixies_new[$n][0]==$name_a&&$aos_suffixies_new[$n]['verb']==$verb_a)
                                            {
                                               
                                                $flag_noexist_suff=0;
                                            }

                                           // echo "<hr>$name_a $query_a $verb_a FLAG SUFF:$flag_noexist_suff FLAG VERB: $flag_noexist<hr>";
                                        }

                                        //echo "FLAG:'$flag_noexist_suff' K$k";

                                        if($flag_noexist_suff)
                                        {
                                            //echo "<HR>".$massive_search[$k][$j][0][15]."<HR>";
                                            $aos_suffixies_new[]=$aos_suffixies[$t]; 
                                            $massive_search_new[]=$massive_search[$k];
                                        }

                                       
                                        
                                    }

                               }

                            }


                            if($massive_search_new)
                            {
                                $massive_search=$massive_search_new;
                            }

                            if($aos_suffixies_new)
                            {
                                $aos_suffixies=$aos_suffixies_new;
                            }
                         

                            //echo "<HR>";
                            //print_r($massive_search);
                            //echo "<BR><BR>";
                            //echo "<HR>";

                            //echo "<BR><BR>MS:";
                            //print_r($massive_search);
                            //echo "<HR>";

                            //ADDING SUFFIXES
                            $counter=0;
                            for($k=0;$k<count($massive_search);$k++)
                            {
                                $massive_search[$k][$j][]=$aos_suffixies[$k];

                            }

                        
                        }   
                        break;
                    case "Ao":
                        $flag_some_endings=0;
 
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                            //echo "<HR>TEMATIC:".$tematic[$s]."<HR>";
                            $count = count($massive_search[$s][$j]);
                            $verb_name=$massive_search[0][$j][0][0];
                            $verb_element=$massive_search[0][$j][0][3];
                            $verb_ryad=$massive_search[0][$j][0][4];
                            $number_ao=$massive_search[$s][$j][$count-1][15];

                            $dimensions=dimensions($verb_name, $verb_element, $verb_name, 1, 0, 0, 0);
                            $dimensions_array=dimensions_array($dimensions);
                        
                            $c_string=$dimensions[1];
                        
                            $c_string=str_replace("EE","E",$c_string);
                            $e_position=mb_strpos($c_string,"E");
                            $ec=mb_substr($c_string,$e_position,2);
                            $p=mb_substr($c_string,0,$e_position);

                            if($ec=="E")
                            {
                                $is_open_mool=1;
                            }
                            else
                            {
                                $is_open_mool=0;
                            }


                            if($number_ao=="1A")
                            {
                                $last_name = $verb_name;
                            }
                            else
                            {
                                $last_name = $massive_search[$s][$j][$count-1][0];
                            }

                            //– открытые корни рядов I, U, R и корни ряда А1 вида PA1C 
                            if(($is_open_mool&&($verb_ryad=="I0"||$verb_ryad=="I1"||$verb_ryad=="I2"||$verb_ryad=="U0"||$verb_ryad=="U1"||$verb_ryad=="U2"||$verb_ryad=="R0"||$verb_ryad=="R1"||$verb_ryad=="R2"))||($verb_ryad=="A1"&&$p!=""&&$ec=="EC"))
                            {
                                $special=1;
                            }
                            elseif($verb_ryad=="M0"||$verb_ryad=="M1"||$verb_ryad=="M2")
                            {
                                $special=2;
                            }
                            elseif($verb_ryad=="N0"||$verb_ryad=="N1"||$verb_ryad=="N2")
                            {
                                $special=3;
                            }
                            else
                            {
                                $special=4;
                            }

                            //echo "SPECIAL $is_open_mool $special";

                            //echo "<BR>S$s QUERY:".$lico." ".$chislo." ".$pada[$j]." ".$tematic[$s]." "."Ao$number_ao"." ".$last_name." ".$special." "."0<BR>";
                            
                            $ao=Ao($lico,$chislo,$pada[$j],$tematic[$s],"Ao$number_ao",$last_name,$special,0);

                            //print_r($ao);
                            
                            $count_endings=0;
                            $count_endings=count($ao['endings']);
  
                       
                            unset($massive_search_new);
                            if($count_endings<16)
                            {
                                for($ce=0;$ce<$count_endings;$ce++)
                                {
                                    $massive_search_new[$ce][$j]=$massive_search[0][$j];
                                    $massive_search_new[$ce][$j][]=$ao['endings'][$ce];
                                }

                                for($ce=1;$ce<count($massive_search);$ce++)
                                {
                                    $massive_search_new[]=$massive_search[$ce];
                                }
                               
                                $massive_search=$massive_search_new;
                                
                                $flag_some_endings=$count_endings;

                            }
                            else
                            {
                                //echo "FSE: $flag_some_endings S:$s<BR>";

                                if($s>=$flag_some_endings)
                                {
                                    $massive_search[$s][$j][]=$ao['endings'];
                                }
                            }


                            if($ao['augment'])
                            {
                                $verb=$massive_search[$s][$j][0];
                                $temp=$massive_search[$s][$j];

                                for($t=0;$t<count($massive_search[$s][$j]);$t++)
                                {
                                    $temp[0]=$ao['augment'];
                                    $temp[$t+1]=$massive_search[$s][$j][$t];
                                }

                                $massive_search[$s][$j]=$temp;
                            }
                        }
                        break;
                    case "In":
                        $flag_some_endings=0;
 
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                            //echo "<HR>TEMATIC:".$tematic[$s]."<HR>";
                            $count = count($massive_search[$s][$j]);
                            $verb_name=$massive_search[0][$j][0][0];
                            $verb_element=$massive_search[0][$j][0][3];
                            $verb_ryad=$massive_search[0][$j][0][4];
                            $number_ao=$massive_search[$s][$j][$count-1][15];

                            $dimensions=dimensions($verb_name, $verb_element, $verb_name, 1, 0, 0, 0);
                            $dimensions_array=dimensions_array($dimensions);
                        
                            $c_string=$dimensions[1];
                        
                            $c_string=str_replace("EE","E",$c_string);
                            $e_position=mb_strpos($c_string,"E");
                            $ec=mb_substr($c_string,$e_position,2);
                            $p=mb_substr($c_string,0,$e_position);

                            if($ec=="E")
                            {
                                $is_open_mool=1;
                            }
                            else
                            {
                                $is_open_mool=0;
                            }


                            if($number_ao=="1A")
                            {
                                $last_name = $verb_name;
                            }
                            else
                            {
                                $last_name = $massive_search[$s][$j][$count-1][0];
                            }

                            //– открытые корни рядов I, U, R и корни ряда А1 вида PA1C 
                            if(($is_open_mool&&($verb_ryad=="I0"||$verb_ryad=="I1"||$verb_ryad=="I2"||$verb_ryad=="U0"||$verb_ryad=="U1"||$verb_ryad=="U2"||$verb_ryad=="R0"||$verb_ryad=="R1"||$verb_ryad=="R2"))||($verb_ryad=="A1"&&$p!=""&&$ec=="EC"))
                            {
                                $special=1;
                            }
                            elseif($verb_ryad=="M0"||$verb_ryad=="M1"||$verb_ryad=="M2")
                            {
                                $special=2;
                            }
                            elseif($verb_ryad=="N0"||$verb_ryad=="N1"||$verb_ryad=="N2")
                            {
                                $special=3;
                            }
                            else
                            {
                                $special=4;
                            }

                            //echo "SPECIAL $is_open_mool $special";

                            //echo "<BR>S$s QUERY:".$lico." ".$chislo." ".$pada[$j]." ".$tematic[$s]." "."Ao$number_ao"." ".$last_name." ".$special." "."0<BR>";
                            
                            $ao=Ao($lico,$chislo,$pada[$j],$tematic[$s],"Ao$number_ao",$last_name,$special,0);

                            //print_r($ao);
                            
                            $count_endings=0;
                            $count_endings=count($ao['endings']);
  
                       
                            unset($massive_search_new);
                            if($count_endings<16)
                            {
                                for($ce=0;$ce<$count_endings;$ce++)
                                {
                                    $massive_search_new[$ce][$j]=$massive_search[0][$j];
                                    $massive_search_new[$ce][$j][]=$ao['endings'][$ce];
                                }

                                for($ce=1;$ce<count($massive_search);$ce++)
                                {
                                    $massive_search_new[]=$massive_search[$ce];
                                }
                               
                                $massive_search=$massive_search_new;
                                
                                $flag_some_endings=$count_endings;

                            }
                            else
                            {
                                //echo "FSE: $flag_some_endings S:$s<BR>";

                                if($s>=$flag_some_endings)
                                {
                                    $massive_search[$s][$j][]=$ao['endings'];
                                }
                            }

                        }
                        break;
                    case "FuS":
                        
                     
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                            $massive_search[$s][$j][]=FuS();
                        }
                 
                        break;
                    
                    case "Fu":
                        
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                            $massive_search[$s][$j][]=Fu($lico,$chislo,$pada[$j]);
                        }
                        break;
                    
                    case "Co":
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                            $augment="|a|";
                            $count = count($massive_search[$s][$j]);
                            $last_name = $massive_search[$s][$j][$count-1][0];
                            $massive_search[$s][$j][]=Co($lico,$chislo,$pada[$j],$last_name,0);
                        }
                        break;
                    case "G": //Не реализовано для форм с приставками!
                           // for($s=0;$s<count($massive_search);$s++) 
                           // {
                           //     $verb_ryad=$massive_search[0][$j][0][4];
                           //     $massive_search[$s][$j][]=G($verb_ryad);
                           // }
                            $verb_ryad=$massive_search[0][$j][0][4];
                            $g=G($verb_ryad);

                            //print_r($g);

                            $count_forms=count($g);
                            $count_now=count($massive_search);

                            $counter2=0; $counter3=0;

                          

                            for($cn=0;$cn<$count_forms;$cn++)
                            {
                                $massive_search_new[$cn][$j]=$massive_search[0][$j];
                               // echo $cn;
                            }
                            $massive_search=$massive_search_new;

                            //print_r($massive_search);
                            //echo "<BR>";
                            //echo "<BR><BR>";
                            //$counter2=0;
                            
                            for($cf=0;$cf<$count_forms;$cf++)
                            {  
                                        $massive_search[$cf][$j][]=$g[$cf];

                            }

                            //print_r($massive_search);
                            //echo "<BR>";
                            //echo "<BR><BR>";
                            //print_r($massive_search);

                            break;
                    case "PaFuAS":
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                            $massive_search[$s][$j][]=PaFuAS($pada[$j],0);
                            $massive_search[$s][$j][count($massive_search[$s][$j])-1][15]="PaFuAS";
                        }
                        break;

                    case "CaS":

                        $verb=$massive_search[0][$j][0];
                        
                        if($massive[2]=="DS")
                        {
                            $not_chered=1;
                        }
                        else
                        {
                            $not_chered=0;
                        }

                        $cas=CaS($verb,$not_chered);


                        $suffixes=$cas[0];
                        $new_root_name=$cas[1];
                        $new_root_element=$cas[2];

                        $changings[]="";

                        $count_forms=count($suffixes);
                        $count_massive=count($massive_search);

                        //echo "<BR>Count FormS:".$count_forms."<BR>";
            
                        if($count_forms>1)
                        {
                            for($cf=0;$cf<$count_forms;$cf++)
                            {
                                $massive_search[$ms+$cf]=$massive_search[$ms];
                            }

                            for($cf=0;$cf<$count_forms;$cf++)
                            {
                                $massive_search[$ms+$cf][$j][]=$suffixes[$cf];
                            }
                        }
                        else
                        {
                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                $massive_search[$s][$j][]=$suffixes[0];
                            }
                        }


                        break;

                    case "DS":

                        $verb=$massive_search[0][$j][0];

                        if($i>1){
                            $not_after_root=1;
                        }
                        else{
                            $not_after_root=0;
                        }

                        $dses=DS($verb,$not_after_root,$new_root_name,$new_root_element,$pada[$j],$debug);
                        $p_before_mool=$dses['p_before_mool'];

                       

                        $count_forms=count($dses[0]);
                        $count_now=count($massive_search);

                        if($debug)
                        {
                            echo "В итоге получилось $count_forms варианта основы дезидератива<BR><BR>";
                            //print_r($dses);
                            //echo "<BR><BR>";
                           
                        }
                        
                        if($count_forms>1)
                        {

                            $count_now=count($massive_search);
                            $counter2=0; $counter3=0;

                            for($cf=0;$cf<$count_forms;$cf++)
                            {  

                                for($cn=0;$cn<$count_now;$cn++)
                                {
                                    $massive_search[$counter2]=$massive_search[$cn];
                                    
                                    //CHANGE ROOT
                                    $massive_search[$counter2][$j][0][0]=$dses[0][$counter3];
                                    $massive_search[$counter2][$j][0]['p_before_mool']=$p_before_mool[$counter3];

                                    $counter2++;
                                    $counter3++;
                                    if($counter3>count($dses[0])-1)
                                    {
                                        $counter3=0;
                                    }
                                }

                            }

                          

                            $count_now=count($massive_search);

                            for($cf=0;$cf<$count_now;$cf++)
                            {
                                $massive_search[$cf][$j][]=$dses[1];
                            }

                            

                        }
                        else
                        {
                            //print_r($p_before_mool);

                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                //CHANGE ROOT
                                $massive_search[$s][$j][0][0]=$dses[0][0];
                                $massive_search[$s][$j][0]['p_before_mool']=$p_before_mool[0];
                            }

                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                $massive_search[$s][$j][]=$dses[1];
                            }

                        }
                        //print_r($massive_search);
                        break;
                    case "IS":
                        

                        $verb=$massive_search[0][$j][0];
                        $is=IS($verb,$debug)[0];
                        $is_p_before_mool=IS($verb,$debug)['p_before_mool'];

                       

                        $count_forms=count($is);
                        $count_now=count($massive_search);
                        
                        if($count_forms>1)
                        {

                            $count_now=count($massive_search);
                            $counter2=0; $counter3=0;

                            for($cf=0;$cf<$count_forms;$cf++)
                            {  

                                for($cn=0;$cn<$count_now;$cn++)
                                {
                                    $massive_search[$counter2]=$massive_search[$cn];

                                    //CHANGE ROOT
                                    $massive_search[$counter2][$j][0][0]=$is[$counter3];
                                    $massive_search[$counter2][$j][0][15]="IS";
                                    $massive_search[$counter2][$j][0]['p_before_mool']=$is_p_before_mool[$counter3];
                                    
                                   // echo "<HR>";
                                  //  print_r($is_p_before_mool);
                                   // echo "<HR>";

                                    $counter2++;
                                    $counter3++;
                                    if($counter3>count($is)-1)
                                    {
                                        $counter3=0;
                                    }
                                }

                            }

                        }
                        else
                        {
                            //echo "<HR>";
                            //        echo $is_p_before_mool[0];
                            //        echo "<HR>";

                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                $massive_search[$s][$j][0][0]=$is[0];
                                $massive_search[$s][$j][0][15]="IS";
                                $massive_search[$s][$j][0]['p_before_mool']=$is_p_before_mool[0];
                            }
 

                        }
                        //print_r($massive_search);
                        break;
                    case "PeS":
                        
                        $verb=$massive_search[0][$j][0];

                        $pes=PeS($verb,$pada[$j],$debug);

                        //echo "<BR>PES:";
                        //print_r($pes);
                       

                        $count_forms=count($pes[0]);
                        $count_now=count($massive_search);

                        //echo "<BR>count_forms: $count_forms<BR>";
                        
                        if($count_forms>1)
                        {

                            $count_now=count($massive_search);
                            $counter2=0; $counter3=0;

                            for($cf=0;$cf<$count_forms;$cf++)
                            {  

                                for($cn=0;$cn<$count_now;$cn++)
                                {
                                    $massive_search[$counter2]=$massive_search[$cn];

                                    //CHANGE ROOT
                                    if($pes[0][$counter3])
                                    {
                                        $massive_search[$counter2][$j][0][0]=$pes[0][$counter3];
                                        $massive_search[$counter2][$j][0][15]=$pes['source'];
                                        $massive_search[$counter2][$j][0][8]=$pes['double_prefix'].$pes['double'][0];
                  
                                        $massive_search[$counter2][$j][0]['no_udvoenie']=$pes['no_udvoenie'][$counter3];
                                        $massive_search[$counter2][$j][0]['stop']=$pes['stop'];
                                        $massive_search[$counter2][$j][0]['flag_e']=$pes['flag_e'];
                                        $massive_search[$counter2][$j][0]['need_two']=$pes['need_two'];
                                        $massive_search[$counter2][$j][0]['change_later']=$pes['change_later'];
                                       
                                    }

                                    $massive_search[$counter2][$j][0]['p_before_mool']=$pes['p_before_mool'];

                                    $counter2++;
                                    $counter3++;
                                    if($counter3>count($pes[0])-1)
                                    {
                                        $counter3=0;
                                    }
                                }

                            }

                            $count_now=count($massive_search);
                        }
                        else
                        {
                          
                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                //CHANGE ROOT
                                if($pes[0][0])
                                {
      
                                        $massive_search[$s][$j][0][0]=$pes[0][0];
                                        $massive_search[$s][$j][0][8]=$pes['double_prefix'].$pes['double'][0];
                                        $massive_search[$s][$j][0][15]=$pes['source'];
                  
                                        $massive_search[$s][$j][0]['no_udvoenie']=$pes['no_udvoenie'][0];
                                        $massive_search[$s][$j][0]['stop']=$pes['stop'];
                                        $massive_search[$s][$j][0]['flag_e']=$pes['flag_e'];
                                        $massive_search[$s][$j][0]['need_two']=$pes['need_two'];
                                        $massive_search[$s][$j][0]['change_later']=$pes['change_later'];
                                        

                                        
                                }

                                $massive_search[$s][$j][0]['p_before_mool']=$pes['p_before_mool'];
                            }
                            
                            //echo "MASS SEARCH:<BR>";
                            //print_r($massive_search);

                        }
                       // echo "<BR>";
                        break;

                    case "PeOS":
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                            $massive_search[$s][$j][]=PeOS($pada[$j]);
                        }
                        break;   
                    case "PeO":
                        

                        $massive_search[0][$j][0][0]=$massive_search[0][$j][0][8];
                        //print_r($massive_search);
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                            $count = count($massive_search[$s][$j]);
                            $last_name = $massive_search[$s][$j][$count-1][0];
                            $massive_search[$s][$j][]=PeO($lico,$chislo,$pada[$j],$last_name,$tematic[$s],0);
                        }
                        break;
                    case "PeIp":
                        $massive_search[0][$j][0][0]=$massive_search[0][$j][0][8];
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                            $count = count($massive_search[$s][$j]);
                            $last_name = $massive_search[$s][$j][$count-1][0];
                            $massive_search[$s][$j][]=PeIp($verb,$lico,$chislo,$pada[$j],$last_name,$tematic[$s],0);
                        }
                        break;
                
                    case "PeSbS":
                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                $massive_search[$s][$j][]=PeSbS();
                            }
                            break;
                    case "AoSbS":
                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                $massive_search[$s][$j][]=AoSbS();
                            }
                            break;
                    case "PeSb":
                                $massive_search[0][$j][0][0]=$massive_search[0][$j][0][8];
                                for($s=0;$s<count($massive_search);$s++) 
                                {
                                    $count = count($massive_search[$s][$j]);
                                    $last_name = $massive_search[$s][$j][$count-1][0];
                                    $prsb=PeSb($lico,$chislo,$pada[$j],$last_name,$tematic[$s],0);
                                }
        
        
                                    $count_forms=count($prsb);
                                    $count_now=count($massive_search);
                                    
                                    if($count_forms>1)
                                    {
            
                                        $count_now=count($massive_search);
                                        $counter2=0; $counter3=0;
                                
                                        for($cf=0;$cf<$count_forms;$cf++)
                                        {  
                                            for($cn=0;$cn<$count_now;$cn++)
                                            {
                                                $massive_search_2[$counter2]=$massive_search[$cn];
                                                $massive_search_2[$counter2][$j][]=$prsb[$cf];
                                                $counter2++;
                                            }
                                        }
        
                                        $massive_search=$massive_search_2;
                                    }
                                
                        break;
                    case "AoSb":
                        
                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                $count = count($massive_search[$s][$j]);
                                $last_name = $massive_search[$s][$j][$count-1][0];
                                $prsb=PeSb($lico,$chislo,$pada[$j],$last_name,$tematic[$s],0);
                            }
    
    
                                $count_forms=count($prsb);
                                $count_now=count($massive_search);
                                
                                if($count_forms>1)
                                {
        
                                    $count_now=count($massive_search);
                                    $counter2=0; $counter3=0;
                            
                                    for($cf=0;$cf<$count_forms;$cf++)
                                    {  
                                        for($cn=0;$cn<$count_now;$cn++)
                                        {
                                            $massive_search_2[$counter2]=$massive_search[$cn];
                                            $massive_search_2[$counter2][$j][]=$prsb[$cf];
                                            $counter2++;
                                        }
                                    }
    
                                    $massive_search=$massive_search_2;
                                }
                            
                        break;
                    case "AoP":
                        if($chislo==1&&$lico==3)
                        {    
                        $augment="";
                        $augment_var="|a|";
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                                $count = count($massive_search[$s][$j]);
                                $last_name = $massive_search[$s][$j][$count-1][0];
                                $verb=$massive_search[0][$j][0];

                                
                                    $aop=AoP($verb,$lico,$chislo,$pada[$j],$last_name,$tematic[$s],0);
 


                                
                        $new_root=$aop[0];
                        if(count($new_root)<=1)
                        {
                            $new_root=$new_root[0];
                        }

                        $root_rule[]=$aop[2];
                        if(count($root_rule)<=1)
                        {
                            $root_rule=$root_rule[0];
                        }

                        $count_forms=count($aop[1]);
                        $count_now=count($massive_search);

                    
                        

                        if($count_forms>1)
                        {

                            $count_now=count($massive_search);
                            $counter2=0; $counter3=0;

                            for($cf=0;$cf<$count_forms;$cf++)
                            {  
                                for($cn=0;$cn<$count_now;$cn++)
                                {
                                    $massive_search[$counter2]=$massive_search[$cn];

                                

                                    //CHANGE ROOT
                                    if($new_root!="")
                                    {
                                    

                                        $massive_search[$counter2][$j][0][0]=$new_root[$counter2];
                                        
                                    }
                                    $counter2++;
                                }
                            }

                            $count_now=count($massive_search);

                            for($cn=0;$cn<$count_now;$cn++)
                            {
                                if($papeps[1][$cn]!="")
                                {
                                    $massive_search[$cn][$j][]=$aop[1][$cn];
                                    $massive_search[$cn][$j][count($massive_search[$cn][$j])-1][15]=$root_rule[0];
                                }

                            }

                        }
                        else
                        {
                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                //CHANGE ROOT
                                if($new_root!="")
                                {
                                    $massive_search[$s][$j][0][0]=$new_root;
                                    //$massive_search[$s][$j][count($massive_search[$s][$j])-1][15]=$root_rule[0];
                                }
                            }

                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                    $massive_search[$s][$j][]=$aop[1][0];
                                    $massive_search[$s][$j][count($massive_search[$s][$j])-1][15]=$root_rule[0];
                            }

                        }

                            }


                        }
                        else
                        {
                            $massive_search="";
                        }
                            
                        break;
                    case "Pk":
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                            $count = count($massive_search[$s][$j]);
                            $last_name = $massive_search[$s][$j][$count-1][0];
                            $number_ao=$massive_search[$s][$j][$count-2][15];
                            $tematic=$massive_search[$s][$j][$count-2]['tematic'];
                            //($lico,$chislo,$pada,$tematic,$lemma,$lastname,$special,$debug)

                            $count = count($massive_search[$s][$j]);
                            $verb_name=$massive_search[0][$j][0][0];
                            $verb_element=$massive_search[0][$j][0][3];
                            $verb_ryad=$massive_search[0][$j][0][4];
                            $number_ao=$massive_search[$s][$j][$count-1][15];

                            $dimensions=dimensions($verb_name, $verb_element, $verb_name, 1, 0, 0, 0);
                            $dimensions_array=dimensions_array($dimensions);
                        
                            $c_string=$dimensions[1];
                        
                            $c_string=str_replace("EE","E",$c_string);
                            $e_position=mb_strpos($c_string,"E");
                            $ec=mb_substr($c_string,$e_position,2);
                            $p=mb_substr($c_string,0,$e_position);

                            if($ec=="E")
                            {
                                $is_open_mool=1;
                            }
                            else
                            {
                                $is_open_mool=0;
                            }


                            if($number_ao=="1A")
                            {
                                $last_name = $verb_name;
                            }
                            else
                            {
                                $last_name = $massive_search[$s][$j][$count-1][0];
                            }

                            //– открытые корни рядов I, U, R и корни ряда А1 вида PA1C 
                            if(($is_open_mool&&($verb_ryad=="I0"||$verb_ryad=="I1"||$verb_ryad=="I2"||$verb_ryad=="U0"||$verb_ryad=="U1"||$verb_ryad=="U2"||$verb_ryad=="R0"||$verb_ryad=="R1"||$verb_ryad=="R2"))||($verb_ryad=="A1"&&$p!=""&&$ec=="EC"))
                            {
                                $special=1;
                            }
                            elseif($verb_ryad=="M0"||$verb_ryad=="M1"||$verb_ryad=="M2")
                            {
                                $special=2;
                            }
                            elseif($verb_ryad=="N0"||$verb_ryad=="N1"||$verb_ryad=="N2")
                            {
                                $special=3;
                            }
                            else
                            {
                                $special=4;
                            }

                            if($pada[$j]=="P")
                            {
                                $pk=Pk($lico,$chislo,$pada[$j],$tematic,"Ao$number_ao",$last_name,$special,0);
                            }

                            if($pada[$j]=="A")
                            {
                                $pk=Pk($lico,$chislo,$pada[$j],$tematic,"Pk",$last_name,0,0);
                            }


                            $massive_search[$s][$j][]=$pk['endings'];
                        }
                        break;
                        break;
                    case "PkS":
                        $verb=$massive_search[0][$j][0];
                        $pks=PkS($verb,$pada[$j],$debug);

                        $count_forms=count($pks);
                        $count_now=count($massive_search);
                        
                        $suffixes=$pks;

                        $count_forms=count($suffixes);
                        $count_massive=count($massive_search);

                        //echo "<BR>Count FormS:".$count_forms."<BR>";
            
                        if($count_forms>1)
                        {
                            for($cf=0;$cf<$count_forms;$cf++)
                            {
                                $massive_search[$ms+$cf]=$massive_search[$ms];
                            }

                            for($cf=0;$cf<$count_forms;$cf++)
                            {
                                $massive_search[$ms+$cf][$j][]=$suffixes[$cf];
                            }
                        }
                        else
                        {
                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                $massive_search[$s][$j][]=$suffixes[0];
                            }
                        }

                        break;
                    case "PluPe":
                        $massive_search[0][$j][0][0]=$massive_search[0][$j][0][8];
                        if($lico==3&&$chislo==3&&$pada[$j]=="A")
                        {
                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                $augment="";
                                $augment_var="|a|";
                                $count = count($massive_search[$s][$j]);
                                $last_name = $massive_search[$s][$j][$count-1][0];
                                
                                $massive_search_2=$massive_search;                                    
                                $massive_search_2[$s][$j][]=VerbCache::search_in_db(79,'endings',3);
                                    
                            }
                        }

                        for($s=0;$s<count($massive_search);$s++) 
                        {
                            $augment="";
                            $augment_var="|a|";
                            $count = count($massive_search[$s][$j]);
                            $last_name = $massive_search[$s][$j][$count-1][0];
                            $massive_search[$s][$j][]=PluPe($lico,$chislo,$pada[$j],$last_name,$tematic[$s],0);
                        }

                        if($massive_search_2)
                        {
                            $massive_search=array_merge($massive_search,$massive_search_2);
                        }

                  

                        break;
                    case "PeF":

                        //http://sanskrit/generator2.php?id=3&command=VR-pPeS-PeF&lico=1&chislo=1&pada=P BEFORE>AFTER
                        //http://sanskrit/generator2.php?id=393&command=VR-PeS-PeF&lico=1&chislo=1&pada=P AFTER>BEFORE
                        $verb=$massive_search[0][0][0];

                        $pef=PeF($id,$lico,$chislo,$pada[$j]);

                              
                        $count_forms=count($pef);

                        if($pef[0][7]==1)
                        {
                            $verb_0=str_replace("|","",$massive_search[0][$j][0][0]);
                            $verb_8=str_replace("|","",$massive_search[0][$j][0][8]);

                            if($verb_0!=$verb_8)
                            {
                                $massive_search[]=$massive_search[0];
                                $massive_search[0][$j][0][0]=$massive_search[0][$j][0][8];
                            }
                        }

                        //print_r($massive_search);
                       
                        //echo "<BR>count_forms $count_forms<BR>";

                        if($count_forms>1)
                        {
                            
                            $count_now=count($massive_search);
                             if($count_now>$count_forms)
                            {
                                $bigger=$count_now;
                                $smaller=$count_forms;
                            }
                            else
                            {
                                $bigger=$count_forms;
                                $smaller=$count_now;
                            }
 
                            $counter2=0; $counter3=0;

                            for($cf=0;$cf<$smaller;$cf++)
                            {  

                                for($cn=0;$cn<$bigger;$cn++)
                                {
                                    //echo "COUNTER2: $counter2 COUNTER3: $counter3<BR>";
                                    $massive_search_new[$counter2]=$massive_search[$counter3];
                                    $counter2++;

                                    if($count_forms<$count_now)
                                    {
                                        $counter3++;
                                    }
                                  
                                }

                                if($count_forms>$count_now)
                                {
                                    $counter3++;
                                }
                               
                                if($counter3>1)
                                {
                                    $counter3=0;
                                }
                                

                            }

   
                            $massive_search=$massive_search_new;
                            $count_now=count($massive_search);
                            $counter3=0;
                                    
                            for($cf=0;$cf<$count_now;$cf++)
                            {
                                $massive_search[$cf][$j][]=$pef[$counter3];

                                if(($massive_search[$cf][$j][0]['no_udvoenie']==0)||($pef[0][7]!=1))
                                {
                                    $massive_search[$cf][$j][0][0]=$massive_search[$cf][$j][0][8];
                                    
                                }
                                else
                                {
                                    $massive_search[$cf][$j][0]['flag_e']='';
                                }

                                $counter3++;
                                if($counter3>$count_forms-1)
                                {
                                    $counter3=0;
                                }
                            }

                        }
                        else
                        {
                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                $massive_search[$s][$j][]=$pef[0];

                                if(($massive_search[$s][$j][0]['no_udvoenie']==0)||($pef[0][7]!=1))
                                {
                                    $massive_search[$s][$j][0][0]=$massive_search[$s][$j][0][8];
                                    
                                }
                                else
                                {
                                    $massive_search[$s][$j][0]['flag_e']='';
                                }
                            }

                        }
                      

                        break;

                    case "pPeS":
                        
                        $ppes=pPeS();
                        
                        $count_forms=count($ppes);

                        if($count_forms>1)
                        {
                            $count_now=count($massive_search);
                            $counter2=0; $counter3=0;

                            for($cf=0;$cf<$count_forms;$cf++)
                            {  

                                for($cn=0;$cn<$count_now;$cn++)
                                {
                                    $massive_search[$counter2]=$massive_search[$cn];
                                    $counter2++;
                                    $counter3++;
                                    if($counter3>count($pef[0])-1)
                                    {
                                        $counter3=0;
                                    }
                                }

                            }

                            $count_now=count($massive_search);

                            for($cf=0;$cf<$count_now;$cf++)
                            {
                                $massive_search[$cf][$j][]=$ppes[$cf];
                            }

                        }
                        else
                        {

                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                $massive_search[$s][$j][]=$ppes[0];
                            }

                        }
                        break;

                    case "PS":
                        
                        //echo "MaSearch: ".count($massive_search);
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                                $massive_search[$s][$j][]=PS();
                        }
                        break;   
                
                    case "PaPrP":
                        
                            //echo "MaSearch: ".count($massive_search);
                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                    $massive_search[$s][$j][]=PaPrP();
                            }
                            break;   

                    case "PrS":
                       
                        $count_root_variants=count($massive_search);

                        for($crv=0;$crv<$count_root_variants;$crv++)
                        {

                           $prs=PrS($massive[$i-1],$verb_info[12],$massive_search[$crv][$j][0][0],$verb_info[1],$verb_info[2],$verb_info[3],$verb_info[4],$pada[$j]);
                           
                           if(!$p_before_mool)
                           {
                            $p_before_mool=$prs['p_before_mool'];
                            //print_r($p_before_mool);echo "<BR><BR>";
                           }

                           if($debug)
                           {
                            echo "Подготовленный корень: ".$massive_search[$crv][$j][0][0]." ";
                            echo $prs['debug'];
                            echo "<BR>";
                           }
 
                            $new_root=$prs[0];
                            if(count($new_root)<=1)
                            {
                                $new_root=$new_root[0];
                            }

                            $root_rule[]=$prs[2];
                            if(count($root_rule)<=1)
                            {
                                $root_rule=$root_rule[0];
                            }

                            $count_forms=count($prs[1]);
                            $count_now=count($massive_search);

                            //echo "COUNT FORMS PrS:$count_forms<BR><BR>";
                            
                            if($count_forms>1)
                            {
    
                                $count_now=count($massive_search);
                                $counter2=0; $counter3=0;
    
                                for($cf=0;$cf<$count_forms;$cf++)
                                {  
                                    for($cn=0;$cn<$count_now;$cn++)
                                    {

                                        $massive_search[$counter2]=$massive_search[$cn];

                                        //CHANGE ROOT
                                        if($new_root!="")
                                        {
                                            $massive_search[$counter2][$j][0][0]=$new_root[$counter2];
                                            $massive_search[$counter2][$j][0][15]=$root_rule[$counter2];
                                            
                                        }

                                        $massive_search[$counter2][$j][0]['p_before_mool']=$p_before_mool[$counter2];

                                        
                                        if($root_rule[$counter2]==1||$root_rule[$counter2]==4||$root_rule[$counter2]==6)
                                        {
                                            $tematic[$counter2]=1;
                                        }
                                        else
                                        {
                                            $tematic[$counter2]=0;
                                        }

                                        $lemma[$counter2]="PrS".$root_rule[$counter2];


                                        $counter2++;

                                    }
                                }
    
                                $count_now=count($massive_search);
    
                                for($cn=0;$cn<$count_now;$cn++)
                                {
                                    if($prs[1][$cn]!="")
                                    {
                                        $massive_search[$cn][$j][]=$prs[1][$cn];
                                    }

                                   // echo $prs['debug_str'][$cn]."<BR>";

                                }
    
                                
                            }
                            else
                            {
                                //echo "Count Form = 1<BR>";
                                for($s=0;$s<count($massive_search);$s++) 
                                {
                                    //CHANGE ROOT
                                    if($new_root!="")
                                    {
                                        $massive_search[$crv][$j][0][0]=$new_root;
                                        $massive_search[$crv][$j][0][15]=$root_rule[0];
                                        $massive_search[$crv][$j][0]['p_before_mool']=$p_before_mool[0];
                                    }

                                    

                                }
    
                                for($s=0;$s<count($massive_search);$s++) 
                                {
                                    if($prs[1][$s]!="")
                                    {
                                        $massive_search[$crv][$j][]=$prs[1][$s];
                                    }
                                }

                            
                                if($root_rule[0]==1||$root_rule[0]==4||$root_rule[0]==6)
                                {
                                    $tematic[0]=1;
                                }
                                else
                                {
                                    $tematic[0]=0;
                                }

                                $lemma[0]="PrS".$root_rule[0];
    
                            }
                        
                          
                        }
                            break; 

                    case "Pr":
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                          
                            $pr=Pr($lico,$chislo,$pada[$j],$tematic[$s],$lemma[$s]);
                            //echo "Присоединение окончаний (".$pr[7].")".$pr[0]."<BR>";

                            $massive_search[$s][$j][]=$pr;
                        }
                        break;
                    case "OS":
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                            $massive_search[$s][$j][]=OS($tematic[$s],$pada[$j]);
                        }
                        break;
                    case "O":
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                            $count = count($massive_search[$s][$j]);
                            $last_name = $massive_search[$s][$j][$count-1][0];
                            $massive_search[$s][$j][]=O($lico,$chislo,$pada[$j],$last_name,$tematic[$s],0);
                        }
                        break;
                    case "Im":
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                            $augment="|a|";
                            $count = count($massive_search[$s][$j]);
                            $last_name = $massive_search[$s][$j][$count-1][0];
                            $massive_search[$s][$j][]=Im($lico,$chislo,$pada[$j],$last_name,$tematic[$s],0);
                        }
                        break;
                    case "Ip":
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                            $count = count($massive_search[$s][$j]);
                            $last_name = $massive_search[$s][$j][$count-1][0];
                            $massive_search[$s][$j][]=Ip($verb,$lico,$chislo,$pada[$j],$last_name,$tematic[$s],0);
                        }
                        break;
                    case "AoIp":
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                                $count = count($massive_search[$s][$j]);
                                $last_name = $massive_search[$s][$j][$count-1][0];
                                $massive_search[$s][$j][]=AoIp($verb,$lico,$chislo,$pada[$j],$last_name,$tematic[$s],0);
                        }
                        break;
                    case "PrSbS":
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                            $massive_search[$s][$j][]=PrSbS();
                        }
                        break;

                    case "PrSb":
                        
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                            $count = count($massive_search[$s][$j]);
                            $last_name = $massive_search[$s][$j][$count-1][0];
                            $prsb=PrSb($lico,$chislo,$pada[$j],$last_name,$tematic[$s],0);
                        }


                            $count_forms=count($prsb);
                            $count_now=count($massive_search);
                            
                            if($count_forms>1)
                            {
    
                                $count_now=count($massive_search);
                                $counter2=0; $counter3=0;
                        
                                for($cf=0;$cf<$count_forms;$cf++)
                                {  
                                    for($cn=0;$cn<$count_now;$cn++)
                                    {
                                        $massive_search_2[$counter2]=$massive_search[$cn];
                                        $massive_search_2[$counter2][$j][]=$prsb[$cf];
                                        $counter2++;
                                    }
                                }

                                $massive_search=$massive_search_2;
                            }
                        
                        break;
                    case "PaPrAS":
                    
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                            $papras=PaPrAS($pada[$j],$massive_search[$s][$j]);

                            $massive_search[$s][$j][]=$papras[0];
                            $massive_search[$s][$j][count($massive_search[$s][$j])-1][15]=$papras[1];
                        }
                        break;
                    
                    case "PaPePS":

                        $verb=$massive_search[0][$j][0];
                        $papeps=PaPePS($verb);

                        $new_root=$papeps[0];
                        if(count($new_root)<=1)
                        {
                            $new_root=$new_root[0];
                        }

                        $root_rule[]=$papeps[2];
                        if(count($root_rule)<=1)
                        {
                            $root_rule=$root_rule[0];
                        }

                        $count_forms=count($papeps[1]);
                        $count_now=count($massive_search);

                    
                        

                        if($count_forms>1)
                        {

                            $count_now=count($massive_search);
                            $counter2=0; $counter3=0;

                            for($cf=0;$cf<$count_forms;$cf++)
                            {  
                                for($cn=0;$cn<$count_now;$cn++)
                                {
                                    $massive_search[$counter2]=$massive_search[$cn];

                                

                                    //CHANGE ROOT
                                    if($new_root!="")
                                    {
                                    

                                        $massive_search[$counter2][$j][0][0]=$new_root[$counter2];
                                        
                                    }
                                    $counter2++;
                                }
                            }

                            $count_now=count($massive_search);

                            for($cn=0;$cn<$count_now;$cn++)
                            {
                                if($papeps[1][$cn]!="")
                                {
                                    $massive_search[$cn][$j][]=$papeps[1][$cn];
                                    $massive_search[$cn][$j][count($massive_search[$cn][$j])-1][15]=$root_rule[0];
                                }

                            }

                        }
                        else
                        {
                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                //CHANGE ROOT
                                if($new_root!="")
                                {
                                    $massive_search[$s][$j][0][0]=$new_root;
                                    //$massive_search[$s][$j][count($massive_search[$s][$j])-1][15]=$root_rule[0];
                                }
                            }

                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                    $massive_search[$s][$j][]=$papeps[1][0];
                                    $massive_search[$s][$j][count($massive_search[$s][$j])-1][15]=$root_rule[0];
                            }

                        }
                        break;

                    case "pPaPeAS":
                        for($s=0;$s<count($massive_search);$s++) 
                        {
                            $massive_search[$s][$j][]=pPaPeAS($pada[$j]);
                        }
                        break;
                    
                    case "PaPeAS":
                        $massive_search[0][$j][0][0]=$massive_search[0][$j][0][8];
                        $papeas=PaPeAS($pada[$j]);

                        $new_root=$papeas[0];
                        if(count($new_root)<=1)
                        {
                            $new_root=$new_root[0];
                        }

                        $root_rule=$papeas[1];

                        $count_forms=count($papeas[0]);
                        $count_now=count($massive_search);

                        $verb=$massive_search[0];

                        $ryad=$massive_search[0][$j][0][4];

                        //echo "VI:".$massive_search[0][$j][0][4];
                    
                            if(($ryad=="N0"||$ryad=="N1"|$ryad=="N2"||$ryad=="M0"||$ryad=="M1"||$ryad=="M2")&&$papeas[0][0]=="uØs")
                            { 

                                $massive_search_2=$massive_search;                                    
                                
                                for($s=0;$s<count($massive_search_2);$s++) 
                                {
                                    $massive_search_2[$s][$j][]=array("i");
                                    $massive_search_2[$s][$j][]=$papeas[0][0];
                                    $massive_search_2[$s][$j][count($massive_search[$s][$j])-1][15]=$root_rule;
                                }


                                for($s=0;$s<count($massive_search);$s++) 
                                {
                                        $massive_search[$s][$j][]=$papeas[0][0];
                                        $massive_search[$s][$j][count($massive_search[$s][$j])-1][15]=$root_rule;
                                }

                                $massive_search=array_merge($massive_search,$massive_search_2);

                            }
                            else
                            {

                                for($s=0;$s<count($massive_search);$s++) 
                                {
                                        $massive_search[$s][$j][]=$papeas[0][0];
                                        $massive_search[$s][$j][count($massive_search[$s][$j])-1][15]=$root_rule;
                                }

                            }

                        break;
                    case "PaFuP":
                        //$verb=$massive_search[0][$j][0];
                        $pafup=PaFuP($massive[$i-1]);
                        $root_rule=$pafup[1];

                        $count_forms=count($pafup[0]);
                        $count_now=count($massive_search);

                        if($count_forms>1)
                        {

                            $count_now=count($massive_search);
                            $counter2=0; $counter3=0;

                            for($cf=0;$cf<$count_forms;$cf++)
                            {  
                                for($cn=0;$cn<$count_now;$cn++)
                                {
                                    $massive_search[$counter2]=$massive_search[$cn];
                                    $counter2++;
                                }
                            }

                            $count_now=count($massive_search);


                            for($cn=0;$cn<$count_now;$cn++)
                            {
                                    //echo count($massive_search[$cn][$j])."<BR><BR>";
                                    $massive_search[$cn][$j][]=$pafup[0][$cn];
                                    $massive_search[$cn][$j][count($massive_search[$cn][$j])-1][15]=$root_rule;
                            }

                        }
                        else
                        {
                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                    $massive_search[$s][$j][]=$papeps[0];
                                    $massive_search[$s][$j][count($massive_search[$s][$j])-1][15]=$root_rule;
                            }

                        }
                        break;
                
                
                    }

        }
        
        if($debug)
       {
           /*
            echo "<BR>";echo "<BR>";
            echo "TOTAL: ";

            for($t=0;$t<count($massive_search);$t++)
            {
            echo "<BR>";
            print_r($massive_search[$t]);
            echo "<BR>";

            }
            */
            //echo "<BR>------------<BR>";
            
        }
        

        $itog_massive[$j]=$massive_search;

        unset($massive_search);

    }



    $result[0]=$itog_massive[0];
    $result[1]=$itog_massive[1];

    $result['augment']=$augment; 
    $result['source']=$verb_info[0]; 
    $result['augment_var']=$augment_var; 
    $result['postgment']=$postgment; 
    //$result['p_before_mool']=$p_before_mool;

    //echo "RESULT of<BR>";
    //print_r($result);
    //echo "<BR><BR>";
    return $result;

}


function debug_table($morfems)
{

            echo '<BR><table width="10%" class="table table-bordered table-fit">';
            echo "<tr>";
            echo "<td></td>";
            for($t=0;$t<count($morfems);$t++)
            {
            
                echo "<td>".$morfems[$t][14]."</td>";
            }
            echo "</tr>";

            echo "<tr>";
            echo "<td>Morpheme</td>";
            for($t=0;$t<count($morfems);$t++)
            {
            
                echo "<td>".$morfems[$t][0]."</td>";
            }
            echo "</tr>";

            echo "<tr>";
            echo "<td>Query</td>";
            for($t=0;$t<count($morfems);$t++)
            {
            
                echo "<td>".$morfems[$t][7]."</td>";
            }
            echo "</tr>";

            echo "<tr>";
            echo "<td>Type of morpheme</td>";
            for($t=0;$t<count($morfems);$t++)
            {
            
                echo "<td>".$morfems[$t][5]."</td>";
            }
            echo "</tr>";

            
            echo "<tr>";
            echo "<td>Transformation</td>";
            for($t=0;$t<count($morfems);$t++)
            {
            
                echo "<td>".$morfems[$t][8]."</td>";
            }
            echo "</tr>";

            echo "<tr>";
            echo "<td>Special rules</td>";
            for($t=0;$t<count($morfems);$t++)
            {
            
                echo "<td>".$morfems[$t][15]."</td>";
            }
            echo "</tr>";

        

            echo "</table>";
}
?>