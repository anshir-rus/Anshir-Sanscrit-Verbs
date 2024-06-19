<?php


function check_setnost($mool, $suffix, $is_suffix, $second_is_suffix, $special_rules, $source, $suffix_steam, $omonim = null)
{
    // Получение размеров mool и преобразование в массив
    $dimensions_mool = dimensions($mool, "smth", "something", 1, 0, 0, "");
    $dimensions_mool_array = dimensions_array($dimensions_mool);

    // Длина корня
    $dlina_kornya = strlen($dimensions_mool[1]);

    // Определение сдвига для последней буквы корня
    if ($dimensions_mool_array[$dlina_kornya - 1][0] == "|") {
        $sdvig = ($dimensions_mool_array[$dlina_kornya - 2][0] == "|") ? 3 : 2;
    } else {
        $sdvig = 1;
    }

    // Определение последней буквы корня и её характеристик
    $mool_last_letter = $dimensions_mool_array[$dlina_kornya - $sdvig][0];
    $mool_last_letter2 = $dimensions_mool_array[$dlina_kornya - $sdvig - 1][0];
    $mool_last_cons = $dimensions_mool_array[$dlina_kornya - $sdvig][1];
    $mool_last_vzryv = $dimensions_mool_array[$dlina_kornya - $sdvig][2];
    $seek_last_letter = seeking_1_bukva($mool_last_letter, 0);

    $FLAG_NEED_SET = 0;
    $FLAG_NEED_SET_ENDINGS = 0;
    $FLAG_NEED_SET_ENDINGS_VAR = 0;
    $FLAG_NEED_SET_ENDINGS_LONG = 0;
    $FLAG_NEED_SET_ENDINGS_LONG_VAR = 0;

    // Определение необходимости сетности на основе согласной буквы или суффикса
    if ($seek_last_letter[1] == "C" || $mool_last_cons == "C" || 
        ($is_suffix == 3 && in_array($mool_last_letter, ["e", "o", "āu", "āi"]) || 
        ($mool_last_letter2 == "a" && in_array($mool_last_letter, ["u", "i"])))) {
        
        $first_letter_suffix = mb_substr($suffix, 0, 1);
        if (in_array($first_letter_suffix, ["s", "t"])) {
            $FLAG_NEED_SET = 1;
        }
    }

    // Дополнительные правила для суффиксов
    if (($source == "jn̥̄" || $source == "tn̥" || $source == "uØh" || $source == "bhṛ" || 
        $source == "bn̥dh" || $source == "uØc" || $source == "śap" || $source == "sah" || 
        ($source == "uØp" && in_array($omonim, [1, 2])) ) && 
        $suffix == "se" && $second_is_suffix == 3 && $suffix_steam == "PeF") {
        $FLAG_NEED_SET_ENDINGS = 1;
    }

    if (($source == "uØkṣ" || $source == "Øs" || $source == "ruh" || $source == "ruj" || 
        ($source == "kṛt" && in_array($omonim, [1, 2])) || $source == "kṣip" || $source == "tṛd" || 
        ($source == "vid" && $omonim == 2) || $source == "viś" || $source == "sṛj" || 
        ($source == "rudh" && in_array($omonim, [1, 2])) || $source == "duh" || $source == "druh" || 
        $source == "ṛ") && 
        $suffix == "tha" && $second_is_suffix == 3 && $suffix_steam == "PeF") {
        $FLAG_NEED_SET_ENDINGS = 1;
    }

    if (in_array($source, ["bhū", "uØc"]) && $suffix == "tha" && 
        $second_is_suffix == 3 && $suffix_steam == "PeF") {
        $FLAG_NEED_SET_ENDINGS_VAR = 1;
    }

    if (in_array($source, ["uØc", "sad", "bhū", "uØd", "sac", "yup", "Øs", "cṝ", "pat", "hn̥", 
        "uØs", "ym̥", "dāś", "śak", "vand", "hiṃs", "ṛ", "sūd"]) && 
        in_array($suffix, ["va", "vahe", "ma", "mahe"]) && $second_is_suffix == 3 && $suffix_steam == "PeF") {
        $FLAG_NEED_SET_ENDINGS = 1;
    }

    if (in_array($source, ["an", "rud", "vm̥̄", "suØp", "śuØṣ"]) && 
        in_array($suffix, ["mi", "si", "ti"]) && $second_is_suffix == 3 && $special_rules == 2) {
        $FLAG_NEED_SET_ENDINGS = 1;
    }

    if (in_array($source, ["īś", "jakṣ", "ym̥"]) && 
        in_array($suffix, ["mi", "si", "ti"]) && $second_is_suffix == 3 && $special_rules == 2) {
        $FLAG_NEED_SET_ENDINGS_VAR = 1;
    }

    if (in_array($source, ["īḍ", "īś", "jn̥̄", "vas", "śam"]) && 
        $suffix == "sva" && $second_is_suffix == 3 && $special_rules == 2) {
        $FLAG_NEED_SET_ENDINGS = 1;
    }

    if (in_array($source, ["rud", "śnath", "stan"]) && 
        in_array($suffix, ["hi", "tu"]) && $second_is_suffix == 3 && $special_rules == 2) {
        $FLAG_NEED_SET_ENDINGS = 1;
    }

    if ($source == "suØp" && in_array($suffix, ["hi", "tu"]) && 
        $second_is_suffix == 3 && $special_rules == 2) {
        $FLAG_NEED_SET_ENDINGS_VAR = 1;
    }

    if (in_array($source, ["m̥̄", "tu", "brū", "ru", "stu"]) && 
        in_array($suffix, ["mi", "si", "ti"]) && $second_is_suffix == 3 && $special_rules == 2) {
        $FLAG_NEED_SET_ENDINGS_LONG = 1;
    }

    if (in_array($source, ["an", "m̥̄", "kṝ", "brū", "ras", "rud", "vm̥̄", "śuØṣ"]) && 
        in_array($suffix, ["t", "s"]) && $second_is_suffix == 3 && $special_rules == 2) {
        $FLAG_NEED_SET_ENDINGS_LONG = 1;
    }

    if ($source == "Øs" && in_array($suffix, ["t", "s"]) && 
        $second_is_suffix == 3 && $special_rules == 2) {
        $FLAG_NEED_SET_ENDINGS_LONG_VAR = 1;
    }

    if (in_array($source, ["m̥̄", "śam"]) && $suffix == "sva" && 
        $second_is_suffix == 3 && $special_rules == 2) {
        $FLAG_NEED_SET_ENDINGS_LONG = 1;
    }

    if ($source == "brū" && in_array($suffix, ["hi", "tu"]) && 
        $second_is_suffix == 3 && $special_rules == 2) {
        $FLAG_NEED_SET_ENDINGS_LONG = 1;
    }

    // Формирование результата
    return [
        'need_verb_setnost' => $FLAG_NEED_SET,
        'need_ending_setnost_long_var' => $FLAG_NEED_SET_ENDINGS_LONG_VAR,
        'need_ending_setnost_long' => $FLAG_NEED_SET_ENDINGS_LONG,
        'need_ending_setnost' => $FLAG_NEED_SET_ENDINGS,
        'need_ending_setnost_var' => $FLAG_NEED_SET_ENDINGS_VAR
    ];
}

