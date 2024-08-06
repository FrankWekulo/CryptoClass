<?php
session_start();
if ($_SESSION['role'] != 'Finance') {
    echo "Access denied.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Finance Interface</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchTransactions() {
                $.ajax({
                    url: 'fetch_transactions.php',
                    method: 'GET',
                    success: function(data) {
                        $('#transactionTable').html(data);
                    }
                });
            }

            fetchTransactions(); // Initial fetch

            $('#insertForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response);
                        fetchTransactions(); // Fetch updated transactions
                    }
                });
            });

            // Toggle form visibility
            $('.toggle-form').on('click', function() {
                var target = $(this).data('target');
                $('#' + target).toggleClass('hidden');
            });
        });
    </script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h2 class="text-center text-3xl font-bold text-gray-700 mb-6">Finance Interface</h2>

        <button data-target="insertForm" class="toggle-form bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4">Insert New Transaction</button>
        <form id="insertForm" action="insert_transaction.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 hidden">
            <div class="mb-4">
                <label for="customer_id" class="block text-gray-700 text-sm font-bold mb-2">Customer ID:</label>
                <input type="text" id="customer_id" name="customer_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="card_id" class="block text-gray-700 text-sm font-bold mb-2">Card ID:</label>
                <input type="text" id="card_id" name="card_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Amount:</label>
                <input type="text" id="amount" name="amount" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date:</label>
                <input type="date" id="date" name="date" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex items-center justify-between">
                <input type="submit" value="Insert Transaction" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            </div>
        </form>

        <h3 class="text-2xl font-semibold text-gray-600 mb-4">Transaction Details</h3>
        <div id="transactionTable">
            <?php
            $servername = "localhost";
            $username = "finance_user";
            $password = "finance_password";
            $dbname = "credit_card_vault";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM FinanceView";
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
        </div>

        <a href="logout.php" class="block text-center mt-6 text-blue-500 hover:underline">Logout</a>
    </div>
</body>
</html>
