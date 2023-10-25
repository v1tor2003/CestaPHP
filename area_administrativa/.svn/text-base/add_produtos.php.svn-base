<?php

$include_head[] = <<<EOS
<script type="text/javascript" language="javascript" src="../javascript/functions_select.js" ></script>
EOS;
require("cabecalho.php");

?>
<body>
	<div class="caixa_principal">

	<form>
	<table border="0" style="borde:none;">
	<tr>
		<td>
			<select name="list1" multiple size="10" style="width:150px;" >
					
					<?php
						
					$strsql = "SELECT * FROM tabela_produtos A,tabela_produtos_medidas B,tabela_unidade_medidas C WHERE (B.produto_id,B.medida_id) <> ALL (SELECT produto_id,medida_id FROM tabela_precos C WHERE C.coleta_id = '".$coleta_id."') AND (A.produto_id = B.produto_id AND B.medida_id = C.medida_id) ORDER BY B.produto_id";
					$produtos = mysql_query($strsql) or die(mysql_error());
					
					if ($produtos && mysql_num_rows($produtos)>0)	
						while($row = mysql_fetch_array($produtos))
								{
					?>
						
					<option value="<?php echo($row['produto_id']."/".$row['medida_id']); ?>" >
						 <?php echo ($row['produto_nome_visualizacao']." - ".$row['medida_simbolo']);?>
					</option>
					
					<?php
					}	 	
					?>	
										
					</select>
	</TD>
	<td valign=middle align=center>
		<input type="button" name="right" value="&gt;&gt;" onClick="moveSelectedOptions(this.form['list1'],this.form['list2'],true,this.form['movepattern1'].value)"><BR><br />
		<input TYPE="button" NAME="right" VALUE="&gt;&gt;" onClick="moveAllOptions(this.form['list1'],this.form['list2'],true,this.form['movepattern1'].value)"><BR><BR>
		<input TYPE="button" NAME="left" VALUE="&lt;&lt;" onClick="moveSelectedOptions(this.form['list2'],this.form['list1'],true,this['form'].movepattern1.value)"><BR><br />
		<input type="button" name="left" value="&lt;&lt;" onClick="moveAllOptions(this.form['list2'],this.form['list1'],true,this.form['movepattern1'].value)">
	</td>
	<td>
	<select name="list2" multiple size=10 onDblClick="moveSelectedOptions(this.form['list2'],this.form['list1'],true,this.form['movepattern1'].value)" style="width:150px;">
	</select>
	</td>
	<input type="hidden" name="movepattern1" value="">
</tr>
</table>
</form>
	<div class="clearer"><span></span></div>
	
	</div>
</body>