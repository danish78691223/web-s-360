<?php
session_start();

// Simulate user selection (In real case, fetch from database based on user login)
$business_type = isset($_GET['type']) ? $_GET['type'] : 'education';

function getDashboardContent($type) {
    switch ($type) {
        case 'ecommerce':
            return "<h2>Welcome to the E-commerce Dashboard</h2><p>Manage your products, orders, and customers here.</p>";
        case 'education':
            return "<h2>Welcome to the Education Dashboard</h2><p>Manage courses, students, and exams here.</p>";
        case 'healthcare':
            return "<h2>Welcome to the Healthcare Dashboard</h2><p>Manage patients, appointments, and doctors here.</p>";
        default:
            return "<h2>Dashboard</h2><p>Select a valid business type.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Education Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .sidebar {
            width: 250px;
            position: fixed;
            height: 100%;
            background: #2c3e50;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 15px;
            display: block;
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #1abc9c;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center">Edu Dashboard</h4>
        <a href="#"><i class="fas fa-home"></i> Dashboard</a>
        <a href="#"><i class="fas fa-user-graduate"></i> Students</a>
        <a href="#"><i class="fas fa-book"></i> Courses</a>
        <a href="#"><i class="fas fa-chart-bar"></i> Analytics</a>
        <a href="#"><i class="fas fa-bell"></i> Notifications</a>
        <a href="#"><i class="fas fa-cog"></i> Settings</a>
    </div>
    
    <div class="content">
        <h2>Dashboard Overview</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-primary text-white p-3">
                    <h4><i class="fas fa-user-graduate"></i> Students</h4>
                    <p>1,200 Enrolled</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white p-3">
                    <h4><i class="fas fa-book"></i> Courses</h4>
                    <p>45 Available</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white p-3">
                    <h4><i class="fas fa-chart-line"></i> Attendance</h4>
                    <p>85% Avg</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>