<?php

require_once("mail_functions.php");

function send_email_senha($username, $useremail, $usersenha) {
    $to = $useremail;
    $subject = 'Cesta Básica: Recuperação de Senha';
    $message =
            '<html><body>
	Sr(a) <b>' . $username . '</b>, <br />
	Seu usuário e senha no sistema ACCB - Acompanhamento da Cesta Básica<br />
	Usuário: <a href="mailto:' . $useremail . '">' . $useremail . '</a><br />
	Senha: ' . $usersenha . '<br />
	<br />
	Para quaisquer dúvidas e/ou sugestões entre em contato com
	os desenvolvedores.
	<br />
	<a href="http://nbcgib.uesc.br/cesta" >http://nbcgib.uesc.br/cesta</a>
	<br />
	Este email foi enviado automáticamente, por favor não o responda!
	</body></html>
	';

    /*
      // To send HTML mail, the Content-type header must be set
      $headers  = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
      $headers .= 'Date: '.gmdate("D, d M Y H:i:s",time())." GMT". "\r\n";
      $headers .= 'From: www-data@nbcgib.uesc.br' . "\n";

      $headers .='Reply-to: noreply@nbcgib.uesc.br'."\r\n";
      //$headers .='Vagner Luz do Carmo <vluzrmos@gmail.com>'."\r\n";
      return @mail($to, $subject, $message, $headers);

     */
    
    return send_smtp_mail($to, $subject, $message);
}

?>