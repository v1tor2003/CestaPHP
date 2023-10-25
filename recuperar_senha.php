<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>&Aacute;rea Restrita - Login Usu&aacute;rios</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<link type="text/css" rel="stylesheet" href="area_administrativa/estilo/estilo.css" />
	<link type="text/css" rel="stylesheet" href="area_administrativa/estilo/mylogin.css" />
	<script src='javascript/ajax.js' type='text/javascript'></script>
	<script src='javascript/jquery.js' type='text/javascript'></script>
	<script type='text/javascript' > 
	//msgs default

	emailWait    = "Aguarde...";
	defDelay      = 500;
	erroLoginId   = "errologin";
	
	function ajax_send_email(usermail)
	{
	    
        XMLHttp = cria_request(); //funcao em ajax.js
		XMLHttp.open("POST",'funcRecSenha.php',true);
		XMLHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		XMLHttp.send("usermail="+usermail);
		
		XMLHttp.onreadystatechange=function(){
                   if (XMLHttp.readyState==4 && XMLHttp.status==200){
                         response = XMLHttp.responseText;
                         $('#'+erroLoginId).slideDown();
                         $('#'+erroLoginId).html(response);
                    }
                }
	}
	


	$(document).ready
	(
	 function()
	 {
	    document.getElementsByTagName("form")[0].setAttribute("action","javascript: void(0);");
		$("#"+erroLoginId).hide();
		$(".text").focus(
			   function()
			    { 
				  $("#"+erroLoginId).slideUp();
				  $(".text").css("color","black");
				}
			   );
		$("input[name='usermail']").ready(function(){
		$("input[name='usermail']").val("");
		});	   
			   
		$("form").submit(function()
		{ 
			usermail = $("input[name='usermail']").val();
			//valida��o de formularios : Email - Tipo:Restrito RFC 1034/1035.
			//fonte: http://www.mhavila.com.br/topicos/web/valform.html
			regexEmail = /^[\w-]+(\.[\w-]+)*@(([A-Za-z\d][A-Za-z\d-]{0,61}[A-Za-z\d]\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\])$/;
			$('#'+erroLoginId).slideDown();
			if(usermail == '' || !regexEmail.test(usermail))
			{	 
				$('#'+erroLoginId).html(emailInvalido);
				return false;
			}
			$('#'+erroLoginId).html(emailWait);
			ajax_send_email(usermail);
		});
	 }
	);//document ready
	
	
	
</script>
</head>
<body id="mylogin">
  <div class="caixa_principal">
   <?php require("area_administrativa/topo.php"); ?>
	<div class="menu_superior">&nbsp; </div>
				
	<h2>Recuperar Senha</h2>
    <fieldset class='login'>
		<p id="errologin"></p>		
		<form method="post" action='#' > 
          <p>
		  <label>Email:</label>
		  <input name="usermail" type="text" value="" class='text' />
		  <input type="submit" value="Enviar" class="submit" />
		  </p>
		</form>
		<a id="recsenha" href='login.php'>Retornar...</a>
	</fieldset>
	<span> Ir para </span><a id="restrito" href="index.php"> &Aacute;rea P&uacute;blica</a>
		<img src="area_publica/images/cadeadoaberto.png" alt="&Aacute;rea p&uacute;blica" width="20" height="20" />
	<div class="rodape">Desenvolvido por: Vagner Luz do Carmo / Desenho:  Adriano Medeiros dos Santos </div>
  </div>
 </body>
</html>
