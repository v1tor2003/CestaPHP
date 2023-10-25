<?php include 'cabecalho.php';?>
	    <div id="main">
	       <!-- <h2><img src="images/title_hottest_locations.gif" width="447" height="24" alt="hottest locations" /></h2> -->
		<div class="inner">
		    <a href="boletim.php" class="anostyle">
			<h3 class="blue">
			Boletins Mensais
			</h3>
			<img src="images/boletim.png" alt="stunning italian history" class="left" />
		    </a>
		    <p>São realizados diariamente os levantamentos de preço em estabelecimentos

		    comerciais das cidades de Ilhéus e Itabuna para os 12 itens que compõem

		    a cesta básica oficial. Os dados coletados são tabulados e submetidos a

		    análises, sendo divulgados sob a forma de boletins eletrônicos mensais. </p><br/><br/>
		    <a href="tabelasegraficos.php" class="anostyle">
		    <h3 class="green">Tabelas e Gr&aacute;ficos</h3>
		    <img src="images/graficos.png" alt="sea, the beaches" class="left" /></a>
		    <p>Tabelas e Gr&aacute;ficos referentes ao acompanhemento mensal da evolu&ccedil;&atilde;o dos pre&ccedil;os dos produtos que comp&otilde;em a Cesta B&aacute;sica
			fornecendo dados como gasto mensal, pre&ccedil;o m&eacute;dio e o tempo despendido por um trabalhador que recebe um sal&aacute;rio m&iacute;nimo para 
			adquirir estes bens. </p><br/><br/>

		    <a href="contato.php" class="anostyle">
			<h3 class="yellow">Contato</h3>
			<img src="images/contato.png" alt="" class="left" />
		    </a>
		    <p>
			 Entre em contato com a equipe do ACCB pelo e-mail <a href="mailto:cbuesc@gmail.com" target="_blank">cbuesc@gmail.com</a> ou diretamente com um dos envolvidos <a href="contato.php">click aqui</a>. Para enviar sugest&otilde;es e conhecer um pouco mais sobre o projeto de extens&atilde;o ACCB, metodologia do DIEESE, Gerenciador de Dados da Cesta B&aacute;sica.  </p>
		    <div class="clear"></div>
		</div>
		<div class="inner" style="border:none;">
			<h3 class="golden">O Projeto ACCB</h3>
		    <p>Um resumo sobre o projeto.</p>
			
		    <a href="http://www.uesc.br/cursos/graduacao/bacharelado/economia/" class="anostyle">
			<h3 class="silveren">Curso de Ci&ecirc;ncias Ec&ocirc;nomicas</h3>
		    </a>
		    <p>O curso de Ci&ecirc;ncias Ec&ocirc;nomicas da UESC tem as suas ra&iacute;zes assentadas na Faculdade de Ci&ecirc;ncias Econ&ocirc;micas de Itabuna - FACEI, fundada em 1966 no munic&iacute;pio de Itabuna - BA. Em 1974, a FACEI se integrou a Federa&ccedil;&atilde;o das Escolas Superiores de Ilh&eacute;us e Itabuna - FESPI, a qual, posteriormente, foi transformada na Universidade Estadual de Santa Cruz - UESC.</p>
		    <p class="readmore"><a href="http://www.uesc.br/cursos/graduacao/bacharelado/economia/" target="_blank"><img src="images/readmore.gif" width="68" height="15" alt="readmore" /></a></p>
		    <br/><br/>
		    <a href="equipe.php" style="color:inherit;text-decoration: none;">
			<h3 class="equipe">Redes Sociais</h3>
		    </a>
		    
            <p>
            <a href="https://twitter.com/cbuesc"><img class="social_contact_img" src="images/twitter_icon.png">@cbuesc</a><br/>
            </p>
            <p>
            <a href="https://www.facebook.com/cbuesc"><img class="social_contact_img" src="images/fb_icon.png">@cbuesc</a><br/>
            </p>

			<!-- <ul>
                            <?php
                                $SQL_Equipe = "SELECT nome_completo, funcao FROM tabela_equipe eq, tabela_equipe_funcoes ef WHERE eq.funcao_id = ef.id AND eq.mostrar_home=TRUE ORDER BY nome_completo";
                                $Equipe_res = mysql_query($SQL_Equipe);
                                while($Equipe_row = mysql_fetch_assoc($Equipe_res)){
                                    echo "<li>".$Equipe_row['nome_completo']." - ".$Equipe_row['funcao']."</li>";
                                }
                            ?>
                            
			    <li>Prof. M&ocirc;nica de Moura Pires - Coordenadora</li>
			    <li>Prof. Gustavo Joaquim Lisboa - Colaborador </li>
			    <li>Prof. Marcelo In&aacute;cio Ferreira Ferraz - Colaborador</li>
			    <li>Prof. Dany Sanchez Dominguez - Coordenador TI</li>
			    <li>Kati&uacute;cia Mavin Oliveira Costa  - Estagi&aacute;ria ACCB</li>
			    <li>Lucas Martins Silva - Estagi&aacute;rio ACCB</li>
                            <li>Tainar Silva D&oacute;ria - Estagi&aacute;rio ACCB</li>
			    <li>Adriano Medeiros dos Santos - Desenvolvedor</li>
			    <li>Vagner Luz do Carmo - Desenvolvedor</li>
                            
			</ul></p> --><br/><br/>

			<div class="box_selecao">
				<a href="../doc/convocacao.doc"><p>SELEÇÃO PARA BOLSISTA CLIQUE AQUI</p></a>

			</div>

		    <div id="special">
			<a href="http://www.uesc.br" target="_blank" ><img src="images/logo_uesc_100.jpg" width="100px"  height="100px" alt="special offer" /></a>
			<a href="http://www.cnpq.br" target="_blank" ><img src="images/cnpq.jpg" width="200px" height="90px" alt="special offer" /></a>
		    </div><!-- end special -->
		</div><!-- end packages -->
		<div class="clear"></div>

	    </div><!-- end main -->
<?php require("rodape.php"); ?>
