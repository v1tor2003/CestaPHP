<?php
error_reporting(E_ERROR | E_PARSE);
$haction = $_POST['haction'];
if($haction){
    //echo 'Em desenvolvimento...' ; exit;
    
    $id    = (int)$_POST['hid'];
    
    
    if($haction=='save'){
        $nome  = $_POST['membroequipe_nome'];
        $email = $_POST['membroequipe_email'];
        $funcao_id = $_POST['membroequipe_funcao'];
        $mostrar_home = (int)$_POST['mostrar_home'];
        $mostrar_contatos = (int)$_POST['mostrar_contatos'];
        
        if($id){
            $SQL_save = "UPDATE tabela_equipe SET nome_completo='$nome', email='$email', funcao_id='$funcao_id', mostrar_home='$mostrar_home', mostrar_contatos='$mostrar_contatos' WHERE id='$id'";
            $SQL_saveres  = mysqli_query($conn, $SQL_save);
            if($SQL_saveres)
            $save_edit_msg = 'Membro da equipe alterado com sucesso';
        }
        else{
            $SQL_insert = "INSERT INTO tabela_equipe (nome_completo, email, funcao_id, mostrar_home, mostrar_contatos) VALUES ('$nome','$email','$funcao_id','$mostrar_home','$mostrar_contatos')";
            $SQL_insertres = mysqli_query($conn, $SQL_insert);
            if($SQL_insertres)
            $save_edit_msg = 'Membro da equipe adicionado com sucesso';
        }
    }
    else if ($haction == 'edit'){
        $SQL_edit = "SELECT * FROM tabela_equipe WHERE id='$id'";
        $SQL_editres = mysqli_query($conn, $SQL_edit);
        if($SQL_editres){
            $membroequipe = mysqli_fetch_assoc($SQL_editres);
        }
    }
    else if($haction == 'del'){
        $SQL_del = "DELETE FROM tabela_equipe WHERE id='$id'";
        $SQL_delres = @mysqli_query($conn, $SQL_del);
        if($SQL_delres){
            $save_edit_msg = 'Membro da equipe deletado com sucesso!';
        }
    }
    
}
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
					
					<?php 
						$SQL_Equipe = "SELECT eq.*, ef.id as ef_id, ef.funcao FROM tabela_equipe eq,  tabela_equipe_funcoes ef WHERE eq.funcao_id = ef.id ORDER BY nome_completo";
                                                $Equipe_res = mysqli_query($conn, $SQL_Equipe);
							
					?>
					
					<h1 id="Mcaption" style="text-align:left">Cadastro da Equipe</h1>
                                        <?php
                                            if($save_edit_msg){
                                                echo '<p>'.$save_edit_msg.'</p>';
                                            }
                                        ?>
					<table cellspacing="0" id="listTable" summary="Tabela da Equipe" style="width:523px;">
						<!--colgroup>
							<col id="nome_completo" />
							<col id="acoes" />
						</colgroup-->		
						<thead>
							<tr>
								<th scope="col" class="tdboder">Nome Completo</th>
								<th scope="col" class="tdboder">Email</th>
                                                                <th scope="col" class="tdboder">Função</th>
                                                                <th scope="col" class="tdboder">Exibe em Home</th>
                                                                <th scope="col" class="tdboder">Exibe em Contatos</th>
								<th scope="col" class="tdboder">A&ccedil;&otilde;es</th>
							</tr>
						</thead>
					
					<?php
						
						while ($row = mysqli_fetch_array($Equipe_res))
						{
							if($l_cor == '') $l_cor = "par"; else $l_cor = "";
					?>
					
						<tr class="<?php echo ($l_cor);?>">
							<td class="tdboder"><?php echo($row['nome_completo']); ?></td>
							<td class="tdboder"><?php echo($row['email']); ?></td>
                                                        <td class="tdboder"><?php echo($row['funcao']); ?></td>
                                                        <td class="tdboder"><?php echo ($row['mostrar_home'])?"Sim" :"N&atilde;o"; ?></td>
                                                        <td class="tdboder"><?php echo ($row['mostrar_contatos'])?"Sim" :"N&atilde;o"; ?></td>
							<td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($row['id']); ?>','edit', '','','');"><img src="images/botao_editar.png" border="0"></a></td>
							<?php 
                                                            /*<td class="tdboderCod"><a href="javascript: " onClick="return submit_Action('<?php echo($row['id']); ?>','del','<?php echo($row['nome_completo']); ?>','Deseja apagar o membro da equipe','');"><img src="images/botao_deletar.png" border="0"></a></td>
                                                             */
                                                        ?>
						</tr>
					
					<?php	   
						 }//do while
					?>
					
					</table>	
					
						 
								
					<fieldset>
			
						<legend><?php if($membroequipe['id']) {?>Editar<?php } else {?>Adicionar<?php }?> Membro da Equipe</legend>
						
						
			
					<form name="form_cadastro" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>" style="width:500px;">
						
						
						<input type="hidden" name="hid" value="<?php echo ($membroequipe['id'])?$membroequipe['id']:''; ?>" />
						
			
						<p>
							<label for="membroequipe_nome">Nome:</label>
							<input type="text" maxlength="64" name="membroequipe_nome" size="30" value="<?php echo($membroequipe['nome_completo']); ?>" />
							<span class="obrig">*</span>
							<input type="hidden" name="haction" value="save"/>
						</p>
						<p>
						<label for="membroequipe_email">Email:</label>
						<input type="text" maxlength="64" name="membroequipe_email" size="30" value="<?php echo($membroequipe['email']); ?>" />
							<span class="obrig">*</span>
						</p>
                                                <p>
                                                    <label for="membroequipe_funcao">Função:</label>
                                                    <select id="membroequipe_funcao" name="membroequipe_funcao">
                                                        <!--option value=""> -- Selecione a Fun&ccedil;&atilde;o --</option-->
                                                        <?php
                                                            $membroequipe_funcoes_sql = "SELECT * FROM tabela_equipe_funcoes ORDER BY funcao";
                                                            $membroequipe_funcoes_res = mysqli_query($conn, $membroequipe_funcoes_sql);
                                                            while($memfunc_row = mysqli_fetch_assoc($membroequipe_funcoes_res)){
                                                                echo "<option value='".$memfunc_row['id']."' ".(($membroequipe['funcao_id']==$memfunc_row['id'])?"selected":"").">".$memfunc_row['funcao']."</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </p>
                                                <p>
                                                    <label for="mostrar_home">Mostrar na p&aacute;gina inicial:</label>
                                                    <input type="checkbox" id="mostrar_home" name="mostrar_home" value='1' <?php if(isset($membroequipe['mostrar_home'])){ echo ($membroequipe['mostrar_home'])?"checked='checked'":"";}else echo 'checked="checked"';?>/>
                                                </p>
                                                <p>
                                                    <label for="mostrar_contatos">Mostrar na p&aacute;gina de contatos:</label>
                                                    <input type="checkbox" id="mostrar_contatos" name="mostrar_contatos" value='1' <?php if(isset($membroequipe['mostrar_contatos'])){ echo ($membroequipe['mostrar_contatos'])?"checked='checked'":"";}else echo 'checked="checked"';?>/>
                                                </p>
						<p>
							<input type="submit" class="botao_submit" value="<?php if($membroequipe['id']) {?>Salvar<?php } else {?>Adicionar<?php }?>" size="40" />
							<?php if($membroequipe['id'] != ''){?>
							<input type="button" value="Cancelar" class="botao_cancelar" onclick="javascript: go_Page('cadastro_equipe.php');"/>
							<?php }?>
						</p>
						<p class="obrig" align="right" style="color:#FF0000;margin-left:10px;">* Campos obrigat&oacute;rios&nbsp;&nbsp;&nbsp;</p>
					</form> 
				</fieldset>			
			
			</div>
			
			
			<div class="clearer"><span></span></div>

			<div class="rodape">&nbsp </div>
			
			<!-- Mensagem de erro exibida caso o usuário queira cadastrar um dado que já existe no BD -->
			<?php if($herr != ''){ ?>
				<script type="text/javascript" language="javascript">
					alert('<?php echo($herr);?>');
				</script>
			<?php } ?> 

		</div>
		
		<form name="frm_send_data" method="post" action="">
			<input type="hidden" name="haction" value=""/>
			<input type="hidden" name="hid" value=""/>
		</form>
		
		<script language="javascript" type="text/javascript">
			var frm_validator = new Validator("form_cadastro");
			frm_validator.addValidation("membroequipe_nome","required","O campo NOME n�o pode ficar em branco!");
                        frm_validator.addValidation("membroequipe_funcao","required","O campo FUNCAO n�o pode ficar em branco!");
			frm_validator.addValidation("membroequipe_email","email","O campo EMAIL est� inv�lido!");
		</script>
	</body>
</html>