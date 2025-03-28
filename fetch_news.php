<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "newsadda";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM news ORDER BY created_at DESC";
$result = $conn->query($sql);

$newsData = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $newsData[] = $row;
    }
    echo json_encode($newsData);
} else {
    echo json_encode([]);
}

$conn->close();
?>
