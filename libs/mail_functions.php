<?php

require_once('classes/PHPMailer/class.phpmailer.php');
require_once("classes/PHPMailer/class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

function send_smtp_mail($email, $assunto, $mensagem) {
    $mail = new PHPMailer();
    
    $mail->CharSet = "utf-8";
    //$mail->AddCustomHeader('Content-type: text/html; charset=UTF-8');
    
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = "nbcgib.uesc.br"; // SMTP server
    //$mail->SMTPDebug = 2;                     // enables SMTP debug information (for testing)
    // 1 = errors and messages
    // 2 = messages only
    $mail->SMTPAuth = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "ssl";              // sets the prefix to the servier
    $mail->Host = "nbcgib.uesc.br"; // sets the SMTP server
    $mail->Port = 465;                    // set the SMTP port for the GMAIL server
    $mail->Username = "cesta_basica@nbcgib.uesc.br"; // SMTP account username
    $mail->Password = "Ji9Fed7o";        // SMTP account password

    $mail->SetFrom('cesta_basica@nbcgib.uesc.br', 'Sistema ACCB');

    $mail->AddReplyTo("noreply@nbcgib.uesc.br", "Não Responda");


    $mail->Subject = $assunto;

    //$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
    $mail->WordWrap = 50; // set word wrap

    $mail->MsgHTML($mensagem);

    $address = $email;
    $mail->AddAddress($address);

    return $mail->Send();
}








?>