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
	$name = $_GET[name]; //nombre pokemos aliado
	$name1 = $_GET[name1]; //nombre pokemon enemigo
	$lvl = $_GET[lvl]; // nivel de ambos pokemon
	$rar = $_GET[rar]; // rareza pokemon enemigo

	// datos pokemon
	$res2 = mysql_query("SELECT P.id_poke, D.name, P.n_dex, S.exp, S.level, ((D.hp_multi*S.level)+D.hp_base) AS hp, ((D.atk_multi*S.level)+D.atk_base) AS atk, ((D.def_multi*S.level)+D.def_base) AS def, D.id_type1, D.id_type2 FROM pokemon AS P, stats AS S, pokedex AS D WHERE D.n_dex = P.n_dex AND S.id_poke = P.id_poke AND P.id_trainer = ".$_SESSION['user']);
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
	}

	$urlBig = "https://img.pokemondb.net/artwork/";
	$urlSmall = "https://img.pokemondb.net/sprites/ruby-sapphire/normal/";

	if(!$pokeStart||!$n){
		$pokeStart = $pokeID[0];
		$n = 0;
	}

	// datos sobre el oro
	$res4 = mysql_query("SELECT amount FROM gold WHERE id_trainer = ".$_SESSION['user']);
	$goldData = mysql_fetch_array($res4);
	
	$oro = $goldData[amount] - (($lvl * $rar +50)/4); //oro total 
	$oro1 = ($lvl * $rar +50)/4; // oro perdido
	if($oro < 0){
		$oro = 0;
	}
	$potencia = pow($lvl, 4);
	$expe = $potencia / 7;
	$oro = round($oro);
	$oro1 = round($oro1);
	$expe = round($expe);
	
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Pokemon | Derrota</title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
	<header>
	<div class="container">
		<h1>Pukamon Derrota</h1>
	</div>
	</header>

	<div class="container">
		<div class="titulo">
			<h2><center><strong>Has perdido</strong></center></h2>
		</div>
	</div>
	
	<div class="container">
		<div class="main row">
			<div class="col-md-5">
				<?php echo "<center><img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/pokeloser.jpg' class='img-responsive' alt='".$name."'></center>"; ?> 
			</div>
			<div class="col-md-3">
				<?php echo "<center><img src=".$urlBig.strtolower($name).".jpg class='img-responsive' alt='".$name."'></center>"; ?> 
			</div>

			<div class="col-md-3">
			<?php
				$sql = "UPDATE gold SET amount = $oro WHERE id_trainer = ".$_SESSION['user']."";
				$sql1 = mysql_query($sql);
				echo "<h3><center>".$name." ha sido derrotado por ".$name1." enemigo</h3></center>";
				echo "<center><h3>Has pagado: ".$oro1." monedas de oro para sanar a tu Pokemon</h3></center>";
			?>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="main row">
			<center>
				<ul class="botones">
					<a href="pokemon.php" title="Pokemon"><li>Volver a tus pokemon</li></a>
				</ul>
			</center>
		</div>
	</div>
</body>
</html>
