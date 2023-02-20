<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sorting Alrorithms</title>
</head>
<body>

<?php
set_time_limit(0); //Время выполнения не ограничено!

$select = ['ghbdtn', 'aylhtq', 'wewe'];

$n = isset($_POST['n']) ? intval($_POST['n']) : 1000;
$min = isset($_POST['mix']) ? intval($_POST['mix']) : -100;
$max = isset($_POST['max']) ? intval($_POST['max']) : 100;
?>

<form method="post" action=" <?= $_SERVER['PHP_SELF'] ?>">
    <label>Размерность массива:
        <input type="text" name="n" maxlength="5" size="5" value="<?= $n ?>">
    </label>
    <label>Минимум:
        <input type="text" name="min" maxlength="4" size="4" value="<?= $min ?>">
    </label>
    <label>
        Максимум:
        <input type="text" name="max" maxlength="4" size="4" value="<?= $max ?>">
    </label>
    <label>Алгоритм сортировки:
        <select name="alg" size="1">
            <option value="">Выбрать</option>
            <?php
            foreach ($select as $i => $nameFun) : ?>
                <option value="<?= $i ?>"><?= $nameFun ?></option>
            <?php
            endforeach; ?>
        </select>
    </label>
    <input type="submit" name="action" value="Выполнить">
</form>


<div>
    <?php
    if (isset($_POST['action'])): ?>
        <?php if ($n < 2): ?>
            <p>Меньше 2 элементов, нечего сортировать!</p>
        <?php endif; ?>

        <?php if (isset($select[0][1])): ?>
            <p>Не выбран алгоритм!</p>
        <?php endif; ?>

    <?php
    endif; ?>
</div>
</body>
</html>