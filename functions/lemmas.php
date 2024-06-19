<?php

function VR($id)
{
    return VerbCache::search_in_db($id,'verbs',1);
}

function FuS()
{
    //FuS: S(2)-sya-
    return VerbCache::search_in_db(3,'suffixes',2);
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
        $result[]=VerbCache::search_in_db(96,'suffixes',2);
        $result[]=VerbCache::search_in_db(98,'suffixes',2);
        $result[]=VerbCache::search_in_db(99,'suffixes',2);
        $result[]=VerbCache::search_in_db(100,'suffixes',2);
        $result[]=VerbCache::search_in_db(101,'suffixes',2);
    }
    elseif($ryad=="L")
    {
        $result[]=VerbCache::search_in_db(97,'suffixes',2);
        $result[]=VerbCache::search_in_db(98,'suffixes',2);
        $result[]=VerbCache::search_in_db(99,'suffixes',2);
        $result[]=VerbCache::search_in_db(100,'suffixes',2);
        $result[]=VerbCache::search_in_db(101,'suffixes',2);
    }
    else
    {
        $result[]=VerbCache::search_in_db(96,'suffixes',2);
        $result[]=VerbCache::search_in_db(97,'suffixes',2);
        $result[]=VerbCache::search_in_db(98,'suffixes',2);
        $result[]=VerbCache::search_in_db(99,'suffixes',2);
        $result[]=VerbCache::search_in_db(100,'suffixes',2);
        $result[]=VerbCache::search_in_db(101,'suffixes',2);
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
        return VerbCache::search_in_db(23,'suffixes',2);
    }

    if($pada=="A")
    {
        return VerbCache::search_in_db(24,'suffixes',2);
    }

    

}

