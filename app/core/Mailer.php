<?php
namespace App\Core;

class Mailer {
    /**
     * Get the configured admin email address
     */
    public static function getAdminEmail() {
        try {
            $db = Database::getInstance();
            $row = $db->fetchOnePrepared("SELECT email FROM admin WHERE email IS NOT NULL AND email != '' LIMIT 1");
            if (!empty($row['email'])) {
                return trim($row['email']);
            }
            $row2 = $db->fetchOnePrepared("SELECT contact_email FROM footer_contact LIMIT 1");
            if (!empty($row2['contact_email'])) {
                return trim($row2['contact_email']);
            }
        } catch (\Throwable $e) {
            // fallback
        }
        return 'support@karudacomputers.com';
    }

    /**
     * Send HTML email with SMTP (PHPMailer) when configured, or fallback to mail() + file logging
     */
    public static function send($to, $subject, $htmlBody, $replyTo = null) {
        $fromEmail = (defined('SMTP_USER') && !empty(SMTP_USER)) ? SMTP_USER : 'support@karudacomputers.com';
        $fromName = defined('SMTP_FROM_NAME') ? SMTP_FROM_NAME : 'Karuda Computers';

        // 1. If SMTP credentials (Mail ID & Passkey) are configured in app/config/config.php, use PHPMailer
        if (defined('SMTP_USER') && !empty(SMTP_USER) && defined('SMTP_PASS') && !empty(SMTP_PASS)) {
            try {
                $autoloadPath = dirname(__DIR__, 2) . '/vendor/autoload.php';
                if (file_exists($autoloadPath)) {
                    require_once $autoloadPath;
                }
                if (class_exists('\\PHPMailer\\PHPMailer\\PHPMailer')) {
                    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = defined('SMTP_HOST') ? SMTP_HOST : 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = trim(SMTP_USER);
                    $mail->Password = trim(SMTP_PASS);
                    $sec = defined('SMTP_SECURE') ? strtolower(SMTP_SECURE) : 'tls';
                    $mail->SMTPSecure = ($sec === 'ssl') ? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = defined('SMTP_PORT') ? (int)SMTP_PORT : 587;
                    $mail->setFrom(SMTP_USER, $fromName);
                    $mail->addAddress($to);
                    if ($replyTo) {
                        $mail->addReplyTo($replyTo);
                    }
                    $mail->isHTML(true);
                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = $subject;
                    $mail->Body = $htmlBody;
                    $sent = $mail->send();
                    self::logMail($to, $subject, $htmlBody, true, 'SMTP_DELIVERED');
                    return true;
                }
            } catch (\Throwable $e) {
                self::logMail($to, $subject, $htmlBody, false, 'SMTP_FAIL: ' . $e->getMessage());
            }
        }

        // 2. Standard PHP mail fallback
        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = "From: $fromName <$fromEmail>";
        if ($replyTo) {
            $headers[] = "Reply-To: $replyTo";
        } else {
            $headers[] = "Reply-To: $fromEmail";
        }
        $headers[] = "X-Mailer: PHP/" . phpversion();

        $headerStr = implode("\r\n", $headers);
        $sent = @mail($to, $subject, $htmlBody, $headerStr);

        // Always log outgoing email for local dev & audit verification
        self::logMail($to, $subject, $htmlBody, $sent, $sent ? 'MAIL_SENT' : 'LOCAL_QUEUED');

        return $sent;
    }

    /**
     * Send notification for new contact inquiry
     */
    public static function sendContactNotification($name, $email, $mobile, $message) {
        $adminEmail = self::getAdminEmail();
        $date = date('d-m-Y H:i:s');

        // 1. Email to Admin
        $adminSubject = "New Contact Inquiry from $name - Karuda Computers";
        $adminBody = self::buildEmailTemplate(
            "New Customer Inquiry Received",
            "A customer has submitted a new inquiry via the Karuda Computers contact form. Details are below:",
            [
                'Customer Name' => htmlspecialchars($name),
                'Email Address' => htmlspecialchars($email),
                'Phone / WhatsApp' => htmlspecialchars($mobile),
                'Inquiry Details' => nl2br(htmlspecialchars($message)),
                'Submission Time' => $date
            ]
        );
        self::send($adminEmail, $adminSubject, $adminBody, $email);

        // 2. Acknowledgment Email to Customer
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $userSubject = "Thank You for Contacting Karuda Computers!";
            $userBody = self::buildEmailTemplate(
                "We Received Your Message, $name!",
                "Thank you for contacting Karuda Computers. Our technical support team has received your inquiry and will contact you within 2-4 business hours.",
                [
                    'Your Name' => htmlspecialchars($name),
                    'Contact Phone' => htmlspecialchars($mobile),
                    'Your Message' => nl2br(htmlspecialchars($message)),
                    'Ticket Time' => $date
                ],
                "Need immediate urgent assistance? Call our direct hotline: <strong>+91 98765 43210</strong>."
            );
            self::send($email, $userSubject, $userBody, $adminEmail);
        }

