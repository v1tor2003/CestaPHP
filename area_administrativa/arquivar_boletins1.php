<?php
include_once("mysql.lib");
	$action = $_REQUEST['haction'];
	$mes = $_REQUEST['mes'];
	$ano = $_REQUEST['ano'];
	$pesquisa_id = $_REQUEST['hid'];
	$dir_raiz = getcwd();
	chdir('../boletins') or die('Erro , diretorio nao encontrado "../boletins"');
	$dir_boletim = getcwd().'/';	
	if($action == 'upload')
	{
	echo("2".$action."<br>2.1".$dir_boletim."<br>");
		
		$strsql = "SELECT pesquisa_id FROM tabela_pesquisas WHERE EXTRACT(MONTH FROM pesquisa_data) = '".$mes."' AND EXTRACT(YEAR FROM pesquisa_data) = '".$ano."' ";
	
		
		$res = mysql_query($strsql) or die(mysql_error());
		
		if(mysql_num_rows($res)==0)
		{
			$herr = 'Não existe uma pesquisa para este mês!';
		}
		else
		{
				
			$row = mysql_fetch_array($res);
			$pesquisa_id = $row['pesquisa_id'];	
			
			$strsql = "SELECT * FROM tabela_boletim WHERE boletim_id = '".$pesquisa_id."'";
			$res = mysql_query($strsql) or die(mysql_error());
			$qtd = mysql_num_rows($res);
				
			if($qtd >0)
			{
				$herr = 'Um boletim já esta cadastrado para este mês!';
			}
			else
			{
				
				
				
				if($_FILES['userfile']['error'] > 0)
				{
					$herr =  'Erro:';
					
					switch($_FILES['userfile']['error'])
					{
						case 1: $herr .= 'Arquivo excedeu tamanho máximo!'; 
								break;
						case 2: $herr .= 'Arquivo excedeu tamanho máximo!';
								break;
						case 3: $herr .= 'Arquivo parcialmente carregado!';
								break;
						case 4: $herr .= 'Arquivo não foi carregado!';
								break;
					}
					
					exit;
				}
				
				if($_FILES["userfile"]["type"] == "application/pdf" || preg_match('/(\.pdf)$/',$_FILES['userfile']['name']))
				{
				
			
					$upfile = $dir_boletim.$_FILES['userfile']['name'];
					echo("3".$upfile."<br>");
					if(is_uploaded_file($_FILES['userfile']['tmp_name']))
					{
						if(!move_uploaded_file($_FILES['userfile']['tmp_name'],$upfile))
						{
							phpinfo();
						    die("4"."<br>");
							$herr ='Problem: Could not move file to destination directory';
						}
					}
					else
					{
						$herr = 'Problem: Possible file upload attack.';
					}
				}
				else
					$herr = "Arquivo não é do formato PDF";
				}
				
				if(file_exists($upfile))
				{
					
					$strsql = "INSERT INTO tabela_boletim(boletim_id,boletim_nome) VALUES ('".$pesquisa_id."','".$_FILES['userfile']['name']."')";
					mysql_query($strsql) or die(mysql_error());
				}	
			}
			
			$action = '';
	}
	else
		if($action == 'del')
		{
			$strsql = "SELECT * FROM `cesta_basica`.`tabela_boletim` WHERE boletim_id='".$pesquisa_id."'";
			$sql =  mysql_query($strsql);
			//----------------------------------------------------------------------------------------------
			//by vluzrmos..., 
			if($sql)
				$res =  mysql_fetch_assoc($sql);
			
			$file = $res['boletim_nome'];
		    $path = $dir_boletim.$file;
			//muda o '\' por '/', caso o nome seja exibido na tela...
			$tam = strlen($path);
			for($i=0;$i<$tam;$i++)
			{
			  if(substr($path,$i,1)=="\\") $path = substr_replace($path,"/",$i,1);
			}
			
			$rmFile = @unlink($path); //'@' ignora o warning que seria exibido na tela
			if (!$rmFile)
			{
				$herr = "Não foi possível deletar o arquivo \"$path\" !";
			}
			//end of... by vluzrmos....
			//---------------------------------------------------------------------------------------------
			else
			{
				$strsql = "DELETE FROM tabela_boletim WHERE boletim_id = '".$pesquisa_id."'";
				$res = mysql_query($strsql) or die(mysql_error());
			}
		
		}
	
	
	  $records_per_page = 5;		
	  $start_rec = ($_REQUEST['hp']!='') ? $_REQUEST['hp'] : 0;
	  $strsql = "SELECT * FROM tabela_boletim";   
	  $produtos = mysql_query($strsql) or die(mysql_error());
	  $total_rec = mysql_num_rows($produtos);
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
			<?php  require("menu_lateral_boletim.php"); ?>
			</div>
			
			<div class="conteudo_pagina">
			
			<?php 
						  	$strsql = "SELECT B.boletim_id,B.boletim_nome,EXTRACT(YEAR FROM P.pesquisa_data) AS ano,M.mes_nome AS mes FROM tabela_boletim B JOIN tabela_pesquisas P ON B.boletim_id = P.pesquisa_id,tabela_mes M WHERE M.mes_id = EXTRACT(MONTH FROM P.pesquisa_data) ORDER BY P.pesquisa_data DESC LIMIT ".$start_rec.",".$records_per_page;
							$boletim = mysql_query($strsql) or die(mysql_error());
							
					if ($boletim && mysql_num_rows($boletim)>0){	
					?>
					<h1 id="Mcaption" style="text-align:left">Cadastro de Boletim</h1>
					<table cellspacing="0" id="listTable" summary="Tabela de Boletim" style="width:537px;">
					<colgroup>
						<col id="codigo" />
						<col id="nome" />
						<col id="cidade" />
						<col id="acoes" />
					</colgroup>		
					<thead>
						<tr>
							<th scope="col" class="tdboderCod">C&oacute;digo</th>
							<th scope="col" class="tdboder">M&ecirc;s</th>
							<th scope="col" class="tdboder">Ano</th>
							<th scope="col" class="tdboder">Nome</th>
							<th scope="col" class="tdboderCod">A&ccedil;&otilde;es</th>
						</tr>
					</thead>
						 <?php
						 	
							while ($row = mysql_fetch_array($boletim)){
								if($l_cor == '') $l_cor = "par"; else $l_cor = "";
						  ?>
							   <tr class="<?php echo ($l_cor);?>">
								 <td class="tdboderCod"><?php echo($row['boletim_id']); ?></td>
								 <td class="tdboder"><?php echo($row['mes']); ?></td>
								 <td class="tdboder"><?php echo($row['ano']); ?></td>
								  <td class="tdboder"><?php echo($row['boletim_nome']); ?></td>
								 <td class="tdboderCod"><!--Acoes-->
								     <a href="<?echo('../boletins/'.$row['boletim_nome']); ?>" target="_blank" >
									 <img alt="Pr&eacute;-visualizar arquivo" title="Pr&eacute;-visualizar arquivo"src="images/b_view.png" border="0" />
								     </a>
								     <a href="javascript: " onClick="return submit_Action('<?php echo($row['boletim_id']); ?>','del','<?php echo($row['boletim_nome']); ?>','Deseja apagar o boletim ','');">
									 <img alt="Excluir arquivo" title="Excluir arquivo" src="images/botao_deletar.png" border="0" />
								     </a>
								     </td>
								 </tr>
								 <?php	   
								 }//do while
						 ?>
						 </table>
						 <table cellpadding="0" cellspacing="" style="border:none">
						<tr>
						<td>
						   <table align="left" style="border:0; margin-top:10px;" width="520px;">
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
			
			</td>
			</tr>
			<?php }else{ ?>
			<h1 id="Mcaption" style="text-align:left">Sem Boletins cadastradas</h1>
			<?php }?>
			<tr>
			<td>
			<fieldset>
			
			<legend>Adicionar Boletim</legend>
						
			<form enctype="multipart/form-data" name="form_cadastro" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>" style="width:505px;">
			<p>
					
					<label for="mes">M&ecirc;s:</label>
					<select name="mes" >
				
						<option value="0"><--------   Escolha o M&ecirc;s  --------></option>
					
						<?php
							
							$strsql = "SELECT * FROM tabela_mes";
							$mes = mysql_query($strsql) or die(mysql_error());
					
							if ($mes && mysql_num_rows($mes)>0)	
								while($row = mysql_fetch_array($mes))
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
						
							$ano = date(Y);
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
				<label for="nome">Arquivo:</label>
				<input type="hidden" name="haction" value="upload" />
				<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
				<input type="file" maxlength="100" name="userfile" size="40" />
				<span class="obrig">*</span>
			</p>
			<p>
				<input type="submit" class="botao_submit" value="Enviar Arquivo" size="40" />
			</p>
			<p class="obrig" align="right" style="color:#FF0000";margin-left:10px;>
				* Campos obrigat&oacute;rios&nbsp;&nbsp;&nbsp;
			</p>
						
			</form> 
		</fieldset>	
		</td>
		</tr>
		</table>		
			
		
			</div>
			
			
			<div class="clearer"><span></span></div>

			<div class="rodape">&nbsp </div>

</div>
			
			<?php if($herr != ''){ ?>
			
			<script type="text/javascript" language="javascript">
			
			alert('<?php echo($herr);?>');
			
			</script>
			
			
<?php } ?> 
	
<form name="frm_send_data" method="post" action=""/>
<input type="hidden" name="hp" value="<?php echo($start_rec);?>" />
<input type="hidden" name="haction" value=""/>
<input type="hidden" name="hid" value=""/>
</form>
</body>
</html>
<script type="text/javascript" language="javascript">
			
	var frm_validator = new Validator("form_cadastro");
	frm_validator.addValidation("mes","dontselect=0","O campo MÊS não foi selecionado!");
	frm_validator.addValidation("ano","dontselect=0","O campo ANO não foi selecionado!");
	frm_validator.addValidation("ano","final","Corrija todo(s) o(s) erro(s)!!!");
			
</script>
