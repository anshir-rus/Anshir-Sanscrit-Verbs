<?
/* 
Based on method of Ivan Tolchelnikov
Programming by Andrei Shirobokov 2023 
*/

	require_once "db.php";

	$ryad="A1";

	$mysqli = new mysqli($nameServer, $userName, $password, $DBname);
	if ($mysqli -> connect_error) {
	  printf("Соединение не удалось: %s\n", $mysqli -> connect_error);
	  exit();
	};

	mysqli_set_charset($mysqli, 'utf8');
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		$home = $_SERVER['DOCUMENT_ROOT'];
		$dir = $home . '/';
		
		
		if (($fp = fopen("$ryad.txt", "r")) !== FALSE) {
		while (($data = fgetcsv($fp, 0, ";")) !== FALSE) {
			$list[] = $data;
		}
		}
		
		fclose($fp);
	//$files = $list;
	//print_r($list);
	
	//echo $list[0];
	
	for($i=1;$i<count($list);$i++)
	{
		$array=explode("	",$list[$i][0]);
		print_r($array);
		echo "<BR>";
		
		$query = "INSERT INTO verbs (ryad,name,omonim,whitney,setnost,type,pada,prs,aos,translate,element,adhoc,comments,padezh,para) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$statement = $mysqli->prepare($query);

		$padezh="";
		$para=0;

		//bind parameters for markers, where (s = string, i = integer, d = double,  b = blob)
		$statement->bind_param('ssisssssssssssi', $ryad,$array[0],$array[1], $array[2],$array[3],$array[4], $array[5],$array[6],$array[7],$array[8], $array[9],$array[10],$array[11],$padezh,$para);
	
	

		if($statement->execute()){
			print 'Success! ID of last inserted record is : ' .$statement->insert_id .'<br />';
		}else{
			die('Error : ('. $mysqli->errno .') '. $mysqli->error);
		}
		$statement->close();
		
	}
	
		
	

?>