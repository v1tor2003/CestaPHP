<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>&Aacute;rea Restrita - Login Usu&aacute;rios</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<link type="text/css" rel="stylesheet" href="area_administrativa/estilo/estilo.css" />
	<link type="text/css" rel="stylesheet" href="area_administrativa/estilo/mylogin.css" />
	<script src='javascript/ajax.js' type='text/javascript'></script>
	<script src='javascript/jquery.js' type='text/javascript'></script>
	<script src='javascript/mylogin.js' type='text/javascript'></script>
</head>
<body id="mylogin">
  <div class="caixa_principal">
   <?php require("area_administrativa/topo.php"); ?>
	<div class="menu_superior">&nbsp; </div>
				
	<h2>&Aacute;rea Restrita</h2>
    <fieldset class='login'>
		<p id="errologin"></p>		
		<form method="post" action='javascript: void(0);'>
          
		  <label>Usu&aacute;rio:</label>
		  <input name="nome" type="text" class="text" />
		  <hr />
          
		  <label>Senha:&nbsp;&nbsp;&nbsp;</label>
		  <input name="senha" type='password' value='%$#$#*+-' class="text" />
		  <hr />
          
		  <input type="submit" value="Entrar" class="submit" />
		</form>
		<a id="recsenha" href='recuperar_senha.php'>Esqueceu a Senha?</a>
	</fieldset>
	<span> Ir para </span><a id="restrito" href="index.php"> &Aacute;rea P&uacute;blica</a>
		<img src="area_publica/images/cadeadoaberto.png" alt="&Aacute;rea p&uacute;blica" width="20" height="20" />
	<div class="rodape">Copyright &copy;2009 - <?php echo(date(Y)); ?>&nbsp;
    Departamento de Ci&ecirc;ncias Econ&ocirc;micas da UESC - DCEC. Todos os direitos reservados. </div>
  </div>
 </body>
</html>
