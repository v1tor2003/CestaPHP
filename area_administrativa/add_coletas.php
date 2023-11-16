<?php 
	$pesquisa_id = $_REQUEST['pesquisa_id'];
	$coleta_id =  $_REQUEST['hid'];
	$estabelecimento_id = $_REQUEST['estabelecimento_id'];
	$coleta_data = formata_data($_REQUEST['coleta_data'],2);
	$action = $_REQUEST['haction'];
	 
	if ($action=='edit'){	  
	  	$strsql = "SELECT * FROM tabela_coletas WHERE coleta_id = '".$coleta_id."'";
		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		$res = mysqli_fetch_array($res);
		$coleta_id = $res['coleta_id'];
		$estabelecimento_id = $res['estabelecimento_id'];
		$coleta_data = formata_data($res['coleta_data'],1);
		$pesquisa_id = $res['pesquisa_id'];
		
	}
	
	if ($action=='save'){	  
	  
		$strsql = "SELECT * FROM tabela_coletas WHERE (estabelecimento_id= '".$estabelecimento_id."' AND coleta_data= '".$coleta_data."') AND (coleta_id <> '".$coleta_id."') AND pesquisa_id = ".$pesquisa_id;
	  
	  $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn)); 		
		
		if ($res && mysqli_num_rows($res)>0)
		  $herr = "Existe outra coleta no mesmo estabelecimento e data.";
		else{
		  
		  if ($coleta_id!='')
		    $strsql = "UPDATE tabela_coletas SET estabelecimento_id = '".$estabelecimento_id."',coleta_data = '".$coleta_data."' WHERE coleta_id = '".$coleta_id."'";  		
		  else
		    $strsql = "INSERT INTO tabela_coletas (estabelecimento_id,pesquisa_id,coleta_data) VALUES ('".$estabelecimento_id."','".$pesquisa_id."','".$coleta_data."')";
			
			
			mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
			
			$coleta_id = '';
			$estabelecimento_id = '';
			$coleta_data = '';
	    	$action = '';
				
			header("Location: cadastro_coletas.php?pesquisa_id=".$pesquisa_id);
		}//do else de num_rows > 0
		
		
	}//do if save
	
	
	if ($action=='del'){
                
                $strsql = "DELETE FROM tabela_precos WHERE coleta_id = '$coleta_id'";
                mysqli_query($conn, $strsql) or die(mysqli_error($conn) . " - SQL: " . $strsql . " - Arquivo: " . __FILE__ . " - Linha: " . __LINE__);
                
                $strsql = "DELETE FROM tabela_coleta_est_sec WHERE coleta_id = '$coleta_id'";
                mysqli_query($conn, $strsql) or die(mysqli_error($conn) . " - SQL: " . $strsql . " - Arquivo: " . __FILE__ . " - Linha: " . __LINE__);
                
	  	$strsql = "DELETE FROM tabela_coletas WHERE coleta_id = '".$coleta_id."'";
		mysqli_query($conn, $strsql) or die(mysqli_error($conn) . " - SQL: " . $strsql . " - Arquivo: " . __FILE__ . " - Linha: " . __LINE__);
                
		header("Location: cadastro_coletas.php?pesquisa_id=".$pesquisa_id);
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
				<?php if($coleta_id) {?>Editar<?php } else {?>Adicionar<?php }?> Coleta</legend>
						
			<form id="form_cadastro" name="form_cadastro" method="post" action="<?php echo($_SERVER['PHP_SELF']."?pesquisa_id=".$pesquisa_id); ?>" style="width:610px;">
	
			<?php if($coleta_id) {?>
			<p id="codigo">
				<label for="codigo">C&oacute;digo:</label> 
				<input type="text" disabled="disabled" size="5" class="inactive" value="<?php echo($coleta_id); ?> "/>
				<input type="hidden" name="hid" value="<?php echo($coleta_id); ?>">
			</p>
			<?php } ?>
			<p>
					<label for="cidade">Cidade:</label>
					<select name="cidade_id" id="cidade" onchange=" pop_select('procura_estabelecimentos.php','<-------  Escolha o Estabelecimento ------->')">
				
						<option value="0"><--------- &nbsp;&nbsp;&nbsp;Escolha a Cidade&nbsp;&nbsp;&nbsp; -----------></option>
					
						<?php
							
							if($action == 'edit')
							{
								$strsql = "SELECT * FROM tabela_cidades A,tabela_estabelecimentos B,tabela_bairros C WHERE C.bairro_id = B.bairro_id AND C.cidade_id = A.cidade_id AND B.estabelecimento_id = '".$estabelecimento_id."'";
								//die($strsql);
								$cidade = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
								$row = mysqli_fetch_array($cidade);
								$cidade_id = $row['cidade_id'];
							}
							
							
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
					<select name="estabelecimento_id" <?php if($action  != 'edit'){ ?> class="select_desativado" disabled="disabled" <?php } ?> id="select_pop">
				
						<option value="0"><------ Escolha o Estabelecimento ------></option>
						<?php if($action == 'edit'){ 
						
						$strsql = "SELECT * FROM tabela_estabelecimentos A,tabela_cidades B,tabela_bairros C WHERE (A.bairro_id = C.bairro_id AND B.cidade_id = C.cidade_id) AND B.cidade_id = '".$cidade_id."'";
						$estabelecimentos = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
					
						if ($estabelecimentos && mysqli_num_rows($estabelecimentos)>0)	
							while($row = mysqli_fetch_array($estabelecimentos))
							{?>
							<option value="<?php echo($row['estabelecimento_id']); ?>" <?php if($estabelecimento_id == $row['estabelecimento_id']){?>selected="selected" <?php } ?>  > <?php echo ($row['estabelecimento_nome']." (".$row['bairro_nome'].")");?></option>
							
							<?php
							 	}
							}?>
										
					</select>
					<span class="obrig">*</span>
				</p>
				
				<p>
				<label for="nome">Data:</label>
				<input type="text" size="20" readonly class="inactive" name="coleta_data" value="<?php echo($coleta_data); ?>" />
				<a onclick="displayCalendar(document.forms[0].coleta_data,'dd/mm/yyyy',this)"><img src="images/b_calendar.png" /></a>
				<span class="obrig">*</span>
				<input type="hidden" name="pesquisa_id" value="<?php echo ($pesquisa_id);?>" />
				<input type="hidden" name="haction" value="save"/>
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

<script language="javascript" type="text/javascript">
	var frm_validator = new Validator("form_cadastro");
	frm_validator.addValidation("estabelecimento_id","dontselect=0","O campo ESTABELECIMENTO não foi selecionado!");
	frm_validator.addValidation("coleta_data","required","O campo DATA não foi preenchido!");
	frm_validator.addValidation("coleta_data","final","Corrija todo(s) o(s) erro(s)!!!");
</script>