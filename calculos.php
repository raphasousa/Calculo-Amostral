<?php
	include("header.php");
?>
<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		div#content h2{text-align: center; color: #666;}
		div#content h3{text-align: center; color: #666;}

		div#content {
            width: 650px;
            margin: auto;
            background: #ccc;
        }

		div#calculos {
			width: 600px;
			margin: auto;
		}

		div#calculos ul {
			margin: 0;
			padding: 0;
			list-style: none;
			width: 600px;
			text-align: center;
		}

		div#calculos ul li {position: relative;}

		div#calculos li ul {
			position: absolute;
			left: 599px;
			top: 0;
			display: none;
		}

		div#calculos ul li a {
			display: block;
			text-decoration: none;
			color: #E2144A;
			background: #fff;
			padding: 5px;
			border: 2px solid #ccc; 
		}

		div#calculos ul li a:hover{
			text-decoration: none; 
			color: #111; 
			background-color: #ccc;
			border: 2px solid #fff; 
		}

		div#calculos li:hover ul { display: block; }
	</style>
</head>
<body>
	<div id="content">
		<br />
		<h2>Escolha o cálculo desejado:</h2>
		<br />
		<h3>Tamanho da Amostra para:</h3>
		<br />
		<div id="calculos">
			<ul> 
			    <li><a href="ta_ic_proporcao.php">Intervalo de Confiança de uma Proporção</a></li> 
			    <li><a href="ta_ic_media.php">Intervalo de Confiança da Média</a></li> 
			    <li><a href="ta_ic_diferenca_proporcao.php">Intervalo de Confiança - Diferença entre 2 Proporções</a></li> 
			    <li><a href="ta_ic_diferenca_media_independente.php">Diferença entre 2 Médias com Grupos Independentes</a></li> 
			    <li><a href="ta_ic_diferenca_media_dependente.php">Diferença entre 2 Médias com Grupos Dependentes</a></li> 
			    <li><a href="ta_ic_correlacao.php">Correlação entre 2 Variáveis</a></li> 
		  	</ul>
	  	</div>
	  	<br />
	</div>
</body>
</html>