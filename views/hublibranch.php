<!DOCTYPE html>
<html lang="en">

<head>

    <?php require_once("navigation.php"); ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Top Tech Courses in Hubli | DharwadHubballiTutor | Free Demo</title>

    <meta
        name="description"
        content="Join DharwadHubballiTutor in Hubli for practical training in Power BI, Data Analytics, Full Stack Development, Advanced Excel, Digital Marketing and Software Testing. Book a free demo.">

    <meta
        name="keywords"
        content="Power BI training Hubli, Advanced Excel course Hubli, Data Analytics institute Hubli, Full Stack Development Hubli, Digital Marketing training Hubli, Software Testing course Hubli, job placement courses Hubli">

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
   HUBLI PAGE REDESIGN
   Navigation + Footer intentionally untouched
========================================================= */


        /* =========================================================
   GLOBAL
========================================================= */

        :root {
            --hubli-navy: #0b2d67;
            --hubli-blue: #123f7a;
            --hubli-purple: #24135f;
            --hubli-yellow: #ffd400;
            --hubli-text: #13233a;
            --hubli-muted: #667085;
            --hubli-light: #f6f8fc;
            --hubli-white: #ffffff;
        }


        body {
            font-family: 'Poppins', sans-serif;
            background: #ffffff;
            color: var(--hubli-text);
        }


        /* Prevent accidental horizontal overflow */

        html,
        body {
            overflow-x: hidden;
        }


        /* =========================================================
   COMMON CONTAINER
========================================================= */

        .hubli-section {
            padding: 90px 0;
        }


        .hubli-container {
            position: relative;
        }


        /* =========================================================
   SECTION HEADING
========================================================= */

        .hubli-heading {
            text-align: center;

            max-width: 760px;

            margin: 0 auto 55px;
        }


        .hubli-eyebrow {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 8px;

            padding: 9px 17px;

            border-radius: 999px;

            background: #fff6cf;

            color: #695500;

            font-size: 13px;

            font-weight: 700;

            letter-spacing: .2px;

            margin-bottom: 18px;
        }


        .hubli-eyebrow i {
            color: #d4a900;
        }


        .hubli-heading h2 {
            margin: 0;

            color: var(--hubli-text);

            font-size: clamp(30px, 4vw, 46px);

            line-height: 1.12;

            font-weight: 800;

            letter-spacing: -1px;
        }


        .hubli-heading p {
            margin: 18px auto 0;

            max-width: 650px;

            color: var(--hubli-muted);

            font-size: 16px;

            line-height: 1.8;
        }


        /* =========================================================
   HERO
========================================================= */

        .hubli-hero {
            position: relative;

            overflow: hidden;

            margin-top: 18px;

            margin-left: 4px;
            margin-right: 4px;

            padding: 72px 0 70px;

            border-radius: 0 0 24px 24px;

            color: #ffffff;

            background:
                radial-gradient(circle at 85% 15%,
                    rgba(255, 212, 0, .12),
                    transparent 25%),
                radial-gradient(circle at 10% 90%,
                    rgba(77, 146, 255, .13),
                    transparent 30%),
                linear-gradient(135deg,
                    #0b2d67 0%,
                    #123f7a 58%,
                    #193f75 100%);
        }


        .hubli-hero::before {
            content: "";

            position: absolute;

            width: 520px;
            height: 520px;

            border-radius: 50%;

            right: -220px;
            top: -280px;

            background: rgba(255, 255, 255, .035);
        }


        .hubli-hero::after {
            content: "";

            position: absolute;

            width: 320px;
            height: 320px;

            border-radius: 50%;

            left: -170px;
            bottom: -200px;

            background: rgba(255, 212, 0, .035);
        }


        .hubli-hero .container {
            position: relative;
            z-index: 2;
        }


        .hubli-hero-content {
            padding-right: 25px;
        }


        .hubli-hero-eyebrow {
            display: inline-flex;

            align-items: center;

            gap: 9px;

            padding: 10px 17px;

            border: 1px solid rgba(255, 255, 255, .22);

            border-radius: 999px;

            background: rgba(255, 255, 255, .07);

            color: #ffffff;

            font-size: 13px;

            font-weight: 700;

            margin-bottom: 23px;
        }


        .hubli-hero-eyebrow i {
            color: var(--hubli-yellow);
        }


        .hubli-hero h1 {
            margin: 0;

            max-width: 720px;

            font-size: clamp(38px, 5vw, 64px);

            line-height: 1.06;

            font-weight: 800;

            letter-spacing: -2px;
        }


        .hubli-hero h1 span {
            color: var(--hubli-yellow);
        }


        .hubli-hero-description {
            max-width: 650px;

            margin: 25px 0;

            color: rgba(255, 255, 255, .78);

            font-size: 17px;

            line-height: 1.8;
        }


        /* Hero benefits */

        .hubli-hero-benefits {
            display: flex;

            flex-wrap: wrap;

            gap: 13px 24px;

            padding: 0;

            margin: 0 0 30px;

            list-style: none;
        }


        .hubli-hero-benefits li {
            display: flex;

            align-items: center;

            gap: 8px;

            color: #ffffff;

            font-size: 14px;

            font-weight: 600;
        }


        .hubli-hero-benefits i {
            color: var(--hubli-yellow);

            font-size: 15px;
        }


        /* Hero buttons */

        .hubli-hero-actions {
            display: flex;

            align-items: center;

            flex-wrap: wrap;

            gap: 12px;
        }


        .hubli-primary-btn,
        .hubli-secondary-btn {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 9px;

            min-height: 50px;

            padding: 0 23px;

            border-radius: 999px;

            text-decoration: none;

            font-size: 14px;

            font-weight: 700;

            border: 0;

            transition:
                transform .2s ease,
                box-shadow .2s ease,
                background .2s ease;
        }


        .hubli-primary-btn {
            background: var(--hubli-yellow);

            color: #111111;

            box-shadow:
                0 10px 24px rgba(255, 212, 0, .18);
        }


        .hubli-primary-btn:hover {
            color: #111111;

            transform: translateY(-3px);

            box-shadow:
                0 15px 30px rgba(255, 212, 0, .25);
        }


        .hubli-secondary-btn {
            background: rgba(255, 255, 255, .08);

            color: #ffffff;

            border: 1px solid rgba(255, 255, 255, .25);
        }


        .hubli-secondary-btn:hover {
            color: #ffffff;

            background: rgba(255, 255, 255, .15);

            transform: translateY(-3px);
        }


        /* =========================================================
   HERO PROFILE CARD
========================================================= */

        .hubli-profile-area {
            display: flex;

            justify-content: center;
        }


        .hubli-profile-card {
            position: relative;

            width: min(100%, 440px);

            padding: 32px 30px 28px;

            text-align: center;

            border-radius: 28px;

            border: 1px solid rgba(255, 255, 255, .18);

            background: rgba(255, 255, 255, .08);

            backdrop-filter: blur(10px);

            box-shadow:
                0 25px 60px rgba(0, 0, 0, .18);
        }


        .hubli-profile-badge {
            position: absolute;

            right: 20px;
            top: 20px;

            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding: 8px 13px;

            border-radius: 999px;

            background: var(--hubli-yellow);

            color: #161616;

            font-size: 11px;

            font-weight: 800;
        }


        .hubli-profile-image {
            width: 245px;
            height: 245px;

            object-fit: cover;

            border-radius: 50%;

            border: 7px solid rgba(255, 255, 255, .9);

            box-shadow:
                0 18px 40px rgba(0, 0, 0, .24);
        }


        .hubli-profile-card h3 {
            margin: 22px 0 5px;

            color: #ffffff;

            font-size: 25px;

            font-weight: 800;
        }


        .hubli-profile-card p {
            margin: 0;

            color: rgba(255, 255, 255, .74);

            font-size: 13px;

            line-height: 1.6;
        }


        .hubli-validate-btn {
            display: inline-flex;

            align-items: center;

            gap: 8px;

            margin-top: 18px;

            padding: 10px 17px;

            border-radius: 999px;

            background: rgba(255, 255, 255, .10);

            border: 1px solid rgba(255, 255, 255, .20);

            color: #ffffff;

            text-decoration: none;

            font-size: 12px;

            font-weight: 700;

            transition: .2s ease;
        }


        .hubli-validate-btn:hover {
            color: #111111;

            background: var(--hubli-yellow);
        }


        /* =========================================================
   TRUST STRIP
========================================================= */

        .hubli-trust {
            position: relative;
            z-index: 4;

            /* 10px GAP between HERO and TRUST STRIP */
            margin-top: 10px;
        }

        .hubli-trust-inner {
            display: grid;
            grid-template-columns: repeat(4, 1fr);

            overflow: hidden;

            border-radius: 20px;
            background: #ffffff;
            border: 1px solid #e9edf4;

            box-shadow:
                0 14px 35px rgba(17, 35, 61, .08);
        }

        .hubli-trust-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;

            min-height: 78px;
            padding: 14px 18px;

            border-right: 1px solid #edf0f5;

            color: #27364c;
            font-size: 13px;
            font-weight: 700;
        }

        .hubli-trust-item:last-child {
            border-right: 0;
        }

        .hubli-trust-item i {
            color: #e3bb00;
            font-size: 18px;
        }


        /* =========================================================
   WHY US
========================================================= */

        .hubli-why-section {
            background: #ffffff;
        }


        .hubli-feature-card {
            height: 100%;

            padding: 30px 25px;

            border-radius: 20px;

            border: 1px solid #e9edf4;

            background: #ffffff;

            box-shadow:
                0 8px 24px rgba(18, 40, 70, .045);

            transition:
                transform .25s ease,
                box-shadow .25s ease,
                border-color .25s ease;
        }


        .hubli-feature-card:hover {
            transform: translateY(-7px);

            border-color: rgba(255, 212, 0, .65);

            box-shadow:
                0 18px 35px rgba(18, 40, 70, .09);
        }


        .hubli-feature-icon {
            width: 54px;
            height: 54px;

            display: flex;

            align-items: center;
            justify-content: center;

            margin-bottom: 19px;

            border-radius: 15px;

            background: #fff7cf;

            color: #b38d00;

            font-size: 20px;
        }


        .hubli-feature-card h3 {
            margin-bottom: 9px;

            color: var(--hubli-text);

            font-size: 18px;

            font-weight: 800;
        }


        .hubli-feature-card p {
            margin: 0;

            color: var(--hubli-muted);

            font-size: 13px;

            line-height: 1.7;
        }


        /* =========================================================
   COURSES
========================================================= */

        .hubli-courses-section {
            background: var(--hubli-light);
        }


        .hubli-course-card {
            position: relative;

            height: 100%;

            padding: 28px;

            border-radius: 22px;

            border: 1px solid #e9edf4;

            background: #ffffff;

            box-shadow:
                0 8px 22px rgba(18, 40, 70, .045);

            transition:
                transform .25s ease,
                box-shadow .25s ease;
        }


        .hubli-course-card:hover {
            transform: translateY(-7px);

            box-shadow:
                0 18px 38px rgba(18, 40, 70, .10);
        }


        .hubli-course-icon {
            width: 52px;
            height: 52px;

            display: flex;

            align-items: center;
            justify-content: center;

            margin-bottom: 20px;

            border-radius: 15px;

            background: var(--hubli-purple);

            color: var(--hubli-yellow);

            font-size: 19px;
        }


        .hubli-course-card h3 {
            margin-bottom: 10px;

            color: var(--hubli-text);

            font-size: 20px;

            font-weight: 800;
        }


        .hubli-course-card p {
            min-height: 51px;

            margin-bottom: 23px;

            color: var(--hubli-muted);

            font-size: 13px;

            line-height: 1.7;
        }


        .hubli-course-link {
            display: inline-flex;

            align-items: center;

            gap: 7px;

            padding: 10px 16px;

            border-radius: 999px;

            border: 1px solid #e1e6ef;

            background: #ffffff;

            color: var(--hubli-purple);

            font-size: 12px;

            font-weight: 800;

            cursor: pointer;

            transition: .2s ease;
        }


        .hubli-course-link:hover {
            border-color: var(--hubli-yellow);

            background: var(--hubli-yellow);

            color: #111111;
        }


        /* =========================================================
   HOW WE TRAIN
========================================================= */

        .hubli-process-section {
            background: #ffffff;
        }


        .hubli-process {
            position: relative;

            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 20px;
        }


        .hubli-process::before {
            content: "";

            position: absolute;

            left: 11%;
            right: 11%;

            top: 38px;

            height: 2px;

            background:
                linear-gradient(90deg,
                    #ffd400,
                    #dce2eb,
                    #ffd400);

            z-index: 0;
        }


        .hubli-process-item {
            position: relative;

            z-index: 1;

            text-align: center;
        }


        .hubli-process-number {
            width: 76px;
            height: 76px;

            display: flex;

            align-items: center;
            justify-content: center;

            margin: 0 auto 20px;

            border-radius: 50%;

            background: #ffffff;

            border: 7px solid #fff5c5;

            color: var(--hubli-purple);

            font-size: 20px;

            font-weight: 800;

            box-shadow:
                0 7px 20px rgba(20, 35, 60, .08);
        }


        .hubli-process-item h3 {
            margin-bottom: 8px;

            font-size: 17px;

            font-weight: 800;

            color: var(--hubli-text);
        }


        .hubli-process-item p {
            max-width: 220px;

            margin: 0 auto;

            color: var(--hubli-muted);

            font-size: 12px;

            line-height: 1.7;
        }


        /* =========================================================
   TESTIMONIALS
========================================================= */

        .hubli-testimonial-section {
            background: var(--hubli-light);
        }


        .hubli-testimonial-wrap {
            position: relative;

            max-width: 900px;

            margin: auto;

            overflow: hidden;
        }


        .hubli-testimonial-track {
            display: flex;

            transition:
                transform .55s cubic-bezier(.4, 0, .2, 1);
        }


        .hubli-testimonial-slide {
            min-width: 100%;

            padding: 5px 10px;
        }


        .hubli-testimonial-card {
            position: relative;

            padding: 38px;

            border-radius: 24px;

            background: #ffffff;

            border: 1px solid #e8edf4;

            box-shadow:
                0 10px 30px rgba(18, 40, 70, .06);
        }


        .hubli-quote-icon {
            color: #ead000;

            font-size: 30px;

            margin-bottom: 15px;
        }


        .hubli-testimonial-card blockquote {
            margin: 0;

            color: #35445a;

            font-size: 16px;

            line-height: 1.85;

            font-style: italic;
        }


        .hubli-testimonial-person {
            display: flex;

            align-items: center;

            gap: 13px;

            margin-top: 25px;
        }


        .hubli-person-avatar {
            width: 44px;
            height: 44px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: var(--hubli-purple);

            color: var(--hubli-yellow);

            font-weight: 800;
        }


        .hubli-testimonial-person strong {
            display: block;

            color: var(--hubli-text);

            font-size: 14px;
        }


        .hubli-testimonial-person span {
            color: var(--hubli-muted);

            font-size: 12px;
        }


        .hubli-testimonial-controls {
            display: flex;

            justify-content: center;

            gap: 8px;

            margin-top: 22px;
        }


        .hubli-testimonial-dot {
            width: 8px;
            height: 8px;

            padding: 0;

            border: 0;

            border-radius: 50%;

            background: #ccd4df;

            cursor: pointer;

            transition: .2s ease;
        }


        .hubli-testimonial-dot.active {
            width: 25px;

            border-radius: 10px;

            background: var(--hubli-yellow);
        }


        /* =========================================================
   ALUMNI CAROUSEL
    ========================================================= */

        .hubli-alumni-section {
            background: #ffffff;
        }


        .hubli-alumni-wrapper {
            position: relative;

            display: flex;

            align-items: center;

            gap: 10px;
        }


        .hubli-alumni-viewport {
            flex: 1;

            overflow: hidden;

            padding: 10px 0 20px;
        }


        .hubli-alumni-track {
            display: flex;

            transition:
                transform .55s cubic-bezier(.4, 0, .2, 1);
        }


        .hubli-alumni-slide {
            flex: 0 0 25%;

            padding: 0 8px;
        }


        .hubli-company-card {
            height: 120px;

            display: flex;

            align-items: center;
            justify-content: center;

            padding: 18px;

            border-radius: 18px;

            border: 1px solid #edf0f5;

            background: #fafbfc;

            transition:
                transform .25s ease,
                box-shadow .25s ease,
                background .25s ease;
        }


        .hubli-company-card:hover {
            transform: translateY(-5px);

            background: #ffffff;

            box-shadow:
                0 12px 28px rgba(18, 40, 70, .08);
        }


        .hubli-company-card img {
            max-width: 135px;

            max-height: 65px;

            width: auto;

            object-fit: contain;

            filter: grayscale(100%);

            opacity: .62;

            transition:
                filter .25s ease,
                opacity .25s ease,
                transform .25s ease;
        }


        .hubli-company-card:hover img {
            filter: grayscale(0);

            opacity: 1;

            transform: scale(1.04);
        }


        .hubli-alumni-arrow {
            flex-shrink: 0;

            width: 42px;
            height: 42px;

            display: flex;

            align-items: center;
            justify-content: center;

            border: 0;

            border-radius: 50%;

            background: var(--hubli-purple);

            color: #ffffff;

            cursor: pointer;

            transition: .2s ease;
        }


        .hubli-alumni-arrow:hover {
            background: var(--hubli-yellow);

            color: #111111;

            transform: scale(1.06);
        }


        .hubli-alumni-dots {
            display: flex;

            justify-content: center;

            gap: 7px;

            margin-top: 18px;
        }


        .hubli-alumni-dots button {
            width: 7px;
            height: 7px;

            padding: 0;

            border: 0;

            border-radius: 50%;

            background: #ccd4df;

            cursor: pointer;
        }


        .hubli-alumni-dots button.active {
            width: 23px;

            border-radius: 10px;

            background: var(--hubli-yellow);
        }


        /* =========================================================
   VISIT HUBLI
========================================================= */

        .hubli-visit-section {
            background: var(--hubli-light);
        }


        .hubli-visit-card {
            height: 100%;

            padding: 35px;

            border-radius: 24px;

            background: #ffffff;

            border: 1px solid #e7ecf3;

            box-shadow:
                0 10px 28px rgba(18, 40, 70, .05);
        }


        .hubli-visit-card h3 {
            margin-bottom: 14px;

            color: var(--hubli-text);

            font-size: 26px;

            font-weight: 800;
        }


        .hubli-visit-card>p {
            color: var(--hubli-muted);

            line-height: 1.8;

            font-size: 14px;
        }


        .hubli-contact-item {
            display: flex;

            align-items: flex-start;

            gap: 13px;

            margin-top: 22px;
        }


        .hubli-contact-icon {
            flex-shrink: 0;

            width: 42px;
            height: 42px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 12px;

            background: #fff6cf;

            color: #ad8800;
        }


        .hubli-contact-item strong {
            display: block;

            margin-bottom: 3px;

            color: var(--hubli-text);

            font-size: 13px;
        }


        .hubli-contact-item span,
        .hubli-contact-item a {
            color: var(--hubli-muted);

            font-size: 13px;

            line-height: 1.6;

            text-decoration: none;
        }


        .hubli-contact-item a:hover {
            color: var(--hubli-purple);
        }


        .hubli-map-wrap {
            height: 100%;

            min-height: 430px;

            overflow: hidden;

            border-radius: 24px;

            border: 1px solid #e5eaf1;

            box-shadow:
                0 10px 28px rgba(18, 40, 70, .06);
        }


        .hubli-map {
            width: 100%;
            height: 100%;

            min-height: 430px;

            display: block;

            border: 0;
        }


        /* =========================================================
   MODAL
   Existing enquiry functionality preserved
========================================================= */

        #demomodal .modal-content {
            border: 0;

            border-radius: 22px;

            overflow: hidden;

            box-shadow:
                0 25px 70px rgba(0, 0, 0, .20);
        }


        #demomodal .modal-header {
            padding: 22px 24px;

            border-bottom: 1px solid #edf0f5;
        }


        #demomodal .modal-title {
            color: var(--hubli-purple) !important;

            font-weight: 800 !important;
        }


        #demomodal .modal-body {
            padding: 24px;
        }


        #demomodal .label {
            display: block;

            margin-bottom: 7px;

            color: #29384d;

            font-size: 13px;
        }


        #demomodal .form-control,
        #demomodal .form-select {
            min-height: 48px;

            margin-bottom: 17px;

            border-radius: 11px;

            border: 1px solid #dfe5ed;

            font-family: 'Poppins', sans-serif;

            font-size: 13px;
        }


        #demomodal .form-control:focus,
        #demomodal .form-select:focus {
            border-color: #bba100;

            box-shadow:
                0 0 0 3px rgba(255, 212, 0, .16);
        }


        #demomodal .modal-footer {
            padding: 0;

            border-top: 0;
        }


        #demomodal .modal-footer .btn-primary {
            background: var(--hubli-purple);

            border-color: var(--hubli-purple);
        }


        /* =========================================================
   RESPONSIVE
========================================================= */

        @media (max-width: 1199px) {

            .hubli-hero h1 {
                font-size: 50px;
            }

            .hubli-profile-image {
                width: 220px;
                height: 220px;
            }

        }


        @media (max-width: 991.98px) {

            .hubli-hero {
                padding: 55px 0;
            }


            .hubli-hero-content {
                padding-right: 0;

                text-align: center;
            }


            .hubli-hero h1,
            .hubli-hero-description {
                margin-left: auto;
                margin-right: auto;
            }


            .hubli-hero-benefits {
                justify-content: center;
            }


            .hubli-hero-actions {
                justify-content: center;
            }


            .hubli-profile-area {
                margin-top: 45px;
            }


            .hubli-trust-inner {
                grid-template-columns:
                    repeat(2, 1fr);
            }


            .hubli-trust-item:nth-child(2) {
                border-right: 0;
            }


            .hubli-trust-item:nth-child(-n+2) {
                border-bottom: 1px solid #edf0f5;
            }


            .hubli-process {
                grid-template-columns:
                    repeat(2, 1fr);

                row-gap: 45px;
            }


            .hubli-process::before {
                display: none;
            }


            .hubli-alumni-slide {
                flex-basis: 33.333333%;
            }

        }


        @media (max-width: 767.98px) {

            .hubli-section {
                padding: 70px 0;
            }


            .hubli-heading {
                margin-bottom: 38px;
            }


            .hubli-heading h2 {
                font-size: 32px;

                letter-spacing: -.7px;
            }


            .hubli-hero {
                margin-top: 12px;

                padding: 45px 0 50px;

                border-radius: 0 0 20px 20px;
            }


            .hubli-hero h1 {
                font-size: 38px;

                letter-spacing: -1.2px;
            }


            .hubli-hero-description {
                font-size: 15px;
            }


            .hubli-hero-benefits {
                flex-direction: column;

                align-items: center;

                gap: 10px;
            }


            .hubli-hero-actions {
                flex-direction: column;

                width: 100%;
            }


            .hubli-primary-btn,
            .hubli-secondary-btn {
                width: 100%;

                max-width: 290px;
            }


            .hubli-profile-card {
                padding: 27px 20px 24px;
            }


            .hubli-profile-badge {
                right: 12px;
                top: 12px;

                font-size: 10px;
            }


            .hubli-profile-image {
                width: 200px;
                height: 200px;
            }


            .hubli-trust {
                margin-top: -12px;

                padding-left: 10px;
                padding-right: 10px;
            }


            .hubli-trust-inner {
                grid-template-columns: 1fr;
            }


            .hubli-trust-item {
                border-right: 0 !important;

                border-bottom: 1px solid #edf0f5;
            }


            .hubli-trust-item:last-child {
                border-bottom: 0;
            }


            .hubli-process {
                grid-template-columns: 1fr;
            }


            .hubli-process-item p {
                max-width: 290px;
            }


            .hubli-testimonial-card {
                padding: 28px 22px;
            }


            .hubli-testimonial-card blockquote {
                font-size: 14px;
            }


            .hubli-alumni-slide {
                flex-basis: 50%;
            }


            .hubli-company-card {
                height: 105px;
            }


            .hubli-company-card img {
                max-width: 105px;
                max-height: 55px;
            }


            .hubli-alumni-arrow {
                width: 36px;
                height: 36px;

                font-size: 11px;
            }


            .hubli-visit-card {
                padding: 28px 22px;
            }


            .hubli-map-wrap,
            .hubli-map {
                min-height: 350px;
            }

        }


        @media (max-width: 480px) {

            .hubli-hero h1 {
                font-size: 32px;
            }


            .hubli-profile-image {
                width: 175px;
                height: 175px;
            }


            .hubli-alumni-slide {
                flex-basis: 100%;
            }

        }

        .features-strip {
            position: relative;
            z-index: 5;

            width: calc(100% - 80px);
            max-width: 1220px;

            /* 10px gap between HERO and FEATURE STRIP */
            margin: 0px auto 0;

            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 22px;

            display: grid;
            grid-template-columns: repeat(4, 1fr);

            box-shadow: 0 15px 35px rgba(15, 23, 42, 0.10);
            overflow: hidden;
        }

        .feature-item {
            min-height: 86px;
            padding: 20px 28px;

            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;

            border-right: 1px solid #e5e7eb;
        }

        .feature-item:last-child {
            border-right: none;
        }

        .feature-item i,
        .feature-item .icon {
            color: #facc15;
            font-size: 22px;
            flex-shrink: 0;
        }

        .feature-item span {
            color: #172554;
            font-size: 15px;
            font-weight: 700;
            white-space: nowrap;
        }
    </style>

