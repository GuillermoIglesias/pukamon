<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
	// Sino hay sesion iniciada, enviara a la pagina de login
	if( !isset($_SESSION['user']) ) {
		header("Location: index.php");
		exit;
	}

	// Si el usuario tiene algun pokemon, no es una cuenta nueva
	$res0 = mysql_query("SELECT P.n_dex FROM pokemon AS P WHERE P.id_trainer = ".$_SESSION['user']);
	$pokeFirst = array();
	while($pokeCheck = mysql_fetch_array($res0)){
		array_push($pokeFirst,$pokeCheck[n_dex]);
	}

	if(sizeof($pokeFirst) != 0){
		header("Location: pokemon.php");
		exit;
	}

	// consultas para obtener los datos necesarios
	$res = mysql_query("SELECT id_trainer, nick, email FROM trainer WHERE id_trainer=".$_SESSION['user']);
	$userData = mysql_fetch_array($res);

	// datos pokemon iniciales
	$res1 = mysql_query("SELECT P.n_dex, P.name, P.id_type1, P.id_type2, P.description FROM pokedex AS P WHERE P.name = 'Bulbasaur' OR P.name = 'Charmander' OR P.name = 'Squirtle'");

	$pokeNdex = array();
	$pokeName = array();
	$pokeT1 = array();
	$pokeT2 = array();
	$pokeDes = array();
	
	array_push($pokeNdex,0);
	array_push($pokeName," ");
	array_push($pokeT1," ");
	array_push($pokeT2," ");
	array_push($pokeDes," ");
	
	while($pokeData = mysql_fetch_array($res1)){
		array_push($pokeNdex,$pokeData[n_dex]);
		array_push($pokeName,$pokeData[name]);
		array_push($pokeT1,$pokeData[id_type1]);
		array_push($pokeT2,$pokeData[id_type2]);
		array_push($pokeDes,$pokeData[description]);
	}

	$error = false;
	if( isset($_POST['btn-start']) ) {

		$option = $_POST['starter'];

		if(!$option){
			$error = true;
			$errorOPT = "ERROR: ¡No has elegido tu Pokemon inicial!";
		}

		if(!$error){
			$errorQ = true;
			$errorI = true;
			switch($option){
				case 1:
					$pokemon = mysql_query("INSERT INTO pokemon(id_trainer, n_dex) VALUES('$userData[id_trainer]','1')");
					$id_poke = mysql_insert_id();
					$pokeStats = mysql_query("INSERT INTO stats(id_poke, level, atk1, atk2, exp) VALUES('$id_poke','5','MB19','MB14','125')");
					if ($pokemon && $pokeStats){ $errorQ = false; }
					break;
				case 2:
					$pokemon = mysql_query("INSERT INTO pokemon(id_trainer, n_dex) VALUES('$userData[id_trainer]','4')");
					$id_poke = mysql_insert_id();
					$pokeStats = mysql_query("INSERT INTO stats(id_poke, level, atk1, atk2, exp) VALUES('$id_poke','5','MB02','MB03','125')");
					if ($pokemon && $pokeStats){ $errorQ = false; }
					break;
				case 3:
					$pokemon = mysql_query("INSERT INTO pokemon(id_trainer, n_dex) VALUES('$userData[id_trainer]','7')");
					$id_poke = mysql_insert_id();
					$pokeStats = mysql_query("INSERT INTO stats(id_poke, level, atk1, atk2, exp) VALUES('$id_poke','5','MB19','MB05','125')");
					if ($pokemon && $pokeStats){ $errorQ = false; }
					break;
			}

			$pokeball1 = mysql_query("INSERT INTO item(id_trainer, id_pokeball) VALUES('$userData[id_trainer]','pokeball')");
			
			$pokeball2 = mysql_query("INSERT INTO item(id_trainer, id_pokeball) VALUES('$userData[id_trainer]','pokeball')");
			
			$pokeball3 = mysql_query("INSERT INTO item(id_trainer, id_pokeball) VALUES('$userData[id_trainer]','pokeball')");

			$superball1 = mysql_query("INSERT INTO item(id_trainer, id_pokeball) VALUES('$userData[id_trainer]','superball')");
			
			$superball2 = mysql_query("INSERT INTO item(id_trainer, id_pokeball) VALUES('$userData[id_trainer]','superball')");
			
			$ultraball = mysql_query("INSERT INTO item(id_trainer, id_pokeball) VALUES('$userData[id_trainer]','ultraball')");

			$gold = mysql_query("INSERT INTO gold(id_trainer, amount) VALUES('$userData[id_trainer]','1000')");

			if($pokeball1 && $pokeball2 && $pokeball3 && $superball1 && $superball2 && $ultraball&& $gold){ $errorI = false; }

			if (!$errorQ && !$errorI ){
				header("Location: pokemon.php");
			} else {
				$errorOPT = "ERROR: Hay un problema con las consultas SQL.";
			}	
		}
	}

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Pokemon | Bienvenido</title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
	<header>
	<div class="container">
		<h1>¡Bienvenido <?php echo $userData[nick]."!"; ?> </h1>
	</div>
	</header>

	<div class="container">
		<div class="titulo">
			<h1><center><strong>¡Elige tu Pokemon Inicial!</strong></center></h1>
		</div>
	</div>
	
	<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
		<div class="container">
			
			<?php for($i = 1; $i < sizeof($pokeNdex); $i++){
				echo "<div class='col-sm-4'>
					<center><img src='http://assets.pokemon.com/assets/cms2/img/pokedex/full/00".$pokeNdex[$i].".png' class='img-responsive' alt=".$pokeName[$i]."></center>
					<table class='table table-striped table-bordered'>
						<tbody>
							<tr>
								<th>Nombre</th>
								<td>".$pokeName[$i]."</td>
							</tr>
							<tr>
								<th>Tipo</th>
								<td>";
								if($pokeT2[$i]){echo "<img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$pokeT1[$i]."-type.png'  width='60' height='25' alt='".$pokeT1[$i]."-type'> <img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$pokeT2[$i]."-type.png' width='60' height='25' alt='".$pokeT2[$i]."-type'>";} else{echo  "<img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$pokeT1[$i]."-type.png' class='img-responsive'  width='60' height='25' alt='".$pokeT1[$i]."-type'>";}
								echo"</td>
							</tr>
							<tr>
								<th>Descripción</th>
								<td>".$pokeDes[$i]."</td>
							</tr>
						</tbody>
					</table>
					<div class='radio'>
		  				<center><input type='radio' name='starter' id=".$pokeName[$i]." value=".$i."><label for=".$pokeName[$i]."><strong>Elegir a ".$pokeName[$i]."</strong></label></center>
					</div>
				</div>
				";}
			?>
			
		</div>

		<div class="container">
			<br>
			<div class="anuncio row">
				<div class="col-sm-4">
					<center><h1><strong>Además recibirás:</strong></h1><center>
				</div>
				<div class="col-sm-1">
					<img src="http://docencia.eit.udp.cl/~17407128/pukamon/images/coin.svg" class='img-responsive' alt="Coins">
				</div>
				<div class="col-sm-1">
					<img src="http://docencia.eit.udp.cl/~17407128/pukamon/images/pokeball.png" class='img-responsive' alt="Pokeball">
				</div>
				<div class="col-sm-1">
					<img src="http://docencia.eit.udp.cl/~17407128/pukamon/images/pokeball.png" class='img-responsive' alt="Pokeball">
				</div>
				<div class="col-sm-1">
					<img src="http://docencia.eit.udp.cl/~17407128/pukamon/images/pokeball.png" class='img-responsive' alt="Pokeball">
				</div>
				<div class="col-sm-1">
					<img src="http://docencia.eit.udp.cl/~17407128/pukamon/images/superball.png" class='img-responsive' alt="Superball">
				</div>
				<div class="col-sm-1">
					<img src="http://docencia.eit.udp.cl/~17407128/pukamon/images/superball.png" class='img-responsive' alt="Superball">
				</div>
				<div class="col-sm-1">
					<img src="http://docencia.eit.udp.cl/~17407128/pukamon/images/ultraball.png" class='img-responsive' alt="Utraball">
				</div>
			</div>
			
			<div class="boton row">
				<br>
				<?php if (isset($errorOPT)) { ?>
				<div class="col-md-12">
					<div class="alert alert-danger alert-dismissible">
						<button class="close" aria-label="close"><span>&times;</span></button>
						<center><?php echo $errorOPT; ?></center>
					</div>
				</div>
				<?php } ?>
				<center><input type="submit" class="btn btn-primary btn-lg" name="btn-start" value="¡COMENZAR!" /></center>
				<br>
				
			</div>
		</div>
	</form>

</body>
</html>
<?php ob_end_flush(); ?>