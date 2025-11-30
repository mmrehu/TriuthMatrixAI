<?php
session_start();
require_once '../database.php';

$currentPage = 'scan';
$pageTitle = 'Scan Content - TruthGuard AI';

// Get user stats from database
$user_id = 1; // Assuming logged in user ID 1
$userStats = [];
$recentScans = [];

try {
    // Get total scans count
    $stmt = $pdo->prepare("SELECT COUNT(*) as total_scans FROM user_scans WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $totalScans = $stmt->fetch(PDO::FETCH_ASSOC)['total_scans'];
    
    // Get average confidence (as accuracy)
    $stmt = $pdo->prepare("SELECT AVG(ai_confidence) as avg_accuracy FROM user_scans WHERE user_id = ? AND ai_confidence > 0");
    $stmt->execute([$user_id]);
    $avgAccuracy = $stmt->fetch(PDO::FETCH_ASSOC)['avg_accuracy'];
    
    // Get today's scans
    $stmt = $pdo->prepare("SELECT COUNT(*) as today_scans FROM user_scans WHERE user_id = ? AND DATE(created_at) = CURDATE()");
    $stmt->execute([$user_id]);
    $todayScans = $stmt->fetch(PDO::FETCH_ASSOC)['today_scans'];
    
    // Get this week's scans
    $stmt = $pdo->prepare("SELECT COUNT(*) as week_scans FROM user_scans WHERE user_id = ? AND YEARWEEK(created_at) = YEARWEEK(CURDATE())");
    $stmt->execute([$user_id]);
    $weekScans = $stmt->fetch(PDO::FETCH_ASSOC)['week_scans'];
    
    $userStats = [
        'total_scans' => $totalScans,
        'avg_accuracy' => $avgAccuracy ? round($avgAccuracy, 0) : 0,
        'today_scans' => $todayScans,
        'week_scans' => $weekScans
    ];
    
    // Get recent scans (last 3)
    $stmt = $pdo->prepare("SELECT scan_type, ai_verdict, created_at FROM user_scans WHERE user_id = ? ORDER BY created_at DESC LIMIT 3");
    $stmt->execute([$user_id]);
    $recentScans = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    error_log("Error fetching user stats: " . $e->getMessage());
    // Fallback to dummy data if database fails
    $userStats = [
        'total_scans' => 142,
        'avg_accuracy' => 87,
        'today_scans' => 8,
        'week_scans' => 42
    ];
}

// Handle form submissions
$scanResult = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scanType = $_POST['scan_type'] ?? 'text';
    
    try {
        // Process different scan types
        switch ($scanType) {
            case 'text':
                $content = $_POST['content'] ?? '';
                if (!empty($content)) {
                    // Call your factcheck API
                    $apiResult = callFactCheckAPI($content);
                    
                    if ($apiResult && isset($apiResult['final_verdict'])) {
                        $verdict = $apiResult['final_verdict'];
                        $confidence = $apiResult['overall_confidence'];
                        $reason = $apiResult['detailed_analysis']['claude']['reason'] ?? 'No reason provided';
                        
                        // Save to database
                        $stmt = $pdo->prepare("INSERT INTO user_scans (user_id, scan_type, content, ai_verdict, ai_confidence, analysis_reason) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$user_id, 'text', $content, $verdict, $confidence, $reason]);
                        
                        $scanResult = [
                            'type' => 'text',
                            'verdict' => $verdict,
                            'confidence' => $confidence,
                            'content' => $content,
                            'reason' => $reason,
                            'full_analysis' => $apiResult
                        ];
                    } else {
                        throw new Exception("API analysis failed");
                    }
                }
                break;
                
            case 'image':
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'uploads/scans/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $fileName = uniqid() . '_' . $_FILES['image']['name'];
                    $filePath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                        // Extract text from image using FREE OCR APIs
                        $extractedText = extractTextFromImage($filePath);
                        
                        // Prepare content for API
                        if (!empty($extractedText) && strlen(trim($extractedText)) > 10) {
                            $contentForAPI = "Text extracted from image: " . $extractedText;
                        } else {
                            $contentForAPI = "Image analysis for file: " . $fileName . ". No readable text found in image.";
                        }
                        
                        // Call factcheck API
                        $apiResult = callFactCheckAPI($contentForAPI);
                        
                        if ($apiResult && isset($apiResult['final_verdict'])) {
                            $verdict = $apiResult['final_verdict'];
                            $confidence = $apiResult['overall_confidence'];
                            $reason = $apiResult['detailed_analysis']['claude']['reason'] ?? 'Image content analysis';
                            
                            // Save to database
                            $stmt = $pdo->prepare("INSERT INTO user_scans (user_id, scan_type, content, file_path, ai_verdict, ai_confidence, analysis_reason) VALUES (?, ?, ?, ?, ?, ?, ?)");
                            $stmt->execute([$user_id, 'image', $contentForAPI, $filePath, $verdict, $confidence, $reason]);
                            
                            $scanResult = [
                                'type' => 'image',
                                'verdict' => $verdict,
                                'confidence' => $confidence,
                                'file_path' => $filePath,
                                'file_name' => $fileName,
                                'extracted_text' => $extractedText,
                                'reason' => $reason
                            ];
                        }
                    }
                }
                break;
                
            case 'url':
                $url = $_POST['url'] ?? '';
                if (!empty($url)) {
                    // Extract content from URL
                    $urlContent = extractUrlContent($url);
                    $apiResult = callFactCheckAPI($urlContent);
                    
                    if ($apiResult && isset($apiResult['final_verdict'])) {
                        $verdict = $apiResult['final_verdict'];
                        $confidence = $apiResult['overall_confidence'];
                        $reason = $apiResult['detailed_analysis']['claude']['reason'] ?? 'URL content analysis';
                        
                        // Save to database
                        $stmt = $pdo->prepare("INSERT INTO user_scans (user_id, scan_type, content, ai_verdict, ai_confidence, analysis_reason) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$user_id, 'url', $urlContent, $verdict, $confidence, $reason]);
                        
                        $scanResult = [
                            'type' => 'url',
                            'verdict' => $verdict,
                            'confidence' => $confidence,
                            'url' => $url,
                            'extracted_content' => $urlContent,
                            'reason' => $reason
                        ];
                    }
                }
                break;
                
            case 'bulk':
                if (isset($_FILES['bulk_files']) && is_array($_FILES['bulk_files']['name'])) {
                    $uploadDir = 'uploads/bulk/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $bulkResults = [];
                    $fileCount = count($_FILES['bulk_files']['name']);
                    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                    
                    for ($i = 0; $i < $fileCount; $i++) {
                        if ($_FILES['bulk_files']['error'][$i] === UPLOAD_ERR_OK) {
                            $originalFileName = $_FILES['bulk_files']['name'][$i];
                            $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
                            
                            // Check if file is an image
                            if (!in_array($fileExtension, $allowedTypes)) {
                                $bulkResults[] = [
                                    'file_name' => $originalFileName,
                                    'verdict' => 'unknown',
                                    'confidence' => 0,
                                    'reason' => 'Only image files are allowed for bulk upload. This file type (' . $fileExtension . ') is not supported.'
                                ];
                                continue; // Skip this file
                            }
                            
                            $fileName = uniqid() . '_' . $originalFileName;
                            $filePath = $uploadDir . $fileName;
                            
                            if (move_uploaded_file($_FILES['bulk_files']['tmp_name'][$i], $filePath)) {
                                // Extract text from image using FREE OCR APIs
                                $extractedText = extractTextFromImage($filePath);
                                
                                // Prepare content for API
                                if (!empty($extractedText) && strlen(trim($extractedText)) > 10) {
                                    $contentForAPI = "Text extracted from image: " . $extractedText;
                                } else {
                                    $contentForAPI = "Image analysis for file: " . $originalFileName . ". No readable text found in image.";
                                }
                                
                                // Call factcheck API
                                $apiResult = callFactCheckAPI($contentForAPI);
                                
                                if ($apiResult && isset($apiResult['final_verdict'])) {
                                    $verdict = $apiResult['final_verdict'];
                                    $confidence = $apiResult['overall_confidence'];
                                    $reason = $apiResult['detailed_analysis']['claude']['reason'] ?? 'Bulk image analysis';
                                    
                                    // Save to database
                                    $stmt = $pdo->prepare("INSERT INTO user_scans (user_id, scan_type, content, file_path, ai_verdict, ai_confidence, analysis_reason) VALUES (?, ?, ?, ?, ?, ?, ?)");
                                    $stmt->execute([$user_id, 'bulk', $contentForAPI, $filePath, $verdict, $confidence, $reason]);
                                    
                                    $bulkResults[] = [
                                        'file_name' => $originalFileName,
                                        'verdict' => $verdict,
                                        'confidence' => $confidence,
                                        'reason' => $reason
                                    ];
                                } else {
                                    // If API fails for this file
                                    $bulkResults[] = [
                                        'file_name' => $originalFileName,
                                        'verdict' => 'unknown',
                                        'confidence' => 0,
                                        'reason' => 'API analysis failed for this file.'
                                    ];
                                }
                            }
                        }
                    }
                    
                    $scanResult = [
                        'type' => 'bulk',
                        'results' => $bulkResults
                    ];
                }
                break;
        }
        
        // Refresh stats after new scan
        if ($scanResult && $scanResult['type'] !== 'error') {
            // Get updated stats
            $stmt = $pdo->prepare("SELECT COUNT(*) as total_scans FROM user_scans WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $userStats['total_scans'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_scans'];
            
            $stmt = $pdo->prepare("SELECT COUNT(*) as today_scans FROM user_scans WHERE user_id = ? AND DATE(created_at) = CURDATE()");
            $stmt->execute([$user_id]);
            $userStats['today_scans'] = $stmt->fetch(PDO::FETCH_ASSOC)['today_scans'];
            
            // Get updated recent scans
            $stmt = $pdo->prepare("SELECT scan_type, ai_verdict, created_at FROM user_scans WHERE user_id = ? ORDER BY created_at DESC LIMIT 3");
            $stmt->execute([$user_id]);
            $recentScans = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
    } catch (Exception $e) {
        error_log("Scan error: " . $e->getMessage());
        $scanResult = [
            'type' => 'error',
            'error' => $e->getMessage()
        ];
    }
}

// Function to extract content from URL
function extractUrlContent($url) {
    try {
        // Validate URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return "Invalid URL provided: " . $url;
        }
        
        // Initialize cURL
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_MAXREDIRS => 3
        ]);
        
        $html = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($httpCode !== 200 || !$html) {
            return "Failed to fetch URL. HTTP Code: " . $httpCode . ". Error: " . $error;
        }
        
        // Create DOMDocument and load HTML
        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        
        $content = "URL Content Analysis for: " . $url . "\n\n";
        
        // Extract title
        $title = $doc->getElementsByTagName('title');
        if ($title->length > 0) {
            $content .= "Page Title: " . trim($title->item(0)->textContent) . "\n\n";
        }
        
        // Extract meta description
        $metas = $doc->getElementsByTagName('meta');
        for ($i = 0; $i < $metas->length; $i++) {
            $meta = $metas->item($i);
            if ($meta->getAttribute('name') == 'description') {
                $content .= "Meta Description: " . trim($meta->getAttribute('content')) . "\n\n";
                break;
            }
        }
        
        // Extract main headings (h1)
        $h1s = $doc->getElementsByTagName('h1');
        if ($h1s->length > 0) {
            $content .= "Main Headings:\n";
            for ($i = 0; $i < min($h1s->length, 3); $i++) {
                $content .= "- " . trim($h1s->item($i)->textContent) . "\n";
            }
            $content .= "\n";
        }
        
        // Extract first paragraph
        $paragraphs = $doc->getElementsByTagName('p');
        if ($paragraphs->length > 0) {
            $firstPara = trim($paragraphs->item(0)->textContent);
            if (strlen($firstPara) > 50) {
                $content .= "First Paragraph: " . substr($firstPara, 0, 300) . (strlen($firstPara) > 300 ? "..." : "") . "\n\n";
            }
        }
        
        // Extract article content (common patterns)
        $articleContent = "";
        
        // Try to find article tag
        $articles = $doc->getElementsByTagName('article');
        if ($articles->length > 0) {
            $articleContent = trim($articles->item(0)->textContent);
        } else {
            // Try common content div classes
            $xpath = new DOMXPath($doc);
            $commonContentSelectors = [
                '//div[contains(@class, "content")]',
                '//div[contains(@class, "article")]',
                '//div[contains(@class, "post")]',
                '//div[contains(@class, "entry")]',
                '//div[contains(@class, "story")]'
            ];
            
            foreach ($commonContentSelectors as $selector) {
                $nodes = $xpath->query($selector);
                if ($nodes->length > 0) {
                    $articleContent = trim($nodes->item(0)->textContent);
                    break;
                }
            }
        }
        
        if (!empty($articleContent) && strlen($articleContent) > 100) {
            $content .= "Article Content (sample): " . substr($articleContent, 0, 500) . (strlen($articleContent) > 500 ? "..." : "");
        } else {
            $content .= "Note: Limited content extracted. The page might be dynamic or require JavaScript.";
        }
        
        return $content;
        
    } catch (Exception $e) {
        return "URL Content Analysis for: " . $url . "\n\nError extracting content: " . $e->getMessage();
    }
}