</head>


<body>


    <!-- =========================================================
     HERO
========================================================= -->

    <section class="hubli-hero">

        <div class="container">

            <div class="row align-items-center gy-5">

                <div class="col-lg-7">

                    <div class="hubli-hero-content">

                        <div class="hubli-hero-eyebrow">
                            <i class="fas fa-graduation-cap"></i>
                            Practical Tech Training in Hubli
                        </div>


                        <h1>
                            Build
                            <span>Job-Ready</span>
                            Skills & Launch Your Career.
                        </h1>


                        <p class="hubli-hero-description">
                            Learn in-demand technology skills through practical
                            training, live projects and career-focused guidance
                            designed to help you move confidently toward your
                            next opportunity.
                        </p>


                        <ul class="hubli-hero-benefits">

                            <li>
                                <i class="fas fa-check-circle"></i>
                                Hands-on Experience
                            </li>

                            <li>
                                <i class="fas fa-check-circle"></i>
                                Certified Trainer
                            </li>

                            <li>
                                <i class="fas fa-check-circle"></i>
                                Live Practical Projects
                            </li>

                        </ul>


                        <div class="hubli-hero-actions">

                            <button
                                type="button"
                                class="hubli-primary-btn cta-button"
                                data-bs-toggle="modal"
                                data-bs-target="#demomodal">

                                <i class="fas fa-calendar-check"></i>

                                Book FREE Demo

                            </button>


                            <a
                                href="#hubli-courses"
                                class="hubli-secondary-btn">

                                <i class="fas fa-layer-group"></i>

                                Explore Courses

                            </a>

                        </div>

                    </div>

                </div>


                <div class="col-lg-5">

                    <div class="hubli-profile-area">

                        <div class="hubli-profile-card">

                            <div class="hubli-profile-badge">

                                <i class="fas fa-certificate"></i>

                                Certified Trainer

                            </div>


                            <img
                                src="../img/hifzashaikh.jpeg"
                                alt="Hifza Shaikh, CEO of DharwadHubballiTutor"
                                class="hubli-profile-image">


                            <h3>
                                Hifza Shaikh
                            </h3>


                            <p>
                                Microsoft Certified:
                                Power BI Data Analyst Associate
                            </p>


                            <a
                                href="https://learn.microsoft.com/api/credentials/share/en-us/HifzaShaikh-6127/7A20FAB4E28C2BD3?sharingId=2AAFD99171047755"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="hubli-validate-btn">

                                <i class="fas fa-shield-alt"></i>

                                Validate Certificate

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- =========================================================
     TRUST STRIP
