<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
	// Sino hay sesion iniciada, enviara a la pagina de login
	if( !isset($_SESSION['user']) ) {
		header("Location: index.php");
		exit;
	}


	// datos todos pokemon
	$res2 = mysql_query("SELECT D.n_dex, D.name, D.sprite FROM pokedex AS D");
	
	$pokeNdex = array();
	$pokeName = array();
	$pokeSprite = array();
	while($pokeData = mysql_fetch_array($res2)){
		array_push($pokeNdex,$pokeData[n_dex]);
		array_push($pokeName,$pokeData[name]);
		array_push($pokeSprite,$pokeData[sprite]);
	}

	$urlSmall = "https://img.pokemondb.net/sprites/ruby-sapphire/normal/";

	if( isset($_POST['btn-search'])){
		$text = $_POST['text'];

		$searchPoke = mysql_query("SELECT n_dex FROM pokedex WHERE name = '$text'");
		$PokeDex = mysql_fetch_array($searchPoke);

		if(!empty($PokeDex[n_dex])){
			header("Location: pokedex.php?id=".$PokeDex[n_dex]);
			exit;
		} else {
			header("Location: not_found.php");
			exit;
		}
		
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Pokemon | Pokedex</title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

	<header>
	<div class="container">
		<h1>Pukamon Pokedex</h1>
	</div>
	</header>

	<div class="container">
		<div class="titulo">
			<h2><center><strong>Pokemon No Encontrado</strong></center></h2>
		</div>
		<br></br>
	</div>
	
	<div class="container">
		<div class="main row">
			<div class="col-xs-3">
				<h2 class="bold">Buscar Pokemon</h2>
				<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
					<form class="navbar-form navbar-left" role="search">
						<div class="form-group">
							<input type="text" name="text" class="form-control" placeholder="Ej: Pikachu">
						</div>
						<center><button type="submit" class="btn btn-primary btn-block" name="btn-search">Buscar</button></center>
					</form>
				</form>
			</div>
			<div class="col-xs-6">
				<center><img src=http://docencia.eit.udp.cl/~17407128/pukamon/images/ghost.jpg class='img-responsive' alt='Missigno'></center> 
				<h2>El Pokemon que estás buscando no se encuentra, intenta nuevamente.</h2>
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
	
	
	
	<div class="container">
		<div class="titulo">
			<h2><center><strong>Lista de Pokemon</strong></center></h2>
		</div>
	</div>
	
	<div class="container">
		<div class="main row">
			<?php for($i = 1; $i < sizeof($pokeNdex); $i++){
				echo "<div class='col-xs-1'><center><img src=".$urlSmall.$pokeSprite[$i]." class='img-responsive' alt=".$pokeName[$i]."></center><center><a href='pokedex.php?id=".$pokeNdex[$i]."' title=".$pokeName[$i].">#".$pokeNdex[$i]."<br>".$pokeName[$i]."</a></center><br/></div>";
				}
			?>
		</div>
	</div>

	<script src="assets/jquery.js"</script>
	<script src="assets/js/bootstrap.min.js"></script>	
</body>
</html>
<?php ob_end_flush(); ?>