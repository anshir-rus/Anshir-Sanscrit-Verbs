
<!DOCTYPE html>
<html>
    <head>
        <title>Глагольные корни</title>
        <meta charset="utf-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </head>
    <body class="d-flex flex-column min-vh-100">

    <? include "header.php"; ?>
    <div class="container mt-5" style="max-width: 1255px">


    <form action="analysis.php">
        <div class="mb-3">
            <input type="text"  class="form-control" name="word" size="20">
          
        </div>
        <button type="submit" value="Find"  class="btn btn-primary">Find</button>
    </form>
   
    
    <BR>

    <?php
$start = microtime(true);
require_once "db.php";
include "functions.php";

$word_source=$_REQUEST['word'];


for($i=0;$i<mb_strlen($word_source);$i++)
{
    $prev_letter=mb_substr($word_source,$i-1,1);
    $one_letter=mb_substr($word_source,$i,1);


   

    if($one_letter=="ṣ"&&is_vowel($prev_letter))
    {
        $one_letter="s";
        $letter[]=$one_letter;
    }
    elseif($one_letter=="o"&&$i==0)
    {
        $one_letter="au";
        $letter[]=$one_letter;
    }
    elseif($one_letter=="e")
    {
        $one_letter="ai";
        $letter[]=$one_letter;
    }
    else
    {
        $letter[]=$one_letter;
    }
}

$word=implode("",$letter);




$query_verb = "SELECT * FROM verbs ";
$result = mysqli_query($connection, $query_verb);

if (mysqli_num_rows($result) > 0) {
while ($res = mysqli_fetch_array($result)) {

    $verbs_id[]=$res['id'];
    $verbs[]=$res['name'];
    $verbs_omonim[]=$res['omonim'];
    $verbs_ryad[]=$res['ryad'];
    $verbs_type_lat=$res['type'];

    switch($verbs_type_lat)
    {
        case "I":$verb_type=1;break;
        case "II":$verb_type=2;break;
        case "III":$verb_type=3;break;
        case "IV":$verb_type=4;break;
        default:$verb_type=0;break;
    }

    $verbs_type[]=$verb_type;
    $verbs_setnost[]=$res['setnost'];
    $verbs_element[]=$res['element'];
    $verbs_alternate[]=$res['alternate'];
    $verbs_translate[]=$res['translate'];
    $verbs_double_v[]=$res['double_v'];

    //update_verbs_double_field($res['id'],$res['name'],$res['omonim'],$verb_type,$res['element'],$res['ryad']);
    

}
}

$query = "SELECT * FROM endings ORDER BY nabor ASC ";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
while ($res = mysqli_fetch_array($result)) {

    $id=$res['id'];

    $endings[]=$res['name'];
    $pada[]=$res['pada'];
    $chislo[]=$res['chislo'];
    $lico[]=$res['lico'];
    $lemma[]=$res['lemma'];
    $query_end[]=$res['query'];


}
}

$query_suf = "SELECT * FROM suffixes WHERE final_form!=''";
$result = mysqli_query($connection, $query_suf);

if (mysqli_num_rows($result) > 0) {
while ($res = mysqli_fetch_array($result)) {

    $id=$res['id'];

    $suffix[]=$res['name'];
    $suffix_lemma[]=$res['final_form'];
    $suffix_pada[]=$res['pada'];

}
}

$vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "ai", "o", "au"];

$test_no_vowel=word_no_vowels("tm̥̄s");
//echo "<HR>$test_no_vowel<HR>";




