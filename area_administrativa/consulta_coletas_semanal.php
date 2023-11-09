<?php
	error_reporting(E_ERROR | E_PARSE);
	function validateDate($date, $format = 'Y-m-d'){
		if (!isset($date) || is_null($date) ||strlen($date)<8)
			return false;
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}

	$action = $_REQUEST['haction'];		
	$data_ini = $_REQUEST['data_ini'];
	$data_fim = $_REQUEST['data_fim'];
	$cidade_id = $_REQUEST['cidade_id'];
	$bairro_id = $_REQUEST['bairro_id'];
	$herr = '';
	//echo "$action--$data_ini--$data_fim--$cidade_id--$bairro_id<br>";
    if (isset($action)){
		if (!validateDate($data_ini)) $herr .= 'Verifique o formato da Data inicial!\n';
		if (!validateDate($data_fim)) $herr .= 'Verifique o formato da Data final!\n';
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
			<?php require("menu_lateral_consultas.php"); ?>
		</div>		
		<div id="principal" class="conteudo_pagina">
			<!-- Contedo referente a esta página -->
			<h1 id="Mcaption" style="text-align:left">Consulta de Preços - Semanal</h1>
			<form id="form_filtro" name="form_filtro"  method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>">
				<fieldset style="width:655px; margin-top:0px; padding-right: 0px;">
				<legend>Filtro</legend>
				<table style="border: 0px;"cellspacing="0" cellpadding="0" id="listTable" summary="Tabela de filtro" >
					<tr>
						<td width="130px" align="right"><span class="legend_filtro">Inicio (aaaa-mm-dd)</span></td>
						<td width="130px" align="right"><span class="legend_filtro">Fim  (aaaa-mm-dd)</span></td>
						<td width="" align="right"><span class="legend_filtro">Cidade</span></td>
						<td width="" align="right"><span class="legend_filtro">Bairro</span></td>
						<td width="" align="left">
							&nbsp;							
							<input type="hidden" name="haction" value="filtrar" />
						</td>
					</tr>
					<tr>
						<td align="right">
							<input type="text" name="data_ini" id="data_ini" value="<?php echo $data_ini ?>"  style="width:90px;">
							<span class="obrig">*</span>
						</td>
						<td align="right">
							<input type="text" name="data_fim" id="data_fim" value="<?php echo $data_fim ?>"  style="width:90px;">
							<span class="obrig">*</span>
						</td>
						<td align="right">
							<select name="cidade_id" id="cidade" style="width:90px;" size="1" onChange="pop_select('procura_bairros.php','&nbsp;')">
								<option value="">&nbsp;</option>
								<?php
                                	$strsql = "SELECT * FROM tabela_cidades";
                                	$cidades = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
                                	if ($cidades && mysqli_num_rows($cidades) > 0)
                                    	while ($row = mysqli_fetch_array($cidades)) {
								?>
											<option value="<?php echo($row['cidade_id']); ?>" <?php if ($cidade_id == $row['cidade_id']) {?>selected="selected" <?php } ?>  > <?php echo ($row['cidade_nome']); ?></option>
								<?php
									}	
								?>							
							</select>				
						</td>
						<td align="right" size="1">
							<select name="bairro_id" id="select_pop" style="width:120px;" <?php if ($cidade_id == '') { ?> class="select_desativado" disabled="disabled" <?php } ?>>
							<option value=""></option>
							<?php 
								if (isset($cidade_id)){
									$strsql = "SELECT * FROM tabela_bairros WHERE cidade_id = '" . $cidade_id . "'";
                                	$bairros = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
                                	if ($bairros && mysqli_num_rows($bairros) > 0)
                                    	while ($row = mysqli_fetch_array($bairros)) {
                            ?>
                                        	<option value="<?php echo($row['bairro_id']); ?>" <?php if ($bairro_id == $row['bairro_id']) { ?>selected="selected" <?php } ?>  > <?php echo ($row['bairro_nome']); ?></option>

							<?php 		
										}//do while bairros

								}//do if cidade_id
							?>
							</select>
						</td>						
						<td>
							<input class="botao_submit" type="submit"  value="Aplicar" style="width:65px;" />
						</td>
					</tr>
				</table>
				<p class="obrig" align="right" style="color:#FF0000;margin-left:10px;">* Campos obrigat&oacute;rios&nbsp;&nbsp;&nbsp;</p>
				</fieldset>
				</form>
			<?php
			if ($action=='filtrar' && $herr==''){
			$strsql =  "SELECT A.coleta_id,
							  A.coleta_data,
							  D.cidade_nome,
							  C.bairro_nome,
							 B.estabelecimento_nome
						FROM tabela_coletas A, 
							 tabela_estabelecimentos B,
							 tabela_bairros C,
							 tabela_cidades D 
						WHERE (A.estabelecimento_id = B.estabelecimento_id) AND 	  
							(C.bairro_id =B.bairro_id) AND 
							(C.cidade_id = D.cidade_id) AND 	  
							(coleta_data >= '".$data_ini."') AND 
							(coleta_data <= '".$data_fim."')";
						if (isset($cidade_id) and $cidade_id!='' and $cidade_id!='0'){
							$strsql .= "AND (D.cidade_id = $cidade_id)";
							if (isset($bairro_id) and $bairro_id!='' and $bairro_id!='0')
								$strsql .= "AND (C.bairro_id = $bairro_id)"; 
						}	
			//echo "<br>$strsql<br>";
			$coletas = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
			$nColetas =  mysqli_num_rows($coletas);
			//echo "<br>nColetas = $nColetas<br>";
			if ($coletas && $nColetas > 0)
				{
			?>
				<div id="produtos_oficiais">
					<table cellspacing="0" id="listTable" summary="Tabela de Preços das Coletas" style="width:680px;">
						<colgroup>
							<col id="codigo" />
							<col id="produto" />
							<col id="medida" />
							<col id="cidade" />
							<col id="bairro" />
							<col id="local" />
							<col id="data" />
							<col id="preco" />
							<col id="media" />
							<col id="total" />
						</colgroup>		
						<thead>
						<tr>
							<th scope="col" class="tdboderCod">C&oacute;d.</th>
							<th scope="col" class="tdboder">Produto</th>
							<th scope="col" class="tdboder">Coleta</th>
							<th scope="col" class="tdboder">Cidade</th>
							<th scope="col" class="tdboder">Bairro</th>
							<th scope="col" class="tdboder">Local</th>
							<th scope="col" class="tdboder">&nbsp;&nbsp; Data &nbsp;&nbsp;</th>
							<th scope="col" class="tdboder">M&eacute;dia Obs.</th>
							<th scope="col" class="tdboder">M&eacute;dia</th>
							<th scope="col" class="tdboder">Total</th>
						</tr>
						</thead>
						<?php
						   for ($i=0; $i < $nColetas; $i++) { 
								$row_col = mysqli_fetch_array($coletas);								
								$strsql =  "SELECT 	A.produto_id, 
													A.produto_nome_visualizacao, 
													B.medida_simbolo,
													C.precos_media_observado,
													C.precos_media,
													C.precos_total
											FROM tabela_produtos A, 
												tabela_unidade_medidas B, 
												tabela_precos C 
											WHERE (A.produto_id = C.produto_id) AND 
												(B.medida_id = C.medida_id) AND 	   
												(A.produto_cesta = 1) AND
												(coleta_id = ".$row_col['coleta_id'].")
											ORDER BY A.produto_id"; 
								//echo("<tr><td colspan=10>$strsql</td></tr>");			  
								$produtos = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
								$nProdutos =  mysqli_num_rows($produtos);
								if ($produtos && $nProdutos > 0){
									while ($row_prod = mysqli_fetch_array($produtos)){	
										if($l_cor == '') $l_cor = "par"; else $l_cor = "";							
							?>
									<tr class="<?php echo ($l_cor);?>">
										<td class="tdboderCod"><?php echo($row_prod['produto_id']); ?></td>
										<td class="tdboder"><?php echo $row_prod['produto_nome_visualizacao']; ?></td>
										<td class="tdboder"><?php echo $row_col['coleta_id']; ?></td>
										<td class="tdboderCod"><?php echo $row_col['cidade_nome']; ?></td>
										<td class="tdboderCod"><?php echo $row_col['bairro_nome']; ?></td>
										<td class="tdboderCod"><?php echo $row_col['estabelecimento_nome']; ?></td>
										<td class="tdboderCod"><?php echo $row_col['coleta_data']; ?></td>
										<td class="tdboderCod"><?php echo $row_prod['precos_media_observado']; ?></td>
										<td class="tdboderCod"><?php echo $row_prod['precos_media']; ?></td>
										<td class="tdboderCod"><?php echo $row_prod['precos_total']; ?></td>
									</tr>		
							<?php
									}//do laco de produtos   
								}//do if de produtos>0
							}//do laço for de coletas
						?>								
					</table>
				</div>
			<?php
				}else //de if verificacao de numero de coletas
					if ($action=="filtrar")
						echo "<div>Não existem coletas para os parâmetros especificados no filtro.</div>"		
			
			?>							
		<?php
			}//do if haction e herr
		?>		
		</div>
		<div class="clearer"><span></span></div>
		<div class="rodape">&nbsp </div>		
	</div>
</body>
<?php if ($herr != '') { ?>
<script type="text/javascript" language="javascript">
	alert('<?php echo($herr); ?>');
</script>
<?php } ?> 
<script language="javascript" type="text/javascript">
	var frm_validator = new Validator("form_filtro");
	frm_validator.addValidation("data_ini","required","A data incial é obrigatória!");
	frm_validator.addValidation("data_fim","required","A data final é obrigatória!");
	frm_validator.addValidation("data_fim","final","Corrija todo(s) o(s) erro(s)!!!");
</script>
</html>
