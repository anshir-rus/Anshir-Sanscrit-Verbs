<?php

function VR($id)
{
    return search_in_db($id,'verbs',1);
}

function FuS()
{
    //FuS: S(2)-sya-
    return search_in_db(3,'suffixes',2);
}

function Fu($lico,$chislo,$pada)
{
    $result=Endings1F($lico,$chislo,$pada,"-","Fu");
    return $result;
}

function Co($lico,$chislo,$pada,$lastname,$debug)
{
   // → Co: (прист.)-а-FuS(?)-2F
    $last_letter=mb_substr($lastname,-1,1);

   if($debug)
   {
    echo "Добавляем окончания Co<BR>";
    echo "Перед окончанием морфема: ".$lastname."<BR>";
    echo "Последний звук: ".$last_letter."<BR>";
    if($lico==1&&$chislo==1&&$pada=="P")
    {
        echo "1 sg. P. => Специальное окончание, если последняя морфема оканчивается на гласную<BR><BR>";
    }
   }

    $result=Endings2F($lico,$chislo,$pada,"-","Co",$lastname,0,$debug);

    return $result;
}

function G($ryad)
{
   // echo "RYAD:".$ryad."<BR>";

    if($ryad=="A2")
    {
        $result[]=search_in_db(96,'suffixes',2);
        $result[]=search_in_db(98,'suffixes',2);
        $result[]=search_in_db(99,'suffixes',2);
        $result[]=search_in_db(100,'suffixes',2);
        $result[]=search_in_db(101,'suffixes',2);
    }
    elseif($ryad=="L")
    {
        $result[]=search_in_db(97,'suffixes',2);
        $result[]=search_in_db(98,'suffixes',2);
        $result[]=search_in_db(99,'suffixes',2);
        $result[]=search_in_db(100,'suffixes',2);
        $result[]=search_in_db(101,'suffixes',2);
    }
    else
    {
        $result[]=search_in_db(96,'suffixes',2);
        $result[]=search_in_db(97,'suffixes',2);
        $result[]=search_in_db(98,'suffixes',2);
        $result[]=search_in_db(99,'suffixes',2);
        $result[]=search_in_db(100,'suffixes',2);
        $result[]=search_in_db(101,'suffixes',2);
    }



    return $result;
}

function PaFuAS($pada,$debug) //Перед (1)-n̥t- «а» в суффиксах FuS на письме не отображается: (2)-sy/a/-.
{
    //FuS(1)-n̥t 
    //FuS(1)-māna

    //Перед (1)-n̥t- «а» в суффиксах FuS на письме не отображается: (2)-sy/a/-.

    if($pada=="P")
    {
        return search_in_db(23,'suffixes',2);
    }

    if($pada=="A")
    {
        return search_in_db(24,'suffixes',2);
    }

    

}

function CaS($verb,$not_chered)
{
    //$verb=search_in_db($id,'verbs',1);
    //print_r($verb);

    $name=$verb[0];
    $omonim=$verb[1];
    $type=$verb[2];
    $element=$verb[11];
    $ryad=$verb[4];

    $dimensions=dimensions($name, $element, $name, 1, 0, 0, 0);
    $dimensions_array=dimensions_array($dimensions);

    $c_string=$dimensions[1];
    $c_string=str_replace("EE","E",$c_string);
    $e_position=mb_strpos($c_string,"E");
    $ecc=mb_substr($c_string,$e_position,3);
    
    //echo "<BR>NAME: $name STR:".$ecc; echo "<BR><BR>";
    //$nul = ["Ø̄", "Ø"];

    //ПОМЕНЯТЬ ЕСС в нек-х случаях на EF!

    switch($ryad)
    {
        case "A1":
            if($name=="rac"||$name=="prath"||$name=="rah"||$name=="vadh"||$name=="vyath"||$name=="sthag")
            {
                $suffix[]=search_in_db(26,'suffixes',2);
                break;
            }

            if($name=="ghaṭ"||$name=="trap"||$name=="lag"||$name=="viØdh"||$name=="suØp")
            {
                $suffix[]=search_in_db(26,'suffixes',2);
            }

            if($ecc=="ECC")
            {
                $suffix[]=search_in_db(26,'suffixes',2);
            }

            if($ecc=="EC")
            {
                $suffix[]=search_in_db(27,'suffixes',2);
            }
            break;

        case "A2":
            if($name=="chØ̄"||$name=="pØ̄"||$name=="pyā"||$name=="uØ̄"||$name=="vīØ̄"||($name=="śØ̄"&&$omonim==1)||$name=="sØ̄")
            {
                $suffix[]=search_in_db(30,'suffixes',2);
                break;
            }

            if($name=="śīØ̄")
            {
                $suffix[]=search_in_db(30,'suffixes',2);
            }
            elseif($name=="pā")
            {
                $suffix[]=search_in_db(31,'suffixes',2);
            }
            elseif($name=="sphØ̄")
            {
                $suffix[]=search_in_db(32,'suffixes',2);
            }
            elseif($ecc=="EC")
            {
                $suffix[]=search_in_db(27,'suffixes',2);
            }
            else
            {
                $suffix[]=search_in_db(34,'suffixes',2);
            }
            break;
        case "I0":

            if($name=="il")
            {
                $suffix[]=search_in_db(25,'suffixes',2);
                break;
            }

            if(($name=="iṣ"&&$omonim==2)||($name=="cit"&&$omonim==2)||($name=="sīv"&&$omonim==2)||($name=="srīv"&&$omonim==2))
            {
                $suffix[]=search_in_db(25,'suffixes',2);
            }

            if($name=="rī"||$name=="vlī"||$name=="hrī")
            {
                $suffix[]=search_in_db(33,'suffixes',2);
                break;
            }

            if(($name=="kṣi"&&$omonim==1))
            {
                $suffix[]=search_in_db(33,'suffixes',2);
            }

            if($name=="īḍ")
            {
                $suffix[]=search_in_db(27,'suffixes',2);
                break;
            }

            if(($name=="ji"&&$omonim==1)||$name=="krī"||$name=="lī")
            {
                $suffix[]=search_in_db(35,'suffixes',2);
                break;
            }

            if(($name=="kṣi"&&$omonim==2)||($name=="ci"&&$omonim==1)||$name=="smi"||$name=="vī")
            {
                $suffix[]=search_in_db(35,'suffixes',2);
            }

            if($type=="2"||$type=="4"||$ecc=="ECC"||$ecc=="EC")
            {
                $suffix[]=search_in_db(26,'suffixes',2);
            }

            if($ecc=="E")
            {
                $suffix[]=search_in_db(27,'suffixes',2);
            }
            break;
        case "I1":

            if($name=="il")
            {
                $suffix[]=search_in_db(25,'suffixes',2);
                break;
            }

            if(($name=="iṣ"&&$omonim==2)||($name=="cit"&&$omonim==2)||($name=="sīv"&&$omonim==2)||($name=="srīv"&&$omonim==2))
            {
                $suffix[]=search_in_db(25,'suffixes',2);
            }

            if($name=="rī"||$name=="vlī"||$name=="hrī")
            {
                $suffix[]=search_in_db(33,'suffixes',2);
                break;
            }

            if(($name=="kṣi"&&$omonim==1))
            {
                $suffix[]=search_in_db(33,'suffixes',2);
            }

            if($name=="īḍ")
            {
                $suffix[]=search_in_db(27,'suffixes',2);
                break;
            }

            if(($name=="ji"&&$omonim==1)||$name=="krī"||$name=="lī")
            {
                $suffix[]=search_in_db(35,'suffixes',2);
                break;
            }

            if(($name=="kṣi"&&$omonim==2)||($name=="ci"&&$omonim==1)||$name=="smi"||$name=="vī")
            {
                $suffix[]=search_in_db(35,'suffixes',2);
            }

            if($type=="2"||$type=="4"||$ecc=="ECC"||$ecc=="EC")
            {
                $suffix[]=search_in_db(26,'suffixes',2);
            }

            if($ecc=="E")
            {
                $suffix[]=search_in_db(27,'suffixes',2);
            }
            break;

        case "I2":

                if($name=="il")
                {
                    $suffix[]=search_in_db(25,'suffixes',2);
                    break;
                }
    
                if(($name=="iṣ"&&$omonim==2)||($name=="cit"&&$omonim==2)||($name=="sīv"&&$omonim==2)||($name=="srīv"&&$omonim==2))
                {
                    $suffix[]=search_in_db(25,'suffixes',2);
                }
    
                if($name=="rī"||$name=="vlī"||$name=="hrī")
                {
                    $suffix[]=search_in_db(33,'suffixes',2);
                    break;
                }
    
                if(($name=="kṣi"&&$omonim==1))
                {
                    $suffix[]=search_in_db(33,'suffixes',2);
                }
    
                if($name=="īḍ")
                {
                    $suffix[]=search_in_db(27,'suffixes',2);
                    break;
                }
    
                if(($name=="ji"&&$omonim==1)||$name=="krī"||$name=="lī")
                {
                    $suffix[]=search_in_db(35,'suffixes',2);
                    break;
                }
    
                if(($name=="kṣi"&&$omonim==2)||($name=="ci"&&$omonim==1)||$name=="smi"||$name=="vī")
                {
                    $suffix[]=search_in_db(35,'suffixes',2);
                }
    
                if($type=="2"||$type=="4"||$ecc=="ECC"||$ecc=="EC")
                {
                    $suffix[]=search_in_db(26,'suffixes',2);
                }
    
                if($ecc=="E")
                {
                    $suffix[]=search_in_db(27,'suffixes',2);
                }
                break;
        case "U0":
            
            if($name=="duṣ"||($name=="ūh"&&$omonim==2))
                {
                    $suffix[]=search_in_db(25,'suffixes',2);
                    break;
                }
    
                if($name=="puṭ"||$name=="rūṣ")
                {
                    $suffix[]=search_in_db(25,'suffixes',2);
                }
    
                if($name=="pū")
                {
                    $suffix[]=search_in_db(26,'suffixes',2);
                }

                if($name=="ruh")
                {
                    $suffix[]=search_in_db(33,'suffixes',2);
                    $new_root_name="ro";
                    $new_root_element="o";
                }
    
                if($type=="2"||$type=="4"||$ecc=="EC"||$ecc=="ECC")
                {
                    
                    $suffix[]=search_in_db(26,'suffixes',2);
                }
    
                if($ecc=="E")
                {
                    $suffix[]=search_in_db(27,'suffixes',2);
                }
                
                break;

        case "U1":
                if($name=="duṣ"||($name=="ūh"&&$omonim==2))
                {
                    $suffix[]=search_in_db(25,'suffixes',2);
                    break;
                }
    
                if($name=="puṭ"||$name=="rūṣ")
                {
                    $suffix[]=search_in_db(25,'suffixes',2);
                }
    
                if($name=="pū")
                {
                    $suffix[]=search_in_db(26,'suffixes',2);
                }

                if($name=="ruh")
                {
                    $suffix[]=search_in_db(33,'suffixes',2);
                    $new_root_name="ro";
                    $new_root_element="o";
                }
    
                if($type=="2"||$type=="4"||$ecc=="EC"||$ecc=="ECC")
                {
                    $suffix[]=search_in_db(26,'suffixes',2);
                }
    
                if($ecc=="E")
                {
                    $suffix[]=search_in_db(27,'suffixes',2);
                }
                
                break;

        case "U2":
            
            //echo "ECC:".$ecc;

            if($name=="duṣ"||($name=="ūh"&&$omonim==2))
            {
                $suffix[]=search_in_db(25,'suffixes',2);
                break;
            }

            if($name=="puṭ"||$name=="rūṣ")
            {
                $suffix[]=search_in_db(25,'suffixes',2);
            }

            if($name=="pū")
            {
                $suffix[]=search_in_db(26,'suffixes',2);
            }

            if($name=="ruh")
            {
                $suffix[]=search_in_db(33,'suffixes',2);
                $new_root_name="ro";
                $new_root_element="o";
            }

            if($name=="dhū")
            {
                $suffix[]=search_in_db(27,'suffixes',2);
                //$new_root_name="dhūn";
                //$new_root_element="ū";
            }

            if($type=="2"||$type=="4"||$ecc=="EC"||$ecc=="ECC")
            {
                
                $suffix[]=search_in_db(26,'suffixes',2);
            }

            if($ecc=="E")
            {
                $suffix[]=search_in_db(27,'suffixes',2);
            }
            
            break;
        case "R0":
            if($name=="kṣar")
            {
                $suffix[]=search_in_db(27,'suffixes',2);
                break;
            }

            if($name=="ṛ")
            {
                $suffix[]=search_in_db(33,'suffixes',2);
                break;
            }

        

            if(($name=="gṝ"&&$omonim==2)||$name=="pṝ"||$name=="sphṝ")
            {
                $suffix[]=search_in_db(25,'suffixes',2);
            }
            elseif($type=="2"||$type=="4"||$ecc=="EC"||$ecc=="ECC"||$name=="smṛ"||$name=="jṝ")
            {
                $suffix[]=search_in_db(26,'suffixes',2);
            }

            if($ecc=="E"||$name=="tvar"||$name=="svar")
            {
                $suffix[]=search_in_db(27,'suffixes',2);
            }

            

            break;

        case "R1":
            if($name=="kṣar")
            {
                $suffix[]=search_in_db(27,'suffixes',2);
                break;
            }

            if($name=="ṛ")
            {
                $suffix[]=search_in_db(33,'suffixes',2);
                break;
            }

            if(($name=="gṝ"&&$omonim==2)||$name=="pṝ"||$name=="sphṝ")
            {
                $suffix[]=search_in_db(25,'suffixes',2);
            }
            elseif($type=="2"||$type=="4"||$ecc=="EC"||$ecc=="ECC"||$name=="smṛ"||$name=="jṝ")
            {
                $suffix[]=search_in_db(26,'suffixes',2);
            }

            if($ecc=="E"||$name=="tvar"||$name=="svar")
            {
                $suffix[]=search_in_db(27,'suffixes',2);
            }

            break;

        case "R2":
            if($name=="kṣar")
            {
                $suffix[]=search_in_db(27,'suffixes',2);
                break;
            }

            if($name=="ṛ")
            {
                $suffix[]=search_in_db(33,'suffixes',2);
                break;
            }

            if(($name=="gṝ"&&$omonim==2)||$name=="pṝ"||$name=="sphṝ")
            {
                $suffix[]=search_in_db(25,'suffixes',2);
            }
            elseif($type=="2"||$type=="4"||$ecc=="EC"||$ecc=="ECC"||$name=="smṛ"||$name=="jṝ")
            {
                $suffix[]=search_in_db(26,'suffixes',2);
            }

            if($ecc=="E"||$name=="tvar"||$name=="svar")
            {
                $suffix[]=search_in_db(27,'suffixes',2);
            }

            break;

        case "L":
            if($name=="jval"||$name=="dal")
            {
                $suffix[]=search_in_db(26,'suffixes',2);
                break;
            }

            if($ecc=="EC"||$ecc=="ECC"||$name=="cal"||$name=="lal"||$name=="skhal"||$name=="hval")
            {
                $suffix[]=search_in_db(26,'suffixes',2);
            }

            if($ecc=="E")
            {
                $suffix[]=search_in_db(27,'suffixes',2);
            }
            break;

        case "M0":
            if($name=="dm̥̄"||$name=="śrm̥̄"||($name=="śm̥̄"&&$omonim==1)||$name=="gm̥"||$name=="drm̥"||$name=="nm̥"||$name=="rm̥"||$name=="tm̥̄")
            {
                $suffix[]=search_in_db(26,'suffixes',2);
                break;
            }

            if($name=="kram"||$name=="bhrm̥̄"||$name=="vm̥̄")
            {
                $suffix[]=search_in_db(26,'suffixes',2);
            }

            if($ecc=="E")
            {
                $suffix[]=search_in_db(27,'suffixes',2);
            }
            break;

        case "M1":
                if($name=="dm̥̄"||$name=="śrm̥̄"||($name=="śm̥̄"&&$omonim==1)||$name=="gm̥"||$name=="drm̥"||$name=="nm̥"||$name=="rm̥"||$name=="tm̥̄")
                {
                    $suffix[]=search_in_db(26,'suffixes',2);
                    break;
                }
    
                if($name=="kram"||$name=="bhrm̥̄"||$name=="vm̥̄")
                {
                    $suffix[]=search_in_db(26,'suffixes',2);
                }
    
                if($ecc=="E")
                {
                    $suffix[]=search_in_db(27,'suffixes',2);
                }
                break;

        case "M2":
                    if($name=="dm̥̄"||$name=="śrm̥̄"||($name=="śm̥̄"&&$omonim==1)||$name=="gm̥"||$name=="drm̥"||$name=="nm̥"||$name=="rm̥"||$name=="tm̥̄")
                    {
                        $suffix[]=search_in_db(26,'suffixes',2);
                        break;
                    }
        
                    if($name=="kram"||$name=="bhrm̥̄"||$name=="vm̥̄")
                    {
                        $suffix[]=search_in_db(26,'suffixes',2);
                    }
        
                    if($ecc=="E")
                    {
                        $suffix[]=search_in_db(27,'suffixes',2);
                    }
                    break;           
        case "N0":
            if($name=="pn̥"||$name=="jn̥̄"||$name=="dhvn̥̄")
            {
                $suffix[]=search_in_db(26,'suffixes',2);
                break;
            }

            if($name=="grn̥th"||$name=="chn̥d"||$name=="mn̥th"||$name=="mn̥d")
            {
                $suffix[]=search_in_db(25,'suffixes',2);
            }

            if($type=="2"||$type=="4"||$ecc=="EC"||$ecc=="ECC")
            {
                $suffix[]=search_in_db(26,'suffixes',2);
            }

            if($ecc=="E"||$name=="pan"||$name=="phaṇ"||$name=="raṇ")
            {
                $suffix[]=search_in_db(27,'suffixes',2);
            }
            break;

        case "N1":
                if($name=="pn̥"||$name=="jn̥̄"||$name=="dhvn̥̄")
                {
                    $suffix[]=search_in_db(26,'suffixes',2);
                    break;
                }
    
                if($name=="grn̥th"||$name=="chn̥d"||$name=="mn̥th"||$name=="mn̥d")
                {
                    $suffix[]=search_in_db(25,'suffixes',2);
                }
    
                if($type=="2"||$type=="4"||$ecc=="EC"||$ecc=="ECC")
                {
                    $suffix[]=search_in_db(26,'suffixes',2);
                }
    
                if($ecc=="E"||$name=="pan"||$name=="phaṇ"||$name=="raṇ")
                {
                    $suffix[]=search_in_db(27,'suffixes',2);
                }
                break;

        case "N2":
                    if($name=="pn̥"||$name=="jn̥̄"||$name=="dhvn̥̄")
                    {
                        $suffix[]=search_in_db(26,'suffixes',2);
                        break;
                    }
        
                    if($name=="grn̥th"||$name=="chn̥d"||$name=="mn̥th"||$name=="mn̥d")
                    {
                        $suffix[]=search_in_db(25,'suffixes',2);
                    }
        
                    if($type=="2"||$type=="4"||$ecc=="EC"||$ecc=="ECC")
                    {
                        $suffix[]=search_in_db(26,'suffixes',2);
                    }
        
                    if($ecc=="E"||$name=="pan"||$name=="phaṇ"||$name=="raṇ")
                    {
                        $suffix[]=search_in_db(27,'suffixes',2);
                    }
                    break;
    }

          

    if($not_chered==1)
    {
        for($i=0;$i<count($suffix);$i++)
        {
            $suffix[$i][7]=0;
        }
    }

    //$suffix=array_filter(array_unique($suffix));

    //echo "<BR><BR><BR>SUFFIX2:";
    //print_r($suffix);
    //echo "<BR><BR><BR>";

    $result[0]=$suffix;
    $result[1]=$new_root_name;
    $result[2]=$new_root_element;

    return $result;

}