$answer='';$h_counter=0;
for($i=0;$i<count($endings);$i++)
{
    $word_length=mb_strlen($word);
    $length=mb_strlen($endings[$i]);
    if($endings[$i]=="Ø")
    {
        $length=0;
    }
    $len_minus=$word_length-$length;

    //echo mb_substr($word,$len_minus,$length)."---".$endings[$i]."<BR><BR>";

       
    if(mb_substr($word,$len_minus,$length)==$endings[$i]||$endings[$i]=="Ø")
    {

        
        

        $hypotesa[$h_counter][0]=$lemma[$i];
        $hypotesa[$h_counter][1]=$pada[$i];
        $hypotesa[$h_counter][2]=$lico[$i];
        $hypotesa[$h_counter][3]=$chislo[$i];
        $hypotesa[$h_counter][4]=$query_end[$i];
        $h_counter++;

        $answer.='<tr><td>'.$endings[$i].'</td>
        <td>'.$pada[$i].'</td>
        <td>'.$lico[$i].'</td>
        <td>'.$chislo[$i].'</td>
        <td>'.$query_end[$i].'</td>
        <td>'.$lemma[$i].'</td>
      
        </tr>';

        $lemmas=explode(",",trim($lemma[$i]));
 
        $suffix_string="";

        $half_word=mb_substr($word,0,$len_minus);

        unset($half_word_var);

        /*
        if(mb_substr($endings[$i],0,1)=="ā")
        {
            $half_word_var1[0]=$half_word;
           // $half_word_var1[1]=$half_word."ā";
           // $half_word_var1[2]=$half_word."a";
        }
        else
        {
            $half_word_var1[0]=$half_word;
        }
        */
        $half_word_var1[0]=$half_word;
     

        for($s=0;$s<count($verbs_id);$s++)
        {
            $alter=explode(",",$verbs_alternate[$s]);

            $alter_0_massive=find_more_with_other_letters($alter[0],"last");
            $alter_1_massive=find_more_with_other_letters($alter[1],"last");
            $alter_2_massive=find_more_with_other_letters($alter[2],"last");
    
            if(mb_strpos(trim($half_word),$alter_0_massive[0])!==false||mb_strpos(trim($half_word),$alter_0_massive[1])!==false||mb_strpos(trim($half_word),$alter_1_massive[0])!==false||
            mb_strpos(trim($half_word),$alter_1_massive[1])!==false||mb_strpos(trim($half_word),$alter_2_massive[0])!==false||mb_strpos(trim($half_word),$alter_2_massive[1])!==false)
            {
                $priority_max_mas[]=$verbs_id[$s];
            }

            $alter=explode(",",$verbs_double_v[$s]);

            $alter_0_massive=find_more_with_other_letters($alter[0],"last");
            if($alter[1])
            {
                $alter_1_massive=find_more_with_other_letters($alter[1],"last");
            }

            if($alter[2])
            {
                $alter_2_massive=find_more_with_other_letters($alter[2],"last");
            }
    
            /*
            if(mb_strpos(trim($half_word),$alter_0_massive[0])!==false||mb_strpos(trim($half_word),$alter_0_massive[1])!==false||mb_strpos(trim($half_word),$alter_1_massive[0])!==false||
            mb_strpos(trim($half_word),$alter_1_massive[1])!==false||mb_strpos(trim($half_word),$alter_2_massive[0])!==false||mb_strpos(trim($half_word),$alter_2_massive[1])!==false)
            {
                $priority_max_mas[]=$verbs_id[$s];
            }
            */

            if(mb_strpos(word_no_vowels($half_word),word_no_vowels($alter_0_massive[0]))!==false||mb_strpos(word_no_vowels($half_word),word_no_vowels($alter_0_massive[1]))!==false
            ||mb_strpos(word_no_vowels($half_word),word_no_vowels($alter_1_massive[0]))!==false||mb_strpos(word_no_vowels($half_word),word_no_vowels($alter_1_massive[1]))!==false
            ||mb_strpos(word_no_vowels($half_word),word_no_vowels($alter_2_massive[0]))!==false||mb_strpos(word_no_vowels($half_word),word_no_vowels($alter_2_massive[1]))!==false)
            {
                $priority_max_mas[]=$verbs_id[$s];
            }

            switch($half_word)
            {
                case "icch":
                    $priority_max_mas[]=283;
                    break;
                case "gacch":
                    $priority_max_mas[]=701;
                    break;
                case "ṛcch":
                    $priority_max_mas[]=586;
                    break;
                case "yacch":
                    $priority_max_mas[]=704;
                    break;
                case "pib":
                    $priority_max_mas[]=184;
                    break;
                case "tiṣṭh":
                    $priority_max_mas[]=226;
                    break;
            }

        }

        //print_r($half_word_var1);
        //echo "<BR><BR>";

        for($hwv=0;$hwv<count($half_word_var1);$hwv++)
        {

            $half_word=$half_word_var1[$hwv];

            for($j=0;$j<count($lemmas);$j++)
            {
                unset($suffix_massive);
                unset($suffix_massive_pada);
                unset($suffix_can);

                for($k=0;$k<count($suffix_lemma);$k++)
                {
                    unset($many_suffix);

                        if(mb_strpos($suffix_lemma[$k],","))
                        {
                            $many_suffix=explode(",",$suffix_lemma[$k]);
                        
                        }

                        if(count($many_suffix)>1)
                        {
                            for($l=0;$l<count($many_suffix);$l++)
                            {
                                if(trim($lemmas[$j])==$many_suffix[$l])
                                {
                                    $suffix_massive[]=$suffix[$k];
                                    $suffix_massive_pada[]=$suffix_pada[$k];

                                }
                            }
                        }
                        else
                        {
                            if(trim($lemmas[$j])==$suffix_lemma[$k])
                            {
                                $suffix_massive[]=$suffix[$k];
                                $suffix_massive_pada[]=$suffix_pada[$k];
                            }
                        }

                }   

                $suffix_massive=array_values(array_unique($suffix_massive));
                $suffix_massive_pada=array_values(array_unique($suffix_massive_pada));

                $flag_maybe=0;

                $half_word_var=find_more_with_other_letters($half_word,"last");

                //echo "COUNT SFX:".count($suffix_massive)."<BR>";

                for($k=0;$k<count($suffix_massive);$k++)
                {
                    
                

                   // print_r($half_word_var);
                  //  echo "<BR><BR>";

                    $flag_maybe2=0;


                    for($h=0;$h<count($half_word_var);$h++)
                    {

                        $suffix_length=mb_strlen($suffix_massive[$k]);
                        $half_length=mb_strlen($half_word_var[$h]);
                        $last_part_half_word=mb_substr($half_word_var[$h],$half_length-$suffix_length,$suffix_length);
        
                        $suffix_var=find_more_with_other_letters($suffix_massive[$k],"first");

                        //print_r($suffix_var);

                        //echo "H:$h<BR>";

                        
                        for($s=0;$s<count($suffix_var);$s++)
                        {

                            //if(mb_strpos($last_part_half_word,$suffix_var[$s])!==false||$suffix_var[$s]=="-"||$suffix_var[$s]=="|")
                            //echo "LPG: $last_part_half_word SUF:".$suffix_var[$s]." ".mb_substr($last_part_half_word,$suffix_length)."<BR>";
                            
                            //echo word_no_vowels($half_word);

                            //echo $half_word_var[$h];
                            //echo "<BR><BR>";
                           
                            $lemmas[$j]=trim($lemmas[$j]);
                        
                            if($last_part_half_word==$suffix_var[$s]&&($suffix_massive_pada[$k]==$pada[$i]||!$suffix_massive_pada[$k])
                            ||($suffix_var[$s]=="-"&&search_no_vowels_in_verb_alternate($verbs_alternate,$half_word))
                            ||($suffix_var[$s]=="|"&&search_no_vowels_in_verb_alternate($verbs_alternate,$half_word))
                            ||($suffix_var[$s]=="-"&&search_no_vowels_in_verb_alternate($verbs_double_v,$half_word))
                            ||($suffix_var[$s]=="|"&&search_no_vowels_in_verb_alternate($verbs_double_v,$half_word))
                            )
                            {
                               
                                //&&search_no_vowels_in_verb($verbs,$half_word)
                                $suffix_can[$k]="<u>похоже</u>";
                                //echo "<HR> $k HERE".$suffix_can[$k]."<HR>";
                                $flag_maybe=1;
                                $flag_maybe2=1;

                               

                                if($lemmas[$j]=="Pr"||$lemmas[$j]=="O"||$lemmas[$j]=="Im"||$lemmas[$j]=="Ip"||$lemmas[$j]=="PrSb")
                                {
                                        //echo "Проверка $h<BR>";
                                  
                                        if($suffix_var[$s]=="a")
                                        {
                                            $last_letter_ps=mb_substr($half_word_var[$h],-1,1);
                                            //echo "Проверка на PS suff '".$suffix_var[$s]."' ".$half_word_var[$h]." Last:$last_letter_ps<BR>";
                                            if($last_letter_ps=="y")
                                            {
                                               // echo "похоже на Пассив<BR>";
                                                $suffix_can[$k].=" Также похоже на Пассив - образуется с финитными формами н.в. времени с суффиксом пассива -y ";
                                                $passive_hyp[]=array("Passive-".$lemmas[$j],$pada[$i],$lico[$i],$chislo[$i],$query_end[$i]);
                                            }
                                        }
                                        else
                                        {
                                            $last_letter2_ps=mb_substr($half_word_var[$h],mb_strlen($half_word_var[$h])-3,2);
                                           // echo "Проверка на PS suff '".$suffix_var[$s]."' ".$half_word_var[$h]." Last:$last_letter2_ps<BR>";
                                            if($last_letter2_ps=="ya")
                                            {
                                                //echo "похоже на Пассив<BR>";
                                                $suffix_can[$k].=" Также похоже на <b>Пассив</b> - образуется с финитными формами н.в. (Pr,O,Im,Ip,PrSb) с суффиксом пассива -y и суффиксом н.в. -a ";
                                                $passive_hyp[]=array("Passive-".$lemmas[$j],$pada[$i],$lico[$i],$chislo[$i],$query_end[$i]);

                                                //print_r($passive_hyp);
                                            }
                                        }
                                    
                                }

                                //echo $last_part_half_word." --- ".$suffix_var[$s]." Pada:".$suffix_massive_pada[$k]." Myabe1  $flag_maybe Myabe2 $flag_maybe2<BR>";

                            }
                            elseif(!$flag_maybe2)
                            {
                               
                                $suffix_can[$k]="<u>не похоже</u>";
                                //echo "<HR> $k Maybe2:  $flag_maybe2 HERE2 ".$suffix_can[$k]."<HR>";
                            }
                        
                        
                        }

                    }
                    //echo "<HR>";
                    //print_r($suffix_can);
                    //echo "<HR>";
                }

                //echo "<BR><BR>";
                //echo "CAN:";
                //print_r($suffix_can);
                //echo "<BR><BR>";

                $suff_answer[$j]="<BR><u>".$lemmas[$j].":</u> ";

                if(!$suffix_massive)
                {
                    //print_r($verbs_alternate);

                    if((search_no_vowels_in_verb_alternate($verbs_alternate,$half_word))||(($half_word=="iccha"||$half_word=="gaccha"||$half_word=="ṛccha"
                    ||$half_word=="yaccha"||$half_word=="piba"||$half_word=="tiṣṭha")&&($lemmas[$j]=="Ip"||$lemmas[$j]=="PrS")))
                    {
                        $suff_answer[$j].="-"." (<u>похоже</u>)";
                        $suffix_can[$k]="<u>похоже</u>";
                        $flag_maybe=1;
                        
                        

                    }
                    else
                    {
                        $suff_answer[$j].="-"." (<u>не похоже</u>)";
                        $suffix_can[$k]="<u>не похоже</u>";
                        $flag_maybe=0;
                        //echo "HERE???<BR>";
                    }
                }
                
                ///////////////////////
                if($flag_maybe)
                {
                    $maybe[$j]=1;
                }
                else
                {
                    $maybe[$j]=0;
                }
                //////////////////////////////

                //echo "SUFFIX CEN<BR>";
                //print_r($suffix_can);
                //echo "<BR><BR>";

                for($k=0;$k<count($suffix_massive);$k++)
                {
                    //echo $k;
                    //echo "<BR>";
                    
                    if($suffix_massive_pada[$k])
                    {
                        $suff_answer[$j].=$suffix_massive[$k]." для залога <i>".$suffix_massive_pada[$k]."</i> (".$suffix_can[$k].") ";
                    }
                    else
                    {
                        $suff_answer[$j].=$suffix_massive[$k]." ".$suffix_massive_pada[$k]. " (".$suffix_can[$k].") ";
                    }
                    
                }

                if($maybe[$j])
                {
                    $hypotesa_after_suff[]=$lemmas[$j];
                    $maybe_string="Да";
                    
                }
                else
                {
                    $hypotesa_after_suff[]="";
                    $maybe_string="Нет";
                }

                $suff_answer[$j].="<BR>Может быть эта словоформа: <b>$maybe_string</b>";

                $suff_answer=array_values(array_unique($suff_answer));

                unset($suffix_massive);
            
            }

            
           // $hypotesa_after_suff=array_values(array_unique($hypotesa_after_suff));

          //  print_r($hypotesa_after_suff);

            for($hw=0;$hw<mb_strlen($half_word);$hw++)
            {
                $flag_v=0;

                for($v=0;$v<count($vowels);$v++)
                {
                    if(mb_substr($half_word,$hw,1)==$vowels[$v])
                    {
                        $flag_v=1;
                    }
                }

                if($flag_v==0)
                {
                    $c_word[]=mb_substr($half_word,$hw,1);
                    $flag_c=0;
                }
            }
            
            $answer.='<tr><td colspan=6>Слово без окончания: ';
            //echo $half_word;

            //$answer.="COUNT VAR: ".count($half_word_var); 

            for($o=0;$o<count($half_word_var);$o++)
            {
                $answer.=$half_word_var[$o]." ";
            }

            $answer.= "<BR><BR>Суффиксы:";

            for($j=0;$j<count($suff_answer);$j++)
            {
                $answer.="<BR>".$suff_answer[$j];
            }

            $answer.='</td></tr>';

        }
    
       // echo $h."<BR><BR>";
       // print_r($hypotesa);
       //  echo "<BR><BR>";
    
    }
 
    //print_r($hypotesa_after_suff);

    if($hypotesa_after_suff)
    {
        $hypotesa_2[]=$hypotesa_after_suff;
    }

   
    
    unset($hypotesa_after_suff);
    unset($suff_answer);
    unset($suffix_massive);
}

