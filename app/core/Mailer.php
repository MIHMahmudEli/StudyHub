<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../app/lib/PHPMailer/src/Exception.php';
require_once '../app/lib/PHPMailer/src/PHPMailer.php';
require_once '../app/lib/PHPMailer/src/SMTP.php';

class Mailer {
    public static function sendOTP($email, $name, $otp, $type) {
        $mail = new PHPMailer(true);

        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'studyhubteam.official@gmail.com';
            $mail->Password   = 'flalqppbuuqrrkxd';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Sender and recipient
            $mail->setFrom('studyhubteam.official@gmail.com', 'StudyHub');
            $mail->addAddress($email, $name);
            $mail->isHTML(true);

            // Determine email type
            $messageBody = "";
            $headerText = "";
            $otpBoxColor = "";

            if ($type == 'register') {
                $mail->Subject = 'StudyHub - OTP Verification';
                $messageBody = "<p>Thank you for registering with <b>StudyHub</b>. Use the OTP below to verify your email:</p>";
                $otpBoxColor = '#28a745'; // green
                $headerText = '🔑 OTP Verification';
            } else { // forgot password
                $mail->Subject = 'StudyHub - Password Reset OTP';
                $messageBody = "<p>You requested a password reset for <b>StudyHub</b>. Please use the OTP below to reset your password:</p>";
                $otpBoxColor = '#dc3545'; // red
                $headerText = '🔑 Password Reset OTP';
            }

            // HTML body
            $mail->Body = "
            <div style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; text-align: center;'>
                <div style='max-width: 500px; margin: auto; background: white; padding: 25px; border-radius: 10px; box-shadow: 0px 4px 15px rgba(0,0,0,0.15);'>
                    <h2 style='color: #007BFF;'>$headerText</h2>
                    <p style='color: #333; font-size: 16px;'>Hello <b>$name</b>,</p>
                    $messageBody
                    <div style='background: $otpBoxColor; color: white; font-size: 24px; font-weight: bold; padding: 15px; margin: 20px auto; width: 200px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);'>
                        $otp
                    </div>
                    <p style='color: #777; font-size: 12px;'>This OTP is valid for 10 minutes. If you did not request this, you can safely ignore this email.</p>
                    <hr style='border: 0; height: 1px; background: #ddd; margin: 20px 0;'>
                    <p style='color: #888; font-size: 12px;'>Best Regards,<br><b>StudyHub Team</b></p>
                </div>
            </div>";

            // Plain-text body
            $mail->AltBody = "Hello $name,\n\n$messageBody\n\nYour OTP code is: $otp\n\nThis OTP is valid for 10 minutes. If you did not request this, you can safely ignore this email.\n\nBest regards,\nStudyHub Team";

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log("Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }

    public function sendCertificate($user, $type, $rank, $attachmentPath = null) {
        $mail = new PHPMailer(true);
        $name = htmlspecialchars($user['name']);
        $email = $user['email'];
        $date = date('F d, Y');
        
        $awardTitle = ($type === 'student') ? "Academic Excellence" : "Exceptional Contributor";
        $medalIcon = ($rank == 1) ? "🥇" : (($rank == 2) ? "🥈" : "🥉");

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'studyhubteam.official@gmail.com';
            $mail->Password   = 'flalqppbuuqrrkxd';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('studyhubteam.official@gmail.com', 'StudyHub Awards');
            $mail->addAddress($email, $name);
            
            // Add attachment if provided
            if ($attachmentPath && file_exists($attachmentPath)) {
                $mail->addAttachment($attachmentPath, "StudyHub_Certificate_" . str_replace(" ", "_", $user['name']) . ".pdf");
            }

            $mail->isHTML(true);
            $mail->Subject = "Official Achievement Certificate - StudyHub ($awardTitle)";

            $mail->Body = "
            <div style='background-color: #f4f1ea; padding: 40px 10px; font-family: \"Times New Roman\", serif;'>
                <div style='max-width: 600px; margin: auto; background: #fff9f0; border: 2px solid #b8860b; padding: 5px; box-shadow: 0 15px 35px rgba(0,0,0,0.1);'>
                    <div style='border: 1px solid #d4af37; padding: 30px; text-align: center; background-color: #ffffff;'>
                        
                        <div style='margin-bottom: 20px;'>
                           <span style='border-bottom: 2px solid #1e293b; color: #1e293b; font-size: 20px; font-weight: bold; text-transform: uppercase; letter-spacing: 4px;'>StudyHub Academic Board</span>
                        </div>

                        <div style='color: #b8860b; font-size: 10px; letter-spacing: 5px; text-transform: uppercase; font-weight: bold; margin-bottom: 15px;'>Recognition of Excellence</div>
                        
                        <h1 style='color: #1e293b; font-size: 42px; margin: 0 0 5px 0; font-weight: normal; text-transform: uppercase; letter-spacing: 6px;'>Certificate</h1>
                        <h2 style='color: #475569; font-size: 20px; margin: 0 0 30px 0; font-weight: normal; font-style: italic; border-bottom: 1px solid #d4af37; display: inline-block; padding-bottom: 5px;'>of Professional Excellence</h2>
                        
                        <p style='color: #64748b; font-size: 15px; margin-bottom: 8px; font-style: italic;'>Presented with honor to</p>
                        <h3 style='color: #1e293b; font-size: 32px; margin: 0 0 25px 0; font-weight: bold; border-bottom: 1px solid #1e293b; display: inline-block; padding-bottom: 3px;'>$name</h3>
                        
                        <div style='margin-bottom: 30px;'>
                            <p style='color: #b8860b; font-size: 22px; font-weight: bold; margin: 0 0 8px 0;'>$awardTitle $medalIcon</p>
                            <p style='color: #475569; font-size: 14px; line-height: 1.6; max-width: 480px; margin: auto;'>Congratulations! Your official achievement certificate is attached to this email as a PDF document.</p>
                        </div>
                        
                        <div style='margin-bottom: 35px; border-top: 1px solid #d4af37; padding-top: 20px; display: table; width: 100%; border-collapse: collapse;'>
                            <div style='display: table-cell; text-align: left; width: 45%;'>
                                <div style='border-bottom: 1px solid #1e293b; color: #1e293b; font-size: 12px; font-weight: bold; font-style: italic;'>Academic Director</div>
                                <div style='font-size: 9px; color: #94a3b8; margin-top: 3px;'>OFFICIAL VALIDATION</div>
                            </div>
                            <div style='display: table-cell; text-align: center; vertical-align: bottom; font-size: 28px;'>🏅</div>
                            <div style='display: table-cell; text-align: right; width: 45%;'>
                                <div style='border-bottom: 1px solid #1e293b; color: #1e293b; font-size: 12px; font-weight: bold;'>$date</div>
                                <div style='font-size: 9px; color: #94a3b8; margin-top: 3px;'>DATE OF ISSUANCE</div>
                            </div>
                        </div>

                        <p style='color: #64748b; font-size: 12px; margin-top: 20px;'>Please keep this document for your professional records.</p>
                    </div>
                </div>
                <div style='text-align: center; margin-top: 30px; color: #94a3b8; font-size: 10px; text-transform: uppercase; letter-spacing: 1px;'>
                    Credential ID: " . strtoupper(substr(md5($user['id'] . $date), 0, 16)) . "
                </div>
            </div>";

            $mail->AltBody = "Congratulations $name!\n\nYou have been awarded the StudyHub Achievement Certificate for: $awardTitle ($medalIcon).\nDate: $date\nYour official certificate is attached to this email as a PDF document.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Award Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
