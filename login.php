<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "credit_card_vault";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// User input
$input_username = $_POST['username'];
$input_password = $_POST['password'];

// Hash the input password
$hashed_password = hash('sha256', $input_password);

// Authenticate user
$sql = "SELECT * FROM Users WHERE Username = ? AND Password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $input_username, $hashed_password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $role = $user['Role'];
    
    // Start session and store user role
    session_start();
    $_SESSION['role'] = $role;

    // Redirect based on role
    if ($role == 'Admin') {
        header("Location: admin_interface.php");
    } elseif ($role == 'Finance') {
        header("Location: finance_interface.php");
    } elseif ($role == 'Support') {
        header("Location: support_interface.php");
    } else {
        echo "No access.";
    }
    exit;
} else {
    echo "Invalid username or password.";
}

$stmt->close();
$conn->close();
?>
