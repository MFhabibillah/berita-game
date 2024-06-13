<?php
session_start();
include '../../config/database.php';


// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     header("Location: ../../index.php");
//     exit();
// }

// Handle CRUD operations for categories
if (isset($_POST['category_action'])) {
    $category_name = $_POST['category_name'];
    $category_id = $_POST['category_id'] ?? null;

    if ($_POST['category_action'] == 'add') {
        $stmt = $pdo->prepare("INSERT INTO Categories (category_name) VALUES (:category_name)");
        $stmt->bindParam(':category_name', $category_name);
    } elseif ($_POST['category_action'] == 'edit') {
        $stmt = $pdo->prepare("UPDATE Categories SET category_name = :category_name WHERE category_id = :category_id");
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':category_name', $category_name);
    } elseif ($_POST['category_action'] == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM Categories WHERE category_id = :category_id");
        $stmt->bindParam(':category_id', $category_id);
    }

    
    $stmt->execute();
    header("Location: dashboard.php");
    exit();
}

// Handle CRUD operations for tags
if (isset($_POST['tag_action'])) {
    $tag_name = $_POST['tag_name'];
    $tag_id = $_POST['tag_id'] ?? null;

    if ($_POST['tag_action'] == 'add') {
        $stmt = $pdo->prepare("INSERT INTO Tags (tag_name) VALUES (:tag_name)");
        $stmt->bindParam(':tag_name', $tag_name);
    } elseif ($_POST['tag_action'] == 'edit') {
        $stmt = $pdo->prepare("UPDATE Tags SET tag_name = :tag_name WHERE tag_id = :tag_id");
        $stmt->bindParam(':tag_id', $tag_id);
        $stmt->bindParam(':tag_name', $tag_name);
    } elseif ($_POST['tag_action'] == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM Tags WHERE tag_id = :tag_id");
        $stmt->bindParam(':tag_id', $tag_id);
    }

    $stmt->execute();
    header("Location: dashboard.php");
    exit();
}

