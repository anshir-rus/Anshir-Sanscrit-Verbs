<?php
 // setcookie('login', md5("123".$secret_word)); 
 //setcookie('login', md5($_REQUEST['email'].$secret_word));
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Глагольные корни</title>
        <meta charset="utf-8">

        <!--
        <link rel="stylesheet" href="https://bootstraptema.ru/plugins/font-awesome/4-4-0/font-awesome.min.css" />
        <script src="https://bootstraptema.ru/plugins/jquery/jquery-1.11.3.min.js"></script>
        <script src="https://bootstraptema.ru/_sf/3/394.js" type="text/javascript"></script>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    
  <style>
    @charset "UTF-8";
    .animated {
    -webkit-animation-duration: 1s;
    -moz-animation-duration: 1s;
    -o-animation-duration: 1s;
    animation-duration: 1s;
    -webkit-animation-fill-mode: both;
    -moz-animation-fill-mode: both;
    -o-animation-fill-mode: both;
    animation-fill-mode: both;
    }

    .animated.hinges {
    -webkit-animation-duration: 2s;
    -moz-animation-duration: 2s;
    -o-animation-duration: 2s;
    animation-duration: 2s;
    }

    .animated.slow {
    -webkit-animation-duration: 3s;
    -moz-animation-duration: 3s;
    -o-animation-duration: 3s;
    animation-duration: 3s;
    }

    .animated.snail {
    -webkit-animation-duration: 4s;
    -moz-animation-duration: 4s;
    -o-animation-duration: 4s;
    animation-duration: 4s;
    }

    @-webkit-keyframes shake {
    0%, 100% {-webkit-transform: translateX(0);}
    10%, 30%, 50%, 70%, 90% {-webkit-transform: translateX(-10px);}
    20%, 40%, 60%, 80% {-webkit-transform: translateX(10px);}
    }

    @-moz-keyframes shake {
    0%, 100% {-moz-transform: translateX(0);}
    10%, 30%, 50%, 70%, 90% {-moz-transform: translateX(-10px);}
    20%, 40%, 60%, 80% {-moz-transform: translateX(10px);}
    }

    @-o-keyframes shake {
    0%, 100% {-o-transform: translateX(0);}
    10%, 30%, 50%, 70%, 90% {-o-transform: translateX(-10px);}
    20%, 40%, 60%, 80% {-o-transform: translateX(10px);}
    }

    @keyframes shake {
    0%, 100% {transform: translateX(0);}
    10%, 30%, 50%, 70%, 90% {transform: translateX(-10px);}
    20%, 40%, 60%, 80% {transform: translateX(10px);}
    }

    .shake {
    -webkit-animation-name: shake;
    -moz-animation-name: shake;
    -o-animation-name: shake;
    animation-name: shake;
    }

    .login .modal-dialog{
    width: 350px;
    }
    .login .modal-footer{
    border-top: 0;
    margin-top: 0px;
    padding: 10px 20px 20px;
    }
    .login .modal-header {
    border: 0 none;
    padding: 15px 15px 15px;
    /* padding: 11px 15px; */
    }
    .login .modal-body{
    /* background-color: #eeeeee; */
    }
    .login .division {
    float: none;
    margin: 0 auto 18px;
    overflow: hidden;
    position: relative;
    text-align: center;
    width: 100%;
    }
    .login .division .line {
    border-top: 1px solid #DFDFDF;
    position: absolute;
    top: 10px;
    width: 34%;
    }
    .login .division .line.l {
    left: 0;
    }
    .login .division .line.r {
    right: 0;
    }
    .login .division span {
    color: #424242;
    font-size: 17px;
    }
    .login .box .social {
    float: none;
    margin: 0 auto 30px;
    text-align: center;
    }

    .login .social .circle{
    background-color: #EEEEEE;
    color: #FFFFFF;
    border-radius: 100px;
    display: inline-block;
    margin: 0 17px;
    padding: 15px;
    }
    .login .social .circle .fa{
    font-size: 16px;
    }
    .login .social .facebook{
    background-color: #455CA8;
    color: #FFFFFF;
    }
    .login .social .google{
    background-color: #F74933;
    }
    .login .social .github{
    background-color: #403A3A;
    }
    .login .facebook:hover{
    background-color: #6E83CD;
    }
    .login .google:hover{
    background-color: #FF7566;
    }
    .login .github:hover{
    background-color: #4D4D4d;;
    }
    .login .forgot {
    color: #797979;
    margin-left: 0;
    overflow: hidden;
    text-align: center;
    width: 100%;
    }
    .login .btn-login, .registerBox .btn-register{
    background-color: #00BBFF;
    border-color: #00BBFF;
    border-width: 0;
    color: #FFFFFF;
    display: block;
    margin: 0 auto;
    padding: 15px 50px;
    text-transform: uppercase;
    width: 100%;
    }
    .login .btn-login:hover, .registerBox .btn-register:hover{
    background-color: #00A4E4;
    color: #FFFFFF;
    }
    .login .form-control{
    border-radius: 3px;
    background-color: rgba(0, 0, 0, 0.09);
    box-shadow: 0 1px 0px 0px rgba(0, 0, 0, 0.09) inset;
    color: #FFFFFF;
    }
    .login .form-control:hover{
    background-color: rgba(0,0,0,.16);
    }
    .login .form-control:focus{
    box-shadow: 0 1px 0 0 rgba(0, 0, 0, 0.04) inset;
    background-color: rgba(0,0,0,0.23);
    color: #FFFFFF;
    }
    .login .box .form input[type="text"], .login .box .form input[type="password"] {
    border-radius: 3px;
    border: none;
    color: #333333;
    font-size: 16px;
    height: 46px;
    margin-bottom: 5px;
    padding: 13px 12px;
    width: 100%;
    }


    @media (max-width:400px){
    .login .modal-dialog{
    width: 100%;
    }
    }
