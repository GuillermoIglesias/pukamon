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

	$pokeStart = $_GET[id];
	$n = $_GET[n];
	$pe = rand(1, 151); // n_dex del proximo pokemon rival
	$hp=-1;
	$hp1=-1;
	$aus=1;
	

	// datos pokemon
	$res2 = mysql_query("SELECT P.id_poke, D.name, P.n_dex, D.sprite, S.exp, S.level, ((D.hp_multi*S.level)+D.hp_base) AS hp, ((D.atk_multi*S.level)+D.atk_base) AS atk, ((D.def_multi*S.level)+D.def_base) AS def, D.id_type1, D.id_type2 FROM pokemon AS P, stats AS S, pokedex AS D WHERE D.n_dex = P.n_dex AND S.id_poke = P.id_poke AND P.id_trainer = ".$_SESSION['user']);
	$pokeID = array();
	$pokeName = array();
	$pokeNdex = array();
	$pokeExp = array();
	$pokeLevel = array();
	$pokeHP = array();
	$pokeATK = array();
	$pokeDEF = array();
	$pokeT1 = array();
	$pokeT2 = array();
	$pokeSprite = array();
	while($pokeData = mysql_fetch_array($res2)){
		array_push($pokeID,$pokeData[id_poke]);
		array_push($pokeName,$pokeData[name]);
		array_push($pokeNdex,$pokeData[n_dex]);
		array_push($pokeExp,$pokeData[exp]);
		array_push($pokeLevel,$pokeData[level]);
		array_push($pokeHP,$pokeData[hp]);
		array_push($pokeATK,$pokeData[atk]);
		array_push($pokeDEF,$pokeData[def]);
		array_push($pokeT1,$pokeData[id_type1]);
		array_push($pokeT2,$pokeData[id_type2]);
		array_push($pokeSprite,$pokeData[sprite]);
	}

	$urlBig = "https://img.pokemondb.net/artwork/";
	$urlSmall = "https://img.pokemondb.net/sprites/ruby-sapphire/normal/";

	if(!$pokeStart||!$n){
		$pokeStart = $pokeID[0];
		$n = 0;
	}
	
	// datos sobre ataques
	$res3 = mysql_query("SELECT A.id_atk, A.name, A.id_type, A.pp, A.power, A.accuracy FROM atk AS A, (SELECT atk1 FROM stats WHERE id_poke = $pokeStart) AS S1, (SELECT atk2 FROM stats WHERE id_poke = $pokeStart) AS S2, (SELECT atk3 FROM stats WHERE id_poke = $pokeStart) AS S3, (SELECT atk4 FROM stats WHERE id_poke = $pokeStart) AS S4 WHERE A.id_atk = S1.atk1 OR A.id_atk = S2.atk2 OR A.id_atk = S3.atk3 OR A.id_atk = S4.atk4");

	// datos sobre el oro
	$res4 = mysql_query("SELECT amount FROM gold WHERE id_trainer = ".$_SESSION['user']);
	$goldData = mysql_fetch_array($res4);
	
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Pokemon | Batalla</title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
	<header>
	<div class="container">
		<h1>Pukamon Batalla</h1>
	</div>
	</header>

	<div class="container">
		<div class="titulo">
			<h2><center><strong>Pokemon Luchador</strong></center></h2>
		</div>
		<br></br>
	</div>
	
	<div class="container">
		<div class="main row">			
			<div class="col-md-4">
				<?php echo "<center><img src=".$urlBig.substr($pokeSprite[$n],0,-4).".jpg class='img-responsive' alt='".$pokeName[$n]."'></center>"; ?> 
			</div>

			<div class="col-md-5">
				<table class="table table-striped table-bordered table-hover">
					<tbody>
						<tr>
							<th>Nombre</th>
							<td><strong><?php echo $pokeName[$n]; ?></strong></td>
						</tr>
						<tr>
							<th>Nivel</th>
							<td><strong><?php echo $pokeLevel[$n]; ?></strong></td>
						</tr>
						<tr>
							<th>Tipo</th>
							<td><?php if($pokeT2[$n]){echo "<img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$pokeT1[$n]."-type.png'  width='60' height='25' alt='".$pokeT1[$n]."-type'> <img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$pokeT2[$n]."-type.png' width='60' height='25' alt='".$pokeT2[$n]."-type'>";} else{echo  "<img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$pokeT1[$n]."-type.png' class='img-responsive'  width='60' height='25' alt='".$pokeT1[$n]."-type'>";} ?></td>
						
						<tr>
							<th>Experiencia</th>
							<td><?php echo number_format($pokeExp[$n],0,",",".")." / ".number_format((($pokeLevel[$n]+1)*($pokeLevel[$n]+1)*($pokeLevel[$n]+1)),0,",","."); ?></td>
						</tr>
						</tr>
						<tr>
							<th>HP</th>
							<td><?php echo round($pokeHP[$n]); ?></td>
						</tr>
						<tr>
							<th>Ataque</th>
							<td><?php echo round($pokeATK[$n]); ?></td>
						</tr>
						<tr>
							<th>Defensa</th>
							<td><?php echo round($pokeDEF[$n]); ?></td>
						</tr>
						<tr>
							<th>Ataques</th>
							<td>
							<?php
								if($pokeStart){ 
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
			
			<div class="col-md-3">
				<ul class="botones">
					<a href="batalla.php" title="Batalla"><li>Batalla</li></a>
					<a href="pokedex.php" title="Pokedex"><li>Pokedex</li></a>
					<a href="pokemon.php" title="Pokemon"><li>Pokemon</li></a>
					<a href="inventario.php" title="Inventario"><li>Inventario</li></a>
					<a href="tienda.php" title="Tienda"><li>Tienda</li></a>
					<a href="logout.php?logout" title="Salir"><li>Salir</li></a>
				</ul>
			</div>

			</div>
		</div>
	</div>
	
	<div class="container">
		<?php 
		echo"
		<ul class='botones'>
			<center><button type='button' class='btn btn-danger btn-lg'><a href='batallapost.php?id=".$pokeID[$n]."&n=".$n."&pe=".$pe."&hp=".$hp."&hp1=".$hp1."&aus=".$aus."' title=".$pokeName[$n].">¡A luchar!</a></button></center>
		</ul>";
		?>
	</div>

	<div class="container">
		<div class="titulo">
			<h2><center><strong>¡Elige un Pokemon para luchar!</strong></center></h2>
		</div>
	</div>
	<div class="container">
		<div class="main row">
			<?php for($i = 0; $i < sizeof($pokeID); $i++){
				echo "<div class='col-xs-1'><center><img src=".$urlSmall.$pokeSprite[$i]." class='img-responsive' alt=".$pokeName[$i]."></center><center><a href='batalla.php?id=".$pokeID[$i]."&n=".$i."' title=".$pokeName[$i].">".$pokeName[$i]."<br> LVL ".$pokeLevel[$i]."</a></center><br/></div>";
					}
			?>	
			
		</div>
	</div>
</body>
</html>
