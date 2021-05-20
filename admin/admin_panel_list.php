<?php
require_once '../core/config.php';

$result = mysqli_query($db, "SELECT * FROM `db_tests`");
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="/admin/assets/css/index.css">
    <link rel="stylesheet" type="text/css" href="/admin/assets/css/menu.css">
    <title>АдминПанель - Тестирования</title>
</head>
<body>
<div class="sidebar">
    <div class="logo_content">
        <div class="logo">
            <i class='bx bxl-c-plus-plus'></i>
            <div class="logo_name">CodingLab</div>
        </div>
        <i class='bx bx-menu' id="btn"></i>
    </div>
    <ul class="nav_list">
        <li>
            <a href="admin_panel.php">
                <i class='bx bx-grid-alt'></i>
            </a>
            <span class="tooltip active">Создать тест</span>
        </li>
        <li>
            <a href="admin_panel_list.php">
                <i class='bx bx-folder'></i>
            </a>
            <span class="tooltip">Готовые тесты</span>
        </li>
    </ul>
    <div class="profile_content">
        <a href="../index.php">
            <div class="profile">
                <i class='bx bx-log-out' id="log_out"></i>
            </div>
        </a>
    </div>
</div>
<div class="home_content">
    <div class="text_h1"><h1>Список готовых тестов</h1></div>
    <div class="out_result">
        <table>
            <tr>
                <th>Название</th>
                <th>Статус</th>
                <th>Управление</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_array($result)) {
                echo '<tr>';
                echo '<td>' . $row['test_name'] . '</td>';
                echo '<td>' . $row['enable'] . '</td>';
                echo '<td><input type="submit" value="Delete"><input type="submit" value="Edit"></td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>