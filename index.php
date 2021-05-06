<?php 
ini_set("display_errors", 1);
error_reporting(-1);
require_once 'core/config.php'; 
require_once 'core/functions.php';

if (isset($_POST['test']) ){
    $test = (int)$_POST['test'];
    unset($_POST['test']);
    $result = get_correct_answers($test);
    if (!is_array($result)) exit('Ошибка!');
    //Данные теста
    $test_all_data = get_test_data($test);
    // 1 - Масив вопрос\ответы, 2 - правильные ответы, 3 - ответы пользователя
    $post = $_POST;
    $test_all_data_result = get_test_data_result($test_all_data, $result, $post);
    echo print_result($test_all_data_result);
    die;
}

//Список тестов
$tests = get_tests();

if ( isset($_GET['test']) ){
    $test_id = (int)$_GET['test'];
    $test_data = get_test_data($test_id);
    if (is_array($test_data)){
        $count_questions = count($test_data);
        $pagination = pagination($count_questions, $test_data);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Система тестирования</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
    <link rel="stylesheet" type="text/css" href="assets/css/maincard.css">
    <link rel="stylesheet" type="text/css" href="assets/css/questions-style.css">
</head>
<body>
    <div class="container">
        <div class="title-new-test">
            <h1>Тестирование</h1>
            <div class="title-new-info">
                Курсов: <?php echo count($tests);?>
            </div>
        </div>
    </div>
	<div class="wrap">

		<?php if( $tests ): ?>
			<?php  foreach($tests as $test): ?>
                <a href="?test=<?=$test['id']?>" class="card education">
                    <div class="overlay">
                    </div>
                    <p><?=$test['test_name']?></p>
                </a>
            <?php endforeach;?>
		<?php else: // $tests?>
			<h3>Нет тестов</h3>
		<?php endif; // $tests?>
    </div>
<!--
**
*   Вывод вопросов
**
-->
    <br><hr><br>

    <div class="content_tests">
        <?php if (isset($test_data)):?>

                <p>Всего вопросов: <?=$count_questions?></p>
                <?=$pagination?>
                <span class="none" id="test-id"><?=$test_id?></span>

                <div class="test-data">
                    <?php foreach ($test_data as $id_question => $item): // каждый конкретный вопрос + ответ?>
                        <div class="question" data-id="<?=$id_question?>" id="question-<?=$id_question?>">

                            <?php foreach ($item as $id_answer => $answer): // проходим по масиву вопрос ответ?>
                                <?php if (!$id_answer): // выводим вопрос ?>
                                    <p class="q"><?=$answer?></p>
                                    <?php else: // Варианты ответов?>
                                        <p class="a">
                                            <input type="radio" id="answer-<?=$id_answer?>" name="question-<?=$id_question?>" value="<?=$id_answer?>">
                                            <label for="answer-<?=$id_answer?>"><?=$answer?></label>
                                        </p>
                                <?php endif; //$id_answer?>
                            <?php endforeach; // $item?>
                        </div>
                    <?php endforeach; // $test_data?>
                </div> <!-- .test-data -->

            <div class="buttons">
                    <button class="center btn" id="btn">Закончить тест</button>
                </div>
        <?php else: // isset($test_data)?>
            <p>Выбирите тест</p>
        <?php endif; // isset($test_data)?>
    </div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="assets/js/scripts.js"></script>
</body>
</html>