<?php

include('..\..\connection.php');

if (isset($_POST['user_Login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to fetch user details based on email
    $query = "SELECT id, username, password, email FROM users WHERE email=?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $row = $result->fetch_assoc();

        if ($row) {
            // Verify the password
            $verified_password = password_verify($password, $row['password']);

            if ($verified_password) {
                // Start session and set session variables
                session_start();
                $_SESSION['email'] = $row['email'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_id'] = $row['id'];

                // Redirect to the home page
                header("location: ../index.php");
                exit;
            } else {
                echo "<script>alert('Invalid password! Please try again.')</script>";
            }
        } else {
            echo "<script>alert('No user found with this email address.')</script>";
        }
    } else {
        echo "<script>alert('Error in query execution!')</script>";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="max-w-md mx-auto mt-16 p-8 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">User Login</h2>
        <form action="" method="POST" class="space-y-6">
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" id="email" placeholder="Email" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label for="pwd" class="block text-gray-700 font-medium mb-2">Password</label>
                <input type="password" name="password" id="pwd" placeholder="Password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <button type="submit" name="user_Login"
                    class="w-full py-3 mt-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                Login
            </button>
        </form>
    </div>
</body>
</html>
