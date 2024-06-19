<?php
/* 
Based on method of Ivan Tolchelnikov
Programming by Andrei Shirobokov 2023 
*/

require_once "../db.php";

  if (isset($_POST['query'])) {
      $query = "SELECT * FROM endings WHERE name LIKE '%{$_POST['query']}%' ";
  }
  else
  {
	       $query = "SELECT * FROM endings ";
  }	  
	  
    if($_POST['query']==""||!$_POST['query'])
	{
		      $query = "SELECT * FROM endings ";
	}
	  

	    $_POST['query']="";

        $result = mysqli_query($connection, $query);

        if (mysqli_num_rows($result) > 0) {
        while ($res = mysqli_fetch_array($result)) {
        
        $id=$res['id'];
        
        $answer.="<tr>
        <td>".$res['name']. "</td>
        <td>".$res['nabor']."</td>
        <td>".$res['pada']."</td>
        <td>".$res['lico']. "</td>
        <td>".$res['chislo']. "</td>
        
        <td>".$res['query']." ".$res['transform']. "</td>
        
        <td>".$res['lemma']. "</td>
        
        </tr>";
        }
        
        $itog='<table class="table table-bordered">
        <thead><tr><th scope="col">Окончание</th>
        <th scope="col">Набор</th>
        <th scope="col">Залог</th>
        <th scope="col">Лицо</th>
        <th scope="col">Число</th>
        <th scope="col">Запрос МП</th>
        <th scope="col">Код формы</th>
        
        
        </tr></thead>
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