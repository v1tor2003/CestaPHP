<?php

  	$usuario_id = addslashes(htmlspecialchars($_REQUEST['hid']));
	$usuario_nome = mysql_escape_string(addslashes(htmlspecialchars($_REQUEST['usuario_nome'])));
	$usuario_email = mysql_escape_string(addslashes(htmlspecialchars($_REQUEST['usuario_email'])));
	$usuario_senha = addslashes(htmlspecialchars($_REQUEST['usuario_senha']));
	$action = addslashes(htmlspecialchars($_REQUEST['haction']));
	$herr = '';

	if ($action=='edit')
	{	  
	  	$strsql = "SELECT * FROM tabela_usuarios WHERE usuario_id = '".$usuario_id."'";
		$res = mysql_query($strsql) or die(mysql_error());
		$res = mysql_fetch_array($res);
		$usuario_id = $res['usuario_id'];
		$usuario_nome = $res['usuario_nome'];
		$usuario_email = $res['usuario_email'];
		$usuario_senha = $res['usuario_senha'];
	}
	
	if ($action=='save')
	{	  
	  
	 	$strsql = "SELECT * FROM tabela_usuarios WHERE usuario_nome= '".$usuario_nome."' AND usuario_id <> '".$usuario_id."'";
		$sqlemail = "SELECT * FROM tabela_usuarios WHERE usuario_email='".$usuario_email."'";
	  	$res = mysql_query($strsql) or die(mysql_error()); 		
		$resEmail = mysql_query($sqlemail) or die(mysql_error());
		if ($res && mysql_num_rows($res)>0)
			$herr = "Existe outro usu�rio com o mesmo nome!";
		else
		{
			if ($usuario_id!='')
		    	$strsql = "UPDATE tabela_usuarios SET usuario_nome = '".$usuario_nome."',usuario_senha = '".$usuario_senha."',usuario_email='".$usuario_email."'  WHERE usuario_id= '".$usuario_id."'";
		  	else
		    	$strsql = "INSERT INTO tabela_usuarios (usuario_nome,usuario_senha,usuario_email) VALUES ('".$usuario_nome."','".$usuario_senha."','".$usuario_email."')";
			
			mysql_query($strsql) or die(mysql_error());	
			
			$usuario_id = '';
			$usuario_nome = '';
			$usuario_email = '';
			$usuario_senha ='';
	    	$action = '';
			
		}//do else de num_rows > 0
	}//do if save
	
	if ($action=='del')
	{
	 	$strsql = "DELETE FROM tabela_usuarios WHERE usuario_id = '".$usuario_id."'";
		mysql_query($strsql) or die(mysql_error());	
		header("Location:".$_SERVER['PHP_SELF']);
		die();
	}

