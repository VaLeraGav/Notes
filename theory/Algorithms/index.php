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


$n = 1000;
$min = -100;
$max = 100;
$count = 1;

function sortingComparisons($namesFun, $arr, $count, $sorts)
{
    $nameFun = $namesFun[0][1];
    if ($nameFun !== 'all') {
        return outputArrAndTime($arr, $nameFun, $count);
    }
    array_pop($sorts); // убрать all из массива

    foreach ($sorts as [$name, $fun]) {
        print_r("\n" . "-------------------");
        print_r("\n" . $name);
        $arrFunTime[] = getArrFunTime($arr, $fun, $count);
    }
    print_r("\n-------------------\n");

    $min = $arrFunTime[0][1];
    $max = $arrFunTime[0][1];
    foreach ($arrFunTime as $i => [$name, $time]) {
        if ($time < $min) {
            $min = $time;
        }
        if ($time > $max) {
            $max = $time;
        }
    }
    print_r("\n$min\n");
    print_r("\n$max\n");
    return "";
}


function outputArrAndTime($arrNotSort, $nameFun, $count): string
{
    $time = averagePerformance(fn() => $nameFun([...$arrNotSort]), $count);
    $oldArr = implode(',', $arrNotSort);
    $sortArr = implode(',', $nameFun($arrNotSort));
    return <<<DOC
Исходный массив: $oldArr

Отсортированный массив: $sortArr

Затраченное время: $time
DOC;
}

function getArrFunTime($arrNotSort, $nameFun, $count): array
{
    $time = averagePerformance(fn() => $nameFun([...$arrNotSort]), $count);
    print_r("\nЗатраченное время: {$time}");
    return [$nameFun, $time];
}

while (true) {
    echo "Количество элементов: {$n}\n";
    echo "Минимальное: {$min}\n";
    echo "Максимальное: {$max}\n";
    echo "Количество раз выполнений: {$count}\n";

    echo "Изменить(y): ";
    $choose = trim(fgets(STDIN)); // читает одну строку из STDIN
    echo "===========================\n";
    if ($choose === 'y') {
        echo "Количество элементов: ";
        $n = fgets(STDIN);
        if ($n < 2) {
            echo "Меньше 2 элементов, нечего сортировать!\n";
            exit;
        }
        echo "Минимальное: ";
        $min = fgets(STDIN);
        echo "Максимальное: ";
        $max = fgets(STDIN);
        echo "Количество раз выполнений: ";
        $count = fgets(STDIN);
        if ($count > 100) {
            echo "Больше 1000 не рекомендуется!\n";
            exit;
        }
        echo "===========================\n";
    }

    foreach ($sorts as $i => $arrFun) {
        echo $i . ". " . $arrFun[0] . "\n";
    }
    echo "Выбарть: ";
    $numFun = fgets(STDIN);
    echo "===========================";

    $select = array_slice($sorts, $numFun, 1);
    echo "\nВыбрали: {$select[0][0]} ";

    $randArr = generateArray($n, $min, $max);

    echo sortingComparisons($select, $randArr, $count, $sorts);

    echo "\n===========================\n";
    echo "\nПовторить(y): ";
    $repeat = trim(fgets(STDIN));
    if ($repeat !== 'y') {
        exit;
    }
    echo "===========================\n";
}
