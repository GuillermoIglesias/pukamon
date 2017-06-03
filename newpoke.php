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

	$pokeNew = $_GET[id];


	$new = mysql_query("SELECT D.n_dex FROM pokemon AS P, pokedex AS D WHERE P.id_poke = $pokeNew AND P.n_dex = D.n_dex ");
	$newn = mysql_fetch_array($new);


// datos pokemon

	$res2 = mysql_query("SELECT P.id_poke, D.name, P.n_dex, S.exp, S.level, D.sprite, ((D.hp_multi*S.level)+D.hp_base) AS hp, ((D.atk_multi*S.level)+D.atk_base) AS atk, ((D.def_multi*S.level)+D.def_base) AS def, D.id_type1, D.id_type2  FROM pokemon AS P, stats AS S, pokedex AS D  WHERE S.id_poke = $pokeNew AND P.id_poke = $pokeNew AND D.n_dex = $newn[0]");


	$pokeData = mysql_fetch_array($res2);


	$urlBig = "https://img.pokemondb.net/artwork/";


	// datos sobre ataques
	$res3 = mysql_query("SELECT A.name, A.id_type, A.pp, A.power, A.accuracy FROM atk AS A, (SELECT atk1 FROM stats WHERE id_poke = $pokeNew) AS S WHERE A.id_atk = S.atk1 ");


	if (isset($_POST['btn-volver'])) {
		header("Location: inventario.php");
		exit;
	}


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Pokemon | Pokemon Nuevo </title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

	<header>
	<div class="container">
		<h1>Pukamon Nuevo Pokemon </h1>
	</div>
	</header>

	<div class="container">
		<div class="titulo">
			<h2><center><strong>Has Obtenido:</strong></center></h2>
		</div>
		<br></br>
	</div>
	


	<div class="login-form">
	<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
	<div class="container">
		<div class="main row">
			
			<div class="col-md-9">
				<div class="cointainer">

			<div class="col-md-1"></div>
			

			<div class="col-md-5">
				<?php echo "<center><img src=".$urlBig.substr($pokeData[sprite],0,-4).".jpg class='img-responsive' alt='".$pokeData[name]."'></center>"; ?> 
			</div>

			<div class="col-md-5">
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
						
						<tr>
							<th>Experiencia</th>
							<td><?php echo number_format($pokeData[exp],0,",",".")." / ".number_format((($pokeData[level]+1)*($pokeData[level]+1)*($pokeData[level]+1)),0,",","."); ?></td>
						</tr>
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
						<tr>
							<th>Ataques</th>
							<td>
							<?php
								if($pokeNew){ 
									while($pokeMove = mysql_fetch_array($res3)){
										echo "<div class='dropdown'><button class='btn btn-primary dropdown-toggle btn-block' type='button' data-toggle='dropdown'>".$pokeMove[name]."
											<span class='caret'></span></button><ul class='dropdown-menu'>
												<li><table class='table table-bordered'>
													<tbody>
														<tr>
															<th>Tipo</th>
															<td> <img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$pokeMove[id_type]."-type.png' class='img-responsive'  width='60' height='25' alt='".$pokeMove[id_type]."-type'></td>
														</tr>
														<tr>
															<th>PP</th>
															<td>".$pokeMove[pp]."</td>
														</tr>
														<tr>
															<th>Poder</th>
															<td>".$pokeMove[power]."</td>
														</tr>
														<tr>
															<th>Precisión</th>
															<td>".($pokeMove[accuracy]*100)."%</td>
														</tr>
													</tbody>
												</table></li></ul></div>";
									}
								}
								else{
										echo "Error ID Poke";
								}
							?>
							</td>
						</tr>
					</tbody>
				</table>
				
			</div>
			




				<div class="main row">
					<center><button type="submit" class="btn btn-success btn-lg" name="btn-volver" >Volver a Inventario</button></center>	
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