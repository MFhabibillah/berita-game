<?php
session_start();
include '../../config/database.php';

// Check if the user is an author
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'author') {
    header("Location: ../../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['update_article'])) {
    $article_id = $_POST['article_id'];
    $title = $_POST['edit_title'];
    $content = $_POST['edit_content'];
    $category_id = $_POST['edit_category_id'];
    $status = $_POST['edit_status'];

    // Check if cover image is uploaded
    $cover_image_id = null;
    if (isset($_FILES['edit_cover_image']) && $_FILES['edit_cover_image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['edit_cover_image']['tmp_name'];
        $file_name = $_FILES['edit_cover_image']['name'];
        $destination = '../../uploads/' . $file_name;

        if (move_uploaded_file($file_tmp_path, $destination)) {
            // Insert file record
            $stmt = $pdo->prepare("INSERT INTO Files (user_id, file_path) VALUES (:user_id, :file_path)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':file_path', $file_name);
            $stmt->execute();
            $cover_image_id = $pdo->lastInsertId();
        }
    }

    // Update news record
    if ($cover_image_id !== null) {
        // If cover image was uploaded
        $stmt = $pdo->prepare("UPDATE News SET title = :title, content = :content, category_id = :category_id, status = :status, cover_image_id = :cover_image_id WHERE news_id = :news_id AND author_id = :author_id");
        $stmt->bindParam(':cover_image_id', $cover_image_id);
    } else {
        // If cover image was not uploaded
        $stmt = $pdo->prepare("UPDATE News SET title = :title, content = :content, category_id = :category_id, status = :status WHERE news_id = :news_id AND author_id = :author_id");
    }

    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':news_id', $article_id);
    $stmt->bindParam(':author_id', $user_id);
    $stmt->execute();

    header("Location: ../../views/author/dashboard.php");
    exit();
}

// Handle multi-update for article status
if (isset($_POST['article_action']) && $_POST['article_action'] == 'multi_update') {
    if (isset($_POST['article_ids']) && is_array($_POST['article_ids'])) {
        foreach ($_POST['article_ids'] as $article_id) {
            $status = $_POST['status_' . $article_id];
            $stmt = $pdo->prepare("UPDATE News SET status = :status WHERE news_id = :news_id");
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':news_id', $article_id);
            $stmt->execute();
        }
    }
    header("Location: ../../views/author/dashboard.php");
    exit();
}
?>
