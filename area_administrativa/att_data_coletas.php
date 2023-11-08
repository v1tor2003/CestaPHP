<?php 
	$pesquisa_id = $_REQUEST['pesquisa_id'];
	$action = $_REQUEST['haction'];
	$qt = $_REQUEST['qt'];
	$ids = $_REQUEST['id'];


	if($action == 'save')
	{
	
		for($i=0;$i<$qt;$i++)
		{
			$coletas[] = $_REQUEST['coleta'.$i];
		}
		
		for($i=0;$i<$qt;$i++)
		{
			 $strsql = "UPDATE tabela_coletas SET coleta_data = '".formata_data($coletas[$i],2)."' WHERE coleta_id = '".$ids[$i]."'"; 			 
			 $res = mysqli_query($conn,$strsql) or die(mysqli_error($conn));
		}
		
		header("Location: cadastro_coletas.php?pesquisa_id=".$pesquisa_id);
		
		
	}
	else
	{
	
	$strsql = "SELECT * FROM tabela_coletas NATURAL JOIN tabela_estabelecimentos NATURAL JOIN tabela_bairros WHERE pesquisa_id = '".$pesquisa_id."' AND coleta_data = '0000-00-00'";
	
	$res = mysqli_query($conn,$strsql) or die(mysqli_error($conn));
					
	
	if ($res && mysqli_num_rows($res)>0)	
	while($row = mysqli_fetch_array($res))
	{
		$estabelecimento_id[] = $row['estabelecimento_id'];
		$coleta[] = $row['coleta_id'];
		$nomes [] = $row['estabelecimento_nome']." (".$row['bairro_nome'].")";
		$datas[] = '0000-00-00';
	}
	
	$qt = count($nomes);
	
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
							$pesquisas = mysqli_query($conn,$strsql) or die(mysqli_error($conn));
							
					if ($pesquisas && mysqli_num_rows($pesquisas)>0){
						$row = mysqli_fetch_array($pesquisas)	
					?>
					<h1 id="Mcaption" style="text-align:left">Pesquisa: <?php echo ($row['mes_nome']."/".$row['pesquisa_ano']); ?> <a href="cadastro_coletas.php?pesquisa_id=<?php echo($pesquisa_id);?>"><img style=" float:right; border:none; margin-right:15px;" src="images/seta_azul.png" ></a></h1>
					<?php } ?>
						 
								
		<fieldset>
			
			<legend>
		Atualizar Datas</legend>
						
			<form id="form_cadastro" name="form_cadastro" method="post" action="<?php echo($_SERVER['PHP_SELF']."?pesquisa_id=".$pesquisa_id); ?>" style="width:610px;">
	<?php
			for($i=0;$i<count($estabelecimento_id);$i++)
			{
			
			
			?>
			<p>
			<input type="hidden" value="<?php echo($coleta[$i]);?>" name="id[]"/>
			<label style="width:200px;"><?php echo($nomes[$i]);?> </label><input type="text"  class="inactive" name="coleta<?php echo($i);?>"/><a onclick="displayCalendar(document.forms[0].coleta<?php echo($i);?>,'dd/mm/yyyy',this)"><img src="images/b_calendar.png" /></a>
			</p>
			<?php } ?>
			<p>
				<input type="hidden" name="qt" value="<?php echo ($qt);?>" />
				<input type="hidden" name="pesquisa_id" value="<?php echo ($pesquisa_id);?>" />
				<input type="hidden" name="haction" value="save"/>
				<input type="submit" class="botao_submit" value="Registrar Datas" size="40" />
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


<script language="javascript" type="text/javascript">

	var frm_validator = new Validator("form_cadastro");


<?php

	for($i=0;$i<count($nomes);$i++)
			{
?>
	frm_validator.addValidation("coleta<?php echo($i);?>","required","O campo DATA do <?php echo($nomes[$i]);?> não foi preenchido!");
<?php
	}
?>
	frm_validator.addValidation("coleta<?php echo($i-1);?>","final","Corrija todo(s) o(s) erro(s)!!!");
</script>
