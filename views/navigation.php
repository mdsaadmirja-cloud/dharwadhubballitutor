<?php
$configs = require_once("config.php");
require_once("blogadmin/dblayer/businessOps.php");
require_once("blogadmin/dblayer/categoryOps.php");
require_once("blogadmin/dblayer/postOps.php");
require_once "blogadmin/dblayer/socialMediaHandleOps.php";
require_once "Admin/DB Operations/CoursesOps.php";
require_once "blogadmin/dblayer/termsandconditionsOps.php";
require_once("blogadmin/dblayer/PrivacyPolicyOps.php");
require_once("blogadmin/dblayer/testimonialsOps.php");
require_once("blogadmin/dblayer/facebookchatOps.php");
require_once("middleware/middleware.php");
require_once("middleware/csrf_middleware.php");
$request = $_SERVER['REQUEST_URI'];
$path = substr($request, 1, -1);
$Home = preg_replace('|/|', 'DharwadHubballiTutor', $request);
if (!isset($post)) {
  $post = null;
}
if ($post == 0) {
  if (
    $request != '/dharwad/' &&
    $request != '/hubli/' &&
    $request != '/belgavi/' &&
    $request != '/' &&
    $request != '' &&
    $request != '/about/' &&
    $request != '/contact/' &&
    $request != '/termsandconditions/' &&
    $request != '/PrivacyPolicy/'
  ) {

    http_response_code(404);
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
    include('404.html'); // provide your own HTML for the error page
    die();
  }
}

$middleware = new Middleware();

// Add CSRF middleware
$middleware->add(new CsrfMiddleware());
$request = $_SERVER;
$middleware->handle($request);
?>
<?php
$courselist = DBcourse::selectall();
$business = DBbusiness::getBusinessDetails();
$socialMediaHandles = DBsocialMediaHandle::read(); ?>
<!doctype html>
<html lang="en">

<head>
  <title><?php echo ($post != 0) ? $post->getPostTitle()  : "DharwadHubballiTutor | Software Training, Internships & 100% Placement"; ?></title>

  <meta charset="utf-8">
  <meta name="author" content="DharwadHubballiTutor" />
  <meta name="description" content="<?php echo ($post != 0) ? $post->getKeywords() : "DharwadHubballiTutor is one of the best software training institutes in Hubli and Dharwad to learn and build your career on the following cutting-edge technologies like Full stack web design and development, Digital Marketing, Data Science, Cloud Computing, Software Testing, SAP, Basic Computer programming language, and many more. "; ?>" />
  <meta name="keywords" content="<?php echo ($post != 0) ? $post->getKeywords() : "Provides:Python Programming Courses,Provides:Java,Provides:Web Designing,
    Provides:Mechanical Designing,Provides:Civil Designing,Provides:Digital Marketing,Provides:Basics of Computers,
    Provides:Advanced Excel,Provides:C Language Course,Provides:C++ Course,Provides:Cloud Computing Course"; ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=5" />

  <meta name="title" content="DharwadHubballiTutor | Software Training, Internships & 100% Placement">
  <meta name="description" content="Build your career with DharwadHubballiTutor. ISO 9001:2015 certified & NASSCOM member. Microsoft Certified Trainers providing software coaching, internships, and guaranteed placement in Hubli-Dharwad.">
  <meta name="keywords" content="Software training Hubli, Python classes Dharwad, Java training Hubballi, Web development internship, IT placement Hubli, Microsoft Certified Trainer, DharwadHubballiTutor">
  <meta name="author" content="DharwadHubballiTutor">
  <meta name="robots" content="index, follow">

  <?php
  // ✅ CANONICAL URL (dynamic, strips query strings so filtered/duplicate
  // URL variants all point back to the clean canonical version)
  $canonicalPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $canonicalUrl = "https://www.dharwadhubballitutor.com" . $canonicalPath;
  ?>
  <link rel="canonical" href="<?php echo $canonicalUrl; ?>" />

  <meta property="og:type" content="website">
  <meta property="og:url" content="https://www.dharwadhubballitutor.com/">
  <meta property="og:title" content="DharwadHubballiTutor | Build your Career in Software Industry">
  <meta property="og:description" content="Join the best software training institute in Hubli-Dharwad. Learn from Microsoft Certified Trainers and get 100% placement assistance.">
  <meta property="og:image" content="https://www.dharwadhubballitutor.com/img/bg-home.jpg">

  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="https://www.dharwadhubballitutor.com/">
  <meta property="twitter:title" content="DharwadHubballiTutor | Software Training & Placement">
  <meta property="twitter:description" content="ISO 9001:2015 Certified Training Institute in Hubli-Dharwad. Expertise in cutting-edge tech.">
  <meta property="twitter:image" content="https://www.dharwadhubballitutor.com/img/bg-home.jpg">

  <meta name="theme-color" content="#4042e2">
  <!-- ✅ PRECONNECT (SAFE PERFORMANCE BOOST) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://cdnjs.cloudflare.com">

  <!-- ✅ GOOGLE FONTS (SAFE) -->
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Baskervville&family=Roboto:wght@400;700;900&display=swap">
  <link href="https://fonts.googleapis.com/css2?family=Baskervville&family=Roboto:wght@400;700;900&display=swap" rel="stylesheet">

  <!-- ✅ CRITICAL CSS (MINIMAL) -->
  <style>
    body {
      margin: 0;
      font-family: 'Roboto', system-ui;
      background: #fff;
      color: #000
    }

    header,
    nav {
      display: block
    }
  </style>

  <!-- ✅ CSS (BLOCKING BUT STABLE) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link href="/css/dharwadhubballitutor.css" rel="stylesheet">
  <link href="/css/style.css" rel="stylesheet">

  <!-- ✅ ICONS & COMPONENT CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="preload"
      href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
      as="style"
      onload="this.onload=null;this.rel='stylesheet'">