function setnost_letter($mool, $suffix, $is_suffix, $second_is_suffix, $verb_setnost, $suffix_steam, $query, $special_rules, $source)
{
    // Проверка необходимости сетности
    $check_setnost = check_setnost($mool, $suffix, $is_suffix, $second_is_suffix, $special_rules, $source, $suffix_steam);

    // Начальная установка переменной сетности
    $setnost = '';

    // Если сетность глагола требуется
    if ($check_setnost['need_verb_setnost']) {
        if ($verb_setnost == "0") {
            $setnost = "STOP";
        } else {
            if (!in_array($verb_setnost, ["s", "a", "v"])) {
                // Установка сетности на основе типа основы суффикса
                switch ($suffix_steam) {
                    case "FuS":
                        $setnost = handle_verb_setnost($verb_setnost, ["v", "s", "s", "v", "a"], ["s"]);
                        break;
                    case "DS":
                        $setnost = handle_verb_setnost($verb_setnost, ["v", "s", "s", "a", "a"], ["s"]);
                        break;
                    case "PaPePS":
                        $setnost = handle_verb_setnost($verb_setnost, ["a", "v", "a", "a", "a"], ["s"]);
                        break;
                    case "G":
                        $setnost = handle_verb_setnost($verb_setnost, ["v", "v", "v", "a", "a"], ["s"]);
                        break;
                    default:
                        $setnost = handle_default_setnost($verb_setnost, $query);
                        break;
                }
            } else {
                $setnost = $verb_setnost;
            }
        }
    }

    // Применение специальных правил сетности окончаний
    if ($check_setnost['need_ending_setnost_long']) {
        $setnost = "ss";
    }

    if ($check_setnost['need_ending_setnost_long_var']) {
        $setnost = "vv";
    }

    if ($check_setnost['need_ending_setnost_var']) {
        $setnost = "v";
    }

    if ($check_setnost['need_ending_setnost']) {
        $setnost = "s";
    }

    return $setnost;
}

/**
 * Обрабатывает сетность для указанных случаев и возвращает соответствующее значение сетности
 */
function handle_verb_setnost($verb_setnost, $cases, $query_cases)
{
    switch ($verb_setnost) {
        case "v1":
            return $cases[0];
        case "v2":
            return $cases[1];
        case "v3":
            return $cases[2];
        case "v4":
            return $cases[3];
        case "v5":
            return handle_query_case($query_cases);
        default:
            return '';
    }
}

