<?php 
$include_head[]='<link href="boletim.css" type="text/css" rel="stylesheet"/>';
$include_head[]="<style type='text/css'>
#main{
	/*margin-left: 40px;*/
}
h2.title{
	margin-bottom:0px;
}

</style>";
include ('cabecalho.php');?>
<div id="main" class="noMainPage">
    <div class="inner" >
		<h3 class="title">Boletins</h3>
		<p> Os boletins contêm informações acerca das variações de preço dos produtos que compõem a cesta básica oficial a partir de levantamento de preço realizado em estabelecimentos comerciais das cidades baianas de Ilhéus e Itabuna. Aqui são disponibilizadas informações acerca do gasto mensal, preço médio, tempo de trabalho necessário, variação mensal, semestral, anual e do ano de cada item da cesta. Também são feitas análises conjunturais sobre os principais fatores que geram os movimentos dos preços dos produtos da cesta básica. Essa publicação é encaminhada mensalmente para os meios de comunicação impresso, televisivo e eletrônico. </p>
       <div class="clear"></div>
    </div>

    <div class="inner" style="border:none;margin-top:2.6em;;">
	<p>
	    <a href="sobreboletimaccbuesc.php" class="anostyle1">Sobre o boletim</a></br></br>
	</p>

    </div>

    <div class="clear"></div>
    <div id="inner" >
<?php
		  include_once('../libs/mysql.lib');
		  $sql_boletim = "SELECT B.boletim_id,B.boletim_nome,EXTRACT(YEAR FROM P.pesquisa_data) AS ano,M.mes_nome AS mes FROM tabela_boletim B JOIN tabela_pesquisas P ON B.boletim_id = P.pesquisa_id,tabela_mes M WHERE M.mes_id = EXTRACT(MONTH FROM P.pesquisa_data) ORDER BY P.pesquisa_data DESC";
		  $res_boletim = mysqli_query($conn, $sql_boletim);
		    
		  $class=true;
			
			$lin = array();
		  while ($res_boletim_array = mysqli_fetch_array($res_boletim))
		  {
				$ano = (int)$res_boletim_array['ano'];
				$lin[$ano] .= "<tr ><td><a type=\"aplication/pdf\" href='../boletins/" . $res_boletim_array['boletim_nome'] . "' alt='' title='Clique para visualizar'>".$res_boletim_array['mes']."</a></td></tr>";
			}
						
			foreach($lin as $ano => $tr){
			
			?>
				<div class='tablecontent'>
					<table cellspacing="0">
						<thead>
							<th ><?php echo  $ano ;?></th>
						</thead>
						<tbody>
							<?php
								echo $tr;
							?>	
						</tbody>
					</table>
				</div>
				<?php } ?>
				
		</div>
    </div>
	<?php require('rodape.php'); ?>