</style>
<!--
<script src="/js/jquery-1.12.4-jquery.min.js"></script>
<script src="/bootstrap/js/bootstrap.min.js"></script>
	-->
    
      </head>
    <body class="d-flex flex-column min-vh-100">

  <nav class="py-0 bg-light border-bottom">
    <div class="container d-flex flex-wrap">
      <ul class="nav me-auto">
        <li class="nav-item"><a href="/" class="nav-link px-2 link-dark active" aria-current="page">Verb roots</a></li>
        <li class="nav-item"><a href="/nouns.php" class="nav-link px-2 link-dark active" aria-current="page">Nouns</a></li>
        <li class="nav-item"><a href="/preverbs.php" class="nav-link px-2 link-secondary">Preverbs</a></li>
        <li class="nav-item"><a href="/suffixies.php" class="nav-link px-2 link-secondary">Suffixes</a></li>
        <li class="nav-item"><a href="/endings.php" class="nav-link px-2 link-secondary">Endings</a></li>
        <li class="nav-item"><a href="/pronouns.php" class="nav-link px-2 link-secondary">Pronouns</a></li>
        <li class="nav-item"><a href="/particles.php" class="nav-link px-2 link-secondary">Particles</a></li>
        <li class="nav-item"><a href="/compare/" class="nav-link px-2 link-secondary">Verifications</a></li>

            <div class="btn-group">
        <li class="nav-item" data-bs-toggle="dropdown" aria-expanded="false">
        <a href="/#" class="nav-link px-2 link-secondary">Trainings</a>
        </li>

        <ul class="dropdown-menu">
          <li class="dropdown-item"><a href="/trainings/devanagari.php" class="nav-link px-2 link-secondary">Devanāgarī</a></li>
          <li class="dropdown-item"><a href="/trainings/memory.php" class="nav-link px-2 link-secondary">Memory</a></li>
        </ul>
      </div>

             

      </ul>

      <?php

/*
if (isset($_COOKIE['login'])) {
	echo $_COOKIE['login']; // Значение 1
}
*/
      //echo "COOKIE:".$_COOKIE['login'].$_SESSION['login'];

    if ($_COOKIE['login']) { 

      ?>
        <ul class="nav justify-content-end">
       
        <li class="nav-item"><a href="/profile.php" class="nav-link px-2 link-secondary">Namaste, <? echo $_COOKIE['name']; ?>!</a></li>
    
        <li class="nav-item"><a class="btn btn-default" data-toggle="modal" href="javascript:void(0)" onclick="deleteCookie('login')">Log out</a></li>
        </ul>
      <?

    }
    else
    {
      ?>

        <ul class="nav justify-content-end">
        <li class="nav-item"><a class="btn btn-default" data-toggle="modal" href="javascript:void(0)" onclick="openLoginModal();">Log in</a></li>
        <li class="nav-item"><a class="btn btn-default" data-toggle="modal" href="javascript:void(0)" onclick="openRegisterModal();">Register</a></li>
        </ul>

      <?php
    }
      ?>

    </div>
  </nav>

  
 <div class="modal fade login" id="loginModal">
 <div class="modal-dialog login animated">
 <div class="modal-content">
 <div class="modal-header">

 <h5 class="modal-title">Login</h5>
 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
 
 </div>
 <div class="modal-body"> 
 <div class="box">
 <div class="content">