require("cabecalho.php");
?>
<body>
 
		
		<div class="caixa_principal">

			<?php require("topo.php"); ?>
			
			<div class="menu_superior">
			<?php require("menu_superior.php"); ?>
			</div>
			
			<div class="menu_lateral">
			<?php require("menu_lateral_cadastros.php"); ?>
			</div>
			
			<div class="conteudo_pagina">
			<!-- Contedo referente a esta pgina -->
					
					<?php 
						$strsql = "SELECT * FROM tabela_usuarios";
						$usuarios = mysql_query($strsql) or die(mysql_error());
							
						/*Se houverem usuário cadastrados é construída a tabela de listagem de usuário */
						if ($usuarios && mysql_num_rows($usuarios)>0)
						{	
					?>
					
					<h1 id="Mcaption" style="text-align:left">Cadastro de Usu&aacute;rios</h1>
					<table cellspacing="0" id="listTable" summary="Tabela de Usuarios" style="width:523px;">
						<colgroup>
							<col id="codigo" />
							<col id="nome" />
							<col id="acoes" />
						</colgroup>		
					
						<thead>
							<tr>
								<th scope="col" class="tdboderCod">C&oacute;digo</th>
								<th scope="col" class="tdboder">Nome</th>
								<th scope="col" class="tdboder">Email</th>
								<th scope="col" colspan="2" class="tdboder">A&ccedil;&otilde;es</th>
							</tr>
						</thead>
					
					<?php
						
						while ($row = mysql_fetch_array($usuarios))
						{
							if($l_cor == '') $l_cor = "par"; else $l_cor = "";
					?>
					
						<tr class="<?php echo ($l_cor);?>">
							<td class="tdboderCod"><?php echo($row['usuario_id']); ?></td>
							<td class="tdboder"><?php echo($row['usuario_nome']); ?></td>
							<td class="tdboder"><?php echo($row['usuario_email']); ?></td>
							<td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($row['usuario_id']); ?>','edit', '','','');"><img src="images/botao_editar.png" border="0"></a></td>
							<td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($row['usuario_id']); ?>','del','<?php echo($row['usuario_nome']); ?>','Deseja apagar o usu�rio','');"><img src="images/botao_deletar.png" border="0"></a></td>
						</tr>
					
					<?php	   
						 }//do while
					?>
					
					</table>	
					
					<?php 
					}/* senão houverem usuários cadastraos */
					else
					{ 
					?>
					<h1 id="Mcaption" style="text-align:left">Sem usu&aacute;rios cadastradas</h1>
					<?php }?>
						 
								
					<fieldset>
			
						<legend><?php if($usuario_id) {?>Editar<?php } else {?>Adicionar<?php }?> Usu&aacute;rios</legend>
						
						
			
					<form name="form_cadastro" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>" style="width:500px;">
						
						<?php if($usuario_id) {?>
						<p>
							<label for="codigo">C&oacute;digo:</label> 
							<input type="text" disabled="disabled" size="5" class="inactive" value="<?php echo($usuario_id); ?> "/>
							<input type="hidden" name="hid" value="<?php echo($usuario_id); ?>" />
						</p>
						<?php } ?>
			
						<p>
							<label for="nome">Nome:</label>
							<input type="text" maxlength="64" name="usuario_nome" size="30" value="<?php echo($usuario_nome); ?>" />
							<span class="obrig">*</span>
							<input type="hidden" name="haction" value="save"/>
						</p>
						<p>
						<label for="email">Email:</label>
						<input type="text" maxlength="64" name="usuario_email" size="30" value="<?php echo($usuario_email); ?>" />
							<span class="obrig">*</span>
						</p>
						<p>
							<label for="senha">Senha:</label>
							<input type="password" maxlength="15" name="usuario_senha" size="30" value="<?php echo($usuario_senha); ?>" />
							<span class="obrig">*</span><span class="mascara">(8 a 12 caracteres)</span>
						</p>
						
						<p>
							<input type="submit" class="botao_submit" value="<?php if($usuario_id) {?>Editar<?php } else {?>Adicionar<?php }?>" size="40" />
							<?php if($usuario_id != ''){?>
							<input type="button" value="Cancelar" class="botao_cancelar" onclick="javascript: go_Page('cadastro_usuarios.php');"/>
							<?php }?>
						</p>
						
						<p class="obrig" align="right" style="color:#FF0000";margin-left:10px;>* Campos obrigat&oacute;rios&nbsp;&nbsp;&nbsp;</p>
					</form> 
				</fieldset>			
			
			</div>
			
			
			<div class="clearer"><span></span></div>

			<div class="rodape">&nbsp </div>
			
			<!-- Mensagem de erro exibida caso o usuário queira cadastrar um dado que já existe no BD -->
			<?php if($herr != ''){ ?>
				<script type="text/javascript" language="javascript">
					alert('<?php echo($herr);?>');
				</script>
			<?php } ?> 

		</div>
		
		<form name="frm_send_data" method="post" action="">
			<input type="hidden" name="haction" value=""/>
			<input type="hidden" name="hid" value=""/>
		</form>
		
		<script language="javascript" type="text/javascript">
			var frm_validator = new Validator("form_cadastro");
			frm_validator.addValidation("usuario_nome","required","O campo NOME n�o pode ficar em branco!");
			frm_validator.addValidation("usuario_email","email","O campo EMAIL est� inv�lido!");
			frm_validator.addValidation("usuario_senha","minlen=8","O campo SENHA deve ter entre 8 e 12 caracteres!");
			frm_validator.addValidation("usuario_senha","final","Corrija todo(s) o(s) erro(s)!!!");
		</script>
	
	</body>
</html>