// Function to call your factcheck API
function callFactCheckAPI($content) {
    $apiUrl = "http://localhost/truth%20matrix/dashboard/factcheck.php";
    
    // Use POST method instead of GET for better handling
    $postData = http_build_query(['news' => $content]);
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/x-www-form-urlencoded'
        ]
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($httpCode === 200 && $response) {
        $result = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $result;
        }
    }
    
    // Fallback: If API fails, return a default analysis
    return [
        'final_verdict' => 'uncertain',
        'overall_confidence' => 50,
        'detailed_analysis' => [
            'claude' => [
                'reason' => 'API temporarily unavailable. Please try again.'
            ]
        ]
    ];
}

// Function to extract text from image using FREE OCR APIs
function extractTextFromImage($imagePath) {
    // Check if the image exists
    if (!file_exists($imagePath)) {
        return 'Image file not found.';
    }
    
    // Get file extension
    $fileExtension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
    
    // Check if it's a supported image format
    $supportedFormats = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'webp'];
    if (!in_array($fileExtension, $supportedFormats)) {
        return 'Unsupported image format.';
    }
    
    // Method 1: Using FREE OCR.space API
    $extractedText = extractTextWithOCRSpace($imagePath);
    if (!empty($extractedText) && strlen(trim($extractedText)) > 10) {
        return trim($extractedText);
    }
    
    // Method 2: Using FREE ApiFlash OCR
    $extractedText = extractTextWithApiFlash($imagePath);
    if (!empty($extractedText) && strlen(trim($extractedText)) > 10) {
        return trim($extractedText);
    }
    
    // Method 3: Simple image analysis (fallback)
    return analyzeImageContext($imagePath);
}

