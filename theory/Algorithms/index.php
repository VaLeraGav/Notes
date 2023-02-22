<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once('function.php');

set_time_limit(0); //Время выполнения не ограничено

function averagePerformance($f, $count = 1)
{
    $start = microtime(true);
    for ($i = 0; $i < $count; $i++) {
        $f();
    }

    return (microtime(true) - $start) / $count;
}

$sorts = [
    ['Сортировка пузырьком', 'bubbleSort',],
    ['Сортировка перемешиванием', 'cocktailSort',],
    ['Гномья', 'gnomeSort',],
    ['Быстрая сортировка', 'quickSort',],
    ['Сортировка выбором', 'selectionSort',],
    ['Сортировка расчёской', 'combSort',],
    ['Сортировка вставками', 'insertionSort',],
    ['Сортировка слиянием', 'mergeSort',],
    ['Терпеливая', 'patienceSort',],
    ['Сортировка Шелла', 'shellSort',],
    ['Все', 'all']
];


$n = 100;
$min = -100;
$max = 100;
$count = 1;

function sortingComparisons($nameFuns, $arr, $count, $sorts)
{
    $nameFun = $nameFuns[0][1];
    if ($nameFun !== 'all') {
        return oneSort($arr, $nameFun, $count);
    }
    array_pop($sorts); // убрать all из массива

    foreach ($sorts as [$name, $fun]) {
        print_r("\n" . "-------------------");
        print_r("\n" . $name);
        print_r("\n" . manySort($arr, $fun, $count));
        $arrFun[] =  manySortArr($arr, $fun, $count);

    }
    print_r($arrFun );
    return "";
}


function oneSort($arr, $nameFun, $count)
{
    $t = averagePerformance(fn() => $nameFun([...$arr]), $count);
    $oldArr = implode(',', $arr);
    $sortedArr = implode(',', $nameFun($arr));
    return <<<DOC
Исходный массив: $oldArr

Отсортированный массив: $sortedArr

Затраченное время: $t
DOC;
}

function manySort($arr, $nameFun, $count)
{
    $t = averagePerformance(fn() => $nameFun([...$arr]), $count);
    return "Затраченное время: {$t}";
}

function manySortArr($arr, $nameFun, $count)
{
    $t = averagePerformance(fn() => $nameFun([...$arr]), $count);
    return [$nameFun, $t];
}



while (true) {
    echo "Количество элементов: {$n}\n";
    if ($n < 2) {
        echo "Меньше 2 элементов, нечего сортировать!\n";
    }
    echo "Минимальное: {$min}\n";
    echo "Максимальное: {$max}\n";
    echo "Количество раз выполнений: {$count}\n";
    if ($count > 100) {
        echo "Больше 100 не рекомендуется!\n";
    }

    echo "Изменить(y): ";
    $choose = trim(fgets(STDIN)); // читает одну строку из STDIN
    echo "===========================\n";
    if ($choose === 'y') {
        echo "Количество элементов: ";
        $n = fgets(STDIN);
        echo "Минимальное: ";
        $min = fgets(STDIN);
        echo "Максимальное: ";
        $max = fgets(STDIN);
        echo "Количество раз выполнений: ";
        $count = fgets(STDIN);
        echo "===========================\n";
    }

    foreach ($sorts as $i => $arrFun) {
        echo $i . ". " . $arrFun[0] . "\n";
    }
    echo "Выбарть: ";
    $numFun = fgets(STDIN);
    echo "===========================";

    $choice = array_slice($sorts, $numFun, 1);
    echo "\nВыбрали: {$choice[0][0]} ";

    $randArr = generateArray($n, $min, $max);

    echo sortingComparisons($choice, $randArr, $count, $sorts);

    echo "\n===========================\n";
    echo "\nПовторить(y): ";
    $repeat = trim(fgets(STDIN));
    if ($repeat !== 'y') {
        exit;
    }
    echo "===========================\n";
}





