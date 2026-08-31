<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-10940352050">
</script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'AW-10940352050');
</script>
<!-- Google tag (gtag.js) event - delayed navigation helper -->
<script>
    // Helper function to delay opening a URL until a gtag event is sent.
    // Call it in response to an action that should navigate to a URL.
    function gtagSendEvent(url) {
        var callback = function() {
            if (typeof url === 'string') {
                window.location = url;
            }
        };
        gtag('event', 'ads_conversion_Sign_Up_1', {
            'event_callback': callback,
            'event_timeout': 2000,
            // <event_parameters>
        });
        return false;
    }
</script>
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


<?php

$request = $_SERVER['REQUEST_URI'];
error_log($request);
$str = $request;
$parsed = parse_url($str);

if (!empty($parsed['query'])) {
    error_log($parsed['query']);
    $request = '/';
}
switch ($request) {
    case  '/':
        require __DIR__ . '/views/index.php';
        break;
    case  '/hubli/':
        require __DIR__ . '/views/hublibranch.php';
        break;
    case  '/dharwad/':
        require __DIR__ . '/views/dharwadbranch.php';
        break;
    case '/belgavi/':
        require __DIR__ . '/views/belgavibranch.php';
        break;
    case '':
        require __DIR__ . '/views/index.php';
        break;
    case '/?fbclid=':
        require __DIR__ . '/views/index.php';
        break;
    case  '/contact/':
        require __DIR__ . '/views/contact.php';
        break;
    case  '/about/':
        require __DIR__ . '/views/about.php';
        break;
    case  '/termsandconditions/':
        require __DIR__ . '/views/termsandconditions.php';
        break;
    case  '/PrivacyPolicy/':
        require __DIR__ . '/views/PrivacyPolicy.php';
        break;
    case '/admin/login.php':
        require __DIR__ . '/admin/views/login.php';
        break;

    case '/webhooks':
        require __DIR__ . '/webhook.php';
        break;

    case '/courses.php':
        require __DIR__ . '/views/courses.php';
        break;
    default:
        require __DIR__ . '/views/route.php';
        route::get($request);
        break;
}
