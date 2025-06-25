<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$email = $_SESSION['user_email'];
$date = date('Y-m-d');
$time = date('H:i:s');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SenseBox Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            senseblue: '#1e3a8a',
            sensepurple: '#7c3aed',
            sensegreen: '#22c55e',
            senseorange: '#f97316',
            sensepink: '#ec4899',
            sensegray: '#1f2937'
          }
        }
      }
    }
  </script>
  <style>
    .dark .transition-bg {
      background: linear-gradient(to bottom right, #111827, #1f2937);
    }
    .transition-bg {
      background: linear-gradient(to bottom right, #eff6ff, #f9fafb);
    }
  </style>
</head>

<body class="relative text-gray-800 dark:text-gray-200 transition-colors duration-500">

<div class="flex h-screen">

  <!-- Sidebar Navigation -->
  <aside class="w-64 bg-gradient-to-b from-senseblue to-sensepurple text-white p-6 flex flex-col space-y-6 z-20">
    <div class="text-3xl font-bold">🌍 SenseBox</div>
    <nav class="flex flex-col space-y-4 text-lg mt-4">
      <a href="#" class="hover:text-sensegreen transition">📊 Dashboard</a>
      <a href="#" class="hover:text-sensegreen transition">🗺️ Floor Map</a>
      <a href="#" class="hover:text-sensegreen transition">⚙️ Settings</a>
    </nav>
    <div class="mt-auto text-sm">
      📅 <?php echo $date; ?><br>
      🕒 <?php echo $time; ?>
    </div>
  </aside>

  <!-- Background Image -->
  <div class="absolute inset-0 bg-cover bg-center opacity-20 dark:opacity-30 z-0"
       style="background-image: url('your-image-url.jpg');">
  </div>

  <!-- Main Content -->
  <main class="flex-1 p-8 overflow-auto space-y-8 relative z-10">

    <!-- Header -->
    <header class="flex justify-between items-center">
      <h1 class="text-3xl font-extrabold text-senseblue dark:text-sensegreen">📋 Control Dashboard</h1>
      <div class="flex items-center space-x-4">
        <button id="toggleDark" class="bg-sensepurple text-white px-3 py-1 rounded hover:bg-sensepink transition">🌓</button>
        <div class="font-semibold">🟢 Operational</div>
        <div class="font-medium">👤 <?php echo htmlspecialchars($email); ?></div>
        <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">Logout</a>
      </div>
    </header>

    <!-- Sensor Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="p-6 bg-white dark:bg-sensegray rounded-2xl shadow-lg text-center space-y-2 hover:scale-105 transition">
        <div class="text-5xl">🌡️</div>
        <div class="text-xl font-bold">Temperature</div>
        <div class="text-2xl text-sensepink">24°C</div>
      </div>
      <div class="p-6 bg-white dark:bg-sensegray rounded-2xl shadow-lg text-center space-y-2 hover:scale-105 transition">
        <div class="text-5xl">⛽</div>
        <div class="text-xl font-bold">Gas</div>
        <div class="text-2xl text-sensegreen">3 ppm</div>
      </div>
      <div class="p-6 bg-white dark:bg-sensegray rounded-2xl shadow-lg text-center space-y-2 hover:scale-105 transition">
        <div class="text-5xl">⚙️</div>
        <div class="text-xl font-bold">Pressure</div>
        <div class="text-2xl text-senseorange">150 PSI</div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="p-6 bg-white dark:bg-sensegray rounded-2xl shadow-lg">
        <h2 class="text-xl font-bold mb-4">📊 Sensor Status Overview</h2>
        <canvas id="statusChart"></canvas>
      </div>
      <div class="p-6 bg-white dark:bg-sensegray rounded-2xl shadow-lg">
        <h2 class="text-xl font-bold mb-4">📈 Data Trends</h2>
        <canvas id="trendChart"></canvas>
      </div>
    </div>

    <!-- Alerts Panel -->
    <div class="p-6 bg-white dark:bg-sensegray rounded-2xl shadow-lg">
      <h2 class="text-xl font-bold mb-4">📩 Alerts & Notifications</h2>
      <ul class="space-y-2 text-base">
        <li class="text-red-500">🔴 Gas level critical in Zone 3 (5 ppm at 14:03)</li>
        <li class="text-orange-400">🟠 Pressure high in Machine 5 (150 PSI at 13:59)</li>
      </ul>
    </div>

    <!-- LED Control Panel -->
    <div class="p-6 bg-white dark:bg-sensegray rounded-2xl shadow-lg">
      <h2 class="text-xl font-bold mb-4">💡 LED Control</h2>
      <div class="flex space-x-4">
        <button onclick="turnOn()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">Turn ON</button>
        <button onclick="turnOff()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Turn OFF</button>
      </div>
    </div>

    <!-- Factory Map -->
    <div class="p-6 bg-white dark:bg-sensegray rounded-2xl shadow-lg">
      <h2 class="text-xl font-bold mb-4">🗺️ Factory Floor Map</h2>
      <div class="h-64 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800 rounded flex items-center justify-center text-gray-600 dark:text-gray-400">
        [Interactive Map Placeholder]
      </div>
    </div>

  </main>

</div>

<!-- Chart.js Scripts -->
<script>
  const ctxStatus = document.getElementById('statusChart').getContext('2d');
  new Chart(ctxStatus, {
    type: 'doughnut',
    data: {
      labels: ['🟢 Normal', '🟠 Warning', '🔴 Critical'],
      datasets: [{
        data: [75, 15, 10],
        backgroundColor: ['#22c55e', '#facc15', '#ef4444']
      }]
    }
  });

  const ctxTrend = document.getElementById('trendChart').getContext('2d');
  new Chart(ctxTrend, {
    type: 'line',
    data: {
      labels: ['14:00', '14:05', '14:10', '14:15'],
      datasets: [
        {
          label: 'Temperature (°C)',
          data: [24, 25, 24.5, 26],
          borderColor: '#3b82f6',
          fill: false
        },
        {
          label: 'Pressure (PSI)',
          data: [145, 150, 148, 152],
          borderColor: '#f97316',
          fill: false
        },
        {
          label: 'Vibration (mm/s)',
          data: [3, 3.5, 3.8, 4],
          borderColor: '#22c55e',
          fill: false
        }
      ]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
  });

  // Dark Mode Toggle
  document.getElementById('toggleDark').addEventListener('click', () => {
    document.documentElement.classList.toggle('dark');
  });

  // LED Control Functions
  function turnOn() {
    fetch("http://192.168.137.144/off")
      .then(response => response.text())
      .then(data => console.log(data))
      .catch(error => console.error('Error:', error));
  }

  function turnOff() {
    fetch("http://192.168.137.144/on")
      .then(response => response.text())
      .then(data => console.log(data))
      .catch(error => console.error('Error:', error));
  }
</script>

</body>
</html>
