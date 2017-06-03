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

	// datos sobre items
	$res2 = mysql_query("SELECT id_item, id_atk, id_exp, id_pokeball FROM item WHERE id_trainer =".$_SESSION['user']);

	$itemAtk = array();
	$itemExp = array();
	$itemBall = array();

	while($itemID = mysql_fetch_array($res2)){
		if ($itemID[id_pokeball]){array_push($itemBall,$itemID[id_item]);}
		if ($itemID[id_exp]){array_push($itemExp,$itemID[id_item]);}
		if ($itemID[id_atk]){array_push($itemAtk,$itemID[id_atk]);}
	}


	if(isset($_POST['btn-ball'])){
		$IDitem = $_POST['btn-ball'];

		$ballTypeRes = mysql_query("SELECT id_pokeball FROM item WHERE id_item = $IDitem");
		$IDpoke = mysql_fetch_array($ballTypeRes);

		if(!empty($IDpoke)){
			$destroy = mysql_query("DELETE FROM item WHERE id_item = $IDitem");
		}

		if ($IDpoke[id_pokeball] == 'pokeball') {

			$pokearray = mysql_query("SELECT n_dex FROM pokedex  WHERE rarity = '1' ");
		
			$ndexpoke =  array();
			while ($selectpoke = mysql_fetch_array($pokearray)){
				array_push($ndexpoke,$selectpoke[n_dex]);
			}

			// pokemon aleatorio
			$n = rand(0,sizeof($ndexpoke)-1);
			$poke = $ndexpoke[$n];

			$pokenuevo = mysql_query("INSERT INTO pokemon(id_trainer, n_dex)  VALUES ('$userData[id_trainer]', '$poke' )");
			$pokeID = mysql_insert_id();
		
			$atkID = mysql_query("SELECT id_atk FROM atk , (SELECT id_type1, id_type2 FROM pokedex WHERE n_dex = $poke ) AS T WHERE id_type = T.id_type1 OR id_type = T.id_type2 OR id_type = 'normal' AND power <= '50'  ");
	
			$statins =  array();
			while ($stat = mysql_fetch_array($atkID)){
				array_push($statins,$stat[id_atk]);
			}

			$ns1 = rand(0,sizeof($statins)-1);
			$statpoke1 = $statins[$ns1];

			$statnuevo = mysql_query("INSERT INTO stats(id_poke, level, atk1,  exp)  VALUES ('$pokeID','5', '$statpoke1',  '125' )");


			header("Location: newpoke.php?id=".$pokeID);
			exit;
		}


		if ($IDpoke[id_pokeball] == 'superball') {

			$pokearray = mysql_query("SELECT n_dex FROM pokedex  WHERE rarity = '1' OR rarity = '2' ");
		
			$ndexpoke =  array();
			while ($selectpoke = mysql_fetch_array($pokearray)){
				array_push($ndexpoke,$selectpoke[n_dex]);
			}

			// pokemon aleatorio
			$n = rand(0,sizeof($ndexpoke)-1);
			$poke = $ndexpoke[$n];

			$pokenuevo = mysql_query("INSERT INTO pokemon(id_trainer, n_dex)  VALUES ('$userData[id_trainer]', '$poke' )");
			$pokeID = mysql_insert_id();
		
			$atkID = mysql_query("SELECT id_atk FROM atk , (SELECT id_type1, id_type2 FROM pokedex WHERE n_dex = $poke ) AS T WHERE id_type = T.id_type1 OR id_type = T.id_type2 OR id_type = 'normal' AND power <= '60'  ");
	
			$statins =  array();
			while ($stat = mysql_fetch_array($atkID)){
				array_push($statins,$stat[id_atk]);
			}

			$ns1 = rand(0,sizeof($statins)-1);
			$statpoke1 = $statins[$ns1];

			$statnuevo = mysql_query("INSERT INTO stats(id_poke, level, atk1,  exp)  VALUES ('$pokeID','10', '$statpoke1',  '1000' )");

			header("Location: newpoke.php?id=".$pokeID);
			exit;
		}

		if ($IDpoke[id_pokeball] == 'ultraball') {

			$pokearray = mysql_query("SELECT n_dex FROM pokedex  WHERE rarity = '2' OR rarity = '3' ");
		
			$ndexpoke =  array();
			while ($selectpoke = mysql_fetch_array($pokearray)){
				array_push($ndexpoke,$selectpoke[n_dex]);
			}

			// pokemon aleatorio
			$n = rand(0,sizeof($ndexpoke)-1);
			$poke = $ndexpoke[$n];

			$pokenuevo = mysql_query("INSERT INTO pokemon(id_trainer, n_dex)  VALUES ('$userData[id_trainer]', '$poke' )");
			$pokeID = mysql_insert_id();
		
			$atkID = mysql_query("SELECT id_atk FROM atk , (SELECT id_type1, id_type2 FROM pokedex WHERE n_dex = $poke ) AS T WHERE id_type = T.id_type1 OR id_type = T.id_type2 OR id_type = 'normal'  AND power <= '70' ");
	
			$statins =  array();
			while ($stat = mysql_fetch_array($atkID)){
				array_push($statins,$stat[id_atk]);
			}

			$ns1 = rand(0,sizeof($statins));
			$statpoke1 = $statins[$ns1];

			$statnuevo = mysql_query("INSERT INTO stats(id_poke, level, atk1,  exp)  VALUES ('$pokeID','20', '$statpoke1',  '8000' )");

			header("Location: newpoke.php?id=".$pokeID);
			exit;
		}

		if ($IDpoke[id_pokeball] == 'masterball') {

			$pokearray = mysql_query("SELECT n_dex FROM pokedex  WHERE rarity = '3' OR rarity = '4' ");
		
			$ndexpoke =  array();
			while ($selectpoke = mysql_fetch_array($pokearray)){
				array_push($ndexpoke,$selectpoke[n_dex]);
			}

			// pokemon aleatorio
			$n = rand(0,sizeof($ndexpoke)-1);
			$poke = $ndexpoke[$n];

			$pokenuevo = mysql_query("INSERT INTO pokemon(id_trainer, n_dex)  VALUES ('$userData[id_trainer]', '$poke' )");
			$pokeID = mysql_insert_id();
		
			$atkID = mysql_query("SELECT id_atk FROM atk , (SELECT id_type1, id_type2 FROM pokedex WHERE n_dex = $poke ) AS T WHERE id_type = T.id_type1 OR id_type = T.id_type2 OR id_type = 'normal' AND power <= '80'  ");
	
			$statins =  array();
			while ($stat = mysql_fetch_array($atkID)){
				array_push($statins,$stat[id_atk]);
			}

			$ns1 = rand(0,sizeof($statins));
			$statpoke1 = $statins[$ns1];

			$statnuevo = mysql_query("INSERT INTO stats(id_poke, level, atk1,  exp)  VALUES ('$pokeID','40', '$statpoke1',  '64000' )");

			header("Location: newpoke.php?id=".$pokeID);
			exit;
		}
	}

	if (isset($_POST['btn-exp'])) {
		$IDexp = $_POST['btn-exp'];

		header("Location: newexp.php?id=".$IDexp);
		exit;
	}

	if (isset($_POST['btn-atk'])) {
		$IDatk = $_POST['btn-atk'];
		
		header("Location: newatk.php?id=".$IDatk);
		exit;
	}


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Pokemon | Inventario</title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

	<header>
	<div class="container">
		<h1>Pukamon Inventario</h1>
	</div>
	</header>

	<div class="container">
		<div class="titulo">
			<h2><center><strong>Tus Objetos</strong></center></h2>
		</div>
	<br></br>
	</div>
	


	<div class="login-form">
	<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
	<div class="container">
		<div class="main row">
			
			<div class="col-md-9">
				<div class="cointainer">
					<center><h3><strong>Pokeballs</strong></h3><center>
					<div class="poke row">
						<?php for($i = 0; $i < sizeof($itemBall); $i++){
							$resBall = mysql_query("SELECT P.id_ball, P.name, P.description, I.id_item FROM pokeball AS P, item AS I WHERE I.id_item = $itemBall[$i] AND I.id_pokeball = P.id_ball");
							$ballData = mysql_fetch_array($resBall);		
							echo "<div class='col-md-3'>
								<center><img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$ballData[id_ball].".png' class='img-responsive' alt='".$ballData[name]."'>
								<div class='dropdown'>
									<h4>".$ballData[name]."<h4>
									<button class='btn btn-primary dropdown-toggle btn-block' type='button' data-toggle='dropdown'>Descripción
									<span class='caret'></span></button>
									<ul class='dropdown-menu'>
									  <li><p>".$ballData[description]."</a></li>
									</ul>
								</div>
								<button type='submit' class='btn btn-success btn-sm btn-block' name='btn-ball' value=".$ballData[id_item].">Usar</button>
								</center><br/>
							</div>";
							}
						?>

	
					</div>
					
					<center><h3><strong>Experiencia</strong></h3><center>
					<div class="exp row">
						<?php for($i = 0; $i < sizeof($itemExp); $i++){
							$resExp = mysql_query("SELECT E.id_exp, E.amount, E.description, I.id_item FROM exp_store AS E, item AS I WHERE I.id_item = $itemExp[$i] AND I.id_exp = E.id_exp");
							$expData = mysql_fetch_array($resExp);		
							echo "<div class='col-md-3'>
								<center><img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$expData[id_exp].".png' class='img-responsive' width='100' height='100' alt='".$expData[id_exp]."'>
								<div class='dropdown'>
									<h4>".$expData[amount]." XP<h4>
									<button class='btn btn-primary dropdown-toggle btn-block' type='button' data-toggle='dropdown'>Descripción
									<span class='caret'></span></button>
									<ul class='dropdown-menu'>
									  <li><p>".$expData[description]."</a></li>
									</ul>
								</div>
								<button type='button|submit' class='btn btn-success btn-sm btn-block' name='btn-exp' value=".$expData[id_item].">Usar</button>
								</center><br/>
							</div>";
							}
						?>
					
					</div>
					
					<center><h3><strong>Movimientos</strong></h3><center>
					<div class="move row">
						<?php for($i = 0; $i < sizeof($itemAtk); $i++){
							$resAtk = mysql_query("SELECT I.id_item, I.id_Atk, A.name, A.id_type, A.pp, A.power, A.accuracy FROM atk AS A, item AS I WHERE A.id_atk = '$itemAtk[$i]' AND A.id_atk = I.id_atk ");
							$atkData = mysql_fetch_array($resAtk);		
							echo "<div class='col-md-3'>
								<center><img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$atkData[id_type].".png' class='img-responsive' alt='".$atkData[id_atk]."'>
								<div class='dropdown'>
									<h4>".$atkData[name]."<h4>
									<button class='btn btn-primary dropdown-toggle btn-block' type='button' data-toggle='dropdown'>Descripción
									<span class='caret'></span></button>
									<ul class='dropdown-menu'>
									<li><table class='table table-bordered'>
													<tbody>
														<tr>
															<th>Tipo</th>
															<td><img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$atkData[id_type]."-type.png' class='img-responsive'  width='60' height='60' alt='".$atkData[id_type]."-type'></td>
														</tr>
														<tr>
															<th>PP</th>
															<td>".$atkData[pp]."</td>
														</tr>
														<tr>
															<th>Poder</th>
															<td>".$atkData[power]."</td>
														</tr>
														<tr>
															<th>Precisión</th>
															<td>".($atkData[accuracy]*100)."%</td>
														</tr>
													</tbody>
												</table></li></ul></div>
								<button type='button|submit' class='btn btn-success btn-sm btn-block' name='btn-atk' value=".$atkData[id_item].">Usar</button>
								</center><br/>
							</div>";
							}
						?>

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