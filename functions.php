<?php

function find_in_array($what,$array,$letter)
{
    $result="";
    for($i=0;$i<count($array);$i++)
    {
        if($array[$i]==$what)
        {
            $result=$letter;
        }
    }

    return $result;
}

function duplication_first($mool,$mool_number,$mool_type,$mool_change,$mool_type_change,$debug)
{
    $dimensions=dimensions($mool,$mool_change,$mool,1,0,1);
    $dimensions_array=dimensions_array($dimensions);
   
    $i=0;$flag_big_e=0;$f_mool="";

    while($dimensions_array[$i][1]!="E"){$i++;}


    if($dimensions_array[$i+1][1]=="E"){$flag_big_e=1;$i++;}

    for($j=$i+1;$j<count($dimensions_array);$j++)
    {
       
        $f_mool.=$dimensions_array[$j][0];

    }

    

    if($debug){ echo "<b>Удвоение корня (первый шаг - определяем P') </b><BR><BR>";}

    if($debug){echo dimensions_table($dimensions);}

    if($i==1)
    {
        if($debug){echo "P пустое<BR>";}
        $p_new="";$p_mool="";
        $model=$mool_change.$f_mool;
    }
    else
    {

        if($debug){echo "P не пустое<BR>";}

        if(($i>2&&$flag_big_e==0)||($i>3&&$flag_big_e==1))
        {
            
            $x1[0]=$dimensions_array[1][0];
            $x1[1]=$dimensions_array[1][1];
            $x1[2]=$dimensions_array[1][2];

            $x2[0]=$dimensions_array[2][0];
            $x2[1]=$dimensions_array[2][1];
            $x2[2]=$dimensions_array[2][2];

            $p_mool=$x1[0].$x2[0];

            if($x1[2].$x2[2]=="ST")
            {
                $p_new=$x2[0];
                $model=$p_new."E'".$p_mool.$mool_change.$f_mool;
                $comment="остаётся шумная";
            }
            else
            {
                $p_new=$x1[0];
                $model=$p_new."E'".$p_mool.$mool_change.$f_mool;
                $comment="остаётся первая";
            }

        }
        else
        {
            $x=$dimensions_array[1][0];
            $p_mool=$x;
        
            $p_new=$x;$f_mool="";
            for($j=$i+1;$j<count($dimensions_array);$j++)
            {
                $f_mool.=$dimensions_array[$j][0];
            }
            
            

            $model=$p_new."E'".$p_mool.$mool_change.$f_mool;
            $comment="ничего не происходит";
        }


        

        if($x1)
        {
            $p_text="P = $p_mool ('".$x1[2].$x2[2]."') ";
        }
        else
        {
            $p_text="P = $p_mool ";
        }

        $e_text="E = $mool_change ";

        if($f_mool=="")
        {
            $f_text="F = Ø <BR>";
        }
        else
        {
            $f_text="F = $f_mool <BR>";
        }

        if($x1)
        {
            $pada1="x1='".$x1[2]."' x2='".$x2[2]."' <BR><BR>Шаг 1 удвоения: ".$model." ($comment)<BR>";
        }
        else
        {
            $pada1="<BR>Шаг 1 удвоения: ".$model." ($comment)<BR>";
        }

        if($debug){echo $p_text.$e_text.$f_text;}
        if($debug){echo $pada1;}

        $comment="";

        switch($p_new)
        {
            case "kh":$comment=" ( $p_new меняется на"; $p_new="k";$comment.= " $p_new )";break;
            case "ch":$comment=" ( $p_new меняется на"; $p_new="c";$comment.= " $p_new )";break;
            case "ṭh":$comment=" ( $p_new меняется на"; $p_new="ṭ";$comment.= " $p_new )";break;
            case "th":$comment=" ( $p_new меняется на"; $p_new="t";$comment.= " $p_new )";break;
            case "ph":$comment=" ( $p_new меняется на"; $p_new="p";$comment.= " $p_new )";break;
            case "gh":$comment=" ( $p_new меняется на"; $p_new="g";$comment.= " $p_new )";break;
            case "jh":$comment=" ( $p_new меняется на"; $p_new="j";$comment.= " $p_new )";break;
            case "ḍh":$comment=" ( $p_new меняется на"; $p_new="ḍ";$comment.= " $p_new )";break;
            case "dh":$comment=" ( $p_new меняется на"; $p_new="d";$comment.= " $p_new )";break;
            case "bh":$comment=" ( $p_new меняется на"; $p_new="b";$comment.= " $p_new )";break;
        }

        if(!$comment){$comment=" (ничего не происходит) ";}
        $model=$p_new."E'".$p_mool.$mool_change.$f_mool;
        if($debug){echo "<BR>Шаг 2 удвоения: ".$model." $comment <BR>";}

        $comment="";

        switch($mool)
        {
            case "ji":$p_new="j";$p_mool="g";$comment=" (корень-исключение $mool)";break;
            case "cit":$p_new=$p_new;$p_mool="k";$comment=" (корень-исключение $mool)";break;
            case "ci":
                if($mool_number==1)
                {$p_new=$p_new;$p_mool="k";$comment=" (корень-исключение $mool $mool_number)";}
                elseif($mool_number==2)
                {$p_new=$p_new;$p_mool="k";$comment=" (корень-исключение $mool $mool_number)";}
                break;
        }

        if(!$comment)
        {

            switch($p_new)
            {
                case "h":
                    if($mool=="hn̥"||$mool=="hi")
                    {
                        $p_new="j";
                        $p_mool="gh";
                        $comment=" (корень-исключение $mool)";
                    }
                    else
                    {
                        $comment=" ( $p_new меняется на"; 
                        $p_new="k";
                        $comment.= " $p_new)";
                    }
                    break;
                    
                case "k": $comment=" ( $p_new меняется на";$p_new="c";$comment.= " $p_new )";break;
                case "g": $comment=" ( $p_new меняется на";$p_new="j";$comment.= " $p_new )";break;
            }

        }

        if(!$comment){$comment=" (ничего не происходит) ";}
        $model=$p_new."E'".$p_mool.$mool_change.$f_mool;
        if($debug){echo "<BR>Шаг 3 удвоения: ".$model." $comment<BR>";}
    }

    $itog[0]=$model;
    $itog[1]=$p_new;
    $itog[2]="E'";
    $itog[3]=$p_mool;
    $itog[4]=$mool_change;
    $itog[5]=$f_mool;

   

    return $itog;

}


function duplication_p2($array,$mool,$mool_type,$mool_type_change,$omonim,$debug)
{

    $p_new=$array[1];

    $p_mool=$array[3];
    $mool_change=$array[4];
    $f_mool=$array[5];

  
    if($debug){ echo "<BR><b>Подготовка корня для создания простого перфекта </b><BR>";}

    if(!$array[1])
    {
        
        if($mool_type_change=="R1")
        {
            if($mool=="ṛ")
            {
                $model="ar";$comment="глагол-исключение $mool";
            }
            else
            {
                $model="ān".$array[4].$array[5];
                $comment="Ряд $mool_type_change";
            }
        }
        elseif($mool_type_change=="N1")
        {
            $model="ān".$array[4].$array[5];
            $comment="Ряд $mool_type_change";
        }
        elseif($mool=="Øs")
        {
            $model="ās".$array[4];
            $comment="глагол-исключение $mool";
        }
        elseif($mool=="m̥̄")
        {
            $model="ām".$array[4];
            $comment="глагол-исключение $mool";
        }
        elseif($mool=="ej"||$mool=="edh")
        {
            $model="Только описательный перфект";
            $comment="глагол-исключение $mool";
        }
        else
        {
            $new_e=get_e_mp_simple($mool_type_change,$mool_type,1);
            $model=$new_e.$array[4].$array[5];
            $comment="ветвь 5, Р = 0, путь присоединения E в 1 МП – по схеме E'EF";
        }

    }
    else
    {
        $vowels=["a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","ai","o","au"];
        
        if($mool=="hū")
        {
            $model="juhū";
            $comment=" корень-исключение $mool ";
        }
        elseif($mool=="hvṛ")
        {
            $model="jahvṛ";
            $comment=" корень-исключение $mool ";
        }
        elseif($mool=="śū")
        {
            $model="śuśū";
            $comment=" корень-исключение $mool ";
        }
        elseif($mool_type_change=="A1")
        {
           
            if($mool=="viØc"||$mool=="viØdh"||$mool=="suØp"||$mool=="miØkṣ")
            {
                //PPEF   

                $model=$p_mool.$p_mool.$mool_change.$f_mool;
                $comment=" ветвь 1 непустого Р, корень-исключение $mool, А1, схема удвоения PPEF ";
            }
            elseif($mool=="śad"&&$omonim=="1")
            {

                $model="śāśad";
                $comment=" ветвь 1 непустого Р, корень-исключение $mool $omonim, А1, схема удвоения śā + śad ";
            }
            elseif((substr($p_new,0,1)=="i"||substr($p_new,0,1)=="u")&&($mool!="uØkṣ"&&$mool!="śuØṣ"))
            {
                //P’ØPA1F   ???????????????

                $model=$p_new."Ø".$p_mool.$mool_change.$f_mool;
                $comment=" ветвь 1 непустого Р, первая буква P i или u, А1, схема удвоения P’ØPA1F ";

            }
            elseif($mool_type==2||strpos($mool,"ṛ")||$mool=="uØkṣ"||$mool=="śuØṣ")
            {
                //P’aPA1F

                $model=$p_new."a".$p_mool.$mool_change.$f_mool;
                $comment=" ветвь 1 непустого Р, А1, схема удвоения P’aPA1F ";

            }
        }
        elseif($mool_type_change=="A2")
        {
            $flag_has_vowels=0;

            

            for($s=0;$s<mb_strlen($mool);$s++)
            {
                $what=mb_substr($mool,$s,1);
                $compare=find_in_array($what,$vowels,"V");
                if($compare=="V"){$flag_has_vowels=1;}
            }

            if($mool=="jīØ̄")
            {
                $model="jijīØ̄";
                $comment=" корень-исключение $mool ";
            }
            elseif($mool=="uØ̄") /// ???
            {
                //1 М П – ū [ūvus – 3 pl.]; 2 М П – vavā [vavau]  ?

                $model="uØuØ̄";
                $comment=" корень-исключение $mool ???";  
            }
            elseif($mool=="vīØ̄")
            {
                $model="vivīØ";  
                $comment=" корень-исключение $mool ";
            }
            elseif($flag_has_vowels==0||$mool_type==2||$mool=="śīØ̄"||$mool=="stīØ̄")
            {
                //P’aPA2F
     
                $model=$p_new."a".$p_mool.$mool_change.$f_mool;
                $comment=" ветвь 2 непустого Р, А2, схема удвоения P’aPA2F ";
            }


        }
        elseif($mool_type_change=="I0"||$mool_type_change=="I1"||$mool_type_change=="I2")
        {
            //Корни  рядов I удваиваются по схеме P’iPIF:

            $model=$p_new."i".$p_mool.$mool_change.$f_mool;
            $comment=" ветвь 3 непустого Р, ряд I, схема удвоения P’iPIF ";

        }
        elseif($mool_type_change=="U0"||$mool_type_change=="U1"||$mool_type_change=="U2")  // ????
        {
            if($mool=="bhū")
            {
                $model="babhū";
                $comment=" p2√bhū = babhū [babhūva] (!) U2 не чередуется, в позиции EV принимает вид ūv";  // !!!
            }
            elseif($mool=="dhau")
            {
                $model="dadhau";
                $comment=" корень-исключение $mool";
            }
            else
            {
                //P’uPUF

                $model=$p_new."u".$p_mool.$mool_change.$f_mool;
                $comment=" ветвь 4 непустого Р, ряд U, схема удвоения P’uPUF ";

            }

        }
        else
        {
            //P’aPRF P’aPLF  P’aPNF P’aPMF
            $model=$p_new."a".$p_mool.$mool_change.$f_mool;
            $comment=" ветви 5-8 непустого Р, ряд $mool_type_change, схема удвоения P’aP(чередующийся элемент ряда)F ";
        }

    }

    if($debug){ echo "<BR>Удвоенный корень для чередования без сандхи: $model ($comment)<BR><BR>";}

    return $model;

    //echo "Форма простого  перфекта 3sg: ";
   // echo get_word($model,$mool_number,$mool_type,$mool_change,$mool_type_change,$suffix,$suffix_ask,$suffix_transform,$glagol_or_imennoy,$verb_setnost,0)[0];
    //echo "<BR>";

}

