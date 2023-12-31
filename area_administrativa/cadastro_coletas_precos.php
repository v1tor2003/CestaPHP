<?php
$coleta_id = $_REQUEST['coleta_id'];

$precos_coleta = $_REQUEST['precos_coleta'];
$precos_codigo = $_REQUEST['precos_codigo'];
$produtos_id = $_REQUEST['produtos_id'];
$produto_selecionados = $_REQUEST['produtos_selecionados'];
$produto_id = $_REQUEST['hid'];
$medida_id = $_REQUEST['hid1'];

$estabelecimentos_secundarios = $_REQUEST['estabelecimento_secundario'];

$action = $_REQUEST['haction'];
$pesquisa_id = $_REQUEST['pid'];
$precos_id = $_REQUEST['precos_id'];
$flag = false;
$herr_flag = false;
$records_per_page = $_REQUEST['rpp'];
$aux = array();


if ($action == 'salvar') {
    for ($i = 0; $i < count($precos_codigo); $i++) {
        $strsql = "DELETE FROM tabela_auxiliar_precos WHERE precos_id = " . $precos_codigo[$i];
        $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));

        for ($j = ($i * 5); $j < ($i * 5 + 5); $j++) {

            if ($precos_coleta[$j] != "" || $precos_coleta[$j] != NULL) {
                $strsql = "INSERT INTO tabela_auxiliar_precos(precos_id,preco_produto) VALUES ('" . $precos_codigo[$i] . "','" . $precos_coleta[$j] . "')";
                $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
            }
        }
    }



    for ($i = 0; $i < count($produtos_id); $i++) {
        if ($estabelecimentos_secundarios[$i] > 0) {
            //evita duplicatas
            $strsql = "SELECT * FROM tabela_coleta_est_sec WHERE coleta_id = $coleta_id AND est_has_sec_id = $estabelecimentos_secundarios[$i] AND  produto_id = $produtos_id[$i]";
            $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn) . " - SQL: " . $strsql . " - Arquivo: " . __FILE__ . " - Linha: " . __LINE__);

            //apaga outros estabelecimentos secundarios cadastrados para um mesmo produto
            $strsql = "DELETE FROM tabela_coleta_est_sec WHERE coleta_id=$coleta_id AND produto_id = $produtos_id[$i] AND est_has_sec_id != $estabelecimentos_secundarios[$i]";
            mysqli_query($conn, $strsql) or die(mysqli_error($conn) . " - SQL: " . $strsql . " - Arquivo: " . __FILE__ . " - Linha: " . __LINE__);

            if (mysqli_num_rows($res) < 1) {
                $strsql = "INSERT INTO tabela_coleta_est_sec (coleta_id, est_has_sec_id, produto_id) VALUES (" . $coleta_id . "," . $estabelecimentos_secundarios[$i] . "," . $produtos_id[$i] . ")";
                mysqli_query($conn, $strsql) or die(mysqli_error($conn) . " - SQL: " . $strsql . " - Arquivo: " . __FILE__ . " - Linha: " . __LINE__);
            }
        }
    }
    //Apaga todos os estabelecimentos cadastrados para esta coleta que nao tem produtos assossiados"
    $strsql = "DELETE FROM tabela_coleta_est_sec WHERE coleta_id=$coleta_id AND produto_id NOT IN(" . implode(',', $produtos_id) . ")";
    mysqli_query($conn, $strsql) or die(mysqli_error($conn) . " - SQL: " . $strsql . " - Arquivo: " . __FILE__ . " - Linha: " . __LINE__);



    $precos_coleta = NULL;
    $precos_codigo = NULL;
    $herr = "Preços salvos com sucesso!";
    $herr_flag = true;
    $action = '';
}