function DS($verb,$not_after_root,$new_root_name,$new_root_element,$pada,$debug)
{
    

    if($new_root_name!=""&&$new_root_element!="")
    {
        $verb[0]=$new_root_name;
        $verb[3]=$new_root_element;
    }
 
    if($not_after_root==1)
    {
        $ds=get_desiderative($verb[0],$verb[1],$verb[2],$verb[3],$verb[4],$verb[6],$not_after_root,$pada,$debug)[0];
    
        $ds_massive=explode(",",$ds);
        $ds_massive=array_filter(array_unique($ds_massive));
 
        $result[0]=$ds_massive;  
        $result[1]=search_in_db(28,'suffixes',2);
    }
    else
    {
        $ds=get_desiderative($verb[0],$verb[1],$verb[2],$verb[3],$verb[4],$verb[6],$not_after_root,$pada,$debug)[0];
    
        $ds_massive=explode(",",$ds);
        $ds_massive=array_filter(array_unique($ds_massive));
        
        $result[0]=$ds_massive; 
        $result[1]=search_in_db(29,'suffixes',2);
    }
    
    return $result;
 
}

function IS($verb,$debug)
{
    $name=$verb[0];
    $omonim=$verb[1];
    $type=$verb[2];
    $element=$verb[11];
    $ryad=$verb[4];
    
    $is=get_intensive($name,$omonim,$type,$element,$ryad,$debug);
    
    $is_massive=explode(",",$is[0]);

    $result=$is_massive;  
    //print_r($result);
    return $result;
    
}

function PkS($verb,$pada,$debug)
{
    //В открытых корнях ряда А2 может происходить трансформация [А2↦I]. Все корни II типа, а также √stīø̄ способны образовывать как форму с трансформацией, так и без нее. 
    //Оставшиеся корни I типа проходят трансформацию, кроме √jīø̄, √mø̄ 5, √uø̄, √vīø̄.

    $name=$verb[0];
    $omonim=$verb[1];
    $type=$verb[2];
    $element=$verb[11];
    $ryad=$verb[4];

    $dimensions=dimensions($name, $element, $name, 1, 0, 0, 0);
    $dimensions_array=dimensions_array($dimensions);

    $c_string=str_replace("|","",$dimensions[1]);

    $c_string=str_replace("EE","E",$c_string);
    $e_position=mb_strpos($c_string,"E");

    $p=mb_substr($c_string,0,$e_position);
    $ec=mb_substr($c_string,$e_position,2);

    if($ec=="E")
    {
        $is_open_mool=1;
    }
    else
    {
        $is_open_mool=0;
    }

    if($pada=="P")
    {
        if(($is_open_mool&&$ryad=="A2")||($type==2)||($name=="stīØ̄"))
        {
            $result[]=search_in_db(93,'suffixes',2);
            $result[]=search_in_db(94,'suffixes',2);
        }
        elseif($name=="jīØ̄"||($name=="mØ̄"&&$omonim==5)||$name=="uØ̄"||$name=="vīØ̄")
        {
            $result[]=search_in_db(93,'suffixes',2);
        }
        else
        {
            $result[]=search_in_db(94,'suffixes',2);
        }
    }

    if($pada=="A")
    {
        $result[]=search_in_db(95,'suffixes',2);
    }

    return $result;

}

function Pk($lico,$chislo,$pada,$tematic,$lemma,$lastname,$special,$debug)
{
      //echo "L: $lico,CH: $chislo,P: $pada,TEM: $tematic,LEM: $lemma,LN: $lastname, SP: $special, Debug $debug<BR>";  

      $last_letter=mb_substr($lastname,-1,1);

      $result['endings']=Endings2F($lico,$chislo,$pada,$tematic,$lemma,$lastname,$special,$debug);

   
   
       return $result;
   
}

