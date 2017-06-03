<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
	// consultas para obtener los datos necesarios
	if( !isset($_SESSION['user']) ) {
		header("Location: index.php");
		exit;
	}

	$buyMSG = $_GET[msg];

	if(empty($buyMSG)){
		$buyMSG = false;
	}

	$res = mysql_query("SELECT id_trainer, nick, email FROM trainer WHERE id_trainer=".$_SESSION['user']);
	$userData = mysql_fetch_array($res);
	
	// datos pokeball
	$res2 = mysql_query("SELECT P.id_ball, P.rarity, P.name, P.value, P.description FROM pokeball AS P ORDER BY P.value ASC");
	
	$ballId= array();
	$ballRarity = array();
	$ballName = array();
	$ballValue = array();
	$ballDesc = array();

	while($ballData = mysql_fetch_array($res2)){
		array_push($ballId,$ballData[id_ball]);
		array_push($ballRarity,$ballData[rarity]);
		array_push($ballName,$ballData[name]);
		array_push($ballValue,$ballData[value]);
		array_push($ballDesc,$ballData[description]);

	}

	// datos experiencias
	$res3 = mysql_query("SELECT E.id_exp, E.amount, E.value, E.description FROM exp_store AS E ORDER BY E.value ASC");
	
	$expId= array();
	$expAmou = array();
	$expValue = array();
	$expDesc = array();


	while($expData = mysql_fetch_array($res3)){
		array_push($expId,$expData[id_exp]);
		array_push($expAmou,$expData[amount]);
		array_push($expValue,$expData[value]);
		array_push($expDesc,$expData[description]);

	}


	// datos ataques
	$res4 = mysql_query("SELECT A.id_atk, A.name, A.id_type, A.power, A.accuracy, A.pp, A.value FROM atk AS A ORDER BY A.value ASC");
	
	$atkId= array();
	$atkName = array();
	$atkIdtype = array();
	$atkPower= array();
	$atkAccu= array();
	$atkPP= array();
	$atkValue = array();


	while($atkData = mysql_fetch_array($res4)){
		array_push($atkId,$atkData[id_atk]);
		array_push($atkName,$atkData[name]);
		array_push($atkIdtype,$atkData[id_type]);
		array_push($atkPower,$atkData[power]);
		array_push($atkAccu,$atkData[accuracy]);
		array_push($atkPP,$atkData[pp]);
		array_push($atkValue,$atkData[value]);

	}


	// cantidad de monedad del usuario
	$resgold = mysql_query("SELECT amount FROM gold WHERE id_trainer = ".$_SESSION['user']);
	$goldData = mysql_fetch_array($resgold);

	// si compra pokeball
	if(isset($_POST['btn-ball'])){
		$IDcomprarball = $_POST['btn-ball'];

		$preball = mysql_query("SELECT value FROM pokeball WHERE id_ball = '$IDcomprarball'");
		$precioball = mysql_fetch_array($preball);

		$newgoldData1 = $goldData[amount] - $precioball[value];

		if($newgoldData1>=0){

			$actualizar = mysql_query("UPDATE gold SET amount = '$newgoldData1' WHERE id_trainer = ".$_SESSION['user']);
			$resp = mysql_query("INSERT INTO item(id_trainer, id_pokeball)  VALUES ('$userData[id_trainer]', '$IDcomprarball' )");

			header("location: tienda.php?msg=true");	

		}	
		
	}


	// si compra experiencia
	if(isset($_POST['btn-exp'])){
		$IDcomprarEXP = $_POST['btn-exp'];

		$preEXP = mysql_query("SELECT value FROM exp_store WHERE id_exp = '$IDcomprarEXP'");
		$precioEXP = mysql_fetch_array($preEXP);

		$newgoldData2 = $goldData[amount] - $precioEXP[value];

		if($newgoldData2>=0){

			$actualizar = mysql_query("UPDATE gold SET amount = '$newgoldData2' WHERE id_trainer = ".$_SESSION['user']);			
			$resp = mysql_query("INSERT INTO item(id_trainer, id_exp)  VALUES ('$userData[id_trainer]', '$IDcomprarEXP' )");

			header("location:tienda.php?msg=true");		
		}	
	
		
	}

	// si compra ataque
	if(isset($_POST['btn-atk'])){
		$IDcomprarATK = $_POST['btn-atk'];

		$preATK = mysql_query("SELECT value FROM atk WHERE id_atk = '$IDcomprarATK'");
		$precioATK = mysql_fetch_array($preATK);

		$newgoldData3 = $goldData[amount] - $precioATK[value];

		if($newgoldData3>=0){

			$actualizar = mysql_query("UPDATE gold SET amount = '$newgoldData3' WHERE id_trainer = ".$_SESSION['user']);			
			$resp = mysql_query("INSERT INTO item(id_trainer, id_atk)  VALUES ('$userData[id_trainer]', '$IDcomprarATK' )");


			header("location:tienda.php?msg=true");		
		}	

		
	}


