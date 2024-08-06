<?php
// Database connection
$servername = "localhost";
$username = "finance_user";
$password = "finance_password";
$dbname = "credit_card_vault";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert transaction
$customer_id = $_POST['customer_id'];
$card_id = $_POST['card_id'];
$amount = $_POST['amount'];
$date = $_POST['date'];

$sql = "INSERT INTO Transactions (CustomerID, CardID, Amount, Date) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iids", $customer_id, $card_id, $amount, $date);

if ($stmt->execute()) {
    echo "New transaction inserted successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
