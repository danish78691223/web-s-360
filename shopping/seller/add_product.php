<?php
session_start();
include '../../db.php';

if (!isset($_SESSION['seller_id'])) {
    echo "<script>alert('You must be logged in as a seller to add products.'); window.location.href='../seller_login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $price = floatval($_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $description = mysqli_real_escape_string($conn, $_POST['description']); 
    $seller_id = $_SESSION['seller_id'];

    // Image upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $target_dir = "../../shopping/seller/uploads/"; // ✅ Updated Path
        $image_name = basename($_FILES['product_image']['name']);
        $imageFileType = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_types = array("jpg", "jpeg", "png", "gif");

        if (!in_array($imageFileType, $allowed_types)) {
            echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.');</script>";
            exit;
        }

        $unique_name = time() . "_" . $image_name;
        $target_file = $target_dir . $unique_name;

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
            $image_path = $unique_name;

            // Insert into database
            $sql = "INSERT INTO seller_products (product_name, price, category, description, image, seller_id) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdsssi", $product_name, $price, $category, $description, $image_path, $seller_id);

            if ($stmt->execute()) {
                echo "<script>alert('Product added successfully!'); window.location.href='seller_dashboard.php';</script>";
            } else {
                echo "<script>alert('Error adding product. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Failed to upload image. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Please upload an image.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Add Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="product_name" class="form-control" placeholder="Enter product name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" name="price" class="form-control" placeholder="Enter price" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-control" required>
                    <option value="electronics">Electronics</option>
                    <option value="fashion">Fashion</option>
                    <option value="home">Home</option>
                    <option value="beauty">Beauty</option>
                    <option value="toys">Toys</option>
                    <option value="books">Books</option>
                    <option value="sports">Sports</option>
                    <option value="automobile">Automobile</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Enter product description" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Product Image</label>
                <input type="file" name="product_image" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>
</body>
</html>