// Method 1: Extract text using FREE OCR.space API
function extractTextWithOCRSpace($imagePath) {
    try {
        $apiKey = 'K81461271388957'; // Free API key from OCR.space
        $url = 'https://api.ocr.space/parse/image';
        
        $imageData = file_get_contents($imagePath);
        $base64Image = base64_encode($imageData);
        
        $postData = [
            'apikey' => $apiKey,
            'base64Image' => 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . $base64Image,
            'language' => 'eng',
            'isOverlayRequired' => false,
            'OCREngine' => 2 // Engine 2 is more accurate
        ];
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            $result = json_decode($response, true);
            if (isset($result['ParsedResults'][0]['ParsedText'])) {
                $text = trim($result['ParsedResults'][0]['ParsedText']);
                return cleanExtractedText($text);
            }
        }
    } catch (Exception $e) {
        // Silent fail, try next method
    }
    
    return '';
}

// Method 2: Extract text using FREE ApiFlash OCR
function extractTextWithApiFlash($imagePath) {
    try {
        $apiKey = 'YOUR_APIFLASH_KEY'; // You can get free key from apiFlash
        $url = 'https://api.apiflash.com/v1/urltoocr';
        
        // For local files, we need to upload to a temporary URL or use base64
        // Since we can't upload local files directly, we'll skip this for now
        // and rely on OCR.space
        
        return '';
        
    } catch (Exception $e) {
        return '';
    }
}

// Method 3: Using FREE Tesseract OCR if available on server
function extractTextWithTesseract($imagePath) {
    // Try different possible Tesseract paths
    $possiblePaths = [
        '/usr/bin/tesseract',
        '/usr/local/bin/tesseract', 
        'tesseract',
        'C:\Program Files\Tesseract-OCR\tesseract.exe',
        'C:\Program Files (x86)\Tesseract-OCR\tesseract.exe'
    ];
    
    $tesseractPath = null;
    foreach ($possiblePaths as $path) {
        if ($path === 'tesseract') {
            // Check if tesseract is in system PATH
            $output = [];
            $returnCode = 0;
            exec('which tesseract 2>/dev/null || where tesseract 2>/dev/null', $output, $returnCode);
            if ($returnCode === 0 && !empty($output)) {
                $tesseractPath = 'tesseract';
                break;
            }
        } elseif (file_exists($path)) {
            $tesseractPath = $path;
            break;
        }
    }
    
    if (!$tesseractPath) {
        return '';
    }
    
    // Create temporary files
    $tempDir = sys_get_temp_dir();
    $outputFile = tempnam($tempDir, 'ocr_');
    
    // Build command
    $command = escapeshellcmd($tesseractPath) . ' ' . 
               escapeshellarg($imagePath) . ' ' . 
               escapeshellarg($outputFile) . ' ' .
               '-l eng --psm 6 -c preserve_interword_spaces=1 2>&1';
    
    // Execute Tesseract OCR
    $output = [];
    $returnCode = 0;
    exec($command, $output, $returnCode);
    
    $text = '';
    if ($returnCode === 0 && file_exists($outputFile . '.txt')) {
        $text = file_get_contents($outputFile . '.txt');
        // Clean up temporary files
        if (file_exists($outputFile . '.txt')) {
            unlink($outputFile . '.txt');
        }
        if (file_exists($outputFile)) {
            unlink($outputFile);
        }
        
        // Clean the extracted text
        $text = cleanExtractedText($text);
    }
    
    return $text;
}

