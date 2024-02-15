<?php
error_reporting(E_ERROR | E_PARSE);
set_time_limit(0);
include "lemmas.php";
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
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "sanskrit";

    //echo "$word,$id,$command,$lico,$chislo,$pada<BR>";

    $connection = new mysqli($servername, $username, $password, $dbname);
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
     
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
                $comment = " Сандхизируемые корни ряда A1 (кроме √uøkṣ, √śuøṣ), имеющие i или u в P, удваиваются по схеме PPA1F ";
            }
            else
            {
                $model = $p_new ."|a|". $p_mool ."|". $mool_change ."|". $f_mool;

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

                $model = $p_new . "a" . $p_mool . $mool_change . $f_mool;
                //$prefix = $p_new . "|a|";
                $comment = " ветвь 2 непустого Р, А2, схема удвоения P’aPA2F ";
            }
        } elseif ($mool_type_change == "I0" || $mool_type_change == "I1" || $mool_type_change == "I2") {
            //Корни  рядов I удваиваются по схеме P’iPIF:

            

            $model =  $p_new . "|i|".$p_mool ."|". $mool_change ."|". $f_mool;

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

                $change_later=manual_e($p_new."|u|".$p_mool."|".$mool_change,$mool_change);
         
                $comment = " ветвь 4 непустого Р, ряд U, схема удвоения P’uPUF ";
            }
        } else {
            //P’aPRF P’aPLF  P’aPNF P’aPMF

            $model = $p_new . "|a|" . $p_mool . "|". $mool_change . "|". $f_mool;
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
            $prefix[]="iy";
            $comment[]=" корень-исключеие $mool ";
        }
        else
        {
            $model[]="-";
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

    return $result;

}

function simple_sandhi($word,$mool_change,$osnova,$debug)  // Глагольные и формы и не-падежные окончания
{
    $dimensions=dimensions($word,"som","smth",0,0,0,"");
    $dimensions_array=dimensions_array($dimensions);

    $sandhi=sandhi($dimensions,$dimensions_array,$word,$mool_change,1,0,$osnova,$debug);

    return $sandhi;
}

