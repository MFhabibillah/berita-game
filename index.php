<?php
include 'config/database.php';
session_start();

// Fetch categories
$categoriesQuery = "SELECT * FROM Categories";
$categoriesStmt = $pdo->prepare($categoriesQuery);
$categoriesStmt->execute();
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch tags
$tagsQuery = "SELECT * FROM Tags";
$tagsStmt = $pdo->prepare($tagsQuery);
$tagsStmt->execute();
$tags = $tagsStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch news
$newsQuery = "SELECT News.*, Files.file_path FROM news  LEFT JOIN Files ON News.cover_image_id = Files.file_id";
$newsStmt = $pdo->prepare($newsQuery);
$newsStmt->execute();
$news = $newsStmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-4 col-md-5 mb-4">
            <!-- About This Website Widget -->
            <div class="card mb-4" style="background-color: #333; color: white;">
                <div class="card-header h5" style="color: #BED754;">
                    ABOUT THIS WEBSITE
                </div>
                <div class="card-body">
                    <p>Welcome to Gamer Society, your ultimate portal for all things gaming! Our website is dedicated to bringing you the latest news, reviews,
                        and insights from the world of video games. Whether you're a casual gamer or a hardcore enthusiast,
                        Gamer Society is your go-to source for up-to-date information on game releases, industry trends,
                        and exclusive interviews with game developers and industry experts.
                    </p>
                    <p>At Gamer Society, we are passionate about the gaming community and strive to create a platform
                        where gamers can come together to stay informed and engage in thoughtful discussions. Our team of experienced writers and gaming
                        aficionados work tirelessly to deliver high-quality content that is both informative and entertaining. Join us on this exciting journey
                        through the ever-evolving landscape of gaming, and become a part of the Gamer Society!</p>
                </div>
            </div>

            <!-- Categories Widget -->
            <div class="card mb-4" style="background-color: #333; color: white;">
                <div class="card-header" style="color: #BED754;">
                    Categories
                </div>
                <ul class="list-group list-group-flush" style="background-color: #333; color: white;">
                    <?php foreach ($categories as $category): ?>
                        <li class="list-group-item" style="background-color: #333; color: white;">
                            <?php echo $category['category_name']; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Tags Widget -->
            <div class="card mb-4" style="background-color: #333; color: white;">
                <div class="card-header" style="color: #BED754;">
                    Tags
                </div>
                <ul class="list-group list-group-flush" style="background-color: #333; color: white;">
                    <?php foreach ($tags as $tag): ?>
                        <li class="list-group-item" style="background-color: #333; color: white;">
                            <?php echo $tag['tag_name']; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8 col-md-7">
            <!-- Hero Section for the Latest News -->
            <?php foreach ($news as $article): ?>
                <?php $latestNews = array_shift($news); ?>
                <div class="card mb-4" style="background-color: #333; color: white;">
                    <div class="card-img-top img-fluid" alt="..."
                        style="height: 360px; width: 100%; background-size: cover; background-image: url('/gamersociety/uploads/<?php echo $latestNews['file_path']; ?>');">
                    </div>
                    <div class="card-body">
                        <h3 class="card-title" style="color: #BED754;"><?php echo $latestNews['title']; ?></h3>
                        <p class="card-text"><?php echo substr($latestNews['content'], 0, 200); ?>...</p>
                        <div class="text-end">
                            <a href="views/article_detail.php?id=<?php echo $latestNews['news_id']; ?>"
                                class="btn btn-warning">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
