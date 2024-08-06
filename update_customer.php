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

// Update customer
$customer_id = $_POST['customer_id'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$username = $_POST['username'];
$password = $_POST['password'];
$card_number = $_POST['card_number'];
$expiry_date = $_POST['expiry_date'];

// Hash the password using SHA-256
$hashed_password = hash('sha256', $password);

// Validate credit card number
if (!validateCreditCard($card_number)) {
    die("Invalid credit card number.");
}

// Check if credit card number is unique
if (!isCreditCardUnique($card_number, $conn, $encryption_key)) {
    die("Credit card number already exists.");
}

$conn->autocommit(FALSE); // Start transaction

try {
    $sql = "UPDATE Customers SET Name=?, Email=?, Phone=?, Username=?, Password=? WHERE CustomerID=?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssssi", $name, $email, $phone, $username, $hashed_password, $customer_id);

        if (!$stmt->execute()) {
            throw new Exception("Error: " . $stmt->error);
        }
        $stmt->close();
    } else {
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    $sql = "UPDATE CreditCards SET Token=AES_ENCRYPT(?, ?), ExpiryDate=? WHERE CustomerID=?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssi", $card_number, $encryption_key, $expiry_date, $customer_id);

        if (!$stmt->execute()) {
            throw new Exception("Error: " . $stmt->error);
        }
        $stmt->close();
    } else {
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    $conn->commit(); // Commit transaction
    echo "Customer and credit card updated successfully.";
} catch (Exception $e) {
    $conn->rollback(); // Rollback transaction on error
    echo $e->getMessage();
}

$conn->close();
?>
