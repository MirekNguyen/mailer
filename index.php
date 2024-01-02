<?php

namespace App;

use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

header("Access-Control-Allow-Origin: *");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $message = htmlspecialchars($_POST["message"]);
        $subject = htmlspecialchars($_POST["subject"]);
        $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
        if (!$email) {
            throw new Exception("Invalid email");
        }
        if (empty($message) || empty($subject) || empty($email)) {
            throw new Exception("Missing required fields");
        }

        if (
            empty($_ENV["MAILER_HOST"])
            || empty($_ENV["MAILER_USER"])
            || empty($_ENV["MAILER_PASS"])
            || empty($_ENV["MAILER_EMAIL_TO"])
            || empty($_ENV["MAILER_PORT"])
        ) {
            throw new Exception("Environment variables not set");
        }
        $mailer = new Mailer();
        $mailer->connect(
            host: $_ENV["MAILER_HOST"],
            username: $_ENV["MAILER_USER"],
            password: $_ENV["MAILER_PASS"],
            port: $_ENV["MAILER_PORT"],
        );
        $mailer->send(
            message: $message,
            subject: $subject,
            fromEmail: $email,
            toEmail: $_ENV["MAILER_EMAIL_TO"],
        );
        header("Content-Type: application/json");
        echo json_encode([
            "status" => "success",
            "message" => "Email sent successfully",
        ]);
    } catch (\Exception $e) {
        header("Content-Type: application/json");
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage(),
        ]);
    }
} else {
    http_response_code(400);
    echo "Method not allowed";
}
