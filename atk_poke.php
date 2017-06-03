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

	$pokeNew = $_GET[n];
	$idItem = $_GET[id];
	$asigMSG = $_GET[msg];

	if(empty($asigMSG)){
		$asigMSG = false;
	}

	$item = mysql_query("SELECT id_atk FROM item WHERE id_item = '$idItem' ");
	$atkNew = mysql_fetch_array($item);

	$dataATK = mysql_query("SELECT  name, id_type, power, accuracy, pp FROM atk WHERE id_atk = '$atkNew[id_atk]' ");
	$DatosAtk = mysql_fetch_array($dataATK);



	$new = mysql_query("SELECT n_dex FROM pokemon WHERE id_poke = $pokeNew");
	$newn = mysql_fetch_array($new);


	// datos pokemon
	$res2 = mysql_query("SELECT P.id_poke, D.name, P.n_dex, S.exp, S.level, D.sprite, ((D.hp_multi*S.level)+D.hp_base) AS hp, ((D.atk_multi*S.level)+D.atk_base) AS atk, ((D.def_multi*S.level)+D.def_base) AS def, D.id_type1, D.id_type2  FROM pokemon AS P, stats AS S, pokedex AS D  WHERE S.id_poke = $pokeNew AND P.id_poke = $pokeNew AND D.n_dex = $newn[n_dex]");


	$pokeData = mysql_fetch_array($res2);


	$urlBig = "https://img.pokemondb.net/artwork/";


		// datos sobre ataques
	$res3 = mysql_query("SELECT A.id_atk, A.name, A.id_type, A.pp, A.power, A.accuracy FROM atk AS A, (SELECT atk1 FROM stats WHERE id_poke = $pokeNew) AS S1, (SELECT atk2 FROM stats WHERE id_poke = $pokeNew) AS S2, (SELECT atk3 FROM stats WHERE id_poke = $pokeNew) AS S3, (SELECT atk4 FROM stats WHERE id_poke = $pokeNew) AS S4 WHERE A.id_atk = S1.atk1 OR A.id_atk = S2.atk2 OR A.id_atk = S3.atk3 OR A.id_atk = S4.atk4");

	$pokeID = array();
	$pokeName = array();
	$pokeType = array();
	$pokePP = array();
	$pokePower = array();
	$pokeAccuracy = array();

	while ($pokeMove = mysql_fetch_array($res3)) {
		array_push($pokeID, $pokeMove[id_atk]);
		array_push($pokeName, $pokeMove[name]);
		array_push($pokeType, $pokeMove[id_type]);
		array_push($pokePP, $pokeMove[pp]);
		array_push($pokePower, $pokeMove[power]);
		array_push($pokeAccuracy, $pokeMove[accuracy]);
	}

	if (isset($_POST['btn-atk'])) {
		$idCambio = $_POST['btn-atk'];

		$res4 = mysql_query("SELECT atk1, atk2, atk3, atk4 FROM stats WHERE id_poke = '$pokeNew' ");
		$atkDataC = mysql_fetch_array($res4);



		if ($atkDataC[atk1] == $idCambio) {
			$cambiar = mysql_query("UPDATE stats SET atk1 = '$atkNew[id_atk]' WHERE id_poke = '$pokeNew' ");

			if(!empty($atkDataC)){
				$destroy = mysql_query("DELETE FROM item WHERE id_item = $idItem");
			}

		}

		else if ($atkDataC[atk2] == $idCambio) {
			$cambiar = mysql_query("UPDATE stats SET atk2 = '$atkNew[id_atk]' WHERE id_poke = '$pokeNew' ");

			if(!empty($atkDataC)){
				$destroy = mysql_query("DELETE FROM item WHERE id_item = $idItem");
			}
		}
		else if ($atkDataC[atk3] == $idCambio) {
			$cambiar = mysql_query("UPDATE stats SET atk3 = '$atkNew[id_atk]' WHERE id_poke = '$pokeNew' ");

			if(!empty($atkDataC)){
				$destroy = mysql_query("DELETE FROM item WHERE id_item = $idItem");
			}
		}
		else if ($atkDataC[atk4] == $idCambio) {
			$cambiar = mysql_query("UPDATE stats SET atk4 = '$atkNew[id_atk]' WHERE id_poke = '$pokeNew' ");

			if(!empty($atkDataC)){
				$destroy = mysql_query("DELETE FROM item WHERE id_item = $idItem");
			}
		}

		header("location: atk_poke.php?id=".$idItem."&n=".$pokeNew."&msg=true");
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
	<title>Pokemon | Ataques </title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

	<header>
	<div class="container">
		<h1>Pukamon Ataques </h1>
	</div>
	</header>

	<div class="container">
		<div class="titulo">
			<h2><center><strong>Asignar Ataque:</strong></center></h2>
		</div>
		<br></br>
	</div>
	


	<div class="login-form">
	<?php echo "<form method='post' action='atk_poke.php?id=".$idItem."&n=".$pokeNew."'>"; ?>
	<div class="container">
		<div class="main row">
			
			<div class="col-md-9">

				<?php if ($asigMSG == true) { ?>
					<div class="col-md-12">
						<div class="alert alert-success">
							<center><H4>¡Has Cambiado el Ataque!</H4></center>
						</div>
					</div>
				<?php } ?>
		
				<div class="cointainer">
				<?php if ($asigMSG == false) { ?>
				<div class="col-md-4">
					<center><h3><strong>Nuevo Ataque:</strong></h3><center>
						<center><?php 
							echo "<h4><strong>".$DatosAtk[name]."</strong><h4>
									<table class='table table-bordered  table-striped'>
										<tbody>
											<tr>
												<th>Tipo</th>
												<td><img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$DatosAtk[id_type]."-type.png' class='img-responsive'  width='60' height='25' alt='".$DatosAtk[id_type]."-type'></td>
											</tr>
											<tr>
												<th>PP</th>
												<td>".$DatosAtk[pp]."</td>
											</tr>
											<tr>
												<th>Poder</th>
												<td>".$DatosAtk[power]."</td>
											</tr>
											<tr>
												<th>Precisión</th>
												<td>".($DatosAtk[accuracy]*100)."%</td>
											</tr>
										</tbody>
									</table>";
						?></center>

	
					</div>


				<div class="col-md-4">
					<?php echo "<center><img src=".$urlBig.substr($pokeData[sprite],0,-4).".jpg class='img-responsive' alt='".$pokeData[name]."'></center>"; ?> 
				</div>

				<div class="col-md-4">
					<table class="table table-striped table-bordered table-hover">
						<tbody>
							<tr>
								<th>Nombre</th>
								<td><strong><?php echo $pokeData[name]; ?></strong></td>
							</tr>
							<tr>
								<th>Nivel</th>
								<td><strong><?php echo $pokeData[level]; ?></strong></td>
							</tr>
							<tr>
								<th>Tipo</th>
								<td><?php if($pokeData[id_type2]){echo "<img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$pokeData[id_type1]."-type.png'  width='60' height='25' alt='".$pokeData[id_type1]."-type'> <img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$pokeData[id_type2]."-type.png' width='60' height='25' alt='".$pokeData[id_type2]."-type'>";} else{echo  "<img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$pokeData[id_type1]."-type.png' class='img-responsive'  width='60' height='25' alt='".$pokeData[id_type1]."-type'>";} ?></td>
							</tr>
							<tr>
								<th>Experiencia</th>
								<td><?php echo number_format($pokeData[exp],0,",",".")." / ".number_format((($pokeData[level]+1)*($pokeData[level]+1)*($pokeData[level]+1)),0,",","."); ?></td>
							
							</tr>
							<tr>
								<th>HP</th>
								<td><?php echo round($pokeData[hp]); ?></td>
							</tr>
							<tr>
								<th>Ataque</th>
								<td><?php echo round($pokeData[atk]); ?></td>
							</tr>
							<tr>
								<th>Defensa</th>
								<td><?php echo round($pokeData[def]); ?></td>
							</tr>
						
						</tbody>
					</table>
				
				</div>
				<br></br>
				<center>

				

				<center><div class="col-md-12">
					<?php if ($asigMSG == false) { ?> <center><h3><strong>Elegir Ataque:</strong></h3><center><?php } ?>
						<?php if ($asigMSG == true) { ?> <center><h3><strong>Ataques:</strong></h3><center><?php } ?>
					</div></center>
					<div class="move row">
						<?php for($i = 0; $i < sizeof($pokeName); $i++){
							echo "<div class='col-md-3'>
								<center>
								
								<div class='dropdown'>
									<h4><strong>".$pokeName[$i]."</strong><h4>
									<table class='table table-bordered  table-striped'>
													<tbody>
														<tr>
															<th>Tipo</th>
															<td><img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$pokeType[$i]."-type.png' class='img-responsive'  width='60' height='25' alt='".$pokeType[$i]."-type'></td>
														</tr>
														<tr>
															<th>PP</th>
															<td>".$pokePP[$i]."</td>
														</tr>
														<tr>
															<th>Poder</th>
															<td>".$pokePower[$i]."</td>
														</tr>
														<tr>
															<th>Precisión</th>
															<td>".($pokeAccuracy[$i]*100)."%</td>
														</tr>

													</tbody>
												</table>
								<button type='submit' class='btn btn-primary btn-sm btn-block' name='btn-atk' value=".$pokeID[$i]." >Cambiar Ataque</button>
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