function AoS($steam,$steam_name,$class,$name,$omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,$debug)
{
    //echo "$steam,$class,$name,$omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada<BR><BR>";

    //echo "STEAM_NAME:";

    $dimensions=dimensions($name, $verb_change, $name, 1, 0, 0, 0);
    $dimensions_array=dimensions_array($dimensions);

    $c_string=str_replace("|","",$dimensions[1]);

    $c_string=str_replace("EE","E",$c_string);


    $e_position=mb_strpos($c_string,"E");
    $p=mb_substr($c_string,0,$e_position);

    $p=str_replace("#","",$p);

    $f=mb_substr($c_string,$e_position+1,mb_strlen($c_string)-mb_strlen($p)-mb_strlen($verb_change));

    //echo "STEAM: $steam<BR>";

    if($steam=="DS")
    {
        $class=5;
        $class_massive[0]=$class;
    }
    elseif($steam=="IS")
    {
        $class_massive[0]=1;
        $class_massive[1]=4;
    }
    elseif($steam=="CaS"&&$steam_name=="pay")
    {
        $class=3;
        $class_massive[0]=$class;
    }
    elseif($steam=="CaS"&&$steam_name=="ay")
    {
        $class=5;
        $class_massive[0]=$class;
    }
    else
    {

        if(mb_strpos($class,",")!=0)
        {
            $class_massive=explode(",",$class);
        }
        else
        {
            $class_massive[0]=$class;
        }

    }
    


    for($i=0;$i<count($class_massive);$i++)
    {
       switch($class_massive[$i])
        {
          
            case "1": //кроме P. 3 pl (⟨a-pø̄(1)-us⟩ = apus).
                
                $change_later[]=array();

                if($name=="pØ̄")
                {

                    $suffix[][0]=search_in_db(86,'suffixes',2);
                    //$root_rule[]="1A";
                    $new_root[]=$name;
                    $debug_str.="Образование Аориста 1 класса: корень исключение √pØ̄ <BR>";

                   
                }
                elseif($name=="bhū")
                {

                    $suffix[][0]=search_in_db(87,'suffixes',2);
                    //$root_rule[]="1A";
                    $new_root[]=$name;
                    $debug_str.="Образование Аориста 1 класса: корень исключение √bhū <BR>";

                   
                }
                elseif($name=="īkṣ")
                {

                    $suffix[][0]=search_in_db(90,'suffixes',2);
                    $new_root[]="ekṣ";
                    $debug_str.="Образование Аориста 1 класса: корень исключение īkṣ <BR>";

                }
                elseif($name=="ṛ")
                {

                    $suffix[][0]=search_in_db(90,'suffixes',2);
                    $new_root[]="ar";
                    $debug_str.="Образование Аориста 1 класса: корень исключение √ṛ <BR>";

                }
                elseif($name=="ṛdh")
                {

                    $suffix[][0]=search_in_db(90,'suffixes',2);
                    $new_root[]="ardh";
                    $debug_str.="Образование Аориста 1 класса: корень исключение ṛdh <BR>";

                }
                else
                {
                    $suffix[][0]=search_in_db(90,'suffixes',2);
                    //$suffix[][0]=array();
                    $new_root[]="$name";
                    $debug_str.="Образование Аориста 1 класса <BR>";
                }


                break;
            
            case "2":
                if($verb_ryad=="M2"||$verb_ryad=="N2"||$name=="ṛ"||$name=="kṛ"||$name=="sṛ"||($name=="gṝ"&&$omonim==2)||$name=="jṝ"||$name=="dṝ")
                {
                    $new_root[]=$name;
                    $suffix[][0]=search_in_db(88,'suffixes',2);
                    $change_later[]=array();
                }
                elseif($name=="gm̥")
                {
                    $new_root[]=$name;
                    $suffix[][0]=search_in_db(88,'suffixes',2);
                    $change_later[]=array();

                    $new_root[]=$name;
                    $suffix[][0]=search_in_db(89,'suffixes',2);
                    $change_later[]=array();
                }
                else
                {
                    $new_root[]=$name;
                    $suffix[][0]=search_in_db(89,'suffixes',2);
                    $change_later[]=array();
                }
                break;

            case "3":

                    //echo "$name,$omonim,$verb_type,$verb_change,$verb_ryad,$debug<BR><BR>";
                    $aos=get_aos3($name,$omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,$debug);
                    
                    $change_later_length=count($change_later);
                    for($cl=0;$cl<count($aos['change_later']);$cl++)
                    {
                        $change_later[$change_later_length]=$aos['change_later'][$cl];
                        $change_later_length++;
                    }
                    
                    $count_suffix=count($suffix);
                    //echo "<BR><BR>AOS:<BR>";
                    //print_r($aos);
        
                    $aos_string="";
                    for($iaos=0;$iaos<4;$iaos++)
                    {
                        if($aos['model'][$iaos])
                        {
                            $aos_massive[]=$aos['model'][$iaos];
                        }
    
                        if($aos['enew'][$iaos])
                        {
                            $enew_massive[]=$aos['enew'][$iaos];
                        }
                    }
   
                    if(count($new_root)==0)
                    {
                        
                        $new_root=$aos_massive;
                    }
                    else
                    {
     
                        for($iaos=0;$iaos<count($aos_massive);$iaos++)
                        {
                            $new_root[]=$aos_massive[$iaos];
                        }
                    }
    
                    //echo "<BR><BR>enew_massive:<BR>";
                    //print_r($enew_massive);

                   

                    if($p=="")
                    {
                        $suffix[$ie+$count_suffix][0]=search_in_db(71,'suffixes',2);
                    }
                    else
                    {
    
                        for($ie=0;$ie<count($enew_massive);$ie++)
                        {
        
                            if($enew_massive[$ie]=="|i|"||$enew_massive[$ie]=="|ī|")
                            {
                                switch($verb_ryad)
                                {
                                    case "A1":
                                        $suffix[$ie+$count_suffix][0]=search_in_db(71,'suffixes',2);
                                        //$root_rule[]="3A";
                                        break;
        
                                    case "A2":
                                        $suffix[$ie+$count_suffix][0]=search_in_db(71,'suffixes',2);
                                        //$root_rule[]="3A";
                                        break;
        
                                    case "L":
                                        $suffix[$ie+$count_suffix][0]=search_in_db(72,'suffixes',2);
                                        //$root_rule[]="3A";
                                        break;
                                }
        
                                if($verb_ryad=="I0"||$verb_ryad=="I1"||$verb_ryad=="I2")
                                {
                                
                                    
                                    if(($name=="ji"&&$omonim=="1")||$name=="jri"||$name=="ḍī"||$name=="bhī"||$name=="rī"||$name=="vī"||$name=="śī"||$name=="śrī"||$name=="hīḍ")
                                    {
                                        $suffix[$ie+$count_suffix][0]=search_in_db(71,'suffixes',2);
                                        //$root_rule[]="3A";
                                    }
                                    else
                                    {
                                        $suffix[$ie+$count_suffix][0]=search_in_db(72,'suffixes',2);
                                        //$root_rule[]="3A";
                                    }
        
                                    if($name=="hīḍ")
                                    {
                                        $suffix[$ie+$count_suffix][0]=search_in_db(71,'suffixes',2);
                                        //$root_rule[]="3A";
                                    }
        
                                }
        
                                if($verb_ryad=="U0"||$verb_ryad=="U1"||$verb_ryad=="U2")
                                {
                                    //echo "HEREHEREHE<BR>";
                                    $suffix[$ie+$count_suffix][0]=search_in_db(71,'suffixes',2);
                                    //$root_rule[]="3A";
                                }
        
                                if($verb_ryad=="R0"||$verb_ryad=="R1"||$verb_ryad=="R2")
                                {
                                    if($p!=""&&$f!="")
                                    {
                                        $suffix[$ie+$count_suffix][0]=search_in_db(72,'suffixes',2);
                                        //$root_rule[]="3A";
                                    }
                                    else
                                    {
                                        $suffix[$ie+$count_suffix][0]=search_in_db(71,'suffixes',2);
                                        //$root_rule[]="3A";
                                    }
        
                                
                                    if($name=="vṛ"||$name=="dṝ"||$name=="stṝ")
                                    {
                                        $suffix[$ie+$count_suffix][1]=search_in_db(72,'suffixes',2);
                                        //$root_rule[]="3A";
                                    }
        
                                }
        
                                if($verb_ryad=="M0"||$verb_ryad=="M1"||$verb_ryad=="M2")
                                {
        
                                    $suffix[$ie+$count_suffix][0]=search_in_db(71,'suffixes',2);//$root_rule[]="3A";
                                }
        
                                if($verb_ryad=="N0"||$verb_ryad=="N1"||$verb_ryad=="N2")
                                {
                                    if(($name=="chn̥d")||$name=="mn̥th"||$name=="rn̥j"||$name=="rn̥dh"||$name=="śvn̥c"||$name=="syn̥d"||$name=="srn̥s"||$name=="svn̥j")
                                    {
                                        $suffix[$ie+$count_suffix][0]=search_in_db(72,'suffixes',2);//$root_rule[]="3A";
                                    }
                                    else
                                    {
                                        $suffix[$ie+$count_suffix][0]=search_in_db(71,'suffixes',2);//$root_rule[]="3A";
                                    }
        
                                    if($name=="mn̥th"||$name=="rn̥j"||$name=="rn̥dh")
                                    {
                                        $suffix[$ie+$count_suffix][1]=search_in_db(72,'suffixes',2);//$root_rule[]="3A";
                                    }
        
                                }
                            }
        
                            if($enew_massive[$ie]=="|a|"||$enew_massive[$ie]=="|u|"||$enew_massive[$ie]=="|ū|")
                            {
                                switch($verb_ryad)
                                {
                                    case "A1":
                                        $suffix[$ie+$count_suffix][0]=search_in_db(71,'suffixes',2);//$root_rule[]="3A";
                                        break;
        
                                    case "A2":
                                        $suffix[$ie+$count_suffix][0]=search_in_db(71,'suffixes',2);//$root_rule[]="3A";
                                        break;
        
                                }
        
                                if($verb_ryad=="R0"||$verb_ryad=="R1"||$verb_ryad=="R2"||$verb_ryad=="N0"||$verb_ryad=="N1"||$verb_ryad=="N2")
                                {
                                    $suffix[$ie+$count_suffix][0]=search_in_db(71,'suffixes',2);//$root_rule[]="3A";
                                }
        
                                if($verb_ryad=="U0"||$verb_ryad=="U1"||$verb_ryad=="U2")
                                {
                                    $suffix[$ie+$count_suffix][0]=search_in_db(72,'suffixes',2);//$root_rule[]="3A";
        
                                
                                    // √PU 1 и 2 МП
                                    if($p!=""&&$f=="")
                                    {
                                        $suffix[$ie+$count_suffix][1]=search_in_db(71,'suffixes',2);//$root_rule[]="3A";
                                    }
        
                        
                                }
                            }
                        
                        }
                    
                    }
                    break;

            
            case "4":

                switch($verb_pada)    
                {
                    case "P":
                        if($verb_ryad=="A1")
                        {
                            
                            if($verb_name=="tvakṣ")
                            {
                                     
                                    $suffix[][0]=search_in_db(75,'suffixes',2);
                                    
                                    $new_root[]=$name;
                                    $debug_str.="Корень √tvakṣ , для образования Аориста 4 класса МП = 2<BR>";
                            }
                            else
                            {
                            
                                    $suffix[][0]=search_in_db(76,'suffixes',2);
                                    
                                    $new_root[]=$name;
                                    $debug_str.="Образование Аориста 4 класса: для ряда A1 МП = 3<BR>";
                                 
                            }
                            
                        }
    
                        if($verb_ryad=="A2")
                        {
                            $suffix[][0]=search_in_db(76,'suffixes',2);
                            
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 4 класса: для ряда A2 МП = 3<BR>";
                                   
                        }
                    
                    
                        if($verb_ryad=="I0"||$verb_ryad=="I1"||$verb_ryad=="I2")
                        {
                            $suffix[][0]=search_in_db(76,'suffixes',2);
                            
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 4 класса: для рядов I МП = 3<BR>";
    
                        }
    
                        if($verb_ryad=="U0"||$verb_ryad=="U1"||$verb_ryad=="U2")
                        {
                           
                            $suffix[][0]=search_in_db(76,'suffixes',2);
                            
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 4 класса: для рядов U МП = 3<BR>";

                            if($name=="muc"||$name=="yuj"||$name=="yudh")
                            {
                                $suffix[][0]=search_in_db(75,'suffixes',2);
                                $new_root[]=$name;
                                $debug_str.="Корни √muc, √yuj, √yudh , для образования Аориста 4 класса доп. МП = 2<BR>";
                            }
    
                        }
    
    
                        if($verb_ryad=="R0"||$verb_ryad=="R1"||$verb_ryad=="R2")
                        {
                            $suffix[][0]=search_in_db(76,'suffixes',2);
                            
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 4 класса: для рядов R МП = 3<BR>";
    
                        }
    
                        if($verb_ryad=="L")
                        {
    
                        }
                        
    
                        if($verb_ryad=="M0"||$verb_ryad=="M1"||$verb_ryad=="M2")
                        {
                            if($verb_name=="rm̥")
                            {
                                     
                                    $suffix[][0]=search_in_db(75,'suffixes',2);
                                    
                                    $new_root[]=$name;
                                    $debug_str.="Корень √rm̥ , для образования Аориста 4 класса МП = 2<BR>";
                            }
                            else
                            {
                            
                                    $suffix[][0]=search_in_db(76,'suffixes',2);
                                    
                                    $new_root[]=$name;
                                    $debug_str.="Образование Аориста 4 класса: для ряда M МП = 3<BR>";
                                 
                            }
    
                        }
    
                        if($verb_ryad=="N0"||$verb_ryad=="N1"||$verb_ryad=="N2")
                        {
                            if($verb_name=="grn̥th")
                            {
                                     
                                    $suffix[][0]=search_in_db(74,'suffixes',2);
                                    
                                    $new_root[]=$name;
                                    $debug_str.="Корень √grn̥th , для образования Аориста 4 класса МП = 1<BR>";
                            }
                            else
                            {
                            
                                    $suffix[][0]=search_in_db(76,'suffixes',2);
                                    
                                    $new_root[]=$name;
                                    $debug_str.="Образование Аориста 4 класса: для ряда N МП = 3<BR>";
                                 
                            }
    
                        }
                        
                        break;
                    case "A":
                        
                        if($verb_ryad=="A1")
                        {
                            
                            $suffix[][0]=search_in_db(75,'suffixes',2);
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 4 класса: для Atmanepada, ряда A1 МП = 2<BR>";
 
                        }
    
                        if($verb_ryad=="A2")
                        {
                            $suffix[][0]=search_in_db(75,'suffixes',2);
                            $new_root[]=$name;
                            
                            $suffix[][0]=search_in_db(76,'suffixes',2);
                            $new_root[]=$name;

                            $debug_str.="Образование Аориста 4 класса: для Atmanepada, ряда A2 МП = 2 & 3<BR>";
                                   
                        }
                    
                    
                        if($verb_ryad=="I0"||$verb_ryad=="I1"||$verb_ryad=="I2")
                        {
                           if($f=='')
                           {
                                $suffix[][0]=search_in_db(75,'suffixes',2);
                                $new_root[]=$name;
                                $debug_str.="Образование Аориста 4 класса: для Atmanepada, открытый корень, ряда I МП = 2<BR>";
                           }
                           else
                           {
                                $suffix[][0]=search_in_db(74,'suffixes',2);
                                $new_root[]=$name;
                                $debug_str.="Образование Аориста 4 класса: для Atmanepada, закрытый корень, ряда I МП = 1<BR>";
                           }
                        }
    
                        if($verb_ryad=="U0"||$verb_ryad=="U1"||$verb_ryad=="U2")
                        {
                           
                            $suffix[][0]=search_in_db(74,'suffixes',2);
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 4 класса: для Atmanepada, закрытый корень, ряда U МП = 1<BR>";

                            if($f=='')
                            {
                                 $suffix[][0]=search_in_db(75,'suffixes',2);
                                 $new_root[]=$name;
                                 $debug_str.="Образование Аориста 4 класса: для Atmanepada, открытый корень, ряда U доп. МП = 2<BR>";
                            }

                        }
    
    
                        if($verb_ryad=="R0"||$verb_ryad=="R1"||$verb_ryad=="R2")
                        {
                            $suffix[][0]=search_in_db(74,'suffixes',2);
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 4 класса: для Atmanepada ряда R МП = 1<BR>";
    
                        }
    
                        if($verb_ryad=="L")
                        {
                            $suffix[][0]=search_in_db(74,'suffixes',2);
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 4 класса: для Atmanepada ряда L МП = 1<BR>";
                        }
                        
    
                        if($verb_ryad=="M0"||$verb_ryad=="M1"||$verb_ryad=="M2")
                        {
                            $suffix[][0]=search_in_db(75,'suffixes',2);
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 4 класса: для Atmanepada ряда M МП = 2<BR>";
    
                        }
    
                        if($verb_ryad=="N0"||$verb_ryad=="N1"||$verb_ryad=="N2")
                        {
                            $suffix[][0]=search_in_db(75,'suffixes',2);
                            $new_root[]=$name;
                            
                            $suffix[][0]=search_in_db(74,'suffixes',2);
                            $new_root[]=$name;

                            $debug_str.="Образование Аориста 4 класса: для Atmanepada, рядов N МП = 2 & 1<BR>";
    
                        }
                        
                        break;
                }
                break;

            case "5":
                //$change_later[]=array('');
                if($name=="uØkṣ")
                {
                    $suffix[][0]=search_in_db(85,'suffixes',2);
                    //$root_rule[]="5A";
                    $new_root[]="okṣ";
                    $debug_str.="Образование Аориста 5 класса: корень исключение √uøkṣ <BR>";
                }
                elseif($name=="dØ̄"&&$omonim==1)
                {
                   
                    $suffix[][0]=search_in_db(85,'suffixes',2);
                    //$root_rule[]="5A";
                    $new_root[]="dad";
                    $debug_str.="Образование Аориста 5 класса: корень исключение √dø̄ 1  <BR>";
                }
                else
                {


                    if($verb_ryad=="A1")
                    {
                        $suffix[][0]=search_in_db(78,'suffixes',2);
                        //$root_rule[]="5A";
                        $new_root[]=$name;
                        $debug_str.="Образование Аориста 5 класса: для ряда A1 МП = 2<BR>";
                             
                            if(mb_strlen($f)==1&&$f=="C")
                            {
                                 
                                $suffix[][0]=search_in_db(79,'suffixes',2);
                                $root_rule[]="5A";$new_root[]=$name;
                                $debug_str.="Корень типа √PA1C , для образования Аориста 5 класса доп. МП = 3";
                            }
                        
                    }

                    if($verb_ryad=="A2")
                    {
                        $suffix[][0]=search_in_db(80,'suffixes',2);
                        //$root_rule[]="5A";
                        $new_root[]=$name;
                        $debug_str.="Образование Аориста 5 класса: для ряда A2 МП = 3 [A20↦I]<BR>";
                               
                    }
                
                
                    if($verb_ryad=="I0"||$verb_ryad=="I1"||$verb_ryad=="I2")
                    {
                        $suffix[][0]=search_in_db(78,'suffixes',2);
                        //$root_rule[]="5A";
                        $new_root[]=$name;
                        $debug_str.="Образование Аориста 5 класса: для ряда I МП = 2 <BR>";

                        if($name=="śri"||$name=="nī")
                        {
                            $suffix[][0]=search_in_db(79,'suffixes',2);
                            //$root_rule[]="5A";
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 5 класса: для ряда I для корней √śri, √nī дополнительно МП = 3 <BR>";
                        }

                    }

                    if($verb_ryad=="U0"||$verb_ryad=="U1"||$verb_ryad=="U2")
                    {
                        $suffix[][0]=search_in_db(78,'suffixes',2);
                        //$root_rule[]="5A";
                        $new_root[]=$name;
                        $debug_str.="Образование Аориста 5 класса: для ряда U МП = 2 <BR>";

                        if($p!=""&&$f=="")
                        {
                            $suffix[][0]=search_in_db(79,'suffixes',2);
                            //$root_rule[]="5A";
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 5 класса: для ряда U для корней √PU  дополнительно МП = 3 <BR>";
                        }

                        if($name=="dū"||$name=="dhu"||$name=="nu"||$name=="nū"||$name=="nud"||$name=="muc")
                        {
                            $suffix[][0]=search_in_db(77,'suffixes',2);
                            //$root_rule[]="5A";
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 5 класса: для ряда U для корней √dū, √dhu, √nu 1, √nū, √nud, √muc дополнительно МП = 1 <BR>";
                        }

                    }


                    if($verb_ryad=="R0"||$verb_ryad=="R1"||$verb_ryad=="R2")
                    {
                        $suffix[][0]=search_in_db(78,'suffixes',2);
                      //  $root_rule[]="5A";
                        $new_root[]=$name;
                        $debug_str.="Образование Аориста 5 класса: для ряда R МП = 2 <BR>";

                        if(($p!=""&&$f=="")||$name=="mṛj"||$name=="bhṛjj")
                        {
                            $suffix[][0]=search_in_db(79,'suffixes',2);
                           // $root_rule[]="5A";
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 5 класса: для ряда R для корней √PR и √mṛj, √bhṛjj  дополнительно МП = 3 <BR>";
                        }

                        if($name=="pṝ")
                        {
                            $suffix[][0]=search_in_db(77,'suffixes',2);
                          //  $root_rule[]="5A";$
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 5 класса: для ряда R для корней √pṝ дополнительно МП = 1 <BR>";
                        }

                    }

                    if($verb_ryad=="L")
                    {
                        $suffix[][0]=search_in_db(78,'suffixes',2);
                       // $root_rule[]="5A";
                        $new_root[]=$name;
                        $debug_str.="Образование Аориста 5 класса: для ряда L МП = 2 <BR>";

                        if($p!=""&&$f=="")
                        {
                            $suffix[][0]=search_in_db(79,'suffixes',2);
                         //   $root_rule[]="5A";
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 5 класса: для ряда L для корней √PL дополнительно МП = 3 <BR>";
                        }

                    }
                    

                    if($verb_ryad=="M0"||$verb_ryad=="M1"||$verb_ryad=="M2")
                    {
                        $suffix[][0]=search_in_db(78,'suffixes',2);
                       // $root_rule[]="5A";
                        $new_root[]=$name;
                        $debug_str.="Образование Аориста 5 класса: для ряда M МП = 2 <BR>";

                        if($name=="gm̥")
                        {
                            $suffix[][0]=search_in_db(77,'suffixes',2);
                          //  $root_rule[]="5A";
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 5 класса: для ряда M для корней √gm̥ дополнительно МП = 1 <BR>";
                        }

                    }

                    if($verb_ryad=="N0"||$verb_ryad=="N1"||$verb_ryad=="N2")
                    {
                        $suffix[][0]=search_in_db(78,'suffixes',2);
                       // $root_rule[]="5A";
                        $new_root[]=$name;
                        $debug_str.="Образование Аориста 5 класса: для ряда N МП = 2 <BR>";

                        if($p!=""&&$f=="")
                        {
                            $suffix[][0]=search_in_db(79,'suffixes',2);
                           // $root_rule[]="5A";
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 5 класса: для ряда N для корней √PN дополнительно МП = 3 <BR>";
                        }

                    }
                    
                    break;

                }
            case "6":
                //2МП – корни рядов M, √dṝ | 3МП – остальные.

                //Перед окончаниями P. 2, 3 sg. суффикс принимает вид (2/3)-s/iṣ/- и вставная -ī- обязательна (например, ⟨a-ghār(3)-s/iṣ/(2)-ī-t⟩ = aghārṣīt).
                if($verb_ryad=="M0"||$verb_ryad=="M1"||$verb_ryad=="M2"||$verb_name=="dṝ")
                {
                    $suffix[][0]=search_in_db(81,'suffixes',2);
                    $new_root[]=$name;
                    $debug_str.="Образование Аориста 6 класса: корень рядов М или dṝ МП=2<BR>";
                }
                else
                {
                    $suffix[][0]=search_in_db(82,'suffixes',2);
                    $new_root[]=$name;
                    $debug_str.="Образование Аориста 6 класса: МП=3<BR>";
                }
                break;
            case "7":
                $suffix[][0]=search_in_db(73,'suffixes',2);
                $new_root[]=$name;
                $debug_str.="Образование Аориста 7 класса: VR(1)-sa- <BR>";
                break;
        }
    }

    //echo "<BR><BR>NR:";
    //print_r($root_rule);

    //echo "<BR><BR>CHAMNGE LATER:";
    //print_r($change_later);

    

    $result[0]=$new_root;
    $result[1]=$suffix;
    $result[2]=$root_rule;
    $result['debug']=$debug_str;

    if($p==''&&mb_strlen($f)==1)
    {
        $ec=1;
    }
    else
    {
        $ec=0;
    }

    $result['ec']=$ec;

    if($debug)
    {
        //echo "<BR>";
        echo "<b>".$debug_str."</b>";
    }

    $result['stop']=$aos['stop'];
    $result['flag_e']=$aos['flag_e'];
    $result['need_two']=$aos['need_two'];
    $result['change_later']=$change_later;

    //echo "<BR><BR>RESULT";
    //print_r($result);

    return $result;
}

