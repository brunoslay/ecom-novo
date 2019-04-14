<?php

namespace Hcode\Model;

use Rain\Tpl;

class Mailer {

	public const USERNAME = "slayscript@gmail.com";
	const PASSWORD = "!zero12345";
	const NAME_FROM = "Hcode Store";

	private $mail;

	public function __construct($toAddress, $toName, $subject, $tplName, $data = [])
	{

		$config = array(
			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/git/ecommerce"."/views/email/",
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/git/ecommerce/views-cache/",
			//"debug"         => true // set to false to improve the speed
	   );

		Tpl::configure( $config );

		$tpl = new Tpl;

		foreach ($data as $key => $value) {
			$tpl->assign($key, $value);
		}

		$html = $tpl->draw($tplName, true);


		$this->mail = new \PHPMailer;

		$this->mail->isSMTP();

		$this->mail->SMTPDebug = 2;

		$this->mail->Debugoutput = "html";

		$this->mail->Host = "smtp.gmail.com";

		$this->mail->Port = 587;

		$this->mail->SMTPSecure = "tls";

		$this->mail->SMTPAuth = true;

		$this->mail->Username = Mailer::USERNAME;

		$this->mail->Password = Mailer::PASSWORD;

		$this->mail->setFrom(Mailer::USERNAME, mAILER::NAME_FROM);

		$this->mail->addAddress($toAddress, $toName);

		$this->mail->Subject = $subject;

		$this->mail->msgHTML($html);

		$this->mail->AltBody = "This is a plan-text message body";

		

	}

	public function send(){
		return $this->mail->send();
	}
}

//

// require_once "vendor/autoload.php";

/*$mail = new PHPMailer;

$mail->isSMTP();

$mail->->SMTPDebug = 2;

$mail->Debugoutput = "html";

$mail->Host = "smtp.gmail.com";

$mail->Port = 587;

$mail->SMTPSecure = "tls";

$mail->SMTPAuth = true;

$mail->Username = "brunoslay2@gmail.com"

$mail->Password = "<?senha?>";

$mail->setFrom('brunoslay2@gmail.com', "Curso PHP 7");

$mail->addAddress("bruno.souza933@gmail.com", "Suporte Stay Code");

$mail->Subject = "Testando a Classe PHPMAILER com Gmail";

$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));

$mail->AltBody = "This is a plan-text message body";

if (!$meil->send()) {
	echo "Mailer Error: " . $mail->ErrorInfo;
} else {
	echo "Message sent successfull!!!";
}*/
