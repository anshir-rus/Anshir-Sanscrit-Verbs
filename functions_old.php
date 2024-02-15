<?php
error_reporting(E_ERROR | E_PARSE);
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

function search_in_corpus($word)
{
        $url = "https://samskrtam.ru/parallel-corpus/10_rigveda.html";
        $urls = array("https://samskrtam.ru/parallel-corpus/01_rigveda.html","https://samskrtam.ru/parallel-corpus/02_rigveda.html","https://samskrtam.ru/parallel-corpus/03_rigveda.html","https://samskrtam.ru/parallel-corpus/10_rigveda.html","https://samskrtam.ru/parallel-corpus/01_mahabharata-adiparva.html");


		
		
		$headers = []; // создаем заголовки

        for($i=0;$i<count($urls);$i++)
        {
            echo '<BR><BR>Смотрим в <a href="'.$urls[$i].'">'.$i.' мандале Ригведы</a> слово '.$word.' </b><BR>';
            $curl = curl_init(); // создаем экземпляр curl

            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_VERBOSE, 1); 
            curl_setopt($curl, CURLOPT_POST, false); // 
            curl_setopt($curl, CURLOPT_URL, $urls[$i]);

            $result = curl_exec($curl);

            $search=" ".$word." ";

            $search_in_corpus=mb_strpos($result,$search);

            if($search_in_corpus)
            {
                $last=$search_in_corpus+mb_strlen($word)+1;

                $how=mb_strlen($word)+600;

                echo "<BR>".strip_tags(mb_substr($result,$search_in_corpus-310,310));
                echo " <b>".strip_tags(mb_substr($result,$search_in_corpus+1,mb_strlen($word)))."</b>";
                echo " ".strip_tags(mb_substr($result,$last,$how))." ";
                echo "<BR>";
            }
            else
            {
                echo "Не найдено";
            }

         }
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
        echo "<b>Удвоение корня (первый шаг - определяем P') </b><BR><BR>";
    }

    if ($debug) {
        echo dimensions_table($dimensions);
    }

    if ($i == 1) {
        if ($debug) {
            echo "P пустое<BR>";
        }
        $p_new = "";
        $p_mool = "";
        $model = $mool_change . $f_mool;
    } else {

        if ($debug) {
            echo "P не пустое<BR>";
        }

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
                $comment = "остаётся шумная";
            } else {
                $p_new = $x1[0];
                $model = $p_new . "E'" . $p_mool . $mool_change . $f_mool;
                $comment = "остаётся первая";
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
            $comment = "ничего не происходит";
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

        if ($debug) {
            echo $p_text . $e_text . $f_text;
        }
        if ($debug) {
            echo $pada1;
        }

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

        if ($debug) {
            echo "<BR>Шаг 2 удвоения: " . $model . " $comment <BR>";
        }

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
            echo "<BR>Шаг 3 удвоения: " . $model . " $comment<BR>";
        }
    }

    $itog[0] = $model;
    $itog[1] = $p_new;
    $itog[2] = "E'";
    $itog[3] = $p_mool;
    $itog[4] = $mool_change;
    $itog[5] = $f_mool;
    $itog[6] = $f_mool_array;
    $itog[7] = $p_mool_array;

    return $itog;
}

