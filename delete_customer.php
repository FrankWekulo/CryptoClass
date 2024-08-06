<?php
// Database connection
$servername = "localhost";
$username = "admin";
$password = "admin_password";
$dbname = "credit_card_vault";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$customer_id = $_POST['customer_id'];

$conn->autocommit(FALSE); // Start transaction

try {
    // Delete from Transactions
    $sql = "DELETE FROM Transactions WHERE CardID IN (SELECT CardID FROM CreditCards WHERE CustomerID=?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);

    if (!$stmt->execute()) {
        throw new Exception("Error: " . $stmt->error);
    }
    $stmt->close();

    // Delete from CreditCards
    $sql = "DELETE FROM CreditCards WHERE CustomerID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);

    if (!$stmt->execute()) {
        throw new Exception("Error: " . $stmt->error);
    }
    $stmt->close();

    // Delete from Customers
    $sql = "DELETE FROM Customers WHERE CustomerID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);

    if (!$stmt->execute()) {
        throw new Exception("Error: " . $stmt->error);
    }
    $stmt->close();

    $conn->commit(); // Commit transaction
    echo "Customer and related details deleted successfully.";
} catch (Exception $e) {
    $conn->rollback(); // Rollback transaction on error
    echo $e->getMessage();
}

$conn->close();
?>
