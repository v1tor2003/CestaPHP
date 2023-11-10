<?php 
	error_reporting(E_ERROR | E_PARSE);
	$pesquisa_id = $_REQUEST['pesquisa_id'];
	$cidade_id =  $_REQUEST['hid'];
	$action = $_REQUEST['haction'];
	
	if ($action=='save'){	  
	  
		  
	$strsql = "INSERT INTO tabela_pesquisas_cidades (cidade_id,pesquisa_id) VALUES ('".$cidade_id."','".$pesquisa_id."')";
			
	mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
			
	header("Location: cadastro_precos_cidade.php?pesquisa_id=".$pesquisa_id);
		
		
	}//do if save
	
	
	if ($action=='del'){
	
	  	$strsql = "DELETE FROM tabela_pesquisas_cidades WHERE cidade_id = '".$cidade_id."' AND pesquisa_id = '".$pesquisa_id."'";
	
		mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
			
		header("Location: cadastro_precos_cidade.php?pesquisa_id=".$pesquisa_id);
	}
	
$include_head[] = <<<EOS
<link rel="stylesheet" type="text/css" href="estilo/dhtmlgoodies_calendar.css?random=20051112" />\n
<script type="text/javascript" language="javascript" src="../javascript/valida_forms.js"></script>\n
<script type="text/javascript" language="javascript" src="../javascript/utils.js"></script>\n
<script type="text/javascript" language="javascript" src="../javascript/ajax.js"></script>\n
<script type="text/javascript" language="javascript" src="../javascript/dhtmlgoodies_calendar.js"></script>\n
EOS;

require("cabecalho.php");
?><body>
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
					<h1 id="Mcaption" style="text-align:left">Pesquisa: <?php echo ($row['mes_nome']."/".$row['pesquisa_ano']); ?> <a href="cadastro_precos_cidade.php?pesquisa_id=<?php echo($pesquisa_id);?>"><img style=" float:right; border:none; margin-right:15px;" src="images/seta_azul.png" ></a></h1>
					<?php } ?>
						 
								
		<fieldset>
			
			<legend>Adicionar Cidade</legend>
						
			<form id="form_cadastro" name="form_cadastro" method="post" action="<?php echo($_SERVER['PHP_SELF']."?pesquisa_id=".$pesquisa_id); ?>" style="width:610px;">
			<p>
					<label for="cidade">Cidade:</label>
					<select name="hid" id="cidade">
				
						<option value="0"><--------- &nbsp;&nbsp;&nbsp;Escolha a Cidade&nbsp;&nbsp;&nbsp; -----------></option>
					
						<?php
							
							
							$strsql = "SELECT * FROM tabela_cidades WHERE cidade_id NOT IN (SELECT cidade_id FROM tabela_pesquisas_cidades WHERE pesquisa_id = '".$pesquisa_id."')";
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
			    <input type="hidden"  size="5" name="pesquisa_id" value="<?php echo($pesquisa_id); ?> "/>
				<input type="hidden" name="haction" value="save"/>
				<input type="submit" class="botao_submit" value="Adicionar" size="40" />
				<input type="button" value="Cancelar" class="botao_cancelar" onclick="javascript: go_Page('cadastro_precos_cidade.php?pesquisa_id=<?php echo $pesquisa_id; ?>');"/>
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

<script language="javascript" type="text/javascript">
	var frm_validator = new Validator("form_cadastro");
	frm_validator.addValidation("hid","dontselect=0","O campo CIDADE n�o foi selecionado!");
	frm_validator.addValidation("hid","final","Corrija todo(s) o(s) erro(s)!!!");
</script>