function duplication_p2($array, $mool, $mool_type, $mool_type_change, $omonim, $debug) {    //как чередуется ar ??

    $p_new = $array[1];

    $p_mool = $array[3];
    $mool_change = $array[4];
    $f_mool = $array[5];

    if ($debug) {
        echo "<BR><b>Подготовка корня для создания простого перфекта </b><BR>";
    }

    if (!$array[1]) {

        if ($mool_type_change == "R1") {  ///Как чередуется ???
            if ($mool == "ṛ") {
                $model = "ār";
                $stop=1;
                $comment = "глагол-исключение $mool";
            } else {
                $model = "ān" . $array[4] . $array[5];
                $prefix="ān";
                $comment = "Ряд $mool_type_change схема ānR1F";
            }
        } elseif ($mool_type_change == "N1") {
            $model = "ān" . $array[4] . $array[5];
            $prefix="ān";
            $comment = "Ряд $mool_type_change ānN1F";
        } elseif ($mool == "Øs") {
            $model = "ās";
            $prefix="ās";
            $stop=1;
            $comment = "глагол-исключение $mool";
        } elseif ($mool == "m̥̄") {
            $model = "ām";
            $prefix = "ām";
            $stop=1;
            $comment = "глагол-исключение $mool";
        } elseif ($mool == "īj" || $mool == "edh") {
            $model = "Только описательный перфект";
            $comment = "глагол-исключение $mool";
        } else {

            $e_1_word=$mool_change. $array[4] . $array[5];

            $first_chered=$array[4] . $array[5];

            $new_e=$mool_change;

            $change_later=array("E'",$mool_change,1,mb_strlen($mool_change));

            $prefix=$new_e;
            $model = $new_e . $array[4] . $array[5];
            
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

            if ($mool == "viØc" || $mool == "viØdh" || $mool == "suØp" || $mool == "miØkṣ") {
                //PPEF   

                $model = $p_mool ."|". $p_mool . $mool_change . $f_mool;
                $prefix = $p_mool;
                $comment = " ветвь 1 непустого Р, корень-исключение $mool, А1, схема удвоения PPEF ";
            } elseif ($mool == "śad" && $omonim == "1") {

                $model = "śāśad";
                $prefix = "śā";
                $stop=1;
                $comment = " ветвь 1 непустого Р, корень-исключение $mool $omonim, А1, схема удвоения śā + śad ";
            } elseif ((substr($p_new, 0, 1) == "i" || substr($p_new, 0, 1) == "u") && ($mool != "uØkṣ" && $mool != "śuØṣ")) {
                //P’ØPA1F   ???????????????

                $model = $p_new . "Ø" . $p_mool . $mool_change . $f_mool;
                $prefix = $p_new ."|". "Ø";
                $comment = " ветвь 1 непустого Р, первая буква P i или u, А1, схема удвоения P’ØPA1F ";
            } elseif ($mool_type == 2 || strpos($mool, "ṛ") || $mool == "uØkṣ" || $mool == "śuØṣ") {
                //P’aPA1F

                $model = $p_new ."a". $p_mool . $mool_change . $f_mool;
                $prefix = $p_new ."|". "a";
                $comment = " ветвь 1 непустого Р, А1, схема удвоения P’aPA1F ";
            }
            else
            {
                $model="<font color=red>Что-то пошло не так ¯\_(ツ)_/¯ не попали в условия веток</font>";
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
                $prefix = "ji";
                $comment = " корень-исключение $mool ";
            } elseif ($mool == "uØ̄") { /// ???
                //1 М П – ū [ūvus – 3 pl.]; 2 М П – vavā [vavau]  ?

                $model = "uØuØ̄";
                $prefix = "uØ";
                $comment = " корень-исключение $mool ???";
            } elseif ($mool == "vīØ̄") {
                $model = "vivīØ";
                $prefix = "vi";
                $comment = " корень-исключение $mool ";
            } elseif ($flag_has_vowels == 0 || $mool_type == 2 || $mool == "śīØ̄" || $mool == "stīØ̄") {
                //P’aPA2F

                $model = $p_new . "a" . $p_mool . $mool_change . $f_mool;
                $prefix = $p_new . "|a|";
                $comment = " ветвь 2 непустого Р, А2, схема удвоения P’aPA2F ";
            }
        } elseif ($mool_type_change == "I0" || $mool_type_change == "I1" || $mool_type_change == "I2") {
            //Корни  рядов I удваиваются по схеме P’iPIF:

            $model = $p_new . "i" . $p_mool . $mool_change . $f_mool;
            $prefix = $p_new . "|i|";
            $comment = " ветвь 3 непустого Р, ряд I, схема удвоения P’iPIF ";
        } elseif ($mool_type_change == "U0" || $mool_type_change == "U1" || $mool_type_change == "U2") {  // ????
            if ($mool == "bhū") {
                $model = "babhū";
                $prefix = "ba";
                $comment = " p2√bhū = babhū [babhūva] (!) U2 не чередуется, в позиции EV принимает вид ūv";  // !!!
            } elseif ($mool == "dhau") {
                $model = "dadhau";
                $prefix = "da";
                $comment = " корень-исключение $mool";
            } else {
                //P’uPUF

                $model = $p_new . "u" . $p_mool . $mool_change . $f_mool;
                $prefix = $p_new . "|u|";
                $comment = " ветвь 4 непустого Р, ряд U, схема удвоения P’uPUF ";
            }
        } else {
            //P’aPRF P’aPLF  P’aPNF P’aPMF
            $model = $p_new . "a" . $p_mool . $mool_change . $f_mool;
            $prefix = $p_new . "|a|";
            $comment = " ветви 5-8 непустого Р, ряд $mool_type_change, схема удвоения P’aP(чередующийся элемент ряда)F ";
        }
    }

    if ($debug) {
        echo "<BR>Удвоенный корень для чередования без сандхи: $model ($comment)<BR><BR>";
    }

    //$model_sandhi=simple_sandhi($model,$mool_change,0)[0];
    //echo simple_sandhi("a|u|a|v",$mool_change,0)[0];
    $model=str_replace("|","",$model);

    $result[0]=$model;
    $result[1]=$prefix;
    $result[2]=$stop;
    $result[3]=$model_sandhi;
    $result[4]=$change_later;


    return $result;

    //echo "Форма простого  перфекта 3sg: ";
    // echo get_word($model,$mool_number,$mool_type,$mool_change,$mool_type_change,$suffix,$suffix_ask,$suffix_transform,$glagol_or_imennoy,$verb_setnost,0)[0];
    //echo "<BR>";
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
                $model[]=$p_new."i".$p_mool.$mool_change.$f_mool;
                $prefix[]=$p_new."|i|";
                $comment[]=" Корни ряда A1, чередующиеся по I типу, удваиваются по схеме P’iPA1F";
            }
            elseif($mool_type==2)
            {
                $model[]=$p_new."a".$p_mool.$mool_change.$f_mool;
                $prefix[]=$p_new."|a|";
                $comment[]=" Корни ряда A1, чередующиеся по II типу, удваиваются по схеме P’aPA1F";
            }

        }
        elseif ($mool_type_change == "A2") {   /// с вар.

            if($mool=="rā")
            {
                $model[]=$p_new."a"."rā";
                $prefix[]=$p_new."|a|";
                $comment[]=" вариант о.н.в. 3 класса для корня-исключения $mool удваивается по схеме P’aPA2F";
            }
            
            if(($mool=="dØ̄"&&$omonim==1)||($mool=="dhØ̄"&&$omonim==1)||($mool=="hØ̄"&&$omonim==1))
            {
                $model[]=$p_new."a".$p_mool.$mool_change.$f_mool;
                $prefix[]=$p_new."|a|";
                $comment[]=" √dØ̄ 1, √dhØ̄ 1, √hØ̄ 1 удваиваются по схеме P’aPA2F";
            }
            else
            {
                $model[]=$p_new."i".$p_mool.$mool_change.$f_mool;
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
                $model[]=$p_new."i".$p_mool.$mool_change.$f_mool;
                $prefix[]=$p_new."|i|";
                $comment[]=" корень ряда I удваивается по схеме P’iPIF";
            }

        }
        elseif ($mool_type_change == "U0"||$mool_type_change == "U1"||$mool_type_change == "U2") {

           
                $model[]=$p_new."u".$p_mool.$mool_change.$f_mool;
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
                $model[]=$p_new."i".$p_mool.$mool_change.$f_mool;
                $prefix[]=$p_new."|i|";
                $comment[]=" корень ряда R удваивается по схеме P’iPRF";
            }

        }
        elseif ($mool_type_change == "N0"||$mool_type_change == "N1"||$mool_type_change == "N2"||$mool_type_change == "L") {


                $model[]=$p_new."a".$p_mool.$mool_change.$f_mool;
                $prefix[]=$p_new."|a|";
                $comment[]=" корни ряда N-L удваиваются по схеме P’aPNF - P’aPLF";

        }
        elseif ($mool_type_change == "M0"||$mool_type_change == "M1"||$mool_type_change == "M2")
        {
            $model[]=$p_mool.$mool_change.$f_mool;
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

function duplication_d2($array, $mool, $mool_type, $mool_type_change, $omonim, $is_open_mool, $verb_setnost, $debug) {   // Здесь пока МП определяется БЕЗ таблицы 4!

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
        $setn="s";
        $e1[0]=get_e_mp_table4("",$mool,$setn,$omonim,$mool_type,$mool_change,$mool_type_change,1,"");
        $setn="is";
        $e1[1]=get_e_mp_table4("",$mool,$setn,$omonim,$mool_type,$mool_change,$mool_type_change,1,"");
    }


    $e1=get_e_mp_table4("",$mool,$setn,$omonim,$mool_type,$mool_change,$mool_type_change,1,"");
    $e2=get_e_mp_table4("",$mool,$setn,$omonim,$mool_type,$mool_change,$mool_type_change,2,"");
    $e3=get_e_mp_table4("",$mool,$setn,$omonim,$mool_type,$mool_change,$mool_type_change,3,"");


    if ($debug) {
        echo "<BR><b>Подготовка корня для создания дезидератива </b><BR>";
 
        echo "<BR>Сетность корня для образования формы основы дезидератива DS: ".$verb_setnost_des."<BR>";

    }
    $c=0;
    if (!$array[1]) {   /// Как оно чередуется???

       

        if($f_mool=="h")
        {
            $e2=get_e_mp_simple($mool_type_change, $mool_type, 2);
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

                $e2=get_e_mp_simple($mool_type_change, $mool_type, 2);
                $model[]=$e2."|".$f_mool."|i|".$f_mool_array[1];
                $comment[$c]=" Если в F два согласных (F = C1C2), то корень удваивается по схеме E2FiC2";
                $c++;
            }
            else
            {
                //Остальные корни, начинающиеся на чередующийся элемент, удваиваются по схеме E2FiF:
                $e2=get_e_mp_simple($mool_type_change, $mool_type, 2);
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
            
                        $e2=get_e_mp_simple($mool_type_change, $mool_type, 2);
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
                $model[]="suṣu".$e1."p";
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

                $e2=get_e_mp_simple($mool_type_change, $mool_type, 2);
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

                $e2=get_e_mp_simple($mool_type_change, $mool_type, 2);
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

    $j=0;$c=0;
    $count=count($model);

    if ($debug) {
        if($count>1)
        {
            echo "<BR>Удвоенный корень для чередования без сандхи.<BR><BR>У этого корня есть ".$count." варианта";
        }
    }

    for($i=0;$i<$count;$i++)
    {   
        $c++;

        $model[$i]=str_replace("Ø","",$model[$i]);
        $model[$i]=str_replace("Ø̄","",$model[$i]);
        $model[$i]=str_replace("||","|",$model[$i]);

        //Добавляем суффикс -s, требование к чередованию было на этапе удвоения, больше не чередуем

        $last_letter=mb_substr($model[$i], -1, 1);
        $last_letter_v=seeking_1_bukva($last_letter,0)[1];

        if($verb_setnost_des=="s"&&$last_letter_v=="C")
        {
            $first=$model[$i]."|i|"."s|";
            $first=str_replace("||","|",$first);
            $first=simple_sandhi($first,$mool_change,"DeS",0)[0];
            $model_suff[$j]=$first;

            $j++;

        }
        elseif($verb_setnost_des=="a")
        {
            $first=$model[$i]."|"."s|";
            $first=str_replace("||","|",$first);
            $first=simple_sandhi($first,$mool_change,"DeS",0)[0];
            $model_suff[$j]=$first;

            $j++;

        }
        elseif($verb_setnost_des=="v")
        {
            
            if($last_letter_v=="C")
            {
                $first=$model[$i]."|i|"."s";
                $first=str_replace("||","|",$first);
                $first=simple_sandhi($first,$mool_change,"DeS",0)[0];
            }
          

            $second=$model[$i]."|"."s";
            $second=str_replace("||","|",$second);
            $second=simple_sandhi($second,$mool_change,"DeS",0)[0];

            if($first&&$second)
            {
                $model_suff[$j]="$first,$second";
            }
            elseif($first&&!$second)
            {
                $model_suff[$j]="$first";
            }
            elseif(!$first&&$second)
            {
                $model_suff[$j]="$second";
            }

            $j++;

        }
        else
        {
            $model[]="В языке такой формы не встречается";
            if ($debug) {
                echo "В языке такой формы не встречается (сетность 0)";
            }
        }

        $model[$i]=str_replace("|","",$model[$i]);

        if ($debug) {
            if($count>1)
            {
                echo "<BR> $c) $model[$i] (".$comment[$i].") ";
            }
            else
            {
                echo "<BR>Удвоенный корень для чередования без сандхи: ".$model[0]." (".$comment[$i].") <BR><BR>";
            }

            echo "<BR>Добавляем суффикс -s, требование к чередованию было на этапе удвоения, больше не чередуем. Применяем сандхи.<BR>";
            echo "<b>Итог: ".$model_suff[$i]."</b><BR>";
        }

    }

    $model_suff=array_filter($model_suff);

    $result[0]=$model_suff;
    $result[1]=$model;
 //   $result[1]=$model_2;
  //  $result[2]=$model_var;
  //  $result[3]=$model_2_var;
//
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

    $e1=get_e_mp_simple($mool_type_change, $mool_type, 1);
    $e2=get_e_mp_simple($mool_type_change, $mool_type, 2);
    $e3=get_e_mp_simple($mool_type_change, $mool_type, 3);


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
                    $model = $p_new."ā".$p_mool.$mool_change.$f_mool;
                    $comment = " Корни ряда А1 удваиваются по схеме P’āPEF";
                }
                

            }
            elseif ($mool_type_change == "A2")
            {
                    $model = $p_new."ā".$p_mool.$mool_change.$f_mool;
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
                
                    $model = $p_new."e".$p_mool.$mool_change.$f_mool;
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
                
                    $model = $p_new."o".$p_mool.$mool_change.$f_mool;
                    $comment = " Корни рядов U удваиваются по схеме P’oPE(F):";
                }
            }
            elseif($mool_type_change == "M0" || $mool_type_change == "M1" || $mool_type_change == "M2")
            {
                
                    $model = $p_new."am".$p_mool.$mool_change;
                    $comment = " Корни рядов М удваиваются по схеме P’amPE ";

                    if($mool=="gm̥")
                    {
                        $model2 = "gánīgm̥";
                        $comment.= " Корень - исключение $mool (вар) ";
                    }
            }
            elseif ($mool_type_change == "L")
            {
                
                    $model = $p_new."al".$p_mool.$mool_change.$f_mool;
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
                        $model = $p_new."arī".$p_mool.$mool_change.$f_mool;
                        $comment = " Все корни рядов R могут удваиваться по схеме P’arīPE(F) (со вставной -ī-)";

                        //Некоторые корни R, а именно: √kṛṣ, √dṛ 2, √dṛś, √dṛh, √bṛh, √dhṛ, √vṛ 1,  √vṛ 2, √vṛj, √vṛt, √sṛ, √hṛ 1, √hṛ 2, √kṝ 2, √gṝ 1, √dṝ, √tṝ – также способны удваиваться по схеме P’arPE(F) (без вставной -ī-):
                        if($mool=="kṛṣ"||($mool=="dṛ"&&$omonim==2)||$mool=="dṛś"||$mool=="dṛh"||$mool=="bṛh"||$mool=="dhṛ"||($mool=="vṛ"&&$omonim==1)||($mool=="vṛ"&&$omonim==2)||$mool=="vṛj"||$mool=="vṛt"||$mool=="sṛ"||($mool=="hṛ"&&$omonim==1)||($mool=="hṛ"&&$omonim==2)||($mool=="kṝ"&&$omonim==2)||($mool=="gṝ"&&$omonim==1)||$mool=="dṝ"||$mool=="tṝ")
                        {
                            $model2 = $p_new."ar".$p_mool.$mool_change.$f_mool;
                            $comment.= " Некоторые корни R, а именно: $mool также способны удваиваться по схеме P’arPE(F) (без вставной -ī-)";
                        }

                        //Некоторые корни R, а именно: √garh, √dhṛ, √mṛṣ, √śṛ, √spṛdh, √smṛ, √hvṛ, √kṝ 1, √jṝ, √jvar √tṝ, √pṝ, √śṝ, √stṝ, √svar, √tvar, √hvṛ– также могут удваиваться по схеме P’āPE(F):
                        if($mool=="garh"||$mool=="dhṛ"||$mool=="mṛṣ"||$mool=="śṛ"||$mool=="spṛdh"||$mool=="smṛ"||$mool=="hvṛ"||($mool=="kṝ"&&$omonim==1)||$mool=="jṝ"||$mool=="jvar"||$mool=="tṝ"||$mool=="pṝ"||$mool=="śṝ"||$mool=="stṝ"||$mool=="svar"||$mool=="tvar"||$mool=="hvṛ")
                        {
                            $model2 = $p_new."ā".$p_mool.$mool_change.$f_mool;
                            $comment.= " Некоторые корни R, а именно: $mool также могут удваиваться по схеме P’āPE(F)";
                        }

                    }
            }
            elseif ($mool_type_change == "N0" || $mool_type_change == "N1" || $mool_type_change == "N2")
            {
                $model = $p_new."an".$p_mool.$mool_change.$f_mool;
                $comment = " Все корни рядов N могут удваиваться по схеме P’anPE(F) (без вставной -ī-)";

                if($is_open_mool==0||$mool=="phan"||$mool=="pn̥"||$mool=="vn̥̄"||$mool=="svan")
                {
                    $model2 = $p_new."anī".$p_mool.$mool_change.$f_mool;
                    $comment.= " Закрытые корни рядов N, а также некоторые корни, а именно: √phan, √pn̥, √vn̥̄, √svan – также могут удваиваться по схеме P’anīPE(F)";
                }

                if($is_open_mool==0||$mool=="khn̥̄"||$mool=="jn̥̄"||$mool=="sn̥̄")
                {
                    $model3 = $p_new."ā".$p_mool.$mool_change.$f_mool;
                    $comment.= " Закрытые корни рядов N и некоторые другие корни, а именно: √khn̥̄, √jn̥̄, √sn̥̄ – также могут удваиваться по схеме P’āPE(F)";
                }



            }


    }

    if ($debug) {
        echo "<BR>$model<BR>$comment<BR>";
    }

    $result[0]=$model;
    $result[1]=$model2;
    $result[2]=$model3;
    $result[3]=$comment;

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

    


    if (!$array[1]) {   

        if($mool_type==3)
        {
            $mool_type=1;
        }
        
        $e1=get_e_mp_simple($mool_type_change, $mool_type, 1);
        $e2=get_e_mp_simple($mool_type_change, $mool_type, 2);
        $e3=get_e_mp_simple($mool_type_change, $mool_type, 3);


        if($f_mool=="h")
        {
          
            $model=$e2."ji".$f_mool;
            $comment=" Если F’ = h, то корень удваивается по схеме E2’jiF ";
        }
        elseif($mool=="m̥̄")
        {
            $model="аmam";
            $comment=" корень-исключение $mool";

        }
        elseif($mool=="akṣ")
        {
            $model="аcikṣ";
            $comment=" корень-исключение $mool";

        }
        else
        {

            if(count($f_mool_array)==2)
            {
                $new_f=$f_mool_array[1];
                $model=$e2.$f_mool."i".$new_f;
                $comment=" Если F’ > одного согласного (F’ = C1C2), то корень удваивается по схеме E2’FiC2";

            }
            else
            {
                $new_f=$f_mool;
                $model=$e2.$f_mool."i".$new_f;
                $comment=" Остальные корни этого вида удваиваются по схеме E2’FiF ";
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
                      
                        $model=$e2.$f_without_h."i".$new_f;
                        $comment.="<BR> Если F – придыхательный согласный (F = H), то он преобразуется в свою непридыхательную пару E2’С-iF";

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
                $comment.=" Исключение: a2√suØp (вар.) = sū + suØp = sūsuØp ";
            }
            else
            {
                $model=$p_new."i".$p_mool.$e2.$f_mool;
                $model2=$p_new."ī".$p_mool.$e2.$f_mool;
                $comment.=" Все корни ряда А1 могут удваиваться по схемам P’iPA12F и P’īPA12F ";
            }

            if($is_open_mool==0&&$mool_type==2)
            {
                $model3=$p_new."a".$p_mool.$e2.$f_mool;
                $comment.=" Закрытые корни ряда А1 II типа также могут удваиваться по схеме P’aPA12F ";
            }
        }
        elseif($mool_type_change=="A2")
        {
            if($mool=="dØ̄"&&$omonim==1)
            {
                $model="dīdad";
                $comment.=" Открытые корни А2 составляют класс исключений $mool $omonim ";
            }
            elseif($mool=="dhØ̄"&&$omonim==2)
            {
                $model="dadhØ̄";
                $comment.=" Открытые корни А2 составляют класс исключений $mool $omonim ";
            }
            elseif($mool=="pØ̄")
            {
                $model="pīpī";
                $comment.=" Открытые корни А2 составляют класс исключений $mool $omonim ";
            }
            elseif($mool=="mØ̄"&&$omonim==3)
            {
                $model="mīme";
                $comment.=" Открытые корни А2 составляют класс исключений $mool $omonim ";
            }
            elseif($mool=="sØ̄")
            {
                $model="sīṣe";
                $comment.=" Открытые корни А2 составляют класс исключений $mool $omonim ";
            }
            elseif($mool=="hØ̄"&&$omonim==1)
            {
                $model="jījah";
                $comment.=" Открытые корни А2 составляют класс исключений $mool $omonim ";
            }
            elseif($is_open_mool==0)
            {
                $model=$p_new."a".$p_mool.$e2.$f_mool;
                $model2=$p_new."i".$p_mool."a".$f_mool;
                $model3=$p_new."ī".$p_mool."a".$f_mool;
                $comment.=" Закрытые корни А2 удваиваются по схеме P’aPA22F, а также по схемам P’iPaF и P’īPaF ";
            }
            else
            {
                $model=" видимо это аорист не 3 класса ";
                $comment=" видимо это аорист не 3 класса ";
            }
        }
        elseif ($mool_type_change == "I0" || $mool_type_change == "I1" || $mool_type_change == "I2")
        {
            
            if(($mool=="ji"&&$omonim==1)||$mool=="jri"||$mool=="ḍī"||$mool=="bhī"||$mool=="rī"||$mool=="vī"||$mool=="śī"||$mool=="śrī")
            {
                $model=$p_new."i".$p_mool.$e2.$f_mool;
                $model2=$p_new."ī".$p_mool.$e2.$f_mool;
                $comment.=" Корни √ji 1, √jri, √ḍī, √bhī, √rī, √vī, √śī, √śrī, √hīḍ (вар.) удваиваются по схемам P’iPI2(F) и P’īPI2(F) ";
            }
            else
            {

                if($mool=="hīḍ")
                {
                    $model=$p_new."i".$p_mool.$e2.$f_mool;
                    $model2=$p_new."ī".$p_mool.$e2.$f_mool;
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
                $comment.=" Остальные корни рядов I удваиваются по схемам P’iPI1(F) и P’īPI1(F) ";

            }
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
            $model=$p_new."u".$p_mool.$e1.$f_mool;
            $model2=$p_new."ū".$p_mool.$e1.$f_mool;
            $comment.=" Все корни U могут удваиваться по схемам P’uPU1(F) и P’ūPU1(F) ";

            if($is_open_mool==1)
            {
                $model3=$p_new."u".$p_mool.$e2.$f_mool;
                $model4=$p_new."ū".$p_mool.$e2.$f_mool;
                $model5=$p_new."i".$p_mool.$e2.$f_mool;
                $model6=$p_new."ī".$p_mool.$e2.$f_mool;
                $comment.=" Открытые корни U также могут удваиваться по схемам P’uPU2(F), P’ūPU2(F), P’iPU2(F) и P’īPU2(F) ";
            }


        }
        elseif ($mool_type_change == "R0" || $mool_type_change == "R1" || $mool_type_change == "R2")
        {
 
            if($mool=="pṝ")
            {
                $model="pūpṝ";
                $comment.=" корень-исключение $mool ряда R ";
            }
            elseif($mool=="sphṝ")
            {
                $model="pusphur";
                $comment.=" корень-исключение $mool ряда R ";
            }
            elseif($mool=="vṛ")
            {
                $model=$p_new."a".$p_mool.$e2.$f_mool;
                $model2=$p_new."ī".$p_mool.$e1.$f_mool;
                $comment.=" √vṛ, √dṝ, √stṝ – также могут удваиваться по схемам P’aPR2(F) и P’īPR1(F) ";
            }
            elseif($mool=="dṝ")
            {
                $model=$p_new."a".$p_mool.$e2.$f_mool;
                $model2=$p_new."ī".$p_mool.$e1.$f_mool;
                $comment.=" √vṛ, √dṝ, √stṝ – также могут удваиваться по схемам P’aPR2(F) и P’īPR1(F) ";
            }
            elseif($mool=="stṝ")
            {
                $model=$p_new."a".$p_mool.$e2.$f_mool;
                $model2=$p_new."ī".$p_mool.$e1.$f_mool;
                $comment.=" √vṛ, √dṝ, √stṝ – также могут удваиваться по схемам P’aPR2(F) и P’īPR1(F) ";
            }
            else
            {
                if($is_open_mool==0)
                {
                    $model=$p_new."a".$p_mool.$e2.$f_mool;
                    $model2=$p_new."ī".$p_mool.$e1.$f_mool;
                    $comment.=" Закрытые корни рядов R также могут удваиваться по схемам P’aPR2(F) и P’īPR1(F) ";
                    
                }
                else
                {
                    $model=$p_new."ī".$p_mool.$e2.$f_mool;
                    $comment.=" Открытые корни R удваиваются по схеме P’īPR2 ";
                }

                if($mool_type == 2)
                {
                    $model3=$p_new."i".$p_mool.$e1.$f_mool;
                    $comment.=" Все корни рядов R II типа также могут удваиваться по схеме P’iPR1(F) ";
                }

           
            }

        }
        elseif($mool_type_change=="L")
        {
            $model=$p_new."i".$p_mool.$e1.$f_mool;
            $model2=$p_new."ī".$p_mool.$e1.$f_mool;
            $comment.=" Корни ряда L удваиваются по схемам P’iPL1(F) и P’īPL1(F) ";
        }
        elseif($mool_type_change == "M0" || $mool_type_change == "M1" || $mool_type_change == "M2")
        {
            if($mool=="kam")
            {
                $model="cakam";
                $comment.=" Исключение: а2√kam (вар.) = ca + kam = cakam  ";
            }
            
            
                $model2=$p_new."i".$p_mool.$e2;
                $model3=$p_new."ī".$p_mool.$e2;
                $comment.=" Корни рядов М удваиваются по схемам P’iPM2 и P’īPM2 ";
            
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
                $model=$p_new."a".$p_mool.$e1.$f_mool;
                $model2=$p_new."i".$p_mool.$e1.$f_mool;
                $model3=$p_new."ī".$p_mool.$e1.$f_mool;
                $comment.=" Корни √chn̥d, √śvn̥c, √syn̥d, √srn̥s, √svn̥j удваиваются по схемам P’aPN1(F), P’iPN1(F) и P’īPN1(F)";
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
                    $model=$p_new."a".$p_mool.$e1.$f_mool;
                    $model2=$p_new."i".$p_mool.$e1.$f_mool;
                    $model3=$p_new."ī".$p_mool.$e1.$f_mool;
                    $comment.=" Корни √mn̥th (вар.), √rn̥j (вар.), √rn̥dh (вар.) удваиваются по схемам P’aPN1(F), P’iPN1(F) и P’īPN1(F) ";
                }

                if($is_open_mool==1&&$mool_type==1)
                {
                    //с. Открыте корни N I типа удваиваются по схеме P’īPN2F: a2√hn̥ = jī + ghan = jīghan [ajīghanat]
                    $model4=$p_new."ī".$p_mool.$e2.$f_mool;
                    $comment.=" Открыте корни N I типа удваиваются по схеме P’īPN2F ";
                }
                else
                {
                    $model4=$p_new."a".$p_mool.$e2.$f_mool;
                    $comment.=" Остальные корни N удваиваются по схеме P’aPN2(F) ";
                }
            }


        }


    }


    if ($debug) {
        echo "<BR>$model<BR>$comment<BR>";
    }

    $result[0]=$model;
    $result[1]=$model2;
    $result[2]=$model3;
    $result[3]=$model4;
    $result[4]=$model5;
    $result[5]=$model6;
    $result[6]=$comment;

    return $result;
   
}


