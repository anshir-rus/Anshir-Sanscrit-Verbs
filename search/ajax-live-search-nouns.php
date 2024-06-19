<?php
/* 
Based on method of Ivan Tolchelnikov
Programming by Andrei Shirobokov 2023 
*/

  require_once "db.php";

  if (isset($_POST['query'])) {
      $query = "SELECT * FROM nouns WHERE name LIKE '%{$_POST['query']}%' OR translate LIKE '%{$_POST['query']}%' ";
  }
  else
  {
	       $query = "SELECT * FROM nouns";
  }	  
	  
    if($_POST['query']==""||!$_POST['query'])
	{
		      $query = "SELECT * FROM nouns";
	}
	  

	    $_POST['query']="";

        $result = mysqli_query($connection, $query);
        if (mysqli_num_rows($result) > 0) {
        while ($res = mysqli_fetch_array($result)) {
    
            $answer.="<tr>
            <td><a href='/noun.php?id=".$res['id']. "' class='link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover'>".$res['name']. "</a></td>
            <td>".$res['pol']. "</td>
            <td>".$res['sklonenie'].$res['sklonenie_type']. "</td>

            <td>".$res['ryad']. "</td>
            <td>".$res['type']. "</td>
        <td>".$res['translate']. "</td>
        </tr>";	
		
      }
	  
      $itog='<table class="table table-bordered">
      <thead>
<tr>
<th scope="col">Существительное</th><th scope="col">Пол</th><th scope="col">Склонение</th><th scope="col">Ряд</th><th scope="col">Тип</th><th scope="col">Перевод</th>
</tr>
</thead> <tbody>'.$answer.'</tbody></table>';
	  echo $itog;
	  
    } else {
      echo "
      <div class='alert alert-danger mt-3 text-center' role='alert'>
          Не найдено
      </div>
      ";
    }
    
  
    
    
    

  
?>