========================================================= -->

    <section class="hubli-trust">

        <div class="container">

            <div class="hubli-trust-inner">

                <div class="hubli-trust-item">

                    <i class="fas fa-laptop-code"></i>

                    Practical Training

                </div>


                <div class="hubli-trust-item">

                    <i class="fas fa-project-diagram"></i>

                    Live Projects

                </div>


                <div class="hubli-trust-item">

                    <i class="fas fa-certificate"></i>

                    Certified Trainer

                </div>


                <div class="hubli-trust-item">

                    <i class="fas fa-briefcase"></i>

                    Career Guidance

                </div>

            </div>

        </div>

    </section>



    <!-- =========================================================
     WHY CHOOSE US
========================================================= -->

    <section class="hubli-section hubli-why-section">

        <div class="container">

            <div class="hubli-heading">

                <div class="hubli-eyebrow">

                    <i class="fas fa-star"></i>

                    WHY CHOOSE US

                </div>


                <h2>
                    Training Designed Around Your Career
                </h2>


                <p>
                    Build practical skills, work on meaningful projects
                    and get guidance that goes beyond classroom learning.
                </p>

            </div>


            <div class="row gy-4">

                <div class="col-md-6 col-lg-3">

                    <div class="hubli-feature-card">

                        <div class="hubli-feature-icon">

                            <i class="fas fa-chalkboard-teacher"></i>

                        </div>

                        <h3>
                            Expert Trainers
                        </h3>

                        <p>
                            Learn from trainers with practical,
                            real-world technology experience.
                        </p>

                    </div>

                </div>


                <div class="col-md-6 col-lg-3">

                    <div class="hubli-feature-card">

                        <div class="hubli-feature-icon">

                            <i class="fas fa-briefcase"></i>

                        </div>

                        <h3>
                            Career Support
                        </h3>

                        <p>
                            Get guidance designed to help you prepare
                            for interviews and career opportunities.
                        </p>

                    </div>

                </div>


                <div class="col-md-6 col-lg-3">

                    <div class="hubli-feature-card">

                        <div class="hubli-feature-icon">

                            <i class="fas fa-laptop-code"></i>

                        </div>

                        <h3>
                            Practical Projects
                        </h3>

                        <p>
                            Learn by building practical projects that
                            strengthen your portfolio and confidence.
                        </p>

                    </div>

                </div>


                <div class="col-md-6 col-lg-3">

                    <div class="hubli-feature-card">

                        <div class="hubli-feature-icon">

                            <i class="fas fa-certificate"></i>

                        </div>

                        <h3>
                            Industry Certification
                        </h3>

                        <p>
                            Develop recognized skills and strengthen
                            your professional profile.
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
        class="hubli-section hubli-courses-section"
        id="hubli-courses">

        <div class="container">

            <div class="hubli-heading">

                <div class="hubli-eyebrow">

                    <i class="fas fa-layer-group"></i>

                    PROFESSIONAL COURSES

                </div>


                <h2>
                    Learn Skills That Move Your Career Forward
                </h2>


                <p>
                    Choose practical technology courses designed around
                    real-world skills, projects and career development.
                </p>

            </div>


            <div class="row gy-4">


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
                        'Learn Excel from fundamentals to advanced analytics and automation.'
                    ],

                    [
                        'title' => 'Data Analytics',
                        'icon' => 'fa-chart-line',
                        'description' =>
                        'Analyze data using Python, SQL and Power BI.'
                    ],

                    [
                        'title' => 'Full Stack Development',
                        'icon' => 'fa-code',
                        'description' =>
                        'Build modern end-to-end web applications and development skills.'
                    ],

                    [
                        'title' => 'Digital Marketing',
                        'icon' => 'fa-bullhorn',
                        'description' =>
                        'Develop practical skills in SEO, SEM and social media marketing.'
                    ],

                    [
                        'title' => 'Software Testing',
                        'icon' => 'fa-bug',
                        'description' =>
                        'Learn manual and automated testing methodologies.'
                    ]

                ];


                foreach ($courses as $course) {

                    echo '

                <div class="col-md-6 col-lg-4">

                    <div class="hubli-course-card">

                        <div class="hubli-course-icon">

                            <i class="fas ' . $course['icon'] . '"></i>

                        </div>


                        <h3>
                            ' . htmlspecialchars($course['title']) . '
                        </h3>


                        <p>
                            ' . htmlspecialchars($course['description']) . '
                        </p>


                        <button
                            type="button"
                            class="hubli-course-link cta-button"
                            data-bs-toggle="modal"
                            data-bs-target="#demomodal"
                        >

                            Enquire Now

                            <i class="fas fa-arrow-right"></i>

                        </button>

                    </div>

                </div>

                ';
                }

                ?>

            </div>

        </div>

    </section>



    <!-- =========================================================
     HOW WE TRAIN
