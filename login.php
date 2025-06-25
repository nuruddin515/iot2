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
  <script>
    tailwind.config = {
      theme: {
        extend: {
          backgroundImage: {
            'tech': "url('technology.png')"
          }
        }
      }
    };
  </script>
  <style>
    /* Fallback for blurred glass effect */
    .glass {
      background-color: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    }
  </style>
</head>
<body class="bg-tech bg-cover bg-center flex items-center justify-center h-screen relative">

<!-- Overlay for darkening the background slightly -->
<div class="absolute inset-0 bg-black bg-opacity-50"></div>

<!-- Login Card -->
<div class="relative z-10 glass p-8 rounded-2xl shadow-2xl w-full max-w-sm animate-fade-in text-white">
  <h2 class="text-3xl font-extrabold text-center mb-6">Login to SenseBox</h2>

  <?php if (isset($_GET['error'])): ?>
    <p class="text-red-300 text-center mb-4 font-medium"><?= htmlspecialchars($_GET['error']) ?></p>
  <?php endif; ?>

  <form action="process_login.php" method="POST" class="space-y-4" autocomplete="off">
    <div>
      <label for="email" class="block text-sm font-medium text-gray-100">Email</label>
      <input type="email" id="email" name="email" required
             class="mt-1 block w-full p-2 rounded bg-white/80 text-black focus:ring-2 focus:ring-blue-400 focus:outline-none">
    </div>

    <div>
      <label for="password" class="block text-sm font-medium text-gray-100">Password</label>
      <input type="password" id="password" name="password" required
             class="mt-1 block w-full p-2 rounded bg-white/80 text-black focus:ring-2 focus:ring-blue-400 focus:outline-none">
    </div>

    <div>
      <label for="token" class="block text-sm font-medium text-gray-100">Login Token</label>
      <input type="text" id="token" name="token" required
             class="mt-1 block w-full p-2 rounded bg-white/80 text-black focus:ring-2 focus:ring-green-400 focus:outline-none"
             placeholder="Enter your token">
    </div>

    <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg font-semibold transition duration-200 shadow-md">
      Login
    </button>
  </form>

  <div class="text-center mt-4">
    <a href="signup.php" class="text-blue-200 hover:underline">Don't have an account? Sign up</a>
  </div>
</div>

<!-- Tailwind animation (optional) -->
<script>
  document.querySelector('.animate-fade-in')?.classList.add('opacity-0');
  setTimeout(() => {
    document.querySelector('.animate-fade-in')?.classList.remove('opacity-0');
  }, 100);
</script>

</body>
</html>