function get_perfect($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,$debug)
{
    
    $duplication_first=duplication_first($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$debug);

    $duplication_p2=duplication_p2($duplication_first,$verb_name,$verb_type,$verb_ryad,$verb_omonim,$debug);

    $duplication_p2_model=$duplication_p2[0];
    $duplication_p2_prefix=$duplication_p2[1];
    $stop=$duplication_p2[2];
    $duplication_p2_model_sandhi=$duplication_p2[3];
    $change_later=$duplication_p2[4];
   
    $mool_after_duplication=$duplication_first[3].$duplication_first[4].$duplication_first[5];

    if($change_later[0]=="E'")
    {
        $flag_e=1;
    }


    if($debug)
    {
        echo "<b> Удвоение корня для образования перфекта (без сандхи): ".$duplication_p2_model."</b><BR>";
    }

    $is_open_mool = 0;
    if (mb_substr($verb_name, -1, 1) == "Ø" || mb_substr($verb_name, -2, 2) == "Ø̄" || mb_substr($verb_name, -1, 1) == $verb_change || mb_substr($verb_name, -2, 2) == $verb_change || mb_substr($verb_name, -3, 3) == $verb_change ) {
        $is_open_mool = 1;
    }

    

    if($verb_pada=="A"||$verb_pada=="Ā")
    {

        //$postfix_name[0]="a";
        //$postfix_query[0]="3/2";
        $postfix_name[0]="e";
        $postfix_query[0]="1";
        
        $postfix_name[1]="";
        $postfix_query[1]="";
        $postfix_transform[0]="";
        $postfix_transform[1]="";
        //$verb_name=$duplication_p2;


        if($stop!=1&&!$flag_e)
        {
            $perfect=get_word($duplication_p2_prefix,$mool_after_duplication,"",$verb_omonim,$verb_type,$verb_change,$verb_ryad,$postfix_name,$postfix_query,$postfix_transform,"1",$verb_setnost,$stop,$debug,"",0)[0];
        }
        else
        {
            $perfect=$duplication_p2_model.$postfix_name[0];
        }

        
        if($flag_e)
        {
            
            if($debug)
            {
                echo "<BR><BR>II. Проводим первое чередование под запрос суффикса<BR>";
            }
            
            
            $perfect=get_word("",$duplication_p2_model,"",$verb_omonim,$verb_type,$verb_change,$verb_ryad,$postfix_name,$postfix_query,$postfix_transform,"1",$verb_setnost,$stop,$debug,$change_later[3]+1,0)[0];
            $prefix=$duplication_p2_prefix;

           

          //  $perfect=str_replace("|","",$perfect);


            if($debug)
            {
                echo "<BR><BR>III. Проводим второе чередование для определения E'<BR>";
            }

            $perfect=get_word("",$perfect,"",$verb_omonim,$verb_type,$verb_change,$verb_ryad,[''],['1'],[''],"1",$verb_setnost,$stop,$debug,$change_later[2],$flag_e)[3];

            $with_sandhi=simple_sandhi($perfect,$mool_change,"",0)[0];
            if($debug)
            {
            //echo "<BR>После удвоения модель E1EF => чередуем теперь Е1 по Таблице<BR>";
            echo "<BR><b>Перфект после чередования ($verb_pada) с сандхи: $with_sandhi </b>";
            echo '<hr class="hr hr-blurry" />';
            }

            $result[]=$with_sandhi;
        }
        else
        {
            if($debug)
            {
                echo "<BR><b>Перфект после чередования ($verb_pada): $perfect </b>";
            }

            $result[]=$perfect;
        }

        //search_in_corpus($perfect);

    }
    elseif($verb_pada=="P")
    {
        if($verb_ryad=="A2")
        {
            $postfix_name[0]="āu";
            $postfix_query[0]="1";
        }
        elseif($verb_ryad=="A1")
        {
            
            if($duplication_first[3]!=""&&count($duplication_first[6])==1&&seeking_1_bukva($duplication_first[5],0)[1]=="C")
            {
                if($debug)
                {
                    echo "Корень вида PA1С<BR>";
                }
                
                $postfix_name[0]="a";
                $postfix_query[0]="3";
            }
            else
            {
                if($is_open_mool==1)
                {
                    if($debug)
                    {
                        echo "Корень открытый<BR>";
                    }
                    
                    $postfix_name[0]="a";
                    $postfix_query[0]="3";
                }
                else
                {
                    $postfix_name[0]="a";
                    $postfix_query[0]="2";
                }
            }
        }
        else
        {
            if($is_open_mool==1)
            {
                $postfix_name[0]="a";
                $postfix_query[0]="3";
            }
            else
            {
                $postfix_name[0]="a";
                $postfix_query[0]="2";
            }
        }

            $postfix_name[1]="";
            $postfix_query[1]="";
            $postfix_transform[0]="";
            $postfix_transform[1]="";
            //$verb_name=$duplication_p2;

            if($stop!=1&&!$flag_e)
            {
                $perfect=get_word($duplication_p2_prefix,$mool_after_duplication,"",$verb_omonim,$verb_type,$verb_change,$verb_ryad,$postfix_name,$postfix_query,$postfix_transform,"1",$verb_setnost,$stop,$debug,"",0)[0];
            }
            else
            {
                $perfect=$duplication_p2_model.$postfix_name[0];
            }

                if($flag_e)
                {
                    
                    if($debug)
                    {
                        echo "<BR><BR>II. Проводим первое чередование под запрос суффикса<BR>";
                    }
                    
                    
                    $perfect=get_word("",$duplication_p2_model,"",$verb_omonim,$verb_type,$verb_change,$verb_ryad,$postfix_name,$postfix_query,$postfix_transform,"1",$verb_setnost,$stop,$debug,$change_later[3]+1,0)[3];
                    $prefix=$duplication_p2_prefix;
                   // $perfect=str_replace("|","",$perfect);
    
    
                    if($debug)
                    {
                        echo "<BR><BR>III. Проводим второе чередование для определения E'<BR>";
                    }
    
                    $perfect=get_word("",$perfect,"",$verb_omonim,$verb_type,$verb_change,$verb_ryad,[''],['1'],[''],"1",$verb_setnost,$stop,$debug,$change_later[2],$flag_e)[3];
    
                    $with_sandhi=simple_sandhi($perfect,$mool_change,"",0)[0];
                    if($debug)
                    {
                    //echo "<BR>После удвоения модель E1EF => чередуем теперь Е1 по Таблице<BR>";
                    echo "<BR><b>Перфект после чередования ($verb_pada) с сандхи: $with_sandhi </b>";
                    echo '<hr class="hr hr-blurry" />';
                    }
        
                    $result[]=$with_sandhi;
                }
                else
                {
                    if($debug)
                    {
                        echo "<BR><b>Перфект после чередования ($verb_pada): $perfect </b>";
                    }
                    $result[]=$perfect;
                }
                //search_in_corpus($perfect);

    }
    else
    {

        

        if($verb_ryad=="A2")
        {
            $postfix_name[0]="āu";
            $postfix_query[0]="1";
        }
        elseif($verb_ryad=="A1")
        {
            
            
            if($duplication_first[3]!=""&&count($duplication_first[6])==1&&seeking_1_bukva($duplication_first[5],0)[1]=="C")
            {
                
                
                $postfix_name[0]="a";
                $postfix_query[0]="3";


                if($debug)
                {
                    echo "<BR>Корень вида PA1С, требование суффикса: + (3)a <BR>";
                }
            }
            else
            {
                if($is_open_mool==1)
                {
                    if($debug)
                    {
                        echo "Корень открытый<BR>";
                    }
                    
                    $postfix_name[0]="a";
                    $postfix_query[0]="3";
                }
                else
                {
                    $postfix_name[0]="a";
                    $postfix_query[0]="2";
                }
            }
        }
        else
        {
           // echo "OPEN MOOL: $is_open_mool";
            
            if($is_open_mool==1)
            {
                $postfix_name[0]="a";
                $postfix_query[0]="3";
            }
            else
            {
                $postfix_name[0]="a";
                $postfix_query[0]="2";
            }
        }

            $postfix_name[1]="";
            $postfix_query[1]="";
            $postfix_transform[0]="";
            $postfix_transform[1]="";

            if($stop!=1&&!$flag_e)
            {
                $string=get_word($duplication_p2_prefix,$mool_after_duplication,"",$verb_omonim,$verb_type,$verb_change,$verb_ryad,$postfix_name,$postfix_query,$postfix_transform,"1",$verb_setnost,$stop,$debug,"",0);
                $perfect=$string[0];
                $rules_sandhi=$string[5];
            }
            else
            {
                $perfect=$duplication_p2_model.$postfix_name[0];
            }

            if($flag_e)
            {
                
                if($debug)
                {
                    echo "<BR><BR>II. Проводим первое чередование под запрос суффикса<BR>";
                }
                
                $perfect=get_word("",$duplication_p2_model,"",$verb_omonim,$verb_type,$verb_change,$verb_ryad,$postfix_name,$postfix_query,$postfix_transform,"1",$verb_setnost,$stop,$debug,$change_later[3]+1,0)[3];
                $prefix=$duplication_p2_prefix;
   
                
                if($position_2=="|"||$position_2=="#")
                {
                    $position_2=$change_later[2]+1;
                }
                else
                {
                    $position_2=$change_later[2];
                }
                
                
                //$change_later[2]
                $perfect=get_word("",$perfect,"",$verb_omonim,$verb_type,$verb_change,$verb_ryad,[''],['1'],[''],"1",$verb_setnost,$stop,$debug,$position_2,$flag_e)[3];

                $with_sandhi=simple_sandhi($perfect,$mool_change,"",0)[0];
                $rules_sandhi=simple_sandhi($perfect,$mool_change,"",0)[1];
                if($debug)
                {
                //echo "<BR>После удвоения модель E1EF => чередуем теперь Е1 по Таблице<BR>";
                echo "<BR><b>Перфект после чередования ($verb_pada) с сандхи: $with_sandhi </b><BR>Применили правила Эмено: $rules_sandhi";
                echo '<hr class="hr hr-blurry" />';
                }
    
                $result[]=$with_sandhi;
            }
            else
            {
                if($debug)
                {
                    echo "<BR><BR><b>Залог: $verb_pada 1 форма перфекта после чередования (3 sg, P): $perfect </b><BR>Применили правила Эмено: $rules_sandhi";
                    echo '<hr class="hr hr-blurry" />';
                    echo "Чередование для второй формы (Атманепада)<BR>";
                }
                $result[]=$perfect;
            }

            $postfix_name[0]="e";
            $postfix_query[0]="1";
            
            $postfix_name[1]="";
            $postfix_query[1]="";
            $postfix_transform[0]="";
            $postfix_transform[1]="";

            if($stop!=1&&!$flag_e)
            {
                $string=get_word($duplication_p2_prefix,$mool_after_duplication,"",$verb_omonim,$verb_type,$verb_change,$verb_ryad,$postfix_name,$postfix_query,$postfix_transform,"1",$verb_setnost,$stop,$debug,"",0);
                $perfect=$string[0];
                $rules_sandhi=$string[5];
            }
            else
            {
                $perfect=$duplication_p2_model.$postfix_name[0];
            }

            //echo "HERE:::".$flag_e1ef.$perfect."<BR><BR>";

            if($flag_e)
            {
              
                if($debug)
                {
                    echo "<BR>Вторая форма (Атманепада)<BR><BR>II. Проводим первое чередование под запрос суффикса<BR>";
                }
                
                
                $perfect=get_word("",$duplication_p2_model,"",$verb_omonim,$verb_type,$verb_change,$verb_ryad,$postfix_name,$postfix_query,$postfix_transform,"1",$verb_setnost,$stop,$debug,$change_later[3]+1,0)[3];
                $prefix=$duplication_p2_prefix;
               

                //$perfect=str_replace("|","",$perfect);


                if($debug)
                {
                    echo "<BR><BR>III. Проводим второе чередование для определения E'<BR>";
                }
                
                $position_2=mb_substr("#".$perfect,$change_later[2],1);
                // echo "CHL: ".$change_later[2]." POS2: $position_2";
                 
                 if($position_2=="|"||$position_2=="#")
                 {
                     $position_2=$change_later[2]+1;
                 }
                 else
                 {
                     $position_2=$change_later[2];
                 }


                //$change_later[2]
                $perfect=get_word("",$perfect,"",$verb_omonim,$verb_type,$verb_change,$verb_ryad,[''],['1'],[''],"1",$verb_setnost,$stop,$debug,$position_2,$flag_e)[3];

                $with_sandhi=simple_sandhi($perfect,$mool_change,"",0)[0];
                if($debug)
                {
                  echo "<BR><b>Перфект после чередования ($verb_pada) с сандхи: $with_sandhi </b><BR>Применили правила Эмено: $rules_sandhi";
           
                }
    
                $result[]=$with_sandhi;
            }
            else
            {
                if($debug)
                {
                    echo "<BR><BR><b>Залог: $verb_pada 2 форма перфекта после чередования (3 sg, A): $perfect </b><BR>Применили правила Эмено: $rules_sandhi";
                    
                }
                $result[]=$perfect;
            }

            //search_in_corpus($perfect);

    }
    
    //echo get_e_mp_table4("iis",$verb_omonim,$verb_type,"i",$verb_ryad,1,1);

   // print_r($result);
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

function get_desiderative($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,$is_open_mool,$verb_setnost,$debug)
{

		$duplication_first=duplication_first($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$debug);

        $d2=duplication_d2($duplication_first,$verb_name,$verb_type,$verb_ryad,$verb_omonim,$is_open_mool,$verb_setnost,$debug);

		$duplication_d2_model=$d2[0];
        $double=$d2[1];

        if($duplication_d2_model)
        {
            $model_string=implode(",",$duplication_d2_model);
        }
        else
        {
            $model_string="В языке такая форма не встречается";
        }


        if($double)
        {
            $double_string=implode(",",$double);
        }
        else
        {
            $double_string="В языке такая форма не встречается";
        }

        $result[0]=$model_string;
        $result[1]=$double_string;

        return $result;


}

function get_intensive($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,$is_open_mool,$verb_setnost,$debug)
{

		
    //echo "<b>Основа интенсива:</b> ";

    $duplication_first=duplication_first($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$debug);

    $duplication_i2_model=duplication_i2($duplication_first,$verb_name,$verb_type,$verb_ryad,$verb_omonim,$debug)[0];
    $duplication_i2_model_2=duplication_i2($duplication_first,$verb_name,$verb_type,$verb_ryad,$verb_omonim,0)[1];
    $duplication_i2_model_3=duplication_i2($duplication_first,$verb_name,$verb_type,$verb_ryad,$verb_omonim,0)[2];
            
    //echo "<b>";
    //if($duplication_i2_model){echo $duplication_i2_model;echo " ";}
    //if($duplication_i2_model_2){echo $duplication_i2_model_2;echo " ";}
    //if($duplication_i2_model_3){echo $duplication_i2_model_3;echo " ";}

   // echo "</b>";

    return $duplication_i2_model;


}

function get_aos3($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,$is_open_mool,$verb_setnost,$debug)
{
    $duplication_first=duplication_first($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$debug);

		$duplication_a2_model=duplication_a2($duplication_first,$verb_name,$verb_type,$verb_ryad,$verb_omonim,$debug)[0];
		//$duplication_i2_model_2=duplication_i2($duplication_first,$verb_name,$verb_type,$verb_ryad,$verb_omonim,0)[1];
		//$duplication_i2_model_3=duplication_i2($duplication_first,$verb_name,$verb_type,$verb_ryad,$verb_omonim,0)[2];
				
		//echo "<b>";
		//if($duplication_a2_model){echo $duplication_a2_model;echo " ";}


        return $duplication_a2_model;
}


function get_word($prefix, $mool, $postfix, $mool_number, $mool_type, $mool_change, $mool_type_change, $suffix, $suffix_ask, $suffix_transform, $glagol_or_imennoy, $verb_setnost, $stop, $debug, $e_manual,$flag_e) {

    $consonants = ["k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s", "h"];
    $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "ai", "o", "au"];
    $gubnye = ["p", "ph", "b", "bh", "m"];

    if($flag_e)
    {
    
       if(mb_substr($mool,$e_manual,1)=="|")
       {
            $e_manual_next=$e_manual+1;
            
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

    if ($debug) {
        $debug_text .= "<BR><b>Чередование</b><BR><BR>";
        $debug_text .= $mool . $postfix . " + ($suffix_ask[0])" . $suffix[0];

        if ($suffix[1]) {
            $debug_text .= " + ($suffix_ask[1])" . $suffix[1];
        }

        $debug_text .= "<br><br>";
    }

    $is_open_mool = 0;
    if (mb_substr($mool, -1, 1) == "Ø" || mb_substr($mool, -2, 2) == "Ø̄" || mb_substr($mool, -1, 1) == $mool_change || mb_substr($mool, -2, 2) == $mool_change || mb_substr($mool, -3, 3) == $mool_change ) {
        $is_open_mool = 1;
    }



    for($i=0;$i<count($suffix_ask);$i++)
    {
        if(strpos($suffix_ask[$i],"/"))
        {
            $ask_array=explode("/",$suffix_ask[$i]);
            if($is_open_mool==1)
            {
                $suffix_ask[$i]=$ask_array[0];
                if ($debug) {$debug_text .="Корень открытый, запрос суффикса: ".$suffix_ask[$i]."<BR>";}
            }
            else
            {
                $suffix_ask[$i]=$ask_array[1];
                if ($debug) {$debug_text .="Корень закрытый, запрос суффикса: ".$suffix_ask[$i]."<BR>";}
            }

           
        }
    }

    if ($mool_number) {
        $dop = "омоним номер $mool_number, ";
    }

    if ($debug) {
        $debug_text .= "Корень '$mool' $dop $mool_type типа, суффикс " . $suffix[0] . " требует " . $suffix_ask[0] . " морфологической позиции. Корень '$mool' в ответ отдаёт ступень типа " . $answer[$suffix_ask[0]] . " <BR>";
    }

    $word_1 = $prefix.$mool.$postfix.$suffix[0].$suffix[1];

    if ($debug) {

        $debug_text .= "<br>";

        $debug_text .= "Смотрим по 4 таблице для ступени типа " . $answer[$suffix_ask[0]] . "<BR>";
        $debug_text .= "<br>";
        $debug_text .= "До преобразования: $prefix$word_1 <BR>";
    }


    $dimensions = dimensions($word_1, $mool_change, $mool, 1, 0, 0, $e_manual);
    $dimensions_array = dimensions_array($dimensions);
    //print_r(dimensions_array($dimensions));


    if ($debug) {
        $debug_text .= "<BR>" . dimensions_table($dimensions);
    }

   

    $ep = mb_strpos($word_1, $mool_change);

    if ($answer[$suffix_ask[0]] == "0") {



        $line_2 = "E" . find_bukvi($dimensions[1], 1, "E", 1);

        //echo "FIND: ".find_bukvi($dimensions[1], 1, "E", 1);

        if ($line_2 == "EC") {

            $line_3 = ey_or_not($word_1, $ep, $mool_change);

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
                            //echo "HERE";
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
            $line_4 = find_bukvi($dimensions[2], 2, "E", -1) . "E";

           // echo "DIMENS: ".$dimensions[0]." E_MANUAL_NEXT: $e_manual_next MBSUBSTR_HERE:".mb_substr($dimensions[0],$e_manual_next,1)."<BR>";
            if(mb_substr($dimensions[0],$e_manual_next,1)=="|")
            {
                $e_manual_next++;
            }

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
                        //без анусвар висарг придыхательных
                        case "#vE":$itog = "am";
                            break;
                        case "#NE":$itog = "am";
                            break;
                        case "#SE":$itog = "am";
                            break;

                        case "SE":$itog = "am";
                            break;

                        case "TTE":$itog = "am";
                            break;
                        case "vTE":$itog = "am";
                            break;
                        case "NTE":$itog = "am";
                            break;
                        case "STE":$itog = "am";
                            break;

                        case "VTE":$itog = "m";
                            break;
                        case "#TE": $itog = "m";
                            break;
                        // ksm-, hn-
                    };
                    break;

                case "M2": switch ($line_4) {
                        //без анусвар висарг придыхательных
                        case "#vE":$itog = "am";
                            break;
                        case "#NE":$itog = "am";
                            break;
                        case "#SE":$itog = "am";
                            break;

                        case "SE":$itog = "am";
                            break;
                        case "TSE":$itog = "am";
                            break;
                        case "NSE":$itog = "am";
                            break;
                        case "vSE":$itog = "am";
                            break;
                        case "SSE":$itog = "am";
                            break;
                        case "VSE":$itog = "am";
                            break;
                        case "TTE":$itog = "am";
                            break;
                        case "vTE":$itog = "am";
                            break;
                        case "NTE":$itog = "am";
                            break;
                        case "STE":$itog = "am";
                            break;

                        case "VTE":$itog = "m";
                            break;
                        case "#TE": $itog = "m";
                            break;
                        //( ksm-, hn-
                    };
                    break;

                case "N1": switch ($line_4) {
                        //без анусвар висарг придыхательных



                        case "#vE":$itog = "an";
                            break;
                        case "#NE":$itog = "an";
                            break;
                        case "#SE":$itog = "an";
                            break;

                        case "SE":$itog = "an";
                            break;

                        case "TTE":$itog = "an";
                            break;
                        case "vTE":$itog = "an";
                            break;
                        case "NTE":$itog = "an";
                            break;
                        case "STE":$itog = "an";
                            break;

                        case "VTE":$itog = "n";
                            break;
                        case "#TE": $itog = "n";
                            break;
                        // ksm-, hn-
                    };
                    break;

                case "N2": switch ($line_4) {
                        //без анусвар висарг придыхательных
                        case "#vE":$itog = "an";
                            break;
                        case "#NE":$itog = "an";
                            break;
                        case "#SE":$itog = "an";
                            break;

                        case "SE":$itog = "an";
                            break;

                        case "TTE":$itog = "an";
                            break;
                        case "vTE":$itog = "an";
                            break;
                        case "NTE":$itog = "an";
                            break;
                        case "STE":$itog = "an";
                            break;

                        case "VTE":$itog = "n";
                            break;
                        case "#TE": $itog = "n";
                            break;
                        //( ksm-, hn-
                    };
                    break;
            }
        }

    } elseif ($answer[$suffix_ask[0]] == "g") {
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
    } elseif ($answer[$suffix_ask[0]] == "v") {

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
        $line_2 = "<font color=red><b>Что-то пошло не так ¯\_(ツ)_/¯ где-то опечатка</b></font>";
    }


    if ($debug) {
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
            $count_change = 3;
        } elseif ($mool_change == "Ø̄" || $mool_change == "m̥" || $mool_change == "n̥") {
            $count_change = 2;
        } else {
            $count_change = 1;
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
           }
           echo "EMANUAL: ".$e_manual."<BR><BR>";
           */
            
            $new_word = mb_substr_replace($mool,$itog,$e_manual-1,mb_strlen($mool_change));
            $new_word_sandhi = mb_substr_replace($mool,"|" . $itog . "|",$e_manual-1,mb_strlen($mool_change));
            $new_word_sandhi2 = mb_substr_replace($mool,"|" . $itog . "|",$e_manual-1,mb_strlen($mool_change));

         
        }

       
        
    }

    if ($debug) {
        $debug_text .= "Чередование: $mool -> $new_word ($mool_change меняется на $itog) <BR>";
    }

    //Убираем нули?

    $new_word = str_replace("Ø", "", $new_word);
    $new_word = str_replace("Ø̄", "", $new_word);
    $new_word = str_replace("Ø̄", "", $new_word);
    $new_word = str_replace("¯", "", $new_word);

    if ($suffix_transform[0] == "~" || $suffix_transform[0] == "^") {
        if ($debug) {
            $debug_text .= "Выполним межрядовую трансформацию [" . $suffix_transform[0] . "] : ";
        }

        switch ($suffix_transform[0]) {
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

            //$vowels=["a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","ai","o","au"];
            //"Ø̄","Ø","m̥̄","m̥","n̥","n̥̄"
        }


        if ($debug) {
            $debug_text .= "Заменили $itog на $itog_transform";
        }
    }

    $find_arrow = explode("↦", $suffix_transform[0]);

    if ($find_arrow[0] == substr($mool_type_change, 0, 1) || $find_arrow[0] == substr($mool_type_change, 0, 1) . "Ø" || $find_arrow[0] == $mool_type_change || $find_arrow[0] == $mool_type_change . "Ø") {
        if ($debug) {
            $debug_text .= "Выполним межрядовую трансформацию [" . $suffix_transform[0] . "] : ";
        }

        $find_zero = mb_strpos($find_arrow[0], "Ø");

        if ($debug) {
            if ($find_zero == 0) {
                echo "трансформация только для открытых корней.";
            }
        }

       

        if ($debug) {

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
                    switch ($answer[$suffix_ask[0]]) {
                        case "0":$itog_transform = "Ø";
                            break;
                        case "g":$itog_transform = "a";
                            break;
                        case "v":$itog_transform = "ā";
                            break;
                    }
                    break;

                case "A2":
                    switch ($answer[$suffix_ask[0]]) {
                        case "0":$itog_transform = "Ø̄";
                            break;
                        case "g":$itog_transform = "ā";
                            break;
                        case "v":$itog_transform = "ā";
                            break;
                    }
                    break;

                case "I":
                    ///??????////
                    switch ($answer[$suffix_ask[0]]) {
                        //case "0":$itog_transform="Ø";break;
                        case "g":$itog_transform = "e";
                            break;
                        case "v":$itog_transform = "ai";
                            break;
                    }
                    break;

                case "U":
                    ///??????////
                    switch ($answer[$suffix_ask[0]]) {
                        ///case "0":$itog_transform="Ø";break;
                        case "g":$itog_transform = "o";
                            break;
                        case "v":$itog_transform = "au";
                            break;
                    }
                    break;

                case "R":
                    ///??????////
                    switch ($answer[$suffix_ask[0]]) {
                        ///case "0":$itog_transform="Ø";break;
                        case "g":$itog_transform = "ar";
                            break;
                        case "v":$itog_transform = "ār";
                            break;
                    }
                    break;

                case "L":
                    ///??????////
                    switch ($answer[$suffix_ask[0]]) {
                        ///case "0":$itog_transform="Ø";break;
                        case "g":$itog_transform = "al";
                            break;
                        case "v":$itog_transform = "āl";
                            break;
                    }
                    break;

                case "M":
                    ///??????////
                    switch ($answer[$suffix_ask[0]]) {
                        ///case "0":$itog_transform="Ø";break;
                        case "g":$itog_transform = "am";
                            break;
                        case "v":$itog_transform = "ām";
                            break;
                    }
                    break;

                case "N":

                    ///??????////
                    switch ($answer[$suffix_ask[0]]) {
                        ///case "0":$itog_transform="Ø";break;
                        case "g":$itog_transform = "an";
                            break;
                        case "v":$itog_transform = "ān";
                            break;
                    }
                    break;

                default: $itog_transform = $itog;
            }

            if ($debug) {
                $debug_text .= "Заменили $itog на $itog_transform";
            }
        } else {
            $itog_transform = $itog;
        }
    } else {
        $itog_transform = $itog;
    }

    

        $new_word = str_replace($itog, $itog_transform, $new_word);
        $new_word_sandhi = str_replace($itog, $itog_transform, $new_word_sandhi);
    

    $dimensions_mool = dimensions($new_word, "smth", "somenthing", 1, 0, 0,"");  //ai & au будут отображаться отдельными символами
    $dimensions_mool_array = dimensions_array($dimensions_mool);

    $dlina_kornya = strlen($dimensions_mool[1]);

    $mool_last_letter = $dimensions_mool_array[$dlina_kornya - 1][0];
    $mool_last_letter2 = $dimensions_mool_array[$dlina_kornya - 2][0];

    $mool_last_cons = $dimensions_mool_array[$dlina_kornya - 1][1];
    $mool_last_vzryv = $dimensions_mool_array[$dlina_kornya - 1][2];

    $seek_last_letter = seeking_1_bukva($mool_last_letter, 0);


    if ($seek_last_letter[1] == "C" || $mool_last_cons == "C" || $mool_last_letter == "e" || $mool_last_letter == "o" || ($mool_last_letter2 == "a" && $mool_last_letter == "u") || ($mool_last_letter2 == "a" && $mool_last_letter == "i")) { // Если на конце корня взрывная или сибилянт - смотрим еще сетность

        $type_set_forms = array();

        switch ($verb_setnost) {

            case "s":$type_set_forms[0] = "s";
                $type_set_forms[1] = "s";
                $type_set_forms[2] = "s";
                $type_set_forms[4] = "s";
                break;
            case "a":$type_set_forms[0] = "a";
                $type_set_forms[1] = "a";
                $type_set_forms[2] = "a";
                $type_set_forms[4] = "a";
                break;
            case "v":$type_set_forms[0] = "v";
                $type_set_forms[1] = "v";
                $type_set_forms[2] = "v";
                $type_set_forms[4] = "v";
                break;

            case "v1":$type_set_forms[0] = "v";
                $type_set_forms[1] = "v";
                $type_set_forms[2] = "a";
                $type_set_forms[4] = "v";
                break;
            case "v2":$type_set_forms[0] = "s";
                $type_set_forms[1] = "s";
                $type_set_forms[2] = "v";
                $type_set_forms[4] = "v";
                break;
            case "v3":$type_set_forms[0] = "s";
                $type_set_forms[1] = "s";
                $type_set_forms[2] = "a";
                $type_set_forms[4] = "v";
                break;
            case "v4":$type_set_forms[0] = "v";
                $type_set_forms[1] = "a";
                $type_set_forms[2] = "a";
                $type_set_forms[4] = "a";
                break;
            case "v5":

                if ($suffix_ask[0] == 1) {
                    $type_set_forms[0] = "a";
                    $type_set_forms[1] = "a";
                    $type_set_forms[2] = "a";
                    $type_set_forms[4] = "a";
                } else {
                    $type_set_forms[0] = "s";
                    $type_set_forms[1] = "s";
                    $type_set_forms[2] = "s";
                    $type_set_forms[4] = "s";
                }
                break;
        }

        ///



        $first_letter_suffix = mb_substr($suffix[0], 0, 1);

        $FLAG_NEED_SET = 0;
        if ($first_letter_suffix == "s" || $first_letter_suffix == "t") {
            $FLAG_NEED_SET = 1;
        }

        //if($verb_setnost=="0"){$verb_setnost=" <b>в языке такой формы не встречается</b>";}

        if ($FLAG_NEED_SET == 1) {

            if ($suffix[0] == "sya") {
                if ($verb_setnost == "0") {
                    $FLAG_NO_FORM = 1;
                } else {

                    if ($debug) {
                        $debug_text .= "Сетность: $verb_setnost, смотрим для FuS, основы будущего времени: сетность ";
                    }

                    $set_letter = $type_set_forms[0];

                    if ($debug) {
                        $debug_text .= "<b>$set_letter</b>";
                    }
                }
            } else {
                if ($debug) {
                    $debug_text .= "<BR>Сетность: $verb_setnost, пока не умеем образовывать сложные формы, берем сетность по умолчанию как для основы деепричастия: ";
                }

                $set_letter = $type_set_forms[4];

                if ($debug) {
                    $debug_text .= "<b>$set_letter</b>";
                }
            }

            if ($set_letter == "s") {
                $insert_i = "|i|";
            }
            if ($set_letter == "a") {
                $insert_i = "";
            }
            if ($set_letter == "v") {
                $insert_i = "|i|";
                $insert_i2 = "";
                $flag_vet = 1;
            }




            if ($set_letter == "s" && ($first_letter_suffix == "s" || $first_letter_suffix == "t")) {

                $new_word_string = $new_word . "|i|";
                $new_word_sandhi = $new_word_sandhi . "|i|";
            } elseif ($set_letter == "v" && ($first_letter_suffix == "s" || $first_letter_suffix == "t")) {

                $new_word_string = $new_word . "|i|";
                $new_word_sandhi = $new_word_sandhi . "|i|";

                $new_word_string2 = $new_word . "|";
                $new_word_sandhi2 = $new_word_sandhi2 . "|";


            } else {
                $new_word_string = $new_word;
            }
        } else {
            $new_word_string = $new_word;
        }
    } else {
        $new_word_string = $new_word;
    }


    if ($suffix[1]) {
            $two_suff_string = $suffix[0] . "|" . $suffix[1];

            $dimensions_suffix = dimensions($two_suff_string, "smth", "somenthing", 0, 0, 1,"");  //ai & au будут отображаться отдельными символами
            $dimensions_suffix_array = dimensions_array($dimensions_suffix);

            $offset = 0;
            $allpos_suff = array();
            while (($pos = mb_strpos($dimensions_suffix[1], '|', $offset)) !== false) {
                $offset = $pos + 1;
                $allpos_suff[] = $pos - 1;
            }

            //echo "Все вхождения стыков |: "; print_r($allpos_suff);

            $position_suff_styk = $allpos_suff[0];
            $first_cons = $dimensions_suffix_array[$position_suff_styk][1];

            $second_suff = $position_suff_styk + 1;
            $second_suff_letter = $dimensions_suffix_array[$second_suff][0];

            if ($second_suff_letter == "|") {
                $second_suff = $second_suff + 1;
                $second_suff_letter = $dimensions_suffix_array[$second_suff][0];
            }

            $second_vzryv = $dimensions_suffix_array[$second_suff][2];

            //Если на стыке суффиксов образуется сочетание двух согласных, при этом второй суффикс начинается не на носовой или полугласный, то возникает i

            if ($first_cons == "C" && ($second_vzryv == "T" || $second_vzryv == "S")) {
                $set_suffix = "|i|";
                if ($debug) {
                    $debug_text .= "<BR>На стыке между суффиксами будет сетная i";
                }
            } else {
                $set_suffix = "||";
            }




            $new_word_string_sandhi = $new_word_sandhi . "|" . $suffix[0] . $set_suffix . $suffix[1];
            $final_string = $new_word_string . "" . $suffix[0] . $set_suffix . $suffix[1];
            $final_string = str_replace("|", "", $final_string);

            if ($new_word_string2) {
                $new_word_string_sandhi2 = $new_word_sandhi2 . "|" . $suffix[0] . $set_suffix . $suffix[1];
                $final_string2 = $new_word_string2 . "" . $suffix[0] . $set_suffix . $suffix[1];
                $final_string2 = str_replace("|", "", $final_string2);
            }

        } else {
            $new_word_string_sandhi = $new_word_sandhi . "|" . $suffix[0];
            $final_string = $new_word_string . "" . $suffix[0];
            $final_string = str_replace("|", "", $final_string);

            if ($new_word_string2) {
                $new_word_string_sandhi2 = $new_word_sandhi2 . "|" . $suffix[0];
                $final_string2 = $new_word_string2 . "" . $suffix[0];
            }
        }

    $new_word_string_sandhi = str_replace("Ø̄", "", $new_word_string_sandhi);
    $new_word_string_sandhi = str_replace("Ø", "", $new_word_string_sandhi);

    $new_word_string_sandhi = str_replace("||", "|", $new_word_string_sandhi);
    $new_word_string_sandhi = str_replace("||", "|", $new_word_string_sandhi);

    $new_word_string_sandhi2 = str_replace("Ø̄", "", $new_word_string_sandhi2);
    $new_word_string_sandhi2 = str_replace("Ø", "", $new_word_string_sandhi2);
  
    $new_word_string_sandhi2 = str_replace("||", "|", $new_word_string_sandhi2);
    $new_word_string_sandhi2 = str_replace("||", "|", $new_word_string_sandhi2);

    $prefix = str_replace("Ø", "", $prefix);
    $final_string = str_replace("¯", "", $final_string);
  

    

    if ($debug) {
        $debug_text .= "<BR><b>Итог (без сандхи): " . $prefix.$final_string;
        $debug_text .= "</b>";
    }

    if ($new_word_string2) {
        if ($debug) {
            $debug_text .= " также возможна форма <b>$prefix$final_string2</b> , т.к. сетность корня v <BR>";
        }
    }

    

    $new_word_string_sandhi=$prefix."|".$new_word_string_sandhi;
    $new_word_string_sandhi2=$prefix."|".$new_word_string_sandhi2;

    if($new_word_string_sandhi2){$new_word_string_sandhi2=$prefix."|".$new_word_string_sandhi2;}

    $new_word_string_sandhi = str_replace("||", "|", $new_word_string_sandhi);
    $new_word_string_sandhi = str_replace("|̄|", "|", $new_word_string_sandhi);
    $new_word_string_sandhi = str_replace("|̄|", "|", $new_word_string_sandhi);

    $new_word_string_sandhi2 = str_replace("||", "|", $new_word_string_sandhi2);
    $new_word_string_sandhi2 = str_replace("|̄|", "|", $new_word_string_sandhi2);
    $new_word_string_sandhi2 = str_replace("Ø", "", $new_word_string_sandhi2);
    $new_word_string_sandhi2 = str_replace("Ø̄", "", $new_word_string_sandhi2);

  
    
    //echo "Строчка для сандхи: ".$new_word_string_sandhi; echo "<BR><BR>";
    //echo "Строчка для сандхи2: ".$new_word_string_sandhi2; echo "<BR><BR>";


    /*


    $dimensions_end = dimensions($new_word_string_sandhi, $itog, $mool, 0, 0, 0,"");  //ai & au будут отображаться отдельными символами
    $dimensions_end_array = dimensions_array($dimensions_end);

    $sandhi = sandhi($dimensions_end, $dimensions_end_array, $new_word_string_sandhi, $mool, 1, 0, 0);

    

    $result1 = $sandhi[0];

    */
    $sandhi = simple_sandhi($new_word_string_sandhi,$mool,"",0);
    $result1=$sandhi[0];
    $rules1=$sandhi[1];

   /*
    if ($debug) {
        $debug_text .= "<BR><b>Итог с сандхи (знаем " . $sandhi[3] . " правил для внутренних из 40): " . $sandhi[0];
        $debug_text .= "</b> ";
    }

    if ($sandhi[1]) {
        if ($debug) {
            $debug_text .= "<BR>Применили правила Эмено: ";
            $debug_text .= $sandhi[1];
        }
    }
    */

    if ($new_word_string2&&$new_word_string_sandhi2!=""&&$new_word_string_sandhi2!="|") {

        $sandhi = simple_sandhi($new_word_string_sandhi2,$mool,"",0);

       
/*
        if ($debug) {
            $debug_text .= "<BR><BR><b>Вторая форма с сандхи: " . $sandhi[0];
            $debug_text .= "</b> ";

            if ($sandhi[1]) {
                $debug_text .= "<BR>Применили правила Эмено: ";
                $debug_text .= $sandhi[1];
            }
        }
*/
        

        $result2 = $sandhi[0];
        $rules2=$sandhi[1];
    }

    
    }
    else
    {
        $result1=$mool.$suffix[0].$suffix[1];
    }
   
    $result[0] = $result1;
    $result[1] = $result2;
    $result[2] = $itog_transform;

    //echo "ITOG itog_transform: ".$result[2];

    $result[3] = $new_word_string_sandhi;
    $result[4] = $new_word_string_sandhi2;
    $result[5] = $rules1;
    $result[6] = $rules2;

    if ($FLAG_NO_FORM == 1) {
        $result[0] = "Нет формы";

        if ($debug) {
            echo "<b>В языке такая форма не встречается</b>";
        }
    } else {
        if ($debug) {
            echo $debug_text;
        }
    }

    return $result;
}

