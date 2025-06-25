<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'config.php'; // database connection

$successMessage = $errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userEmail = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if (!$userEmail) {
        $errorMessage = "Please enter a valid email address.";
    } else {
        // Fetch the latest unused token
        $stmt = $pdo->prepare("SELECT id, token FROM token WHERE remarks = 'unused' ORDER BY id ASC LIMIT 1");
        $stmt->execute();
        $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($tokenData) {
            $token = $tokenData['token'];
            $tokenId = $tokenData['id'];

            $mail = new PHPMailer(true);
            try {
                // SMTP settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'dinbhrddin1214@gmail.com';
                $mail->Password = 'jefm ekxe xxcg zgwp'; // App password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('dinbhrddin1214@gmail.com', 'Token Request System');
                $mail->addAddress($userEmail);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Your Registration Token';
                $mail->Body    = "Thank you for your request. Here is your registration token: <strong>$token</strong>";
                $mail->AltBody = "Thank you for your request. Your registration token: $token";

                $mail->send();

                // Mark token as used
                $updateStmt = $pdo->prepare("UPDATE token SET remarks = 'used' WHERE id = :id");
                $updateStmt->execute(['id' => $tokenId]);

                $successMessage = "Your token has been sent to your email.";
            } catch (Exception $e) {
                $errorMessage = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $errorMessage = "No available tokens at the moment. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Request Token</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

  <div class="bg-white p-8 rounded shadow-md w-full max-w-sm">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Request Registration Token</h2>

    <?php if ($successMessage): ?>
      <p class="text-green-600 text-center mb-4"><?= htmlspecialchars($successMessage) ?></p>
    <?php elseif ($errorMessage): ?>
      <p class="text-red-600 text-center mb-4"><?= htmlspecialchars($errorMessage) ?></p>
    <?php endif; ?>

    <form action="" method="POST" class="space-y-4">
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Your Email</label>
        <input type="email" id="email" name="email" required
               class="mt-1 block w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">
      </div>

      <button type="submit"
              class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700 transition duration-200">
        Send Request
      </button>
    </form>

    <div class="text-center mt-4">
      <a href="signup.php" class="text-blue-600 hover:underline">Back to Sign Up</a>
    </div>
  </div>

</body>
</html>
