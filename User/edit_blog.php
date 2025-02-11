<?php
include('..\connection.php');

session_start();
if(!isset($_SESSION['username'])){
    header("location: ../index.php");
}

if (isset($_GET['id'])) {
    $blog_id = $_GET['id'];

    $sql = "SELECT title, content FROM posts WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $blog_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $blog = $result->fetch_assoc();

    if (!$blog) {
        echo "Blog not found.";
        exit;
    }
}

if (isset($_POST['update_blog'])) {
    $updated_title = $_POST['title'];
    $updated_content = $_POST['content'];

    
    $update_sql = "UPDATE posts SET title = ?, content = ? WHERE id = ?";
    $update_stmt = $connection->prepare($update_sql);
    $update_stmt->bind_param("ssi", $updated_title, $updated_content, $blog_id);
    $update_stmt->execute();

    
    header("Location: myblogs.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Blog</title>
    <script src="https://cdn.tailwindcss.com"></script> 
</head>
<body>
<div id="addBlogModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
    <div class="bg-white p-8 rounded-lg w-96">
        <h2 class="text-2xl font-semibold mb-4">Update Blog</h2>
        <form action="edit_blog.php?id=<?php echo $blog_id; ?>" method="POST">
           
            <input type="hidden" name="blog_id" value="<?php echo $blog_id; ?>">

            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" id="title" name="title" class="w-full p-2 border border-gray-300 rounded-md mb-4" value="<?php echo htmlspecialchars($blog['title']); ?>" required>

            <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
            <textarea id="content" name="content" class="w-full p-2 border border-gray-300 rounded-md mb-4" required><?php echo htmlspecialchars($blog['content']); ?></textarea>

            <div class="flex justify-end">
                <button type="button" class="px-4 py-2 bg-gray-300 text-black rounded-lg mr-2" id="cancelBtn">Cancel</button>
                <button type="submit" name="update_blog" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Update Blog</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
