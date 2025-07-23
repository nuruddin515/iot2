<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SenseBox — Live Data by Country</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body class="min-h-screen py-8 bg-gray-100">
<div class="container mx-auto px-4">

  <div class="bg-white p-6 rounded-lg shadow mb-6">
    <h1 class="text-3xl font-bold mb-2">SenseBox Live Data</h1>
    <p class="text-gray-600 mb-2">Auto-refresh every <span id="countdown" class="font-semibold">15</span> seconds.</p>
    
    <!-- Country Dropdown -->
    <?php include 'menu.php'; ?>
  </div>

  <div id="boxContent">
    <div class="text-gray-500">Loading data...</div>
  </div>

</div>

<script>
let sensorChart = null;
let map = null;
let marker = null;
let countdown = 15;
let selectedCountry = 'germany';

// Countdown timer and data fetcher
function startAutoRefresh() {
  setInterval(() => {
    countdown--;
    document.getElementById('countdown').textContent = countdown;

    if (countdown <= 0) {
      fetchData(selectedCountry);
      countdown = 15;
    }
  }, 1000);
}

// Handle country change
function onCountryChange(select) {
  selectedCountry = select.value;
  fetchData(selectedCountry);
}

// Fetch API data from fetch_box.php?country=
function fetchData(country) {
  fetch(`fetch_box.php?country=${country}`)
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        document.getElementById('boxContent').innerHTML = `<div class="text-red-600">${data.error}</div>`;
        return;
      }
      updatePage(data);
    })
    .catch(err => {
      console.error('Fetch error:', err);
      document.getElementById('boxContent').innerHTML = `<div class="text-red-600">Error fetching data</div>`;
    });
}

// Update page content
function updatePage(data) {
  const content = `
    <div class="bg-white p-6 rounded-lg shadow mb-6">
      <h2 class="text-2xl font-semibold mb-2">${data.name}</h2>
      <p class="text-gray-600 mb-1"><strong>Description:</strong> ${data.description}</p>
      <p class="text-gray-600"><strong>Longitude:</strong> ${data.longitude}</p>
      <p class="text-gray-600"><strong>Latitude:</strong> ${data.latitude}</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow mb-6">
      <h2 class="text-2xl font-semibold mb-4">Sensor Data (Latest Values)</h2>
      <canvas id="sensorChart" class="w-full h-64"></canvas>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
      <h2 class="text-2xl font-semibold mb-4">Location Map</h2>
      <div id="map" class="w-full h-96 rounded"></div>
    </div>
  `;

  document.getElementById('boxContent').innerHTML = content;

  // Chart.js
  const ctx = document.getElementById('sensorChart').getContext('2d');
  if (sensorChart) sensorChart.destroy();
  sensorChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: data.sensorNames,
      datasets: [{
        label: 'Latest Sensor Values',
        data: data.sensorValues,
        backgroundColor: 'rgba(75, 192, 192, 0.6)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: { y: { beginAtZero: true } }
    }
  });

  // Leaflet map
  if (map) map.remove();
  map = L.map('map').setView([data.latitude, data.longitude], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
  }).addTo(map);
  marker = L.marker([data.latitude, data.longitude]).addTo(map)
    .bindPopup(data.name)
    .openPopup();
  map.whenReady(() => map.invalidateSize());
}

// Initial fetch and timer start
fetchData(selectedCountry);
startAutoRefresh();
</script>
</body>
</html>
