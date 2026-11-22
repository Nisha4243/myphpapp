<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

function sendJson(int $statusCode, array $payload): never
{
    http_response_code($statusCode);
    echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJson(405, ['error' => 'Method not allowed. Use POST.']);
}

$rawBody = file_get_contents('php://input');
$data = json_decode($rawBody ?: '', true);

if (!is_array($data)) {
    sendJson(400, ['error' => 'Invalid JSON payload.']);
}

$name = trim((string) ($data['name'] ?? ''));
$email = filter_var(trim((string) ($data['email'] ?? '')), FILTER_VALIDATE_EMAIL);

if ($name === '' || mb_strlen($name) > 100) {
    sendJson(422, ['error' => 'Name is required and must be 100 characters or fewer.']);
}

if ($email === false) {
    sendJson(422, ['error' => 'A valid email address is required.']);
}

try {
    $dsn = 'mysql:host=127.0.0.1;dbname=app_db;charset=utf8mb4';
    $pdo = new PDO($dsn, 'db_user', 'db_password', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $stmt = $pdo->prepare(
        'INSERT INTO users (name, email, created_at) VALUES (:name, :email, NOW())'
    );
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
    ]);

    sendJson(201, [
        'message' => 'User created successfully.',
        'user' => [
            'id' => (int) $pdo->lastInsertId(),
            'name' => $name,
            'email' => $email,
        ],
    ]);
} catch (PDOException $e) {
    // Log $e->getMessage() securely on the server rather than exposing SQL details
    error_log('Database error: ' . $e->getMessage());
    sendJson(500, ['error' => 'Internal server error.']);
}
