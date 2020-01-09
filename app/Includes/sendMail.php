<?php

/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/16/2016
 * Time: 12:00 PM
 */
class Mail3dtf
{
    public function sendFromGmail($subject, $toEmail, $content)
    {
        $mail = new PHPMailer();
        /*=====================================
         * THIET LAP THONG TIN GUI MAIL
         *=====================================*/
        $mail->IsSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465;
        $mail->SMTPDebug = 1;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Username = "3dtfcom.project@gmail.com";
        $mail->Password = "3dtfproject3dtf";
        //Thiet lap thong tin nguoi gui va email nguoi gui
        $mail->SetFrom('3dtfcom.project@gmail.com', 'SERVICE FROM 3DTF.COM');

        $mail->AddAddress($toEmail, "3DTF.COM Group");
        //$mail->AddReplyTo("3dtfcom.project@gmail.com","Administrator");
        /*=====================================
         * THIET LAP NOI DUNG EMAIL
         *=====================================*/
        $mail->Subject = $subject;
        $mail->CharSet = "utf-8";
        $mail->Body = $content;
        return $mail->Send();
    }
}