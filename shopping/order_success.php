<?php
session_start();
include '../db.php'; // Ensure database connection

if (!isset($_SESSION['order_id']) || !isset($_SESSION['order_details'])) {
    die("❌ Error: Order ID or User details not found in session.");
}

// Extract details
$order_id = $_SESSION['order_id'];
$order_details = $_SESSION['order_details'];
$user_id = $_SESSION['user_id'];  // Get user_id from session
$user_email = $order_details['email'];
$user_name = $order_details['name'];
$total_price = $order_details['total_price'] ?? 0;
$order_date = date("Y-m-d H:i:s");

// Insert order into database
$order_insert_query = "INSERT INTO orders (id, user_id, product_name, price, order_date) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($order_insert_query);
$product_name = "Purchased Items";  // Modify as needed

$stmt->bind_param("iisds", $order_id, $user_id, $product_name, $total_price, $order_date);
$insert_success = $stmt->execute();
$stmt->close();

// Unset session after confirmation
unset($_SESSION['order_id'], $_SESSION['order_details']);

// Include PHPMailer for sending email
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
$email_status = "";

try {
    $mail->isSMTP();
    //$mail->SMTPDebug = 2; // Set to 0 in production
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'askdnk2523@gmail.com';  // Replace with your Gmail address
    $mail->Password = 'hhxn xmis qhrx sejr';    // Use an App Password, NOT your Gmail password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('askdnk2523@gmail.com', 'WEBS 360');
    $mail->addAddress($user_email, $user_name);
    $mail->isHTML(true);
    $mail->Subject = "Order Confirmation - #$order_id";
    $mail->Body    = "<h3>Thank you for your purchase, $user_name!</h3>
                      <p>Your order ID: <b>$order_id</b></p>
                      <p>Total Amount: ₹<b>" . number_format($total_price, 2) . "</b></p>
                      <p>We appreciate your business. Your order will be processed soon.</p>";

    $mail->send();
    //$email_status = "✅ A confirmation email has been sent to <b>$user_email</b>.";
} catch (Exception $e) {
    $email_status = "❌ Error sending email: " . $mail->ErrorInfo;
}

$conn->close();

echo $email_status;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <style>
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

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 90%;
            max-width: 400px;
        }

        h2 {
            color: #27ae60;
        }

        p {
            font-size: 16px;
            color: #333;
            margin: 10px 0;
        }

        .btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            background: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #219150;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Order Successful!</h2>
        <p>Thank you, <strong><?php echo htmlspecialchars($user_name); ?></strong>, for your purchase.</p>
        <p>Your order ID is <strong>#<?php echo htmlspecialchars($order_id); ?></strong>.</p>
        <p>Total Amount: <strong>Rs<?php echo number_format($total_price, 2); ?></strong></p>
        <p><?php echo $email_status = "✅ A confirmation email has been sent to <b>$user_email</b>."; ?></p>
        <a href="shopping.php" class="btn">Continue Shopping</a>
    </div>
</body>
</html>

