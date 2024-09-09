<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$captcha_response = $_POST['g-recaptcha-response'];
$secret_key = "6LfRjAgqAAAAAG_lLTpevswiLK_rS17pfygQZPIT"; 
$verify_url = "https://www.google.com/recaptcha/api/siteverify";
$data = array(
    'secret' => $secret_key,
    'response' => $captcha_response
);
$options = array(
    'http' => array (
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$response = file_get_contents($verify_url, false, $context);
$response_keys = json_decode($response, true);

if($response_keys["success"]) {
    // reCAPTCHA verification successful, process your form data here
    // Example: echo "Form submitted successfully!";
} else {



// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if file is uploaded
    if(isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
        $attachment_tmp_name = $_FILES['attachment']['tmp_name'];
        $attachment_name = $_FILES['attachment']['name'];
    } else {
        $attachment_tmp_name = '';
        $attachment_name = '';
    }

    // Data from the form
    $firstName = $_POST['first_name'] ?? 'N/A';
    $userEmail = $_POST['email_id'] ?? 'no-reply@example.com';
    $comments = $_POST['comments'] ?? 'N/A';

    // Admin Email
    $adminEmail = 'isha220102@gmail.com';
    $adminSubject = 'New Enquiry Received';

    // Email to Admin
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'isha220102@gmail.com'; // Use your SMTP username
        $mail->Password = 'jxsn wcfy bfar egpv'; // Use your SMTP password or App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('isha220102@gmail.com', 'Pancholi Dhara');
        $mail->addAddress($adminEmail); // Admin email

        $mail->isHTML(true);
        $mail->Subject = $adminSubject;
        // The body content of the admin email goes here
        $mail->Body = '<!DOCTYPE html>
        <html>
        <head>
            <title> Mail to Adimin </title>
        </head>
        <body>
            <div bgcolor="#FFFFFF" marginwidth="0" marginheight="0">
                <table width="900" border="5" align="center" cellpadding="0" cellspacing="0" style="border-color: #0a0f4e; padding: 10px">
                    <tr>
                        <td>
                            <table width="900" style="padding: 5px">
                            <tbody>
                            <tr>
                                <td colspan="3">
        
                                    <img src="https://www.php.net/images/logos/new-php-logo.svg" alt="" title="" style="max-width: 200px" />
                                </td>
                            </tr>
                            <tr>
                            <td style="width: 100px" colspan="2">
                                <h3>Inquiry Details of:
                                     <label style="font-size: 14px; font-weight: bold">'.$firstName.'</label>
                                </h3>
                            </td>
                            <td style="width: 290px">
                                <h5 style="font-size: 15px; float: right; text-align: right">Date:&nbsp;'.date("d/m/Y").'</h5>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <hr />
                            </td>
        
                        </tr>
                        <tr>
                            <td>
                                <table width="780" style="padding-left: 10px">
                                    <tr>
                                        <td style="width: 460px">
                                            <span style="font-size: 14px; font-weight: bold;">Name</span>
                                        </td>
                                        <td style="width: 90px">
                                            <span style="font-size: 14px; font-weight: bold; margin-left: 10px;">:</span>
                                        </td>
                                        <td style="width: 3500px">
                                            <label style="font-size: 14px;">'.$firstName.'</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 460px">
                                            <span style="font-size: 14px; font-weight: bold">Email</span>
                                        </td>
                                        <td style="width: 90px">
                                            <span style="font-size: 14px; font-weight: bold; margin-left: 10px;">:</span>
                                        </td>
                                        <td style="width: 3500px">
                                            <label style="font-size: 14px;">'.$_POST['email_id'].'</label>
                                        </td>
                                    </tr>
                                     <tr>
                                        <td style="width: 460px">
                                            <span style="font-size: 14px; font-weight: bold">Comments</span>
                                        </td>
                                        <td style="width: 90px">
                                            <span style="font-size: 14px; font-weight: bold; margin-left: 10px;">:</span>
                                        </td>
                                        <td style="width: 3500px">
                                            <label style="font-size: 14px;">'.$_POST['comments'].'</label>
                                        </td>
                                    </tr>
        
                                    
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <hr />
                            </td>
                        </tr>
        
                        <tr>
                            <td colspan="3">
                                <h3>"Pancholi Dhara"</h3>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <span style="font-size: 11px; color: #545353">
                                    <b>Please do not reply to this email address as this is an automated email.</b></span>
                            </td>
                        </tr>
                            <!-- Rest of your email content here -->
                        </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </body>
        </html>';

        // Add attachment only for admin email
        if ($attachment_tmp_name != '') {
            $mail->addStringAttachment(file_get_contents($attachment_tmp_name), $attachment_name);
        }

        $mail->send();
        echo 'Admin message has been sent.';
    } catch (Exception $e) {
        echo 'Message could not be sent to admin. Mailer Error: ' . $mail->ErrorInfo;
    }

    // Confirmation Email to User
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'isha220102@gmail.com'; // Same SMTP username
        $mail->Password = 'jxsn wcfy bfar egpv'; // Same SMTP password or App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('isha220102@gmail.com', 'Pancholi Dhara');
        $mail->addAddress($userEmail, "$firstName"); // The user who filled the form

        $mail->isHTML(true);
        $mail->Subject = "Thank you for contacting Dhara!";
        // Here you can include the HTML content you've provided for the user email
        $mail->isHTML(true); // Tell PHPMailer to use HTML
        $mail->Body = '<!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <title> Mail to Client </title>
        </head>
        <body>
            <div bgcolor="#FFFFFF" marginwidth="0" marginheight="0">
                <table width="900" border="5" align="center" cellpadding="0" cellspacing="0" style="border-color: #0a0f4e; padding: 10px">
                    <tr>
                        <td>
                            <table width="900" style="padding: 5px">
                                <tbody>
                                    <tr>
                                        <td colspan="3">
        
                                            <img src="img/logo/logo.png" alt="" title="" style="max-width: 200px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100px" colspan="2">
                                            <h3>
                                              Dear <label style="font-size: 14px; font-weight: bold">'. $firstName .',</label>
                                            </h3>
                                        </td>
                                        <td style="width: 290px">
                                            <h5 style="font-size: 15px; float: right; text-align: right">Date:&nbsp;&nbsp;'.date("d/m/Y").'</h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <hr style="border-color:#0a0f4e;" />
                                        </td>
                                    </tr>
                                    <td colspan="3">
                                         Thank you for reaching out! I am excited to discuss how my expertise in 3D modeling and passion for both fantasy and reality can bring your project to life. Lets create something visually stunning together!<br /><br />
                                                I look forward to build a strong  association with you !
                                                <br /><br />
                                                Best Regards,<br /><br /><br />
                                             "Pancholi Dhara"
                                        </td>
                                     <tr>
                                        <td colspan="3">
                                               <hr style="border-color: #0a0f4e;" />
                                            <h3> "Pancholi Dhara".</h3>
        
        
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <span style="font-size: 11px; color: #0a0f4e">
                                                <b>Please do not reply to this email address as this is an automated email.</b></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
        
                        </td>
                    </tr>
                </table>
        
            </div>
        </body>
        </html>';

        // No attachment for user email

        $mail->send();
        echo 'Confirmation message has been sent to the user.';
    } catch (Exception $e) {
        echo 'Confirmation message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
    
// Confirmation Email to User
$mail = new PHPMailer(true);
try {
    // Your existing email sending code...

    // Redirect after sending email
    header('Location: index.html');
    exit; // Ensure script execution stops after redirection
} catch (Exception $e) {
    echo 'Confirmation message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
}
    // Delete the temporary uploaded file
    if ($attachment_tmp_name != '') {
        unlink($attachment_tmp_name);
    }
}

}
?>