function Ao($lico,$chislo,$pada,$tematic,$lemma,$lastname,$special,$debug)
{
   // (прист.)-а-AoS-2F
    $last_letter=mb_substr($lastname,-1,1);

   if($debug)
   {
    echo "Добавляем окончания Ao<BR>";
    echo "Перед окончанием морфема: ".$lastname."<BR>";
    echo "Последний звук: ".$last_letter."<BR>";
    if($lico==1&&$chislo==1&&$pada=="P")
    {
        echo "1 sg. P. => Специальное окончание, если последняя морфема оканчивается на гласную<BR><BR>";
    }
   }


   //echo "<hr>lemma:$lemma<hr>";

    $result['augment']="|a|";
    $result['endings']=Endings2F($lico,$chislo,$pada,$tematic,$lemma,$lastname,$special,$debug);
    //$result=Endings2F($lico,$chislo,$pada,$tematic,"Ao",$lastname,$debug);


    return $result;
}

function In($lico,$chislo,$pada,$tematic,$lemma,$lastname,$special,$debug)
{
    
    //Using Ao without augment

}

function PeS($verb,$pada,$debug)
{

    $name=$verb[0];
    $omonim=$verb[1];
    $type=$verb[2];
    $element=$verb[11];
    $ryad=$verb[4];

    $dimensions=dimensions($name, $element, $name, 1, 0, 0, 0);
    $dimensions_array=dimensions_array($dimensions);

    $c_string=str_replace("|","",$dimensions[1]);

    $c_string=str_replace("EE","E",$c_string);
    $e_position=mb_strpos($c_string,"E");
    $ecc=mb_substr($c_string,$e_position,3);
    $p=mb_substr($c_string,0,$e_position);
    $before_e_in_cons=mb_substr($c_string,$e_position-2,$e_position);
    $cec=mb_substr($c_string,$e_position-1,3);
    
    $c=$dimensions_array[1][0];
    
    $flag_iskl=0;

    

    //	В корнях вида С1A1C2 II типа, где C1  = c, j, t, d, n, p, ph, b, bh, y, r, l, ś (кроме √śaś, √śas), s ; [A1↦I]
    if($ryad=="A1")
    {
        $c0=$dimensions_array[$e_position-2][0];
        
        $c1=$dimensions_array[$e_position-1][0];
        $c2=$dimensions_array[$e_position+1][0];
        $c3=$dimensions_array[$e_position+2][0];
        //echo "CEC: $cec<BR>C1: $c1 <BR>C2: $c2 <BR>";
        if($c1!=$c2&&$c3==''&&$c0=='#')
        {
            if($c1=="c"||$c1=="j"||$c1=="t"||$c1=="d"||$c1=="n"||$c1=="p"||$c1=="ph"||$c1=="b"||$c1=="bh"||$c1=="y"||$c1=="r"||$c1=="l"||$c1=="ś"||$c1=="s")
            {
                if($name!="śaś"&&$name!="śas")
                {
                    $ps_massive[]=str_replace($element,"e",$name);
                    $iskl_massive[]=1;
                    $new_element="e";
                    $new_ryad="I0";
                    $comments[]="В корнях вида С1A1C2 II типа, где C1  = c, j, t, d, n, p, ph, b, bh, y, r, l, ś (кроме √śaś, √śas), s ; [A1↦I]";
                    $pef['prefix']="";
                    $flag_iskl=1;
                }
            }
        }
        
    }

    
    if($before_e_in_cons=="#C")
    {
        if($c!="k"&&$c!="kh"&&$c!="g"&&$c!="gh")
        {
            if(($ryad=="M0"||$ryad=="M1"||$ryad=="M2")&&($type==1)&&($name!="gm̥"))
            {
                //[M↦em]
                $ps_massive[]=str_replace($element,"em",$name);
                $iskl_massive[]=1;
                $new_element="e";
                $new_ryad="I0";
                $comments[]="Удвоение корня по p2√ в некоторых случаях не происходит и осуществляются следующие трансформации: В корнях вида СE, где С ≠ k, kh, g, gh: в М I типа: [M↦em], кроме √gm̥";
                $flag_iskl=1;
                $pef['prefix'][]="";
            }

            if(($ryad=="L")&&($type==2)&&($name!="val"))
            {
                //[L↦el]
                $ps_massive[]=str_replace($element,"el",$name);
                $iskl_massive[]=1;
                $new_element="e";
                $new_type=2;
                $new_ryad="I0";
                $comments[]="Удвоение корня по p2√ в некоторых случаях не происходит и осуществляются следующие трансформации: В корнях вида СE, где С ≠ k, kh, g, gh: в L II типа: [L↦el], кроме √val";
                $flag_iskl=1;
                $pef['prefix'][]="";
            }

            if(($ryad=="R2")&&($type==1))
            {
                //[R2↦er]
                $ps_massive[]=str_replace($element,"er",$name);
                $iskl_massive[]=1;
                $new_element="e";
                $new_type=2;
                $new_ryad="I0";
                $comments[]="Удвоение корня по p2√ в некоторых случаях не происходит и осуществляются следующие трансформации: В корнях вида СE, где С ≠ k, kh, g, gh: в R2 I типа (вар.): [R2↦er]";
                $pef['prefix'][]="";
                
            }

            if(($ryad=="N1")&&($type==1))
            {
                //[N↦en]
                $ps_massive[]=str_replace($element,"en",$name);
                $iskl_massive[]=1;
                $new_element="e";
                $new_type=2;
                $new_ryad="I0";
                $comments[]="Удвоение корня по p2√ в некоторых случаях не происходит и осуществляются следующие трансформации: В корнях вида СE, где С ≠ k, kh, g, gh: в N1 I типа (вар.): [N↦en]";
                $pef['prefix'][]="";
                
            }

            if(($ryad=="N0"||$ryad=="N1"||$ryad=="N2")&&($type==2))
            {
                //[N↦en]
                $ps_massive[]=str_replace($element,"en",$name);
                $iskl_massive[]=1;
                $new_element="e";
                $new_type=2;
                $new_ryad="I0";
                $comments[]="Удвоение корня по p2√ в некоторых случаях не происходит и осуществляются следующие трансформации: В корнях вида СE, где С ≠ k, kh, g, gh: в N II типа (вар.): [N↦en]";
                $pef['prefix'][]="";
                
            }
        }

    }
    
    $ps_massive=array_filter(array_unique($ps_massive));
   
    
    if(!$flag_iskl)
    {
        
        $pe=get_perfect($verb[0],$verb[1],$verb[2],$verb[3],$verb[4],$verb[10],$pada,0);

      

        $pe1=$pe[1];
        $ps_massive_double=explode(",",$pe1);


        for($i=0;$i<count($ps_massive_double);$i++)
        {
            $iskl_massive[]="0";
        }

        $pef['prefix'][]=$pe['prefix'];

        
        if($ps_massive)
        {
            //$ps_massive=array_merge($ps_massive,$ps_massive_double);
        }
        else
        {
            $ps_massive=$ps_massive_double;
        }
    }

  

    $pe_always=get_perfect($verb[0],$verb[1],$verb[2],$verb[3],$verb[4],$verb[10],$pada,$debug);
    $ps_always_massive_double=explode(",",$pe_always[1]);

  
    $result[0]=$ps_massive; 
    $result['source']=$pe_always['source'];
    $result['double']=$ps_always_massive_double;
    $result['no_udvoenie']=$iskl_massive;
    $result['double_prefix']=$pe['prefix'];
    $result['prefix']=$pef['prefix'];
    $result['stop']=$pe_always['stop'];
    $result['flag_e']=$pe_always['flag_e'];
    $result['need_two']=$pe_always['need_two'];
    $result['change_later']=$pe_always['change_later'];
   
   if($debug)
   {
    print_r($comments);
    echo "<HR>";
   }

    return $result;
}

