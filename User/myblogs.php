<?php 
include('..\connection.php');

session_start();
if(!isset($_SESSION['username'])){
    header("location: ../index.php");
}

if(isset($_GET['action'])){
    if($_GET['action'] == 'signout'){
        session_unset();
        session_destroy();
        header("location: ../index.php");
    }
}


if(isset($_POST['add_blog'])){
    $title = $_POST['title'];
    $content = $_POST['content'];

    if($title && $content) {
        $sql = "INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("iss", $_SESSION['user_id'], $title, $content);
        if($stmt->execute()){
            header("location: myblogs.php");
        } else {
            echo "Error adding blog: " . $stmt->error;
        }
    }
}


if(isset($_POST['edit_blog'])){
    $post_id = $_POST['post_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    if($title && $content) {
        $sql = "UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssii", $title, $content, $post_id, $_SESSION['user_id']);
        if($stmt->execute()){
            header("location: myblogs.php");
        } else {
            echo "Error updating blog: " . $stmt->error;
        }
    }
}


if(isset($_GET['delete'])){
    $post_id = $_GET['delete'];

    $sql = "DELETE FROM posts WHERE id = ? AND user_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ii", $post_id, $_SESSION['user_id']);
    if($stmt->execute()){
        header("location: myblogs.php");
    } else {
        echo "Error deleting blog: " . $stmt->error;
    }
}

$user_id = $_SESSION['user_id']; 

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT id, title, content, created_at FROM posts WHERE user_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blogs</title>
    <script src="https://cdn.tailwindcss.com"></script> 
</head>
<body>
    <!-- header section starts -->
    <header class="bg-white p-4 shadow-md">
        <nav class="navbar flex justify-between items-center mt-4">
            <div class="space-x-4">
                <a href="index.php/#blogs" class="text-gray-800 hover:text-blue-500">Blogs</a>
                <a href="index.php/#products" class="text-gray-800 hover:text-blue-500">Products</a>
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

   
    <section class="myblogs" id="myblogs">
        <h1 class="text-3xl font-semibold text-center mt-16 mb-6 text-gray-800">My <span class="text-blue-500">Blogs</span></h1>

        <div class="text-center mb-6">
           
            <button class="px-4 py-2 bg-blue-500 text-white rounded-lg" id="addBlogBtn">Add New Blog</button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 px-4">
            <?php 
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="blog-box bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all">';
                    echo '<h3 class="text-xl font-semibold mb-4">' . htmlspecialchars($row['title']) . '</h3>';
                    echo '<p class="text-gray-600 mb-4">' . htmlspecialchars($row['content']) . '</p>';
                    echo '<a href="#" class="text-blue-500 hover:underline">Created At: ' . htmlspecialchars($row['created_at']) . '</a>';
                    echo '<div class="mt-4 flex justify-between">';
                    
                    echo '<a href="edit_blog.php?id=' . $row['id'] . '" class="text-blue-500 hover:underline">Edit</a>';
                   
                    echo '<a href="myblogs.php?delete=' . $row['id'] . '" class="text-red-500 hover:underline">Delete</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-center text-gray-600">No blogs found.</p>';
            }
            ?>
        </div>
    </section>

   
    <div id="addBlogModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-8 rounded-lg w-96">
            <h2 class="text-2xl font-semibold mb-4">Add New Blog</h2>
            <form action="myblogs.php" method="POST">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" class="w-full p-2 border border-gray-300 rounded-md mb-4" required>

                <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                <textarea id="content" name="content" class="w-full p-2 border border-gray-300 rounded-md mb-4" required></textarea>

                <div class="flex justify-end">
                    <button type="button" class="px-4 py-2 bg-gray-300 text-black rounded-lg mr-2" id="cancelBtn">Cancel</button>
                    <button type="submit" name="add_blog" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Add Blog</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        
        document.getElementById('addBlogBtn').onclick = function() {
            document.getElementById('addBlogModal').classList.remove('hidden');
        }

       
        document.getElementById('cancelBtn').onclick = function() {
            document.getElementById('addBlogModal').classList.add('hidden');
        }
    </script>
</body>
</html>
