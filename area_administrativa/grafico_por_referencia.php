<?php
require("cabecalho.php");
?>
<body>
 
		
		<div class="caixa_principal">

			<?php require("topo.php"); ?>
			
			<div class="menu_superior">
			<?php require("menu_superior.php"); ?>
			</div>
			
			<div class="menu_lateral">
			<?php  require("menu_lateral_consultas.php"); ?>
			</div>
			<div class="conteudo_pagina">
			<fieldset style="width:625px;">
			<legend>Consultas Por Data Refer&ecirc;ncia</legend>
			<form action="" method="post" name="form_consultas" >
			<!-- Inicio do lado esquerdo -->
			<div class="conteudo_pagina_esquerda">
			<p>
			<label>Data Refer&ecirc;ncia</label>
			<input type="text" name="data_inicial" value="mmaaaa" onKeyPress="mascara(this,data)" size="7" maxlength="7" /><span class="obrig">*</span>
			</p>
			<p>
				<label for="cidade">Cidade</label>
				<select name="cidade_id" multiple="multiple" size="4" style="width:140px;">
					<?php
							
						$strsql = "SELECT * FROM tabela_cidades";
						$cidades = mysql_query($strsql) or die(mysql_error());
					
						if ($cidades && mysql_num_rows($cidades)>0)	
							while($row = mysql_fetch_array($cidades))
							{
					?>
						
					<option value="<?php echo($row['cidade_id']); ?>" <?php if($cidade_id == $row['cidade_id']){?>selected="selected" <?php } ?>  > <?php echo ($row['cidade_nome']);?></option>
					
						<?php
						}	 	
						?>	
										
					</select>
					<span class="obrig">*</span>
				</p>
			<p align="center">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" onClick="visualizacao_grafica();" value="Consultar" />
			<input type="reset" value="Limpar" />
			</p>
			</div>
			<!-- Fim do lado esquerdo -->
			<!-- Inicio do lado direito-->
			<div class="conteudo_principal_direita">
			<p>
			<label>Produtos</label>
			<select name="produtos" id="produtos" multiple="multiple" size="12" style="width:180px;">
			<?php
			
				$strsql = "SELECT * FROM tabela_produtos WHERE produto_cesta = '1'";
				$res = mysql_query($strsql) or die(mysql_error());
				
				if($res || mysql_num_rows($res))
				while($row = mysql_fetch_array($res))
				{
			?>
				<option value="<?php echo($row['produto_id']); ?>"><?php echo($row['produto_nome']);?></option>
			<?php
				
				} 
			?>
			
			</select>
			<span class="obrig">*</span>
			</p>
			</div>
			</form>
			</fieldset>
			</div>
			
			
			<div class="clearer"><span></span></div>

			<div class="rodape">&nbsp </div>

</div>
	
</body>
</html>