function PeOS($pada)
{
    if($pada=="P")
    {
        return search_in_db(60,'suffixes',2);
    }

    if($pada=="A")
    {
        return search_in_db(61,'suffixes',2);
    }
}

function PeO($lico,$chislo,$pada,$lastname,$tematic,$debug)
{
    
    //  O: PrS(1)-yā(?)-2F |
    //	PrS(1)-ī(?)-2F

    $last_letter=mb_substr($lastname,-1,1);

   if($debug)
   {
    echo "Добавляем окончания O<BR>";
    echo "Перед окончанием морфема: ".$lastname."<BR>";
    echo "Последний звук: ".$last_letter."<BR>";
    if($lico==1&&$chislo==1&&$pada=="P")
    {
        echo "1 sg. P. => Специальное окончание, если последняя морфема оканчивается на гласную<BR><BR>";
    }
   }

    $result=Endings2F($lico,$chislo,$pada,$tematic,"O",$lastname,0,$debug);

    return $result;
}

function PeIp($verb,$lico,$chislo,$pada,$lastname,$tematic,$debug)
{
    $name=$verb[0];
    $omonim=$verb[1];
    $type=$verb[2];
    $element=$verb[11];
    $ryad=$verb[4];

    $dimensions=dimensions($name, $element, $name, 1, 0, 0, 0);
    $dimensions_array=dimensions_array($dimensions);

    $c_string=$dimensions[1];
    $c_string=str_replace("EE","E",$c_string);
    $e_position=mb_strpos($c_string,"E");
    $ecc=mb_substr($c_string,$e_position,3);

    if($ecc=="E")
    {
        $is_open_mool=1;
    }
    else
    {
        $is_open_mool=0;
    }

    $result=EndingsIpF($lico,$chislo,$pada,$tematic,"Ip",$lastname,$is_open_mool,$debug);

    return $result;
}

function PeSbS()
{

    return search_in_db(52,'suffixes',2);
}

function AoSbS()
{

    return search_in_db(52,'suffixes',2);
}

function PeSb($lico,$chislo,$pada,$lastname,$tematic,$debug)
{

  
    $result[0]=Endings2F($lico,$chislo,$pada,$tematic,"PeSb",$lastname,0,$debug);
    $result[1]=Endings1F($lico,$chislo,$pada,$tematic,"PeSb");

    
    return $result;
}

function AoSb($lico,$chislo,$pada,$lastname,$tematic,$debug)
{

  
    $result[0]=Endings2F($lico,$chislo,$pada,$tematic,"AoSb",$lastname,0,$debug);
    $result[1]=Endings1F($lico,$chislo,$pada,$tematic,"AoSb");

    
    return $result;
}


function PeF($id,$lico,$chislo,$pada)
{
    $verb=search_in_db($id,'verbs',1);

    $name=$verb[0];
    $omonim=$verb[1];
    $type=$verb[2];
    $element=$verb[11];
    $ryad=$verb[4];

    $dimensions=dimensions($name, $element, $name, 1, 0, 0, 0);
    $dimensions_array=dimensions_array($dimensions);

    $c_string=$dimensions[1];

    $c_string=str_replace("EE","E",$c_string);
    $e_position=mb_strpos($c_string,"E");
    $ecc=mb_substr($c_string,$e_position,3);
    $p=mb_substr($c_string,0,$e_position);

    if($p!=""&&$ryad=="A1"&&$ecc=="EC")
    {
        $pa1c=1;
    }
    else
    {
        $pa1c=0;
    }


    if($ecc=="E")
    {
        $is_open_mool=1;
    }
    else
    {
        $is_open_mool=0;
    }

    if($pada=="Ā"){$pada="A";}


    if($pada=="P") 
    {
       
        switch ($chislo)
        {
            case "1":
                switch($lico)
                {
                    case "1":

                        if($ryad=="A2")
                        {
                            $result[]=search_in_db(38,'endings',3);
                            $query[]=1;
                        }
                        else
                        {

                            if($pa1c)
                            {
                                $result[]=search_in_db(40,'endings',3); 
                                $query[]=3;
                            }

                          

                            if($is_open_mool)
                            {
                               // I, U, R, L, M, N
                                if($ryad=="I0"||$ryad=="I1"||$ryad=="I2"||$ryad=="U0"||$ryad=="U1"||$ryad=="U2"||$ryad=="R0"||$ryad=="R1"||$ryad=="R2"||$ryad=="L"||$ryad=="M0"||$ryad=="M1"||$ryad=="M2"||$ryad=="N0"||$ryad=="N1"||$ryad=="N2")
                                {
                                    $result[]=search_in_db(40,'endings',3);
                                    $query[]=3;
                                }
                            }

                            $result[]=search_in_db(39,'endings',3);
                            $query[]=2;

                        }



                        break;

                    case "2":
                        $result[]=search_in_db(41,'endings',3);
                        $query[]=2;
                        break;
                    
                    case "3":

                        if($ryad=="A2")
                        {
                            $result[]=search_in_db(42,'endings',3);
                            $query[]=1;
                        }
                        else
                        {

                            if($pa1c)
                            {
                                $result[]=search_in_db(44,'endings',3); 
                                $query[]=3;
                            }
                            elseif($is_open_mool)
                            {
                               // I, U, R, L, M, N
                                if($ryad=="I0"||$ryad=="I1"||$ryad=="I2"||$ryad=="U0"||$ryad=="U1"||$ryad=="U2"||$ryad=="R0"||$ryad=="R1"||$ryad=="R2"||$ryad=="L"||$ryad=="M0"||$ryad=="M1"||$ryad=="M2"||$ryad=="N0"||$ryad=="N1"||$ryad=="N2")
                                {
                                    $result[]=search_in_db(44,'endings',3);
                                    $query[]=3;
                                }
                            }
                            else
                            {
                                $result[]=search_in_db(43,'endings',3);
                                $query[]=2;
                            }

                        }
                        break;
                }
                break;

            case "2":
                switch($lico)
                {
                    case "1":
                            $result[]=search_in_db(45,'endings',3);
                            $query[]=1;
                            break;
        
                    case "2":
                            $result[]=search_in_db(46,'endings',3);
                            $query[]=1;
                            break;
                        
                    case "3":
                            $result[]=search_in_db(47,'endings',3);
                            $query[]=1;
                            break;
                }
                break;

            case "3":
                switch($lico)
                {
                        case "1":
                                $result[]=search_in_db(48,'endings',3);
                                $query[]=1;
                                break;
            
                        case "2":
                                $result[]=search_in_db(49,'endings',3);
                                $query[]=1;
                                break;
                            
                        case "3":
                                $result[]=search_in_db(50,'endings',3);
                                $query[]=1;
                                break;
                }
                break;
        }

    }
    elseif($pada=="A")
    {
       // echo "$pada PADA";
        switch ($chislo)
        {
            case "1":
                switch($lico)
                {
                    case "1":
                        $result[]=search_in_db(51,'endings',3);
                        $query[]=1;
                        break;

                    case "2":
                        if($ryad=="M0"||$ryad=="M1"||$ryad=="M2"||$ryad=="N0"||$ryad=="N1"||$ryad=="N2")
                        {
                            if($p!=""&&$is_open_mool)
                            {
                                $result[]=search_in_db(53,'endings',3);
                                $query[]=1;
                            }
                        }
                        else
                        {
                            $result[]=search_in_db(52,'endings',3);
                            $query[]=1;
                        }

                        break;
                    
                    case "3":
                        $result[]=search_in_db(54,'endings',3);
                        $query[]=1;
                        break;
                }
                break;

            case "2":
                switch($lico)
                {
                    case "1":
                            $result[]=search_in_db(55,'endings',3);
                            $query[]=1;
                            break;
        
                    case "2":
                            $result[]=search_in_db(56,'endings',3);
                            $query[]=1;
                            break;
                        
                    case "3":
                            $result[]=search_in_db(57,'endings',3);
                            $query[]=1;
                            break;
                }
                break;

            case "3":
                switch($lico)
                {
                        case "1":
                                $result[]=search_in_db(58,'endings',3);
                                $query[]=1;
                                break;
            
                        case "2":
                                $result[]=search_in_db(59,'endings',3);
                                $query[]=1;
                                break;
                            
                        case "3":
                                $result[]=search_in_db(60,'endings',3);
                                $query[]=1;
                                break;
                }
                break;
        }

    }

    return $result;
}


function pPeS()
{
    
    $verb=search_in_db(1,'verbs',1);

    $name=$verb[0];
    $omonim=$verb[1];
    $type=$verb[2];
    $element=$verb[11];
    $ryad=$verb[4];
    $setnost=$verb[6];

    $pe=get_perfect($name,$omonim,$type,$element,$ryad,'',0)[1];

    $result[]=array("ām ".$pe,'',$type,$element,$ryad,$setnost,0,1,'','',$element);

    //////////////////////////////////////////////

    
    $verb=search_in_db(553,'verbs',1);

    $name=$verb[0];
    $omonim=$verb[1];
    $type=$verb[2];
    $element=$verb[11];
    $ryad=$verb[4];
    $setnost=$verb[6];


    $pe=get_perfect($name,$omonim,$type,$element,$ryad,'',0)[1];

    $result[]=array("ām ".$pe,'',$type,$element,$ryad,$setnost,0,1,'','',$element);
  
    //////////////////////////////////////////////

    
    $verb=search_in_db(594,'verbs',1);

    $name=$verb[0];
    $omonim=$verb[1];
    $type=$verb[2];
    $element=$verb[11];
    $ryad=$verb[4];
    $setnost=$verb[6];

   $pe=get_perfect($name,$omonim,$type,$element,$ryad,'',0)[1];

   //echo "<BR>'ām '.$pe,'',$type,$element,$ryad,$setnost,0,'','','',$element<BR>";
  
   $result[]=array("ām ".$pe,'',$type,$element,$ryad,$setnost,0,1,'','',$element);

    
    return $result;
}

function PS()
{
     //PS: S(1)-y-
     return search_in_db(21,'suffixes',2);
}

function PaPrP()
{
    return search_in_db(39,'suffixes',2);
}

