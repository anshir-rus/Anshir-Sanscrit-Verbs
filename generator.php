<?
/* 
Based on method of Ivan Tolchelnikov
Programming by Andrei Shirobokov 2023 
*/
?>
<html class="h-100">
<head>
    <title>Глагольные корни</title>
    	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  	<style>
	table.table-fit {
	width: auto !important;
	table-layout: auto !important;
	}
	table.table-fit thead th,
	table.table-fit tbody td,
	table.table-fit tfoot th,
	table.table-fit tfoot td {
	width: auto !important;
	}
	</style>
</head>

<body class="d-flex flex-column min-vh-100">

<div class="container mt-5" style="max-width: 1255px">

	<form action="generator.php">
	<label for="verb-select">Выберите корень:</label>

	<select name="verbs" id="verb-select">
	<option value="">--Глаголы--</option>
	

	<?
		require_once "db.php";
		include "functions.php";



		$verb_id=$_REQUEST["verbs"];
		$suff_id=$_REQUEST["suffixies"];
		$end_id=$_REQUEST["endings"];
		$query = "SELECT * FROM verbs";
		$result = mysqli_query($connection, $query);
		if (mysqli_num_rows($result) > 0) {
			while ($res = mysqli_fetch_array($result)) {
			$omonim=$res['omonim'];
			
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
			
			if($res['id']==$verb_id){$selected="selected";}else{$selected="";}
			
			$answer="<option value=".$res['id']." $selected>".$res['name']. "$omonim_text $adhoc_text &nbsp;&nbsp;&nbsp;".$res['ryad']."-".$res['type']."</option>";
			echo $answer;
		}
		
		
		
		}
	?>


	</select>

	<select name="suffixies" id="suffixies-select">
	<option value="">--Суффиксы--</option>
	

	<?

		$query = "SELECT * FROM suffixes";
		$result = mysqli_query($connection, $query);
		
		if (mysqli_num_rows($result) > 0) {
			while ($res = mysqli_fetch_array($result)) {
			
			if($res['id']==$suff_id){$selected="selected";}else{$selected="";}	

			$transform="";
			if($res['transform']){$transform="[".$res['transform']."]";}

			$answer="<option value=".$res['id']." $selected>$transform(".$res['query'].") ".$res['name']. "</option>";
			echo $answer;
		}
		}
	?>

	</select>


	<select name="endings" id="endings-select">
	<option value="">--Окончания--</option>

	<?

		$query = "SELECT * FROM endings";
		$result = mysqli_query($connection, $query);
		
		if (mysqli_num_rows($result) > 0) {
			while ($res = mysqli_fetch_array($result)) {
			
			if($res['id']==$end_id){$selected="selected";}else{$selected="";}	
			$answer="<option value=".$res['id']." $selected>(".$res['query'].") ".$res['name']. "</option>";
			
			echo $answer;
		}
		
		
		
		}
	?>

	</select>

	<BR><BR>
	<input type="submit" value="Сгенерировать"></p> 
	</form>
	<BR>

	<?php

		if($suff_id)
		{

			$query = "SELECT * FROM suffixes WHERE id=$suff_id";
			$result = mysqli_query($connection, $query);
			
			if (mysqli_num_rows($result) > 0) {
				while ($res = mysqli_fetch_array($result)) {
					$suff_name=$res['name'];
					$suff_query=$res['query'];
					$suff_transform=$res['transform'];
				}
			}
		}

		if($end_id)
		{
			$query = "SELECT * FROM endings WHERE id=$end_id";
			$result = mysqli_query($connection, $query);
			
			if (mysqli_num_rows($result) > 0) {
				while ($res = mysqli_fetch_array($result)) {
					$end_name=$res['name'];
					$end_query=$res['query'];
					$end_transform=$res['transform'];
				// echo $end_name;
				}
			}
		}
		
		if($verb_id)
		{

			$query = "SELECT * FROM verbs WHERE id=$verb_id";
			$result = mysqli_query($connection, $query);
			
			if (mysqli_num_rows($result) > 0) {
				while ($res = mysqli_fetch_array($result)) {
					$verb_name=$res['name'];
					$verb_omonim=$res['omonim'];
					$verb_type_lat=$res['type'];
					$verb_setnost=$res['setnost'];
					$verb_pada=$res['pada'];
					
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
					
					$omonim=$res['omonim'];
				
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
					
					$answer="<tr><td>".$res['name']. "$omonim_text $adhoc_text</td><td>".$res['ryad']. "</td><td>".$res['whitney']. "</td><td>".$res['setnost']. "</td><td>".$res['type']. "</td><td>".$res['pada']. "</td><td>".$res['prs']. "</td><td>".$res['aos']. "</td><td>".$res['translate']. " ".$res['comments']. "</td></tr>";
					$itog='<table class="table table-bordered"><thead><tr><th scope="col">Корень</th><th scope="col">Ряд</th><th scope="col">Корень по Whitney</th><th scope="col">seṭ-aniṭ</th><th scope="col">Тип</th>
			<th scope="col">P/Ā</th><th scope="col">PrS</th><th scope="col">AoS</th><th scope="col">Перевод / Комментарии</th></tr></thead><tbody>'.$answer.'</tbody></table>';
			
					
				}
			}

		}
		
		//test_sandhi("budh|bhis","budh",1,1);	
	
	if($verb_id&&$suff_id)
	{	
		
		echo $itog; // инфо о корне




		$postfix_name=[$suff_name,$end_name];
		$postfix_query=[$suff_query,$end_query];
		$postfix_transform=[$suff_transform,$end_transform];
	
		$generator=get_word("",$verb_name,"",$verb_omonim,$verb_type,$verb_change,$verb_ryad,$postfix_name,$postfix_query,$postfix_transform,"1",$verb_setnost,0,1,"",0);

		echo "<BR><b>Итог с сандхи: " . $generator[0]."</b>";
		echo "<br>";
		echo "Применили правило Эмено: ".$generator[5];

		if($generator[1]!="|"&&$generator[1]!="")
		{
			echo "<BR><BR><b>Вторая форма с сандхи: " . $generator[0]."</b>";
			echo "<br>";
			echo "Применили правило Эмено: ".$generator[5];
		}
	
		//echo $generator[5];
		//print_r($generator);
		
		echo "<BR>";echo "<BR>";
	}
		

	

	if($verb_name)
	{
		echo '<hr class="hr hr-blurry" />';

		echo $itog; // инфо о корне
		
?>
			<p><u>Perfect: 3.sg.</u> <?echo get_perfect($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,0)[0];?></p>
			<p>
			<a class="btn btn-primary" data-bs-toggle="collapse" href="#collapse_p2" role="button" aria-expanded="false" aria-controls="collapseExample">
			Алгоритм образования Перфекта
			</a>
		
			</p>
			<div class="collapse" id="collapse_p2">
			<div>
				<?php
					get_perfect($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,1);
				?>
			</div>
			</div>
<?

		echo '<hr class="hr hr-blurry" />';

?>
			<p><u>О.н.в. для глаголов 3 класса</u> <?echo	get_onv3($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,0);?></p>
			<p>
			<a class="btn btn-primary" data-bs-toggle="collapse" href="#collapse_pr2" role="button" aria-expanded="false" aria-controls="collapseExample">
			Алгоритм образования Основы настоящего времени для глаголов 3 класса
			</a>
		
			</p>
			<div class="collapse" id="collapse_pr2">
			<div>
				<?php
					get_onv3($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,1);
				?>
			</div>
			</div>
<?
		echo '<hr class="hr hr-blurry" />';

?>
			<p><u>Дезидератив:</u> <?echo	get_desiderative($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,$is_open_mool,$verb_setnost,0)[0];?></p>
			<p>
			<a class="btn btn-primary" data-bs-toggle="collapse" href="#collapse_des" role="button" aria-expanded="false" aria-controls="collapseExample">
			Алгоритм образования Дезидератива
			</a>
		
			</p>
			<div class="collapse" id="collapse_des">
			<div>
				<?php
					get_desiderative($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,$is_open_mool,$verb_setnost,1);
				?>
			</div>
			</div>
<?

		
		echo '<hr class="hr hr-blurry" />';

?>
		<p><u>Интенсив:</u> <?echo	get_intensive($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,$is_open_mool,$verb_setnost,0);?></p>
		<p>
		<a class="btn btn-primary" data-bs-toggle="collapse" href="#collapse_intensive" role="button" aria-expanded="false" aria-controls="collapseExample">
		Алгоритм образования Интенсива
		</a>
	
		</p>
		<div class="collapse" id="collapse_intensive">
		<div>
			<?php
				get_intensive($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,$is_open_mool,$verb_setnost,1);
			?>
		</div>
		</div>
<?

		echo '<hr class="hr hr-blurry" />';

?>
		<p><u>Аорист 3 класа:</u> <?echo	get_aos3($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,$is_open_mool,$verb_setnost,0);?></p>
		<p>
		<a class="btn btn-primary" data-bs-toggle="collapse" href="#collapse_aos3" role="button" aria-expanded="false" aria-controls="collapseExample">
		Алгоритм образования Аориста 3 класса
		</a>
	
		</p>
		<div class="collapse" id="collapse_aos3">
		<div>
			<?php
				get_aos3($verb_name,$verb_omonim,$verb_type,$verb_change,$verb_ryad,$verb_pada,$is_open_mool,$verb_setnost,1);
			?>
		</div>
		</div>
<?

	}



		
	
	?>

	<BR><BR>

	<a href="/">Назад к списку</a>

	<BR><BR>
	
	</div>

	<?
	include "footer.php";
	?>

</body>
</html>