<?php
error_reporting(E_ERROR | E_PARSE);
if ($_REQUEST['haction']) {
    $estabelecimento_id = $_REQUEST['hid'];
    $estabelecimento_nome = $_REQUEST['estabelecimento_nome'];
    $estabelecimento_endereco = $_REQUEST['estabelecimento_endereco'];
    $estabelecimento_contato = $_REQUEST['estabelecimento_contato'];
    $estabelecimento_telefone = $_REQUEST['estabelecimento_telefone'];
    $estabelecimento_referencial = $_REQUEST['estabelecimento_referencial'];
    $estabelecimento_ativo = (int) $_REQUEST['estabelecimento_ativo'];
    $estabelecimentos_secundarios = $_REQUEST['est_secundario'];
    $cidade_id = $_REQUEST['cidade_id'];
    $bairro_id = $_REQUEST['bairro_id'];
    $action = $_REQUEST['haction'];
    $herr = '';
    if ($action == 'edit') {
        $strsql = "SELECT * FROM tabela_estabelecimentos A,tabela_bairros B WHERE A.bairro_id = B.bairro_id AND estabelecimento_id = '" . $estabelecimento_id . "'";
        $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
        $res = mysqli_fetch_array($res);
        $estabelecimento_nome = $res['estabelecimento_nome'];
        $estabelecimento_endereco = $res['estabelecimento_endereco'];
        $estabelecimento_contato = $res['estabelecimento_contato'];
        $estabelecimento_telefone = $res['estabelecimento_telefone'];
        $estabelecimento_referencial = $res['estabelecimento_referencial'];
        $estabelecimento_ativo = $res['estabelecimento_ativo'];
        $cidade_id = $res['cidade_id'];
        $bairro_id = $res['bairro_id'];
    }
    if ($action == 'save') {
        $strsql = "SELECT * FROM tabela_estabelecimentos WHERE (estabelecimento_nome = '" . $estabelecimento_nome . "') AND (estabelecimento_id <> '" . $estabelecimento_id . "' AND bairro_id = '" . $bairro_id . "')";
        $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
        
        if ($res && mysqli_num_rows($res) > 0)
            $herr = "Existe outro estabelecimento com o nome: " . $estabelecimento_nome . " na mesma cidade e bairro";
        else {
            if ($estabelecimento_id != '')//Vamos a atualizar
                $strsql = "UPDATE tabela_estabelecimentos SET estabelecimento_nome = '" . $estabelecimento_nome . "',estabelecimento_endereco = '" . $estabelecimento_endereco . "', estabelecimento_ativo = '" . $estabelecimento_ativo . "', estabelecimento_contato = '" . $estabelecimento_contato . "',estabelecimento_telefone = '" . $estabelecimento_telefone . "',bairro_id = '" . $bairro_id . "',estabelecimento_referencial = '" . $estabelecimento_referencial . "' WHERE estabelecimento_id = '" . $estabelecimento_id . "'";
            else {//Vamos a insertar
                $data = date('Y-m-d');
                $strsql = "INSERT INTO tabela_estabelecimentos (estabelecimento_nome,estabelecimento_endereco, estabelecimento_ativo, estabelecimento_contato,estabelecimento_telefone,estabelecimento_data,bairro_id,estabelecimento_referencial) VALUES ('" . $estabelecimento_nome . "','" . $estabelecimento_endereco . "','" . $estabelecimento_ativo . "', '" . $estabelecimento_contato . "','" . $estabelecimento_telefone . "','" . $data . "','" . $bairro_id . "','" . $estabelecimento_referencial . "')";
            }
            mysqli_query($conn, $strsql) or die(mysqli_error($conn));
            if(count($estabelecimentos_secundarios) > 0){
                if($estabelecimento_id == '') 
                    $estabelecimento_id = mysqli_insert_id($conn);

                $str_estsec = "SELECT * FROM tabela_estabelecimento_has_secundario WHERE estabelecimento_id = ".$estabelecimento_id;//." AND estabelecimento_sec_id IN ('".implode(',', $estabelecimentos_secundarios)."')";
                $res_estsec = mysqli_query($conn, $str_estsec) or die(mysqli_error($conn));
                $estsec_cadastrados = array();
                
                
                while($estsec_row = mysqli_fetch_assoc($res_estsec)){
                    $estsec_cadastrados[] = $estsec_row['estabelecimento_sec_id'];
                }
            
                $values = array();
                $values_del = array();
                /*echo $estabelecimento_id.'<br/>';
                print_r($estabelecimentos_secundarios);
                print_r($estsec_cadatrados);*/
                foreach($estabelecimentos_secundarios as $val){
                        if(!in_array($val,$estsec_cadastrados ))
                            $values[] = "('$estabelecimento_id', '$val')";
                }
                
                foreach($estsec_cadastrados as $val){
                    if(!in_array($val, $estabelecimentos_secundarios))
                            $values_del[] = $val;
                }
                
                if(count($values) > 0){
                    $sql_ins_estsec  = "INSERT INTO tabela_estabelecimento_has_secundario (estabelecimento_id, estabelecimento_sec_id) VALUES ".implode(',',$values);
                    mysqli_query($conn, $sql_ins_estsec) or die(mysqli_error($conn)." - Arquivo: ".__FILE__." Linha: ". __LINE__);
                }
                
                if(count($values_del) > 0){
                    $sql_del_estsec  = "DELETE FROM tabela_estabelecimento_has_secundario WHERE estabelecimento_id = $estabelecimento_id AND estabelecimento_sec_id IN (".implode(',',$values_del).")";
                    mysqli_query($conn, $sql_del_estsec) or die(mysqli_error($conn)." - Arquivo: ".__FILE__." Linha: ". __LINE__);
                }
                
            }
            unset($estabelecimento_id,$estabelecimento_nome,
                  $estabelecimento_endereco, $estabelecimento_contato,
                  $estabelecimento_telefone, $estabelecimento_referencial,
                  $cidade_id, $bairro_id, $action);
            
            /*$estabelecimento_id = '';
            $estabelecimento_nome = '';
            $estabelecimento_endereco = '';
            $estabelecimento_contato = '';
            $estabelecimento_telefone = '';
            $estabelecimento_referencial = '';
            $cidade_id = '';
            $bairro_id = '';
            $action = '';
            */
            header("Location: cadastro_estabelecimentos.php");
        }//do else de num_rows > 0
    }//do if save
    if ($action == 'del') {
        
        /*$sql_est_sec = "DELETE FROM tabela_estabelecimento_has_secundario WHERE estabelecimento_id = ".$estabelecimento_id;
        mysqli_query($sql_est_sec) or die(mysqli_error()." - Arquivo: ".__FILE__." Linha: ". __LINE__);
        */
        $strsql = "DELETE FROM tabela_estabelecimentos WHERE estabelecimento_id = '" . $estabelecimento_id . "'";
        mysqli_query($conn, $strsql) or die(mysqli_error($conn)." - Arquivo: ".__FILE__." Linha: ". __LINE__);
        
        header("Location: cadastro_estabelecimentos.php");
    }
}
$include_head[] = <<<EOS
<link rel="stylesheet" type="text/css" href="estilo/live_validation.css" media="screen" />\n
EOS;

