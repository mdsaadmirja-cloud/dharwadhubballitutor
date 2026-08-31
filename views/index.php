<?php require_once("navigation.php"); ?>
<style>
    body {
        margin: 0;
        min-height: 100vh;
        background: #F7F8FA;
    }

    section {
        position: relative;
    }

    section:not(.dht-hero):not(.alumni-cf-section) {
        padding-top: 0px;
        padding-bottom: 0px;
    }

    body {
        background:
            radial-gradient(circle at top left, #ffffff 0%, #F4F6FB 65%);
    }

    .credential-section {
        background: transparent;

        padding: 80px 0;
    }

    .cert-card {
        background: #ffffff;
        border: none;
        border-top: 4px solid #4042e2;
        border-radius: 10px;
        transition: all 0.4s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .cert-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }

    .icon-box {
        width: 70px;
        height: 70px;
        line-height: 70px;
        background: #f8f9ff;
        border-radius: 50%;
        margin: 0 auto 20px;
        font-size: 28px;
    }

    .border-mct {
        border-top-color: #00a4ef !important;
    }

    .border-iso {
        border-top-color: #28a745 !important;
    }

    .border-msme {
        border-top-color: #fd7e14 !important;
    }

    .border-nasscom {
        border-top-color: #2a0a5e !important;
    }

    .cert-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }

    .cert-text {
        font-size: 0.9rem;
        color: #666;
        line-height: 1.5;
    }

    /* ===============================
   GLOBAL SECTION HEADINGS
    ================================== */

    .section-title {
        text-align: center;
        margin-bottom: 60px;
        padding-top: 15px;

    }

    .section-tag {
        display: inline-block;
        padding: 8px 22px;
        background: #EEF2FF;
        color: #4042E2;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        border-radius: 50px;
        margin-bottom: 28px;
    }

    .section-title h2 {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        color: #14163A;
        line-height: 1.2;
        margin-bottom: 18px;
    }

    .section-title h2 span {
        color: #4042E2;
    }

    .section-title p {
        max-width: 720px;
        margin: auto;
        color: #666;
        font-size: 17px;
        line-height: 1.7;
    }

    .section-title::after {
        content: "";
        display: block;
        width: 90px;
        height: 5px;
        margin: 25px auto 0;
        border-radius: 50px;
        background: linear-gradient(90deg, #F6BE01, #4042E2);
    }

    @media (max-width:768px) {

        .section-title {
            margin-bottom: 40px;
            padding: 0 15px;
        }

        .section-tag {
            font-size: 11px;
            padding: 7px 18px;
        }

        .section-title h2 {
            font-size: clamp(1.8rem, 6vw, 2.2rem);
            line-height: 1.2;
            margin-bottom: 15px;
            overflow-wrap: anywhere;
        }

        .section-title p {
            font-size: 15px;
            line-height: 1.7;
        }

        .section-title::after {
            margin-top: 20px;
        }

    }
</style>
<style>
    .container {
        max-width: 1200px;
        margin: auto;
    }

    .projects-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
    }

    .project-card {
        background: white;
        border-radius: 14px;
        padding: 28px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .project-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.12);
    }

    .project-tag {
        display: inline-block;
        background: #e3f2fd;
        color: #1565c0;
        font-size: 13px;
        padding: 6px 14px;
        border-radius: 20px;
        margin-bottom: 12px;
    }

    .project-card h3 {
        font-size: 22px;
        margin-bottom: 10px;
        color: #0b3c5d;
    }

    .project-card p {
        font-size: 15px;
        line-height: 1.6;
        color: #555;
    }

    .tech {
        margin-top: 15px;
        font-size: 14px;
        color: #333;
    }

    .tech span {
        background: #f1f3f5;
        padding: 6px 10px;
        border-radius: 6px;
        margin-right: 6px;
        display: inline-block;
        margin-top: 6px;
    }

    .footer-note {
        background: #393fd5;
        color: white;
        padding: 60px 20px;
        text-align: center;
    }

    .footer-note h2 {
        font-size: 32px;
        margin-bottom: 15px;
    }

    .footer-note p {
        max-width: 900px;
        margin: auto;
        font-size: 17px;
        opacity: 0.9;
    }

    /* ===== NEW STATS SECTION (Mahjoz-style dark cards) ===== */
    .stats-section {
        background: transparent;

        padding: 60px 20px 70px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .stat-card {
        position: relative;
        background: #14163A;

        border-radius: 22px;
        padding: 26px 22px;
        min-height: 190px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        box-shadow: 0 12px 30px rgba(20, 15, 60, 0.18);
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        border: 3px solid #ffd700;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 22px 45px rgba(20, 15, 60, 0.28);
    }

    .stat-card::before {
        content: "";
        position: absolute;
        top: -30%;
        right: -40%;
        width: 220px;
        height: 220px;
        background: linear-gradient(135deg, rgba(120, 201, 255, 0.35), rgba(255, 215, 0, 0.15));
        border-radius: 50%;
        filter: blur(6px);
        opacity: 0.5;
        pointer-events: none;
    }

    .stat-card::after {
        content: "";
        position: absolute;
        bottom: -50px;
        right: -50px;
        width: 160px;
        height: 160px;
        border: 26px solid rgba(255, 215, 0, 0.35);
        border-radius: 50%;
        pointer-events: none;
    }

    .stat-logo-mark {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-bottom: auto;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
        position: relative;
        z-index: 1;
    }

    .stat-logo-mark img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .stat-card .stat-value {
        color: #FFD700;
        font-size: 2.1rem;
        font-weight: 800;
        line-height: 1.1;
        margin-top: 18px;
        position: relative;
        z-index: 1;
    }

    .stat-card .stat-label {
        color: #E8E9F3;
        font-size: 0.95rem;
        margin-top: 6px;
        position: relative;
        z-index: 1;
    }

    .stat-card .stat-sub {
        color: #B7B9D6;
        font-size: 0.8rem;
        margin-top: 2px;
        position: relative;
        z-index: 1;
    }

    .stat-card .stars i {
        color: #FFD700;
        font-size: 0.95rem;
        margin-right: 2px;
    }

    .stat-card .btn-review {
        margin-top: 14px;
        display: inline-block;
        background: #ffffff;
        color: #14163a;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 8px 16px;
        border-radius: 30px;
        text-decoration: none;
        position: relative;
        z-index: 1;
        width: fit-content;
        transition: all 0.25s ease;
    }

    .stat-card .btn-review:hover {
        background: #FFD700;
        color: #14163a;
    }

    @media (max-width: 991px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 575px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<style>
    /* ===== VELOS-INSPIRED HERO (background image, content one side, floating cards) =====
       FLUID RESPONSIVE REWRITE
       Same colors / fonts / animations / structure — only sizing units changed
       from fixed px + vh to clamp()/min()/vw-based fluid values, so the design
       scales continuously instead of snapping at hard breakpoints. */

    .dht-hero {
        position: relative;

        /* OLD: min-height: 88vh;
           PROBLEM: 88vh is a fixed % of the *raw* viewport height.
             - On short 13" laptop screens (768px tall) 88vh forces a huge box
               relative to content, causing excess empty space above/below text.
             - On ultrawide/4K monitors 88vh can become 900px-1600px+ tall,
               again mostly empty space.
             - On mobile Safari/Chrome, `vh` includes/excludes the address bar
               inconsistently, so the hero visibly jumps in height as the
               browser chrome shows/hides (a classic mobile layout shift).
           FIX: clamp() with a `dvh` (dynamic viewport height) preferred value,
             and hard min/max caps so the box never gets absurdly short or tall.
             dvh recalculates with the real visible viewport on mobile, killing
             the address-bar jump. Older browsers without dvh support simply
             ignore that declaration and keep the vh fallback above it. */
        min-height: 88vh;
        min-height: clamp(560px, 88dvh, 900px);

        width: calc(100% - 32px);
        max-width: 1320px;

        margin: 14px auto 0;

        overflow: hidden;
        display: flex;
        align-items: center;
        color: #fff;

        background: #14163a;

        border: 4px solid #F6BE01;
        border-radius: 20px;
        box-sizing: border-box;
    }

    /* Rotating background image layer (5 crossfading photos) — unchanged */
    .dht-hero-bg-carousel {
        position: absolute;
        inset: 0;
        z-index: 0;
    }

    .dht-hero-bg-img {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        opacity: 0;
        transition: opacity 1.6s ease-in-out;
    }

    .dht-hero-bg-img.active {
        opacity: 1;
    }

    /* Fixed gradient overlay — unchanged, same colors/stops */
    .dht-hero-overlay {
        position: absolute;
        inset: 0;
        z-index: 1;
        pointer-events: none;
        background-image: linear-gradient(100deg, rgba(15, 12, 46, 0.94) 0%, rgba(15, 12, 46, 0.78) 32%, rgba(36, 18, 106, 0.35) 60%, rgba(36, 18, 106, 0.15) 100%);
    }

    .dht-hero-inner {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 1240px;
        margin: 0 auto;

        /* OLD: padding: 70px 40px;  (single fixed value, then a second fixed
           value swapped in at the 991px breakpoint = a visible "jump" in
           spacing right at that width instead of a smooth shrink).
           FIX: fluid clamp() so padding shrinks/grows continuously with the
           viewport, landing on the same 70px/40px at desktop widths and the
           same ~50px/24px feel on small screens — no more discrete jump.
           UPDATE (button-cropping fix): the top/bottom value now also
           reacts to viewport HEIGHT via `dvh`, not just width. The title
           has forced <br> line breaks (5 lines always), so on a short-but-
           wide laptop screen (e.g. 1366x768) the old width-only padding
           stayed at a full 70px even though there wasn't 70px of vertical
           room to spare — that's what pushed the buttons below the fold.
           min(6vw, 6.5dvh) means whichever axis is tighter — width or
           height — controls the padding, so it compresses automatically
           on short screens while staying ~70px on normal/tall ones. */
        padding: clamp(20px, min(6vw, 6.5dvh), 70px) clamp(20px, 4vw, 40px);

        display: grid;
        /* OLD: grid-template-columns: 1.1fr 0.9fr;
           PROBLEM: pure fr-units have no floor, so on medium widths (e.g. a
           1000-1200px laptop window) the 0.9fr visual column can get
           squeezed uncomfortably narrow, pushing the fixed-width floating
           cards inside it past the column edge (the "cropped/overlapping
           card" symptom).
           FIX: minmax() gives each column a sane floor while keeping the
           same 1.1:0.9 proportion once there's room to spare. */
        grid-template-columns: minmax(0, 1.1fr) minmax(280px, 0.9fr);
        gap: clamp(20px, 3vw, 30px);
        align-items: center;
    }

    .dht-hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 215, 0, 0.14);
        border: 1px solid rgba(255, 215, 0, 0.4);
        color: #FFD700;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 7px 16px;
        border-radius: 30px;
        /* Height-aware, same reasoning as .dht-hero-inner padding above:
           frees up a bit more vertical room on short screens. */
        margin-bottom: clamp(8px, 2dvh, 22px);
    }

    .dht-hero-title {
        /* OLD: font-size: clamp(2.4rem, 5.5vw, 3.7rem);  (width-only).
           PROBLEM: this is the actual biggest space consumer on the page —
           the markup has forced <br> tags making the title ALWAYS wrap to
           5 lines. At the old max size (3.7rem/59px, line-height 1.08) that
           alone is ~320px tall no matter how short the viewport is, which
           is what was still pushing the buttons past the fold even after
           compressing the padding/margins.
           FIX: same min(vw, dvh) pattern as the padding above — on a short
           viewport the font-size (and therefore all 5 lines) now shrinks
           together, which saves far more vertical space than margins alone
           ever could. On normal/tall viewports (~950dvh, a typical 1080p
           browser window) this still resolves to essentially the original
           ~59px/3.7rem size. */
        font-size: clamp(2.4rem, min(5.5vw, 6.25dvh), 3.7rem);
        line-height: 1.08;
        font-weight: 700;
        letter-spacing: -0.02em;
        /* Height-aware margin: this title always wraps to 5 lines because of
           the <br> tags in the markup, so its total height doesn't shrink
           with viewport width — only this margin can give room back on
           short screens. */
        margin: 0 0 clamp(8px, 1.8dvh, 20px);
        text-transform: uppercase;
    }

    .dht-hero-title .accent {
        color: #FFD700;
    }

    .dht-hero-subtext {
        /* Small fluid nudge so long words can't force horizontal scroll on
           very narrow (< 340px) phones; visually identical everywhere else. */
        font-size: clamp(0.95rem, 0.9rem + 0.3vw, 1.05rem);
        color: rgba(255, 255, 255, 0.78);
        max-width: 480px;
        line-height: 1.6;
        /* Height-aware, same reasoning as above. */
        margin-bottom: clamp(12px, 3dvh, 32px);
    }

    .dht-hero-buttons {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
    }

    .btn-hero-yellow {
        background: #FFD700;
        color: #111;
        border: none;
        border-radius: 50px;

        /* OLD: width: 180px; height: 56px;
           PROBLEM: a hard-coded 180px width doesn't shrink on very narrow
           phones (~320-360px wide), which combined with the second button
           and `gap:14px` can push content wider than the viewport (cropped
           content / horizontal scroll).
           FIX: min-width keeps the same "pill" look at every size we already
           tested, but lets it compress a little on the smallest phones
           instead of overflowing. Height uses a tiny clamp for the same
           reason; at normal sizes this resolves to the original 56px. */
        min-width: 180px;
        width: auto;
        padding: 0 24px;
        height: clamp(48px, 5vw, 56px);

        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 8px 20px rgba(255, 215, 0, .35);
        transition: all .3s ease;
    }

    .btn-hero-yellow:hover {
        background: #e0a700;
        color: #111;
        transform: translateY(-2px);
    }

    .btn-hero-outline {
        background: rgba(255, 255, 255, 0.06);
        color: #fff;
        border: 1.5px solid rgba(255, 255, 255, 0.5);
        border-radius: 50px;

        /* Same fix as .btn-hero-yellow above, kept identical at desktop size. */
        min-width: 180px;
        width: auto;
        padding: 0 24px;
        height: clamp(48px, 5vw, 56px);

        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: all .3s ease;
    }

    .btn-hero-outline:hover {
        background: rgba(255, 255, 255, 0.16);
        color: #fff;
        transform: translateY(-2px);
    }

    .dht-hero-visual {
        position: relative;

        /* OLD: height: 420px;  (a fixed pixel height for the box that all
           floating cards are absolutely positioned inside).
           PROBLEM: the cards use top/left in *percentages* of this box, but
           the box's WIDTH is fluid (it's a grid column) while its HEIGHT was
           frozen at 420px. That mismatch between a fluid width and a frozen
           height is the root cause of card overlap/crowding on ultrawide
           screens (where the column gets much wider than 420px is tall,
           spreading % positions apart oddly) and on ~992-1150px laptops
           (where the column is narrower than 420px, so cards positioned via
           left:0 / right:-2% start colliding or spilling past the edge).
           FIX: clamp() ties the visual box's height to the viewport within
           sane bounds, so width and height scale together and the existing
           top/left percentage positions stay proportionally correct. */
        height: clamp(320px, 34vw, 420px);
    }

    .dht-float-card {
        position: absolute;
        background: rgba(20, 22, 58, 0.72);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 16px;
        padding: 14px 18px;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.35);
        animation: floatCard 5s ease-in-out infinite;
        border: 2px solid #F6BE01;
    }

    .dht-float-card.card-1 {
        top: 1%;
        left: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        animation-delay: 0s;
    }

    .dht-float-card.card-1 .dht-float-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #FFD700;
        color: #14163a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .dht-float-card.card-1 .dht-float-title {
        font-size: 0.86rem;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .dht-float-card.card-1 .dht-float-sub {
        font-size: 0.74rem;
        color: rgba(255, 255, 255, 0.6);
    }

    .dht-float-card.card-2 {
        top: 52%;
        left: 6%;

        /* OLD: width: 168px; (frozen)
           FIX: same logic as .dht-hero-visual above — let this card's width
           scale gently with the (now-fluid) visual box instead of staying a
           frozen 168px while its container grows/shrinks. Bounds chosen so
           168px is still the exact result at the original design width. */
        width: clamp(140px, 16vw, 168px);
        text-align: center;
        animation-delay: 1.2s;
    }

    .dht-float-card.card-2 .dht-float-label {
        font-size: 0.72rem;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 4px;
    }

    .dht-float-card.card-2 .dht-float-big {
        font-size: 1.9rem;
        font-weight: 800;
        color: #FFD700;
        line-height: 1;
    }

    .dht-float-card.card-2 .dht-float-caption {
        font-size: 0.74rem;
        color: rgba(255, 255, 255, 0.7);
        margin-top: 4px;
    }

    .dht-float-badge {
        position: absolute;
        top: 12%;
        right: 4%;

        /* OLD: width: 108px; height: 108px; (frozen circle)
           FIX: clamp() keeps it a perfect circle at every size (aspect-ratio
           locks width:height) while letting it track the fluid visual box,
           landing on the same 108px at the original design width. */
        width: clamp(88px, 10vw, 108px);
        aspect-ratio: 1 / 1;

        border-radius: 50%;
        background: rgba(20, 22, 58, 0.78);
        border: 3px solid #FFD700;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.35);
        animation: floatCard 5s ease-in-out infinite;
        animation-delay: 0.6s;
    }

    .dht-float-badge .badge-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #FFD700;
        line-height: 1;
    }

    .dht-float-badge .badge-label {
        font-size: 0.66rem;
        color: rgba(255, 255, 255, 0.75);
        margin-top: 4px;
        text-align: center;
    }

    .dht-float-card.card-3 {
        top: 88%;
        right: -2%;
        display: flex;
        align-items: center;
        gap: 12px;
        animation-delay: .8s;
    }

    .dht-float-card.card-3 .dht-float-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #FFD700;
        color: #14163a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .dht-float-card.card-3 .dht-float-title {
        font-size: .86rem;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .dht-float-card.card-3 .dht-float-sub {
        font-size: .74rem;
        color: rgba(255, 255, 255, .6);
    }

    @keyframes floatCard {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-14px);
        }

        100% {
            transform: translateY(0px);
        }
    }

    /* Single structural breakpoint kept from the original (this is a genuine
       *layout change* — one column instead of two — not just a sizing tweak,
       so clamp() alone can't replace it; a media query is still the right
       tool here). Everything inside it is otherwise the same fluid values
       used above, so there's no sizing "jump" left at the 991px line. */
    @media (max-width: 991px) {
        .dht-hero-inner {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .dht-hero-subtext {
            margin-left: auto;
            margin-right: auto;
        }

        .dht-hero-buttons {
            justify-content: center;
        }

        .dht-hero-visual {
            display: none;
        }

        .dht-hero {
            /* Fixed-top mobile navbar (navigation.php) overlaps the top of the
       page. Push the hero down so its rounded top + eyebrow badge
       aren't hidden underneath it. */
            margin: 70px 8px 8px;
            border-radius: 28px;
        }
    }

    /* ===== MOBILE RESPONSIVENESS FIXES (added — desktop untouched) ===== */
    @media (max-width: 991px) {
        .dht-hero-title br {
            display: none;
        }

        .dht-hero {
            min-height: auto;
        }
    }

    @media (max-width:767px) {

        .dht-hero-inner {
            padding: 20px 16px 12px;
        }

        .dht-hero {
            min-height: auto;
        }

    }

    .dht-hero-eyebrow {
        font-size: clamp(0.62rem, 2.4vw, 0.72rem);
        padding: 5px 12px;
        margin-bottom: clamp(6px, 1.5dvh, 14px);
    }

    .dht-hero-title {
        font-size: clamp(1.5rem, 6.5vw, 2.2rem);
        line-height: 1.2;
        margin-bottom: clamp(6px, 1.5dvh, 14px);
    }

    .dht-hero-subtext {
        font-size: clamp(0.82rem, 0.8rem + 0.2vw, 0.92rem);
        line-height: 1.45;
        margin-bottom: clamp(10px, 2dvh, 20px);
    }


    @media (max-width: 480px) {
        .dht-hero-buttons {
            flex-direction: column;
            gap: 10px;
        }

        .btn-hero-yellow,
        .btn-hero-outline {
            width: 100%;
            min-width: 0;
            height: clamp(44px, 11vw, 50px);
        }
    }
</style>

<!-- ====== VELOS-STYLE HERO SECTION ====== -->
<section class="dht-hero">

    <!-- Rotating background photos (5 images related to training/placement) -->
    <div class="dht-hero-bg-carousel" id="dhtHeroBgCarousel">
        <div class="dht-hero-bg-img active"
            style="background-image:url('/img/demo-hero3.webp');">
        </div>
        <div class="dht-hero-bg-img active"
            style="background-image:url('/img/demo-hero2.webp');">
        </div>
        <div class="dht-hero-bg-img active"
            style="background-image:url('/img/demo-hero1.webp');">
        </div>
        <div class="dht-hero-bg-img active"
            style="background-image:url('/img/demo-hero4.webp');">
        </div>
        <div class="dht-hero-bg-img active"
            style="background-image:url('/img/demo-hero5.webp');">
        </div>
        <div class="dht-hero-bg-img active"
            style="background-image:url('/img/demo-hero6.webp');">
        </div>
    </div>
    <div class="dht-hero-overlay"></div>

    <div class="dht-hero-inner">

        <!-- LEFT: CONTENT -->
        <div class="dht-hero-content">
            <span class="dht-hero-eyebrow"><i class="fa fa-graduation-cap"></i> Dharwad & Hubli's Career Launchpad</span>

            <h1 class="dht-hero-title">
                Become Job-Ready<br>
                in 45 Days with <span class="accent">Data Analytics,<br>Digital Marketing<br>& IT Courses</span>
            </h1>

            <p class="dht-hero-subtext">
                Offline + Online Training &middot; Internship + Placement Assistance &middot;
                Learn from Microsoft Certified Trainers across Dharwad & Hubli.
            </p>

            <div class="dht-hero-buttons">
                <button type="button" class="btn btn-hero-yellow" data-bs-toggle="modal" data-bs-target="#demomodal">
                    Book Demo
                </button>

            </div>
        </div>

        <!-- RIGHT: BACKGROUND VISUAL + FLOATING CARDS -->
        <div class="dht-hero-visual">
            <div class="dht-float-card card-1">
                <span class="dht-float-icon"><i class="fa fa-code"></i></span>
                <span>
                    <div class="dht-float-title">Full Stack Web Dev</div>
                    <div class="dht-float-sub">Live batch running now</div>
                </span>
            </div>
            <div class="dht-float-card card-3">
                <span class="dht-float-icon">
                    <i class="fa fa-bar-chart"></i>
                </span>
                <span>
                    <div class="dht-float-title">Data Analytics</div>
                    <div class="dht-float-sub">Live batch running now</div>
                </span>
            </div>

            <div class="dht-float-card card-2">
                <div class="dht-float-label">Placement Rate</div>
                <div class="dht-float-big">92%</div>
                <div class="dht-float-caption">Unlimited Interviews</div>
            </div>

            <div class="dht-float-badge">
                <div class="badge-value">4.9&#9733;</div>
                <div class="badge-label">Google Rating</div>
            </div>
        </div>

    </div>
</section>
<br>
<!-- ====== END HERO ====== -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const heroBgImgs = document.querySelectorAll('#dhtHeroBgCarousel .dht-hero-bg-img');
        if (!heroBgImgs.length) return;
        let heroBgIdx = 0;
        setInterval(() => {
            heroBgImgs[heroBgIdx].classList.remove('active');
            heroBgIdx = (heroBgIdx + 1) % heroBgImgs.length;
            heroBgImgs[heroBgIdx].classList.add('active');
        }, 4000);
    });
