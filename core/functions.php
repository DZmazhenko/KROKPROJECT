<?php


/**
 * распечатка массива
 **/

function print_arr($arr)
{
    echo '<pre>' . print_r($arr, true) . '</pre>';
}

/**
 * получение списка тестов
 **/

function get_tests()
{
    global $db;
    $query = "SELECT * FROM db_tests WHERE enable = '1'";
    $res = mysqli_query($db, $query);
    if (!$res) return false;
    $data = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    return $data;

}

/**
 * получение данных теста
 **/

function get_test_data($test_id)
{
    if (!$test_id) return;
    global $db;
    $query = "SELECT q.question, q.parent_tests, a.id, a.answer, a.parent_question, a.correct_answer
    FROM db_questions q
    LEFT JOIN db_answers a 
        ON q.id = a.parent_question
    LEFT JOIN db_tests
        ON db_tests.id = q.parent_tests
            WHERE q.parent_tests = $test_id AND db_tests.enable = '1'";
    $res = mysqli_query($db, $query);
    $data = null;
    while ($row = mysqli_fetch_assoc($res)) {
        if (!$row['parent_question']) return false;
        $data[$row['parent_question']][0] = $row['question'];
        $data[$row['parent_question']][$row['id']] = $row['answer'];
    }
    return $data;
}

/**
*  получение id вопрос\ответ
 **/
function get_correct_answers($test){
    if (!$test) return false;
    global $db;
    $query = "SELECT q.id AS question_id, a.id AS answer_id
        FROM db_questions q
        LEFT JOIN db_answers a
            ON q.id = a.parent_question
        LEFT JOIN db_tests
            ON db_tests.id = q.parent_tests
                WHERE q.parent_tests = $test AND a.correct_answer = '1' AND db_tests.enable = '1'";
    $res = mysqli_query($db, $query);
    $data = null;
    while($row = mysqli_fetch_assoc($res)){
        $data[$row['question_id']] = $row['answer_id'];
    }
    return $data;
}

/**
 * построение пагинации
 **/

function pagination($count_questions, $test_data)
{
    $keys = array_keys($test_data);
    $pagination = '<div class="pagination">';
    for ($i = 1; $i <= $count_questions; $i++) {
        $key = array_shift($keys);
        if ($i == 1) {
            $pagination .= '<a class="nav-active" href="#question-' . $key . '">' . $i . '</a>';
        } else {
            $pagination .= '<a href="#question-' . $key . '">' . $i . '</a>';
        }
    }
    $pagination .= '</div>';
    return $pagination;
}

/**
* Итоги
 *  1 - Масив вопрос\ответы,
 * 2 - правильные ответы,
 * 3 - ответы пользователя
 **/
function get_test_data_result($test_all_data, $result, $post){
    //заполняем масив $test_all_data - правильными ответами и данными о неотвеченых вопросах

    foreach ($result as $q => $a){
        $test_all_data[$q]['correct_answer'] = $a;
        //Добавим данные о неотвеченых вопросах
        if (!isset($post[$q])){
            $test_all_data[$q]['incorrect_answer'] = 0;
        }
    }

    //добавим не верный ответ, если он есть
    foreach ($post as $q => $a){
        // удалить из POST "левые" значения вопросов
        if (!isset($test_all_data[$q])){
            unset($post[$q]);
            continue;
        }
        //если есть POST "левые" значения ответов
        if (!isset($test_all_data[$q][$a])){
            $test_all_data[$q]['incorrect_answer'] = 0;
            continue;
        }
        //добавить не верный ответ
        if ($test_all_data[$q]['correct_answer'] != $a){
            $test_all_data[$q]['incorrect_answer'] = $a;
        }
    }
    return $test_all_data;
}

/**
* Распечатка результатов
 **/

function print_result($test_all_data_result){
    // переменные результатов
    $all_count = count($test_all_data_result); // Кол-во вопросов
    $correct_answer_count = 0; // Кол-во правильных ответов
    $incorrect_answer_count = 0; // Кол-во неверных ответов
    $percent = 0; //Процент верных ответов

    // подсчёт результатов
    foreach ($test_all_data_result as $item){
        if (isset($item['incorrect_answer'])) $incorrect_answer_count++;
    }
    $correct_answer_count = $all_count - $incorrect_answer_count;
    $percent = ceil($correct_answer_count / $all_count * 100);

    //Вывод результатов
    $print_res = '<div class="questions">';
        $print_res .= '<div class="count-res">';
            $print_res .= "<p>Всего вопросов: <b>{$all_count}</b></p>";
            $print_res .= "<p>Правильных: <b>{$correct_answer_count} ({$percent}%)</b></p>";
            $print_res .= "<p>Неправильных: <b>{$incorrect_answer_count}</b></p>";
        $print_res .= '</div>';

    //Вывод теста..
    foreach ($test_all_data_result as $id_question => $item){ // получаем вопрос + ответы
        $correct_answer = $item['correct_answer'];
        $incorrect_answer = null;
        if (isset($item['incorrect_answer'])){
            $incorrect_answer = $item['incorrect_answer'];
            $class = 'question-res error';
        }else{
            $class = 'question-res ok';
        }
        $print_res .= "<div class='$class'>";
        foreach ($item as $id_answer => $answer){ // Проходимся по масиву ответов
            if ($id_answer === 0){
                //Вопрос
                $print_res .= "<p class='q'>$answer</p>";
            }elseif ( is_numeric($id_answer)){
                //ответ
                if ($id_answer == $correct_answer){
                    //если это верный ответ
                    $class = 'a ok_answer';
                }elseif ($id_answer == $incorrect_answer){
                    //Если не верный ответ
                    $class = 'a error_answer';
                }else{
                    $class = 'a';
                }
                $print_res .= "<p class='$class'>$answer</p>";
            }
        }
        $print_res .= '</div>';
    }

    $print_res .= '</div>';




    return $print_res;
}