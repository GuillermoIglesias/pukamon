<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
	// no deja abrir esta pagina si hay una cuenta iniciada
	if ( isset($_SESSION['user'])!="" ) {
		header("Location: welcome.php");
		exit;
	}
	
	$error = false;
	
	if( isset($_POST['btn-login']) ) {	
		
		$email = trim($_POST['email']);
		$email = strip_tags($email);
		$email = htmlspecialchars($email);
		
		$pass = trim($_POST['pass']);
		$pass = strip_tags($pass);
		$pass = htmlspecialchars($pass);
		
		if(empty($email)){
			$error = true;
			$emailError = "Por favor ingresa tu dirección de email.";
		} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
			$error = true;
			$emailError = "Por favor ingresa una dirección de email válida.";
		}
		
		if(empty($pass)){
			$error = true;
			$passError = "Por favor ingresa tu contraseña.";
		}
		

		if (!$error) {
			
			$password = hash('sha256', $pass); 
		
			$res=mysql_query("SELECT id_trainer, nick, pass FROM trainer WHERE email='$email'");
			$row=mysql_fetch_array($res);
			$count = mysql_num_rows($res); 
			
			if( $count == 1 && $row['pass']==$password ) {
				$_SESSION['user'] = $row['id_trainer'];
				header("Location: welcome.php");
			} else {
				$errMSG = "Datos ingresados son incorrectos, intenta de nuevo.";
			}
				
		}
		
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pokemon | Ingreso</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>

<div class="container">

	<div id="login-form">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
    
    	<div class="col-md-12">
        
        	<div class="form-group">
            	<h2 class="">Bienvenido! Ingresa tus Datos.</h2>
            </div>
        
        	<div class="form-group">
            	<hr />
            </div>
            
            <?php
			if ( isset($errMSG) ) {
				
				?>
				<div class="form-group">
            	<div class="alert alert-danger">
				<span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                </div>
            	</div>
                <?php
			}
			?>
            
            <div class="form-group">
            	<div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
            	<input type="email" name="email" class="form-control" placeholder="Ingresa Email" value="<?php echo $email; ?>" maxlength="40" />
                </div>
                <span class="text-danger"><?php echo $emailError; ?></span>
            </div>
            
            <div class="form-group">
            	<div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
            	<input type="password" name="pass" class="form-control" placeholder="Ingresa Contraseña" maxlength="15" />
                </div>
                <span class="text-danger"><?php echo $passError; ?></span>
            </div>
            
            <div class="form-group">
            	<hr />
            </div>
            
            <div class="form-group">
            	<button type="submit" class="btn btn-block btn-primary" name="btn-login">Ingresar</button>
            </div>
            
            <div class="form-group">
            	<hr />
            </div>
            
            <div class="form-group">
            	<a href="register.php">¿No tienes cuenta? Regístrate Aquí.</a>
            </div>
        
        </div>
   
    </form>
    </div>	

</div>

</body>
</html>
<?php ob_end_flush(); ?>