function ey_or_not($word_1, $ep, $mool_change) {
    
    
    $word_1=str_replace("|","",$word_1);
    
    if ($mool_change == "m̥̄" || $mool_change == "n̥̄") {
        $count = 3;
    } elseif ($mool_change == "m̥" || $mool_change == "n̥" || $mool_change == "Ø̄") {
        $count = 2;
    } else {
        $count = 1;
    }


    $next_bukva = mb_substr($word_1, $ep + $count, 1);

    if ($next_bukva == "y") {
        $line_3 = "Ey";
    } else {
        $line_3 = "Eне-y";
    }

    //echo "EY$next_bukva<BR>$line_3";

    return $line_3;
}

function ev_or_not($word_1, $ep, $mool_change) {

    $word_1=str_replace("|","",$word_1);

    if ($mool_change == "m̥̄" || $mool_change == "n̥̄") {
        $count = 3;
    } elseif ($mool_change == "m̥" || $mool_change == "n̥" || $mool_change == "Ø̄") {
        $count = 2;
    } else {
        $count = 1;
    }

    $next_bukva = mb_substr($word_1, $ep + $count, 1);

    if ($next_bukva == "v") {
        $line_3 = "Ev";
    } else {
        $line_3 = "Eне-v";
    }
    //echo "EV$next_bukva<BR>$line_3";
    return $line_3;
}

