# Алгоритмы

## Содержание

---

- [Алгоритмическая сложность](#алгоритмическая-сложность)
- [Алгоритмы поиска](#алгоритмы-поиска)
    - [Линейный поиск](#линейный-поиск)
    - [Бинарный поиск](#бинарный-поиск)
    - [Экспоненциальный поиск](#экспоненциальный-поиск)
- [Алгоритмы сортировки]()
    - [Сортировка пузырьком](#сортировка-пузырьком--bubble-sort)
    - [Быстрая сортировка](#быстрая-сортировка--quick-sort)
    - [Сортировка выбором](#сортировка-выбором--selection-sort)
    - [Универсальная](#универсальная)
    - [Сортировка перемешиванием](#сортировка-перемешиванием--shaker-cocktail-ripple-shuffle-shuttle-sort)
    - [Сортировка расчёской](#сортировка-расчёской--comb-sort)
    - [Гномья](#гномья--gnome-sort)
    - [Сортировка вставками](#сортировка-вставками--insertion-sort)
    - [Сортировка слиянием](#сортировка-слиянием--merge-sort)
    - [Терпеливая (Пасьянсная)](#терпеливая-пасьянсная--patience-sort)
    - [Сортировка Шелла](#сортировка-шелла--shell-sort)
- [Cтруктуры данных](#cтруктуры-данных)
    - [Связный список](#связный-список)
    - [Стек](#очередь-и-стек)
    - [Очередь](#очередь-и-стек)
    - [Граф](#граф)
    - [Деревья](#деревья)
    - [Куча](#куча)
    - [Хэш-таблица](#хэш)

---

Ссылки:

[//]: # (https://vk.com/@miet_acm-lekciya-2-algoritmy-sortirovki-i-poiska)

[классы//]: # (https://github.com/bazgalev/algorithms)

[хэш//]: # (https://habr.com/ru/post/422259/)

[хэш//]: # (https://habr.com/ru/company/vk/blog/308240/)

[tree//]: # (https://habr.com/ru/post/190176/#Tree)

[tree//]: # (https://russianblogs.com/article/5588930940/)


- https://bimlibik.github.io/posts/sorting-algorithm/

- [https://translated.turbopages.org/](https://translated.turbopages.org/proxy_u/en-ru.ru.f055177c-63f33c99-24f60e28-74722d776562/https/www.geeksforgeeks.org/searching-algorithms/#algo)

## Алгоритмическая сложность

`Сложность алгоритма` - это количественная характеристика, которая говорит о том, сколько времени, либо какой объём
памяти
потребуется для выполнения алгоритма.

`Временная сложность в худшем случае `– функция размера входа, равная максимальному количеству операций, выполненных в
ходе работы алгоритма при решении задачи данного размера.

`Ёмкостная сложность в худшем случае` – функция размера входа, равная максимальному количеству ячеек памяти, к которым
было обращение при решении задач данного размера.

`Асимптотически норма́льная оценка` — в математической статистике оценка, распределение которой стремится к
нормальному распределению при увеличении размера выборки. (сложность зависит от количества элементов)

Измерение времени выполнения алгоритма.

```php
$start = microtime(true);
quickSort($items);
print_r(microtime(true) - $start);

// или
function microseconds() {
    $mt = explode(' ', microtime());
    [$msec, $sec] = $mt;
    return (int)$sec * 1000000 + ((int) round($msec * 1000000));
}
```

Довольно часто время выполнения алгоритмов имеет большой разброс, в этом случае использують статический метод. Нужно
разделить общее время выполнения на количество запусков, чтобы вычислить среднее время выполнения:

```php
function averagePerformance($f, $count)
{
    $start = microseconds();

    for ($i = 0; $i < $count; $i++) {
        $f();
    }

    $end = microseconds();
    return ($end - $start) / $count;
}

$items = [86, 66, 44, 77, 56, 64, 76, 39, 32, 93, 33, 54, 63, 96, 5, 41, 20, 58, 55, 28];
print_r(averagePerformance(fn() => quickSort([...$items]), 1)); // [...$items] - копия массива
print_r(averagePerformance(fn() => quickSort([...$items]), 10)); 
```

Оценка сложности:

- `O` – оценка для худшего случая (нотация О-большое, "Ordnung")
- `Ω` - оценка для хорошего случая
- `Θ` - оценка для среднего случая

Порядок роста функций (расположены в порядке возрастания сложности):

- `O(1)` — константная (Длина массива)
- `O(log n)` — логарифмическая сложность (Бинарный поиск)
- `O(n)` — линейная сложность (Поиск методом перебора)
- `O(n log n)`— линейно-логарифмическая сложность (Быстрая сортировка)
- `O(n^2)` — квадратичная сложность (Пузырьковая сортировка)
- `2^O(n)` — экспоненциальная (Список подмассивов)
- `O(n!)` — факториальная сложность (Список перестановок)

На [этой](https://www.bigocheatsheet.com/) веб-странице рассматриваются пространственные и временные сложности
распространенных алгоритмов, используемых в информатике

Подсчет памяти

```php
echo "Start\n";
memoryUsage(memory_get_usage(), $base_memory_usage);

$a = someBigValue();

echo "String value setted\n";
memoryUsage(memory_get_usage(), $base_memory_usage);

// Чтобы получить использование памяти в привычном виде
function memoryUsage($usage, $base_memory_usage)
{
    $mem_usage = $usage - $base_memory_usage;
    if ($mem_usage < 1024) {
        echo $mem_usage . " bytes";
    } elseif ($mem_usage < 1048576) {
        echo round($mem_usage / 1024, 2) . " kilobytes";
    } else {
        echo round($mem_usage / 1048576, 2) . " megabytes";
    }
}
```

[⏏ К содержанию](#содержание)

---

## Алгоритмы поиска

## Линейный поиск

`Линейный поиск` - самый простой метод поиска. Элемент которые нужно
найти, последовательно ищется в списке.
Этот метод может выполняться для отсортированного или несортированного списка (обычно массивов).

```php
function search($arr, $x)
{
    $n = count($arr);
    for($i = 0; $i < $n; $i++)
    {
        if($arr[$i] == $x)
            {return $i;}
    }
    return -1;
}

function searchRecursive($arr, int $size, int $x)
{
    if ($size == 0)
        {return -1;}
    else {if ($arr[$size - 1] == $x)
        {return $size - 1;}} // return index
    return searchRecursive($arr, $size - 1, $x);
}
```

Когда использовать линейный поиск:

- Когда мы имеем дело с небольшим набором данных.
- Когда вам нужно найти точное значение.
- При поиске в наборе данных, хранящихся в непрерывной памяти.
- Когда вы хотите реализовать простой алгоритм.

Временная сложность линейного поиска равна O (n).

[⏏ К содержанию](#содержание)

## Бинарный поиск

`Бинарный поиск `- это алгоритм поиска, используемый в отсортированном массиве путем многократного деления интервала
поиска пополам. Идея бинарного поиска состоит в том, чтобы использовать информацию о том, что массив отсортирован, и
уменьшить временную сложность до O (Log n).

Плюсы:

1. Реализация алгоритма достаточна легкая (Относительно)
2. Быстрая работа алгоритма

Минусы:

1. Для поиска массив должен быть упорядочен(отсортирован)
2. Двоичный поиск должен иметь возможность обратится к любому элементу данных (по индексу). А это значит, что все
   структуры данных, которые построены на связных списках использоваться не могут

Если нужно искать в небольших массивах, можно использовать метод перебора — он будет работать со сравнимой скоростью или
даже быстрее.

<details>
<summary>⏺binarySearch</summary>

```php
$stopWords = ['а', 'без', 'ближе', 'браво', 'бы', 'вам', 'вас', 'весь', 'во', 'все', 'всего', 'вы'];

function isStopWord($candidate)
{
    $first = 0;
    $last = count($stopWords) - 1;

    while ($first <= $last) {
        $middle = round(($first + $last) / 2);

        if ($candidate === $stopWords[$middle]) {
            return true;
        }

        if ($candidate < $stopWords[$middle]) {
            $last = $middle - 1;
        } else {
            $first = $middle + 1;
        }
    }

    return false;
};

// Рекурсивный способ
function binarySearch($items, $value, $left, $right)
{
   if ($left > $right) {
      return -1;
   }

   $middle = round(($left + $right) / 2);
   if ($value === $items[$middle]) {
      return $middle;
   }

   if ($value < $items[$middle]) {
      return binarySearch($items, $value, $left, $middle - 1);
   }

   return binarySearch($items, $value, $middle + 1, $right);
}

$items = [-3, -1, 1, 3, 5, 7, 9, 11];
print_r(binarySearch($items, 100, 0, count($items) )); // => -1
print_r(binarySearch($items, -1, 0, count($items) )); // => 1
print_r(binarySearch($items, 7, 0, count($items) )); // => 5
```

</details>

Когда использовать бинарный поиск:

- При поиске в большом наборе данных, поскольку он имеет временную сложность O (log n), что означает, что он намного
  быстрее, чем линейный поиск.
- Когда набор данных отсортирован.
- Когда данные хранятся в непрерывной памяти.
- Данные не имеют сложной структуры или взаимосвязей.

Временная сложность: O (log n)

[⏏ К содержанию](#содержание)

## Экспоненциальный поиск

Экспоненциальный поиск включает в себя два этапа:

1. Найдите диапазон, в котором присутствует элемент
2. Выполните бинарный поиск в указанном выше диапазоне.

<details>
<summary>⏺exponentialSearch</summary>

```php
function exponentialSearch($arr, $n, $x)
{
    // If x is present at first location itself
    if ($arr[0] == $x) {
        return 0;
    }

    // Find range for binary search by repeated doubling
    $i = 1;
    while ($i < $n and $arr[$i] <= $x) {
        $i = $i * 2;
    }
    return binarySearch($arr, $i / 2, min($i, $n - 1), $x);
}

function binarySearch($arr, $l, $r, $x)
{
    if ($r >= $l) {
        $mid = $l + ($r - $l) / 2;

        if ($arr[$mid] == $x) {
            return $mid;
        }

        if ($arr[$mid] > $x) {
            return binarySearch($arr, $l, $mid - 1, $x);
        }

        return binarySearch($arr, $mid + 1, $r, $x);
    }
    return -1;
}
```

</details>

Применение экспоненциального поиска:

- Экспоненциальный двоичный поиск особенно полезен для неограниченного поиска, где размер массива бесконечен.
  Пожалуйста,
  обратитесь к неограниченному бинарному поиску для примера.
- Это работает лучше, чем бинарный поиск для ограниченных массивов, а также когда элемент, который нужно искать, ближе к
  первому элементу.

[⏏ К содержанию](#содержание)

---

# Сортировки

Существуют десятки алгоритмов сортировки,вот три фундаментальных алгоритма:

- Пузырьковая сортировка
- Сортировка выбором
- Быстрая сортировка
- Универсальная

![algorithms1.png](../../assets/algorithms1.png)

На [этой](https://www.bigocheatsheet.com/) веб-странице рассматриваются пространственные и временные сложности
распространенных сортировок, используемых в информатике.

## Сортировка пузырьком / Bubble sort

Этот алгоритм считается учебным и почти не применяется на практике из-за низкой эффективности: он медленно работает на
тестах, в которых маленькие элементы (их называют «черепахами») стоят в конце массива. Однако на нём основаны многие
другие методы, например, шейкерная сортировка и сортировка расчёской.

<details>
<summary>⏺bubbleSort</summary>

```php
function bubbleSort1(array $data)
{
    $count_elements = count($data);
    $iterations = $count_elements - 1;

    for ($i = 0; $i < $count_elements; $i++) {
        $changes = false;
        for ($j = 0; $j < $iterations; $j++) {
            if ($data[$j] > $data[($j + 1)]) {
                $changes = true;
                list($data[$j], $data[($j + 1)]) = array($data[($j + 1)], $data[$j]);
            }
        }
        $iterations--;
        if (!$changes) {
            return $data;
        }
    }
    return $data;
}

function bubbleSort2(array $array)
{
    // перебираем массив
    for ($j = 0; $j < count($array) - 1; $j++) {
        for ($i = 0; $i < count($array) - $j - 1; $i++) {
            // если текущий элемент больше следующего
            if ($array[$i] > $array[$i + 1]) {
                // меняем местами элементы
                $tmp_var = $array[$i + 1];
                $array[$i + 1] = $array[$i];
                $array[$i] = $tmp_var;
            }
        }
    }
    return $array;
}

function bubbleSort3(array &$array) {
  $c = count($array) - 1;
  do {
    $swapped = false;
    for ($i = 0; $i < $c; ++$i) {
      if ($array[$i] > $array[$i + 1]) {
      
//                $tmp = $arr[$i];
//                $arr[$i] = $arr[$k];
//                $arr[$k] = $tmp;
      
        list($array[$i + 1], $array[$i]) =
                array($array[$i], $array[$i + 1]);
        $swapped = true;
      }
    }
  } while ($swapped);
  return $array;
}

```

</details>

| Сложность | 	Лучшее	 | Среднее | Худшее |
|-----------|----------|---------|--------|
| Время     | 	O(n)    | O(n^2)  | O(n^2) |
| Память    | 	 	      |         | O(1)   |

[⏏ К содержанию](#содержание)

## Быстрая сортировка / Quick Sort

Общая идея алгоритма:

- Выбрать из массива элемент, который обычно называют опорным. Это может быть любой элемент из массива. От выбора
  опорного элемента не зависит корректность алгоритма, но в отдельных случаях может сильно зависеть его эффективность.
- Сравнить все остальные элементы с опорным и переставить их в массиве так, чтобы разбить массив на три непрерывных
  отрезка, следующих друг за другом: «элементы меньшие опорного», «равные» и «большие».
- Рекурсивно применить первые два шага к отрезкам, содержащим «меньшие» и «большие» значения. Не применять к массиву, в
  котором только один элемент или отсутствуют элементы.

<details>
<summary>⏺quickSort</summary>

```php
function quickSort($arr){
	$loe = $gt = array();
	if(count($arr) < 2){
		return $arr;
	}
	$pivot_key = key($arr);
	$pivot = array_shift($arr);
	foreach($arr as $val){
		if($val <= $pivot){
			$loe[] = $val;
		}elseif ($val > $pivot){
			$gt[] = $val;
		}
	}
	return array_merge(quicksort($loe),array($pivot_key=>$pivot),quicksort($gt));
}

class QuickSorting implements SortingInterface
{
    public function sort(array $items): array
    {
        if (count($items) < 2) {
            return $items;
        }

        $pivot = array_shift($items);

        [$right, $left] = $this->partition($items, $pivot);

        return array_merge(
            $this->sort($left),
            [$pivot],
            $this->sort($right)
        );
    }

    // Простейшая реализации разделения
    private function partition(array $items, int $pivot): array
    {
        $right = [];
        $left = [];

        foreach ($items as $item) {
            $item > $pivot ?
                $right[] = $item :
                $left[] = $item;
        }

        return [$right, $left];
    }
}
```

</details>

| Сложность | Лучшее | Среднее    | Худшее |
|-----------|--------|------------|--------|
| Время     | 	O(n)  | O(n log n) | O(n^2) |
| Память    |        |            | O(n)   |

## Сортировка выбором / Selection Sort

Сортировка выбором - это алгоритм, при котором многократно осуществляется поиск минимального элемента в
неотсортированной части массива и его помещение в конец отсортированной части массива.

Общая идея алгоритма:

- Данный алгоритм условно делит массив на две части:
    - подмассив, который уже отсортирован (находится в левой части массива),
    - подмассив, который нужно отсортировать (находится в правой части массива).
- Поиск минимального значения в неотсортированном массиве. Найденное значение меняем местами с первым элементом в
  неотсортированном массиве.
- Повторяем предыдущий шаг до тех пор, пока массив не будет отсортирован.

<details>
<summary>⏺selectionSort</summary>

```php
function selectionSort(&$arr) {
    $n = count($arr);
    for($i = 0; $i < count($arr); $i++) {
        $min = $i;
        for($j = $i + 1; $j < $n; $j++){
            if($arr[$j] < $arr[$min]){
                $min = $j;
            }
        }
       
        list($arr[$i],$arr[$min]) = array($arr[$min],$arr[$i]);
    }
}
```

</details>

| Сложность | Лучшее | Среднее | Худшее |
|-----------|--------|---------|--------|
| Время     | O(n^2) | O(n^2)  | O(n^2) |
| Память    |        |         | O(1)   |

## Универсальная

Каждую из трех видов сортировок выше можно сделать универсальной — и тогда алгоритм сможет сортировать данные любого
типа. Для этого надо добавить еще один параметр — функцию сравнения (компаратор). Универсальная функция сортировки
вызывает компаратор каждый раз, когда требуется сравнить два элемента и определить, какой из них больше.

<details>
<summary>⏺compare</summary>

```php
$products = [
    ['name' => 'Телевизор', 'price' => 100000, 'rating' => 9.1],
    ['name' => 'Холодильник', 'price' => 20000, 'rating' => 8.3],
    ['name' => 'Пылесос', 'price' => 14000, 'rating' => 7.5],
    ['name' => 'Стиральная машина', 'price' => 30000, 'rating' => 9.2],
    ['name' => 'Миелофон', 'price' => 200000, 'rating' => 8.7],
    ['name' => 'Микроволновка', 'price' => 7000, 'rating' => 8.2],
    ['name' => 'Проигрыватель', 'price' => 20000, 'rating' => 9.0],
    ['name' => 'Посудомоечная машина', 'price' => 80000, 'rating' => 7.8],
    ['name' => 'Мультиварка', 'price' => 5000, 'rating' => 7.1],
];

function compareByPrice($item1, $item2): int
{
    if ($item1['price'] < $item2['price']) {
        return -1;
    } else {if ($item1['price'] == $item2['price']) {
        return 0;
    } else {
        return 1;
    }}
}

function bubbleSort(&$items, $comparator)
{
    for ($limit = count($items) - 1; $limit > 0; $limit -= 1) {
        for ($i = 0; $i < $limit; $i += 1) {
            if ($comparator($items[$i], $items[$i + 1]) > 0) {
                $temporary = $items[$i];
                $items[$i] = $items[$i + 1];
                $items[$i + 1] = $temporary;
            }
        }
    }
}

bubbleSort($products, 'compareByPrice');
```

</details>

## Сортировка перемешиванием / Shaker (cocktail, ripple, shuffle, shuttle) sort

Также известна как шейкерная или коктейльная сортировка.

Сортировка перемешиванием - это разновидность сортировки пузырьком. Отличие в том, что данная сортировка в рамках одной
итерации проходит по массиву в обоих направлениях (слева направо и справа налево), тогда как сортировка пузырьком -
только в одном направлении (слева направо).

<details>
<summary>⏺cocktailSort (Перемешиванием)</summary>

```php
function cocktailSort($arr){
	if (is_string($arr)) {$arr = str_split(preg_replace('/\s+/','',$arr));}

	do{
		$swapped = false;
		for($i=0;$i<count($arr);$i++){
			if(isset($arr[$i+1])){
				if($arr[$i] > $arr[$i+1]){
					list($arr[$i], $arr[$i+1]) = array($arr[$i+1], $arr[$i]);
					$swapped = true;
				}
			}			
		}
 
		if ($swapped == false) {break;}
 
		$swapped = false;
		for($i=count($arr)-1;$i>=0;$i--){
			if(isset($arr[$i-1])){
				if($arr[$i] < $arr[$i-1]) {
					list($arr[$i],$arr[$i-1]) = array($arr[$i-1],$arr[$i]);
					$swapped = true;
				}
			}
		}
	}while($swapped);
 
	return $arr;
}
```

</details>

| Сложность | Лучшее | Среднее | Худшее |
|-----------|--------|---------|--------|
| Время     | O(n)   | O(n^2)  | O(n^2) |
| Память    |        |         | O(1)   |

Сложность у алгоритма такая же, как и у сортировки пузырьком, однако реальное время работы лучше (обычно менее чем в два
раза быстрее).

## Сортировка расчёской / Comb sort

Сортировка расчёской - еще одна разновидность сортировки пузырьком. Данная сортировка улучшает сортировку пузырьком за
счет устранения маленьких значений в конце списка (черепах).

Достигается это тем, что вместо сравнения соседних элементов, сравниваются элементы на достаточно большом расстоянии
друг от друга, постепенно уменьшая это расстояние. Сначала разрыв между элементами берётся максимальный, т.е. на единицу
меньше, чем размер массива. Затем на каждой итерации расстояние уменьшается путём деления расстояния на фактор
уменьшения. Так продолжается до тех пор, пока разность индексов сравниваемых элементов не достигнет единицы. Тогда
сравниваются уже соседние элементы как и в сортировке пузырьком, но эта итерация будет последней.

Оптимальное значение фактора уменьшения - 1,247.

<details>
<summary>⏺combSort</summary>

```php
function combSort($arr){
	$gap = count($arr);
        $swap = true;
	while ($gap > 1 || $swap){
		if($gap > 1) {$gap /= 1.25;}

		$swap = false;
		$i = 0;
		while($i+$gap < count($arr)){
			if($arr[$i] > $arr[$i+$gap]){
				list($arr[$i], $arr[$i+$gap]) = array($arr[$i+$gap],$arr[$i]);
				$swap = true;
			}
			$i++;
		}
	}
	return $arr;
}
```

</details>

| Сложность | Лучшее     | Среднее                                    | Худшее  |
|-----------|------------|--------------------------------------------|---------|
| Время     | O(n log n) | Ω(n^2/2^p), где p - количество инкрементов | 	O(n^2) |
| Память    |            |                                            | O(1)    |

## Гномья / Gnome sort

Алгоритм сравнивает рядом стоящие горшки, если они стоят в нужном порядке, тогда мы переходим на следующий элемент
массива, если нет, ты мы их переставляем и переходим на предыдущий. Нет предыдущего элемента — идём вперед, нет
следующего — значит мы закончили. Изначально мы находимся на втором элементе массива.

<details>
<summary>⏺gnomeSort</summary>

```php
function gnomeSort($arr){
	$i = 1;
	$j = 2;
	while($i < count($arr)){
		if ($arr[$i-1] <= $arr[$i]){
			$i = $j;
			$j++;
		}else{
			list($arr[$i],$arr[$i-1]) = array($arr[$i-1],$arr[$i]);
			$i--;
			if($i == 0){
				$i = $j;
				$j++;
			}
		}
	}
	return $arr;
}
```

</details>

На практике алгоритм может работать так же быстро, как и сортировка вставками.

## Сортировка вставками / Insertion sort

Сортировка вставками - алгоритм, при котором каждый последующий элемент массива сравнивается с предыдущими элементами (
отсортированными) и вставляется в нужную позицию.

Общая идея алгоритма:

- Сравниваем второй элемент с первым элементом массива и при необходимости меняем их местами. Условно эти элементы (
  первый и второй) будут являться отсортированным массивом, остальные элементы - неотсортированным.
- Сравниваем следующий элемент из неотсортированного массива с элементами отсортированного и вставляем в нужную позицию.
- Повторям шаг 2 до тех пор, пока в неотсортированном массиве не останется элементов.

<details>
<summary>⏺insertionSort</summary>

```php
function insertionSort(&$arr){
	for($i=0;$i<count($arr);$i++){
		$val = $arr[$i];
		$j = $i-1;
		while($j>=0 && $arr[$j] > $val){
			$arr[$j+1] = $arr[$j];
			$j--;
		}
		$arr[$j+1] = $val;
	}
}
```

</details>

| Сложность | 	Лучшее | Среднее | Худшее |
|-----------|---------|---------|--------|
| Время     | O(n)    | O(n^2)  | O(n^2) |
| Память    |         |         | O(1)   |

## Сортировка слиянием / Merge sort

Общая идея алгоритма:

- Массив разбивается на две части примерно одинакового размера. Разбиение получившихся массивов повторяется до тех пор,
  пока размер каждого массива не достигнет единицы.
- Каждая из получившихся частей сортируется отдельно, после чего происходит слияние двух массивов следующим образом:
    - На каждом шаге сравниваем первые элементы массивов, берём наименьшее значение и записываем в результирующий
      массив.
    - Когда один из массив закончился, добавляем оставшиеся элементы второго массива в результирующий массив.
- Слияние подмассивов продолжается до тех пор, пока не получим один, отсортированный массив.

<details>
<summary>⏺mergeSort</summary>

```php
function mergeSort($arr){
	if(count($arr) == 1 ) {return $arr;}
	$mid = count($arr) / 2;
    $left = array_slice($arr, 0, $mid);
    $right = array_slice($arr, $mid);
	$left = mergesort($left);
	$right = mergesort($right);
	return merge($left, $right);
}
 
function merge($left, $right){
	$res = array();
	while (count($left) > 0 && count($right) > 0){
		if($left[0] > $right[0]){
			$res[] = $right[0];
			$right = array_slice($right , 1);
		}else{
			$res[] = $left[0];
			$left = array_slice($left, 1);
		}
	}
	while (count($left) > 0){
		$res[] = $left[0];
		$left = array_slice($left, 1);
	}
	while (count($right) > 0){
		$res[] = $right[0];
		$right = array_slice($right, 1);
	}
	return $res;
}
```

</details>

| Сложность | Лучшее     | Среднее    | Худшее     |
|-----------|------------|------------|------------|
| Время     | O(n log n) | O(n log n) | O(n log n) |
| Память    |            |            | O(n)       |

## Терпеливая (Пасьянсная) / Patience sort

Терпеливая сортировка (англ. patience sorting) — алгоритм сортировки с худшей сложностью O(n log n). Позволяет также
вычислить длину наибольшей возрастающей подпоследовательности данного массива. Алгоритм назван по одному из названий
карточной игры "Солитёр" — "Patience".

<details>
<summary>⏺patienceSort</summary>

```php
class PilesHeap extends SplMinHeap {
    public function compare($pile1, $pile2) {
        return parent::compare($pile1->top(), $pile2->top());
    }
}
 
function patienceSort(&$n) {
    $piles = array();
    // sort into piles
    foreach ($n as $x) {
        // binary search
        $low = 0; $high = count($piles)-1;
        while ($low <= $high) {
            $mid = (int)(($low + $high) / 2);
            if ($piles[$mid]->top() >= $x)
                {$high = $mid - 1;}
            else
                {$low = $mid + 1;}
        }
        $i = $low;
        if ($i == count($piles))
            {$piles[] = new SplStack();}
        $piles[$i]->push($x);
    }
 
    // priority queue allows us to merge piles efficiently
    $heap = new PilesHeap();
    foreach ($piles as $pile)
        {$heap->insert($pile);}
    for ($c = 0; $c < count($n); $c++) {
        $smallPile = $heap->extract();
        $n[$c] = $smallPile->pop();
        if (!$smallPile->isEmpty())
        {$heap->insert($smallPile);}
    }
    assert($heap->isEmpty());
}
```

</details>

| Сложность | Лучшее     | Среднее    | Худшее     |
|-----------|------------|------------|------------|
| Время     | O(n log n) | O(n log n) | O(n log n) |
| Память    |            |            | O(n)       |

## Сортировка Шелла / Shell sort

Сортировка Шелла - усовершенствованная разновидность сортировки вставками.

Сначала сравниваются и сортируются между собой значения, стоящие друг от друга на некотором расстоянии - d. После этого
расстояние d уменьшается и процедура повторяется до тех пор, пока значение d не станет минимальным, т.е. d = 1. Это
означает, что сортировка достигла последнего шага. А на последнем шага элементы сортируются обычной сортировкой
вставками.

<details>
<summary>⏺shellSort</summary>

```php
function shellSort($arr)
{
	$inc = round(count($arr)/2);
	while($inc > 0)
	{
		for($i = $inc; $i < count($arr);$i++){
			$temp = $arr[$i];
			$j = $i;
			while($j >= $inc && $arr[$j-$inc] > $temp)
			{
				$arr[$j] = $arr[$j - $inc];
				$j -= $inc;
			}
			$arr[$j] = $temp;
		}
		$inc = round($inc/2.2);
	}
	return $arr;
}
```

</details>

| Сложность | Лучшее     | Среднее                         | Худшее                                             |
|-----------|------------|---------------------------------|----------------------------------------------------|
| Время     | O(n log n) | зависит от выбранных шагов (d)	 | O(n2) или O(n log2 n) (зависит от выбранных шагов) |
| Память    |            |                                 | O(1)                                               |

[⏏ К содержанию](#содержание)

---

## Cтруктуры данных

`Структура данных` — это способ организации информации для более эффективного использования. В программировании
структурой
обычно называют набор данных, связанных определённым образом.

Популярные структуры:

- [Связный список](#связный-список)
- [Стек](#очередь-и-стек)
- [Очередь](#очередь-и-стек)
- [Граф](#граф)
- [Деревья](#деревья)
- [Куча](#куча)
- Множество
- Map
- [Хэш-таблица](#хэш)

Библиотека DS имеет в себе эффективные структуры данных для PHP 7, представленные как альтернативы для типа array

```php
git clone https://github.com/php-ds/extension "php-ds"
```

Либо использовать Standard PHP Library (SPL), которая является частью ядра PHP.
Подробнее [тут](https://www.php.net/manual/en/book.spl.php).

![algorithms2.png](../../assets/algorithms2.png)

[⏏ К содержанию](#содержание)

## Связный список

`Связанный список` – массив, где каждый элемент является отдельным объектом и состоит из двух элементов – данных и
ссылки
на следующий узел.

Бывают:

- `Однонаправленный`, каждый узел хранит адрес или ссылку на следующий узел в списке, и последний узел имеет следующий
  адрес или ссылку как NULL. (1->2->3->4->NULL)

- `Двунаправленный`, две ссылки, связанные с каждым узлом, одним из опорных пунктов на следующий узел и один к
  предыдущему
  узлу. (NULL<-1<->2<->3->NULL)

- `Круговой`, все узлы соединяются, образуя круг. В конце нет NULL. Циклический связанный список может быть одно-или
  двукратным циклическим связанным списком. (1->2->3->1)

Основные операции

- insertAtEnd — Вставка заданного элемента в конец списка
- insertAtHead — Вставка элемента в начало списка
- delete — удаляет заданный элемент из списка
- deleteAtHead — удаляет первый элемент списка
- search — возвращает заданный элемент из списка
- isEmpty — возвращает True, если связанный список пуст

<details>
<summary>⏺класс LinkedList</summary>

```php
class LinkedListNode
{
    public function __construct($value, $next = null)
    {
        $this->value = $value;
        $this->next = $next;
    }
}


class LinkedList
{

    public function __construct()
    {
        $this->head = null;
        $this->tail = null;
    }

    // Вставка элемента в начало списка
    public function insertAtHead($value)
    {
        // Делаем новый узел головой
        $newNode = new LinkedListNode($value, $this->head);
        $this->head = $newNode;

        // Если нет хвоста, этот узел будет и хвостом
        if (!$this->tail) {
            $this->tail = $newNode;
        }

        return $this;
    }

    // Вставка элемента в конец списка
    public function insertAtEnd($value)
    {
        $newNode = new LinkedListNode($value);

        // Если нет головы, этот узел будет головой
        if (!$this->head) {
            $this->head = $newNode;
            $this->tail = $newNode;

            return $this;
        }

        // Присоединяем новый узел к концу, делаем его хвостом
        $this->tail->next = $newNode;
        $this->tail = $newNode;

        return $this;
    }

    // Удаление элемента
    public function deleteAtHead()
    {
        if ($this->head === null) {
            return undefined;
        }
    
        $value = $this->head->value;
        $this->head = $this->head->next;
    
        return $value;
    }

    // Удаление элемента
    public function delete($value)
    {
        if (!$this->head) {
            return null;
        }

        $deletedNode = null;
        // Проверяем с головы какие ноды надо удалить
        while ($this->head && $this->head->value === $value) {
            $deletedNode = $this->head;
            $this->head = $this->head->next;
        }

        $currentNode = $this->head;

        // Если у головы не нашли, проверяем остальные значения в списке
        if ($currentNode !== null) {
            while ($currentNode->next) {
                if ($currentNode->next->value === $value) {
                    $deletedNode = $currentNode->next;
                    $currentNode->next = $currentNode->next->next;
                } else {
                    $currentNode = $currentNode->next;
                }
            }
        }

        // Проверяем хвост
        if ($this->tail->value === $value) {
            $this->tail = $currentNode;
        }

        return $deletedNode;
    }

    // Поиск элемента
    public function search($value)
    {
        if (!$this->head) {
            return null;
        }

        $currentNode = $this->head;

        // Перебираем список с головы, первое найденное значение возвращаем
        while ($currentNode) {
            if ($value !== null && $currentNode->value === $value) {
                return $currentNode;
            }

            // Делаем текущей следующий элемент списка
            $currentNode = $currentNode->next;
        }

        return null;
    }

    // проверку на пустоту
    public function isEmpty()
    {
        return $this->head === null && $this->tail === null;
    }

    public function toArray()
    {
        $result = [];

        if (!$this->head) {
            return $result;
        }

        $currentNode = $this->head;

        while ($currentNode) {
            if ($currentNode->value !== null) {
                $result[] = $currentNode->value;
            }

            $currentNode = $currentNode->next;
        }

        return $result;
    }

    // Определение длины списка
    public function length()
    {
        $result = 0;
        $current = $this->head;

        while ($current !== null) {
            $result = $result + 1;
            $current = $current->next;
        }

        return $result;
    }

    // Вставка элемента в середину или конец списка
    public function insert($index, $value)
    {
        if ($this->head === null) {
            $this->head = new LinkedListNode($value, null);
        } else {
            if ($index === 0) {
                $this->insertAtHead($value);
            } else {
                $current = $this->head;
                while ($current->next !== null && $index > 0) {
                    $current = $current->next;
                    $index = $index - 1;
                }

                $current->next = new LinkedListNode($value, $current->next);
            }
        }
    }
}


$node = new LinkedListNode(1, new LinkedListNode(4, null));
$list = new LinkedList();
```

</details>

|                      | Массив | Список |
|----------------------|--------|--------|
| Вставка в начало     | O(n)   | O(1)   |
| Вставка в середину   | O(n)   | O(n)   |
| Вставка в конец      | O(n)   | O(n)   |
| Доступ по индексу    | O(1)   | O(n)   |
| Удаление из начала   | O(n)   | O(1)   |
| Удаление из середины | O(n)   | O(n)   |
| Удаление из конца    | O(n)   | O(n)   |
| Поиск                | O(n)   | O(n)   |
| Длина                | O(1)   | O(n)   |

<details>
<summary>⏺класс DoubleLinkedList</summary>

```php
class DoubleLinkedListNode
{
    public function __construct($value, $next = null, $previous = null)
    {
        $this->value = $value;
        $this->next = $next;
        $this->previous = $previous;
    }
}

class DoubleLinkedList
{
    public function __construct()
    {
        $this->head = null;
        $this->tail = null;
    }

    public function prepend($value)
    {
        $newNode = new DoubleLinkedListNode($value, $this->head);

        if ($this->head) {
            $this->head->previous = $newNode;
        }

        $this->head = $newNode;

        if (!$this->tail) {
            $this->tail = $newNode;
        }

        return $this;
    }

    public function append($value)
    {
        $newNode = new DoubleLinkedListNode($value);

        if (!$this->head) {
            $this->head = $newNode;
            $this->tail = $newNode;

            return $this;
        }

        $this->tail->next = $newNode;
        $newNode->previous = $this->tail;
        $this->tail = $newNode;

        return $this;
    }

    public function delete($value)
    {
        if (!$this->head) {
            return null;
        }

        $deletedNode = null;
        $currentNode = $this->head;

        while ($currentNode) {
            if ($currentNode->value === $value) {
                $deletedNode = $currentNode;

                if ($deletedNode === $this->head) {
                    $this->head = $deletedNode->next;

                    if ($this->head) {
                        $this->head->previous = null;
                    }

                    if ($deletedNode === $this->tail) {
                        $this->tail = null;
                    }
                } elseif ($deletedNode === $this->tail) {
                    $this->tail = $deletedNode->previous;
                    $this->tail->next = null;
                } else {
                    $previousNode = $deletedNode->previous;
                    $nextNode = $deletedNode->next;

                    $previousNode->next = $nextNode;
                    $nextNode->previous = $previousNode;
                }
            }

            $currentNode = $currentNode->next;
        }

        return $deletedNode;
    }

    public function find($value)
    {
        if (!$this->head) {
            return null;
        }

        $currentNode = $this->head;

        while ($currentNode) {
            if ($value !== null && $currentNode->value === $value) {
                return $currentNode;
            }

            $currentNode = $currentNode->next;
        }

        return null;
    }

    public function toArray()
    {
        $result = [];

        if (!$this->head) {
            return $result;
        }

        $currentNode = $this->head;

        while ($currentNode) {
            if ($currentNode->value !== null) {
                $result[] = $currentNode->value;
            }

            $currentNode = $currentNode->next;
        }

        return $result;
    }
}
```

</details>

- Сложность удаления с данным узлом равна O (1)
- Мы можем использовать двусвязный список для выполнения куч и стеков, бинарных деревьев.

[⏏ К содержанию](#содержание)

## Очередь и стек

`Стек` — абстрактный тип данных, представляющий собой список элементов, организованных по принципу LIFO (англ. last in —
first out, «последним пришёл — первым вышел»).

Основные операции

- push — вставляет элемент сверху
- pop — возвращает верхний элемент после удаления из стека
- isEmpt — возвращает true, если стек пуст
- top — возвращает верхний элемент без удаления из стека

```php
class Stack {
    public $items = [];

    public function push($value)
    {
        array_push($this->items, $value);
    }

    public function pop()
    {
        return array_pop($this->items);
    }

    public function isEmpty()
    {
        return count($this->items) == 0;
    }
}
```

<details>
<summary>⏺Реализация без встроенных функций </summary>

```php
class PhpStack
{

    protected array $data = [];
 
    protected int $length = 0;

    public function push($value = null)
    {
        $this->data[$this->length] = $value;
        $this->length++;
    }

    public function pop()
    {
        $result = $this->top();
        unset($this->data[$this->length - 1]);
        $this->length--;
        return $result;
    }

    public function top()
    {
        if ($this->length < 1) {
            throw new RuntimeException('Structure is empty');
        }

        return $this->data[$this->length - 1];
    }

    public function length(): int
    {
        return $this->length;
    }

    public function isEmpty(): bool
    {
        return $this->length < 1;
    }

}
```

</details>

<details>
<summary>⏺Реализация стека через односвязный список</summary>

```php
class Stack {
    public $items = new LinkedList();

    public function push($value)
    {
        $this->items->add($value);
    }

    public function pop()
    {
        return $this->items->remove();
    }

    public function isEmpty()
    {
        return $this->items->head === null;
    }
}
```

</details>

`Очередь` — хранит элемент последовательным образом. Существенное отличие от стека – использование FIFO (First in First
Out) вместо LIFO.

- enqueue — вставляет элемент в конец очереди
- dequeue — удаляет элемент из начала очереди
- isEmpty — возвращает значение true, если очередь пуста
- top — возвращает первый элемент очереди

```php
class Queue {
    public $items = new DoublyLinkedList();

    public function push($value)
    {
        $this->items->insertBegin($value);
    }

    public function isEmpty() {
        return $this->items->head === null;
    }
}
```

[⏏ К содержанию](#содержание)

## Граф

`Граф` — это набор узлов (вершин), которые соединены друг с другом в виде сети ребрами (дугами).

`Ориентированный`, ребра являются направленными, т.е. существует только одно доступное направление между двумя связными
вершинами.

`Неориентированные`, к каждому из ребер можно осуществлять переход в обоих направлениях.
Смешанные

<details>
<summary>⏺класс Graph (по возможности улучшить)</summary>

```php
class Graph
{
    protected $graph;
    protected $visited = array();

    public function __construct($graph) {
        $this->graph = $graph;
    }

    // найдем минимальное число прыжков (связей) между 2 узлами

    public function breadthFirstSearch($origin, $destination) {
        // пометим все узлы как непосещенные
        foreach ($this->graph as $vertex => $adj) {
            $this->visited[$vertex] = false;
        }

        // пустая очередь
        $q = new SplQueue();

        // добавим начальную вершину в очередь и пометим ее как посещенную
        $q->enqueue($origin);
        $this->visited[$origin] = true;

        // это требуется для записи обратного пути от каждого узла
        $path = array();
        $path[$origin] = new SplDoublyLinkedList();
        $path[$origin]->setIteratorMode(
            SplDoublyLinkedList::IT_MODE_FIFO|SplDoublyLinkedList::IT_MODE_KEEP
        );

        $path[$origin]->push($origin);

        $found = false;
        // пока очередь не пуста и путь не найден
        while (!$q->isEmpty() && $q->bottom() != $destination) {
            $t = $q->dequeue();

            if (!empty($this->graph[$t])) {
                // для каждого соседнего узла
                foreach ($this->graph[$t] as $vertex) {
                    if (!$this->visited[$vertex]) {
                        // если все еще не посещен, то добавим в очередь и отметим
                        $q->enqueue($vertex);
                        $this->visited[$vertex] = true;
                        // добавим узел к текущему пути
                        $path[$vertex] = clone $path[$t];
                        $path[$vertex]->push($vertex);
                    }
                }
            }
        }

        if (isset($path[$destination])) {
            echo "из $origin в $destination за ",
                count($path[$destination]) - 1,
            " прыжков";
            $sep = '';
            foreach ($path[$destination] as $vertex) {
                echo $sep, $vertex;
                $sep = '->';
            }
            echo "n";
        }
        else {
            echo "Нет пути из $origin в $destination";
        }
    }
}

$graph = array(
    'A' => array('B', 'F'),
    'B' => array('A', 'D', 'E'),
    'C' => array('F'),
    'D' => array('B', 'E'),
    'E' => array('B', 'D', 'F'),
    'F' => array('A', 'E', 'C'),
);

$g = new Graph($graph);

// минимальное число шагов из D в C
$g->breadthFirstSearch('D', 'C');
// вывод:
// из D в C за 3 шага
// D->E->F->C

// минимальное число шагов из B в F
$g->breadthFirstSearch('B', 'F');
// вывод:
// из B в F за 2 шага
// B->A->F

// минимальное число шагов из A в C
$g->breadthFirstSearch('A', 'C');
// вывод:
// из A в C за 2 шага
// A->F->C

// минимальное число шагов из A в G
$g->breadthFirstSearch('A', 'G');
// вывод:
// Пути из A в G нет
```

</details>

<details>
<summary>⏺Поиск оптимального пути</summary>

```php
class Dijkstra
{
    protected $graph;

    public function __construct($graph) {
        $this->graph = $graph;
    }

    public function shortestPath($source, $target) {
        // массив кратчайших путей к каждому узлу
        $d = array();
        // массив "предшественников" для каждого узла
        $pi = array();
        // очередь всех неоптимизированных узлов
        $Q = new SplPriorityQueue();

        foreach ($this->graph as $v => $adj) {
            $d[$v] = INF; // устанавливаем изначальные расстояния как бесконечность
            $pi[$v] = null; // никаких узлов позади нет
            foreach ($adj as $w => $cost) {
                // воспользуемся ценой связи как приоритетом
                $Q->insert($w, $cost);
            }
        }

        // начальная дистанция на стартовом узле - 0
        $d[$source] = 0;

        while (!$Q->isEmpty()) {
            // извлечем минимальную цену
            $u = $Q->extract();
            if (!empty($this->graph[$u])) {
                // пройдемся по всем соседним узлам
                foreach ($this->graph[$u] as $v => $cost) {
                    // установим новую длину пути для соседнего узла
                    $alt = $d[$u] + $cost;
                    // если он оказался короче
                    if ($alt < $d[$v]) {
                        $d[$v] = $alt; // update minimum length to vertex установим как минимальное расстояние до этого узла
                        $pi[$v] = $u;  // добавим соседа как предшествующий этому узла
                    }
                }
            }
        }

        // теперь мы можем найти минимальный путь
        // используя обратный проход
        $S = new SplStack(); // кратчайший путь как стек
        $u = $target;
        $dist = 0;
        // проход от целевого узла до стартового
        while (isset($pi[$u]) && $pi[$u]) {
            $S->push($u);
            $dist += $this->graph[$u][$pi[$u]]; // добавим дистанцию для предшествующих
            $u = $pi[$u];
        }

        // стек будет пустой, если нет пути назад
        if ($S->isEmpty()) {
            echo "Нет пути из $source в $target";
        }
        else {
            // добавим стартовый узел и покажем весь путь
            // в обратном (LIFO) порядке
            $S->push($source);
            echo "$dist:";
            $sep = '';
            foreach ($S as $v) {
                echo $sep, $v;
                $sep = '->';
            }
            echo "n";
        }
    }
}
$graph = array(
    'A' => array('B' => 3, 'D' => 3, 'F' => 6),
    'B' => array('A' => 3, 'D' => 1, 'E' => 3),
    'C' => array('E' => 2, 'F' => 3),
    'D' => array('A' => 3, 'B' => 1, 'E' => 1, 'F' => 2),
    'E' => array('B' => 3, 'C' => 2, 'D' => 1, 'F' => 5),
    'F' => array('A' => 6, 'C' => 3, 'D' => 2, 'E' => 5),
);

$g = new Dijkstra($graph);

$g->shortestPath('D', 'C');  // 3:D->E->C
$g->shortestPath('C', 'A');  // 6:C->E->D->A
$g->shortestPath('B', 'F');  // 3:B->D->F
$g->shortestPath('F', 'A');  // 5:F->D->A
$g->shortestPath('A', 'G');  // Нет пути из A в G
```

</details>

[⏏ К содержанию](#содержание)

## Деревья

`Дерево` — это иерархическая структура данных, состоящая из узлов (вершин) и ребер (дуг). Деревья по сути связанные
графы
без циклов.

Типы деревьев

- N дерево
- Сбалансированное дерево
- Бинарное дерево
- Дерево Бинарного Поиска
- AVL дерево
- 2-3-4 деревья

Три способа обхода дерева

- В прямом порядке (сверху вниз) — префиксная форма.
- В симметричном порядке (слева направо) — инфиксная форма.
- В обратном порядке (снизу вверх) — постфиксная форма.

<details>
<summary>⏺класс BinTree (нужно доделать)</summary>

```php
class Node
{
    public $value;
    public $left;
    public $right;
}

// pre-order (прямой порядок)– обработка текущего узла, а затем переход к левому и правому.
//in-order (симметричная) – сначала проход левой стороны, обработка текущего узла и обход правой стороны.
//post-order (обратный порядок) – обход левой и правой стороны, затем обработка текущего значения.
//level-order (в ширину) – обработка текущего значения, затем обработка потомков и переход на следующий уровень.

//https://habr.com/ru/post/190176/#Tree
//https://russianblogs.com/article/5588930940/

class BinTree
{
    // нерекурсивный
    // Обход среднего порядка
    // левое поддерево → корневой узел → правое поддерево
    public function inOrder($root)
    {
        $stack = array();
        $current_node = $root;
        while (!empty($stack) || $current_node != null) {
            while ($current_node != null) {
                $stack[] = $current_node;
                $current_node = $current_node->left;
            }
            $current_node = array_pop($stack);
            echo $current_node->value . " ";
            $current_node = $current_node->right;
        }
    }

    // рекурсия
    // Обход среднего порядка
    public function inOrderRecursive($root)
    {
        if ($root != null) {
            if ($root->left != null) {
                $this->inOrderRecursive($root->left); // рекурсивный обход левого дерева
            }
            echo $root->value . " ";
            if ($root->right != null) {
                $this->inOrderRecursive($root->right); // Рекурсивно пройти по правому дереву
            }
        }
    }

    // нерекурсивный
    // Предварительный порядок обхода корневого узла → левое поддерево → правое поддерево
    // Сначала посещаем корневой узел, затем проходим левое поддерево и, наконец, проходим правое поддерево;
    // и при обходе левого и правого поддеревьев вам все равно нужно сначала пройти через корневой узел, затем левое поддерево и, наконец, правое поддерево
    public function preOrder($root)
    {
        $stack = array();
        $stack[] = $root;
        while (!empty($stack)) {
            $center_node = array_pop($stack);
            echo $center_node->value . " "; // Сначала выводим корневой узел
            if ($center_node->right != null) {
                $stack[] = $center_node->right; // Толкаем левое поддерево
            }
            if ($center_node->left != null) {
                $stack[] = $center_node->left;
            }
        }
    }

    // рекурсия
    // обход предварительного заказа
    public function preOrderRecursive($root)
    {
        if ($root != null) {
            echo $root->value . " "; // корень
            if ($root->left != null) {
                $this->preOrderRecursive($root->left); // рекурсивно пройти по левому дереву
            }
            if ($root->right != null) {
                $this->preOrderRecursive($root->right); // рекурсивно проходим правое дерево
            }
        }
    }

    // нерекурсивный
    // Пост-порядок обхода левое поддерево → правое поддерево → корневой узел
    // Сначала пройти левое поддерево, затем пройти правое поддерево и, наконец, посетить корневой узел; аналогично, при обходе левого и правого поддеревьев сначала необходимо пройти левое поддерево, затем правое поддерево и, наконец, корневой узел
    public function postOrder($root)
    {
        $stack = array();
        $out_stack = array();
        $stack[] = $root;
        while (!empty($stack)) {
            $center_node = array_pop($stack);
            $out_stack[] = $center_node; // Сначала проталкиваем корневой узел и, наконец, выводим
            if ($center_node->left != null) {
                $stack[] = $center_node->left;
            }
            if ($center_node->right != null) {
                $stack[] = $center_node->right;
            }
        }
        while (!empty($out_stack)) {
            $center_node = array_pop($out_stack);
            echo $center_node->value . " ";
        }
    }

    // рекурсия
    // Пост-ордер обхода
    public function postOrderRecursive($root)
    {
        if ($root != null) {
            if ($root->left != null) {
                $this->postOrderRecursive($root->left); // Рекурсивно пройти по левому дереву
            }
            if ($root->right != null) {
                $this->postOrderRecursive($root->right); // Рекурсивно пройти по правому дереву
            }
            echo $root->value . " "; // корень
        }
    }

    // нерекурсивный
    public function levelOrder($root)
    {
        if ($root == null) {
            return;
        }
        $node = $root;
        $queue = array();
        $queue[] = $node; // Корневой узел присоединяется к очереди
        while (!empty ($queue)) {// Продолжаем вывод узлов, пока очередь не станет пустой
            $node = array_shift($queue); // Первый элемент команды гаснет
            echo $node->value . " ";
            // Левый узел первым входит в очередь
            if ($node->left != null) {
                $queue[] = $node->left;
            }
            // Затем правая нода присоединяется к команде
            if ($node->right != null) {
                $queue[] = $node->right;
            }
        }
    }

    // рекурсия
    // Получаем количество уровней дерева (максимальная глубина)
    function getDepth($root): int
    {
        if ($root == null) {// узел пуст
            return 0;
        }
        if ($root->left == null && $root->right == null) {// Только корневой узел
            return 1;
        }

        $left_depth = $this->getDepth($root->left);
        $right_depth = $this->getDepth($root->right);

        return ($left_depth > $right_depth ? $left_depth : $right_depth) + 1;
//        return $left_depth > $right_depth ? ($left_depth + 1) : ($right_depth + 1);
    }

    public function levelOrderRecursive($root)
    {
        // Пустое дерево или необоснованный уровень
        $depth = $this->getDepth($root);
        if ($root == null || $depth < 1) {
            return;
        }
        for ($i = 1; $i <= $depth; $i++) {
            $this->printTree($root, $i);
        }
    }

    public function printTree($root, $level)
    {
        // Пустое дерево или необоснованный уровень
        if ($root == null || $level < 1) {
            return;
        }
        if ($level == 1) {
            echo $root->value. " ";;
        }
        $this->printTree($root->left, $level - 1);
        $this->printTree($root->right, $level - 1);
    }
}

// контрольная работа
$a = new Node();
$b = new Node();
$c = new Node();
$d = new Node();
$e = new Node();
$f = new Node();
$g = new Node(); //
$h = new Node(); //

$a->value = "A";
$b->value = "B";
$c->value = "C";
$d->value = "D";
$e->value = "E";
$f->value = "F";
$g->value = "G";
$h->value = "H";

$a->left = $b;
$a->right = $c;
$b->left = $d;
$b->right = $e;
$c->left = $f;
$d->left = $g;
$d->right = $h;

$bst = new BinTree();

echo "---- 1 ----";
echo "\n";
echo "Безрекурсивный обход перед порядком:";
$bst->preOrder($a);
echo "\n";
echo "Обход рекурсии и предзаказа:";
$bst->preOrderRecursive($a);
echo "\n";

echo "----сначала глубина 2----";
echo "\n";
echo "Безрекурсивный обход среднего порядка:";
$bst->inOrder($a);
echo "\n";
echo "Обход среднего порядка рекурсии:";
$bst->inOrderRecursive($a);
echo "\n";

echo "---- сначала глубина 3----";
echo "\n";
echo "Безрекурсивный обход после заказа:";
$bst->postOrder($a);
echo "\n";
echo "Обход рекурсии и пост-порядка:";
$bst->postOrderRecursive($a);
echo "\n";


echo "---- сначала в ширину 4----";
echo "\n";
echo "Безрекурсивный:";
$bst->levelOrder($a);
echo "\n";
echo "Рекурсивный:";
$bst->levelOrderRecursive($a);
echo "\n";

//---- 1 ----
//Безрекурсивный обход перед порядком:A B D G H E C F
//Обход рекурсии и предзаказа:A B D G H E C F
//----сначала глубина 2----
//Безрекурсивный обход среднего порядка:G D H B E A F C
//Обход среднего порядка рекурсии:G D H B E A F C
//---- сначала глубина 3----
//Безрекурсивный обход после заказа:G H D E B F C A
//Обход рекурсии и пост-порядка:G H D E B F C A
//---- сначала в ширину 4----
//Безрекурсивный:A B C D E F G H
//Рекурсивный:A B C D E F G H

```

</details>

[⏏ К содержанию](#содержание)

## Куча

`Куча` — специальная, деревоподобная структура, у которой есть одно свойство — любой родительский узел всегда больше
либо равен своим потомкам. Таким образом, при выполнении данного условия, корневой элемент кучи всегда будет
максимальным. Данный вариант называют максимальной (полной) кучей или maxheap. Куча, где корневой элемент минимален, а
каждый родительский узел меньше или равен своим потомкам — минимальная куча или minheap.

<details>
<summary>⏺класс BinaryHeap</summary>

```php
// https://habr.com/ru/post/190474/
class BinaryHeap
{
    protected $heap;

    public function __construct()
    {
        $this->heap = array();
    }

    public function isEmpty()
    {
        return empty($this->heap);
    }

    public function count()
    {
        // возвращаем размер кучи
        return count($this->heap) - 1;
    }

    public function extract()
    {
        if ($this->isEmpty()) {
            throw new RunTimeException('Куча пуста!');
        }

        // извлечем первый элемент из кучи и присвоим его как корень
        $root = array_shift($this->heap);

        if (!$this->isEmpty()) {
            // переместим последний элемент кучи на ее вершину
            // чтобы избавиться от двух разделенных между собой ветвей
            $last = array_pop($this->heap);
            array_unshift($this->heap, $last);

            // превратим полукучу в кучу
            $this->adjust(0);
        }

        return $root;
    }

    public function compare($item1, $item2)
    {
        if ($item1 === $item2) {
            return 0;
        }
        // обратное сравнение даст нам minheap
        return ($item1 > $item2 ? 1 : -1);
    }

    protected function isLeaf($node)
    {
        // здесь всегда будет 2n + 1 узел в "подкуче"
        return ((2 * $node) + 1) > $this->count();
    }

    protected function adjust($root)
    {
        // спускаемся как можно ниже
        if (!$this->isLeaf($root)) {
            $left = (2 * $root) + 1; // левый потомок
            $right = (2 * $root) + 2; // правый потомок

            $h = $this->heap;
            // если корень меньше своих потомков
            if (
                (isset($h[$left]) &&
                    $this->compare($h[$root], $h[$left]) < 0)
                || (isset($h[$right]) &&
                    $this->compare($h[$root], $h[$right]) < 0)
            ) {
                // находим старшего потомка
                if (isset($h[$left]) && isset($h[$right])) {
                    $j = ($this->compare($h[$left], $h[$right]) >= 0)
                        ? $left : $right;
                } else {
                    if (isset($h[$left])) {
                        $j = $left; // левый потомок
                    } else {
                        $j = $right; // правый потомк
                    }
                }

                // меняем местами с корнем
                list(
                    $this->heap[$root], $this->heap[$j]
                    ) =
                    array($this->heap[$j], $this->heap[$root]);

                // рекурсивно перебираем кучу относительно
                // нового корневого узла $j
                $this->adjust($j);
            }
        }
    }


    public function insert($item)
    {
        // вставим новые элементы в конец кучи
        $this->heap[] = $item;

        // определим им корректное место
        $place = $this->count();
        $parent = floor($place / 2);
        // пока не на вершине и больше родителя
        while (
            $place > 0 && $this->compare(
                $this->heap[$place],
                $this->heap[$parent]
            ) >= 0
        ) {
            // меняем местами
            list(
                $this->heap[$place], $this->heap[$parent]
                ) =
                array($this->heap[$parent], $this->heap[$place]);
            $place = $parent;
            $parent = floor($place / 2);
        }
    }
}

$heap = new BinaryHeap();
$heap->insert(19);
$heap->insert(36);
$heap->insert(54);
$heap->insert(100);
$heap->insert(17);

//while (!$heap->isEmpty()) {
//    echo $heap->extract() . "n";
//}
```

</details>

[⏏ К содержанию](#содержание)

## Хэш

`Хэширование` — это процесс, используемый для уникальной идентификации объектов и хранения каждого объекта в заранее
рассчитанном уникальном индексе (ключе). По сути это массив, в котором ключ представлен в виде хеш-функции.

В нормально сбалансированной хэш-таблице время поиска не зависит от количества элементов, т.к. индекс нужного элемента
высчитывается, а не ищется перебором.

Применяется:

- Когда необходима постоянная скорость поиска и вставки.
- В криптографических приложениях.
- Когда необходима индексация данных.

Когда хеш-функция генерирует один индекс для нескольких ключей, возникает конфликт: неизвестно, какое значение нужно
сохранить в этом индексе. Это называется `коллизией` хеш-таблицы.

Есть несколько методов борьбы с коллизиями:

- метод цепочек;
- метод открытой адресации: линейное и квадратичное зондирование.

<details>
<summary>⏺класс Hach</summary>

```php

class Hash
{
    public function __construct()
    {
        $this->table = [];
        $this->count = 0;
    }

    public function hash($s)
    {
        $k = 65537;
        $m = 2 ** 20;

        $result = 0;
        $power = 1;
        for ($i = 0; $i < strlen($s); $i += 1) {
            $result = ($result + $power * ord($s[$i])) % $m;
            $power = ($power * $k) % $m;
        }

        return $result;
    }

    public function calculateIndex($table, $key)
    {
        return $this->hash((string)$key) % count($table);
    }

    public function rebuildTableIfNeed()
    {
        if (count($this->table) === 0) {
            $this->table = array_fill(0, 128, null);
        } else {
            $loadFactor = $this->count / count($this->table);
            if ($loadFactor >= 0.8) {
                $newTable = array_fill(0, count($this->table) * 2, null);
                foreach ($this->table as $list) {
                    foreach ($list as $pair) {
                        $newIndex = $this->calculateIndex($newTable, $pair['key']);
                        if (!isset($newTable[$newIndex])) {
                            $newTable[$newIndex] = new LinkedList();
                        }

                        $newTable[$newIndex]->insertAtEnd($pair);
                    }
                }

                $this->table = $newTable;
            }
        }
    }

    public function set($key, $value)
    {
        $this->rebuildTableIfNeed();

        $index = $this->calculateIndex($this->table, $key);
        if (!isset($this->table[$index])) {
            $this->table[$index] = new LinkedList();
        }

        $this->table[$index]->insertAtEnd(['key' => $key, 'value' => $value]);
        $this->count += 1;
    }

    public function get($key)
    {
        $index = $this->calculateIndex($this->table, $key);
        if (!isset($this->table[$index])) {
            return null;
        }

        foreach ($this->table[$index] as $pair1) {
            $pair = (array)$pair1;
            if ($pair['value']['key'] === $key) {
                return $pair['value']['value'];
            }
        }

        return null;
    }
}
```

</details>

[⏏ К содержанию](#содержание)