========================================================= -->

    <section class="hubli-section hubli-process-section">

        <div class="container">

            <div class="hubli-heading">

                <div class="hubli-eyebrow">

                    <i class="fas fa-route"></i>

                    OUR TRAINING APPROACH

                </div>


                <h2>
                    Learn. Practice. Build. Launch.
                </h2>


                <p>
                    Our approach connects classroom learning with
                    practical work and career preparation.
                </p>

            </div>


            <div class="hubli-process">


                <div class="hubli-process-item">

                    <div class="hubli-process-number">
                        01
                    </div>

                    <h3>
                        Learn
                    </h3>

                    <p>
                        Understand the concepts and tools
                        required for your chosen skill.
                    </p>

                </div>


                <div class="hubli-process-item">

                    <div class="hubli-process-number">
                        02
                    </div>

                    <h3>
                        Practice
                    </h3>

                    <p>
                        Strengthen your understanding through
                        guided practical exercises.
                    </p>

                </div>


                <div class="hubli-process-item">

                    <div class="hubli-process-number">
                        03
                    </div>

                    <h3>
                        Build
                    </h3>

                    <p>
                        Apply your knowledge through live,
                        practical project work.
                    </p>

                </div>


                <div class="hubli-process-item">

                    <div class="hubli-process-number">
                        04
                    </div>

                    <h3>
                        Launch
                    </h3>

                    <p>
                        Prepare your portfolio, interviews
                        and next career opportunity.
                    </p>

                </div>

            </div>

        </div>

    </section>



    <!-- =========================================================
     TESTIMONIALS
