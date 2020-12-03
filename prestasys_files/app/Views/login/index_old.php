<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login V9</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.ico" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?=base_url("/assets/Login_v9/vendor/bootstrap/css/bootstrap.min.css")?>">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?=base_url("/assets/Login_v9/fonts/font-awesome-4.7.0/css/font-awesome.min.css")?>">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?=base_url("/assets/Login_v9/fonts/iconic/css/material-design-iconic-font.min.css")?>">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?=base_url("/assets/Login_v9/vendor/animate/animate.css")?>">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?=base_url("/assets/Login_v9/vendor/css-hamburgers/hamburgers.min.css")?>">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?=base_url("/assets/Login_v9/vendor/animsition/css/animsition.min.css")?>">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?=base_url("/assets/Login_v9/vendor/select2/select2.min.css")?>">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?=base_url("/assets/Login_v9/vendor/daterangepicker/daterangepicker.css")?>">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?=base_url("/assets/Login_v9/css/util.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=base_url("/assets/Login_v9/css/main.css")?>">
    <!--===============================================================================================-->
</head>

<body>
<script>

//syscreditos Nombre de la App
window.fbAsyncInit = function() {
  
FB.init({
  appId            : '803296487118312',
  autoLogAppEvents : true,
  xfbml            : true,
  version          : 'v8.0'
});
};

function   Sign_In(){
    FB.login(function(response) {
    if (response.authResponse) {
        
        let userID= response.authResponse.userID;
        let userName= response.name ;

     console.log('Welcome!  Fetching your information.... ',  userID);

     FB.api('/me', function(response) {
       console.log('Good to see you, ' + response.name + '.');
     });
    } else {
     console.log('User cancelled login or did not fully authorize.');
    }
});
}


function Sign_Out(){
    FB.logout(function(response) {
   // Person is now logged out
});
}

function  Usuario_Conectado(){
    FB.getLoginStatus(function(response) {
    console.log(response);
});
}



</script>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>





    <div class="container-login100" style="background-image: url('<?=base_url("assets/Login_v9/images/bg-01.jpg")?>');">
<?php 
if( isset( $errorSesion) ){ echo view("plantillas/error", array("mensaje"=> $errorSesion ));}
?>

        <div class="wrap-login100 p-l-55 p-r-55 p-t-80 p-b-30">
            <form class="login100-form validate-form" action="<?=base_url('usuario/sign_in')?>" method="POST">
                <span class="login100-form-title p-b-37">
					Sign In
				</span>

                <div class="wrap-input100 validate-input m-b-20" data-validate="Enter username or email">
                    <input class="input100" type="text" name="usua" id="usua" placeholder="username or email">
                    <span class="focus-input100"></span>
                </div>

                <div class="wrap-input100 validate-input m-b-25" data-validate="Enter password">
                    <input class="input100" type="password" name="pass" id="pass" placeholder="password">
                    <span class="focus-input100"></span>
                </div>

                <div class="container-login100-form-btn">
                    <button class="login100-form-btn">
						Sign In
					</button>
                </div>

                <div class="text-center p-t-57 p-b-20">
                    <span class="txt1">
						Or login with
					</span>
                </div>

                <div class="flex-c p-b-112">
                    <a onclick="Sign_In()" href="#" class="login100-social-item">
                        <i class="fa fa-facebook-f"></i>
                    </a>

                    <a href="#" class="login100-social-item">
                        <img src="images/icons/icon-google.png" alt="GOOGLE">
                    </a>
                </div>

                <div class="text-center">
                    <a href="#" class="txt2 hov1">
						Sign Up
					</a>
                </div>
            </form>


        </div>
    </div>



    <div id="dropDownSelect1"></div>

    <!--===============================================================================================-->
    <script src="<?=base_url("assets/Login_v9/vendor/jquery/jquery-3.2.1.min.js")?>"></script>
    <!--===============================================================================================-->
    <script src="<?=base_url("assets/Login_v9/vendor/animsition/js/animsition.min.js")?>"></script>
    <!--===============================================================================================-->
    <script src="<?=base_url("assets/Login_v9/vendor/bootstrap/js/popper.js")?>"></script>
    <script src="<?=base_url("assets/Login_v9/vendor/bootstrap/js/bootstrap.min.js")?>"></script>
    <!--===============================================================================================-->
    <script src="<?=base_url("assets/Login_v9/vendor/select2/select2.min.js")?>"></script>
    <!--===============================================================================================-->
    <script src="<?=base_url("assets/Login_v9/vendor/daterangepicker/moment.min.js")?>"></script>
    <script src="<?=base_url("/assets/Login_v9/vendor/daterangepicker/daterangepicker.js")?>"></script>
    <!--===============================================================================================-->
    <script src="<?=base_url("assets/Login_v9/vendor/countdowntime/countdowntime.js")?>"></script>
    <!--===============================================================================================-->
    <script src="<?=base_url("assets/Login_v9/js/main.js")?>"></script>



  
    <script> 

var tipoDeUsuario="";

/**EXISTE USUARIO? */
function userExists(){
    return new Promise((exito, fallo)=>{
        let name= $("#usu").val();
        $.ajax( { async: false, url:"<?=base_url("usuario/getByName")?>/"+name, success:function(res){
            let obj= JSON.parse(  res );
            if( "error" in obj){   alert("El usuario "+name+" no existe"); fallo();
            }else{   $("#tipouser").val(  obj.tipousuario );    exito();   }
        }}
        );
    });
    
}
/**nick ingresado */
function nickIngresado(){
    if( $("#usu").val() == "")
   { alert("Ingrese su nick"); $("#usu").focus();
    return false;}  return true; 
}
/** PASSWORD INGRESADA */
function passwordIngresado(){
    if( $("#pass").val() == "")
   { alert("Ingrese su clave");
    return false;}  return true; 
}

 

   

   


    


function ciudades(){
    $.get("/crediweb/assets/ciudades.json", function( res){
        console.log( res);
        document.getElementById("test").innerHTML= res;
    } );
}



  </script>
</body>

</html>