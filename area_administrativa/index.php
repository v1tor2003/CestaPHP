<?php 
$include_head = array ();
require("cabecalho.php"); ?>
<body>
 
		
		<div class="caixa_principal">

			<?php require("topo.php"); ?>
			
			<div class="menu_superior">
			<?php require("menu_superior.php"); ?>
			</div>
			
			<div class="menu_lateral">
			<?php 
			
			$menu = $_REQUEST['menu'];
			
			switch($menu)
			{
				case 1:	require("menu_lateral_cadastros.php"); 
						break;
				case 3: require("menu_lateral_consultas.php");
						break;
				case 4: require("menu_lateral_boletim.php");
                                    default: ?><div style="height:300px"></div>
						
						<?php break;
			}
			
			?>
			</div>
			
			
			<div class="clearer"><span></span></div>

			<div class="rodape">Copyright &copy;2009 - <?php echo(date('Y')); ?>&nbsp;
    Departamento de Ci&ecirc;ncias Econ&ocirc;micas da UESC - DCEC. Todos os direitos reservados. </div>

		</div>
	
</body>
</html>