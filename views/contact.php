<?php
include('navigation.php');

/*
|--------------------------------------------------------------------------
| CONTACT PAGE
|--------------------------------------------------------------------------
| UI redesigned only.
| Existing enquiry backend, field names, course loading and reCAPTCHA
| flow are preserved.
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<style>
    /* =========================================================
       CONTACT PAGE
       Responsive / Zoom-safe / Navbar-aligned
       ========================================================= */

    .contact-page {
        --contact-navy: #14163a;
        --contact-navy-2: #1d1f4d;
        --contact-gold: #ffd700;
        --contact-gold-2: #f6be01;
        --contact-text: #22243d;
        --contact-muted: #6f7185;
        --contact-bg: #f7f8fc;
        --contact-white: #ffffff;

        width: 100%;
        background: var(--contact-bg);
        color: var(--contact-text);
        overflow-x: hidden;
    }

    /* =========================================================
       MAIN CONTACT HERO
       SAME MAXIMUM WIDTH AS NAVBAR
       ========================================================= */

    /* =========================================================
   CONTACT HERO
   Background image + controlled blur + dark overlay
   ========================================================= */

    .contact-hero {
        position: relative;

        width: min(1140px, calc(100% - 32px));
        min-height: clamp(600px, 76vh, 800px);

        margin: 18px auto 40px;

        border: 2px solid var(--contact-gold);
        border-radius: 22px;

        overflow: hidden;

        display: flex;
        align-items: stretch;

        isolation: isolate;

        background: #080d28;

        box-shadow:
            0 18px 50px rgba(20, 22, 58, 0.18);
    }


    /* =========================================================
   BACKGROUND IMAGE
   Image is blurred ONLY here.
   ========================================================= */

    .contact-hero::before {
        content: "";

        position: absolute;
        inset: -25px;

        background-image: url('../uploads/contact-hero-redesign.webp');

        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;

        filter: blur(5px);

        transform: scale(1.05);

        z-index: -2;
    }


    /* =========================================================
   DARK OVERLAY
   Makes white/yellow text clearly readable
   while keeping the background image visible.
   ========================================================= */

    .contact-hero::after {
        content: "";

        position: absolute;
        inset: 0;

        background:
            linear-gradient(90deg,
                rgba(5, 10, 35, 0.88) 0%,
                rgba(7, 13, 42, 0.76) 42%,
                rgba(7, 13, 42, 0.62) 100%);

        z-index: -1;
    }


    /* =========================================================
   EVERYTHING INSIDE HERO STAYS ABOVE IMAGE + OVERLAY
   ========================================================= */

    .contact-hero>* {
        position: relative;
        z-index: 1;
    }


    /* =========================================================
   LEFT CONTACT CONTENT
   ========================================================= */

    .contact-hero-content {
        position: relative;
        z-index: 2;

        color: #ffffff;
    }


    /* Main heading */

    .contact-hero-content h1 {
        color: #ffffff;
    }


    /* Paragraph */

    .contact-hero-content p {
        color: rgba(255, 255, 255, 0.92);
    }


    /* Labels */

    .contact-hero-content .contact-section-label,
    .contact-hero-content .contact-label {
        color: var(--contact-gold);
    }


    /* Contact headings */

    .contact-hero-content h2,
    .contact-hero-content h3 {
        color: #ffffff;
    }


    /* Contact details */

    .contact-hero-content a,
    .contact-hero-content span,
    .contact-hero-content li {
        color: rgba(255, 255, 255, 0.94);
    }


    /* Yellow branch names / important details */

    .contact-hero-content .branch-name {
        color: var(--contact-gold);
    }


    /* =========================================================
   ENQUIRY FORM
   Must remain sharp and unaffected by blur/overlay
   ========================================================= */

    .contact-enquiry-form {
        position: relative;
        z-index: 3;

        background: rgba(255, 255, 255, 0.97);

        color: #07133d;

        border-radius: 18px;

        backdrop-filter: none;
        -webkit-backdrop-filter: none;
    }


    /* Form heading */

    .contact-enquiry-form h2,
    .contact-enquiry-form h3 {
        color: #07133d;
    }


    /* Form text */

    .contact-enquiry-form p,
    .contact-enquiry-form label {
        color: #52617f;
    }


    /* =========================================================
   RESPONSIVE
   ========================================================= */

    @media (max-width: 900px) {

        .contact-hero {
            width: min(100% - 24px, 760px);

            min-height: auto;

            margin: 12px auto 30px;

            border-radius: 18px;
        }

        .contact-hero::before {
            filter: blur(5px);
        }
    }


    @media (max-width: 640px) {

        .contact-hero {
            width: calc(100% - 20px);

            margin: 10px auto 24px;

            border-radius: 15px;
        }

        .contact-hero::after {
            background:
                linear-gradient(180deg,
                    rgba(5, 10, 35, 0.86) 0%,
                    rgba(7, 13, 42, 0.80) 100%);
        }
    }

    /*
     * Subtle overlay prevents the image from overpowering
     * the contact information.
     */
    .contact-hero::after {
        content: "";
        position: absolute;
        inset: 0;

        background:
            radial-gradient(circle at 15% 20%,
                rgba(255, 215, 0, 0.08),
                transparent 28%),
            linear-gradient(180deg,
                rgba(20, 22, 58, 0.05),
                rgba(20, 22, 58, 0.18));

        pointer-events: none;
        z-index: -1;
    }

    /* =========================================================
       INNER CONTACT LAYOUT
       LEFT DETAILS + RIGHT FORM
       ========================================================= */

    .contact-hero-inner {
        position: relative;
        z-index: 2;

        width: 100%;
        max-width: 100%;

        display: grid;
        grid-template-columns: minmax(0, 1.12fr) minmax(360px, 0.88fr);

        gap: clamp(30px, 5vw, 70px);

        align-items: center;

        padding:
            clamp(42px, 6vw, 70px) clamp(28px, 5vw, 54px);
    }

    /* =========================================================
       LEFT CONTACT CONTENT
       ========================================================= */

    .contact-info-side {
        min-width: 0;
        color: #ffffff;
    }

    .contact-eyebrow {
        display: inline-flex;
        align-items: center;

        padding: 8px 15px;

        border: 1px solid rgba(255, 215, 0, 0.8);
        border-radius: 999px;

        background: rgba(20, 22, 58, 0.65);

        color: var(--contact-gold);

        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.8px;
        text-transform: uppercase;

        margin-bottom: 20px;
    }

    .contact-info-side h1 {
        margin: 0;

        max-width: 700px;

        color: #ffffff;

        font-size: clamp(42px, 5.5vw, 72px);
        line-height: 0.98;
        font-weight: 900;

        letter-spacing: -2px;
    }

    .contact-info-side h1 span {
        color: var(--contact-gold);
    }

    .contact-intro {
        max-width: 620px;

        margin: 22px 0 34px;

        color: rgba(255, 255, 255, 0.88);

        font-size: clamp(14px, 1.5vw, 17px);
        line-height: 1.75;
    }

    /* =========================================================
       CONTACT QUICK DETAILS
       ========================================================= */

    .contact-quick-grid {
        display: grid;

        grid-template-columns: repeat(2, minmax(0, 1fr));

        gap: 18px 26px;

        max-width: 650px;
    }

    .contact-quick-item {
        min-width: 0;

        display: flex;
        align-items: flex-start;

        gap: 12px;
    }

    .contact-quick-icon {
        flex: 0 0 40px;

        width: 40px;
        height: 40px;

        display: flex;
        align-items: center;
        justify-content: center;

        border: 1px solid var(--contact-gold);
        border-radius: 50%;

        color: var(--contact-gold);

        background: rgba(20, 22, 58, 0.55);

        font-size: 16px;
        font-weight: 800;
    }

    .contact-quick-content {
        min-width: 0;
    }

    .contact-quick-content strong {
        display: block;

        margin-bottom: 5px;

        color: #ffffff;

        font-size: 14px;
        font-weight: 800;
    }

    .contact-quick-content a,
    .contact-quick-content span {
        display: block;

        color: rgba(255, 255, 255, 0.78);

        font-size: 13px;
        line-height: 1.65;

        text-decoration: none;

        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .contact-quick-content a:hover {
        color: var(--contact-gold);
    }

    /* =========================================================
       BRANCH LOCATIONS
       ========================================================= */

    .contact-locations {
        grid-column: 1 / -1;

        margin-top: 4px;
        padding-top: 20px;

        border-top: 1px solid rgba(255, 255, 255, 0.18);
    }

    .contact-locations-title {
        margin-bottom: 12px;

        color: #ffffff;

        font-size: 14px;
        font-weight: 800;
    }

    .contact-location-list {
        display: grid;

        grid-template-columns: repeat(3, minmax(0, 1fr));

        gap: 12px;
    }

    .contact-location {
        min-width: 0;
    }

    .contact-location strong {
        display: block;

        margin-bottom: 3px;

        color: var(--contact-gold);

        font-size: 13px;
        font-weight: 800;
    }

    .contact-location a {
        color: rgba(255, 255, 255, 0.75);

        font-size: 12px;

        text-decoration: none;
    }

    .contact-location a:hover {
        color: #ffffff;
    }

    /* =========================================================
       SOCIAL MEDIA
       ========================================================= */

    .contact-social-row {
        display: flex;
        flex-wrap: wrap;

        gap: 9px;

        margin-top: 24px;
    }

    .contact-social-row a {
        width: 38px;
        height: 38px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border: 1px solid rgba(255, 215, 0, 0.7);
        border-radius: 50%;

        background: rgba(20, 22, 58, 0.55);

        color: #ffffff;

        text-decoration: none;

        transition:
            transform 0.2s ease,
            background 0.2s ease,
            color 0.2s ease;
    }

    .contact-social-row a:hover {
        transform: translateY(-2px);

        background: var(--contact-gold);
        color: var(--contact-navy);
    }

    /* =========================================================
       RIGHT ENQUIRY FORM
       ========================================================= */

    .contact-form-card {
        width: 100%;
        max-width: 500px;

        justify-self: end;

        padding: clamp(24px, 3vw, 34px);

        background: rgba(255, 255, 255, 0.97);

        border: 1px solid rgba(255, 255, 255, 0.9);

        border-radius: 18px;

        box-shadow:
            0 20px 55px rgba(0, 0, 0, 0.20);

        color: var(--contact-text);
    }

    .contact-form-card h2 {
        margin: 0;

        color: var(--contact-navy);

        font-size: clamp(25px, 2.5vw, 32px);
        line-height: 1.15;

        font-weight: 900;
    }

    .form-intro {
        margin: 10px 0 24px;

        color: var(--contact-muted);

        font-size: 13px;
        line-height: 1.6;
    }

    .contact-form-card .form-label {
        display: block;

        margin-bottom: 6px;

        color: var(--contact-navy);

        font-size: 12px;
        font-weight: 800;
    }

    .contact-form-card .form-control,
    .contact-form-card .form-select {
        width: 100%;

        min-height: 44px;

        border: 1px solid #dfe1eb;
        border-radius: 9px;

        background: #fbfbfd;

        color: var(--contact-text);

        box-shadow: none;

        font-size: 13px;

        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            background 0.2s ease;
    }

    .contact-form-card .form-control:focus,
    .contact-form-card .form-select:focus {
        border-color: var(--contact-gold-2);

        box-shadow:
            0 0 0 3px rgba(255, 215, 0, 0.16);

        background: #ffffff;

        outline: none;
    }

    .contact-form-card .form-control::placeholder {
        color: #9a9caf;
    }

    .contact-form-card .mb-3 {
        margin-bottom: 14px !important;
    }

    .contact-submit {
        width: 100%;

        min-height: 46px !important;

        margin-top: 5px;

        border: 0 !important;
        border-radius: 9px !important;

        background: var(--contact-navy) !important;

        color: #ffffff !important;

        font-size: 13px !important;
        font-weight: 800 !important;

        letter-spacing: 0.2px;

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            background 0.2s ease;
    }

    .contact-submit:hover,
    .contact-submit:focus {
        background: var(--contact-navy-2) !important;

        transform: translateY(-1px);

        box-shadow:
            0 8px 20px rgba(20, 22, 58, 0.22);
    }

    /* =========================================================
       FORM SECURITY / CAPTCHA
       ========================================================= */

    .captcha-field {
        margin-top: 2px;
    }

    /* =========================================================
       RESPONSIVE — TABLET
       ========================================================= */

    @media (max-width: 991px) {

        .contact-hero {
            width: min(100% - 24px, 900px);

            min-height: auto;

            margin:
                14px auto 30px;

            border-radius: 18px;
        }

        .contact-hero-inner {
            grid-template-columns: minmax(0, 1fr) minmax(320px, 0.9fr);

            gap: 28px;

            padding: 40px 28px;
        }

        .contact-info-side h1 {
            font-size: clamp(38px, 5vw, 56px);
        }

        .contact-intro {
            margin-bottom: 25px;
        }

        .contact-quick-grid {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .contact-location-list {
            grid-template-columns: 1fr;
            gap: 9px;
        }

        .contact-form-card {
            max-width: none;
        }
    }

    /* =========================================================
       RESPONSIVE — MOBILE
       ========================================================= */

    @media (max-width: 767px) {

        .contact-hero {
            width: calc(100% - 16px);

            margin:
                10px auto 24px;

            border-width: 1px;
            border-radius: 16px;

            background-position: center center;
        }

        .contact-hero-inner {
            grid-template-columns: 1fr;

            gap: 28px;

            padding:
                34px 18px 28px;
        }

        .contact-info-side {
            text-align: center;
        }

        .contact-eyebrow {
            margin-bottom: 16px;
        }

        .contact-info-side h1 {
            font-size: clamp(38px, 11vw, 50px);

            letter-spacing: -1.5px;
        }

        .contact-intro {
            margin:
                16px auto 25px;

            max-width: 540px;

            font-size: 14px;
        }

        .contact-quick-grid {
            max-width: 500px;

            margin: 0 auto;

            grid-template-columns: 1fr;
        }

        .contact-quick-item {
            justify-content: flex-start;

            text-align: left;
        }

        .contact-locations {
            text-align: left;
        }

        .contact-location-list {
            grid-template-columns: 1fr;
        }

        .contact-social-row {
            justify-content: center;
        }

        .contact-form-card {
            width: 100%;

            padding:
                24px 18px;

            border-radius: 15px;
        }

        .contact-form-card h2 {
            font-size: 25px;
        }
    }

    /* =========================================================
       RESPONSIVE — SMALL MOBILE
       ========================================================= */

    @media (max-width: 480px) {

        .contact-hero {
            width: calc(100% - 12px);

            margin-top: 7px;
        }

        .contact-hero-inner {
            padding:
                28px 14px 20px;
        }

        .contact-info-side h1 {
            font-size: 36px;
        }

        .contact-intro {
            font-size: 13px;
            line-height: 1.65;
        }

        .contact-quick-icon {
            flex-basis: 36px;

            width: 36px;
            height: 36px;
        }

        .contact-quick-content strong {
            font-size: 13px;
        }

        .contact-quick-content a,
        .contact-quick-content span {
            font-size: 12px;
        }

        .contact-form-card {
            padding: 21px 15px;
        }

        .contact-form-card .form-control,
        .contact-form-card .form-select {
            min-height: 43px;
        }
    }

    /* =========================================================
       VERY LARGE SCREENS
       ========================================================= */

    @media (min-width: 1400px) {

        .contact-hero {
            width: min(1140px, calc(100% - 40px));
        }
    }

    /* =========================================================
       ACCESSIBILITY / REDUCED MOTION
       ========================================================= */

    @media (prefers-reduced-motion: reduce) {

        .contact-social-row a,
        .contact-submit {
            transition: none;
        }
    }
</style>


<div class="contact-page">

    <!-- =====================================================
         CONTACT HERO
         LEFT: CONTACT INFORMATION
         RIGHT: ENQUIRY FORM
         ===================================================== -->

    <section
        class="contact-hero"
        aria-labelledby="contactHeroTitle">

        <div class="contact-hero-inner">

            <!-- =================================================
                 LEFT SIDE — CONTACT INFORMATION
                 ================================================= -->

            <div class="contact-info-side">

                <span class="contact-eyebrow">
                    CONTACT US
                </span>

                <h1 id="contactHeroTitle">
                    We're Here to
                    <span>Help</span>
                </h1>

                <p class="contact-intro">
                    Have a question about our training programs or
                    want to discuss your learning goals? Get in touch
                    with our team and take the next step toward your career.
                </p>


                <!-- QUICK CONTACT -->

                <div class="contact-quick-grid">

                    <!-- CALL -->

                    <div class="contact-quick-item">

                        <div
                            class="contact-quick-icon"
                            aria-hidden="true">
                            ☎
                        </div>

                        <div class="contact-quick-content">

                            <strong>Call Us</strong>

                            <a href="tel:+919741237334">
                                +91 97412 37334
                            </a>

                            <a href="tel:+918007961759">
                                +91 80079 61759
                            </a>

                        </div>

                    </div>


                    <!-- EMAIL -->

                    <div class="contact-quick-item">

                        <div
                            class="contact-quick-icon"
                            aria-hidden="true">
                            ✉
                        </div>

                        <div class="contact-quick-content">

                            <strong>Email Us</strong>

                            <a href="mailto:<?php echo htmlspecialchars(
                                                $business->getBusinessEmail(),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>">
                                <?php echo htmlspecialchars(
                                    $business->getBusinessEmail(),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>
                            </a>

                        </div>

                    </div>


                    <!-- LOCATIONS -->

                    <div class="contact-locations">

                        <div class="contact-locations-title">
                            Visit Us
                        </div>

                        <div class="contact-location-list">

                            <div class="contact-location">

                                <strong>Dharwad Branch</strong>

                                <a
                                    href="https://www.google.com/maps"
                                    target="_blank"
                                    rel="noopener noreferrer">
                                    View on Google Maps →
                                </a>

                            </div>


                            <div class="contact-location">

                                <strong>Hubballi Branch</strong>

                                <a
                                    href="https://www.google.com/maps"
                                    target="_blank"
                                    rel="noopener noreferrer">
                                    View on Google Maps →
                                </a>

                            </div>


                            <div class="contact-location">

                                <strong>Belagavi Branch</strong>

                                <a
                                    href="https://www.google.com/maps"
                                    target="_blank"
                                    rel="noopener noreferrer">
                                    View on Google Maps →
                                </a>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- SOCIAL MEDIA -->

                <div class="contact-social-row">

                    <?php
                    foreach ($socialMediaHandles as $handle) {

                        echo '<a
                                href="' .
                            htmlspecialchars(
                                $handle->getHandle(),
                                ENT_QUOTES,
                                'UTF-8'
                            ) .
                            '"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="Social media link">'
                            .
                            $handle->getIcon()
                            .
                            '</a>';
                    }
                    ?>

                </div>

            </div>


            <!-- =================================================
                 RIGHT SIDE — EXISTING ENQUIRY FORM
                 ================================================= -->

            <div class="contact-form-card">

                <h2>
                    Tell Us What You Need
                </h2>

                <p class="form-intro">
                    Fill in your details below and select your
                    training interest. Our team will get in touch
                    with you.
                </p>


                <!-- =================================================
                     EXISTING FORM
                     BACKEND LOGIC PRESERVED
                     ================================================= -->

                <form
                    class="form"
                    action="../Admin/Controller/newenquiry.php"
                    method="POST"
                    autocomplete="off"
                    id="contactForm">


                    <!-- NAME -->

                    <div class="mb-3">

                        <label
                            class="form-label"
                            for="name2">
                            Name
                        </label>

                        <input
                            type="text"
                            name="name2"
                            id="name2"
                            class="form-control"
                            placeholder="Name"
                            required
                            maxlength="50"
                            oninput="this.value = this.value
                                .replace(/[^A-Za-z ]/g, '')
                                .replace(/\s{2,}/g, ' ')"
                            title="Name should contain only characters and spaces.">

                    </div>


                    <!-- EMAIL -->

                    <div class="mb-3">

                        <label
                            class="form-label"
                            for="email2">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email2"
                            id="email2"
                            class="form-control"
                            placeholder="name@example.com"
                            required
                            maxlength="100"
                            pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}"
                            title="Please enter a valid email address.">

                    </div>


                    <!-- MOBILE -->

                    <div class="mb-3">

                        <label
                            class="form-label"
                            for="phone2">
                            Mobile Number
                        </label>

                        <input
                            type="tel"
                            name="phone2"
                            id="phone2"
                            class="form-control"
                            placeholder="1234567890"
                            required
                            maxlength="10"
                            minlength="10"
                            inputmode="numeric"
                            oninput="this.value = this.value
                                .replace(/[^0-9]/g, '')
                                .slice(0, 10)"
                            title="Phone number must contain only digits.">

                    </div>


                    <!-- TRAINING -->

                    <div class="mb-3">

                        <label
                            class="form-label"
                            for="trainings2">
                            Trainings
                        </label>

                        <select
                            class="form-select"
                            id="trainings2"
                            name="trainings2">

                            <option value="">
                                SELECT YOUR INTEREST
                            </option>

                            <?php

                            $courselist = DBcourse::selectall();

                            foreach ($courselist as $course) {

                                echo "<option value='" .
                                    htmlspecialchars(
                                        $course->get_cname(),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) .
                                    "'>" .
                                    htmlspecialchars(
                                        $course->get_cname(),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) .
                                    "</option>";
                            }

                            ?>

                        </select>

                    </div>


                    <!-- RECAPTCHA TOKEN -->

                    <input
                        type="hidden"
                        id="recaptcha-token"
                        name="recaptcha-token">


                    <!-- CAPTCHA -->

                    <!-- CAPTCHA / EXISTING POST FIELD -->

                    <div class="captcha-field mb-3">

                        <label
                            class="form-label"
                            for="captcha_input">
                            Verification
                        </label>

                        <input
                            class="form-control"
                            type="text"
                            id="captcha_input"
                            name="captcha_input"
                            required
                            placeholder="enter the above code"
                            autocomplete="off">

                    </div>


                    <!-- SUBMIT -->

                    <button
                        type="submit"
                        class="form-control btn btn-primary contact-submit"
                        name="regformsubmit">

                        Submit Enquiry

                    </button>

                </form>

            </div>

        </div>

    </section>

</div>


<!-- =========================================================
     EXISTING reCAPTCHA
     ========================================================= -->

<script src="https://www.google.com/recaptcha/api.js?render=6LeUqr8qAAAAACuw4V1CXyY4tQMb1T1qo5EFWAbg"></script>

<script>
    function onSubmit(token) {

        document
            .getElementById("contactForm")
            .submit();

    }


    function prepareRecaptcha() {

        grecaptcha.ready(function() {

            grecaptcha.execute(
                '6LeUqr8qAAAAACuw4V1CXyY4tQMb1T1qo5EFWAbg', {
                    action: 'submit'
                }
            ).then(function(token) {

                document
                    .getElementById('recaptcha-token')
                    .value = token;

            });

        });

    }


    document
        .getElementById("contactForm")
        .addEventListener("submit", function(e) {

            e.preventDefault();

            grecaptcha.ready(function() {

                grecaptcha.execute(
                    '6LeUqr8qAAAAACuw4V1CXyY4tQMb1T1qo5EFWAbg', {
                        action: 'submit'
                    }
                ).then(function(token) {

                    document
                        .getElementById("recaptcha-token")
                        .value = token;

                    document
                        .getElementById("contactForm")
                        .submit();

                });

            });

        });
</script>


<?php include('footer.php'); ?>