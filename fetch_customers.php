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

$sql = "SELECT * FROM AdminView";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<div class="overflow-x-auto"><table class="min-w-full bg-white border">';
    echo '<thead><tr>';
    while ($field = $result->fetch_field()) {
        echo '<th class="py-2 px-4 border">' . htmlspecialchars($field->name) . '</th>';
    }
    echo '</tr></thead>';
    echo '<tbody>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        foreach ($row as $data) {
            echo '<td class="py-2 px-4 border">' . htmlspecialchars($data) . '</td>';
        }
        echo '</tr>';
    }
    echo '</tbody></table></div>';
} else {
    echo '<p class="text-center text-gray-600">No data available.</p>';
}

$conn->close();
?>
