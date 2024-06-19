<?php
/* 
Based on method of Ivan Tolchelnikov
Programming by Andrei Shirobokov 2023 
*/

  require_once "db.php";

  if (isset($_POST['query'])) {
      $query = "SELECT * FROM suffixes WHERE (comments!='Тестовый' AND name!='|' AND name!='-' AND name LIKE '%{$_POST['query']}%' ) order BY lemma ";
  }
  else
  {
	       $query = "SELECT * FROM suffixes WHERE (comments!='Тестовый' AND name!='|' AND name!='-') order BY lemma ";
  }	  
	  
    if($_POST['query']==""||!$_POST['query'])
	{
		      $query = "SELECT * FROM suffixes WHERE (comments!='Тестовый' AND name!='|' AND name!='-') order BY lemma ";
	}
	  

	    $_POST['query']="";

        $result = mysqli_query($connection, $query);
        if (mysqli_num_rows($result) > 0) {
        while ($res = mysqli_fetch_array($result)) {
    
      
            $answer.="<tr>
            <td>".$res['name']. "</td>
            <td>".$res['query']." ".$res['transform']. "</td>

            <td>".$res['ryad']. "</td>
            <td>".$res['type']. "</td>


            <td>".$res['lemma']. "</td>
            <td>".$res['name_ru']. "</td>


            <td>".$res['source']. "</td>
            </tr>";
		
      }
	  
      $itog='<table class="table table-bordered">
      <thead><tr><th scope="col">Cуффикс</th><th scope="col">Запрос МП</th><th scope="col">Ряд</th>
      <th scope="col">Тип</th>
      
      <th scope="col">Код формы</th>
      <th scope="col">Словоформа</th>
      
      <th scope="col">Перевод</th></tr></thead>
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