require("cabecalho.php");
?>
<body>

    <div class="caixa_principal">

        <?php require("topo.php"); ?>

        <div class="menu_superior">
            <?php require("menu_superior.php"); ?>
        </div>

        <div class="menu_lateral">
            <?php require("menu_lateral_cadastros.php"); ?>
        </div>

        <div class="conteudo_pagina">
            <!-- Contedo referente a esta pgina -->

            <fieldset>
                <legend><?php if ($estabelecimento_id) { ?>Editar<?php } else { ?>Adicionar<?php } ?> Estabelecimento </legend>

                <form name="form_cadastro" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>" style="width:600px;">

                    <?php if ($estabelecimento_id) { ?>
                        <p>
                            <label for="codigo">C&oacute;digo:</label> 
                            <input type="text" disabled="disabled" size="5" class="inactive" value="<?php echo($estabelecimento_id); ?> "/>
                            <input type="hidden" name="hid" value="<?php echo($estabelecimento_id); ?>">
                        </p>
                    <?php } ?>

                    <p>
                        <label for="nome">Nome:</label>
                        <input type="text" maxlength="100" name="estabelecimento_nome" id="estabelecimento_nome" size="40" value="<?php echo($estabelecimento_nome); ?>" />
                        <span class="obrig">*</span>
                        <input type="hidden" name="haction" value="save"/>
                    </p>

                    <p>
                        <label for="endereco">Endere&ccedil;o:</label>
                        <input type="text" name="estabelecimento_endereco" maxlength="255" value="<?php echo($estabelecimento_endereco); ?>" size="40" />
                        <input type="hidden" name="haction" value="save"/>
                    </p>
                    <p>
                        <label for="estabelecimento_ativo">Estabelecimento Ativo: </label>
                        <input type="checkbox" name="estabelecimento_ativo" id="estabelecimento_ativo" value="1" <?php
                    if (isset($estabelecimento_ativo)) {
                        echo (($estabelecimento_ativo) ? 'checked="checked"' : '');
                    } else
                        echo 'checked="checked"';
                    ?>/>
                    </p>
                    <p>
                        <label for="cidade">Cidade:</label>
                        <select name="cidade_id" id="cidade" onChange="pop_select('procura_bairros.php','<---------     Escolha o Bairro    --------->')">

                            <option value="0"><--------   Escolha a Cidade  --------></option>

                            <?php
                            $strsql = "SELECT * FROM tabela_cidades";
                            $cidades = mysqli_query($conn, $strsql) or die(mysqli_error($conn));

                            if ($cidades && mysqli_num_rows($cidades) > 0)
                                while ($row = mysqli_fetch_array($cidades)) {
                                    ?>

                                    <option value="<?php echo($row['cidade_id']); ?>" <?php if ($cidade_id == $row['cidade_id']) { ?>selected="selected" <?php } ?>  > <?php echo ($row['cidade_nome']); ?></option>

        <?php
    }