function PrS($steam,$class,$name,$omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada)
{
    //echo $steam;
    //echo $class." ".$steam." ".$p." ".$f."<BR>";
    //echo "PrS: $steam,$class,$name,$omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada<BR>";

    if(mb_strpos($class,",")!=0)
    {
        $class_massive=explode(",",$class);
    }
    else
    {
        $class_massive[0]=$class;
    }

    if($steam!="VR"&&$steam!="IS")
    {
        $debug_str.="Образование PrS идёт не от корня VR и не от интенсива IS. <b>Образуется по алгоритму для 1 класса о.н.в: S(2)-a-</b><BR>  ";
       
        $suffix[]=search_in_db(22,'suffixes',2);
        $root_rule[]=1;
        $new_root[]=$name;
    }
    elseif($steam!="VR")
    {
       // echo "PrS";
       $debug_str.="Образование PrS идёт не от корня VR, а от интенсива IS. Возможны вариантов для 1,2,6 классов о.н.в.<BR>  ";
       for($i=0;$i<count($class_massive);$i++)
        {
            switch($class_massive[$i])
            {
                case "1":
                $debug_str.="Образуется по алгоритму для 1 класса о.н.в: S(2)-a-<BR>";
                $suffix[]=search_in_db(22,'suffixes',2);
                $root_rule[]=1;
                $new_root[]=$name;
                break;

                case "2":
                    $debug_str.="Образуется по алгоритму для 2 класса о.н.в: присоединение окончания к основе без суффикса<BR>";
                    $suffix[]="";
                    $new_root[]=$name;
                    $root_rule[]=2;
                    break;

                case "6":  //В некоторых VR между E и F корня вставляется инфикс (1)-n- (например, √tṛp → tṛmp-). Таковы: √ṛj, √kṛt 1, √tṛp, √tṛh (вар.), √piś, √piṣ (вар.), √muc (вар.), √yuj, √rudh 2, √lip, √lup, √vid 1, 2, √vidh 2, √sic, √śṛøth, √śṝ 1.
                        $debug_str.="Образуется по алгоритму для 6 класса о.н.в: VR(1)-a-  IS(1)-a-  <BR>";
                        if($name=="iṣ"){$new_root[]="icch";}
                        elseif($name=="ṛ"){$new_root[]="ṛcch";}
                        elseif($name=="gm̥"){$new_root[]="gacch";}
                        elseif($name=="pØ̄"){$new_root[]="pib";}
                        elseif($name=="ym̥"){$new_root[]="yacch";}
                        elseif($name=="sthØ̄"){$new_root[]="tiṣṭhØ̄";}
                        elseif($name=="ṛj"){$new_root[]="ṛ|n|j";}
                        elseif($name=="kṛt"&&$omonim==1){$new_root[]="kṛ|n|t";}
                        elseif($name=="tṛp"){$new_root[]="tṛ|n|p";}
                        elseif($name=="tṛh"){$new_root[]="tṛ|n|h";}
                        elseif($name=="piś"){$new_root[]="pi|n|ś";}
                        elseif($name=="piṣ"){$new_root[]="pi|n|ṣ";}
                        elseif($name=="muc"){$new_root[]="mu|n|c";}
                        elseif($name=="yuj"){$new_root[]="yu|n|j";}
                        elseif($name=="rudh"&&$omonim==2){$new_root[]="ru|n|dh";}
                        elseif($name=="lip"){$new_root[]="li|n|p";}
                        elseif($name=="lup"){$new_root[]="lu|n|p";}
                        elseif($name=="vid"&&$omonim==1){$new_root[]="vi|n|d";}
                        elseif($name=="vid"&&$omonim==2){$new_root[]="vi|n|d";}
                        elseif($name=="vidh"&&$omonim==2){$new_root[]="vi|n|dh";}
                        elseif($name=="sic"){$new_root[]="si|n|c";}
                        elseif($name=="śṛøth"){$new_root[]="śṛø|n|th";}
                        elseif($name=="śṝ"&&$omonim==1){$new_root[]="śṝ|n|";}
                        else
                        {
                            $new_root[]=$name;
                        }

                        
                        $root_rule[]=6;
                        $suffix[]=search_in_db(41,'suffixes',2);


                        if($name=="tṛh"){$new_root[]=$name;$suffix[]=search_in_db(41,'suffixes',2);}
                        if($name=="piṣ"){$new_root[]=$name;$suffix[]=search_in_db(41,'suffixes',2);}
                        if($name=="muc"){$new_root[]=$name;$suffix[]=search_in_db(41,'suffixes',2);}

                        break;

            }
        }
    }
    else
    {
        
        for($i=0;$i<count($class_massive);$i++)
        {

            switch($class_massive[$i])
            {
                case "1":
                    $suffix[]=search_in_db(22,'suffixes',2);
                    $new_root[]=$name;
                    $root_rule[]=1;
                    $debug_str.="О.н.в. 1 класса , образование S(2)-a-";
                    break;

                case "2":
                    $suffix[]="";
                    $new_root[]=$name;
                    $root_rule[]=2;
                    $debug_str.="О.н.в. 2 класса , образование VR- (присоединение окончания к корню)";
                    break;
                
                case "3": //УДВОЕНИЕ
                    $suffix[]="";

                    $onv3=get_onv3($name,$omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,0);
                   // echo "<BR>ONV3: ";
                  //  echo "$name,$omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada<BR><BR>";
                 
                  //  echo "<BR>";
                    $new_root[]=$onv3;
                    $root_rule[]=3;
                    break;

                case "4":
                    //√dṛś → paś(1)-ya-
                    //√mid → med(1)-ya-
                    if($name=="dṛś"){$new_root[]="paś";}
                    elseif($name=="mid"){$new_root[]="med";}
                    else
                    {
                        $new_root[]=$name;
                    }
                    $suffix[]=search_in_db(40,'suffixes',2);
                    $root_rule[]=4;
                    $debug_str.="О.н.в. 4 класса , образование VR(1)-ya-";
                    break;

                case "5":  //Перед окончаниями на -m, -v, требующих 1МП, «u» суффикса перестает отображаться на письме, если VR открытый рядов I, U, R (например, √kṛ → ⟨kṛ(1)-n/u/(1)-mahe⟩ = kṛṇmahe).
                    
                    if($name=="śru"){$new_root[]="śṛ";}
                    else
                    {
                        $new_root[]=$name;
                    }
                    
                    $suffix[]=search_in_db(46,'suffixes',2);
                    $root_rule[]=5;
                    break;

                case "6":  //В некоторых VR между E и F корня вставляется инфикс (1)-n- (например, √tṛp → tṛmp-). Таковы: √ṛj, √kṛt 1, √tṛp, √tṛh (вар.), √piś, √piṣ (вар.), √muc (вар.), √yuj, √rudh 2, √lip, √lup, √vid 1, 2, √vidh 2, √sic, √śṛøth, √śṝ 1.
                    
                    if($name=="iṣ"){$new_root[]="icch";}
                    elseif($name=="ṛ"){$new_root[]="ṛcch";}
                    elseif($name=="gm̥"){$new_root[]="gacch";}
                    elseif($name=="pØ̄"){$new_root[]="pib";}
                    elseif($name=="ym̥"){$new_root[]="yacch";}
                    elseif($name=="sthØ̄"){$new_root[]="tiṣṭhØ̄";}
                    elseif($name=="ṛj"){$new_root[]="ṛ|n|j";}
                    elseif($name=="kṛt"&&$omonim==1){$new_root[]="kṛ|n|t";}
                    elseif($name=="tṛp"){$new_root[]="tṛ|n|p";}
                    elseif($name=="tṛh"){$new_root[]="tṛ|n|h";}
                    elseif($name=="piś"){$new_root[]="pi|n|ś";}
                    elseif($name=="piṣ"){$new_root[]="pi|n|ṣ";}
                    elseif($name=="muc"){$new_root[]="mu|n|c";}
                    elseif($name=="yuj"){$new_root[]="yu|n|j";}
                    elseif($name=="rudh"&&$omonim==2){$new_root[]="ru|n|dh";}
                    elseif($name=="lip"){$new_root[]="li|n|p";}
                    elseif($name=="lup"){$new_root[]="lu|n|p";}
                    elseif($name=="vid"&&$omonim==1){$new_root[]="vi|n|d";}
                    elseif($name=="vid"&&$omonim==2){$new_root[]="vi|n|d";}
                    elseif($name=="vidh"&&$omonim==2){$new_root[]="vi|n|dh";}
                    elseif($name=="sic"){$new_root[]="si|n|c";}
                    elseif($name=="śṛøth"){$new_root[]="śṛø|n|th";}
                    elseif($name=="śṝ"&&$omonim==1){$new_root[]="śṝ|n|";}
                    else
                    {
                        $new_root[]=$name;
                    }

                    
                    $root_rule[]=6;
                    $suffix[]=search_in_db(41,'suffixes',2);


                    if($name=="tṛh"){$new_root[]=$name;$suffix[]=search_in_db(41,'suffixes',2);}
                    if($name=="piṣ"){$new_root[]=$name;$suffix[]=search_in_db(41,'suffixes',2);}
                    if($name=="muc"){$new_root[]=$name;$suffix[]=search_in_db(41,'suffixes',2);}

                    break;

                case "7":

                    
                   // $new_root[]=$name;
                    $suffix[]=search_in_db(47,'suffixes',2);
                    $root_rule[]=7;
                    break;

                case "8":  //Исключения для √kṛ:    Если (1)-u-, то √kṛ → kur(1)-u(1)-    Если (1)-о-, то √kṛ → kar(2)-o(2)-   Em, Ev, Ey в kur(1)-u- → kur(1)-/u/- (если первая буква окончания m, v, y, то суффикс "u" не пишется)
                  
                    $new_root[]=$name;
                    $suffix[]=search_in_db(48,'suffixes',2);
                    $root_rule[]=8;
                    break;

                case "9":  //√jñā → jā(1)-nø̄-
                    if($name=="jñā"){$new_root[]="jā";}
                    else
                    {
                        $new_root[]=$name;
                    }
                    $suffix[]=search_in_db(49,'suffixes',2);
                    $root_rule[]=9;
                    break;

                
            }
        }

        

        
    }

    $result[0]=$new_root;
    $result[1]=$suffix;
    $result[2]=$root_rule;
    $result['debug']=$debug_str;


  

    return $result;
}

function Pr($lico,$chislo,$pada,$tematic,$lemma)
{

    //echo "<BR>$tematic,$lemma<BR>";
    $result=Endings1F($lico,$chislo,$pada,$tematic,$lemma);
    return $result;
}

function OS($tematic,$pada)
{
    //(1)-yā- от атематических PrS P.; (1)-ī- от остальных PrS.
    if($tematic==0&&$pada=="P")
    {
        return search_in_db(50,'suffixes',2);
    }
    else
    {
        return search_in_db(51,'suffixes',2);
    }
}

function O($lico,$chislo,$pada,$lastname,$tematic,$debug)
{
    
    //  O: PrS(1)-yā(?)-2F |
    //	PrS(1)-ī(?)-2F

    $last_letter=mb_substr($lastname,-1,1);

   if($debug)
   {
    echo "Добавляем окончания O<BR>";
    echo "Перед окончанием морфема: ".$lastname."<BR>";
    echo "Последний звук: ".$last_letter."<BR>";
    if($lico==1&&$chislo==1&&$pada=="P")
    {
        echo "1 sg. P. => Специальное окончание, если последняя морфема оканчивается на гласную<BR><BR>";
    }
   }

    $result=Endings2F($lico,$chislo,$pada,$tematic,"O",$lastname,0,$debug);

    return $result;
}

function Im($lico,$chislo,$pada,$lastname,$tematic,$debug)
{
   // → Co: (прист.)-а-FuS(?)-2F
   
    $result=Endings2F($lico,$chislo,$pada,$tematic,"Im",$lastname,0,$debug);

    return $result;
}

function Ip($verb,$lico,$chislo,$pada,$lastname,$tematic,$debug)
{
    $name=$verb[0];
    $omonim=$verb[1];
    $type=$verb[2];
    $element=$verb[11];
    $ryad=$verb[4];

    $dimensions=dimensions($name, $element, $name, 1, 0, 0, 0);
    $dimensions_array=dimensions_array($dimensions);

    $c_string=$dimensions[1];
    $c_string=str_replace("EE","E",$c_string);
    $e_position=mb_strpos($c_string,"E");
    $ecc=mb_substr($c_string,$e_position,3);

    if($ecc=="E")
    {
        $is_open_mool=1;
    }
    else
    {
        $is_open_mool=0;
    }

    $result=EndingsIpF($lico,$chislo,$pada,$tematic,"Ip",$lastname,$is_open_mool,$debug);

    return $result;
}

function AoIp($verb,$lico,$chislo,$pada,$lastname,$tematic,$debug)
{
    $name=$verb[0];
    $omonim=$verb[1];
    $type=$verb[2];
    $element=$verb[11];
    $ryad=$verb[4];

    $dimensions=dimensions($name, $element, $name, 1, 0, 0, 0);
    $dimensions_array=dimensions_array($dimensions);

    $c_string=$dimensions[1];
    $c_string=str_replace("EE","E",$c_string);
    $e_position=mb_strpos($c_string,"E");
    $ecc=mb_substr($c_string,$e_position,3);

    if($ecc=="E")
    {
        $is_open_mool=1;
    }
    else
    {
        $is_open_mool=0;
    }

    $result=EndingsIpF($lico,$chislo,$pada,$tematic,"Ip",$lastname,$is_open_mool,$debug);

    return $result;
}