</script>

<!-- ====== NEW STATS SECTION (Mahjoz-style dark cards) ====== -->
<section class="stats-section">
    <div class="stats-grid">

        <!-- Google Ratings -->
        <div class="stat-card">
            <div class="stat-logo-mark">
                <img src="/views/uploads/dht-logo.webp" alt="DharwadHubballiTutor">
            </div>
            <div class="stars">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-half"></i>
            </div>
            <div class="stat-value" style="font-size:1.6rem;">4.9</div>
            <div class="stat-label">Google Ratings</div>
            <a href="https://www.google.com/search?q=dharwad+Hubballi+tutor#mpd=~14444790509297867122/customers/reviews"
                target="_blank"
                class="btn-review">
                Read Our Google Reviews
            </a>
        </div>

        <!-- Trained Students -->
        <div class="stat-card">
            <div class="stat-logo-mark">
                <img src="/views/uploads/dht-logo.webp" alt="DharwadHubballiTutor">
            </div>
            <div class="stat-value">5000+</div>
            <div class="stat-label">Trained Students</div>
        </div>

        <!-- Experienced Trainers -->
        <div class="stat-card">
            <div class="stat-logo-mark">
                <img src="/views/uploads/dht-logo.webp" alt="DharwadHubballiTutor">
            </div>
            <div class="stat-value" style="font-size:1.5rem;">Experienced Trainers</div>
            <div class="stat-sub">Minimum 3+ Years of Working Experience</div>
        </div>

        <!-- Guaranteed Placement -->
        <div class="stat-card">
            <div class="stat-logo-mark">
                <img src="/views/uploads/dht-logo.webp" alt="DharwadHubballiTutor">
            </div>
            <div class="stat-value" style="font-size:1.5rem;">Guaranteed Placement</div>
            <div class="stat-sub">Unlimited interview opportunities</div>
        </div>

    </div>