========================================================= -->

    <section class="hubli-section hubli-testimonial-section">

        <div class="container">

            <div class="hubli-heading">

                <div class="hubli-eyebrow">

                    <i class="fas fa-comments"></i>

                    STUDENT SUCCESS

                </div>


                <h2>
                    What Our Students Say
                </h2>


                <p>
                    Hear how practical learning and project-based
                    training helped our students move forward.
                </p>

            </div>


            <div class="hubli-testimonial-wrap">

                <div
                    class="hubli-testimonial-track"
                    id="hubliTestimonialTrack">


                    <div class="hubli-testimonial-slide">

                        <div class="hubli-testimonial-card">

                            <div class="hubli-quote-icon">
                                <i class="fas fa-quote-left"></i>
                            </div>


                            <blockquote>

                                "The Power BI course was amazing.
                                The trainer explained complex topics
                                so easily. I got a job within a month
                                of completing the course!"

                            </blockquote>


                            <div class="hubli-testimonial-person">

                                <div class="hubli-person-avatar">
                                    RP
                                </div>

                                <div>

                                    <strong>
                                        Rohan P.
                                    </strong>

                                    <span>
                                        Power BI Course
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="hubli-testimonial-slide">

                        <div class="hubli-testimonial-card">

                            <div class="hubli-quote-icon">
                                <i class="fas fa-quote-left"></i>
                            </div>


                            <blockquote>

                                "Best place in Hubli for full-stack
                                development. The focus on live projects
                                helped me build a great portfolio and
                                crack interviews easily."

                            </blockquote>


                            <div class="hubli-testimonial-person">

                                <div class="hubli-person-avatar">
                                    AK
                                </div>

                                <div>

                                    <strong>
                                        Anjali K.
                                    </strong>

                                    <span>
                                        Full Stack Development
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>


                </div>


                <div
                    class="hubli-testimonial-controls"
                    id="hubliTestimonialDots">

                    <button
                        type="button"
                        class="hubli-testimonial-dot active"
                        data-testimonial="0"
                        aria-label="Show testimonial 1"></button>


                    <button
                        type="button"
                        class="hubli-testimonial-dot"
                        data-testimonial="1"
                        aria-label="Show testimonial 2"></button>

                </div>

            </div>

        </div>

    </section>



    <!-- =========================================================
     ALUMNI
