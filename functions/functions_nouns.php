<?php

    function CommandLineSklonenie($id,$string,$noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$sklonenie,$sklonenie_type,$pol,$chislo,$debug)
    {
        include "db.php";
    
        if($debug)
        {
            //echo "$id,$string,$sklonenie,$sklonenie_type,$chislo,$debug";
        }

        //  $query = "SELECT * FROM name_endings WHERE padezh=$string AND chislo=$chislo AND sklonenie=$sklonenie AND sklonenie_type LIKE '%$sklonenie_type%'";

        $query = "SELECT * FROM name_endings WHERE padezh='$string' AND chislo=$chislo AND sklonenie=$sklonenie 
        AND (sklonenie_type LIKE '%$sklonenie_type%' OR sklonenie_type='0') AND pol LIKE '%$pol%' ";


        //echo $query;

		$result = mysqli_query($connection, $query);
				
				if (mysqli_num_rows($result) > 0) {
					while ($res = mysqli_fetch_array($result)) {
						$name_endings=$res['name'];
                        $query_endings=$res['query'];
                        $transform_endings=$res['transform'];
                    }
                }

                //echo "<BR>NAME ENDI:".$name_endings.$query_endings;

        //1a
                /*
        switch($string)
        {
            case "N":
                switch($chislo)
                {
                    case "1":
                        break;
                    case "2":
                        break;
                    case "3":
                        break;
                }
                break;
            case "Acc":
                break;
            case "I":
                break;
            case "D":
                break;
            case "Abl":
                break;
            case "G":
                break;
            case "L":
                break;
            case "V":
                break;
        }
        */

        $cheredovanie=get_word_simple($noun_name,$noun_element,$noun_e_position,$noun_ryad,$noun_type_number,$query_endings,$transform_endings,1);


        if($string=="N"&&$chislo==1&&(mb_strpos($cheredovanie[1],"ā")!==false||mb_strpos($cheredovanie[1],"ī")!==false))
        {
            $itog_result=mb_substr($cheredovanie[0],0,mb_strlen($cheredovanie[0])-1);
            $itog_nosandhi=$cheredovanie[0]."".$name_endings." (N. sg. морфема содержит звуки ā или ī)";
        }
        else
        {
            $itog_result=$cheredovanie[0]."|".$name_endings;
            $itog_nosandhi=$cheredovanie[0]."".$name_endings;
        }

        //simple_sandhi($word,$mool_change,$osnova,$debug)
        $itog = simple_sandhi($itog_result,'','Noun',0)[0]." ( без сандхи: $itog_nosandhi )";
        $itog = str_replace("Ø", "", $itog);

        return $itog;
    }

    function get_element_simple($ryad, $type, $mp,$suffix_transform) {
        
        switch ($type) {
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


        switch ($ryad) {
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

        
        if($suffix_transform)
        {

            if ($suffix_transform == "~" || $suffix_transform == "^") {
                
                if ($debug&&$glagol_or_imennoy==1) {
                    $debug_text .= "Series transformation: [" . $suffix_transform . "] : ";
                }

                switch ($suffix_transform) {
                    case "~":
                        switch ($itog_transform) {
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
                            //default:$itog_transform = $itog;
                                break;
                        }
                        break;

                    case "^":
                        switch ($itog_transform) {
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
                           // default:$itog_transform = $itog;
                                break;
                        }
                        break;

                    //default: $itog_transform = $itog;
                }


                if ($debug&&$glagol_or_imennoy==1) {
                    $debug_text .= "Replace $itog by $itog_transform<BR><BR>";
                }
            }

            $have_arrow=mb_strpos($suffix_transform,"↦");
           
            if($have_arrow)
            { 
                
                $find_arrow = explode("↦", $suffix_transform);
                
                if ($find_arrow[0] == substr($ryad, 0, 1) || 
                    $find_arrow[0] == substr($ryad, 0, 1) . "Ø" || 
                    $find_arrow[0] == $ryad || 
                    $find_arrow[0] == $ryad . "Ø" ||
                    $find_arrow[0] == "V") {

                       
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
                                switch ($answer[$mp]) {
                                    case "0":$itog_transform = "";
                                        break;
                                    case "g":$itog_transform = "a"; 
                                        break;
                                    case "v":$itog_transform = "ā";
                                        break;
                                }
                                break;

                            case "A2":
                                switch ($answer[$mp]) {
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
                                switch ($answer[$mp]) {
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
                                switch ($answer[$mp]) {
                                    case "0":$itog_transform="o";break;
                                    case "g":$itog_transform = "o";
                                        break;
                                    case "v":$itog_transform = "au";
                                        break;
                                }
                                break;

                            case "R":
                                ///??????////
                                switch ($answer[$mp]) {
                                    case "0":$itog_transform="ar";break;
                                    case "g":$itog_transform = "ar";
                                        break;
                                    case "v":$itog_transform = "ār";
                                        break;
                                }
                                break;

                            case "L":
                                ///??????////
                                switch ($answer[$mp]) {
                                    case "0":$itog_transform="al";break;
                                    case "g":$itog_transform = "al";
                                        break;
                                    case "v":$itog_transform = "āl";
                                        break;
                                }
                                break;

                            case "M":
                                ///??????////
                                switch ($answer[$mp]) {
                                    case "0":$itog_transform="am";break;
                                    case "g":$itog_transform = "am";
                                        break;
                                    case "v":$itog_transform = "ām";
                                        break;
                                }
                                break;

                            case "N":

                                ///??????////
                                switch ($answer[$mp]) {
                                    case "0":$itog_transform="an";break;
                                    case "g":$itog_transform = "an";
                                        break;
                                    case "v":$itog_transform = "ān";
                                        break;
                                }
                                break;

                           // default: $itog_transform = $itog;
                        }

                        if ($debug) {
                            $debug_text .= "Replace by $itog_transform<BR>";
                        }
                    } else {
                       // $itog_transform = $itog;
                    }
                } else {
                   // $itog_transform = $itog;
                }

            }
            //$new_word = str_replace($itog, $itog_transform, $new_word);
            //$new_word_sandhi = str_replace($itog, $itog_transform, $new_word_sandhi);
        }
        //else
        //{
        //    $itog_transform=$itog;
        //}

        //echo $itog_transform."<BR>";

        return $itog_transform;
    }

    function get_word_simple($word,$mool_change,$e_position,$ryad,$type,$mp,$transform,$debug)
    {

        //echo "<BR>GWS: $word,$mool_change,$e_position,$ryad,$type,$mp,$debug";
       
        $dimensions = dimensions($word, $mool_change, $word, 1, 0, 0, 0);
        $dimensions_array = dimensions_array($dimensions);

        $itog= get_element_simple($ryad,$type,$mp,$transform);

        if(!$e_position)
        {
            $new_word = str_replace($mool_change, $itog, $word);
        }
        else
        {
            $new_word = mb_substr($word,0,$e_position-1).$itog.mb_substr($word,$e_position+mb_strlen($mool_change),mb_strlen($word)-mb_strlen($e_position)-mb_strlen($itog));
        }

        if ($debug) {
            $debug_text .= "<BR>" . dimensions_table($dimensions);
            //echo $debug_text;
        }

        $result[0]=$new_word;

        $result[1]=$itog;

        return $result;
    }

?>