function em_or_not($word_1, $ep, $mool_change) {

    $word_1=str_replace("|","",$word_1);

    if ($mool_change == "m̥̄" || $mool_change == "n̥̄") {
        $count = 3;
    } elseif ($mool_change == "m̥" || $mool_change == "n̥" || $mool_change == "Ø̄") {
        $count = 2;
    } else {
        $count = 1;
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
    $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "ai", "o", "au"];
    $gubnye = ["p", "ph", "b", "bh", "m"];

    $before_bukva = mb_substr($word_1, $ep - 2, 1);
    $before_2bukva = mb_substr($word_1, $ep - 3, 2);
    $before_3bukva = mb_substr($word_1, $ep - 4, 3);

    $itog[0] = $before_bukva;
    $itog[1] = "";
    $itog[2] = "";

    for ($i = 0; $i < count($consonants); $i++) {
        if ($before_bukva == $consonants[$i]) {
            $itog[1] = "C"; //Cons.
        }
    }

    for ($i = 0; $i < count($vowels); $i++) {
        if ($before_bukva == $vowels[$i]) {
            $itog[1] = "V"; //Vow.
        }
    }

    for ($i = 0; $i < count($gubnye); $i++) {

        if ($before_bukva == $gubnye[$i] || $before_2bukva == $gubnye[$i]) {
            $itog[2] = "G"; //gubnye
        }
    }
    //print_r($itog);
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


    //print_r($vc);


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

            if ($mool_change == "al" || $mool_change == "an" || $mool_change == "am" || $mool_change == "ar" || $mool_change == "ai") {
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


    //print_r($vc);


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

            if ($mool_change == "al" || $mool_change == "an" || $mool_change == "am" || $mool_change == "ar" || $mool_change == "ai") {
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
            if ($mool_change == "al" || $mool_change == "an" || $mool_change == "am" || $mool_change == "ar" || $mool_change == "āi" || $mool_change == "āu") {
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

            if ($mool_change == "al" || $mool_change == "an" || $mool_change == "am" || $mool_change == "ar" || $mool_change == "ai") {
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

    $result=get_word($prefix,$word,$postfix,$verb_omonim,$verb_type,$letter_e,$verb_ryad,[''],[$mp],[''],"1",0,0,0,$number_of_e,0)[2];


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

    // $head_table.='<th scope="col"></th>';

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
            //echo "<BR>";
            //print_r($array);
            //echo "<BR>";
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
                }
            }
            break;
        case "6":
            //6 Эмено

            if ($first_letter != $second_letter && $second_cons == "V") {

                //echo "ZL: ".$zero_letter;

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
                //echo "zero:$zero_letter first:$first_letter second:$second_letter<BR>";
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
    
                       // $result[3]=$first_number-1;
                        $count_change = 1;
                       // $result[5] = 1;
                        $pravilo = 22;
                   }
                   elseif($second_letter=="t")
                   {
                        $itog[0]="ṣ";
                       // $result[3]=$first_number-1;
                        $count_change = 1;
                       // $result[5] = 1;
                        $pravilo = 22;
                   }
                   else
                   {
                        $itog[0]="ṭ";
                      //  $result[3]=$first_number-1;
                        $count_change = 1;
                       // $result[5] = 1;
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

                                  //  $result[3]=$first_number-1;
                                    $count_change = 1;
                                  //  $result[5] = 1;
                                    $pravilo = 21;
                            }
                            elseif($second_letter=="t")
                            {
                                    $itog[0]="ṣ";

                               //     $result[3]=$first_number-1;
                                    $count_change = 1;
                                //    $result[5] = 1;
                                    $pravilo = 21;
                            }
                            else
                            {
                                    $itog[0]="ṭ";

                                  //  $result[3]=$first_number-1;
                                    $count_change = 1;
                                  //  $result[5] = 1;
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
           // echo "HERE RW: ".$right_word;
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

                //print_r($big_array[6]);
                $itog[0] = $first_letter;
                $itog[1] = "ṣ";
                $count_change = 2;
                $what_change = 0;
                $pravilo = 36;
            }

            if(($first_letter=="ḥ"||$first_letter=="ṃ")&&$mool!="puṃs"&&$mool!="hiṃs")
            {
                if ($second_letter == "s" && $third_letter != "r" && (($zero_cons == "V" && $zero_letter != "a" && $zero_letter != "ā") || $zero_letter == "k" || $zero_letter == "r" || $zero_letter == "l")) {

                    //print_r($big_array[6]);
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
            
            if($first_letter=="n"&&($zero_vzryv=="v"||$zero_vzryv=="V"||$zero_vzryv=="N"))
            {
                $s_search=mb_strrpos($active_word,"ṣ");
                $r_search=mb_strrpos($active_word,"r");
                $rr_search=mb_strrpos($active_word,"ṛ");
                $rrr_search=mb_strrpos($active_word,"ṝ");


                $one_massive=[$s_search,$r_search,$rr_search,$rrr_search];
                $max=max($one_massive);

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

            if ($second_letter == "n" && $first_vzryv == "T" && $first_where = "P") {

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
    
   // print_r($big_array[6]);
   // echo "<BR>";
    //print_r($result[8]);

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

    if ($debug) {
        echo "Все вхождения стыков |: ";
        print_r($allpos_cons);
        echo "<BR><BR>";
    }
        $string=implode($big_array[6]);
        $parts=explode("|",$string);


    $k = 1;$emeno[5]=0;$emeno[6]=0;
    for ($i = count($allpos_cons) - 1; $i >= 0; $i--) {

        if ($debug) {
            echo "<BR>Разбор сандхи №$k с конца. Исходная строчка: <b>" . $result[0] . "</b><BR>";
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
                }
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
    echo "<BR><BR>";

    }

    return $sandhi;


}

function sandhi_reconstruct($find_cc, $enter_text, $itog, $count_change, $count, $debug, $big_array, $what_change) {
    //print_r($big_array);
    if ($debug) {
        echo "Номер вхождения: " . $find_cc . "+ $what_change На что меняется: $itog  Сколько символов поменяется: $count_change<BR>";
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

       // print_r($big_array[6]);

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

?>