echo "<BR><BR>";
echo "<BR>HERE HYP<BR>";
print_r($hypotesa);
echo "<BR><BR>";

//$hypotesa_2=array_values(array_unique($hypotesa_2));
//$hypotesa_2 = array_map("unserialize", array_unique(array_map("serialize", $hypotesa_2)));

//echo "<BR>HERE HYP 2<BR>";
//print_r($hypotesa_2);
//echo "<BR><BR>";

$counter=0;
for($i=0;$i<count($hypotesa_2);$i++)
{
    //echo count($hypotesa_2[$i])."<BR>";
    if(count($hypotesa_2[$i])>1||$hypotesa_2[$i]!="")
    {
        $temp[$counter]=$hypotesa_2[$i];
        $counter++;
    }
}
$hypotesa_2 = $temp;

//echo "<BR>HERE hypotesa_2<BR>";
//print_r($hypotesa_2);
//echo "<BR><BR>";

for($i=0;$i<count($hypotesa);$i++)
{
    $hypotesa[$i][0]=implode(",",$hypotesa_2[$i]);
}

$c_word=array_values(array_unique($c_word));

for($i=0;$i<count($verbs);$i++)
{
    $priority=0;
    for($j=0;$j<count($c_word);$j++)
    {
     
        
        if(mb_strpos($verbs[$i],$c_word[$j])!==false)
        {
            $priority=$priority+1;
        }
    }

    if($priority>1)
    {
        $priority_mas[]=$verbs_id[$i];
    }
    else
    {
        $nopriority_mas[]=$verbs_id[$i];
    }

}