========================================================= -->

    <section class="hubli-section hubli-alumni-section">

        <div class="container">

            <div class="hubli-heading">

                <div class="hubli-eyebrow">

                    <i class="fas fa-building"></i>

                    OUR ALUMNI

                </div>


                <h2>
                    Our Alumni Shine At
                </h2>


                <p>
                    Our learners have built careers across
                    leading organizations.
                </p>

            </div>


            <div class="hubli-alumni-wrapper">


                <button
                    type="button"
                    class="hubli-alumni-arrow"
                    id="hubliAlumniPrev"
                    aria-label="Previous alumni company">

                    <i class="fas fa-chevron-left"></i>

                </button>


                <div class="hubli-alumni-viewport">

                    <div
                        class="hubli-alumni-track"
                        id="hubliAlumniTrack">


                        <div class="hubli-alumni-slide">

                            <div class="hubli-company-card">

                                <img
                                    src="../img/Infosys logo.jpg"
                                    alt="Infosys"
                                    loading="lazy">

                            </div>

                        </div>


                        <div class="hubli-alumni-slide">

                            <div class="hubli-company-card">

                                <img
                                    src="../img/TCS logo.jpg"
                                    alt="TCS"
                                    loading="lazy">

                            </div>

                        </div>


                        <div class="hubli-alumni-slide">

                            <div class="hubli-company-card">

                                <img
                                    src="../img/L T logo.jpg"
                                    alt="L&T"
                                    loading="lazy">

                            </div>

                        </div>


                        <div class="hubli-alumni-slide">

                            <div class="hubli-company-card">

                                <img
                                    src="../img/Lumen logo.jpg"
                                    alt="Lumen"
                                    loading="lazy">

                            </div>

                        </div>


                        <div class="hubli-alumni-slide">

                            <div class="hubli-company-card">

                                <img
                                    src="../img/AJIO logo.jpg"
                                    alt="AJIO"
                                    loading="lazy">

                            </div>

                        </div>


                        <div class="hubli-alumni-slide">

                            <div class="hubli-company-card">

                                <img
                                    src="../img/T system logo.jpg"
                                    alt="T Systems"
                                    loading="lazy">

                            </div>

                        </div>


                    </div>

                </div>


                <button
                    type="button"
                    class="hubli-alumni-arrow"
                    id="hubliAlumniNext"
                    aria-label="Next alumni company">

                    <i class="fas fa-chevron-right"></i>

                </button>

            </div>


            <div
                class="hubli-alumni-dots"
                id="hubliAlumniDots">

                <button
                    type="button"
                    class="active"
                    data-alumni="0"
                    aria-label="Alumni slide 1"></button>

                <button
                    type="button"
                    data-alumni="1"
                    aria-label="Alumni slide 2"></button>

                <button
                    type="button"
                    data-alumni="2"
                    aria-label="Alumni slide 3"></button>

            </div>

        </div>

    </section>



    <!-- =========================================================
     VISIT HUBLI
