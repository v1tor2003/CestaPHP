<?php

	$delimitador_id = $_REQUEST['delimitador_id'];
	$produto_id = $_REQUEST['hid'];
	$medida_id = $_REQUEST['hid1'];
	$racao_minima_medida = $_REQUEST['racao_minima_medida'];
	$racao_minima_quantidade = $_REQUEST['racao_minima_quantidade'];
	$transformador = $_REQUEST['transformador'];
	$flag = $_REQUEST['flag']; // informa se devemos inserir novo produto ou fazer update
	$action = $_REQUEST['haction'];
	$oficial = $_REQUEST['oficial'];
	
	if ($action=='edit')
	{	  
	  	$strsql = "SELECT * FROM tabela_racao_minima A,tabela_unidade_medidas B,tabela_produtos C WHERE A.delimitador_id = '".$delimitador_id."' AND (A.produto_id = '".$produto_id."' AND A.medida_id = '".$medida_id."') AND (A.medida_id = B.medida_id AND A.produto_id = C.produto_id)";
	
		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		$res = mysqli_fetch_array($res);
		$racao_minima_quantidade = $res['racao_minima_quantidade'];
		$transformador = $res['racao_minima_transformador'];
		$produto_id = $res['produto_id'];
		$racao_minima_medida = $res['racao_minima_medida'];
		$produto_info = $res['produto_nome']." (".$res['medida_simbolo'].")";
		$medida_id = $res['medida_id'];
	}
	
	if ($action=='save')
	{	
	
		$aux = preg_split ('/[\/]/', $produto_id);  
		
		  if ($flag == 'UPDATE')
		  $strsql = "UPDATE tabela_racao_minima SET racao_minima_quantidade = '".$racao_minima_quantidade."',racao_minima_transformador = '".$transformador."',racao_minima_medida = '".$racao_minima_medida."' WHERE delimitador_id = '".$delimitador_id."' AND (produto_id = '".$aux[0]."' AND medida_id = '".$aux[1]."')";  
		  else
			$strsql = "INSERT INTO tabela_racao_minima (racao_minima_quantidade,racao_minima_transformador,produto_id,delimitador_id,racao_minima_medida,medida_id) VALUES ('".$racao_minima_quantidade."','".$transformador."','".$aux[0]."','".$delimitador_id."','".$racao_minima_medida."','".$aux[1]."')";
			
			
			mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
			
			$produto_id = '';
			$racao_minima_quantidade = '';
			$transformador = '';
			$racao_minima_medida = '';
			$medida_id = '';
			$produto_inf = '';
	    	$action = '';
		//}//do else de num_rows > 0
	}//do if save
	
	if ($action=='del')
	{
	  $strsql = "DELETE FROM tabela_racao_minima WHERE delimitador_id = '".$delimitador_id."' AND (produto_id='".$produto_id."' AND medida_id = '".$medida_id."')";
		mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
		header("Location:".$_SERVER['PHP_SELF']."?delimitador_id=".$delimitador_id );

	}