$priority_mas=array_values($priority_mas);
//echo "<BR><BR>Priority 1: ".print_r($priority_mas);

$itog='
<table class="table table-bordered">
<thead><tr><th scope="col">Окончание</th>
<th scope="col">Залог</th>
<th scope="col">Лицо</th>
<th scope="col">Число</th>
<th scope="col">Запрос МП</th>
<th scope="col">Словоформы</th>
</tr></thead>
<tbody>'.$answer.'</tbody></table>';

echo "<h1>$word</h1><h6>(без некотрых сандхи)</h6><BR>";
echo "<h3>Гипотеза с окончаниями</h3>";
echo $itog;

    if(mb_substr($word,0,1)=="a"||mb_substr($word,0,1)=="ā")
    {
        $flag_augment=1;
        
    }

    $hypotesa = array_map("unserialize", array_unique(array_map("serialize", $hypotesa)));

echo "<h3>Гипотеза с аугментом</h3>";
echo "Обязательный аугмент у форм: Ao, Im, Co<BR>";
echo "Может быть, может не быть: AoP, PluPe";

$strong_augment=array("Ao","Im","Co","Ao ТЕМ.");

if($flag_augment)
{
    echo "<BR><b>Похоже на аугмент. Но это может быть и корень, начинающийся на а</b><BR>";
}
else
{
    echo "<BR><b>Не похоже на наличие аугмента. Исключаем формы с обязательным аугментом.</b><BR><BR>";

    for($i=0;$i<count($hypotesa);$i++)
    {
        $temp=explode(",",$hypotesa[$i][0]);
        $exclude=exclude_massive($temp,$strong_augment);
        $temp2=implode(",",$exclude);
       // echo $temp2."<BR><BR>";
       // print_r($temp2);
       // if($temp2!="")
       // {
            $hypotesa[$i][0]=$temp2;
       // }
    }

    
}

