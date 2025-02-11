<?php 
include('..\connection.php');
session_start();

if(!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit();
}

$userId = $_SESSION['user_id']; 


if(isset($_GET['id'])) {
    $productId = $_GET['id'];
    $quantity = 1; 

    
    $sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $sql = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        echo "Product quantity updated in cart.";
    } else {
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("iii", $userId, $productId, $quantity);
        $stmt->execute();
        echo "Product added to cart.";
    }

    header("Location: your_redirect_page.php"); 
    exit();
} else {
    echo "No product ID provided.";
}
?>