</section>
<!-- ====== END NEW STATS SECTION ====== -->

<!-- =========================================
     EXPLORE PROGRAMS/COURSES
========================================== -->
<style>
    .explore-programs {
        background: #fff;

        border: 4px solid #F6BE01;
        border-radius: 40px;

        margin: 25px auto;
        padding-top: 30px;
        /* ↓ Reduce top gap */
        padding-bottom: 30px;
        /* ↓ Reduce bottom gap */


        overflow: hidden;
    }

    .program-card {

        background: #fff;

        border: 3px solid #F6BE01;

        border-radius: 22px;

        padding: 25px;

        height: 100%;

        transition: .35s;

        cursor: pointer;

        box-shadow: 0 10px 30px rgba(0, 0, 0, .08);

        display: flex;

        flex-direction: column;

        justify-content: space-between;

        overflow: hidden;

        position: relative;

    }

    .program-card:hover {

        transform: translateY(-8px);

        box-shadow: 0 18px 40px rgba(0, 0, 0, .12);

    }

    .program-card::before {

        content: "";

        position: absolute;

        width: 180px;

        height: 180px;

        border-radius: 50%;

        right: -60px;

        top: -60px;

        background: rgba(246, 190, 1, .10);

    }

    .program-top {

        display: flex;

        justify-content: space-between;

        align-items: flex-start;

    }

    .program-icon {

        width: 72px;

        height: 72px;

        border-radius: 18px;

        background: #14163A;

        color: #FFD700;

        display: flex;

        justify-content: center;

        align-items: center;

        font-size: 28px;

    }

    .program-body {

        margin-top: 30px;

    }

    .program-body h4 {

        font-size: 24px;

        font-weight: 700;

        color: #14163A;

        margin-bottom: 12px;

    }

    .program-body p {

        color: #666;

        line-height: 1.7;

        min-height: 55px;

    }

    .program-footer {

        margin-top: 25px;

        padding-top: 18px;

        border-top: 1px solid #eee;

        display: flex;

        justify-content: space-between;

        align-items: center;

        font-weight: 600;

        color: #4042e2;

    }

    .program-card:hover .program-footer {

        color: #fd7e14;

    }

    .program-footer i {

        transition: .35s;

    }

    .program-card:hover .program-footer i {

        transform: translateX(8px);

    }

    @media(max-width:768px) {

        .program-card {

            padding: 22px;

        }

        .program-icon {

            width: 60px;

            height: 60px;

            font-size: 24px;

        }

        .program-body h4 {

            font-size: 20px;

        }

    }

    @media (max-width:767px) {

        .explore-programs {
            width: calc(100% - 24px);
            margin: 12px auto;

            padding: 35px 12px 40px;

            border-width: 3px;
            border-radius: 18px;

            overflow: hidden;
        }

    }


    .program-card:hover .program-icon {
        transform: rotate(-8deg) scale(1.08);
        transition: .35s;
    }

    .program-header {

        cursor: pointer;

    }

    .program-content {

        max-height: 0;

        overflow: hidden;

        transition: .45s ease;

        margin-top: 0;

    }

    .program-card.active .program-content {

        max-height: 600px;

        margin-top: 20px;

    }

    .course-link {

        display: flex;

        justify-content: space-between;

        align-items: center;

        padding: 14px 0;

        text-decoration: none;

        color: #14163A;

        font-weight: 600;

        border-top: 1px solid #ececec;

        transition: .3s;

    }

    .course-link:hover {

        padding-left: 10px;

        color: #4042e2;

    }

    .course-link i {

        transition: .3s;

    }

    .course-link:hover i {

        transform: translateX(6px);

    }

    .program-btn {

        width: 100%;

        background: #14163A;

        border: none;

        color: #fff;

        height: 48px;

        border-radius: 10px;

        font-weight: 600;

        transition: .3s;

    }

    .program-btn:hover {

        background: #FFD700;

    }

    .course-item {

        display: flex;

        justify-content: space-between;

        align-items: center;

        padding: 16px;

        border-bottom: 1px solid #ececec;

        text-decoration: none;

        color: #14163A;

        font-weight: 600;

        transition: .3s;

    }

    .course-item:hover {

        padding-left: 28px;

        background: #f8f9fa;

        color: #4042e2;

    }

    .course-item::after {

        content: "➜";

        font-size: 18px;

    }

    /* ===============================
   COURSE MODAL
    ==================================*/

    .course-modal {
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }

    .course-header {
        background: #14163A;
        color: #fff;
        padding: 25px 30px;
        border: none;
    }

    .course-header h2 {
        margin: 0;
        font-size: 30px;
        font-weight: 700;
        color: #000000;
    }

    .course-header p {
        margin: 8px 0 0;
        color: #d7d9f7;
    }

    .course-count {
        margin-top: 15px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #FFD700;
        color: #14163A;
        padding: 8px 18px;
        border-radius: 30px;
        font-weight: 700;
    }

    .course-search {
        padding: 20px 22px;
        background: #f8f9fb;
        border-top: 1px solid #ececec;
        border-bottom: 1px solid #ececec;
    }

    .search-box {

        position: relative;

        width: 520px;
        /* change this if you want wider */

        max-width: 100%;

    }

    .search-box input {

        width: 100%;

        height: 50px;

        border: 1px solid #d9dcef;

        border-radius: 12px;

        background: #fff;

        padding: 0 50px 0 18px;

        font-size: 15px;

        color: #14163A;

        transition: .3s;

    }

    .search-box input:focus {

        outline: none;

        border-color: #4042e2;

        box-shadow: 0 0 0 3px rgba(64, 66, 226, .12);

    }

    .search-box i {

        position: absolute;

        right: 18px;

        top: 50%;

        transform: translateY(-50%);

        color: #8a90aa;

        font-size: 16px;

        pointer-events: none;

    }

    #courseBody {

        display: grid;

        gap: 18px;

    }

    .course-card {

        display: flex;

        justify-content: space-between;

        align-items: center;

        background: #fff;

        border: 2px solid #ECECEC;

        border-radius: 16px;

        padding: 18px;

        text-decoration: none;

        transition: .35s;

    }

    .course-card:hover {

        transform: translateY(-3px);

        border-color: #4042e2;

        box-shadow: 0 12px 30px rgba(0, 0, 0, .08);

    }

    .course-left {

        display: flex;

        align-items: center;

        gap: 18px;

    }

    .course-icon {

        width: 60px;

        height: 60px;

        border-radius: 15px;

        background: #14163A;

        color: #FFD700;

        display: flex;

        justify-content: center;

        align-items: center;

        font-size: 24px;

    }

    .course-name {

        font-size: 18px;

        font-weight: 700;

        color: #14163A;

    }

    .course-card i:last-child {

        color: #4042e2;

        transition: .3s;

    }

    .course-card:hover i:last-child {

        transform: translateX(8px);

    }

    /* Close Button */
    .course-close-btn {
        position: absolute;
        top: 18px;
        right: 18px;

        width: 42px;
        height: 42px;

        border: none;
        border-radius: 50%;

        background: #2D316B;
        color: #fff;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 20px;
        cursor: pointer;

        box-shadow: 0 8px 20px rgba(0, 0, 0, .25);

        transition: .3s;

        z-index: 1055;
    }

    .course-close-btn i {
        margin: 0;
        line-height: 1;
    }

    .course-close-btn:hover {
        background: #1B1F4D;
        transform: scale(1.08);
    }

    @media (max-width:991px) {

        .course-close-btn {
            position: fixed;
            top: 15px;
            right: 15px;

            width: 44px;
            height: 44px;

            z-index: 99999;
        }

    }

    @media (max-width:991px) {

        #courseBody {
            overflow-x: hidden;
            overflow-y: auto;
            touch-action: pan-y;
        }

        #courseBody .course-card {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        .course-left {
            min-width: 0;
            flex: 1;
        }

        .course-name {
            white-space: normal;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

    }
</style>

