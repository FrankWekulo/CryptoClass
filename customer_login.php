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

// Authenticate customer
$sql = "SELECT * FROM Customers WHERE Username = ? AND Password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $input_username, $hashed_password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $customer = $result->fetch_assoc();
    
    // Start session and store customer details
    session_start();
    $_SESSION['customer_id'] = $customer['CustomerID'];
    $_SESSION['username'] = $input_username;

    // Redirect to customer dashboard
    header("Location: customer_dashboard.php");
    exit;
} else {
    echo "Invalid username or password.";
}

$stmt->close();
$conn->close();
?>