// Handle CRUD operations for users
if (isset($_POST['user_action'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $user_id = $_POST['user_id'] ?? null;

    if ($_POST['user_action'] == 'add') {
        $stmt = $pdo->prepare("INSERT INTO Users (username, password, email) VALUES (:username, :password, :email)");
    } elseif ($_POST['user_action'] == 'edit') {
        $stmt = $pdo->prepare("UPDATE Users SET username = :username, password = :password, email = :email WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
    } elseif ($_POST['user_action'] == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM Users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
    }

    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    header("Location: dashboard.php");
    exit();
}

// Fetch categories, tags, and users
$categories = $pdo->query("SELECT * FROM Categories")->fetchAll(PDO::FETCH_ASSOC);
$tags = $pdo->query("SELECT * FROM Tags")->fetchAll(PDO::FETCH_ASSOC);
$users = $pdo->query("SELECT * FROM Users")->fetchAll(PDO::FETCH_ASSOC);

include '../../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #000;
            color: #fff;
        }
        .container {
            background-color: #111;
            padding: 20px;
            border-radius: 8px;
        }
        h1, h2 {
            color: #fff;
        }
        .form-control {
            background-color: #222;
            border: 1px solid #444;
            color: #fff;
        }
        .form-control:focus {
            background-color: #333;
            color: #fff;
        }
        .btn-primary, .btn-warning, .btn-danger, .btn-info {
            border: none;
        }
        .btn-primary {
            background-color: #0056b3;
        }
        .btn-warning {
            background-color: #ff8c00;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-info {
            background-color: #17a2b8;
        }
        .list-group-item {
            background-color: #222;
            border: 1px solid #444;
        }
        .list-group-item:hover {
            background-color: #333;
        }
        .btn-sm {
            padding: .25rem .5rem;
            font-size: .875rem;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Admin Dashboard</h1>

        <!-- Categories CRUD -->
        <div class="mt-4">
            <h2>Categories</h2>
            <form method="post">
                <input type="hidden" name="category_id" id="category_id">
                <div class="form-group">
                    <label for="category_name">Category Name</label>
                    <input type="text" class="form-control" name="category_name" id="category_name" required>
                </div>
                <button type="submit" class="btn btn-primary" name="category_action" value="add">Add</button>
            </form>
            <form action="../../controllers/admin/aksi_dashboard.php" method="post">
            <ul class="list-group mt-3 mb-3">
                <?php foreach ($categories as $category): ?>
                    <li class="list-group-item">
                        <input type="checkbox" name="delete[]" value="<?php echo $category['category_id'] ?>">
                        <input id="edit-category" class="form control" name="category[]" value="<?php echo $category['category_name']; ?>" disabled>
                        <button type="button" class="btn btn-sm btn-info float-right" onclick="editCategoryBtn(this)">Edit</button>
                        <input style="display: none;" id="id_category" type="text" name="id[]" value="<?php echo $category['category_id'] ?>" disabled>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button type="submit" class="btn btn-warning" name="edit_category" value="edit">Save</button>
            <button type="submit" class="btn btn-danger" name="delete_category" value="delete">Delete</button>
            </form>
        </div>

        <!-- Tags CRUD -->
        <div class="mt-4">
            <h2>Tags</h2>
            <form method="post">
                <input type="hidden" name="tag_id" id="tag_id">
                <div class="form-group">
                    <label for="tag_name">Tag Name</label>
                    <input type="text" class="form-control" name="tag_name" id="tag_name" required>
                </div>
                <button type="submit" class="btn btn-primary" name="tag_action" value="add">Add</button>
            </form>
            <form action="../../controllers/admin/aksi_dashboard.php" method="post">
            <ul class="list-group mt-3 mb-3">
                <?php foreach ($tags as $tag): ?>
                    <li class="list-group-item">
                        <input type="checkbox" name="tag_delete[]" value="<?php echo $tag['tag_id'] ?>">
                        <input id="edit-tag" class="form control" name="tags[]" value="<?php echo $tag['tag_name']; ?>" disabled>
                        <button type="button" class="btn btn-sm btn-info float-right" onclick="editTagBtn(this)">Edit</button>
                        <input style="display: none;" id="id_tag" type="text" name="id_tag[]" value="<?php echo $tag['tag_id']; ?>" disabled>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button type="submit" class="btn btn-warning" name="edit_tag" value="edit">Save</button>
            <button type="submit" class="btn btn-danger" name="delete_tag" value="delete">Delete</button>
            </form>
        </div>

        <!-- Users CRUD -->
        <div class="mt-4">
            <h2>Users</h2>
            <form method="post">
                <input type="hidden" name="user_id" id="user_id">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username" id="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>
                <button type="submit" class="btn btn-primary" name="user_action" value="add">Add</button>
                <button type="submit" class="btn btn-warning" name="user_action" value="edit">Edit</button>
                <button type="submit" class="btn btn-danger" name="user_action" value="delete">Delete</button>
            </form>
            <ul class="list-group mt-3">
                <?php foreach ($users as $user): ?>
                    <li class="list-group-item">
                        <?php echo $user['username']; ?>
                        <button class="btn btn-sm btn-info float-right" onclick="editUser('<?php echo $user['user_id']; ?>', '<?php echo $user['username']; ?>', '<?php echo $user['email']; ?>')">Edit</button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <script>
        function editCategory(id, name) {
            document.getElementById('category_id').value = id;
            document.getElementById('category_name').value = name;
        }

        function editTag(id, name) {
            document.getElementById('tag_id').value = id;
            document.getElementById('tag_name').value = name;
        }

        function editUser(id, username, email) {
            document.getElementById('user_id').value = id;
            document.getElementById('username').value = username;
            document.getElementById('email').value = email;
        }

        function editCategoryBtn(el) {
            var parent = el.parentNode;
            var field = parent.querySelector("#edit-category");
            var idfield = parent.querySelector("#id_category");
            field.removeAttribute('disabled');
            idfield.removeAttribute('disabled');
        }

        function editTagBtn(el) {
            var parent = el.parentNode;
            var field = parent.querySelector("#edit-tag");
            var idfield = parent.querySelector("#id_tag");
            field.removeAttribute('disabled');
            idfield.removeAttribute('disabled');
        }

    </script>
</body>
</html>
