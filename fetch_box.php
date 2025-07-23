<?php
header('Content-Type: application/json');

// Map countries to their SenseBox IDs
$boxes = [
    'germany' => '65980e43649ae60007c5e32d',
    'usa'     => '652af158b3491b0007c9d93f',
    'france'  => '5cf00d6830705e001aa7cbef'
];

// Get country from query param, default to germany
$country = $_GET['country'] ?? 'germany';
$boxId = $boxes[$country] ?? $boxes['germany'];

// Fetch data from OpenSenseMap API
$boxUrl = "https://api.opensensemap.org/boxes/$boxId";

$response = @file_get_contents($boxUrl);
if ($response === FALSE) {
    echo json_encode(['error' => 'Failed to fetch data from OpenSenseMap API.']);
    exit;
}

$boxData = json_decode($response, true);

// Prepare response data
$name = $boxData['name'] ?? 'Unknown SenseBox';
$description = $boxData['description'] ?? 'No description available.';
$loc = $boxData['currentLocation']['coordinates'] ?? [0, 0];
$sensors = $boxData['sensors'] ?? [];

$sensorNames = [];
$sensorValues = [];

foreach ($sensors as $sensor) {
    $sensorNames[] = $sensor['title'];
    $lastValue = $sensor['lastMeasurement']['value'] ?? null;

    if (is_numeric($lastValue)) {
        $sensorValues[] = floatval($lastValue);
    } else {
        $sensorValues[] = 0; // fallback for non-numeric or missing values
    }
}

// Send structured JSON
echo json_encode([
    'name' => $name,
    'description' => $description,
    'longitude' => $loc[0],
    'latitude' => $loc[1],
    'sensorNames' => $sensorNames,
    'sensorValues' => $sensorValues
]);
