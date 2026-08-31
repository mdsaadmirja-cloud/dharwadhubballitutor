<!DOCTYPE html>
<html lang="en">

<head>

    <?php require_once("navigation.php"); ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Top Tech Courses in Dharwad | DharwadHubballiTutor | Free Demo</title>

    <meta name="description"
        content="Join DharwadHubballiTutor in Dharwad for practical technology courses, live projects, career guidance and placement support. Book a free demo class today!">

    <meta name="keywords"
        content="Power BI training Dharwad, Advanced Excel course Dharwad, Data Analytics institute Dharwad, Full Stack Development Dharwad, Digital Marketing training Dharwad, Software Testing course Dharwad">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    <style>
        /* =========================================================
           GLOBAL
        ========================================================= */

        :root {
            --navy: #102f67;
            --navy-dark: #0b2450;
            --purple: #24135f;
            --purple-dark: #190d49;
            --yellow: #ffd400;
            --yellow-dark: #f4c400;
            --white: #ffffff;
            --text: #18243a;
            --muted: #68758b;
            --light: #f6f8fc;
            --border: #e7ebf2;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #ffffff;
            color: var(--text);
        }

        img {
            max-width: 100%;
        }

        .section-space {
            padding: 80px 0;
        }

        .section-heading {
            text-align: center;
            margin-bottom: 55px;
        }

        .section-heading .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 15px;
            border-radius: 50px;
            background: #fff7cf;
            color: #765d00;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 14px;
        }

        .section-heading h2 {
            margin: 0;
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--text);
        }

        .section-heading p {
            max-width: 650px;
            margin: 14px auto 0;
            color: var(--muted);
            line-height: 1.7;
        }


        /* =========================================================
           HERO
        ========================================================= */

        .hero-section {
            position: relative;
            overflow: hidden;

            margin-top: 18px;

            padding: 55px 0 60px;

            background:
                radial-gradient(circle at 85% 20%,
                    rgba(255, 212, 0, 0.10),
                    transparent 28%),
                linear-gradient(135deg,
                    #0b2d67 0%,
                    #123d78 55%,
                    #193f75 100%);

            color: #fff;
        }

        .hero-section::before {
            content: "";
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.035);
            top: -220px;
            right: -100px;
        }

        .hero-section::after {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 212, 0, 0.035);
            bottom: -180px;
            left: -120px;
        }

        .hero-container {
            position: relative;
            z-index: 2;
        }

        .hero-row {
            min-height: 475px;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;

            padding: 9px 16px;

            border: 1px solid rgba(255, 255, 255, 0.20);
            border-radius: 50px;

            background: rgba(255, 255, 255, 0.07);

            color: #fff;

            font-size: 13px;
            font-weight: 700;

            margin-bottom: 22px;
        }

        .hero-eyebrow i {
            color: var(--yellow);
        }

        .hero-title {
            margin: 0;
            max-width: 680px;

            font-size: clamp(2.8rem, 5vw, 4.6rem);
            line-height: 1.04;
            letter-spacing: -2px;

            font-weight: 800;
        }

        .hero-title .yellow {
            color: var(--yellow);
        }

        .hero-description {
            max-width: 650px;

            margin: 24px 0 22px;

            color: rgba(255, 255, 255, 0.78);

            font-size: 1.05rem;
            line-height: 1.75;
        }

        .hero-benefits {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 20px;

            margin: 0 0 28px;
            padding: 0;

            list-style: none;
        }

        .hero-benefits li {
            display: flex;
            align-items: center;
            gap: 8px;

            color: #fff;
            font-size: 14px;
            font-weight: 600;
        }

        .hero-benefits li i {
            color: var(--yellow);
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .hero-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;

            padding: 14px 25px;

            border-radius: 50px;
            border: 2px solid var(--yellow);

            background: var(--yellow);
            color: #171717;

            font-weight: 800;
            text-decoration: none;

            transition: all .25s ease;
        }

        .hero-btn-primary:hover {
            color: #171717;
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, .22);
        }

        .hero-btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;

            padding: 14px 25px;

            border-radius: 50px;
            border: 2px solid rgba(255, 255, 255, .35);

            background: rgba(255, 255, 255, .07);
            color: #fff;

            font-weight: 700;
            text-decoration: none;

            transition: all .25s ease;
        }

        .hero-btn-secondary:hover {
            color: #fff;
            background: rgba(255, 255, 255, .15);
            transform: translateY(-3px);
        }


        /* =========================================================
           HERO PROFILE CARD
        ========================================================= */

        .hero-profile-area {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .profile-panel {
            position: relative;

            width: min(100%, 430px);

            padding: 30px 28px 26px;

            border-radius: 30px;

            border: 1px solid rgba(255, 255, 255, .18);

            background: rgba(255, 255, 255, .09);

            box-shadow:
                0 25px 60px rgba(0, 0, 0, .18),
                inset 0 1px 0 rgba(255, 255, 255, .08);

            backdrop-filter: blur(8px);

            text-align: center;
        }

        .profile-badge {
            position: absolute;

            top: 18px;
            right: 18px;

            display: inline-flex;
            align-items: center;
            gap: 6px;

            padding: 7px 11px;

            border-radius: 50px;

            background: var(--yellow);
            color: #171717;

            font-size: 11px;
            font-weight: 800;
        }

        .profile-img {
            width: 245px;
            height: 245px;

            object-fit: cover;

            border-radius: 50%;

            border: 6px solid rgba(255, 255, 255, .92);

            box-shadow:
                0 15px 35px rgba(0, 0, 0, .25);

            background: #fff;
        }

        .profile-name {
            margin: 18px 0 5px;

            color: #fff;

            font-size: 1.55rem;
            font-weight: 800;
        }

        .profile-role {
            margin: 0;

            color: rgba(255, 255, 255, .72);

            font-size: 13px;
            line-height: 1.6;
        }

        .certificate-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            margin-top: 18px;

            padding: 10px 18px;

            border-radius: 50px;

            background: rgba(255, 255, 255, .10);
            border: 1px solid rgba(255, 255, 255, .25);

            color: #fff;

            text-decoration: none;

            font-size: 13px;
            font-weight: 700;

            transition: all .25s ease;
        }

        .certificate-btn:hover {
            background: var(--yellow);
            border-color: var(--yellow);
            color: #171717;
        }


        /* =========================================================
           TRUST STRIP
        ========================================================= */

        .trust-strip {
            position: relative;
            z-index: 5;

            margin-top: -1px;

            background: #fff;

            border-bottom: 1px solid var(--border);

            box-shadow: 0 5px 20px rgba(0, 0, 0, .04);
        }

        .trust-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;

            padding: 22px 10px;

            color: var(--text);

            font-size: 14px;
            font-weight: 700;
        }

        .trust-item i {
            color: var(--yellow-dark);
            font-size: 18px;
        }


        /* =========================================================
           WHY US
        ========================================================= */

        .why-section {
            padding: 80px 0;
        }

        .why-card {
            height: 100%;

            padding: 32px 26px;

            background: #fff;

            border: 1px solid var(--border);
            border-radius: 20px;

            box-shadow: 0 8px 25px rgba(20, 35, 60, .05);

            transition: all .25s ease;
        }

        .why-card:hover {
            transform: translateY(-7px);

            border-color: rgba(255, 212, 0, .55);

            box-shadow: 0 18px 35px rgba(20, 35, 60, .10);
        }

        .why-icon {
            width: 55px;
            height: 55px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin-bottom: 20px;

            border-radius: 16px;

            background: #fff7cf;
            color: #876d00;

            font-size: 22px;
        }

        .why-card h3 {
            margin-bottom: 10px;

            font-size: 18px;
            font-weight: 800;
        }

        .why-card p {
            margin: 0;

            color: var(--muted);

            font-size: 14px;
            line-height: 1.7;
        }


        /* =========================================================
           COURSES
        ========================================================= */

        .courses-section {
            padding: 85px 0;

            background:
                linear-gradient(180deg,
                    #f6f8fc 0%,
                    #ffffff 100%);
        }

        .course-card {
            height: 100%;

            border: 1px solid var(--border);
            border-radius: 20px;

            background: #fff;

            box-shadow: 0 8px 25px rgba(20, 35, 60, .05);

            overflow: hidden;

            transition: all .25s ease;
        }

        .course-card:hover {
            transform: translateY(-8px);

            box-shadow: 0 18px 38px rgba(20, 35, 60, .11);

            border-color: rgba(255, 212, 0, .60);
        }

        .course-icon {
            width: 58px;
            height: 58px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin: 0 auto 20px;

            border-radius: 17px;

            background: var(--purple);
            color: var(--yellow);

            font-size: 22px;
        }

        .course-card h3 {
            margin-bottom: 12px;

            font-size: 19px;
            font-weight: 800;

            color: var(--text);
        }

        .course-card p {
            min-height: 52px;

            color: var(--muted);

            font-size: 14px;
            line-height: 1.65;
        }

        .course-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            padding: 10px 19px;

            border-radius: 50px;

            background: var(--purple);
            color: #fff;

            border: none;

            font-size: 13px;
            font-weight: 700;

            transition: all .25s ease;
        }

        .course-btn:hover {
            background: var(--yellow);
            color: #171717;

            transform: translateY(-2px);
        }


        /* =========================================================
           TESTIMONIALS
        ========================================================= */

        .testimonial-section {
            padding: 80px 0;
        }

        .testimonial-card {
            height: 100%;

            padding: 30px;

            border-radius: 20px;

            background: #fff;

            border: 1px solid var(--border);

            box-shadow: 0 8px 25px rgba(20, 35, 60, .05);
        }

        .testimonial-stars {
            color: #f5c400;

            margin-bottom: 16px;
        }

        .testimonial-card p {
            color: #4f5c70;

            font-size: 14px;
            line-height: 1.8;
        }

        .student-name {
            margin-top: 18px;

            font-weight: 800;
            color: var(--text);
        }

        .student-course {
            color: var(--muted);
            font-size: 12px;
        }


        /* =========================================================
   ALUMNI CAROUSEL
    ========================================================= */

        .alumni-section {
            padding: 80px 0;

            background: #f6f8fc;

            overflow: hidden;
        }


        /* -----------------------------
   CAROUSEL WRAPPER
------------------------------ */

        .alumni-carousel-wrapper {
            position: relative;

            display: flex;
            align-items: center;

            width: 100%;
        }


        /* -----------------------------
   VIEWPORT
    ------------------------------ */

        .alumni-carousel {
            width: 100%;

            overflow: hidden;

            padding: 10px 0 20px;
        }


        /* -----------------------------
   MOVING TRACK
    ------------------------------ */

        .alumni-track {
            display: flex;

            transition:
                transform .55s cubic-bezier(.4, 0, .2, 1);

            will-change: transform;
        }


        /* -----------------------------
   SLIDE
    ------------------------------ */

        .alumni-slide {
            flex: 0 0 25%;

            padding: 0 12px;

            box-sizing: border-box;
        }


        /* -----------------------------
   LOGO CARD
    ------------------------------ */

        .company-logo-box {
            height: 115px;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 18px 22px;

            background: rgba(255, 255, 255, .75);

            border: 1px solid rgba(20, 35, 60, .04);

            border-radius: 16px;

            box-shadow:
                0 7px 20px rgba(20, 35, 60, .035);

            transition:
                transform .25s ease,
                box-shadow .25s ease,
                background .25s ease;
        }


        .company-logo-box:hover {
            transform: translateY(-5px);

            background: #ffffff;

            box-shadow:
                0 14px 28px rgba(20, 35, 60, .09);
        }


        /* -----------------------------
   LOGO
    ------------------------------ */

        .company-logo {
            display: block;

            max-width: 135px;
            max-height: 70px;

            width: auto;
            height: auto;

            object-fit: contain;

            filter: grayscale(100%);

            opacity: .62;

            transition:
                filter .25s ease,
                opacity .25s ease,
                transform .25s ease;
        }


        .company-logo-box:hover .company-logo {
            filter: grayscale(0%);

            opacity: 1;

            transform: scale(1.04);
        }


        /* -----------------------------
   ARROWS
    ------------------------------ */

        .alumni-arrow {
            position: relative;
            z-index: 5;

            flex-shrink: 0;

            width: 42px;
            height: 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            border: none;

            border-radius: 50%;

            background: #24135f;

            color: #ffffff;

            cursor: pointer;

            box-shadow:
                0 8px 18px rgba(36, 19, 95, .20);

            transition:
                transform .2s ease,
                background .2s ease;
        }


        .alumni-arrow:hover {
            background: #ffd400;

            color: #171717;

            transform: scale(1.08);
        }


        .alumni-prev {
            margin-right: 8px;
        }


        .alumni-next {
            margin-left: 8px;
        }


        /* -----------------------------
   DOTS
    ------------------------------ */

        .alumni-dots {
            display: flex;

            justify-content: center;
            align-items: center;

            gap: 7px;

            margin-top: 20px;
        }


        .alumni-dots button {
            width: 7px;
            height: 7px;

            padding: 0;

            border: none;

            border-radius: 50%;

            background: #cbd2df;

            cursor: pointer;

            transition:
                width .25s ease,
                background .25s ease;
        }


        .alumni-dots button.active {
            width: 23px;

            border-radius: 10px;

            background: #ffd400;
        }


        /* =========================================================
   TABLET
    ========================================================= */

        @media (max-width: 991.98px) {

            .alumni-slide {
                flex: 0 0 33.333333%;
            }

        }


        /* =========================================================
   MOBILE
    ========================================================= */

        @media (max-width: 767.98px) {

            .alumni-section {
                padding: 65px 0;
            }


            .alumni-slide {
                flex: 0 0 50%;

                padding: 0 7px;
            }


            .company-logo-box {
                height: 100px;

                padding: 14px;
            }


            .company-logo {
                max-width: 105px;

                max-height: 55px;
            }


            .alumni-arrow {
                width: 36px;
                height: 36px;

                font-size: 12px;
            }


            .alumni-prev {
                margin-right: 4px;
            }


            .alumni-next {
                margin-left: 4px;
            }

        }


        /* =========================================================
   SMALL MOBILE
    ========================================================= */

        @media (max-width: 480px) {

            .alumni-slide {
                flex: 0 0 100%;
            }


            .company-logo-box {
                height: 110px;
            }

        }


        /* =========================================================
           VISIT US
        ========================================================= */

        .visit-section {
            padding: 85px 0;
        }

        .visit-card {
            padding: 35px;

            border-radius: 24px;

            background: #fff;

            border: 1px solid var(--border);

            box-shadow: 0 8px 25px rgba(20, 35, 60, .05);
        }

        .visit-card h3 {
            margin-bottom: 20px;

            font-size: 28px;
            font-weight: 800;
        }

        .visit-card p {
            color: var(--muted);
            line-height: 1.7;
        }

        .visit-detail {
            display: flex;
            align-items: flex-start;
            gap: 12px;

            margin-top: 18px;
        }

        .visit-detail i {
            width: 36px;
            height: 36px;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;

            border-radius: 10px;

            background: #fff7cf;
            color: #806600;
        }

        .visit-detail div {
            color: #48566b;

            font-size: 14px;
            line-height: 1.65;
        }

        .visit-detail strong {
            color: var(--text);
        }

        .google-map {
            width: 100%;
            height: 430px;

            border: 0;

            border-radius: 24px;

            box-shadow: 0 10px 30px rgba(20, 35, 60, .10);
        }


        /* =========================================================
           MODAL
        ========================================================= */

        .modal-content {
            border: none;
            border-radius: 20px;
            overflow: hidden;
        }

        .modal-header {
            padding: 20px 24px;

            background: var(--purple);

            border-bottom: none;
        }

        .modal-title {
            color: #fff !important;

            font-weight: 700 !important;
        }

        .modal-header .btn-close {
            filter: invert(1);
        }

        .modal-body {
            padding: 25px;
        }

        .modal-body .label {
            display: block;

            margin-bottom: 7px;

            color: var(--text);

            font-size: 13px;
            font-weight: 700;
        }

        .modal-body .form-control,
        .modal-body .form-select {
            min-height: 48px;

            border-radius: 10px;

            border: 1px solid #dce2eb;
        }

        .modal-body .form-control:focus,
        .modal-body .form-select:focus {
            border-color: var(--yellow-dark);

            box-shadow: 0 0 0 .20rem rgba(255, 212, 0, .18);
        }

        .modal-footer {
            padding: 0 0 5px;

            border-top: none;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 991.98px) {

            .hero-section {
                padding: 50px 0 55px;
            }

            .hero-row {
                min-height: auto;
            }

            .hero-content {
                text-align: center;
            }

            .hero-title {
                font-size: 3rem;
                letter-spacing: -1px;
            }

            .hero-description {
                margin-left: auto;
                margin-right: auto;
            }

            .hero-benefits {
                justify-content: center;
            }

            .hero-actions {
                justify-content: center;
            }

            .hero-profile-area {
                margin-top: 40px;
            }

            .section-space,
            .why-section,
            .testimonial-section,
            .visit-section {
                padding: 65px 0;
            }
        }


        @media (max-width: 767.98px) {

            .hero-section {
                padding: 40px 0 45px;
            }

            .hero-title {
                font-size: 2.35rem;
                line-height: 1.08;
            }

            .hero-description {
                font-size: .95rem;
            }

            .hero-benefits {
                display: block;

                text-align: left;

                max-width: 300px;

                margin-left: auto;
                margin-right: auto;
            }

            .hero-benefits li {
                margin-bottom: 9px;
            }

            .hero-actions {
                flex-direction: column;

                max-width: 300px;

                margin-left: auto;
                margin-right: auto;
            }

            .hero-btn-primary,
            .hero-btn-secondary {
                width: 100%;
            }

            .profile-panel {
                padding: 25px 18px 22px;
            }

            .profile-img {
                width: 205px;
                height: 205px;
            }

            .profile-badge {
                top: 12px;
                right: 12px;
            }

            .section-heading {
                margin-bottom: 40px;
            }

            .section-heading h2 {
                font-size: 1.8rem;
            }

            .why-card {
                padding: 28px 22px;
            }

            .courses-section {
                padding: 65px 0;
            }

            .google-map {
                height: 350px;
                margin-top: 25px;
            }

            .visit-card {
                padding: 25px;
            }

            .visit-card h3 {
                font-size: 24px;
            }

            .alumni-grid {
                gap: 15px;
            }

            .company-logo {
                max-width: 105px;
            }
        }


        @media (max-width: 480px) {

            .hero-title {
                font-size: 2rem;
            }

            .hero-eyebrow {
                font-size: 11px;
            }

            .profile-img {
                width: 185px;
                height: 185px;
            }

            .profile-name {
                font-size: 1.35rem;
            }
        }
    </style>

