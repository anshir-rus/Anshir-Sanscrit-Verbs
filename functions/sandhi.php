<?php


function rule34($mool)
{
    $plus = 0;

    if (in_array($mool, ["dah", "dih", "duh", "druh", "dṛṃh"])) {
        $plus = "dh";
    } elseif ($mool === "guh") {
        $plus = "gh";
    } elseif (in_array($mool, ["bn̥dh", "bādh", "bandh", "budh"])) {
        $plus = "bh";
    }

    return $plus;
}


/*
// PHP 8 Style
function rule34($mool)
{
    $plus = match ($mool) {
        "dah", "dih", "duh", "druh", "dṛṃh" => "dh",
        "guh" => "gh",
        "bn̥dh", "bādh", "bandh", "budh" => "bh",
        default => 0,
    };

    return $plus;
}

*/


function emeno_rules($number, $array, $big_array, $word_length, $zero_number, $first_number, $second_number, $big_array_1, $mool, $glagol_or_imennoy,$last_perenos,$active_word,$right_word, $padezh, $third_number) {
    
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

   // echo "$word_length SEC NUMBER  $second_number SEC:$second_letter "."THIRD:".$third_letter." THR NUMBER $third_number $big_array_1<BR>";

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
                    case "e":$itog[0] = "ay";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "o":$itog[0] = "ay";
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
                    case "e":$itog[0] = "av";
                        $count_change = 1;
                        $pravilo = 3;
                        break;
                    case "o":$itog[0] = "av";
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
                
                //echo "ZERO $zero_letter ZN: $zero_number FIRST LETTER: $first_letter SECOND LETTER: $second_letter<BR>";

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

          
                if ($mool=="nah"&&$first_letter=="h"&&(($second_cons=="C"&&$second_vzryv!="v"&&$second_vzryv!="N")||(!$second_letter&&$first_letter=="h"&&$zero_letter=="a"))) {
                    
                    //echo "Fl: $first_letter Sl: $second_letter SC: $second_cons SV: ".$second_vzryv." SW: $second_where<BR>";
                    
                    $itog[0] = "dh";
                    $count_change = 1;
                    $pravilo = 17;
                }
                break;
        
        case "18": // что значит "факультативно" ? 
                    $mool_conditions = ["uṣṇih", "druh", "snih", "muh"];
                    $is_mool_match = substr($mool, 0, 1) == "d" || in_array($mool, $mool_conditions);
                    $is_valid_second = ($second_cons == "C" && !in_array($second_vzryv, ["v", "N"])) || !$second_letter;
                
                    if ($first_letter == "h" && $is_mool_match && $is_valid_second) {
                        $itog[0] = "gh";
                        $count_change = 1;
                        $pravilo = 18;
                
                        if ($second_zvonkiy == "V") {
                            $result[4] = 2;
                        }
                    }
                    break;
                
        case "19":
                    $nochange = 0;
                
                    $is_h_case = ($first_letter == "h");
                    $is_c_case = ($second_cons == "C" && $second_vzryv != "v" && $second_vzryv != "N");
                    $is_special_second_letter = in_array($second_letter, ["t", "th", "dh"]);
                    $is_mool_special = in_array($mool, ["vah", "sah"]);
                
                    if ($is_h_case && ($is_c_case || !$second_letter)) {
                        if ($is_special_second_letter) {
                            $new_letter_map = ["a" => "ā", "i" => "ī", "u" => "ū"];
                            $new_letter = $new_letter_map[$zero_letter] ?? $zero_letter;
                
                            if (!$is_mool_special) {
                                $itog = [$new_letter, "ḍh", "Ø"];
                            } else {
                                $itog = ["o", "ḍh", "Ø"];
                            }
                            $nochange = 1;
                            $count_change = 3;
                            $result[3] = $first_number - 1;
                            $what_change = 0;
                            $pravilo = 19;
                        } else {
                            $itog[0] = $glagol_or_imennoy == 1 ? "k" : "ṭ";
                            $count_change = 1;
                            $pravilo = 19;
                        }
                
                        if ($itog[0] && !$nochange && rule34($mool)) {
                            $result[6] = rule34($mool);
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
                // 33 Эмено
            
                $trigger_condition = in_array($second_vzryv, ["T", "S"]) || !$second_letter;
                $replace_map = [
                    "kh" => "k", "ch" => "c", "ṭh" => "ṭ", "th" => "t",
                    "ph" => "p", "gh" => "g", "jh" => "j", "ḍh" => "ḍ",
                    "dh" => "d", "bh" => "b"
                ];
            
                if ($trigger_condition) {
                    if (isset($replace_map[$first_letter])) {
                        $itog[0] = $replace_map[$first_letter];
                        $count_change = 1;
                        $pravilo = 33;
                    }
                }
            
                if ($itog[0] && $last_perenos != 2) {
                    if ($rule34_result = rule34($mool)) {
                        $result[6] = $rule34_result;
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

            //echo "$word_length SEC NUMBER  $second_number SEC:$second_letter "."THIRD:".$third_letter." THR NUMBER $third_number $big_array_1<BR>";
            
            if ($second_letter == "s" && $third_letter != "r" && $third_letter != "" && $second_number != $word_length && (($first_cons == "V" && $first_letter != "a" && $first_letter != "ā") || $first_letter == "k" || $first_letter == "r" || $first_letter == "l")) {


                $itog[0] = $first_letter;
                $itog[1] = "ṣ";
                $count_change = 2;
                $what_change = 0;
                $pravilo = 36;
            }

            if(($first_letter=="ḥ"||$first_letter=="ṃ")&&$mool!="puṃs"&&$mool!="hiṃs")
            {
                if ($second_letter == "s" && $third_letter != "r" && $third_letter != "" && (($zero_cons == "V" && $zero_letter != "a" && $zero_letter != "ā") || $zero_letter == "k" || $zero_letter == "r" || $zero_letter == "l")) {

 
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

function sandhi($big_array, $array, $new_word, $mool, $glagol_or_imennoy, $padezh, $osnova, $debug, $p_before_mool='') {
    
    
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

 

            $emeno = emeno_rules($array_rules[$j], $array, $big_array, $word_length, $zero_number, $position_number, $second_number, $big_array[1], $mool, $glagol_or_imennoy,$noperenos,$active_word,$right_word,$padezh,$third_number);

            

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
                //$result[0] = str_replace("||", "|", $result[0]);
      
                //Здесь пытаемся решить переносить придыхания если есть удвоительный слог!
                if($emeno[6]&&!$noperenos)
                {
                    //echo " RES0: ".$result[0]." PN: ".$position_number."<BR>";

                    //echo "EMENO6:_".$emeno[6]."<BR>";

                    //echo "P_BEFORE_MOOL".$p_before_mool."<BR><HR>";

                    $clear_mool=substr($result[0],0,$position_number+1);
                    
                    $f_l=substr($result[0],0,1);
                    $augment_search=substr($result[0],0,3);
                   
                    //if($f_l!="|")
                    //{
                        //Если нет удвоения корня
                        if($p_before_mool=='')
                        {
                            if($f_l!="|")
                            { 
                                $result[0] = $emeno[6].substr($result[0],1,strlen($result[0])-1);
                            }
                            else
                            {
                                $result[0] = $emeno[6].substr($result[0],2,strlen($result[0])-2);
                            }
                        }
                        else
                        {
                            $p_before_mool_len=mb_strlen($p_before_mool);

                            

                            if($augment_search!="|a|")
                            {
                                
                                $result[0] = mb_substr($result[0],0,$p_before_mool_len)."".$emeno[6]."".mb_substr($result[0],$p_before_mool_len+1,strlen($result[0]));
                            }
                            else
                            {
                                //echo $augment_search;
                                $result[0] = $augment_search.$p_before_mool."".$emeno[6]."".mb_substr($result[0],$p_before_mool_len+4,strlen($result[0]));
                          
                            }
                        }

                        //echo "RES:".$result[0]."<BR>";
                    //}
                    //else
                    //{
                    //    $result[0] = $emeno[6].substr($result[0],2,strlen($result[0])-2);
                    //}
                   
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

function Sandhi_String_From_Massive($info_massive_set,$augment,$postgment)
{
        $sandhi_string_set="";
        for($i=0;$i<count($info_massive_set);$i++)
        {
            $sandhi_string_set.=$info_massive_set[$i][0]."|"; 
        }
       
        $sandhi_string_set=str_replace("||","|",$sandhi_string_set);
        $sandhi_string_set=str_replace("||","|",$sandhi_string_set);
        $sandhi_string_set=str_replace("Ø̄","",$sandhi_string_set);
        $sandhi_string_set=str_replace("Ø","",$sandhi_string_set);

        
        if($augment&&$sandhi_string_set)
        {

            if(mb_substr($sandhi_string_set,0,1)!="|")
            {
                $sandhi_string_set=$augment."".$sandhi_string_set.$postgment;
            }
            else
            {
                $sandhi_string_set=$augment."".$sandhi_string_set.$postgment;
            }
        }

        $sandhi_string_set=str_replace("||","|",$sandhi_string_set);
        $sandhi_string_set=str_replace("||","|",$sandhi_string_set);

        return  $sandhi_string_set;
}

function simple_sandhi($word,$mool_change,$osnova,$debug,$p_before_mool='')  // Глагольные и формы и не-падежные окончания
{
    $dimensions=dimensions($word,"som","smth",0,0,0,"");
    $dimensions_array=dimensions_array($dimensions);

    $sandhi=sandhi($dimensions,$dimensions_array,$word,$mool_change,1,0,$osnova,$debug,$p_before_mool);

    return $sandhi;
}

function simple_sandhi_mool($word,$mool,$mool_change,$osnova,$debug)  // Глагольные и формы и не-падежные окончания
{
    $dimensions=dimensions($word,"som","smth",0,0,0,"");
    $dimensions_array=dimensions_array($dimensions);

    $sandhi=sandhi($dimensions,$dimensions_array,$mool,$mool_change,1,0,$osnova,$debug);

    return $sandhi;
}
?>
