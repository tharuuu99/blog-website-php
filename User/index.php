<?php 
include('..\connection.php');

session_start();
if(!isset($_SESSION['username'])){
    header("location: ../index.php");
    exit();
}

if(isset($_GET['action'])){
    if($_GET['action'] == 'signout'){
        session_unset();
        session_destroy();
        header("location: ../index.php");
        exit();
    }
}

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT id, title, content, created_at FROM posts";
$stmt = $connection->prepare($sql);
$stmt->execute();
$resultBlogs = $stmt->get_result();

$sql = "SELECT id, name, price, image FROM products";
$stmt = $connection->prepare($sql);
$stmt->execute();
$resultProducts = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Page</title>
    <script src="https://cdn.tailwindcss.com"></script> 
</head>
<body class="bg-gray-100 font-sans">

    <!-- header section starts -->
    <header class="bg-white p-4 shadow-md">
        <!-- Navbar -->
        <nav class="navbar flex justify-between items-center mt-4 ">
            <div class="space-x-4">
                <a href="#blogs" class="text-gray-800 hover:text-blue-500">Blogs</a>
                <a href="#products" class="text-gray-800 hover:text-blue-500">Products</a>
                <a href="myblogs.php" class="text-gray-800 hover:text-blue-500">My Blogs</a>
                <a href="mycart.php" class="text-gray-800 hover:text-blue-500">My Cart</a>
            </div>
            
            <div class="user flex space-x-4">
                <div class="signout">
                    <a href="myblogs.php?action=signout" class="text-gray-800 hover:text-blue-500">Sign Out</a>
                </div>
            </div>
        </nav>
    </header>
    <!-- header section ends -->

    <!-- Mobile menu button (optional) -->
    <div id="menu-btn" class="fas fa-bars text-gray-800 cursor-pointer md:hidden"></div>

    <!-- Blog Section -->
    <section class="blogs" id="blogs">
        <h1 class="text-3xl font-semibold text-center mt-8 mb-6 text-gray-800">Latest <span class="text-blue-500">Blogs</span></h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 px-4">
            <?php 
            if ($resultBlogs->num_rows > 0) {
                while ($row = $resultBlogs->fetch_assoc()) {
                    echo '<div class="blog-box bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all">';
                    echo '<h3 class="text-xl font-semibold mb-4">' . htmlspecialchars($row['title']) . '</h3>';
                    echo '<p class="text-gray-600 mb-4">' . htmlspecialchars($row['content']) . '</p>';
                    echo '<a href="#" class="text-blue-500 hover:underline">Created At: ' . htmlspecialchars($row['created_at']) . '</a>';
                    echo '<div class="mt-4 flex justify-between">';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-center text-gray-600">No blogs found.</p>';
            }
            ?>
        </div>
    </section>

    <section class="products" id="products">
    <h1 class="text-3xl font-semibold text-center mt-16 mb-6 text-gray-800">Our <span class="text-blue-500">Products</span></h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 px-4">
        <?php 
        if ($resultProducts->num_rows > 0) {
            while ($row = $resultProducts->fetch_assoc()) {
                $imageUrl = htmlspecialchars($row['image']); 
                echo '<div class="product-box bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all">';
                echo '<img src="' . $imageUrl . '" alt="' . htmlspecialchars($row['name']) . '" class="w-full h-64 object-cover mb-4 rounded">';
                echo '<h3 class="text-xl font-semibold mb-4">' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p class="text-gray-600 mb-4">Price: LKR' . htmlspecialchars($row['price']) . '</p>';
                echo '<a href="add_to_cart.php?id=' . $row['id'] . '" class="text-blue-500 hover:underline">Add to Cart</a>';
                echo '</div>';
            }
        } else {
            echo '<p class="text-center text-gray-600">No products found.</p>';
        }
        ?>
    </div>
</section>

</body>
</html>
