<?php
// DB接続設定
$dsn = 'mysql:dbname=tb260012db;host=localhost';
$user = 'tb-260012';
$password = 'mHbC2gBznz';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$sql="CREATE TABLE IF NOT EXISTS m6 (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name CHAR(32),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_spoiler BOOLEAN DEFAULT 0
)";

$pdo->query($sql);

// データ挿入
if (!empty($_POST['name']) && !empty($_POST['comment'])) {
    $name = $_POST['name'];
    $comment = $_POST['comment'];
    $is_spoiler = !empty($_POST['is_spoiler']) ? 1 : 0;
    $now = date('Y-m-d H:i:s');

    $sql = "INSERT INTO m6 (name, comment, created_at, is_spoiler) VALUES (:name, :comment, :created_at, :is_spoiler)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':created_at', $now, PDO::PARAM_STR);
    $stmt->bindParam(':is_spoiler', $is_spoiler, PDO::PARAM_BOOL);
    $stmt->execute();
}

// データ表示
$sql = 'SELECT * FROM m6';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ネタバレ防止掲示板</title>
    <style>
        .spoiler {
            background-color: #000;
            color: #000;
            cursor: pointer;
        }
        .spoiler:hover {
            color: #fff;
        }
    </style>
</head>
<body>
    <form action="" method="POST">
        <input type="text" name="name" placeholder="名前">
        <input type="text" name="comment" placeholder="コメント">
        <label>
            <input type="checkbox" name="is_spoiler" value="1"> ネタバレ
        </label>
        <input type="submit" name="submit" value="投稿">
    </form>

    <h2>投稿一覧</h2>
    <?php
    foreach ($results as $row) {
        echo "<div>";
        echo "<strong>" . htmlspecialchars($row['name']) . "</strong> ";
        echo "<span>[" . htmlspecialchars($row['created_at']) . "]</span><br>";
        if ($row['is_spoiler']) {
            echo "<span class='spoiler'>" . htmlspecialchars($row['comment']) . "</span>";
        } else {
            echo "<span>" . htmlspecialchars($row['comment']) . "</span>";
        }
        echo "</div><hr>";
    }
    ?>
</body>
</html>
?>