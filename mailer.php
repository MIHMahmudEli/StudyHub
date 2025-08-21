<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendOTP($email, $name, $otp) {
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'noreply.studyhubteam@gmail.com';
        $mail->Password   = 'nlfmxiavepbvssky';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('noreply.studyhubteam@gmail.com', 'StudyHub');
        $mail->addAddress($email, $name);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'StudyHub - OTP Verification';

        $mail->Body = "
        <div style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; text-align: center;'>
            <div style='max-width: 500px; margin: auto; background: white; padding: 25px; border-radius: 10px; box-shadow: 0px 4px 15px rgba(0,0,0,0.15);'>
                <h2 style='color: #007BFF;'>🔑 Verify Your Email</h2>
                <p style='color: #333; font-size: 16px;'>Hello <b>$name</b>,</p>
                <p style='color: #555; font-size: 14px;'>Thank you for registering with <b>StudyHub</b>. To complete your registration and verify your email, please use the OTP code below:</p>

                <div style='background: #28a745; color: white; font-size: 24px; font-weight: bold; padding: 15px; margin: 20px auto; width: 200px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);'>
                    $otp
                </div>

                <p style='color: #777; font-size: 12px;'>This OTP is valid for 10 minutes. If you did not request this, you can safely ignore this email.</p>
                <hr style='border: 0; height: 1px; background: #ddd; margin: 20px 0;'>
                <p style='color: #888; font-size: 12px;'>Best Regards,<br><b>StudyHub Team</b></p>
            </div>
        </div>
        ";

        $mail->AltBody = "Hello $name, Your StudyHub OTP code is: $otp. This OTP is valid for 10 minutes.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
