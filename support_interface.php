<?php
session_start();
if ($_SESSION['role'] != 'Support') {
    echo "Access denied.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Support Interface</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h2 class="text-center text-3xl font-bold text-gray-700 mb-6">Support Interface</h2>

        <h3 class="text-2xl font-semibold text-gray-600 mb-4">Customer Details</h3>
        <?php
        $servername = "localhost";
        $username = "support_user";
        $password = "support_password";
        $dbname = "credit_card_vault";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM SupportView";
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

        <a href="logout.php" class="block text-center mt-6 text-blue-500 hover:underline">Logout</a>
    </div>
</body>
</html>
