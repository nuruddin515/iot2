<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SenseBox Menu</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<nav class="bg-white shadow mb-8">
  <div class="container mx-auto px-4">
    <div class="flex items-center justify-between py-4">
      <div class="text-xl font-bold text-gray-800">🌍 SenseBox Dashboard</div>
      <div class="flex items-center space-x-4">
        <form method="get" action="box.php" class="flex items-center space-x-2">
          <label for="location" class="text-gray-600">Select Location:</label>
          <select name="location" id="location" onchange="this.form.submit()" class="border rounded p-2">
            <option value="Cambridge" <?php echo (isset($_GET['location']) && $_GET['location'] == 'Cambridge') ? 'selected' : ''; ?>>Cambridge</option>
            <option value="Germany" <?php echo (isset($_GET['location']) && $_GET['location'] == 'Germany') ? 'selected' : ''; ?>>Germany</option>
            <option value="France" <?php echo (isset($_GET['location']) && $_GET['location'] == 'France') ? 'selected' : ''; ?>>France</option>
            <option value="USA" <?php echo (isset($_GET['location']) && $_GET['location'] == 'USA') ? 'selected' : ''; ?>>USA</option>
          </select>
        </form>
        <a href="login.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-200">Login</a>
      </div>
    </div>
  </div>
</nav>

</body>
</html>
