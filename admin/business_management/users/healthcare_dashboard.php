<?php
session_start();

// Simulate user selection (In real case, fetch from database based on user login)
$business_type = isset($_GET['type']) ? $_GET['type'] : 'healthcare';

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
    <title>Healthcare Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { height: 100vh; background: #343a40; color: white; padding: 20px; }
        .sidebar a { color: white; display: block; padding: 10px; text-decoration: none; }
        .sidebar a:hover { background: #495057; }
        .card { border-radius: 10px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 sidebar">
                <h2>Healthcare Dashboard</h2>
                <a href="#">Dashboard</a>
                <a href="#">Appointments</a>
                <a href="#">Patients</a>
                <a href="#">Doctors</a>
                <a href="#">Billing</a>
                <a href="#">Reports</a>
                <a href="#">Settings</a>
            </nav>
            <main class="col-md-10 p-4">
                <h2>Overview</h2>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card p-3 text-center">
                            <h5>Total Patients</h5>
                            <h3>1,230</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3 text-center">
                            <h5>Active Appointments</h5>
                            <h3>245</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3 text-center">
                            <h5>Doctors Available</h5>
                            <h3>78</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3 text-center">
                            <h5>Revenue</h5>
                            <h3>$150,000</h3>
                        </div>
                    </div>
                </div>
                <canvas id="healthChart" class="mt-4"></canvas>
            </main>
        </div>
    </div>
    <script>
        var ctx = document.getElementById('healthChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Appointments Trend',
                    data: [100, 150, 180, 200, 220, 245],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2
                }]
            }
        });
    </script>
</body>
</html>