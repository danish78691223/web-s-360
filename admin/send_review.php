<?php 
session_start();
$conn = new mysqli("localhost", "root", "", "auth_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch queries from database
$sql = "SELECT id, user_id, user_name, email, message FROM queries ORDER BY created_at DESC";
$result = $conn->query($sql);

if (!$result) {
    die("SQL Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Queries - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #0f0f0f;
            color: white;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 1000px;
            margin: 50px auto;
            background: #1f1f1f;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
        }
        h2 {
            text-align: center;
            color: #00c6ff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border: 1px solid #333;
            text-align: center;
        }
        th {
            background: #0072ff;
            color: white;
        }
        td {
            background: #1a1a1a;
        }
        textarea {
            width: 100%;
            height: 50px;
            resize: none;
            background: #262626;
            color: white;
            border: none;
            padding: 5px;
            border-radius: 5px;
        }
        button {
            padding: 8px 15px;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: linear-gradient(135deg, #0072ff, #00c6ff);
            transform: scale(1.05);
        }
        .back-button {
            display: inline-block;
            margin: 20px;
            padding: 10px 15px;
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.3s;
        }
        .back-button:hover {
            background: linear-gradient(135deg, #ff4b2b, #ff416c);
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <a href="admin_dashboard.php" class="back-button">← Back to Dashboard</a>
    <div class="container">
        <h2>Admin - User Queries</h2>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Query</th>
                    <th>Response</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                    <td>
                        <textarea class="response" id="response_<?php echo $row['id']; ?>"></textarea>
                    </td>
                    <td>
                        <button onclick="sendResponse(<?php echo $row['id']; ?>)">Send</button>
                        <button onclick="deleteQuery(<?php echo $row['id']; ?>)">Delete</button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
    function sendResponse(queryId) {
        let responseText = document.getElementById('response_' + queryId).value.trim();
        if (!responseText) {
            alert('Please enter a response.');
            return;
        }

        let formData = new FormData();
        formData.append('query_id', queryId);
        formData.append('response', responseText);

        fetch('send_response.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Response sent successfully!");
                location.reload();
            } else {
                alert("Error: " + data.error);
            }
        })
        .catch(error => {
            alert('Error sending response. Check console for details.');
            console.error(error);
        });
    }

    function deleteQuery(queryId) {
        if (!confirm("Are you sure you want to delete this query?")) return;

        fetch('delete_query.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'query_id=' + queryId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Query deleted successfully!");
                location.reload();
            } else {
                alert("Error: " + data.error);
            }
        })
        .catch(error => {
            alert('Error deleting query. Check console for details.');
            console.error(error);
        });
    }
    </script>
</body>
</html>
