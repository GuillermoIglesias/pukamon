<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
	// Sino hay sesion iniciada, enviara a la pagina de login
	if( !isset($_SESSION['user']) ) {
		header("Location: index.php");
		exit;
	}
	
	$pokeStart = $_GET[id]; // id del pokemon utilizado
	$n = $_GET[n]; // lugar del pokemon utilizado
	$k = $_GET[k]; // para pp
	$j = $_GET[c]; // para pp enemigo
	$m = $_GET[m]; // id ataque usado por el pokemon del usuario
	$pe = $_GET[pe]; // n_dex del pokemon enemigo
	$hp = $_GET[hp]; //hp del pokemon enemigo
	$hp1 = $_GET[hp1]; // hp propio
	$pepe[0] = $_GET[pepe0]; // pp del pokemon
	$pepe[1] = $_GET[pepe1]; // pp del pokemon
	$pepe[2] = $_GET[pepe2]; // pp del pokemon
	$pepe[3] = $_GET[pepe3]; // pp del pokemon
	$pepeEne[0] = $_GET[pepeEne0]; // pp del pokemon enemigo
	$pepeEne[1] = $_GET[pepeEne1]; // pp del pokemon enemigo
	$pepeEne[2] = $_GET[pepeEne2]; // pp del pokemon enemigo
	$pepeEne[3] = $_GET[pepeEne3]; // pp del pokemon enemigo
	$aus = $_GET[aus]; // auxiliar de ataques de enemigo
	$rrr = $_GET[rrr]; // auxiliar de ataques de enemigo
	$ttt = $_GET[ttt]; // auxiliar de ataques de enemigo
	$yyy = $_GET[yyy]; // auxiliar de ataques de enemigo
	$uuu = $_GET[uuu]; // auxiliar de ataques de enemigo
	$bbb = $_GET[bbb]; // auxiliar de ataques de enemigo

	// consultas para obtener los datos necesarios
	$res = mysql_query("SELECT id_trainer, nick, email FROM trainer WHERE id_trainer=".$_SESSION['user']);
	$userData = mysql_fetch_array($res);

	// datos pokemon entrenador
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
	

	
	// datos pokemon rival
	$res3 = mysql_query("SELECT D.n_dex, D.name, D.id_type1, D.id_type2, D.sprite, D.rarity, ((D.hp_multi*$pokeLevel[$n])+D.hp_base) AS hp, ((D.atk_multi*$pokeLevel[$n])+D.atk_base) AS atk, ((D.def_multi*$pokeLevel[$n])+D.def_base) AS def FROM pokedex AS D WHERE D.n_dex = $pe");
	$pokeNdex1 = array();
	$pokeName1 = array();
	$pokeSprite1 = array();
	$pokeType11 = array();
	$pokeType12 = array();
	$pokeHp1 = array();
	$pokeAtk1 = array();
	$pokeDef1 = array();
	$pokeRar1 = array();
	while($pokeData1 = mysql_fetch_array($res3)){
		array_push($pokeNdex1,$pokeData1[n_dex]);
		array_push($pokeName1,$pokeData1[name]);
		array_push($pokeSprite1,$pokeData1[sprite]);
		array_push($pokeType11,$pokeData1[id_type1]);
		array_push($pokeType12,$pokeData1[id_type2]);
		array_push($pokeHp1,$pokeData1[hp]);
		array_push($pokeAtk1,$pokeData1[atk]);
		array_push($pokeDef1,$pokeData1[def]);
		array_push($pokeRar1,$pokeData1[rarity]);
	}
	
	//dando ataques a enemigo

	$res10 = mysql_query("SELECT A.id_atk, A.name, A.id_type, A.power, A.accuracy, A.pp	FROM  atk as A WHERE A.id_type = '$pokeType11[0]' OR A.id_type = '$pokeType12[0]' OR A.id_type = 'normal'");
	$eneId = array();
	$eneName = array();
	$eneIdType= array();
	$enePow= array();
	$eneAcc= array();
	$enePp= array();
		
	while($eneData = mysql_fetch_array($res10)){
	array_push($eneId,$eneData[id_atk]);
	array_push($eneName,$eneData[name]);
	array_push($eneIdType,$eneData[id_type]);
	array_push($enePow,$eneData[power]);
	array_push($eneAcc,$eneData[accuracy]);
	array_push($enePp,$eneData[pp]);
	}
	
	if($aus==1){
		$e1 = count($eneId); //Para randomizar ataques enemigos
		$eatk = array (rand(0,$e1),rand(0,$e1),rand(0,$e1),rand(0,$e1));
		$rrr = $eatk[0];
		$ttt = $eatk[1];
		$yyy = $eatk[2];
		$uuu = $eatk[3];
		$pepeEne = array ($enePp[$rrr], $enePp[$ttt], $enePp[$yyy], $enePp[$uuu]);
	}
	
	$c = rand(0,3);
	
	if($c == 0){
		$bbb = $rrr;
	}
	else if($c == 1){
		$bbb = $ttt;
	}
	else if($c == 2){
		$bbb = $yyy;
	}
	else if($c == 3){
		$bbb = $uuu;
	}
	$i = array ($rrr, $ttt, $yyy, $uuu); //para el while de la lista de ataques enemigo

	
		// datos ataque enemigo usado 1
		$res8 = mysql_query("SELECT A.id_type, A.name, A.power, A.accuracy, AT.multi FROM atk AS A, type AS T, atk_type AS AT WHERE A.id_atk = '$eneId[$bbb]' AND A.id_type = T.id_type AND T.id_type = AT.id_type_atk AND '$pokeT1[$n]' = AT.id_type_def");
		$pokeETa = array();
		$pokeENa = array();
		$pokeEPa = array();
		$pokeEAa = array();
		$pokeEMu = array();
		while($pokeEData2 = mysql_fetch_array($res8)){
			array_push($pokeETa, $pokeEData2[id_type]);
			array_push($pokeENa, $pokeEData2[name]);
			array_push($pokeEPa, $pokeEData2[power]);
			array_push($pokeEAa, $pokeEData2[accuracy]);
			array_push($pokeEMu, $pokeEData2[multi]);
		}
		
		$Emultiplicador = $pokeEMu[0];
		
		// datos ataque enemigo usado 2
		if($pokeEType12[0]){
			$res9 = mysql_query("SELECT AT.multi FROM atk AS A, type AS T, atk_type AS AT WHERE A.id_atk = '$eneId[$bbb]' AND A.id_type = T.id_type AND T.id_type = AT.id_type_atk AND '$pokeT2[$n]' = AT.id_type_def");
			$pokeEMu1 = array();
			
			while($pokeEData3 = mysql_fetch_array($res9)){
				array_push($pokeMu1, $pokeEData3[multi]);
			}
			
			$Emultiplicador = $pokeEMu[0]*$pokeEMu1[0];
		}
	

	if($m){
		// datos ataque usado 1
		$res5 = mysql_query("SELECT A.id_type, A.name, A.power, A.accuracy, A.pp, AT.multi FROM atk AS A, type AS T, atk_type AS AT WHERE A.id_atk = '$m' AND A.id_type = T.id_type AND T.id_type = AT.id_type_atk AND '$pokeType11[0]' = AT.id_type_def");
		$pokeTa = array();
		$pokeNa = array();
		$pokePa = array();
		$pokeAa = array();
		$pokePp = array();
		$pokeMu = array();
		while($pokeData2 = mysql_fetch_array($res5)){
			array_push($pokeTa, $pokeData2[id_type]);
			array_push($pokeNa, $pokeData2[name]);
			array_push($pokePa, $pokeData2[power]);
			array_push($pokeAa, $pokeData2[accuracy]);
			array_push($pokePp, $pokeData2[pp]);
			array_push($pokeMu, $pokeData2[multi]);
		}

		$multiplicador = $pokeMu[0];
		
		// datos ataque usado 2
		if($pokeType12[0]){
			$res6 = mysql_query("SELECT AT.multi FROM atk AS A, type AS T, atk_type AS AT WHERE A.id_atk = '$m' AND A.id_type = T.id_type AND T.id_type = AT.id_type_atk AND '$pokeType12[0]' = AT.id_type_def");
			$pokeMu1 = array();
			
			while($pokeData3 = mysql_fetch_array($res6)){
				array_push($pokeMu1, $pokeData3[multi]);
			}
			
			$multiplicador = $pokeMu[0]*$pokeMu1[0];
		}

	}
	
	if($hp == -1){
		$hp = $pokeHp1[0];
	}
	if($hp1 == -1){
		$hp1 = $pokeHP[$n];
	}
	
	if($m){
		$exito = rand(0,100);
		if($exito <= $pokeAa[0]*100){
			//$total = (0.0001 * $multiplicador)*((((0.2*$pokeLevel[$n]+1)*$pokeATK[$n]*$pokePa[0])/30*$pokeDef1[0])+2);
			$total = (0.01 *1.25*$multiplicador*92)*(((((0.2*$pokeLevel[$n]/2+1)*$pokeATK[$n]*$pokePa[0])/100000*$pokeDef1[0])+2));
			$to = (string)round($total);
			//echo $pokeName[$n];
			//echo " ha usado: ";
			//echo $pokeNa[0];
			//echo " causando un total de ";
			//echo round($total);
			//echo " puntos de daño a ";
			//echo $pokeName1[0];
			
			if($multiplicador == 0){
				//echo ", no ha tenido efecto alguno";
				$mul = ', no ha tenido efecto alguno';
			}
			if($multiplicador == 0.25){
				//echo ", es poco eficaz";
				$mul = ', es poco eficaz';
			}
			if($multiplicador == 0.5){
				//echo ", no es muy eficaz";
				$mul = ', no es muy eficaz';
			}
			if($multiplicador == 1.5){
				//echo ", es eficaz";
				$mul = ', es eficaz';
			}
			if($multiplicador == 2){
				//echo ", es muy eficaz!";
				$mul = ', es muy eficaz!';
			}
			if($multiplicador == 4){
				//echo ", es super eficaz!";
				$mul = ', es super eficaz!';
			}
			
			$ataqueAmigo = $pokeName[$n]." ha usado: ".$pokeNa[0]." causando un total de ".$to." puntos de daño a ".$pokeName1[0]."".$mul;
		}
		else{
			//echo $pokeNa[0];
			//echo " de ";
			//echo $pokeName[$n];
			//echo " ha fallado.";
			
			$fataqueAmigo = $pokeNa[0]." de ".$pokeName[$n]." ha fallado.";
		}
		
		$hp = $hp - $total;
		if($hp <= 0){
			$hp=0;
			header("Location: victoria.php?id=".$pokeID[$n]."&n=".$n."&name=".$pokeName[$n]."&name1=".$pokeName1[0]."&rar=".$pokeRar1[0]."&lvl=".$pokeLevel[$n]."");
			exit;
			// echo "<a href='victoria.php?id=".$pokeID[$n]."&name=".$pokeName[$n]."&name1=".$pokeName1[0]."&rar=".$pokeRar1[0]."&lvl=".$pokeLevel[$n]."' title=".$pokeName[$n].">"
		}
		
		if($exito <= $eneAcc[$bbb]*100){
			//$total1 = (0.0001 * $Emultiplicador)*((((0.2*$pokeLevel[$n]+1)*$pokeAtk1[0]*$enePow[$bbb])/30*$pokeDEF[$n])+2);
			$total1 = (0.01 *1.25*$Emultiplicador*92)*(((((0.2*$pokeLevel[$n]/2+1)*$pokeAtk1[0]*$enePow[$bbb])/100000*$pokeDEF[$n])+2));
			$to1 = (string)round($total1);
			//echo "<br>";
			//echo $pokeName1[0];
			//echo " ha usado: ";
			//echo $eneName[$bbb];
			//echo " causando un total de ";
			//echo round($total1);
			//echo " puntos de daño a ";
			//echo $pokeName[$n];
			
			if($Emultiplicador == 0){
				//echo ", no ha tenido efecto alguno";
				$mul1 = ', no ha tenido efecto alguno';
			}
			if($Emultiplicador == 0.25){
				//echo ", es poco eficaz";
				$mul1 = ', es poco eficaz';
			}
			if($Emultiplicador == 0.5){
				//echo ", no es muy eficaz";
				$mul1 = ', no es muy eficaz';
			}
			if($Emultiplicador == 1.5){
				//echo ", es eficaz";
				$mul1 = ', es eficaz';
			}
			if($Emultiplicador == 2){
				//echo ", es muy eficaz!";
				$mul1 = ', es muy eficaz!';
			}
			if($Emultiplicador == 4){
				//echo ", es super eficaz!";
				$mul1 = ', es super eficaz!';
			}
			$ataqueEnemigo = $pokeName1[0]." ha usado: ".$eneName[$bbb]." causando un total de ".$to1." puntos de daño a ".$pokeName[$n]."".$mul1;
		}
		else{
			//echo "<br>";
			//echo $eneName[$bbb];
			//echo " de ";
			//echo $pokeName1[0];
			//echo " ha fallado.";
			$fataqueEnemigo = $eneName[$bbb]." de ".$pokeName1[0]." ha fallado.";
		}
		
		$hp1 = $hp1 - $total1;
		if($hp1 <= 0){
			$hp1=0;
			header("Location: derrota.php?id=".$pokeID[$n]."&n=".$n."&name=".$pokeName[$n]."&name1=".$pokeName1[0]."&rar=".$pokeRar1[0]."&lvl=".$pokeLevel[$n]."");
			exit;
			//echo "<a href='derrota.php?id=".$pokeID[$n]."&name=".$pokeName[$n]."&name1=".$pokeName1[0]."&rar=".$pokeRar1[0]."&lvl=".$pokeLevel[$n]."' title=".$pokeName[$n].">"
		}
	}
	
	$urlBig = "https://img.pokemondb.net/artwork/";
	$urlSmall = "https://img.pokemondb.net/sprites/ruby-sapphire/normal/";

	if(!$pokeStart||!$n){
		$pokeStart = $pokeID[0];
		$n = 0;
	}
	
	// datos sobre ataques
	$res4 = mysql_query("SELECT A.id_atk, A.name, A.id_type, A.pp, A.power, A.accuracy FROM atk AS A, (SELECT atk1 FROM stats WHERE id_poke = $pokeStart) AS S1, (SELECT atk2 FROM stats WHERE id_poke = $pokeStart) AS S2, (SELECT atk3 FROM stats WHERE id_poke = $pokeStart) AS S3, (SELECT atk4 FROM stats WHERE id_poke = $pokeStart) AS S4 WHERE A.id_atk = S1.atk1 OR A.id_atk = S2.atk2 OR A.id_atk = S3.atk3 OR A.id_atk = S4.atk4");
	$pokeMoveId = array();
	$pokeMoveName = array();
	$pokeMoveIdType = array();
	$pokeMovePp = array();
	$pokeMovePower = array();
	$pokeMoveAcc = array();
	
	while($pokeMove = mysql_fetch_array($res4)){
		array_push($pokeMoveId, $pokeMove[id_atk]);
		array_push($pokeMoveName, $pokeMove[name]);
		array_push($pokeMoveIdType, $pokeMove[id_type]);
		array_push($pokeMovePp, $pokeMove[pp]);
		array_push($pokeMovePower, $pokeMove[power]);
		array_push($pokeMoveAcc, $pokeMove[accuracy]);
	}
	
	if($aus == 1){
		$pepe = array ($pokeMovePp[0], $pokeMovePp[1], $pokeMovePp[2], $pokeMovePp[3]);
		$aus = 0;
	}
	
	$pepe[$k] = $pepe[$k]-1;
	$pepeEne[$j] = $pepeEne[$j]-1;
	
	// datos sobre el oro
	$res7 = mysql_query("SELECT amount FROM gold WHERE id_trainer = ".$_SESSION['user']);
	$goldData = mysql_fetch_array($res7);
	
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
			<h2><center><strong>¡A la batalla!</strong></center></h2>
		</div>
	</div>
	
	<div class="container">
		<div class="main row">			
			<div class="col-md-4">
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
						</tr>
				</tbody>
			</table>
				<?php echo "<center><img src=".$urlBig.strtolower($pokeName[$n]).".jpg class='img-responsive' alt='".$pokeName[$n]."'></center>"; ?> 
				<table class="table table-striped table-bordered table-hover">
					<tbody>
						<tr>
							<th>HP</th>
							<td><?php echo round($hp1)." / ".round($pokeHP[$n]); ?></td>
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
									$k = 0;
									while($k <= 3){
										if($pokeMoveName[$k]){
											echo "<div class='dropdown'><button class='btn btn-primary dropdown-toggle btn-block' type='button' data-toggle='dropdown'>".$pokeMoveName[$k]."
												<span class='caret'></span></button>
												<ul class='dropdown-menu'>
													<li><table class='table table-bordered'>
														<tbody>
															<tr>
																<th>Tipo</th>
																<td> <img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$pokeMoveIdType[$k]."-type.png' class='img-responsive'  width='60' height='25' alt='".$pokeMoveIdType[$k]."-type'></td>
															</tr>
															<tr>
																<th>PP</th>
																<td>".$pokeMovePp[$k]." / ".$pepe[$k]."</td>
																
															</tr>
															<tr>
																<th>Poder</th>
																<td>".$pokeMovePower[$k]."</td>
															</tr>
															<tr>
																<th>Precisión</th>
																<td>".($pokeMoveAcc[$k]*100)."%</td>
															</tr>
															<tr>
															"; 
															if($pepe[$k]>0){
																echo "
																<ul class='botones'>
																	<center><button type='button' class='btn btn-danger'><a href='batallapost.php?id=".$pokeID[$n]."&n=".$n."&pe=".$pe."&m=".$pokeMoveId[$k]."&hp=".$hp."&hp1=".$hp1."&aus=".$aus."&k=".$k."&j=".$c."&rrr=".$rrr."&ttt=".$ttt."&yyy=".$yyy."&uuu=".$uuu."&pepe0=".$pepe[0]."&pepe1=".$pepe[1]."&pepe2=".$pepe[2]."&pepe3=".$pepe[3]."&pepeEne0=".$pepeEne[0]."&pepeEne1=".$pepeEne[1]."&pepeEne2=".$pepeEne[2]."&pepeEne3=".$pepeEne[3]."' title=".$pokeName[$n].">Usar</a></button></center>
																</ul>
																";
															}
															echo"
															</tr>
														</tbody>
													</table></li></ul></div>";
										}
										$k = $k +1;
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
			
			<div class="col-xs-4">
				<img src="http://docencia.eit.udp.cl/~17407128/pukamon/images/versus1.jpg" width="500" height="600" class="img-responsive" alt="versus"></a>
				<img src="https://media.giphy.com/media/RD4NVPmT8K7ny/giphy.gif" width="500" height="600" class="img-responsive" alt="img1"></a><br/>
				<?php
				echo "
					<div class='alert alert-info'>
						".$ataqueAmigo."
						".$fataqueAmigo."
					</div>
				";
				echo "
					<div class='alert alert-danger'>
						".$ataqueEnemigo."
						".$fataqueEnemigo."
					</div>
				";
				?>
			</div>
			
			<div class="col-md-4">
				<table class="table table-striped table-bordered table-hover">
					<tbody>
						<tr>
							<th>Nombre</th>
							<td><strong><?php echo $pokeName1[0]; ?></strong></td>
						</tr>
						<tr>
							<th>Nivel</th>
							<td><strong><?php echo $pokeLevel[$n]; ?></strong></td>
						</tr>
						<tr>
							<th>Tipo</th>
							<td><?php if($pokeType12[0]){echo "<img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$pokeType11[0]."-type.png'  width='60' height='25' alt='".$pokeType11[0]."-type'> <img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$pokeType12[0]."-type.png' width='60' height='25' alt='".$pokeType12[0]."-type'>";} else{echo  "<img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$pokeType11[0]."-type.png' class='img-responsive'  width='60' height='25' alt='".$pokeType11[0]."-type'>";} ?></td>
						</tr>
					</tbody>
				</table>
				<?php echo "<center><img src=".$urlBig.strtolower($pokeName1[0]).".jpg class='img-responsive' alt='".$pokeName1[0]."'></center>"; ?> 

				<table class="table table-striped table-bordered table-hover">
					<tbody>
						
						<tr>
							<th>HP</th>
							<td>
							<?php echo round($hp)." / ".round($pokeHp1[0]); ?></td>
						</tr>
						<tr>
							<th>Ataque</th>
							<td><?php echo round($pokeAtk1[0]); ?></td>
						</tr>
						<tr>
							<th>Defensa</th>
							<td><?php echo round($pokeDef1[0]); ?></td>
						</tr>
						<tr>
							<th>Ataques</th>
							<td>
							<?php
								if($pokeStart){
									$j = 0;
									$v = array('a','b','c','d');
									while($j <= 3){
										if($eneName[$i[$j]] && $v[0] != $eneName[$i[$j]] && $v[1] != $eneName[$i[$j]] && $v[2] != $eneName[$i[$j]] && $v[3] != $eneName[$i[$j]] ){
											echo "<div class='dropdown'><button class='btn btn-primary dropdown-toggle btn-block' type='button' data-toggle='dropdown'>".$eneName[$i[$j]]."
												<span class='caret'></span></button>
													<ul class='dropdown-menu'>
													<li>
													<table class='table table-bordered'>
														<tbody>
															<tr>
																<th>Tipo</th>
																<td> <img src='http://docencia.eit.udp.cl/~17407128/pukamon/images/".$eneIdType[$i[$j]]."-type.png' class='img-responsive'  width='60' height='25' alt='".$eneIdType[$i[$j]]."-type'></td>
															</tr>
															<tr>
																<th>PP</th>
																<td>".$enePp[$i[$j]]." / " .$pepeEne[$j]."</td>
															</tr>
															<tr>
																<th>Poder</th>
																<td>".$enePow[$i[$j]]."</td>
															</tr>
															<tr>
																<th>Precisión</th>
																<td>".($eneAcc[$i[$j]]*100)."%</td>
															</tr>
														</tbody>
													</table>
													</li>
													</ul>
												</div>";
										}
										$v[$j] = $eneName[$i[$j]];
										$j = $j +1;
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
		</div>
	</div>
	<div class="container">
		<div class="main row">
			<center>
				<ul class="botones">
					<a href="pokemon.php" title="Pokemon"><li>Salir de la batalla</li></a>
				</ul>
			</center>
		</div>
	</div>
</body>
</html>
