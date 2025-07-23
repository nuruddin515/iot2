<div class="flex items-center justify-between flex-wrap gap-4">
  <!-- Country Dropdown -->
  <div>
    <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Select Country</label>
    <select id="country" onchange="onCountryChange(this)" class="border border-gray-300 rounded px-4 py-2 text-gray-700 focus:outline-none focus:ring focus:border-blue-300">
      <option value="germany">Germany</option>
      <option value="usa">USA</option>
      <option value="france">France</option>
    </select>
  </div>

  <!-- Login Button -->
  <div>
    <a href="login.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded transition duration-200 shadow">
      Login
    </a>
  </div>
</div>
