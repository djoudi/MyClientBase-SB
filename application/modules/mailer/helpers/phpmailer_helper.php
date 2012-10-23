<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function phpmail_send($from, $to, $subject, $message, $attachment_path = NULL, $cc = NULL, $bcc = NULL) {

	require_once(APPPATH . 'modules/mailer/helpers/phpmailer/class.phpmailer.php');

	$CI =& get_instance();

	$mail = new PHPMailer();

	$mail->CharSet = 'UTF-8';

	$mail->IsHtml();

	if ($CI->mcbsb->settings->setting('email_protocol') == 'smtp') {

		$mail->IsSMTP();

		$mail->SMTPAuth = true;

		if ($CI->mcbsb->settings->setting('smtp_security')) {

			$mail->SMTPSecure = $CI->mcbsb->settings->setting('smtp_security');

		}

		$mail->Host = $CI->mcbsb->settings->setting('smtp_host');

		$mail->Port = $CI->mcbsb->settings->setting('smtp_port');

		$mail->Username = $CI->mcbsb->settings->setting('smtp_user');

		$mail->Password = $CI->mcbsb->settings->setting('smtp_pass');

	}

	elseif ($CI->mcbsb->settings->setting('email_protocol') == 'sendmail') {

		$mail->IsSendmail();

	}

	if (is_array($from)) {

		$mail->SetFrom($from[0], $from[1]);

	}

	else {

		$mail->SetFrom($from);

	}

	$mail->Subject = $subject;

	$mail->Body = $message;

	$to = (strpos($to, ',')) ? explode(',', $to) : explode(';', $to);

	foreach ($to as $address) {

		$mail->AddAddress($address);

	}

	if ($cc) {

		$cc = (strpos($cc, ',')) ? explode(',', $cc) : explode(';', $cc);

		foreach ($cc as $address) {

			$mail->AddCC($address);

		}

	}

	if ($bcc) {

		$bcc = (strpos($bcc, ',')) ? explode(',', $bcc) : explode(';', $bcc);

		foreach ($bcc as $address) {

			$mail->AddBCC($address);

		}

	}

	if ($attachment_path) {

		$mail->AddAttachment($attachment_path);

	}

	if ($mail->Send()) {

		$CI->session->set_flashdata('custom_success', $CI->lang->line('email_success'));

		return TRUE;

	}

	else {

		$CI->session->set_flashdata('custom_error', $mail->ErrorInfo);

		return FALSE;
		
	}

}

?>