<?php
session_start();
require_once(__DIR__ . '/../includes/db.php');
require_once(__DIR__ . '/../includes/send_email.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Function to generate a unique Business ID
function generateBusinessID($conn) {
    do {
        $business_id = "BID" . rand(100000, 999999);
        $check_sql = "SELECT COUNT(*) as count FROM approved_businesses WHERE business_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $business_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];
        $check_stmt->close();
    } while ($count > 0);
    return $business_id;
}

// Function to generate a random password
function generatePassword($length = 8) {
    return substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, $length);
}

// Approve Business
if (isset($_POST['approve'])) {
    if (!isset($_POST['business_id']) || empty($_POST['business_id'])) {
        $_SESSION['error'] = "Invalid business ID.";
        header("Location: business_register.php");
        exit();
    }

    $business_id = intval($_POST['business_id']);

    // Fetch business details, including business_type and owner_name
    $stmt = $conn->prepare("SELECT id, business_name, email, phone, address, business_type, owner_name FROM pending_businesses WHERE id = ?");
    $stmt->bind_param("i", $business_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $business = $result->fetch_assoc();

        if (!isset($business['business_type']) || empty($business['business_type'])) {
            $_SESSION['error'] = "Business type is missing.";
            header("Location: business_register.php");
            exit();
        }

        $new_business_id = generateBusinessID($conn);
        $password = generatePassword();
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $business_type = strtolower(trim($business['business_type']));
        $owner_name = $business['owner_name'];

        // Define dashboard URLs for different business types
        $dashboard_mapping = [
            "e-commerce" => "ecommerce_dashboard.php",
            "education" => "education_dashboard.php",
            "healthcare" => "healthcare_dashboard.php",
            "default" => "general_dashboard.php"
        ];

        $dashboard_url = $dashboard_mapping[$business_type] ?? $dashboard_mapping["default"];

        error_log("Business Type: " . $business_type);
        error_log("Assigned Dashboard: " . $dashboard_url);

        // Insert into approved businesses
        $insert_stmt = $conn->prepare("INSERT INTO approved_businesses (business_id, business_name, email, phone, address, password, business_type, owner_name, dashboard_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("sssssssss", $new_business_id, $business['business_name'], $business['email'], $business['phone'], $business['address'], $hashed_password, $business_type, $owner_name, $dashboard_url);

        if (!$insert_stmt->execute()) {
            $_SESSION['error'] = "Error approving business: " . $insert_stmt->error;
            header("Location: business_register.php");
            exit();
        }

        // Send approval email with business ID and password
        $emailSent = sendEmail($business['email'], "Business Approved", "Your business has been approved with Business ID: $new_business_id and Password: $password");

        if (!$emailSent) {
            $_SESSION['error'] = "Business approved but email sending failed.";
            header("Location: business_register.php");
            exit();
        }

        // Delete from pending list
        $delete_stmt = $conn->prepare("DELETE FROM pending_businesses WHERE id = ?");
        $delete_stmt->bind_param("i", $business_id);
        $delete_stmt->execute();

        $_SESSION['success'] = "Business approved successfully.";
    } else {
        $_SESSION['error'] = "Business not found.";
    }

    header("Location: business_register.php");
    exit();
}


// Reject Business
if (isset($_POST['reject'])) {
    if (!isset($_POST['business_id']) || empty($_POST['business_id'])) {
        $_SESSION['error'] = "Invalid business ID.";
        header("Location: business_register.php");
        exit();
    }

    $business_id = intval($_POST['business_id']);
    $stmt = $conn->prepare("SELECT email FROM pending_businesses WHERE id = ?");
    $stmt->bind_param("i", $business_id);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    $stmt->close();

    if ($email) {
        sendEmail($email, "Business Rejected", "Your business registration has been rejected. Due To Incorrect Information");
        $delete_stmt = $conn->prepare("DELETE FROM pending_businesses WHERE id = ?");
        $delete_stmt->bind_param("i", $business_id);
        $delete_stmt->execute();
        $_SESSION['success'] = "Business rejected successfully.";
    } else {
        $_SESSION['error'] = "Business not found.";
    }
    
    header("Location: business_register.php");
    exit();
}

// Fetch pending businesses
$query = "SELECT * FROM pending_businesses";
$result = $conn->query($query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Business Approvals</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        /* Success & Error Messages */
        .success-msg {
            color: green;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .error-msg {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }

        /* Table Styling */
        table {
            width: 80%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Buttons */
        button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: 0.3s;
        }

        button[name="approve"] {
            background-color: #28a745;
            color: white;
        }

        button[name="approve"]:hover {
            background-color: #218838;
        }

        .reject-btn {
            background-color: #dc3545;
            color: white;
        }

        .reject-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <h2>Pending Business Approvals</h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <p class="success-msg"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <p class="error-msg"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Business Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['business_name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['phone']); ?></td>
            <td><?php echo htmlspecialchars($row['address']); ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="business_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="approve">Approve</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="business_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="reject" class="reject-btn">Reject</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
<a href="admin_dashboard.php" class="back-btn">Back</a>
<style>
  .back-btn {
      display: inline-block;
      padding: 10px 20px;
      background-color: #007bff; /* Blue color */
      color: white;
      text-decoration: none;
      font-size: 16px;
      font-weight: bold;
      border-radius: 5px;
      transition: background-color 0.3s ease-in-out, transform 0.2s;
  }

  .back-btn:hover {
      background-color: #0056b3; /* Darker blue on hover */
      transform: scale(1.05);
  }

  .back-btn:active {
      background-color: #004494; /* Even darker when clicked */
      transform: scale(0.95);
  }
</style>
</body>
</html>