function CaS($verb,$not_chered)
{
    //$verb=VerbCache::search_in_db($id,'verbs',1);
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
                $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
                break;
            }

            if($name=="ghaṭ"||$name=="trap"||$name=="lag"||$name=="viØdh"||$name=="suØp")
            {
                $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
            }

            if($ecc=="ECC")
            {
                $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
            }

            if($ecc=="EC")
            {
                $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
            }
            break;

        case "A2":
            if($name=="chØ̄"||$name=="pØ̄"||$name=="pyā"||$name=="uØ̄"||$name=="vīØ̄"||($name=="śØ̄"&&$omonim==1)||$name=="sØ̄")
            {
                $suffix[]=VerbCache::search_in_db(30,'suffixes',2);
                break;
            }

            if($name=="śīØ̄")
            {
                $suffix[]=VerbCache::search_in_db(30,'suffixes',2);
            }
            elseif($name=="pā")
            {
                $suffix[]=VerbCache::search_in_db(31,'suffixes',2);
            }
            elseif($name=="sphØ̄")
            {
                $suffix[]=VerbCache::search_in_db(32,'suffixes',2);
            }
            elseif($ecc=="EC")
            {
                $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
            }
            else
            {
                $suffix[]=VerbCache::search_in_db(34,'suffixes',2);
            }
            break;
        case "I0":

            if($name=="il")
            {
                $suffix[]=VerbCache::search_in_db(25,'suffixes',2);
                break;
            }

            if(($name=="iṣ"&&$omonim==2)||($name=="cit"&&$omonim==2)||($name=="sīv"&&$omonim==2)||($name=="srīv"&&$omonim==2))
            {
                $suffix[]=VerbCache::search_in_db(25,'suffixes',2);
            }

            if($name=="rī"||$name=="vlī"||$name=="hrī")
            {
                $suffix[]=VerbCache::search_in_db(33,'suffixes',2);
                break;
            }

            if(($name=="kṣi"&&$omonim==1))
            {
                $suffix[]=VerbCache::search_in_db(33,'suffixes',2);
            }

            if($name=="īḍ")
            {
                $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
                break;
            }

            if(($name=="ji"&&$omonim==1)||$name=="krī"||$name=="lī")
            {
                $suffix[]=VerbCache::search_in_db(35,'suffixes',2);
                break;
            }

            if(($name=="kṣi"&&$omonim==2)||($name=="ci"&&$omonim==1)||$name=="smi"||$name=="vī")
            {
                $suffix[]=VerbCache::search_in_db(35,'suffixes',2);
            }

            if($type=="2"||$type=="4"||$ecc=="ECC"||$ecc=="EC")
            {
                $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
            }

            if($ecc=="E")
            {
                $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
            }
            break;
        case "I1":

            if($name=="il")
            {
                $suffix[]=VerbCache::search_in_db(25,'suffixes',2);
                break;
            }

            if(($name=="iṣ"&&$omonim==2)||($name=="cit"&&$omonim==2)||($name=="sīv"&&$omonim==2)||($name=="srīv"&&$omonim==2))
            {
                $suffix[]=VerbCache::search_in_db(25,'suffixes',2);
            }

            if($name=="rī"||$name=="vlī"||$name=="hrī")
            {
                $suffix[]=VerbCache::search_in_db(33,'suffixes',2);
                break;
            }

            if(($name=="kṣi"&&$omonim==1))
            {
                $suffix[]=VerbCache::search_in_db(33,'suffixes',2);
            }

            if($name=="īḍ")
            {
                $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
                break;
            }

            if(($name=="ji"&&$omonim==1)||$name=="krī"||$name=="lī")
            {
                $suffix[]=VerbCache::search_in_db(35,'suffixes',2);
                break;
            }

            if(($name=="kṣi"&&$omonim==2)||($name=="ci"&&$omonim==1)||$name=="smi"||$name=="vī")
            {
                $suffix[]=VerbCache::search_in_db(35,'suffixes',2);
            }

            if($type=="2"||$type=="4"||$ecc=="ECC"||$ecc=="EC")
            {
                $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
            }

            if($ecc=="E")
            {
                $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
            }
            break;

        case "I2":

                if($name=="il")
                {
                    $suffix[]=VerbCache::search_in_db(25,'suffixes',2);
                    break;
                }
    
                if(($name=="iṣ"&&$omonim==2)||($name=="cit"&&$omonim==2)||($name=="sīv"&&$omonim==2)||($name=="srīv"&&$omonim==2))
                {
                    $suffix[]=VerbCache::search_in_db(25,'suffixes',2);
                }
    
                if($name=="rī"||$name=="vlī"||$name=="hrī")
                {
                    $suffix[]=VerbCache::search_in_db(33,'suffixes',2);
                    break;
                }
    
                if(($name=="kṣi"&&$omonim==1))
                {
                    $suffix[]=VerbCache::search_in_db(33,'suffixes',2);
                }
    
                if($name=="īḍ")
                {
                    $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
                    break;
                }
    
                if(($name=="ji"&&$omonim==1)||$name=="krī"||$name=="lī")
                {
                    $suffix[]=VerbCache::search_in_db(35,'suffixes',2);
                    break;
                }
    
                if(($name=="kṣi"&&$omonim==2)||($name=="ci"&&$omonim==1)||$name=="smi"||$name=="vī")
                {
                    $suffix[]=VerbCache::search_in_db(35,'suffixes',2);
                }
    
                if($type=="2"||$type=="4"||$ecc=="ECC"||$ecc=="EC")
                {
                    $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
                }
    
                if($ecc=="E")
                {
                    $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
                }
                break;
        case "U0":
            
            if($name=="duṣ"||($name=="ūh"&&$omonim==2))
                {
                    $suffix[]=VerbCache::search_in_db(25,'suffixes',2);
                    break;
                }
    
                if($name=="puṭ"||$name=="rūṣ")
                {
                    $suffix[]=VerbCache::search_in_db(25,'suffixes',2);
                }
    
                if($name=="pū")
                {
                    $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
                }

                if($name=="ruh")
                {
                    $suffix[]=VerbCache::search_in_db(33,'suffixes',2);
                    $new_root_name="ro";
                    $new_root_element="o";
                }
    
                if($type=="2"||$type=="4"||$ecc=="EC"||$ecc=="ECC")
                {
                    
                    $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
                }
    
                if($ecc=="E")
                {
                    $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
                }
                
                break;

        case "U1":
                if($name=="duṣ"||($name=="ūh"&&$omonim==2))
                {
                    $suffix[]=VerbCache::search_in_db(25,'suffixes',2);
                    break;
                }
    
                if($name=="puṭ"||$name=="rūṣ")
                {
                    $suffix[]=VerbCache::search_in_db(25,'suffixes',2);
                }
    
                if($name=="pū")
                {
                    $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
                }

                if($name=="ruh")
                {
                    $suffix[]=VerbCache::search_in_db(33,'suffixes',2);
                    $new_root_name="ro";
                    $new_root_element="o";
                }
    
                if($type=="2"||$type=="4"||$ecc=="EC"||$ecc=="ECC")
                {
                    $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
                }
    
                if($ecc=="E")
                {
                    $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
                }
                
                break;

        case "U2":
            
            //echo "ECC:".$ecc;

            if($name=="duṣ"||($name=="ūh"&&$omonim==2))
            {
                $suffix[]=VerbCache::search_in_db(25,'suffixes',2);
                break;
            }

            if($name=="puṭ"||$name=="rūṣ")
            {
                $suffix[]=VerbCache::search_in_db(25,'suffixes',2);
            }

            if($name=="pū")
            {
                $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
            }

            if($name=="ruh")
            {
                $suffix[]=VerbCache::search_in_db(33,'suffixes',2);
                $new_root_name="ro";
                $new_root_element="o";
            }

            if($name=="dhū")
            {
                $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
                //$new_root_name="dhūn";
                //$new_root_element="ū";
            }

            if($type=="2"||$type=="4"||$ecc=="EC"||$ecc=="ECC")
            {
                
                $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
            }

            if($ecc=="E")
            {
                $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
            }
            
            break;
        case "R0":
            if($name=="kṣar")
            {
                $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
                break;
            }

            if($name=="ṛ")
            {
                $suffix[]=VerbCache::search_in_db(33,'suffixes',2);
                break;
            }

        

            if(($name=="gṝ"&&$omonim==2)||$name=="pṝ"||$name=="sphṝ")
            {
                $suffix[]=VerbCache::search_in_db(25,'suffixes',2);
            }
            elseif($type=="2"||$type=="4"||$ecc=="EC"||$ecc=="ECC"||$name=="smṛ"||$name=="jṝ")
            {
                $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
            }

            if($ecc=="E"||$name=="tvar"||$name=="svar")
            {
                $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
            }

            

            break;

        case "R1":
            if($name=="kṣar")
            {
                $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
                break;
            }

            if($name=="ṛ")
            {
                $suffix[]=VerbCache::search_in_db(33,'suffixes',2);
                break;
            }

            if(($name=="gṝ"&&$omonim==2)||$name=="pṝ"||$name=="sphṝ")
            {
                $suffix[]=VerbCache::search_in_db(25,'suffixes',2);
            }
            elseif($type=="2"||$type=="4"||$ecc=="EC"||$ecc=="ECC"||$name=="smṛ"||$name=="jṝ")
            {
                $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
            }

            if($ecc=="E"||$name=="tvar"||$name=="svar")
            {
                $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
            }

            break;

        case "R2":
            if($name=="kṣar")
            {
                $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
                break;
            }

            if($name=="ṛ")
            {
                $suffix[]=VerbCache::search_in_db(33,'suffixes',2);
                break;
            }

            if(($name=="gṝ"&&$omonim==2)||$name=="pṝ"||$name=="sphṝ")
            {
                $suffix[]=VerbCache::search_in_db(25,'suffixes',2);
            }
            elseif($type=="2"||$type=="4"||$ecc=="EC"||$ecc=="ECC"||$name=="smṛ"||$name=="jṝ")
            {
                $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
            }

            if($ecc=="E"||$name=="tvar"||$name=="svar")
            {
                $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
            }

            break;

        case "L":
            if($name=="jval"||$name=="dal")
            {
                $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
                break;
            }

            if($ecc=="EC"||$ecc=="ECC"||$name=="cal"||$name=="lal"||$name=="skhal"||$name=="hval")
            {
                $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
            }

            if($ecc=="E")
            {
                $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
            }
            break;

        case "M0":
            if($name=="dm̥̄"||$name=="śrm̥̄"||($name=="śm̥̄"&&$omonim==1)||$name=="gm̥"||$name=="drm̥"||$name=="nm̥"||$name=="rm̥"||$name=="tm̥̄")
            {
                $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
                break;
            }

            if($name=="kram"||$name=="bhrm̥̄"||$name=="vm̥̄")
            {
                $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
            }

            if($ecc=="E")
            {
                $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
            }
            break;

        case "M1":
                if($name=="dm̥̄"||$name=="śrm̥̄"||($name=="śm̥̄"&&$omonim==1)||$name=="gm̥"||$name=="drm̥"||$name=="nm̥"||$name=="rm̥"||$name=="tm̥̄")
                {
                    $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
                    break;
                }
    
                if($name=="kram"||$name=="bhrm̥̄"||$name=="vm̥̄")
                {
                    $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
                }
    
                if($ecc=="E")
                {
                    $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
                }
                break;

        case "M2":
                    if($name=="dm̥̄"||$name=="śrm̥̄"||($name=="śm̥̄"&&$omonim==1)||$name=="gm̥"||$name=="drm̥"||$name=="nm̥"||$name=="rm̥"||$name=="tm̥̄")
                    {
                        $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
                        break;
                    }
        
                    if($name=="kram"||$name=="bhrm̥̄"||$name=="vm̥̄")
                    {
                        $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
                    }
        
                    if($ecc=="E")
                    {
                        $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
                    }
                    break;           
        case "N0":
            if($name=="pn̥"||$name=="jn̥̄"||$name=="dhvn̥̄")
            {
                $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
                break;
            }

            if($name=="grn̥th"||$name=="chn̥d"||$name=="mn̥th"||$name=="mn̥d")
            {
                $suffix[]=VerbCache::search_in_db(25,'suffixes',2);
            }

            if($type=="2"||$type=="4"||$ecc=="EC"||$ecc=="ECC")
            {
                $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
            }

            if($ecc=="E"||$name=="pan"||$name=="phaṇ"||$name=="raṇ")
            {
                $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
            }
            break;

        case "N1":
                if($name=="pn̥"||$name=="jn̥̄"||$name=="dhvn̥̄")
                {
                    $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
                    break;
                }
    
                if($name=="grn̥th"||$name=="chn̥d"||$name=="mn̥th"||$name=="mn̥d")
                {
                    $suffix[]=VerbCache::search_in_db(25,'suffixes',2);
                }
    
                if($type=="2"||$type=="4"||$ecc=="EC"||$ecc=="ECC")
                {
                    $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
                }
    
                if($ecc=="E"||$name=="pan"||$name=="phaṇ"||$name=="raṇ")
                {
                    $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
                }
                break;

        case "N2":
                    if($name=="pn̥"||$name=="jn̥̄"||$name=="dhvn̥̄")
                    {
                        $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
                        break;
                    }
        
                    if($name=="grn̥th"||$name=="chn̥d"||$name=="mn̥th"||$name=="mn̥d")
                    {
                        $suffix[]=VerbCache::search_in_db(25,'suffixes',2);
                    }
        
                    if($type=="2"||$type=="4"||$ecc=="EC"||$ecc=="ECC")
                    {
                        $suffix[]=VerbCache::search_in_db(26,'suffixes',2);
                    }
        
                    if($ecc=="E"||$name=="pan"||$name=="phaṇ"||$name=="raṇ")
                    {
                        $suffix[]=VerbCache::search_in_db(27,'suffixes',2);
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
        $result[1]=VerbCache::search_in_db(28,'suffixes',2);
    }
    else
    {
        $getds=get_desiderative($verb[0],$verb[1],$verb[2],$verb[3],$verb[4],$verb[6],$not_after_root,$pada,$debug);

        $ds=$getds[0];
        $p_before_mool=$getds['p_before_mool'];

        $ds_massive=explode(",",$ds);
        $ds_massive=array_filter(array_unique($ds_massive));
        
        $result[0]=$ds_massive; 
        $result[1]=VerbCache::search_in_db(29,'suffixes',2);
        $result['p_before_mool']=$p_before_mool;
    }
    
    //print_r($result);
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

    $result[0]=$is_massive;  
    $result['p_before_mool']=$is['p_before_mool'];
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
            $result[]=VerbCache::search_in_db(93,'suffixes',2);
            $result[]=VerbCache::search_in_db(94,'suffixes',2);
        }
        elseif($name=="jīØ̄"||($name=="mØ̄"&&$omonim==5)||$name=="uØ̄"||$name=="vīØ̄")
        {
            $result[]=VerbCache::search_in_db(93,'suffixes',2);
        }
        else
        {
            $result[]=VerbCache::search_in_db(94,'suffixes',2);
        }
    }

    if($pada=="A")
    {
        $result[]=VerbCache::search_in_db(95,'suffixes',2);
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

                    $suffix[][0]=VerbCache::search_in_db(86,'suffixes',2);
                    //$root_rule[]="1A";
                    $new_root[]=$name;
                    $debug_str.="Образование Аориста 1 класса: корень исключение √pØ̄ <BR>";

                   
                }
                elseif($name=="bhū")
                {
                    //echo "VERBROOT::::$name";
                    $suffix[][0]=VerbCache::search_in_db(87,'suffixes',2);
                    //print_r($suffix);
                    //$root_rule[]="1A";
                    $new_root[]=$name;
                    $debug_str.="Образование Аориста 1 класса: корень исключение √bhū <BR>";

                   
                }
                elseif($name=="īkṣ")
                {

                    $suffix[][0]=VerbCache::search_in_db(90,'suffixes',2);
                    $new_root[]="ekṣ";
                    $debug_str.="Образование Аориста 1 класса: корень исключение īkṣ <BR>";

                }
                elseif($name=="ṛ")
                {

                    $suffix[][0]=VerbCache::search_in_db(90,'suffixes',2);
                    $new_root[]="ar";
                    $debug_str.="Образование Аориста 1 класса: корень исключение √ṛ <BR>";

                }
                elseif($name=="ṛdh")
                {

                    $suffix[][0]=VerbCache::search_in_db(90,'suffixes',2);
                    $new_root[]="ardh";
                    $debug_str.="Образование Аориста 1 класса: корень исключение ṛdh <BR>";

                }
                else
                {
                    $suffix[][0]=VerbCache::search_in_db(90,'suffixes',2);
                    //$suffix[][0]=array();
                    $new_root[]="$name";
                    $debug_str.="Образование Аориста 1 класса <BR>";
                }


                break;
            
            case "2":
                if($verb_ryad=="M2"||$verb_ryad=="N2"||$name=="ṛ"||$name=="kṛ"||$name=="sṛ"||($name=="gṝ"&&$omonim==2)||$name=="jṝ"||$name=="dṝ")
                {
                    $new_root[]=$name;
                    $suffix[][0]=VerbCache::search_in_db(88,'suffixes',2);
                    $change_later[]=array();
                }
                elseif($name=="gm̥")
                {
                    $new_root[]=$name;
                    $suffix[][0]=VerbCache::search_in_db(88,'suffixes',2);
                    $change_later[]=array();

                    $new_root[]=$name;
                    $suffix[][0]=VerbCache::search_in_db(89,'suffixes',2);
                    $change_later[]=array();
                }
                else
                {
                    $new_root[]=$name;
                    $suffix[][0]=VerbCache::search_in_db(89,'suffixes',2);
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

                        if($aos['p_before_mool'][$iaos])
                        {
                            $aos_pbefore_mool[]=$aos['p_before_mool'][$iaos];
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
                        $suffix[$ie+$count_suffix][0]=VerbCache::search_in_db(71,'suffixes',2);
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
                                        $suffix[$ie+$count_suffix][0]=VerbCache::search_in_db(71,'suffixes',2);
                                        //$root_rule[]="3A";
                                        break;
        
                                    case "A2":
                                        $suffix[$ie+$count_suffix][0]=VerbCache::search_in_db(71,'suffixes',2);
                                        //$root_rule[]="3A";
                                        break;
        
                                    case "L":
                                        $suffix[$ie+$count_suffix][0]=VerbCache::search_in_db(72,'suffixes',2);
                                        //$root_rule[]="3A";
                                        break;
                                }
        
                                if($verb_ryad=="I0"||$verb_ryad=="I1"||$verb_ryad=="I2")
                                {
                                
                                    
                                    if(($name=="ji"&&$omonim=="1")||$name=="jri"||$name=="ḍī"||$name=="bhī"||$name=="rī"||$name=="vī"||$name=="śī"||$name=="śrī"||$name=="hīḍ")
                                    {
                                        $suffix[$ie+$count_suffix][0]=VerbCache::search_in_db(71,'suffixes',2);
                                        //$root_rule[]="3A";
                                    }
                                    else
                                    {
                                        $suffix[$ie+$count_suffix][0]=VerbCache::search_in_db(72,'suffixes',2);
                                        //$root_rule[]="3A";
                                    }
        
                                    if($name=="hīḍ")
                                    {
                                        $suffix[$ie+$count_suffix][0]=VerbCache::search_in_db(71,'suffixes',2);
                                        //$root_rule[]="3A";
                                    }
        
                                }
        
                                if($verb_ryad=="U0"||$verb_ryad=="U1"||$verb_ryad=="U2")
                                {
                                    //echo "HEREHEREHE<BR>";
                                    $suffix[$ie+$count_suffix][0]=VerbCache::search_in_db(71,'suffixes',2);
                                    //$root_rule[]="3A";
                                }
        
                                if($verb_ryad=="R0"||$verb_ryad=="R1"||$verb_ryad=="R2")
                                {
                                    if($p!=""&&$f!="")
                                    {
                                        $suffix[$ie+$count_suffix][0]=VerbCache::search_in_db(72,'suffixes',2);
                                        //$root_rule[]="3A";
                                    }
                                    else
                                    {
                                        $suffix[$ie+$count_suffix][0]=VerbCache::search_in_db(71,'suffixes',2);
                                        //$root_rule[]="3A";
                                    }
        
                                
                                    if($name=="vṛ"||$name=="dṝ"||$name=="stṝ")
                                    {
                                        $suffix[$ie+$count_suffix][1]=VerbCache::search_in_db(72,'suffixes',2);
                                        //$root_rule[]="3A";
                                    }
        
                                }
        
                                if($verb_ryad=="M0"||$verb_ryad=="M1"||$verb_ryad=="M2")
                                {
        
                                    $suffix[$ie+$count_suffix][0]=VerbCache::search_in_db(71,'suffixes',2);//$root_rule[]="3A";
                                }
        
                                if($verb_ryad=="N0"||$verb_ryad=="N1"||$verb_ryad=="N2")
                                {
                                    if(($name=="chn̥d")||$name=="mn̥th"||$name=="rn̥j"||$name=="rn̥dh"||$name=="śvn̥c"||$name=="syn̥d"||$name=="srn̥s"||$name=="svn̥j")
                                    {
                                        $suffix[$ie+$count_suffix][0]=VerbCache::search_in_db(72,'suffixes',2);//$root_rule[]="3A";
                                    }
                                    else
                                    {
                                        $suffix[$ie+$count_suffix][0]=VerbCache::search_in_db(71,'suffixes',2);//$root_rule[]="3A";
                                    }
        
                                    if($name=="mn̥th"||$name=="rn̥j"||$name=="rn̥dh")
                                    {
                                        $suffix[$ie+$count_suffix][1]=VerbCache::search_in_db(72,'suffixes',2);//$root_rule[]="3A";
                                    }
        
                                }
                            }
        
                            if($enew_massive[$ie]=="|a|"||$enew_massive[$ie]=="|u|"||$enew_massive[$ie]=="|ū|")
                            {
                                switch($verb_ryad)
                                {
                                    case "A1":
                                        $suffix[$ie+$count_suffix][0]=VerbCache::search_in_db(71,'suffixes',2);//$root_rule[]="3A";
                                        break;
        
                                    case "A2":
                                        $suffix[$ie+$count_suffix][0]=VerbCache::search_in_db(71,'suffixes',2);//$root_rule[]="3A";
                                        break;
        
                                }
        
                                if($verb_ryad=="R0"||$verb_ryad=="R1"||$verb_ryad=="R2"||$verb_ryad=="N0"||$verb_ryad=="N1"||$verb_ryad=="N2")
                                {
                                    $suffix[$ie+$count_suffix][0]=VerbCache::search_in_db(71,'suffixes',2);//$root_rule[]="3A";
                                }
        
                                if($verb_ryad=="U0"||$verb_ryad=="U1"||$verb_ryad=="U2")
                                {
                                    $suffix[$ie+$count_suffix][0]=VerbCache::search_in_db(72,'suffixes',2);//$root_rule[]="3A";
        
                                
                                    // √PU 1 и 2 МП
                                    if($p!=""&&$f=="")
                                    {
                                        $suffix[$ie+$count_suffix][1]=VerbCache::search_in_db(71,'suffixes',2);//$root_rule[]="3A";
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
                                     
                                    $suffix[][0]=VerbCache::search_in_db(75,'suffixes',2);
                                    
                                    $new_root[]=$name;
                                    $debug_str.="Корень √tvakṣ , для образования Аориста 4 класса МП = 2<BR>";
                            }
                            else
                            {
                            
                                    $suffix[][0]=VerbCache::search_in_db(76,'suffixes',2);
                                    
                                    $new_root[]=$name;
                                    $debug_str.="Образование Аориста 4 класса: для ряда A1 МП = 3<BR>";
                                 
                            }
                            
                        }
    
                        if($verb_ryad=="A2")
                        {
                            $suffix[][0]=VerbCache::search_in_db(76,'suffixes',2);
                            
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 4 класса: для ряда A2 МП = 3<BR>";
                                   
                        }
                    
                    
                        if($verb_ryad=="I0"||$verb_ryad=="I1"||$verb_ryad=="I2")
                        {
                            $suffix[][0]=VerbCache::search_in_db(76,'suffixes',2);
                            
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 4 класса: для рядов I МП = 3<BR>";
    
                        }
    
                        if($verb_ryad=="U0"||$verb_ryad=="U1"||$verb_ryad=="U2")
                        {
                           
                            $suffix[][0]=VerbCache::search_in_db(76,'suffixes',2);
                            
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 4 класса: для рядов U МП = 3<BR>";

                            if($name=="muc"||$name=="yuj"||$name=="yudh")
                            {
                                $suffix[][0]=VerbCache::search_in_db(75,'suffixes',2);
                                $new_root[]=$name;
                                $debug_str.="Корни √muc, √yuj, √yudh , для образования Аориста 4 класса доп. МП = 2<BR>";
                            }
    
                        }
    
    
                        if($verb_ryad=="R0"||$verb_ryad=="R1"||$verb_ryad=="R2")
                        {
                            $suffix[][0]=VerbCache::search_in_db(76,'suffixes',2);
                            
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
                                     
                                    $suffix[][0]=VerbCache::search_in_db(75,'suffixes',2);
                                    
                                    $new_root[]=$name;
                                    $debug_str.="Корень √rm̥ , для образования Аориста 4 класса МП = 2<BR>";
                            }
                            else
                            {
                            
                                    $suffix[][0]=VerbCache::search_in_db(76,'suffixes',2);
                                    
                                    $new_root[]=$name;
                                    $debug_str.="Образование Аориста 4 класса: для ряда M МП = 3<BR>";
                                 
                            }
    
                        }
    
                        if($verb_ryad=="N0"||$verb_ryad=="N1"||$verb_ryad=="N2")
                        {
                            if($verb_name=="grn̥th")
                            {
                                     
                                    $suffix[][0]=VerbCache::search_in_db(74,'suffixes',2);
                                    
                                    $new_root[]=$name;
                                    $debug_str.="Корень √grn̥th , для образования Аориста 4 класса МП = 1<BR>";
                            }
                            else
                            {
                            
                                    $suffix[][0]=VerbCache::search_in_db(76,'suffixes',2);
                                    
                                    $new_root[]=$name;
                                    $debug_str.="Образование Аориста 4 класса: для ряда N МП = 3<BR>";
                                 
                            }
    
                        }
                        
                        break;
                    case "A":
                        
                        if($verb_ryad=="A1")
                        {
                            
                            $suffix[][0]=VerbCache::search_in_db(75,'suffixes',2);
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 4 класса: для Atmanepada, ряда A1 МП = 2<BR>";
 
                        }
    
                        if($verb_ryad=="A2")
                        {
                            $suffix[][0]=VerbCache::search_in_db(75,'suffixes',2);
                            $new_root[]=$name;
                            
                            $suffix[][0]=VerbCache::search_in_db(76,'suffixes',2);
                            $new_root[]=$name;

                            $debug_str.="Образование Аориста 4 класса: для Atmanepada, ряда A2 МП = 2 & 3<BR>";
                                   
                        }
                    
                    
                        if($verb_ryad=="I0"||$verb_ryad=="I1"||$verb_ryad=="I2")
                        {
                           if($f=='')
                           {
                                $suffix[][0]=VerbCache::search_in_db(75,'suffixes',2);
                                $new_root[]=$name;
                                $debug_str.="Образование Аориста 4 класса: для Atmanepada, открытый корень, ряда I МП = 2<BR>";
                           }
                           else
                           {
                                $suffix[][0]=VerbCache::search_in_db(74,'suffixes',2);
                                $new_root[]=$name;
                                $debug_str.="Образование Аориста 4 класса: для Atmanepada, закрытый корень, ряда I МП = 1<BR>";
                           }
                        }
    
                        if($verb_ryad=="U0"||$verb_ryad=="U1"||$verb_ryad=="U2")
                        {
                           
                            $suffix[][0]=VerbCache::search_in_db(74,'suffixes',2);
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 4 класса: для Atmanepada, закрытый корень, ряда U МП = 1<BR>";

                            if($f=='')
                            {
                                 $suffix[][0]=VerbCache::search_in_db(75,'suffixes',2);
                                 $new_root[]=$name;
                                 $debug_str.="Образование Аориста 4 класса: для Atmanepada, открытый корень, ряда U доп. МП = 2<BR>";
                            }

                        }
    
    
                        if($verb_ryad=="R0"||$verb_ryad=="R1"||$verb_ryad=="R2")
                        {
                            $suffix[][0]=VerbCache::search_in_db(74,'suffixes',2);
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 4 класса: для Atmanepada ряда R МП = 1<BR>";
    
                        }
    
                        if($verb_ryad=="L")
                        {
                            $suffix[][0]=VerbCache::search_in_db(74,'suffixes',2);
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 4 класса: для Atmanepada ряда L МП = 1<BR>";
                        }
                        
    
                        if($verb_ryad=="M0"||$verb_ryad=="M1"||$verb_ryad=="M2")
                        {
                            $suffix[][0]=VerbCache::search_in_db(75,'suffixes',2);
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 4 класса: для Atmanepada ряда M МП = 2<BR>";
    
                        }
    
                        if($verb_ryad=="N0"||$verb_ryad=="N1"||$verb_ryad=="N2")
                        {
                            $suffix[][0]=VerbCache::search_in_db(75,'suffixes',2);
                            $new_root[]=$name;
                            
                            $suffix[][0]=VerbCache::search_in_db(74,'suffixes',2);
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
                    $suffix[][0]=VerbCache::search_in_db(85,'suffixes',2);
                    //$root_rule[]="5A";
                    $new_root[]="okṣ";
                    $debug_str.="Образование Аориста 5 класса: корень исключение √uøkṣ <BR>";
                }
                elseif($name=="dØ̄"&&$omonim==1)
                {
                   
                    $suffix[][0]=VerbCache::search_in_db(85,'suffixes',2);
                    //$root_rule[]="5A";
                    $new_root[]="dad";
                    $debug_str.="Образование Аориста 5 класса: корень исключение √dø̄ 1  <BR>";
                }
                else
                {


                    if($verb_ryad=="A1")
                    {
                        $suffix[][0]=VerbCache::search_in_db(78,'suffixes',2);
                        //$root_rule[]="5A";
                        $new_root[]=$name;
                        $debug_str.="Образование Аориста 5 класса: для ряда A1 МП = 2<BR>";
                             
                            if(mb_strlen($f)==1&&$f=="C")
                            {
                                 
                                $suffix[][0]=VerbCache::search_in_db(79,'suffixes',2);
                                $root_rule[]="5A";$new_root[]=$name;
                                $debug_str.="Корень типа √PA1C , для образования Аориста 5 класса доп. МП = 3";
                            }
                        
                    }

                    if($verb_ryad=="A2")
                    {
                        $suffix[][0]=VerbCache::search_in_db(80,'suffixes',2);
                        //$root_rule[]="5A";
                        $new_root[]=$name;
                        $debug_str.="Образование Аориста 5 класса: для ряда A2 МП = 3 [A20↦I]<BR>";
                               
                    }
                
                
                    if($verb_ryad=="I0"||$verb_ryad=="I1"||$verb_ryad=="I2")
                    {
                        $suffix[][0]=VerbCache::search_in_db(78,'suffixes',2);
                        //$root_rule[]="5A";
                        $new_root[]=$name;
                        $debug_str.="Образование Аориста 5 класса: для ряда I МП = 2 <BR>";

                        if($name=="śri"||$name=="nī")
                        {
                            $suffix[][0]=VerbCache::search_in_db(79,'suffixes',2);
                            //$root_rule[]="5A";
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 5 класса: для ряда I для корней √śri, √nī дополнительно МП = 3 <BR>";
                        }

                    }

                    if($verb_ryad=="U0"||$verb_ryad=="U1"||$verb_ryad=="U2")
                    {
                        $suffix[][0]=VerbCache::search_in_db(78,'suffixes',2);
                        //$root_rule[]="5A";
                        $new_root[]=$name;
                        $debug_str.="Образование Аориста 5 класса: для ряда U МП = 2 <BR>";

                        if($p!=""&&$f=="")
                        {
                            $suffix[][0]=VerbCache::search_in_db(79,'suffixes',2);
                            //$root_rule[]="5A";
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 5 класса: для ряда U для корней √PU  дополнительно МП = 3 <BR>";
                        }

                        if($name=="dū"||$name=="dhu"||$name=="nu"||$name=="nū"||$name=="nud"||$name=="muc")
                        {
                            $suffix[][0]=VerbCache::search_in_db(77,'suffixes',2);
                            //$root_rule[]="5A";
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 5 класса: для ряда U для корней √dū, √dhu, √nu 1, √nū, √nud, √muc дополнительно МП = 1 <BR>";
                        }

                    }


                    if($verb_ryad=="R0"||$verb_ryad=="R1"||$verb_ryad=="R2")
                    {
                        $suffix[][0]=VerbCache::search_in_db(78,'suffixes',2);
                      //  $root_rule[]="5A";
                        $new_root[]=$name;
                        $debug_str.="Образование Аориста 5 класса: для ряда R МП = 2 <BR>";

                        if(($p!=""&&$f=="")||$name=="mṛj"||$name=="bhṛjj")
                        {
                            $suffix[][0]=VerbCache::search_in_db(79,'suffixes',2);
                           // $root_rule[]="5A";
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 5 класса: для ряда R для корней √PR и √mṛj, √bhṛjj  дополнительно МП = 3 <BR>";
                        }

                        if($name=="pṝ")
                        {
                            $suffix[][0]=VerbCache::search_in_db(77,'suffixes',2);
                          //  $root_rule[]="5A";$
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 5 класса: для ряда R для корней √pṝ дополнительно МП = 1 <BR>";
                        }

                    }

                    if($verb_ryad=="L")
                    {
                        $suffix[][0]=VerbCache::search_in_db(78,'suffixes',2);
                       // $root_rule[]="5A";
                        $new_root[]=$name;
                        $debug_str.="Образование Аориста 5 класса: для ряда L МП = 2 <BR>";

                        if($p!=""&&$f=="")
                        {
                            $suffix[][0]=VerbCache::search_in_db(79,'suffixes',2);
                         //   $root_rule[]="5A";
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 5 класса: для ряда L для корней √PL дополнительно МП = 3 <BR>";
                        }

                    }
                    

                    if($verb_ryad=="M0"||$verb_ryad=="M1"||$verb_ryad=="M2")
                    {
                        $suffix[][0]=VerbCache::search_in_db(78,'suffixes',2);
                       // $root_rule[]="5A";
                        $new_root[]=$name;
                        $debug_str.="Образование Аориста 5 класса: для ряда M МП = 2 <BR>";

                        if($name=="gm̥")
                        {
                            $suffix[][0]=VerbCache::search_in_db(77,'suffixes',2);
                          //  $root_rule[]="5A";
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 5 класса: для ряда M для корней √gm̥ дополнительно МП = 1 <BR>";
                        }

                    }

                    if($verb_ryad=="N0"||$verb_ryad=="N1"||$verb_ryad=="N2")
                    {
                        $suffix[][0]=VerbCache::search_in_db(78,'suffixes',2);
                       // $root_rule[]="5A";
                        $new_root[]=$name;
                        $debug_str.="Образование Аориста 5 класса: для ряда N МП = 2 <BR>";

                        if($p!=""&&$f=="")
                        {
                            $suffix[][0]=VerbCache::search_in_db(79,'suffixes',2);
                           // $root_rule[]="5A";
                            $new_root[]=$name;
                            $debug_str.="Образование Аориста 5 класса: для ряда N для корней √PN дополнительно МП = 3 <BR>";
                        }

                    }
                    
                    break;

                }
                break;
            case "6":
                //2МП – корни рядов M, √dṝ | 3МП – остальные.

                //Перед окончаниями P. 2, 3 sg. суффикс принимает вид (2/3)-s/iṣ/- и вставная -ī- обязательна (например, ⟨a-ghār(3)-s/iṣ/(2)-ī-t⟩ = aghārṣīt).
                if($verb_ryad=="M0"||$verb_ryad=="M1"||$verb_ryad=="M2"||$verb_name=="dṝ")
                {
                    $suffix[][0]=VerbCache::search_in_db(81,'suffixes',2);
                    $new_root[]=$name;
                    $debug_str.="Образование Аориста 6 класса: корень рядов М или dṝ МП=2<BR>";
                }
                else
                {
                    $suffix[][0]=VerbCache::search_in_db(82,'suffixes',2);
                    $new_root[]=$name;
                    $debug_str.="Образование Аориста 6 класса: МП=3<BR>";
                }
                break;
            case "7":
                $suffix[][0]=VerbCache::search_in_db(73,'suffixes',2);
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
    $result['p_before_mool']=$aos_pbefore_mool;
    
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
    $result['p_before_mool']=$pe_always['p_before_mool'];
    
   
   if($debug)
   {
    print_r($comments);
    echo "<HR>";
   }
   //echo "<BR>";
   // print_r($result);
   // echo "<BR>";
    return $result;
}

