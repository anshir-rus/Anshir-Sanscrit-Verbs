<?php
/* 
Based on method of Ivan Tolchelnikov
Programming by Andrei Shirobokov 2023 
*/

  require_once "db.php";

  if (isset($_POST['query'])) {
      $query = "SELECT * FROM particles WHERE  name LIKE '%{$_POST['query']}%' OR translate LIKE '%{$_POST['query']}%'";
      //$query = "SELECT * FROM preverbs";
      //echo $query;
  }
  else
  {
	       $query = "SELECT * FROM particles";
  }	  
	  
    if($_POST['query']==""||!$_POST['query'])
	{
		      $query = "SELECT * FROM particles";
	}
	  

	    $_POST['query']="";

        $result = mysqli_query($connection, $query);
        if (mysqli_num_rows($result) > 0) {
        while ($res = mysqli_fetch_array($result)) {
    
      
            $answer.="<tr>
            <td>".$res['name']. "</td>
            <td>".$res['translate']. "</td>
            <td>".$res['type']. "</td>
            </tr>";
		
      }
	  
      $itog='<table class="table table-bordered">
      <thead>
      <tr>
      <th scope="col">Частица</th><th scope="col">Перевод</th><th scope="col">Тип</th>
      </tr>
      </thead>
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