?>



<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Pokemon | Tienda</title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

	<header>
	<div class="container">
		<h1>Pukamon Tienda</h1>
	</div>
	</header>

	<div class="container">
		<div class="titulo">
			<h2><center><strong>Tienda</strong></center></h2>
		</div>
	</div>

	

	<div class="login-form">
	<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
	<div class="container">
		<div class="main row">
			<div class="col-md-9">
				<?php if ($buyMSG == true) { ?>
				<div class="col-md-12">
					<div class="alert alert-success alert-dismissible">
						<button class="close" aria-label="close"><span>&times;</span></button>
						<center><H4>¡Compra exitosa!</H4></center>
					</div>
				</div>
				<?php } ?>

				<center><h3><strong>Pokeballs</strong></h3></center>
				<div class="poke row">


				<?php if ($newgoldData1<0) { ?>
				<div class="col-md-12">
					<div class="alert alert-danger alert-dismissible">
						<button class="close" aria-label="close"><span>&times;</span></button>
						<center><H4><strong>Alerta!</strong> Monedas insuficientes.</H4></center>
					</div>
				</div>
				<?php } ?>

						
				<?php 
					for($i = 0; $i < sizeof($ballRarity); $i++){

							echo "<div class='col-md-3'>
								<center><img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$ballId[$i].".png' class='img-responsive' alt='".$ballId[$i]."'>
								<div class='dropdown'>
									<h4><strong>".$ballName[$i]."</strong><h4>
									<h4><img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/coin.svg' width='20' height='20' alt='coin'> ".number_format($ballValue[$i],0,",",".")." <h4> 
									<button class='btn btn-primary dropdown-toggle btn-block' type='button' data-toggle='dropdown'>Descripción
									<span class='caret'></span></button>
									<ul class='dropdown-menu'>
									<li><p>".$ballDesc[$i]."</p>
										</li></ul></div>

								<button type='button|submit' class='btn btn-success btn-sm btn-block ' name='btn-ball'  value=".$ballId[$i]." data-toggle='modal' data-target='#myModal'>Comprar</button> 


								</center>
								
								<br></br>

							</div>";
							}
						?>


				</div>


				<center><h3><strong>Experiencia</strong></h3></center>
				<div class="exp row">

					<?php if ($newgoldData2<0) { ?>
						<div class="col-md-12">
							<div class="alert alert-danger alert-dismissible">
								<button class="close" aria-label="close"><span>&times;</span></button>
								<center><H4><strong>Alerta!</strong> Monedas insuficientes.</H4></center>
							</div>
						</div>
					<?php } ?>
	

					<?php for($i = 0; $i < sizeof($expId); $i++){
							echo "<div class='col-md-3'>
							<center><img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$expId[$i].".png' class='img-responsive'  width='100' height='100'  alt='".$expId[$i]."'>
							<div class='dropdown'>
								<h4><strong>".$expAmou[$i]." XP</strong><h4>
								<h4><img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/coin.svg' width='20' height='20' alt='coin'> ".number_format($expValue[$i],0,",",".")."<h4>
								<button class='btn btn-primary dropdown-toggle btn-block' type='button' data-toggle='dropdown'>Descripción									<span class='caret'></span></button>
								<ul class='dropdown-menu'>
								  <li><p>".$expDesc[$i]."</a></li>
								</ul>
							</div>
							<button type='button|submit' class='btn btn-success btn-sm btn-block' name='btn-exp' value=".$expId[$i].">Comprar</button>
							</center>
							<br></br>
						</div>";
						}
					?>
					
				</div>


				<center><h3><strong>Movimientos</strong></h3></center>
				<div class="exp row">

					<?php if ($newgoldData3<0) { ?>
						<div class="col-md-12">
							<div class="alert alert-danger alert-dismissible">
								<button class="close" aria-label="close"><span>&times;</span></button>
								<center><H4><strong>Alerta!</strong> Monedas insuficientes.</H4></center>
							</div>
						</div>
					<?php } ?>

					<?php for($i = 0; $i < sizeof($atkId); $i++){

							echo "<div class='col-md-3'>
							<center><img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$atkIdtype[$i].".png' class='img-responsive' alt='".$atkId[$i]."'>
							<div class='dropdown'>
								<h4><strong>".$atkName[$i]."</strong><h4>
								<h4><img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/coin.svg' width='20' height='20' alt='coin'> ".number_format($atkValue[$i],0,",",".")."<h4>
								<button class='btn btn-primary dropdown-toggle btn-block' type='button' data-toggle='dropdown'>Descripción
								<span class='caret'></span></button>
								<ul class='dropdown-menu'>											
								<li><table class='table table-bordered'>
									<tbody>
										<tr> 


											<th>Tipo</th>
											<td> <img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$atkIdtype[$i]."-type.png' class='img-responsive'  width='60' height='60' alt='".$atkIdtype[$i]."-type'> </td>
										</tr>
										<tr>
											<th>PP</th>
											<td>".$atkPP[$i]."</td>
											</tr>

											<tr>
											<th>Poder</th>
											<td>".$atkPower[$i]."</td>
										</tr>

										<tr>
											<th>Precisión</th>
											<td>".($atkAccu[$i]*100)."</td>
										</tr>

										</tbody>
										</table></li></ul></div>
							
							<button type='button|submit' class='btn btn-success btn-sm btn-block' name='btn-atk' value=".$atkId[$i].">Comprar</button>
							</center>
							<br></br>
						</div>";
						}
					?>
					
				</div>
					
			</div>

			<div class="col-sm-3">
				<center>
					<H4><img src="http://docencia.eit.udp.cl/~17407128/pukamon/images/coin.svg" weight="35" height="35" alt="coin" ><strong><?php echo " ".number_format($goldData[amount],0,",","."); ?></H4></strong>
				</center>
			</div>
			<!-- Menu-->
			<div class="col-sm-3 ">
				<ul class="botones">
					<a href="batalla.php" title="Batalla"><li>Batalla</li></a>
					<a href="pokedex.php" title="Pokedex"><li>Pokedex</li></a>
					<a href="pokemon.php" title="Pokemon"><li>Pokemon</li></a>
					<a href="inventario.php" title="Inventario"><li>Inventario</li></a>
					<a href="tienda.php" title="Tienda"><li>Tienda</li></a>
					<a href="logout.php?logout" title="Salir"><li>Salir</li></a>
				</ul>
			</div>
			<!-- Fin Menu -->

	</div> 
	
	</form>

</div>

	<script src="assets/jquery.js"</script>
	<script src="assets/js/bootstrap.min.js"></script>	
</body>

</html>
<?php ob_end_flush(); ?>