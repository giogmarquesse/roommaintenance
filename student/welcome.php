<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('images/Student Management System (1).png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 100vh;
            margin: 0;
            cursor: pointer;
            overflow: hidden;
            position: relative;
        }

        /* Dark overlay for better text readability */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.4));
            z-index: 1;
        }

        .welcome-container {
            position: relative;
            z-index: 2;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            padding: 2rem;
        }

        .welcome-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            animation: fadeInUp 1s ease-out;
        }

        .welcome-subtitle {
            font-size: 1.4rem;
            margin-bottom: 3rem;
            opacity: 0.9;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
            animation: fadeInUp 1s ease-out 0.3s both;
        }

        .continue-text {
            font-size: 1.1rem;
            opacity: 0.8;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
            animation: fadeInUp 1s ease-out 0.6s both;
        }

        .click-indicator {
            animation: pulse 2s infinite, fadeInUp 1s ease-out 0.9s both;
            font-size: 1rem;
            opacity: 0.7;
        }

        .welcome-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 3rem 2rem;
            max-width: 600px;
            width: 90%;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            animation: fadeInScale 1.2s ease-out;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 0.7;
                transform: scale(1);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
        }

        /* Loading spinner for transition */
        .loading-spinner {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }

        /* Fade out animation */
        .fade-out {
            animation: fadeOut 0.8s ease-in-out forwards;
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: scale(1.05);
            }
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2.5rem;
            }
            .welcome-subtitle {
                font-size: 1.2rem;
            }
            .welcome-card {
                padding: 2rem 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .welcome-title {
                font-size: 2rem;
            }
            .welcome-subtitle {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body onclick="proceedToSystem()">
    <!-- Dark overlay -->
    <div class="overlay"></div>
    
    <!-- Loading spinner -->
    <div class="loading-spinner">
        <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Welcome content -->
    <div class="welcome-container">
        <div class="welcome-card">
            <h1 class="welcome-title">Welcome</h1>
            <p class="welcome-subtitle">Student Management System</p>
            <p class="continue-text">Your comprehensive solution for managing student records, courses, and academic information</p>
            <div class="click-indicator">
                <i class="bi bi-mouse"></i>
                Click anywhere to continue
                <br>
                <small style="opacity: 0.6;">↓</small>
            </div>
        </div>
    </div>

    <script>
        let isTransitioning = false;

        function proceedToSystem() {
            if (isTransitioning) return;
            
            isTransitioning = true;
            
            // Add fade out animation
            document.body.classList.add('fade-out');
            
            // Show loading spinner
            document.querySelector('.loading-spinner').style.display = 'block';
            
            // Add a small delay for better user experience
            setTimeout(() => {
                // Redirect to the main system (change this URL as needed)
                window.location.href = 'index.php'; // or 'dashboard.php', 'login.php', etc.
            }, 800);
        }

        // Add keyboard support (Enter or Space to continue)
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                proceedToSystem();
            }
        });

        // Add touch support for mobile devices
        document.addEventListener('touchstart', function(event) {
            proceedToSystem();
        });

        // Prevent multiple rapid clicks
        document.addEventListener('click', function(event) {
            if (isTransitioning) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    </script>
</body>
</html>