        return true;
    }

    /**
     * Send notification for newsletter subscription
     */
    public static function sendNewsletterNotification($subscriberEmail) {
        $adminEmail = self::getAdminEmail();
        $date = date('d-m-Y H:i:s');

        // 1. Email to Admin
        $adminSubject = "New Newsletter Subscriber - Karuda Computers";
        $adminBody = self::buildEmailTemplate(
            "New Newsletter Subscriber Joined",
            "A new user has subscribed to the Karuda Computers Tech Club newsletter via the website.",
            [
                'Subscriber Email' => htmlspecialchars($subscriberEmail),
                'Subscribed On' => $date,
                'Source' => 'Website Footer Newsletter Bar'
            ]
        );
        self::send($adminEmail, $adminSubject, $adminBody, $subscriberEmail);

        // 2. Welcome Email to Subscriber
        if (!empty($subscriberEmail) && filter_var($subscriberEmail, FILTER_VALIDATE_EMAIL)) {
            $userSubject = "Welcome to Karuda Computers Tech Club!";
            $userBody = self::buildEmailTemplate(
                "Welcome to Karuda Computers VIP Club! 🎉",
                "You're officially on the insider list! As a subscriber, you'll be the first to know about exclusive flash deals, GPU drops, custom gaming PC builds, and member-only coupons.",
                [
                    'Your Registered Email' => htmlspecialchars($subscriberEmail),
                    'Member Status' => 'Active VIP Member',
                    'Joined Date' => $date
                ],
                "Visit our online store anytime at <a href='http://localhost/karudaCom/allproducts.php' style='color: #003B95; font-weight: 700;'>Karuda Computers Store</a>."
            );
            self::send($subscriberEmail, $userSubject, $userBody, $adminEmail);
        }

        return true;
    }

    /**
     * Responsive, branded HTML email template generator themed with #003B95 and #002566
     */
    private static function buildEmailTemplate($heading, $leadText, array $details, $footerNote = '') {
        $rowsHtml = '';
        foreach ($details as $label => $val) {
            $rowsHtml .= "
                <tr>
                    <td style='padding: 11px 16px; background: #F8FAFC; border-bottom: 1px solid #E2E8F0; font-weight: 700; color: #002566; width: 35%; font-size: 13.5px;'>$label</td>
                    <td style='padding: 11px 16px; background: #FFFFFF; border-bottom: 1px solid #E2E8F0; color: #334155; font-size: 14px;'>$val</td>
                </tr>
            ";
        }

        $footerNoteHtml = $footerNote ? "<p style='margin-top: 22px; font-size: 13.5px; color: #002566; background: #EEF4FF; padding: 14px 18px; border-radius: 8px; border-left: 4px solid #003B95;'>$footerNote</p>" : '';

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        </head>
        <body style='margin: 0; padding: 25px 0; background: #F1F5F9; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif;'>
            <table role='presentation' width='100%' cellspacing='0' cellpadding='0' border='0'>
                <tr>
                    <td align='center'>
                        <table role='presentation' width='600' style='max-width: 600px; width: 100%; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0, 37, 102, 0.12); border: 1px solid #CBD5E1;' cellspacing='0' cellpadding='0' border='0'>
                            
                            <!-- Header Strip (Brand Theme #003B95 to #002566) -->
                            <tr>
                                <td style='background: linear-gradient(135deg, #003B95 0%, #002566 100%); padding: 28px 30px; text-align: center; border-bottom: 3px solid #002566;'>
                                    <h1 style='margin: 0; font-size: 25px; font-weight: 900; color: #ffffff; letter-spacing: 1.5px; text-transform: uppercase;'>
                                        KARUDA <span style='color: #60A5FA;'>COMPUTERS</span>
                                    </h1>
                                    <p style='margin: 6px 0 0; color: #DBEAFE; font-size: 12px; letter-spacing: 2px; text-transform: uppercase;'>Premium Computers, Custom PCs & Hardware</p>
                                </td>
                            </tr>

                            <!-- Body Content -->
                            <tr>
                                <td style='padding: 32px 30px;'>
                                    <h2 style='margin: 0 0 12px; font-size: 20px; color: #002566; font-weight: 800;'>$heading</h2>
                                    <p style='margin: 0 0 22px; font-size: 14.5px; color: #475569; line-height: 1.6;'>$leadText</p>

                                    <!-- Details Table -->
                                    <table width='100%' cellspacing='0' cellpadding='0' style='border-collapse: collapse; border-radius: 10px; overflow: hidden; border: 1px solid #E2E8F0;'>
                                        $rowsHtml
                                    </table>

                                    $footerNoteHtml
                                </td>
                            </tr>

                            <!-- Footer -->
                            <tr>
                                <td style='background: #002566; padding: 18px 30px; text-align: center;'>
                                    <p style='margin: 0; font-size: 12px; color: #DBEAFE;'>
                                        © " . date('Y') . " Karuda Computers. All Rights Reserved.<br>
                                        Helpline: +91 98765 43210 | support@karudacomputers.com
                                    </p>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        ";
    }

    /**
     * File log helper for debugging and audit verification
     */
    private static function logMail($to, $subject, $body, $status, $statusText = 'STATUS') {
        $logDir = dirname(__DIR__, 2) . '/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }
        $logFile = $logDir . '/mail_notifications.log';
        $entry = "[" . date('Y-m-d H:i:s') . "] TO: $to | SUBJECT: $subject | STATUS: $statusText\n";
        @file_put_contents($logFile, $entry, FILE_APPEND);
    }
}
