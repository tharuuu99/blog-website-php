<?php
include('..\..\connection.php');  
function setValue($name)
{
    if (isset($_POST[$name])) {
        echo htmlspecialchars($_POST[$name]);
    }
}

if (isset($_POST['first_next'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    $result = mysqli_query($connection, $query);
    
    if ($result) {
        echo "<script>alert('Signup successful!'); window.location.href = '../../User/login/login.php';</script>";
    } else {
        echo "<script>alert('Signup failed. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <div class="max-w-lg mx-auto mt-8 p-6 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Signup Info</h2>

        <form action="" method="POST">
            <div class="space-y-6">
                
               
                <div>
                    <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
                    <input type="text" id="username" name="username" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" 
                        value="<?php setValue('username'); ?>" required>
                </div>

                
                <div>
                    <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                    <input type="email" id="email" name="email" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" 
                        value="<?php setValue('email'); ?>" required>
                </div>

            
                <div>
                    <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                    <input type="password" id="password" name="password" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>

                
                <div>
                    <button type="submit" name="first_next" 
                        class="w-full py-3 mt-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        SignUp
                        <span class="ml-2">
                            <ion-icon name="arrow-forward-outline"></ion-icon>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>

</body>
</html>
