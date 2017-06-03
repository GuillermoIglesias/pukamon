<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
	// Sino hay sesion iniciada, enviara a la pagina de login
	if( !isset($_SESSION['user']) ) {
		header("Location: index.php");
		exit;
	}

	$pokeStart = $_GET[id];

	if(empty($pokeStart)){
		$pokeStart = 1;
	}

	// consulta pokemon a mostrar
	$res = mysql_query("SELECT D.n_dex, D.name, D.id_type1, D.id_type2, D.rarity, D.description, D.hp_base, D.atk_base, D.def_base FROM pokedex AS D WHERE D.n_dex = '$pokeStart'");
	
	$pokeMain = mysql_fetch_array($res);
	
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

	$urlBig = "https://img.pokemondb.net/artwork/";
	$urlSmall = "https://img.pokemondb.net/sprites/ruby-sapphire/normal/";

	// datos evolucion directa pokemon
	$res3 = mysql_query("SELECT P.name, G.name AS name2, E.n_dex, E.n_dex_pre, E.level_pre, E.n_dex_post, E.level_post FROM evolution AS E, pokedex AS P, pokedex AS G WHERE E.n_dex = $pokeStart AND P.n_dex = E.n_dex_post AND G.n_dex = E.n_dex_pre" );
	$evoNdex = array();
	$evoNdexPre = array();
	$evoLvlPre = array();
	$evoNdexPost = array();
	$evoLvlPost = array();
	$evoNamePost = array();
	$evoNamePre = array();
	while($evoData1 = mysql_fetch_array($res3)){
		array_push($evoNdex,$evoData1[n_dex]);
		array_push($evoNdexPre,$evoData1[n_dex_pre]);
		array_push($evoLvlPre,$evoData1[level_pre]);
		array_push($evoNdexPost,$evoData1[n_dex_post]);
		array_push($evoLvlPost,$evoData1[level_post]);
		array_push($evoNamePost,$evoData1[name]);
		array_push($evoNamePre,$evoData1[name2]);

	}
	
	// datos pre-pre evolución de pokemon
	$res4 = mysql_query("SELECT P.name, G.name AS name2, E.n_dex, E.n_dex_pre, E.level_pre, I.n_dex AS n_dex2, I.n_dex_post AS n_dex_post2, I.n_dex_pre AS n_dex_pre2, I.level_pre AS level_pre2 FROM evolution AS E, evolution AS I, pokedex AS P, pokedex AS G WHERE E.n_dex = $pokeStart AND I.n_dex = (E.n_dex)-1 AND I.n_dex_post = (E.n_dex) AND I.n_dex_pre = (E.n_dex)-2 AND P.n_dex = I.n_dex AND G.n_dex=I.n_dex_pre");
	$evo1Ndex = array();
	$evo1NdexPre = array();
	$evo1LvlPre = array();
	$evo2Ndex = array();
	$evo2NdexPost = array();
	$evo2NdexPre = array();
	$evo2LvlPre = array();
	$evo1NamePre = array();
	$evo2NamePre = array();
	while($evoData2 = mysql_fetch_array($res4)){
		array_push($evo1Ndex,$evoData2[n_dex]);
		array_push($evo1NdexPre,$evoData2[n_dex_pre]);
		array_push($evo1LvlPre,$evoData2[level_pre]);
		array_push($evo2Ndex,$evoData2[n_dex2]);
		array_push($evo2NdexPost,$evoData2[n_dex_post2]);
		array_push($evo2NdexPre,$evoData2[n_dex_pre2]);
		array_push($evo2LvlPre,$evoData2[level_pre2]);
		array_push($evo1NamePre,$evoData2[name]);
		array_push($evo2NamePre,$evoData2[name2]);

	}
	
	// datos post-post evolución de pokemon
	$res5 = mysql_query("SELECT P.name, G.name AS name2, E.n_dex, E.n_dex_post, E.level_post, I.n_dex AS n_dex2, I.n_dex_pre AS n_dex_pre2, I.n_dex_post AS n_dex_post2, I.level_post AS level_post2 FROM evolution AS E, evolution AS I, pokedex AS P, pokedex AS G WHERE E.n_dex = $pokeStart AND I.n_dex = (E.n_dex)+1 AND I.n_dex_pre = (E.n_dex) and I.n_dex_post = (E.n_dex)+2 AND P.n_dex = I.n_dex AND G.n_dex=I.n_dex_post");
	$evo3Ndex = array();
	$evo3NdexPost = array();
	$evo3LvlPost = array();
	$evo4Ndex = array();
	$evo4NdexPre = array();
	$evo4NdexPost = array();
	$evo4LvlPost = array();
	$evo3NamePost = array();
	$evo4NamePost = array();
	while($evoData3 = mysql_fetch_array($res5)){
		array_push($evo3Ndex,$evoData3[n_dex]);
		array_push($evo3NdexPost,$evoData3[n_dex_post]);
		array_push($evo3LvlPost,$evoData3[level_post]);
		array_push($evo4Ndex,$evoData3[n_dex2]);
		array_push($evo4NdexPre,$evoData3[n_dex_pre2]);
		array_push($evo4NdexPost,$evoData3[n_dex_post2]);
		array_push($evo4LvlPost,$evoData3[level_post2]);
		array_push($evo3NamePost,$evoData3[name]);
		array_push($evo4NamePost,$evoData3[name2]);

	}
	
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
			<h2><center><strong><?php echo $pokeMain[name]; ?></strong></center></h2>
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
				<br/>
				<br/>
				<h4>Debilidades y Fortalezas</h4>
				<!-- Trigger the modal with a button -->
				<button type="button" class="btn btn-success btn-md btn-block" data-toggle="modal" data-target="#myModal">Abrir</button>

				<!-- Modal -->
				<div class="modal fade" id="myModal" role="dialog">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Tabla de Debilidades y Fortalezas Pokemon</h4>
							</div>
							<div class="modal-body">
								<center><img src="http://docencia.eit.udp.cl/~17407128/pukamon/images/tabla.png" class="img-responsive" alt="tabla"></center>
								
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-3">
				<?php echo "<center><img src=".$urlBig.substr($pokeSprite[$pokeStart],0,-4).".jpg class='img-responsive' alt='".$pokeMain[name]."'></center>"; ?> 
			</div>
			<div class="col-xs-3">
				<table class="table table-striped table-bordered table-hover">
					<tbody>
						<tr>
							<th>Pokedex №</th>
							<td><strong><?php echo $pokeMain[n_dex]; ?></strong></td>
						</tr>
						<tr>
							<th>Tipo</th>
							<td><?php if($pokeMain[id_type2]){echo "<img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$pokeMain[id_type1]."-type.png'  width='60' height='25' alt='".$pokeMain[id_type1]."-type'> <img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$pokeMain[id_type2]."-type.png' width='60' height='25' alt='".$pokeMain[id_type2]."-type'>";} else{echo  "<img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$pokeMain[id_type1]."-type.png' class='img-responsive'  width='60' height='25' alt='".$pokeMain[id_type1]."-type'>";} ?></td>
						</tr>
						<tr>
							<th>HP Base</th>
							<td><?php echo round($pokeMain[hp_base]); ?></td>
						</tr>
						<tr>
							<th>Atk Base</th>
							<td><?php echo round($pokeMain[atk_base]); ?></td>
						</tr>
						<tr>
							<th>Def Base</th>
							<td><?php echo round($pokeMain[def_base]); ?></td>
						</tr>
						<tr>
							<th>Descripción</th>
							<td><?php echo $pokeMain[description]; ?></td>
						</tr>
						<tr>
							<th>Encontrado</th>
							<td><?php switch($pokeMain[rarity]){
									case 1:
										echo "<center><img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/pokeball.png' weight='40' height='40'  alt='Pokeball'> <img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/superball.png' weight='40' height='40' alt='Superball'></center>";
										break;
									case 2:
										echo "<center><img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/superball.png' weight='40' height='40'  alt='Superball'> <img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/ultraball.png' weight='40' height='40' alt='Ultraball'></center>";
										break;
									case 3:
										echo "<center><img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/ultraball.png' weight='40' height='40'  alt='Ultraball'> <img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/masterball.png' weight='40' height='40' alt='Masterball'></center>";
										break;
									case 4:
										echo "<center><img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/masterball.png' weight='40' height='40' alt='Masterball'></center>";
										break;

								}



							?></td>
						</tr>
					</tbody>
				</table>
			</div>
			


			<div class="col-sm-3">
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
	
	<div class="container">
		<div class="main row">
			<div class="titulo">
				<h3><center><strong>Evoluciones</strong></center></h3>
			</div>
		</div>
	</div>

	<div class="container">	
		<div class="main row">
		<?php
		$a = 0;
			
			if(!$evoNdexPre[0] && !$evoNdexPost[0] && $a == 0){
				echo"
				<div class='col-xs-12'>
					<center><img src=".$urlSmall.$pokeSprite[$pokeStart]." class='img-responsive' alt='".$pokeName[$pokeStart]."'></center>
					<center><h3>No tiene pre-evoluciones ni evoluciones.</h3></center>
				</div>
				";
				$a = 1;
			}
			
			if($evo2NdexPre[0]>0 && $a == 0){ // si es que tiene una pre-pre evolución
				echo"
				<div class='col-xs-3'>
					<center><img src=".$urlSmall.strtolower($evo2NamePre[0]).".png class='img-responsive' alt='".$evo2NamePre[0]."'>
					#".$evo2NdexPre[0]."
					".$evo2NamePre[0]."</center>
					
				</div>
				
				<div class='col-xs-1'>
					<center><br><i class='icon-arrow'>→</i><br>
					Level ".$evo2LvlPre[0]." </center>
				</div>
				
				<div class='col-xs-3'>
					<center><img src=".$urlSmall.strtolower($evo1NamePre[0]).".png class='img-responsive' alt='".$evo1NamePre[0]."'>
					#".$evo1NdexPre[0]."
					".$evo1NamePre[0]."</center>
				</div>
				
				<div class='col-xs-1'>
					<center><br><i class='icon-arrow'>→</i><br>
					Level ".$evo1LvlPre[0]."</center> 
				</div>
				
				<div class='col-xs-3'>
					<center><img src=".$urlSmall.$pokeSprite[$pokeStart]." class='img-responsive' alt='".$pokeName[$pokeStart]."'>
					#".$evo1Ndex[0]."
					".$pokeName[$pokeStart]."</center>
				</div>
				";
				$a = 1;
			}
			
			if($evo4NdexPost[0] > 0 && $a == 0){ // si es que tiene una post-post evolución
				echo"
				<div class='col-xs-3'>
					<center><img src=".$urlSmall.$pokeSprite[$pokeStart]." class='img-responsive' alt='".$pokeName[$pokeStart]."'>
					#".$evo3Ndex[0]."
					".$pokeName[$pokeStart]."</center>
				</div>
				
				<div class='col-xs-1'>
					<center><br><i class='icon-arrow'>→</i><br>
					Level ".$evo3LvlPost[0]."</center>
				</div>
				
				<div class='col-xs-3'>
					<center><img src=".$urlSmall.strtolower($evo3NamePost[0]).".png class='img-responsive' alt='".$evo3NamePost[0]."'>
					#".$evo3NdexPost[0]."
					".$evo3NamePost[0]."</center>
				</div>
				
				<div class='col-xs-1'>
					<center><br><i class='icon-arrow'>→</i><br>
					Level ".$evo4LvlPost[0]."</center> 
				</div>
				
				<div class='col-xs-3'>
					<center><img src=".$urlSmall.strtolower($evo4NamePost[0]).".png class='img-responsive' alt='".$evo4NamePost[0]."'>
					#".$evo4NdexPost[0]."
					".$evo4NamePost[0]."</center>
				</div>
				";
				$a = 1;
			}
			
			if($evoNdexPre[0]!=0 && $evoNdexPost[0]!=0 && $a == 0){ //Si tiene pre y post evolución
				echo"
				<div class='col-xs-3'>
					<center><img src=".$urlSmall.strtolower($evoNamePre[0]).".png class='img-responsive' alt='".$evoNamePre[0]."'>
					#".$evoNdexPre[0]."
					".$evoNamePre[0]."</center>
				</div>
				
				<div class='col-xs-1'>
					<center><br><i class='icon-arrow'>→</i><br>
					Level ".$evoLvlPre[0]."</center> 
				</div>
				
				<div class='col-xs-3'>
					<center><img src=".$urlSmall.$pokeSprite[$pokeStart]." class='img-responsive' alt='".$pokeName[$pokeStart]."'>
					#".$evoNdex[0]."
					".$pokeName[$pokeStart]."</center>
				</div>
				
				<div class='col-xs-1'>
					<center><br><i class='icon-arrow'>→</i><br>
					Level ".$evoLvlPost[0]."</center> 
				</div>
				
				<div class='col-xs-3'>
					<center><img src=".$urlSmall.strtolower($evoNamePost[0]).".png class='img-responsive' alt='".$evoNamePost[0]."'>
					#".$evoNdexPost[0]."
					".$evoNamePost[0]."</center>
				</div>
				";
				$a = 1;
			}
			if($evoNdexPre[0] == 0 && $evoNdexPost != [0] && $a == 0){
				if($pokeName[$pokeStart] == "Eevee"){
					echo"
					<div class='col-xs-4'>
						<center><img src=".$urlSmall.$pokeSprite[$pokeStart]." class='img-responsive' alt='".$pokeName[$pokeStart]."'>
						#".$evoNdex[0]."
						".$pokeName[$pokeStart]."</center>
					</div>
					
					<div class='col-xs-2'>
						<center><br><i class='icon-arrow'>→</i><br>
						Level ".$evoLvlPost[0]."</center>
					</div>
					
					<div class='col-xs-2'>
						<center><img src=".$urlSmall.strtolower($evoNamePost[0]).".png class='img-responsive' alt='".$evoNamePost[0]."'>#".$evoNdexPost[0]." ".$evoNamePost[0]."</center>
					</div>
					<div class='col-xs-2'>
						<center><img src=".$urlSmall.strtolower($evoNamePost[1]).".png class='img-responsive' alt='".$evoNamePost[1]."'>
						#".$evoNdexPost[1]." ".$evoNamePost[1]."</center>
					</div>
					<div class='col-xs-2'>
						<center><img src=".$urlSmall.strtolower($evoNamePost[2]).".png class='img-responsive' alt='".$evoNamePost[2]."'>
						#".$evoNdexPost[2]." ".$evoNamePost[2]."</center>
					</div>
					"; 
					$a = 1;

				} else {
				echo"
				<div class='col-xs-5'>
					<center><img src=".$urlSmall.$pokeSprite[$pokeStart]." class='img-responsive' alt='".$pokeName[$pokeStart]."'>
					#".$evoNdex[0]."
					".$pokeName[$pokeStart]."</center>
				</div>
				
				<div class='col-xs-2'>
					<center><br><i class='icon-arrow'>→</i><br>
					Level ".$evoLvlPost[0]."</center>
				</div>
				
				<div class='col-xs-5'>
					<center><img src=".$urlSmall.strtolower($evoNamePost[0]).".png class='img-responsive' alt='".$evoNamePost[0]."'>
					#".$evoNdexPost[0]."
					".$evoNamePost[0]."</center>
				</div>
				";}
				$a = 1;
			}
			if($evoNdexPre[0]!= 0 && $evoNdexPost == [0] && $a == 0){
				echo"
				<div class='col-xs-5'>
					<center><img src=".$urlSmall.strtolower($evoNamePre[0]).".png class='img-responsive' alt='".$evoNamePre[0]."'>
					#".$evoNdexPre[0]."
					".$evoNamePre[0]."</center>
				</div>
				
				<div class='col-xs-2'>
					<center><br><i class='icon-arrow'>→</i><br>
					Level ".$evoLvlPre[0]."</center> 
				</div>
				
				<div class='col-xs-5'>
					<center><img src=".$urlSmall.$pokeSprite[$pokeStart]." class='img-responsive' alt='".$pokeName[$pokeStart]."'>
					#".$evoNdex[0]."
					".$pokeName[$pokeStart]."</center>
				</div>
				";
				$a = 1;
			}
			
				
		?>
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