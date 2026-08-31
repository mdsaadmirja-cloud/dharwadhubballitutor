<?php
// lms/views/login.php
session_start();
if (isset($_SESSION['user'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin_dashboard.php');
        exit();
    } else {
        header('Location: student_dashboard.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/img/favicon.png">
    <title>Welcome | DharwadHubballiTutor LMS</title>
    <style>
        :root {
            --primary-dark:#2a0b5e;
            --secondary-dark: #2a0b5e;
            --primary-light: #f9fafb;
            --primary-accent: #f1ba08;
            --primary-accent-hover: #f1ba08;
            --text-light: #f3f4f6;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --border-color: #d1d5db;
        }

        * {
            box-sizing: border-box;
        }

        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .left-pane, .right-pane {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .left-pane {
            background: linear-gradient(160deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
            position: relative;
            padding: 40px;
            color: var(--text-light);
            text-align: center;
            flex-direction: column;
        }

        #particle-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .branding-content {
            z-index: 2;
            animation: fadeIn 1s ease-out;
        }

        .branding-logo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 24px;
            border: 3px solid rgba(255,255,255,0.1);
        }

        .branding-title {
            font-size: 2em;
            font-weight: 700;
            margin: 0 0 10px 0;
        }

        .branding-subtitle {
            font-size: 1em;
            color: var(--text-muted);
            max-width: 400px;
        }

        .right-pane {
            background: var(--primary-light);
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }

        .login-card > * {
            opacity: 0;
            animation: slideInUp 0.7s forwards;
        }

        .login-title {
            font-size: 2em;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 10px;
            animation-delay: 0.2s;
        }

        .login-subtitle {
            color: var(--text-muted);
            margin-bottom: 40px;
            font-size: 1em;
            animation-delay: 0.3s;
        }

        .form-group {
            position: relative;
            margin-bottom: 30px;
            animation-delay: 0.4s;
        }

        .form-input {
            width: 100%;
            padding: 12px 0;
            font-size: 1em;
            border: none;
            border-bottom: 2px solid var(--border-color);
            background: transparent;
            outline: none;
            color: var(--text-dark);
            transition: border-color 0.3s;
        }

        .form-label {
            position: absolute;
            top: 12px;
            left: 0;
            font-size: 1em;
            color: var(--text-muted);
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .form-input:focus ~ .form-label,
        .form-input:not(:placeholder-shown) ~ .form-label {
            top: -16px;
            font-size: 0.8em;
            color: var(--primary-accent);
        }

        .form-input:focus {
            border-bottom-color: var(--primary-accent);
        }

        .google-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 600;
            color: var(--text-light);
            background: var(--primary-accent);
            cursor: pointer;
            transition: background-color 0.3s;
            z-index: 1;
            animation-delay: 0.5s;
        }

        .google-btn:hover {
            background-color: var(--primary-accent-hover);
        }

        .google-icon {
            width: 22px;
            height: 22px;
            background: url('https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg') no-repeat center/contain;
            filter: brightness(0) invert(1);
        }

        .footer-text {
            margin-top: 24px;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9em;
            animation-delay: 0.6s;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .left-pane {
                height: 50vh;
                padding: 20px;
            }

            .right-pane {
                height: 50vh;
                padding: 20px;
                align-items: flex-start;
            }

            .branding-title {
                font-size: 1.5em;
            }

            .branding-subtitle {
                font-size: 0.9em;
            }

            .login-card {
                padding: 20px;
                max-width: 90%;
                margin: 0 auto;
            }

            .login-title {
                font-size: 1.5em;
            }

            .login-subtitle {
                font-size: 0.9em;
            }

            .google-btn {
                padding: 12px;
                font-size: 0.95em;
            }
        }

        @media (max-width: 480px) {
            .branding-logo {
                width: 80px;
                height: 80px;
            }

            .login-title {
                font-size: 1.4em;
            }

            .login-subtitle,
            .footer-text {
                font-size: 0.85em;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="left-pane">
        <canvas id="particle-canvas"></canvas>
        <div class="branding-content">
            <img src="../img/logo.png" alt="DharwadHubballiTutor Logo" class="branding-logo">
            <h1 class="branding-title">DharwadHubballiTutor</h1>
            <p class="branding-subtitle">Empowering the next generation of learners through technology.</p>
        </div>
    </div>

    <div class="right-pane">
        <div class="login-card">
            <h2 class="login-title">Welcome Back</h2>
            <p class="login-subtitle">Please sign in to access your dashboard.</p>

            <a class="google-btn" href="../controller/googleCallback.php">
                <span class="google-icon" aria-hidden="true"></span>
                <span>Sign in with Google</span>
            </a>

            <p class="footer-text"><small>Only authorized student accounts are permitted.</small></p>
        </div>
    </div>
</div>

<script>
    const canvas = document.getElementById('particle-canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = document.querySelector('.left-pane').offsetHeight;

    let particlesArray;

    const mouse = {
        x: null,
        y: null,
        radius: 150
    };

    window.addEventListener('mousemove', event => {
        mouse.x = event.x;
        mouse.y = event.y;
    });

    class Particle {
        constructor(x, y, directionX, directionY, size, color) {
            this.x = x;
            this.y = y;
            this.directionX = directionX;
            this.directionY = directionY;
            this.size = size;
            this.color = color;
        }

        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2, false);
            ctx.fillStyle = 'rgb(215, 215, 6)';
            ctx.fill();
        }

        update() {
            if (this.x > canvas.width || this.x < 0) {
                this.directionX = -this.directionX;
            }
            if (this.y > canvas.height || this.y < 0) {
                this.directionY = -this.directionY;
            }
            this.x += this.directionX;
            this.y += this.directionY;
            this.draw();
        }
    }

    function init() {
        particlesArray = [];
        let numberOfParticles = (canvas.height * canvas.width) / 9000;
        for (let i = 0; i < numberOfParticles; i++) {
            let size = (Math.random() * 2) + 1;
            let x = Math.random() * (canvas.width - size * 2) + size * 2;
            let y = Math.random() * (canvas.height - size * 2) + size * 2;
            let directionX = (Math.random() * 0.4) - 0.2;
            let directionY = (Math.random() * 0.4) - 0.2;
            particlesArray.push(new Particle(x, y, directionX, directionY, size));
        }
    }

    function connect() {
        for (let a = 0; a < particlesArray.length; a++) {
            for (let b = a; b < particlesArray.length; b++) {
                let dx = particlesArray[a].x - particlesArray[b].x;
                let dy = particlesArray[a].y - particlesArray[b].y;
                let distance = dx * dx + dy * dy;
                if (distance < (canvas.width / 7) * (canvas.height / 7)) {
                    let opacityValue = 1 - (distance / 20000);
                    ctx.strokeStyle = `rgba(107, 114, 128, ${opacityValue})`;
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(particlesArray[a].x, particlesArray[a].y);
                    ctx.lineTo(particlesArray[b].x, particlesArray[b].y);
                    ctx.stroke();
                }
            }
        }
    }

    function animate() {
        requestAnimationFrame(animate);
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        particlesArray.forEach(p => p.update());
        connect();
    }

    window.addEventListener('resize', () => {
        canvas.width = window.innerWidth;
        canvas.height = document.querySelector('.left-pane').offsetHeight;
        init();
    });

    window.addEventListener('mouseout', () => {
        mouse.x = undefined;
        mouse.y = undefined;
    });

    init();
    animate();
</script>
</body>
</html>
