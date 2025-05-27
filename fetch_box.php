<?php
header('Content-Type: application/json');

$boxId = '603bbc5eaf6fbf001b07a8e9';
$url = "https://api.opensensemap.org/boxes/{$boxId}";

$response = @file_get_contents($url);
if ($response === false) {
  echo json_encode(['error' => 'Failed to fetch data']);
  exit;
}

$data = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
  echo json_encode(['error' => 'Invalid JSON response']);
  exit;
}

$result = [
  'name'        => $data['name'] ?? 'N/A',
  'description' => $data['description'] ?? 'N/A',
  'longitude'   => $data['currentLocation']['coordinates'][0] ?? 0,
  'latitude'    => $data['currentLocation']['coordinates'][1] ?? 0,
  'sensorNames' => [],
  'sensorValues'=> []
];

foreach ($data['sensors'] as $sensor) {
  $result['sensorNames'][]  = $sensor['title'] ?? 'Unknown';
  $result['sensorValues'][] = $sensor['lastMeasurement']['value'] ?? 0;
}

echo json_encode($result);
