<?php include 'cabecalho.php';?>
	    <div id="main" >
		<div class="inner" style="border:none;width:100%">
		<h3 class="title">Equipe e Contatos</h3>

                <p>
                    E-mail da equipe: <a href="mailto:cbuesc@gmail.com">cbuesc@gmail.com</a>.
                </p>
                <p>
                    E-mails individuais:
                </p>
                <ul id="contatos">
                <?php
                                $SQL_Equipe = "SELECT nome_completo, funcao, email FROM tabela_equipe eq, tabela_equipe_funcoes ef WHERE eq.funcao_id = ef.id AND eq.mostrar_contatos=TRUE ORDER BY nome_completo";
                                $Equipe_res = mysql_query($SQL_Equipe);
                                while($Equipe_row = mysql_fetch_assoc($Equipe_res)){
                                    echo '<li><span class="nivelcontato">'.$Equipe_row['funcao'].'</span> - '.$Equipe_row['nome_completo']." - Email: <a class='email' href='mailto:".$Equipe_row['email']."'>".$Equipe_row['email']. "</a></li>";
                                }
                ?>
                
                    <!--
		    <li><span class="nivelcontato">Coordenadora</span> - Prof. M&ocirc;nica de Moura Pires -  Email: <a class="email" href="mailto:mpires@uesc.br">mpires@uesc.br</a></li>
		    <li><span class="nivelcontato">Colaborador</span> - Prof. Gustavo Joaquim Lisboa </li>
		    <li><span class="nivelcontato">Colaborador</span> - Prof. Marcelo In&aacute;cio Ferreira Ferraz</li>
		    <li><span class="nivelcontato">Coordenador TI</span > - Prof. Dany Sanchez Dominguez - Email: <a class="email" href="mailto:dany@labbi.uesc.br">dany@labbi.uesc.br</a></li>
		    <li><span class="nivelcontato">Estagi&aacute;ria ACCB</span> - Kati&uacute;cia Mavin Oliveira Costa -  Email: <a class="email" href="mailto:katy_mavin1@hotmail.com">katy_mavin1@hotmail.com</a> </li>
		    <li><span class="nivelcontato">Estagi&aacute;rio ACCB</span> - Lucas Martins Silva - Email: <a class="email" href="mailto:luucas_ms@hotmail.com">luucas_ms@hotmail.com</a> </li>
                    <li><span class="nivelcontato">Estagi&aacute;ria ACCB</span> - Tainar Silva D&oacute;ria - Email: <a class="email" href="mailto:tainardoria@hotmail.com">tainardoria@hotmail.com</a> </li>
		    <li><span class="nivelcontato">Desenvolvedor</span> - Adriano Medeiros dos Santos </li>
		    <li><span class="nivelcontato">Desenvolvedor</span> - Vagner Luz do Carmo -		     Email: <a class="email" href="mailto:vluzrmos@gmail.com">vluzrmos@gmail.com</a></li>
                    -->
		</ul>



		<div class="clear"></div>
		</div>
	    </div><!-- end main -->
	    <?php require("rodape.php"); ?>