function PeOS($pada)
{
    if($pada=="P")
    {
        return VerbCache::search_in_db(60,'suffixes',2);
    }

    if($pada=="A")
    {
        return VerbCache::search_in_db(61,'suffixes',2);
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

    return VerbCache::search_in_db(52,'suffixes',2);
}

function AoSbS()
{

    return VerbCache::search_in_db(52,'suffixes',2);
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
    $verb=VerbCache::search_in_db($id,'verbs',1);

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
                            $result[]=VerbCache::search_in_db(38,'endings',3);
                            $query[]=1;
                        }
                        else
                        {

                            if($pa1c)
                            {
                                $result[]=VerbCache::search_in_db(40,'endings',3); 
                                $query[]=3;
                            }

                          

                            if($is_open_mool)
                            {
                               // I, U, R, L, M, N
                                if($ryad=="I0"||$ryad=="I1"||$ryad=="I2"||$ryad=="U0"||$ryad=="U1"||$ryad=="U2"||$ryad=="R0"||$ryad=="R1"||$ryad=="R2"||$ryad=="L"||$ryad=="M0"||$ryad=="M1"||$ryad=="M2"||$ryad=="N0"||$ryad=="N1"||$ryad=="N2")
                                {
                                    $result[]=VerbCache::search_in_db(40,'endings',3);
                                    $query[]=3;
                                }
                            }

                            $result[]=VerbCache::search_in_db(39,'endings',3);
                            $query[]=2;

                        }



                        break;

                    case "2":
                        $result[]=VerbCache::search_in_db(41,'endings',3);
                        $query[]=2;
                        break;
                    
                    case "3":

                        if($ryad=="A2")
                        {
                            $result[]=VerbCache::search_in_db(42,'endings',3);
                            $query[]=1;
                        }
                        else
                        {

                            if($pa1c)
                            {
                                $result[]=VerbCache::search_in_db(44,'endings',3); 
                                $query[]=3;
                            }
                            elseif($is_open_mool)
                            {
                               // I, U, R, L, M, N
                                if($ryad=="I0"||$ryad=="I1"||$ryad=="I2"||$ryad=="U0"||$ryad=="U1"||$ryad=="U2"||$ryad=="R0"||$ryad=="R1"||$ryad=="R2"||$ryad=="L"||$ryad=="M0"||$ryad=="M1"||$ryad=="M2"||$ryad=="N0"||$ryad=="N1"||$ryad=="N2")
                                {
                                    $result[]=VerbCache::search_in_db(44,'endings',3);
                                    $query[]=3;
                                }
                            }
                            else
                            {
                                $result[]=VerbCache::search_in_db(43,'endings',3);
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
                            $result[]=VerbCache::search_in_db(45,'endings',3);
                            $query[]=1;
                            break;
        
                    case "2":
                            $result[]=VerbCache::search_in_db(46,'endings',3);
                            $query[]=1;
                            break;
                        
                    case "3":
                            $result[]=VerbCache::search_in_db(47,'endings',3);
                            $query[]=1;
                            break;
                }
                break;

            case "3":
                switch($lico)
                {
                        case "1":
                                $result[]=VerbCache::search_in_db(48,'endings',3);
                                $query[]=1;
                                break;
            
                        case "2":
                                $result[]=VerbCache::search_in_db(49,'endings',3);
                                $query[]=1;
                                break;
                            
                        case "3":
                                $result[]=VerbCache::search_in_db(50,'endings',3);
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
                        $result[]=VerbCache::search_in_db(51,'endings',3);
                        $query[]=1;
                        break;

                    case "2":
                        if($ryad=="M0"||$ryad=="M1"||$ryad=="M2"||$ryad=="N0"||$ryad=="N1"||$ryad=="N2")
                        {
                            if($p!=""&&$is_open_mool)
                            {
                                $result[]=VerbCache::search_in_db(53,'endings',3);
                                $query[]=1;
                            }
                        }
                        else
                        {
                            $result[]=VerbCache::search_in_db(52,'endings',3);
                            $query[]=1;
                        }

                        break;
                    
                    case "3":
                        $result[]=VerbCache::search_in_db(54,'endings',3);
                        $query[]=1;
                        break;
                }
                break;

            case "2":
                switch($lico)
                {
                    case "1":
                            $result[]=VerbCache::search_in_db(55,'endings',3);
                            $query[]=1;
                            break;
        
                    case "2":
                            $result[]=VerbCache::search_in_db(56,'endings',3);
                            $query[]=1;
                            break;
                        
                    case "3":
                            $result[]=VerbCache::search_in_db(57,'endings',3);
                            $query[]=1;
                            break;
                }
                break;

            case "3":
                switch($lico)
                {
                        case "1":
                                $result[]=VerbCache::search_in_db(58,'endings',3);
                                $query[]=1;
                                break;
            
                        case "2":
                                $result[]=VerbCache::search_in_db(59,'endings',3);
                                $query[]=1;
                                break;
                            
                        case "3":
                                $result[]=VerbCache::search_in_db(60,'endings',3);
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
    
    $verb=VerbCache::search_in_db(1,'verbs',1);

    $name=$verb[0];
    $omonim=$verb[1];
    $type=$verb[2];
    $element=$verb[11];
    $ryad=$verb[4];
    $setnost=$verb[6];

    $pe=get_perfect($name,$omonim,$type,$element,$ryad,'',0)[1];

    $result[]=array("ām ".$pe,'',$type,$element,$ryad,$setnost,0,1,'','',$element);

    //////////////////////////////////////////////

    
    $verb=VerbCache::search_in_db(553,'verbs',1);

    $name=$verb[0];
    $omonim=$verb[1];
    $type=$verb[2];
    $element=$verb[11];
    $ryad=$verb[4];
    $setnost=$verb[6];


    $pe=get_perfect($name,$omonim,$type,$element,$ryad,'',0)[1];

    $result[]=array("ām ".$pe,'',$type,$element,$ryad,$setnost,0,1,'','',$element);
  
    //////////////////////////////////////////////

    
    $verb=VerbCache::search_in_db(594,'verbs',1);

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
     return VerbCache::search_in_db(21,'suffixes',2);
}

function PaPrP()
{
    return VerbCache::search_in_db(39,'suffixes',2);
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
       
        $suffix[]=VerbCache::search_in_db(22,'suffixes',2);
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
                $suffix[]=VerbCache::search_in_db(22,'suffixes',2);
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
                        $suffix[]=VerbCache::search_in_db(41,'suffixes',2);


                        if($name=="tṛh"){$new_root[]=$name;$suffix[]=VerbCache::search_in_db(41,'suffixes',2);}
                        if($name=="piṣ"){$new_root[]=$name;$suffix[]=VerbCache::search_in_db(41,'suffixes',2);}
                        if($name=="muc"){$new_root[]=$name;$suffix[]=VerbCache::search_in_db(41,'suffixes',2);}

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
                    $suffix[]=VerbCache::search_in_db(22,'suffixes',2);
                    $new_root[]=$name;
                    $root_rule[]=1;
                    $p_before_mool[]='';
                    $debug_str.="О.н.в. 1 класса , образование S(2)-a-";
                    break;

                case "2":
                    $suffix[]="";
                    $new_root[]=$name;
                    $root_rule[]=2;
                    $p_before_mool[]='';
                    $debug_str.="О.н.в. 2 класса , образование VR- (присоединение окончания к корню)";
                    break;
                
                case "3": //УДВОЕНИЕ
                    $suffix[]="";

                    $onv3=get_onv3($name,$omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,0)[0];

                    $p_before_mool[]=get_onv3($name,$omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,0)['p_before_mool'][0];

                    //echo "<BR>ONV3: ";
                    //print($p_before_mool);
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
                    $suffix[]=VerbCache::search_in_db(40,'suffixes',2);
                    $root_rule[]=4;
                    $p_before_mool[]='';
                    $debug_str.="О.н.в. 4 класса , образование VR(1)-ya-";
                    break;

                case "5":  //Перед окончаниями на -m, -v, требующих 1МП, «u» суффикса перестает отображаться на письме, если VR открытый рядов I, U, R (например, √kṛ → ⟨kṛ(1)-n/u/(1)-mahe⟩ = kṛṇmahe).
                    
                    if($name=="śru"){$new_root[]="śṛ";}
                    else
                    {
                        $new_root[]=$name;
                    }
                    
                    $suffix[]=VerbCache::search_in_db(46,'suffixes',2);
                    $root_rule[]=5;
                    $p_before_mool[]='';
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
                    $p_before_mool[]='';
                    $suffix[]=VerbCache::search_in_db(41,'suffixes',2);


                    if($name=="tṛh"){$new_root[]=$name;$suffix[]=VerbCache::search_in_db(41,'suffixes',2);}
                    if($name=="piṣ"){$new_root[]=$name;$suffix[]=VerbCache::search_in_db(41,'suffixes',2);}
                    if($name=="muc"){$new_root[]=$name;$suffix[]=VerbCache::search_in_db(41,'suffixes',2);}

                    break;

                case "7":

                    
                   // $new_root[]=$name;
                    $suffix[]=VerbCache::search_in_db(47,'suffixes',2);
                    $root_rule[]=7;
                    $p_before_mool[]='';
                    break;

                case "8":  //Исключения для √kṛ:    Если (1)-u-, то √kṛ → kur(1)-u(1)-    Если (1)-о-, то √kṛ → kar(2)-o(2)-   Em, Ev, Ey в kur(1)-u- → kur(1)-/u/- (если первая буква окончания m, v, y, то суффикс "u" не пишется)
                  
                    $new_root[]=$name;
                    $suffix[]=VerbCache::search_in_db(48,'suffixes',2);
                    $root_rule[]=8;
                    $p_before_mool[]='';
                    break;

                case "9":  //√jñā → jā(1)-nø̄-
                    if($name=="jñā"){$new_root[]="jā";}
                    else
                    {
                        $new_root[]=$name;
                    }
                    $suffix[]=VerbCache::search_in_db(49,'suffixes',2);
                    $root_rule[]=9;
                    $p_before_mool[]='';
                    break;

                
            }
        }

        

        
    }

    $result[0]=$new_root;
    $result[1]=$suffix;
    $result[2]=$root_rule;
    $result['debug']=$debug_str;
    $result['p_before_mool']=$p_before_mool;

  

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
        return VerbCache::search_in_db(50,'suffixes',2);
    }
    else
    {
        return VerbCache::search_in_db(51,'suffixes',2);
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
        $suffix[]=VerbCache::search_in_db(121,'endings',3);
        $new_root[]="pūr";
        $root_rule[]="AoP";
    }
    elseif($ryad=="A1"&&$p!=''&&$ec=="EC")
    {
       $suffix[]=VerbCache::search_in_db(121,'endings',3);
       $new_root[]=$name;
       $root_rule[]="AoP";
    }
    elseif($ryad!="A1"&&$is_open_mool==1)
    {
       $suffix[]=VerbCache::search_in_db(121,'endings',3);
       $new_root[]=$name;
       $root_rule[]="AoP";
    }
    else
    {
        $suffix[]=VerbCache::search_in_db(122,'endings',3);
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

    return VerbCache::search_in_db(52,'suffixes',2);
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
        $suffix=VerbCache::search_in_db(53,'suffixes',2);
    }

    if($pada=="A")
    {
        if($last_letter=="a"||$last_letter=="ā")
        {
            $suffix=VerbCache::search_in_db(54,'suffixes',2);
        }
        else
        {
            $suffix=VerbCache::search_in_db(55,'suffixes',2);
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
        $suffix[]=VerbCache::search_in_db(56,'suffixes',2);
        $new_root[]="hūr";
        $root_rule[]="PaPePS";
    }

    if($name=="pi")
    {
        $suffix[]=VerbCache::search_in_db(56,'suffixes',2);
        $new_root[]="pī";
        $root_rule[]="PaPePS";
        $stop=1;
    }
    
    if($name=="dīv")
    {
        $suffix[]=VerbCache::search_in_db(56,'suffixes',2);
        $new_root[]="dyū";
        $root_rule[]="PaPePS";
        $stop=1;
    }

    if($name=="dØ̄"&&$omonim==1)
    {
        $suffix[]=VerbCache::search_in_db(58,'suffixes',2);
        $new_root[]="dat";
        $root_rule[]="PaPePS";
    }

    if($name=="vij"||($name=="vid"&&$omonim==2)||$name=="nud"||$name=="ud"||($name=="kṣi"&&$omonim==2)||$name=="hrī"||$name=="dhū"||$name=="pū"||$name=="sū")
    {
        $suffix[]=VerbCache::search_in_db(56,'suffixes',2);
        $new_root[]=$name;
        $root_rule[]="PaPePS";
    }

    if($name=="ghrā"||$name=="stīØ̄"||$name=="vā"||$name=="jīØ̄"||$name=="pyā"||$name=="mlā")
    {
        $suffix[]=VerbCache::search_in_db(57,'suffixes',2);
        $new_root[]=$name;
        $root_rule[]="PaPePS";
    }

    if(($ryad=="R2"&&$is_open_mool)||$name=="lag"||$name=="vṛØśc"||$name=="n̥c"||$name=="majj"||$name=="ruj"||($name=="bhuj"&&$omonim==1)||$name=="bhn̥j"||$name=="ad"
    ||$name=="chad"||$name=="had"||$name=="pad"||$name=="śad"||$name=="sad"||$name=="hlād"||$name=="chid"||$name=="khid"||$name=="klid"||$name=="kṣvid"||$name=="mid"||$name=="svid"
    ||$name=="skn̥d" ||$name=="syn̥d" ||$name=="ṛd" ||$name=="chṛd" ||$name=="tṛd" ||$name=="kṣud" ||$name=="tud" ||$name=="ḍī" ||$name=="lī"
    ||$name=="vlī"||$name=="blī"||$name=="du"||$name=="dū"||$name=="lū"||$name=="śū")
    {
        $suffix[]=VerbCache::search_in_db(56,'suffixes',2);
        $new_root[]=$name;
        $root_rule[]="PaPePS";
       
    }
    elseif($name=="glā"||($name=="drā"&&$omonim==1)||($name=="drā"&&$omonim==2)||($name=="hØ̄"&&$omonim==1)||($name=="hØ̄"&&$omonim==2))
    {
        /*
        (2)-na- присоединяется к некоторым VR, а именно:
        •	некоторые открытые корни ряда А2, а именно: √glā, √drā 1, 2, √ghrā (вар.), √hø̄ 1, 2, √stīø̄ (вар.), √vā (вар.), √jīø̄ (вар.), √pyā (вар.), √mlā (вар.);
        */
        $suffix[]=VerbCache::search_in_db(57,'suffixes',2);
        $new_root[]=$name;
        $root_rule[]="PaPePS";
    }
    elseif($name=="tṝ"||($name=="śṝ"&&$omonim==1)||($name=="śṝ"&&$omonim==2))
    {
        $suffix[]=VerbCache::search_in_db(58,'suffixes',2);
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
        $suffix[]=VerbCache::search_in_db(58,'suffixes',2);

      
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
   // return VerbCache::search_in_db(59,'suffixes',2);
   if($pada=="P")
    {
        return VerbCache::search_in_db(62,'suffixes',2);
    }

    if($pada=="A")
    {
        return VerbCache::search_in_db(63,'suffixes',2);
    }
}

function PaPeAS($pada)
{
    //echo $pada;
    
    if($pada=="P")
    {
        $suffix[]=VerbCache::search_in_db(62,'suffixes',2);
    }

    if($pada=="A")
    {
        $suffix[]=VerbCache::search_in_db(63,'suffixes',2);
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
            $suffix[]=VerbCache::search_in_db(64,'suffixes',2);
            $suffix[]=VerbCache::search_in_db(65,'suffixes',2);
            $suffix[]=VerbCache::search_in_db(66,'suffixes',2);
            $suffix[]=VerbCache::search_in_db(67,'suffixes',2);
            $suffix[]=VerbCache::search_in_db(68,'suffixes',2);
            $suffix[]=VerbCache::search_in_db(69,'suffixes',2);
            $suffix[]=VerbCache::search_in_db(70,'suffixes',2);
            break;
        case "DS":
            $suffix[]=VerbCache::search_in_db(64,'suffixes',2);
            $suffix[]=VerbCache::search_in_db(65,'suffixes',2);
            $suffix[]=VerbCache::search_in_db(68,'suffixes',2);
            $suffix[]=VerbCache::search_in_db(69,'suffixes',2);
            break;
        case "IS":
            $suffix[]=VerbCache::search_in_db(64,'suffixes',2);
            $suffix[]=VerbCache::search_in_db(65,'suffixes',2);
            $suffix[]=VerbCache::search_in_db(68,'suffixes',2);
            $suffix[]=VerbCache::search_in_db(69,'suffixes',2);
            break;
        case "CaS":
            $suffix[]=VerbCache::search_in_db(64,'suffixes',2);
            $suffix[]=VerbCache::search_in_db(65,'suffixes',2);
            $suffix[]=VerbCache::search_in_db(68,'suffixes',2);
            $suffix[]=VerbCache::search_in_db(69,'suffixes',2);
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
                        $result=VerbCache::search_in_db(1,'endings',3);
                        break;
   
                    case "2":
                        $result=VerbCache::search_in_db(2,'endings',3);
                        break;
                    
                    case "3":
                        $result=VerbCache::search_in_db(3,'endings',3);
                        break;
                }
                break;
   
            case "2":
                switch($lico)
                {
                    case "1":
                            $result=VerbCache::search_in_db(4,'endings',3);
                            break;
        
                    case "2":
                            $result=VerbCache::search_in_db(5,'endings',3);
                            break;
                        
                    case "3":
                            $result=VerbCache::search_in_db(6,'endings',3);
                            break;
                }
                break;
   
            case "3":
                switch($lico)
                {
                        case "1":
                                $result=VerbCache::search_in_db(7,'endings',3);
                                break;
            
                        case "2":
                                $result=VerbCache::search_in_db(8,'endings',3);
                                break;
                            
                        case "3":
                                if($lemma=="PrS3")
                                {
                                    $result=VerbCache::search_in_db(62,'endings',3);
                                }
                                elseif($tematic==1)
                                {
                                    $result=VerbCache::search_in_db(61,'endings',3);
                                }
                                else
                                {
                                    $result=VerbCache::search_in_db(9,'endings',3);
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
                            $result=VerbCache::search_in_db(63,'endings',3);
                        }
                        else
                        {
                            $result=VerbCache::search_in_db(10,'endings',3);
                        }
                        break;
   
                    case "2":
                        $result=VerbCache::search_in_db(11,'endings',3);
                        break;
                    
                    case "3":
                        $result=VerbCache::search_in_db(12,'endings',3);
                        break;
                }
                break;
   
            case "2":
                switch($lico)
                {
                    case "1":
                            $result=VerbCache::search_in_db(13,'endings',3);
                            break;
        
                    case "2":
                        if($tematic==1)
                        {
                            $result=VerbCache::search_in_db(64,'endings',3);
                        }
                        else
                        {    
                            $result=VerbCache::search_in_db(14,'endings',3);
                        }
                        break;
                        
                    case "3":
                        if($tematic==1)
                        {
                            $result=VerbCache::search_in_db(65,'endings',3);
                        }
                        else
                        {    
                            $result=VerbCache::search_in_db(15,'endings',3);
                        }
                        break;
                }
                break;
   
            case "3":
                switch($lico)
                {
                        case "1":
                                $result=VerbCache::search_in_db(16,'endings',3);
                                break;
            
                        case "2":
                                $result=VerbCache::search_in_db(17,'endings',3);
                                break;
                            
                        case "3":
                            if($tematic==1)
                            {
                                $result=VerbCache::search_in_db(66,'endings',3);
                            }
                            else
                            {    
                                $result=VerbCache::search_in_db(18,'endings',3);
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
                                $result=VerbCache::search_in_db(67,'endings',3);
                            }
                            else
                            {
                                $result=VerbCache::search_in_db(19,'endings',3);
                            }
                        }
                        else
                        {
                            //Parasmaipada
                            $result=VerbCache::search_in_db(37,'endings',3);
                        }
                        break;

                    case "2":
                        $result=VerbCache::search_in_db(20,'endings',3);
                        break;
                    
                    case "3":
                        $result=VerbCache::search_in_db(21,'endings',3);
                        break;
                }
                break;

            case "2":
                switch($lico)
                {
                    case "1":
                            if($lemma=="Ao1A")
                            {
                                $result[0]=VerbCache::search_in_db(107,'endings',3);
                                $result[1]=VerbCache::search_in_db(108,'endings',3);
                            }
                            else
                            {
                                $result=VerbCache::search_in_db(22,'endings',3);
                            }
                            break;
        
                    case "2":
                        if($lemma=="Ao1A")
                        {
                            $result[0]=VerbCache::search_in_db(109,'endings',3);
                            $result[1]=VerbCache::search_in_db(110,'endings',3);
                        }
                        else
                        {
                            $result=VerbCache::search_in_db(23,'endings',3);
                        }
                        break;
                        
                    case "3":
                        if($lemma=="Ao1A")
                        {
                            $result[0]=VerbCache::search_in_db(111,'endings',3);
                            $result[1]=VerbCache::search_in_db(112,'endings',3);
                        }
                        else
                        {
                            $result=VerbCache::search_in_db(24,'endings',3);
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
                                $result[0]=VerbCache::search_in_db(113,'endings',3);
                                $result[1]=VerbCache::search_in_db(114,'endings',3);
                            }
                            else
                            {
                                $result=VerbCache::search_in_db(25,'endings',3);
                            }
                            break;
            
                        case "2":
                            if($lemma=="Ao1A")
                            {
                                $result[0]=VerbCache::search_in_db(115,'endings',3);
                                $result[1]=VerbCache::search_in_db(116,'endings',3);
                            }
                            else
                            {
                                $result=VerbCache::search_in_db(26,'endings',3);
                            }
                            break;
                            
                        case "3":
                                if($lemma=="O")
                                {
                                    $result=VerbCache::search_in_db(68,'endings',3);
                                }
                                elseif($tematic==1)
                                {
                                    $result=VerbCache::search_in_db(69,'endings',3);
                                }
                                elseif($lemma=="PrS3")
                                {
                                    $result=VerbCache::search_in_db(70,'endings',3);
                                }
                                elseif($lemma=="Ao")
                                {
                                    $result=VerbCache::search_in_db(71,'endings',3);
                                }
                                else
                                {
                                    $result=VerbCache::search_in_db(27,'endings',3);
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
                            $result=VerbCache::search_in_db(72,'endings',3);
                        }
                        elseif($lemma=="Ao1A")
                        {
                            //echo $special;

                            if($special==1)
                            {
                                $result=VerbCache::search_in_db(119,'endings',3);
                            }

                            if($special==2)
                            {
                                $result[0]=VerbCache::search_in_db(118,'endings',3);
                                $result[1]=VerbCache::search_in_db(119,'endings',3);
                            }

                            if($special==3)
                            {
                                $result[0]=VerbCache::search_in_db(117,'endings',3);
                                $result[1]=VerbCache::search_in_db(118,'endings',3);
                            }

                            if($special==4)
                            {
                                $result=VerbCache::search_in_db(118,'endings',3);
                            }
                         
                        }
                        else
                        {
                            
                            $result=VerbCache::search_in_db(28,'endings',3);
                        }
                        break;

                    case "2":
                        $result=VerbCache::search_in_db(29,'endings',3);
                        break;
                    
                    case "3":
                        $result=VerbCache::search_in_db(30,'endings',3);
                        break;
                }
                break;

            case "2":
                switch($lico)
                {
                    case "1":
                            $result=VerbCache::search_in_db(31,'endings',3);
                            break;
        
                    case "2":
                            if($lemma=="O")
                            {
                                $result=VerbCache::search_in_db(73,'endings',3);
                            }
                            elseif($lemma=="Pk")
                            {
                                $result=VerbCache::search_in_db(74,'endings',3);
                            }
                            elseif($tematic=="1")
                            {
                                $result=VerbCache::search_in_db(75,'endings',3);
                            }
                            else
                            {    
                                $result=VerbCache::search_in_db(32,'endings',3);
                            }
                            break;
                        
                    case "3":
                            if($lemma=="O")
                            {
                                $result=VerbCache::search_in_db(76,'endings',3);
                            }
                            elseif($lemma=="Pk")
                            {
                                $result=VerbCache::search_in_db(77,'endings',3);
                            }
                            elseif($tematic=="1")
                            {
                                $result=VerbCache::search_in_db(78,'endings',3);
                            }
                            else
                            {    
                                $result=VerbCache::search_in_db(33,'endings',3);
                            }
                                break;
                }
                break;

            case "3":
                switch($lico)
                {
                        case "1":
                                $result=VerbCache::search_in_db(34,'endings',3);
                                break;
            
                        case "2":
                                $result=VerbCache::search_in_db(35,'endings',3);
                                break;
                            
                        case "3":
                            if($lemma=="O"||$lemma=="Pk"||$lemma=="Ao1A")
                            {
                                if($lemma=="Ao1A")
                                {
                                    if($lastname=="bhū")
                                    {
                                        $result=VerbCache::search_in_db(120,'endings',3);
                                    }
                                }
                                else
                                {
                                    $result=VerbCache::search_in_db(79,'endings',3);
                                }
                            }
                            elseif($tematic=="1")
                            {
                                $result=VerbCache::search_in_db(80,'endings',3);
                            }
                            else
                            {
                                $result=VerbCache::search_in_db(36,'endings',3);
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
                        $result=VerbCache::search_in_db(81,'endings',3);
                        break;

                    case "2":   // OPEN MOOL?
                        if(!$v_ending)
                        {
                            if($tematic==1)
                            {
                                $result=VerbCache::search_in_db(82,'endings',3);
                            }
                            elseif($lemma=="PrS9")
                            {
                               if($is_open_mool==0)
                               {
                                    $result=VerbCache::search_in_db(84,'endings',3);
                               }
                            }
                            else
                            {
                                $result=VerbCache::search_in_db(85,'endings',3);
                            }
                        }
                        else
                        {
                            //V перед суффиксом
                            $result=VerbCache::search_in_db(83,'endings',3);
                        }
                        break;
                    
                    case "3":
                        $result=VerbCache::search_in_db(86,'endings',3);
                        break;
                }
                break;

            case "2":
                switch($lico)
                {
                    case "1":
                            $result=VerbCache::search_in_db(87,'endings',3);
                            break;
        
                    case "2":
                            $result=VerbCache::search_in_db(88,'endings',3);
                            break;
                        
                    case "3":
                            $result=VerbCache::search_in_db(89,'endings',3);
                            break;
                }
                break;

            case "3":
                switch($lico)
                {
                        case "1":
                                $result=VerbCache::search_in_db(90,'endings',3);
                                break;
            
                        case "2":
                                $result=VerbCache::search_in_db(91,'endings',3);
                                break;
                            
                        case "3":
                                if($tematic==1)
                                {
                                    $result=VerbCache::search_in_db(92,'endings',3);
                                }
                                elseif($lemma=="PrS3")
                                {
                                    $result=VerbCache::search_in_db(93,'endings',3);
                                }
                                else
                                {
                                    $result=VerbCache::search_in_db(94,'endings',3);
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
                        $result=VerbCache::search_in_db(95,'endings',3);
                        break;

                    case "2":
                        $result=VerbCache::search_in_db(96,'endings',3);
                        break;
                    
                    case "3":
                        $result=VerbCache::search_in_db(97,'endings',3);
                        break;
                }
                break;

            case "2":
                switch($lico)
                {
                    case "1":
                            $result=VerbCache::search_in_db(98,'endings',3);
                            break;
        
                    case "2":
                            if($tematic=="1")
                            {
                                $result=VerbCache::search_in_db(99,'endings',3);
                            }
                            else
                            {    
                                $result=VerbCache::search_in_db(100,'endings',3);
                            }
                            break;
                        
                    case "3":
                            if($tematic=="1")
                            {
                                $result=VerbCache::search_in_db(101,'endings',3);
                            }
                            else
                            {    
                                $result=VerbCache::search_in_db(102,'endings',3);
                            }
                            break;
                }
                break;

            case "3":
                switch($lico)
                {
                        case "1":
                                $result=VerbCache::search_in_db(103,'endings',3);
                                break;
            
                        case "2":
                                $result=VerbCache::search_in_db(104,'endings',3);
                                break;
                            
                        case "3":
                            if($tematic=="1")
                            {
                                $result=VerbCache::search_in_db(105,'endings',3);
                               
                            }
                            else
                            {
                                $result=VerbCache::search_in_db(106,'endings',3);
                               
                            }
                            break;
                }
                break;
        }
    }

    return $result;
}


?>