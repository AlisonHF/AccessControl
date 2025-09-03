<?php

namespace src\Utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class RecoveryEmail{

    private $email;

    private $dest;

    private $cod;

    public function __construct(string $sendDest, int $Reccod)
    {
        $this->dest = $sendDest;

        $this->cod = $Reccod;

        $this->email = new PHPMailer(true);

        $this->email->isSMTP(); // SMTP

        $this->email->SMTPAuth = true; // Autenticação SMTP

        $this->email->Username   = 'alison.teste.email@gmail.com'; // E-mail utilizado para envio 

        $this->email->Password   = 'mxbfkhbmjilcirkd '; // Senha do e-mail

        $this->email->SMTPSecure = 'tls'; 

        $this->email->Host = 'smtp.gmail.com'; // SMTP do e-mail usado para envio

        $this->email->Port = 587;

        $this->email->CharSet = 'UTF-8';

        $this->email->isHTML(true); // Se enviará mensagens em HTML

        $this->email->setFrom('alison.teste.email@gmail.com', 'AcessControl'); // Remetente

        $this->email->addAddress($sendDest);

    }

    public function sendEmail()
    {
        $this->email->Subject = 'Código de recuperação';

        $this->email->Body = 'Recebemos um pedido de redefinição de sua senha do seu acesso ao aplicativo AccessControl.<br/>Por favor, use o código abaixo para redefinir sua senha:';

        $this->email->Body .= "<br><h1>$this->cod</h1>";

        //$this->email->AltBody = 'Este é o cortpo da mensagem para clientes de e-mail que não reconhecem HTML';

        $this->email->send();

    }

}

?>