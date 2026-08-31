<?php
ob_start();
include 'db.php';

if (isset($_POST['submit'])) {
    $rating = isset($_POST['rating']) ? (int) $_POST['rating'] : NULL;
    $student_interest = isset($_POST['student_interest']) ? implode(',', $_POST['student_interest']) : NULL;
    $business_needs = isset($_POST['business_needs']) ? implode(',', $_POST['business_needs']) : NULL;

    $stmt = $conn->prepare("INSERT INTO lead_capture 
        (full_name, mobile, city, user_type, student_status, student_goal, student_interest,
         business_type, running_ads, business_needs, challenge, offer, contact_preference, rating) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "sssssssssssssi",
        $_POST['full_name'],
        $_POST['mobile'],
        $_POST['city'],
        $_POST['user_type'],
        $_POST['student_status'],
        $_POST['student_goal'],
        $student_interest,
        $_POST['business_type'],
        $_POST['running_ads'],
        $business_needs,
        $_POST['challenge'],
        $_POST['offer'],
        $_POST['contact_preference'],
        $rating
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
    <title>Lead Capture</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <style>
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
            max-width: 580px;
        }

        /* ─── Brand header ─── */
        .brand-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 350px;
            height: 150px;
            border-radius: 16px;
            margin-bottom: 16px;
            overflow: hidden;
        }

        .logo-wrap img {
            max-width: 350px;
            height: auto;
            display: block;
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
            color: black;
        }

        .field input,
        .field select,
        .field textarea {
            background: white;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: var(--radius);
            padding: 14px 16px;
            font-size: 14px;
            font-family: 'DM Sans',
                sans-serif;
            color: black;
            width: 100%;
            transition: var(--transition);
            appearance: none;
            -webkit-appearance: none;
            outline: none;
        }

        .field select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='8' viewBox='0 0 14 8'%3E%3Cpath d='M1 1l6 6 6-6' stroke='%23C9A84C' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 40px;
            cursor: pointer;
        }

        .field input[type="text"]:focus,
        .field select:focus {
            border-color: white;
            background: rgb(201, 168, 76);
            box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.08);
        }

        .field input[type="text"]::placeholder {
            color: rgb(0, 0, 0);
            font-size: 13px;
        }

        .field select option {
            background: #1A1F2E;
            color: white;
        }

        /* ─── Multi-select hint ─── */
        .multi-hint {
            font-size: 11px;
            color: rgba(201, 168, 76, 0.5);
            letter-spacing: 0.3px;
            margin-top: -4px;
        }

        /* ─── Conditional sections ─── */
        .conditional-section {
            display: none;
            flex-direction: column;
            gap: 18px;
            padding: 20px;
            background: rgba(201, 168, 76, 0.04);
            border: 1px solid rgba(201, 168, 76, 0.12);
            border-radius: var(--radius);
            animation: stepIn 0.3s ease both;
        }

        .conditional-section.visible {
            display: flex;
        }

        .section-tag {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gold);
            font-weight: 500;
            margin-bottom: -6px;
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

        /* ─── Success banner ─── */
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

        .field.has-error input,
        .field.has-error select,
        .field.has-error textarea {
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

        .field select[multiple] {
            height: 120px;
            padding: 10px;
            border-radius: 10px;
            background: #ffffff;
            color: #000;
            overflow-y: auto;
        }

        /* Options styling */
        .field select[multiple] option {
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 5px;
            background: #1A1F2E;
            color: #fff;
        }

        /* Selected option */
        .field select[multiple] option:checked {
            background: #C9A84C;
            color: #000;
            font-weight: 500;
        }

        .checkbox-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 8px;
        }

        .checkbox-group label {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 12px;
            border-radius: 12px;
            background: rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: all 0.25s ease;
            font-size: 13px;
            border: 1px solid transparent;
            position: relative;
        }

        /* Hide default checkbox */
        .checkbox-group input {
            width: 18px;
            height: 18px;
            accent-color: #C9A84C;
            cursor: pointer;
        }

        /* Text */
        .checkbox-group span {
            flex: 1;
        }

        /* Hover */
        .checkbox-group label:hover {
            border-color: rgb(255, 255, 255);
        }

        /* Checked state */
        .checkbox-group input:checked+span {
            font-weight: 600;
        }

        /* Full card highlight */
        .checkbox-group label:has(input:checked) {
            background: rgb(18, 63, 152);
            color: rgb(18, 63, 152)00;
            border-color: #C9A84C;
        }

        .checkbox-group label:has(input:checked) span {
            color: var(--text-primary);
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
                    <span class="logo-fallback">LC</span>
                <?php endif; ?>
            </div>
            <h1>Lead Capture</h1>
            <p>Let us connect you with the right opportunity</p>
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
                        <div class="success-text">Submitted — we'll be in touch soon!</div>
                        <div class="success-sub">Our team will reach out on your preferred contact.</div>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" id="leadForm" novalidate>

                <!-- ── Step 1: Basic Info ── -->
                <div class="form-step active" id="step-1">
                    <div class="step-header">
                        <div class="step-eyebrow">Step 1 · Basics</div>
                        <div class="step-title">Who are you?</div>
                    </div>
                    <div class="field-group">
                        <div class="field-row">
                            <div class="field" id="f-name">
                                <label for="full_name">Full Name</label>
                                <input type="text" id="full_name" name="full_name" placeholder="Your name"
                                    autocomplete="name">
                                <span class="field-error">Name is required</span>
                            </div>
                            <div class="field" id="f-mobile">
                                <label for="mobile">Mobile Number</label>
                                <input type="tel" id="mobile" name="mobile" placeholder="10-digit number"
                                    maxlength="10">
                                <span class="field-error">Valid mobile number required</span>
                            </div>
                        </div>
                        <div class="field-row">
                            <div class="field" id="f-city">
                                <label for="city">City</label>
                                <input list="karnatakaCities" id="city" name="city" placeholder="Select or type city"
                                    class="styled-input">
                                <datalist id="karnatakaCities">
                                    <option value="Bengaluru">
                                    <option value="Mysuru">
                                    <option value="Hubballi">
                                    <option value="Dharwad">
                                    <option value="Belagavi">
                                    <option value="Mangaluru">
                                    <option value="Davangere">
                                    <option value="Ballari">
                                    <option value="Shivamogga">
                                    <option value="Tumakuru">
                                    <option value="Udupi">
                                    <option value="Bidar">
                                    <option value="Vijayapura">
                                    <option value="Raichur">
                                    <option value="Kolar">
                                    <option value="Hassan">
                                    <option value="Mandya">
                                    <option value="Bagalkot">
                                    <option value="Gadag">
                                    <option value="Haveri">
                                    <option value="Chitradurga">
                                    <option value="Hospet">
                                    <option value="Bhadravati">
                                    <option value="Sagara">
                                </datalist>
                                <span class="field-error">City is required</span>
                            </div>
                            <div class="field" id="f-user-type">
                                <label for="user_type">You are a</label>
                                <select id="user_type" name="user_type" onchange="toggleSections()">
                                    <option value="" disabled selected>Select</option>
                                    <option>Student</option>
                                    <option>Business Owner</option>
                                    <option>Working Professional</option>
                                    <option>Startup Founder</option>
                                </select>
                                <span class="field-error">Please select</span>
                            </div>
                        </div>

                        <!-- Student Section -->
                        <div class="conditional-section" id="studentSection">
                            <div class="section-tag">Student Details</div>
                            <div class="field-row">
                                <div class="field">
                                    <label for="student_status">Current Status</label>
                                    <select id="student_status" name="student_status">
                                        <option value="" disabled selected>Select</option>
                                        <option>School</option>
                                        <option>PUC</option>
                                        <option>Degree</option>
                                        <option>Graduate</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <label for="student_goal">Your Goal</label>
                                    <select id="student_goal" name="student_goal">
                                        <option value="" disabled selected>Select</option>
                                        <option>Job</option>
                                        <option>Higher Studies</option>
                                        <option>Skill Upgrade</option>
                                        <option>Not Sure</option>
                                    </select>
                                </div>
                            </div>
                            <div class="field">
                                <label for="student_interest">Interested In</label>

                                <div class="checkbox-group">
                                    <label><input type="checkbox" name="student_interest[]" value="Data Analytics">
                                        <span>Data Analytics</span></label>
                                    <label><input type="checkbox" name="student_interest[]" value="AI">
                                        <span>AI</span></label>
                                    <label><input type="checkbox" name="student_interest[]" value="Digital Marketing">
                                        <span>Digital Marketing</span></label>
                                    <label><input type="checkbox" name="student_interest[]" value="Programming">
                                        <span>Programming</span></label>
                                </div>

                                <span class="multi-hint">Select multiple (Ctrl / Cmd)</span>
                            </div>
                        </div>

                        <!-- Business Section -->
                        <div class="conditional-section" id="businessSection">
                            <div class="section-tag">Business Details</div>
                            <div class="field-row">
                                <div class="field">
                                    <label for="business_type">Business Type</label>
                                    <select id="business_type" name="business_type">
                                        <option value="" disabled selected>Select</option>
                                        <option>Retail Shop</option>
                                        <option>Coaching Institute</option>
                                        <option>Service Business</option>
                                        <option>Startup</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <label for="running_ads">Running Ads?</label>
                                    <select id="running_ads" name="running_ads">
                                        <option value="" disabled selected>Select</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="field">
                                <label for="business_needs">Business Needs</label>
                                <div class="checkbox-group">
                                    <label>
                                        <input type="checkbox" name="business_needs[]" value="Lead Generation">
                                        <span>Lead Generation</span>
                                    </label>

                                    <label>
                                        <input type="checkbox" name="business_needs[]" value="Social Media">
                                        <span>Social Media</span>
                                    </label>

                                    <label>
                                        <input type="checkbox" name="business_needs[]" value="Sales Analytics">
                                        <span>Sales Analytics</span>
                                    </label>

                                    <label>
                                        <input type="checkbox" name="business_needs[]" value="Website">
                                        <span>Website</span>
                                    </label>
                                    
                                    <label>
                                        <input type="checkbox" name="business_needs[]" value="Digital Marketing">
                                        <span>Digital Marketing</span>
                                    </label>
                                </div>
                                <span class="multi-hint">Hold Ctrl / Cmd to select multiple</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-nav">
                        <button type="button" class="btn-next" onclick="nextStep(1)">Continue →</button>
                    </div>
                </div>

                <!-- ── Step 2: Intent & Challenge ── -->
                <div class="form-step" id="step-2">
                    <div class="step-header">
                        <div class="step-eyebrow">Step 2 · Intent</div>
                        <div class="step-title">What are you looking for?</div>
                    </div>
                    <div class="field-group">
                        <div class="field" id="f-challenge">
                            <label for="challenge">Your Biggest Challenge</label>
                            <textarea id="challenge" name="challenge"
                                placeholder="Tell us what's holding you back..."></textarea>
                            <span class="field-error">Please describe your challenge</span>
                        </div>
                        <div class="field-row">
                            <div class="field" id="f-offer">
                                <label for="offer">What do you want?</label>
                                <select id="offer" name="offer">
                                    <option value="" disabled selected>Select</option>
                                    <option>Free Career Guidance</option>
                                    <option>Free Demo Class</option>
                                    <option>Free Business Audit</option>
                                    <option>Free Marketing Strategy</option>
                                </select>
                                <span class="field-error">Please select</span>
                            </div>
                            <div class="field" id="f-contact">
                                <label for="contact_preference">Contact Preference</label>
                                <select id="contact_preference" name="contact_preference">
                                    <option value="" disabled selected>Select</option>
                                    <option>Call</option>
                                    <option>WhatsApp</option>
                                    <option>Both</option>
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

                <!-- ── Step 3: Rating ── -->
                <div class="form-step" id="step-3">
                    <div class="step-header">
                        <div class="step-eyebrow">Step 3 · Experience</div>
                        <div class="step-title">How was your experience?</div>
                    </div>
                    <div class="field-group">
                        <div class="rating-block">
                            <div class="rating-label">Rate Your Visit</div>
                            <div class="star-rating">
                                <input type="radio" name="rating" value="5" id="r5"><label for="r5">★</label>
                                <input type="radio" name="rating" value="4" id="r4"><label for="r4">★</label>
                                <input type="radio" name="rating" value="3" id="r3"><label for="r3">★</label>
                                <input type="radio" name="rating" value="2" id="r2"><label for="r2">★</label>
                                <input type="radio" name="rating" value="1" id="r1"><label for="r1">★</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-nav">
                        <button type="button" class="btn-prev" onclick="prevStep(3)">← Back</button>
                        <button type="submit" name="submit" class="btn-submit">Submit Lead ✓</button>
                    </div>
                </div>

            </form>
        </div>

        <div class="form-footer" style="color: #F0EDE6;">Event Analytics Partner <br>
            <a href="https://dharwadhubballitutor.com"><span
                    style="font-size: 19px; font-weight: bold; color: aliceblue;">DharwadHubballiTutor</span></a>
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

        function toggleSections() {
            const type = document.getElementById('user_type').value;
            const isStudent = type === 'Student';
            const isBusiness = type === 'Business Owner' || type === 'Startup Founder';

            const studentEl = document.getElementById('studentSection');
            const businessEl = document.getElementById('businessSection');

            studentEl.classList.toggle('visible', isStudent);
            businessEl.classList.toggle('visible', isBusiness);
        }

        function validateStep(step) {
            let valid = true;
            document.getElementById('step-' + step).querySelectorAll('.field').forEach(f => f.classList.remove('has-error'));

            if (step === 1) {
                const name = document.getElementById('full_name').value.trim();
                const mobile = document.getElementById('mobile').value.trim();
                const city = document.getElementById('city').value.trim();
                const type = document.getElementById('user_type').value;

                if (!name) { document.getElementById('f-name').classList.add('has-error'); valid = false; }
                if (!/^\d{10}$/.test(mobile)) { document.getElementById('f-mobile').classList.add('has-error'); valid = false; }
                if (!city) { document.getElementById('f-city').classList.add('has-error'); valid = false; }
                if (!type) { document.getElementById('f-user-type').classList.add('has-error'); valid = false; }
            }

            if (step === 2) {
                const challenge = document.getElementById('challenge').value.trim();
                const offer = document.getElementById('offer').value;
                const contact = document.getElementById('contact_preference').value;

                if (!challenge) { document.getElementById('f-challenge').classList.add('has-error'); valid = false; }
                if (!offer) { document.getElementById('f-offer').classList.add('has-error'); valid = false; }
                if (!contact) { document.getElementById('f-contact').classList.add('has-error'); valid = false; }
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