<section class="explore-programs py-3">

    <div class="container">

        <div class="section-title">
            <span class="section-tag">Explore Everything</span>

            <h2>
                Discover Our <span>Programs</span>
            </h2>

            <p>
                Choose from industry-ready courses, internships, workshops and IT services to build your successful career.
            </p>
        </div>

        <div class="row g-4">

            <?php

            $categoryList = DBcategory::getAllCategory();
            sort($categoryList);

            $icons = [
                'training'   => 'fa-graduation-cap',
                'internship' => 'fa-briefcase',
                'services'   => 'fa-cogs',
                'jobs'       => 'fa-suitcase',
                'workshops'  => 'fa-rocket',
                'blogs' => 'fa-newspaper-o'
            ];

            $descriptions = [
                'training'   => 'Industry Ready Professional Courses',
                'internship' => 'Real Company Live Projects',
                'services'   => 'Professional IT Services',
                'jobs'       => 'Latest Career Opportunities',
                'workshops'  => 'Hands-on Practical Sessions',
                'blogs'      => 'Latest Articles & Updates'
            ];

            foreach ($categoryList as $category):

                $categoryName = ucfirst(strtolower($category->getCategoryName()));
                $key = strtolower($category->getCategoryName());

                $categoryIcon = $icons[$key] ?? 'fa-folder-open';
                $description  = $descriptions[$key] ?? 'Explore our programs';
            ?>

                <div class="col-lg-4 col-md-6">

                    <div class="program-card">

                        <div class="program-top">

                            <div class="program-icon">
                                <i class="fa <?= $categoryIcon ?>"></i>
                            </div>

                        </div>

                        <div class="program-body">

                            <h4><?= $categoryName ?></h4>

                            <p><?= $description ?></p>

                        </div>

                        <div class="program-footer">

                            <button
                                type="button"
                                class="program-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#courseModal">

                                Explore Courses

                            </button>

                        </div>

                        <!-- Hidden Course Data -->

                        <div
                            class="course-data d-none"
                            data-description="<?= htmlspecialchars($description); ?>">

                            <?php

                            foreach ($category->getMappedSubCategory($category->getCategoryId()) as $subcategory):

                                $postList = DBpost::getPostBySubCategoryFornt($subcategory->getSubCategoryId());

                                foreach ($postList as $navpost):

                                    $title = $navpost->getPostTitle();
                                    $icon = "fa-book";

                                    $lower = strtolower($title);

                                    if (str_contains($lower, "python")) $icon = "fa-code";
                                    elseif (str_contains($lower, "java")) $icon = "fa-coffee";
                                    elseif (str_contains($lower, "php")) $icon = "fa-code";
                                    elseif (str_contains($lower, "mern")) $icon = "fa-globe";
                                    elseif (str_contains($lower, "react")) $icon = "fa-code";
                                    elseif (str_contains($lower, "ai")) $icon = "fa-cogs";
                                    elseif (str_contains($lower, "machine")) $icon = "fa-cogs";
                                    elseif (str_contains($lower, "data")) $icon = "fa-bar-chart";
                                    elseif (str_contains($lower, "sql")) $icon = "fa-database";
                                    elseif (str_contains($lower, "cloud")) $icon = "fa-cloud";
                                    elseif (str_contains($lower, "aws")) $icon = "fa-cloud";
                                    elseif (str_contains($lower, "azure")) $icon = "fa-cloud";
                                    elseif (str_contains($lower, "security")) $icon = "fa-shield";
                                    elseif (str_contains($lower, "marketing")) $icon = "fa-bullhorn";

                            ?>

                                    <a
                                        href="<?= $navpost->getPostUrl(); ?>"
                                        class="course-card">

                                        <div class="course-left">

                                            <div class="course-icon">

                                                <i class="fa <?= $icon ?>"></i>

                                            </div>

                                            <div>

                                                <div class="course-name">

                                                    <?= $title ?>

                                                </div>

                                            </div>

                                        </div>

                                        <span class="course-arrow">

                                            <i class="fa fa-arrow-right"></i>

                                        </span>

                                    </a>

                            <?php
                                endforeach;
                            endforeach;
                            ?>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</section>

<!-- PREMIUM MODAL -->

<div
    class="modal fade"
    id="courseModal"
    tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-scrollable">

        <div class="modal-content course-modal">

            <div class="modal-header course-header">

                <div>

                    <h2 id="courseTitle"></h2>

                    <p id="courseDescription"></p>

                    <div class="course-count">

                        <i class="fa fa-book"></i>

                        <span id="courseCount"></span>

                    </div>

                </div>

                <button type="button"
                    class="course-close-btn"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>

            </div>

            <div class="course-search">

                <div class="search-box">

                    <i class="fa fa-search"></i>

                    <input
                        type="text"
                        id="courseSearch"
                        placeholder="Search Courses..."
                        autocomplete="off">

                </div>

            </div>

            <div class="modal-body">

                <div id="courseBody"></div>

            </div>

        </div>

    </div>

</div>
<script>
    document.querySelectorAll(".program-btn").forEach(function(button) {

        button.addEventListener("click", function() {

            const card = this.closest(".program-card");

            const hidden = card.querySelector(".course-data");

            document.getElementById("courseTitle").innerHTML =
                card.querySelector("h4").innerHTML;

            document.getElementById("courseDescription").innerHTML =
                hidden.dataset.description;

            document.getElementById("courseBody").innerHTML =
                hidden.innerHTML;

            const total =
                document.querySelectorAll("#courseBody .course-card").length;

            document.getElementById("courseCount").innerHTML =
                total + " Courses Available";

        });

    });

    document.getElementById("courseSearch").addEventListener("keyup", function() {

        let value = this.value.toLowerCase();

        let visible = 0;

        document.querySelectorAll("#courseBody .course-card").forEach(function(card) {

            let text = card.innerText.toLowerCase();

            if (text.indexOf(value) > -1) {

                card.style.display = "flex";
                visible++;

            } else {

                card.style.display = "none";

            }

        });

        document.getElementById("courseCount").innerHTML =
            visible + " Courses Available";

    });
</script>

<style>
    /* ===== Accreditations Cover-Flow Carousel (scoped with cred- prefix) ===== */
    .credential-section.cred-cf-section {
        background: #fdfdfd;
        padding: 80px 0 90px;
        overflow: hidden;
        border: 3px solid #F6BE01;
        border-radius: 40px;
        margin: 25px auto;

    }

    .cred-carousel-wrapper {
        position: relative;
        width: 100%;
        max-width: 1200px;
        height: 420px;
        margin: 0 auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .cred-carousel {
        position: relative;
        width: 100%;
        height: 100%;
        perspective: 1500px;
        transform-style: preserve-3d;
    }

    .cred-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: transform 0.7s cubic-bezier(0.77, 0, 0.175, 1), opacity 0.7s ease;
        opacity: 0.8;
    }

    .cred-slide-content {
        position: relative;
        width: 270px;
        height: 300px;
        background: #ffffff;
        border-top: 6px solid #4042e2;
        border-radius: 14px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 30px 26px;
    }

    .cred-icon-box {
        width: 74px;
        height: 74px;
        background: #f8f9ff;
        border-radius: 50%;
        margin-bottom: 20px;
        font-size: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #F6BE01;
    }

    .cred-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 12px;
    }

    .cred-text {
        font-size: 0.9rem;
        color: #666;
        line-height: 1.5;
    }

    .cred-slide.active {
        opacity: 1;
        z-index: 2;
        transform: translateZ(0) rotateY(0deg) scale(1);
    }

    .cred-slide.prev {
        z-index: 1;
        transform: translateX(-32%) scale(0.82) rotateY(45deg);
    }

    .cred-slide.next {
        z-index: 1;
        transform: translateX(32%) scale(0.82) rotateY(-45deg);
    }

    .cred-slide.hidden {
        opacity: 0;
        pointer-events: none;
        transform: translateX(0) scale(0.5);
    }

    .cred-carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);

        width: 52px;
        height: 52px;

        display: flex;
        justify-content: center;
        align-items: center;

        background: #fff;
        border: 2px solid #F6BE01;
        border-radius: 50%;

        color: #4042e2;
        cursor: pointer;

        padding: 0;
        line-height: 1;

        box-shadow: 0 8px 20px rgba(0, 0, 0, .1);
        transition: .3s;
    }

    .cred-carousel-btn i {
        font-size: 18px;
        line-height: 1;
    }

    .cred-carousel-btn:hover {
        background: #4042e2;
        color: #fff;
        transform: translateY(-50%) scale(1.1);
    }

    .cred-carousel-btn.cred-prev {
        left: 0;
    }

    .cred-carousel-btn.cred-next {
        right: 0;
    }

    @media (max-width: 767px) {

        .credential-section.cred-cf-section {
            width: calc(100% - 24px);
            margin: 12px auto;
            padding: 35px 12px;
            border-radius: 18px;
            overflow: hidden;
        }

        /* Heading */
        .cred-heading-title {
            font-size: 1.9rem;
            line-height: 1.2;
            margin-bottom: 12px;
        }

        .cred-heading-sub {
            font-size: 0.95rem;
            line-height: 1.6;
            max-width: 100%;
            padding: 0 10px;
            margin: 0 auto 30px;
        }

        /* Carousel */
        .cred-carousel-wrapper {
            width: 100%;
            height: 260px;
            overflow: hidden;
        }

        .cred-carousel {
            width: 100%;
            overflow: hidden;
        }

        .cred-slide-content {
            width: 180px;
            height: 210px;
            padding: 18px 14px;
            border-radius: 18px;
        }

        .cred-icon-box {
            width: 55px;
            height: 55px;
            font-size: 22px;
            margin-bottom: 12px;
        }

        .cred-title {
            font-size: 0.95rem;
            margin-bottom: 8px;
        }

        .cred-text {
            font-size: 0.8rem;
            line-height: 1.45;
        }

        /* Side Cards */
        .cred-slide.prev {
            transform: translateX(-22%) scale(0.68);
            opacity: .35;
        }

        .cred-slide.next {
            transform: translateX(22%) scale(0.68);
            opacity: .35;
        }

        /* Navigation Buttons */
        .cred-carousel-btn {
            width: 36px;
            height: 36px;
        }

        .cred-carousel-btn.cred-prev {
            left: 6px;
        }

        .cred-carousel-btn.cred-next {
            right: 6px;
        }
    }
</style>

