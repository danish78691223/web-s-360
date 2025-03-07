<?php
session_start();

// Simulate user selection (In real case, fetch from database based on user login)
$business_type = isset($_GET['type']) ? $_GET['type'] : 'ecommerce';

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
    <title>E-Commerce Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            display: flex;
        }
        .sidebar {
            width: 250px;
            background: #343a40;
            color: white;
            padding: 15px;
            height: 100vh;
            position: fixed;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
            width: 100%;
        }
        .card {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 class="text-center">E-Dashboard</h3>
        <ul class="nav flex-column">
            <li class="nav-item"><a href="#" class="nav-link text-white">Orders</a></li>
            <li class="nav-item"><a href="#" class="nav-link text-white">Products</a></li>
            <li class="nav-item"><a href="#" class="nav-link text-white">Customers</a></li>
            <li class="nav-item"><a href="#" class="nav-link text-white">Sales Analytics</a></li>
            <li class="nav-item"><a href="#" class="nav-link text-white">Reports</a></li>
            <li class="nav-item"><a href="#" class="nav-link text-white">Settings</a></li>
        </ul>
    </div>
    
    <div class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5>Total Sales</h5>
                        <h3>$12,500</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5>Total Orders</h5>
                        <h3>1,240</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h5>Total Customers</h5>
                        <h3>980</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5>Trending Products</h5>
                        <h3>50+</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <canvas id="salesChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Sales ($)',
                    data: [500, 1000, 1500, 2000, 2500, 3000],
                    borderColor: 'blue',
                    fill: false
                }]
            }
        });

        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        const ordersChart = new Chart(ordersCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Orders',
                    data: [50, 70, 90, 110, 130, 150],
                    backgroundColor: 'green'
                }]
            }
        });
    </script>
</body>
</html>