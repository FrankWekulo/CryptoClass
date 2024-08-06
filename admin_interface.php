<?php
session_start();
if ($_SESSION['role'] != 'Admin') {
    echo "Access denied.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Interface</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js" defer></script>
<style>
        .hidden {
            display: none;
        }
        form {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        form:not(.hidden) {
            max-height: 1000px; /* Adjust this value based on your largest form's height */
            transition: max-height 0.5s ease-in;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h2 class="text-center text-3xl font-bold text-gray-700 mb-6">Admin Interface</h2>

        <button data-target="insertForm" class="toggle-form bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4">Insert New Customer</button>
<form id="insertForm" action="insert_customer.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 hidden">
    <div class="mb-4">
        <label for="name_insert" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
        <input type="text" id="name_insert" name="name" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="mb-4">
        <label for="email_insert" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
        <input type="email" id="email_insert" name="email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="mb-4">
        <label for="phone_insert" class="block text-gray-700 text-sm font-bold mb-2">Phone:</label>
        <input type="text" id="phone_insert" name="phone" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="mb-4">
        <label for="username_insert" class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
        <input type="text" id="username_insert" name="username" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="mb-4">
        <label for="password_insert" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
        <input type="password" id="password_insert" name="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="mb-4">
        <label for="card_number_insert" class="block text-gray-700 text-sm font-bold mb-2">Credit Card Number:</label>
        <input type="text" id="card_number_insert" name="card_number" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="mb-4">
        <label for="expiry_date_insert" class="block text-gray-700 text-sm font-bold mb-2">Expiry Date:</label>
        <input type="date" id="expiry_date_insert" name="expiry_date" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="flex items-center justify-between">
        <input type="submit" value="Insert Customer" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
    </div>
</form>


        <button data-target="updateForm" class="toggle-form bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4">Update Customer</button>
<form id="updateForm" action="update_customer.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 hidden">
    <div class="mb-4">
        <label for="customer_id_update" class="block text-gray-700 text-sm font-bold mb-2">Customer ID:</label>
        <input type="text" id="customer_id_update" name="customer_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="mb-4">
        <label for="name_update" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
        <input type="text" id="name_update" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="mb-4">
        <label for="email_update" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
        <input type="email" id="email_update" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="mb-4">
        <label for="phone_update" class="block text-gray-700 text-sm font-bold mb-2">Phone:</label>
        <input type="text" id="phone_update" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="mb-4">
        <label for="username_update" class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
        <input type="text" id="username_update" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="mb-4">
        <label for="password_update" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
        <input type="password" id="password_update" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="mb-4">
        <label for="card_number_update" class="block text-gray-700 text-sm font-bold mb-2">Credit Card Number:</label>
        <input type="text" id="card_number_update" name="card_number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="mb-4">
        <label for="expiry_date_update" class="block text-gray-700 text-sm font-bold mb-2">Expiry Date:</label>
        <input type="date" id="expiry_date_update" name="expiry_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="flex items-center justify-between">
        <input type="submit" value="Update Customer" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
    </div>
</form>


        <button data-target="deleteForm" class="toggle-form bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4">Delete Customer</button>
        <form id="deleteForm" action="delete_customer.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 hidden">
            <div class="mb-4">
                <label for="customer_id_delete" class="block text-gray-700 text-sm font-bold mb-2">Customer ID:</label>
                <input type="text" id="customer_id_delete" name="customer_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex items-center justify-between">
                <input type="submit" value="Delete Customer" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            </div>
        </form>

        <h3 class="text-2xl font-semibold text-gray-600 mb-4">Customer Details</h3>
        <div id="customerTable">
            <!-- Customer table will be loaded here dynamically -->
        </div>

        <a href="logout.php" class="block text-center mt-6 text-blue-500 hover:underline">Logout</a>
    </div>

    <script>
    $(document).ready(function() {
        function loadCustomers() {
            $.ajax({
                url: 'fetch_customers.php',
                method: 'GET',
                success: function(data) {
                    $('#customerTable').html(data);
                }
            });
        }

        // Load customers on page load
        loadCustomers();

        // Handle form submissions
        $('form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    alert(response);
                    loadCustomers();
                    form.find('input[type="text"], input[type="email"], input[type="date"]').val('');
                    form.hide();
                }
            });
        });

    });
</script>
</body>
</html>