for($i=0;$i<count($hypotesa);$i++)
{
    if($hypotesa[$i][0]!="")
    {
        $hypotesa_temp[]=$hypotesa[$i];
    }
}
$hypotesa=$hypotesa_temp;



echo "Hypotesa: ";
//!MERGE!
if($passive_hyp)
{
    $hypotesa=array_merge($hypotesa,$passive_hyp);
}
print_r($hypotesa);
$priority_max_mas=array_values(array_unique($priority_max_mas));



//!MERGE!
$search_this_verbs=$priority_max_mas;
//$search_this_verbs=array_merge($priority_max_mas,$priority_mas);
//$search_this_verbs=array("258");


echo "<BR><BR>For search:";
print_r($search_this_verbs);


$counter=0;

for($k=0;$k<count($search_this_verbs);$k++)
{
    $id=$search_this_verbs[$k];
    $vid=$id-1;

    for($i=0;$i<count($hypotesa);$i++)
    {
        $temp=explode(",",$hypotesa[$i][0]);
        $sg1[0]="";

        for($j=0;$j<count($temp);$j++)
        {
            $sg1=forms($id,short_long($temp[$j]),$hypotesa[$i][2],$hypotesa[$i][3],$hypotesa[$i][1],0);

            //echo short_long($temp[$j])."<BR>";
            //print_r($sg1);
            //echo "<BR>";
            
            if(mb_strpos($sg1[0],trim($word_source))!==false)
            {

                if($verbs_omonim[$vid])
                {
                    $result_table.=$verbs_omonim[$vid];
                }

                $result_gen[$counter][0]=$verbs[$vid];
                $result_gen[$counter][1]=$verbs_omonim[$vid];
                $result_gen[$counter][2]=$temp[$j];
                $result_gen[$counter][3]=short_long($temp[$j]);
                $result_gen[$counter][4]=$hypotesa[$i][1];
                $result_gen[$counter][5]=$hypotesa[$i][2];
                $result_gen[$counter][6]=$hypotesa[$i][3];
                $result_gen[$counter][7]=$sg1[0];
                $result_gen[$counter][8]=$verbs_translate[$vid];
                
                $counter++;
               
            }
        }
    }

}  

$result_gen = array_map("unserialize", array_unique(array_map("serialize", $result_gen)));
$result_gen = array_values($result_gen);
//print_r($result_gen);

echo "<HR><BR><h3>Результат поиска</h3>";

