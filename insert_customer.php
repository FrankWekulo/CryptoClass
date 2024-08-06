<?php
include 'validateCreditCard.php';
include 'isCreditCardUnique.php';

// Database connection
$servername = "localhost";
$username = "admin";
$password = "admin_password";
$dbname = "credit_card_vault";
$encryption_key = "encryption_key"; // Use a secure key management practice

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert customer
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$username = $_POST['username'];
$password = $_POST['password'];
$card_number = $_POST['card_number'];
$expiry_date = $_POST['expiry_date'];

// Hash the password using SHA-256
$hashed_password = hash('sha256', $password);

// Initialize an error message variable
$error_message = "";

// Validate credit card number
if (!validateCreditCard($card_number)) {
    $error_message = "Invalid credit card number.";
}

// Check if credit card number is unique
if ($error_message == "" && !isCreditCardUnique($card_number, $conn, $encryption_key)) {
    $error_message = "Credit card number already exists.";
}

if ($error_message != "") {
    echo "<script>alert('$error_message');</script>";
} else {
    $conn->autocommit(FALSE); // Start transaction

    try {
        $sql = "INSERT INTO Customers (name, email, phone, username, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $email, $phone, $username, $hashed_password);

        if (!$stmt->execute()) {
            throw new Exception("Error: " . $stmt->error);
        }

        $customer_id = $stmt->insert_id; // Get the inserted customer ID
        $stmt->close();

        $sql = "INSERT INTO CreditCards (CustomerID, Token, ExpiryDate) VALUES (?, AES_ENCRYPT(?, ?), ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $customer_id, $card_number, $encryption_key, $expiry_date);

        if (!$stmt->execute()) {
            throw new Exception("Error: " . $stmt->error);
        }

        $conn->commit(); // Commit transaction
        echo "New customer and credit card inserted successfully.";
    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaction on error
        echo $e->getMessage();
    }

    $stmt->close();
}

$conn->close();
?>
