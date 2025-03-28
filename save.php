<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'newsadda');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle Banner Upload
    $bannerImagePath = '';
    if (!empty($_FILES['banner']['name'])) {
        $bannerImagePath = 'uploads/' . basename($_FILES['banner']['name']);
        move_uploaded_file($_FILES['banner']['tmp_name'], $bannerImagePath);
    }

    // Get Title and Content
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);

    // Handle Optional Images
    $optionalImages = [];
    if (!empty($_FILES['optionalImages']['name'][0])) {
        foreach ($_FILES['optionalImages']['name'] as $key => $imageName) {
            $imagePath = 'uploads/' . basename($imageName);
            if (move_uploaded_file($_FILES['optionalImages']['tmp_name'][$key], $imagePath)) {
                $optionalImages[] = $imagePath;
            }
        }
    }

    $optionalImagesJson = json_encode($optionalImages);

    // Insert into Database
    $sql = "INSERT INTO news (title, content, banner_image, optional_image) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $title, $content, $bannerImagePath, $optionalImagesJson);

    if ($stmt->execute()) {
        echo "Content uploaded successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
