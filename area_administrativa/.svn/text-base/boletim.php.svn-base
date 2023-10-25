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
			<?php require("menu_lateral_boletim.php"); ?>
			</div>
			
			
			<div class="conteudo_pagina">
			<table cellpadding="0" style="border:none;">
			<tr><td>
			<h1 id="Mcaption" style="text-align:left">Pesquisas Realizadas</h1>
			</td></tr>
			<tr><td>
			<form id="form_filtro" name="form_filtro">
	<fieldset style="width:560px; margin-top:0px; padding-right: 0px;">
		<legend>Filtro</legend>
		<table style="border: 0px;"cellspacing="0" cellpadding="0" id="listTable" summary="Tabela de filtro" >
			<tr>
				<td width="" align="right"><span class="legend_filtro">Campo:</span></td>
				<td width="" align="right"><span class="legend_filtro">Operador:</span></td>
			  <td width="" align="right"><span class="legend_filtro">Texto(valor):</span></td>
				<td width="" align="right"><span class="legend_filtro">Ordenar por:</span></td>
				<td width="" align="left">
				<input class="submit_filtro" type="button"  value="Aplicar" style="width:65px;" onClick="filtra_tabela('filtrar')" />
				<input type="hidden" id="status_filtro" value="0" />
				<input type="hidden" name="haction" value="filtrar" />
				</td>
			</tr>
			<tr>
				<td align="right">
					<select name="campo_filtrado" id="campo_filtrado" size="1" onchange="altera_valor()" style="width:70px;">
						<option value="pesquisa_id">C&oacute;digo</option>
						<option value="mes">M&ecirc;s</option>
						<option value="ano">Ano</option>
					</select>				
				</td>
				<td align="right">
					<select name="operador_filtro" style="width:100px;" size="1">
            <option value="=">= (igual a)</option>
	          <option value="!=">!= (diferente de)</option>
	          <option value="<">&lt; (menor que)</option>
	          <option value=">">&gt; (maior que)</option>
	          <option value="<=">&le; (menor e igual)</option>
	          <option value=">=">&ge; (maior e igual)</option>
			</select>				
				</td>
				<td align="right" id="valor_filtro" style="width:120px;">
					<input type="text" name="valor_filtro" id="filtro_value" maxlength="15" size="10" value=""/>
				</td>
				<td align="right">
		      <select name="ordenar_por" style="width:120px">	
						<option value="pesquisa_id/asc">C&oacute;digo (Cresc.)</option>
						<option value="pesquisa_id/desc">C&oacute;digo (Decres.)</option>
						<option value="ano/asc">Ano (Cresc.)</option>
						<option value="ano/desc">Ano (Decres.)</option>
						<option value="mes/asc">M&ecirc;s (Cresc.)</option>	  	
						<option value="mes/desc">M&ecirc;s (Decres. )</option>	  	
			  </select>		
					</td>
					<td><input class="submit_filtro" type="button" style="width:65px;" value="Remover" onClick="filtra_tabela('remover')"/></td>
			</tr>
		</table>
	</fieldset>
</form>
			</td></tr>
			<tr><td>
			<div id="tabela">
				<?php require('lista_pesquisas_consultas.php'); ?>
			 </div>
			 </td>
			 </tr>
			 </table>	
			
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
<script language="javascript" type="text/javascript">
		
	function atualiza_tabela(pag)
	{
	
		var url = '';
		var status_filtro = document.getElementById("status_filtro").value;
				
		url = 'lista_pesquisas_consultas.php?hp='+escape(pag);	
		
		if(status_filtro == 1)
		{
			var params = $('form_filtro').serialize();
			url += '&' + params;
		}

		new Ajax.Updater('tabela',url, {method: 'post',asynchronous:true});
	}
	
	function filtra_tabela(action)
	{
		var url = 'lista_pesquisas_consultas.php';
		
		if(action == 'filtrar')
		{
			var params = $('form_filtro').serialize();
			document.getElementById('status_filtro').value = '1';
			url += '?' + params+'&haction='+action;
			
		}
		else
		{
			var valor_filtro = document.getElementById("valor_filtro");
			valor_filtro.innerHTML = '';
			document.getElementById('status_filtro').value = '0';
			document.form_filtro.campo_filtrado.value = "pesquisa_id";
			document.form_filtro.operador_filtro.value = "=";
			document.form_filtro.ordenar_por.value = "pesquisa_id/asc";
			
			
			
			var element = document.createElement("input");
			element.setAttribute("name","valor_filtro");
			element.setAttribute("size","10");
			//element.setAttribute("OnKeyPress","return is_num(event);");
			valor_filtro.appendChild(element);
			
		}
		
		new Ajax.Updater('tabela',url, {method: 'post',asynchronous:true});
	}
	
	function altera_valor()
	{
		var campo_filtrado = document.getElementById("campo_filtrado").value;
		var valor_filtro = document.getElementById("valor_filtro");
		var ano;
		
		valor_filtro.innerHTML = '';
		if(campo_filtrado != 'pesquisa_id')
		{
			var select_input = document.createElement("SELECT");
			select_input.setAttribute("name","valor_filtro");
			
			if(campo_filtrado == 'ano')
			{
				
				ano = (new Date()).getFullYear();
				
				for(var i = 0 ; (ano != 1998) ; ano--,i++)
					select_input.options[i] = new Option(ano, ano);
				
			}
			else
				if(campo_filtrado == 'mes')
				{
				
					var mes_nome = new Array("Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
					
					for(var i=0; i< 12;i++)
						select_input.options[i] = new Option(mes_nome[i],i+1);
					
					
				}
			
			valor_filtro.appendChild(select_input);
			
		}
		else
		{
			var element = document.createElement("input");
			element.setAttribute("name","valor_filtro");
			element.setAttribute("size","12");
			element.setAttribute("OnKeyPress","return is_num(event);");
			valor_filtro.appendChild(element);
		}
		
	
	}
	
</script>