?>	

                        </select>
                        <span class="obrig">*</span>
                    </p>

                    <p>
                        <label for="bairro">Bairro:</label>
                        <select <?php if ($cidade_id == '') { ?> class="select_desativado" disabled="disabled" <?php } ?> name="bairro_id" id="select_pop" >

                            <option value="0"><---------     Escolha o Bairro    ---------></option>
                            <?php
                            if ($action == 'edit') {
                                $strsql = "SELECT * FROM tabela_bairros WHERE cidade_id = '" . $cidade_id . "'";
                                $bairros = mysqli_query($conn, $strsql) or die(mysqli_error($conn));

                                if ($bairros && mysqli_num_rows($bairros) > 0)
                                    while ($row = mysqli_fetch_array($bairros)) {
                                        ?>
                                        <option value="<?php echo($row['bairro_id']); ?>" <?php if ($bairro_id == $row['bairro_id']) { ?>selected="selected" <?php } ?>  > <?php echo ($row['bairro_nome']); ?></option>

        <?php }
}
?>
                        </select>
                        <span class="obrig">*</span>
                    </p>

                    <p>
                        <label for="estabelecimento_contato">Contato:</label>
                        <input type="text" name="estabelecimento_contato" maxlength="255" value="<?php echo($estabelecimento_contato); ?>" size="40" />
                    </p>
                    <p>
                        <label for="estabelecimento_telefone">Telefone:</label>
                        <input type="text" name="estabelecimento_telefone" id="estabelecimento_tel" onKeyPress="mascara(this,telefone)" maxlength="14" value="<?php echo($estabelecimento_telefone); ?>" size="40" />
                        <input type="hidden" name="haction" value="save"/>
                    </p>
                    <p>
                        <label for="estabelecimento_referencial">Referência:</label>
                        <textarea name="estabelecimento_referencial" rows="4" cols="30"><?php echo($estabelecimento_referencial); ?></textarea>
                    </p>

                    <p>
                        <script type='text/javascript'>
                            //secundariosarray = '<input type="hidden" name="est_secundario[]"/>';
                            function onchange_estsec(select, selectedIndex){
                                var option;
                                if(selectedIndex == undefined)
                                    option = select.options[select.selectedIndex];
                                else
                                    option = select.options[selectedIndex];
                                
                                var dom_lista_est_sec = document.getElementById('lista_estsec');
                                var inputText = document.createElement('input');
                                inputText.type = "text";
                                inputText.value = option.text;
                                inputText.size = 40;//option.text.length;
                                inputText.readOnly = true;
                                
                                var inputHidden = document.createElement('input');
                                inputHidden.type  = 'hidden';
                                inputHidden.name  = 'est_secundario[]';
                                inputHidden.value = option.value;
                                
                                //desativa a opcao da lista de estabelecimentos secundarios
                                option.disabled = true;
                                //coloca o primeiro como selecionado " === select ==="
                                select.options[0].selected = true;
                                
                                //por questoes de layout...
                                var label = document.createElement('label');
                                label.innerHTML = "&nbsp";//inputHidden.value;
                                //
                                var novalinha = document.createElement('br');
                                
                                //botão para excluir
                                var img_excluir = document.createElement ('img');
                                img_excluir.src = "images/botao_deletar.png";
                                //img_excluir.style="height:10px";
                                img_excluir.onclick = function(){
                                    //remove elementos do documento
                                    dom_lista_est_sec.removeChild(label);
                                    dom_lista_est_sec.removeChild(inputText);
                                    dom_lista_est_sec.removeChild(inputHidden);
                                    dom_lista_est_sec.removeChild(novalinha);
                                    dom_lista_est_sec.removeChild(img_excluir);
                                    option.disabled = false;
                                }
                                //adciona no documento
                                dom_lista_est_sec.appendChild(label);
                                dom_lista_est_sec.appendChild(inputText);
                                dom_lista_est_sec.appendChild(inputHidden);
                                dom_lista_est_sec.appendChild(img_excluir);
                                dom_lista_est_sec.appendChild(novalinha);
                            }
                        </script>
                        <?php
                        $sql_estsecundarios = "SELECT * FROM tabela_estabelecimentos_secundarios order by estabelecimento_sec_nome";
                        $res_estsecundarios = mysqli_query($conn, $sql_estsecundarios) or die(mysqli_error($conn)." - Arquivo: ".__FILE__." Linha: ". __LINE__);
                        
                        //array com ids de estabelecimentos secundarios cadastrados 
                        //para este estabelecimento
                        $est_has_sec = array();
                        
                        if($estabelecimento_id){
                            $sql_est_has_sec = "SELECT estabelecimento_sec_id FROM tabela_estabelecimento_has_secundario NATURAL JOIN tabela_estabelecimentos_secundarios WHERE estabelecimento_id = ".$estabelecimento_id." order by estabelecimento_sec_nome";
                            $res_est_has_sec = mysqli_query($conn, $sql_est_has_sec) or die(mysqli_error($conn)." - Arquivo: ".__FILE__." Linha: ". __LINE__);
                            while($row_est_has_sec = mysqli_fetch_assoc($res_est_has_sec)){
                                $est_has_sec[] = $row_est_has_sec['estabelecimento_sec_id'];
                            }
                        }
                        
                        $html_select_estsecundarios = '';
                        $html_lista_estsecundarios = '';
                        $id_option = 1;
                        $options_selected = array();
                        while ($row_estsecundario = mysqli_fetch_assoc($res_estsecundarios)) {
                            $html_select_estsecundarios .= '<option value="' . $row_estsecundario['estabelecimento_sec_id'].'">' ;
                            $html_select_estsecundarios .=  $row_estsecundario['estabelecimento_sec_nome'] . '</option>';
                            if(in_array($row_estsecundario['estabelecimento_sec_id'], $est_has_sec)){
                                $options_selected[] = $id_option;
                            }
                            
                            $id_option ++;
                        }   
                        ?>
                        <label for="estabelecimentos_secundarios">Secund&aacute;rios:</label>
                        <select id="select_lista_estsec" onchange="this.value!=0&&onchange_estsec(this);">
                            <option value="0" > --- Selecione --- </option>
                            <?php echo $html_select_estsecundarios; ?>
                        </select>
                        <p  id="lista_estsec">
                            <?php echo $html_lista_estsecundarios;?>
                        </p>
                        <script type="text/javascript">
                            var select_estsec = document.getElementById('select_lista_estsec');
                            <?php
                                foreach($options_selected as $id_option){
                                    echo "onchange_estsec(select_estsec,".$id_option.");";
                                }
                                ?>
                        </script>


                    </p>
                    <p>
                        <input type="submit" class="botao_submit" value="<?php if ($estabelecimento_id) { ?>Editar<?php } else { ?>Adicionar<?php } ?>" size="40" />
                        <input type="button" value="Cancelar" class="botao_cancelar"  onclick="javascript: go_Page('cadastro_estabelecimentos.php');"/>

                    </p>

                    <p class="obrig" align="right" style="color:#FF0000";margin-left:10px;>* Campos obrigat&oacute;rios&nbsp;&nbsp;&nbsp;</p>


                </form> 
            </fieldset>			

        </div>


        <div class="clearer"><span></span></div>

        <div class="rodape">&nbsp </div>

<?php if ($herr != '') { ?>

            <script type="text/javascript" language="javascript">
                alert('<?php echo($herr); ?>');
            </script>

<?php } ?> 

    </div>

    <form name="frm_send_data" method="post" action="">
        <input type="hidden" name="haction" value="">
        <input type="hidden" name="hid" value="">
    </form>
    <script language="javascript" type="text/javascript">
        var frm_validator = new Validator("form_cadastro");
        frm_validator.addValidation("estabelecimento_nome","required","O campo NOME não pode ficar em branco!");
        frm_validator.addValidation("cidade_id","dontselect=0","O campo CIDADE nao foi selecionado!");
        frm_validator.addValidation("bairro_id","dontselect=0","O campo BAIRRO nao foi selecionado!");
        frm_validator.addValidation("estabelecimento_telefone","isfone","O campo TELEFONE está incompleto!");
        frm_validator.addValidation("estabelecimento_telefone","final","Corrija todo(s) o(s) erro(s)!!!");
    </script>
</body>
</html>