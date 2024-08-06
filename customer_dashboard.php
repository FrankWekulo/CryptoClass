<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "credit_card_vault";
$encryption_key = "encryption_key"; // Use the same key used during encryption

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve customer details
$customer_id = $_SESSION['customer_id'];
$sql = "SELECT Name, Email, Phone, AES_DECRYPT(Token, ?) AS DecryptedCardNumber, ExpiryDate, TransactionID, Amount, Date FROM AdminView WHERE CustID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $encryption_key, $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="style.css"> <!-- Ensure this path is correct -->
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($customer['Name']); ?></h2>
	<a href="customer_logout.php">Logout</a>
        <table>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($customer['Email']); ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?php echo htmlspecialchars($customer['Phone']); ?></td>
            </tr>
            <tr>
                <th>Credit Card Number</th>
                <td><?php echo htmlspecialchars($customer['DecryptedCardNumber']); ?></td>
            </tr>
            <tr>
                <th>Expiry Date</th>
                <td><?php echo htmlspecialchars($customer['ExpiryDate']); ?></td>
            </tr>
            <tr>
                <th>Transaction ID</th>
                <td><?php echo htmlspecialchars($customer['TransactionID']); ?></td>
            </tr>
            <tr>
                <th>Amount</th>
                <td><?php echo htmlspecialchars($customer['Amount']); ?></td>
            </tr>
            <tr>
                <th>Date</th>
                <td><?php echo htmlspecialchars($customer['Date']); ?></td>
            </tr>
        </table>
	<a href="update_customer.html">Update Your Details</a>
    </div>
</body>
</html>