if ($action == 'adicionar_produto') {
    $herr = "Produtos com duplicidade na medida:\\n";
    for ($i = 0; $i < count($produto_selecionados); $i++) {

        $flag = false;

        $aux = preg_split('/[\/]/', $produto_selecionados[$i]);
        $produto_selecionado[$i] = $aux[0];
        $medida[$i] = $aux[1];
        $flag_cadastro[$i] = 1; //caso o produto ainda não esteja cadastrado recebe 1 como default

        $strsql = "SELECT produto_id,medida_id FROM tabela_precos WHERE coleta_id = '" . $coleta_id . "' AND produto_id = '" . $produto_selecionado[$i] . "'";
        $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));

        if (mysqli_num_rows($res) > 0) {
            $flag = true;
            $herr_flag = true;

            $row = mysqli_fetch_array($res);
            $strsql = "SELECT * FROM tabela_produtos WHERE produto_id = " . $row['produto_id'];

            $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
            $res = mysqli_fetch_array($res);
            $produto_nome = $res['produto_nome_visualizacao'];

            $strsql = "SELECT * FROM tabela_unidade_medidas WHERE medida_id= " . $medida[$i];

            $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
            $res = mysqli_fetch_array($res);
            $simbolo = $res['medida_simbolo'];

            $herr .= " - " . $produto_nome . " (" . $simbolo . ")\\n";
        }

        if (!$flag) {

            $strsql = "INSERT INTO tabela_precos (produto_id,coleta_id,medida_id) VALUES ('" . $produto_selecionado[$i] . "','" . $coleta_id . "','" . $medida[$i] . "')";
            $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
        }
    }

    $action = '';
    $medida = NULL;
    $produto_nome = NULL;
}

if ($action == 'del') {

    $strsql = "DELETE FROM tabela_coleta_est_sec WHERE coleta_id = '$coleta_id' AND produto_id='$produto_id'";
    mysqli_query($conn, $strsql) or die(mysqli_error($conn) . " - SQL: " . $strsql . " - Arquivo: " . __FILE__ . " - Linha: " . __LINE__);

    $strsql = "DELETE FROM tabela_precos WHERE coleta_id = '" . $coleta_id . "' AND (produto_id = '" . $produto_id . "' AND medida_id = '" . $medida_id . "')";
    mysqli_query($conn, $strsql) or die(mysqli_error($conn));

    header("Location:" . $_SERVER['PHP_SELF'] . "?coleta_id=" . $coleta_id . "&rpp=" . $records_per_page . "&pid=" . $pesquisa_id);
}


if ($records_per_page == '')
    $records_per_page = 12;
$start_rec = ($_REQUEST['hp'] != '') ? $_REQUEST['hp'] : 0;
$strsql = "SELECT A.produto_id,B.produto_nome_visualizacao,C.medida_descricao,C.medida_simbolo,A.precos_id,A.medida_id FROM tabela_precos A,tabela_produtos B,tabela_unidade_medidas C WHERE A.coleta_id = '" . $coleta_id . "' AND (A.produto_id = B.produto_id AND A.medida_id = C.medida_id)";
$produtos = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
$total_rec = mysqli_num_rows($produtos);
if ($start_rec >= $total_rec)
    $start_rec -= $records_per_page;
if ($start_rec < 0)
    $start_rec = 0;
$last_rec = ($start_rec + $records_per_page > $total_rec) ? $total_rec : $start_rec + $records_per_page;
$back_rec = $start_rec - $records_per_page;
$pages = floor($total_rec / $records_per_page);
$start_last_page = ($pages * $records_per_page == $total_rec) ? ($pages - 1) * $records_per_page : $pages * $records_per_page;
$pagina_atual = floor(($start_rec == 0 ) ? 1 : (($start_rec / $records_per_page) + 1));



