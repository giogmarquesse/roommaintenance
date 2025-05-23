<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard | Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background particles */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: float 20s infinite linear;
            pointer-events: none;
        }

        @keyframes float {
            0% { transform: translateY(0px) translateX(0px); }
            50% { transform: translateY(-20px) translateX(10px); }
            100% { transform: translateY(0px) translateX(0px); }
        }

        .container {
            position: relative;
            z-index: 1;
        }

        .header-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: white;
            animation: slideInDown 0.8s ease-out;
        }

        .header-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header-section .lead {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 1.5rem;
        }

        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
            animation: slideInUp 0.8s ease-out;
        }

        .dashboard-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 1);
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .dashboard-card:hover::before {
            transform: scaleX(1);
        }

        .card-body {
            padding: 2rem;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .card-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .card-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #2d3748;
        }

        .card-text {
            color: #4a5568;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .btn-custom {
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn-custom:hover::before {
            left: 100%;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #4299e1, #3182ce);
            color: white;
        }

        .btn-success-custom {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
        }

        .btn-warning-custom {
            background: linear-gradient(135deg, #ed8936, #dd6b20);
            color: white;
        }

        .btn-purple-custom {
            background: linear-gradient(135deg, #805ad5, #6b46c1);
            color: white;
        }

        .btn-danger-custom {
            background: linear-gradient(135deg, #f56565, #e53e3e);
            color: white;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .logout-btn {
            background: rgba(248, 113, 113, 0.1);
            border: 2px solid rgba(248, 113, 113, 0.3);
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(248, 113, 113, 0.2);
            border-color: rgba(248, 113, 113, 0.5);
            color: white;
            transform: translateY(-2px);
        }

        /* Animations */
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Staggered animation for cards */
        .col-md-4:nth-child(1) .dashboard-card { animation-delay: 0.1s; }
        .col-md-4:nth-child(2) .dashboard-card { animation-delay: 0.2s; }
        .col-md-4:nth-child(3) .dashboard-card { animation-delay: 0.3s; }
        .col-md-4:nth-child(4) .dashboard-card { animation-delay: 0.4s; }

        /* Responsive design */
        @media (max-width: 768px) {
            .header-section h1 {
                font-size: 2rem;
            }
            
            .card-body {
                padding: 1.5rem;
            }
            
            .card-icon {
                font-size: 2.5rem;
            }
        }

        /* Loading animation */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .hidden {
            opacity: 0;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <!-- Page Loader -->
    <div class="page-loader" id="pageLoader">
        <div class="loader-spinner"></div>
    </div>

    <div class="container py-5">
        <!-- Header Section -->
        <div class="header-section">
            <h1><i class="fas fa-tachometer-alt me-3"></i>Student Management System</h1>
            <p class="lead"><i class="fas fa-user-tag me-2"></i>Role: <?php echo htmlspecialchars($_SESSION['role']); ?></p>
            <a href="logout.php" class="btn logout-btn">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </a>
        </div>

        <!-- Dashboard Cards -->
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="card-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h5 class="card-title">Register New Student</h5>
                        <p class="card-text">Add a new student into the system with complete information and details.</p>
                        <a href="register_student.php" class="btn btn-custom btn-primary-custom">
                            <i class="fas fa-plus me-2"></i>Register Student
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h5 class="card-title">View Students</h5>
                        <p class="card-text">Browse and search through all registered students' information and records.</p>
                        <a href="view_students.php" class="btn btn-custom btn-success-custom">
                            <i class="fas fa-eye me-2"></i>View Students
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="card-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h5 class="card-title">Manage Courses & Majors</h5>
                        <p class="card-text">View, add, edit, or remove courses and majors offered by the institution.</p>
                        <a href="manage_courses.php" class="btn btn-custom btn-warning-custom">
                            <i class="fas fa-cogs me-2"></i>Manage Courses
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="card-icon">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <h5 class="card-title">Manage Students</h5>
                        <p class="card-text">Edit, update, or remove existing student records and information.</p>
                        <a href="manage_students.php" class="btn btn-custom btn-purple-custom">
                            <i class="fas fa-edit me-2"></i>Manage Students
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Page loader
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('pageLoader').classList.add('hidden');
            }, 500);
        });

        // Add smooth scroll behavior for potential future use
        document.documentElement.style.scrollBehavior = 'smooth';

        // Add click effect to cards
        document.querySelectorAll('.dashboard-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.closest('.btn')) {
                    const link = this.querySelector('a');
                    if (link) {
                        link.click();
                    }
                }
            });
        });

        // Add ripple effect to buttons
        document.querySelectorAll('.btn-custom').forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
    </script>

    <style>
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: rippleEffect 0.6s linear;
            pointer-events: none;
        }

        @keyframes rippleEffect {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</body>
</html>