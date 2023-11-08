<?php

  	$pesquisa_id = $_REQUEST['hid'];
	$pesquisa_mes = $_REQUEST['mes'];
	$pesquisa_ano = $_REQUEST['ano'];
	$action = $_REQUEST['haction'];
	$herr = '';

	if ($action=='edit'){	  
	  	$strsql = "SELECT pesquisa_id,EXTRACT(MONTH FROM pesquisa_data) AS mes_id,EXTRACT(YEAR FROM pesquisa_data) AS pesquisa_ano FROM tabela_pesquisas WHERE pesquisa_id = '".$pesquisa_id."'";
		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		$res = mysqli_fetch_array($res);
		$pesquisa_id = $res['pesquisa_id'];
		$pesquisa_mes = $res['mes_id'];
		$pesquisa_ano = $res['pesquisa_ano'];
	}
	
	if ($action=='save'){	  
	  
		$strsql = "SELECT * FROM tabela_pesquisas WHERE (pesquisa_data = '".$pesquisa_ano."-".$pesquisa_mes."-00') AND pesquisa_id <> '".$pesquisa_id."'";

	  $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn)); 	
		
		if ($res && mysqli_num_rows($res)>0)
		  $herr = "Existe outra pesquisa para o mesmo m�s e ano.";
		else{
		  
		  if ($pesquisa_id!='')
		    $strsql = "UPDATE tabela_pesquisas SET pesquisa_data = '".$pesquisa_ano."-".$pesquisa_mes."-00' WHERE pesquisa_id = '".$pesquisa_id."'";  		
		  else
		    $strsql = "INSERT INTO tabela_pesquisas (pesquisa_data,pesquisa_detalhada) VALUES ('".$pesquisa_ano."-".$pesquisa_mes."-00','1')";
			
			
			//die($strsql);
			
			mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
			
			$pesquisa_id = '';
			$pesquisa_mes = '';
			$pesquisa_ano = '';
	    	$action = '';
			header("Location: cadastro_pesquisas.php");
			
		}//do else de num_rows > 0
	}//do if save
	
	if ($action=='del'){
	
		$strsql = "DELETE FROM tabela_pesquisa_resultados_produtos WHERE pesquisa_id = '".$pesquisa_id."'";
		mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
		
		$strsql = "DELETE FROM tabela_pesquisas_cidades WHERE pesquisa_id = '".$pesquisa_id."'";
		mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
		
                $strsql = "SELECT coleta_id FROM tabela_coletas WHERE pesquisa_id = '".$pesquisa_id."'";
                $res_coleta = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		    
                if(mysqli_num_rows($res_coleta)!=0){
			$coletas_id = array();
                
			while ($coleta_array = mysqli_fetch_assoc($res_coleta)){
                    $coletas_id [] = $coleta_array['coleta_id'];
			}
                
			$strsql = "DELETE FROM tabela_precos WHERE coleta_id IN (".implode(',',$coletas_id).")";
			mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		   }
		   
                               
                $strsql = "DELETE FROM tabela_coletas WHERE pesquisa_id = '".$pesquisa_id."'";
                mysqli_query($conn, $strsql) or die(mysqli_error($conn));
                
                $strsql = "DELETE FROM tabela_pesquisas WHERE pesquisa_id = '".$pesquisa_id."'";
		mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		header("Location: cadastro_pesquisas.php");
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
			<?php require("menu_lateral_coletas.php"); ?>
			</div>
			
			<div class="conteudo_pagina">
			<h1 id="Mcaption" style="text-align:left">Cadastro de Pesquisas</h1>			 
								
		<fieldset>
			
			<legend>
				<?php if($pesquisa_id) {?>Editar<?php } else {?>Adicionar<?php }?> Pesquisa</legend>
						
						
			
			<form name="form_cadastro" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>" style="width:552px;">
	
			<?php if($pesquisa_id) {?>
			<p>
				<label for="codigo">C&oacute;digo:</label> 
				<input type="text" disabled="disabled" size="5" class="inactive" value="<?php echo($pesquisa_id); ?> "/>
				<input type="hidden" name="hid" value="<?php echo($pesquisa_id); ?>">
			</p>
			<?php } ?>
			<p>
					
					<label for="mes">M&ecirc;s:</label>
					<select name="mes" >
				
						<option value="0"><--------   Escolha o M&ecirc;s  --------></option>
					
						<?php
							
							$strsql = "SELECT * FROM tabela_mes";
							$mes = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
					
							if ($mes && mysqli_num_rows($mes)>0)	
								while($row = mysqli_fetch_array($mes))
								{
						?>
						
						<option value="<?php echo($row['mes_id']); ?>" <?php if($pesquisa_mes == $row['mes_id']){?> selected="selected" <?php } ?>  > <?php echo ($row['mes_nome']);?></option>
					
						<?php
						}	 	
						?>	
										
					</select>
					<span class="obrig">*</span>
				</p>
				
				<p>
					<label for="ano">Ano:</label>
					<select name="ano" >
				
						<option value="0"><--------   Escolha o Ano  --------></option>
					
						<?php
						
							$ano = date('Y');
							while($ano > 1998)
							{
						?>
						
						<option value="<?php echo($ano); ?>"  <?php if($pesquisa_ano == $ano){?>selected="selected" <?php } ?>  > <?php echo ($ano);?></option>
					
						<?php
						
						$ano--;
						}	 	
						?>	
										
					</select>
					<span class="obrig">*</span>
				</p>
			
			
			<p>
				<input type="hidden" name="haction" value="save">
				<input type="submit" class="botao_submit" value="<?php if($pesquisa_id) {?>Editar<?php } else {?>Adicionar<?php }?>" size="40" />
				<input type="button" value="Cancelar" class="botao_cancelar" onclick="javascript: go_Page('cadastro_pesquisas.php');"/>
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
<form name="frm_send_data" method="post" action="">
<input type="hidden" name="haction" value="">
<input type="hidden" name="hid" value="">
</form>
<script language="javascript" type="text/javascript">

	var frm_validator = new Validator("form_cadastro");
	frm_validator.addValidation("mes","dontselect=0","O campo M�S n�o foi selecionado!");
	frm_validator.addValidation("ano","dontselect=0","O campo ANO n�o foi selecionado!");
	frm_validator.addValidation("ano","final","Corrija todo(s) o(s) erro(s)!!!");
		
	function atualiza_paginacao(pagina)
	{
	
		var url = '';
		
		url = 'lista_pequisas.php?pagina='+escape(pagina);
		new Ajax.Updater('tabela',url, {method: 'post',asynchronous:true});
	}
</script>