function AoP($verb,$lico,$chislo,$pada,$lastname,$tematic,$debug)
{
    //print_r($verb);
    
    $name=$verb[0];
    $omonim=$verb[1];
    $type=$verb[2];
    $element=$verb[11];
    $ryad=$verb[4];

    $dimensions=dimensions($name, $element, $name, 1, 0, 0, 0);
    $dimensions_array=dimensions_array($dimensions);

    $c_string=$dimensions[1];
    $c_string=str_replace("EE","E",$c_string);
    $e_position=mb_strpos($c_string,"E");
    $ec=mb_substr($c_string,$e_position,3);
    $p=mb_substr($c_string,0,$e_position);

    if($ec=="E")
    {
        $is_open_mool=1;
    }
    else
    {
        $is_open_mool=0;
    }

    //echo "EC:".$ec."<BR><BR>";

    //3МП для √PA1C и открытых корней остальных рядов. 2МП для остальных. Исключение: √pṝ → (a)pūri.

    if($name=="pṝ")
    {
        $suffix[]=search_in_db(121,'endings',3);
        $new_root[]="pūr";
        $root_rule[]="AoP";
    }
    elseif($ryad=="A1"&&$p!=''&&$ec=="EC")
    {
       $suffix[]=search_in_db(121,'endings',3);
       $new_root[]=$name;
       $root_rule[]="AoP";
    }
    elseif($ryad!="A1"&&$is_open_mool==1)
    {
       $suffix[]=search_in_db(121,'endings',3);
       $new_root[]=$name;
       $root_rule[]="AoP";
    }
    else
    {
        $suffix[]=search_in_db(122,'endings',3);
        $new_root[]=$name;
        $root_rule[]="AoP";
    }

    $result[0]=$new_root;
    $result[1]=$suffix;
    $result[2]=$root_rule;
    $result['augment_var']="a";

    return $result;
}

function PrSbS()
{

    return search_in_db(52,'suffixes',2);
}

function PrSb($lico,$chislo,$pada,$lastname,$tematic,$debug)
{

    //echo "HERE: $lico,$chislo,$pada,$tematic,PrSb,$lastname,$debug<BR>";
    $result[0]=Endings2F($lico,$chislo,$pada,$tematic,"PrSb",$lastname,0,$debug);
    $result[1]=Endings1F($lico,$chislo,$pada,$tematic,"PrSb");
   
    
    return $result;
}

function PluPe($lico,$chislo,$pada,$lastname,$tematic,$debug)
{
    
    $result=Endings2F($lico,$chislo,$pada,"","PluPe",$lastname,0,$debug);

    return $result;
}

function PaPrAS($pada,$massive)
{
   // → PaPrA:
	//PrS(1)-n̥t(?)-NF (P) |
	//PrS(1)-(m)āna(?)-NF (Ā)
    //echo "<BR><BR>Mass";
    
    //echo "<BR><BR>";

    for($i=0;$i<count($massive);$i++)
    {
        $steam.=$massive[$i][0];
    }

    //echo $steam;
    $last_letter=mb_substr($steam,-1,1);
    //echo $last_letter;

    if($pada=="P")
    {
        $suffix=search_in_db(53,'suffixes',2);
    }

    if($pada=="A")
    {
        if($last_letter=="a"||$last_letter=="ā")
        {
            $suffix=search_in_db(54,'suffixes',2);
        }
        else
        {
            $suffix=search_in_db(55,'suffixes',2);
        }
        
    }

    $root_rule="PaPrAS";

    $result[0]=$suffix;
    $result[1]=$root_rule;
    return $result;
}

function PaPePS($verb)
{
    $name=$verb[0];
    $omonim=$verb[1];
    $type=$verb[2];
    $element=$verb[11];
    $ryad=$verb[4];

    $dimensions=dimensions($name, $element, $name, 1, 0, 0, 0);
    $dimensions_array=dimensions_array($dimensions);

    $c_string=$dimensions[1];
    $c_string=str_replace("EE","E",$c_string);
    $e_position=mb_strpos($c_string,"E");
    $ecc=mb_substr($c_string,$e_position,3);

    if($ecc=="E")
    {
        $is_open_mool=1;
    }
    else
    {
        $is_open_mool=0;
    }
    
    /*
        (1)-na- присоединяется к некоторым VR, а именно:
    •	открытые корни ряда R2 (некоторые имеют более редкую форму с (1)-ta-, а также √hūrch → hūrna- (вар.);
    •	√lag;
    •	некоторые корни на -с, а именно: √vṛøśc, √n̥c;
    •	некоторые корни на -j, а именно: √majj, √vij (вар.), √ruj, √bhuj 1, √bhn̥j;
    •	некоторые корни на -d, а именно: √ad, √chad, √had, √pad, √śad, √sad, √hlād, √chid, √khid, √klid, √kṣvid, √mid, √svid, √vid 2 (вар.), √skn̥d, √syn̥d, √ṛd, √chṛd, √tṛd, √kṣud, √nud (вар.), √tud, √ud (вар.);
    •	некоторые открыте корни рядов I, а именно: √kṣi 2 (вар.), √ḍī, √lī, √pi → pīna-, √vlī (√blī), √hrī (вар.);
    •	некоторые открыте корни рядов U, а именно: √du, √dhū (вар.), √dū, √lū, √pū (вар.), √śū, √sū (вар.), а также √dīv 2 → dyūna-;
    
    √hūrch → hūrna- (вар.)
    √pi → pīna-
    √dīv 2 → dyūna-
    Исключение: √dø̄ 1 → ⟨dadø̄(pr2√, 1)-ta-⟩ = datta- (вар.)
    */

    //unset($root_rule);

    if($name=="hūrch")
    {
        $suffix[]=search_in_db(56,'suffixes',2);
        $new_root[]="hūr";
        $root_rule[]="PaPePS";
    }

    if($name=="pi")
    {
        $suffix[]=search_in_db(56,'suffixes',2);
        $new_root[]="pī";
        $root_rule[]="PaPePS";
        $stop=1;
    }
    
    if($name=="dīv")
    {
        $suffix[]=search_in_db(56,'suffixes',2);
        $new_root[]="dyū";
        $root_rule[]="PaPePS";
        $stop=1;
    }

    if($name=="dØ̄"&&$omonim==1)
    {
        $suffix[]=search_in_db(58,'suffixes',2);
        $new_root[]="dat";
        $root_rule[]="PaPePS";
    }

    if($name=="vij"||($name=="vid"&&$omonim==2)||$name=="nud"||$name=="ud"||($name=="kṣi"&&$omonim==2)||$name=="hrī"||$name=="dhū"||$name=="pū"||$name=="sū")
    {
        $suffix[]=search_in_db(56,'suffixes',2);
        $new_root[]=$name;
        $root_rule[]="PaPePS";
    }

    if($name=="ghrā"||$name=="stīØ̄"||$name=="vā"||$name=="jīØ̄"||$name=="pyā"||$name=="mlā")
    {
        $suffix[]=search_in_db(57,'suffixes',2);
        $new_root[]=$name;
        $root_rule[]="PaPePS";
    }

    if(($ryad=="R2"&&$is_open_mool)||$name=="lag"||$name=="vṛØśc"||$name=="n̥c"||$name=="majj"||$name=="ruj"||($name=="bhuj"&&$omonim==1)||$name=="bhn̥j"||$name=="ad"
    ||$name=="chad"||$name=="had"||$name=="pad"||$name=="śad"||$name=="sad"||$name=="hlād"||$name=="chid"||$name=="khid"||$name=="klid"||$name=="kṣvid"||$name=="mid"||$name=="svid"
    ||$name=="skn̥d" ||$name=="syn̥d" ||$name=="ṛd" ||$name=="chṛd" ||$name=="tṛd" ||$name=="kṣud" ||$name=="tud" ||$name=="ḍī" ||$name=="lī"
    ||$name=="vlī"||$name=="blī"||$name=="du"||$name=="dū"||$name=="lū"||$name=="śū")
    {
        $suffix[]=search_in_db(56,'suffixes',2);
        $new_root[]=$name;
        $root_rule[]="PaPePS";
       
    }
    elseif($name=="glā"||($name=="drā"&&$omonim==1)||($name=="drā"&&$omonim==2)||($name=="hØ̄"&&$omonim==1)||($name=="hØ̄"&&$omonim==2))
    {
        /*
        (2)-na- присоединяется к некоторым VR, а именно:
        •	некоторые открытые корни ряда А2, а именно: √glā, √drā 1, 2, √ghrā (вар.), √hø̄ 1, 2, √stīø̄ (вар.), √vā (вар.), √jīø̄ (вар.), √pyā (вар.), √mlā (вар.);
        */
        $suffix[]=search_in_db(57,'suffixes',2);
        $new_root[]=$name;
        $root_rule[]="PaPePS";
    }
    elseif($name=="tṝ"||($name=="śṝ"&&$omonim==1)||($name=="śṝ"&&$omonim==2))
    {
        $suffix[]=search_in_db(58,'suffixes',2);
        $new_root[]=$name;
        $root_rule[]="PaPePS";
    }
    elseif($stop!=1)
    {
        //echo "NAME:".$name;
        /*
        S(1)-ta- – от остальных S.
        Исключение: √dø̄ 1 → ⟨dadø̄(pr2√, 1)-ta-⟩ = datta- (вар.)
        Пример: √nī → ⟨nī(1)-ta-⟩ = nīta-
        */
        $suffix[]=search_in_db(58,'suffixes',2);

      
        $new_root[]=$name;
        $root_rule[]="PaPePS";
    }

   
   

    $result[0]=$new_root;
    $result[1]=$suffix;
    $result[2]=$root_rule;
    return $result;

}

function pPaPeAS($pada)
{
   // return search_in_db(59,'suffixes',2);
   if($pada=="P")
    {
        return search_in_db(62,'suffixes',2);
    }

    if($pada=="A")
    {
        return search_in_db(63,'suffixes',2);
    }
}

function PaPeAS($pada)
{
    //echo $pada;
    
    if($pada=="P")
    {
        $suffix[]=search_in_db(62,'suffixes',2);
    }

    if($pada=="A")
    {
        $suffix[]=search_in_db(63,'suffixes',2);
    }

    $root_rule="PaPeAS";

    $result[0]=$suffix;
    $result[1]=$root_rule;

    
    return $result;
}

function PaFuP($previous)
{

    switch($previous)
    {
        case "VR":
            $suffix[]=search_in_db(64,'suffixes',2);
            $suffix[]=search_in_db(65,'suffixes',2);
            $suffix[]=search_in_db(66,'suffixes',2);
            $suffix[]=search_in_db(67,'suffixes',2);
            $suffix[]=search_in_db(68,'suffixes',2);
            $suffix[]=search_in_db(69,'suffixes',2);
            $suffix[]=search_in_db(70,'suffixes',2);
            break;
        case "DS":
            $suffix[]=search_in_db(64,'suffixes',2);
            $suffix[]=search_in_db(65,'suffixes',2);
            $suffix[]=search_in_db(68,'suffixes',2);
            $suffix[]=search_in_db(69,'suffixes',2);
            break;
        case "IS":
            $suffix[]=search_in_db(64,'suffixes',2);
            $suffix[]=search_in_db(65,'suffixes',2);
            $suffix[]=search_in_db(68,'suffixes',2);
            $suffix[]=search_in_db(69,'suffixes',2);
            break;
        case "CaS":
            $suffix[]=search_in_db(64,'suffixes',2);
            $suffix[]=search_in_db(65,'suffixes',2);
            $suffix[]=search_in_db(68,'suffixes',2);
            $suffix[]=search_in_db(69,'suffixes',2);
            break;
    }

    $root_rule="PaFuP";
    $result[0]=$suffix;
    $result[1]=$root_rule;

    return $result;
}

function Endings1F($lico,$chislo,$pada,$tematic,$lemma)
{
    if($pada=="Ā"){$pada="A";}
    if($pada=="P") 
    {
   
        switch ($chislo)
        {
            case "1":
                switch($lico)
                {
                    case "1":
                        $result=search_in_db(1,'endings',3);
                        break;
   
                    case "2":
                        $result=search_in_db(2,'endings',3);
                        break;
                    
                    case "3":
                        $result=search_in_db(3,'endings',3);
                        break;
                }
                break;
   
            case "2":
                switch($lico)
                {
                    case "1":
                            $result=search_in_db(4,'endings',3);
                            break;
        
                    case "2":
                            $result=search_in_db(5,'endings',3);
                            break;
                        
                    case "3":
                            $result=search_in_db(6,'endings',3);
                            break;
                }
                break;
   
            case "3":
                switch($lico)
                {
                        case "1":
                                $result=search_in_db(7,'endings',3);
                                break;
            
                        case "2":
                                $result=search_in_db(8,'endings',3);
                                break;
                            
                        case "3":
                                if($lemma=="PrS3")
                                {
                                    $result=search_in_db(62,'endings',3);
                                }
                                elseif($tematic==1)
                                {
                                    $result=search_in_db(61,'endings',3);
                                }
                                else
                                {
                                    $result=search_in_db(9,'endings',3);
                                }
                                break;
                }
                break;
        }
   
    }
    elseif($pada=="A")
    {
        
        switch ($chislo)
        {
            case "1":
                switch($lico)
                {
                    case "1":
                        if($tematic==1)
                        {
                            $result=search_in_db(63,'endings',3);
                        }
                        else
                        {
                            $result=search_in_db(10,'endings',3);
                        }
                        break;
   
                    case "2":
                        $result=search_in_db(11,'endings',3);
                        break;
                    
                    case "3":
                        $result=search_in_db(12,'endings',3);
                        break;
                }
                break;
   
            case "2":
                switch($lico)
                {
                    case "1":
                            $result=search_in_db(13,'endings',3);
                            break;
        
                    case "2":
                        if($tematic==1)
                        {
                            $result=search_in_db(64,'endings',3);
                        }
                        else
                        {    
                            $result=search_in_db(14,'endings',3);
                        }
                        break;
                        
                    case "3":
                        if($tematic==1)
                        {
                            $result=search_in_db(65,'endings',3);
                        }
                        else
                        {    
                            $result=search_in_db(15,'endings',3);
                        }
                        break;
                }
                break;
   
            case "3":
                switch($lico)
                {
                        case "1":
                                $result=search_in_db(16,'endings',3);
                                break;
            
                        case "2":
                                $result=search_in_db(17,'endings',3);
                                break;
                            
                        case "3":
                            if($tematic==1)
                            {
                                $result=search_in_db(66,'endings',3);
                            }
                            else
                            {    
                                $result=search_in_db(18,'endings',3);
                            }
                            break;
                }
                break;
        }
   
    }
   
    return $result;
}


