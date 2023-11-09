<?php
error_reporting(E_ERROR | E_PARSE);
if ($_REQUEST['haction']) {
    $estabelecimento_sec_id = $_REQUEST['hid'];
    $estabelecimento_sec_nome = $_REQUEST['estabelecimento_sec_nome'];
    /* $estabelecimento_sec_endereco = $_REQUEST['estabelecimento_sec_endereco'];
      $estabelecimento_sec_contato = $_REQUEST['estabelecimento_sec_contato'];
      $estabelecimento_sec_telefone = $_REQUEST['estabelecimento_sec_telefone'];
      $estabelecimento_sec_referencial = $_REQUEST['estabelecimento_sec_referencial'];
      $estabelecimento_sec_ativo = (int) $_REQUEST['estabelecimento_sec_ativo'];
      $cidade_id = $_REQUEST['cidade_id'];
      $bairro_id = $_REQUEST['bairro_id']; */
    $action = $_REQUEST['haction'];
    $herr = '';
    if ($action == 'edit') {
        $strsql = "SELECT * FROM tabela_estabelecimentos_secundarios WHERE estabelecimento_sec_id = '" . $estabelecimento_sec_id . "'";
        $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
        $res = mysqli_fetch_array($res);
        $estabelecimento_sec_nome = $res['estabelecimento_sec_nome'];
        /* $estabelecimento_sec_endereco = $res['estabelecimento_sec_endereco'];
          $estabelecimento_sec_contato = $res['estabelecimento_sec_contato'];
          $estabelecimento_sec_telefone = $res['estabelecimento_sec_telefone'];
          $estabelecimento_sec_referencial = $res['estabelecimento_sec_referencial'];
          $estabelecimento_sec_ativo = $res['estabelecimento_sec_ativo'];
          $cidade_id = $res['cidade_id'];
          $bairro_id = $res['bairro_id']; */
    }
    if ($action == 'save') {
        $strsql = "SELECT * FROM tabela_estabelecimentos_secundarios WHERE (estabelecimento_sec_nome = '" . $estabelecimento_sec_nome . "') AND (estabelecimento_sec_id <> '" . $estabelecimento_sec_id . "')";
        //die($strsql);
        $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));
        if ($res && mysqli_num_rows($res) > 0)
            $herr = "Existe outro estabelecimento secundario com o nome: " . $estabelecimento_sec_nome . "";
        else {
            if ($estabelecimento_sec_id != '')//Vamos a atualizar
                $strsql = "UPDATE tabela_estabelecimentos_secundarios SET estabelecimento_sec_nome = '" . $estabelecimento_sec_nome . "' WHERE estabelecimento_sec_id = '" . $estabelecimento_sec_id . "'";
            else {//Vamos a insertar
                //$data = date('Y-m-d');
                $strsql = "INSERT INTO tabela_estabelecimentos_secundarios (estabelecimento_sec_nome) VALUES ('" . $estabelecimento_sec_nome . "')";
            }
            mysqli_query($conn, $strsql) or die(mysqli_error($conn));
            $estabelecimento_sec_id = '';
            $estabelecimento_sec_nome = '';
            /* $estabelecimento_sec_endereco = '';
              $estabelecimento_sec_contato = '';
              $estabelecimento_sec_telefone = '';
              $estabelecimento_sec_referencial = '';
              $cidade_id = '';
              $bairro_id = ''; */
            $action = '';

            header("Location: cadastro_estabelecimentos_secundarios.php");
        }//do else de num_rows > 0
    }//do if save
    if ($action == 'del') {
        $strsql = "DELETE FROM tabela_estabelecimentos_secundarios WHERE estabelecimento_sec_id = '" . $estabelecimento_sec_id . "'";
        mysqli_query($conn, $strsql) or die(mysqli_error($conn));
        header("Location: cadastro_estabelecimentos_secundarios.php");
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
                <legend><?php if ($estabelecimento_sec_id) { ?>Editar<?php } else { ?>Adicionar<?php } ?> Estabelecimento Secund&aacute;rio</legend>

                <form name="form_cadastro" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>" style="width:600px;">

<?php if ($estabelecimento_sec_id) { ?>
                        <p>
                            <label for="codigo">C&oacute;digo:</label> 
                            <input type="text" disabled="disabled" size="5" class="inactive" value="<?php echo($estabelecimento_sec_id); ?> "/>
                            <input type="hidden" name="hid" value="<?php echo($estabelecimento_sec_id); ?>">
                        </p>
                    <?php } ?>

                    <p>
                        <label for="nome">Nome:</label>
                        <input type="text" maxlength="100" name="estabelecimento_sec_nome" id="estabelecimento_sec_nome" size="40" value="<?php echo($estabelecimento_sec_nome); ?>" />
                        <span class="obrig">*</span>
                        <input type="hidden" name="haction" value="save"/>
                    </p>

                    
                    <p>
                        <input type="submit" class="botao_submit" value="<?php if ($estabelecimento_sec_id) { ?>Editar<?php } else { ?>Adicionar<?php } ?>" size="40" />
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
        frm_validator.addValidation("estabelecimento_sec_nome","required","O campo NOME não pode ficar em branco!");
        
    </script>
</body>
</html>