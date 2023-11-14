<?


	$home = $_SERVER['DOCUMENT_ROOT'];
	$dir = $home . '/';
	
	
	if (($fp = fopen("a1.txt", "r")) !== FALSE) {
	while (($data = fgetcsv($fp, 0, ";")) !== FALSE) {
		$list[] = $data;
	}
	}
	
	fclose($fp);
	//$files = $list;
	//print_r($list);
	
	//echo $list[0];
	
	for($i=0;$i<count($list);$i++)
	{
		$array=explode("	",$list[$i][0]);
		print_r($array);
		echo "<BR>";
	}
	
	

?>