// Helper function to clean extracted text
function cleanExtractedText($text) {
    if (empty($text)) return '';
    
    // Remove excessive whitespace
    $text = preg_replace('/\s+/', ' ', $text);
    
    // Remove special characters but keep basic punctuation
    $text = preg_replace('/[^\w\s\.\,\!\?\-\:\(\)\@\#\$\%\&\*]/', '', $text);
    
    // Trim and return
    return trim($text);
}

// Method 4: Simple image context analysis (fallback)
function analyzeImageContext($imagePath) {
    $fileName = basename($imagePath);
    
    // Remove the unique ID prefix we added
    $cleanName = preg_replace('/^[a-f0-9]+_/', '', $fileName);
    
    // Remove file extension
    $cleanName = pathinfo($cleanName, PATHINFO_FILENAME);
    
    // Common patterns that might indicate content type
    $patterns = [
        'screenshot' => 'Contains screenshot content',
        'meme' => 'Image appears to be a meme',
        'news' => 'News-related image content',
        'post' => 'Social media post image',
        'ad' => 'Advertisement content',
        'quote' => 'Text quote image',
        'chart' => 'Data visualization chart',
        'graph' => 'Statistical graph',
        'infographic' => 'Informational graphic',
        'document' => 'Document screenshot',
        'text' => 'Text-based image',
        'message' => 'Message or communication',
        'whatsapp' => 'WhatsApp message/screenshot',
        'twitter' => 'Twitter post/screenshot',
        'facebook' => 'Facebook post/screenshot',
        'instagram' => 'Instagram post/screenshot'
    ];
    
    $description = "Image file analysis: " . $cleanName;
    
    foreach ($patterns as $pattern => $desc) {
        if (stripos($cleanName, $pattern) !== false) {
            $description .= " - " . $desc;
            break;
        }
    }
    
    // Add file size and dimensions info
    $imageInfo = @getimagesize($imagePath);
    if ($imageInfo) {
        $description .= " [Dimensions: " . $imageInfo[0] . "x" . $imageInfo[1] . "px]";
    }
    
    return $description;
}

