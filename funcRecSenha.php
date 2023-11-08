<?php
include_once("./sendEmailSenha.php");
include_once("./libs/mysql.lib");
 $usermail = trim($_POST['usermail']);
if($usermail!=''){

	 $sql = sprintf("SELECT * FROM `tabela_usuarios` WHERE usuario_email='%s'",  mysqli_real_escape_string($conn, $usermail));
	 
	 $sql_query = mysqli_query($conn, $sql);
	 
	if($sql_query && mysqli_num_rows($sql_query) == 1)
	{
	   $tabel = mysqli_fetch_array($sql_query);
	   
	   $usermail  = $tabel['usuario_email'];
	   $username  = $tabel['usuario_nome'];
	   $usersenha = $tabel['usuario_senha'];
	   if(send_email_senha($username, $usermail, $usersenha)){
               echo "Email de recupera&ccedil;&atilde;o de senha enviado para ".$usermail." !";
           }
           else{
               echo "N&atilde;o foi poss&iacute;vel contactar o servidor de e-mail !";
           }
	}
	else{
  	  echo  "Email n&atilde;o cadastrado no sistema!";
        }
}
else{
    echo "Deve digitar um e-mail v&aacute;lido!";
}

?>
