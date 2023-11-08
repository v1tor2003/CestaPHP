<?php 
	$coleta_id = $_REQUEST['coleta_id'];
	$produto_id = $_REQUEST['hid'];
	$medida_id = $_REQUEST['hid1'];
	$action = $_REQUEST['haction'];
	$pesquisa_id = $_REQUEST['pid'];
	$precos_id = $_REQUEST['precos_id'];
	$flag = false;
	$aux = array();
	
	if($action == 'adicionar_produto')
	{
		//Insere o produto na tabela precos
		$aux = preg_split ('/[\/]/', $produto_id);  
		
		$produto_id = $aux[0];
		$medida_id = $aux[1];
		
		$strsql = "SELECT produto_id FROM tabela_precos WHERE coleta_id = '".$coleta_id."' AND produto_id = '".$produto_id."'";
		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		
		if(mysqli_num_rows($res)>0)
		{
			$flag = true;
			$herr = 'Produto já cadastrado com outra medida!';
		}
		
		if(!$flag)
		{
			$strsql = "SELECT produto_nome FROM tabela_produtos WHERE produto_id = ".$produto_id."";
			
			$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
			$res = mysqli_fetch_array($res);
			$produto_nome = $res['produto_nome_visualizacao'];
			
			$strsql = "INSERT INTO tabela_precos (produto_id,coleta_id,medida_id) VALUES ('".$produto_id."','".$coleta_id."','".$medida_id."')";
		
			$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
			$action = 'edit';
		}
	}

	if($action == 'edit')
	{	
		
	
		$strsql = "SELECT A.precos_id,B.produto_nome_visualizacao FROM tabela_precos A,tabela_produtos B WHERE A.coleta_id = '".$coleta_id."' AND ( A.produto_id='".$produto_id."' AND A.medida_id = '".$medida_id."' ) AND (A.produto_id = B.produto_id)";
		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		$res = mysqli_fetch_array($res);
		$precos_id = $res['precos_id'];
		$produto_nome = $res['produto_nome_visualizacao'];
		
		$strsql = "SELECT COUNT(*) cont FROM tabela_auxiliar_precos WHERE precos_id = ".$precos_id."";
		$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
		$res = mysqli_fetch_array($res);
		$cont = $res['cont'];
		
		$precos = array();
		
		if($cont != 0)
		{
		
			$strsql = "SELECT preco_produto FROM tabela_auxiliar_precos WHERE precos_id = '".$precos_id."'";
			$res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
			
			while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC))	
				array_push($precos,$row['preco_produto']);
				
		}
				
	}
	
	if ($action=='del')
	{	
		
		$strsql = "DELETE FROM tabela_precos WHERE coleta_id = '".$coleta_id."' AND (produto_id = '".$produto_id."' AND medida_id = '".$medida_id."')";
		mysqli_query($conn, $strsql) or die(mysqli_error($conn));	
		header("Location:".$_SERVER['PHP_SELF']."?coleta_id=".$coleta_id."&pid=".$pesquisa_id);	
				
	}
	
	
	$records_per_page = 5;		
  	$start_rec = ($_REQUEST['hp']!='') ? $_REQUEST['hp'] : 0;
  	$strsql = "SELECT A.produto_id,B.produto_nome_visualizacao,C.medida_descricao,C.medida_simbolo,A.precos_id,A.medida_id FROM tabela_precos A,tabela_produtos B,tabela_unidade_medidas C WHERE A.coleta_id = '".$coleta_id."' AND (A.produto_id = B.produto_id AND A.medida_id = C.medida_id)";   
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
			<?php require("menu_lateral_coletas.php"); ?>
			</div>
			
			
			<div id="principal" class="conteudo_pagina">
			<!-- Contedo referente a esta pgina -->
			
			<?php 
					
			$strsql = "SELECT * FROM tabela_coletas A, tabela_estabelecimentos B,tabela_bairros C,tabela_cidades D  WHERE (A.estabelecimento_id = B.estabelecimento_id) AND coleta_id = '".$coleta_id."' AND C.bairro_id = B.bairro_id AND C.cidade_id = D.cidade_id";
					
			$coletas = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
							
			if ($coletas && mysqli_num_rows($coletas)>0)
			{
				$row = mysqli_fetch_array($coletas);		
				$estabelecimento_nome = $row['estabelecimento_nome'];
				$data = formata_data($row['coleta_data'],1);
				$bairro = $row['bairro_nome'];
				$cidade = $row['cidade_nome'];
						
			}
			?>
			<a href="<?php echo('cadastro_coletas.php?pesquisa_id='.$pesquisa_id); ?>"><img style=" float:right; border:none;" src="images/seta_azul.png" ></a>
			<h1 id="Mcaption" style="text-align:left">Coleta: <?php echo ($estabelecimento_nome." - ".$data."<br>Local &nbsp;: ".$cidade." - ".$bairro); ?></h1>
			<hr />
			<h1 id="Mcaption" style="text-align:left">Produtos Cadastrados</h1>
			<!--<table  border="0px" id="link_table_add"  style="width:590px"><tr><td>[<a href="" id="link_adicionar" >Adicionar Produtos</a>]</td></tr></table>-->
			<?php 
					
				$strsql = "SELECT A.produto_id,B.produto_nome_visualizacao,C.medida_descricao,C.medida_simbolo,A.precos_id,A.medida_id FROM tabela_precos A,tabela_produtos B,tabela_unidade_medidas C WHERE A.coleta_id = '".$coleta_id."' AND (A.produto_id = B.produto_id AND A.medida_id = C.medida_id) ORDER BY A.produto_id  LIMIT ".$start_rec.",".$records_per_page;
				$produtos = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
				if ($produtos && mysqli_num_rows($produtos)>0){
				
				$qtd_prod = mysqli_num_rows($produtos);
				
				$strsql = "SELECT MAX(quantidade) as maximo FROM (SELECT precos_id, count(*) as
quantidade FROM tabela_auxiliar_precos GROUP BY precos_id) As table1";

				$res= mysqli_query($conn, $strsql) or die(mysqli_error($conn)); 
				$res = mysqli_fetch_array($res);
				$qt = $res['maximo'];
			?>
			<table cellspacing="0" id="listTable" summary="Tabela de PreÃ§os das Coletas" style="width:580px;">
				<colgroup>
					<col id="codigo" />
					<col id="produto" />
					<col id="medida" />
					<col id="preco" />
				</colgroup>		
			
				<thead>
					<tr>
						<th scope="col" class="tdboderCod">C&oacute;digo</th>
						<th scope="col" class="tdboder">Produto</th>
						<th scope="col" class="tdboder">Medida</th>
						<?php for($i=0;$i<$qt;$i++){ ?>
							<th scope="col" class="tdboder"><?php echo("Pre&ccedil;o ".($i +1));?></th>
						<?php } ?>
						<th scope="col" colspan="2" class="tdboder">A&ccedil;&otilde;es</th>
					</tr>
				</thead>
				<?php
						while ($row = mysqli_fetch_array($produtos))
						{
			
							if($l_cor == '') 
								$l_cor = "par";
							else
								$l_cor = "";
				?>
				<tr class="<?php echo ($l_cor);?>">
					<td class="tdboderCod"><?php echo($row['produto_id']); ?></td>
					<td class="tdboder"><?php echo($row['produto_nome_visualizacao']); ?></td>
					<td class="tdboder"><?php echo($row['medida_descricao']); ?></td>
					<?php
								 
						$strsql = "SELECT * FROM tabela_auxiliar_precos WHERE precos_id = '".$row['precos_id']."'";			
						$precos_produto = mysqli_query($conn, $strsql) or die(mysqli_error($conn)); 
									
						if ($precos_produto && mysqli_num_rows($precos_produto)>=0){
																
							for($i=0;$i<$qt;$i++){
								
								$row1 = mysqli_fetch_array($precos_produto);
											
										
					?>
						<td class="tdboder"  width="50" align="center"><?php echo(isEmpty($row1['preco_produto'])); ?></td>
						
						<?php } ?>
						<td class="tdboderCod"><a href="javascript: " onClick="return submit_Action2('<?php echo($row['produto_id']); ?>','<?php echo($row['medida_id']); ?>','edit','','','<?php echo($_SERVER['PHP_SELF']."?coleta_id=".$coleta_id."&pid=".$pesquisa_id."&hp=".$start_rec); ?>');"><img src="images/cifrao2.jpg" border="0"></a></td>
						<td class="tdboderCod"><a href="javascript: " onClick="return submit_Action2('<?php echo($row['produto_id']); ?>','<?php echo($row['medida_id']); ?>','del','<?php echo($row['produto_nome_visualizacao']); ?>','Deseja apagar o produto ','');"><img src="images/botao_deletar.png" border="0"></a></td>
				</tr>
				
				<?php	   
					 }
					}//do while
				?>
			</table>
			<p class="legenda_tabela">
			<b>Quantidade: (<?php echo($total_rec); ?>)</b>
			</p>
			 <table align="left" style="border:0; margin-top:10px;" width="570px;">
			<tr>
			<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php if ($start_rec!=0) {?>
			<span class="pag_links"><a href="<?php echo($_SERVER['PHP_SELF']."?hp=0&coleta_id=".$coleta_id."&pid=".$pesquisa_id); ?>" >Primeira</a></span>&nbsp;&nbsp;|&nbsp;<?php }else{ ?> <span class="pag_links">Primeira</span>&nbsp;&nbsp;|&nbsp;<?php }?>
			<?php if ($back_rec>=0) {?>
			<span class="pag_links"><a href="<?php echo($_SERVER['PHP_SELF']."?hp=".($back_rec)."&coleta_id=".$coleta_id."&pid=".$pesquisa_id); ?>">Anterior</a></span>
			<?php }else{?>
			<span class="pag_links">Anterior</span>
			<?php } ?>
			</td>
			<td align="center">
			P&aacute;gina: <b><?php echo($pagina_atual); ?></b> &nbsp;&nbsp; Resultados: <b><? echo((($total_rec) ? $start_rec+1 : 0)." - ".$last_rec);?></b> de <b><?php  echo($total_rec); ?></b>
			</td>
			<td align="left">
			<?php if ($last_rec<$total_rec) {?>
			<span class="pag_links"><a href="<?php echo($_SERVER['PHP_SELF']."?hp=".($last_rec)."&coleta_id=".$coleta_id."&pid=".$pesquisa_id); ?>">Pr&oacute;xima</a></span>&nbsp;&nbsp;|&nbsp;<?php }else {?>
			<span class="pag_links">Pr&oacute;xima</span>&nbsp;&nbsp;|&nbsp;
			<?php } ?>
			<?php if ($start_rec+$records_per_page<$total_rec) {?>
			<span class="pag_links"><a href="<?php echo($_SERVER['PHP_SELF']."?hp=".($start_last_page)."&coleta_id=".$coleta_id."&pid=".$pesquisa_id); ?>">&Uacute;ltima</a></span>
			<?php }else{ ?>
				<span class="pag_links">&Uacute;ltima</span>
			<?php } ?>
			
			</td>
			</tr>
			</table>
			<br /><br /><br />
			<?php }else{ ?>
				 <h1 id="Mcaption" style="text-align:left">&nbsp;&nbsp;Sem produtos cadastrados nesta coleta</h1>
			<?php }?>	
					
			<hr />	
			<h1 id="Mcaption" style="text-align:left">Produtos N&atilde;o Cadastrados</h1>
			<form id="form_produto" name="form_produto" method="post">
				<p>
					<select name="hid" >
						<option value="0"><--------   Escolha o Produto  --------></option>
					
					<?php
						
					$strsql = "SELECT * FROM tabela_produtos A,tabela_produtos_medidas B,tabela_unidade_medidas C WHERE (B.produto_id,B.medida_id) <> ALL (SELECT produto_id,medida_id FROM tabela_precos C WHERE C.coleta_id = '".$coleta_id."') AND (A.produto_id = B.produto_id AND B.medida_id = C.medida_id) ORDER BY B.produto_id";
					$produtos = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
					
					if ($produtos && mysqli_num_rows($produtos)>0)	
						while($row = mysqli_fetch_array($produtos))
								{
					?>
						
					<option value="<?php echo($row['produto_id']."/".$row['medida_id']); ?>" <?php if($produto_id == $row['produto_id'] && $medida_id == $row['medida_id'] ){?> selected="selected" <?php } ?>  >
						 <?php echo ($row['produto_nome_visualizacao']." - ".$row['medida_simbolo']." (".$row['medida_descricao'].")");?>
					</option>
					
					<?php
					}	 	
					?>	
										
					</select>
					<input type="hidden" value="<?php echo($precos_id); ?>" name="precos_id" />
					<input type="hidden" value="<?php echo($coleta_id); ?>" name="coleta_id" />
					<input type="hidden" value="<?php echo($pesquisa_id);?>" name="pid" />
					<input type="hidden" value="adicionar_produto" name="haction"/>
					<input type="submit" value="Adicionar" />
					
					</p> 
				</form>
				<?php if($action == 'edit'){?>
				<div id="div_precos">
				<fieldset style="width:555px;">
				<legend>Cadastros de Pre&ccedil;os - <?php echo($produto_nome); ?></legend>
				<form action="" name="form_cadastro" method="post" id="form_cadastro">
				<p>
				<input style="margin: 0px 0px 0px 380px"  type="button" class="botao_adicionar" value="     Adicionar Preco" accesskey="A" onClick="adicionar_campo();" />
				<input type="hidden" id="precos_id" value="<?php echo($precos_id); ?>" name="precos_id" />
				<input type="hidden" value="<?php echo($start_rec); ?>" name="hp" />
				</p>
				<?php 
					if($cont != 0){
					for($i = 0 ; $i<$cont ;$i++) { 
				?>
				<p id="<?php echo($i+1); ?>">
				<label>Pre&ccedil;o&nbsp;: </label> <input type="text" maxlength="5" size="8" value="<?php echo($precos[$i]);?>" id="<?php echo("preco_produto".($i+1));?>" onkeypress="mascara(this,soNumeros)"/><img src="images/botao_deletar.png" style="margin:0px 0px 0px 10px" onClick="delete_caixa('<?php echo($i+1);?>');">
				</p>
				<?php }
					}
					else{?>
					<p id="1">
					<label>Pre&ccedil;o: </label> <input type="text" maxlength="5" size="8" id="preco_produto1" onkeypress="mascara(this,soNumeros)" /><img src="images/botao_deletar.png" style="margin:0px 0px 0px 10px" onClick="delete_caixa('1');" >
					</p>
					<?php }?>
					<p id="botao_salvar">
					<input type="hidden" name="hp" id="hp" value="<?php echo($start_rec); ?>" />
					<input type="button" class="botao_submit" value="Salvar" onClick="salva_precos(<?php echo $coleta_id; ?>);" />
					<input type="button" value="Cancelar" class="botao_cancelar" onclick="javascript: go_Page('<?php echo($_SERVER['PHP_SELF']."?coleta_id=".$coleta_id."&pid=".$pesquisa_id); ?>');"/>
					</p>
					 
				</form> 
				</fieldset>
				</div>
				<?php } ?>
			
		</div>
			
			
	<div class="clearer"><span></span></div>

	<div class="rodape">&nbsp </div>