$result_table="";
for($i=0;$i<count($result_gen);$i++)
{
    if($result_gen[$i][1])
    {
       $omonim=$result_gen[$i][1];
    }
    
    $result_table.="<tr><td>".$result_gen[$i][0]." $omonim</td><td>".$result_gen[$i][2]."</td><td>".$result_gen[$i][3]."</td><td>".$result_gen[$i][4]."</td><td>".$result_gen[$i][5]."</td>
    <td>".$result_gen[$i][6]."</td><td>".$result_gen[$i][7]."</td><td>".$result_gen[$i][8]."</td><tr>";
}

$itog_gen='<table class="table table-bordered">
<thead>
<tr>
<th scope="col">Возможный глагол</th>
<th scope="col">Форма</th>
<th scope="col">Код формы</th>
<th scope="col">Залог</th>
<th scope="col">Лицо</th>
<th scope="col">Число</th>
<th scope="col">Результат генерации</th>
<th scope="col">Перевод</th>
</tr>
</thead>
<tbody>'.$result_table.'</tbody></table>';

echo $itog_gen;

echo '<br>===============================================================<BR>';
echo 'Время выполнения скрипта: ' . (microtime(true) - $start) . ' sec.';
echo '<br>===============================================================<BR>';



function update_alternate()
{

    for($s=0;$s<count($verbs_id);$s++)
    {
        $alter=check_alternate($verbs_id[$s],$verbs,$verbs_omonim,$verbs_type,$verbs_element,$verbs_ryad,$verbs_setnost);

        echo $verbs_id[$s]." ".$alter[0]." ".$alter[1]." ".$alter[2]." ".trim($half_word)."<BR>";

        //echo mb_strpos(trim($half_word),$alter[2])."<BR>";

        $string=implode(",",$alter);

        echo "STRING:".$string;

        $query = "UPDATE verbs SET alternate=? WHERE id=?";
        $statement = $connection->prepare($query);
        $statement->bind_param('si',$string,$verbs_id[$s]);
        $statement->execute();
        $statement->close();

        //if(mb_strpos(trim($half_word),$alter[0])!==false||mb_strpos(trim($half_word),$alter[1])!==false||mb_strpos(trim($half_word),$alter[2])!==false)
        //{
        //    $priority_max_mas[]=$verbs_id[$s];
        //}
    }

}



function check_alternate($verb_id,$verbs,$verbs_omonim,$verbs_type,$verbs_element,$verbs_ryad,$verbs_setnost)
{

    $verb_id=$verb_id-1;
    $verb_name=$verbs[$verb_id];
    $verb_omonim=$verbs_omonim[$verb_id];
    $verb_type=$verbs_type[$verb_id];
    $verb_element=$verbs_element[$verb_id];
    $verb_ryad=$verbs_ryad[$verb_id];
    $verb_set=$verbs_setnost[$verb_id];

    //echo "$verb_name,$verb_set,$verb_omonim,$verb_type,$verb_element,$verb_ryad"."<BR>";
    
    $e[0]=get_e_mp_table4("",$verb_name,$verb_set,$verb_omonim,$verb_type,$verb_element,$verb_ryad,1,"");
    $e[1]=get_e_mp_table4("",$verb_name,$verb_set,$verb_omonim,$verb_type,$verb_element,$verb_ryad,2,"");
    $e[2]=get_e_mp_table4("",$verb_name,$verb_set,$verb_omonim,$verb_type,$verb_element,$verb_ryad,3,"");

    $result[0]=str_replace($verb_element,$e[0],$verb_name);
    $result[1]=str_replace($verb_element,$e[1],$verb_name);
    $result[2]=str_replace($verb_element,$e[2],$verb_name);

    $result[0]= str_replace("Ø̄", "", $result[0]);
    $result[1]= str_replace("Ø̄", "", $result[1]);
    $result[2]= str_replace("Ø̄", "", $result[2]);

    $result[0]= str_replace("Ø", "", $result[0]);
    $result[1]= str_replace("Ø", "", $result[1]);
    $result[2]= str_replace("Ø", "", $result[2]);
    

    return $result;
}

function exclude_massive($hypotesa,$strong_augment)
{
    for($i=0;$i<count($hypotesa);$i++)
    {
        $flag_ex=0;
        for($j=0;$j<count($strong_augment);$j++)
        {
            //echo "HYP:".$hypotesa[$i]." STR:".$strong_augment[$j]." ";
            if(trim($hypotesa[$i])==$strong_augment[$j])
            {
                $flag_ex=1;
            }
            //echo "FLAG_EX: $flag_ex<BR>";
        }

        if(!$flag_ex&&trim($hypotesa[$i])!='')
        {
            $hypotesa_new[]=$hypotesa[$i];
        }
    }

    return $hypotesa_new;
}

