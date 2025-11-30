<?php
session_start();
$currentPage = 'community';
$pageTitle = 'Community - TruthGuard AI';
// Database connection
require_once '../database.php';

// Fact-checking API functions from scan.php
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
    
    // Method 2: Simple image analysis (fallback)
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

// Method 3: Simple image context analysis (fallback)
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

// Initialize variables
$current_user = null;
$posts = [];
$trending_posts = [];
$leaderboard = [];
$error = '';
$success = '';

// Get current user info
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $current_user = $stmt->fetch();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_post'])) {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $media_type = $_POST['media_type'] ?? 'text';
        $article_url = trim($_POST['article_url'] ?? '');
      
        if (empty($title) || empty($content)) {
            $error = 'Please fill in both title and content.';
        } elseif (!$current_user) {
            $error = 'You must be logged in to create posts.';
        } else {
            try {
                // Handle file upload for images and URLs for articles
                $media_url = null;
                $contentForAPI = $content; // Default content for API
                
                if ($media_type === 'image' && isset($_FILES['media_file']) && $_FILES['media_file']['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = '../uploads/community/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                  
                    $file_extension = pathinfo($_FILES['media_file']['name'], PATHINFO_EXTENSION);
                    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                  
                    if (in_array(strtolower($file_extension), $allowed_extensions)) {
                        $filename = uniqid() . '_' . time() . '.' . $file_extension;
                        $filepath = $upload_dir . $filename;
                      
                        if (move_uploaded_file($_FILES['media_file']['tmp_name'], $filepath)) {
                            $media_url = 'uploads/community/' . $filename;
                            
                            // Extract text from image for API analysis
                            $extractedText = extractTextFromImage($filepath);
                            if (!empty($extractedText) && strlen(trim($extractedText)) > 10) {
                                $contentForAPI = "Text extracted from image: " . $extractedText . "\n\nPost Description: " . $content;
                            } else {
                                $contentForAPI = "Image analysis for file: " . $filename . ". No readable text found in image.\n\nPost Description: " . $content;
                            }
                        } else {
                            $error = 'Failed to upload image.';
                        }
                    } else {
                        $error = 'Invalid file type. Please upload JPG, PNG, GIF, or WebP images only.';
                    }
                } elseif ($media_type === 'article' && !empty($article_url)) {
                    // Validate URL
                    if (filter_var($article_url, FILTER_VALIDATE_URL)) {
                        $media_url = $article_url;
                        // Extract content from URL for API analysis
                        $urlContent = extractUrlContent($article_url);
                        $contentForAPI = "URL Analysis: " . $urlContent . "\n\nPost Description: " . $content;
                    } else {
                        $error = 'Please enter a valid URL for the article.';
                    }
                } else {
                    // For text posts, use the content directly
                    $contentForAPI = $content;
                }
              
                if (!$error) {
                    // Call AI fact-checking API (same as scan.php)
                    $apiResult = callFactCheckAPI($contentForAPI);
                    
                    if ($apiResult && isset($apiResult['final_verdict'])) {
                        $ai_verdict = $apiResult['final_verdict'];
                        $ai_confidence = $apiResult['overall_confidence'];
                        $analysis_reason = $apiResult['detailed_analysis']['claude']['reason'] ?? 'Community post analysis';
                    } else {
                        // Fallback if API fails
                        $ai_verdict = 'uncertain';
                        $ai_confidence = 50;
                        $analysis_reason = 'AI analysis temporarily unavailable';
                    }
                  
                    $stmt = $pdo->prepare("INSERT INTO community_posts (user_id, title, content, media_type, media_url, ai_verdict, ai_confidence, ai_verified, analysis_reason) VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?)");
                    $stmt->execute([$current_user['id'], $title, $content, $media_type, $media_url, $ai_verdict, $ai_confidence, $analysis_reason]);
                  
                    // Update user points (10 points for creating a post)
                    $stmt = $pdo->prepare("UPDATE users SET points = COALESCE(points, 0) + 10 WHERE id = ?");
                    $stmt->execute([$current_user['id']]);
                  
                    $success = 'Your post has been submitted and analyzed by AI! +10 points';
                    // Refresh to show new post
                    header("Location: community.php");
                    exit();
                }
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
  
    if (isset($_POST['add_comment'])) {
        $post_id = $_POST['post_id'] ?? 0;
        $comment_text = trim($_POST['comment_text'] ?? '');
      
        if (empty($comment_text)) {
            $error = 'Please enter a comment.';
        } elseif (!$current_user) {
            $error = 'You must be logged in to comment.';
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO post_comments (post_id, user_id, comment_text) VALUES (?, ?, ?)");
                $stmt->execute([$post_id, $current_user['id'], $comment_text]);
              
                // Update comment count
                $stmt = $pdo->prepare("UPDATE community_posts SET comment_count = comment_count + 1 WHERE id = ?");
                $stmt->execute([$post_id]);
              
                // Update user points (5 points for commenting)
                $stmt = $pdo->prepare("UPDATE users SET points = COALESCE(points, 0) + 5 WHERE id = ?");
                $stmt->execute([$current_user['id']]);
              
                $success = 'Comment added successfully! +5 points';
                header("Location: community.php");
                exit();
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
  
    // FIXED VOTING HANDLER
    if (isset($_POST['vote_type'])) {
        $post_id = $_POST['post_id'] ?? 0;
        $vote_type = $_POST['vote_type'] ?? '';
      
        if (!$current_user) {
            $error = 'You must be logged in to vote.';
        } elseif (empty($post_id) || empty($vote_type)) {
            $error = 'Invalid vote data.';
        } else {
            try {
                // Check if user already voted
                $stmt = $pdo->prepare("SELECT * FROM post_votes WHERE post_id = ? AND user_id = ?");
                $stmt->execute([$post_id, $current_user['id']]);
                $existing_vote = $stmt->fetch();
              
                if ($existing_vote) {
                    // Remove previous vote
                    $remove_field = '';
                    switch ($existing_vote['vote_type']) {
                        case 'upvote': $remove_field = 'upvotes'; break;
                        case 'downvote': $remove_field = 'downvotes'; break;
                        case 'misleading': $remove_field = 'misleading_votes'; break;
                    }
                  
                    if ($remove_field) {
                        $stmt = $pdo->prepare("UPDATE community_posts SET $remove_field = $remove_field - 1 WHERE id = ?");
                        $stmt->execute([$post_id]);
                    }
                  
                    // Delete old vote
                    $stmt = $pdo->prepare("DELETE FROM post_votes WHERE post_id = ? AND user_id = ?");
                    $stmt->execute([$post_id, $current_user['id']]);
                }
              
                // Add new vote
                $add_field = '';
                switch ($vote_type) {
                    case 'upvote': $add_field = 'upvotes'; break;
                    case 'downvote': $add_field = 'downvotes'; break;
                    case 'misleading': $add_field = 'misleading_votes'; break;
                }
              
                if ($add_field) {
                    $stmt = $pdo->prepare("UPDATE community_posts SET $add_field = $add_field + 1 WHERE id = ?");
                    $stmt->execute([$post_id]);
                  
                    $stmt = $pdo->prepare("INSERT INTO post_votes (post_id, user_id, vote_type) VALUES (?, ?, ?)");
                    $stmt->execute([$post_id, $current_user['id'], $vote_type]);
                  
                    // Update user points (2 points for voting)
                    $stmt = $pdo->prepare("UPDATE users SET points = COALESCE(points, 0) + 2 WHERE id = ?");
                    $stmt->execute([$current_user['id']]);
                  
                    $success = 'Vote recorded! +2 points';
                } else {
                    $error = 'Invalid vote type.';
                }
              
                header("Location: community.php");
                exit();
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

// Get posts from database
try {
    $stmt = $pdo->query("
        SELECT cp.*, u.username, u.full_name, u.points as user_points
        FROM community_posts cp
        JOIN users u ON cp.user_id = u.id
        ORDER BY cp.created_at DESC
    ");
    $posts = $stmt->fetchAll();
    // Get comments for each post
    foreach ($posts as &$post) {
        $stmt = $pdo->prepare("
            SELECT pc.*, u.username, u.full_name
            FROM post_comments pc
            JOIN users u ON pc.user_id = u.id
            WHERE pc.post_id = ?
            ORDER BY pc.created_at ASC
        ");
        $stmt->execute([$post['id']]);
        $post['comments'] = $stmt->fetchAll();
      
        // Get user's vote for this post
        if ($current_user) {
            $stmt = $pdo->prepare("SELECT vote_type FROM post_votes WHERE post_id = ? AND user_id = ?");
            $stmt->execute([$post['id'], $current_user['id']]);
            $user_vote = $stmt->fetch();
            $post['user_vote'] = $user_vote ? $user_vote['vote_type'] : null;
        }
    }
    // Get trending posts (most voted)
    $stmt = $pdo->query("
        SELECT cp.*, u.username,
               (cp.upvotes + cp.downvotes + cp.misleading_votes) as total_votes
        FROM community_posts cp
        JOIN users u ON cp.user_id = u.id
        ORDER BY total_votes DESC
        LIMIT 5
    ");
    $trending_posts = $stmt->fetchAll();
    // Get leaderboard (users with most points)
    $stmt = $pdo->query("
        SELECT u.id, u.username, u.full_name,
               COALESCE(u.points, 0) as total_points,
               COUNT(DISTINCT cp.id) as post_count,
               COUNT(DISTINCT pc.id) as comment_count
        FROM users u
        LEFT JOIN community_posts cp ON u.id = cp.user_id
        LEFT JOIN post_comments pc ON u.id = pc.user_id
        GROUP BY u.id, u.username, u.full_name, u.points
        ORDER BY total_points DESC
        LIMIT 5
    ");
    $leaderboard = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Error loading posts: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* CSS Variables / Design System */
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
        html {
            overflow-x: hidden;
            width: 100%;
        }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--background);
            color: var(--foreground);
            line-height: 1.6;
            overflow-x: hidden;
            width: 100%;
            max-width: 100vw;
        }
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1rem;
            width: 100%;
            overflow-x: hidden;
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
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px hsla(189, 94%, 55%, 0.3); }
            50% { box-shadow: 0 0 40px hsla(189, 94%, 55%, 0.6); }
        }
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
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .slide-up {
            animation: slideUp 0.6s ease-out forwards;
        }
        .fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
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
            width: 100%;
            max-width: 100vw;
        }
        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 4rem;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1rem;
            width: 100%;
        }
        @media (min-width: 1024px) {
            .header-content {
                height: 5rem;
                padding: 0 2rem;
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
            width: 100%;
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
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
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
        /* Community Layout */
        .community {
            padding-top: 5rem;
            min-height: 100vh;
            width: 100%;
            overflow-x: hidden;
        }
        @media (min-width: 1024px) {
            .community {
                padding-top: 6rem;
            }
        }
        .community-content {
            padding: 2rem 0;
            width: 100%;
            overflow-x: hidden;
        }
        @media (min-width: 1024px) {
            .community-content {
                padding: 3rem 0;
            }
        }
        .community-grid {
            display: grid;
            gap: 1.5rem;
            width: 100%;
        }
        @media (min-width: 1024px) {
            .community-grid {
                grid-template-columns: 1fr 350px;
                gap: 2rem;
            }
        }
        .community-main {
            display: grid;
            gap: 1.5rem;
            width: 100%;
        }
        .community-sidebar {
            display: grid;
            gap: 1.5rem;
            align-content: start;
            width: 100%;
        }
        /* Community Cards */
        .community-card {
            padding: 1.5rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
            width: 100%;
            overflow: hidden;
        }
        .community-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px hsla(189, 94%, 55%, 0.2);
        }
        .community-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            width: 100%;
        }
        .community-card-title {
            font-size: 1.25rem;
            font-weight: 600;
        }
        .community-card-icon {
            width: 2rem;
            height: 2rem;
            color: var(--primary);
        }
        /* Create Post Card */
        .create-post {
            padding: 1.5rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
            width: 100%;
            overflow: hidden;
        }
        .create-post-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            width: 100%;
        }
        .user-avatar {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }
        .create-post-input {
            flex: 1;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius);
            padding: 1rem;
            color: var(--foreground);
            font-family: inherit;
            font-size: 0.875rem;
            resize: none;
            transition: all 0.3s ease;
            width: 100%;
            min-width: 0;
        }
        .create-post-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px hsla(189, 94%, 55%, 0.2);
        }
        .create-post-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1rem;
            width: 100%;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .create-post-options {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .create-post-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            background: rgba(255, 255, 255, 0.05);
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        .create-post-option.active {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--primary);
        }
        .create-post-option:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .create-post-option svg {
            width: 1.25rem;
            height: 1.25rem;
            color: var(--muted-foreground);
        }
        .file-input {
            display: none;
        }
        .media-input-container {
            display: none;
            margin-top: 0.5rem;
            width: 100%;
        }
        .media-input-container.active {
            display: block;
        }
        /* Post Cards */
        .post {
            padding: 1.5rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
            width: 100%;
            overflow: hidden;
        }
        .post-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1rem;
            width: 100%;
            gap: 1rem;
        }
        .post-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
            min-width: 0;
        }
        .post-user-info {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        .post-username {
            font-weight: 600;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .post-time {
            font-size: 0.75rem;
            color: var(--muted-foreground);
        }
        .post-content {
            margin-bottom: 1.5rem;
            width: 100%;
        }
        .post-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            word-break: break-word;
        }
        .post-text {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            margin-bottom: 1rem;
            word-break: break-word;
        }
        .post-media {
            border-radius: var(--radius);
            overflow: hidden;
            margin-bottom: 1rem;
            width: 100%;
        }
        .post-media img {
            width: 100%;
            height: auto;
            display: block;
            max-width: 100%;
        }
        .post-ai-verdict {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            width: 100%;
        }
        .ai-verdict-real {
            background: rgba(16, 185, 129, 0.2);
            border-left: 4px solid #10b981;
        }
        .ai-verdict-fake {
            background: rgba(239, 68, 68, 0.2);
            border-left: 4px solid #ef4444;
        }
        .ai-verdict-misleading {
            background: rgba(245, 158, 11, 0.2);
            border-left: 4px solid #f59e0b;
        }
        .ai-verdict-unknown {
            background: rgba(107, 114, 128, 0.2);
            border-left: 4px solid #6b7280;
        }
        .ai-verdict-text {
            font-size: 0.875rem;
            font-weight: 600;
        }
        .ai-verdict-confidence {
            font-size: 0.75rem;
            color: var(--muted-foreground);
        }
        /* Fixed AI Verdict Icon Size */
        .post-ai-verdict svg {
            width: 1.5rem;
            height: 1.5rem;
            flex-shrink: 0;
        }
        .post-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            width: 100%;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .vote-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .vote-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            background: rgba(255, 255, 255, 0.05);
            border: none;
            color: var(--foreground);
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        .vote-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .vote-btn.active {
            background: rgba(255, 255, 255, 0.15);
        }
        .vote-btn.real.active {
            background: rgba(16, 185, 129, 0.3);
            color: #10b981;
        }
        .vote-btn.fake.active {
            background: rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }
        .vote-btn.misleading.active {
            background: rgba(245, 158, 11, 0.3);
            color: #f59e0b;
        }
        .vote-btn svg {
            width: 1rem;
            height: 1rem;
        }
        .post-stats {
            display: flex;
            gap: 1rem;
            font-size: 0.75rem;
            color: var(--muted-foreground);
            flex-shrink: 0;
        }
        .post-stat {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .post-stat svg {
            width: 1rem;
            height: 1rem;
        }

        /* IMPROVED COMMENT SECTION STYLES */
        .post-comments {
            margin-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1rem;
            width: 100%;
        }
        .comment {
            display: flex;
            gap: 0.75rem;
            padding: 1rem;
            border-radius: var(--radius);
            background: rgba(255, 255, 255, 0.03);
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
            width: 100%;
        }
        .comment:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        .comment:last-child {
            margin-bottom: 0;
        }
        .comment-content {
            flex: 1;
            min-width: 0;
        }
        .comment-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            flex-wrap: wrap;
        }
        .comment-username {
            font-weight: 600;
            font-size: 0.875rem;
        }
        .comment-time {
            font-size: 0.75rem;
            color: var(--muted-foreground);
        }
        .comment-text {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            line-height: 1.5;
            word-break: break-word;
        }
        .comment-actions {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }
        .comment-action {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            color: var(--muted-foreground);
            background: none;
            border: none;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .comment-action:hover {
            color: var(--primary);
        }
        .comment-action svg {
            width: 1rem;
            height: 1rem;
        }
        .add-comment {
            display: flex;
            gap: 0.75rem;
            margin-top: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: var(--radius);
            width: 100%;
        }
        .comment-input {
            flex: 1;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius);
            padding: 0.75rem;
            color: var(--foreground);
            font-family: inherit;
            font-size: 0.875rem;
            resize: none;
            transition: all 0.3s ease;
            min-height: 60px;
            width: 100%;
        }
        .comment-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px hsla(189, 94%, 55%, 0.2);
        }
        .comment-form {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            width: 100%;
        }
        .comment-submit {
            align-self: flex-end;
        }

        /* AI Verified Badge */
        .ai-verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            flex-shrink: 0;
        }
        .ai-verified-badge svg {
            width: 0.875rem;
            height: 0.875rem;
        }
        /* User Points Badge */
        .points-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            background: rgba(139, 92, 246, 0.2);
            color: #8b5cf6;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            flex-shrink: 0;
        }
        /* Trending Content */
        .trending-list {
            display: grid;
            gap: 1rem;
            width: 100%;
        }
        .trending-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
            width: 100%;
        }
        .trending-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        .trending-rank {
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            border-radius: 50%;
            font-weight: 700;
            font-size: 0.875rem;
            flex-shrink: 0;
        }
        .trending-content {
            flex: 1;
            min-width: 0;
        }
        .trending-title {
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
            word-break: break-word;
        }
        .trending-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.75rem;
            color: var(--muted-foreground);
            flex-wrap: wrap;
        }
        .trending-votes {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .trending-votes svg {
            width: 0.875rem;
            height: 0.875rem;
            color: var(--primary);
        }
        .verdict-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            flex-shrink: 0;
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
        /* Leaderboard */
        .leaderboard-list {
            display: grid;
            gap: 1rem;
            width: 100%;
        }
        .leaderboard-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
            width: 100%;
        }
        .leaderboard-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        .leaderboard-rank {
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            border-radius: 50%;
            font-weight: 700;
            font-size: 0.875rem;
            flex-shrink: 0;
        }
        .leaderboard-avatar {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }
        .leaderboard-info {
            flex: 1;
            min-width: 0;
        }
        .leaderboard-name {
            font-weight: 600;
            font-size: 0.875rem;
            word-break: break-word;
        }
        .leaderboard-stats {
            display: flex;
            gap: 1rem;
            font-size: 0.75rem;
            color: var(--muted-foreground);
            flex-wrap: wrap;
        }
        .leaderboard-score {
            font-weight: 700;
            color: var(--primary);
        }
        /* Modal */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            width: 100vw;
            height: 100vh;
        }
        .modal.active {
            opacity: 1;
            visibility: visible;
        }
        .modal-content {
            width: 90%;
            max-width: 500px;
            background: var(--card);
            border-radius: var(--radius);
            padding: 2rem;
            transform: translateY(20px);
            transition: transform 0.3s ease;
            margin: 1rem;
        }
        .modal.active .modal-content {
            transform: translateY(0);
        }
        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            width: 100%;
        }
        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
        }
        .modal-close {
            background: none;
            border: none;
            color: var(--muted-foreground);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        .modal-close:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--foreground);
        }
        .modal-close svg {
            width: 1.25rem;
            height: 1.25rem;
        }
        .report-options {
            display: grid;
            gap: 1rem;
            margin-bottom: 1.5rem;
            width: 100%;
        }
        .report-option {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            border-radius: var(--radius);
            background: rgba(255, 255, 255, 0.05);
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }
        .report-option:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .report-option input {
            display: none;
        }
        .report-option span {
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 50%;
            border: 2px solid var(--muted-foreground);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        .report-option input:checked + span {
            border-color: var(--primary);
            background: var(--primary);
        }
        .report-option input:checked + span::after {
            content: '';
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            background: white;
        }
        .report-option label {
            flex: 1;
            font-size: 0.875rem;
            cursor: pointer;
        }
        .report-textarea {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius);
            padding: 1rem;
            color: var(--foreground);
            font-family: inherit;
            font-size: 0.875rem;
            resize: none;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        .report-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px hsla(189, 94%, 55%, 0.2);
        }
        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            flex-wrap: wrap;
        }
        /* Notifications */
        .notification {
            position: fixed;
            top: 6rem;
            right: 1rem;
            padding: 1rem 1.5rem;
            border-radius: var(--radius);
            font-weight: 500;
            z-index: 3000;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            max-width: calc(100vw - 2rem);
        }
        .notification.show {
            transform: translateX(0);
        }
        .notification.success {
            background: rgba(16, 185, 129, 0.9);
            color: white;
        }
        .notification.error {
            background: rgba(239, 68, 68, 0.9);
            color: white;
        }
        /* Footer */
        .footer {
            background: rgba(255, 255, 255, 0.03);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 3rem 0;
            width: 100%;
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
            width: 100%;
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
            width: 100%;
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
    </style>
</head>
<body>
    <!-- Notifications -->
    <?php if ($success): ?>
    <div class="notification success show" id="successNotification">
        <?php echo htmlspecialchars($success); ?>
    </div>
    <?php endif; ?>
    <?php if ($error): ?>
    <div class="notification error show" id="errorNotification">
        <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>
    
     <?php include 'header.php'; ?>

    <!-- Community -->
    <section class="community">
        <div class="container">
            <div class="community-content">
                <div class="community-grid">
                    <div class="community-main">
                        <!-- Create Post -->
                        <?php if ($current_user): ?>
                        <div class="create-post glass-strong fade-in-up">
                            <form method="POST" class="create-post-form" enctype="multipart/form-data">
                                <div class="create-post-header">
                                    <div class="user-avatar"><?php echo substr($current_user['full_name'], 0, 2); ?></div>
                                    <div style="flex: 1; display: flex; flex-direction: column; gap: 0.5rem; min-width: 0;">
                                        <input type="text" name="title" class="create-post-input" placeholder="Post title..." required value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                                        <textarea name="content" class="create-post-input" placeholder="Share content for community verification..." required rows="3"><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
                                        
                                        <!-- Media Inputs -->
                                        <div id="image-input-container" class="media-input-container">
                                            <input type="file" name="media_file" id="media_file" class="file-input" accept="image/*">
                                            <label for="media_file" class="btn btn-outline btn-sm" style="cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                    <polyline points="21 15 16 10 5 21"></polyline>
                                                </svg>
                                                Choose Image
                                            </label>
                                            <span id="file-name" style="font-size: 0.75rem; color: var(--muted-foreground); margin-left: 0.5rem;"></span>
                                        </div>
                                        
                                        <div id="article-input-container" class="media-input-container">
                                            <input type="url" name="article_url" id="article_url" class="create-post-input" placeholder="Enter article URL..." value="<?php echo htmlspecialchars($_POST['article_url'] ?? ''); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="create-post-actions">
                                    <div class="create-post-options">
                                        <div class="create-post-option active" data-type="text">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                                <polyline points="10 9 9 9 8 9"></polyline>
                                            </svg>
                                            Text
                                        </div>
                                        <div class="create-post-option" data-type="image">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                <polyline points="21 15 16 10 5 21"></polyline>
                                            </svg>
                                            Image
                                        </div>
                                        <div class="create-post-option" data-type="article">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                            </svg>
                                            Article
                                        </div>
                                    </div>
                                    <input type="hidden" name="media_type" value="text" id="media_type">
                                    <button type="submit" name="create_post" class="btn btn-primary">Post</button>
                                </div>
                            </form>
                        </div>
                        <?php else: ?>
                        <div class="create-post glass-strong fade-in-up">
                            <p style="text-align: center; color: var(--muted-foreground);">
                                Please <a href="../login.php" style="color: var(--primary);">login</a> to create posts and engage with the community.
                            </p>
                        </div>
                        <?php endif; ?>
                        <!-- Community Posts -->
                        <div class="community-card glass-strong fade-in-up delay-1">
                            <div class="community-card-header">
                                <h2 class="community-card-title">Recent Community Posts</h2>
                                <svg class="community-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <!-- Posts from Database -->
                            <?php if (empty($posts)): ?>
                            <div class="post glass fade-in-up">
                                <div class="post-content">
                                    <p style="text-align: center; color: var(--muted-foreground);">
                                        No posts yet. Be the first to create a post!
                                    </p>
                                </div>
                            </div>
                            <?php else: ?>
                                <?php foreach ($posts as $post): ?>
                                <div class="post glass fade-in-up">
                                    <div class="post-header">
                                        <div class="post-user">
                                            <div class="user-avatar"><?php echo substr($post['full_name'], 0, 2); ?></div>
                                            <div class="post-user-info">
                                                <div class="post-username">
                                                    <?php echo htmlspecialchars($post['full_name']); ?>
                                                    <?php if ($post['ai_verified']): ?>
                                                    <span class="ai-verified-badge" title="AI Verified">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                                        </svg>
                                                        AI Verified
                                                    </span>
                                                    <?php endif; ?>
                                                    <?php if ($post['user_points'] > 0): ?>
                                                    <span class="points-badge" title="User Points">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                        </svg>
                                                        <?php echo $post['user_points']; ?> pts
                                                    </span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="post-time"><?php echo date('M j, Y g:i A', strtotime($post['created_at'])); ?></div>
                                            </div>
                                        </div>
                                        <?php if ($current_user): ?>
                                        <button class="btn btn-sm btn-outline report-btn" data-post-id="<?php echo $post['id']; ?>">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                            </svg>
                                            Report
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                    <div class="post-content">
                                        <h3 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                                        <p class="post-text"><?php echo htmlspecialchars($post['content']); ?></p>
                                        <?php if ($post['media_url']): ?>
                                        <div class="post-media">
                                            <?php if ($post['media_type'] === 'image'): ?>
                                            <img src="../<?php echo htmlspecialchars($post['media_url']); ?>" alt="Post image" onerror="this.style.display='none'">
                                            <?php elseif ($post['media_type'] === 'article'): ?>
                                            <div style="padding: 1rem; background: rgba(255,255,255,0.05); border-radius: var(--radius);">
                                                <a href="<?php echo htmlspecialchars($post['media_url']); ?>" target="_blank" style="color: var(--primary); text-decoration: none;">
                                                    <strong>📰 Article Link:</strong> <?php echo htmlspecialchars($post['media_url']); ?>
                                                </a>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($post['ai_verdict'] != 'unknown'): ?>
                                        <div class="post-ai-verdict ai-verdict-<?php echo $post['ai_verdict']; ?>">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <?php if ($post['ai_verdict'] == 'real'): ?>
                                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                                <?php elseif ($post['ai_verdict'] == 'fake'): ?>
                                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                                <?php else: ?>
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                                <?php endif; ?>
                                            </svg>
                                            <div>
                                                <div class="ai-verdict-text">AI Verdict: <?php echo strtoupper($post['ai_verdict']); ?></div>
                                                <div class="ai-verdict-confidence"><?php echo $post['ai_confidence']; ?>% confidence</div>
                                                <?php if (isset($post['analysis_reason'])): ?>
                                                <div class="ai-verdict-confidence" style="margin-top: 0.25rem; font-style: italic;">
                                                    <?php echo htmlspecialchars($post['analysis_reason']); ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="post-actions">
                                        <?php if ($current_user): ?>
                                        <div class="vote-actions">
                                            <!-- FIXED VOTING FORM -->
                                            <form method="POST" class="vote-form" data-post-id="<?php echo $post['id']; ?>">
                                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                                <input type="hidden" name="vote_type" value="">
                                                <button type="button" class="vote-btn real <?php echo ($post['user_vote'] ?? '') === 'upvote' ? 'active' : ''; ?>" data-vote-type="upvote">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                                                    </svg>
                                                    Real (<?php echo $post['upvotes']; ?>)
                                                </button>
                                                <button type="button" class="vote-btn fake <?php echo ($post['user_vote'] ?? '') === 'downvote' ? 'active' : ''; ?>" data-vote-type="downvote">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"></path>
                                                    </svg>
                                                    Fake (<?php echo $post['downvotes']; ?>)
                                                </button>
                                                <button type="button" class="vote-btn misleading <?php echo ($post['user_vote'] ?? '') === 'misleading' ? 'active' : ''; ?>" data-vote-type="misleading">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                                    </svg>
                                                    Misleading (<?php echo $post['misleading_votes']; ?>)
                                                </button>
                                            </form>
                                        </div>
                                        <?php else: ?>
                                        <div class="vote-actions">
                                            <button class="vote-btn real" onclick="showLoginAlert()">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                                                </svg>
                                                Real (<?php echo $post['upvotes']; ?>)
                                            </button>
                                            <button class="vote-btn fake" onclick="showLoginAlert()">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"></path>
                                                </svg>
                                                Fake (<?php echo $post['downvotes']; ?>)
                                            </button>
                                            <button class="vote-btn misleading" onclick="showLoginAlert()">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                                </svg>
                                                Misleading (<?php echo $post['misleading_votes']; ?>)
                                            </button>
                                        </div>
                                        <?php endif; ?>
                                        <div class="post-stats">
                                            <div class="post-stat">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                                                </svg>
                                                <?php echo $post['comment_count']; ?>
                                            </div>
                                            <div class="post-stat">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M4 12v7a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-7"></path>
                                                    <polyline points="16 6 12 2 8 6"></polyline>
                                                    <line x1="12" y1="2" x2="12" y2="15"></line>
                                                </svg>
                                                <?php echo $post['share_count']; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="post-comments">
                                        <?php foreach ($post['comments'] as $comment): ?>
                                        <div class="comment">
                                            <div class="user-avatar" style="width: 2.5rem; height: 2.5rem; font-size: 0.75rem;">
                                                <?php echo substr($comment['full_name'], 0, 2); ?>
                                            </div>
                                            <div class="comment-content">
                                                <div class="comment-header">
                                                    <div class="comment-username"><?php echo htmlspecialchars($comment['full_name']); ?></div>
                                                    <div class="comment-time"><?php echo date('M j, Y g:i A', strtotime($comment['created_at'])); ?></div>
                                                </div>
                                                <div class="comment-text"><?php echo htmlspecialchars($comment['comment_text']); ?></div>
                                                <div class="comment-actions">
                                                    <button class="comment-action">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                                                        </svg>
                                                        <?php echo $comment['upvotes']; ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                      
                                        <?php if ($current_user): ?>
                                        <div class="add-comment">
                                            <div class="user-avatar" style="width: 2.5rem; height: 2.5rem; font-size: 0.75rem;">
                                                <?php echo substr($current_user['full_name'], 0, 2); ?>
                                            </div>
                                            <form method="POST" class="comment-form">
                                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                                <textarea name="comment_text" class="comment-input" placeholder="Add a comment..." required></textarea>
                                                <button type="submit" name="add_comment" class="btn btn-primary btn-sm comment-submit">Post Comment</button>
                                            </form>
                                        </div>
                                        <?php else: ?>
                                        <div class="add-comment">
                                            <p style="color: var(--muted-foreground); font-size: 0.875rem; text-align: center; width: 100%;">
                                                <a href="../login.php" style="color: var(--primary);">Login</a> to add a comment
                                            </p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="community-sidebar">
                        <!-- Trending Content -->
                        <div class="community-card glass-strong fade-in-up delay-2">
                            <div class="community-card-header">
                                <h2 class="community-card-title">Trending Content</h2>
                                <svg class="community-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                </svg>
                            </div>
                            <div class="trending-list">
                                <?php foreach ($trending_posts as $index => $trending): ?>
                                <div class="trending-item glass">
                                    <div class="trending-rank"><?php echo $index + 1; ?></div>
                                    <div class="trending-content">
                                        <div class="trending-title"><?php echo htmlspecialchars($trending['title']); ?></div>
                                        <div class="trending-meta">
                                            <div class="trending-votes">
                                                <svg viewBox="0 0 24 24" fill="currentColor">
                                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                </svg>
                                                <?php echo $trending['total_votes']; ?>
                                            </div>
                                            <span class="verdict-badge verdict-<?php echo $trending['ai_verdict']; ?>">
                                                <?php echo strtoupper($trending['ai_verdict']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <!-- Leaderboard -->
                        <div class="community-card glass-strong fade-in-up delay-3">
                            <div class="community-card-header">
                                <h2 class="community-card-title">Top Contributors</h2>
                                <svg class="community-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <path d="M20 8v6M23 11h-6"></path>
                                </svg>
                            </div>
                            <div class="leaderboard-list">
                                <?php foreach ($leaderboard as $index => $user): ?>
                                <div class="leaderboard-item glass">
                                    <div class="leaderboard-rank"><?php echo $index + 1; ?></div>
                                    <div class="leaderboard-avatar"><?php echo substr($user['full_name'], 0, 2); ?></div>
                                    <div class="leaderboard-info">
                                        <div class="leaderboard-name"><?php echo htmlspecialchars($user['full_name']); ?></div>
                                        <div class="leaderboard-stats">
                                            <span class="leaderboard-score"><?php echo $user['total_points'] ?? 0; ?> pts</span>
                                            <span><?php echo $user['post_count']; ?> posts</span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <!-- Community Guidelines -->
                        <div class="community-card glass-strong fade-in-up delay-4">
                            <div class="community-card-header">
                                <h2 class="community-card-title">Community Guidelines</h2>
                                <svg class="community-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                            </div>
                            <ul style="font-size: 0.875rem; color: var(--muted-foreground); padding-left: 1.25rem;">
                                <li style="margin-bottom: 0.5rem;">Be respectful to other members</li>
                                <li style="margin-bottom: 0.5rem;">Provide evidence when possible</li>
                                <li style="margin-bottom: 0.5rem;">Report suspicious content</li>
                                <li style="margin-bottom: 0.5rem;">Vote based on facts, not opinions</li>
                                <li>Help maintain a trustworthy community</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Report Modal -->
    <?php if ($current_user): ?>
    <div class="modal" id="reportModal">
        <div class="modal-content glass-strong">
            <div class="modal-header">
                <h3 class="modal-title">Report Content</h3>
                <button class="modal-close" id="closeReportModal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <form id="reportForm">
                <div class="report-options">
                    <div class="report-option">
                        <input type="radio" id="spam" name="report_type" value="spam">
                        <span></span>
                        <label for="spam">Spam or misleading</label>
                    </div>
                    <div class="report-option">
                        <input type="radio" id="harmful" name="report_type" value="harmful">
                        <span></span>
                        <label for="harmful">Harmful or dangerous content</label>
                    </div>
                    <div class="report-option">
                        <input type="radio" id="harassment" name="report_type" value="harassment">
                        <span></span>
                        <label for="harassment">Harassment or bullying</label>
                    </div>
                    <div class="report-option">
                        <input type="radio" id="false-info" name="report_type" value="false-info">
                        <span></span>
                        <label for="false-info">False information</label>
                    </div>
                    <div class="report-option">
                        <input type="radio" id="other" name="report_type" value="other">
                        <span></span>
                        <label for="other">Other</label>
                    </div>
                </div>
                <textarea class="report-textarea" name="description" placeholder="Please provide additional details (optional)"></textarea>
                <div class="modal-actions">
                    <button type="button" class="btn btn-outline" id="cancelReport">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
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
        // Notifications auto-hide
        setTimeout(() => {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
                if (notification) {
                    notification.classList.remove('show');
                    setTimeout(() => notification.remove(), 300);
                }
            });
        }, 5000);

        // Media type selection
        function initializeMediaTabs() {
            const mediaOptions = document.querySelectorAll('.create-post-option');
            const imageInputContainer = document.getElementById('image-input-container');
            const articleInputContainer = document.getElementById('article-input-container');
            const mediaTypeInput = document.getElementById('media_type');
            
            function updateMediaInputs(selectedType) {
                // Hide all inputs first
                if (imageInputContainer) imageInputContainer.classList.remove('active');
                if (articleInputContainer) articleInputContainer.classList.remove('active');
                
                // Show appropriate input based on type
                if (selectedType === 'image' && imageInputContainer) {
                    imageInputContainer.classList.add('active');
                } else if (selectedType === 'article' && articleInputContainer) {
                    articleInputContainer.classList.add('active');
                }
                // For 'text' type, both remain hidden
                
                // Update active state of options
                mediaOptions.forEach(option => {
                    option.classList.remove('active');
                });
                
                // Find and activate the selected option
                const selectedOption = document.querySelector(`[data-type="${selectedType}"]`);
                if (selectedOption) {
                    selectedOption.classList.add('active');
                }
                
                // Update hidden input
                if (mediaTypeInput) {
                    mediaTypeInput.value = selectedType;
                }
            }
            
            // Add click event listeners to media options
            mediaOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const type = this.getAttribute('data-type');
                    updateMediaInputs(type);
                });
            });
            
            // Initialize with text type
            updateMediaInputs('text');
        }

        // File input display
        function initializeFileInput() {
            const mediaFileInput = document.getElementById('media_file');
            const fileNameDisplay = document.getElementById('file-name');
            
            if (mediaFileInput && fileNameDisplay) {
                mediaFileInput.addEventListener('change', function(e) {
                    const fileName = e.target.files[0] ? e.target.files[0].name : '';
                    fileNameDisplay.textContent = fileName;
                });
            }
        }

        // Report Modal functionality
        function initializeReportModal() {
            const reportModal = document.getElementById('reportModal');
            const closeReportModal = document.getElementById('closeReportModal');
            const cancelReport = document.getElementById('cancelReport');
            const reportForm = document.getElementById('reportForm');
            let currentPostId = null;
            
            if (!reportModal) return;
            
            // Add event listeners to report buttons
            document.querySelectorAll('.report-btn').forEach(button => {
                button.addEventListener('click', function() {
                    currentPostId = this.getAttribute('data-post-id');
                    reportModal.classList.add('active');
                });
            });

            function closeReportModalFunc() {
                reportModal.classList.remove('active');
                if (reportForm) reportForm.reset();
            }

            if (closeReportModal) {
                closeReportModal.addEventListener('click', closeReportModalFunc);
            }
            
            if (cancelReport) {
                cancelReport.addEventListener('click', closeReportModalFunc);
            }

            if (reportForm) {
                reportForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                  
                    const formData = new FormData(this);
                    const reportData = {
                        post_id: currentPostId,
                        report_type: formData.get('report_type'),
                        description: formData.get('description')
                    };
                    
                    if (!reportData.report_type) {
                        alert('Please select a report reason.');
                        return;
                    }
                    
                    // For demo purposes, just show success message
                    alert('Thank you for your report. Our team will review it shortly.');
                    closeReportModalFunc();
                });
            }

            reportModal.addEventListener('click', function(e) {
                if (e.target === reportModal) {
                    closeReportModalFunc();
                }
            });
        }

        // Mobile Menu functionality
        function initializeMobileMenu() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            
            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', () => {
                    mobileMenu.classList.toggle('active');
                });
                
                const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
                mobileNavLinks.forEach(link => {
                    link.addEventListener('click', () => {
                        mobileMenu.classList.remove('active');
                    });
                });
            }
        }

        // Animations
        function initializeAnimations() {
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

            document.querySelectorAll('.fade-in-up').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                observer.observe(el);
            });
        }

        // Login alert for non-logged in users
        function showLoginAlert() {
            alert('Please login to vote on posts.');
        }

        // FIXED VOTING FUNCTIONALITY
        function initializeVoting() {
            document.querySelectorAll('.vote-form').forEach(form => {
                const voteButtons = form.querySelectorAll('.vote-btn[data-vote-type]');
                const voteTypeInput = form.querySelector('input[name="vote_type"]');
                const postId = form.getAttribute('data-post-id');
                
                voteButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const voteType = this.getAttribute('data-vote-type');
                        
                        // Update the hidden input
                        voteTypeInput.value = voteType;
                        
                        // Remove active class from all buttons in this form
                        voteButtons.forEach(btn => {
                            btn.classList.remove('active');
                        });
                        
                        // Add active class to clicked button
                        this.classList.add('active');
                        
                        // Submit the form
                        form.submit();
                    });
                });
            });
        }

        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeMediaTabs();
            initializeFileInput();
            initializeReportModal();
            initializeMobileMenu();
            initializeAnimations();
            initializeVoting(); // Initialize voting functionality
        });
    </script>
</body>
</html>