<?php
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
			
                var cidades = document.getElementById("cidade_id");
                var tam = cidades.length;
                var verifCidade = false;
			
                //verifica se alguma cidade foi escolhida
                for(i=0;i < tam;i++)
                    if(cidades[i].selected){
                        verifCidade=true;
                        break;
                    };
			
                if(!verifCidade)
                    err += "Deve escolher ao menos UMA cidade!\n";

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
                <legend>Consultas Por Produtos</legend>
                <form action="" method="post" name="form_consultas" >
                    <!-- Inicio do lado esquerdo -->
                    <div class="conteudo_pagina_esquerda">
                        <p>
                            <label>Produtos</label>
                            <select name="produtos" id="produtos" style="width:180px;">
                                <?php
                                $strsql = "SELECT * FROM tabela_produtos WHERE produto_cesta = '1'";
                                $res = mysqli_query($conn, $strsql) or die(mysqli_error());

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
                        <p>
                            <label>Tipo de Dado</label>
                            <select name="tipo_dado" id="tipo_dado" style="width:180px;">
                                <option value="1">Gasto Mensal</option>
                                <option value="2">Tempo de Trabalho</option>
                                <option value="3">Pre&ccedil;o M&eacute;dio</option>
                            </select>
                            <span class="obrig">*</span>
                        </p>
                        <p>
                            <label>Per&iacute;odo</label>
                            <input type="text" name="data_inicial" id="data_inicial" value="mm/aaaa" onKeyPress="mascara(this,data)" size="7" maxlength="7" /><span class="obrig">*</span>
                            &nbsp;  at&eacute;&nbsp;&nbsp;
                            <input type="text" name="data_final" id="data_final" value="<?php echo date("m/Y"); ?>" size="7" onKeyPress="mascara(this,data)" maxlength="7" /><span class="obrig">*</span>
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
                            <label for="cidade">Cidade</label>
                            <select name="cidade_id" id="cidade_id" multiple="multiple" size="6" style="width:140px;">
<?php
                                $strsql = "SELECT * FROM tabela_cidades";
                                $cidades = mysqli_query($conn, $strsql) or die(mysqli_error());

                                if ($cidades && mysqli_num_rows($cidades) > 0)
                                    while ($row = mysqli_fetch_array($cidades)) {
?>

                                        <option value="<?php echo($row['cidade_id']); ?>" > <?php echo ($row['cidade_nome']); ?></option>

                                <?php
                                    }
                                ?>

                            </select>
                            <span class="obrig">*</span>
                        </p>
                    </div>
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
        var url = 'res_consulta_por_produto.php?';
        var prod = document.getElementById("produtos").value;
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
	
        params = 'hcit=' + getSelectedItem() + '&hp=' + prod + "&hdti=" + dt_ini + '&hdtf=' + dt_fim + '&htd=' + tp_dado;
        url += params;
	
        window.open(url,'','scrollbars=yes,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,copyhistory=no,height=' + altura + ',width=' + largura + ',top='+meio1+',left='+meio2+'');
        return false;
	
    }
	
    function getSelectedItem()
    {

        var cidades = document.getElementById("cidade_id");
        var tam = cidades.length;
        var i = 0;
        var aux = "";

        for (i = 0; i < tam; i++)
        {
            if (cidades[i].selected)
                aux = aux + cidades[i].value + "/";
	
        }


        return aux.substring(0,aux.length-1);
    }
	
</script>
