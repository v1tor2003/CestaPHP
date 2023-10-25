<?php
/*******************************************************************************
 * ** ** ** ** ** ** ** ** ** ** ** ** TABELAS  ** ** ** ** ** ** ** ** ** ** **
 *******************************************************************************/
?>
<?php 


$totalProdutos = count($produtos);


if($f['tipoconsulta'] == 'cesta_custo_total'){
	
	foreach($info as $c_nome => $p){
		?>
		<h3 class="nomeCidadeNaTabela">Cidade: <?php echo $c_nome ?></h3>
		<table  cellspacing="0" >
			<thead>
				<tr>
					<th class='periodo'>Per&iacute;odo</th>
					<th class='produtos'>Custo Total da Cesta Básica</th>
				</tr>
				
			</thead>
			<tbody>
				<?php
				foreach($p as $periodo => $produto){
					echo "<tr><td>".formata_data2tabela($periodo)."</td>";
					foreach($produto as $p_nome => $vlr){
						echo "<td class='td_valor'>".formata_numero($p_nome,2)."</td>";
					}
					echo "</tr>\n";
				}
				?>
			</tbody>
		</table>

		<p id="endtable">Fonte: Departamento de Ci&ecirc;ncias Econ&ocirc;micas da UESC - DCEC.
		</p>
	<?php
	}
}
/*
 * Percorre o vetor de informação assciondo a chave(nome da cidade) á $c_nome
 * e o valor (vetor de periodos) à $p
 */
else{

	foreach($info as $c_nome => $p){
		?>
		<h3 class="nomeCidadeNaTabela">Cidade: <?php echo $c_nome ?></h3>
		<table  cellspacing="0" >
			<thead>
				<tr>
					<th rowspan='2' class='periodo'>Per&iacute;odo</th>
					<th colspan='<?php echo $totalProdutos?>' class='produtos'>Produtos</th>
				</tr>
				<tr class='tr_produtos'>
					<?php 
					for($i=0;$i<$totalProdutos;$i++){
						echo "<th>{$produtos[$i]}</th>";
					}
					?>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($p as $periodo => $produto){
					echo "<tr><td>".formata_data2tabela($periodo)."</td>";
					foreach($produto as $p_nome => $vlr){
						echo "<td class='td_valor'>".(($f['tipoconsulta']=='produto_tempo_trabalho')?  converte_horas($vlr):/*$salario_simbolo.' '.*/formata_numero($vlr,2))."</td>";
					}
					echo "</tr>\n";
				}
				?>
			</tbody>
		</table>

		<p id="endtable">Fonte: Departamento de Ci&ecirc;ncias Econ&ocirc;micas da UESC - DCEC.
		</p>
		<?php 
	}
}
?>
