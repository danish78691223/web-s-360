<?php
session_start();
header('Content-Type: application/json');

ob_clean();
header('Content-Type: application/json');
echo json_encode(["success" => true]);


error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure you have PHPMailer installed via Composer

$conn = new mysqli("localhost", "root", "", "auth_system");

// Check database connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Database connection failed: " . $conn->connect_error]));
}

// Validate POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
    exit;
}

if (!isset($_POST['query_id']) || !isset($_POST['response'])) {
    echo json_encode(["success" => false, "error" => "Missing required fields."]);
    exit;
}

$query_id = intval($_POST['query_id']);
$response_text = trim($_POST['response']);

if (empty($response_text)) {
    echo json_encode(["success" => false, "error" => "Response cannot be empty."]);
    exit;
}

// Fetch user email from database
$sql = "SELECT email, user_name FROM queries WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $query_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "error" => "Query not found."]);
    exit;
}

$row = $result->fetch_assoc();
$user_email = $row['email'];
$user_name = $row['user_name'];

$stmt->close();

// Store response in database
$update_stmt = $conn->prepare("UPDATE queries SET response = ?, responded_at = NOW() WHERE id = ?");
$update_stmt->bind_param("si", $response_text, $query_id);

if (!$update_stmt->execute()) {
    echo json_encode(["success" => false, "error" => "Database error: " . $update_stmt->error]);
    exit;
}
$update_stmt->close();

// Send email to user
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Your SMTP host (Gmail, Outlook, etc.)
    $mail->SMTPAuth = true;
    $mail->Username = 'askdnk2523@gmail.com'; // Your email
    $mail->Password = 'hhxn xmis qhrx sejr'; // Your email app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Sender and recipient
    $mail->setFrom('your-email@gmail.com', 'Admin - Your Website');
    $mail->addAddress($user_email, $user_name);

    // Email content
    $mail->isHTML(true);
    $mail->Subject = "Response to Your Query - Your Website";
    $mail->Body = "
        <h3>Hello, $user_name,</h3>
        <p>Thank you for reaching out. Here is our response to your query:</p>
        <blockquote style='background:#f3f3f3;padding:10px;border-left:4px solid #0072ff;'>$response_text</blockquote>
        <p>If you have further questions, feel free to ask.</p>
        <p>Best Regards,<br>Your Website Support Team</p>
    ";

    // Send email
    $mail->send();
    echo json_encode(["success" => true, "message" => "Response sent and email delivered successfully!"]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => "Email could not be sent. Mailer Error: " . $mail->ErrorInfo]);
}

$conn->close();
?>
