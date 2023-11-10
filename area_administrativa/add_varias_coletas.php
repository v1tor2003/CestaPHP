<?php 
	error_reporting(E_ERROR | E_PARSE);
	$pesquisa_id = $_REQUEST['pesquisa_id'];
	$coleta_id =  $_REQUEST['hid'];
	$estabelecimento_id = $_REQUEST['estabelecimento_id'];
	$coleta_data = formata_data($_REQUEST['coleta_data'],2);
	$action = $_REQUEST['haction'];
	
	if ($action=='save')
	{
	  
		for($i=0;$i<count($estabelecimento_id) ; $i++)
		{
	    $strsql = "INSERT INTO tabela_coletas (estabelecimento_id,pesquisa_id,coleta_data) VALUES ('".$estabelecimento_id[$i]."','".$pesquisa_id."','')";
			mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
		}
		
		
		$coleta_id = '';
		$estabelecimento_id = '';
	   	$action = '';

				
		header("Location: att_data_coletas.php?pesquisa_id=".$pesquisa_id);
	}
		

require("cabecalho.php");
?>
<body>
 
		
		<div class="caixa_principal" id="caixa_principal">

			<?php require("topo.php"); ?>
			
			<div class="menu_superior">
			<?php require("menu_superior.php"); ?>
			</div>
			
			<div class="menu_lateral">
			<?php require("menu_lateral_coletas.php"); ?>
			</div>
			
			<div class="conteudo_pagina">
			<!-- Contedo referente a esta pgina -->
			<?php 
						  	$strsql = "SELECT B.mes_nome,EXTRACT(YEAR FROM A.pesquisa_data) AS pesquisa_ano FROM tabela_pesquisas A, tabela_mes B WHERE EXTRACT(MONTH FROM A.pesquisa_data) = B.mes_id AND A.pesquisa_id = '".$pesquisa_id."'";
							
							$pesquisas = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
							
					if ($pesquisas && mysqli_num_rows($pesquisas)>0){
						$row = mysqli_fetch_array($pesquisas)	
					?>
					<h1 id="Mcaption" style="text-align:left">Pesquisa: <?php echo ($row['mes_nome']."/".$row['pesquisa_ano']); ?> <a href="cadastro_coletas.php?pesquisa_id=<?php echo($pesquisa_id);?>"><img style=" float:right; border:none; margin-right:15px;" src="images/seta_azul.png" ></a></h1>
					<?php } ?>
						 
								
		<fieldset>
			
			<legend>
				<?php 
				

						if($coleta_id) {?>Editar<?php } else {?>Adicionar<?php }?> Coleta</legend>
						
			<form id="form_cadastro" name="form_cadastro" method="post" action="<?php echo($_SERVER['PHP_SELF']."?pesquisa_id=".$pesquisa_id); ?>" style="width:610px;">

			<p>
					<label for="cidade">Cidade:</label>
					<select name="cidade_id" style="width:282px;" id="cidade" onChange="pop_select2()">
				
						<option value="0"><--------- &nbsp;&nbsp;&nbsp;Escolha a Cidade&nbsp;&nbsp;&nbsp; -----------></option>
					
						<?php
							
							$strsql = "SELECT * FROM tabela_cidades";
							$cidades = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
					
							if ($cidades && mysqli_num_rows($cidades)>0)	
								while($row = mysqli_fetch_array($cidades))
								{
						?>
						
						<option value="<?php echo($row['cidade_id']."/".$pesquisa_id); ?>" <?php if($cidade_id == $row['cidade_id']){?>selected="selected" <?php } ?>  > <?php echo ($row['cidade_nome']);?></option>
					
						<?php
						}	 	
						?>	
										
					</select>
				</p>
				
			<p>
					
					<label for="estabelecimento">Estabelecimento:</label>
					<select name="estabelecimento_id[]" multiple="multiple" id="select_pop" size="12" style="width:282px;">
										
					</select>			
				<input type="hidden" name="pesquisa_id" value="<?php echo ($pesquisa_id);?>" />
				<input type="hidden" name="haction" value="save"/>
					<span class="obrig">*</span>
				</p>
			
			<p>
				<input type="submit" class="botao_submit" value="<?php if($coleta_id) {?>Editar<?php } else {?>Adicionar<?php }?>" size="40" />
				<input type="button" value="Cancelar" class="botao_cancelar" onclick="javascript: go_Page('cadastro_coletas.php?pesquisa_id=<?php echo $pesquisa_id; ?>');"/>
			</p>
		
			<p class="obrig" align="right" style="color:#FF0000";margin-left:10px;>* Campos obrigat&oacute;rios&nbsp;&nbsp;&nbsp;</p>
						
			
		</form> 
		</fieldset>	
			
	</div>
			
			
	<div class="clearer"><span></span></div>

	<div class="rodape">&nbsp </div>

</div>
	
</body>
</html>

<?php if($herr != ''){ ?>
			
			<script type="text/javascript" language="javascript">
			
			alert('<?php echo($herr);?>');
			
			</script>
			
			
<?php } ?> 