<section class="credential-section cred-cf-section container-fluid">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="section-title">
                <span class="section-tag">Trusted Excellence</span>

                <h2>
                    Our Official <span>Accreditations</span>
                </h2>

                <p>
                    Recognized by Microsoft, ISO, NASSCOM and MSME for maintaining the highest standards in technical education.
                </p>
            </div>
        </div>

        <div class="cred-carousel-wrapper">
            <div class="cred-carousel" id="credCarousel"></div>
            <button class="cred-carousel-btn cred-prev">
                <i class="fa fa-chevron-left"></i>
            </button>

            <button class="cred-carousel-btn cred-next">
                <i class="fa fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const credCarousel = document.getElementById('credCarousel');
        const credWrapper = document.querySelector('.cred-carousel-wrapper');
        const credPrevBtn = document.querySelector('.cred-carousel-btn.cred-prev');
        const credNextBtn = document.querySelector('.cred-carousel-btn.cred-next');

        const credSlidesData = [{
                icon: 'fa fa-windows',
                color: '#00a4ef',
                border: '#00a4ef',
                title: 'Microsoft Certified Trainers',
                text: 'Authorized instruction delivered by globally recognized MCT professionals.'
            },
            {
                icon: 'fa fa-users',
                color: '#2a0a5e',
                border: '#2a0a5e',
                title: 'NASSCOM Member',
                text: 'Official member of India\u2019s premier council for the IT-BPM industry.'
            },
            {
                icon: 'fa fa-shield',
                color: '#28a745',
                border: '#28a745',
                title: 'ISO 9001:2015',
                text: 'Certified for Quality Management Systems in providing professional training.'
            },
            {
                icon: 'fa fa-university',
                color: '#fd7e14',
                border: '#fd7e14',
                title: 'MSME Registered',
                text: 'A Government of India recognized enterprise (Udyam Registration).'
            }
        ];

        let credCurrentIndex = 0;
        let credAutoSlideInterval;

        function createCredSlides() {
            credSlidesData.forEach((data, index) => {
                const slide = document.createElement('div');
                slide.className = 'cred-slide';
                slide.dataset.index = index;

                const slideContent = document.createElement('div');
                slideContent.className = 'cred-slide-content';
                slideContent.style.borderTopColor = data.border;

                const iconBox = document.createElement('div');
                iconBox.className = 'cred-icon-box';
                iconBox.style.color = data.color;
                const icon = document.createElement('i');
                icon.className = data.icon;
                iconBox.appendChild(icon);

                const title = document.createElement('div');
                title.className = 'cred-title';
                title.textContent = data.title;

                const text = document.createElement('p');
                text.className = 'cred-text';
                text.textContent = data.text;

                slideContent.appendChild(iconBox);
                slideContent.appendChild(title);
                slideContent.appendChild(text);
                slide.appendChild(slideContent);
                credCarousel.appendChild(slide);
            });
        }

        function updateCredSlides() {
            const slides = document.querySelectorAll('.cred-slide');
            const slideCount = slides.length;

            slides.forEach((slide, index) => {
                slide.classList.remove('active', 'prev', 'next', 'hidden');

                let newIndex = index - credCurrentIndex;
                if (newIndex < 0) newIndex += slideCount;
                newIndex %= slideCount;

                if (newIndex === 0) {
                    slide.classList.add('active');
                } else if (newIndex === 1) {
                    slide.classList.add('next');
                } else if (newIndex === slideCount - 1) {
                    slide.classList.add('prev');
                } else {
                    slide.classList.add('hidden');
                }
            });
        }

        function credMoveNext() {
            credCurrentIndex = (credCurrentIndex + 1) % credSlidesData.length;
            updateCredSlides();
        }

        function credMovePrev() {
            credCurrentIndex = (credCurrentIndex - 1 + credSlidesData.length) % credSlidesData.length;
            updateCredSlides();
        }

        function startCredAutoSlide() {
            stopCredAutoSlide();
            credAutoSlideInterval = setInterval(credMoveNext, 4000);
        }

        function stopCredAutoSlide() {
            clearInterval(credAutoSlideInterval);
        }

        credNextBtn.addEventListener('click', credMoveNext);
        credPrevBtn.addEventListener('click', credMovePrev);
        credWrapper.addEventListener('mouseenter', stopCredAutoSlide);
        credWrapper.addEventListener('mouseleave', startCredAutoSlide);

        createCredSlides();
        updateCredSlides();
        startCredAutoSlide();
    });
</script>
<style>
    /* ===== Popular Blog Cards (matches new course-card style) ===== */
    .popular-blogs-section {
        background: #ffffff;
        padding: 70px 0 80px;
        border: 4px solid #F6BE01;
        border-radius: 40px;
    }

    .pb-eyebrow {
        display: inline-block;
        background: #eef1ff;
        color: #4042e2;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        padding: 7px 18px;
        border-radius: 30px;
        margin-bottom: 14px;
    }

    .pb-heading {
        font-size: clamp(1.8rem, 3.2vw, 2.4rem);
        font-weight: 700;
        color: #16182b;
        margin-bottom: 40px;
    }

    .pb-heading .pb-accent {
        color: #4042e2;
    }

    .pb-card {
        background: #ffffff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 340px;
        display: flex;
        flex-direction: column;
        margin: 10px;
        border: 2px solid #F6BE01;
    }

    .pb-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.12);
    }

    .pb-card-img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        display: block;
    }

    .pb-card-body {
        padding: 18px 20px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .pb-card-title {
        font-size: 1.02rem;
        font-weight: 600;
        color: #16182b;
        line-height: 1.35;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .pb-card-text {
        font-size: 0.88rem;
        color: #666;
        line-height: 1.5;
        margin-bottom: 14px;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .pb-readmore {
        display: inline-block;
        align-self: flex-start;
        background: #fd7e14;
        color: #ffffff;
        padding: 8px 20px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.82rem;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .pb-readmore:hover {
        background: #e0690a;
        color: #ffffff;
        transform: translateY(-2px);
    }

    #owl-one .owl-dots {
        display: none !important;
    }

    /* ===========================
   Popular Blogs - Mobile Only
   =========================== */
    .popular-blogs-section {
        width: calc(100% - 24px);

        margin: 0 auto;

        padding: 30px 12px 15px;

        border-width: 3px;
        border-radius: 18px;
        overflow: hidden;
    }

    .pb-eyebrow {
        font-size: 11px;
        padding: 7px 16px;
        margin-bottom: 12px;
    }

    .pb-heading {
        font-size: 1.9rem;
        line-height: 1.2;
        margin-bottom: 28px;
        padding: 0 10px;
    }

    #owl-one {
        padding: 0 5px;
    }

    .pb-card {
        height: 300px;
        margin: 6px;
        border-radius: 14px;
    }

    .pb-card-img {
        height: 130px;
    }

    .pb-card-body {
        padding: 15px;
    }

    .pb-card-title {
        font-size: .95rem;
        margin-bottom: 8px;
    }

    .pb-card-text {
        font-size: .82rem;
        line-height: 1.45;
        -webkit-line-clamp: 2;
    }

    .pb-readmore {
        padding: 7px 18px;
        font-size: .78rem;
    }

    #owl-one .owl-nav button {
        width: 34px;
        height: 34px;
    }

    #owl-one .owl-dots {
        display: none !important;
    }
