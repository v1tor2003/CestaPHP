<?php 
	
	mysqlii_set_charset($conn,'utf8');

	$action = $_REQUEST['haction'];
	$produto_id = $_REQUEST['hid'];
	$produto_nome = $_REQUEST['produto_nome'];
	$produto_nome_visualizacao = $_REQUEST['produto_nome_visualizacao'];
	$produto_cesta = $_REQUEST['produto_cesta'];
	$produto_tipo = $_REQUEST['produto_tipo'];
	
	/************************************************************************************************
	*	
	*	- Quantidade de medidas dos produtos 
	*	- Inicializa os vetores que conterão informações sobre as medidas
	*
	*************************************************************************************************/
	
	$medidas_pesquisada = array();
	$medidas = array();
	$quantidade = 0;
	
	$herr = '';
	
	if ($action=='edit')
	{
	
		$strsql = "SELECT A.produto_id,A.produto_nome,A.produto_cesta,A.produto_nome_visualizacao,B.medida_descricao,B.medida_simbolo,C.medida_id,C.medida_pesquisada,A.produto_tipo FROM tabela_produtos A, tabela_unidade_medidas B, tabela_produtos_medidas C WHERE (A.produto_id = C.produto_id AND C.medida_id = B.medida_id) AND A.produto_id ='".$produto_id."'";
		
		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		
		if ($res && mysqli_num_rows($res)>0)	
			while($row = mysqli_fetch_array($res))
			{
				$produto_nome = $row['produto_nome'];
				$produto_nome_visualizacao = $row['produto_nome_visualizacao'];
				$produto_cesta = $row['produto_cesta'];
				$produto_tipo = $row['produto_tipo'];
				array_push($medidas,$row['medida_id']);
				array_push($medidas_pesquisada,$row['medida_pesquisada']);
			}
				
			$quantidade = count($medidas);
			
	}
	else
		if ($action=='save')
		{	  
		  
			$strsql = "SELECT * FROM tabela_produtos WHERE produto_nome = '".$produto_nome."' AND produto_id <> '".$produto_id."'";
			
			$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn)); 	
				
			if ($res && mysqli_num_rows($res)>0)
			  $herr = "Existe outro produto com o mesmo nome.";
			else
			{
			
				/************************************************************************************************
				*	
				*	- Preenche os vetores com as informações das medidas 
				*
				*************************************************************************************************/
			
				
				if(isset($_REQUEST['medidas_produto']))
				{
					$medidas = $_REQUEST['medidas_produto'];
					$medidas_pesquisada = $_REQUEST['medida_pesquisada'];
					$cont = count($medidas_pesquisada);
				}
			
			  
		
				if ($produto_id!='')
			  	{
			  		$strsql = "UPDATE tabela_produtos SET produto_nome = '".$produto_nome."',produto_nome_visualizacao = '".$produto_nome_visualizacao."', produto_cesta= '".$produto_cesta."',produto_tipo= '".$produto_tipo."' WHERE produto_id = '".$produto_id."'"; 					
					mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
				
					$strsql = "DELETE FROM tabela_produtos_medidas WHERE produto_id = '".$produto_id."'";
					mysqli_query($conn, $strsql) or die(mysqli_error($conn));
				
			  	} 		
			  	else
			  	{
					$strsql = "INSERT INTO tabela_produtos (produto_nome,produto_nome_visualizacao,produto_cesta,produto_tipo) VALUES ('".$produto_nome."','".$produto_nome_visualizacao."','".$produto_cesta."','".$produto_tipo."')";
					//die($strsql);
					mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
					
					
					$strsql = "SELECT produto_id FROM tabela_produtos WHERE produto_nome = '".$produto_nome."'";
					$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
					$row = mysqli_fetch_array($res);
					$produto_id = $row['produto_id'];	
				} 	
				
				/************************************************************************************************
				*	
				*	- Insere as informações de medidas do produto 
				*
				*************************************************************************************************/
				
				for($i=0;$i<$cont;$i++)
				{
					
					$strsql = "INSERT INTO tabela_produtos_medidas (produto_id,medida_id,medida_pesquisada) VALUES ('".$produto_id."','".$medidas[$i]."','".$medidas_pesquisada[$i]."')";
					mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
				}
				
			}//fim do else de (num_rows > 0)
			
			
			$medidas_pesquisada = array();
			$medidas = array();
			$medidas_descricao = array();
			$medida_id = '';
			$quantidade = '';
			$produto_id = '';
			$produto_nome = '';
			$produto_nome_visualizacao = '';
			$produto_cesta = '';
			$produto_tipo = '';
			$action = '';
				
		}//fim do if($action == 'save')
		else
			if ($action=='del')
			{
			
				/*$strsql = "DELETE FROM tabela_produtos_medidas WHERE produto_id= '".$produto_id."'";
				mysqli_query($conn, $strsql) or die(mysqli_error($conn));	*/
				
				$strsql = "DELETE FROM tabela_produtos WHERE produto_id= '".$produto_id."'";
				mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
				
				
				header("Location: ".$_SERVER['PHP_SELF']."?hp=".$_REQUEST['hp']);
			}
			
		
	$records_per_page = 5;		
  	$start_rec = ($_REQUEST['hp']!='') ? $_REQUEST['hp'] : 0;
  	$strsql = "SELECT * FROM tabela_produtos";   
	$produtos = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
	$total_rec = mysqli_num_rows($produtos);
	
	if ($start_rec>=$total_rec) $start_rec -= $records_per_page;
	if ($start_rec<0) $start_rec=0;	
	
	$last_rec = ($start_rec + $records_per_page > $total_rec) ? $total_rec : $start_rec + $records_per_page;  
	$back_rec = $start_rec - $records_per_page;
	$pages = floor($total_rec/$records_per_page);
	$start_last_page = ($pages*$records_per_page==$total_rec) ? ($pages-1)*$records_per_page : $pages*$records_per_page;
	$pagina_atual = floor(($start_rec == 0 )? 1 : (($start_rec/$records_per_page)+1));
	

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
			
			<h1 id="Mcaption" style="text-align:left">Cadastro de Produtos</h1>
			<table cellspacing="0" style="border:none;">
					<tr><td>
			
			<!-- Conteúdo referente a esta página -->
					<?php 
						  	$strsql = "SELECT * FROM tabela_produtos ORDER BY produto_id LIMIT ".$start_rec.",".$records_per_page;

							$produtos = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
							
							if ($produtos && mysqli_num_rows($produtos)>0){	
					?>
					<table cellspacing="0" id="listTable" summary="Tabela de Produtos" style="width:613px;">
					<colgroup>
						<col id="codigo" />
						<col id="nome" />
						<col id="cesta" />
						<col id="acoes" />
					</colgroup>		
					<thead>
						<tr>
							<th scope="col" class="tdboderCod">C&oacute;digo</th>
							<th scope="col" class="tdboder">Nome</th>
							<th scope="col" class="tdboder">Nome Espec&iacute;fico</th>
							<th scope="col" class="tdboder">CB</th>
							<th scope="col" colspan="2" class="tdboder">A&ccedil;&otilde;es</th>
						</tr>
					</thead>
						 <?php
						 	
							while ($row = mysqli_fetch_array($produtos)){
								if($l_cor == '') $l_cor = "par"; else $l_cor = "";
						  ?>
							   <tr class="<?php echo ($l_cor);?>">
								 <td class="tdboderCod"><?php echo($row['produto_id']); ?></td>
								  <td class="tdboder"><?php echo($row['produto_nome_visualizacao']); ?></td>
								 <td class="tdboder"><?php echo($row['produto_nome']); ?></td>
								 <td class="tdboderCod"><?php if($row['produto_cesta'] == 1){?>S<?php }else{?>N<?php }?></td>
								 <td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($row['produto_id']); ?>','edit', '','','');"><img src="images/botao_editar.png" border="0"></a></td>
								 <td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($row['produto_id']); ?>','del','<?php echo($row['produto_nome']); ?>','Deseja apagar o produto','<?php echo($_SERVER['PHP_SELF']); ?>');"><img src="images/botao_deletar.png" border="0"></a></td>
								 </tr>
								 <?php	   
								 }//do while
						 ?>
						 </table>	
						 </td>
						 </tr>
						 <tr><td>
						  <table align="left" style="border:0; margin-top:10px;" width="640px;">
			<tr>
			<td align="left">&nbsp;
			<?php if ($start_rec!=0) {?>
			<span class="pag_links"><a href="<?php echo($_SERVER['PHP_SELF']."?hp=0"); ?>" >Primeira</a></span>&nbsp;&nbsp;|&nbsp;<?php }else{ ?> <span class="pag_links">Primeira</span>&nbsp;&nbsp;|&nbsp;<?php }?>
			<?php if ($back_rec>=0) {?>
			<span class="pag_links"><a href="<?php echo($_SERVER['PHP_SELF']."?hp=".($back_rec)); ?>">Anterior</a></span>
			<?php }else{?>
			<span class="pag_links">Anterior</span>
			<?php } ?>
			</td>
			<td align="center">
			P&aacute;gina: <b><?php echo($pagina_atual); ?></b> &nbsp;&nbsp; Resultados: <b><? echo((($total_rec) ? $start_rec+1 : 0)." - ".$last_rec);?></b> de <b><?php  echo($total_rec); ?></b>
			</td>
			<td align="left">
			<?php if ($last_rec<$total_rec) {?>
			<span class="pag_links"><a href="<?php echo($_SERVER['PHP_SELF']."?hp=".($last_rec)); ?>">Pr&oacute;xima</a></span>&nbsp;&nbsp;|&nbsp;<?php }else {?>
			<span class="pag_links">Pr&oacute;xima</span>&nbsp;&nbsp;|&nbsp;
			<?php } ?>
			<?php if ($start_rec+$records_per_page<$total_rec) {?>
			<span class="pag_links"><a href="<?php echo($_SERVER['PHP_SELF']."?hp=".($start_last_page)); ?>">&Uacute;ltima</a></span>
			<?php }else{ ?>
				<span class="pag_links">&Uacute;ltima</span>
			<?php } ?>
			
			</td>
			</tr>
			</table>
			
			</td></tr>
				
						 <?php }else{ ?>
						 <h1 id="Mcaption" style="text-align:left">Sem produtos cadastrados</h1></td></tr>
						 <?php }?>
						 
						
						
			<tr><td>			
		<fieldset>
			
			<legend>
				<?php if($produto_id) {?>Editar<?php } else {?>Adicionar<?php }?> Produto 
			</legend>
						
						
			
			<form name="form_cadastro" id="cadastro" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>" style="width:590px;" onsubmit="return validate_form(this);">
			<?php if($produto_id) {?>
			<p>
				<label for="codigo">C&oacute;digo:</label> 
				<input type="text" disabled="disabled" size="5" class="inactive" value="<?php echo($produto_id); ?> "/>
				<input type="hidden" name="hid" value="<?php echo($produto_id); ?>">
			</p>
			<?php } ?>
			<p>
			<label for="nome">Nome:</label>
				<input type="text" maxlength="100" id="produto_nome_visualizacao" name="produto_nome_visualizacao" size="40" value="<?php echo($produto_nome_visualizacao); ?>" />
				<span class="obrig">*</span>
			</p>
			
			<p>
				<label for="nome">Nome Espec&iacute;fico:</label>
				<input type="text" maxlength="100" id="produto_nome" name="produto_nome" size="40" value="<?php echo($produto_nome); ?>" />
				<span class="obrig">*</span>
				<input type="hidden" name="haction" value="save"/>
				<input type="hidden" name="quantidade" value="<?php echo($quantidade)?>" id="quantidade" />
				<p>
					<label for="tipo">Tipo:</label>
					<select name="produto_tipo">
				
						<option value="0"><--------   Escolha o Tipo  --------></option>
					
						<?php
							
							$strsql = "SELECT * FROM tabela_tipos_produtos";
							$tipos = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
					
							if ($tipos && mysqli_num_rows($tipos)>0)	
								while($row = mysqli_fetch_array($tipos))
								{
						?>
						
						<option value="<?php echo($row['tipo_id']); ?>" <?php if($produto_tipo == $row['tipo_id']){?>selected="selected" <?php } ?>  > <?php echo ($row['tipo_nome']);?></option>
					
						<?php
						}	 	
						?>	
										
					</select>
					<span class="obrig">*</span>
				</p>
			<hr />
			<?php 
			
				$strsql = "SELECT * FROM tabela_unidade_medidas";
						$med = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
					
						$i = 0;
						if ($med && mysqli_num_rows($med)>0){	
			?>
				<table class="tabela_produtos" cellpadding="0" cellspacing="0">
				<caption style="margin-right:270px;">Medidas Pesquisadas</caption>
				<?				
						while($row = mysqli_fetch_array($med))
						{
							
							$checked = '';
							$value = '';
							$disabled = '';
							$class = '';
							
							$indice = busca_chave($row['medida_id'],$medidas);
							
							if($indice != -1)
							{
								$checked = 'checked="checked"';
								$value = $medidas_pesquisada[$indice];
								$class = 'class="active"';
							}
							else
							{
								$disabled = 'disabled="disabled"';
								$class = 'class="inactive"';
							}
							
							if($i%3==0){?>  </tr><tr> <?php }?>
			
							<td style="padding-top:20px;">
					<label><?php echo($row['medida_simbolo']);?></label><input type="checkbox" onClick="edita_medida('<?php echo($i);?>')" name="medidas_produto[]" id="medidas_produto" value="<?php echo($row['medida_id']); ?>" <?php echo($checked); ?> />&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" size="8" name="medida_pesquisada[]" maxlength="5" onkeypress="mascara(this,soNumeros2)" id="<?php echo("medida_pesquisada".$i); ?>" <?php echo($disabled." ".$class);?> value="<?php echo($value); ?>" /><input type="hidden" id="<?php echo("medida_descricao".$i); ?>" value="<?php echo($row['medida_descricao']); ?>" /></td>
					<?php
						$i++;
						}	 	
					?>	
					</tr>
					</table>
					<?php }else{ ?>
					<label>Sem medidas cadastradas. </label>
					<?php } ?>
					
					<hr />
			</p>
			<p><label for="cesta">Produto Cesta:</label>
			<input type="checkbox" name="produto_cesta" value="1" <?php if ($produto_cesta == 1){ ?> checked="checked" <?php } ?> style="vertical-align:bottom;" />
			<input type="hidden" name="hp" value="<?php echo($start_rec);?>" />
			<br /></p>
			
			<p>
				<input type="submit" class="botao_submit"value="<?php if($produto_id) {?>Editar<?php } else {?>Adicionar<?php }?>" size="40" />
				<?php if($produto_id != ''){?>
				<input type="button" value="Cancelar" class="botao_cancelar" onclick="javascript: go_Page('<?php echo($_SERVER['PHP_SELF']."?hp=".($start_rec)); ?>');"/>
					<?php }?>
					
			</p>
			<p class="obrig" align="right" style="color:#FF0000">* Campos obrigat&oacute;rios&nbsp;&nbsp;&nbsp;</p>
						
			
		</form> 
		</fieldset>	
		</td>
		</tr>
		</table>		
			
	</div>
			
			
	<div class="clearer"><span></span></div>

	<div class="rodape">&nbsp </div>
	
	<?php if($herr != ''){ ?>
			
			<script type="text/javascript" language="javascript">
			
			alert('<?php echo($herr);?>');
			
			</script>
			
			
	<?php } ?> 

