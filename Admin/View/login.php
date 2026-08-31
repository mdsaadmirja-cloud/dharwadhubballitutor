<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$loginError = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DharwadHubballiTutor - The Ultimate Login Experience</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-color: #0c0a18;
            --card-bg-color: rgba(22, 19, 44, 0.45);
            --border-color: rgba(255, 255, 255, 0.2);
            --text-color: #f0f0f0;
            --label-color: #a09cb7;
            --heading-color: #ffffff;
            --primary-color: #8a2be2;
            --primary-hover-color: #9932cc;
            --aurora-color-1: #8a2be2;
            --aurora-color-2: #4158D0;
        }

        html.light-mode {
            --bg-color: #f0f2f5;
            --card-bg-color: rgba(255, 255, 255, 0.5);
            --border-color: rgba(0, 0, 0, 0.1);
            --text-color: #1c1c1e;
            --label-color: #5a5a5e;
            --heading-color: #000000;
            --primary-color: #8a2be2;
            --primary-hover-color: #7b1cc1;
            --aurora-color-1: #8a2be2;
            --aurora-color-2: #4158D0;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background-color 0.4s ease;
            overflow: hidden;
        }

        #vanta-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        /* Aurora Effect */
        body::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background-image: radial-gradient(circle, var(--aurora-color-1) 0%, var(--aurora-color-2) 100%);
            border-radius: 50%;
            filter: blur(150px);
            opacity: 0.2;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: aurora-glow 20s infinite alternate;
        }

        @keyframes aurora-glow {
            from {
                transform: translate(-50%, -50%) scale(0.8) rotate(0deg);
            }

            to {
                transform: translate(-45%, -55%) scale(1.2) rotate(180deg);
            }
        }

        .login-container {
            position: relative;
            z-index: 2;
            padding: 3rem;
            background: var(--card-bg-color);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.3);
            transform: perspective(1000px) rotateX(0) rotateY(0);
            transition: transform 0.6s ease-out, box-shadow 0.6s ease-out;
        }

        .login-container:hover {
            transform: perspective(1000px) rotateX(2deg) rotateY(-4deg) scale(1.02);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.4);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-header h1 {
            color: var(--heading-color);
            font-weight: 700;
            font-size: 2rem;
            margin: 0;
        }

        .login-header p {
            color: var(--label-color);
            font-size: 1rem;
            margin-top: 0.5rem;
        }

        .input-group {
            position: relative;
            margin-bottom: 2rem;
        }

        .input-field {
            width: 100%;
            background: transparent;
            border: none;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-color);
            font-size: 1rem;
            padding: 10px 5px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .input-label {
            position: absolute;
            top: 10px;
            left: 5px;
            color: var(--label-color);
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .input-field:focus+.input-label,
        .input-field:valid+.input-label {
            top: -15px;
            font-size: 0.85rem;
            color: var(--primary-color);
        }

        .input-field:focus {
            border-bottom-color: var(--primary-color);
        }

        .login-button {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 12px;
            background-color: var(--primary-color);
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .login-button:hover {
            background-color: var(--primary-hover-color);
            transform: translateY(-3px);
        }

        /* Shimmer Effect */
        .login-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: skewX(-30deg);
            transition: left 0.7s ease-in-out;
        }

        .login-button:hover::before {
            left: 150%;
        }

        /* Theme Toggle */
        .theme-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
            cursor: pointer;
            z-index: 10;
            background-color: var(--card-bg-color);
            border: 1px solid var(--border-color);
            padding: 8px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .theme-toggle i {
            font-size: 1.2rem;
            color: var(--text-color);
        }
    </style>
</head>

<body>

    <div id="vanta-canvas"></div>

    <div class="theme-toggle" id="theme-toggler">
        <i class="fa-solid fa-moon"></i>
    </div>

    <div class="login-container">
        <div class="login-header">
            <h1>Welcome Back</h1>

            <p>Enter your credentials to access your account.</p>

            <?php if ($loginError !== ''): ?>
                <div style="
            background: rgba(220, 53, 69, 0.12);
            border: 1px solid rgba(220, 53, 69, 0.35);
            color: #dc3545;
            padding: 10px 14px;
            border-radius: 10px;
            margin-top: 15px;
            font-size: 14px;
            text-align: center;
        ">
                    <?php echo htmlspecialchars($loginError); ?>
                </div>
            <?php endif; ?>
        </div>
        <form action="../Controller/login.php" method="post" autocomplete="off">
            <div class="input-group">
                <input type="text" id="user_email" name="user_email" class="input-field" required>
                <label for="user_email" class="input-label">Username or Email</label>
            </div>
            <div class="input-group">
                <input type="password" id="user_password" name="user_password" class="input-field" required>
                <label for="user_password" class="input-label">Password</label>
            </div>
            <button type="submit" class="login-button">Sign In</button>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.globe.min.js"></script>
    <script>
        // Vanta.js Animation Initialization
        VANTA.GLOBE({
            el: "#vanta-canvas",
            mouseControls: true,
            touchControls: true,
            gyroControls: false,
            minHeight: 200.00,
            minWidth: 200.00,
            scale: 1.00,
            scaleMobile: 1.00,
            color: 0x8a2be2, // Primary color
            color2: 0xffffff, // Secondary color (dots)
            backgroundColor: document.documentElement.classList.contains('light-mode') ? 0xf0f2f5 : 0x0c0a18,
            size: 0.7
        });

        // Theme Toggler Logic
        const themeToggler = document.getElementById('theme-toggler');
        const htmlElement = document.documentElement;

        themeToggler.addEventListener('click', () => {
            htmlElement.classList.toggle('light-mode');
            const isLightMode = htmlElement.classList.contains('light-mode');
            themeToggler.innerHTML = isLightMode ? '<i class="fa-solid fa-sun"></i>' : '<i class="fa-solid fa-moon"></i>';

            // Note: Vanta.js background doesn't update dynamically.
            // For a full implementation, you would destroy and re-init Vanta on theme change,
            // or simply accept that the background animation color remains consistent.
        });
    </script>
</body>

</html>