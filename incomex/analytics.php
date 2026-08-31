<?php
ob_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Analytics</title>
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

        /* ─── Background grid ─── */
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

        /* ─── Wrapper ─── */
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
            color: black;
        }

        .field input[type="text"],
        .field select {
            background: white;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: var(--radius);
            padding: 14px 16px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
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
            background: rgb(0, 255, 106);
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
            color: rgb(0, 0, 0);
            font-weight: 500;
        }

        .success-sub {
            font-size: 12px;
            color: rgba(0, 0, 0, 0.6);
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

        /* Fix city input to match select */
        .styled-input {
            background: white !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: var(--radius);
            padding: 14px 16px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: var(--text-primary);
            width: 100%;
            transition: var(--transition);
            outline: none;
        }

        .styled-input:focus {
            border-color: white;
            background: rgba(201, 168, 76, 0.04);
            box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.08);
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
                    <span class="logo-fallback">VA</span>
                <?php endif; ?>
            </div>
            <h1>Visitor Analytics</h1>
            <p>Help us understand you better — takes just 60 seconds</p>
        </div>

        <!-- Progress bar (rendered by JS) -->
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
                        <div class="success-text">Response recorded — thank you!</div>
                        <div class="success-sub">Your feedback helps us serve you better.</div>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" id="analyticsForm" novalidate>

                <!-- ── Step 1: About You ── -->
                <div class="form-step active" id="step-1">
                    <div class="step-header">
                        <div class="step-eyebrow">Step 1 · Identity</div>
                        <div class="step-title">Tell us about yourself</div>
                    </div>
                    <div class="field-group">
                        <div class="field-row">
                            <div class="field" id="f-name">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" placeholder="Your name" autocomplete="name">
                                <span class="field-error">Name is required</span>
                            </div>
                            <div class="field" id="f-city">
                                <label for="city">City</label>
                                <input list="karnatakaCities" id="city" name="city"
                                    placeholder="Select or type your city" class="styled-input">
                                <span class="field-error">City is required</span>

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
                                    <option value="Chikkamagaluru">
                                    <option value="Hassan">
                                    <option value="Mandya">
                                    <option value="Bagalkot">
                                    <option value="Gadag">
                                    <option value="Haveri">
                                    <option value="Chitradurga">
                                    <option value="Koppal">
                                    <option value="Yadgir">
                                    <option value="Ramanagara">
                                    <option value="Chamarajanagar">
                                    <option value="Kodagu">
                                    <option value="Karwar">
                                    <option value="Sirsi">
                                    <option value="Dandeli">
                                    <option value="Bhatkal">
                                    <option value="Honnavar">
                                    <option value="Gokarna">
                                    <option value="Ullal">
                                    <option value="Puttur">
                                    <option value="Sullia">
                                    <option value="Moodbidri">
                                    <option value="Karkala">
                                    <option value="Bantwal">
                                    <option value="Sagara">
                                    <option value="Bhadravati">
                                    <option value="Tirthahalli">
                                    <option value="Arsikere">
                                    <option value="Holalkere">
                                    <option value="Hosadurga">
                                    <option value="Harihar">
                                    <option value="Ranebennur">
                                    <option value="Byadgi">
                                    <option value="Shiggaon">
                                    <option value="Sindhanur">
                                    <option value="Manvi">
                                    <option value="Lingasugur">
                                    <option value="Basavakalyan">
                                    <option value="Humnabad">
                                    <option value="Aurad">
                                    <option value="Sedam">
                                    <option value="Afzalpur">
                                    <option value="Jewargi">
                                    <option value="Chincholi">
                                    <option value="Shahpur">
                                    <option value="Surpur">
                                    <option value="Muddebihal">
                                    <option value="Indi">
                                    <option value="Jamkhandi">
                                    <option value="Mudhol">
                                    <option value="Gokak">
                                    <option value="Athani">
                                    <option value="Chikodi">
                                    <option value="Nipani">
                                    <option value="Ramdurg">
                                    <option value="Saundatti">
                                    <option value="Bailhongal">
                                    <option value="Kudligi">
                                    <option value="Sandur">
                                    <option value="Hospet">
                                    <option value="Kamalapur">
                                    <option value="Molakalmuru">
                                </datalist>
                            </div>
                        </div>
                        <div class="field-row">
                            <div class="field" id="f-age">
                                <label for="age_group">Age Group</label>
                                <select id="age_group" name="age_group">
                                    <option value="" disabled selected>Select</option>
                                    <option>15–20</option>
                                    <option>20–30</option>
                                    <option>30–45</option>
                                    <option>45+</option>
                                </select>
                                <span class="field-error">Please select</span>
                            </div>
                            <div class="field" id="f-user-type">
                                <label for="user_type">You are a</label>
                                <select id="user_type" name="user_type">
                                    <option value="" disabled selected>Select</option>
                                    <option>Student</option>
                                    <option>Business Owner</option>
                                    <option>Employee</option>
                                    <option>Entrepreneur</option>
                                </select>
                                <span class="field-error">Please select</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-nav">
                        <button type="button" class="btn-next"
                            style="color: #F0EDE6; background-color: rgb(18, 63, 152);" onclick="nextStep(1)">Continue
                            →</button>
                    </div>
                </div>

                <!-- ── Step 2: Intent ── -->
                <div class="form-step" id="step-2">
                    <div class="step-header">
                        <div class="step-eyebrow">Step 2 · Intent</div>
                        <div class="step-title">What brought you here?</div>
                    </div>
                    <div class="field-group">
                        <div class="field" id="f-purpose">
                            <label for="visit_purpose">Visit Purpose</label>
                            <select id="visit_purpose" name="visit_purpose">
                                <option value="" disabled selected>Select purpose</option>
                                <option>Business</option>
                                <option>Job / Internship</option>
                                <option>Learning</option>
                                <option>Purchase</option>
                                <option>Networking</option>
                            </select>
                            <span class="field-error">Please select</span>
                        </div>
                        <div class="field" id="f-interest">
                            <label for="interest_category">Interest Category</label>
                            <select id="interest_category" name="interest_category">
                                <option value="" disabled selected>Select interest</option>
                                <option>Manufacturing</option>
                                <option>Education &amp; Training</option>
                                <option>Technology / IT</option>
                                <option>Retail Products</option>
                                <option>Agriculture</option>
                                <option>Services</option>
                            </select>
                            <span class="field-error">Please select</span>
                        </div>
                        <div class="field" id="f-source">
                            <label for="source">How did you know about the event?</label>
                            <select id="source" name="source">
                                <option value="" disabled selected>Select option</option>
                                <option>Social Media</option>
                                <option>Hoardings</option>
                                <option>Newspaper</option>
                                <option>Auto Advertisement</option>
                                <option>Friends / Referral</option>
                                <option>Other</option>
                            </select>
                            <span class="field-error">Please select</span>
                        </div>
                        <div class="field-row">
                            <div class="field" id="f-plan">
                                <label for="plan_action">Planning to</label>
                                <select id="plan_action" name="plan_action">
                                    <option value="" disabled selected>Select plan</option>
                                    <option>Buy something</option>
                                    <option>Invest in business</option>
                                    <option>Take a service</option>
                                    <option>Just exploring</option>
                                </select>
                                <span class="field-error">Please select</span>
                            </div>
                            <div class="field" id="f-budget">
                                <label for="budget_range">Budget Range</label>
                                <select id="budget_range" name="budget_range">
                                    <option value="" disabled selected>Select budget</option>
                                    <option>Below ₹10,000</option>
                                    <option>₹10,000 – ₹50,000</option>
                                    <option>₹50,000 – ₹5 Lakhs</option>
                                    <option>₹5 Lakhs+</option>
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
                        <div class="step-eyebrow">Step 3 · Feedback</div>
                        <div class="step-title">Rate your experience</div>
                    </div>
                    <div class="field-group">
                        <div class="rating-block">
                            <div class="rating-label">Overall Experience</div>
                            <div class="star-rating">
                                <input type="radio" name="rating" value="5" id="star5"><label for="star5">★</label>
                                <input type="radio" name="rating" value="4" id="star4"><label for="star4">★</label>
                                <input type="radio" name="rating" value="3" id="star3"><label for="star3">★</label>
                                <input type="radio" name="rating" value="2" id="star2"><label for="star2">★</label>
                                <input type="radio" name="rating" value="1" id="star1"><label for="star1">★</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-nav">
                        <button type="button" class="btn-prev" onclick="prevStep(3)">← Back</button>
                        <button type="submit" name="submit" class="btn-submit">Submit Analytics ✓</button>
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
        let currentStep = 1;

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
            currentStep = step;
        }

        function validateStep(step) {
            let valid = true;
            const stepEl = document.getElementById('step-' + step);

            // Clear all errors in this step
            stepEl.querySelectorAll('.field').forEach(f => f.classList.remove('has-error'));

            if (step === 1) {
                const nameVal = document.getElementById('name').value.trim();
                const cityVal = document.getElementById('city').value.trim();
                const ageVal = document.getElementById('age_group').value;
                const typeVal = document.getElementById('user_type').value;

                if (!nameVal) { document.getElementById('f-name').classList.add('has-error'); valid = false; }
                if (!cityVal) { document.getElementById('f-city').classList.add('has-error'); valid = false; }
                if (!ageVal) { document.getElementById('f-age').classList.add('has-error'); valid = false; }
                if (!typeVal) { document.getElementById('f-user-type').classList.add('has-error'); valid = false; }
            }

            if (step === 2) {
                const fields = [
                    ['visit_purpose', 'f-purpose'],
                    ['interest_category', 'f-interest'],
                    ['source', 'f-source'],
                    ['plan_action', 'f-plan'],
                    ['budget_range', 'f-budget'],
                ];
                fields.forEach(([id, fid]) => {
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

        // If success param present, keep showing the first step clean
        if (window.location.search.includes('success')) {
            showStep(1);
        }
    </script>

    <?php
    if (isset($_POST['submit'])) {
        $rating = isset($_POST['rating']) ? (int) $_POST['rating'] : NULL;

       $stmt = $conn->prepare("INSERT INTO visitor_responses 
(name, city, age_group, visit_purpose, interest_category, user_type, plan_action, budget_range, source, rating) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "sssssssssi",
            $_POST['name'],
            $_POST['city'],
            $_POST['age_group'],
            $_POST['visit_purpose'],
            $_POST['interest_category'],
            $_POST['user_type'],
            $_POST['plan_action'],
            $_POST['budget_range'],
            $_POST['source'],
            $rating
        );

        if ($stmt->execute()) {
            header("Location: success.php");
exit();
        }

        $stmt->close();
    }
    ?>

</body>

</html>
<?php ob_end_flush(); ?>