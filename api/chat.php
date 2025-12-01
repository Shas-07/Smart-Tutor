<?php
// AI Chat API Endpoint - Handles OpenRouter API calls
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/api.php';
require_once '../config/session.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized. Please login first.']);
    exit();
}

// Check if API key is configured
if (OPENROUTER_API_KEY === 'YOUR_OPENROUTER_API_KEY') {
    http_response_code(500);
    echo json_encode([
        'error' => 'API key not configured',
        'message' => 'Please configure your OpenRouter API key in config/api.php'
    ]);
    exit();
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['message']) || empty(trim($input['message']))) {
    http_response_code(400);
    echo json_encode(['error' => 'Message is required']);
    exit();
}

$user_message = trim($input['message']);

// Prepare the request to OpenRouter
$data = [
    'model' => OPENROUTER_MODEL,
    'messages' => [
        [
            'role' => 'system',
            'content' => AI_SYSTEM_PROMPT
        ],
        [
            'role' => 'user',
            'content' => $user_message
        ]
    ],
    'temperature' => 0.7,
    'max_tokens' => 500
];

// Initialize cURL
$ch = curl_init(OPENROUTER_API_URL);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . OPENROUTER_API_KEY,
        'HTTP-Referer: ' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'http://localhost'),
        'X-Title: Smart Tutor'
    ],
    CURLOPT_TIMEOUT => 30
]);

// Execute request
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);

curl_close($ch);

// Handle errors
if ($curl_error) {
    http_response_code(500);
    echo json_encode([
        'error' => 'API request failed',
        'message' => $curl_error
    ]);
    exit();
}

if ($http_code !== 200) {
    http_response_code($http_code);
    $error_data = json_decode($response, true);
    echo json_encode([
        'error' => 'API error',
        'message' => $error_data['error']['message'] ?? 'Unknown error',
        'code' => $http_code
    ]);
    exit();
}

// Parse response
$response_data = json_decode($response, true);

if (!isset($response_data['choices'][0]['message']['content'])) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Invalid API response',
        'message' => 'No content in response'
    ]);
    exit();
}

// Return the AI response
echo json_encode([
    'success' => true,
    'message' => $response_data['choices'][0]['message']['content']
]);

?>

