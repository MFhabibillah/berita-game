<?php
session_start();
include '../config/database.php';  // Menggunakan jalur relatif ke folder config

$news_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Fetch news article data
$stmt = $pdo->prepare("SELECT n.*, c.category_name, a.bio, u.username, u.email, f.file_path
                        FROM News n
                        LEFT JOIN Categories c ON n.category_id = c.category_id
                        LEFT JOIN Authors a ON n.author_id = a.author_id
                        LEFT JOIN Users u ON a.user_id = u.user_id
                        LEFT JOIN Files f ON f.file_id = n.cover_image_id
                        WHERE n.news_id = :news_id");
$stmt->bindParam(':news_id', $news_id);
$stmt->execute();
$news = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$news) {
    echo "Article not found.";
    exit();
}

// Fetch tags
$stmt = $pdo->prepare("SELECT t.tag_name 
                        FROM Tags t
                        LEFT JOIN News_Tags nt ON t.tag_id = nt.tag_id
                        WHERE nt.news_id = :news_id");
$stmt->bindParam(':news_id', $news_id);
$stmt->execute();
$tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch comments
$stmt = $pdo->prepare("SELECT c.*, u.username 
                        FROM Comments c
                        LEFT JOIN Users u ON c.user_id = u.user_id
                        WHERE c.news_id = :news_id");
$stmt->bindParam(':news_id', $news_id);
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle new comment submission
if (isset($_POST['submit_comment'])) {
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("INSERT INTO Comments (news_id, user_id, comment) VALUES (:news_id, :user_id, :comment)");
    $stmt->bindParam(':news_id', $news_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':comment', $comment);
    $stmt->execute();
    header("Location: article_detail.php?id=$news_id");
    exit();
}

// Handle rating submission
if (isset($_POST['submit_rating'])) {
    $rating = $_POST['rating'];
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("INSERT INTO Ratings (news_id, user_id, rating) VALUES (:news_id, :user_id, :rating)");
    $stmt->bindParam(':news_id', $news_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':rating', $rating);
    $stmt->execute();
    header("Location: article_detail.php?id=$news_id");
    exit();
}

$stmt = $pdo->prepare("select * from ratings where news_id = :id");
$stmt->bindParam(':id', $news_id);
$stmt->execute();
$ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);
$avgRating = 0;

foreach ($ratings as $rating) {
    $avgRating = $avgRating + $rating['rating'];
}

if($avgRating != 0){
    $avgRating = $avgRating / count($ratings);
}


?>
    <?php include '../includes/header.php'; ?>

    <style>
        .rating {
            display: inline-block;
            font-size: 0;
            direction: rtl;
        }

        .rating > input {
            display: none;
        }

        .rating > label {
            font-size: 2rem;
            cursor: pointer;
            color: #ddd;
            padding: 0 0.1em;
        }

        .rating > input:checked ~ label,
        .rating:not(:checked) > label:hover,
        .rating:not(:checked) > label:hover ~ label {
            color: gold;
        }

        .rating > input:checked + label:hover,
        .rating > input:checked ~ label:hover,
        .rating > label:hover ~ input:checked ~ label,
        .rating > input:checked ~ label:hover ~ label {
            color: gold;
        }
    </style>
    <div class="container mt-5">
        <div class="card" style="background-color: #333; color: white;">
            <div class="card-header" style="color: #BED754;">
                <h1><?php echo htmlspecialchars($news['title']); ?></h1>
                <a href="/gamersociety/uploads/<?php echo htmlspecialchars($news['file_path']); ?>" download class="btn btn-secondary">Download this Image</a>
            </div>
            <div class="card-body">
                <?php if ($news['file_path']): ?>
                    <img src="/gamersociety/uploads/<?php echo htmlspecialchars($news['file_path']); ?>" alt="Cover Image" class="img-fluid mb-3">
                <?php endif; ?>
                <?php echo $news['content']; ?>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($news['category_name']); ?></p>
                <p><strong>Tags:</strong>
                    <?php foreach ($tags as $tag): ?>
                        <span class="badge badge-info"><?php echo htmlspecialchars($tag['tag_name']); ?></span>
                    <?php endforeach; ?>
                </p>
                <p class="mt-3"><strong>Penulis:</strong> <?php echo htmlspecialchars($news['username']); ?> | <?php echo htmlspecialchars($news['email']); ?><br> <?php echo htmlspecialchars($news['bio']); ?></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-7">
        <!-- Rating Section -->
        <div class="card mt-4" style="background-color: #333; color: white;">
            <div class="card-header" style="color: #BED754;">
                <h2>Rate this article</h2>
                <div class="card-title">Average Rating <?php echo $avgRating ?> 
                <?php  
                    for ($i=0; $i < $avgRating; $i++) { 
                        echo "🌟";
                    }
                ?>
            </div>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form method="post">
                        <div class="form-group">
                        <div class="rating">
                            <input type="radio" id="star5" name="rating" value="5" /><label for="star5" title="5 stars">★</label>
                            <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 stars">★</label>
                            <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 stars">★</label>
                            <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 stars">★</label>
                            <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 star">★</label>
                        </div>
                        </div>
                        <button type="submit" class="btn btn-warning" name="submit_rating">Submit</button>
                    </form>
                <?php else: ?>
                    <p><a href="../../index.php">Login</a> to rate this article.</p>
                <?php endif; ?>
            </div>
        </div>
        </div>

        <!-- Comment Section -->
         <div class="col-lg-6 col-md-7">
        <div class="card mt-4" style="background-color: #333; color: white;">
            <div class="card-header" style="color: #BED754;">
                <h2>Comments</h2>
            </div>
            <div class="card-body">
                <?php foreach ($comments as $comment): ?>
                    <div class="border p-3 mb-3">
                        <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                        <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                    </div>
                <?php endforeach; ?>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <form method="post">
                        <div class="form-group">
                            <label for="comment">Add a comment:</label>
                            <textarea class="form-control" name="comment" id="comment" rows="3" style="border: 1px solid white; background-color: #333; color: white;" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning" name="submit_comment">Submit</button>
                    </form>
                <?php else: ?>
                    <p><a href="../../index.php">Login</a> to add a comment.</p>
                <?php endif; ?>
            </div>
        </div>
        </div>

        </div>

       </div>
    
    <?php include '../includes/footer.php'; ?>

