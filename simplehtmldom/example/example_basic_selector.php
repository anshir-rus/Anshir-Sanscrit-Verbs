<?php
// example of how to use basic selector to retrieve HTML contents
set_time_limit(0);
ini_set('memory_limit', '512M');
include('../simple_html_dom.php');
 
// get DOM from URL or file
//$getcontents = file_get_contents('https://samskrtam.ru/parallel-corpus/01_rigveda.html',false,null);

//$html=file_get_html('https://samskrtam.ru/parallel-corpus/01_rigveda.html');

$big=file_get_html('https://samskrtam.ru/parallel-corpus/01_rigveda.html');
//$big=str_replace("chapter_block iast","chapter_block_iast",$big);
//$big=str_replace("chapter_block iast","chapter_block_iast",$big);
$big=str_replace("a href","a",$big);

//echo $big;

/*
'<div class="citation_block">
<div class="range" title="Ригведа. I. 1. 4" id="Ригведа. Мандалы I-IV, 1989: 5">4</div>
<div class="chapter_content">
<div class="chapter_block iast">
agne yaṃ yajñam adhvaraṃ viśvataḥ paribhūr asi ।<br>sa id deveṣu gacchati ॥4॥<br>
</div>
<div class="chapter_block translation">
О Агни, жертва (и) обряд,<br>Которые ты охватываешь со всех сторон,<br>Именно они идут к богам.
</div>
</div>
<div class="comments">
<div class="comment_item" id="comment_1_4">
<span class="comment_number" 
title="Ригведа. Мандалы I-IV, 1989: 546">4</span>. 
<span class="comment_text">
…обряд (adhvará-)… — Здесь имеется в виду совокупность ритуальных действий, осуществляемых жрецами-адхварью, или же торжественное шествие при жертвоприношении (ср. ádhvan- «путь»).
</span></div>
</div>';
*/

/*
$big='<div class="citation_block">
<div class="range" title="Ригведа. I. 164. 36" id="Ригведа. Мандалы I-IV, 1989: 204">36</div>
<div class="chapter_content">
<div class="chapter_block iast">
saptārdhagarbhā bhuvanasya reto viṣṇos tiṣṭhanti pradiśā vidharmaṇi ।<br>te dhītibhir manasā te vipaścitaḥ paribhuvaḥ pari bhavanti viśvataḥ ॥36॥<br>
</div>
<div class="chapter_block translation">
Семеро полуотпрысков по приказу Вишну<br>Заняты распределением семени мироздания.<br>Они — молитвами, они, вдохновенные, — мыслью<br>(Всех) превосходят во всем, (эти) превосходящие.
</div>
</div>
<div class="comments">
<div class="comment_item" id="comment_164_36a-b">
<span class="comment_number" 
title="Ригведа. Мандалы I-IV, 1989: 648">36a-b</span>. 
<span class="comment_text">
Семеро полуотпрысков… — Речь идет о семи Ангирасах или риши — см. примеч. к I, 1, 6. Они названы полуотпрысками, потому что, по ведийским представлениям, родились из семени, пролитого Отцом-небом (см., например, I, 71, 8) и были выпестованы в дальнейшем Агни. Заняты распределением (tiṣṭhanti vídharmaṇi)… — Букв. «нахо- дятся, чтобы распределять» — в этом глагольном сочетании vídharmaṇi — инфинитив, который управляет прямым дополнением «семя» (rе́tas-)… семени мироздания. — Сомы.
</span></div>
</div>
<div class="clear"></div>
</div>';
*/
//$bigplain=$html->plaintext;

$target="anuyacchamānāḥ";
$pos=mb_strpos($big,$target);

echo $pos."<BR>";
$str=substr($big,$pos+10000,10000);



$str=str_replace($target,"<b>".$target."</b>",$str);

//echo $str."<BR><BR>";

$html=str_get_html($str);
//echo $html;
//$html=str_replace("chapter_block iast","chapter_block_iast",$html);

// find all link
//foreach($html->find('a') as $e) 
//    echo $e->href . '<br>';

// find all image
//foreach($html->find('img') as $e)
 //   echo $e->src . '<br>';

// find all image with full tag
//foreach($html->find('img') as $e)
//    echo $e->outertext . '<br>';

// find all div tags with id=gbar

foreach($html->find('div.range') as $e)
    echo "<a href='https://samskrtam.ru/parallel-corpus/01_rigveda.html#chapter_".$e->innertext ."'>Ссылка</a><br><br>";

foreach($html->find('div.chapter_block') as $e)
    echo $e->innertext . '<br><br>';

    /*
// find all span tags with class=gb1
foreach($html->find('span.gb1') as $e)
    echo $e->outertext . '<br>';

// find all td tags with attribite align=center
foreach($html->find('td[align=center]') as $e)
    echo $e->innertext . '<br>';
    
// extract text from table
echo $html->find('td[align="center"]', 1)->plaintext.'<br><hr>';
*/
// extract text from HTML
//echo $html->plaintext;
?>