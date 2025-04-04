<?php
include '../db.php'; // Ensure database connection

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs safely
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $category = trim($_POST['category']);
    $stock = (int) $_POST['stock'];
    $description = trim($_POST['description']); // Added description

    // Validate inputs
    if (empty($name) || empty($price) || empty($category) || empty($description) || $stock < 0) {
        die("Invalid input data. Please check your entries.");
    }

    // Image upload handling with validation
    $target_dir = "../uploads/";
    $image = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Allowed file types
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($imageFileType, $allowed_types)) {
        die("Only JPG, JPEG, PNG & GIF files are allowed.");
    }

    // Move uploaded file
    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        die("File upload failed.");
    }

    // Insert into database using prepared statements
    $stmt = $conn->prepare("INSERT INTO products (name, price, category, stock, description, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $name, $price, $category, $stock, $description, $image);

    if ($stmt->execute()) {
        header("Location: shop_management.php");
        exit;
    } else {
        die("Database insert failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<style>
    /* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Form Container */
form {
    background: #fff;
    padding: 20px;
    width: 400px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* Headings */
h2 {
    text-align: center;
    color: #333;
}

/* Labels */
label {
    font-weight: bold;
    color: #555;
}

/* Input Fields */
input, select, textarea {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
}

/* Textarea */
textarea {
    height: 100px;
    resize: none;
}

/* File Input */
input[type="file"] {
    border: none;
}

/* Button */
button {
    background: #28a745;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background: #218838;
}

/* Responsive Design */
@media (max-width: 500px) {
    form {
        width: 90%;
    }
}

h2 {
    text-align: center;
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    margin-top: 10px;
}


</style>
<body>
    <h2>Add New Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" name="name" required>

        <label for="price">Price:</label>
        <input type="text" name="price" required pattern="^\d+(\.\d{1,2})?$" title="Enter a valid price">

        <label for="category">Category:</label>
        <select name="category">
            <option value="electronics">Electronics</option>
            <option value="clothing">Fashion</option>
            <option value="home">Home</option>
            <option value="Grocery">Grocery</option>
            <option value="Gadgets">Gadgets</option>
            <option value="Beauty">Beauty</option>
        </select>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" required min="0">

        <label for="description">Description:</label>
        <textarea name="description" required placeholder="Enter product description"></textarea>

        <label for="image">Product Image:</label>
        <input type="file" name="image" required accept=".jpg, .jpeg, .png, .gif">

        <button type="submit">Add Product</button>
    </form>
</body>
</html>