?>
<?php
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
		
		<?php 
			
			$strsql = "SELECT * FROM tabela_delimitador_racao WHERE delimitador_id = '".$delimitador_id."'";
			$delimitador = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
							
			if ($delimitador && mysqli_num_rows($delimitador)>0)
			{
				
				$row = mysqli_fetch_array($delimitador)	
		?>
		
		
				<h1 id="Mcaption" style="text-align:left">Delimitador: <?php echo ($row['delimitador_descricao']); ?></h1>
					
		<?php 
			} 
		
			$strsql = "SELECT * FROM tabela_racao_minima A,tabela_unidade_medidas B,tabela_produtos C WHERE (A.medida_id= B.medida_id AND A.produto_id = C.produto_id) AND delimitador_id = '".$delimitador_id."' ORDER BY A.produto_id";
							
			$produtos = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
							
			if ($produtos && mysqli_num_rows($produtos)>0)
			{	
		
		?>
				<h1 id="Mcaption" style="text-align:left">Cadastro de produtos</h1>
				
				<table cellspacing="0" id="listTable" summary="Tabela de Produtos" style="width:533px;">
					
					<colgroup>
						<col id="codigo" />
						<col id="nome" />
						<col id="quantidade" />
						<col id="transformador" />
						<col id="acoes" />
					</colgroup>		
					
					<thead>
						<tr>
							<th scope="col" class="tdboderCod">C&oacute;digo</th>
							<th scope="col" class="tdboder">Nome</th>
							<th scope="col" class="tdboder">Quantidade</th>
							<th scope="col" class="tdboder">Conversor</th>
							<th scope="col" colspan="2" class="tdboder">A&ccedil;&otilde;es</th>
						</tr>
					</thead>
						 
			<?php
					 	
					while ($row = mysqli_fetch_array($produtos))
					{
						if($l_cor == '') $l_cor = "par"; else $l_cor = "";
			?>
			
					<tr class="<?php echo ($l_cor);?>">
						<td class="tdboderCod"><?php echo($row['produto_id']); ?></td>
						<td class="tdboder"><?php echo($row['produto_nome_visualizacao']." (".$row['medida_simbolo'].")"); ?></td>
						
						<?php
							
							$strsql = "SELECT * FROM tabela_unidade_medidas WHERE medida_id = '".$row['racao_minima_medida']."'";
								 $medida = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
								 $row1 = mysqli_fetch_array($medida);	
								?>
								 <td class="tdboder"><?php echo($row['racao_minima_quantidade']. " (".$row1['medida_simbolo'].")"); ?></td>
								 <td class="tdboder"><?php echo($row['racao_minima_transformador']); ?></td>
								 <td class="tdboderCod"><a href="javascript: " onClick="return submit_Action2('<?php echo($row['produto_id']); ?>','<?php echo($row['medida_id']); ?>','edit', '','','edicao_delimitadores.php?delimitador_id=<?php echo $delimitador_id; ?>');"><img src="images/botao_editar.png" border="0"></a></td>
<td class="tdboderCod">
	<a href="javascript: " onClick="return submit_Action2('<?php echo($row['produto_id']); ?>','<?php echo($row['medida_id']); ?>','del','<?php echo($row['produto_nome_visualizacao']); ?>','Deseja apagar o produto ','edicao_delimitadores.php?delimitador_id=<?php echo $delimitador_id; ?>');"><img src="images/botao_deletar.png" border="0"></a></td>
								 </tr>
								 <?php	   
								 }//do while
						 ?>
						 </table>	
						 <?php }else{ ?>
						 <h1 id="Mcaption" style="text-align:left">Sem produtos cadastrados</h1>
						 <?php }?>
						 
						 
			
			<fieldset>
			
				<legend>
					<?php if($produto_id) { echo ("Editar - ".$produto_info); } else {?>Adicionar Produto<?php }?> 
				</legend>
						
						
			<form name="form_cadastro" method="post" action="<?php echo($_SERVER['PHP_SELF']."?delimitador_id=".$delimitador_id); ?>" style="width:510px;">
	
			<?php if($action == 'edit')
					{
					?>
					<input type="hidden" name="hid" value="<?php echo($produto_id."/".$medida_id); ?>" />
					<?php }else{ ?>
					<p>
					<label for="produto">
						Produto:
					</label>
					
					<select name="hid">
				
						<option value="0"><--------   Escolha o Produto   --------></option>
						<?php
						
						$strsql = "SELECT * FROM tabela_produtos_medidas A,tabela_unidade_medidas B,tabela_produtos C WHERE (A.produto_id,A.medida_id) NOT IN (SELECT produto_id,medida_id FROM tabela_racao_minima WHERE delimitador_id = '".$delimitador_id."') AND (A.produto_id = C.produto_id AND A.medida_id = B.medida_id";
						
						if($oficial == 1)
						{
							$strsql = $strsql." AND C.produto_cesta = '1')";
						}
						else
						{
							$strsql = $strsql." AND C.produto_cesta = '0')";
						}
						
								
						$produtos = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
					
						if ($produtos && mysqli_num_rows($produtos)>0)	
							while($row = mysqli_fetch_array($produtos))
							{
						?>
						<option value="<?php echo($row['produto_id']."/".$row['medida_id']); ?>" <?php if($produto_id == $row['produto_id'] && ($medida_id == $row['medida_id'])){?>selected="selected" <?php } ?>  > <?php echo ($row['produto_nome']." (".$row['medida_simbolo'].")");?></option>
					
						<?php
						}
						?>		
					</select>
					<span class="obrig">*</span>
					<?php } ?>
			</p>
			<p>
				<label for="medidas">Medida:</label>
				<select name="racao_minima_medida">
				
					<option value="0"><--------   Escolha a Medida   --------></option>
					
					<?php
							
						$strsql = "SELECT * FROM tabela_unidade_medidas";
						$medidas = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
					
						
						if ($medidas && mysqli_num_rows($medidas)>0)	
						while($row = mysqli_fetch_array($medidas))
						{
			
					?>
						
					<option value="<?php echo($row['medida_id']); ?>" <?php if($racao_minima_medida == $row['medida_id']){?>selected="selected" <?php } ?>  > <?php echo ($row['medida_simbolo']);?></option>
					
					<?php
						}	 	
					?>	
										
				</select>
				
				<span class="obrig">*</span>
			</p>
			<p>
				<label for="racao_minima_quantidade">Ra&ccedil;&atilde;o M&iacute;nima:</label>
				<input type="text" maxlength="5" onkeypress="mascara(this,soNumeros)" name="racao_minima_quantidade" size="10" value="<?php echo($racao_minima_quantidade); ?>" />
				<span class="obrig"> *</span>
			</p>
			<p>
				<label for="transformador">Transformador:</label>
				<input type="text" name="transformador" size="10" maxlength="5" onkeypress="mascara(this,soNumeros)" value="<?php echo($transformador); ?>" /><span class="obrig"> *</span>
				<input type="hidden" name="haction" value="save"/>
			</p>
			<p>
				<input type="submit" class="botao_submit" name="botao_submit" value="<?php if($produto_id) {?>Editar<?php } else {?>Adicionar<?php }?>" size="40" />
				<?php if($produto_id != ''){?>
				<input type="hidden" name="flag" value="UPDATE" />
				<input type="button" value="Cancelar" class="botao_cancelar" onclick="javascript: go_Page('edicao_delimitadores.php?delimitador_id=<?php echo $delimitador_id?>');"/>
					<?php }
					else{?>
					<input type="hidden" name="flag" value="INSERT" />
					<?php } ?>
					
			</p>
			<p class="obrig" align="right" style="color:#FF0000";margin-left:10px;>* Campos obrigat&oacute;rios&nbsp;&nbsp;&nbsp;</p>
						
			
		</form> 
		</fieldset>			
			
	</div>
			
			
	<div class="clearer"><span></span></div>

	<div class="rodape">&nbsp </div>
	

</div>

<form name="frm_send_data" method="post" action="">
<input type="hidden" name="haction" value="">
<input type="hidden" name="hid" value="">
<input type="hidden" name="hid1" value="" />
</form>
</body>
</html>
 
<script language="javascript" type="text/javascript">
	var frm_validator = new Validator("form_cadastro");
	var flag = true;
</script>


<?php if($action == 'edit'){ ?>
 
 <script language="javascript" type="text/javascript">
	flag = false;
</script>
<?php } ?>
<script language="javascript" type="text/javascript">

	if(flag)
		frm_validator.addValidation("hid","dontselect=0","O campo PRODUTO não foi selecionado!");
	frm_validator.addValidation("racao_minima_medida","dontselect=0","O campo MEDIDA DA RACAO MINIMA não foi selecionado!");
	frm_validator.addValidation("racao_minima_quantidade","required","O campo QUANTIDADE DA RACAO MINIMA não foi preenchido!");
	frm_validator.addValidation("transformador","required","O campo TRANSFORMADOR DA RACAO MINIMA não foi preenchido!");
	frm_validator.addValidation("transformador","final","Corrija todo(s) o(s) erro(s)!!!");
</script>