</style>
<section class="popular-blogs-section container-fluid">
    <div class="container">
        <div class="section-title">
            <span class="section-tag">Most Popular</span>

            <h2>
                Our Most Popular <span>Courses</span>
            </h2>

            <p>
                Explore our best-selling professional courses with live projects, internships and placement assistance.
            </p>
        </div>

        <div id="owl-one" class="owl-carousel owl-theme">
            <?php
            $postOnHomeList = DBpost::getPostOnHome();
            foreach ($postOnHomeList as $postOnHome) {
                $string = strip_tags($postOnHome->getPostDescription());
                if (strlen($string) > 500) {
                    $stringCut = substr($string, 0, 80);
                    $endPoint = strrpos($stringCut, ' ');
                    $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                    $string .= '...';
                }

                echo '<div class="item">
                    <div class="pb-card">
                        <img class="pb-card-img" loading="lazy" decoding="async" src="/blogadmin/img/Post/' . $postOnHome->getImage() . '" alt="' . htmlspecialchars($postOnHome->getPostTitle()) . '"/>
                        <div class="pb-card-body">
                            <h5 class="pb-card-title">' . $postOnHome->getPostTitle() . '</h5>
                            <p class="pb-card-text">' . $string . '</p>
                            <a class="pb-readmore" href="' . $postOnHome->getPostUrl() . '">Read More</a>
                        </div>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>
</section>
<br>
<style>
    /* ===== Testimonials — horizontal layout, compact height ===== */
    .testimonial-section {
        background: transparent;
        border: 4px solid #F6BE01;
        border-radius: 40px;
        padding: 70px 0 60px;

    }

    .ts-eyebrow {
        display: inline-block;
        background: #fff1e6;
        color: #fd7e14;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        padding: 7px 18px;
        border-radius: 30px;
        margin-bottom: 14px;
    }

    .ts-heading {
        font-size: clamp(1.8rem, 3.2vw, 2.4rem);
        font-weight: 700;
        color: #16182b;
        margin-bottom: 40px;
    }

    .ts-heading .ts-accent {
        color: #4042e2;
    }

    /* Force Owl to size itself off the active slide only, not the tallest */
    #owl-two.owl-carousel,
    #owl-two .owl-stage-outer,
    #owl-two .owl-stage,
    #owl-two .owl-item,
    #owl-two .item {
        height: auto !important;
    }

    .ts-card {
        background: #ffffff;
        border: 1px solid #eef0f4;
        border-radius: 18px;
        padding: 40px 36px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 28px;
        min-height: 220px;
        height: auto;
        max-width: 900px;
        margin: 20px auto;
        border: 2px solid #F6BE01;
    }

    .ts-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.1);
    }

    .ts-avatar-col {
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .ts-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background-size: cover;
        background-position: center;
        border: 3px solid #f1f3ff;
    }

    .ts-name {
        font-size: 0.92rem;
        font-weight: 700;
        color: #16182b;
        margin: 0;
        text-align: center;
        white-space: nowrap;
    }

    .ts-content-col {
        flex: 1;
        min-width: 0;
        border-left: 1px solid #eef0f4;
        padding-left: 20px;
    }

    /* Google-style rating pill: stars + numeric badge together */
    .ts-rating-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f7f8fa;
        border-radius: 30px;
        padding: 4px 12px;
        margin-bottom: 10px;
    }

    .ts-rating-pill .ts-stars i {
        color: #FFD700;
        font-size: 0.85rem;
        margin-right: 1px;
    }

    .ts-rating-pill .ts-rating-num {
        font-size: 0.8rem;
        font-weight: 700;
        color: #16182b;
    }

    .ts-quote {
        font-size: 1rem;
        color: #555;
        line-height: 1.65;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .ts-quote i {
        color: #c9cde0;
        margin: 0 4px;
    }

    @media (max-width:767px) {

        /* Testimonials Section */
        .testimonial-section {
            width: calc(100% - 24px);
            margin-top: 5px auto;
            padding: 30px 12px;
            border-radius: 18px;
        }

        /* Heading */
        .ts-heading {
            margin-bottom: 20px;
        }

        .ts-heading h2 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .ts-heading p {
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        /* Card */
        .ts-card {
            padding: 16px;
            min-height: auto;
        }

        .ts-avatar {
            width: 70px;
            height: 70px;
        }

        .ts-review {
            font-size: 0.85rem;
            line-height: 1.5;
            margin: 10px 0;
        }

        /* Slider */
        .ts-slider {
            margin-top: 15px;
        }

        .owl-dots {
            margin-top: 12px !important;
        }

    }
</style>
<section class="testimonial-section container-fluid">
    <div class="container">
        <div class="section-title">
            <span class="section-tag">Student Reviews</span>

            <h2>
                What Our Students <span>Say</span>
            </h2>

            <p>
                Thousands of students have started successful careers after learning with DharwadHubballiTutor.
            </p>
        </div>

        <div id="owl-two" class="carousel-testimony owl-carousel ftco-owl">
            <?php $testimonialsList = DBtestimonials::getTestimonialsList();
            foreach ($testimonialsList as $testimonials) {

                $string = strip_tags($testimonials->getDescription());
                if (strlen($string) > 220) {
                    $stringCut = substr($string, 0, 220);
                    $endPoint = strrpos($stringCut, ' ');
                    $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                    $string .= '...';
                }

                $rating = $testimonials->getRateNow();

                $starsHtml = '';
                for ($i = 1; $i <= 5; $i++) {
                    $starsHtml .= $rating >= $i
                        ? '<i class="fa fa-star" aria-hidden="true"></i>'
                        : '<i class="fa fa-star" aria-hidden="true" style="color:#ddd"></i>';
                }

                $card = '<div class="item">
        <div class="ts-card">
            <div class="ts-avatar-col">
                <div class="ts-avatar" style="background-image: url(/blogadmin/img/Post/' . $testimonials->getImage() . ')"></div>
                <h5 class="ts-name">' . $testimonials->getName() . '</h5>
            </div>
            <div class="ts-content-col">
                <div class="ts-rating-pill">
                    <span class="ts-stars">' . $starsHtml . '</span>
                    <span class="ts-rating-num">' . number_format($rating, 1) . '</span>
                </div>
                <p class="ts-quote"><i class="fa fa-quote-left"></i>' . $string . '<i class="fa fa-quote-right"></i></p>
            </div>
        </div>
    </div>';
                echo $card;
            }
            ?>
        </div>
    </div>
</section>
<style>
    /* ===== Why Choose Us (image-card style) ===== */
    .why-section {
        width: calc(100% - 32px);
        max-width: 1320px;
        margin: 20px auto;

        background: #fff;
        padding: 70px 20px 60px;

        border: 4px solid #F6BE01;
        border-radius: 40px;
        box-sizing: border-box;
    }

    .why-header {
        margin-bottom: 34px;
        text-align: center;
    }

    .why-title {
        font-size: clamp(1.7rem, 3vw, 2.2rem);
        font-weight: 700;
        color: #16182b;
        margin: 0 0 8px;
    }

    .why-title .business-name {
        color: #4042e2;
    }

    .why-subtext {
        color: #8A8F98;
        font-size: 0.95rem;
        max-width: 560px;
        margin: 0 auto;
        line-height: 1.5;
    }

    .why-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 18px;
    }

    .why-card {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .why-card-visual {
        width: 100%;
        aspect-ratio: 1 / 1;
        border-radius: 18px;
        background-size: cover;
        background-position: center;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12);
        transition: transform 0.3s ease;
        position: relative;
        overflow: hidden;
        border: 2px solid #F6BE01;
    }

    .why-card-visual::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0.35) 100%);
    }

    .why-card:hover .why-card-visual {
        transform: translateY(-6px);
    }

    .why-card-label {
        margin-top: 12px;
        font-size: 0.88rem;
        font-weight: 700;
        color: #16182b;
        text-align: center;
        line-height: 1.3;
    }

    @media (max-width: 991px) {
        .why-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 575px) {
        .why-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
<section id="category" class="why-section container">
    <div class="section-title">
        <span class="section-tag">Why Choose Us</span>

        <h2>
            Why <span>DharwadHubballiTutor</span>?
        </h2>

        <p>
            Learn from industry experts with practical training, internships and guaranteed placement support.
        </p>
    </div>

    <div class="why-grid">
        <div class="why-card">
            <div class="why-card-visual" style="background-image:url('https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=400&q=80');"></div>
            <div class="why-card-label">Experienced Trainers</div>
        </div>
        <div class="why-card">
            <div class="why-card-visual" style="background-image:url('https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=400&q=80');"></div>
            <div class="why-card-label">Special Attention for Weak Student</div>
        </div>
        <div class="why-card">
            <div class="why-card-visual" style="background-image:url('https://images.unsplash.com/photo-1521791136064-7986c2920216?w=400&q=80');"></div>
            <div class="why-card-label">100% Placement Gauranteed</div>
        </div>
        <div class="why-card">
            <div class="why-card-visual" style="background-image:url('https://images.unsplash.com/photo-1506784983877-45594efa4cbe?w=400&q=80');"></div>
            <div class="why-card-label">Flexible Class Timings</div>
        </div>
        <div class="why-card">
            <div class="why-card-visual" style="background-image:url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=400&q=80');"></div>
            <div class="why-card-label">Customized Course Content</div>
        </div>
        <div class="why-card">
            <div class="why-card-visual" style="background-image:url('https://images.unsplash.com/photo-1553729784-e91953dec042?w=400&q=80');"></div>
            <div class="why-card-label">Affordable Course Fees Structure</div>
        </div>
    </div>
</section>
<section class="hero">
    <div class="section-title">
        <span class="section-tag">Hands-on Learning</span>

        <h2>
            Our Live <span>Projects</span>
        </h2>

        <p>
            Gain real-world experience by working on live industry projects guided by experienced mentors.
        </p>
    </div>
</section>
<style>
    .hero {
        background: #ffffff;
        padding: 70px 20px 60px;
        border: 4px solid #F6BE01;
        border-radius: 40px;
        text-align: center;
        max-width: 1320px;
        margin: 20px auto;
        box-sizing: border-box;
        position: relative;
        overflow: hidden;
    }

    /* Optional subtle decorative effect */
    .hero::before {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        background: rgba(246, 190, 1, 0.08);
        border-radius: 50%;
        top: -80px;
        left: -80px;
    }

    .hero::after {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        background: rgba(246, 190, 1, 0.06);
        border-radius: 50%;
        bottom: -70px;
        right: -70px;
    }

    .hero h1 {
        margin: 0;
        font-size: 3rem;
        font-weight: 700;
        color: #341963;
        line-height: 1.2;
        position: relative;
        z-index: 2;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero {
            padding: 50px 20px;
            border-radius: 25px;
            margin: 20px 15px;
        }

        .hero h1 {
            font-size: 2rem;
        }
    }
</style>
<style>
    /* ===== Project Cards (image + modal Read More) ===== */
    .project-card {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        flex-direction: column;
        border: 2px solid #F6BE01;
    }

    .project-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.12);
    }

    .project-card-img {
        width: 100%;
        height: 170px;
        object-fit: cover;
        display: block;
    }

    .project-card-body {
        padding: 24px 26px 28px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .project-readmore {
        display: inline-block;
        align-self: flex-start;
        margin-top: 16px;
        background: #4042e2;
        color: #ffffff;
        padding: 8px 20px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.85rem;
        border: none;
        text-decoration: none;
        transition: all 0.25s ease;
        border: 2px solid #F6BE01;
    }

    .project-readmore:hover {
        background: #2f31b8;
        color: #ffffff;
    }

    .project-modal-title {
        color: #0b3c5d;
        font-weight: 600;
    }

    .hero {
        text-align: center;
    }

    .hero p {
        max-width: 900px;
        margin: 0 auto;
    }

    /* Make modal completely white */
    .modal-content {
        background: #fff;
        border: none;
        border-radius: 18px;
        overflow: hidden;
    }

    .modal-header {
        background: #fff !important;
        border-bottom: 1px solid #eee;
        padding: 20px 25px;
    }

    .modal-body {
        background: #fff !important;
        padding: 25px;
    }

    .modal-footer {
        background: #fff !important;
        border-top: 1px solid #eee;
    }

    .project-modal-title {
        color: #16182b;
        font-weight: 600;
    }

    .modal-header .close,
    .modal-header .btn-close {
        background: transparent;
        opacity: 1;
    }
</style>
<!-- PROJECTS -->
<div class="container">
    <div class="projects-grid">

        <!-- NWKRTC -->
        <div class="project-card">
            <img class="project-card-img" loading="lazy" decoding="async" src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&q=80" alt="NWKRTC BI Dashboard">
            <div class="project-card-body">
                <div class="project-tag">Public Transport Analytics</div>
                <h3>NWKRTC – BI Dashboard</h3>
                <p>
                    A powerful Business Intelligence system to track depot-wise
                    revenue, route performance, fleet availability, and operational
                    efficiency for North Western Karnataka Road Transport Corporation.
                </p>
                <div class="tech">
                    <span>Power BI</span>
                    <span>SQL</span>
                    <span>Data Pipeline</span>
                </div>
                <button type="button" class="project-readmore" data-bs-toggle="modal" data-bs-target="#projModal1">Read More</button>
            </div>
        </div>

        <!-- ACE DECORS -->
        <div class="project-card">
            <img class="project-card-img" loading="lazy" decoding="async" src="https://images.unsplash.com/photo-1553413077-190dd305871c?w=600&q=80" alt="ACE DECORS ERP">
            <div class="project-card-body">
                <div class="project-tag">ERP System</div>
                <h3>ACE DECORS – ERP Application</h3>
                <p>
                    A full ERP solution to manage customer orders, inventory,
                    production tracking, billing, and business reporting for an
                    interior & decor company.
                </p>
                <div class="tech">
                    <span>Web App</span>
                    <span>MySQL</span>
                    <span>Reports</span>
                </div>
                <button type="button" class="project-readmore" data-bs-toggle="modal" data-bs-target="#projModal2">Read More</button>
            </div>
        </div>

        <!-- RECON -->
        <div class="project-card">
            <img class="project-card-img" loading="lazy" decoding="async" src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=80" alt="RECON Event Analytics">
            <div class="project-card-body">
                <div class="project-tag">Event Analytics</div>
                <h3>RECON – Event Analytics Platform</h3>
                <p>
                    A QR-based feedback and stall-wise analytics system that provides
                    live insights on footfall, visitor satisfaction, and exhibitor
                    performance.
                </p>
                <div class="tech">
                    <span>Forms</span>
                    <span>Database</span>
                    <span>Dashboards</span>
                </div>
                <button type="button" class="project-readmore" data-bs-toggle="modal" data-bs-target="#projModal3">Read More</button>
            </div>
        </div>

        <!-- OXFORD -->
        <div class="project-card">
            <img class="project-card-img" loading="lazy" decoding="async" src="https://images.unsplash.com/photo-1509062522246-3755977927d7?w=600&q=80" alt="Oxford Coaching Web App">
            <div class="project-card-body">
                <div class="project-tag">Education Technology</div>
                <h3>Oxford Coaching – Dynamic Web App</h3>
                <p>
                    A student and batch management system with online registration,
                    notices, and administrative controls for coaching classes.
                </p>
                <div class="tech">
                    <span>Web App</span>
                    <span>Admin Panel</span>
                    <span>Database</span>
                </div>
                <button type="button" class="project-readmore" data-bs-toggle="modal" data-bs-target="#projModal4">Read More</button>
            </div>
        </div>

        <!-- ANGADI -->
        <div class="project-card">
            <img class="project-card-img" loading="lazy" decoding="async" src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&q=80" alt="Angadi Coaching Web Application">
            <div class="project-card-body">
                <div class="project-tag">Education Technology</div>
                <h3>Angadi Coaching – Web Application</h3>
                <p>
                    A digital platform to manage admissions, student records,
                    course information, and communication with students.
                </p>
                <div class="tech">
                    <span>Web App</span>
                    <span>MySQL</span>
                    <span>Admin Panel</span>
                </div>
                <button type="button" class="project-readmore" data-bs-toggle="modal" data-bs-target="#projModal5">Read More</button>
            </div>
        </div>

        <!-- ANJUMAN -->
        <div class="project-card">
            <img class="project-card-img" loading="lazy" decoding="async" src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=600&q=80" alt="Anjuman BCA BBA Institute">
            <div class="project-card-body">
                <div class="project-tag">College Management</div>
                <h3>Anjuman BCA & BBA Institute</h3>
                <p>
                    A digital academic management system for handling student data,
                    academic records, and institutional information in a centralized
                    platform.
                </p>
                <div class="tech">
                    <span>Web System</span>
                    <span>Database</span>
                    <span>Automation</span>
                </div>
                <button type="button" class="project-readmore" data-bs-toggle="modal" data-bs-target="#projModal6">Read More</button>
            </div>
        </div>

    </div>
</div>
<!-- ===== PROJECT DETAIL MODALS ===== -->
<div class="modal fade" id="projModal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="project-modal-title">NWKRTC – BI Dashboard</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=900&q=80" loading="lazy" decoding="async" class="img-fluid rounded mb-3" alt="NWKRTC BI Dashboard">
                <p>
                    A powerful Business Intelligence system built for North Western Karnataka Road Transport
                    Corporation to track depot-wise revenue, route performance, fleet availability, and
                    operational efficiency across the network. The dashboard consolidates data pipelines
                    from multiple depots into a single Power BI reporting layer, backed by a SQL data
                    warehouse, giving management real-time visibility into revenue trends, route
                    profitability, and fleet utilization for faster, data-driven decisions.
                </p>
                <div class="tech">
                    <span>Power BI</span>
                    <span>SQL</span>
                    <span>Data Pipeline</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="projModal2" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="project-modal-title">ACE DECORS – ERP Application</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <img src="https://images.unsplash.com/photo-1553413077-190dd305871c?w=900&q=80" loading="lazy" decoding="async" class="img-fluid rounded mb-3" alt="ACE DECORS ERP">
                <p>
                    A full ERP solution developed for an interior & decor company to manage the complete
                    order lifecycle — from customer order intake to inventory tracking, production
                    scheduling, billing, and business reporting. Built as a centralized MySQL-backed
                    web application, it replaces manual spreadsheets with a single system that gives
                    the business real-time stock levels, order status, and financial reports.
                </p>
                <div class="tech">
                    <span>Web App</span>
                    <span>MySQL</span>
                    <span>Reports</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="projModal3" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="project-modal-title">RECON – Event Analytics Platform</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=900&q=80" loading="lazy" decoding="async" class="img-fluid rounded mb-3" alt="RECON Event Analytics">
                <p>
                    A QR-based feedback and stall-wise analytics system designed for large-scale events
                    and exhibitions. Visitors scan a QR code at each stall to submit feedback, which
                    feeds into a live dashboard showing footfall trends, visitor satisfaction scores,
                    and exhibitor performance in real time — helping organizers make on-the-spot
                    decisions and exhibitors measure their event ROI.
                </p>
                <div class="tech">
                    <span>Forms</span>
                    <span>Database</span>
                    <span>Dashboards</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="projModal4" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="project-modal-title">Oxford Coaching – Dynamic Web App</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?w=900&q=80" loading="lazy" decoding="async" class="img-fluid rounded mb-3" alt="Oxford Coaching Web App">
                <p>
                    A student and batch management system built for Oxford Coaching to handle
                    online registration, batch scheduling, notices, and administrative controls
                    from a single admin panel. The platform streamlines day-to-day coaching
                    operations, replacing manual registers with a searchable, database-backed
                    record system for staff and students alike.
                </p>
                <div class="tech">
                    <span>Web App</span>
                    <span>Admin Panel</span>
                    <span>Database</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="projModal5" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="project-modal-title">Angadi Coaching – Web Application</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=900&q=80" loading="lazy" decoding="async" class="img-fluid rounded mb-3" alt="Angadi Coaching Web Application">
                <p>
                    A digital platform built for Angadi Coaching to manage admissions, student
                    records, course information, and communication with students, all backed
                    by a MySQL database. The system centralizes previously scattered paperwork
                    into a single admin-controlled portal, making enrollment and record-keeping
                    faster and more reliable.
                </p>
                <div class="tech">
                    <span>Web App</span>
                    <span>MySQL</span>
                    <span>Admin Panel</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="projModal6" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="project-modal-title">Anjuman BCA & BBA Institute</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=900&q=80" loading="lazy" decoding="async" class="img-fluid rounded mb-3" alt="Anjuman BCA BBA Institute">
                <p>
                    A digital academic management system built for Anjuman BCA & BBA Institute to
                    handle student data, academic records, and institutional information in a
                    single centralized platform. The system automates routine record-keeping
                    tasks, giving faculty and administrators a consistent, database-driven view
                    of student and institutional data.
                </p>
                <div class="tech">
                    <span>Web System</span>
                    <span>Database</span>
                    <span>Automation</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER CTA -->
<style>
    /* ===== Alumni Cover-Flow Carousel ===== */
    .alumni-cf-section {
        background: #14163A;
        padding: 30px 0 80px;
        overflow: hidden;
    }

    .alumni-cf-heading {
        text-align: center;
        padding: 40px 20px 10px;
    }

    .alumni-cf-heading h2 {
        color: #fbfbfc;
        font-weight: 700;
        margin: 0;
        font-size: 1.5rem;
    }

    .alumni-cf-heading h2,
    .alumni-cf-heading h2 * {
        color: #FFFFFF !important;
    }

    .alumni-cf-wrapper {
        position: relative;
        width: 100%;
        max-width: 1000px;
        height: 300px;
        margin: 40px auto 0;
        display: flex;
        align-items: center;
        justify-content: center;
        perspective: 1200px;
    }

    .alumni-cf-carousel {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .alumni-cf-item {
        width: 220px;
        height: 220px;
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        position: absolute;
        top: 0;
        left: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        transform: translateX(-50%) scale(0.6);
        opacity: 0;
        transition: transform 0.6s cubic-bezier(.77, 0, .18, 1), opacity 0.6s ease;
        pointer-events: none;
        border: 4px solid #F6BE01;
    }

    .alumni-cf-item img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .alumni-cf-item.active {
        transform: translateX(-50%) scale(1);
        opacity: 1;
        z-index: 3;
        pointer-events: auto;
    }

    .alumni-cf-item.left {
        transform: translateX(-160%) scale(0.8) rotateY(25deg);
        opacity: 0.75;
        z-index: 2;
        pointer-events: auto;
    }

    .alumni-cf-item.right {
        transform: translateX(60%) scale(0.8) rotateY(-25deg);
        opacity: 0.75;
        z-index: 2;
        pointer-events: auto;
    }

    .alumni-cf-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);

        width: 50px;
        height: 50px;

        display: flex;
        justify-content: center;
        align-items: center;

        background: #ffffff;
        border: 3px solid #F6BE01;
        border-radius: 50%;

        color: #4042e2;
        cursor: pointer;

        padding: 0;
        line-height: 1;

        box-shadow: 0 8px 20px rgba(0, 0, 0, .15);
        transition: all .3s ease;
    }

    .alumni-cf-btn:hover {
        background: #FFD700;
    }

    .alumni-cf-btn.alumni-cf-left {
        left: 10px;
    }

    .alumni-cf-btn.alumni-cf-right {
        right: 10px;
    }

    @media (max-width: 767px) {
        .alumni-cf-wrapper {
            height: 220px;
        }

        .alumni-cf-item {
            width: 150px;
            height: 150px;
            padding: 16px;
        }

        .alumni-cf-item.left {
            transform: translateX(-140%) scale(0.7) rotateY(25deg);
        }

        .alumni-cf-item.right {
            transform: translateX(40%) scale(0.7) rotateY(-25deg);
        }

        .alumni-cf-btn {
            width: 38px;
            height: 38px;
            font-size: 1.1rem;
        }
    }

    .alumni-cf-section .section-title h2,
    .alumni-cf-section .section-title h2 span {
        color: #fff !important;
    }
