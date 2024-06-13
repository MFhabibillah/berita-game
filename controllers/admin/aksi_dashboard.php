<?php 

require_once "../../config/database.php";

if (isset($_POST["edit_category"])) {
    $id_categories = $_POST['id'];
    $categories = $_POST['category'];
    foreach ($id_categories as $index => $id) {
        $query = "UPDATE categories set category_name = :category where category_id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":category" , $categories[$index]);
        $stmt->bindParam(":id" , $id);
        $stmt->execute();
    }
}

if(isset($_POST['delete_category'])){
    $id_categories = $_POST['delete'];
    foreach ($id_categories as $index => $id) {
        $query = "DELETE from categories where category_id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id" , $id);
        $stmt->execute();
    }
}

if (isset($_POST["edit_tag"])) {
    $id_tags = $_POST['id_tag'];
    $tags = $_POST['tags'];
    // var_dump($tags);
    var_dump($id_tags);
    foreach ($id_tags as $index => $id) {
        $query = "UPDATE tags set tag_name = :tag where tag_id = :id";
        $stmt = $pdo->prepare($query);
        // var_dump($index);
        // var_dump($tags[$index]);
        $stmt->bindParam(":tag" , $tags[$index]);
        $stmt->bindParam(":id" , $id);
        $stmt->execute();
    }
}

if(isset($_POST['delete_tag'])){
    $id_tags = $_POST['tag_delete'];
    foreach ($id_tags as $index => $id) {
        $query = "DELETE from tags where tag_id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id" , $id);
        $stmt->execute();
    }
}

header('Location:../../views/admin/dashboard.php');