<noscript>
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
</noscript>

<link rel="preload"
      href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"
      as="style"
      onload="this.onload=null;this.rel='stylesheet'">

<noscript>
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
</noscript>

  <!-- ✅ FAVICON -->
  <link rel="icon" type="image/x-icon" href="/img/favicon.png">

  <!-- ✅ GOOGLE ADS -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=AW-10940352050"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'AW-10940352050');
    gtag('config', 'AW-10940352050/lnvSCPjyqIoZELKM4uAo', {
      'phone_conversion_number': '9741237334'
    });
  </script>

  <!-- ✅ GOOGLE ANALYTICS -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-0XVT2BPSTB"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'G-0XVT2BPSTB');
  </script>

  <!-- ✅ MISC -->
  <style>
    .g-recaptcha {
      margin: 10px 0
    }
  </style>


  <!-- Google Tag Manager -->
  <script>
    (function(w, d, s, l, i) {
      w[l] = w[l] || [];
      w[l].push({
        'gtm.start': new Date().getTime(),
        event: 'gtm.js'
      });
      var f = d.getElementsByTagName(s)[0],
        j = d.createElement(s),
        dl = l != 'dataLayer' ? '&l=' + l : '';
      j.async = true;
      j.src =
        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
      f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-KBLDLJNT');
  </script>
  <!-- End Google Tag Manager -->

  <!-- ✅ STRUCTURED DATA (JSON-LD) — added for SEO, no visual impact -->
  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [{
          "@type": "EducationalOrganization",
          "@id": "https://www.dharwadhubballitutor.com/#organization",
          "name": "DharwadHubballiTutor",
          "alternateName": "DHT",
          "url": "https://www.dharwadhubballitutor.com/",
          "logo": "https://www.dharwadhubballitutor.com/views/uploads/DHT-logo%20(2).webp",
          "image": "https://www.dharwadhubballitutor.com/img/bg-home.jpg",
          "description": "Software training institute in Hubli-Dharwad offering Full Stack Development, Data Analytics, Digital Marketing, Python, Java and Placement Training with 100% placement assistance.",
          "telephone": "<?php echo $business->getBusinessContact(); ?>",
          "email": "<?php echo $business->getBusinessEmail(); ?>",
          "priceRange": "$$",
          "areaServed": ["Dharwad", "Hubballi", "Hubli", "Karnataka"],
          "sameAs": [
            "https://www.youtube.com/@dharwadhubballitutor"
          ],
          "department": [{
              "@type": "LocalBusiness",
              "name": "DharwadHubballiTutor - Dharwad Branch",
              "url": "https://maps.app.goo.gl/TEYLkH4uqMKrMNWz9",
              "address": {
                "@type": "PostalAddress",
                "streetAddress": "J G Nippani Complex, Beside SBI, Gandhinagar-04",
                "addressLocality": "Dharwad",
                "addressRegion": "Karnataka",
                "addressCountry": "IN"
              }
            },
            {
              "@type": "LocalBusiness",
              "name": "DharwadHubballiTutor - Hubballi Branch",
              "url": "https://maps.app.goo.gl/JSRskPW2cJNnQN8E7",
              "address": {
                "@type": "PostalAddress",
                "streetAddress": "Plot 26, Jaya Nagar, Vidya Nagar",
                "addressLocality": "Hubballi",
                "addressRegion": "Karnataka",
                "postalCode": "580021",
                "addressCountry": "IN"
              }
            }
          ]
        },
        {
          "@type": "WebSite",
          "@id": "https://www.dharwadhubballitutor.com/#website",
          "url": "https://www.dharwadhubballitutor.com/",
          "name": "DharwadHubballiTutor",
          "publisher": {
            "@id": "https://www.dharwadhubballitutor.com/#organization"
          },
          "potentialAction": {
            "@type": "SearchAction",
            "target": "https://www.dharwadhubballitutor.com/search?q={search_term_string}",
            "query-input": "required name=search_term_string"
          }
        }
      ]
    }
  </script>

</head>


<body>
  <!--<?php
      #$PluginCode = DBfacebook::getPlugin();
      # echo $PluginCode->getPluginCode(); 
      ?>-->

  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KBLDLJNT"
      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

  <style>
    /* ===== VELOS-STYLE DARK PILL NAVBAR ===== */
    .dht-navbar-shell {
      width: calc(100% - 32px);
      max-width: 1320px;
      margin: 14px auto 0;
      padding: 0;
    }

    .dht-navbar {
      width: 100%;
      box-sizing: border-box;

      display: flex;
      align-items: center;
      justify-content: space-between;

      padding: 10px 22px;

      background: linear-gradient(135deg, #14163a 0%, #1b0d52 60%, #24126A 100%);

      border: 4px solid #F6BE01;
      border-radius: 16px;


      box-shadow: 0 14px 34px rgba(20, 15, 60, .28);
    }

    .dht-navbar-logo {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      color: #ffffff !important;
      font-weight: 800;
      font-size: 1.05rem;
      white-space: nowrap;
    }

    .dht-navbar-logo img {
      width: 34px;
      height: 34px;
      object-fit: contain;
      border-radius: 8px;
      background: #ffffff;
      padding: 3px;
    }

    .dht-navbar-links {
      display: flex;
      align-items: center;
      gap: 6px;
      list-style: none;
      margin: 0 16px;
      padding: 4px;
      flex: 1;
      justify-content: center;
    }

    .dht-navbar-links .nav-item {
      position: relative;
    }

    .dht-navbar-links .nav-link {
      color: rgba(255, 255, 255, 0.78) !important;
      font-weight: 600;
      font-size: 0.92rem;
      padding: 9px 16px !important;
      border-radius: 30px;
      transition: all 0.25s ease;
      display: inline-flex;
      align-items: center;
      gap: 7px;

    }

    .dht-navbar-links .nav-link:hover {
      color: #ffffff !important;
      background: rgba(255, 255, 255, 0.08);
    }

    .dht-navbar-links .nav-link.active {
      color: #14163a !important;
      background: #FFD700;
    }

    .dht-navbar-links .nav-link.active .dht-dot {
      background: #14163a;
    }

    .dht-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: #FFD700;
      display: inline-block;
    }

    /* Courses dropdown, styled to match the dark pill navbar */
    .dht-dropdown {
      position: relative;
    }

    .dht-dropdown-menu {

      display: none;

      position: absolute;

      top: 100%;
      right: 0;

      margin-top: 10px;

      width: 600px;

      max-height: 70vh;

      overflow-y: auto;
      overflow-x: hidden;

      overscroll-behavior: contain;

      background: #14163a;

      border: 1px solid rgba(255, 215, 0, .2);
      border-radius: 16px;

      padding: 14px;

      box-shadow: 0 18px 45px rgba(0, 0, 0, .35);

      z-index: 9999;
    }

    .dht-dropdown.dht-open>.dht-dropdown-menu {

      display: block;

    }

    .dht-navbar-links .dropdown-header {

      display: flex;
      align-items: center;
      gap: 8px;

      color: #FFD700;

      font-size: 13px;

      font-weight: 700;

      text-transform: uppercase;

      letter-spacing: 1px;

      margin: 18px 0 12px;

      padding-bottom: 10px;

      border-bottom: 1px solid rgba(255, 255, 255, .08);

    }

    .dht-navbar-links .dropdown-divider {
      border-color: rgba(255, 255, 255, 0.12);
    }

    .dht-navbar-links .dropdown-inner-menu {
      list-style: none;
      padding-left: 0;
      margin-bottom: 10px;
    }

    .dht-navbar-links .dropdown-item {
      color: rgba(255, 255, 255, 0.82);
      font-size: 0.88rem;
      padding: 6px 8px;
      border-radius: 8px;
    }

    .dht-navbar-links .dropdown-item:hover {
      background: rgba(255, 215, 0, 0.14);
      color: #ffffff;
    }

    .dht-lms-btn {
      background: #FFD700;
      color: #14163a !important;
      font-weight: 700;
      font-size: 0.9rem;
      padding: 10px 22px;
      border-radius: 30px;
      text-decoration: none;
      white-space: nowrap;
      transition: all 0.25s ease;
      box-shadow: 0 8px 18px rgba(255, 215, 0, 0.3);
    }

    .dht-lms-btn:hover {
      background: #e6c200;
      color: #14163a !important;
      transform: translateY(-2px);
    }

    @media (max-width: 991px) {
      .dht-navbar-shell {
        display: none;
      }
    }

    /* ===== Courses accordion (Training / Internship / Jobs / Workshops / Blogs -> sublinks) ===== */


    .dht-accordion-item {
      position: relative;
    }

    .dht-accordion-item>.dht-accordion-toggle {

      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;

    }

    .dht-accordion-caret {

      transition: .35s cubic-bezier(.4, 0, .2, 1);

    }

    .dht-accordion-open .dht-accordion-caret {

      transform: rotate(180deg);

      color: #FFD700;

    }

    .dht-accordion-item.dht-accordion-open>.dht-accordion-toggle .dht-accordion-caret {
      transform: rotate(-180deg);
    }

    /*
      NOTE: max-height here is only the CLOSED baseline for the animation.
      The OPEN height is no longer hardcoded (that was the scrolling bug) —
      it is now set inline via JS to the panel's real scrollHeight, so no
      content is ever clipped regardless of how many courses render.
    */
    .dht-accordion-panel {

      list-style: none;

      margin: 0;

      padding: 0;

      max-height: 0;

      overflow: hidden;

      transition:

        max-height .35s ease,

        padding .25s ease;

    }

    /* Force vertical stacking — overrides external flex-row rule on .dropdown-menu */


    /* only show once Bootstrap adds .show on click of "Courses" */




    .dht-accordion-toggle {
      width: 100%;
    }

    .dht-accordion-panel>li {
      display: block !important;
      width: 100% !important;
    }

    .dht-mobile-courses .dropdown-item {

      transform: translateY(8px);

      opacity: 0;

      animation: mobileCourse .35s forwards;

    }

    @keyframes mobileCourse {

      from {

        transform: translateY(8px);

        opacity: 0;

      }

      to {

        transform: translateY(0);

        opacity: 1;

      }

    }

    .dht-mobile-courses .dropdown-item {

      border-left: 4px solid transparent;

    }

    .dht-mobile-courses .dropdown-item:hover {

      border-left-color: #FFD700;

    }

    .dht-mobile-courses .nav-link:active {

      transform: scale(.98);

    }

    .dht-mobile-courses .dht-accordion-panel {

      padding: 8px 0 14px;

    }

    /* the dropdown needs a positioned ancestor to anchor to */

    /* ================= COURSE CARDS ================= */

    .dht-course-card {
      list-style: none;
      margin: 10px 0;
    }

    .dht-course-link {

      display: flex;
      align-items: center;
      gap: 15px;
      width: 100%;
      box-sizing: border-box;

      padding: 15px;

      background: #1b1f4d;

      border: 1px solid rgba(255, 255, 255, .08);

      border-radius: 16px;

      text-decoration: none;

      transition: .30s ease;

    }

    .dht-course-link:hover {

      background: linear-gradient(135deg,
          #252b67,
          #2d3577);

      border-color: #FFD700;

      transform: translateX(8px);

      box-shadow: 0 15px 30px rgba(246, 190, 1, .25);

    }

    .dht-course-icon {

      width: 52px;
      height: 52px;

      border-radius: 50%;

      background: linear-gradient(135deg, #FFD700, #F6BE01);

      color: #14163a;

      display: flex;

      align-items: center;

      justify-content: center;

      font-size: 22px;

      flex-shrink: 0;

    }

    .dht-course-content {

      flex: 1;

      display: flex;

      flex-direction: column;

      align-items: flex-start;

    }

    .dht-course-title {

      color: #fff;

      font-size: 15px;

      font-weight: 700;

      line-height: 1.4;

    }

    .dht-course-subtitle {

      color: #AEB6D8;

      font-size: 12px;

      margin-top: 4px;

    }

    .dht-course-more {

      margin-top: 8px;

      color: #FFD700;

      font-size: 12px;

      font-weight: 700;

    }

    .dht-course-more i {

      margin-left: 5px;

      transition: .3s;

    }

    .dht-course-link:hover .dht-course-more i {

      transform: translateX(5px);

    }

    .dht-course-link * {

      pointer-events: none;

    }

    .dht-course-icon i {

      display: block;

      color: #14163a;

      font-size: 20px;

      line-height: 1;

    }

    .dht-dropdown-menu::-webkit-scrollbar {
      width: 8px;
    }

    .dht-dropdown-menu::-webkit-scrollbar-track {
      background: #1b1f4d;
      border-radius: 20px;
    }

    .dht-dropdown-menu::-webkit-scrollbar-thumb {
      background: #F6BE01;
      border-radius: 20px;
    }

    .dht-dropdown-menu::-webkit-scrollbar-thumb:hover {
      background: #FFD700;
    }

    .dht-mobile-courses .dropdown-item {

      display: flex;

      align-items: center;

    }

    .mobile-course-content {

      flex: 1;

      min-width: 0;
    }

    .dht-mobile-courses .dropdown-item .fa-angle-right {

      flex-shrink: 0;

      margin-left: 12px;
    }

    .dht-mobile-courses .dropdown-item {

      margin: 10px 16px;

      width: auto;

      box-sizing: border-box;
    }

    /* ==============================
   MOBILE OFFCANVAS
============================== */

    .offcanvas {

      background: #f9f9ff;

      color: #ffffff;

      width: 340px;

      border-left: 3px solid #F6BE01;

    }

    .offcanvas-header {

      background: linear-gradient(135deg, #1B1F4D, #24126A);

      border-bottom: 1px solid rgba(255, 255, 255, .08);

      padding: 18px;

    }

    .offcanvas-header .navbar-brand {

      color: #fff !important;

      font-size: 18px;

      font-weight: 700;

      display: flex;

      align-items: center;

      gap: 10px;

    }

    .offcanvas-header img {

      width: 34px;

      height: 34px;

    }

    .offcanvas .btn-close {

      filter: invert(1);

      opacity: .9;

    }

    /* ===========================
   MOBILE MENU CARDS
=========================== */

    .dht-mobile-courses {

      display: flex;

      flex-direction: column;

      gap: 12px;

      padding: 10px;

    }

    .dht-mobile-courses .nav-item {

      list-style: none;

    }

    .dht-mobile-courses .nav-link {

      display: flex;

      align-items: center;

      justify-content: space-between;

      gap: 12px;

      padding: 16px 18px;

      border-radius: 14px;

      background: #1B1F4D;

      color: #111010 !important;

      text-decoration: none;

      font-size: 16px;

      font-weight: 600;

      border: 1px solid rgba(255, 255, 255, .08);

      transition: .25s;

    }

    .dht-mobile-courses .nav-link:hover {

      background: #242B66;

    }

    .dht-mobile-courses .nav-link.active {

      background: #FFD700;

      color: #14163A !important;

    }

    /* ===================================
   MOBILE COURSE CARDS
=================================== */

    .dht-mobile-courses .dropdown-item {

      display: flex;

      align-items: center;

      gap: 14px;

      padding: 14px;

      margin: 8px 12px;

      border-radius: 12px;

      background: #252B67;

      color: #fff !important;

      text-decoration: none;

      border: 1px solid rgba(255, 255, 255, .08);

      transition: .25s;

    }

    .dht-mobile-courses .dropdown-item:hover {

      background: #2F387C;

      border-color: #FFD700;

    }

    /* LEFT COURSE ICON */
    .dht-mobile-courses .dropdown-item>i:first-child {
      display: none;
    }

    /* RIGHT ARROW */
    .dht-mobile-courses .dropdown-item>.fa-angle-right {
      display: flex;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #FFD700;
      color: #14163A;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      flex-shrink: 0;
    }

    /* Let the title use the extra space */
    .mobile-course-content {
      flex: 1;
      margin-left: 0;
    }

    .mobile-course-content {

      flex: 1;

    }

    .mobile-course-title {

      color: #ffffff;

      font-size: 15px;

      font-weight: 700;

    }

    .mobile-course-title {

      white-space: normal;

      line-height: 1.4;

      display: -webkit-box;

      -webkit-line-clamp: 2;

      -webkit-box-orient: vertical;

      overflow: hidden;

      word-break: break-word;
    }

    .dht-mobile-courses .dht-accordion-item {

      transition: .30s ease;

    }

    .dht-mobile-courses .dht-accordion-item.dht-accordion-open {

      border: 2px solid #FFD700;

      box-shadow: 0 10px 25px rgba(246, 190, 1, .25);

    }

    /* ==========================================
   MOBILE TOP HEADER
========================================== */

    .mobile-menu-top {

      text-align: center;

      margin: 0 10px 25px;

      padding: 25px 18px;

      border-radius: 18px;

      background: linear-gradient(135deg, #1B1F4D, #2A3175);

      border: 1px solid rgba(255, 255, 255, .08);

      box-shadow: 0 15px 35px rgba(0, 0, 0, .25);

    }

    .mobile-logo {

      margin-bottom: 15px;

    }

    .mobile-logo img {

      width: 80px;

      height: 80px;

      border-radius: 50%;

      background: #ffffff;

      padding: 8px;

      object-fit: contain;

      box-shadow: 0 10px 25px rgba(0, 0, 0, .25);

    }

    .mobile-menu-top h5 {

      color: #ffffff;

      font-size: 22px;

      font-weight: 700;

      margin-bottom: 8px;

    }

    .mobile-menu-top p {

      color: #C9D3FF;

      font-size: 14px;

      margin-bottom: 22px;

      line-height: 1.5;

    }


    /* ==========================================
   BUTTON WRAPPER
========================================== */

    .mobile-buttons {

      display: flex;

      gap: 12px;

    }


    /* ==========================================
   COMMON BUTTON STYLE
========================================== */

    .mobile-buttons .mobile-call,
    .mobile-buttons .mobile-demo {

      flex: 1;

      display: flex;

      justify-content: center;

      align-items: center;

      gap: 8px;

      padding: 13px 15px;

      border-radius: 12px;

      font-size: 15px;

      font-weight: 600;

      text-decoration: none;

      transition: .30s ease;

      cursor: pointer;

    }


    /* ==========================================
   CALL BUTTON
========================================== */

    .mobile-call {

      background: #2E356E;

      color: #ffffff;

      border: 1px solid rgba(255, 255, 255, .08);

    }

    .mobile-call:hover {

      background: #39438B;

      color: #ffffff;

      transform: translateY(-2px);

    }


    /* ==========================================
   BOOK DEMO BUTTON
========================================== */

    .mobile-demo {

      background: #F6BE01;

      color: #14163A;

      border: none;

    }

    .mobile-demo:hover {

      background: #FFD84A;

      color: #14163A;

      transform: translateY(-2px);

    }


    /* ==========================================
   ICONS
========================================== */

    .mobile-buttons i {

      font-size: 15px;

    }


    /* ==========================================
   MOBILE RESPONSIVE
========================================== */

    @media (max-width:380px) {

      .mobile-buttons {

        flex-direction: column;

      }

    }

    /* ==========================================
   GLASS EFFECT
========================================== */

    .dht-mobile-courses .nav-link {

      background: rgba(255, 255, 255, .06);

      backdrop-filter: blur(12px);

      -webkit-backdrop-filter: blur(12px);

      border: 1px solid rgba(255, 255, 255, .08);

      box-shadow: 0 8px 20px rgba(0, 0, 0, .18);

    }

    .dht-mobile-courses .nav-link:hover {

      border-color: #F6BE01;

      transform: translateX(5px);

    }

    /* ==========================================
   CUSTOM SCROLLBAR
========================================== */

    .offcanvas-body::-webkit-scrollbar {

      width: 6px;

    }

    .offcanvas-body::-webkit-scrollbar-track {

      background: transparent;

    }

    .offcanvas-body::-webkit-scrollbar-thumb {

      background: #F6BE01;

      border-radius: 20px;

    }

    /* ==========================================
   SMOOTH OPENING
========================================== */

    .offcanvas.show {

      animation: menuSlide .35s ease;

    }

    @keyframes menuSlide {

      from {

        transform: translateX(40px);

        opacity: 0;

      }

      to {

        transform: translateX(0);

        opacity: 1;

      }

    }

    /* ==========================================
   TOUCH EFFECT
========================================== */

    .dht-mobile-courses .nav-link:active,

    .mobile-buttons button:active,

    .mobile-buttons a:active {

      transform: scale(.97);

    }
  </style>

  <header class="dht-navbar-shell only-desktop">
    <nav class="dht-navbar" aria-label="Primary navigation">
      <a class="dht-navbar-logo" href="<?php echo $configs['app_info']['appName'] ?>">
        <img src="/views/uploads/DHT-logo%20(2).webp" alt="DharwadHubballiTutor - Best Software Training Institute in Dharwad and Hubli" width="34" height="34">
        <?php echo $business->getBusinessName(); ?>
      </a>

      <ul class="dht-navbar-links navbar-nav flex-row">
        <li class="nav-item">
          <a class="nav-link active" href="/">
            <span>
              <i class="fa fa-home me-2"></i>
              Home
            </span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo $configs['app_info']['appName']; ?>/about/">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo $configs['app_info']['appName']; ?>/contact/">Contact</a>
        </li>
        <!--
<li class="nav-item">
    <a class="nav-link" href="https://dharwadhubballitutor.com/lms/views/login.php">
        LMS
    </a>
</li>
-->

        <?php
        /*
        // Categories now live as a single "Courses" dropdown placed right after LMS,
        // instead of a separate hamburger menu button. Each top-level category
        // (Training / Internship / Jobs / Workshops / Blogs, etc.) opens a
        // second-level flyout with its own sublinks on hover (desktop) or tap
        // (touch devices), via the .dht-submenu-item / .dht-submenu markup below.
        // Loop / data logic is unchanged from before.
        $categoryList = DBcategory::getAllCategory();
        sort($categoryList);
        echo '<li class="nav-item dht-dropdown">
              <a class="nav-link dht-dropdown-toggle"
   href="#"
   id="navbarDropdownCourses">
    Courses
    <i class="fa fa-angle-down ms-1"></i>
</a>
              <ul class="dht-dropdown-menu dht-courses-menu dht-accordion">';

        foreach ($categoryList as $category) {
          $categoryLabel = ucfirst(strtolower($category->getCategoryName()));

          if (empty($category->getMappedSubCategory())) {
            echo '<li><a class="dropdown-item" href="#">' . $categoryLabel . '</a></li>';
          } else {
            echo '<li class="dht-accordion-item">
                  <a class="dropdown-item dht-accordion-toggle" href="#">
                    ' . $categoryLabel . '
                    <i class="fa fa-angle-down dht-accordion-caret"></i>
                  </a>
                  <ul class="dht-accordion-panel">';

            foreach ($category->getMappedSubCategory($category->getCategoryId()) as $subcategory) {
              echo '
<li>
    <h6 class="dropdown-header">
        <i class="fa fa-graduation-cap"></i>
        ' . $subcategory->getSubCategoryName() . '
    </h6>
</li>';
              $postList = DBpost::getPostBySubCategoryFornt($subcategory->getSubCategoryId());
              sort($postList);
              foreach ($postList as $navpost) {

                $courseTitle = $navpost->getPostTitle();

                // Remove common suffixes to make the title shorter
                $courseTitle = str_replace(" Web Development Course", "", $courseTitle);
                $courseTitle = str_replace(" Course", "", $courseTitle);

                // Determine icon BEFORE echo (Font Awesome 4.7 compatible)
                $title = strtolower($courseTitle);
                $icon = "fa-laptop"; // Default

                if (strpos($title, "cloud") !== false) {
                  $icon = "fa-cloud";
                } elseif (strpos($title, "python") !== false) {
                  $icon = "fa-code";
                } elseif (strpos($title, "php") !== false) {
                  $icon = "fa-code";
                } elseif (strpos($title, "java") !== false) {
                  $icon = "fa-coffee";
                } elseif (strpos($title, ".net") !== false) {
                  $icon = "fa-desktop";
                } elseif (strpos($title, "website") !== false) {
                  $icon = "fa-globe";
                } elseif (strpos($title, "hosting") !== false) {
                  $icon = "fa-server";
                } elseif (strpos($title, "domain") !== false) {
                  $icon = "fa-link";
                } elseif (strpos($title, "data") !== false) {
                  $icon = "fa-line-chart";
                } elseif (strpos($title, "marketing") !== false) {
                  $icon = "fa-bullhorn";
                } elseif (strpos($title, "android") !== false) {
                  $icon = "fa-android";
                }

                echo '
    <li class="dht-course-card">

        <a href="' . $navpost->getPostUrl() . '" class="dht-course-link">

            <div class="dht-course-icon">
                <i class="fa ' . $icon . '"></i>
            </div>

            <div class="dht-course-content">

                <div class="dht-course-title">
                    ' . $courseTitle . '
                </div>

                <div class="dht-course-subtitle">
                    Industry Ready Course
                </div>

                <div class="dht-course-more">
                    Explore
                    <i class="fa fa-arrow-right"></i>
                </div>

            </div>

        </a>

    </li>';
              }
            }
            echo '</ul></li>';
          }
        }
        echo '</ul></li>';
        */
        ?>
      </ul>

      <a class="dht-lms-btn" href="https://dharwadhubballitutor.com/lms/views/login.php">LMS Login</a>
    </nav>
  </header>

  <!-- ===== MOBILE NAV (unchanged logic, off-canvas menu) ===== -->
  <nav class="navbar bg-light fixed-top d-lg-none pt-2">
    <div class="container-fluid">
      <a class="navbar-brand align-items-center business-name px-3" href="<?php echo $configs['app_info']['appName'] ?>">
        <?php echo $business->getBusinessName(); ?>
      </a>
      <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Open navigation menu">
        <i class="fa fa-bars"></i>
      </button>
      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">

          <a class="navbar-brand"
            href="<?php echo $configs['app_info']['appName'] ?>">

            <img src="/views/uploads/DHT-logo%20(2).webp"
              alt="Logo">

            <span><?php echo $business->getBusinessName(); ?></span>

          </a>

          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas"
            aria-label="Close">
          </button>

        </div>
        <div class="offcanvas-body">
          <div class="mobile-menu-top">

            <div class="mobile-logo">

              <img src="/views/uploads/DHT-logo (2).webp">

            </div>

            <h5>DharwadHubballiTutor</h5>

            <p>

              Learn • Build • Get Hired

            </p>

            <div class="mobile-buttons">

              <a href="tel:+919741237334"

                class="mobile-call">

                <i class="fa fa-phone"></i>

                Call Us

              </a>

              <button type="button"
                class="mobile-demo"
                data-bs-toggle="modal"
                data-bs-target="#demomodal">

                <i class="fa fa-calendar me-2"></i>

                Book Demo

              </button>

            </div>

          </div>

          <ul class="navbar-nav me-auto mb-2 mb-lg-0 dht-mobile-courses">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="/">Home</a>
            </li>
            <?php
            $categoryList = DBcategory::getAllCategory();
            sort($categoryList);
            foreach ($categoryList as $category) {
              $categoryLabel = ucfirst(strtolower($category->getCategoryName()));

              if (empty($category->getMappedSubCategory())) {
                echo '<li class="nav-item">
              <a class="nav-link" href="#">' . $categoryLabel . '</a>
            </li>';
              } else {
                echo '

<li class="nav-item dht-accordion-item">

<a href="#"

class="nav-link dht-accordion-toggle">

<span>

<i class="fa fa-graduation-cap me-2"></i>

' . $categoryLabel . '

</span>

<i class="fa fa-angle-down dht-accordion-caret"></i>

</a>

<ul class="dht-accordion-panel">

';

                foreach ($category->getMappedSubCategory($category->getCategoryId()) as $subcategory) {
                  /*
echo '
<li>
    <h6 class="dropdown-header">
        <i class="fa fa-graduation-cap"></i>
        ' . $subcategory->getSubCategoryName() . '
    </h6>
</li>';
*/
                  $postList = DBpost::getPostBySubCategoryFornt($subcategory->getSubCategoryId());
                  sort($postList);
                  foreach ($postList as $navpost) {
                    $title = $navpost->getPostTitle();

                    $titleLower = strtolower($title);

                    $icon = "fa-laptop";

                    if (strpos($titleLower, "python") !== false) {
                      $icon = "fa-code";
                    } elseif (strpos($titleLower, "php") !== false) {
                      $icon = "fa-code";
                    } elseif (strpos($titleLower, "java") !== false) {
                      $icon = "fa-coffee";
                    } elseif (strpos($titleLower, "cloud") !== false) {
                      $icon = "fa-cloud";
                    } elseif (strpos($titleLower, "marketing") !== false) {
                      $icon = "fa-bullhorn";
                    } elseif (strpos($titleLower, "data") !== false) {
                      $icon = "fa-line-chart";
                    }

                    echo '

<li>

<a class="dropdown-item"

href="' . $navpost->getPostUrl() . '">

<!-- Icon removed -->

<div class="mobile-course-content">

<div class="mobile-course-title">

' . $title . '

</div>



</div>

<i class="fa fa-angle-right"></i>

</a>

</li>

';
                  }
                }

                echo '</ul></li>';
              }
            }
            ?>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="<?php echo $configs['app_info']['appName']; ?>/about/">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="<?php echo $configs['app_info']['appName']; ?>/contact/">Contact</a>
            </li>
            <li class=nav-item>
              <a class="nav-link" aria-current="page" href="https://dharwadhubballitutor.com/lms/views/login.php" class="btn btn-outline-primary">LMS</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <script>
    document.addEventListener("DOMContentLoaded", function() {

      // Desktop "Courses" trigger
      const dropdown = document.querySelector(".dht-dropdown");
      const toggle = document.querySelector(".dht-dropdown-toggle");

      let autoCloseTimer = null;

      function closeAllMenus(reason) {
        stopAutoCloseTimer();

        if (dropdown) {
          dropdown.classList.remove("dht-open");
        }

        document.querySelectorAll(".dht-accordion-item").forEach(function(item) {
          item.classList.remove("dht-accordion-open");
          const panel = item.querySelector(":scope > .dht-accordion-panel");
          if (panel) {
            panel.style.maxHeight = "0px";
          }
        });
      }

      function startAutoCloseTimer() {
        clearTimeout(autoCloseTimer);

        autoCloseTimer = setTimeout(function() {
          closeAllMenus("15 SECOND TIMER");
        }, 15000);
      }

      function stopAutoCloseTimer() {
        clearTimeout(autoCloseTimer);
      }

      // ==========================
      // Desktop Courses dropdown
      // ==========================
      if (toggle && dropdown) {
        toggle.addEventListener("click", function(e) {
          e.preventDefault();
          e.stopPropagation();

          const isOpen = dropdown.classList.contains("dht-open");

          if (isOpen) {
            closeAllMenus("TOGGLE CLICK");
          } else {
            dropdown.classList.add("dht-open");
            startAutoCloseTimer();
          }
        });
      }

      // ==========================
      // Shared accordion (Desktop + Mobile)
      // Panels now expand to their REAL content height (scrollHeight)
      // instead of a hardcoded max-height, so nothing gets clipped and
      // the outer dropdown's native scrollbar can always reach every course.
      // ==========================
      document.querySelectorAll(".dht-accordion-item").forEach(function(item) {

        const btn = item.querySelector(".dht-accordion-toggle");
        if (!btn) return;

        btn.addEventListener("click", function(e) {

          e.preventDefault();
          e.stopPropagation();

          // Start timer ONLY for desktop dropdown
          const isDesktop = !!item.closest(".dht-courses-menu");

          if (isDesktop) {
            startAutoCloseTimer();
          }

          // Only one accordion open in the same menu
          const container =
            item.closest(".dht-courses-menu, .dht-mobile-courses") ||
            item.parentElement;

          const isOpening = !item.classList.contains("dht-accordion-open");

          container.querySelectorAll(".dht-accordion-item").forEach(function(i) {
            if (i !== item) {
              i.classList.remove("dht-accordion-open");
              const otherPanel = i.querySelector(":scope > .dht-accordion-panel");
              if (otherPanel) {
                otherPanel.style.maxHeight = "0px";
              }
            }
          });

          item.classList.toggle("dht-accordion-open", isOpening);

          const panel = item.querySelector(":scope > .dht-accordion-panel");
          if (panel) {
            panel.style.maxHeight = isOpening ? panel.scrollHeight + "px" : "0px";
          }
        });

      });

      // Keep an open panel's height correct if the viewport/content reflows
      window.addEventListener("resize", function() {
        document.querySelectorAll(".dht-accordion-item.dht-accordion-open").forEach(function(item) {
          const panel = item.querySelector(":scope > .dht-accordion-panel");
          if (panel) {
            panel.style.maxHeight = panel.scrollHeight + "px";
          }
        });
      });

      // ==========================
      // Outside click
      // ==========================
      document.addEventListener("click", function(e) {

        if (e.target.closest(".alumni-cf-btn")) {
          return;
        }

        if (dropdown && e.target.closest(".dht-dropdown")) {
          return;
        }

        if (e.target.closest(".dht-mobile-courses")) {
          return;
        }

        closeAllMenus("OUTSIDE CLICK");

      });

      // ==========================
      // Reset mobile accordions
      // ==========================
      const offcanvas = document.getElementById("offcanvasNavbar");

      if (offcanvas) {
        offcanvas.addEventListener("hidden.bs.offcanvas", function() {
          closeAllMenus("OFFCANVAS CLOSED");
        });
      }

      // ==========================
      // Escape key
      // ==========================
      document.addEventListener("keydown", function(e) {

        if (e.key === "Escape") {
          closeAllMenus("ESC KEY");
        }

      });
    });
  </script>