// Function to format time ago correctly
function timeAgo($timestamp) {
    $time = strtotime($timestamp);
    $now = time();
    $diff = $now - $time;
    
    if ($diff < 60) {
        return 'Just now';
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } else {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Content - TruthGuard AI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --background: hsl(220, 28%, 8%);
            --foreground: hsl(210, 40%, 98%);
            --card: hsl(220, 25%, 12%);
            --card-foreground: hsl(210, 40%, 98%);
            --primary: hsl(189, 94%, 55%);
            --primary-foreground: hsl(220, 28%, 8%);
            --secondary: hsl(240, 15%, 20%);
            --secondary-foreground: hsl(210, 40%, 98%);
            --muted: hsl(220, 20%, 18%);
            --muted-foreground: hsl(215, 20%, 65%);
            --border: hsl(220, 20%, 20%);
            --glow-cyan: hsl(189, 94%, 55%);
            --glow-blue: hsl(217, 91%, 60%);
            --radius: 0.75rem;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--background);
            color: var(--foreground);
            line-height: 1.6;
            overflow-x: hidden;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        @media (min-width: 1024px) {
            .container {
                padding: 0 2rem;
            }
        }

        /* Glass Effects */
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-strong, .glass-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glow-text {
            text-shadow: 0 0 30px hsla(189, 94%, 55%, 0.5);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px hsla(189, 94%, 55%, 0.3); }
            50% { box-shadow: 0 0 40px hsla(189, 94%, 55%, 0.6); }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .slide-up {
            animation: slideUp 0.6s ease-out forwards;
        }

        .pulse {
            animation: pulse-glow 2s ease-in-out infinite;
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(30px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 4rem;
        }

        @media (min-width: 1024px) {
            .header-content {
                height: 5rem;
            }
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo-icon {
            width: 2rem;
            height: 2rem;
            color: var(--primary);
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @media (min-width: 1024px) {
            .logo-text {
                font-size: 1.5rem;
            }
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-link {
            color: var(--muted-foreground);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -0.25rem;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link.active {
            color: var(--primary);
        }

        .nav-link.active::after {
            width: 100%;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .desktop-nav {
            display: none;
        }

        @media (min-width: 1024px) {
            .desktop-nav {
                display: flex;
            }
        }

        .mobile-menu-btn {
            display: block;
            background: none;
            border: none;
            color: var(--foreground);
            cursor: pointer;
            padding: 0.5rem;
        }

        @media (min-width: 1024px) {
            .mobile-menu-btn {
                display: none;
            }
        }

        .menu-icon {
            width: 1.5rem;
            height: 1.5rem;
        }

        .mobile-menu {
            display: none;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .mobile-menu.active {
            display: block;
        }

        .mobile-nav {
            padding: 1.5rem 1rem;
        }

        .mobile-nav-link {
            display: block;
            color: var(--muted-foreground);
            text-decoration: none;
            padding: 0.75rem 0;
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .mobile-nav-link:hover {
            color: var(--primary);
        }

        .mobile-menu-actions {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            color: white;
            box-shadow: 0 4px 16px hsla(189, 94%, 55%, 0.5);
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px hsla(189, 94%, 55%, 0.6);
        }

        .btn-ghost {
            background: transparent;
            color: var(--foreground);
        }

        .btn-ghost:hover {
            color: var(--primary);
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: var(--foreground);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-lg {
            padding: 0.875rem 1.75rem;
            font-size: 1rem;
        }

        .btn-icon {
            width: 1.25rem;
            height: 1.25rem;
        }

        .btn-primary .btn-icon {
            transition: transform 0.3s ease;
        }

        .btn-primary:hover .btn-icon {
            transform: translateX(4px);
        }

        /* Scan Layout */
        .scan {
            padding-top: 5rem;
            min-height: 100vh;
        }

        @media (min-width: 1024px) {
            .scan {
                padding-top: 6rem;
            }
        }

        .scan-content {
            padding: 2rem 0;
        }

        @media (min-width: 1024px) {
            .scan-content {
                padding: 3rem 0;
            }
        }

        .scan-grid {
            display: grid;
            gap: 1.5rem;
        }

        @media (min-width: 1024px) {
            .scan-grid {
                grid-template-columns: 1fr 400px;
                gap: 2rem;
            }
        }

        .scan-main {
            display: grid;
            gap: 1.5rem;
        }

        .scan-sidebar {
            display: grid;
            gap: 1.5rem;
            align-content: start;
        }

        /* Scan Cards */
        .scan-card {
            padding: 1.5rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
        }

        .scan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px hsla(189, 94%, 55%, 0.2);
        }

        .scan-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .scan-card-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .scan-card-icon {
            width: 2rem;
            height: 2rem;
            color: var(--primary);
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .tab {
            padding: 0.75rem 1.5rem;
            background: none;
            border: none;
            color: var(--muted-foreground);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 2px solid transparent;
        }

        .tab:hover {
            color: var(--primary);
        }

        .tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-input, .form-textarea, .form-file {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--foreground);
            font-family: inherit;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .form-input:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px hsla(189, 94%, 55%, 0.2);
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-file {
            padding: 1.5rem;
            text-align: center;
            border: 2px dashed rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-file:hover {
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.03);
        }

        .file-icon {
            width: 3rem;
            height: 3rem;
            margin: 0 auto 1rem;
            color: var(--muted-foreground);
        }

        .file-input {
            display: none;
        }

        /* Results */
        .result-card {
            padding: 1.5rem;
            border-radius: var(--radius);
            margin-top: 1.5rem;
            transition: all 0.3s ease;
        }

        .result-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .result-title {
            font-size: 1.125rem;
            font-weight: 600;
        }

        .verdict-badge {
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .verdict-real {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .verdict-fake {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .verdict-misleading {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }

        .verdict-uncertain {
            background: rgba(156, 163, 175, 0.2);
            color: #9ca3af;
        }

        .confidence-meter {
            width: 100%;
            height: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            overflow: hidden;
            margin: 1rem 0;
        }

        .confidence-fill {
            height: 100%;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            border-radius: 4px;
            transition: width 0.6s ease;
        }

        .result-content {
            margin-top: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: var(--radius);
        }

        .result-image {
            max-width: 100%;
            border-radius: var(--radius);
        }

        .bulk-results {
            display: grid;
            gap: 1rem;
        }

        .bulk-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            border-radius: var(--radius);
        }

        .bulk-file-name {
            font-weight: 500;
        }

        .analysis-details {
            margin-top: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.02);
            border-radius: var(--radius);
            border-left: 3px solid var(--primary);
        }

        .analysis-reason {
            font-style: italic;
            color: var(--muted-foreground);
        }

        .extracted-text {
            margin-top: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.02);
            border-radius: var(--radius);
            border-left: 3px solid #10b981;
        }

        .extracted-text h4 {
            margin-bottom: 0.5rem;
            color: #10b981;
        }

        /* User Stats */
        .user-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .user-stat {
            text-align: center;
            padding: 1rem;
            border-radius: var(--radius);
        }

        .user-stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        @media (min-width: 768px) {
            .user-stat-value {
                font-size: 2rem;
            }
        }

        .user-stat-label {
            font-size: 0.875rem;
            color: var(--muted-foreground);
        }

        /* Recent Scans */
        .scan-list {
            display: grid;
            gap: 1rem;
        }

        .scan-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
        }

        .scan-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .scan-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
        }

        .scan-icon svg {
            width: 1.25rem;
            height: 1.25rem;
            color: var(--primary);
        }

        .scan-info {
            flex: 1;
        }

        .scan-type {
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .scan-time {
            font-size: 0.75rem;
            color: var(--muted-foreground);
        }

        .scan-verdict {
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Footer */
        .footer {
            background: rgba(255, 255, 255, 0.03);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 3rem 0;
        }

        @media (min-width: 1024px) {
            .footer {
                padding: 4rem 0;
            }
        }

        .footer-grid {
            display: grid;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        @media (min-width: 768px) {
            .footer-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .footer-grid {
                grid-template-columns: repeat(6, 1fr);
            }
        }

        .footer-brand {
            grid-column: span 2;
        }

        .footer-description {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            margin: 1rem 0 1.5rem;
            max-width: 20rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-link {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .social-link svg {
            width: 1.25rem;
            height: 1.25rem;
            color: var(--muted-foreground);
            transition: color 0.3s ease;
        }

        .social-link:hover svg {
            color: var(--primary);
        }

        .footer-links-title {
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        .footer-bottom {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            align-items: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        @media (min-width: 768px) {
            .footer-bottom {
                flex-direction: row;
                justify-content: space-between;
            }
        }

        .footer-bottom p {
            font-size: 0.875rem;
            color: var(--muted-foreground);
        }

        .footer-bottom-links {
            display: flex;
            gap: 1.5rem;
        }

        .footer-bottom-links a {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-bottom-links a:hover {
            color: var(--primary);
        }
        
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
            animation: fadeInUp 0.4s ease-out;
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 2rem;
        }
        
        .loading-spinner {
            width: 2rem;
            height: 2rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
     <?php include 'header.php'; ?>
    <!-- Scan Section -->
    <section class="scan">
        <div class="container">
            <div class="scan-content">
                <div class="scan-grid">
                    <div class="scan-main">
                        <!-- Welcome Card -->
                        <div class="scan-card glass-strong fade-in-up">
                            <div class="scan-card-header">
                                <h2 class="scan-card-title">AI Content Scanner</h2>
                                <svg class="scan-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path>
                                </svg>
                            </div>
                            <p>Use our advanced AI to detect deepfakes, manipulated media, and misinformation across different content types.</p>
                        </div>

                        <!-- Scan Tabs -->
                        <div class="scan-card glass-strong fade-in-up delay-1">
                            <div class="tabs">
                                <button class="tab active" data-tab="text">Text</button>
                                <button class="tab" data-tab="image">Image</button>
                                <button class="tab" data-tab="url">Article URL</button>
                                <button class="tab" data-tab="bulk">Bulk Upload</button>
                            </div>

                            <!-- Loading Indicator -->
                            <div class="loading" id="loadingIndicator">
                                <div class="loading-spinner"></div>
                                <p>Analyzing content with AI...</p>
                            </div>

                            <!-- Text Scan Form -->
                            <form id="text-form" class="tab-content active" method="POST" enctype="multipart/form-data" onsubmit="showLoading()">
                                <input type="hidden" name="scan_type" value="text">
                                <div class="form-group">
                                    <label class="form-label" for="content">Paste Text Content</label>
                                    <textarea class="form-textarea glass" id="content" name="content" placeholder="Paste the text you want to analyze for authenticity..." required><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                    </svg>
                                    Analyze Text
                                </button>
                            </form>

                            <!-- Image Scan Form -->
                            <form id="image-form" class="tab-content" method="POST" enctype="multipart/form-data" onsubmit="showLoading()">
                                <input type="hidden" name="scan_type" value="image">
                                <div class="form-group">
                                    <label class="form-label" for="image">Upload Image</label>
                                    <div class="form-file glass" id="image-dropzone">
                                        <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                        <p>Drag & drop an image or click to browse</p>
                                        <p style="font-size: 0.75rem; color: var(--muted-foreground); margin-top: 0.5rem;">Supports JPG, PNG, GIF (Max 10MB)</p>
                                        <input type="file" class="file-input" id="image" name="image" accept="image/*" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                    Analyze Image
                                </button>
                            </form>

                            <!-- URL Scan Form -->
                            <form id="url-form" class="tab-content" method="POST" enctype="multipart/form-data" onsubmit="showLoading()">
                                <input type="hidden" name="scan_type" value="url">
                                <div class="form-group">
                                    <label class="form-label" for="url">Enter Article URL</label>
                                    <input type="url" class="form-input glass" id="url" name="url" placeholder="https://example.com/article" value="<?php echo isset($_POST['url']) ? htmlspecialchars($_POST['url']) : ''; ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                        <polyline points="15 3 21 3 21 9"></polyline>
                                        <line x1="10" y1="14" x2="21" y2="3"></line>
                                    </svg>
                                    Analyze URL
                                </button>
                            </form>

                            <!-- Bulk Upload Form -->
                            <form id="bulk-form" class="tab-content" method="POST" enctype="multipart/form-data" onsubmit="showLoading()">
                                <input type="hidden" name="scan_type" value="bulk">
                                <div class="form-group">
                                    <label class="form-label" for="bulk_files">Upload Multiple Images</label>
                                    <div class="form-file glass" id="bulk-dropzone">
                                        <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                        <p>Drag & drop multiple images or click to browse</p>
                                        <p style="font-size: 0.75rem; color: var(--muted-foreground); margin-top: 0.5rem;">Supports JPG, PNG, GIF, BMP, WebP (Max 10 files, 10MB each)</p>
                                        <input type="file" class="file-input" id="bulk_files" name="bulk_files[]" multiple accept="image/*" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="17 8 12 3 7 8"></polyline>
                                        <line x1="12" y1="3" x2="12" y2="15"></line>
                                    </svg>
                                    Analyze All Images
                                </button>
                            </form>

                            <!-- Results Display -->
                            <?php if ($scanResult && $scanResult['type'] !== 'error'): ?>
                                <div class="result-card glass fade-in-up delay-2">
                                    <?php if ($scanResult['type'] === 'bulk'): ?>
                                        <!-- Bulk results have different structure -->
                                        <div class="result-header">
                                            <h3 class="result-title">Bulk Analysis Results</h3>
                                        </div>
                                        
                                        <div class="result-content">
                                            <p><strong>Bulk Analysis Completed:</strong> Processed <?php echo count($scanResult['results']); ?> file(s)</p>
                                            <div class="bulk-results">
                                                <?php foreach ($scanResult['results'] as $result): ?>
                                                    <div class="bulk-item glass">
                                                        <div>
                                                            <span class="bulk-file-name"><?php echo htmlspecialchars($result['file_name']); ?></span>
                                                            <?php if (isset($result['reason'])): ?>
                                                                <p style="font-size: 0.75rem; color: var(--muted-foreground); margin-top: 0.25rem;">
                                                                    <?php echo htmlspecialchars(substr($result['reason'], 0, 100) . (strlen($result['reason']) > 100 ? '...' : '')); ?>
                                                                </p>
                                                            <?php endif; ?>
                                                        </div>
                                                        <span class="verdict-badge verdict-<?php echo $result['verdict'] ?? 'unknown'; ?>">
                                                            <?php echo strtoupper($result['verdict'] ?? 'UNKNOWN'); ?> 
                                                            <?php if (isset($result['confidence']) && $result['confidence'] > 0): ?>
                                                                (<?php echo number_format($result['confidence'], 1); ?>%)
                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <!-- Regular single scan results -->
                                        <div class="result-header">
                                            <h3 class="result-title">Analysis Result</h3>
                                            <span class="verdict-badge verdict-<?php echo $scanResult['verdict'] ?? 'unknown'; ?>">
                                                <?php echo strtoupper($scanResult['verdict'] ?? 'UNKNOWN'); ?>
                                            </span>
                                        </div>
                                        
                                        <?php if (isset($scanResult['confidence']) && $scanResult['confidence'] > 0): ?>
                                            <div class="confidence-meter">
                                                <div class="confidence-fill" style="width: <?php echo $scanResult['confidence']; ?>%"></div>
                                            </div>
                                            <p>AI Confidence: <strong><?php echo number_format($scanResult['confidence'], 1); ?>%</strong></p>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($scanResult['reason'])): ?>
                                        <div class="analysis-details">
                                            <p class="analysis-reason"><?php echo htmlspecialchars($scanResult['reason']); ?></p>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div class="result-content">
                                            <?php if ($scanResult['type'] === 'text'): ?>
                                                <p><strong>Scanned Text:</strong></p>
                                                <p><?php echo htmlspecialchars(substr($scanResult['content'], 0, 200) . (strlen($scanResult['content']) > 200 ? '...' : '')); ?></p>
                                            <?php elseif ($scanResult['type'] === 'image'): ?>
                                                <p><strong>Scanned Image:</strong> <?php echo $scanResult['file_name']; ?></p>
                                                <?php if (file_exists($scanResult['file_path'])): ?>
                                                    <img src="<?php echo $scanResult['file_path']; ?>" alt="Scanned Image" class="result-image">
                                                <?php endif; ?>
                                                
                                                <?php if (isset($scanResult['extracted_text'])): ?>
                                                    <div class="extracted-text">
                                                        <h4>Extracted Text from Image:</h4>
                                                        <p><?php echo htmlspecialchars(substr($scanResult['extracted_text'], 0, 300) . (strlen($scanResult['extracted_text']) > 300 ? '...' : '')); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            <?php elseif ($scanResult['type'] === 'url'): ?>
                                                <p><strong>Scanned URL:</strong> <a href="<?php echo $scanResult['url']; ?>" target="_blank"><?php echo $scanResult['url']; ?></a></p>
                                                <?php if (isset($scanResult['extracted_content'])): ?>
                                                    <div class="extracted-text">
                                                        <h4>Extracted Content:</h4>
                                                        <p><?php echo nl2br(htmlspecialchars(substr($scanResult['extracted_content'], 0, 500) . (strlen($scanResult['extracted_content']) > 500 ? '...' : ''))); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php elseif ($scanResult && $scanResult['type'] === 'error'): ?>
                                <div class="result-card glass fade-in-up delay-2" style="border-left: 3px solid #ef4444;">
                                    <div class="result-header">
                                        <h3 class="result-title">Analysis Failed</h3>
                                        <span class="verdict-badge verdict-fake">ERROR</span>
                                    </div>
                                    <p>There was an error analyzing your content: <?php echo htmlspecialchars($scanResult['error']); ?></p>
                                    <p>Please try again or contact support if the problem persists.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="scan-sidebar">
                        <!-- User Stats -->
                        <div class="scan-card glass-strong fade-in-up delay-2">
                            <div class="scan-card-header">
                                <h2 class="scan-card-title">Your Scan Stats</h2>
                                <svg class="scan-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                </svg>
                            </div>
                            <div class="user-stats">
                                <div class="user-stat glass">
                                    <div class="user-stat-value gradient-text"><?php echo $userStats['total_scans']; ?></div>
                                    <div class="user-stat-label">Total Scans</div>
                                </div>
                                <div class="user-stat glass">
                                    <div class="user-stat-value gradient-text"><?php echo $userStats['avg_accuracy']; ?>%</div>
                                    <div class="user-stat-label">Accuracy</div>
                                </div>
                                <div class="user-stat glass">
                                    <div class="user-stat-value gradient-text"><?php echo $userStats['today_scans']; ?></div>
                                    <div class="user-stat-label">Today</div>
                                </div>
                                <div class="user-stat glass">
                                    <div class="user-stat-value gradient-text"><?php echo $userStats['week_scans']; ?></div>
                                    <div class="user-stat-label">This Week</div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Scans -->
                        <div class="scan-card glass-strong fade-in-up delay-3">
                            <div class="scan-card-header">
                                <h2 class="scan-card-title">Recent Scans</h2>
                                <svg class="scan-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    <polyline points="17 6 23 6 23 12"></polyline>
                                </svg>
                            </div>
                            <div class="scan-list">
                                <?php if (!empty($recentScans)): ?>
                                    <?php foreach ($recentScans as $scan): ?>
                                        <div class="scan-item glass">
                                            <div class="scan-icon">
                                                <?php 
                                                $icon = '';
                                                $scanType = strtolower($scan['scan_type']);
                                                if ($scanType === 'text') {
                                                    $icon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>';
                                                } elseif ($scanType === 'image') {
                                                    $icon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>';
                                                } elseif ($scanType === 'url') {
                                                    $icon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>';
                                                } else {
                                                    $icon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>';
                                                }
                                                echo $icon;
                                                ?>
                                            </div>
                                            <div class="scan-info">
                                                <div class="scan-type"><?php echo ucfirst($scan['scan_type']); ?> Analysis</div>
                                                <div class="scan-time">
                                                    <?php echo timeAgo($scan['created_at']); ?>
                                                </div>
                                            </div>
                                            <span class="scan-verdict verdict-<?php echo strtolower($scan['ai_verdict']); ?>">
                                                <?php echo strtoupper($scan['ai_verdict']); ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="scan-item glass">
                                        <div class="scan-info">
                                            <div class="scan-type">No scans yet</div>
                                            <div class="scan-time">Start scanning to see results</div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Upgrade CTA -->
                        <div class="scan-card glass-strong fade-in-up delay-4">
                            <div class="scan-card-header">
                                <h2 class="scan-card-title">Upgrade to Pro</h2>
                                <svg class="scan-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                                </svg>
                            </div>
                            <p>Unlock unlimited scans, advanced analytics, and priority processing.</p>
                            <a href="pricing.php" class="btn btn-primary w-full" style="margin-top: 1rem;">
                                Upgrade Now
                                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="index.php" class="logo">
                        <svg class="logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span class="logo-text">TruthGuard AI</span>
                    </a>
                    <p class="footer-description">Advanced AI-powered content verification to combat misinformation and deepfakes.</p>
                    <div class="social-links">
                        <a href="#" class="social-link">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                        </a>
                        <a href="#" class="social-link">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path>
                            </svg>
                        </a>
                        <a href="#" class="social-link">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </a>
                        <a href="#" class="social-link">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                <rect x="2" y="9" width="4" height="12"></rect>
                                <circle cx="4" cy="4" r="2"></circle>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h3 class="footer-links-title">Product</h3>
                    <ul>
                        <li><a href="scan.php">Content Scanner</a></li>
                        <li><a href="pricing.php">Pricing</a></li>
                        <li><a href="api.php">API</a></li>
                        <li><a href="changelog.php">Changelog</a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h3 class="footer-links-title">Resources</h3>
                    <ul>
                        <li><a href="blog.php">Blog</a></li>
                        <li><a href="guides.php">Guides</a></li>
                        <li><a href="community.php">Community</a></li>
                        <li><a href="support.php">Support</a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h3 class="footer-links-title">Company</h3>
                    <ul>
                        <li><a href="about.php">About</a></li>
                        <li><a href="careers.php">Careers</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="privacy.php">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 TruthGuard AI. All rights reserved.</p>
                <div class="footer-bottom-links">
                    <a href="terms.php">Terms of Service</a>
                    <a href="privacy.php">Privacy Policy</a>
                    <a href="cookies.php">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('active');
            });

            // Close mobile menu when clicking on a link
            const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.remove('active');
                });
            });
        }

        // Tab functionality
        const tabs = document.querySelectorAll('.tab');
        const tabContents = document.querySelectorAll('.tab-content');

        function showTab(tabId) {
            // Sab hide karo
            tabs.forEach(tab => tab.classList.remove('active'));
            tabContents.forEach(content => {
                content.classList.remove('active');
                content.style.display = 'none';
            });

            // Sirf selected dikhao
            const targetTab = document.querySelector(`.tab[data-tab="${tabId}"]`);
            const targetContent = document.getElementById(tabId + '-form');

            if (targetTab) targetTab.classList.add('active');
            if (targetContent) {
                targetContent.classList.add('active');
                targetContent.style.display = 'block';
            }
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                const id = this.getAttribute('data-tab');
                showTab(id);
            });
        });

        // Pehla tab default dikhao
        showTab('text');

        // File upload dropzone functionality
        const imageDropzone = document.getElementById('image-dropzone');
        const imageInput = document.getElementById('image');
        const bulkDropzone = document.getElementById('bulk-dropzone');
        const bulkInput = document.getElementById('bulk_files');

        // Image dropzone
        if (imageDropzone && imageInput) {
            imageDropzone.addEventListener('click', () => {
                imageInput.click();
            });

            imageDropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                imageDropzone.style.borderColor = 'var(--primary)';
                imageDropzone.style.background = 'rgba(255, 255, 255, 0.08)';
            });

            imageDropzone.addEventListener('dragleave', () => {
                imageDropzone.style.borderColor = 'rgba(255, 255, 255, 0.2)';
                imageDropzone.style.background = 'rgba(255, 255, 255, 0.05)';
            });

            imageDropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                imageDropzone.style.borderColor = 'rgba(255, 255, 255, 0.2)';
                imageDropzone.style.background = 'rgba(255, 255, 255, 0.05)';
                
                if (e.dataTransfer.files.length) {
                    imageInput.files = e.dataTransfer.files;
                    updateDropzoneText(imageDropzone, e.dataTransfer.files[0].name);
                }
            });

            imageInput.addEventListener('change', () => {
                if (imageInput.files.length) {
                    updateDropzoneText(imageDropzone, imageInput.files[0].name);
                }
            });
        }

        // Bulk dropzone
        if (bulkDropzone && bulkInput) {
            bulkDropzone.addEventListener('click', () => {
                bulkInput.click();
            });

            bulkDropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                bulkDropzone.style.borderColor = 'var(--primary)';
                bulkDropzone.style.background = 'rgba(255, 255, 255, 0.08)';
            });

            bulkDropzone.addEventListener('dragleave', () => {
                bulkDropzone.style.borderColor = 'rgba(255, 255, 255, 0.2)';
                bulkDropzone.style.background = 'rgba(255, 255, 255, 0.05)';
            });

            bulkDropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                bulkDropzone.style.borderColor = 'rgba(255, 255, 255, 0.2)';
                bulkDropzone.style.background = 'rgba(255, 255, 255, 0.05)';
                
                if (e.dataTransfer.files.length) {
                    bulkInput.files = e.dataTransfer.files;
                    updateBulkDropzoneText(bulkDropzone, e.dataTransfer.files);
                }
            });

            bulkInput.addEventListener('change', () => {
                if (bulkInput.files.length) {
                    updateBulkDropzoneText(bulkDropzone, bulkInput.files);
                }
            });
        }

        function updateDropzoneText(dropzone, fileName) {
            const p = dropzone.querySelector('p');
            p.textContent = fileName;
        }

        function updateBulkDropzoneText(dropzone, files) {
            const p = dropzone.querySelector('p');
            if (files.length === 1) {
                p.textContent = files[0].name;
            } else {
                p.textContent = `${files.length} files selected`;
            }
        }

        // Loading indicator
        function showLoading() {
            document.getElementById('loadingIndicator').style.display = 'block';
        }

        // Intersection Observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            // Observe all fade-in elements
            document.querySelectorAll('.fade-in-up').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                observer.observe(el);
            });
        });
    </script>
</body>
</html>