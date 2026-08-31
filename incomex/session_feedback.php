<?php
ob_start();
include 'db.php';

if (isset($_POST['submit'])) {
    $rating = isset($_POST['session_rating']) ? (int) $_POST['session_rating'] : NULL;

    $stmt = $conn->prepare("INSERT INTO session_feedback 
        (session_name, user_category, session_rating, usefulness, speaker_rating, liked_most, outcome) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "ssissss",
        $_POST['session_name'],
        $_POST['user_category'],
        $rating,
        $_POST['usefulness'],
        $_POST['speaker_rating'],
        $_POST['liked_most'],
        $_POST['outcome']
    );

    if ($stmt->execute()) {
        header("Location: success.php");
            exit();
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Feedback</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <style>
     @media (max-width: 480px) {
    .logo-wrap {
        max-width: 300px;
    }
}

        :root {
            --gold: #C9A84C;
            --gold-light: #E8C97A;
            --gold-dim: rgba(201, 168, 76, 0.15);
            --dark: rgb(18, 63, 152);
            --dark-card: #C9A84C;
            --dark-surface: #161B26;
            --dark-border: rgba(201, 168, 76, 0.2);
            --text-primary: #F0EDE6;
            --text-muted: rgb(255, 255, 255);
            --text-accent: rgb(255, 255, 255);
            --success: rgb(0, 0, 0);
            --error: #E74C3C;
            --radius: 12px;
            --transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--dark);
            background-image:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(201, 168, 76, 0.07) 0%, transparent 60%),
                radial-gradient(ellipse 40% 30% at 80% 80%, rgba(201, 168, 76, 0.04) 0%, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            color: var(--text-primary);
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(201, 168, 76, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(201, 168, 76, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        .form-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 560px;
        }

        /* ─── Brand header ─── */
        .brand-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    max-width: 350px;
    height: auto;
    margin: 0 auto 16px;  /* center horizontally */
    overflow: visible;    /* ✅ IMPORTANT: stop cutting */
}

        .logo-wrap img {
    width: 100%;
    max-width: 100%;
    height: auto;
    object-fit: contain;
}

        .logo-fallback {
            font-family: 'Cormorant Garamond', serif;
            font-size: 28px;
            font-weight: 600;
            color: var(--gold);
            letter-spacing: 2px;
        }

        .brand-header h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 28px;
            font-weight: 500;
            color: var(--text-primary);
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .brand-header p {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 6px;
            letter-spacing: 0.3px;
        }

        /* ─── Progress bar ─── */
        .progress-bar-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 28px;
        }

        .progress-step {
            flex: 1;
            height: 2px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 2px;
            overflow: hidden;
            transition: var(--transition);
        }

        .progress-step.active {
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
        }

        .progress-step.done {
            background: var(--gold);
        }

        .progress-label {
            font-size: 11px;
            color: var(--text-muted);
            white-space: nowrap;
            letter-spacing: 0.5px;
        }

        /* ─── Card ─── */
        .form-card {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 20px;
            padding: 36px 40px;
            position: relative;
            overflow: hidden;
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            opacity: 0.6;
        }

        /* ─── Step header ─── */
        .step-header {
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .step-eyebrow {
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: white;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .step-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 22px;
            font-weight: 500;
            color: var(--text-primary);
            line-height: 1.3;
        }

        /* ─── Fields ─── */
        .field-group {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .field label {
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .field select {
            background: white;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='8' viewBox='0 0 14 8'%3E%3Cpath d='M1 1l6 6 6-6' stroke='%23C9A84C' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: var(--radius);
            padding: 14px 40px 14px 16px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: black;
            width: 100%;
            transition: var(--transition);
            appearance: none;
            -webkit-appearance: none;
            outline: none;
            cursor: pointer;
        }

        .field select:focus {
            border-color: var(--gold);
            background-color: rgba(201, 168, 76, 0.04);
            box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.08);
        }

        .field select option {
            background: #1A1F2E;
            color: var(--text-primary);
        }

        /* ─── Star rating ─── */
        .rating-block {
            margin-top: 4px;
        }

        .rating-block .rating-label {
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 12px;
        }

        .star-rating {
            direction: rtl;
            display: inline-flex;
            justify-content: flex-start;
            gap: 6px;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 32px;
            color: rgb(255, 255, 255);
            cursor: pointer;
            transition: all 0.2s ease;
            margin: 0;
            display: block;
            line-height: 1;
            text-shadow: none;
        }

        .star-rating input:checked~label {
            color: rgb(18, 63, 152);
            text-shadow: 0 0 12px rgba(201, 168, 76, 0.4);
        }

        .star-rating label:hover,
        .star-rating label:hover~label {
            color: rgb(18, 63, 152);
            text-shadow: 0 0 12px rgba(201, 168, 76, 0.3);
            transform: scale(1.15);
        }

        /* ─── Nav buttons ─── */
        .form-nav {
            display: flex;
            gap: 12px;
            margin-top: 32px;
        }

        .btn-prev {
            flex: 0 0 auto;
            padding: 14px 22px;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: var(--radius);
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-muted);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-prev:hover {
            border-color: var(--gold);
            color: var(--gold);
        }

        .btn-next,
        .btn-submit {
            flex: 1;
            padding: 16px 24px;
            background: linear-gradient(135deg, var(--gold) 0%, #A8832A 100%);
            border: none;
            border-radius: var(--radius);
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            font-weight: 500;
            color: #0A0C10;
            cursor: pointer;
            transition: var(--transition);
            letter-spacing: 0.3px;
            position: relative;
            overflow: hidden;
        }

        .btn-next::before,
        .btn-submit::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .btn-next:hover,
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(201, 168, 76, 0.3);
        }

        .btn-next:hover::before,
        .btn-submit:hover::before {
            opacity: 1;
        }

        .btn-next:active,
        .btn-submit:active {
            transform: translateY(0);
        }

        /* ─── Steps ─── */
        .form-step {
            display: none;
            animation: stepIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) both;
        }

        .form-step.active {
            display: block;
        }

        @keyframes stepIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ─── Success state ─── */
        .success-banner {
            background: rgba(46, 204, 113, 0.08);
            border: 1px solid rgba(46, 204, 113, 0.25);
            border-radius: var(--radius);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 28px;
            animation: stepIn 0.5s ease both;
        }

        .success-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(46, 204, 113, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 18px;
        }

        .success-text {
            font-size: 14px;
            color: #2ECC71;
            font-weight: 500;
        }

        .success-sub {
            font-size: 12px;
            color: rgba(46, 204, 113, 0.6);
            margin-top: 2px;
        }

        /* ─── Validation errors ─── */
        .field-error {
            font-size: 11px;
            color: var(--error);
            margin-top: -4px;
            display: none;
        }

        .field.has-error select {
            border-color: rgba(231, 76, 60, 0.5);
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.08);
        }

        .field.has-error .field-error {
            display: block;
        }

        /* ─── Footer ─── */
        .form-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: rgba(138, 139, 143, 0.5);
            letter-spacing: 0.3px;
        }

        /* ─── Responsive ─── */
        @media (max-width: 520px) {
            .form-card {
                padding: 28px 24px;
            }

            .field-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <div class="form-wrapper">

        <!-- Brand Header -->
        <div class="brand-header">
            <div class="logo-wrap">
                <?php if (file_exists('Screenshot 2026-04-23 122856.png')): ?>
                    <img src="Screenshot 2026-04-23 122856.png" alt="Logo">
                <?php else: ?>
                    <span class="logo-fallback">SF</span>
                <?php endif; ?>
            </div>
            <h1>Session Feedback</h1>
            <p>Rate your session experience — takes just 60 seconds</p>
        </div>

        <!-- Progress bar -->
        <div class="progress-bar-wrap">
            <div class="progress-step done" id="prog-1"></div>
            <div class="progress-step" id="prog-2"></div>
            <div class="progress-step" id="prog-3"></div>
            <span class="progress-label" id="prog-label">Step 1 of 3</span>
        </div>

        <!-- Card -->
        <div class="form-card">

            <?php if (isset($_GET['success'])): ?>
                <div class="success-banner">
                    <div class="success-icon">✓</div>
                    <div>
                        <div class="success-text">Feedback submitted — thank you!</div>
                        <div class="success-sub">Your input helps us improve future sessions.</div>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" id="sessionForm" novalidate>

                <!-- ── Step 1: Session & Profile ── -->
                <div class="form-step active" id="step-1">
                    <div class="step-header">
                        <div class="step-eyebrow">Step 1 · Profile</div>
                        <div class="step-title">Which session did you attend?</div>
                    </div>
                    <div class="field-group">
                        <div class="field" id="f-session">
                            <label for="session_name">Session Attended</label>
                            <select id="session_name" name="session_name">
                                <option value="" disabled selected>Select session</option>
                                <option>Startup Growth</option>
                                <option>AI &amp; Future</option>
                                <option>Digital Marketing</option>
                                <option>Business Scaling</option>
                            </select>
                            <span class="field-error">Please select a session</span>
                        </div>
                        <div class="field" id="f-category">
                            <label for="user_category">Your Category</label>
                            <select id="user_category" name="user_category">
                                <option value="" disabled selected>Select category</option>
                                <option>Student</option>
                                <option>Business Owner</option>
                                <option>Entrepreneur</option>
                                <option>Job Seeker</option>
                                <option>Professional</option>
                            </select>
                            <span class="field-error">Please select your category</span>
                        </div>
                    </div>
                    <div class="form-nav">
                        <button type="button" class="btn-next" onclick="nextStep(1)">Continue →</button>
                    </div>
                </div>

                <!-- ── Step 2: Session Quality ── -->
                <div class="form-step" id="step-2">
                    <div class="step-header">
                        <div class="step-eyebrow">Step 2 · Quality</div>
                        <div class="step-title">How was the session?</div>
                    </div>
                    <div class="field-group">
                        <div class="rating-block">
                            <div class="rating-label">Rate the Session</div>
                            <div class="star-rating">
                                <input type="radio" name="session_rating" value="5" id="s5"><label for="s5">★</label>
                                <input type="radio" name="session_rating" value="4" id="s4"><label for="s4">★</label>
                                <input type="radio" name="session_rating" value="3" id="s3"><label for="s3">★</label>
                                <input type="radio" name="session_rating" value="2" id="s2"><label for="s2">★</label>
                                <input type="radio" name="session_rating" value="1" id="s1"><label for="s1">★</label>
                            </div>
                        </div>
                        <div class="field-row">
                            <div class="field" id="f-usefulness">
                                <label for="usefulness">Was it useful?</label>
                                <select id="usefulness" name="usefulness">
                                    <option value="" disabled selected>Select</option>
                                    <option>Very Useful</option>
                                    <option>Useful</option>
                                    <option>Average</option>
                                    <option>Not Useful</option>
                                </select>
                                <span class="field-error">Please select</span>
                            </div>
                            <div class="field" id="f-speaker">
                                <label for="speaker_rating">Speaker Rating</label>
                                <select id="speaker_rating" name="speaker_rating">
                                    <option value="" disabled selected>Select</option>
                                    <option>Excellent</option>
                                    <option>Good</option>
                                    <option>Average</option>
                                    <option>Poor</option>
                                </select>
                                <span class="field-error">Please select</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-nav">
                        <button type="button" class="btn-prev" onclick="prevStep(2)">← Back</button>
                        <button type="button" class="btn-next" onclick="nextStep(2)">Continue →</button>
                    </div>
                </div>

                <!-- ── Step 3: Insights ── -->
                <div class="form-step" id="step-3">
                    <div class="step-header">
                        <div class="step-eyebrow">Step 3 · Insights</div>
                        <div class="step-title">What stood out for you?</div>
                    </div>
                    <div class="field-group">
                        <div class="field" id="f-liked">
                            <label for="liked_most">What did you like most?</label>
                            <select id="liked_most" name="liked_most">
                                <option value="" disabled selected>Select</option>
                                <option>Practical examples</option>
                                <option>Speaker knowledge</option>
                                <option>Interaction</option>
                                <option>Presentation style</option>
                                <option>Case studies</option>
                            </select>
                            <span class="field-error">Please select</span>
                        </div>
                        <div class="field" id="f-outcome">
                            <label for="outcome">After this session, you feel:</label>
                            <select id="outcome" name="outcome">
                                <option value="" disabled selected>Select</option>
                                <option>Learned something valuable</option>
                                <option>Motivated to act</option>
                                <option>Just informational</option>
                            </select>
                            <span class="field-error">Please select</span>
                        </div>
                    </div>
                    <div class="form-nav">
                        <button type="button" class="btn-prev" onclick="prevStep(3)">← Back</button>
                        <button type="submit" name="submit" class="btn-submit">Submit Feedback ✓</button>
                    </div>
                </div>

            </form>
        </div>

       <div class="form-footer" style="color: #F0EDE6;">Event Analytics Partner <br>
            <a href="https://dharwadhubballitutor.com"><span style="font-size: 19px; font-weight: bold; color: aliceblue;">DharwadHubballiTutor</span></a>
        </div>

    </div>

    <script>
        const STEPS = 3;

        function updateProgress(step) {
            for (let i = 1; i <= STEPS; i++) {
                const el = document.getElementById('prog-' + i);
                el.className = 'progress-step';
                if (i < step) el.classList.add('done');
                else if (i === step) el.classList.add('active');
            }
            document.getElementById('prog-label').textContent = 'Step ' + step + ' of ' + STEPS;
        }

        function showStep(step) {
            document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
            document.getElementById('step-' + step).classList.add('active');
            updateProgress(step);
        }

        function validateStep(step) {
            let valid = true;
            document.getElementById('step-' + step).querySelectorAll('.field').forEach(f => f.classList.remove('has-error'));

            if (step === 1) {
                [['session_name', 'f-session'], ['user_category', 'f-category']].forEach(([id, fid]) => {
                    if (!document.getElementById(id).value) {
                        document.getElementById(fid).classList.add('has-error');
                        valid = false;
                    }
                });
            }

            if (step === 2) {
                [['usefulness', 'f-usefulness'], ['speaker_rating', 'f-speaker']].forEach(([id, fid]) => {
                    if (!document.getElementById(id).value) {
                        document.getElementById(fid).classList.add('has-error');
                        valid = false;
                    }
                });
            }

            if (step === 3) {
                [['liked_most', 'f-liked'], ['outcome', 'f-outcome']].forEach(([id, fid]) => {
                    if (!document.getElementById(id).value) {
                        document.getElementById(fid).classList.add('has-error');
                        valid = false;
                    }
                });
            }

            return valid;
        }

        function nextStep(from) {
            if (!validateStep(from)) return;
            showStep(from + 1);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function prevStep(from) {
            showStep(from - 1);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        if (window.location.search.includes('success')) showStep(1);
    </script>

</body>

</html>
<?php ob_end_flush(); ?>