<!--

    <div class="social">

      <a class="oauth-container btn darken-4 white black-text" href="/users/google-oauth/" style="text-transform:none">
          <div class="left">
              <img width="20px" alt="Google sign-in" src="https://www.svgrepo.com/show/475656/google-color.svg" /> Google
          </div>
        
      </a>

    </div>

    <div class="division">
          <div class="line l"></div>
          <span>or</span>
          <div class="line r"></div>
    </div>

-->

    <div class="error" id="error"></div>
    <div class="alert alert-success" role="alert" id="err_reg" style="display:none;"></div>
    <div class="form loginBox" id="auth_form">
                  <form method="" action="" accept-charset="UTF-8">
                  <input id="auth_email" class="form-control" type="text" placeholder="Email" name="email">
                  <input id="auth_password" class="form-control" type="password" placeholder="Password" name="password">
                  <input class="btn btn-default btn-login" type="button" id="btn_auth" value="Login" onclick="loginAjax()">
                  </form>
    </div>
    </div>
 </div>

 <div class="box">
    <div class="content registerBox" style="display:none;">
        
        <div class="form" id="registration_form">
              <form method="" html="{:multipart=>true}" data-remote="true" action="" accept-charset="UTF-8">
              <input id="reg_name" class="form-control" type="text" placeholder="Name" name="name">
              <input id="reg_email" class="form-control" type="text" placeholder="Email" name="email">
              <input id="reg_password" class="form-control" type="password" placeholder="Password" name="password">
              <input id="reg_password_confirmation" class="form-control" type="password" placeholder="Repeat Password" name="password_confirmation">
              <input class="btn btn-default btn-register" type="button" id="btn_register" value="Create account" name="commit">
              </form>
        </div>
    </div>
 </div>

 </div>
 <div class="modal-footer">
    <div class="forgot login-footer">
        <span>Looking to 
        <a href="javascript: showRegisterForm();">create an account</a>
        ?</span>
    </div>
    <div class="forgot register-footer" style="display:none">
        <span>Already have an account?</span>
        <a href="javascript: showLoginForm();">Login</a>
    </div>
 </div> 
 </div>
 </div>
 </div>


 
<script>
function loginAjax()
{
		
    var email 	 = $('#auth_email').val();
		var password = $('#auth_password').val();

    if(email == ''){ //check email not empty
		  alert('Please enter email address'); 
		}
    else
    {
      $.ajax({
				url: '/authorization/auth.php',
				type: 'post',
				data: { email: email, password: password },
				success: function(response){
					if(response!='')
          {
            $('#err_reg').show();
            $('#error').hide();
            //$('#err_reg').html(response);
            $('#err_reg').html("Успешная авторизация!");
            window.location.replace("index.php");
            
          }
          else
          {
            //$('#err_reg').show();
            //$('#err_reg').html(response);
            shakeModal();
          }
				}
			});
				
			$('#auth_form')[0].reset();
    }

}

function deleteCookie(name) {
  document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
  window.location.replace("/index.php");
}
	
	$(document).on('click','#btn_register',function(e){
		
		e.preventDefault();
			
    var name 	 = $('#reg_name').val();
		var email 	 = $('#reg_email').val();
		var password = $('#reg_password').val();
    var password_confirmation = $('#reg_password_confirmation').val();

    
			
		var atpos  = email.indexOf('@');
			
    if(name == ''){ //check email not empty
		  alert('Please enter name'); 
		}
		else if(email == ''){ //check email not empty
		  alert('Please enter email address'); 
		}
		else if(atpos < 1 ){ //check valid email format
			alert('Please enter valid email address'); 
		}
		else if(password == ''){ //check password not empty
			alert('Please enter password'); 
		}
    else if(password != password_confirmation){ //check password not empty
			alert('Passwords mismatch'); 
		}
		else{			


			$.ajax({
				url: '/authorization/register.php',
				type: 'post',
				data: { name: name,email: email, password: password },
				success: function(response){
					if(response==1)
          {
            $('#err_reg').show();
            $('#err_reg').html("Успешная регистрация!<BR> Теперь вы можете войти в аккаунт");
            showLoginForm();
          }
				}
			});
				
			$('#registraion_form')[0].reset();
		}
	});

</script>

<?php
include "db.php";
//header('Content-Type: text/html; charset=utf-8');
?>