<?php
header("Content-Type: application/json");
session_start();

if (!isset($_SESSION['lead'])) {
    $_SESSION['lead'] = [
        "step" => 0,
        "name" => "",
        "phone" => "",
        "interest" => ""
    ];
}

// If we are in lead capture mode

// ---------- CONFIG ----------
$apiKey = "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";
$MAX_MEMORY = 6; // last N messages
// ----------------------------

// Read input
$input = json_decode(file_get_contents("php://input"), true);
$message = trim($input['message'] ?? '');

if ($message === '') {
    echo json_encode(["reply" => "Please type your question."]);
    exit;
}

// If we are in lead capture mode
if ($_SESSION['lead']['step'] === 1) {
    $_SESSION['lead']['name'] = $message;
    $_SESSION['lead']['step'] = 2;
    echo json_encode(["reply" => "Thanks! Please share your mobile number."]);
    exit;
}

if ($_SESSION['lead']['step'] === 2) {
    $_SESSION['lead']['phone'] = $message;
    $_SESSION['lead']['step'] = 3;
    echo json_encode(["reply" => "Which course are you interested in?"]);
    exit;
}

if ($_SESSION['lead']['step'] === 3) {

    // Save interest
    $_SESSION['lead']['interest'] = $message;

    // Safety validation before DB insert
    if (
        $_SESSION['lead']['name'] !== "" &&
        $_SESSION['lead']['phone'] !== "" &&
        $_SESSION['lead']['interest'] !== ""
    ) {
        saveLead($_SESSION['lead']);
        
    }
     $_SESSION['lead'] = [
        "step" => 0,
        "name" => "",
        "phone" => "",
        "interest" => ""
    ];

echo json_encode([
    "reply" => "✅ Your enquiry has been booked successfully.\nWe’ve sent you a WhatsApp confirmation.",
    "whatsapp" => $whatsappUrl
]);
exit;

}


// Load system prompt
$systemPrompt = file_get_contents(__DIR__ . "/prompt.txt");

// Initialize memory
if (!isset($_SESSION['chat_history'])) {
    $_SESSION['chat_history'] = [];
}

/**
 * Add user message to memory
 */
$_SESSION['chat_history'][] = [
    "role" => "user",
    "content" => [
        ["type" => "input_text", "text" => $message]
    ]
];

/**
 * Keep only last N messages (cost control)
 */
$_SESSION['chat_history'] = array_slice(
    $_SESSION['chat_history'],
    -$MAX_MEMORY
);

/**
 * Build payload with memory
 */
$payload = [
    "model" => "gpt-4o-mini",
    "input" => array_merge(
        [
            [
                "role" => "system",
                "content" => [
                    ["type" => "input_text", "text" => $systemPrompt]
                ]
            ]
        ],
        $_SESSION['chat_history']
    )
];

// API call
$ch = curl_init("https://api.openai.com/v1/responses");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json"
    ],
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_TIMEOUT => 30
]);

$response = curl_exec($ch);
curl_close($ch);

// Decode response
$data = json_decode($response, true);


// ---------- EXTRACT AI RESPONSE ----------
$reply = "Please contact our team.";


/* ðŸ”¥ DETECT BOOK_ENQUIRY IMMEDIATELY */


/* Now do cleaning AFTER detection */
$reply = trim($reply);
$reply = preg_replace("/\n{3,}/", "\n\n", $reply);
$lines = explode("\n", $reply);
$reply = implode("\n", array_slice($lines, 0, 6));



if (
    isset($data['output'][0]['content']) &&
    is_array($data['output'][0]['content'])
) {
    foreach ($data['output'][0]['content'] as $block) {
        if (($block['type'] ?? '') === 'output_text') {
            $reply = $block['text'];
            break;
        }
    }
}

// ---------- SAVE AI REPLY TO MEMORY ----------
$_SESSION['chat_history'][] = [
    "role" => "assistant",
    "content" => [
        ["type" => "output_text", "text" => $reply]
    ]
];

$lowerMsg = strtolower($message);

// Check if user clearly wants to join / contact
$userWantsEnquiry =
    strpos($lowerMsg, "join") !== false ||
    strpos($lowerMsg, "enroll") !== false ||
    strpos($lowerMsg, "register") !== false ||
    strpos($lowerMsg, "book") !== false ||
    strpos($lowerMsg, "contact") !== false ||
    strpos($lowerMsg, "yes") !== false ||
    strpos($lowerMsg, "ok") !== false ||
    strpos($lowerMsg, "k") !== false ||
    strpos($lowerMsg, "sure") !== false ||
    strpos($lowerMsg, "call") !== false;
if ($userWantsEnquiry && strpos($reply, "BOOK_ENQUIRY") !== false) {
    $_SESSION['lead']['step'] = 1;
    echo json_encode([
        "reply" => "May I know your name?"
    ]);
    exit;
}

// Final response to UI
echo json_encode(["reply" => $reply]);

function saveLead($lead) {
    $conn = new mysqli('68.178.149.184','dht','pKw-j-w9','dharwadhubballitutor');

    if ($conn->connect_error) {
        return;
    }

    $stmt = $conn->prepare(
        "INSERT INTO chatbot_leads (name, phone, interest) VALUES (?, ?, ?)"
    );

error_log($lead['name']);
    $stmt->bind_param(
        "sss",
        $lead['name'],
        $lead['phone'],
        $lead['interest']
    );

    $stmt->execute();
    $stmt->close();
    $conn->close();
    $whatsAppMsg = urlencode(
    "Hi {$lead['name']} 👋\n\n"
  . "Thank you for your enquiry at DharwadHubballiTutor.\n"
  . "Our team will contact you shortly.\n\n"
  . "📞 +91 97412 37334"
);

$whatsappUrl = "https://wa.me/91{$lead['phone']}?text={$whatsAppMsg}";

}

