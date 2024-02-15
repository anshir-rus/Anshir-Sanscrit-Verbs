<?php
/* 
Based on method of Ivan Tolchelnikov
Programming by Andrei Shirobokov 2023 
*/

  require_once "db.php";

  if (isset($_POST['query'])) {
      $query = "SELECT * FROM pronouns WHERE name LIKE '%{$_POST['query']}%' OR padezh LIKE '%{$_POST['query']}%' OR translate LIKE '%{$_POST['query']}%'  OR short_translate LIKE '%{$_POST['query']}%' ";
  }
  else
  {
	       $query = "SELECT * FROM pronouns";
  }	  
	  
    if($_POST['query']==""||!$_POST['query'])
	{
		      $query = "SELECT * FROM pronouns";
	}
	  

	    $_POST['query']="";

        $result = mysqli_query($connection, $query);
        if (mysqli_num_rows($result) > 0) {
        while ($res = mysqli_fetch_array($result)) {
    
            $answer.="<tr>
        <td>".$res['name']. "</td>
        <td>".$res['padezh']. "</td>
        <td>".$res['steam']. "</td>
        <td>".$res['translate']. "</td>
        <td>".$res['short_translate']. "</td>
        <td>".$res['source']. "</td>
        </tr>";	
		
      }
	  
      $itog='<table class="table table-bordered">
      <thead><tr><th scope="col">Местоимение</th><th scope="col">Падеж</th><th scope="col">Основа</th><th scope="col">Перевод</th><th scope="col">Краткий перевод</th><th scope="col">Источник</th></tr></thead>
      <tbody>'.$answer.'</tbody></table>';
	  echo $itog;
	  
    } else {
      echo "
      <div class='alert alert-danger mt-3 text-center' role='alert'>
          Не найдено
      </div>
      ";
    }
    
  
    
    
    

  
?>