function Endings2F($lico,$chislo,$pada,$tematic,$lemma,$lastname,$special,$debug)
{
   // → Co: (прист.)-а-FuS(?)-2F
    $last_letter=mb_substr($lastname,-1,1);
    //echo "LAST SUFF: $lastname LAST LETTER:$last_letter<BR>";
    if($debug)
    {
        
            if($lico==1&&$chislo==1&&$pada=="P")
            {
                echo "1 sg. P. => Специальное окончание, если последняя морфема оканчивается на гласную<BR><BR>";
            }
    }

    if($lemma!="O")
    {

        switch($last_letter)
        {
            case "a":$v_ending=1;break;
            case "i":$v_ending=1;break;
            case "ṛ":$v_ending=1;break;
            case "ḷ":$v_ending=1;break;
            case "u":$v_ending=1;break;
            case "ā":$v_ending=1;break;
            case "ī":$v_ending=1;break;
            case "ṝ":$v_ending=1;break;
            case "ḹ":$v_ending=1;break;
            case "ū":$v_ending=1;break;
            default:$v_ending=0;break;
        }

    }
    else
    {
        $v_ending=0;
    }

    if($pada=="P") 
    {

        switch ($chislo)
        {
            case "1":
                switch($lico)
                {
                    case "1":
                        if(!$v_ending)
                        {
                            if($lemma=="O"||$tematic==1)
                            {
                                $result=search_in_db(67,'endings',3);
                            }
                            else
                            {
                                $result=search_in_db(19,'endings',3);
                            }
                        }
                        else
                        {
                            //Parasmaipada
                            $result=search_in_db(37,'endings',3);
                        }
                        break;

                    case "2":
                        $result=search_in_db(20,'endings',3);
                        break;
                    
                    case "3":
                        $result=search_in_db(21,'endings',3);
                        break;
                }
                break;

            case "2":
                switch($lico)
                {
                    case "1":
                            if($lemma=="Ao1A")
                            {
                                $result[0]=search_in_db(107,'endings',3);
                                $result[1]=search_in_db(108,'endings',3);
                            }
                            else
                            {
                                $result=search_in_db(22,'endings',3);
                            }
                            break;
        
                    case "2":
                        if($lemma=="Ao1A")
                        {
                            $result[0]=search_in_db(109,'endings',3);
                            $result[1]=search_in_db(110,'endings',3);
                        }
                        else
                        {
                            $result=search_in_db(23,'endings',3);
                        }
                        break;
                        
                    case "3":
                        if($lemma=="Ao1A")
                        {
                            $result[0]=search_in_db(111,'endings',3);
                            $result[1]=search_in_db(112,'endings',3);
                        }
                        else
                        {
                            $result=search_in_db(24,'endings',3);
                        }
                        break;
                }
                break;

            case "3":
                switch($lico)
                {
                        case "1":
                            if($lemma=="Ao1A")
                            {
                                $result[0]=search_in_db(113,'endings',3);
                                $result[1]=search_in_db(114,'endings',3);
                            }
                            else
                            {
                                $result=search_in_db(25,'endings',3);
                            }
                            break;
            
                        case "2":
                            if($lemma=="Ao1A")
                            {
                                $result[0]=search_in_db(115,'endings',3);
                                $result[1]=search_in_db(116,'endings',3);
                            }
                            else
                            {
                                $result=search_in_db(26,'endings',3);
                            }
                            break;
                            
                        case "3":
                                if($lemma=="O")
                                {
                                    $result=search_in_db(68,'endings',3);
                                }
                                elseif($tematic==1)
                                {
                                    $result=search_in_db(69,'endings',3);
                                }
                                elseif($lemma=="PrS3")
                                {
                                    $result=search_in_db(70,'endings',3);
                                }
                                elseif($lemma=="Ao")
                                {
                                    $result=search_in_db(71,'endings',3);
                                }
                                else
                                {
                                    $result=search_in_db(27,'endings',3);
                                }
                                break;
                }
                break;
        }
    }
    elseif($pada=="A"||$pada=="Ā")
    {
       // echo $lastname;
      
        switch ($chislo)
        {
            case "1":
                switch($lico)
                {
                    case "1":
                        if($lemma=="O"||$lemma=="Pk")
                        {
                            $result=search_in_db(72,'endings',3);
                        }
                        elseif($lemma=="Ao1A")
                        {
                            //echo $special;

                            if($special==1)
                            {
                                $result=search_in_db(119,'endings',3);
                            }

                            if($special==2)
                            {
                                $result[0]=search_in_db(118,'endings',3);
                                $result[1]=search_in_db(119,'endings',3);
                            }

                            if($special==3)
                            {
                                $result[0]=search_in_db(117,'endings',3);
                                $result[1]=search_in_db(118,'endings',3);
                            }

                            if($special==4)
                            {
                                $result=search_in_db(118,'endings',3);
                            }
                         
                        }
                        else
                        {
                            
                            $result=search_in_db(28,'endings',3);
                        }
                        break;

                    case "2":
                        $result=search_in_db(29,'endings',3);
                        break;
                    
                    case "3":
                        $result=search_in_db(30,'endings',3);
                        break;
                }
                break;

            case "2":
                switch($lico)
                {
                    case "1":
                            $result=search_in_db(31,'endings',3);
                            break;
        
                    case "2":
                            if($lemma=="O")
                            {
                                $result=search_in_db(73,'endings',3);
                            }
                            elseif($lemma=="Pk")
                            {
                                $result=search_in_db(74,'endings',3);
                            }
                            elseif($tematic=="1")
                            {
                                $result=search_in_db(75,'endings',3);
                            }
                            else
                            {    
                                $result=search_in_db(32,'endings',3);
                            }
                            break;
                        
                    case "3":
                            if($lemma=="O")
                            {
                                $result=search_in_db(76,'endings',3);
                            }
                            elseif($lemma=="Pk")
                            {
                                $result=search_in_db(77,'endings',3);
                            }
                            elseif($tematic=="1")
                            {
                                $result=search_in_db(78,'endings',3);
                            }
                            else
                            {    
                                $result=search_in_db(33,'endings',3);
                            }
                                break;
                }
                break;

            case "3":
                switch($lico)
                {
                        case "1":
                                $result=search_in_db(34,'endings',3);
                                break;
            
                        case "2":
                                $result=search_in_db(35,'endings',3);
                                break;
                            
                        case "3":
                            if($lemma=="O"||$lemma=="Pk"||$lemma=="Ao1A")
                            {
                                if($lemma=="Ao1A")
                                {
                                    if($lastname=="bhū")
                                    {
                                        $result=search_in_db(120,'endings',3);
                                    }
                                }
                                else
                                {
                                    $result=search_in_db(79,'endings',3);
                                }
                            }
                            elseif($tematic=="1")
                            {
                                $result=search_in_db(80,'endings',3);
                            }
                            else
                            {
                                $result=search_in_db(36,'endings',3);
                                break;
                            }
                }
                break;
        }
    }

    //echo "<BR><BR>RESULT<BR>";
    //print_r($result);
    //echo "<BR><BR>";

    return $result;

}

function EndingsIpF($lico,$chislo,$pada,$tematic,$lemma,$lastname,$is_open_mool,$debug)
{
   // → Co: (прист.)-а-FuS(?)-2F
    $last_letter=mb_substr($lastname,-1,1);

   if($debug)
   {
       
        if($lico==2&&$chislo==1&&$pada=="P")
        {
            echo "2 sg. P. => Специальное окончание, если последняя морфема оканчивается на гласную<BR><BR>";
        }
   }

   $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "ai", "o", "au"];

    if($tematic!=1)
    {

        switch($last_letter)
        {
            case "a":$v_ending=1;break;
            case "i":$v_ending=1;break;
            case "ṛ":$v_ending=1;break;
            case "ḷ":$v_ending=1;break;
            case "u":$v_ending=1;break;
            case "ā":$v_ending=1;break;
            case "ī":$v_ending=1;break;
            case "ṝ":$v_ending=1;break;
            case "ḹ":$v_ending=1;break;
            case "ū":$v_ending=1;break;
            default:$v_ending=0;break;
        }

    }
    else
    {
        $v_ending=0;
    }

    if($pada=="P") 
    {

        switch ($chislo)
        {
            case "1":
                switch($lico)
                {
                    case "1":
                        $result=search_in_db(81,'endings',3);
                        break;

                    case "2":   // OPEN MOOL?
                        if(!$v_ending)
                        {
                            if($tematic==1)
                            {
                                $result=search_in_db(82,'endings',3);
                            }
                            elseif($lemma=="PrS9")
                            {
                               if($is_open_mool==0)
                               {
                                    $result=search_in_db(84,'endings',3);
                               }
                            }
                            else
                            {
                                $result=search_in_db(85,'endings',3);
                            }
                        }
                        else
                        {
                            //V перед суффиксом
                            $result=search_in_db(83,'endings',3);
                        }
                        break;
                    
                    case "3":
                        $result=search_in_db(86,'endings',3);
                        break;
                }
                break;

            case "2":
                switch($lico)
                {
                    case "1":
                            $result=search_in_db(87,'endings',3);
                            break;
        
                    case "2":
                            $result=search_in_db(88,'endings',3);
                            break;
                        
                    case "3":
                            $result=search_in_db(89,'endings',3);
                            break;
                }
                break;

            case "3":
                switch($lico)
                {
                        case "1":
                                $result=search_in_db(90,'endings',3);
                                break;
            
                        case "2":
                                $result=search_in_db(91,'endings',3);
                                break;
                            
                        case "3":
                                if($tematic==1)
                                {
                                    $result=search_in_db(92,'endings',3);
                                }
                                elseif($lemma=="PrS3")
                                {
                                    $result=search_in_db(93,'endings',3);
                                }
                                else
                                {
                                    $result=search_in_db(94,'endings',3);
                                }
                                break;
                }
                break;
        }
    }
    elseif($pada=="A")
    {
        switch ($chislo)
        {
            case "1":
                switch($lico)
                {
                    case "1":
                        $result=search_in_db(95,'endings',3);
                        break;

                    case "2":
                        $result=search_in_db(96,'endings',3);
                        break;
                    
                    case "3":
                        $result=search_in_db(97,'endings',3);
                        break;
                }
                break;

            case "2":
                switch($lico)
                {
                    case "1":
                            $result=search_in_db(98,'endings',3);
                            break;
        
                    case "2":
                            if($tematic=="1")
                            {
                                $result=search_in_db(99,'endings',3);
                            }
                            else
                            {    
                                $result=search_in_db(100,'endings',3);
                            }
                            break;
                        
                    case "3":
                            if($tematic=="1")
                            {
                                $result=search_in_db(101,'endings',3);
                            }
                            else
                            {    
                                $result=search_in_db(102,'endings',3);
                            }
                            break;
                }
                break;

            case "3":
                switch($lico)
                {
                        case "1":
                                $result=search_in_db(103,'endings',3);
                                break;
            
                        case "2":
                                $result=search_in_db(104,'endings',3);
                                break;
                            
                        case "3":
                            if($tematic=="1")
                            {
                                $result=search_in_db(105,'endings',3);
                               
                            }
                            else
                            {
                                $result=search_in_db(106,'endings',3);
                               
                            }
                            break;
                }
                break;
        }
    }

    return $result;
}

//////////////////////////////////////////////////////////////


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
    $verb_info=search_in_db($id,'verbs',1);

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


                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                //CHANGE ROOT
                                $massive_search[$s][$j][0][0]=$dses[0][0];
                            }

                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                $massive_search[$s][$j][]=$dses[1];
                            }

                        }
                        
                        break;
                    case "IS":
                        

                        $verb=$massive_search[0][$j][0];
                        $is=IS($verb,$debug);

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

                            for($s=0;$s<count($massive_search);$s++) 
                            {
                                $massive_search[$s][$j][0][0]=$is[0];
                                $massive_search[$s][$j][0][15]="IS";
                            }
 

                        }

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
                                $massive_search_2[$s][$j][]=search_in_db(79,'endings',3);
                                    
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

                        //echo "CRV <BR>$count_root_variants";
                        //echo $massive_search[1][$j][0][0];
                        //echo "<BR><BR>";


                        for($crv=0;$crv<$count_root_variants;$crv++)
                        {

                           $prs=PrS($massive[$i-1],$verb_info[12],$massive_search[$crv][$j][0][0],$verb_info[1],$verb_info[2],$verb_info[3],$verb_info[4],$pada[$j]);

                           //echo "PRS:<BR>";
                           //print_r($prs);
                           //echo "<BR><BR>";

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