function short_long($temp)
{
    if($temp)
    {
        switch(trim($temp))
        {
            case "Co":
                $command="VR-FuS-Co";
                break;
            
            case "Pr":
                $command="VR-PrS-Pr";
                break;

            case "Passive-Pr":
                $command="VR-PS-PrS-Pr";
                break;

            case "AoSb":
                $command="VR-AoS-AoSbS-AoSb";
                break;
            
            case "Ao":
                $command="VR-AoS-Ao";
                break;
                
            case "PeF":
                $command="VR-PeS-PeF";
                break;

            case "Im":
                $command="VR-PrS-Im";
                break;

            case "Passive-Im":
                $command="VR-PS-PrS-Im";
                break;

            case "PrSb":
                $command="VR-PrS-PrSbS-PrSb";
                break;

            case "Passive-PrSb":
                $command="VR-PS-PrS-PrSbS-PrSb";
                break;

            case "PeSb":
                $command="VR-PeS-PeSbS-PeSb";
                break;

            case "PluPe":
                $command="VR-PeS-PluPe";
                break;

            case "Ip":
                $command="VR-PrS-Ip";
                break;

            case "Passive-Ip":
                $command="VR-PS-PrS-Ip";
                break;

            case "PeIp":
                $command="VR-PeS-PeIp";
                break;

            case "AoIp":
                $command="VR-AoS-AoIp";
                break;

            case "AoP":
                $command="VR-AoS-AoP";
                break;

            case "Fu":
                $command="VR-FuS-Fu";
                break;

            case "O":
                $command="VR-PrS-OS-O";
                break;

       
            case "Passive-O":
                $command="VR-PS-PrS-OS-O";
                break;
                
            
            case "PeO":
                $command="VR-PeS-PeOS-PeO";
                break;    
                
            case "Pk":
                $command="VR-AoS-PkS-Pk";
                break;  
               

            default:
               // echo "$temp - unknown route";
                break;
        }
    


    }
    return $command;
}



function find_more_with_other_letters($word,$type)
{
    if($type=="last")
    {
        $last_letter=mb_substr($word,mb_strlen($word)-1,1);

        $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "ai", "o", "au"];


        switch($last_letter)
        {
            case "o":$variant="av";break;
            case "e":$variant="ay";$variant2="ai";$variant3="aī";break;
            case "ā":$variant="aa";break;
            case "u":$variant="v";break;
            case "ū":$variant="v";break;
            case "i":$variant="y";break;
            case "ī":$variant="y";break;
            case "ṛ":$variant="r";break;
            //default:$variant="-";
        }

        $variants[0]=$word;

        if($variant)
        {
            $variants[1]=mb_substr($word,0,mb_strlen($word)-1).$variant;
        }

        if($variant2)
        {
            $variants[2]=mb_substr($word,0,mb_strlen($word)-1).$variant2;
        }

        if($variant3)
        {
            $variants[3]=mb_substr($word,0,mb_strlen($word)-1).$variant3;
        }
    }


    if($type=="first")
    {
        $last_letter=mb_substr($word,0,1);

        $consonants = ["k", "kh", "g", "gh", "ṅ", "c", "ch", "j", "jh", "ñ", "ṭ", "ṭh", "ḍ", "ḍh", "ṇ", "t", "th", "d", "dh", "n", "p", "ph", "b", "bh", "m", "y", "r", "l", "v", "ś", "ṣ", "s", "h"];
        

        switch($last_letter)
        {
            case "a":$variant="ā";break;
            case "u":$variant="ū";break;
            case "i":$variant="ī";break;

            case "s":$variant="ṣ";break;
            case "n":$variant="ṇ";break;
            case "t":$variant="ṭ";break;
 
            default:$variant="";
        }

        $variants[0]=$word;
        $variants[1]=$variant.mb_substr($word,1,mb_strlen($word)-1);
    }

    //print_r($variants);
    return $variants;
}

function is_vowel($letter)
{
    $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "ḷ", "ḹ", "e", "ai", "o", "au"];

    $is_vowel=0;
    for($j=0;$j<count($vowels);$j++)
    {
        if($letter==$vowels[$j])
        {
            $is_vowel=1;
        }
    }

    return $is_vowel;
}

function word_no_vowels($word_source)
{
    //Убираем гласные + м и н в корне и в запросе и проверяем
    $vowels = ["a", "ā", "i", "ī", "u", "ū", "ṛ", "ṝ", "n̥", "n̥̄", "m̥", "m̥̄", "ḷ", "ḹ", "e", "ai", "o", "au"];
    mb_internal_encoding("UTF-8");
    //print_r($vowels);

    for($i=0;$i<mb_strlen($word_source);$i++)
    {
        $one_letter=mb_substr($word_source,$i,1);
        $second_letter=mb_substr($word_source,$i+1,1);

        if($second_letter=="̥")
        {
            $one_letter="";
        }
        
        if($one_letter=="̥")
        {
            $one_letter=mb_substr($word_source,$i-1,2);
        }

        
        $is_vowel=0;
        for($j=0;$j<count($vowels);$j++)
        {
           
            if($one_letter==$vowels[$j])
            {
              $is_vowel=1;
              
            }

        }

        if(!$is_vowel)
        {
            $letter[]=$one_letter;
        }

    }

    $word=implode("",$letter);
    $word=str_replace("̄","",$word);

    return $word;   

}

