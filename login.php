<?php
session_start();
require 'db.php'; // This should connect to your MySQL DB

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize input
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // Prepare and execute query to fetch user
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    if (!$stmt) {
        echo json_encode([
            "status" => "error",
            "message" => "Database error: " . $conn->error
        ]);
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check user and password
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Match password (for demo using plain text — in real use password_hash and password_verify)
        if ($user['password'] === $password) {
            $_SESSION['user'] = [
                'fullname' => $user['fullname'],
                'email' => $user['email']
            ];

            echo json_encode([
                "status" => "success",
                "message" => "Login successful!",
                "fullname" => $user['fullname']
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Incorrect password."
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "User not found."
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request."
    ]);
}
?>