</div>
<form name="frm_send_data" method="post" action=""/>
<input type="hidden" name="hp" value="<?php echo($start_rec);?>" />
<input type="hidden" name="haction" value=""/>
<input type="hidden" name="hid" value=""/>
</form>
</body>
</html>

<script type="text/javascript" language="javascript">
function edita_medida(indice)
{	
	var check_medida = document.getElementById("cadastro").medidas_produto[indice];
	var input_text;
	
	input_text = document.getElementById("medida_pesquisada"+indice);
	
	if(check_medida.checked == true)
	{	
		input_text.className = "active";
		input_text.disabled = null;
	}
	else
	{
		input_text.className = "inactive";
		input_text.setAttribute("disabled","disabled");
		input_text.value = '';
		
	}
	
}

function validate_required(str)
{
	  if (str==null||str=="")
	  {
	  	return false;
	  }
	  else
	  {
	  	return true;
	  }

}

function validate_form(form)
{
	var check_medidas = form.medidas_produto;
	var tam = check_medidas.length;
	var msg_alert = '';
	var input_text = null;
	var flag = true;
	var medida = null;
	var i,j;
	
	input_text = document.getElementById("produto_nome");
	input_text1 = document.getElementById("produto_nome_visualizacao");
	
	if (validate_required(input_text.value) == false)
	{
		flag = false;
		msg_alert += 'O campo NOME ESPECIFICO não foi preenchido!\n'; 
	}
	
	if (validate_required(input_text1.value) == false)
	{
		flag = false;
		msg_alert += 'O campo NOME não foi preenchido!\n'; 
	}
	

	for(var i = 0,j=0 ; i < tam ; i++)
	{
		if(check_medidas[i].checked == true)
		{
			input_text = document.getElementById("medida_pesquisada"+i);
		
			if (validate_required(input_text.value) == false)
			{
				flag = false;
				medida = document.getElementById("medida_descricao"+i).value;
				msg_alert += 'A medida ' + medida.toUpperCase() + ' não foi preenchida!\n'; 
			}
			
		}
		else
			j++;
			
	}
	
	if(tam == j)
	{
		flag = false;
		msg_alert += 'Nenhuma MEDIDA cadastrada!\n';
	}
	
	msg_alert += '\nCorrija todo(s) o(s) erros!'; 
	
	if(!flag)
	{
		alert(msg_alert);
		return false;
	}

}
		
</script>