function simple_sandhi_mool($word,$mool,$mool_change,$osnova,$debug)  // Глагольные и формы и не-падежные окончания
{
    $dimensions=dimensions($word,"som","smth",0,0,0,"");
    $dimensions_array=dimensions_array($dimensions);

    $sandhi=sandhi($dimensions,$dimensions_array,$mool,$mool_change,1,0,$osnova,$debug);

    return $sandhi;
}

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
            $comment[$c]="F = h, E2jiF";
            $c++;

        }
        elseif($mool=="i")
        {
            $model[]="iyi";
            $model[]="ayiyi";
            $comment[$c]=" корень-исключение $mool 1 форма";
            $c++;
            $comment[$c]=" корень-исключение $mool 2 форма";
            $c++;
            $stop=1;
        }
        elseif($mool=="akṣ")
        {
            $model[]="ācikṣ";
            $comment[$c]=" корень-исключение $mool";
            $c++;
        }
        elseif($mool=="ṛ")
        {
            $model[]="arir";
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
                $comment[$c]=" Если в F два согласных (F = C1C2), то корень удваивается по схеме E2FiC2";
                $c++;
            }
            else
            {
                //Остальные корни, начинающиеся на чередующийся элемент, удваиваются по схеме E2FiF:
               // $e2=get_e_mp_simple($mool_type_change, $mool_type, 2);
                $model[]=$e2."|".$f_mool."|i|".$f_mool;
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
                $comment[$c]=" корень-исключение $mool ряда $mool_type_change";
                $c++;
            }
            else
            {

                if($mool=="śak")
                {
                    $model[]="śiśik";
                    $comment[$c]=" корень-исключение $mool ряда $mool_type_change";
                    $c++;
                }
                elseif($mool=="pad")  //pipād [pipādiṣ-]  (вар.)   Во всех остальных случаях √pad ведет себя как aniṭ.
                {
                    $model[]="pipād";
                    $comment[$c]=" корень-исключение $mool ряда $mool_type_change";
                    $c++;
                }
                elseif($mool=="pat")
                {
                    $model[]="pīpat";
                    $comment[$c]=" корень-исключение $mool ряда $mool_type_change";
                    $c++;
                }

                //Корни ряда A1 удваиваются по схеме P’iPE2F:

               // $e2=get_e_mp_simple($mool_type_change, $mool_type, 2);
                $model[]=$p_new."|i|".$p_mool."|".$e2."|".$f_mool;
                $comment[$c]=" Корни ряда A1 удваиваются по схеме P’iPE2F";
                $c++;

    
            
            }

        }
        elseif ($mool_type_change == "A2") {   ///////// (F) ????

            if($mool=="dhØ̄"&&$omonim==1)
            {
                $model[]="dadh".$e1;
                $comment[$c]=" корень-исключение $mool $omonim ряда $mool_type_change";
                $c++;
            }

            if($mool=="pØ̄")
            {
                $model[]="pip".$e1;;
                $comment[$c]=" корень-исключение $mool ряда $mool_type_change";
                $c++;
            }
                //Корни ряда A2, удваиваются по схеме P’iPE2(F):

               // $e2=get_e_mp_simple($mool_type_change, $mool_type, 2);
                $model[]=$p_new."|i|".$p_mool."|".$e2."|".$f_mool;
                $comment[$c]=" Корни ряда A2, удваиваются по схеме P’iPE2(F)";
                $c++;
   
        }
        elseif ($mool_type_change == "I0" || $mool_type_change == "I1" || $mool_type_change == "I2") {

            if($is_open_mool)
            {
                //Открытые корни рядов I удваивается по схеме P’iPī:
                $model[]=$p_new."|i|".$p_mool."|ī";
                $comment[$c]=" Открытые корни рядов I удваивается по схеме P’iPī";
                $c++;
            }
            else
            {
                
                
                if($verb_setnost_des=="s"||$verb_setnost_des=="v")
                {
                    // Закрытые корни рядов I при образовании дезидератива со вставным -i- между d2√ и суффиксом (d2√)-s- (seṭ-форма) удваиваиваются по схемам P’iPE1F и P’iPE2F:
                    
                    $model[]=$p_new."|i|".$p_mool."|".$e1."|".$f_mool;
                    $comment[$c]=" Закрытые корни рядов I при образовании дезидератива со вставным -i- между d2√ и суффиксом (d2√)-s- (seṭ-форма) удваиваиваются по схемам P’iPE1F и P’iPE2F";
                    $c++;
                    $model[]=$p_new."|i|".$p_mool."|".$e2."|".$f_mool;
                    $comment[$c]=" Закрытые корни рядов I при образовании дезидератива со вставным -i- между d2√ и суффиксом (d2√)-s- (seṭ-форма) удваиваиваются по схемам P’iPE1F и P’iPE2F";
                    $c++;
                }
                elseif($verb_setnost_des=="a")
                {
                    //Закрытые корни рядов I при образовании дезидератива без вставного -i- между d2√ и суффиксом (d2√)-s- (aniṭ-форма) удваиваются по схеме P’iPE1F:

                    $model[]=$p_new."|i|".$p_mool."|".$e1."|".$f_mool;
                    $comment[$c]=" Закрытые корни рядов I при образовании дезидератива со вставным -i- между d2√ и суффиксом (d2√)-s- (seṭ-форма) удваиваиваются по схемам P’iPE1F и P’iPE2F";
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
        elseif ($mool_type_change == "U0" || $mool_type_change == "U1" || $mool_type_change == "U2") {

            if($is_open_mool)
            {
                
                //Открытые корни рядов U удваивается по схеме P’uPū:
                $model[]=$p_new."|u|".$p_mool."|ū|";
                $comment[$c]=" Открытые корни рядов U удваивается по схеме P’uPū";
                $c++;


              

            }
            else
            {
                if($verb_setnost_des=="s"||$verb_setnost_des=="v")
                {
                    // Закрытые корни рядов I при образовании дезидератива со вставным -i- между d2√ и суффиксом (d2√)-s- (seṭ-форма) удваиваиваются по схемам P’iPE1F и P’iPE2F:
                    
                    $model[]=$p_new."|u|".$p_mool."|".$e1."|".$f_mool;
                    $comment[$c]=" Закрытые корни рядов U при образовании дезидератива со вставным -i- между d2√ и суффиксом (d2√)-s- (seṭ-форма) удваиваиваются по схемам P’uPE1F и P’uPE2F";
                    $c++;
                    $model[]=$p_new."|u|".$p_mool."|".$e2."|".$f_mool;
                    $comment[$c]=" P’uPE2F";
                    $c++;
                }
                elseif($verb_setnost_des=="a")
                {
                    //Закрытые корни рядов I при образовании дезидератива без вставного -i- между d2√ и суффиксом (d2√)-s- (aniṭ-форма) удваиваются по схеме P’uPE1F:

                    $model[]=$p_new."|u|".$p_mool."|".$e1."|".$f_mool;
                    $comment[$c]=" Закрытые корни рядов U при образовании дезидератива без вставного -i- между d2√ и суффиксом (d2√)-s- (aniṭ-форма)  удваиваиваются по схеме P’uPE1F";
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
        elseif ($mool_type_change == "R0" || $mool_type_change == "R1" || $mool_type_change == "R2") {

            if($is_open_mool)
            {
                //a1.1. Открытые корни рядов R, чье P оканчивается на звук губного ряда, а также √tṝ (вар.) удваиваются по схеме P’uPūr: d2√mṛ = mu + mūr = mumūr [mumūrṣ-]
                if($mool=="tṝ")
                {
                    $model[]=$p_new."|u|".$p_mool."|ūr";
                    $comment[$c]=" корень-исключение √tṝ (вар.) удваивается по схеме P’uPūr";
                    $c++;
                }
                elseif(seeking_1_bukva($last_p_letter,0)[4]=="L")
                {
                    $model[]=$p_new."|u|".$p_mool."|ūr";
                    $comment[$c]=" Открытые корни рядов R, чье P оканчивается на звук губного ряда удваиваются по схеме P’uPūr";
                    $c++;
                }
                else
                {
                    $model[]=$p_new."|i|".$p_mool."|īr";
                    $comment[$c]=" Остальные открытые корни рядов R удваиваются по схеме: P’iPīr";
                    $c++;
                }

                if($verb_setnost_des=="s"||$verb_setnost_des=="v")
                {
                    //Открытые корни рядов R при образовании дезидератива со вставным -i- между d2√ и суффиксом (d2√)-s- (seṭ-форма) удваиваются по схеме P’iPE2:
                    //d2√jṝ = ji + jar = jijar [jijariṣ-]

                    $model[]=$p_new."|i|".$p_mool."|".$e2;
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
                   $model[]=$p_new."|i|".$p_mool."|".$e2."|".$f_mool;
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
            $comment[$c]=" Корни ряда L удваиваются по схеме P’iPE2(F)";
            $c++;


        }
        elseif ($mool_type_change == "M0" || $mool_type_change == "M1" || $mool_type_change == "M2")
        {
            //Корни рядов M удваиваются по схеме P’iPE2:
            //d2√kṣm̥ = ci + kṣam = cikṣam [cikṣaṃs- & cikṣamiṣ-]

            $model[]=$p_new."|i|".$p_mool."|".$e2;
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
                    $comment[$c]=" В рядах N некоторые корни, а именно: √sn̥̄ (вар.), √mn̥th (вар.), √vn̥̄, √rn̥dh, √mn̥d – удваиваются по схеме P’iPE1(F)";
                    $c++;
                }
                else
                {

                    if($mool=="sn̥̄"||$mool=="mn̥th")
                    {
                        //В рядах N некоторые корни, а именно: √sn̥̄ (вар.), √mn̥th (вар.), √vn̥̄, √rn̥dh, √mn̥d – удваиваются по схеме P’iPE1(F):
                        $model[]=$p_new."|i|".$p_mool."|".$e1."|".$f_mool;
                        $comment[$c]=" В рядах N некоторые корни, а именно: √sn̥̄ (вар.), √mn̥th (вар.), √vn̥̄, √rn̥dh, √mn̥d – удваиваются по схеме P’iPE1(F)";
                        $c++;
                    }

                    //Остальные корни рядов N удваиваются по схеме P’iPE2(F):
                    //d2√jn̥̄ = ji + jan = jijan [jijaniṣ-]

                    $model[]=$p_new."|i|".$p_mool."|".$e2."|".$f_mool;
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


  

    return $result;

}

function duplication_i2($array, $mool, $mool_type, $mool_type_change, $omonim, $debug){
    $p_new = $array[1];

    $p_mool = $array[3];
    $mool_change = $array[4];
    $f_mool = $array[5];
    $f_mool_array = $array[6];
    $p_mool_array = $array[7];

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
                    $comment = " Корни ряда А1 удваиваются по схеме P’āPEF";
                }
                

            }
            elseif ($mool_type_change == "A2")
            {
                    $model = $p_new."|"."ā"."|".$p_mool."|".$mool_change."|".$f_mool;
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
                    $comment = " Корни рядов U удваиваются по схеме P’oPE(F):";
                }
            }
            elseif($mool_type_change == "M0" || $mool_type_change == "M1" || $mool_type_change == "M2")
            {
                
                    $model = $p_new."|"."am"."|".$p_mool."|".$mool_change;
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
                        $model = $p_new."arī".$p_mool."|".$mool_change."|".$f_mool;
                        $comment = " Все корни рядов R могут удваиваться по схеме P’arīPE(F) (со вставной -ī-)";

                        //Некоторые корни R, а именно: √kṛṣ, √dṛ 2, √dṛś, √dṛh, √bṛh, √dhṛ, √vṛ 1,  √vṛ 2, √vṛj, √vṛt, √sṛ, √hṛ 1, √hṛ 2, √kṝ 2, √gṝ 1, √dṝ, √tṝ – также способны удваиваться по схеме P’arPE(F) (без вставной -ī-):
                        if($mool=="kṛṣ"||($mool=="dṛ"&&$omonim==2)||$mool=="dṛś"||$mool=="dṛh"||$mool=="bṛh"||$mool=="dhṛ"||($mool=="vṛ"&&$omonim==1)||($mool=="vṛ"&&$omonim==2)||$mool=="vṛj"||$mool=="vṛt"||$mool=="sṛ"||($mool=="hṛ"&&$omonim==1)||($mool=="hṛ"&&$omonim==2)||($mool=="kṝ"&&$omonim==2)||($mool=="gṝ"&&$omonim==1)||$mool=="dṝ"||$mool=="tṝ")
                        {
                            $model2 = $p_new."ar".$p_mool."|".$mool_change."|".$f_mool;
                            $comment.= " Некоторые корни R, а именно: $mool также способны удваиваться по схеме P’arPE(F) (без вставной -ī-)";
                        }

                        //Некоторые корни R, а именно: √garh, √dhṛ, √mṛṣ, √śṛ, √spṛdh, √smṛ, √hvṛ, √kṝ 1, √jṝ, √jvar √tṝ, √pṝ, √śṝ, √stṝ, √svar, √tvar, √hvṛ– также могут удваиваться по схеме P’āPE(F):
                        if($mool=="garh"||$mool=="dhṛ"||$mool=="mṛṣ"||$mool=="śṛ"||$mool=="spṛdh"||$mool=="smṛ"||$mool=="hvṛ"||($mool=="kṝ"&&$omonim==1)||$mool=="jṝ"||$mool=="jvar"||$mool=="tṝ"||$mool=="pṝ"||$mool=="śṝ"||$mool=="stṝ"||$mool=="svar"||$mool=="tvar"||$mool=="hvṛ")
                        {
                            $model2 = $p_new."ā".$p_mool."|".$mool_change."|".$f_mool;
                            $comment.= " Некоторые корни R, а именно: $mool также могут удваиваться по схеме P’āPE(F)";
                        }

                    }
            }
            elseif ($mool_type_change == "N0" || $mool_type_change == "N1" || $mool_type_change == "N2")
            {
                $model = $p_new."|an|".$p_mool."|".$mool_change."|".$f_mool;
                $comment = " Все корни рядов N могут удваиваться по схеме P’anPE(F) (без вставной -ī-)";

                if($is_open_mool==0||$mool=="phan"||$mool=="pn̥"||$mool=="vn̥̄"||$mool=="svan")
                {
                    $model2 = $p_new."|anī|".$p_mool."|".$mool_change."|".$f_mool;
                    $comment.= " Закрытые корни рядов N, а также некоторые корни, а именно: √phan, √pn̥, √vn̥̄, √svan – также могут удваиваться по схеме P’anīPE(F)";
                }

                if($is_open_mool==0||$mool=="khn̥̄"||$mool=="jn̥̄"||$mool=="sn̥̄")
                {
                    $model3 = $p_new."|ā|".$p_mool."|".$mool_change."|".$f_mool;
                    $comment.= " Закрытые корни рядов N и некоторые другие корни, а именно: √khn̥̄, √jn̥̄, √sn̥̄ – также могут удваиваться по схеме P’āPE(F)";
                }



            }


    }

    if ($debug) {
        echo "<BR>$model<BR>$comment<BR>";
    }

    $result[0]=$model;
    
    if($model2)
    {
        $result[0]= $result[0].",".$model2;
    }
    if($model3)
    {
        $result[0]=$result[0].",".$model3;
    }

    $result[1]=$comment;

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
                $change_later[]=manual_e($p_new.$e_new.$p_mool.$e,$mool_change);

                $model2=$p_new.$e_new2.$p_mool.$e.$f_mool;
                $change_later[]=manual_e($p_new.$e_new2.$p_mool.$e,$mool_change);
                $comment.=" Все корни ряда А1 могут удваиваться по схемам P’iPA1F и P’īPA1F ";
            }

            if($is_open_mool==0&&$mool_type==2)
            {
                $e_new3="|a|";
                $model3=$p_new.$e_new3.$p_mool.$e.$f_mool;
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
                $change_later[]=manual_e($p_new.$e_new.$p_mool.$e,$mool_change);

                $model2=$p_new.$e_new2.$p_mool."|a|".$f_mool;
                $change_later[]=manual_e($p_new.$e_new2.$p_mool.$e,$mool_change);

                $model3=$p_new.$e_new3.$p_mool."|a|".$f_mool;
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
                $change_later[]=manual_e($p_new.$e_new2.$p_mool.$e,$mool_change);

                $model3=$p_new.$e_new3.$p_mool.$e.$f_mool;
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
            $change_later[]=manual_e($p_new.$e_new.$p_mool.$e,$mool_change);

            $model2=$p_new.$e_new2.$p_mool.$e.$f_mool;
            $change_later[]=manual_e($p_new.$e_new2.$p_mool.$e,$mool_change);

            $comment.=" Все корни U могут удваиваться по схемам P’uPU(F) и P’ūPU(F): ";

            if($is_open_mool==1)
            {
                $model3=$p_new.$e_new3.$p_mool.$e.$f_mool;
                $change_later[]=manual_e($p_new.$e_new3.$p_mool.$e,$mool_change);

                $model4=$p_new.$e_new4.$p_mool.$e.$f_mool;
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
                    $change_later[]=manual_e($p_new.$e_new.$p_mool.$e,$mool_change);

                    $model2=$p_new.$e_new2.$p_mool.$e.$f_mool;
                    $change_later[]=manual_e($p_new.$e_new2.$p_mool.$e,$mool_change);
                    $comment.=" Закрытые корни рядов R также могут удваиваться по схемам P’aPR(F) и P’īPR(F): ";
                    
                }
                else
                {
                    $e_new="|ī|";
                    $model=$p_new.$e_new.$p_mool.$e;
                    $change_later[]=manual_e($p_new.$e_new.$p_mool.$e,$mool_change);

                    //print_r($change_later);

                    $comment.=" Открытые корни R удваиваются по схеме P’īPR ";
                }

                if($mool_type == 2)
                {
                    $e_new3="|i|";
                    $model3=$p_new.$e_new3.$p_mool.$e.$f_mool;
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
            $change_later[]=manual_e($p_new.$e_new.$p_mool.$e,$mool_change);

            $model2=$p_new.$e_new2.$p_mool.$e.$f_mool;
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
                $change_later[]=manual_e($p_new.$e_new2.$p_mool.$e,$mool_change);

                $model3=$p_new.$e_new3.$p_mool.$e.$f_mool;
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
                    $change_later[]=manual_e($p_new.$e_new.$p_mool.$e,$mool_change);

                    $model2=$p_new.$e_new2.$p_mool.$e.$f_mool;
                    $change_later[]=manual_e($p_new.$e_new2.$p_mool.$e,$mool_change);

                    $model3=$p_new.$e_new3.$p_mool.$e.$f_mool;
                    $change_later[]=manual_e($p_new.$e_new3.$p_mool.$e,$mool_change);

                    $comment.=" Корни √mn̥th (вар.), √rn̥j (вар.), √rn̥dh (вар.) удваиваются по схемам P’aPN1(F), P’iPN1(F) и P’īPN1(F) ";
                }

                if($is_open_mool==1&&$mool_type==1)
                {
                    //с. Открыте корни N I типа удваиваются по схеме P’īPN2F: a2√hn̥ = jī + ghan = jīghan [ajīghanat]
                    $e_new4="|ī|";
                    $model4=$p_new.$e_new4.$p_mool.$e.$f_mool;
                    $change_later[]=manual_e($p_new.$e_new4.$p_mool.$e,$mool_change);

                    $comment.=" Открыте корни N I типа удваиваются по схеме P’īPNF ";
                }
                else
                {
                    $e_new4="|a|";
                    $model4=$p_new.$e_new4.$p_mool.$e.$f_mool;
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

    $result['enew'][]=$e_new;
    $result['enew'][]=$e_new2;
    $result['enew'][]=$e_new3;
    $result['enew'][]=$e_new4;

    $result['change_later']=$change_later;
    $result['comment']=$comment;

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

		$duplication_pr2_model=duplication_pr2($duplication_first,$verb_name,$verb_type,$verb_ryad,$verb_omonim,$debug)[0];
		$duplication_pr2_prefix=duplication_pr2($duplication_first,$verb_name,$verb_type,$verb_ryad,$verb_omonim,0)[1];


        $model_string=implode(",",$duplication_pr2_model);

        $result=$model_string;

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

        return $result;


}

function get_intensive($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$debug)
{

    $duplication_first=duplication_first($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$debug);

    $duplication_i2=duplication_i2($duplication_first,$verb_name,$verb_type,$verb_ryad,$verb_omonim,$debug);

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
    $result_string['change_later']=$change_later;
    $result_string['flag_e']=$flag_e;


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
            $debug_text .= "Корень '$mool' $dop $mool_type типа, суффикс " . $suffix . " требует " . $suffix_ask . " морфологической позиции. Корень '$mool' в ответ отдаёт ступень типа " . $answer[$suffix_ask] . " <BR>";
        }

        $word_1 = $prefix.$mool.$postfix.$suffix;

        if ($debug&&$glagol_or_imennoy==1) {

            $debug_text .= "<br>";
            $debug_text .= "Смотрим по 4 таблице для ступени типа " . $answer[$suffix_ask] . "<BR>";
            $debug_text .= "<br>";
            $debug_text .= "До преобразования: $word_1 <BR>";
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
            $debug_text .= "Тип для второй строчки в таблице: $line_2";
            $debug_text .= "<BR>Тип для третьей строчки в таблице: ";

            if ($line_3_1 == "Ev") {
                $debug_text .= $line_3_1;
            } elseif ($line_3_2 == "Em") {
                $debug_text .= $line_3_2;
            } else {
                $debug_text .= $line_3;
            }
            $debug_text .= "$text_for_m_n<BR>";

            $debug_text .= "Ряд чередования: $mool_type_change<BR>";

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
            $debug_text .= "Чередование: $mool -> $new_word";
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
                    $debug_text .= "Выполним межрядовую трансформацию [" . $suffix_transform . "] : ";
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
                    $debug_text .= "Заменили $itog на $itog_transform<BR><BR>";
                }
            }

            $have_arrow=mb_strpos($suffix_transform,"↦");

            if($have_arrow)
            {
                $find_arrow = explode("↦", $suffix_transform);

                if ($find_arrow[0] == substr($mool_type_change, 0, 1) || $find_arrow[0] == substr($mool_type_change, 0, 1) . "Ø" || $find_arrow[0] == $mool_type_change || $find_arrow[0] == $mool_type_change . "Ø") {
                    if ($debug) {
                        $debug_text .= "Выполним межрядовую трансформацию [" . $suffix_transform . "] : ";
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
                            $debug_text .= "Заменили $itog на $itog_transform<BR>";
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
            $debug_text .= "Итог чередования (без сандхи): " . $prefix.$final_string;
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

        $sandhi = simple_sandhi($new_word_string_sandhi,$mool,"",0);
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

function rule34($mool)
{
   
    if($mool=="dah"||$mool=="dih"||$mool=="duh"||$mool=="druh"||$mool=="dṛṃh")
    {
        $plus="dh";
    }
    elseif($mool=="guh")
    {
        $plus="gh";
    }
    elseif($mool=="bn̥dh"||$mool=="bādh"||$mool=="bandh"||$mool=="budh")
    {

        $plus="bh";
    }
    else
    {
        $plus=0;
    }

    return $plus;

}


function emeno_rules($number, $array, $big_array, $word_length, $zero_number, $first_number, $second_number, $big_array_1, $mool, $glagol_or_imennoy,$last_perenos,$active_word,$right_word, $padezh) {
    
   // echo "MOOL:".$mool;

    $zero_letter = $array[$zero_number][0];
    $zero_cons = $array[$zero_number][1];
    $zero_vzryv = $array[$zero_number][2];
    $zero_where = $array[$zero_number][3];
    $zero_zvonkiy = $array[$zero_number][4];
    
    $first_letter = $array[$first_number][0];
    $first_cons = $array[$first_number][1];
    $first_vzryv = $array[$first_number][2];
    $first_where = $array[$first_number][3];
    $first_zvonkiy = $array[$first_number][4];

    $second_letter = $array[$second_number][0];
    $second_cons = $array[$second_number][1];
    $second_vzryv = $array[$second_number][2];
    $second_where = $array[$second_number][3];
    $second_zvonkiy = $array[$second_number][4];

    $third_letter = $array[$third_number][0];
    $third_cons = $array[$third_number][1];
    $third_vzryv = $array[$third_number][2];
    $third_where = $array[$third_number][3];
    $third_zvonkiy = $array[$third_number][4];

    $what_change=0;

    $consonants = ["k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s", "h"];
    $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "ai", "o", "au"];
   
    
    switch ($number) {

        case "1":
            //1 Эмено
            //print_r($big_array[6]);
  
            if ($first_letter == "a" || $first_letter == "ā") {
                switch ($second_letter) {
                    case "e":$itog[0] = "āi";
                        $count_change = 2;
                        $pravilo = 1;
                        break;
                    case "o":$itog[0] = "āu";
                        $count_change = 2;
                        $pravilo = 1;
                        break;
                    case "āi":$itog[0] = "āi";
                        $count_change = 2;
                        $pravilo = 1;
                        break;
                    case "āu":$itog[0] = "āu";
                        $count_change = 2;
                        $pravilo = 1;
                        break;
                }
                $what_change=$first_letter;
            }
            break;

        case "2":
            //2 Эмено

            if ($first_letter == "a" || $first_letter == "ā") {
                switch ($second_letter) {
                    case "i":$itog[0] = "e";
                        $count_change = 2;
                        $pravilo = 2;
                        break;
                    case "ī":$itog[0] = "e";
                        $count_change = 2;
                        $pravilo = 2;
                        break;
                    case "u":$itog[0] = "o";
                        $count_change = 2;
                        $pravilo = 2;
                        break;
                    case "ū":$itog[0] = "o";
                        $count_change = 2;
                        $pravilo = 2;
                        break;
                    case "ṛ":$itog[0] = "ar";
                        $count_change = 2;
                        $pravilo = 2;
                        break;
                    case "ṝ":$itog[0] = "ar";
                        $count_change = 2;
                        $pravilo = 2;
                        break;
                    case "ḷ":$itog[0] = "al";
                        $count_change = 2;
                        $pravilo = 2;
                        break;
                    case "ḹ":$itog[0] = "al";
                        $count_change = 2;
                        $pravilo = 2;
                        break;
                }
            }
            break;

        case "3":
            //3 Эмено
            // Тут "дифтонги перед гласной", но ai и au я здесь не учитываю. Если нужно - придется логику их парсинга менять

            if ($first_letter == "āi") {
                switch ($second_letter) {
                    case "a":$itog[0] = "āy";
                        $count_change = 1;
                        $pravilo = 3;
                        //$result[3]=$first_number+2;
                        break;
                    case "ā":$itog[0] = "āy";

                        $count_change = 1;
                        $pravilo = 3;
                        
                        break;
                    case "e":$itog[0] = "āy";
                        $count_change = 1;
                        $pravilo = 3;
                        //$result[3]=$first_number+2;
                        break;
                    case "o":$itog[0] = "āy";
                        $count_change = 1;
                        $pravilo = 3;
                        
                        break;

                    case "i":$itog[0] = "āy";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ī":$itog[0] = "āy";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "u":$itog[0] = "āy";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ū":$itog[0] = "āy";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ṛ":$itog[0] = "āy";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ṝ":$itog[0] = "āy";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ḷ":$itog[0] = "āy";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ḹ":$itog[0] = "āy";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                }
                $what_change=$first_letter;
            }


            if ($first_letter == "āu") {
                switch ($second_letter) {
                    case "a":$itog[0] = "āv";
                        $count_change = 1;
                        $pravilo = 3;
                        //$result[3]=$first_number+2;
                        break;
                    case "ā":$itog[0] = "āv";
                        $count_change = 1;
                        $pravilo = 3;
                        
                        break;
                    case "e":$itog[0] = "āv";
                        $count_change = 1;
                        $pravilo = 3;
                        //$result[3]=$first_number+2;
                        break;
                    case "o":$itog[0] = "āv";
                    case "i":$itog[0] = "āv";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ī":$itog[0] = "āv";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "u":$itog[0] = "āv";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ū":$itog[0] = "āv";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ṛ":$itog[0] = "āv";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ṝ":$itog[0] = "āv";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ḷ":$itog[0] = "āv";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ḹ":$itog[0] = "āv";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                }
                $what_change=$first_letter;
            }

            if ($first_letter == "e") {
                switch ($second_letter) {
                    case "a":$itog[0] = "ay";
                        $count_change = 1;
                        $pravilo = 3;
                        //$result[3]=$first_number+2;
                        break;
                    case "ā":$itog[0] = "ay";
                        $count_change = 1;
                        $pravilo = 3;
                        
                        break;
                    case "i":$itog[0] = "ay";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ī":$itog[0] = "ay";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "u":$itog[0] = "ay";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ū":$itog[0] = "ay";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ṛ":$itog[0] = "ay";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ṝ":$itog[0] = "ay";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ḷ":$itog[0] = "ay";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ḹ":$itog[0] = "ay";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                }
                $what_change=$first_letter;
            }

            if ($first_letter == "o") {
                switch ($second_letter) {
                    case "a":$itog[0] = "av";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ā":$itog[0] = "av";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "i":$itog[0] = "av";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ī":$itog[0] = "av";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "u":$itog[0] = "av";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ū":$itog[0] = "av";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ṛ":$itog[0] = "av";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ṝ":$itog[0] = "av";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ḷ":$itog[0] = "av";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "ḹ":$itog[0] = "av";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                }
            
                $what_change=$first_letter;
            }

            break;
        case "4":  //есть ли не односложные корни?
           
            if($second_cons=="V")
            {
            
                if($active_word==$mool)
                {
                
                    if(mb_substr($mool,-1,1)=="i"||mb_substr($mool,-1,1)=="ī")
                    {
                        $itog[0] = "iy";
                        $count_change = 1;
                        $pravilo = 4;
                    }

                    if(mb_substr($mool,-1,1)=="u"||mb_substr($mool,-1,1)=="ū")
                    {
                        $itog[0] = "uv";
                        $count_change = 1;
                        $pravilo = 4;
                    }

                }

            }
            break;
        
        case "5":
            if($first_letter=="ṝ")
            {
                if($zero_cons=="C")
                {
                   
                    if($zero_where=="L")
                    {
                        if($second_cons=="V")
                        {
                            $itog[0]="ur";
                            $count_change=1;
                            $pravilo=5;
                        }
                        else
                        {
                            $itog[0]="ūr";
                            $count_change=1;
                            $pravilo=5;
                        }
                    }
                    else
                    {
                        if($second_cons=="V")
                        {
                            $itog[0]="ir";
                            $count_change=1;
                            $pravilo=5;
                        }
                        else
                        {
                            $itog[0]="īr";
                            $count_change=1;
                            $pravilo=5;
                        }
                    }

                    $what_change=$first_letter;
                }
            }
            break;
        case "6":
            //6 Эмено

            if ($first_letter != $second_letter && $second_cons == "V") {
                
                

                switch ($first_letter) {
                    case "ṛ":
                        if($second_letter!="ṛ"&&$second_letter!="ṝ")
                        {
                            $itog[0] = "r";
                            $count_change = 1;
                            $pravilo = 6;
                        }
                        break;
                    case "i":
                        if($second_letter!="i"&&$second_letter!="ī")
                        {
                        $itog[0] = "y";
                        $count_change = 1;
                        $pravilo = 6;
                        }
                        break;
                    case "ī":
                        if($second_letter!="i"&&$second_letter!="ī")
                        {
                        $itog[0] = "y";
                        $count_change = 1;
                        $pravilo = 6;
                        }
                        break;
                    case "u":
                        if($second_letter!="u"&&$second_letter!="ū")
                        {
                        $itog[0] = "v";
                        $count_change = 1;
                        $pravilo = 6;
                        }
                        break;
                    case "ū":
               
                        if($second_letter!="u"&&$second_letter!="ū")
                        {
                        $itog[0] = "v";
                        $count_change = 1;
                        $pravilo = 6;
                        }
                        break;
                }
            }
            break;

        case "7":
            //7 Эмено

            if ($first_letter == $second_letter) {
                switch ($first_letter) {
                    case "a":
                        $itog[0] = "ā";
                        $itog[1] = "Ø";
                     
                        $count_change = 2;
                        $pravilo=7;
                        break;
                    case "i":$itog[0] = "ī";   $itog[1] = "Ø";
                        $count_change = 2;
                        $pravilo=7;
                        break;
                    case "u":$itog[0] = "ū";   $itog[1] = "Ø";
                        $count_change = 2;
                        $pravilo=7;
                        break;
                }

              


                //echo $itog[0];
            }

            switch($first_letter)
            {
                case "ā":
                    if($second_letter=="a"||$second_letter=="ā")
                    {
                        $itog[0] = "ā";   $itog[1] = "Ø";
                       
                        $count_change = 2;
                        $pravilo=7;
                    }
                    break;

                case "ī":
                    if($second_letter=="i"||$second_letter=="ī")
                    {
                        $itog[0] = "ī";   $itog[1] = "Ø";
                        $count_change = 2;
                        $pravilo=7;
                    }
                    break;

                case "ū":
                    if($second_letter=="u"||$second_letter=="ū")
                    {
                        $itog[0] = "ū";   $itog[1] = "Ø";
                        $count_change = 2;
                        $pravilo=7;
                    }
                    break;

                case "a":
                    if($second_letter=="ā")
                    {
                        $itog[0] = "ā";   $itog[1] = "Ø";
                        $count_change = 2;
                        $pravilo=7;
                    }
                    break;

                case "i":
                    if($second_letter=="ī")
                    {
                        $itog[0] = "ī";   $itog[1] = "Ø";
                        $count_change = 2;
                        $pravilo=7;
                    }
                    break;

                case "u":
                    if($second_letter=="ū")
                    {
                        $itog[0] = "ū";   $itog[1] = "Ø";
                        $count_change = 2;
                        $pravilo=7;
                    }
                    break;


                
            }

            break;

        case "8":
           
            break;
        case "9":
            
           // echo $big_array_1;
            $big_array_2=str_replace("|","",$big_array_1);
            $twosybmols=mb_substr(trim($big_array_2),strlen(trim($big_array_2))-2,2);
           // echo "FS: ".$twosybmols;

           if($twosybmols=="CC")
           {

                if($second_letter)
                {

                    //9 Эмено  
                    $i = 0;
                    $counter = 0;
                    $count_change = 0;
                    $number_ishod = $word_length;

                    while (mb_substr($big_array_1, $number_ishod, 1) != "V") {
                        $number_ishod = $word_length - $i;
                        $counter++;

                        $i++;
                        if($i>100){break;}
                    }

                    $change = $number_ishod; // Позиция 1 гласной с конца
        
                    $change++; // Тут либо С либо палка

                        if (mb_substr($big_array_1, $change, 1) == "|") 
                        {
                            $change++;
                        } // Если палка, берем еще
                    
                    $change++; // Удаляем всё кроме 1 согласной
    
                        
                    $real_change = $word_length - $change;
    
                    $length_minus_counter = $word_length - $counter + 1;

                    $length_minus_counter++; // Берем позицию после V

                    if (mb_substr($big_array_1, $length_minus_counter, 1) == "|") {
                        $length_minus_counter++;
                    } // Если натыкаемся на палку, берем дальше, теперь здесь лежит позиция 1 согласной

                    $length_minus_counter++; // Здесь позиция следующей согласной, с которой удаляем

                    if (mb_substr($big_array_1, $length_minus_counter, 1) == "|") {
                        $length_minus_counter++;
                    } // Если натыкаемся на палку, берем дальше, теперь здесь лежит позиция 1 согласной
                    //$length_minus_counter++;
            

                        if($active_word==$mool&&$zero_letter=="r"&&$first_vzryv=="T")
                        {
                        
                            $length_minus_counter++;
                            if (mb_substr($big_array_1, $length_minus_counter, 1) == "|") {
                                $length_minus_counter++;
                            } // Если на
                            //$right_word=0;
                            $pravilo = 8;

                            $count_change = $real_change-1;

                            $word_length = strlen($big_array_1);

                            if ($count_change > 0 && $length_minus_counter != $word_length) {
                                    $result[3] = $length_minus_counter;
                                    $itog[0] = "Ø";
                                    $itog[1] = "Ø";
                            }
                        }

                    
                        if($pravilo!=8)
                        {
                            $count_change = $real_change-1;

                            $word_length = strlen($big_array_1);

                            if ($count_change > 0 && $length_minus_counter != $word_length) {
                                    $result[3] = $length_minus_counter;
                                    $itog[0] = "Ø";
                                    $itog[1] = "Ø";
                                    $itog[2] = "Ø";
                                    $what_change=0;
                                    
                                        $pravilo = 9;
                                    // $result[5] = -5;
                        
                        }

                    }
                }

            }
            break;

        case "10":

            if ($zero_cons == "V" && $first_letter == "ch" && $second_cons=="V") {
                $itog[0] = "cch";
                $count_change = 1;
                $result[5] = -1;
                $pravilo = 10;
            }
            break;

        case "11":

            //11 Эмено

            if ($first_letter == "m" && ($second_letter == "v" || $second_letter == "m")) {
                $itog[0] = "n";
                $count_change = 1;
                $pravilo = 11;
            }
            break;

        case "12":
            //12 Эмено

            if ($first_letter == "s" && ($zero_vzryv == "T" && $second_vzryv == "T")) {
                $itog[0] = "Ø";
                $count_change = 1;
                $pravilo = 12;
                $result[5] = 1;
            }
            break;
        case "13":
            if($first_letter=="s"&&$padezh==1&&$second_cons=="C")
            {
                $itog[0]="ḥ";
                $count_change = 1;
                $pravilo = 13;
            }
            break;
        case "14":  //в "некоторых" формах s перед s ??
                //14 Эмено
    
                if ($first_letter == "s" && $second_letter=="s") {
                    $itog[0] = "t";
                    $count_change = 1;
                    $pravilo = 14;
                }
            break;
        case "15":

            if ($first_letter == "s" && $second_letter == "dh") {
                $itog[0] = "Ø";
                $count_change = 1;
                $pravilo = 15;
            }
            break;
        case "16":
            if($first_letter=="h"&&$second_letter=="n"&&$mool=="han")
            {
                $itog[0] = "gh";
                $count_change = 1;
                $pravilo = "16 (check!) ";
            }
            break;
        case "17":  // только для nah ? 

                if ($mool=="nah"&&(($second_cons=="C"&&$second_vzryv!="v"&&$second_vzryv!="N")||(!$second_letter&&$first_letter=="h"&&$zero_letter=="a"))) {
                    $itog[0] = "dh";
                    $count_change = 1;
                    $pravilo = 17;
                }
                break;
        case "18":  // что значит "факультативно" ? 

                if ($first_letter=="h"&&(substr($mool,0,1)=="d"||$mool=="uṣṇih"||$mool=="druh"||$mool=="snih"||$mool=="muh")&&(($second_cons=="C"&&$second_vzryv!="v"&&$second_vzryv!="N")||!$second_letter)) {
                    $itog[0] = "gh";
                    $count_change = 1;
                    $pravilo = 18;
                    if($second_zvonkiy=="V")
                    {
                        $result[4] = 2;
                    }
                }
                break;
        case "19":  
            $nochange=0;
            if ($first_letter=="h"&&(($second_cons=="C"&&$second_vzryv!="v"&&$second_vzryv!="N")||!$second_letter))  {

                if($second_letter=="t"||$second_letter=="th"||$second_letter=="dh")
                {
                    switch($zero_letter)
                    {
                        case "a":$new_letter="ā";break;
                        case "i":$new_letter="ī";break;
                        case "u":$new_letter="ū";break;
                        default:$new_letter=$zero_letter;
                    }

                    if($mool!="vah"&&$mool!="sah")
                    {
                    
                        $itog[0]=$new_letter;
                        $itog[1]="ḍh";
                        $itog[2]="Ø";
                        $nochange=1;
                    }
                    else
                    {
                        
                        $itog[0]="o";
                        $itog[1]="ḍh";
                        $itog[2]="Ø";
                        $nochange=1;
                    }

                    $count_change = 3;
                    $result[3]=$first_number-1;
                    $what_change = 0;
                    $pravilo = 19;
                }
                else  /// или в абсолютном исходе!
                {
                        if($glagol_or_imennoy==1) // допустим это будет глагольная форма, 0 - именная
                        {
                            $itog[0]="k";
                            $count_change = 1;
                            $pravilo = 19;
                        }
                        else
                        {
                            $itog[0]="ṭ";
                            $count_change = 1;
                            $pravilo = 19;
                        }

                }

                if($itog[0]&&$nochange!=1)
                {
                    if(rule34($mool))
                    {
                        $result[6]=rule34($mool);
                    }
                }

               
            
            }
            break;
        case "20": 
            if($zero_letter=="k"&&$first_letter=="ṣ"&&$mool=="jakṣ"&&(($second_cons=="C"&&$second_vzryv!="v"&&$second_vzryv!="N")||!$second_letter)) 
            {
                $itog[0]="gh";
                $itog[1]="Ø";
                $result[3]=$first_number-1;
                $count_change = 2;
                $what_change=0;
                $result[5] = 1;
                $pravilo = 20;
               
            }
            break;
        case "21":
            if($zero_letter=="k"&&$first_letter=="ṣ"&&(($second_cons=="C"&&$second_vzryv!="v"&&$second_vzryv!="N")||!$second_letter)) 
            {
               if($second_letter=="s")
               {
                    if($glagol_or_imennoy==1)
                    {
                        $itog[0]="k";$itog[1]="Ø";
                    }
                    else
                    {
                        $itog[0]="ṭ";$itog[1]="Ø";
                    }

                    $result[3]=$first_number-1;
                    $count_change = 2;
                    $result[5] = 1;
                    $pravilo = 21;
               }
               elseif($second_letter=="t")
               {
                    $itog[0]="ṣ";$itog[1]="Ø";

                    $result[3]=$first_number-1;
                    $count_change = 2;
                    $result[5] = 1;
                    $pravilo = 21;
               }
               else
               {
                    $itog[0]="ṭ";$itog[1]="Ø";

                    $result[3]=$first_number-1;
                    $count_change = 2;
                    $result[5] = 1;
                    $pravilo = 21;
               }
                   
            }
            break;
        case "22":  
                if($first_letter=="ch"&&(($second_cons=="C"&&$second_vzryv!="v"&&$second_vzryv!="N")||!$second_letter)) 
                {
                   if($second_letter=="s")
                   {
                        if($glagol_or_imennoy==1)
                        {
                            $itog[0]="k";
                        }
                        else
                        {
                            $itog[0]="ṭ";
                        }

                        $count_change = 1;
                        $pravilo = 22;
                   }
                   elseif($second_letter=="t")
                   {
                        $itog[0]="ṣ";
                        $count_change = 1;
                        $pravilo = 22;
                   }
                   else
                   {
                        $itog[0]="ṭ";
                        $count_change = 1;
                        $pravilo = 22;
                   }
                       
                }
                break;
        case "23":  
            if($zero_letter=="ś"&&$first_letter=="c"&&(($second_cons=="C"&&$second_vzryv!="v"&&$second_vzryv!="N")||!$second_letter)) 
            {
               if($second_letter=="s")
               {
                    if($glagol_or_imennoy==1)
                    {
                        $itog[0]="k";
                    }
                    else
                    {
                        $itog[0]="ṭ";
                    }

                    $result[3]=$first_number-1;
                    $count_change = 2;
                    $result[5] = 1;
                    $pravilo = 23;
               }
               elseif($second_letter=="t")
               {
                    $itog[0]="ṣ";
                    $result[3]=$first_number-1;
                    $count_change = 2;
                    $result[5] = 1;
                    $pravilo = 23;
               }
               else
               {
                    $itog[0]="ṭ";
                    $result[3]=$first_number-1;
                    $count_change = 2;
                    $result[5] = 1;
                    $pravilo = 23;
               }
                   
            }
            break;
        case "24":
         //echo "zero:$zero_letter first:$first_letter second:$second_letter<BR>";
         if($first_letter=="ṣ"&&(($second_cons=="C"&&$second_vzryv!="v"&&$second_vzryv!="N")||!$second_letter)) 
         {
            if($second_letter=="s")
            {
                 if($glagol_or_imennoy==1)
                 {
                     $itog[0]="k";
                 }
                 else
                 {
                     $itog[0]="ṭ";
                 }

                 $count_change = 1;
                 $pravilo = 24;
            }
            elseif($second_letter=="t")
            {
                 $itog[0]="ṣ";
                 $count_change = 1;
                 $pravilo = 24;
            }
            else
            {
                 $itog[0]="ṭ";
                 $count_change = 1;
                 $pravilo = 24;
            }
                
         }
         break;

        case "25":  //факультативно в naś ? ś в конечной позиции или в именных формах перед s и bh ?
          
            if($first_letter=="ś"&&(($second_cons=="C"&&$second_vzryv!="v"&&$second_vzryv!="N")||!$second_letter)) 
            {
               if($mool=="diś"||$mool=="dṛś"||$mool=="mṛś"||$mool=="spṛś"||$mool=="naś")
               {
                    if($second_letter=="s"||$second_letter=="bh")
                    {
                        $itog[0]="k";
                        $count_change = 1;
                        $pravilo = 25;
                    }
               }
               else
               {
               
                    if($second_letter=="s")
                    {
                            
                        //echo "HERE<BR><BR>";
                        if($glagol_or_imennoy==1)
                            {
                                $itog[0]="k";
                                $count_change=1;
                                $pravilo = 25;
                               // echo "HERE1<BR><BR>";
                            }
                            else
                            {
                              //  echo "HERE2 $first_number<BR><BR>";
                              //  print_r($big_array[6]);
                                $itog[0]="ṭ";
                                $count_change=1;
                                $pravilo = 25;
                            }
        
          
                    }
                    elseif($second_letter=="t")
                    {
                            $itog[0]="ṣ";
                            $count_change = 1;
                            $pravilo = 25;
                         
                    }
                    else
                    {
                            $itog[0]="ṭ";
                            $count_change = 1;
                            $pravilo = 25;
            
                    }
               }
                  // echo "Count change $count_change";
            }
            break;
        
     
        case "26":
                    if(($mool=="bhṛjj"||$mool=="bhrāj"||$mool=="mṛj"||$mool=="yaj"||$mool=="rāj"||$mool=="sṛj"||$mool=="parivrāj")&&($mool!="ṛtvij"&&$mool!="sraj"))
                    {
                        if($first_letter=="j"&&(($second_cons=="C"&&$second_vzryv!="v"&&$second_vzryv!="N")||!$second_letter))
                        {
                            if($second_letter=="s")
                            {
                                    if($glagol_or_imennoy==1)
                                    {
                                        $itog[0]="k";
                                    }
                                    else
                                    {
                                        $itog[0]="ṭ";
                                    }

                                    $count_change = 1;
                                    $pravilo = 21;
                            }
                            elseif($second_letter=="t")
                            {
                                    $itog[0]="ṣ";

                                    $count_change = 1;
                                    $pravilo = 21;
                            }
                            else
                            {
                                    $itog[0]="ṭ";

                                    $count_change = 1;
                                    $pravilo = 21;
                            }
                            
                         }
                    }
                    break;
        case "27":
                        if($zero_letter=="j"&&$first_letter=="h"&&(($second_cons=="C"&&$second_vzryv!="v"&&$second_vzryv!="N")||!$second_letter)) 
                        {
                            $itog[0]="k";
                            $count_change = 1;
                            $pravilo = 27;      
                        }
                        break;
                
        case "28":

                        if($first_letter=="c"&&(($second_cons=="C"&&$second_vzryv!="v"&&$second_vzryv!="N")||!$second_letter)) 
                        {
                           
                            $itog[0]="k";
                            $count_change = 1;
                            $pravilo = 28;  
                          
                        }
                        break;

        case "29":
                        if($first_letter=="j"&&(($second_cons=="C"&&$second_vzryv!="v"&&$second_vzryv!="N")||!$second_letter))
                        {
                            $itog[0]="g";
                            $count_change = 1;
                            $pravilo = 29;      
                        }
                        break;

        case "30":  ///Тут есть необработанное исключение - обратить внимание! Правило не привеняется к о.н.в. dadh и к дезидеративу от dhaa - класть
 
            if (($second_letter == "t" || $second_letter == "th") && ($first_letter == "gh" || $first_letter == "jh" || $first_letter == "ḍh" || $first_letter == "dh" || $first_letter == "bh")) {
                $itog[0] = $first_letter . "dh";
                $count_change = 2;
                $pravilo = 30;
                $result[4] = 1;
            }
            break;
        case "31":
            //31 Эмено
            // echo "1ZV: $first_letter : ".$first_zvonkiy." 2ZV: $second_letter : $second_zvonkiy";
           
           // if ($first_zvonkiy == "V" && $first_vzryv == "T" && (($second_zvonkiy == "D" && $second_cons == "C")||($right_word==0))) {
            if ($first_zvonkiy == "V" && $first_vzryv == "T" && (($second_zvonkiy == "D" && $second_cons == "C")||!$second_letter)) {
               // echo " FZ $first_zvonkiy $first_vzryv FL $first_letter SL $second_letter   RW: ".$right_word."<BR><BR>";
                switch ($first_letter) {
                    case "g":$itog[0] = "k";
                        $count_change = 1;
                        break;
                    case "j":$itog[0] = "c";
                        $count_change = 1;
                        break;
                    case "ḍ":$itog[0] = "ṭ";
                        $count_change = 1;
                        break;
                    case "d":$itog[0] = "t";
                        $count_change = 1;
                        break;
                    case "b":$itog[0] = "p";
                        $count_change = 1;
                        break;

                    case "gh":$itog[0] = "kh";
                        $count_change = 1;
                        break;
                    case "jh":$itog[0] = "ch";
                        $count_change = 1;
                        break;
                    case "ḍh":$itog[0] = "ṭh";
                        $count_change = 1;
                        break;
                    case "dh":$itog[0] = "th";
                        $count_change = 1;
                        break;
                    case "bh":$itog[0] = "ph";
                        $count_change = 1;
                        break;
                }

                $pravilo = 31;
            }
            break;

        case "32":

            //32 Эмено

            if ($first_zvonkiy == "D" && $first_vzryv == "T" && ($second_zvonkiy == "V" && $second_vzryv == "T")) {

                switch ($first_letter) {
                    case "k":$itog[0] = "g";
                        $count_change = 1;
                        $pravilo = 32;
                        break;
                    case "c":$itog[0] = "j";
                        $count_change = 1;
                        $pravilo = 32;
                        break;
                    case "ṭ":$itog[0] = "ḍ";
                        $count_change = 1;
                        $pravilo = 32;
                        break;
                    case "t":$itog[0] = "d";
                        $count_change = 1;
                        $pravilo = 32;
                        break;
                    case "p":$itog[0] = "b";
                        $count_change = 1;
                        $pravilo = 32;
                        break;

                    case "kh":$itog[0] = "gh";
                        $count_change = 1;
                        $pravilo = 32;
                        break;
                    case "ch":$itog[0] = "jh";
                        $count_change = 1;
                        $pravilo = 32;
                        break;
                    case "ṭh":$itog[0] = "ḍh";
                        $count_change = 1;
                        $pravilo = 32;
                        break;
                    case "th":$itog[0] = "dh";
                        $count_change = 1;
                        $pravilo = 32;
                        break;
                    case "ph":$itog[0] = "bh";
                        $count_change = 1;
                        $pravilo = 32;
                        break;
                }
            }
            break;

        case "33":
            //33 Эмено
           

            if ($second_vzryv == "T" || $second_vzryv == "S" || !$second_letter ) {

                switch ($first_letter) {
                    case "kh":$itog[0] = "k";
                        $count_change = 1;
                        $pravilo = 33;
                        break;
                    case "ch":$itog[0] = "c";
                        $count_change = 1;
                        $pravilo = 33;
                        break;
                    case "ṭh":$itog[0] = "ṭ";
                        $count_change = 1;
                        $pravilo = 33;
                        break;
                    case "th":$itog[0] = "t";
                        $count_change = 1;
                        $pravilo = 33;
                        break;
                    case "ph":$itog[0] = "p";
                        $count_change = 1;
                        $pravilo = 33;
                        break;

                    case "gh":$itog[0] = "g";
                        $count_change = 1;
                        $pravilo = 33;
                        //$result[4] = 0; // Отменяем правило 34
                        break;
                    case "jh":$itog[0] = "j";
                        $count_change = 1;
                        $pravilo = 33;
                        break;
                    case "ḍh":$itog[0] = "ḍ";
                        $count_change = 1;
                        $pravilo = 33;
                        break;
                    case "dh":$itog[0] = "d";
                        $count_change = 1;
                        $pravilo = 33;
                        break;
                    case "bh":$itog[0] = "b";
                        $count_change = 1;
                        $pravilo = 33;
                        break;
                }
            }



            if($itog[0]&&$last_perenos!=2)
            {
               
                if(rule34($mool))
                {
                    $result[6]=rule34($mool);
                }
            }
        
            break;

        
        case "34": // Добавлено отдельной функцией в правила 19 и 33
            break; 
        case "35":

            //35 Эмено

            if (($first_vzryv == "N" && $second_vzryv == "S")) {

                $itog[0] = "ṃ";
                $count_change = 1;
                $pravilo .= " 35";
            }
            break;

        case "36": //Анусвара или висарга между гласной и s не мешает замене за исключением pums,hims и еще "некоторых" - это не реализовано
            //36 Эмено 
            
            if ($second_letter == "s" && $third_letter != "r" && (($first_cons == "V" && $first_letter != "a" && $first_letter != "ā") || $first_letter == "k" || $first_letter == "r" || $first_letter == "l")) {


                $itog[0] = $first_letter;
                $itog[1] = "ṣ";
                $count_change = 2;
                $what_change = 0;
                $pravilo = 36;
            }

            if(($first_letter=="ḥ"||$first_letter=="ṃ")&&$mool!="puṃs"&&$mool!="hiṃs")
            {
                if ($second_letter == "s" && $third_letter != "r" && (($zero_cons == "V" && $zero_letter != "a" && $zero_letter != "ā") || $zero_letter == "k" || $zero_letter == "r" || $zero_letter == "l")) {

 
                    $itog[0] = $zero_letter;
                    $itog[1] = $first_letter;
                    $itog[2] = "ṣ";
                    $count_change = 3;
                    $what_change = -1;
                    $pravilo = 36;
                }
            }

            break;
        case "37":
            
            if($first_letter=="n"&&($second_vzryv=="v"||$second_vzryv=="V"||$second_vzryv=="N"))
            {
                //print_r($big_array);
               

                $active_word=mb_substr($big_array[0],0,$first_number);

                //echo $first_number." FL:".$first_letter." ZV:".$second_vzryv." AW: $active_word<BR>";
                
                $s_search=mb_strpos($active_word,"ṣ");
                $r_search=mb_strpos($active_word,"r");
                $rr_search=mb_strpos($active_word,"ṛ");
                $rrr_search=mb_strpos($active_word,"ṝ");


                $one_massive=[$s_search,$r_search,$rr_search,$rrr_search];
                $max=max($one_massive);

                //print_r($one_massive);

                if($max)
                {
                    $short=mb_substr($active_word,$max+1,mb_strlen($active_word)-$max-2);
                   

                    $flag_ok=1;
                    for($i=0;$i<mb_strlen($short);$i++)
                    {
                        $one=mb_substr($short,$i,1);
                        $vzryv=seeking_1_bukva($one,0);
                       
                        if($one!="y")
                        {
                            if($vzryv[4]=="P"||$vzryv[4]=="R"||$vzryv[4]=="D")
                            {
                                $flag_ok=0;
                            }
                        }
                
                    }

                    if($flag_ok==1)
                    {
                        $itog[0]="ṇ";
                        $count_change=1;
                        $pravilo=37;
                    }
                }


            }
            break;
        case "38":
            //38 Эмено

            if (($first_where == "R" && $first_cons == "C" && $second_where == "D" && ($second_vzryv == "T" || $second_vzryv == "N")) && !($first_letter == "r" && $second_vzryv == "T")) {
                switch ($second_letter) {
                    case "t":$itog[0] = $first_letter; $itog[1] = "ṭ";
                        $count_change = 2;
                        break;
                    case "th":$itog[0] = $first_letter; $itog[1] = "ṭh";
                        $count_change = 2;
                        break;
                    case "d":$itog[0] = $first_letter; $itog[1] = "ḍ";
                        $count_change = 2;
                        break;
                    case "dh":$itog[0] = $first_letter; $itog[1] = "ḍh";
                        $count_change = 2;
                        break;
                }

                $pravilo = 38;
            }
            break;

        case "39":
            //39 Эмено

            if ($first_vzryv == "N" && $second_vzryv == "T") {

                switch ($second_letter) {

                    case "k":$itog[0] = "ṅ";
                        $count_change = 1;
                        $pravilo = 39;
                        break;
                    case "c":$itog[0] = "ñ";
                        $count_change = 1;
                        $pravilo = 39;
                        break;
                    case "ṭ":$itog[0] = "ṇ";
                        $count_change = 1;
                        $pravilo = 39;
                        break;
                    case "t":$itog[0] = "n";
                        $count_change = 1;
                        $pravilo = 39;
                        break;
                    case "p":$itog[0] = "m";
                        $count_change = 1;
                        $pravilo = 39;
                        break;

                    case "kh":$itog[0] = "ṅ";
                        $count_change = 1;
                        $pravilo = 39;
                        break;
                    case "ch":$itog[0] = "ñ";
                        $count_change = 1;
                        $pravilo = 39;
                        break;
                    case "ṭh":$itog[0] = "ṇ";
                        $count_change = 1;
                        $pravilo = 39;
                        break;
                    case "th":$itog[0] = "n";
                        $count_change = 1;
                        $pravilo = 39;
                        break;
                    case "ph":$itog[0] = "m";
                        $count_change = 1;
                        $pravilo = 39;
                        break;

                    case "g":$itog[0] = "ṅ";
                        $count_change = 1;
                        $pravilo = 39;
                        break;
                    case "j":$itog[0] = "ñ";
                        $count_change = 1;
                        //$itog="";
                        $pravilo = 39;
                        break;
                    case "ḍ":$itog[0] = "ṇ";
                        $count_change = 1;
                        $pravilo = 39;
                        break;
                    case "d":$itog[0] = "n";
                        $count_change = 1;
                        $pravilo = 39;
                        break;
                    case "b":$itog[0] = "m";
                        $count_change = 1;
                        $pravilo = 39;
                        break;

                    case "gh":$itog[0] = "ṅ";
                        $count_change = 1;
                        $pravilo = 39;
                        break;
                    case "jh":$itog[0] = "ñ";
                        $count_change = 1;
                        $pravilo = 39;
                        break;
                    case "ḍh":$itog[0] = "ṇ";
                        $count_change = 1;
                        $pravilo = 39;
                        break;
                    case "dh":$itog[0] = "n";
                        $count_change = 1;
                        $pravilo = 39;
                        break;
                    case "bh":$itog[0] = "m";
                        $count_change = 1;
                        $pravilo = 39;
                        break;
                }
            }
            break;

        case "40":
            //40 Эмено
           
            if ($second_letter == "n" && $first_vzryv == "T" && $first_where == "P") {



                $itog[0] = $first_letter;
                $itog[1] = "ñ";
                $count_change = 2;
                $pravilo = 40;
            }
          
      
    }

    $result[0] = $itog;
    $result[1] = $count_change;
    $result[2] = $pravilo;
    if (!$result[3]) {
        $result[3] = $first_number;
    }
    if (!$result[4]) {
        $result[4] = $last_perenos;
    }
    if (!$result[5]) {
        $result[5] = 0;
    }
    if (!$result[6]) {
        $result[6] = 0;
    }

   // $result[7] = $right_word;
    $result[8] = $what_change;
    if(!$result[8])
    {
        $result[8]=0;
    }
    
  
  
    

    return $result;

    
}

function sandhi($big_array, $array, $new_word, $mool, $glagol_or_imennoy, $padezh, $osnova, $debug) {


    if($osnova=="DeS")
    {
        $array_rules = array(1, 2, 3, 4, 5, 6, 7, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40);
    }
    else
    {
        $array_rules = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40);
    }


    $result[3] = count($array_rules);

    if (!$result[0]) {
        $result[0] = $new_word;
    }

    $big_array = dimensions($result[0], "something", "smth", 0, 0, 0,"");
    $array = dimensions_array($big_array);

  
   

    if ($debug) {
        echo $big_array[1] . "<BR>";
    }

    $offset = 0;
    $allpos_cons = array();
    while (($pos = mb_strpos($big_array[1], '|', $offset)) !== false) {
        $offset = $pos + 1;
        $allpos_cons[] = $pos - 1;
    }
    $allpos_cons[] = mb_strlen($big_array[1])-1;

    //////////////////////////////////////

    for ($i = count($allpos_cons) - 1; $i >= 0; $i--) 
    {
        $l1=mb_substr($big_array[1],$allpos_cons[$i],1);
        $l2=mb_substr($big_array[1],$allpos_cons[$i]+2,1);
        $l1l2=$l1.$l2;
        if($l1l2=="VV")
        {
            $vv_allpos_cons[]=$allpos_cons[$i];
        }
    }

    for ($i = 0; $i <count($allpos_cons); $i++) 
    {
        $l1=mb_substr($big_array[1],$allpos_cons[$i],1);
        $l2=mb_substr($big_array[1],$allpos_cons[$i]+2,1);
        $l1l2=$l1.$l2;
        if($l1l2!="VV")
        {
            $other_allpos_cons[]=$allpos_cons[$i];
        }
    }


    if($vv_allpos_cons)
    {
        $allpos_cons=array_merge($vv_allpos_cons,$other_allpos_cons);
    }
    else
    {
        $allpos_cons=$other_allpos_cons; 
    }

  //////////////////////////////////////

    if ($debug) {
        echo "Все вхождения стыков |: ";
        print_r($allpos_cons);
        echo "<BR><BR>";
    }
        $string=implode($big_array[6]);
        $parts=explode("|",$string);


    $k = 1;
    $emeno[5]=0;
    $emeno[6]=0;

    for ($i = 0; $i <count($allpos_cons); $i++) 
    //for ($i = count($allpos_cons) - 1; $i >= 0; $i--)
    {
        if ($debug) {
            echo "<BR>Разбор сандхи №$k скопление гласных с конца, остальные с начала. Вхождение стыка:".$allpos_cons[$i].". Исходная строчка: <b>" . $result[0] . "</b><BR>";
        }

        $refresh = 0;

        $word_length = count($array);

        $itog = "";
        $count_change = 0;
        $what_change = 0;

        $position_number = $allpos_cons[$i] - $emeno[5];

        for ($j = 0; $j < count($array_rules); $j++) {    //Пробуем применить последовательно все правила сандхи из $array_rules

            $sdvig=0;
            if($emeno[5]!=0&&$sdvig==0)
            {
                $sdvig=$emeno[5];
                
            }

            $noperenos=$emeno[4];
                
            $second_number = $position_number + 1;
            $second_letter = $array[$second_number][0];

            if ($second_letter == "|") {
                $second_number = $second_number + 1;
                
            }

            $third_number = $second_number + 1;
            $third_letter = $array[$third_number][0];

            if ($third_letter == "|") {
                $third_number = $third_number + 1;
      
            }

            $zero_number = $position_number - 1;
            $zero_letter = $array[$zero_number][0];

            if ($zero_letter == "|") {
                $zero_number = $zero_number - 1;
        
            }

           // echo "Sdvig: ".$sdvig."<BR>";
            $zero_number = $zero_number-$sdvig;
            $position_number=$position_number-$sdvig;
            $second_number=$second_number-$sdvig;
            $third_number=$third_number-$sdvig;

            $active_word=$parts[$i];

            $right_word=$parts[$i+1];


            $emeno = emeno_rules($array_rules[$j], $array, $big_array, $word_length, $zero_number, $position_number, $second_number, $big_array[1], $mool, $glagol_or_imennoy,$noperenos,$active_word,$right_word,$padezh);

            

            if ($emeno[2]) {

                if ($debug) {
                    echo "<BR>На входе: <b>" . $result[0] . "</b><BR>";
                    echo "Что поменяется: ".$emeno[8]." На что поменяется: ".$emeno[0][0]."<BR>";
                }

                $length_whatchange=mb_strlen($emeno[8]);
                $length_nawhatchange=mb_strlen($emeno[0][0]);
                $popravka=$length_nawhatchange-$length_whatchange;
                ///echo "POPR: ".$popravka;

               // print_r($allpos_cons);
                for($len=0;$len<count($allpos_cons);$len++)
                {
                     $allpos_cons[$len]=$allpos_cons[$len]+$popravka;
                }
               // print_r($allpos_cons);
               

                $result[0] = sandhi_reconstruct($emeno[3], $result[0], $emeno[0], $emeno[1], count($array), $debug, $big_array, $emeno[8]);
                $result[0] = str_replace("Ø", "", $result[0]);

      

                if($emeno[6]&&!$noperenos)
                {
                    $f_l=substr($result[0],0,1);
                    if($f_l!="|")
                    {
                        $result[0] = $emeno[6].substr($result[0],1,strlen($result[0])-1);
                    }
                    else
                    {
                        $result[0] = $emeno[6].substr($result[0],2,strlen($result[0])-2);
                    }
                   
                }

                $result[1] = $result[1] . " " . $emeno[2];

                if($emeno[6]&&!$noperenos)
                {
                    $result[1] = $result[1] . " " . "34";
                }

                if ($debug) {
                    echo "<BR>На выходе: <b>" . $result[0] . "</b><BR>";
                }
                if ($debug) {
                    echo "<BR>Применили правила: <b>" . $result[1] . "</b><BR><BR>";
                }


                $big_array = dimensions($result[0], "something", "smth", 0, 0, 0,"");
                $array = dimensions_array($big_array);
            }

          
        }

        $k++;
    }

    $result[0] = str_replace("|", "", $result[0]);
    $result[0] = str_replace("|", "", $result[0]);
    return $result;
}

function test_sandhi($test_word,$test_mool,$test_glagol_or_imennoy,$padezh,$debug)
{
    if($debug)
    {
        echo "<BR>";
        echo "Тест сандхи (без чередований!):<BR>";
        echo "$test_word<BR>";
    }


 

    $dimensions_test=dimensions($test_word,"som","smth",0,0,0,"");

    $dimensions_test_array=dimensions_array($dimensions_test);


    $sandhi=sandhi($dimensions_test,$dimensions_test_array,$test_word,$test_mool,$test_glagol_or_imennoy,$padezh,"",$debug);

    if($debug)
    {

    echo "<BR><b>Тест сандхи (знаем ".$sandhi[3]." правил для внутренних из 40): ".$sandhi[0]; echo "</b> ";
    if($sandhi[1])
    {
    echo "<BR>Применили правила Эмено: ";
    echo $sandhi[1];
    }
    echo "<BR><HR>";

    }

    return $sandhi;


}

function sandhi_reconstruct($find_cc, $enter_text, $itog, $count_change, $count, $debug, $big_array, $what_change) {
    
    if ($debug) {
        echo "Номер вхождения: " . $find_cc . " На что меняется: ".$itog[0]."  Сколько символов поменяется: $count_change<BR>";
    }

    if ($itog) {

        $find_cc=$find_cc+$what_change;

        if($big_array[6][$find_cc]=="|")
        {
            $find_cc--;
        }

        
        $big_array[6][$find_cc]=$itog[0];

        if($count_change==2)
        {
            $next_cc=$find_cc+$count_change-1;
            if($big_array[6][$next_cc]=="|")
            {
                $next_cc++;
            }

            $big_array[6][$next_cc]=$itog[1];
        }

       

        if($count_change==3)
        {
            $next_cc=$find_cc+$count_change-2;
            if($big_array[6][$next_cc]=="|")
            {
                $next_cc++;
            }

            $big_array[6][$next_cc]=$itog[1];

            $next_cc++;

            if($big_array[6][$next_cc]=="|")
            {
                $next_cc++;
            }

            $big_array[6][$next_cc]=$itog[2];

        }

        $flag=0;
        for ($i = 0; $i < $count; $i++) {
            
           
            
            if($big_array[6][$i]!="Ø")
            {
                $text.= $big_array[6][$i];
            }
        }

        //$temp=

      /*

        for ($i = 0; $i < $find_cc; $i++) {
            $text1 .= $big_array[6][$i];
        }

        if ($itog == "Ø") {
            $text2 = "";
        } else {
            $text2 = $itog;
        }


        for ($i = $find_cc + $count_change; $i < $count; $i++) {
            $text3 .= $big_array[6][$i];
        }

        $text = $text1 . $text2 . $text3;

       */


        
        $result = $text;
    } else {
        $result = $enter_text;
    }

    return $result;
}

////CHEREDOVATEL3000////

function cheredovatel($id,$massive_search,$augment,$postgment,$source,$debug)
{
    
    $FLAG_STOP=0;$no_stop=1;


    $info_massive_before[0]=search_in_db($id,'verbs',1);
    $verb_setnost=$info_massive_before[0][6];
    $verb_ryad=$info_massive_before[0][4];

    for($i=0;$i<count($massive_search);$i++)
    {
        $info_massive_before[$i+1]=$massive_search[$i];
    }
       
    $no_stop=1;
    //echo "<BR><BR>";
    //print_r($massive_search);
    for($i=0;$i<count($massive_search);$i++)
    {
       // echo "<HR>MSI0: ".$massive_search[0][0]."<HR>";
        if(($massive_search[$i][0]==""&&!$massive_search[$i]['endings'])||(!$massive_search[$i][0]&&$massive_search[$i]['endings']==""))
        {
          // echo "I:$i<BR>";
            $no_stop=$no_stop*0;

        }
    }



    //print_r($massive_search);
    $j=0;
    for($i=0;$i<count($massive_search);$i++)
    {
        if($massive_search[$i][0]=="|"&&$massive_search[$i][7]=="-")
        {

        }
        else
        {
            $massive_search_new[]=$massive_search[$i];
        }
    
    }
    $massive_search=$massive_search_new;

  //  echo "NO STOP: $no_stop<BR>";

    if($no_stop=="0"){$FLAG_STOP=1;}

    if(!$FLAG_STOP)
    {

        $combine=combine_massives($massive_search);

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

            $debug=$debug; 
            //$e_manual=''; 

            $suffix_steam=$combine[$i][1][9];

            if(!$stop&&!$flag_e)
            {
                //echo "FIRST AGAIINNN";
               
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

                //echo "<HR>".$change_later[1]."<HR>";

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

    }

   

    $ch=count($combine_word_sandhi);
    for($i=0;$i<count($massive_search);$i++)
    {
        
        $info_massive[$i]=$massive_search[$i];
        if($combine_word_sandhi[$ch-1])
        {
            $info_massive[$i][0]=$combine_word_sandhi[$ch-1];
        }
        $ch--;
    }


    //////////////////////SETNOST/////////////////////

    //DELETE INVISIBLE SUFFIXIES
    $info_massive_origin=$info_massive;
    $j=0;
    for($i=0;$i<count($info_massive);$i++)
    {
        if($info_massive[$i][0]!="|")
        {
            $info_massive_new[]=$info_massive[$i];

        }
        else
        {
            //$info_massive[$i][15]=$info_massive[$i][15];
            //echo $info_massive[$i][15];
        }
    
    }

  
    $info_massive=$info_massive_new;

    $something=combine_massives($info_massive_new);

    for($i=0;$i<count($something);$i++)
    {
        $first=$something[$i][0][0];
        $first_setnost=$something[$i][0][6];
        $second=$something[$i][1][0];
        $suffix_steam=$something[$i][1][9];
        $suffix_ask=$something[$i][1][7];
        $first_is_suffix=$something[$i][0][5];
        $second_is_suffix=$something[$i][1][5];
        $special_rules=$something[0][0][15];
        

        echo "<BR>";
        print_r($something);
        //echo $source;
        //echo "<BR>".$something[0][0][5];
        //echo "<BR>".$something[0][1][5];

        $set=Setnost($first,$second,$first_is_suffix,$second_is_suffix,$first_setnost,$suffix_steam,$suffix_ask,$special_rules,$source)['setnost'];

        //echo "SET: ".$set;

        if($set=="s"||$set=="ss")
        {
            for($j=0;$j<count($info_massive);$j++)
            {
                if($info_massive[$j][0]==$first&&$info_massive[$j+1][0]==$second)
                {
                    $anit_massive[]=$j;
                }
   
            }
       
           
        }

       

        if($set=="v"||$set=="s"||$set=="ss"||$set=="vv")
        {
            for($j=0;$j<count($info_massive);$j++)
            {
                if($info_massive[$j][0]==$first&&$info_massive[$j+1][0]==$second)
                {
                    $set_massive[]=$j;
                }
   
            }
       
           
        }

       // echo "<HR>$set<HR>";

        if($set=="STOP")
        {
            $FLAG_STOP=1;
        }

    }
 
    for($k=0;$k<count($info_massive);$k++)
    {
        $flag=0;
        for($j=count($set_massive)-1;$j>=0;$j--)
        {
                if($k==$set_massive[$j]) 
                {
                    $flag=1;
                }
        }
        
        $info_massive_set[]=$info_massive[$k];
        if($flag==1)
        {
            if($set=="ss")
            {
                $info_massive_set[][0]="|ī|";
            }
            elseif($set=="vv")
            {
                $info_massive_set[][0]="|ī|";
            }
            else
            {
                $info_massive_set[][0]="|i|";
            }
        }

    }

    for($k=0;$k<count($info_massive);$k++)
    {
        $flag=0;
        for($j=count($anit_massive)-1;$j>=0;$j--)
        {
                if($k==$set_massive[$j]) 
                {
                    $flag=1;
                }
        }
        
        $info_massive_anit[]=$info_massive[$k];
        if($flag==1)
        {
            if($set=="ss")
            {
                $info_massive_anit[][0]="|ī|";
            }
            elseif($set=="vv")
            {
                $info_massive_anit[][0]="|ī|";
            }
            else
            {
                $info_massive_anit[][0]="|i|";
            }
        }

    }
    $s++;
    
    /////////////////////////DELETE///////////////////
    
    
    $info_massive_set=Change_in_Suffixes($info_massive_before,$info_massive_set)[0];


    $info_massive_set_rgveda=Change_in_Suffixes($info_massive_before,$info_massive_set)[1];
    $comments_set=Change_in_Suffixes($info_massive_before,$info_massive_set)[2];
    

 
    $info_massive_anit=Change_in_Suffixes($info_massive_before,$info_massive_anit)[0];
    $info_massive_anit_rgveda=Change_in_Suffixes($info_massive_before,$info_massive_anit)[1];
    $comments_anit=Change_in_Suffixes($info_massive_before,$info_massive_anit)[2];
  /////
 //echo "<HR>FLAG_STOP:$FLAG_STOP<hr>";

    //////////////////////SANDHI/////////////////////
    if(!$FLAG_STOP)
    {

        $sandhi_string_set=Sandhi_String_From_Massive($info_massive_set,$augment,$postgment);
        $sandhi_string_anit=Sandhi_String_From_Massive($info_massive_anit,$augment,$postgment);
        $sandhi_string_set_rgveda=Sandhi_String_From_Massive($info_massive_set_rgveda,$augment,$postgment);
        $sandhi_string_anit_rgveda=Sandhi_String_From_Massive($info_massive_anit_rgveda,$augment,$postgment);

        //echo "sdfsdf".$sandhi_string_set;

        $itog1=simple_sandhi($sandhi_string_set,$info_massive_before[0][0],"",0)[0];
        $itog1_emeno=simple_sandhi($sandhi_string_set,$info_massive_before[0][0],"",0)[1];

        $itog2=simple_sandhi($sandhi_string_anit,$info_massive_before[0][0],"",0)[0];
        $itog2_emeno=simple_sandhi($sandhi_string_anit,$info_massive_before[0][0],"",0)[1];

        $itog3=simple_sandhi($sandhi_string_set_rgveda,$info_massive_before[0][0],"",0)[0];
        $itog3_emeno=simple_sandhi($sandhi_string_set_rgveda,$info_massive_before[0][0],"",0)[1];

        $itog4=simple_sandhi($sandhi_string_anit_rgveda,$info_massive_before[0][0],"",0)[0];
        $itog4_emeno=simple_sandhi($sandhi_string_anit_rgveda,$info_massive_before[0][0],"",0)[1];

        if($itog1==$itog2)
        {
            $result[0][0]=$sandhi_string_set;
            $result[0][1]=$itog1;
            $result[0][2]=$itog1_emeno;

          
        }
        else
        {
            $result[0][0]=$sandhi_string_set;
            $result[0][1]=$itog1;
            $result[0][2]=$itog1_emeno;

            $result[1][0]=$sandhi_string_anit;
            $result[1][1]=$itog2;
            $result[1][2]=$itog2_emeno;

            
        }

        
        if($itog3||$itog4)
        {

            if($itog3==$itog4)
            {
                $result[2][0]=$sandhi_string_set_rgveda;
                $result[2][1]=$itog3;
                $result[2][2]=$itog3_emeno;

            
            }
            else
            {
                $result[2][0]=$sandhi_string_set_rgveda;
                $result[2][1]=$itog3;
                $result[2][2]=$itog3_emeno;

                $result[3][0]=$sandhi_string_anit_rgveda;
                $result[3][1]=$itog4;
                $result[3][2]=$itog4_emeno;

                
            }

        }

    }
    else
    {
            $result[0]="STOP";
    }

       // $result[2]=$combine_word_nosandhi;
        $result[4][0]=$info_massive_before;
        $result[4][1]=$info_massive;
        $result[4][2]=$info_massive_set;
        $result[4][3]=$info_massive_anit;
        $result[4][4]=$info_massive_set_rgveda;
        $result[4][5]=$info_massive_anit_rgveda;
        $result[4][6]=$comments_set;
        $result[4][7]=$comments_anit;
        $result['string']=$str;
        return $result;

        

}

function check_setnost($mool,$suffix,$is_suffix,$second_is_suffix,$special_rules,$source,$suffix_steam)
{
    
    $dimensions_mool = dimensions($mool, "smth", "somenthing", 1, 0, 0,"");  //ai & au будут отображаться отдельными символами
    $dimensions_mool_array = dimensions_array($dimensions_mool);

    //print_r($dimensions_mool_array);
    //echo "<BR>";

    $dlina_kornya = strlen($dimensions_mool[1]);
    //echo "DLINA: $dlina_kornya<BR>";

    if($dimensions_mool_array[$dlina_kornya - 1][0]=="|")
    {
        if($dimensions_mool_array[$dlina_kornya - 2][0]=="|")
        {
            $sdvig=3;
        }
        else
        {
            $sdvig=2;
        }
    }
    else
    {
        $sdvig=1;
    }

    $mool_last_letter = $dimensions_mool_array[$dlina_kornya - $sdvig][0];    
    $mool_last_letter2 = $dimensions_mool_array[$dlina_kornya - $sdvig-1][0];
    $mool_last_cons = $dimensions_mool_array[$dlina_kornya - $sdvig][1];     
    $mool_last_vzryv = $dimensions_mool_array[$dlina_kornya - $sdvig][2];
    $seek_last_letter = seeking_1_bukva($mool_last_letter, 0);

    //Сетность

    //echo "<BR>MOOL LAST LETTER".$mool_last_letter." cons:".$mool_last_cons."<BR>";

    //Суффиксы сетные, если оканчиваются на согласную

    //echo "<BR>IS SUFFIX $second_is_suffix source $source SUFFIX $suffix<BR>";

    if ($seek_last_letter[1] == "C" || $mool_last_cons == "C" || ($is_suffix==3&&($mool_last_letter == "e" || $mool_last_letter == "o" || $mool_last_letter == "āu" 
    || $mool_last_letter == "āi" || ($mool_last_letter2 == "a" && $mool_last_letter == "u") || ($mool_last_letter2 == "a" && $mool_last_letter == "i")))) 
    {       
         
        
        $first_letter_suffix = mb_substr($suffix, 0, 1);

        //echo "Mool last:$mool_last_letter Mool last2:$mool_last_letter2 Suff:$first_letter_suffix<BR><BR>";

        $FLAG_NEED_SET = 0;
        if ($first_letter_suffix == "s" || $first_letter_suffix == "t") {
            $FLAG_NEED_SET = 1;
        }

        //if($verb_setnost=="0"){$verb_setnost=" <b>в языке такой формы не встречается</b>";}
        
    }

    //PeF
    //-i- перед (1)-se : √jn̥̄, √tn̥, √uøh, √bhṛ, √bn̥dh, √uøc, √śap, √sah, √uøp 1, 2
    if(($source=="jn̥̄"||$source=="tn̥"||$source=="uØh"||$source=="bhṛ"||$source=="bn̥dh"||$source=="uØc"||$source=="śap"||$source=="sah"||($source=="uØp"&&$omonim==1)||($source=="uØp"&&$omonim==2))
    &&($suffix=="se")
    &&$second_is_suffix==3
    &&$suffix_steam=="PeF")
    {
        $FLAG_NEED_SET_ENDINGS = 1;
    }



    //-i- перед (2)-tha : √uøkṣ, √øs, √ruh, √ruj, √kṛt 1, 2, √kṣip, √tṛd, √vid 2, √viś, √sṛj, √rudh 1, 2, √duh, √druh, √ṛ
    if(($source=="uØkṣ"||$source=="Øs"||$source=="ruh"||$source=="ruj"||($source=="kṛt"&&$omonim==1)||($source=="kṛt"&&$omonim==2)||$source=="kṣip"||$source=="tṛd"
    ||($source=="vid"&&$omonim==2)||$source=="viś"||$source=="sṛj"||($source=="rudh"&&$omonim==1)||($source=="rudh"&&$omonim==2)||$source=="duh"||$source=="druh"
    ||$source=="ṛ")
    &&($suffix=="tha")
    &&$second_is_suffix==3
    &&$suffix_steam=="PeF")
    {
        $FLAG_NEED_SET_ENDINGS = 1;
    }

     //-i- перед (2)-tha : √bhū (вар.), √uøc (вар.)
     if(($source=="bhū"||$source=="uØc")
    &&($suffix=="tha")
    &&$second_is_suffix==3
    &&$suffix_steam=="PeF")
    {
        $FLAG_NEED_SET_ENDINGS_VAR = 1;
    }

    //-i- перед (1)-va, (1)-vahe, (1)-ma, (1)-mahe : √uøc, √sad, √bhū, √uød, √sac, √yup, √øs, √cṝ, √pat, √hn̥, √uøs, √ym̥, √dāś, √śak, √vand, √hiṃs, √ṛ, √sūd
    if(($source=="uØc"||$source=="sad"||$source=="bhū"||$source=="uØd"||$source=="sac"||$source=="yup"||$source=="Øs"||$source=="cṝ"||$source=="pat"
    ||$source=="hn̥"||$source=="uØs"||$source=="ym̥"||$source=="dāś"||$source=="śak"||$source=="vand"||$source=="hiṃs"||$source=="ṛ"||$source=="sūd")
    &&($suffix=="va"||$suffix=="vahe"||$suffix=="ma"||$suffix=="mahe")
    &&$second_is_suffix==3
    &&$suffix_steam=="PeF")
    {
        $FLAG_NEED_SET_ENDINGS = 1;
    }


    //PrS 2
    //√an, √rud, √vm̥̄, √śuc, √śuøṣ, √suøp •	-i- перед (2)-mi, (2)-si, (2)-ti : 
    if(($source=="an"||$source=="rud"||$source=="vm̥̄"||$source=="suØp"||$source=="śuØṣ")
    &&($suffix=="mi"||$suffix=="si"||$suffix=="ti")
    &&$second_is_suffix==3
    &&$special_rules==2)
    {
        $FLAG_NEED_SET_ENDINGS = 1;
    }

    //√īś (вар.), √jakṣ (вар.), √ym̥ (вар.) •	-i- перед (2)-mi, (2)-si, (2)-ti : 
    if(($source=="īś"||$source=="jakṣ"||$source=="ym̥")
    &&($suffix=="mi"||$suffix=="si"||$suffix=="ti")
    &&$second_is_suffix==3
    &&$special_rules==2)
    {
           $FLAG_NEED_SET_ENDINGS_VAR = 1;
    }

    //•	-i- перед (1)-sva : √īḍ, √īś, √jn̥̄, √vas, √śam
    if(($source=="īḍ"||$source=="īś"||$source=="jn̥̄"||$source=="vas"||$source=="śam")
    &&($suffix=="sva")
    &&$second_is_suffix==3
    &&$special_rules==2)
    {
        $FLAG_NEED_SET_ENDINGS = 1;
    }

    //•	-i- перед (1)-hi, (2)-tu : √rud, √śnath, √stan
    if(($source=="rud"||$source=="śnath"||$source=="stan")
    &&($suffix=="hi"||$suffix=="tu")
    &&$second_is_suffix==3
    &&$special_rules==2)
    {
        $FLAG_NEED_SET_ENDINGS = 1;
    }

     //•	-i- перед (1)-hi, (2)-tu : √suøp (вар.)
     if(($source=="suØp")
     &&($suffix=="hi"||$suffix=="tu")
     &&$second_is_suffix==3
     &&$special_rules==2)
     {
         $FLAG_NEED_SET_ENDINGS_VAR = 1;
     }


     //•	-ī- перед (2)-mi, (2)-si, (2)-ti : √m̥̄, √tu, √brū, √ru 1, √stu
     if(($source=="m̥̄"||$source=="tu"||($source=="ru"&&$omonim==1)||$source=="brū"||$source=="stu")
     &&($suffix=="mi"||$suffix=="si"||$suffix=="ti")
     &&$second_is_suffix==3
     &&$special_rules==2)
     {
         $FLAG_NEED_SET_ENDINGS_LONG = 1;
     }
 

    //√an, √m̥̄, √kṝ 2, √brū, √ras, √rud, √vm̥̄, √śuøṣ
    if(($source=="an"||$source=="m̥̄"||($source=="kṝ"&&$omonim==2)||$source=="brū"||$source=="ras"||$source=="rud"||$source=="vm̥̄"||$source=="śuØṣ")
    &&($suffix=="t"||$suffix=="s")
    &&$second_is_suffix==3
    &&$special_rules==2)
    {
        $FLAG_NEED_SET_ENDINGS_LONG = 1;
    }

    //√øs (вар.)
    if(($source=="Øs")&&($suffix=="t"||$suffix=="s")&&$second_is_suffix==3&&$special_rules==2)
    {
        $FLAG_NEED_SET_ENDINGS_LONG_VAR = 1;
    }

    //•	-ī- перед (1)-sva : √m̥̄, √śam
    if(($source=="m̥̄"||$source=="śam")
    &&($suffix=="sva")
    &&$second_is_suffix==3
    &&$special_rules==2)
    {
        $FLAG_NEED_SET_ENDINGS_LONG = 1;
    }

    //•	-ī- перед (2)-hi, (2)-tu : √brū
    if(($source=="brū")
    &&($suffix=="hi"||$suffix=="tu")
    &&$second_is_suffix==3
    &&$special_rules==2)
    {
        $FLAG_NEED_SET_ENDINGS_LONG = 1;
    }

    $result['need_verb_setnost']=$FLAG_NEED_SET;
    $result['need_ending_setnost_long_var']=$FLAG_NEED_SET_ENDINGS_LONG_VAR;
    $result['need_ending_setnost_long']=$FLAG_NEED_SET_ENDINGS_LONG;
    $result['need_ending_setnost']=$FLAG_NEED_SET_ENDINGS;
    $result['need_ending_setnost_var']=$FLAG_NEED_SET_ENDINGS_VAR;
    return $result;
}

function setnost_letter($mool,$suffix,$is_suffix,$second_is_suffix,$verb_setnost,$suffix_steam,$query,$special_rules,$source)
{
  
   $check_setnost=check_setnost($mool,$suffix,$is_suffix,$second_is_suffix,$special_rules,$source,$suffix_steam);
      
    if($check_setnost['need_verb_setnost'])
    {
        if($verb_setnost=="0")
        {
            $setnost="STOP";
        }
        else
        {

            if($verb_setnost!="s"&&$verb_setnost!="a"&&$verb_setnost!="v")
            {
                
                switch($suffix_steam)
                {
                    case "FuS":
                        switch($verb_setnost)
                        {
                            case "v1":
                                $setnost="v";
                                break;

                            case "v2":
                                $setnost="s";
                                break;

                            case "v3":
                                $setnost="s";
                                break;

                            case "v4":
                                $setnost="v";
                                break;

                            case "v5":
                                if($query==1)
                                {
                                    $setnost="a";
                                }
                                elseif($query==2)
                                {
                                    $setnost="s";
                                }
                                break;
            
                        }
                        break;
                    case "DS":
                        switch($verb_setnost)
                        {
                            case "v1":
                                    $setnost="v";
                                    break;
        
                            case "v2":
                                    $setnost="s";
                                    break;
        
                            case "v3":
                                    $setnost="s";
                                    break;
        
                            case "v4":
                                    $setnost="a";
                                    break;
        
                            case "v5":
                                if($query==1)
                                {
                                    $setnost="a";
                                }
                                elseif($query==2)
                                {
                                    $setnost="s";
                                }
                                    break;
                
                        }
                        break;

                    case "PaPePS":
                        switch($verb_setnost)
                        {
                                case "v1":
                                        $setnost="a";
                                        break;
            
                                case "v2":
                                        $setnost="v";
                                        break;
            
                                case "v3":
                                        $setnost="a";
                                        break;
            
                                case "v4":
                                        $setnost="a";
                                        break;
            
                                case "v5":
                                    if($query==1)
                                    {
                                        $setnost="a";
                                    }
                                    elseif($query==2)
                                    {
                                        $setnost="s";
                                    }
                                        break;
                    
                        }
                        break;
                    case "G":
                        switch($verb_setnost)
                        {
                                case "v1":
                                            $setnost="v";
                                            break;
                
                                case "v2":
                                            $setnost="v";
                                            break;
                
                                case "v3":
                                            $setnost="v";
                                            break;
                
                                case "v4":
                                            $setnost="a";
                                            break;
                
                                case "v5":
                                    if($query==1)
                                    {
                                        $setnost="a";
                                    }
                                    elseif($query==2)
                                    {
                                        $setnost="s";
                                    }
                                            break;
                        
                                    }
                                    break;
                                    
                            
                    default:
                                    switch($verb_setnost)
                                    {
                                        case "v1":
                                            $setnost="v";
                                            break;
                
                                        case "v2":
                                            $setnost="v";
                                            break;
                
                                        case "v3":
                                            $setnost="v";
                                            break;
                
                                        case "v4":
                                            $setnost="a";
                                            break;
                
                                        case "v5":
                                            if($query==1)
                                            {
                                                $setnost="a";
                                            }
                                            elseif($query==2)
                                            {
                                                $setnost="s";
                                            }
                                            break;
                        
                                    }
                                    break;
                                    
                }
            }
            else
            {
                $setnost=$verb_setnost;
            }

        }

    }

    //Применение специальных правил сетности окончаний

    if($check_setnost['need_ending_setnost_long'])
    {
        $setnost="ss";
    }

    if($check_setnost['need_ending_setnost_long_var'])
    {
        $setnost="vv";
    }

    if($check_setnost['need_ending_setnost_var'])
    {
        $setnost="v";
    }

    if($check_setnost['need_ending_setnost'])
    {
        $setnost="s";
    }


    return $setnost;
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

function search_in_db($id,$where,$type)
{
  
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "sanskrit";
    // Create connection
    $connection = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    
          
			$query_db = "SELECT * FROM $where where id=$id";
 			$conn = mysqli_query($connection, $query_db);
 			
			if (mysqli_num_rows($conn) > 0) {
				while ($res = mysqli_fetch_array($conn)) {
					$verb_name=$res['name'];
					$verb_omonim=$res['omonim'];
					$verb_type_lat=$res['type'];
					$verb_setnost=$res['setnost'];
					$verb_pada=$res['pada'];
                    $verb_prs=$res['prs'];
                    $verb_aos=$res['aos'];

					
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
					
                    $element=$res['element'];
					$omonim=$res['omonim'];
                    $query=$res['query'];
                    $transform=$res['transform'];
                    $steam=$res['lemma'];
                    $lemma=$res['lemma'];
                    $rule=$res['rule'];
				
                    
				$omonim_text="";
				if($omonim)
				{
					$omonim_text=" $omonim ";
				}
				
				$adhoc=$res['adhoc'];
				$adhoc_text="";
				if($adhoc)
				{
					$adhoc_text="<BR>несам.: $adhoc ";
				}
										
				}
			}
            else
            {
                echo "no result";
            }


           if($type==1)
           {
            $query=0;
            $transform='';
            $lemma="VR";
           }

           if($type==2)
           {
               // $substr=mb_substr($verb_name,-1,1);
               // $cons=seeking_1_bukva($substr,0)[1];
                
               // if($cons=="C")
               // {
                    $verb_setnost='s';
               // }
              //  else
               // {
                  //  $verb_setnost='a'; 
               // }

           }
            
            $result[0]=$verb_name;
            $result[1]=$omonim;
            $result[2]=$verb_type;
            $result[3]=$verb_change;
            $result[4]=$verb_ryad;
            $result[5]=$type;
            $result[6]=$verb_setnost;
            $result[7]=$query;
            $result[8]=$transform;
            $result[9]=$steam;
            $result[10]=$verb_pada;
            $result[11]=$element;
            $result[12]=$verb_prs;
            $result[13]=$verb_aos;
            $result[14]=$lemma;
            $result[15]=$rule;

    //}
		
    return $result;
}


function Setnost($mool,$suffix,$is_suffix,$second_is_suffix,$verb_setnost,$suffix_steam,$query,$special_rules,$source)
{
    $mool=str_replace("|","",$mool);
    $suffix=str_replace("|","",$suffix);

    //echo "SOURC STEMA: $source";
    
    $check=check_setnost($mool,$suffix,$is_suffix,$second_is_suffix,$special_rules,$source,$suffix_steam)['need_verb_setnost'];
    $check_end_setnost=check_setnost($mool,$suffix,$is_suffix,$second_is_suffix,$special_rules,$source,$suffix_steam)['need_ending_setnost'];
    $check_end_setnost_var=check_setnost($mool,$suffix,$is_suffix,$second_is_suffix,$special_rules,$source,$suffix_steam)['need_ending_setnost_var'];
    $check_end_setnost_long=check_setnost($mool,$suffix,$is_suffix,$second_is_suffix,$special_rules,$source,$suffix_steam)['need_ending_setnost_long'];
    $check_end_setnost_long_var=check_setnost($mool,$suffix,$is_suffix,$second_is_suffix,$special_rules,$source,$suffix_steam)['need_ending_setnost_long_var'];

   // echo "<BR>CHECK: $mool,$suffix itog: $check<BR>";

    if($check)
    {
        $setnost=setnost_letter($mool,$suffix,$is_suffix,$second_is_suffix,$verb_setnost,$suffix_steam,$query,$special_rules,$source,$suffix_steam);
        $result['setnost']=$setnost;
    }

    if($check_end_setnost_long||$check_end_setnost_long_var||$check_end_setnost||$check_end_setnost_var)
    {
        
        $setnost=setnost_letter($mool,$suffix,$is_suffix,$second_is_suffix,$verb_setnost,$suffix_steam,$query,$special_rules,$source,$suffix_steam);
        //echo "Применяются особые правила сетности для окончаний<BR><BR>";
        $result['setnost']=$setnost;
    }

    return $result;
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

function Sandhi_String_From_Massive($info_massive_set_rgveda,$augment,$postgment)
{
        $sandhi_string_set_rgveda="";
        for($i=0;$i<count($info_massive_set_rgveda);$i++)
        {
            $sandhi_string_set_rgveda.=$info_massive_set_rgveda[$i][0]."|"; 
        }
       
        $sandhi_string_set_rgveda=str_replace("||","|",$sandhi_string_set_rgveda);
        $sandhi_string_set_rgveda=str_replace("||","|",$sandhi_string_set_rgveda);
        $sandhi_string_set_rgveda=str_replace("Ø̄","",$sandhi_string_set_rgveda);
        $sandhi_string_set_rgveda=str_replace("Ø","",$sandhi_string_set_rgveda);
        //$sandhi_string_set_rgveda=str_replace("||","|",$sandhi_string_set_rgveda);
        //$sandhi_string_set_rgveda=str_replace("||","|",$sandhi_string_set_rgveda);
        //$sandhi_string_set_rgveda=str_replace("||","|",$sandhi_string_set_rgveda);
        
        if($augment&&$sandhi_string_set_rgveda)
        {
            if(mb_substr($sandhi_string_set_rgveda,0,1)!="|")
            {
                $sandhi_string_set_rgveda=$augment."".$sandhi_string_set_rgveda.$postgment;
            }
            else
            {
                $sandhi_string_set_rgveda=$augment."".$sandhi_string_set_rgveda.$postgment;
            }
        }

        $sandhi_string_set_rgveda=str_replace("||","|",$sandhi_string_set_rgveda);
        $sandhi_string_set_rgveda=str_replace("||","|",$sandhi_string_set_rgveda);

        return  $sandhi_string_set_rgveda;
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
            $name="Настоящее время";
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
            $name="Каузативно-дезидеративный оптатив настоящего времени";
             break;
        case "VR-CaS-DS-PS-PrS-OS-O":
            $name="Каузативно-дезидеративно-пассивный оптатив настоящего времени";
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
?>