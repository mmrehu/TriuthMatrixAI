<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// ========== MULTIPLE API KEYS (FALLBACK SYSTEM) ==========
$OPENROUTER_KEYS = [
    "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",
    "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",   
    "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",   
];

$input = json_decode(file_get_contents('php://input'), true);
$news = $input['content'] ?? $input['news'] ?? $_POST['content'] ?? $_POST['news'] ?? '';

if (empty($news)) {
    http_response_code(400);
    echo json_encode(['error' => 'Content parameter is required', 'example' => '{"content": "Your text to analyze"}']);
    exit;
}

$response = [
    'news' => $news,
    'timestamp' => date('Y-m-d H:i:s'),
    'analysis' => [],
    'final_verdict' => 'uncertain',
    'overall_confidence' => 0,
    'agreement_level' => '0%',
    'sources_analyzed' => 0,
    'sources_used' => [],
    'detailed_analysis' => [],
    'factors_considered' => ['factual_accuracy', 'source_reliability', 'consensus', 'logic']
];

try {
    $results = analyzeWithMultipleAPIs($news);
    $final = calculateFinalVerdict($results);

    $response['final_verdict'] = $final['verdict'];
    $response['overall_confidence'] = $final['confidence'];
    $response['agreement_level'] = $final['agreement_level'];
    $response['sources_analyzed'] = count($results);
    $response['sources_used'] = array_keys($results);
    $response['detailed_analysis'] = $results;
    
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Analysis failed', 'message' => $e->getMessage()]);
}

// =================================== MAIN FUNCTIONS ===================================
function analyzeWithMultipleAPIs($news) {
    global $OPENROUTER_KEYS;
    $results = [];

    // === CLAUDE via OpenRouter with MULTIPLE KEY FALLBACK ===
    foreach ($OPENROUTER_KEYS as $index => $key) {
        if ($key === "" || strpos($key, "dal-de") !== false) continue;
        
        try {
            $result = analyzeWithClaude($news, $key);
            $result['source_name'] = "Claude 3.5 Sonnet";
            $result['trust_score'] = 96;
            $result['used_key_index'] = $index + 1;
            $result['api_provider'] = "OpenRouter (Key #" . ($index + 1) . ")";
            $results['claude'] = $result;
            break;
        } catch (Exception $e) {
            if ($index === count($OPENROUTER_KEYS) - 1 || empty($OPENROUTER_KEYS[$index + 1])) {
                $results['claude'] = [
                    'verdict' => 'uncertain',
                    'confidence' => 10,
                    'reason' => 'All OpenRouter keys failed or rate limited',
                    'error' => $e->getMessage(),
                    'source_name' => 'Claude 3.5 Sonnet (Failed)',
                    'trust_score' => 96,
                    'api_provider' => 'OpenRouter (All keys exhausted)'
                ];
            }
            continue;
        }
    }

    return $results;
}

function analyzeWithClaude($news, $key) {
    $prompt = "Fact check this news carefully (Hindi/English mixed bhi chalega):\n\"$news\"\n\nReply ONLY in valid JSON (no markdown, no ```):\n{\"verdict\":\"real|fake|misleading|uncertain\",\"confidence\":95,\"reason\":\"short clear reason in English or Hinglish\"}";

    $data = [
        "model" => "anthropic/claude-3.5-sonnet",
        "messages" => [["role" => "user", "content" => $prompt]],
        "max_tokens" => 400,
        "temperature" => 0.1
    ];

    $res = curl_post("https://openrouter.ai/api/v1/chat/completions", $data, "Bearer $key");

    if (isset($res['error'])) {
        throw new Exception($res['error']['message'] ?? 'Unknown OpenRouter error');
    }

    $text = $res['choices'][0]['message']['content'] ?? '';
    
    $json = parse_json_from_text($text);
    if (!$json) {
        throw new Exception("Claude ne JSON nahi diya, raw output: " . substr($text, 0, 200));
    }

    $json['confidence'] = isset($json['confidence']) ? min(100, max(0, (int)$json['confidence'])) : 80;

    return $json;
}

function curl_post($url, $data, $auth = null) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_HTTPHEADER => array_filter([
            "Content-Type: application/json",
            $auth ? "Authorization: $auth" : null,
            "HTTP-Referer: https://yourdomain.com",
            "X-Title: News Fact Checker",
        ])
    ]);

    $res = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($http_code >= 400) {
        throw new Exception("HTTP $http_code - $error");
    }

    return json_decode($res, true) ?: [];
}

function parse_json_from_text($text) {
    $text = preg_replace('/^```json\s*|```$/m', '', $text);
    preg_match('/\{.*\}/s', $text, $m);
    if (!$m) return null;
    $json = json_decode($m[0], true);
    return (json_last_error() === JSON_ERROR_NONE) ? $json : null;
}

function calculateFinalVerdict($results) {
    $votes = ['real' => 0, 'fake' => 0, 'misleading' => 0, 'uncertain' => 0];
    $weights = ['claude' => 1.0];
    $total_weight = 0;

    foreach ($results as $source => $r) {
        if (isset($r['error']) || !isset($r['verdict'])) continue;
        $w = $weights[$source] ?? 0.3;
        $votes[$r['verdict']] += $w * ($r['confidence'] / 100);
        $total_weight += $w;
    }

    if ($total_weight == 0) {
        return ['verdict' => 'uncertain', 'confidence' => 0, 'agreement_level' => '0%'];
    }

    arsort($votes);
    $winner = key($votes);
    $confidence = round(($votes[$winner] / $total_weight) * 100);

    return [
        'verdict' => $winner,
        'confidence' => $confidence,
        'agreement_level' => $confidence . '%'
    ];
}
?>