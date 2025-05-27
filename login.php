<?php
session_start();

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
  <title>Login - SenseBox Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="bg-white p-8 rounded-xl shadow-md w-full max-w-sm">
  <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Login to SenseBox</h2>

  <?php
  if (isset($_GET['error'])) {
      echo "<p class='text-red-600 text-center mb-4'>" . htmlspecialchars($_GET['error']) . "</p>";
  }
  ?>

  <form action="process_login.php" method="POST" class="space-y-4">
    <div>
      <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
      <input type="email" id="email" name="email" required
             class="mt-1 block w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">
    </div>

    <div>
      <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
      <input type="password" id="password" name="password" required
             class="mt-1 block w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">
    </div>

    <button type="submit"
            class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700 transition duration-200">
      Login
    </button>
  </form>

  <div class="text-center mt-4">
    <a href="signup.php" class="inline-block bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition duration-200">
      Sign Up
    </a>
  </div>
</div>

</body>
</html>
