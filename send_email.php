    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require 'vendor/autoload.php'; // Loads PHPMailer

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Debug output to ensure the form is reaching this file
        // Remove or comment out echo statements once debugging is complete.
        echo "Form data received.<br>";

        // Retrieve and sanitize form fields
        $name    = strip_tags(trim($_POST['name']));
        $email   = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $phone   = strip_tags(trim($_POST['phone']));
        $message = strip_tags(trim($_POST['message']));

        // Basic validation
        if (empty($name) || empty($email) || empty($phone) || empty($message)) {
            echo 'Please fill in all required fields.';
            exit;
        }

        $mail = new PHPMailer(true);

        try {
            // Optional debugging (set to 0 or remove in production)
            $mail->SMTPDebug = 0;
            
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'iam.chel1021@gmail.com'; // Your Gmail address
            $mail->Password   = 'npon jezx baiw jboe';      // Your Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Recipients   
            $mail->setFrom('iam.chel1021@gmail.com', 'Chel');
            $mail->addAddress('iam.chel1021@gmail.com', 'Rachelle');

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Contact Form Submission from ' . $name;
            $emailBody  = '<h2>New Contact Form Submission</h2>';
            $emailBody .= '<p><strong>Name:</strong> ' . $name . '</p>';
            $emailBody .= '<p><strong>Email:</strong> ' . $email . '</p>';
            $emailBody .= '<p><strong>Phone:</strong> ' . $phone . '</p>';
            $emailBody .= '<p><strong>Message:</strong><br>' . nl2br($message) . '</p>';
            $mail->Body = $emailBody;

            $mail->send();
            echo '✅ Message sent successfully!';
        } catch (Exception $e) {
            echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo 'Invalid request method.';
    }
    ?>