require("cabecalho.php");
?>
<body>


    <div class="caixa_principal">

        <?php require("topo.php"); ?>

        <div class="menu_superior">
            <?php require("menu_superior.php"); ?>
        </div>

        <div class="menu_lateral">
            <?php require("menu_lateral_coletas.php"); ?>
        </div>


        <div id="principal" class="conteudo_pagina">
            <!-- Contedo referente a esta pgina -->

            <?php
            $strsql = "SELECT * FROM tabela_coletas A, tabela_estabelecimentos B,tabela_bairros C,tabela_cidades D  WHERE (A.estabelecimento_id = B.estabelecimento_id) AND coleta_id = '" . $coleta_id . "' AND C.bairro_id = B.bairro_id AND C.cidade_id = D.cidade_id";

            $coletas = mysqli_query($conn, $strsql) or die(mysqli_error($conn));

            if ($coletas && mysqli_num_rows($coletas) > 0) {
                $row = mysqli_fetch_array($coletas);
                $estabelecimento_id = $row['estabelecimento_id'];
                $estabelecimento_nome = $row['estabelecimento_nome'];
                $data = formata_data($row['coleta_data'], 1);
                $bairro = $row['bairro_nome'];
                $cidade = $row['cidade_nome'];
            }
            ?>
            <a href="<?php echo('cadastro_coletas.php?pesquisa_id=' . $pesquisa_id); ?>"><img style=" float:right; border:none;" src="images/seta_azul.png" ></a>
            <h1 id="Mcaption" style="text-align:left">Coleta: <?php echo ($estabelecimento_nome . " - " . $data . "<br>Local &nbsp;: " . $cidade . " - " . $bairro); ?></h1>
            <hr />
            <h1 id="Mcaption" style="text-align:left">Produtos Cadastrados</h1>
            <!--<table  border="0px" id="link_table_add"  style="width:590px"><tr><td>[<a href="" id="link_adicionar" >Adicionar Produtos</a>]</td></tr></table>-->
            <?php
            $strsql = "SELECT A.precos_id,A.produto_id,B.produto_nome_visualizacao,C.medida_descricao,C.medida_simbolo,A.precos_id,A.medida_id FROM tabela_precos A,tabela_produtos B,tabela_unidade_medidas C WHERE A.coleta_id = '" . $coleta_id . "' AND (A.produto_id = B.produto_id AND A.medida_id = C.medida_id) ORDER BY A.produto_id  LIMIT " . $start_rec . "," . $records_per_page;
            $produtos = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
            if ($produtos && mysqli_num_rows($produtos) > 0) {

                $qtd_prod = mysqli_num_rows($produtos);
                $qt = 5;

                $sql_estsecundarios = "SELECT * FROM tabela_estabelecimento_has_secundario tab_est_has_sec NATURAL JOIN tabela_estabelecimentos_secundarios WHERE tab_est_has_sec.estabelecimento_id = '" . $estabelecimento_id . "' ORDER BY estabelecimento_sec_nome";
                $res_estsecundarios = mysqli_query($conn, $sql_estsecundarios) or die(mysqli_error($conn));
                ;
                $html_select_estsecundarios = '';

                $sql_coleta_est_sec = "SELECT * FROM tabela_coleta_est_sec NATURAL JOIN tabela_estabelecimento_has_secundario WHERE coleta_id=" . $coleta_id;
                $res_coleta_est_sec = mysqli_query($conn, $sql_coleta_est_sec) or die(mysqli_error($conn) . " - SQL: " . $sql_coleta_est_sec . " - Arquivo: " . __FILE__ . " - Linha: " . __LINE__);

                while ($row_coleta_est_sec = mysqli_fetch_assoc($res_coleta_est_sec)) {
                    $coleta_est_sec[] = $row_coleta_est_sec;
                }

                unset($estabelecimentos_secundarios); // = array();
                while ($row_est_sec = mysqli_fetch_assoc($res_estsecundarios)) {
                    $estabelecimentos_secundarios[] = $row_est_sec;
                    //$html_select_estsecundarios .= '<option value="'.$row_estsecundarios['est_has_sec_id'].'">'.$row_estsecundarios['estabelecimento_sec_nome'].'</option>';
                }

                //die(print_r($coleta_est_sec).print_r($estabelecimentos_secundarios));
                ?>
                <form method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>">
                    <a href="<?php echo('cadastro_coletas.php?pesquisa_id=' . $pesquisa_id); ?>"> </a>
                    <table cellspacing="0" id="listTable" summary="Tabela de PreÃ§os das Coletas">
                        <colgroup>
                            <!--col id="codigo" /-->
                            <col id="produto" />
                            <col id="estabelecimento" />
                            <col id="medida" />
                            <col id="preco" />
                        </colgroup>		

                        <thead>
                            <tr>
                                    <!--<th scope="col" class="tdboderCod">C&oacute;digo</th>-->
                                <th scope="col" class="tdboder">Produto</th>
                                <th scope="col" class="tdboder">Estabelecimento</th>
                                <th scope="col" class="tdboder">Medida</th>
                                <?php for ($i = 0; $i < $qt; $i++) { ?>
                                    <th scope="col" class="tdboder"><?php echo("Pre&ccedil;o " . ($i + 1)); ?></th>
                                <?php } ?>
                                <th scope="col" class="tdboder">A&ccedil;&atilde;o</th>
                            </tr>
                        </thead>
                        <?php
                        while ($row = mysqli_fetch_array($produtos)) {

                            if ($l_cor == '' || $l_cor == 'impar')
                                $l_cor = "par";
                            else
                                $l_cor = "impar";
                            ?>
                            <tr class="<?php echo ($l_cor); ?>">
                                    <!--<td class="tdboderCod"><?php echo($row['produto_id']); ?></td>-->
                                <td class="tdboder">
                                    <?php
                                    echo "<input type='hidden' name='produtos_id[]' value='" . $row['produto_id'] . "'/>";
                                    echo($row['produto_nome_visualizacao']);
                                    ?>
                                </td>
                                <td class="tdboder">
                                    <select name="estabelecimento_secundario[]" style="width:120px">
                                        <option value="">Est. principal</option>
                                        <?php
                                        foreach ($estabelecimentos_secundarios as $est_sec) {
                                            echo "<option value='" . $est_sec['est_has_sec_id'] . "' ";
                                            foreach ($coleta_est_sec as $coleta_sec) {
                                                if ($row['produto_id'] == $coleta_sec['produto_id']
                                                        AND $est_sec['estabelecimento_sec_id'] == $coleta_sec['estabelecimento_sec_id']) {
                                                    echo "selected='selected'";
                                                    break;
                                                }
                                            }
                                            echo ">" . $est_sec['estabelecimento_sec_nome'] . "</option>";
                                        }

                                        //echo $html_select_estsecundarios;
                                        ?>
                                    </select>

                                </td>
                                <td class="tdboder">
                                    <?php
                                    $strsql = "SELECT medida_id, medida_descricao,produto_id FROM tabela_produtos_medidas NATURAL JOIN tabela_unidade_medidas WHERE produto_id=" . $row['produto_id'];
                                    $bd_medidas = mysqli_query($conn, $strsql) or die(mysqli_error($conn));

                                    if ($bd_medidas && mysqli_num_rows($bd_medidas) > 1) {
                                        ?>
                                        <select name="medida_aux" id="medida_aux<?php echo($row['produto_id']); ?>" onChange="return altera_medida_produto('<?php echo($row['produto_id']); ?>')" style="width:100px;">
                                            <?php
                                            while ($row_aux = mysqli_fetch_array($bd_medidas)) {
                                                ?>
                                                <option value="<?php echo($row_aux['medida_id'] . "/" . $row['produto_id'] . "/" . $row['precos_id']); ?>" <?php if ($row['medida_id'] == $row_aux['medida_id']) { ?>selected="selected" <?php } ?>  > <?php echo ($row_aux['medida_descricao']); ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                        <?php
                                    } else {
                                        echo($row['medida_descricao']);
                                    }
                                    ?>	
                                </td>
                            <input type="hidden" name="precos_codigo[]" value="<?php echo($row['precos_id']); ?>"  />
                            <?php
                            $strsql = "SELECT * FROM tabela_auxiliar_precos WHERE precos_id = '" . $row['precos_id'] . "'";
                            $precos_produto = mysqli_query($conn, $strsql) or die(mysqli_error($conn));

                            if ($precos_produto && mysqli_num_rows($precos_produto) >= 0) {

                                for ($i = 0; $i < $qt; $i++) {

                                    $row1 = mysqli_fetch_array($precos_produto);

                                    if ($row1 != '') {
                                        ?>
                                        <td class="tdboder"  width="50" align="center"><input type="text" size="4" name="precos_coleta[]" onkeypress="mascara(this,soNumeros)" maxlength="5" value="<?php echo($row1['preco_produto']); ?>"  /></td>
                                    <?php } else {
                                        ?>
                                        <td class="tdboder"  width="50" align="center"><input type="text" size="4" name="precos_coleta[]" onkeypress="mascara(this,soNumeros)" maxlength="5" value=""  /></td>
                                        <?php
                                    }
                                }
                                ?>
                                <td class="tdboderCod"><a href="javascript: " onClick="return submit_Action2('<?php echo($row['produto_id']); ?>','<?php echo($row['medida_id']); ?>','del','<?php echo($row['produto_nome_visualizacao']); ?>','Deseja apagar o produto ','<?php echo($_SERVER['PHP_SELF'] . "?coleta_id=" . $coleta_id . "&pid=" . $pesquisa_id); ?>')"><img src="images/botao_deletar.png" border="0"></a></td>
                                </tr>

                                <?php
                            }
                        }//do while
                        ?>
                    </table>
                    <input type="hidden" value="<?php echo($precos_id); ?>" name="precos_id" />
                    <input type="hidden" value="<?php echo($coleta_id); ?>" name="coleta_id" />
                    <input type="hidden" value="<?php echo($pesquisa_id); ?>" name="pid" />
                    <input type="hidden" value="<?php echo($records_per_page); ?>" name="rpp" />
                    <input type="hidden" value="<?php echo($start_rec); ?>" name="hp" />
                    <input type="hidden" value="salvar" name="haction" />
                    <p class="legenda_tabela">
                        <b>Quantidade: (<?php echo($total_rec); ?>)</b>
                    </p>
                    <p style="width:570px;">
                        &nbsp;&nbsp;&nbsp;Listagem por p&aacute;gina: <a href="<?php echo($_SERVER['PHP_SELF'] . "?coleta_id=" . $coleta_id . "&rpp=5&pid=" . $pesquisa_id); ?>">5</a> <a href="<?php echo($_SERVER['PHP_SELF'] . "?coleta_id=" . $coleta_id . "&rpp=10&pid=" . $pesquisa_id); ?>">10</a> <a href="<?php echo($_SERVER['PHP_SELF'] . "?coleta_id=" . $coleta_id . "&rpp=12&pid=" . $pesquisa_id); ?>"a>12</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="submit" value="Salvar" />
                    </p>

                </form>
                <table align="left" style="border:0; margin-top:10px;" width="570px;">
                    <tr>
                        <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <?php if ($start_rec != 0) { ?>
                                <span class="pag_links"><a href="<?php echo($_SERVER['PHP_SELF'] . "?hp=0&coleta_id=" . $coleta_id . "&pid=" . $pesquisa_id); ?>" >Primeira</a></span>&nbsp;&nbsp;|&nbsp;<?php } else { ?> <span class="pag_links">Primeira</span>&nbsp;&nbsp;|&nbsp;<?php } ?>
                            <?php if ($back_rec >= 0) { ?>
                                <span class="pag_links"><a href="<?php echo($_SERVER['PHP_SELF'] . "?hp=" . ($back_rec) . "&coleta_id=" . $coleta_id . "&pid=" . $pesquisa_id); ?>">Anterior</a></span>
                            <?php } else { ?>
                                <span class="pag_links">Anterior</span>
                            <?php } ?>
                        </td>
                        <td align="center">
                            P&aacute;gina: <b><?php echo($pagina_atual); ?></b> &nbsp;&nbsp; Resultados: <b><? echo((($total_rec) ? $start_rec + 1 : 0) . " - " . $last_rec); ?></b> de <b><?php echo($total_rec); ?></b>
                        </td>
                        <td align="left">
                            <?php if ($last_rec < $total_rec) { ?>
                                <span class="pag_links"><a href="<?php echo($_SERVER['PHP_SELF'] . "?hp=" . ($last_rec) . "&coleta_id=" . $coleta_id . "&pid=" . $pesquisa_id); ?>">Pr&oacute;xima</a></span>&nbsp;&nbsp;|&nbsp;<?php } else { ?>
                                <span class="pag_links">Pr&oacute;xima</span>&nbsp;&nbsp;|&nbsp;
                            <?php } ?>
                            <?php if ($start_rec + $records_per_page < $total_rec) { ?>
                                <span class="pag_links"><a href="<?php echo($_SERVER['PHP_SELF'] . "?hp=" . ($start_last_page) . "&coleta_id=" . $coleta_id . "&pid=" . $pesquisa_id); ?>">&Uacute;ltima</a></span>
                            <?php } else { ?>
                                <span class="pag_links">&Uacute;ltima</span>
                            <?php } ?>

                        </td>
                    </tr>
                </table>
                <br /><br /><br />
            <?php } else { ?>
                <h1 id="Mcaption" style="text-align:left">&nbsp;&nbsp;Sem produtos cadastrados nesta coleta</h1>
            <?php } ?>	

            <hr />	
            <h1 id="Mcaption" style="text-align:left">Produtos N&atilde;o Cadastrados nesta Coleta</h1>
            <form id="form_produto" name="form_produto" method="post" style="padding-left:170px;">
                <table>
                    <tr>
                        <td>
                            <select name="produtos_selecionados[]" multiple="multiple" size="12" style="width:180px;" >
                                <?php
                                $strsql = "SELECT * FROM tabela_produtos A,tabela_produtos_medidas B,tabela_unidade_medidas C WHERE (B.produto_id,B.medida_id) <> ALL (SELECT produto_id,medida_id FROM tabela_precos C WHERE C.coleta_id = '" . $coleta_id . "') AND (A.produto_id = B.produto_id AND B.medida_id = C.medida_id) ORDER BY B.produto_id";
                                $produtos = mysqli_query($conn, $strsql) or die(mysqli_error($conn));

                                if ($produtos && mysqli_num_rows($produtos) > 0)
                                    while ($row = mysqli_fetch_array($produtos)) {
                                        ?>

                                        <option value="<?php echo($row['produto_id'] . "/" . $row['medida_id']); ?>" <?php if ($produto_id == $row['produto_id'] && $medida_id == $row['medida_id']) { ?> selected="selected" <?php } ?>  >
                                            <?php echo ($row['produto_nome_visualizacao'] . " - " . $row['medida_simbolo'] . " (" . $row['medida_descricao'] . ")"); ?>
                                        </option>

                                        <?php
                                    }
                                ?>	

                            </select>
                            <input type="hidden" value="adicionar_produto" name="haction"/>
                            <input type="hidden" value="<?php echo($precos_id); ?>" name="precos_id" />
                            <input type="hidden" value="<?php echo($coleta_id); ?>" name="coleta_id" />
                            <input type="hidden" value="<?php echo($pesquisa_id); ?>" name="pid" />
                            <input type="hidden" value="<?php echo($records_per_page); ?>" name="rpp" />
                            <input type="hidden" value="<?php echo($start_rec); ?>" name="hp" />
                        </td>
                        <td>
                            <input type="submit" value="Adicionar" />
                        </td>
                    </tr>
                </table>
            </form>

        </div>


        <div class="clearer"><span></span></div>

        <div class="rodape">&nbsp </div>

    </div>

    <?php if ($herr_flag == true) { ?>

        <script type="text/javascript" language="javascript">
                    			
            alert('<?php echo($herr); ?>');
                    			
        </script>


    <?php } ?> 
    <form name="frm_send_data" method="post" action="">
        <input type="hidden" name="haction" value="">
        <input type="hidden" name="hid" value="">
        <input type="hidden" name="hid1" value="">
    </form>	

    <script language="javascript" type="text/javascript">
	
	
        //var frm_validator = new Validator("form_produto");
        //frm_validator.addValidation("hid","dontselect=0","Selecione um PRODUTO!");
        //frm_validator.addValidation("hid","final","");
	
        //Função que adiciona um campo no formulario
        function adicionar_campo()
        {
            //Pega - se o formulário que queremos acrescentar o campo
            var form = document.getElementById("form_cadastro");
            var cont = 0;
	
            //Verificamos o numero de campos que já temos
            for (var i = 0; i < form.childNodes.length; i++) 
            {
                if(form.childNodes[i].nodeName.toLowerCase() == "p")
                    cont = cont + 1;
            }
	
            //Cria um parágrafo com label, caixa de texto e imagem  
            paragrafo = document.createElement("p");
            paragrafo.setAttribute("id", cont);
	
            var label = document.createElement("label");
            textNode = document.createTextNode("Preco :");
            label.appendChild(textNode);
		  
            var cx_preco = document.createElement("input");
            cx_preco.setAttribute("id","preco_produto"+cont);
            cx_preco.setAttribute("maxlength","5");
            cx_preco.setAttribute("onkeypress","mascara(this,soNumeros)");
            cx_preco.size = "8";
	
            var image = '<img src="images/botao_deletar.png" style="margin:0px 0px 0px 10px" onClick="delete_caixa('+cont+');">';
	
            paragrafo.appendChild(label);
            paragrafo.appendChild(cx_preco);
            paragrafo.innerHTML += image;
	
            //Colocamos o campo adicionado antes do botao salvar
            form.insertBefore(paragrafo,document.getElementById("botao_salvar"));
        }

        //Função que deleta um campo desejado
        function delete_caixa(id)
        {	
            var precos_id = document.getElementById("precos_id").value;
            var form = document.getElementById("form_cadastro");
            var paragrafo = document.getElementById(id+"");
            var valor = document.getElementById("preco_produto"+id).value;
	
            var url = "edita_precos.php?precos_id="+escape(precos_id)+"&preco_produto="+escape(valor)+"&action=del";
            request.open("GET",url,true);
            request.send(null);
	
            form.removeChild(paragrafo);

        }

        function salva_precos(coleta)
        {
            var precos_id = document.getElementById("precos_id").value;
            var x=document.getElementById("form_cadastro");
            var pag = document.getElementById("hp").value;
            var aux = null;
            var msg = '';
            var flag = true;
	
            //pegamos os campos precos
            for (var i=0;i<x.length;i++)
            {
                aux = x.elements[i];
		
                if(aux.value != '')
                {
                    if(aux.nodeName.toLowerCase() == "input" && aux.type == "text")
                        msg += "/" + x.elements[i].value;
                }
                else
                {
                    flag = false;
		
                }
		
            }
	
            //se naum tiver nenhuma campo vazio então podemos salvar todos os campos
            if(flag)
            {
		
                var url = "edita_precos.php?precos_id="+escape(precos_id)+"&preco_produto="+escape(msg) + "&action=save&coleta_id="+escape(coleta) + "&hp=" + escape(pag);
                go_Page(url);
            }
            else
            {
                alert("DELETE ou PREENCHA os campos vazios!");
            }
	
        }

        function updatePage()
        {
            var divMain = document.getElementById("principal");
            var divPrecos = document.getElementById("div_precos");
	
            divMain.removeChild(divPrecos);
        }

    </script>
</body>
</html>