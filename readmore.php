<?php
if (!isset($_GET['id'])) {
    echo "Invalid Request";
    exit;
}

$id = intval($_GET['id']);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'newsadda');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM news WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "News not found";
    exit;
}

$news = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Convert placeholders to actual image tags
function insertImages($content, $optionalImages) {
    if (!is_array($optionalImages) || empty($optionalImages)) {
        return $content; // Return without images if no images are found
    }

    foreach ($optionalImages as $image) {
        $imageTag = "<img src='" . htmlspecialchars($image) . "' alt='Optional Image' style='max-width:100%; margin: 10px 0;'/>
        ";
        $content = preg_replace('/\[image\]/', $imageTag, $content, 1);
    }
    return $content;
}

// Decode optional images from JSON
$optionalImages = json_decode($news['optional_image'], true) ?? [];
$news['content'] = insertImages($news['content'], $optionalImages);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($news['title']); ?></title>
  <link rel="stylesheet" href="readmore.css" />
</head>
<body>
<nav class="navbar">
    <div class="logo">NewsAdda</div>
    <ul class="nav-links">
      <li><a href="index.html">Home</a></li>
      <li><a href="#">About</a></li>
      <li><a href="#">Services</a></li>
      <li><a href="#">Contact</a></li>
      <li><a href="login.php">Login/Signup</a></li>
      <li><a href="pass.html">Admin</a></li>
    </ul>
    <div class="hamburger" id="hamburger">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </nav>
  <div class="news-detail">
    <?php if (!empty($news['banner_image'])): ?>
      <img src="<?php echo htmlspecialchars($news['banner_image']); ?>" alt="Banner Image" />
    <?php endif; ?>

    <h1><?php echo htmlspecialchars($news['title']); ?></h1>
    <p><?php echo nl2br($news['content']); ?></p>
  </div>
</body>
</html>