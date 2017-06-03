<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
	// Sino hay sesion iniciada, enviara a la pagina de login
	if( !isset($_SESSION['user']) ) {
		header("Location: index.php");
		exit;
	}

	// consultas para obtener los datos necesarios
	$res = mysql_query("SELECT id_trainer, nick, email FROM trainer WHERE id_trainer=".$_SESSION['user']);
	$userData = mysql_fetch_array($res);

	$urlSmall = "https://img.pokemondb.net/sprites/ruby-sapphire/normal/";

	$idEXP = $_GET[id];
	$asigMSG = $_GET[msg];

	if(empty($asigMSG)){
		$asigMSG = false;
	}

	// datos pokemon
	$poke = mysql_query("SELECT D.name, S.level, S.exp, D.sprite, S.id_poke FROM pokemon AS P, pokedex AS D, stats AS S WHERE P.id_trainer = $userData[id_trainer] AND P.id_poke = S.id_poke AND P.n_dex = D.n_dex");

	
	$pokeName =  array();
	$pokeLevel= array();
	$pokeEXP = array();
	$pokeSprite = array();
	$pokeID =  array();


	while ( $pokeData = mysql_fetch_array($poke)) {
		array_push($pokeName,$pokeData[name]);
		array_push($pokeLevel,$pokeData[level]);
		array_push($pokeEXP,$pokeData[exp]);
		array_push($pokeSprite,$pokeData[sprite]);
		array_push($pokeID,$pokeData[id_poke]);

	}

	$res2 = mysql_query("SELECT E.amount FROM exp_store AS E, item AS I WHERE I.id_exp = E.id_exp AND I.id_item = $idEXP");
	$cantidad = mysql_fetch_array($res2);

	if (isset($_POST['btn-poke'])) {
		$idPoke = $_POST['btn-poke'];

		$pokedat = mysql_query("SELECT exp FROM stats WHERE id_poke = '$idPoke' ");
		$expPoke = mysql_fetch_array($pokedat);

		$newEXP = $expPoke[exp] + $cantidad[amount];

		$nivel = floor(pow($newEXP, 1/3));

		$actualizar = mysql_query("UPDATE stats SET exp = '$newEXP', level = '$nivel' WHERE id_poke = '$idPoke' ");

		if(!empty($expPoke)){
			$destroy = mysql_query("DELETE FROM item WHERE id_item = $idEXP");
		}


		header("location: newexp.php?id=0&msg=true");
		exit;
		
	}

	if (isset($_POST['btn-volver'])) {

		header("Location: inventario.php");
		exit;
	}


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Pokemon | Experiencia </title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

	<header>
	<div class="container">
		<h1>Pukamon Experiencia </h1>
	</div>
	</header>

	<div class="container">
		<div class="titulo">
			<h2><center><strong>Aumentar <?php echo number_format($cantidad[amount],0,',','.'); ?> EXP</strong></center></h2>
		</div>
		<br></br>
	</div>
	


	<div class="login-form">
	<?php echo "<form method='post' action='newexp.php?id=".$idEXP."'>"; ?>
	<div class="container">
		<div class="main row">
			
			<div class="col-md-9">

				<?php if ($asigMSG == true) { ?>
					<div class="msg row">
						<div class="col-md-12">
							<div class="alert alert-success ">
								<center><H4>¡Has Aumentado la Experiencia!</H4></center>
							</div>
						</div>
					</div>
				<?php } ?>
				
				<div class="cointainer">
				<?php if ($asigMSG == false) { ?>
					<div class="poke row">
						<?php for($i = 0; $i < sizeof($pokeName); $i++){
		
							echo "<div class='col-md-3'>
								<center><img src=".$urlSmall.strtolower($pokeSprite[$i])." class='img-responsive' alt='".$pokeSprite[$i]."'>
								<div class='dropdown'>
									<h4>".$pokeName[$i]."<h4>
									<button class='btn btn-primary dropdown-toggle btn-block' type='button' data-toggle='dropdown'>Descripción
									<span class='caret'></span></button>
									<ul class='dropdown-menu'>
									  <li><table class='table table-bordered'>
													<tbody>
														<tr>
															<th>Nivel</th>
															<td>".$pokeLevel[$i]." </td>
														</tr>
														<tr>
															<th>EXP</th>
															<td>".number_format($pokeEXP[$i],0,',','.')." / ".number_format((($pokeLevel[$i]+1)*($pokeLevel[$i]+1)*($pokeLevel[$i]+1)),0,",",".")."</td>
														</tr>

													</tbody>
												</table></li>
									</ul>
								</div>
								<button type='submit' class='btn btn-success btn-sm btn-block' name='btn-poke' value=".$pokeID[$i]." >Aumentar EXP</button>
								</center><br/>
							</div>";
							}
						?>

	
					</div>
					<?php } ?>





				<div class="main row">
					<center><button type="submit" class="btn btn-success btn-lg" name="btn-volver" >Volver al Inventario</button></center>	
				</div>



				</div>
			</div>

			<div class="col-sm-3">
				<ul class="botones">
					<a href="batalla.php" title="Batalla"><li>Batalla</li></a>
					<a href="pokedex.php" title="Pokedex"><li>Pokedex</li></a>
					<a href="pokemon.php" title="Pokemon"><li>Pokemon</li></a>
					<a href="inventario.php" title="Inventario"><li>Inventario</li></a>
					<a href="tienda.php" title="Tienda"><li>Tienda</li></a>
					<a href="logout.php?logout" title="Logout"><li>Salir</li></a>
				</ul>
			</div>	


		</div>
	</div>

		</form>
<br></br>
</div>


</body>
</html>
<?php ob_end_flush(); ?>