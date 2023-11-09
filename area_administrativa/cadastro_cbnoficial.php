<?php
  error_reporting(E_ERROR | E_PARSE);
	$delimitador_id = $_REQUEST['hid'];
	$delimitador_descricao = $_REQUEST['delimitador_descricao'];
	$delimitador_data = formata_data($_REQUEST['delimitador_data'],2);
	$flag = true;
	$action = $_REQUEST['haction'];
	$herr = '';
	

	if ($action=='edit')
	{
		  
	  	$strsql = "SELECT * FROM tabela_delimitador_racao WHERE delimitador_id = '".$delimitador_id."'";
		$res = mysqli_query($conn, $conn, $strsql) or die(mysqli_error($conn));
		$res = mysqli_fetch_array($res);
		$delimitador_id = $res['delimitador_id'];
		$delimitador_descricao = $res['delimitador_descricao'];
		$delimitador_data = formata_data($res['delimitador_data_registro'],1);
		$delimitador_em_uso = $res['delimitador_em_uso'];
		
	}
	
	if ($action=='save')
	{	  
	  
		$strsql = "SELECT * FROM tabela_delimitador_racao WHERE (delimitador_descricao= '".$delimitador_descricao."' AND delimitador_id <> '".$delimitador_id."') ";
	  	$res = mysqli_query($conn,  $strsql) or die(mysqli_error($conn)); 		
		
		if ($res && mysqli_num_rows($res)>0)
			$herr = "Existe outro delimitador com a mesma descri��o.";
		else
		{
		
			if($delimitador_em_uso == 1)
			{
			
				$strsql = "SELECT * FROM tabela_delimitador_racao WHERE (delimitador_em_uso= '1' AND delimitador_oficial='0' AND delimitador_id <> '".$delimitador_id."') ";
				$res = mysqli_query($conn, $conn, $strsql) or die(mysqli_error($conn)); 	
				
				if ($res && mysqli_num_rows($res)>0)
				{
				  $herr = "Existe outro delimitador em uso!";
				  $flag = false;
				}
					
			}  
		
			if($flag)
			{		
				
				if ($delimitador_id!='')//Vamos a atualizar
					$strsql = "UPDATE tabela_delimitador_racao SET delimitador_descricao= '".$delimitador_descricao."', delimitador_data_registro = '".$delimitador_data."',delimitador_em_uso= '".$delimitador_em_uso."' WHERE delimitador_id = '".$delimitador_id."'";  		
				else//Vamos a insertar
					$strsql = "INSERT INTO tabela_delimitador_racao (delimitador_descricao,delimitador_data_registro,delimitador_em_uso,delimitador_oficial) VALUES ('".$delimitador_descricao."','".$delimitador_data."','".$delimitador_em_uso."','0')";
					
				mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
					
				$delimitador_id = '';
				$delimitador_descricao = '';
				$delimitador_data = '';
				$delimitador_em_uso = '';
				$action = '';
				
			}//do flag	
		}//do else
	}//do if save
	
	if ($action=='del')
	{
		$strsql = "DELETE FROM tabela_racao_minima WHERE delimitador_id = '".$delimitador_id."'";
		mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
		
	  	$strsql = "DELETE FROM tabela_delimitador_racao WHERE delimitador_id = '".$delimitador_id."'";
		mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
		header("Location:".$_SERVER['PHP_SELF']);
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
				<h1 id="Mcaption" style="text-align:left">Cadastro de C.B N&atilde;o Oficial</h1>
			
				<!-- Conte�do referente a esta p�gina -->
				<?php
				 
				$strsql = "SELECT * FROM tabela_delimitador_racao WHERE delimitador_oficial = 0";
				$delimitador = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
							
				if ($delimitador && mysqli_num_rows($delimitador)>0)
				{	
				?>
					<table cellspacing="0" id="listTable" summary="Tabela de Delimitadores" style="width:563px;">
					<colgroup>
						<col id="codigo" />
						<col id="descri��o" />
						<col id="data"/>
						<col id="acoes" />
					</colgroup>		
					<thead>
						<tr>
							<th scope="col" class="tdboderCod">C&oacute;digo</th>
							<th scope="col" class="tdboder">Descri&ccedil;&atilde;o</th>
							<th scope="col" class="tdboder">Data</th>
							<th scope="col" colspan="3" class="tdboder">A&ccedil;&otilde;es</th>
						</tr>
					</thead>
				<?php
						 	
					while ($row = mysqli_fetch_array($delimitador))
					{
								
						if($l_cor == '') 
							$l_cor = "par"; 
						else 
							$l_cor = "";
				?>
						<tr class="<?php echo ($l_cor);?>">
							 <td class="tdboderCod">
							 	<?php echo($row['delimitador_id']); ?>
							</td>
							 <td class="tdboder">
							 	<?php echo($row['delimitador_descricao']); ?>
							</td>
							 <td class="tdboder">
							 	<?php echo(formata_data($row['delimitador_data_registro'],1)); ?>
							</td>
							<td class="tdboderCod">
								<a href="javascript: " onClick="return submit_Action('<?php echo($row['delimitador_id']); ?>','edit', '','','');">
									<img src="images/botao_editar.png" border="0">
								</a>
							</td>
							<td class="tdboderCod">
								<a href="javascript: " onClick="return submit_Action('<?php echo($row['delimitador_id']); ?>','del','<?php echo($row['delimitador_descricao']); ?>','Deseja apagar o delimitador ','');">
									<img src="images/botao_deletar.png" border="0">
								</a>
							</td>
							<td class="tdboderCod">
								<a href="javascript: " onClick="return submit_Action('','','','','edicao_delimitadores.php?delimitador_id=<?php echo $row['delimitador_id']; ?>&oficial=0');">
									<img src="images/estrutura.png" border="0">
								</a>
							</td>
						</tr>
				<?php	   
					 }//do while
				?>
				</table>	
				<?php
					}
					else
					{ 
				?>
					<h1 id="Mcaption" style="text-align:left">Sem delimitadores cadastrados</h1>
				<?php
				
					}
					
				?>
						 
		<!-- Formul�rio da P�gina -->					
		<fieldset>
			<legend>
				<?php if($delimitador_id) {?>Editar<?php } else {?>Adicionar<?php }?> C.B N&atilde;o Oficial</legend>
						
			<form name="form_cadastro" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>" style="width:540px;">
			<?php 
			if($delimitador_id) 
			{
			?>
			<p>
				<label for="codigo">C&oacute;digo:</label> 
				<input type="text" disabled="disabled" size="5" class="inactive" value="<?php echo($delimitador_id); ?> "/>
				<input type="hidden" name="hid" value="<?php echo($delimitador_id); ?>">
			</p>
			<?php 
			}
			?>
			<p>
				<label for="nome">Descri&ccedil;&atilde;o:</label>
				<input type="text" maxlength="255" name="delimitador_descricao" size="40" value="<?php echo($delimitador_descricao); ?>" />
				<span class="obrig">*</span>
			</p>
			<p>
				<label for="nome">Registro:</label>
				<input type="text" maxlength="100" readonly="readonly" class="inactive" name="delimitador_data" size="20" value="<?php echo($delimitador_data); ?>" />
				<a onclick="displayCalendar(document.forms[0].delimitador_data,'dd/mm/yyyy',this)"><img src="images/b_calendar.png" /></a>
				<span class="obrig">*</span>
				<input type="hidden" name="haction" value="save"/>
			</p>
			<p>
				<label for="em_uso">Em uso:</label>
				<input type="checkbox" name="delimitador_em_uso" value="1" <?php if ($delimitador_em_uso == 1){ ?> checked="checked" <?php } ?> style="vertical-align:bottom;" />
				<br />
			</p>
			<p>
				<input type="submit" class="botao_submit" align="left" value="<?php if($delimitador_id) {?>Editar<?php } else {?>Adicionar<?php }?>" size="40" />
				<?php if($delimitador_id != ''){?>
				<input type="button" value="Cancelar" class="botao_cancelar" onclick="javascript: go_Page('cadastro_delimitador.php');"/>
				<?php }?>
			</p>
			<p class="obrig" align="right" style="color:#FF0000";margin-left:10px;>* Campos obrigat&oacute;rios&nbsp;&nbsp;&nbsp;</p>
		</form> 
		</fieldset>			
			
	</div>
			
			
	<div class="clearer"><span></span></div>

	<div class="rodape">&nbsp </div>
	
	<?php if($herr != ''){ ?>
			
			<script type="text/javascript" language="javascript">
			
			alert('<?php echo($herr);?>');
			
			</script>
			
			
	<?php } ?> 

</div>
	
</body>
</html>

<form name="frm_send_data" method="post" action="">
<input type="hidden" name="haction" value="">
<input type="hidden" name="hid" value="">
</form>
<script language="javascript" type="text/javascript">
	var frm_validator = new Validator("form_cadastro");
	frm_validator.addValidation("delimitador_descricao","required","O campo DESCRI��O n�o pode ficar em branco!");
	frm_validator.addValidation("delimitador_data","required","O campo DATA n�o foi preenchido!");
	frm_validator.addValidation("delimitador_data","final","Corrija todo(s) o(s) erro(s)!!!");
</script>
</body>
</html>