</style>

<section class="alumni-cf-section container-fluid">
    <div class="section-title">
        <span class="section-tag">Success Stories</span>

        <h2>
            Our Alumni Work <span>At</span>
        </h2>

        <p>
            Our graduates are building successful careers in leading companies across India and beyond.
        </p>
    </div>

    <div class="alumni-cf-wrapper">
        <div class="alumni-cf-carousel" id="alumniCfCarousel"></div>
        <button class="alumni-cf-btn alumni-cf-left">
            <i class="fa fa-chevron-left"></i>
        </button>

        <button class="alumni-cf-btn alumni-cf-right">
            <i class="fa fa-chevron-right"></i>
        </button>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // TODO: swap in the real company names for logos 3-6 below (they weren't
        // legible in the source image) so every alt tag is fully descriptive.
        const alumniLogos = [{
                src: '/img/logo 1.png',
                name: 'TVG Agency'
            },
            {
                src: '/img/logo 2.webp',
                name: 'Ken Gen'
            },
            {
                src: '/img/logo 3.png',
                name: 'Alumni placement company 3'
            },
            {
                src: '/img/logo 4.png',
                name: 'Alumni placement company 4'
            },
            {
                src: '/img/logo 5.png',
                name: 'Alumni placement company 5'
            },
            {
                src: '/img/logo 6.png',
                name: 'Alumni placement company 6'
            }
        ];

        const alumniCarousel = document.getElementById('alumniCfCarousel');
        const alumniBtnLeft = document.querySelector('.alumni-cf-btn.alumni-cf-left');
        const alumniBtnRight = document.querySelector('.alumni-cf-btn.alumni-cf-right');
        let alumniActiveIndex = 0;
        let alumniItems = [];

        function createAlumniItems() {
            alumniLogos.forEach((logo) => {
                const item = document.createElement('div');
                item.className = 'alumni-cf-item';
                const img = document.createElement('img');
                img.src = logo.src;
                img.alt = logo.name + ' — DharwadHubballiTutor alumni placement';
                img.loading = 'lazy';
                item.appendChild(img);
                alumniCarousel.appendChild(item);
            });
            alumniItems = Array.from(alumniCarousel.getElementsByClassName('alumni-cf-item'));
        }

        function updateAlumniCarousel() {
            alumniItems.forEach((item, i) => {
                item.classList.remove('active', 'left', 'right');
                if (i === alumniActiveIndex) {
                    item.classList.add('active');
                } else if (i === (alumniActiveIndex + 1) % alumniItems.length) {
                    item.classList.add('right');
                } else if (i === (alumniActiveIndex - 1 + alumniItems.length) % alumniItems.length) {
                    item.classList.add('left');
                }
            });
        }

        alumniBtnLeft.addEventListener('click', () => {
            alumniActiveIndex = (alumniActiveIndex - 1 + alumniItems.length) % alumniItems.length;
            updateAlumniCarousel();
        });

        alumniBtnRight.addEventListener('click', () => {
            alumniActiveIndex = (alumniActiveIndex + 1) % alumniItems.length;
            updateAlumniCarousel();
        });

        let alumniAutoRotate = setInterval(() => alumniBtnRight.click(), 4000);
        document.querySelector('.alumni-cf-wrapper').addEventListener('mouseenter', () => clearInterval(alumniAutoRotate));
        document.querySelector('.alumni-cf-wrapper').addEventListener('mouseleave', () => {
            alumniAutoRotate = setInterval(() => alumniBtnRight.click(), 4000);
        });

        createAlumniItems();
        updateAlumniCarousel();
    });
