<?php
// contact-process.php

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Collect and sanitize form data
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = filter_var(trim($_POST['message']), FILTER_SANITIZE_STRING);
    
    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }
    
    // If no errors, send email
    if (empty($errors)) {
        // Your email address (where you'll receive notifications)
        $to = "vasavi2972006@gmail.com";
        
        // Email subject - auto-generated
        $email_subject = "New Message from Portfolio: " . substr($message, 0, 50) . "...";
        
        // Email body
        $email_body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #6C63FF; color: white; padding: 15px; border-radius: 5px; }
                .content { background: #f9f9f9; padding: 20px; border-radius: 5px; margin-top: 10px; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #333; display: inline-block; width: 80px; }
                .message { background: white; padding: 15px; border-left: 4px solid #6C63FF; margin-top: 10px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>New Contact Form Submission</h2>
                    <p>From your portfolio website</p>
                </div>
                <div class='content'>
                    <div class='field'>
                        <span class='label'>Name:</span> $name
                    </div>
                    <div class='field'>
                        <span class='label'>Email:</span> <a href='mailto:$email'>$email</a>
                    </div>
                    <div class='field'>
                        <span class='label'>Time:</span> " . date('F j, Y, g:i a') . "
                    </div>
                    <div class='field'>
                        <span class='label'>Message:</span>
                        <div class='message'>$message</div>
                    </div>
                </div>
                <div style='margin-top: 20px; padding-top: 15px; border-top: 1px dashed #ddd; text-align: center; color: #666;'>
                    <p>This message was sent from your portfolio contact form.</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Email headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Vasavi Portfolio <no-reply@yourportfolio.com>" . "\r\n";
        $headers .= "Reply-To: $name <$email>" . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Send email
        if (mail($to, $email_subject, $email_body, $headers)) {
            // Success - redirect back with success message
            header('Location: contact.php?status=success');
            exit;
        } else {
            // Email failed
            header('Location: contact.php?status=mailerror');
            exit;
        }
    } else {
        // Validation errors
        $error_string = implode(', ', $errors);
        header('Location: contact.php?status=error&message=' . urlencode($error_string));
        exit;
    }
} else {
    // Not a POST request
    header('Location: contact.php');
    exit;
}
?>