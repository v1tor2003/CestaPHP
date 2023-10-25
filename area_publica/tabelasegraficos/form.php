<form action="<?php echo $thisFile;?>#result" name="tabelasegraficos" id="tabelaegrafico" method="POST">
	<p >
	    Escolha o tipo de exibi&ccedil;&atilde;o<em>*</em>:<br/>
	    <label><input name="tipoexibicao" id="tipoexibicao_tabela" type="radio" value="tabela" <?php if($form['tipoexibicao']=='tabela' || !isset($form['tipoexibicao'])) echo 'checked="checked"' ?>/>Tabela</label>
	    <label><input name="tipoexibicao" id="tipoexibicao_grafico"  type="radio" value="grafico" <?php if($form['tipoexibicao']=='grafico') echo 'checked="checked"' ?> />Gr&aacute;fico</label><br/>
	</p>
	<p >
	    Per&iacute;odo<em>*</em>:
	   
	    De <input name="p_inicio" id="p_inicio" type="text" size="5" maxlength="7" value="<?php if(isset($form['p_inicio'])) echo $form['p_inicio']; else echo $mcalc."/".$acalc; ?>"/> a <input type="text" size="5" maxlength="7" name="p_final" id="p_final" value="<?php if(isset($form['p_final'])) echo $form['p_final']; else  echo ($dataAtual->format("@Mes/@Ano"));?>"/>
	</p>
	<p>
	    Resultados para cada: <select name="periodoMeses" >
	    <option  value="1" <?php if($f["periodoMeses"]=="1"  or !$f["peridoMeses"] or !$f) echo 'selected="selected"';?>>1 mês</option>
	    <option  value="2" <?php if($f["periodoMeses"]=="2") echo 'selected="selected"';?>>2 meses</option>
	    <option  value="3" <?php if($f["periodoMeses"]=="3") echo 'selected="selected"';?>>3 meses</option>
	    <option  value="4" <?php if($f["periodoMeses"]=="4") echo 'selected="selected"';?>>4 meses</option>
	    <option  value="5" <?php if($f["periodoMeses"]=="5") echo 'selected="selected"';?>>5 meses</option>
	    <option value="6" <?php if($f["periodoMeses"]=="6") echo 'selected="selected"';?>>6 meses</option>
	    </select>
	</p>
	<p class="floatLeft">
	    Tipo da consulta<em>*</em>: <br/>
	    <label><input name="tipoconsulta" id="tipoconsulta" type="radio" value="produto_preco_total" <?php if($form['tipoconsulta']=='produto_preco_total'|| !isset($form['tipoconsulta'])) echo 'checked="checked"'?> onchange="enableOption()"/>Gasto Mensal</label><br/>
	    <label><input name="tipoconsulta" id="tipoconsulta" type="radio" value="produto_preco_medio" <?php if($form['tipoconsulta']=='produto_preco_medio') echo 'checked="checked"'?> onchange="enableOption()"/>Pre&ccedil;o M&eacute;dio</label><br/>
	    <label><input name="tipoconsulta" id="tipoconsulta" type="radio" value="produto_tempo_trabalho" <?php if($form['tipoconsulta']=='produto_tempo_trabalho') echo 'checked="checked"'?> onchange="enableOption()"/>Tempo de Trabalho</label><br/>
		<label><input name="tipoconsulta" id="tipoconsulta" type="radio" value="cesta_custo_total" <?php if($form['tipoconsulta']=='cesta_custo_total') echo 'checked="checked"'?> onchange="disableOption()"/>Custo Total da Cesta</label><br/>
		
	</p>
	<p class="floatLeft">
	    Cidade(s)<em>*<sup> 1</sup></em>:<br/>
	    <?php
	    /*
	     * Seleciona as cidades de pesquisa no banco de dados
	     * e gera o campo <select> que os valores são os id's(BD) das cidades
	     */
	    $strsql = "SELECT * FROM tabela_cidades tc ORDER BY tc.cidade_nome ASC";
	    $cidades = mysqli_query($conn,$strsql) or die(mysqli_error($conn));
	    $numCidades = mysqli_num_rows($cidades);
	    ?>

	    <select name="cidades[]" id="cidades" size="<?php echo $numCidades;?>" multiple="multiple">
	    <?php

	    if ($cidades && $numCidades>0){
		$row = mysqli_fetch_array($cidades);
		if($row)
		{?>
		<option value="<?php echo($row['cidade_id']); ?>" <?php if(in_array($row['cidade_id'],is_array($form['cidades'])?$form['cidades']:array())|| empty ($form)) echo 'selected="selected"'?>> <?php echo ($row['cidade_nome']);?></option>
		<?php }
		while($row = mysqli_fetch_array($cidades))
		{?>
		    <option value="<?php echo($row['cidade_id']); ?>" <?php if(in_array($row['cidade_id'],is_array($form['cidades'])?$form['cidades']:array())) echo 'selected="selected"'?>  > <?php echo ($row['cidade_nome']);?></option>
	    <?php	}

	    }
	    ?>

	    </select>
	</p>
	<p class="floatLeft">
	    Produto(s)<em>*<sup> 1</sup></em>:<br/>
	    <?php
		/*
		 * Seleciona os produtos do banco de dados
		 * gera o campo <select/> com os id's(BD) dos produtos
		 */
		    $strsql = "SELECT * FROM tabela_produtos tp WHERE produto_cesta = '1' ORDER BY tp.produto_nome ASC";
		    $res = mysqli_query($conn,$strsql) or die(mysqli_error($conn));
		    $numProdutos = mysqli_num_rows($res);
             ?>
	    <select name="produtos[]" id="produtos" size="<?php echo $numProdutos;?>" multiple="multiple" >
		<?php

		    if($res && $numProdutos > 0){
			$row = mysqli_fetch_array($res);
			if($row){?>
		<option value="<?php echo($row['produto_id']); ?>" <?php if(in_array($row['produto_id'],is_array($form['produtos'])?$form['produtos']:array())|| empty ($form)) echo 'selected="selected"'?>><?php echo($row['produto_nome_visualizacao']);?></option>
			    <?php }
			while($row = mysqli_fetch_array($res))
			{?>
			    <option value="<?php echo($row['produto_id']); ?>"  <?php if(in_array($row['produto_id'],is_array($form['produtos'])?$form['produtos']:array())) echo 'selected="selected"'?>><?php echo($row['produto_nome_visualizacao']);?></option>
			<?php	}

		    }?>
	    </select>
	</p>
	<p class="clear"></p>
	<p class="legenda">
	    <em>*</em> &mdash; Campos obrigat&oacute;rios<br/>
	    <em>1</em> &mdash; Para selecionar mais de um elemento pressione ctrl+click , shift+click ou click e arraste sobre os elementos.
	</p>
	<p class="clear">
	    <input type="submit" value="Gerar"/>
	    <input type="button" value="Limpar campos" onclick='formSetOptions();'/>
	</p>
	<input type='hidden' name="noexibe" value="false"/>
	<input type='hidden' name="chartType" value="column"/>
</form>
<script type="text/javascript">
	var form_defaults = <?php try{ echo json_encode($f_default_values); } catch(Exception $e){ echo "{}"; }?>;
	function formSetOptions(){
		var form = document.tabelasegraficos;
		for(var prop in form_defaults){
			if(form_defaults[prop].value instanceof Array){
				for(i=0;i < form[prop].length; i++){
					form[prop][i].selected=false;
					form[prop][i].checked=false;
				}
				for(var i in form_defaults[prop].value){
					var f_ivalue=form_defaults[prop].value[i];
					form[prop][f_ivalue].selected=true;
					form[prop][f_ivalue].checked=true;
				}
			}
			else{
				form[prop].value = form_defaults[prop].value;
			}
		}
	}

	function disableOption(){
		// alert(document.getElementById("produtos").options.length);
		for (var i=0; document.getElementById("produtos").options.length>i;i++){
			document.getElementById("produtos").options[i].selected = true;

		}
		
	}

	function enableOption(){
		// alert(document.getElementById("produtos").options.length);
		for (var i=0; document.getElementById("produtos").options.length>i;i++){
			document.getElementById("produtos").options[i].disabled = false;
		}
		
	}


</script>
<div class="clear"></div>
	