</div>

<?php if($herr != ''){ ?>
			
			<script type="text/javascript" language="javascript">
			
			alert('<?php echo($herr);?>');
			
			</script>
			
			
<?php } ?> 
<form name="frm_send_data" method="post" action="">
<input type="hidden" name="haction" value="">
<input type="hidden" name="hid" value="">
<input type="hidden" name="hid1" value="">
</form>	

<script language="javascript" type="text/javascript">
	
	
	var frm_validator = new Validator("form_produto");
	frm_validator.addValidation("hid","dontselect=0","Selecione um PRODUTO!");
	frm_validator.addValidation("hid","final","");
	
//Função que adiciona um campo no formulario
function adicionar_campo()
{
	//Pega - se o formulário que queremos acrescentar o campo
	var form = document.getElementById("form_cadastro");
	var cont = 0;
	
	//Verificamos o numero de campos que já temos
    for (var i = 0; i < form.childNodes.length; i++) 
	{
    	if(form.childNodes[i].nodeName.toLowerCase() == "p")
			cont = cont + 1;
	}
	
	//Cria um parágrafo com label, caixa de texto e imagem  
	paragrafo = document.createElement("p");
	paragrafo.setAttribute("id", cont);
	
	var label = document.createElement("label");
	textNode = document.createTextNode("Preco :");
	label.appendChild(textNode);
		  
	var cx_preco = document.createElement("input");
	cx_preco.setAttribute("id","preco_produto"+cont);
	cx_preco.setAttribute("maxlength","5");
	cx_preco.setAttribute("onkeypress","mascara(this,soNumeros)");
	cx_preco.size = "8";
	
	var image = '<img src="images/botao_deletar.png" style="margin:0px 0px 0px 10px" onClick="delete_caixa('+cont+');">';
	
	paragrafo.appendChild(label);
	paragrafo.appendChild(cx_preco);
	paragrafo.innerHTML += image;
	
	//Colocamos o campo adicionado antes do botao salvar
	form.insertBefore(paragrafo,document.getElementById("botao_salvar"));
}