</head>


<body>


    <!-- =========================================================
     HERO
     ========================================================= -->

    <section class="hero-section">

        <div class="container hero-container">

            <div class="row align-items-center hero-row">

                <!-- LEFT -->
                <div class="col-lg-7 hero-content">

                    <div class="hero-eyebrow">
                        <i class="fas fa-graduation-cap"></i>
                        Practical Tech Training in Dharwad
                    </div>

                    <h1 class="hero-title">
                        Build
                        <span class="yellow">Job-Ready</span>
                        Skills.
                        <span>Launch Your Career.</span>
                    </h1>

                    <p class="hero-description">
                        Learn in-demand technology skills through practical training,
                        live projects and career-focused guidance designed to help
                        you move confidently toward your next opportunity.
                    </p>

                    <ul class="hero-benefits">

                        <li>
                            <i class="fas fa-check-circle"></i>
                            Hands-on Experience
                        </li>

                        <li>
                            <i class="fas fa-check-circle"></i>
                            Certified Trainers
                        </li>

                        <li>
                            <i class="fas fa-check-circle"></i>
                            Live Practical Projects
                        </li>

                    </ul>

                    <div class="hero-actions">

                        <button
                            type="button"
                            class="hero-btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#demomodal">

                            <i class="fas fa-calendar-check"></i>

                            Book FREE Demo

                        </button>

                        <a
                            href="#courses"
                            class="hero-btn-secondary">

                            <i class="fas fa-layer-group"></i>

                            Explore Courses

                        </a>

                    </div>

                </div>


                <!-- RIGHT -->
                <div class="col-lg-5 hero-profile-area">

                    <div class="profile-panel">

                        <div class="profile-badge">
                            <i class="fas fa-certificate"></i>
                            Certified Trainer
                        </div>

                        <img
                            src="../img/hifzashaikh.jpeg"
                            alt="Hifza Shaikh"
                            class="profile-img">

                        <h2 class="profile-name">
                            Hifza Shaikh
                        </h2>

                        <p class="profile-role">
                            Microsoft Certified: Power BI Data Analyst Associate
                        </p>

                        <a
                            href="https://learn.microsoft.com/api/credentials/share/en-us/HifzaShaikh-6127/7A20FAB4E28C2BD3?sharingId=2AAFD99171047755"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="certificate-btn">

                            <i class="fas fa-external-link-alt"></i>

                            Validate Certificate

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- =========================================================
     TRUST STRIP
     ========================================================= -->

    <section class="trust-strip">

        <div class="container">

            <div class="row g-0">

                <div class="col-6 col-lg-3">
                    <div class="trust-item">
                        <i class="fas fa-laptop-code"></i>
                        Practical Learning
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="trust-item">
                        <i class="fas fa-project-diagram"></i>
                        Live Projects
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="trust-item">
                        <i class="fas fa-user-tie"></i>
                        Career Guidance
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="trust-item">
                        <i class="fas fa-award"></i>
                        Certification
                    </div>
                </div>

            </div>

        </div>

    </section>



    <!-- =========================================================
     WHY CHOOSE US
     ========================================================= -->

    <section class="why-section">

        <div class="container">

            <div class="section-heading">

                <div class="eyebrow">
                    <i class="fas fa-star"></i>
                    WHY CHOOSE US
                </div>

                <h2>
                    Training That Builds Real Skills
                </h2>

                <p>
                    Learn through practical experiences instead of only
                    watching theory. Our focus is on skills you can actually use.
                </p>

            </div>


            <div class="row g-4">

                <div class="col-md-6 col-lg-3">

                    <div class="why-card">

                        <div class="why-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>

                        <h3>
                            Expert Trainers
                        </h3>

                        <p>
                            Learn from experienced trainers who focus on
                            practical industry requirements.
                        </p>

                    </div>

                </div>


                <div class="col-md-6 col-lg-3">

                    <div class="why-card">

                        <div class="why-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>

                        <h3>
                            Placement Support
                        </h3>

                        <p>
                            Get career guidance and support to help you
                            prepare for real opportunities.
                        </p>

                    </div>

                </div>


                <div class="col-md-6 col-lg-3">

                    <div class="why-card">

                        <div class="why-icon">
                            <i class="fas fa-laptop-code"></i>
                        </div>

                        <h3>
                            Practical Training
                        </h3>

                        <p>
                            Work on hands-on exercises and projects that
                            strengthen your portfolio.
                        </p>

                    </div>

                </div>


                <div class="col-md-6 col-lg-3">

                    <div class="why-card">

                        <div class="why-icon">
                            <i class="fas fa-certificate"></i>
                        </div>

                        <h3>
                            Certification
                        </h3>

                        <p>
                            Build credibility by completing structured
                            training and earning relevant certification.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- =========================================================
     COURSES
     ========================================================= -->

    <section
        class="courses-section"
        id="courses">

        <div class="container">

            <div class="section-heading">

                <div class="eyebrow">
                    <i class="fas fa-layer-group"></i>
                    OUR COURSES
                </div>

                <h2>
                    Learn Skills That Employers Need
                </h2>

                <p>
                    Choose a practical technology course and start building
                    skills through structured learning and real projects.
                </p>

            </div>


            <div class="row g-4">

                <?php

                $courses = [

                    [
                        'title' => 'Power BI',
                        'icon' => 'fa-chart-bar',
                        'description' =>
                        'Master data visualization and business intelligence.'
                    ],

                    [
                        'title' => 'Advanced Excel',
                        'icon' => 'fa-file-excel',
                        'description' =>
                        'Learn advanced analytics, formulas, dashboards and automation.'
                    ],

                    [
                        'title' => 'Data Analytics',
                        'icon' => 'fa-chart-line',
                        'description' =>
                        'Analyze real-world data using Python, SQL and Power BI.'
                    ],

                    [
                        'title' => 'Full Stack Development',
                        'icon' => 'fa-code',
                        'description' =>
                        'Build complete modern web applications through practical development.'
                    ],

                    [
                        'title' => 'Digital Marketing',
                        'icon' => 'fa-bullhorn',
                        'description' =>
                        'Learn SEO, SEM and social media marketing techniques.'
                    ],

                    [
                        'title' => 'Software Testing',
                        'icon' => 'fa-bug',
                        'description' =>
                        'Understand manual and automated testing methodologies.'
                    ]

                ];


                foreach ($courses as $course) {

                    echo '

                <div class="col-md-6 col-lg-4">

                    <div class="course-card">

                        <div class="card-body text-center p-4">

                            <div class="course-icon">

                                <i class="fas ' . $course['icon'] . '"></i>

                            </div>

                            <h3>
                                ' . $course['title'] . '
                            </h3>

                            <p>
                                ' . $course['description'] . '
                            </p>

                            <button
                                type="button"
                                class="course-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#demomodal">

                                Enquire Now

                                <i class="fas fa-arrow-right"></i>

                            </button>

                        </div>

                    </div>

                </div>';
                }

                ?>

            </div>

        </div>

    </section>



    <!-- =========================================================
     TESTIMONIALS
     ========================================================= -->

    <section class="testimonial-section">

        <div class="container">

            <div class="section-heading">

                <div class="eyebrow">
                    <i class="fas fa-comments"></i>
                    STUDENT EXPERIENCES
                </div>

                <h2>
                    What Our Students Say
                </h2>

            </div>


            <div class="row g-4">

                <div class="col-md-6">

                    <div class="testimonial-card">

                        <div class="testimonial-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>

                        <p>
                            "The Power BI course was amazing. The trainer
                            explained complex topics so easily. I got a job
                            within a month of completing the course!"
                        </p>

                        <div class="student-name">
                            Rohan P.
                        </div>

                        <div class="student-course">
                            Power BI Course
                        </div>

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="testimonial-card">

                        <div class="testimonial-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>

                        <p>
                            "Best place in Hubli for full-stack development.
                            The focus on live projects helped me build a great
                            portfolio and crack interviews easily."
                        </p>

                        <div class="student-name">
                            Anjali K.
                        </div>

                        <div class="student-course">
                            Full Stack Development
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- =========================================================
     OUR ALUMNI
     ========================================================= -->

    <section class="alumni-section">

        <div class="container">

            <div class="section-heading">

                <div class="eyebrow">
                    <i class="fas fa-building"></i>
                    OUR ALUMNI
                </div>

                <h2>
                    Our Alumni Shine At
                </h2>

            </div>


            <div class="alumni-carousel-wrapper">

                <button
                    type="button"
                    class="alumni-arrow alumni-prev"
                    aria-label="Previous company">

                    <i class="fas fa-chevron-left"></i>

                </button>


                <div
                    class="alumni-carousel"
                    id="alumniCarousel">

                    <div class="alumni-track">


                        <!-- INFOSYS -->
                        <div class="alumni-slide">

                            <div class="company-logo-box">

                                <img
                                    src="../img/Infosys logo.jpg"
                                    alt="Infosys"
                                    class="company-logo">

                            </div>

                        </div>


                        <!-- TCS -->
                        <div class="alumni-slide">

                            <div class="company-logo-box">

                                <img
                                    src="../img/TCS logo.jpg"
                                    alt="TCS"
                                    class="company-logo">

                            </div>

                        </div>


                        <!-- L&T -->
                        <div class="alumni-slide">

                            <div class="company-logo-box">

                                <img
                                    src="../img/L T logo.jpg"
                                    alt="L&T"
                                    class="company-logo">

                            </div>

                        </div>


                        <!-- LUMEN -->
                        <div class="alumni-slide">

                            <div class="company-logo-box">

                                <img
                                    src="../img/Lumen logo.jpg"
                                    alt="Lumen"
                                    class="company-logo">

                            </div>

                        </div>


                        <!-- AJIO -->
                        <div class="alumni-slide">

                            <div class="company-logo-box">

                                <img
                                    src="../img/AJIO logo.jpg"
                                    alt="AJIO"
                                    class="company-logo">

                            </div>

                        </div>


                        <!-- T SYSTEMS -->
                        <div class="alumni-slide">

                            <div class="company-logo-box">

                                <img
                                    src="../img/T system logo.jpg"
                                    alt="T Systems"
                                    class="company-logo">

                            </div>

                        </div>

                    </div>

                </div>


                <button
                    type="button"
                    class="alumni-arrow alumni-next"
                    aria-label="Next company">

                    <i class="fas fa-chevron-right"></i>

                </button>

            </div>


            <!-- DOTS -->

            <div
                class="alumni-dots"
                id="alumniDots">

                <button
                    type="button"
                    class="active"
                    data-slide="0"
                    aria-label="Slide 1">
                </button>

                <button
                    type="button"
                    data-slide="1"
                    aria-label="Slide 2">
                </button>

                <button
                    type="button"
                    data-slide="2"
                    aria-label="Slide 3">
                </button>

                <button
                    type="button"
                    data-slide="3"
                    aria-label="Slide 4">
                </button>

                <button
                    type="button"
                    data-slide="4"
                    aria-label="Slide 5">
                </button>

                <button
                    type="button"
                    data-slide="5"
                    aria-label="Slide 6">
                </button>

            </div>

        </div>

    </section>



    <!-- =========================================================
     VISIT US
     ========================================================= -->

    <section class="visit-section">

        <div class="container">

            <div class="row align-items-center g-5">

                <div class="col-lg-5">

                    <div class="visit-card">

                        <div class="section-heading text-start mb-4">

                            <div class="eyebrow">
                                <i class="fas fa-map-marker-alt"></i>
                                VISIT US
                            </div>

                            <h2>
                                Visit Our Dharwad Branch
                            </h2>

                        </div>

                        <p>
                            Come and meet our counselors for a free
                            career guidance session.
                        </p>


                        <div class="visit-detail">

                            <i class="fas fa-map-marker-alt"></i>

                            <div>

                                <strong>
                                    Address
                                </strong>

                                <br>

                                J G Nippani Complex,<br>
                                Beside SBI Gandhinagar,-04,<br>
                                Dharwad,<br>
                                Karnataka, India

                            </div>

                        </div>


                        <div class="visit-detail">

                            <i class="fas fa-phone"></i>

                            <div>

                                <strong>
                                    Contact
                                </strong>

                                <br>

                                <a href="tel:+919741237334">
                                    +91 9741237334
                                </a>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-lg-7">

                    <iframe
                        class="google-map"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d492273.3668720411!2d74.49204497162403!3d15.43672037651035!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bb8d370eace81bb%3A0xf20b739d863002a2!2sDharwadHubballiTutor(Web%20design%20and%20development%2C%20Digital%20Marketing%2C%20Data%20Science%2C%20Automation%20Testing%20%2C%20Stock%20Market)!5e0!3m2!1sen!2sin!4v1756189985079!5m2!1sen!2sin"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>

                </div>

            </div>

        </div>

    </section>



    <!-- =========================================================
     DEMO MODAL
     ========================================================= -->

    <div
        class="modal fade"
        id="demomodal"
        tabindex="-1"
        aria-labelledby="demoModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <h5
                        class="modal-title"
                        id="demoModalLabel">

                        Register for a FREE Demo Class

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>

                </div>


                <div class="modal-body">

                    <?php

                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }

                    ?>

                    <form
                        action="../Admin/Controller/newenquiry.php"
                        method="POST"
                        autocomplete="off">

                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?php
                                    if (isset($_SESSION['csrf_token'])) {
                                        echo htmlspecialchars($_SESSION['csrf_token']);
                                    }
                                    ?>">


                        <label
                            class="label"
                            for="name2">

                            Name

                        </label>

                        <input
                            type="text"
                            name="name2"
                            class="form-control"
                            id="name2"
                            placeholder="Enter Your Full Name"
                            required>


                        <input
                            type="hidden"
                            name="front"
                            value="front">


                        <label
                            class="label"
                            for="email2">

                            Email

                        </label>

                        <input
                            type="email"
                            name="email2"
                            class="form-control"
                            id="email2"
                            placeholder="name@example.com">


                        <label
                            class="label"
                            for="phone2">

                            Phone Number

                        </label>

                        <input
                            type="tel"
                            name="phone2"
                            class="form-control"
                            id="phone2"
                            placeholder="10-Digit Mobile Number"
                            required
                            pattern="^[6-9]\d{9}$">


                        <label
                            class="label"
                            for="demo2">

                            Course of Interest

                        </label>

                        <select
                            class="form-select"
                            id="demo2"
                            name="trainings2"
                            required>

                            <option
                                value=""
                                selected
                                disabled>

                                -- Select a Course --

                            </option>

                            <?php

                            if (class_exists('DBcourse')) {

                                $courselist = DBcourse::selectall();

                                foreach ($courselist as $course) {

                                    echo "<option value='" .
                                        htmlspecialchars($course->get_cname()) .
                                        "'>" .
                                        htmlspecialchars($course->get_cname()) .
                                        "</option>";
                                }
                            }

                            ?>

                        </select>


                        <input
                            type="hidden"
                            id="recaptcha-token"
                            name="recaptcha-token">


                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">

                                Close

                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary"
                                name="demosubmit">

                                Submit Request

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>



    <!-- =========================================================
     FOOTER
     DO NOT CHANGE
     ========================================================= -->

    <?php require_once("footer.php"); ?>



    <script>
        /*
         * Existing CTA analytics
         * Preserved intentionally.
         */

        document
            .querySelectorAll('.cta-button')
            .forEach(function(button) {

                button.addEventListener('click', function() {

                    if (typeof gtag !== 'undefined') {

                        gtag('event', 'generate_lead', {

                            'event_category': 'engagement',

                            'event_label': 'Book Demo Modal Opened'

                        });

                    }

                });

            });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const carousel = document.getElementById("alumniCarousel");

            if (!carousel) {
                return;
            }

            const track = carousel.querySelector(".alumni-track");

            const slides = Array.from(
                track.querySelectorAll(".alumni-slide")
            );

            const prevButton =
                document.querySelector(".alumni-prev");

            const nextButton =
                document.querySelector(".alumni-next");

            const dots =
                Array.from(
                    document.querySelectorAll("#alumniDots button")
                );


            let currentIndex = 0;

            let autoPlay;


            /* -----------------------------------------
               HOW MANY LOGOS ARE VISIBLE?
            ------------------------------------------ */

            function getVisibleSlides() {

                if (window.innerWidth <= 480) {
                    return 1;
                }

                if (window.innerWidth <= 767) {
                    return 2;
                }

                if (window.innerWidth <= 991) {
                    return 3;
                }

                return 4;
            }


            /* -----------------------------------------
               TOTAL POSITIONS
            ------------------------------------------ */

            function getMaxIndex() {

                return Math.max(
                    0,
                    slides.length - getVisibleSlides()
                );

            }


            /* -----------------------------------------
               UPDATE CAROUSEL
            ------------------------------------------ */

            function updateCarousel() {

                const visible =
                    getVisibleSlides();

                const slideWidth =
                    100 / visible;

                const maxIndex =
                    getMaxIndex();


                if (currentIndex > maxIndex) {
                    currentIndex = 0;
                }


                track.style.transform =
                    "translateX(-" +
                    (currentIndex * slideWidth) +
                    "%)";


                /*
                 * Update dots.
                 */

                dots.forEach(function(dot, index) {

                    dot.classList.toggle(
                        "active",
                        index === currentIndex
                    );

                });

            }


            /* -----------------------------------------
               NEXT
            ------------------------------------------ */

            function nextSlide() {

                const maxIndex =
                    getMaxIndex();


                if (currentIndex >= maxIndex) {

                    currentIndex = 0;

                } else {

                    currentIndex++;

                }


                updateCarousel();

            }


            /* -----------------------------------------
               PREVIOUS
            ------------------------------------------ */

            function previousSlide() {

                const maxIndex =
                    getMaxIndex();


                if (currentIndex <= 0) {

                    currentIndex = maxIndex;

                } else {

                    currentIndex--;

                }


                updateCarousel();

            }


            /* -----------------------------------------
               BUTTONS
            ------------------------------------------ */

            nextButton.addEventListener(
                "click",
                function() {

                    nextSlide();

                    restartAutoPlay();

                }
            );


            prevButton.addEventListener(
                "click",
                function() {

                    previousSlide();

                    restartAutoPlay();

                }
            );


            /* -----------------------------------------
               DOTS
            ------------------------------------------ */

            dots.forEach(function(dot, index) {

                dot.addEventListener(
                    "click",
                    function() {

                        const maxIndex =
                            getMaxIndex();

                        currentIndex =
                            Math.min(index, maxIndex);

                        updateCarousel();

                        restartAutoPlay();

                    }
                );

            });


            /* -----------------------------------------
               AUTO PLAY
            ------------------------------------------ */

            function startAutoPlay() {

                autoPlay =
                    setInterval(
                        nextSlide,
                        3000
                    );

            }


            function stopAutoPlay() {

                clearInterval(autoPlay);

            }


            function restartAutoPlay() {

                stopAutoPlay();

                startAutoPlay();

            }


            /* -----------------------------------------
               PAUSE ON HOVER
            ------------------------------------------ */

            carousel.addEventListener(
                "mouseenter",
                stopAutoPlay
            );


            carousel.addEventListener(
                "mouseleave",
                startAutoPlay
            );


            /* -----------------------------------------
               RESPONSIVE
            ------------------------------------------ */

            window.addEventListener(
                "resize",
                function() {

                    updateCarousel();

                }
            );


            /* -----------------------------------------
               INITIALIZE
            ------------------------------------------ */

            updateCarousel();

            startAutoPlay();

        });
    </script>


</body>

</html>