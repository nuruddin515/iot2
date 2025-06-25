<?php
session_start();

// Security headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up - SenseBox Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<!-- Token Modal -->
<div id="tokenModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 flex items-center justify-center z-50" role="dialog" aria-modal="true" aria-labelledby="tokenTitle">
  <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-xs text-center">
    <h2 id="tokenTitle" class="text-xl font-bold mb-4 text-gray-800">Enter Registration Token</h2>
    <input type="text" id="popupTokenInput" placeholder="Enter token here"
           class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none mb-4">
    <button onclick="validateToken()"
            class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700 transition duration-200 mb-2">
      Submit
    </button>
    <a href="request_token.php" target="_blank"
       class="inline-block w-full bg-gray-200 text-gray-700 p-2 rounded hover:bg-gray-300 transition duration-200">
      Request a Token
    </a>
    <p id="tokenError" class="text-red-600 text-sm mt-2 hidden">Invalid or used token. Please try again.</p>
  </div>
</div>

<!-- Sign Up Form -->
<div class="bg-white p-8 rounded-xl shadow-md w-full max-w-sm hidden" id="signupFormContainer">
  <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Create Account</h2>

  <?php if (isset($_GET['error'])): ?>
    <p class="text-red-600 text-center mb-4">
      <?= htmlspecialchars($_GET['error']) ?>
    </p>
  <?php endif; ?>

  <form action="process_signup.php" method="POST" class="space-y-4" autocomplete="off">
    <div>
      <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
      <input type="email" id="email" name="email" required
             class="mt-1 block w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">
    </div>

    <div>
      <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
      <input type="password" id="password" name="password" required minlength="6"
             class="mt-1 block w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none"
             placeholder="At least 6 characters">
    </div>

    <div>
      <label for="token" class="block text-sm font-medium text-gray-700">Registration Token</label>
      <input type="text" id="token" name="token" readonly
             class="mt-1 block w-full p-2 border border-gray-300 rounded bg-gray-100 focus:outline-none"
             placeholder="Verified token">
    </div>

    <button type="submit"
            class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700 transition duration-200">
      Sign Up
    </button>
  </form>

  <div class="text-center mt-4">
    <a href="login.php" class="text-blue-600 hover:underline">Already have an account? Log in</a>
  </div>
</div>

<!-- AJAX Token Check -->
<script>
function validateToken() {
  const token = $("#popupTokenInput").val().trim();
  if (!token) {
    $("#tokenError").text("Token is required.").removeClass("hidden");
    return;
  }

  $.ajax({
    url: "check_token.php",
    type: "POST",
    data: { token: token },
    success: function(response) {
      if (response.trim() === "valid") {
        $("#tokenModal").addClass("hidden");
        $("#signupFormContainer").removeClass("hidden");
        $("#token").val(token);
        $("#tokenError").addClass("hidden");
      } else {
        $("#tokenError").text("Invalid or used token. Please try again.").removeClass("hidden");
      }
    },
    error: function() {
      $("#tokenError").text("Server error. Please try again later.").removeClass("hidden");
    }
  });
}
</script>

</body>
</html>
