<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <title>Success</title>
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #0f2a6b 0%, #1e3c72 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }
        .box {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border-radius: 20px;
            padding: 48px 40px 40px 40px;
            text-align: center;
            width: 100%;
            max-width: 420px;
            position: relative;
            color: #222;
            animation: fadeIn 1s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(40px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .success-icon {
            background: linear-gradient(135deg, #c9a84c 60%, #ffe082 100%);
            border-radius: 50%;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px auto;
            box-shadow: 0 4px 16px rgba(201,168,76,0.25);
        }
        .success-icon svg {
            width: 38px;
            height: 38px;
            color: #fff;
        }
        h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.2rem;
            margin: 0 0 12px 0;
            font-weight: 600;
            color: #0f2a6b;
        }
        p {
            font-size: 1.1rem;
            margin-bottom: 28px;
            color: #444;
        }
        .btn {
            display: inline-block;
            padding: 12px 32px;
            background: linear-gradient(90deg, #c9a84c 60%, #ffe082 100%);
            color: #0f2a6b;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(201,168,76,0.15);
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }
        .btn:hover {
            background: linear-gradient(90deg, #ffe082 60%, #c9a84c 100%);
            color: #222;
            box-shadow: 0 4px 16px rgba(201,168,76,0.25);
        }
        @media (max-width: 500px) {
            .box {
                padding: 32px 10px 28px 10px;
                max-width: 95vw;
            }
        }
    </style>
</head>
<body>
    <div class="box">
        <div class="success-icon">
            <svg viewBox="0 0 52 52" fill="none">
                <circle cx="26" cy="26" r="25" stroke="white" stroke-width="2"/>
                <path d="M16 27.5L23 34.5L36 19.5" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h2>Thank You!</h2>
        <p>Your response has been submitted successfully.<br>We appreciate your feedback.</p>
    </div>
</body>
</html>