========================================================= -->

    <section class="hubli-section hubli-visit-section">

        <div class="container">

            <div class="hubli-heading">

                <div class="hubli-eyebrow">

                    <i class="fas fa-map-marker-alt"></i>

                    VISIT OUR HUBLI BRANCH

                </div>


                <h2>
                    Let's Talk About Your Career
                </h2>


                <p>
                    Visit our Hubli branch and meet our counselors
                    for guidance about your learning journey.
                </p>

            </div>


            <div class="row gy-4 align-items-stretch">


                <div class="col-lg-5">

                    <div class="hubli-visit-card">

                        <h3>
                            Hubli Branch
                        </h3>


                        <p>
                            Come and meet our counselors for a
                            free career guidance session.
                        </p>


                        <div class="hubli-contact-item">

                            <div class="hubli-contact-icon">

                                <i class="fas fa-map-marker-alt"></i>

                            </div>


                            <div>

                                <strong>
                                    Address
                                </strong>

                                <span>

                                    Plot #26, Jaya Nagar,<br>
                                    Vidya Nagar, Hubli-580021<br>
                                    Karnataka, India

                                </span>

                            </div>

                        </div>


                        <div class="hubli-contact-item">

                            <div class="hubli-contact-icon">

                                <i class="fas fa-phone-alt"></i>

                            </div>


                            <div>

                                <strong>
                                    Contact
                                </strong>

                                <a href="tel:+919741237334">
                                    +91 9741237334
                                </a>

                            </div>

                        </div>


                        <div style="margin-top:28px;">

                            <button
                                type="button"
                                class="hubli-primary-btn cta-button"
                                data-bs-toggle="modal"
                                data-bs-target="#demomodal">

                                <i class="fas fa-calendar-check"></i>

                                Book a FREE Demo

                            </button>

                        </div>

                    </div>

                </div>


                <div class="col-lg-7">

                    <div class="hubli-map-wrap">

                        <iframe
                            class="hubli-map"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d123109.48192939209!2d75.07513225068908!3d15.36720582088609!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bb8d7fbf392e209%3A0x981b8c9c9e8507dc!2sDharwadhubballitutor%20(Power%20Bi%2C%20Advanced%20Excel%2CData%20analytics%2C%20full%20stack%20development%2C%20digital%20marketing%2Csoftware%20testing%2C)!5e0!3m2!1sen!2sin!4v1755591916853!5m2!1sen!2sin"
                            loading="lazy"
                            allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"></iframe>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- =========================================================
     EXISTING DEMO MODAL
     FUNCTIONAL LOGIC PRESERVED
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
                        aria-label="Close"></button>

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

                                        echo htmlspecialchars(
                                            $_SESSION['csrf_token']
                                        );
                                    }

                                    ?>">


                        <label
                            class="label"
                            for="name2">
                            <b>Name</b>
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
                            id="demofront"
                            value="front">


                        <label
                            class="label"
                            for="email2">
                            <b>Email</b>
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
                            <b>Phone Number</b>
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
                            <b>Course of Interest</b>
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

                                $courselist =
                                    DBcourse::selectall();


                                foreach ($courselist as $course) {

                                    echo
                                    "<option value='" .
                                        htmlspecialchars(
                                            $course->get_cname()
                                        ) .
                                        "'>" .
                                        htmlspecialchars(
                                            $course->get_cname()
                                        ) .
                                        "</option>";
                                }
                            }

                            ?>

                        </select>


                        <input
                            type="hidden"
                            id="recaptcha-token"
                            name="recaptcha-token">


                        <div class="modal-footer pb-0 px-0">

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



    <?php require_once("footer.php"); ?>



    <script>
        /* =========================================================
   ANALYTICS
   Existing CTA tracking preserved
========================================================= */

        document
            .querySelectorAll('.cta-button')
            .forEach(function(button) {

                button.addEventListener(
                    'click',
                    function() {

                        if (typeof gtag !== 'undefined') {

                            gtag(
                                'event',
                                'generate_lead', {
                                    'event_category': 'engagement',
                                    'event_label': 'Book Demo Modal Opened'
                                }
                            );

                        }

                    }
                );

            });



        /* =========================================================
           TESTIMONIAL CAROUSEL
        ========================================================= */

        (function() {

            const track =
                document.getElementById(
                    'hubliTestimonialTrack'
                );

            const dots =
                document.querySelectorAll(
                    '#hubliTestimonialDots .hubli-testimonial-dot'
                );


            if (!track || !dots.length) {
                return;
            }


            let current = 0;

            let timer;


            function showTestimonial(index) {

                current = index;

                track.style.transform =
                    'translateX(-' +
                    (current * 100) +
                    '%)';


                dots.forEach(
                    function(dot, dotIndex) {

                        dot.classList.toggle(
                            'active',
                            dotIndex === current
                        );

                    }
                );

            }


            function startAutoPlay() {

                timer =
                    setInterval(
                        function() {

                            current =
                                (current + 1) %
                                dots.length;

                            showTestimonial(current);

                        },
                        4500
                    );

            }


            function restartAutoPlay() {

                clearInterval(timer);

                startAutoPlay();

            }


            dots.forEach(
                function(dot, index) {

                    dot.addEventListener(
                        'click',
                        function() {

                            showTestimonial(index);

                            restartAutoPlay();

                        }
                    );

                }
            );


            startAutoPlay();

        })();



        /* =========================================================
           ALUMNI CAROUSEL
        ========================================================= */

        (function() {

            const track =
                document.getElementById(
                    'hubliAlumniTrack'
                );

            const slides =
                track ?
                Array.from(
                    track.querySelectorAll(
                        '.hubli-alumni-slide'
                    )
                ) : [];


            const prev =
                document.getElementById(
                    'hubliAlumniPrev'
                );


            const next =
                document.getElementById(
                    'hubliAlumniNext'
                );


            const dots =
                document.querySelectorAll(
                    '#hubliAlumniDots button'
                );


            if (!track || !slides.length) {
                return;
            }


            let current = 0;

            let timer;


            function visibleSlides() {

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


            function maxIndex() {

                return Math.max(
                    0,
                    slides.length - visibleSlides()
                );

            }


            function update() {

                const visible =
                    visibleSlides();

                const maximum =
                    maxIndex();


                if (current > maximum) {
                    current = 0;
                }


                const amount =
                    100 / visible;


                track.style.transform =
                    'translateX(-' +
                    (current * amount) +
                    '%)';


                dots.forEach(
                    function(dot, index) {

                        dot.classList.toggle(
                            'active',
                            index === current
                        );

                    }
                );

            }


            function nextSlide() {

                const maximum =
                    maxIndex();


                current =
                    current >= maximum ?
                    0 :
                    current + 1;


                update();

            }


            function previousSlide() {

                const maximum =
                    maxIndex();


                current =
                    current <= 0 ?
                    maximum :
                    current - 1;


                update();

            }


            function startAutoPlay() {

                timer =
                    setInterval(
                        nextSlide,
                        3200
                    );

            }


            function restartAutoPlay() {

                clearInterval(timer);

                startAutoPlay();

            }


            if (next) {

                next.addEventListener(
                    'click',
                    function() {

                        nextSlide();

                        restartAutoPlay();

                    }
                );

            }


            if (prev) {

                prev.addEventListener(
                    'click',
                    function() {

                        previousSlide();

                        restartAutoPlay();

                    }
                );

            }


            dots.forEach(
                function(dot, index) {

                    dot.addEventListener(
                        'click',
                        function() {

                            current =
                                Math.min(
                                    index,
                                    maxIndex()
                                );

                            update();

                            restartAutoPlay();

                        }
                    );

                }
            );


            const viewport =
                document.querySelector(
                    '.hubli-alumni-viewport'
                );


            if (viewport) {

                viewport.addEventListener(
                    'mouseenter',
                    function() {
                        clearInterval(timer);
                    }
                );


                viewport.addEventListener(
                    'mouseleave',
                    function() {
                        startAutoPlay();
                    }
                );

            }


            window.addEventListener(
                'resize',
                update
            );


            update();

            startAutoPlay();

        })();
    </script>


</body>

</html>