</script>
<style>
    #demomodal .modal-dialog {
        max-width: 440px;
    }

    #demomodal .modal-content {
        border: 2px solid #FFD700;
        border-radius: 24px;
        overflow: hidden;
        background: #fff;
    }

    #demomodal .modal-header {
        background: linear-gradient(160deg, #14163a 0%, #1f1547 100%);
        border-bottom: 3px solid #FFD700;
        padding: 26px 30px;
    }

    #demomodal .modal-header h3 {
        color: #101111 !important;
        font-weight: 800;
        font-size: 1.3rem;
        margin: 0;
    }

    #demomodal .modal-header .close {
        color: #fff;
        opacity: 1;
        background: rgba(255, 255, 255, 0.1);
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        transition: all 0.25s ease;

    }

    #demomodal .modal-header .close:hover {
        background: #FFD700;
        color: #14163a;
    }

    #demomodal .modal-body {
        padding: 28px 30px 6px;
        background: #ffffff;
    }

    #demomodal .label {
        font-weight: 700;
        color: #16182b;
        font-size: 0.88rem;
        margin-bottom: 6px;
        margin-top: 16px;
        display: block;
    }

    #demomodal .label:first-of-type {
        margin-top: 0;
    }

    #demomodal .form-control,
    #demomodal .form-select {
        border-radius: 12px;
        border: 1.5px solid #eef0f4;
        background: #f7f8fa !important;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.25s ease;
    }

    #demomodal .form-control:focus,
    #demomodal .form-select:focus {
        border-color: #FFD700;
        box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.18);
        background: #ffffff !important;
        outline: none;
    }

    #demomodal .modal-footer {
        border-top: none;
        padding: 18px 30px 30px;
        gap: 12px;
    }

    #demomodal .modal-footer .btn {
        border-radius: 50px;
        font-weight: 700;
        padding: 10px 26px;
        border: none;
        transition: all 0.25s ease;
    }

    #demomodal .modal-footer button[type="submit"] {
        background: #FFD700;
        color: #14163a;
    }

    #demomodal .modal-footer button[type="submit"]:hover {
        background: #e0a700;
        transform: translateY(-2px);
    }

    #demomodal .modal-footer button[data-bs-dismiss="modal"] {
        background: transparent;
        color: #666;
        border: 1.5px solid #ddd;
    }

    #demomodal .modal-footer button[data-bs-dismiss="modal"]:hover {
        background: #f5f5f5;
    }

    @media (max-width:768px) {

        #demomodal .modal-content {
            max-height: 75vh !important;
            overflow-y: auto !important;
        }

        #demomodal .modal-header {
            padding: 15px 20px !important;
        }

        #demomodal .modal-body {
            padding: 15px !important;
        }

        #demomodal .form-group,
        #demomodal .mb-3 {
            margin-bottom: 12px !important;
        }

        #demomodal label {
            margin-bottom: 5px !important;
            font-size: 14px !important;
        }

        #demomodal input,
        #demomodal select,
        #demomodal textarea {
            height: 46px !important;
            padding: 10px 14px !important;
            font-size: 15px !important;
        }

        #demomodal .modal-footer {
            padding: 15px !important;
        }

    }
</style>
<div class="modal fade" id="demomodal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Register for Demo Class</h3>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="text-align: right;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php session_start(); ?>
                <form class="modal-content" action="../Admin/Controller/newenquiry.php" method="POST" autocomplete="off" id="contactForm">
                    <div class="container">

                        <label class="label" for="name2"><b>Name</b></label>
                        <input type="text"
                            name="name2"
                            class="form-control"
                            id="name2"
                            placeholder="Name"
                            required
                            autocomplete="off"
                            oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')"
                            pattern="[A-Za-z]+( [A-Za-z]+)*"
                            title="Name should contain letters and spaces only." />
                        <input type="hidden" name="front" class="form-control" id="demofront" value="front" />
                        <label class="label" for="email2"><b>Email</b></label>
                        <input type="email" name="email2" class="form-control" id="email2" placeholder="name@example.com" />
                        <label class="label" for="phone2"><b>Enter your number:</b></label>
                        <input type="tel" name="phone2" class="form-control" id="phone2" placeholder="Number" required pattern="^[6-9]\d{9}$" />
                        <label class="label" for="demo2"><b>Demo Class For </b></label>
                        <select class="form-select" id="demo2" name="trainings2" style="background-color:#f1f1f1">
                            <option value="">SELECT YOUR INTEREST</option>
                            <?php
                            $courselist = DBcourse::selectall();
                            foreach ($courselist as $course) {
                                echo "<option value='" . $course->get_cname() . "'>" . $course->get_cname() . "</option>";
                            }
                            ?>
                        </select>
                        <br />
                        <input type="hidden" id="recaptcha-token" name="recaptcha-token">
                    </div>

            </div>
            <div class="modal-footer">
                <button type=button class="btn btn-warning" data-bs-dismiss=modal>Close</button>
                <button type=submit class="btn btn-warning" name="demosubmit">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<br>
<style>
    #modal2 .modal-dialog {
        max-width: 460px;
    }

    #modal2 .modal-content {
        border-radius: 24px;
        overflow: hidden;
        border: none;
        box-shadow: 0 25px 60px rgba(20, 15, 60, 0.35);
    }

    #modal2 .modal-header {
        background: linear-gradient(160deg, #14163a 0%, #1f1547 100%);
        border-bottom: 3px solid #FFD700;
        padding: 26px 30px;
    }

    #modal2 .modal-header h2 {
        color: #ffffff !important;
        font-weight: 800;
        font-size: 1.25rem;
        margin: 0;
    }

    #modal2 .modal-header .close {
        color: #fff;
        opacity: 1;
        background: rgba(255, 255, 255, 0.1);
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        transition: all 0.25s ease;
    }

    #modal2 .modal-header .close:hover {
        background: #FFD700;
        color: #14163a;
    }

    #modal2 .modal-body {
        padding: 28px 30px 6px;
        background: #ffffff;
        max-height: 60vh;
        overflow-y: auto;
    }

    #modal2 .label {
        font-weight: 700;
        color: #16182b;
        font-size: 0.88rem;
        margin-bottom: 6px;
        margin-top: 16px;
        display: block;
    }

    #modal2 .label:first-of-type {
        margin-top: 0;
    }

    #modal2 .form-control,
    #modal2 .form-select {
        border-radius: 12px;
        border: 1.5px solid #eef0f4;
        background: #f7f8fa !important;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.25s ease;
    }

    #modal2 .form-control:focus,
    #modal2 .form-select:focus {
        border-color: #FFD700;
        box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.18);
        background: #ffffff !important;
        outline: none;
    }

    #modal2 .modal-footer {
        border-top: none;
        padding: 18px 30px 30px;
        gap: 12px;
    }

    #modal2 .modal-footer .btn {
        border-radius: 50px;
        font-weight: 700;
        padding: 10px 26px;
        border: none;
        transition: all 0.25s ease;
    }

    #modal2 .modal-footer button[type="submit"] {
        background: #FFD700;
        color: #14163a;
    }

    #modal2 .modal-footer button[type="submit"]:hover {
        background: #e0a700;
        transform: translateY(-2px);
    }

    #modal2 .modal-footer button[data-bs-dismiss="modal"] {
        background: transparent;
        color: #666;
        border: 1.5px solid #ddd;
    }

    #modal2 .modal-footer button[data-bs-dismiss="modal"]:hover {
        background: #f5f5f5;
    }
</style>
<div class="modal fade" id=modal2 tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog modal-dialog-centered" role=document>
        <div class=modal-content>
            <div class=modal-header>
                <h2>Training and Internship</h2>
                <button type=button class=close data-bs-dismiss=modal aria-label=Close>
                    <span aria-hidden=true>&times;</span>
                </button>
            </div>
            <div class=modal-body>
                <form class="modal-content" action="../Admin/Controller/newenquiry.php" method="POST" autocomplete="off">
                    <div class="container">
                        <label class=label for=name2b><b>Name</b></label>
                        <input type="text" name="name2" class="form-control" id="name2b" placeholder="Name" required />
                        <input type="hidden" name="front" class="form-control" id="front2b" value="front" />
                        <label class=label for=email2b><b>Email</b></label>
                        <input type=email name=email2 class=form-control id=email2b placeholder=name@example.com />
                        <label class=label for=phone2b><b>Enter your number:</b></label>
                        <input type=tel name=phone2 class=form-control id=phone2b placeholder=Number required />
                        <label class=label for=trainings2b><b>Trainings</b></label>
                        <select class=form-select id=trainings2b name=trainings2 style="background-color:#f1f1f1">
                            <option value="">Select your Interest</option>
                            <?php
                            $courselist = DBcourse::selectall();
                            foreach ($courselist as $course) {
                                echo "<option value='" . $course->get_cname() . "'>" . $course->get_cname() . "</option>";
                            }
                            ?>
                        </select><br />
                        <label class=label for=internship2><b>Internships</b></label>
                        <select class=form-select id=internship2 name=internship2 style="background-color:#f1f1f1">
                            <option value=" ">Select your Interest</option>
                            <?php
                            $courselist = DBcourse::selectall();
                            foreach ($courselist as $course) {
                                echo "<option value='" . $course->get_cname() . "'>" . $course->get_cname() . "</option>";
                            }
                            ?>
                        </select>
                        <br />

                    </div>

            </div>
            <div class=modal-footer>
                <button type=button class="btn btn-warning" data-bs-dismiss=modal>Close</button>
                <button type=submit class="btn btn-warning" name="footerformsubmit">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once("footer.php"); ?>
<script src="https://www.google.com/recaptcha/api.js?render=6LeUqr8qAAAAACuw4V1CXyY4tQMb1T1qo5EFWAbg"></script>
<script>
    function onSubmit(token) {
        document.getElementById("contactForm").submit();
    }

    function prepareRecaptcha() {
        grecaptcha.ready(function() {
            grecaptcha.execute('6LeUqr8qAAAAACuw4V1CXyY4tQMb1T1qo5EFWAbg', {
                action: 'submit'
            }).then(function(token) {
                document.getElementById('recaptcha-token').value = token;
            });
        });
    }

    document.getElementById("contactForm").addEventListener("submit", function(e) {

        e.preventDefault();

        grecaptcha.ready(function() {

            grecaptcha.execute(
                '6LeUqr8qAAAAACuw4V1CXyY4tQMb1T1qo5EFWAbg', {
                    action: 'submit'
                }
            ).then(function(token) {

                document.getElementById("recaptcha-token").value = token;

                document.getElementById("contactForm").submit();

            });

        });

    });
</script>

<script>
    function saveUserData(userData) {
        debugger;
        $.post('http://www.dharwadhubballitutor.com/views/userData.php', {
            oauth_provider: 'google',
            userData: JSON.stringify(userData)
        });
    }

    // Render Google Sign-in button
    function renderButton() {
        gapi.signin2.render('gSignIn', {
            'scope': 'profile email',
            'width': 200,
            'height': 40,
            'longtitle': true,
            'theme': 'dark',
            'onsuccess': onSuccess,
            'onfailure': onFailure
        });
    }

    // Sign-in success callback
    function onSuccess(googleUser) {
        // Get the Google profile data (basic)
        var profile = googleUser.getBasicProfile();

        // Retrieve the Google account data
        gapi.client.load('oauth2', 'v2', function() {
            var request = gapi.client.oauth2.userinfo.get({
                'userId': 'me'
            });
            request.execute(function(resp) {
                // Display the user details
                var profileHTML = '<h3>Welcome ' + resp.given_name +
                    '! <a href="javascript:void(0);" onclick="signOut();">Sign out</a></h3>';
                profileHTML += '<img src="' + resp.picture + '"/><p><b>Google ID: </b>' + resp.id +
                    '</p><p><b>Name: </b>' + resp.name + '</p><p><b>Email: </b>' + resp.email +
                    '</p><p><b>Gender: </b>' + resp.gender + '</p><p><b>Locale: </b>' + resp.locale +
                    '</p><p>';
                document.getElementsByClassName("userContent")[0].innerHTML = profileHTML;

                document.getElementById("gSignIn").style.display = "none";
                document.getElementsByClassName("userContent")[0].style.display = "block";

                // Save user data
                saveUserData(resp);
            });
        });
    }

    // Sign-in failure callback
    function onFailure(error) {
        alert(error);
    }

    // Sign out the user
    function signOut() {
        var auth2 = gapi.auth2.getAuthInstance();
        auth2.signOut().then(function() {
            document.getElementsByClassName("userContent")[0].innerHTML = '';
            document.getElementsByClassName("userContent")[0].style.display = "none";
            document.getElementById("gSignIn").style.display = "block";
        });

        auth2.disconnect();

    }
</script>