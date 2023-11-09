<?php
  error_reporting(E_ERROR | E_PARSE);
  $bairro_id = $_REQUEST['hid'];
	$bairro_nome = $_REQUEST['bairro_nome'];
	$cidade_id = $_REQUEST['cidade_id'];
	$action = $_REQUEST['haction'];
	$herr = '';

	if ($action=='edit'){	  
	  	$strsql = "SELECT * FROM tabela_bairros WHERE bairro_id = '".$bairro_id."'";
		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		$res = mysqli_fetch_array($res);
		$bairro_id = $res['bairro_id'];
		$bairro_nome = $res['bairro_nome'];
		$cidade_id = $res['cidade_id'];
	}
	
	if ($action=='save'){	  
	  
	  $strsql = "SELECT * FROM tabela_bairros WHERE (bairro_nome= '".$bairro_nome."' AND bairro_id <> '".$bairro_id."') AND cidade_id = '".$cidade_id."'";
	  $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn)); 		
		
		if ($res && mysqli_num_rows($res)>0)
		{
		  $strsql = "SELECT * FROM tabela_cidades WHERE cidade_id = '".$cidade_id."'";
		  $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		  $res = mysqli_fetch_array($res);
		  $herr = "Existe outro bairro com o mesmo nome em ".$res['cidade_nome'];
		}
		else{
		  
		  if ($bairro_id!='')
		    $strsql = "UPDATE tabela_bairros SET bairro_nome = '".$bairro_nome."', cidade_id = '".$cidade_id."'  WHERE bairro_id = '".$bairro_id."'";  		
		  else
		    $strsql = "INSERT INTO tabela_bairros (bairro_nome,cidade_id) VALUES ('".$bairro_nome."','".$cidade_id."')";
			
			
			//die($strsql);
			
			mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
			
			$bairro_id = '';
			$bairro_nome = '';
			$cidade_id = '';
	    	$action = '';
		}//do else de num_rows > 0
	}//do if save
	
	if ($action=='del'){
	  $strsql = "DELETE FROM tabela_bairros WHERE bairro_id = '".$bairro_id."'";
		mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
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
		
			<!-- Conteúdo referente a esta página -->
					<?php 
						  	$strsql = "SELECT * FROM tabela_bairros A, tabela_cidades B WHERE A.cidade_id = B.cidade_id";
							$bairros = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
							
					if ($bairros && mysqli_num_rows($bairros)>0){	
					?>
					<h1 id="Mcaption" style="text-align:left">Cadastro de Bairros</h1>
					<table cellspacing="0" id="listTable" summary="Tabela de Bairros" style="width:533px;">
					<colgroup>
						<col id="codigo" />
						<col id="nome" />
						<col id="cidade" />
						<col id="acoes" />
					</colgroup>		
					<thead>
						<tr>
							<th scope="col" class="tdboderCod">C&oacute;digo</th>
							<th scope="col" class="tdboder">Nome</th>
							<th scope="col" class="tdboder">Cidade</th>
							<th scope="col" colspan="2" class="tdboder">A&ccedil;&otilde;es</th>
						</tr>
					</thead>
						 <?php
						 	
							while ($row = mysqli_fetch_array($bairros)){
								if($l_cor == '') $l_cor = "par"; else $l_cor = "";
						  ?>
							   <tr class="<?php echo ($l_cor);?>">
								 <td class="tdboderCod"><?php echo($row['bairro_id']); ?></td>
								 <td class="tdboder"><?php echo($row['bairro_nome']); ?></td>
								 <td class="tdboder"><?php echo($row['cidade_nome']); ?></td>
								 <td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($row['bairro_id']); ?>','edit', '','','');"><img src="images/botao_editar.png" border="0"></a></td>
								 <td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($row['bairro_id']); ?>','del','<?php echo($row['bairro_nome']); ?>','Deseja apagar o bairro ','');"><img src="images/botao_deletar.png" border="0"></a></td>
								 </tr>
								 <?php	   
								 }//do while
						 ?>
						 </table>	
						 <?php }else{ ?>
						 <h1 id="Mcaption" style="text-align:left">Sem Bairros cadastradas</h1>
						 <?php }?>
						 
								
		<fieldset>
			
			<legend>
				<?php if($bairro_id) {?>Editar<?php } else {?>Adicionar<?php }?> Bairro</legend>
						
						
			
			<form name="form_cadastro" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>" style="width:510px;">
			<?php if($bairro_id) {?>
			<p>
				<label for="codigo">C&oacute;digo:</label> 
				<input type="text" disabled="disabled" size="5" class="inactive" value="<?php echo($bairro_id); ?> "/>
				<input type="hidden" name="hid" value="<?php echo($bairro_id); ?>">
			</p>
			<?php } ?>
			
			<p>
				<label for="nome">Nome:</label>
				<input type="text" maxlength="100" name="bairro_nome" size="40" value="<?php echo($bairro_nome); ?>" />
				<span class="obrig">*</span>
				<input type="hidden" name="haction" value="save"/>
			</p>
			<p>
					<label for="cidade">Cidade:</label>
					<select name="cidade_id">
				
						<option value="0"><--------   Escolha a Cidade  --------></option>
					
						<?php
							
							$strsql = "SELECT * FROM tabela_cidades";
							$cidades = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
					
							if ($cidades && mysqli_num_rows($cidades)>0)	
								while($row = mysqli_fetch_array($cidades))
								{
						?>
						
						<option value="<?php echo($row['cidade_id']); ?>" <?php if($cidade_id == $row['cidade_id']){?>selected="selected" <?php } ?>  > <?php echo ($row['cidade_nome']);?></option>
					
						<?php
						}	 	
						?>	
										
					</select>
					<span class="obrig">*</span>
				</p>
				
			<p>
				<input type="submit" class="botao_submit" value="<?php if($bairro_id) {?>Editar<?php } else {?>Adicionar<?php }?>" size="40" />
				<?php if($bairro_id != ''){?>
				<input type="button" value="Cancelar" class="botao_cancelar" onclick="javascript: go_Page('cadastro_bairros.php');"/>
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

<form name="frm_send_data" method="post" action="">
<input type="hidden" name="haction" value="">
<input type="hidden" name="hid" value="">
</form>
<script language="javascript" type="text/javascript">
	var frm_validator = new Validator("form_cadastro");
	frm_validator.addValidation("bairro_nome","required","O campo NOME não pode ficar em branco!");
	frm_validator.addValidation("cidade_id","dontselect=0","O campo CIDADE não foi selecionado!");
	frm_validator.addValidation("cidade_id","final","Corrija todo(s) o(s) erro(s)!!!");
</script>
</body>
</html>