//Função que deleta um campo desejado
function delete_caixa(id)
{	
	var precos_id = document.getElementById("precos_id").value;
	var form = document.getElementById("form_cadastro");
	var paragrafo = document.getElementById(id+"");
	var valor = document.getElementById("preco_produto"+id).value;
	
	var url = "edita_precos.php?precos_id="+escape(precos_id)+"&preco_produto="+escape(valor)+"&action=del";
	request.open("GET",url,true);
	request.send(null);
	
	form.removeChild(paragrafo);

}

function salva_precos(coleta)
{
	var precos_id = document.getElementById("precos_id").value;
	var x=document.getElementById("form_cadastro");
	var pag = document.getElementById("hp").value;
	var aux = null;
	var msg = '';
	var flag = true;
	
	//pegamos os campos precos
	for (var i=0;i<x.length;i++)
	{
		aux = x.elements[i];
		
		if(aux.value != '')
		{
			if(aux.nodeName.toLowerCase() == "input" && aux.type == "text")
			msg += "/" + x.elements[i].value;
		}
		else
		{
			flag = false;
		
		}
		
	}
	
	//se naum tiver nenhuma campo vazio então podemos salvar todos os campos
	if(flag)
	{
		
		var url = "edita_precos.php?precos_id="+escape(precos_id)+"&preco_produto="+escape(msg) + "&action=save&coleta_id="+escape(coleta) + "&hp=" + escape(pag);
		go_Page(url);
	}
	else
	{
		alert("DELETE ou PREENCHA os campos vazios!");
	}
	
}

function updatePage()
{
	var divMain = document.getElementById("principal");
	var divPrecos = document.getElementById("div_precos");
	
	divMain.removeChild(divPrecos);
}

</script>
</body>
</html>