function get_word($mool,$mool_number,$mool_type,$mool_change,$mool_type_change,$suffix,$suffix_ask,$suffix_transform,$glagol_or_imennoy,$verb_setnost,$debug)
{

    $consonants=["k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s","h"];
    $vowels=["a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","ai","o","au"];
    $gubnye=["p", "ph", "b", "bh","m"];


        
        //$has_0=0;$has_guna=0;$has_vriddhi=0;
        switch ($mool_type)
        {
            case "1":$answer[1]="0";$answer[2]="g";$answer[3]="v";break;
            case "2":$answer[1]="g";$answer[2]="g";$answer[3]="v";break;
            case "3":$answer[1]="0";$answer[2]="0";$answer[3]="0";break;
            case "4":$answer[1]="v";$answer[2]="v";$answer[3]="v";break;
        }

    if($debug)
    {
        $debug_text.= "<BR><b>Чередование</b><BR><BR>";
        $debug_text.= $mool." + ($suffix_ask[0])".$suffix[0];


        if($suffix[1])
        {
            $debug_text.= " + ($suffix_ask[1])".$suffix[1];
        }

        $debug_text.= "<br><br>";

    }

    if($mool_number)
    {
        $dop="омоним номер $mool_number, ";
    }

    if($debug)
    {
        $debug_text.= "Корень '$mool' $dop $mool_type типа, суффикс ".$suffix[0]." требует ".$suffix_ask[0]." морфологической позиции. Корень '$mool' в ответ отдаёт ступень типа ".$answer[$suffix_ask[0]]." <BR>";
    }

    $word_1=$mool.$suffix[0].$suffix[1];

    if($debug)
    {

        $debug_text.= "<br>";

        $debug_text.= "Смотрим по 4 таблице для ступени типа ".$answer[$suffix_ask[0]]."<BR>";
        $debug_text.= "<br>";
        $debug_text.= "До преобразования: $word_1 <BR>";
    }


    $dimensions=dimensions($word_1,$mool_change,$mool,1,0,0);
    $dimensions_array=dimensions_array($dimensions);
    //print_r(dimensions_array($dimensions));


    if($debug)
    {
        $debug_text.= "<BR>".dimensions_table($dimensions);
    }

    $ep=mb_strpos($word_1,$mool_change);

    if($answer[$suffix_ask[0]]=="0")
    {


    $line_2="E".find_bukvi($dimensions[1],1,"E",1);

    if($line_2=="EC")
    {

        $line_3=ey_or_not($word_1,$ep,$mool_change);
        //echo $line_3."Word: ".$word_1." EP: ".$ep."<BR>";
    


    switch ($mool_type_change) {
        
        case "A1": switch ($line_3)
                    {
                            case "Eне-y":$itog="Ø";break;
                            case "Ey":$itog="Ø";break;
                            
                    };
                    break;
                
        case "A2":  switch ($line_3)
                    {
                            case "Eне-y":
                                        $before = what_is_before($word_1, $ep);
                                        if($before[1]=="C")
                                        {
                                            $table5="Смотрим по таблице 5  Выбор i | ī в ряду А2.";
                                            if($mool=="chØ̄"||($mool=="dØ̄"&&$mool_number==1)||($mool=="dØ̄"&&$mool_number==2)||($mool=="mØ̄"&&$mool_number==1)||($mool=="mØ̄"&&$mool_number==2)||($mool=="mØ̄"&&$mool_number==3)||($mool=="mØ̄"&&$mool_number==4)||($mool=="śØ̄"&&$mool_number==1)||($mool=="sØ̄")||($mool=="sthØ̄")||($mool=="śØ̄s"))
                                            {
                                                $itog = "i";$table5.=" Корень из ячейки i <BR>";break;
                                            }
                                            elseif($mool=="gØ̄"||($mool=="hØ̄"&&$mool_number==2)||($mool=="dhØ̄"&&$mool_number==2)||$mool=="pØ̄"||$mool=="sphØ̄")  // nØ̄- (PrS9)  29 - ????
                                            {
                                                $itog = "ī";$table5.=" Корень из ячейки ī <BR>";break;
                                            }
                                            elseif(($mool=="dØ̄"&&$mool_number==3)||($mool=="dhØ̄"&&$mool_number==1)||($mool=="mØ̄"&&$mool_number==5)||($mool=="lØ̄")||($mool=="hØ̄"))
                                            {
                                                $itog = "(i&ī)";$table5.=" Корень из ячейки i&ī <BR>";break;
                                            }
                                            else
                                            {
                                                $table5.=" Корня в табличке нет ¯\_(ツ)_/¯ <BR>";break;
                                            }

                                        }
                                        elseif($before[1]=="V")
                                        {
                                            $itog = "Ø";break;
                                        }
                            case "Ey":
                                        $before = what_is_before($word_1, $ep);
                                        if($before[1]=="C")
                                        {
                                            $itog = "i";break;
                                        }
                                        elseif($before[1]=="V")
                                        {
                                            $itog = "Ø";break;
                                        }
                            
                    };
                    break;        
        
        case "I1": switch ($line_3)
                    {
                            case "Eне-y":$itog="i";break;
                            case "Ey":$itog="ī";break;
                            
                    };
                    break;

        case "I2": switch ($line_3)
                    {
                            case "Eне-y":$itog="ī";break;
                            case "Ey":$itog="ī";break;
                            
                    };
                    break;
        
        case "U1": switch ($line_3)
                    {
                            case "Eне-y":$itog="u";break;
                            case "Ey":$itog="ū";break;
                            
                    };
                    break;

        case "U2": $itog="ū";break;

    
        
        case "R1": switch ($line_3)
                    {
                            case "Eне-y":$itog="ṛ";break;
                            case "Ey":$itog="ri";break;
                            
                    };
                    break;
                
        case "R2": if(!$glagol_or_imennoy)
                    {
                        $itog="ṝ";
                    }
                    else
                    {
                                    
                            $gubn_string=find_bukvi($dimensions[3],1,"E",-1);
                            
                            if($gubn_string=="L")
                            {
                                $itog="ūr";
                                $snoska27="Чередующийся элемент после губной согласной.";
                            }
                            else
                            {
                                $itog="īr";
                                $snoska27="Чередующийся элемент стоит НЕ после губной согласной";
                            }
                            break;
                    
                    }
                    break;        
                
        case "L": $itog="ḷ";break;

        case "M1": switch ($line_3)
                    {
                        case "Ey":$itog="am";break;   
                        case "Eне-y":
                            $line_3_1=ev_or_not($word_1,$ep,$mool_change);
                            $line_3_2=em_or_not($word_1,$ep,$mool_change);
                            //echo "HERE";
                            $text_for_m_n="($line_3_1 , $line_3_2)";

                            if($line_3_1=="Ev"||$line_3_2=="Em")
                            {
                                $itog="an";break;
                            }
                            else
                            {
                                $itog="a";break;
                            }
                            
                            
                            
                    };
                    break;    

        case "M2": $itog="ām"; break;  
        
        case "N1": switch ($line_3)
                    {
                        case "Ey":$itog="an";break;   
                        case "Eне-y":
                            $line_3_1=ev_or_not($word_1,$ep,$mool_change);
                            $line_3_2=em_or_not($word_1,$ep,$mool_change);

                        // echo "HERE";

                            $text_for_m_n="($line_3_1 , $line_3_2)";
                            if($line_3_1=="Ev"||$line_3_2=="Em")
                            {
                                $itog="an";break;
                            }
                            else
                            {
                                $itog="a";break;
                            }
                            
                            
                            
                    };
                    break;    

        case "N2": $itog="ā"; break;  

    }

    }

    if($line_2=="EV")
    {

        

        $line_3=find_bukvi($dimensions[1],1,"E",-1)."E";
    
        if($line_3=="#E"||$line_3=="VE")
        {
        } 
        else
        {
            $line_3=find_bukvi($dimensions[1],2,"E",-1)."E";
        }   

        $cons_string=find_bukvi($dimensions[1],1,"E",-1);
        $word_string=find_bukvi($word_1,1,$mool_change,-1);
        $line_4=find_bukvi($dimensions[2],3,"E",-1)."E";

        switch ($mool_type_change) {
            
            case "A1": $itog="Ø";break;
            case "A2": 
                    
                        switch ($cons_string)
                        {
                                case "C":$line_4="Перед Ø̄ согласная <BR>";$itog="Ø̄";break;
                                case "V": 
                                // echo $word_string;
                                    if($word_string=="i"||$word_string=="ī"){$line_4="Перед Ø̄ гласная i или ī <BR>";$mool_change=$word_string.$mool_change;$itog="yØ̄";}
                                    if($word_string=="u"||$word_string=="ū"){$line_4="Перед Ø̄ гласная u или ū <BR>";$mool_change=$word_string.$mool_change;$itog="uvØ̄";}
                                    break;
                        };
                        break;        
            
            case "I1": switch ($line_3)
                        {
                                case "CCE":$itog="iy";break;
                                case "#CE":$itog="iy";break;
                                case "#E": $itog="y";break;
                                case "VCE":$itog="y";break;
                                case "VE": $itog="y";break;
                                
                        };
                        break;

            case "I2": switch ($line_3)
                        {
                                case "CCE":$itog="iy";break;
                                case "#CE":$itog="iy";break;
                                case "#E": $itog="y";break;
                                case "VCE":$itog="y";break;
                                case "VE": $itog="y";break;
                                
                        };
                        break;

                case "U1": switch ($line_3)
                        {
                                case "CCE":$itog="uv";break;
                                case "#CE":$itog="uv";break;
                                case "#E": $itog="v";break;
                                case "VCE":$itog="v";break;
                                case "VE": $itog="v";break;
                                
                        };
                        break;

                case "U2": switch ($line_3)
                        {
                                case "CCE":$itog="uv";break;
                                case "#CE":$itog="uv";break;
                                case "#E": $itog="v";break;
                                case "VCE":$itog="v";break;
                                case "VE": $itog="v";break;
                                
                        };
                        break;
            
            
                case "R1": switch ($line_3)
                        {
                            case "CCE":$itog="ar";break;
                            case "#CE":
                                    
                                    $gubn_string=find_bukvi($dimensions[3],1,"E",-1);
                                    if($gubn_string=="L")
                                    {
                                        $itog="ur";
                                        $snoska27="Чередующийся элемент после губной согласной.";
                                    }
                                    else
                                    {
                                        $itog="ir";
                                        $snoska27="Чередующийся элемент стоит НЕ после губной согласной";
                                    }
                                    break;

                            case "#E": $itog="ar";break;
                            case "VCE":$itog="r";break;
                            case "VE": $itog="r";break;
                                
                        };
                        break;

                        case "R2": switch ($line_3)
                        {
                            case "CCE":$itog="ar";break;
                            case "#CE":
                                    
                                $gubn_string=find_bukvi($dimensions[3],1,"E",-1);
                                if($gubn_string=="L")
                                {
                                    $itog="ur";
                                    $snoska27="Чередующийся элемент после губной согласной.";
                                }
                                else
                                {
                                    $itog="ir";
                                    $snoska27="Чередующийся элемент стоит НЕ после губной согласной";
                                }
                                break;

                            case "#E": $itog="r";break;
                            case "VCE":$itog="r";break;
                            case "VE": $itog="r";break;
                                
                        };
                        break;
                    
                        case "M1": switch ($line_4)
                        {
                            //без анусвар висарг придыхательных
                            case "#vE":$itog="am";break;
                            case "#NE":$itog="am";break;
                            case "#SE":$itog="am";break;

                            case "SE":$itog="am";break;
                            
                            case "TTE":$itog="am";break;
                            case "vTE":$itog="am";break;
                            case "NTE":$itog="am";break;
                            case "STE":$itog="am";break;
                            
                            case "VTE":$itog="m";break;
                            case "#TE": $itog="m";break;
                            // ksm-, hn-

                                
                        };
                        break;  

                        case "M2": switch ($line_4)
                        {
                            //без анусвар висарг придыхательных
                            case "#vE":$itog="am";break;
                            case "#NE":$itog="am";break;
                            case "#SE":$itog="am";break;

                            case "SE":$itog="am";break;
                            
                            case "TTE":$itog="am";break;
                            case "vTE":$itog="am";break;
                            case "NTE":$itog="am";break;
                            case "STE":$itog="am";break;
                            
                            case "VTE":$itog="m";break;
                            case "#TE": $itog="m";break;
                            //( ksm-, hn-

                                
                        };
                        break;   

                        case "N1": switch ($line_4)
                        {
                            //без анусвар висарг придыхательных

                            

                            case "#vE":$itog="an";break;
                            case "#NE":$itog="an";break;
                            case "#SE":$itog="an";break;

                            case "SE":$itog="an";break;
                            
                            case "TTE":$itog="an";break;
                            case "vTE":$itog="an";break;
                            case "NTE":$itog="an";break;
                            case "STE":$itog="an";break;
                            
                            case "VTE":$itog="n";break;
                            case "#TE": $itog="n";break;
                            // ksm-, hn-

                                
                        };
                        break; 


                        case "N2": switch ($line_4)
                        {
                            //без анусвар висарг придыхательных
                            case "#vE":$itog="an";break;
                            case "#NE":$itog="an";break;
                            case "#SE":$itog="an";break;

                            case "SE":$itog="an";break;
                            
                            case "TTE":$itog="an";break;
                            case "vTE":$itog="an";break;
                            case "NTE":$itog="an";break;
                            case "STE":$itog="an";break;
                            
                            case "VTE":$itog="n";break;
                            case "#TE": $itog="n";break;
                            //( ksm-, hn-

                                
                        };
                        break; 
                    
        
        }

    }

    }
    elseif($answer[$suffix_ask[0]]=="g")
    {
        $line_2="g";
        $line_3=ey_or_not($word_1,$ep,$mool_change);
        
        switch ($mool_type_change) {
        
        case "A1": $itog="a"; break;
        case "A2": $itog="ā"; break; 

        case "I0": switch ($line_3)
        {
                case "Eне-y":$itog="e";break;
                case "Ey":$itog="ay";break;
                
        };
        break;
        
        case "I1": switch ($line_3)
        {
                case "Eне-y":$itog="e";break;
                case "Ey":$itog="ay";break;
                
        };
        break;

        case "I2": switch ($line_3)
        {
                case "Eне-y":$itog="e";break;
                case "Ey":$itog="ay";break;
                
        };
        break;
        
        
        case "U0": switch ($line_3)
                    {
                            case "Eне-y":$itog="o";break;
                            case "Ey":$itog="av";break;
                            
                    };
                    break;
        
        case "U1": switch ($line_3)
                    {
                            case "Eне-y":$itog="o";break;
                            case "Ey":$itog="av";break;
                            
                    };
                    break;

        case "U2": switch ($line_3)
                    {
                            case "Eне-y":$itog="o";break;
                            case "Ey":$itog="av";break;
                            
                    };
                    break;
                
        case "R0": $itog="ar"; break;

        case "R1": $itog="ar"; break;

        case "R2": $itog="ar"; break;

        case "L": $itog="al"; break;
                
                
        case "M0": $itog="am"; break;

        case "M1": $itog="am"; break;

        case "M2": $itog="am"; break;

        
        case "N0": $itog="an"; break;

        case "N1": $itog="an"; break;

        case "N2": $itog="an"; break;
                

        }
        
        
    }
    elseif($answer[$suffix_ask[0]]=="v")
    {

        $line_2="v";
        $line_3=ey_or_not($word_1,$ep,$mool_change);
        
        switch ($mool_type_change) {
        
        case "A1": $itog="ā"; break;
        case "A2": $itog="ā"; break; 

        case "I0": switch ($line_3)
        {
            case "Eне-y":$itog="ai";break;
            case "Ey":$itog="āy";break;
                
        };
        break;
        
        
        case "I1": switch ($line_3)
        {
            case "Eне-y":$itog="ai";break;
            case "Ey":$itog="āy";break;
                
        };
        break;

        case "I2": switch ($line_3)
        {
            case "Eне-y":$itog="ai";break;
            case "Ey":$itog="āy";break;
                
        };
        break;
        
        case "U0": switch ($line_3)
                    {
                            case "Eне-y":$itog="au";break;
                            case "Ey":$itog="āv";break;
                            
                    };
                    break;
        
        
        case "U1": switch ($line_3)
                    {
                            case "Eне-y":$itog="au";break;
                            case "Ey":$itog="āv";break;
                            
                    };
                    break;

        case "U2": switch ($line_3)
                    {
                            case "Eне-y":$itog="au";break;
                            case "Ey":$itog="āv";break;
                            
                    };
                    break;
                
        
        case "R0": $itog="ār"; break;
        
        case "R1": $itog="ār"; break;

        case "R2": $itog="ār"; break;

        case "L": $itog="āl"; break;
                
                
        case "M0": $itog="ām"; break;

        case "M1": $itog="ām"; break;

        case "M2": $itog="ām"; break;

        
        case "N0": $itog="ān"; break;

        case "N1": $itog="ān"; break;

        case "N2": $itog="ān"; break;
                

        }


    }

    if($line_2=="")
    {
        $line_2="<font color=red><b>Что-то пошло не так ¯\_(ツ)_/¯ где-то опечатка</b></font>";
    }


    if($debug)
    {
        $debug_text.= "<BR>Тип для второй строчки в таблице: $line_2";
        $debug_text.= "<BR>Тип для третьей строчки в таблице: ";


        if($line_3_1=="Ev"){$debug_text.= $line_3_1;}
        elseif($line_3_2=="Em"){$debug_text.= $line_3_2;}
        else{ $debug_text.= $line_3;}
        $debug_text.= "$text_for_m_n<BR>";

        $debug_text.= "Ряд чередования: $mool_type_change<BR>";


        if($line_4&&$mool_type_change=="M1"&&$mool_type_change=="M2"&&$mool_type_change=="N1"&&$mool_type_change=="N2"){$debug_text.= "Для рядов M и N: ".$line_4."<BR>";}

        if($before)
        {
            $debug_text.= "Перед чередующимся элементом: ".$before[1]."<BR>";
        }

        //echo $line_4; // 5-я ячейка для EV  А2

        $debug_text.= $table5;
        if($snoska27){$debug_text.= $snoska27."<BR>";} //Губные или нет для рядов R2 (EC) , R1,R2 (EV)

        //echo $mool_change;

    }

    for($i=0;$i<strlen($mool);$i++)
    {
        if($mool_change=="m̥̄"||$mool_change=="n̥̄")
        {$count_change=3;}
        elseif($mool_change=="Ø̄"||$mool_change=="m̥"||$mool_change=="n̥")
        {$count_change=2;}
        else
        {$count_change=1;}

        $new_word=str_replace($mool_change,$itog,$mool);
        $new_word_sandhi=str_replace($mool_change,"|".$itog."|",$mool);

    }

    if($debug)
    {
    $debug_text.="Чередование: $mool -> $new_word ($mool_change меняется на $itog) <BR>";
    }

    //Убираем нули?

    $new_word=str_replace("Ø","",$new_word);
    $new_word=str_replace("Ø̄","",$new_word);

    if($suffix_transform[0]=="~"||$suffix_transform[0]=="^")
    {
        if($debug)
        {
            $debug_text.= "Выполним межрядовую трансформацию [".$suffix_transform[0]."] : ";
        }

        switch($suffix_transform[0])
        {
            case "~":
                    switch ($itog) {
                                        case "a":$itog_transform="ā";break;
                                        case "i":$itog_transform="ī";break;
                                        case "u":$itog_transform="ū";break;
                                        case "ṛ":$itog_transform="ṝ";break;
                                        case "ḷ":$itog_transform="ḹ";break;
                                        case "m̥":$itog_transform="m̥̄";break;
                                        case "n̥":$itog_transform="n̥̄";break;
                                        default:$itog_transform=$itog;break;
                    }
                    break;

            case "^":
                    switch ($itog) {
                                        case "ā":$itog_transform="a";break;
                                        case "ī":$itog_transform="i";break;
                                        case "ū":$itog_transform="u";break;
                                        case "ṝ":$itog_transform="ṛ";break;
                                        case "ḹ":$itog_transform="ḷ";break;
                                        case "m̥̄":$itog_transform="m̥";break;
                                        case "n̥̄":$itog_transform="n̥";break;
                                        default:$itog_transform=$itog;break;
                    }
                    break;
            
            default: $itog_transform=$itog;

                //$vowels=["a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","ai","o","au"];
                //"Ø̄","Ø","m̥̄","m̥","n̥","n̥̄"
        }
        
        
        if($debug)
        {
            $debug_text.= "Заменили $itog на $itog_transform";
        }
    }

        $find_arrow=explode("↦",$suffix_transform[0]);
        //print_r($suffix_transform[0]);
        //print_r($find_arrow);

        //echo "MOOL-1:".mb_substr($mool,-2,2);

        if($find_arrow[0]==substr($mool_type_change,0,1)||$find_arrow[0]==substr($mool_type_change,0,1)."Ø"||$find_arrow[0]==$mool_type_change||$find_arrow[0]==$mool_type_change."Ø")
        {
            if($debug)
            {
                $debug_text.= "Выполним межрядовую трансформацию [".$suffix_transform[0]."] : ";
            }
            
            $find_zero=mb_strpos($find_arrow[0],"Ø");
        
            if($debug)
            {
                if($find_zero==0){echo "трансформация только для открытых корней.";}
            }
            
            $is_open_mool=0;
            if(mb_substr($mool,-1,1)=="Ø"||mb_substr($mool,-2,2)=="Ø̄")
            {
                $is_open_mool=1;
            }
        

            if($debug)
            {

                if($is_open_mool)
                {
                    $debug_text.= "$mool корень открытый. ";
                }
                else
                {
                    $debug_text.= "$mool корень закрытый, трансформация не производится. ";
                }

            }

            if(!$find_zero||($find_zero!=0&&$is_open_mool!=0))
            {
                
                $new_mool_type_change=$find_arrow[1];

                switch($new_mool_type_change)
                {
                    case "A1":
                        switch($answer[$suffix_ask[0]])
                        {
                            case "0":$itog_transform="Ø";break;
                            case "g":$itog_transform="a";break;
                            case "v":$itog_transform="ā";break;
                        }
                        break;

                    case "A2":
                        switch($answer[$suffix_ask[0]])
                        {
                            case "0":$itog_transform="Ø̄";break;
                            case "g":$itog_transform="ā";break;
                            case "v":$itog_transform="ā";break;
                        }
                        break;

                    case "I":
                        ///??????////
                        switch($answer[$suffix_ask[0]])
                        {
                            //case "0":$itog_transform="Ø";break;
                            case "g":$itog_transform="e";break;
                            case "v":$itog_transform="ai";break;
                        }
                        break;
                    
                    case "U":
                        ///??????////
                        switch($answer[$suffix_ask[0]])
                        {
                            ///case "0":$itog_transform="Ø";break;
                            case "g":$itog_transform="o";break;
                            case "v":$itog_transform="au";break;
                        }
                        break;

                    case "R":
                        ///??????////
                        switch($answer[$suffix_ask[0]])
                        {
                            ///case "0":$itog_transform="Ø";break;
                            case "g":$itog_transform="ar";break;
                            case "v":$itog_transform="ār";break;
                        }
                        break;

                    case "L":
                        ///??????////
                        switch($answer[$suffix_ask[0]])
                        {
                            ///case "0":$itog_transform="Ø";break;
                            case "g":$itog_transform="al";break;
                            case "v":$itog_transform="āl";break;
                        }
                        break;

                    case "M":
                        ///??????////
                        switch($answer[$suffix_ask[0]])
                        {
                            ///case "0":$itog_transform="Ø";break;
                            case "g":$itog_transform="am";break;
                            case "v":$itog_transform="ām";break;
                        }
                        break;

                    case "N":
                        
                        ///??????////
                        switch($answer[$suffix_ask[0]])
                        {
                            ///case "0":$itog_transform="Ø";break;
                            case "g":$itog_transform="an";break;
                            case "v":$itog_transform="ān";break;
                        }
                        break;

                    default: $itog_transform=$itog;
                }

                if($debug)
                {
                    $debug_text.= "Заменили $itog на $itog_transform";
                }
            }
            else
            {
                $itog_transform=$itog;
            }
        }
        else
        {
            $itog_transform=$itog;
        }
    

    $new_word=str_replace($itog,$itog_transform,$new_word);
    $new_word_sandhi=str_replace($itog,$itog_transform,$new_word_sandhi);

    $dimensions_mool=dimensions($new_word,"smth","somenthing",0,0,0);  //ai & au будут отображаться отдельными символами
    $dimensions_mool_array=dimensions_array($dimensions_mool);

    //echo dimensions_table($dimensions_mool);

    $dlina_kornya=strlen($dimensions_mool[1]);

    $mool_last_letter=$dimensions_mool_array[$dlina_kornya-1][0];
    $mool_last_letter2=$dimensions_mool_array[$dlina_kornya-2][0];

    $mool_last_cons=$dimensions_mool_array[$dlina_kornya-1][1];
    $mool_last_vzryv=$dimensions_mool_array[$dlina_kornya-1][2];

    $seek_last_letter=seeking_1_bukva($mool_last_letter,0);
    //print_r($seek_last_letter);

    //echo "MOOL: ".$mool_last_letter;

    

    if($seek_last_letter[1]=="C"||$mool_last_cons=="C"||$mool_last_letter=="e"||$mool_last_letter=="o"||($mool_last_letter2=="a"&&$mool_last_letter=="u")||($mool_last_letter2=="a"&&$mool_last_letter=="i")) // Если на конце корня взрывная или сибилянт - смотрим еще сетность
    {

        $type_set_forms=array();

        switch($verb_setnost)
        {
           
            case "s":$type_set_forms[0]="s";$type_set_forms[1]="s";$type_set_forms[2]="s";$type_set_forms[4]="s";break;
            case "a":$type_set_forms[0]="a";$type_set_forms[1]="a";$type_set_forms[2]="a";$type_set_forms[4]="a";break;
            case "v":$type_set_forms[0]="v";$type_set_forms[1]="v";$type_set_forms[2]="v";$type_set_forms[4]="v";break;

            case "v1":$type_set_forms[0]="v";$type_set_forms[1]="v";$type_set_forms[2]="a";$type_set_forms[4]="v";break;
            case "v2":$type_set_forms[0]="s";$type_set_forms[1]="s";$type_set_forms[2]="v";$type_set_forms[4]="v";break;
            case "v3":$type_set_forms[0]="s";$type_set_forms[1]="s";$type_set_forms[2]="a";$type_set_forms[4]="v";break;
            case "v4":$type_set_forms[0]="v";$type_set_forms[1]="a";$type_set_forms[2]="a";$type_set_forms[4]="a";break;
            case "v5":
                
                if($suffix_ask[0]==1)
                {
                    $type_set_forms[0]="a";$type_set_forms[1]="a";$type_set_forms[2]="a";$type_set_forms[4]="a";
                }
                else
                {
                    $type_set_forms[0]="s";$type_set_forms[1]="s";$type_set_forms[2]="s";$type_set_forms[4]="s";    
                }
                break;
        }

        ///

       

        $first_letter_suffix=mb_substr($suffix[0],0,1);

        if($first_letter_suffix=="s"||$first_letter_suffix=="t")
        {
            $FLAG_NEED_SET=1;
        }

        //if($verb_setnost=="0"){$verb_setnost=" <b>в языке такой формы не встречается</b>";}

        
        
        if($FLAG_NEED_SET==1)
        {
           
                if($suffix[0]=="sya")
                {
                    if($verb_setnost=="0")
                    {
                       $FLAG_NO_FORM=1;
                    }
                    else
                    {
                    
                        if($debug)
                        {
                            $debug_text.= "Сетность: $verb_setnost, смотрим для FuS, основы будущего времени: сетность ";
                        }

                        $set_letter=$type_set_forms[0];
                        
                        if($debug)
                        { 
                            $debug_text.= "<b>$set_letter</b>";
                        }

                    }

                }
                else
                {
                    if($debug)
                    {
                        $debug_text.= "<BR>Сетность: $verb_setnost, пока не умеем образовывать сложные формы, берем сетность по умолчанию как для основы деепричастия: ";
                    }

                    $set_letter=$type_set_forms[4];
                    
                    if($debug)
                    {
                        $debug_text.= "<b>$set_letter</b>";
                    }
                }

                if($set_letter=="s"){$insert_i="|i|";}
                if($set_letter=="a"){$insert_i="";}
                if($set_letter=="v"){$insert_i="|i|";$insert_i2="";$flag_vet=1;}




                if($set_letter=="s"&&($first_letter_suffix=="s"||$first_letter_suffix=="t"))
                {
                    
                    $new_word_string=$new_word."|i|";
                    $new_word_sandhi=$new_word_sandhi."|i|";
                
                }
                elseif($set_letter=="v"&&($first_letter_suffix=="s"||$first_letter_suffix=="t"))
                {
                    
                    $new_word_string=$new_word."|i|";
                    $new_word_sandhi=$new_word_sandhi."|i|";
                    $new_word_string2=$new_word."";
                    $new_word_sandhi2=$new_word."";
                }
                else
                {
                    $new_word_string=$new_word;
                }
        
        }
        else
        {
            $new_word_string=$new_word;
        }
    }
    else
    {
        $new_word_string=$new_word;
    }

    

    if($suffix[1])
    {
        $two_suff_string=$suffix[0]."|".$suffix[1];
        //echo "SUFF2: ".$two_suff_string;

        $dimensions_suffix=dimensions($two_suff_string,"smth","somenthing",0,0,0);  //ai & au будут отображаться отдельными символами
        $dimensions_suffix_array=dimensions_array($dimensions_suffix);

        //echo dimensions_table($dimensions_suffix);
    
        $offset = 0;
        $allpos_suff = array();
        while (($pos = mb_strpos($dimensions_suffix[1], '|', $offset))!== false) {
            $offset   = $pos + 1;
            $allpos_suff[] = $pos-1;
        }

        //echo "Все вхождения стыков |: "; print_r($allpos_suff);

        $position_suff_styk=$allpos_suff[0];
        $first_cons=$dimensions_suffix_array[$position_suff_styk][1];

        $second_suff=$position_suff_styk+1;
        $second_suff_letter=$dimensions_suffix_array[$second_suff][0];

        if($second_suff_letter=="|")
        {
            $second_suff=$second_suff+1;
            $second_suff_letter=$dimensions_suffix_array[$second_suff][0];
        }

        $second_vzryv=$dimensions_suffix_array[$second_suff][2];

        //Если на стыке суффиксов образуется сочетание двух согласных, при этом второй суффикс начинается не на носовой или полугласный, то возникает i
    
        if($first_cons=="C"&&($second_vzryv=="T"||$second_vzryv=="S"))
        {
            $set_suffix="|i|";
            if($debug)
            { 
                $debug_text.= "<BR>На стыке между суффиксами будет сетная i";
            }
        }
        else
        {
            $set_suffix="||";
        }
        
    
    
        
        $new_word_string_sandhi=$new_word_sandhi."|".$suffix[0].$set_suffix.$suffix[1];
        $final_string=$new_word_string."".$suffix[0].$set_suffix.$suffix[1];
        $final_string=str_replace("|","",$final_string);
        
        if($new_word_string2)
        {
            $new_word_string_sandhi2=$new_word_sandhi2."|".$suffix[0].$set_suffix.$suffix[1];
            $final_string2=$new_word_string2."".$suffix[0].$set_suffix.$suffix[1];
            $final_string2=str_replace("|","",$final_string2);

        }

    

        

        
    

    }
    else
    {
        $new_word_string_sandhi=$new_word_sandhi."|".$suffix[0];
        $final_string=$new_word_string."".$suffix[0];
        $final_string=str_replace("|","",$final_string);

        if($new_word_string2)
        {
            $new_word_string_sandhi2=$new_word_sandhi2."|".$suffix[0];
            $final_string2=$new_word_string2."".$suffix[0];

        }

    }

    $new_word_string_sandhi=str_replace("Ø","",$new_word_string_sandhi);
    $new_word_string_sandhi=str_replace("Ø̄","",$new_word_string_sandhi);
    $new_word_string_sandhi=str_replace("||","|",$new_word_string_sandhi);
    $new_word_string_sandhi=str_replace("||","|",$new_word_string_sandhi);

    $new_word_string_sandhi2=str_replace("Ø","",$new_word_string_sandhi2);
    $new_word_string_sandhi2=str_replace("Ø̄","",$new_word_string_sandhi2);
    $new_word_string_sandhi2=str_replace("||","|",$new_word_string_sandhi2);
    $new_word_string_sandhi2=str_replace("||","|",$new_word_string_sandhi2);

    if($debug)
    {
        $debug_text.= "<BR><BR><b>Итог (без сандхи): ".$final_string; $debug_text.= "</b>";
    }

    if($new_word_string2)
    {
        if($debug)
        {
            $debug_text.= " также возможна форма <b>$final_string2</b> , т.к. сетность корня v <BR>";
        }
    }
    
    //echo "Строчка для сандхи: ".$new_word_string_sandhi; echo "<BR><BR>";
    //echo "Строчка для сандхи2: ".$new_word_string_sandhi2; echo "<BR><BR>";

    $dimensions_end=dimensions($new_word_string_sandhi,$itog,$mool,0,0,0);  //ai & au будут отображаться отдельными символами
    $dimensions_end_array=dimensions_array($dimensions_end);

    //echo dimensions_table($dimensions_end);

    $sandhi=sandhi($dimensions_end,$dimensions_end_array,$new_word_string_sandhi,0);

    if($debug)
    {
        $debug_text.= "<BR><b>Итог с сандхи (знаем ".$sandhi[3]." правил для внутренних из 40): ".$sandhi[0]; $debug_text.= "</b> ";
    }

    if($sandhi[1])
    {
        if($debug)
        {
            $debug_text.= "<BR>Применили правила Эмено: ";
            $debug_text.= $sandhi[1];
        }
    }

    $result1=$sandhi[0];


    if($new_word_string_sandhi2)
    {

        $dimensions_end=dimensions($new_word_string_sandhi2,$itog,$mool,0,0,0);  //ai & au будут отображаться отдельными символами
        $dimensions_end_array=dimensions_array($dimensions_end);

    // echo dimensions_table($dimensions_end);

        $sandhi=sandhi($dimensions_end,$dimensions_end_array,$new_word_string_sandhi2,0);

        if($debug)
        {
            $debug_text.= "<BR><BR><b>Вторая форма с сандхи: ".$sandhi[0]; $debug_text.= "</b> ";
        

            if($sandhi[1])
            {
                $debug_text.= "<BR>Применили правила Эмено: ";
                $debug_text.= $sandhi[1];
            }

        }

        $result2=$sandhi[0];

    }

    /*
    echo "<BR><BR><BR><BR>";


    $test_word="a|a|sas|dhi|a|a";

    $dimensions_test=dimensions($test_word,"som","smth",0,0,1);

    $dimensions_test_array=dimensions_array($dimensions_test);

    echo dimensions_table($dimensions_test);

    $sandhi=sandhi($dimensions_test,$dimensions_test_array,$test_word,1);

    echo "<BR><b>Тест сандхи (знаем ".$sandhi[3]." правил для внутренних из 40): ".$sandhi[0]; echo "</b> ";
    if($sandhi[1])
    {
        echo "<BR>Применили правила Эмено: ";
        echo $sandhi[1];
    }
    */

    $result[0]=$result1;
    $result[1]=$result2;

    if($FLAG_NO_FORM==1)
    {
        $result[0]="Нет формы";

        if($debug)
        {
            echo "<b>В языке такая форма не встречается</b>";
        }
    }
    else
    {
        if($debug)
        {
            echo $debug_text;
        }
    }

    return $result;

    }

    function ey_or_not($word_1,$ep,$mool_change)
    {
        if($mool_change=="m̥̄"||$mool_change=="n̥̄"){$count=3;}
        elseif($mool_change=="m̥"||$mool_change=="n̥"||$mool_change=="Ø̄"){$count=2;}
        else{$count=1;}
        
        
        $next_bukva=mb_substr($word_1,$ep+$count,1);

    
        
        if($next_bukva=="y")
        {
            $line_3="Ey";
        }
        else {
            $line_3="Eне-y";
        }
        
        //echo "EY$next_bukva<BR>$line_3";

        return $line_3;
    }

    function ev_or_not($word_1,$ep,$mool_change)
    {
        
        if($mool_change=="m̥̄"||$mool_change=="n̥̄"){$count=3;}
        elseif($mool_change=="m̥"||$mool_change=="n̥"||$mool_change=="Ø̄"){$count=2;}
        else{$count=1;}
        
        $next_bukva=mb_substr($word_1,$ep+$count,1);
        
        if($next_bukva=="v")
        {
            $line_3="Ev";
        }
        else {
            $line_3="Eне-v";
        }
        //echo "EV$next_bukva<BR>$line_3";
        return $line_3;
    }

    function em_or_not($word_1,$ep,$mool_change)
    {
        
        if($mool_change=="m̥̄"||$mool_change=="n̥̄"){$count=3;}
        elseif($mool_change=="m̥"||$mool_change=="n̥"||$mool_change=="Ø̄"){$count=2;}
        else{$count=1;}
        
        $next_bukva=mb_substr($word_1,$ep+$count,1);
        
        if($next_bukva=="m")
        {
            $line_3="Em";
        }
        else {
            $line_3="Eне-m";
        }
    // echo "<BR>$word_1 EM $next_bukva  $line_3 <BR>";
        return $line_3;
    }

    function what_is_before($word_1,$ep)
    {
        
        $consonants=["k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s","h"];
        $vowels=["a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","ai","o","au"];
        $gubnye=["p", "ph", "b", "bh","m"];
        
        $before_bukva=mb_substr($word_1,$ep-2,1);
        $before_2bukva=mb_substr($word_1,$ep-3,2);
        $before_3bukva=mb_substr($word_1,$ep-4,3);
        
        $itog[0]=$before_bukva;
        $itog[1]="";
        $itog[2]="";
        
        for($i=0;$i<count($consonants);$i++)
        {
            if($before_bukva==$consonants[$i])
            {
                $itog[1]="C"; //Cons.
            }
        }
        
        for($i=0;$i<count($vowels);$i++)
        {
            if($before_bukva==$vowels[$i])
            {
                $itog[1]="V"; //Vow.
            }
        }
        
        for($i=0;$i<count($gubnye);$i++)
        {
            
            if($before_bukva==$gubnye[$i]||$before_2bukva==$gubnye[$i])
            {
                $itog[2]="G"; //gubnye
            }
        }
        //print_r($itog);
        return $itog;
    }

    function aksharas($word_1,$mool_change,$mool,$need_e,$debug,$without_ai_au)
    {
        
        if(!$without_ai_au)
        {
        $all=["|","k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s","h","ṃ","ḥ","a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","ai","o","au","Ø̄","Ø","m̥̄","m̥","n̥","n̥̄"];
        }
        else
        {
            $all=["|","k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s","h","ṃ","ḥ","a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","o","Ø̄","Ø","m̥̄","m̥","n̥","n̥̄"];    
        }
        
        $special=["|"];
        
        //$vowels=["a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","ai","o","au"];
        
        $mool_begin=mb_strpos($word_1,$mool);
        $mool_end=$mool_begin+mb_strlen($mool);

        if($debug){echo "Корень: ".$mool." Чередуется: ".$mool_change."<BR>";}


        $k=0;
        for($i=0;$i<mb_strlen($word_1);$i++)
        {
            $bukva=mb_substr($word_1,$i,1);

            
            if($i!=mb_strlen($word_1)-1)
            {
                $bukva2=mb_substr($word_1,$i,2);
            }
            else
            {
                $bukva2=mb_substr($word_1,$i,2)."#";
            }

            $bukva3=mb_substr($word_1,$i,3);

        if($debug){echo $bukva." ".$bukva2." ".$bukva3." I:".$i." K:$k ";}

        if($need_e)
        {

            if($k==$mool_begin)
            {
                $vc[$k]="#&";
                if($debug){echo  $vc[$k];}
                $k++;

            }

            }

            $cycle=seeking_2_bukva($all,$bukva3,$bukva2,$bukva,0,$k,$i,$mool_begin,$mool_end,$mool_change,0,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

        
    
            if($debug){echo " K-end $k <BR>";}
        }



        $vc=array_values($vc);

        if($debug){print_r($vc);}

        for($i=0;$i<count($vc);$i++)
        {
            $string_vc=$string_vc.$vc[$i];
        }


        //print_r($vc);
        
        
        return $string_vc;
        
    }

    function cons_vow($word_1,$mool_change,$mool,$need_e,$debug,$without_ai_au)
    {
        
        $consonants=["k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s","h","ṃ","ḥ"];
        
        if(!$without_ai_au)
        {
            $vowels=["a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","ai","o","au"];
        }
        else
        {
            $vowels=["a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","o"];
        }

        $nul=["Ø̄","Ø","m̥̄","m̥","n̥","n̥̄"];
        $special=["|"];
        
        $mool_begin=mb_strpos($word_1,$mool);
        $mool_end=$mool_begin+mb_strlen($mool);

        if($debug){echo "Корень: ".$mool." Чередуется: ".$mool_change."<BR>";}


        $k=0;$flag_E_change=0;
        for($i=0;$i<mb_strlen($word_1);$i++)
        {
            $bukva=mb_substr($word_1,$i,1);
            if($i!=mb_strlen($word_1)-1)
            {
                $bukva2=mb_substr($word_1,$i,2);
            }
            else
            {
                $bukva2=mb_substr($word_1,$i,2)."#";
            }

            $bukva3=mb_substr($word_1,$i,3);
            

        if($debug){echo $bukva." ".$bukva2." ".$bukva3." I:".$i." K:$k ";}

        $flag_e=0;

        if($need_e)
        { 
                if($k==$mool_begin)
                {
                    $vc[$k]="#";
                    if($debug){echo  $vc[$k];}
                    $k++;

                }

        }

            if($need_e)
            {   
                
                if($mool_change=="al"||$mool_change=="an"||$mool_change=="am"||$mool_change=="ar"||$mool_change=="ai")
                {
                    if($bukva2==$mool_change&&($i>=$mool_begin&&$i<$mool_end)&&!$flag_e)
                    {
                        $vc[$k]="E"; 
                        $vc[$k+1]="E"; 
                        if($debug){echo  $vc[$k];}
                        $k=$k+2; 
                        $flag_e=1;
                        $howmuche=2;$i++;
                    }
                }
                else
                {
                
                    if($mool_change!="Ø̄"&&$mool_change!="m̥̄"&&$mool_change!="n̥̄"&&$mool_change!="m̥"&&$mool_change!="n̥"&&$mool_change!="n̥")
                    {
                        if($bukva==$mool_change&&($i>=$mool_begin&&$i<$mool_end)&&!$flag_e)
                        {
                            $vc[$k]="E"; 
                            if($debug){echo  $vc[$k];}
                            $k++; 
                            $flag_e=1;
                        }
                    }
                    elseif($mool_change!="m̥̄"&&$mool_change!="n̥̄")
                    {
                        if($bukva2==$mool_change&&($i>=$mool_begin&&$i<$mool_end)&&!$flag_e)
                        {
                            $vc[$k]="E"; 
                            if($debug){echo  $vc[$k];}
                            $k++; 
                            $flag_e=1;
                        }
                    }
                    else
                    {
                    
                        if($bukva3==$mool_change&&($i>=$mool_begin&&$i<$mool_end)&&!$flag_e)
                        {
                            $vc[$k]="E"; 
                            if($debug){echo  $vc[$k];}
                            $k++; 
                            $flag_e=1;
                        }
                    }

                }
            }

            if($debug){echo  "Mool_end: $mool_end   Flag_e: $flag_e ";}
            if(!$flag_e)
            {

            $cycle=seeking_2_bukva($consonants,$bukva3,$bukva2,$bukva,"C",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            $cycle=seeking_2_bukva($vowels,$bukva3,$bukva2,$bukva,"V",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            $cycle=seeking_2_bukva($nul,$bukva3,$bukva2,$bukva,"-",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            $cycle=seeking_2_bukva($special,$bukva3,$bukva2,$bukva,"|",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            }

            if($debug){echo " K-end $k <BR>";}
        }



        $vc=array_values($vc);

        if($debug){print_r($vc);}

        for($i=0;$i<count($vc);$i++)
        {
            $string_vc=$string_vc.$vc[$i];
        }


        //print_r($vc);
        
        
        return $string_vc;
        
    }

    function shum_sibil($word_1,$mool_change,$mool,$need_e,$debug,$without_ai_au)
    {
        
        $consonants=["k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m",  "ś", "ṣ", "s","h"];
    
        if(!$without_ai_au)
        {
            $vowels=["a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","ai","o","au"];
        }
        else
        {
            $vowels=["a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","o"];
        }

        $semivowels=["y", "r", "l", "v"];
        $nul=["Ø̄","Ø"];
        $special=["|"];
        
        $gubnye=["p", "ph", "b", "bh","m","v"];
        $sibils=["ś", "ṣ", "s"];
        $nosovye=["ṅ", "ñ", "ṇ", "n", "m"];
        $shumnye=["k", "kh", "g", "gh", "c", "ch", "j", "jh", "ṭ", "ṭh", "ḍ", "ḍh", "t", "th", "d", "dh","p", "ph", "b", "bh"];
        $anusvara=["ṃ"];
        $visarga=["ḥ"];
        $pridyhanye=["h"];
    
        
        $mool_begin=mb_strpos($word_1,$mool);
        $mool_end=$mool_begin+mb_strlen($mool);
        $vc=[];
        $k=0;

        if($debug){echo "<BR>";}

        for($i=0;$i<mb_strlen($word_1);$i++)
        {
            $bukva=mb_substr($word_1,$i,1);
            
            
            if($i!=mb_strlen($word_1)-1)
            {
                $bukva2=mb_substr($word_1,$i,2);
            }
            else
            {
                $bukva2=mb_substr($word_1,$i,2)."#";
            }
    
            $bukva3=mb_substr($word_1,$i,3);

            if($debug){echo $bukva." ".$bukva2." I:".$i." K:$k ";}
            $flag_e=0;



        
        
            if($need_e)
            {   
                
                
                if($k==$mool_begin)
                {
                    $vc[$k]="#";
                    if($debug){echo  $vc[$k];}
                    $k++;
        
                }
                
                if($mool_change=="al"||$mool_change=="an"||$mool_change=="am"||$mool_change=="ar"||$mool_change=="ai")
                {
                    if($bukva2==$mool_change&&($i>=$mool_begin&&$i<$mool_end)&&!$flag_e)
                    {
                        $vc[$k]="E"; 
                        $vc[$k+1]="E"; 
                        if($debug){echo  $vc[$k];}
                        $k=$k+2; 
                        $flag_e=1;
                        $howmuche=2;$i++;
                    }
                }
                else
                {


                
                if($mool_change!="Ø̄"&&$mool_change!="m̥̄"&&$mool_change!="n̥̄"&&$mool_change!="m̥"&&$mool_change!="n̥")
                {
                    if($bukva==$mool_change&&($i>=$mool_begin&&$i<$mool_end)&&!$flag_e)
                    {
                        $vc[$k]="E"; 
                        if($debug){echo  $vc[$k];}
                        $k++; 
                        $flag_e=1;
                    }
                }
                elseif($mool_change!="m̥̄"&&$mool_change!="n̥̄")
                {
                    if($bukva2==$mool_change&&($i>=$mool_begin&&$i<$mool_end)&&!$flag_e)
                    {
                        $vc[$k]="E"; 
                        if($debug){echo  $vc[$k];}
                        $k++; 
                        $flag_e=1;
                    }
                }
                else
                {
                
                    if($bukva3==$mool_change&&($i>=$mool_begin&&$i<$mool_end)&&!$flag_e)
                    {
                        $vc[$k]="E"; 
                        if($debug){echo  $vc[$k];}
                        $k++; 
                        $flag_e=1;
                    }
                }

                }
            }

            if($debug){echo  " Flag_e $flag_e ";}
            if(!$flag_e)
            {

            $cycle=seeking_2_bukva($shumnye,$bukva3,$bukva2,$bukva,"T",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

        // if($debug){echo " K-T: $k ";}

            $cycle=seeking_2_bukva($sibils,$bukva3,$bukva2,$bukva,"S",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

        //  if($debug){echo " K-S: $k ";}

            $cycle=seeking_2_bukva($vowels,$bukva3,$bukva2,$bukva,"V",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

        //  if($debug){echo " K-V: $k ";}

            $cycle=seeking_2_bukva($semivowels,$bukva3,$bukva2,$bukva,"v",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

        //   if($debug){echo " K-v: $k ";}

            $cycle=seeking_2_bukva($nosovye,$bukva3,$bukva2,$bukva,"N",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

        //   if($debug){echo " K-n: $k ";}

            $cycle=seeking_2_bukva($anusvara,$bukva3,$bukva2,$bukva,"a",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            $cycle=seeking_2_bukva($visarga,$bukva3,$bukva2,$bukva,"i",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            $cycle=seeking_2_bukva($pridyhanye,$bukva3,$bukva2,$bukva,"h",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            $cycle=seeking_2_bukva($nul,$bukva3,$bukva2,$bukva,"-",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            $cycle=seeking_2_bukva($special,$bukva3,$bukva2,$bukva,"|",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            }

            if($debug){echo " Kend: $k <BR>";}
        }

        if($debug){echo " K end after cycle: $k <BR>";}

        if($debug){print_r($vc);}

        for($i=0;$i<count($vc);$i++)
        {
            $string_vc=$string_vc.$vc[$i];
        }

        return $string_vc;
        
    }

    function gubnye($word_1,$mool_change,$mool,$need_e,$debug,$without_ai_au)
    {
        
        $consonants=["k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s","h"];

        $special=["|"];
        
        if(!$without_ai_au)
        {
            $vowels=["a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","ai","o","au"];
        }
        else
        {
            $vowels=["a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","o"];
        }
        

        if(!$without_ai_au)
        {
        $gubnye=["p","ph","b","bh","m","v","u","ū","o","au"];
        }
        else
        {
            $gubnye=["p","ph","b","bh","m","v","u","ū","o"];  
        }

        $velum=["a","ā","k", "kh", "g", "gh", "ṅ"];

        if(!$without_ai_au)
        {
        $palatum=["i","ī","e","ai", "c", "ch", "j", "jh", "ñ", "y", "ś"];
        }
        else
        {
            $palatum=["i","ī","e","c", "ch", "j", "jh", "ñ", "y", "ś"];   
        }

        $retro=["ṛ","ṝ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "r", "ṣ"];
        $dental=["ḷ", "t", "th", "d", "dh", "n", "l", "s"];
        $others=["ṃ","ḥ","h"];

        $nul=["Ø̄","Ø"];

        $mool_begin=mb_strpos($word_1,$mool);
        $mool_end=$mool_begin+mb_strlen($mool);
    
        if($debug){echo "<BR>";}

        $k=0;
        for($i=0;$i<mb_strlen($word_1)+1;$i++)
        {
            $bukva=mb_substr($word_1,$i,1);
            if($i!=mb_strlen($word_1)-1)
            {
                $bukva2=mb_substr($word_1,$i,2);
            }
            else
            {
                $bukva2=mb_substr($word_1,$i,2)."#";
            }
            
            if($i!=mb_strlen($word_1)-2)
            {
                $bukva3=mb_substr($word_1,$i,3);
            }
            else
            {
                $bukva3=mb_substr($word_1,$i,3)."#";
            }
            
        
            if($debug){echo $bukva." ".$bukva2." ".$bukva3." I:".$i." K:$k ";}
            $flag_e=0;

        

            if($need_e)
            {   
                if($k==$mool_begin)
            {
                $vc[$k]="#";
                if($debug){echo $vc[$k];}
                $k++;

            }
                if($mool_change=="al"||$mool_change=="an"||$mool_change=="am"||$mool_change=="ar"||$mool_change=="ai")
                {
                    if($bukva2==$mool_change&&($i>=$mool_begin&&$i<$mool_end)&&!$flag_e)
                    {
                        $vc[$k]="E"; 
                        $vc[$k+1]="E"; 
                        if($debug){echo  $vc[$k];}
                        $k=$k+2; 
                        $flag_e=1;
                        $howmuche=2;$i++;
                    }
                }
                else
                {
                
                    if($mool_change!="Ø̄"&&$mool_change!="m̥̄"&&$mool_change!="n̥̄"&&$mool_change!="m̥"&&$mool_change!="n̥"&&$mool_change!="ai"&&$mool_change!="au")
                    {
                        if($bukva==$mool_change&&($i>=$mool_begin&&$i<$mool_end)&&!$flag_e)
                        {
                            $vc[$k]="E"; 
                            if($debug){echo  $vc[$k];}
                            $k++; 
                            $flag_e=1;
                        }
                    }
                    elseif($mool_change!="m̥̄"&&$mool_change!="n̥̄")
                    {
                        if($bukva2==$mool_change&&($i>=$mool_begin&&$i<$mool_end)&&!$flag_e)
                        {
                            $vc[$k]="E"; 
                            if($debug){echo  $vc[$k];}
                            $k++; 
                            $flag_e=1;
                        }
                    }
                    else
                    {
                    
                        if($bukva3==$mool_change&&($i>=$mool_begin&&$i<$mool_end)&&!$flag_e)
                        {
                            $vc[$k]="E"; 
                            if($debug){echo  $vc[$k];}
                            $k++; 
                            $flag_e=1;
                        }
                    }

                }
            }
            
            if(!$flag_e)
            {
            $cycle=seeking_2_bukva($gubnye,$bukva3,$bukva2,$bukva,"L",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            $cycle=seeking_2_bukva($velum,$bukva3,$bukva2,$bukva,"V",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];
            
            $cycle=seeking_2_bukva($palatum,$bukva3,$bukva2,$bukva,"P",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            $cycle=seeking_2_bukva($retro,$bukva3,$bukva2,$bukva,"R",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            $cycle=seeking_2_bukva($dental,$bukva3,$bukva2,$bukva,"D",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            $cycle=seeking_2_bukva($others,$bukva3,$bukva2,$bukva,"O",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            $cycle=seeking_2_bukva($nul,$bukva3,$bukva2,$bukva,"-",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            $cycle=seeking_2_bukva($special,$bukva3,$bukva2,$bukva,"|",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            }

            if($debug){echo " K-end $k <BR>";}
        }

        if($debug){print_r($vc);}

        for($i=0;$i<count($vc);$i++)
        {
            $string_vc=$string_vc.$vc[$i];
        }

        return $string_vc;
        
    }


    function voice_deaf($word_1,$mool_change,$mool,$need_e,$debug,$without_ai_au)
    {
        
        $consonants=["k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s","h"];
        $vowels=["a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","ai","o","au"];
        
        $gubnye=["p","ph","b","bh","m","v","u","ū","o","au"];
        $velum=["a","ā","k", "kh", "g", "gh", "ṅ"];
        $palatum=["i","ī","e","ai", "c", "ch", "j", "jh", "ñ", "y", "ś"];
        $retro=["ṛ","ṝ","e","ai", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "r", "ṣ"];
        $dental=["ḷ", "t", "th", "d", "dh", "n", "l", "s"];
        $others=["ṃ","ḥ","h"];
        $special=["|"];

        $nul=["Ø̄","Ø"];

        if(!$without_ai_au)
        {
        
        $voicedness=["ṃ","h","a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","ai","o","au", "g", "gh", "ṅ", "j", "jh", "ñ", "ḍ", "ḍh", "ṇ", "d", "dh", "n", "b", "bh", "m", "y", "r", "l", "v"];

        }
        else
        {

        $voicedness=["ṃ","h","a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","o", "g", "gh", "ṅ", "j", "jh", "ñ", "ḍ", "ḍh", "ṇ", "d", "dh", "n", "b", "bh", "m", "y", "r", "l", "v"];

        }
        
        $deafness=["ḥ","k", "kh","c", "ch","ṭ", "ṭh","t", "th", "p", "ph","ś", "ṣ", "s"];

        $mool_begin=mb_strpos($word_1,$mool);
        $mool_end=$mool_begin+mb_strlen($mool);
    
        if($debug){echo "<BR>";}

        $k=0;
        for($i=0;$i<mb_strlen($word_1)+1;$i++)
        {
            $bukva=mb_substr($word_1,$i,1);
            if($i!=mb_strlen($word_1)-1)
            {
                $bukva2=mb_substr($word_1,$i,2);
            }
            else
            {
                $bukva2=mb_substr($word_1,$i,2)."#";
            }
            $bukva3=mb_substr($word_1,$i,3);

            if($debug){echo $bukva." ".$bukva2." I:".$i." K:$k ";}

            $flag_e=0;


        

                if($need_e)
                {   
                    
                    if($k==$mool_begin)
                {
                    $vc[$k]="#";
                    if($debug){echo $vc[$k];}
                    $k++;

                }
                    
                if($mool_change=="al"||$mool_change=="an"||$mool_change=="am"||$mool_change=="ar"||$mool_change=="ai")
                {
                    if($bukva2==$mool_change&&($i>=$mool_begin&&$i<$mool_end)&&!$flag_e)
                    {
                        $vc[$k]="E"; 
                        $vc[$k+1]="E"; 
                        if($debug){echo  $vc[$k];}
                        $k=$k+2; 
                        $flag_e=1;
                        $howmuche=2;$i++;
                    }
                }
                else
                {

                    if($mool_change!="Ø̄"&&$mool_change!="m̥̄"&&$mool_change!="n̥̄"&&$mool_change!="m̥"&&$mool_change!="n̥")
                    {
                        if($bukva==$mool_change&&($i>=$mool_begin&&$i<$mool_end)&&!$flag_e)
                        {
                            $vc[$k]="E"; 
                            if($debug){echo  $vc[$k];}
                            $k++; 
                            $flag_e=1;
                        }
                    }
                    elseif($mool_change!="m̥̄"&&$mool_change!="n̥̄")
                    {
                        if($bukva2==$mool_change&&($i>=$mool_begin&&$i<$mool_end)&&!$flag_e)
                        {
                            $vc[$k]="E"; 
                            if($debug){echo  $vc[$k];}
                            $k++; 
                            $flag_e=1;
                        }
                    }
                    else
                    {
                    
                        if($bukva3==$mool_change&&($i>=$mool_begin&&$i<$mool_end)&&!$flag_e)
                        {
                            $vc[$k]="E"; 
                            if($debug){echo  $vc[$k];}
                            $k++; 
                            $flag_e=1;
                        }
                    }

                }
            }
            
            if(!$flag_e)
            {
            $cycle=seeking_2_bukva($voicedness,$bukva3,$bukva2,$bukva,"V",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            $cycle=seeking_2_bukva($deafness,$bukva3,$bukva2,$bukva,"D",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            
            $cycle=seeking_2_bukva($nul,$bukva3,$bukva2,$bukva,"-",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            $cycle=seeking_2_bukva($special,$bukva3,$bukva2,$bukva,"|",$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug);

            $i=$cycle[2];
            $k=$cycle[1];
            $vc[$cycle[3]]=$cycle[0];

            }
        
            if($debug){echo " K-end $k <BR>";}
        }

        if($debug){print_r($vc);}

        for($i=0;$i<count($vc);$i++)
        {
            $string_vc=$string_vc.$vc[$i];
        }

        return $string_vc;
        
    }


    function find_bukvi($string,$howmany,$what,$napravlenie)
    {
        $e_begin=mb_strpos($string,$what);
    

        if($napravlenie==1)
        {
            $find=mb_substr($string,$e_begin+1,$howmany);
        }

        if($napravlenie==-1)
        {
            if($howmany>$e_begin){$howmany=$e_begin;}
            $find=mb_substr($string,$e_begin-$howmany,$howmany);
        }

        return $find;
    }


    function seeking_2_bukva($massive,$bukva3,$bukva2,$bukva,$label,$k,$i,$mool_begin,$mool_end,$mool_change,$need_e,$howmuche,$debug)
    {
            
            if($howmuche==2)
            {
                $k++;
            }

            $flag_find_2=0;$flag_find_3=0;
            $last_k=$k;

        
        // if($debug){echo " Inside Function:  last K: ". $last_k;}

            if($bukva2&&$bukva&&$bukva3)
            {

                for($j=0;$j<count($massive);$j++)
                {
                    
                    if($bukva3==$massive[$j])
                    {
                        if(($i<=$mool_begin||$i>=$mool_end)||$label=="0")
                        {
                                if($label!="0")
                                {
                                    $vc[$k]=$label; 
                                }
                                else
                                {
                                    $vc[$k]=$bukva3."&";
                                }
        
                                if($debug){echo "three: $bukva3 ".$vc[$k];}
                                $flag_find_3=1;
                                $k++;
                                $i++;
                        }
                        else
                        {
                    
                            if($bukva3!=$mool_change||$need_e==0)
                            {
                                if($label!="0")
                                {
                                    $vc[$k]=$label; 
                                }
                                else
                                {
                                    $vc[$k]=$bukva3."&";
                                }
                                    if($debug){echo "three2: ".$vc[$k];}
                                    $k++;
                                    $i++;
                                    
                            }
                    }
                    }
                }
        
                if(!$flag_find_3)
                {

                        for($j=0;$j<count($massive);$j++)
                        {
                        
                            if($bukva2==$massive[$j])
                            {
                                if(($i<=$mool_begin||$i>=$mool_end)||$label=="0")
                                {
                                        if($label!="0")
                                        {
                                            $vc[$k]=$label; 
                                        }
                                        else
                                        {
                                            $vc[$k]=$bukva2."&";
                                        }

                                        if($debug){echo "two: $bukva2 ".$vc[$k];}
                                        $flag_find_2=1;
                                        $k++;
                                        $i++;
                                }
                                else
                                {
                            
                                    if($bukva2!=$mool_change||$need_e==0)
                                    {
                                        if($label!="0")
                                        {
                                            $vc[$k]=$label; 
                                        }
                                        else
                                        {
                                            $vc[$k]=$bukva2."&";
                                        }
                                            if($debug){echo "two2: ".$vc[$k];}
                                            $k++;
                                            $i++;
                                            
                                    }
                                }
                            }
                        }

                        //  if($debug){echo "<BR> Перед проверкой на 1 букву j:$j Flag2: ".$flag_find_2." Flag3: ".$flag_find_3."<BR>";}

            if(!$flag_find_2)
            {
                for($j=0;$j<count($massive);$j++)
                {
                
                    if($bukva==$massive[$j])
                    {
                        if(($i<=$mool_begin||$i>=$mool_end)||$label=="0")
                        {
                
                            if($label!="0")
                            {
                                $vc[$k]=$label; 
                            }
                            else
                            {
                                $vc[$k]=$bukva."&";
                            } 
                                if($debug){echo "one: ".$vc[$k];}
                                $k++;
                                $i=$i;
                        }
                        else
                        {
                    
                            if($bukva!=$mool_change||$need_e==0)
                            {
                                if($label!="0")
                                {
                                    $vc[$k]=$label; 
                                }
                                else
                                {
                                    $vc[$k]=$bukva."&";
                                } 
                                    if($debug){echo "one2: ".$vc[$k];}
                                    $k++;
                                    $i=$i;
                            }
                    }
                    }
                    
                    
                }
            }
            }
        }
            
    
        
        if($k>$last_k)
        {
            if($label!="0")
            {
                $itog[0]=$label;
            }
            else
            {
                $itog[0]=$vc[$last_k];    
            }
        }
            
            $itog[1]=$k;
            $itog[2]=$i;
            $itog[3]=$last_k;

            return $itog;
}

function get_e_mp_simple($mool_type_change,$mool_type,$mp)
{
    switch ($mool_type)
    {
            case "1":$answer[1]="0";$answer[2]="g";$answer[3]="v";break;
            case "2":$answer[1]="g";$answer[2]="g";$answer[3]="v";break;
            case "3":$answer[1]="0";$answer[2]="0";$answer[3]="0";break;
            case "4":$answer[1]="v";$answer[2]="v";$answer[3]="v";break;
    }
    
    
    switch($mool_type_change)
    {
        case "A1":
            switch($answer[$mp])
            {
                case "0":$itog_transform="Ø";break;
                case "g":$itog_transform="a";break;
                case "v":$itog_transform="ā";break;
            }
            break;

        case "A2":
            switch($answer[$mp])
            {
                case "0":$itog_transform="Ø̄";break;
                case "g":$itog_transform="ā";break;
                case "v":$itog_transform="ā";break;
            }
            break;

        case "I0":

            switch($answer[$mp])
            {
                case "0":$itog_transform="i";break;
                case "g":$itog_transform="e";break;
                case "v":$itog_transform="ai";break;
            }
            break;

        case "I1":

            switch($answer[$mp])
            {
                case "0":$itog_transform="i";break;
                case "g":$itog_transform="e";break;
                case "v":$itog_transform="ai";break;
            }
            break;
          
        case "I2":

                switch($answer[$mp])
                {
                    case "0":$itog_transform="ī";break;
                    case "g":$itog_transform="e";break;
                    case "v":$itog_transform="ai";break;
                }
                break;
        
        case "U":

            switch($answer[$mp])
            {
                case "0":$itog_transform="u";break;
                case "g":$itog_transform="o";break;
                case "v":$itog_transform="au";break;
            }
            break;

        case "U1":

            switch($answer[$mp])
            {
                case "0":$itog_transform="u";break;
                case "g":$itog_transform="o";break;
                case "v":$itog_transform="au";break;
            }
            break;

        case "U2":

            switch($answer[$mp])
            {
                case "0":$itog_transform="ū";break;
                case "g":$itog_transform="o";break;
                case "v":$itog_transform="au";break;
            }
            break;


            
        case "R0":
 
            switch($answer[$mp])
            {
                case "0":$itog_transform="ṛ";break;
                case "g":$itog_transform="ar";break;
                case "v":$itog_transform="ār";break;
            }
            break;

        case "R1":
 
                switch($answer[$mp])
                {
                    case "0":$itog_transform="ṛ";break;
                    case "g":$itog_transform="ar";break;
                    case "v":$itog_transform="ār";break;
                }
                break;

        case "R2":
 
                switch($answer[$mp])
                {
                    case "0":$itog_transform="ṝ";break;
                    case "g":$itog_transform="ar";break;
                    case "v":$itog_transform="ār";break;
                }
                break;

        
        
           
        case "L":

            switch($answer[$mp])
            {
                case "0":$itog_transform="ḷ";break;
                case "g":$itog_transform="al";break;
                case "v":$itog_transform="āl";break;
            }
            break;

        case "M0":

            switch($answer[$mp])
            {
                case "0":$itog_transform="m̥";break;
                case "g":$itog_transform="am";break;
                case "v":$itog_transform="ām";break;
            }
            break;

        case "M1":

            switch($answer[$mp])
            {
                case "0":$itog_transform="m̥";break;
                case "g":$itog_transform="am";break;
                case "v":$itog_transform="ām";break;
            }
            break;

        case "M2":

            switch($answer[$mp])
            {
                case "0":$itog_transform="m̥̄";break;
                case "g":$itog_transform="am";break;
                case "v":$itog_transform="ām";break;
            }
            break;

            
        case "N0":

            switch($answer[$mp])
            {
                case "0":$itog_transform="Ø";break;
                case "g":$itog_transform="an";break;
                case "v":$itog_transform="ān";break;
            }
            break;
        
        case "N1":

            switch($answer[$mp])
            {
                case "0":$itog_transform="n̥";break;
                case "g":$itog_transform="an";break;
                case "v":$itog_transform="ān";break;
            }
            break;

        case "N1":

            switch($answer[$mp])
            {
                case "0":$itog_transform="n̥̄";break;
                case "g":$itog_transform="an";break;
                case "v":$itog_transform="ān";break;
            }
            break;

         
    }

    return $itog_transform;
}

function seeking_1_bukva($bukva,$without_ai_au)
{

    $consonants=["k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s","h","ṃ","ḥ"];
    
    if(!$without_ai_au)
    {
        $vowels=["a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","ai","o","au"];
    }
    else
    {
        $vowels=["a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","o"];
    }

    $semivowels=["y", "r", "l", "v"];
    $nul=["Ø̄","Ø"];
    $special=["|"];
    
    $gubnye=["p", "ph", "b", "bh","m","v"];
    $sibils=["ś", "ṣ", "s"];
    $nosovye=["ṅ", "ñ", "ṇ", "n", "m"];
    $shumnye=["k", "kh", "g", "gh", "c", "ch", "j", "jh", "ṭ", "ṭh", "ḍ", "ḍh", "t", "th", "d", "dh","p", "ph", "b", "bh"];
    $anusvara=["ṃ"];
    $visarga=["ḥ"];
    $pridyhanye=["h"];


    for($i=0;$i<count($consonants);$i++)
    {
        if($bukva==$consonants[$i])
        {
            $cons="C";
        }
    }

    for($i=0;$i<count($vowels);$i++)
    {
        if($bukva==$vowels[$i])
        {
            $cons="V";
        }
    }

    for($i=0;$i<count($gubnye);$i++)
    {
        if($bukva==$gubnye[$i])
        {
            $vzryv="L";
        }
    }

    for($i=0;$i<count($sibils);$i++)
    {
        if($bukva==$sibils[$i])
        {
            $vzryv="S";
        }
    }

    $result[0]=$bukva;
    $result[1]=$cons;
    $result[2]=$vzryv;

    return $result;
}


function dimensions($word_1,$mool_change,$mool,$need_e,$debug,$without_ai_au)
{
    
    //echo "Debug:".$debug."<BR>";
    //echo "Without:".$without_ai_au."<BR>";
    
    $string_vc=cons_vow($word_1,$mool_change,$mool,$need_e,$debug,$without_ai_au);
    //echo "<BR>Последовательность гласные-согласные: ";
    $new_vc=str_replace("E","<b>E</b>",$string_vc);
   
    
    $string_ss=shum_sibil($word_1,$mool_change,$mool,$need_e,$debug,$without_ai_au);
    $new_ss=str_replace("E","<b>E</b>",$string_ss);
    
    
    $string_gub=gubnye($word_1,$mool_change,$mool,$need_e,$debug,$without_ai_au);
    $new_gub=str_replace("E","<b>E</b>",$string_gub);
   
    
    $string_vd=voice_deaf($word_1,$mool_change,$mool,$need_e,$debug,$without_ai_au);
    $new_vd=str_replace("E","<b>E</b>",$string_vd);
 

    $string_all=aksharas($word_1,$mool_change,$mool,$need_e,$debug,$without_ai_au);
    $string_all_array=explode("&",$string_all);

    $k=0;
    for($i=0;$i<count($string_all_array);$i++)
    {
        if($string_all_array[$i]!="")
        {
            $new_massive[$k]=$string_all_array[$i];
            $k++;
        }
    }
    

    $itog[0]="<BR>".$string_all."<BR>".$new_vc."<BR>".$new_ss."<BR>".$new_gub."<BR>".$new_vd;
    $itog[1]=$string_vc;
    $itog[2]=$string_ss;
    $itog[3]=$string_gub;
    $itog[4]=$string_vd;
    $itog[5]=$string_all;
    $itog[6]=$new_massive;

    return $itog;

}

function dimensions_table($dimensions)
{

    $begin='<table width="10%" class="table table-bordered table-fit"><thead><tr>';

   // $head_table.='<th scope="col"></th>';

    for($i=0;$i<count($dimensions[6]);$i++)
    {
        if($dimensions[6][$i])
        {
        $head_table.='<th scope="col">'.$dimensions[6][$i].'</th>';
        }
    }

    $end_head_table='</tr><thead>';

    $row1="<tr>";

    //$dimensions[1]=str_replace("E","<b>E</b>",$dimensions[1]);
    for($i=0;$i<mb_strlen($dimensions[1]);$i++)
    {
        if(mb_substr($dimensions[1],$i,1)=="E")
        {
            $row1.='<td><b>'.mb_substr($dimensions[1],$i,1).'</b></td>';
        }
        else
        {
        $row1.='<td>'.mb_substr($dimensions[1],$i,1).'</td>';
        }
    }

    $row1.="</tr>";


    $row2="<tr>";

    for($i=0;$i<mb_strlen($dimensions[2]);$i++)
    {
        if(mb_substr($dimensions[2],$i,1)=="E")
        {
            $row2.='<td><b>'.mb_substr($dimensions[2],$i,1).'</b></td>';
        }
        else
        {
        $row2.='<td>'.mb_substr($dimensions[2],$i,1).'</td>';
        }
    }

    $row2.="</tr>";


    $row3="<tr>";

    for($i=0;$i<mb_strlen($dimensions[3]);$i++)
    {
        if(mb_substr($dimensions[3],$i,1)=="E")
        {
            $row3.='<td><b>'.mb_substr($dimensions[3],$i,1).'</b></td>';
        }
        else
        {
        $row3.='<td>'.mb_substr($dimensions[3],$i,1).'</td>';
        }
    }

    $row3.="</tr>";


    $row4="<tr>";

    for($i=0;$i<mb_strlen($dimensions[4]);$i++)
    {
        if(mb_substr($dimensions[4],$i,1)=="E")
        {
            $row4.='<td><b>'.mb_substr($dimensions[4],$i,1).'</b></td>';
        }
        else
        {
        $row4.='<td>'.mb_substr($dimensions[4],$i,1).'</td>';
        }
    }

    $row4.="</tr>";

    $end_table='</table>';


    $itog=$begin.$head_table.$end_head_table.$row1.$row2.$row3.$row4.$end_table;
    return $itog;
}

function dimensions_array($dimensions)
{
    for($i=0;$i<count($dimensions[6]);$i++)
    {
       $array[$i][0]=$dimensions[6][$i];
       $array[$i][1]=mb_substr($dimensions[1],$i,1);
       $array[$i][2]=mb_substr($dimensions[2],$i,1);
       $array[$i][3]=mb_substr($dimensions[3],$i,1);
       $array[$i][4]=mb_substr($dimensions[4],$i,1);
    }

   
    return $array;
}

function emeno_rules($number,$zero_letter,$zero_cons,$zero_vzryv,$zero_where,$zero_zvonkiy,$first_letter,$first_cons,$first_vzryv,$first_where,$first_zvonkiy,$second_letter,$second_cons,$second_vzryv,$second_where,$second_zvonkiy,$third_letter,$third_cons,$third_vzryv,$third_where,$third_zvonkiy,$word_length,$zero_number,$first_number,$second_number,$big_array_1)
{
   switch ($number)  
   {
        
        case "1":
                    //1 Эмено

                    if($first_letter=="a"||$first_letter=="ā")
                    {
                        switch($second_letter)
                        {
                            case "e":$itog="ai";$count_change=3;$pravilo=1;break;
                            case "o":$itog="au";$count_change=3;$pravilo=1;break;
                            case "ai":$itog="ai";$count_change=3;$pravilo=1;break;
                            case "au":$itog="au";$count_change=3;$pravilo=1;break;
                        }
                        
                    
                    }
                    break;
    
        case "2":
                    //2 Эмено
            
                    if($first_letter=="a"||$first_letter=="ā")
                    {
                        switch($second_letter)
                        {
                            case "i":$itog="e";$count_change=3;$pravilo=2;break;
                            case "ī":$itog="e";$count_change=3;$pravilo=2;break;
                            case "u":$itog="o";$count_change=3;$pravilo=2;break;
                            case "ū":$itog="o";$count_change=3;$pravilo=2;break;
                            case "ṛ":$itog="ar";$count_change=3;$pravilo=2;break;
                            case "ṝ":$itog="ar";$count_change=3;$pravilo=2;break;
                            case "ḷ":$itog="al";$count_change=3;$pravilo=2;break;
                            case "ḹ":$itog="al";$count_change=3;$pravilo=2;break;
                        }
            
                    
                    }
                    break;
    
        case "3":
                    //3 Эмено
                    // Тут "дифтонги перед гласной", но ai и au я здесь не учитываю. Если нужно - придется логику их парсинга менять
            
                    if($first_letter=="e")
                    {
                        switch($second_letter)
                        {
                            case "a":$itog="ay";$count_change=1;$pravilo=3;break;
                            case "ā":$itog="ay";$count_change=1;$pravilo=3;break;
                            case "i":$itog="ay";$count_change=1;$pravilo=3;break;
                            case "ī":$itog="ay";$count_change=1;$pravilo=3;break;
                            case "u":$itog="ay";$count_change=1;$pravilo=3;break;
                            case "ū":$itog="ay";$count_change=1;$pravilo=3;break;
                            case "ṛ":$itog="ay";$count_change=1;$pravilo=3;break;
                            case "ṝ":$itog="ay";$count_change=1;$pravilo=3;break;
                            case "ḷ":$itog="ay";$count_change=1;$pravilo=3;break;
                            case "ḹ":$itog="ay";$count_change=1;$pravilo=3;break;
                        }
            
                    
                    }
    
                    if($first_letter=="o")
                    {
                        switch($second_letter)
                        {
                            case "a":$itog="av";$count_change=1;$pravilo=2;break;
                            case "ā":$itog="av";$count_change=1;$pravilo=2;break;
                            case "i":$itog="av";$count_change=1;$pravilo=2;break;
                            case "ī":$itog="av";$count_change=1;$pravilo=2;break;
                            case "u":$itog="av";$count_change=1;$pravilo=2;break;
                            case "ū":$itog="av";$count_change=1;$pravilo=2;break;
                            case "ṛ":$itog="av";$count_change=1;$pravilo=2;break;
                            case "ṝ":$itog="av";$count_change=1;$pravilo=2;break;
                            case "ḷ":$itog="av";$count_change=1;$pravilo=2;break;
                            case "ḹ":$itog="av";$count_change=1;$pravilo=2;break;
                        }
            
                    
                    }

                    break;
            
        case "6":
                     //6 Эмено
    
                    if($first_letter!=$second_letter&&$second_cons=="V")
                    {
                        switch($first_letter)
                        {
                            case "ṛ":$itog="r";$count_change=1;$pravilo=6;break;
                            case "ṝ":$itog="r";$count_change=1;$pravilo=6;break;
                            case "i":$itog="y";$count_change=1;$pravilo=6;break;
                            case "ī":$itog="y";$count_change=1;$pravilo=6;break;
                            case "u":$itog="v";$count_change=1;$pravilo=6;break;
                            case "ū":$itog="v";$count_change=1;$pravilo=6;break;
                        }
                    }
                    break;
            

        case "7":
                    //7 Эмено
            
                    if($first_letter==$second_letter)
                    {
                        switch($first_letter)
                        {
                            case "a":$itog="ā";$count_change=3;$pravilo.=" 7";break;
                            case "i":$itog="ī";$count_change=3;$pravilo.=" 7";break;
                            case "u":$itog="ū";$count_change=3;$pravilo.=" 7";break;
                        }
            
            
                        //echo $itog;
                    }
                    break;
    
    
        case "9":
                
                //9 Эмено  Если правила применяются последовательно, сначала удаляются все гласные на конце, а потом применяются остальные правила
                $i=0;$counter=0;$count_change=0;$number_ishod=$word_length; 

                while(mb_substr($big_array_1,$number_ishod,1)!="V")
                {
                    $number_ishod=$word_length-$i;
                    $counter++;
 
                    $i++;
                }
              
                $change=$number_ishod; // Позиция 1 гласной с конца
                $change++; // Тут либо С либо палка
                if(mb_substr($big_array_1,$change,1)=="|"){$change++;} // Если палка, берем еще
                $change++; // Удаляем всё кроме 1 согласной
                $real_change=$word_length-$change;

               // echo "<BR>CHANGE: ".$real_change." ";
    
                $length_minus_counter=$word_length-$counter+1;

                $length_minus_counter++; // Берем позицию после V
                if(mb_substr($big_array_1,$length_minus_counter,1)=="|"){$length_minus_counter++;} // Если натыкаемся на палку, берем дальше, теперь здесь лежит позиция 1 согласной

                $length_minus_counter++; // Здесь позиция следующей согласной, с которой удаляем
            
                $count_change=$real_change;

                $word_length=strlen($big_array_1);

                if($count_change>0&&$length_minus_counter!=$word_length)
                {
                    $result[3]=$length_minus_counter;
                    $itog="Ø";
                    $pravilo=9;
                }

                break;
         
        case "10":
              
                if($zero_cons=="V"&&$first_letter=="ch")
                {
                    $itog="cch";$count_change=2;$pravilo=10;
                }
                break;

        case "11":
        
            //11 Эмено
           
            if($first_letter=="m"&&($second_letter=="v"||$second_letter=="m"))
            {
                $itog="n";$count_change=1;$pravilo=11;
            }
            break;
  
        case "12":
            //12 Эмено
           
            if($first_letter=="s"&&($zero_vzryv=="T"&&$second_vzryv=="T"))
            {
                $itog="Ø";$count_change=1;$pravilo=12;
            }
            break;
          
        case "15":
          
            if($first_letter=="s"&&$second_letter=="dh")
            {
                $itog="Ø";$count_change=1;$pravilo=15;
            }
            break;
        case "28":  
            
            //28 Эмено
    
            if($array[$second_number][1]=="C"&&($array[$second_number][2]=="T"||$array[$second_number][2]=="S"))
            {
                if($array[$find_cc][0]=="c")
                {
                    $itog="k";$count_change=1;
                    $pravilo=28;
                
                }
            }
            break;
  
    
        case "30":  ///Тут есть необработанное исключение - обратить внимание! Правило не привеняется к о.н.в. dadh и к дезидеративу от dhaa - класть
            if(($second_letter=="t"||$second_letter=="th")&&($first_letter=="gh"||$first_letter=="jh"||$first_letter=="ḍh"||$first_letter=="dh"||$first_letter=="bh"))
            {
                $itog=$first_letter."|dh";$count_change=3;$pravilo=30;
            }
            break;
        case "31":
                    //31 Эмено

               // echo "1ZV: $first_letter : ".$first_zvonkiy." 2ZV: $second_letter : $second_zvonkiy";

                    if($first_zvonkiy=="V"&&$first_vzryv=="T"&&(($second_zvonkiy=="D")||$second_number==($word_length-1)))
                    {
                    
                        switch($first_letter)
                        {
                            case "g":$itog="k";$count_change=1;break;
                            case "j":$itog="c";$count_change=1;break;
                            case "ḍ":$itog="ṭ";$count_change=1;break;
                            case "d":$itog="t";$count_change=1;break;
                            case "b":$itog="p";$count_change=1;break;

                            case "gh":$itog="kh";$count_change=1;break;
                            case "jh":$itog="ch";$count_change=1;break;
                            case "ḍh":$itog="ṭh";$count_change=1;break;
                            case "dh":$itog="th";$count_change=1;break;
                            case "bh":$itog="ph";$count_change=1;break;
                        }

                        $pravilo=31;

                    }
                    break;


        case "32":            
                    
                    //32 Эмено
                
                    if($first_zvonkiy=="D"&&$first_vzryv=="T"&&($second_zvonkiy=="V"&&$second_vzryv=="T"))
                    {
                    
                        switch($first_letter)
                        {
                            case "k":$itog="g";$count_change=1;$pravilo=32;break;
                            case "c":$itog="j";$count_change=1;$pravilo=32;break;
                            case "ṭ":$itog="ḍ";$count_change=1;$pravilo=32;break;
                            case "t":$itog="d";$count_change=1;$pravilo=32;break;
                            case "p":$itog="b";$count_change=1;$pravilo=32;break;

                            case "kh":$itog="gh";$count_change=1;$pravilo=32;break;
                            case "ch":$itog="jh";$count_change=1;$pravilo=32;break;
                            case "ṭh":$itog="ḍh";$count_change=1;$pravilo=32;break;
                            case "th":$itog="dh";$count_change=1;$pravilo=32;break;
                            case "ph":$itog="bh";$count_change=1;$pravilo=32;break;
                        }

                    }
                    break;

        
        case "33":
                    //33 Эмено
 
     
                    if((($second_vzryv=="T"||$second_vzryv=="S")||$second_number==($word_length-1)))
                    {
                    
                        switch($first_letter)
                        {
                            case "kh":$itog="k";$count_change=1;$pravilo=33;break;
                            case "ch":$itog="c";$count_change=1;$pravilo=33;break;
                            case "ṭh":$itog="ṭ";$count_change=1;$pravilo=33;break;
                            case "th":$itog="t";$count_change=1;$pravilo=33;break;
                            case "ph":$itog="p";$count_change=1;$pravilo=33;break;

                            case "gh":$itog="g";$count_change=1;$pravilo=33;break;
                            case "jh":$itog="j";$count_change=1;$pravilo=33;break;
                            case "ḍh":$itog="ḍ";$count_change=1;$pravilo=33;break;
                            case "dh":$itog="d";$count_change=1;$pravilo=33;break;
                            case "bh":$itog="b";$count_change=1;$pravilo=33;break;
                        }

                    }
                    break;

        case "35":

                    //35 Эмено
 
                    if(($first_vzryv=="N"&&$second_vzryv=="S"))
                    {

                        $itog="ṃ";$count_change=1;$pravilo.=" 35";

                    }
                    break;
    

        case "36":
                    //36 Эмено  Анусвара или висарга между гласной и s не мешает замене за исключением pums,hims и еще "некоторых" - это не реализовано

                    if($second_letter=="s"&&$third_letter!="r"&&(($first_cons=="V"&&$first_letter!="a"&&$first_letter!="ā")||$first_letter=="k"||$first_letter=="r"||$first_letter=="l"))
                    {

                        $itog=$first_letter."|ṣ";$count_change=3;$pravilo=36;

                    }
                    break;
        case "38":
                    //38 Эмено

                    if(($first_where=="R"&&$second_where=="D"&&($second_vzryv=="T"||$second_vzryv=="N"))&&!($first_letter=="r"&&$second_vzryv=="T"))
                    {
                        switch($second_letter)
                        {
                            case "t":$itog=$first_letter."|ṭ";$count_change=3;break;
                            case "th":$itog=$first_letter."|ṭh";$count_change=3;break;
                            case "d":$itog=$first_letter."|ḍ";$count_change=3;break;
                            case "dh":$itog=$first_letter."|ḍh";$count_change=3;break;

                        }

                        $pravilo=38;
                    }
                    break;

        case "39":
                        //39 Эмено
 
                    if($first_vzryv=="N"&&$second_vzryv=="T")
                    {

                        switch($second_letter)
                        {
                            
                            case "k":$itog="ṅ";$count_change=1;$pravilo=39;break;
                            case "c":$itog="ñ";$count_change=1;$pravilo=39;break;
                            case "ṭ":$itog="ṇ";$count_change=1;$pravilo=39;break;
                            case "t":$itog="n";$count_change=1;$pravilo=39;break;
                            case "p":$itog="m";$count_change=1;$pravilo=39;break;
                            
                            case "kh":$itog="ṅ";$count_change=1;$pravilo=39;break;
                            case "ch":$itog="ñ";$count_change=1;$pravilo=39;break;
                            case "ṭh":$itog="ṇ";$count_change=1;$pravilo=39;break;
                            case "th":$itog="n";$count_change=1;$pravilo=39;break;
                            case "ph":$itog="m";$count_change=1;$pravilo=39;break;

                            case "g":$itog="ṅ";$count_change=1;$pravilo=39;break;
                            case "j":$itog="ñ";$count_change=1;$pravilo=39;break;
                            case "ḍ":$itog="ṇ";$count_change=1;$pravilo=39;break;
                            case "d":$itog="n";$count_change=1;$pravilo=39;break;
                            case "b":$itog="m";$count_change=1;$pravilo=39;break;

                            case "gh":$itog="ṅ";$count_change=1;$pravilo=39;break;
                            case "jh":$itog="ñ";$count_change=1;$pravilo=39;break;
                            case "ḍh":$itog="ṇ";$count_change=1;$pravilo=39;break;
                            case "dh":$itog="n";$count_change=1;$pravilo=39;break;
                            case "bh":$itog="m";$count_change=1;$pravilo=39;break;
                        }


                    }
                    break;

        case "40":
                    //40 Эмено
 
                    if($second_letter=="n"&&$first_vzryv=="T"&&$first_where="P")
                    {
            
                        $itog=$first_letter."ñ";$count_change=3;$pravilo=40;
            
                    }

    }

    $result[0]=$itog;
    $result[1]=$count_change;
    $result[2]=$pravilo;
    if(!$result[3]){$result[3]=$first_number;}
    $result[4]="refresh";

    return $result;
}

function sandhi($big_array,$array,$new_word,$debug)
{
    $array_rules=array(1,2,3,6,7,9,10,11,12,15,28,30,31,32,33,35,36,38,39,40);
    
    $result[3]=count($array_rules);

    /////Согласные//////

    if(!$result[0]){$result[0]=$new_word;}
 
    $big_array=dimensions($result[0],"something","smth",0,0,1);
    $array=dimensions_array($big_array);
 
    if($debug){echo $big_array[1]."<BR>";}
 
    $offset = 0;
    $allpos_cons = array();
    while (($pos = mb_strpos($big_array[1], '|', $offset))!== false) {
         $offset   = $pos + 1;
         $allpos_cons[] = $pos-1;
    }

     if($debug){echo "Все вхождения стыков |: "; print_r($allpos_cons);}
 
    $k=1;
    for($i=count($allpos_cons)-1;$i>=0;$i--)
    {

         if($debug){echo "<BR>Разбор сандхи №$k с конца. Исходная строчка: <b>".$result[0]."</b><BR>";}
         
         $refresh=0;    
 
         $word_length=count($array);
 
         $itog="";$count_change=0; 
 
         $position_number=$allpos_cons[$i];
 

        //if($debug){echo "<BR>Zero letter $zero_letter First letter $first_letter Second letter $second_letter<BR>";}

        
        for($j=0;$j<count($array_rules);$j++)    //Пробуем применить последовательно все правила сандхи из $array_rules
        {
           
            
            $first_letter=$array[$position_number][0];
            $first_cons=$array[$position_number][1];
            $first_vzryv=$array[$position_number][2];
            $first_where=$array[$position_number][3];
            $first_zvonkiy=$array[$position_number][4];
       
            $second_number=$position_number+1;
            $second_letter=$array[$second_number][0];

            if($second_letter=="|")
            {
                $second_number=$second_number+1;
                $second_letter=$array[$second_number][0];
            }

            $second_cons=$array[$second_number][1];
            $second_vzryv=$array[$second_number][2];
            $second_where=$array[$second_number][3];
            $second_zvonkiy=$array[$second_number][4];

            $third_number=$second_number+1;
            $third_letter=$array[$third_number][0];

            if($third_letter=="|")
            {
                $second_number=$third_number+1;
                $third_letter=$array[$third_number][0];
            }

            $third_cons=$array[$third_number][1];
            $third_vzryv=$array[$third_number][2];
            $third_where=$array[$third_number][3];
            $third_zvonkiy=$array[$third_number][4];
           
            ////Здесь потенциально могут быть баги - надо делать проверку на | и по ней смотреть предыдущий элемент
    
            
            $zero_number=$position_number-1;
            $zero_letter=$array[$zero_number][0];

            if($zero_letter=="|")
            {
                $zero_number=$zero_number-1;
                $zero_letter=$array[$zero_number][0];
            }

            $zero_cons=$array[$zero_number][1];
            $zero_vzryv=$array[$zero_number][2];
            $zero_where=$array[$zero_number][3];
            $zero_zvonkiy=$array[$zero_number][4];
    
            ////////////////////////////////////////////////////////////////////////////////

           
            
            $emeno=emeno_rules($array_rules[$j],$zero_letter,$zero_cons,$zero_vzryv,$zero_where,$zero_zvonkiy,$first_letter,$first_cons,$first_vzryv,$first_where,$first_zvonkiy,$second_letter,$second_cons,$second_vzryv,$second_where,$second_zvonkiy,$third_letter,$third_cons,$third_vzryv,$third_where,$third_zvonkiy,$word_length,$zero_number,$position_number,$second_number,$big_array[1]);
            
            if($emeno[2])
            {

            if($debug){echo "<BR>На входе: <b>".$result[0]."</b><BR>";}
            $result[0]=sandhi_reconstruct($emeno[3],$result[0],$emeno[0],$emeno[1],count($array),$debug,$big_array);
            $result[0]=str_replace("Ø","",$result[0]);

    
            $result[1]=$result[1]." ".$emeno[2];
        
            
                if($debug){echo "<BR>На выходе: <b>".$result[0]."</b><BR>";}
                if($debug){echo "<BR>Применили правила: <b>".$result[1]."</b><BR><BR>";}
           
    
            $big_array=dimensions($result[0],"something","smth",0,0,1);
            $array=dimensions_array($big_array);


            }

            if($emeno[4])
            {

            }
            
        }    

        $k++;
    }

    $result[0]=str_replace("|","",$result[0]);
    return $result;

}


function sandhi_reconstruct($find_cc,$enter_text,$itog,$count_change,$count,$debug,$big_array)
{
    //print_r($big_array);
    if($debug){echo "Номер вхождения: ".$find_cc." На что меняется: $itog  Сколько символов поменяется: $count_change<BR>";}

    if($itog)
    {

       // if($debug){echo "|";}

        for($i=0;$i<$find_cc;$i++)
        {
            $text1.=$big_array[6][$i];
        }
        
        if($itog=="Ø")
        {
            $text2="";
        }
        else
        {
            $text2=$itog;
        }
    

        for($i=$find_cc+$count_change;$i<$count;$i++)
        {
            $text3.=$big_array[6][$i];
        }
       
        // if($debug){echo $text1."|".$text2."|".$text3."|";}

        $text=$text1.$text2.$text3;
        $result=$text;
    }
    else
    {
            $result=$enter_text;
    }

    return $result;
}



?>