function search_no_vowels_in_verb($verb_massive,$compare_string)
{
    $compare=0;
    for($i=0;$i<count($verb_massive);$i++)
    {
        //if(mb_strpos(word_no_vowels($verb_massive[$i]),word_no_vowels($compare_string))!==false)
        if(word_no_vowels($verb_massive[$i])==word_no_vowels($compare_string))
        {
           // echo "Похоже на: ".$verb_massive[$i]."<BR><BR>";
            $compare=1;
        }
    }

    return $compare;
}

function search_no_vowels_in_verb_alternate($verb_alternate_massive,$compare_string)
{
    $compare=0;

    for($i=0;$i<count($verb_alternate_massive);$i++)
    {
        $pod_massive=explode(",",$verb_alternate_massive[$i]);

        for($j=0;$j<count($pod_massive);$j++)
        {
            $variants=find_more_with_other_letters($pod_massive[$j],"last");
            for($k=0;$k<count($variants);$k++)
            {
                //echo word_no_vowels($pod_massive[$j])."<BR>";

                if(mb_strpos(word_no_vowels($variants[$k]),word_no_vowels($compare_string))!==false)
                {
                    $compare=1;
                }

            }

        }
    }

    return $compare;
}

function search_no_vowels_in_verb_double_v($verb_alternate_massive,$compare_string)
{
    $compare=0;

    for($i=0;$i<count($verb_alternate_massive);$i++)
    {
        $pod_massive=explode(",",$verb_alternate_massive[$i]);

        for($j=0;$j<count($pod_massive);$j++)
        {
            $variants=find_more_with_other_letters($pod_massive[$j],"last");
            for($k=0;$k<count($variants);$k++)
            {
                //echo word_no_vowels($pod_massive[$j])."<BR>";

                if(mb_strpos(word_no_vowels($variants[$k]),word_no_vowels($compare_string))!==false)
                {
                    $compare=1;
                }

            }

        }
    }

    return $compare;
}


function double_string($name,$omonim,$type,$element,$ryad)
{
    /*
    $name=$verb[0];
    $omonim=$verb[1];
    $type=$verb[2];
    $element=$verb[11];
    $ryad=$verb[4];
    */

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

    return $ps_massive;
    
}

function double_double($name,$omonim,$type,$element,$ryad)
{

    $double_mool_perfect=get_perfect($name,$omonim,$type,$element,$ryad,"","",0);
 
    $sandhi = simple_sandhi($double_mool_perfect[1]."a",$name,"",0);

    $dimensions=dimensions($sandhi[0],"", $name, 1, 0, 0, 0);

    for($i=0;$i<count($dimensions[6]);$i++)
    {
       // echo $dimensions[6][$i]."<BR>";

        if($dimensions[6][$i]=="n̥̄"||$dimensions[6][$i]=="n̥")
        {
            $newword=$newword."n";
        }
        elseif($dimensions[6][$i]=="m̥̄"||$dimensions[6][$i]=="m̥")
        {
            $newword=$newword."m";
        }
        elseif($dimensions[6][$i]=="ṝ"||$dimensions[6][$i]=="ṛ")
        {
            $newword=$newword."r";
        }
        elseif($dimensions[6][$i]=="ḷ")
        {
            $newword=$newword."l";
        }
        else
        {
            $newword=$newword.$dimensions[6][$i];
        }

    }
    $newword=str_replace("#","",$newword);

    $result[0]=$sandhi[0];

    if($newword!=$sandhi[0])
    {
        $result[1]=$newword;
    }

    return $result;
}

function update_verbs_double_field($id,$name,$omonim,$type,$element,$ryad)
{
   
    //$name="Øs";
    //$omonim="";
    //$type="1";
    //$element="Ø";
    //$ryad="A1";
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

    $double_string=double_string($name,$omonim,$type,$element,$ryad);
    $double_double=double_double($name,$omonim,$type,$element,$ryad);

    if($double_string)
    {
        $update_massive=array_merge($double_double,$double_string);
    }
    else
    {
        $update_massive=$double_double;
    }

    //print_r($double_double);
    $string=implode(",",$update_massive);

        

        $query = "UPDATE verbs SET double_v=? WHERE id=?";
        $statement = $connection->prepare($query);
        $statement->bind_param('si',$string,$id);
        $statement->execute();
        $statement->close();

        echo "UPDATE OK";
}

//update_verbs_double_field($name,$omonim,$type,$element,$ryad);
?>
</div>

</body>
</html>