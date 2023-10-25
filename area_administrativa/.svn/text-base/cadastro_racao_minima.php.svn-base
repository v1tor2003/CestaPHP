<?php
  	/*$cidade_id = $_REQUEST['hid'];
	$cidade_nome = $_REQUEST['cidade_nome'];
	$action = $_REQUEST['haction'];
	$herr = '';

	if ($action=='edit'){	  
	  	$strsql = "SELECT * FROM tabela_cidades WHERE cidade_id = '".$cidade_id."'";
		$res = mysql_query($strsql) or die(mysql_error());
		$res = mysql_fetch_array($res);
		$cidade_id = $res['cidade_id'];
		$cidade_nome = $res['cidade_nome'];
	}
	
	if ($action=='save'){	  
	  
	  $strsql = "SELECT * FROM tabela_cidades WHERE cidade_nome= '".$cidade_nome."' AND cidade_id <> '".$cidade_id."'";
	  $res = mysql_query($strsql) or die(mysql_error()); 		
		
		if ($res && mysql_num_rows($res)>0)
		  $herr = "Existe outra cidade com o mesmo nome.";
		else{
		
		  $data = date('Y-m-d');
		  
		  if ($cidade_id!='')
		    $strsql = "UPDATE tabela_cidades SET cidade_nome = '".$cidade_nome."' WHERE cidade_id = '".$cidade_id."'";  		
		  else
		    $strsql = "INSERT INTO tabela_cidades (cidade_nome,cidade_data) VALUES ('".$cidade_nome."','".$data."')";
			
			
			//die($strsql);
			
			mysql_query($strsql) or die(mysql_error());	
			
			$cidade_id = '';
			$cidade_nome = '';
	    	$action = '';
		}//do else de num_rows > 0
	}//do if save
	
	if ($action=='del'){
	  $strsql = "DELETE FROM tabela_cidades WHERE cidade_id = '".$cidade_id."'";
		mysql_query($strsql) or die(mysql_error());	
		header("Location:".$_SERVER['PHP_SELF']);
		die();
	}*/
$include_head[] = <<<EOS
<script type="text/javascript" language="javascript" src="../javascript/livevalidation_standalone.js"></script>
EOS;
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
			
			<?php if($herr != ''){ ?>
			
			<script type="text/javascript" language="javascript">
			
			alert('<?php echo($herr);?>');
			
			</script>
			
			
			<?php } ?> 
			<!-- Conteúdo referente a esta página -->
					<?php 
						  	$strsql = "SELECT * FROM tabela_delimitador_racao";
							$delimitador = mysql_query($strsql) or die(mysql_error());
							
					if ($delimitador && mysql_num_rows($delimitador)>0){	
					?>
					<h1 id="Mcaption" style="text-align:left">Cadastro da Ra&ccedil;&atilde;o M&iacute;nima</h1>
					<table cellspacing="0" id="listTable" summary="Tabela de Cidades" style="width:563px;">
					<colgroup>
						<col id="codigo" />
						<col id="nome" />
						<col id="data" />
						<col id="acoes" />
					</colgroup>		
					<thead>
						<tr>
							<th scope="col" class="tdboderCod">C&oacute;digo</th>
							<th scope="col" class="tdboder">Delimitador</th>
							<th scope="col" class="tdboder">Data</th>
							<th scope="col" colspan="1" class="tdboder">A&ccedil;&otilde;es</th>
						</tr>
					</thead>
						 <?php
						 	
							while ($row = mysql_fetch_array($delimitador)){
								if($l_cor == '') $l_cor = "par"; else $l_cor = "";
						  ?>
							   <tr class="<?php echo ($l_cor);?>">
								 <td class="tdboderCod"><?php echo($row['delimitador_id']); ?></td>
								 <td class="tdboder"><?php echo($row['delimitador_descricao']); ?></td>
								 <td class="tdboder"><?php echo($row['delimitador_data_registro']); ?></td>
								 <td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($row['delimitador_id']); ?>','edit', '','','');"><img src="images/botao_editar.png" border="0"></a></td>
								 </tr>
								 <?php	   
								 }//do while
						 ?>
						 </table>	
						 <?php }else{ ?>
						 <h1 id="Mcaption" style="text-align:left">Sem cidades cadastradas</h1>
						 <?php }?>
						 	
			
	</div>
			
			
	<div class="clearer"><span></span></div>

	<div class="rodape">&nbsp </div>

</div>
	
</body>
</html>
<form name="frm_send_data" method="post" action="">
<input type="hidden" name="haction" value="">
<input type="hidden" name="hid" value="">
</form>
</body>
</html>
