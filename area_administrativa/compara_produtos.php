<?php
error_reporting(E_ERROR | E_PARSE);
require("cabecalho.php");
?>
<body>
    <script type="text/javascript" language="javascript" src="../javascript/jquery.js"></script>
    <script type="text/javascript" language="javascript">
        <!--;
        jQuery.noConflict();
        jQuery(function($)
        {
            err = "";
            $("input[value='Consultar']").click(function()
            {
                if($("#data_inicial").val()=="mm/aaaa")
                    err +="Deve inserir a data inicial do periodo!\n";
                if($("#data_final").val()=="")
                    err +="Deve inserir a data final do periodo!\n";
			
                var produtos = document.getElementById("produtos");
                var tam = produtos.length;
                var contprod = false;
			
                //verifica se algum produto foi escolhido
                for(i=0;i < tam;i++)
                    if(produtos[i].selected){
                        contprod=true;
                        break;
                    };
			
                if(!contprod)
                    err += "Deve escolher ao menos UM produto!\n";

                //se houve alguma mensagem de erro, exibe alerta
                if(err!=""){
                    alert(err);
                    err = "";
                    return false;
                }
                else visualizacao_grafica();
                //*/
            }
        );

        });
        //-->
    </script>


    <div class="caixa_principal">

        <?php require("topo.php"); ?>

        <div class="menu_superior">
            <?php require("menu_superior.php"); ?>
        </div>

        <div class="menu_lateral">
            <?php require("menu_lateral_consultas.php"); ?>
        </div>
        <div class="conteudo_pagina">
            <noscript>
                <center style="font-size:20pt;color:red">
					O JavaScript deve estar habilitado!
                </center>
            </noscript>

            <fieldset style="width:620px;">
                <legend>Consultas Por Cidades</legend>
                <form action="resultado_compara.php" method="post" id="form_consultas" name="form_consultas" >
                    <!-- Inicio do lado esquerdo -->
                    <div class="conteudo_pagina_esquerda">
                        <p>
                            <label for="cidade">Cidade</label>
                            <select name="cidade_id" id="cidade" style="width:180px;">
                                <?php
                                $strsql = "SELECT * FROM tabela_cidades";
                                $cidades = mysqli_query($conn, $strsql) or die(mysqli_error($conn));

                                if ($cidades && mysqli_num_rows($cidades) > 0)
                                    while ($row = mysqli_fetch_array($cidades)) {
                                ?>

                                        <option value="<?php echo($row['cidade_id']); ?>" <?php if ($cidade_id == $row['cidade_id']) {
 ?>selected="selected" <?php } ?>  > <?php echo ($row['cidade_nome']); ?></option>

<?php
                                    }
?>	

                            </select>
                            <span class="obrig">*</span>
                        </p>
                        <p>
                            <label>Tipo de Dado</label>
                            <select id="tipo_dado" style="width:180px;">
                                <option value="1">Gasto Mensal</option>
                                <option value="2">Tempo de Trabalho</option>
                                <option value="3">Pre&ccedil;o M&eacute;dio</option>
                            </select>
                            <span class="obrig">*</span>
                        </p>
                        <p>
                            <label>Per&iacute;odo</label>
                            <input type="text" id="data_inicial" value="mm/aaaa" onKeyPress="mascara(this,data)" size="7" maxlength="7" /><span class="obrig">*</span>
                            &nbsp;&nbsp;at&eacute;&nbsp;&nbsp;
                            <input type="text" id="data_final"  value="<?php echo date("m/Y"); ?>" size="7" onKeyPress="mascara(this,data)" maxlength="7" /><span class="obrig">*</span>
                        </p>
                        <p align="center">
                            <input type="button" value="Consultar" />
                            <input type="reset" value="Limpar" />
                        </p>
                    </div>
                    <!-- Fim do lado esquerdo -->
                    <!-- Inicio do lado direito-->
                    <div class="conteudo_principal_direita">
                        <p>
                            <label>Produtos</label>
                            <select name="produtos" id="produtos" size="12" multiple="multiple" style="width:120px;">
<?php
                                $strsql = "SELECT * FROM tabela_produtos WHERE produto_cesta = '1'";
                                $res = mysqli_query($conn, $strsql) or die(mysqli_error($conn));

                                if ($res || mysqli_num_rows($res))
                                    while ($row = mysqli_fetch_array($res)) {
?>
                                        <option value="<?php echo($row['produto_id']); ?>"><?php echo($row['produto_nome_visualizacao']); ?></option>
                                <?php
                                    }
                                ?>

                            </select>
                            <span class="obrig">*</span>
                        </p>
                        <p class="obrig" align="right" style="color:#FF0000;margin-left:10px;">* Campos obrigat&oacute;rios&nbsp;&nbsp;&nbsp;</p>
                    </div>

                    <!-- Fim do lado direito -->
                </form>
            </fieldset>
        </div>


        <div class="clearer"><span></span></div>

        <div class="rodape">&nbsp </div>

    </div>

</body>
</html>
<script type="text/javascript" language="javascript">


    function visualizacao_grafica()
    {
        var url = 'res_compara_produtos.php?';
        var cit = document.getElementById("cidade").value;
        var dt_ini = document.getElementById("data_inicial").value;
        var dt_fim = document.getElementById("data_final").value;
        var tp_dado = document.getElementById("tipo_dado").value;
        var params = '';
        var altura = 640;
        var largura = 1024;
        var w = screen.width;
        var h = screen.height;
        var meio_w = w/2;
        var meio_h = h/2;
        var altura2 = altura/2;
        var largura2 = largura/2;
        var meio1 = meio_h-altura2;
        var meio2 = meio_w-largura2;
	
        params = 'hprod=' + getSelectedItem() + '&hc=' + cit + "&hdti=" + dt_ini + '&hdtf=' + dt_fim + '&htd=' + tp_dado;
        url += params;
	
        window.open(url,'','scrollbars=yes,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,copyhistory=no,height=' + altura + ',width=' + largura + ',top='+meio1+',left='+meio2+'');
        return false;
	
    }
	
    function getSelectedItem()
    {

        var produtos = document.getElementById("produtos");
        var tam = produtos.length;
        var i = 0;
        var aux = "";

        for (i = 0; i < tam; i++)
        {
            if (produtos[i].selected)
                aux = aux + produtos[i].value + "/";
	
        }

        return aux.substring(0,aux.length-1);
    }
	
</script>