/**
 * Обрабатывает случай запроса и возвращает соответствующее значение сетности
 */
function handle_query_case($query_cases)
{
    global $query;
    if ($query == 1) {
        return $query_cases[0];
    } elseif ($query == 2) {
        return $query_cases[1];
    }
    return '';
}

/**
 * Обрабатывает сетность по умолчанию и возвращает соответствующее значение сетности
 */
function handle_default_setnost($verb_setnost, $query)
{
    switch ($verb_setnost) {
        case "v1":
        case "v2":
        case "v3":
            return "v";
        case "v4":
            return "a";
        case "v5":
            return $query == 1 ? "a" : ($query == 2 ? "s" : '');
        default:
            return '';
    }
}

function make_setnost($info_massive, $source)
{
    // Удаление невидимых суффиксов
    $info_massive_new = array_filter($info_massive, function($item) {
        return $item[0] != "|";
    });

    // Объединение массивов
    $combined_massives = combine_massives($info_massive_new);

    // Инициализация массивов для хранения индексов set и anit
    $set_massive = [];
    $anit_massive = [];
    $FLAG_STOP = 0;

    // Обработка объединённых массивов
    foreach ($combined_massives as $pair) {
        list($first_info, $second_info) = $pair;
        $first = $first_info[0];
        $first_setnost = $first_info[6];
        $second = $second_info[0];
        $suffix_steam = $second_info[9];
        $suffix_ask = $second_info[7];
        $first_is_suffix = $first_info[5];
        $second_is_suffix = $second_info[5];
        $special_rules = $first_info[15];

        // Получение значения setnost
        $set = Setnost($first, $second, $first_is_suffix, $second_is_suffix, $first_setnost, $suffix_steam, $suffix_ask, $special_rules, $source)['setnost'];

        // Обработка значения setnost для массива anit_massive
        if (in_array($set, ["s", "ss"])) {
            for ($j = 0; $j < count($info_massive); $j++) {
                if ($info_massive[$j][0] == $first && $info_massive[$j + 1][0] == $second) {
                    $anit_massive[] = $j;
                }
            }
        }

        // Обработка значения setnost для массива set_massive
        if (in_array($set, ["v", "s", "ss", "vv"])) {
            for ($j = 0; $j < count($info_massive); $j++) {
                if ($info_massive[$j][0] == $first && $info_massive[$j + 1][0] == $second) {
                    $set_massive[] = $j;
                }
            }
        }

        // Проверка на остановку обработки
        if ($set == "STOP") {
            $FLAG_STOP = 1;
            break;
        }
    }

    // Формирование массива set с добавлением специальных символов
    $info_massive_set = [];
    for ($k = 0; $k < count($info_massive); $k++) {
        $flag_set = in_array($k, $set_massive);

        $info_massive_set[] = $info_massive[$k];
        if ($flag_set) {
            $info_massive_set[] = [($set == "ss" || $set == "vv") ? "|ī|" : "|i|"];
        }
    }

    // Формирование массива anit с добавлением специальных символов
    $info_massive_anit = [];
    for ($k = 0; $k < count($info_massive); $k++) {
        $flag_anit = in_array($k, $anit_massive);

        $info_massive_anit[] = $info_massive[$k];
        if ($flag_anit) {
            $info_massive_anit[] = [($set == "ss" || $set == "vv") ? "|ī|" : "|i|"];
        }
    }

    // Возвращение результата
    return [
        'set' => $info_massive_set,
        'anit' => $info_massive_anit
    ];
}

function Setnost($mool, $suffix, $is_suffix, $second_is_suffix, $verb_setnost, $suffix_steam, $query, $special_rules, $source)
{
    $mool = str_replace("|", "", $mool);
    $suffix = str_replace("|", "", $suffix);

    $check_result = check_setnost($mool, $suffix, $is_suffix, $second_is_suffix, $special_rules, $source, $suffix_steam);
    
    $check = $check_result['need_verb_setnost'];
    $check_end_setnost = $check_result['need_ending_setnost'];
    $check_end_setnost_var = $check_result['need_ending_setnost_var'];
    $check_end_setnost_long = $check_result['need_ending_setnost_long'];
    $check_end_setnost_long_var = $check_result['need_ending_setnost_long_var'];

    if ($check || $check_end_setnost || $check_end_setnost_var || $check_end_setnost_long || $check_end_setnost_long_var) {
        $setnost = setnost_letter($mool, $suffix, $is_suffix, $second_is_suffix, $verb_setnost, $suffix_steam, $query, $special_rules, $source, $suffix_steam);
        $result['setnost'] = $setnost;
    }

    return $result ?? [];
}

?>