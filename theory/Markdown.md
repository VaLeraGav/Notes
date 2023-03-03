---
title: "Язык разметки Markdown"
description: "Зачем нужен ещё один язык разметки и как на нём писать."
authors:
- cergmin
  editors:
- tachisis
  contributors:
- skirienko
- skorobaeus
  keywords:
- документация
- разметка
- readme
  related:
- tools/package-managers
- tools/version-control
- tools/github-actions
  tags:
- article
---

# Markdown

---

## Содержание

1. [Введение](#введение)
2. [Параграфы и разрывы строк](#параграфы-и-разрывы-строк)
3. [Оформление текста](#оформление-текста)
4. [Добавление свернутого раздела](#добавление-свернутого-раздела)
5. [Выравнивание текста](#выравнивание-текста)
6. [Комментарии](#комментарии)
7. [Заголовки](#заголовки)
8. [Цитаты](#цитаты)
9. [Блоки-кода](#блоки-кода)
10. [Горизонтальные (разделительные) линии](#горизонтальные-разделительные-линии)
11. [Вставка изображения или ссылки](#вставка-изображения-или-ссылки)
12. [Бейджики](#бейджики)
13. [Ссылки](#ссылки)
14. [Таблица](#таблица)
15. [Emoji](#emoji)
16. [Дополнительные элементы](#дополнительные-элементы)
17. [Сноски](#сноски)

---

## Введение

Расширение .md — это сокращение от слова markdown. Это язык разметки для форматирования текста. Его используют (как и
язык разметки HTML) для нормального отображения документов.

Не забываете в нем можно использовать HTML теги.

У Markdown есть [оригинальная спецификация](https://daringfireball.net/projects/markdown/basics) от одного из создателей
языка — Джона Грубера. К сожалению, она не всегда однозначно описывает синтаксис, из-за чего многие конвертеры Markdown
работают по-разному. Чтобы исправить эту ситуацию, группа разработчиков «поклонников Markdown» создала CommonMark —
спецификацию, которая описывает многие частные случаи, и эталонную реализацию парсера Markdown на JS.

Для вставки символов внутрь тегов воспользуетесь [HTML-кодом символов](https://symbl.cc/ru/html-entities/) - мнемоники

## Параграфы и разрывы строк

Для того чтобы вставить видимый перенос строки (элемент `<br/>`) необходимо окончить строку двумя пробелами и нажатием
клавиши «Enter»

## Оформление текста

```markdown
~~Зачеркнутый текст~~

**Жирный текст (bold)**

*Наклонный текст (italic)*

***Жирный наклонный текст (bold italic)***

Это `слово` будет выделено

<sup>Надстрочный текст</sup>

<sub>Подстрочный текст</sub>

<span style="color: blue">About us</span>
```

Результат:

---

~~Зачеркнутый текст~~

**Жирный текст (bold)**

*Наклонный текст (italic)*

***Жирный наклонный текст (bold italic)***

Это `слово` будет выделено

<sup>Надстрочный текст</sup>какой то текст

<sub>Подстрочный текст</sub>какой то текст

<span style="color: blue">About us</span> <-- не отображается
---

## Добавление свернутого раздела

```markdown
<details>
<summary>My top THINGS-TO-RANK</summary>

- ...

</details>
// <details open> - чтобы атоматически был открытым
```

Резульат:

<details>
<summary>Раздел</summary>

- пункт 1
- пункт 2

</details>

## Выравнивание текста

```markdown
<p align="center">Выравнивание по центру.</p>
<p align="left">Выравнивание по левому краю.</p>
<p align="right">Выравнивание по правому краю.</p>
<p align="justify">Выравнивание по ширине.</p>
```

Резульат:

---

<p align="center">Выравнивание по центру.</p>
<p align="left">Выравнивание по левому краю.</p>
<p align="right">Выравнивание по правому краю.</p>
<p align="justify">Выравнивание по ширине.</p>

---

## Комментарии

```markdown
<!-- комментарий  --> 

[//]: # (комментарий)
```

## Заголовки

```markdown
Заголовок первого уровня
========================
Заголовок второго уровня
-------------------------
```

```markdown
# Заголовок первого уровня

### Заголовок третьего уровня

#### Заголовок четвертого уровня

##### Заголовок пятого уровня

###### Заголовок шестого уровня
```

Результат:

---

# Заголовок первого уровня

### Заголовок третьего уровня

#### Заголовок четвертого уровня

##### Заголовок пятого уровня

###### Заголовок шестого уровня

---

## Цитаты

```markdown
> Первый уровень цитирования
>> Второй уровень цитирования
>>> Третий уровень цитирования
>
>Первый уровень цитирования
```

Резульат:

---
> Первый уровень цитирования
>> Второй уровень цитирования
>>> Третий уровень цитирования
>
>Первый уровень цитирования
>
---

## Списки

```markdown
- Пункт 1

* Проводник

+ Проводник

- Уровень списка 1.
    - Уровень списка 2.
        - Уровень списка 3. 
```

Результат:

---

- Пункт 1

* Пункт 1

+ Пункт 1

- Уровень списка 1.
    - Уровень списка 2.
        - Уровень списка 3.

---

## Блоки кода

```php
print_r("test");
```

```diff
--- filename.orig
+++ filename
@@ -line number,7 +line number,7 @@
     good line of code #1
     good line of code #2
-    the original line with a wrong coding style
+    the line wrong coding style that has been corrected
     good line of code #3
     good line of code #4
```

## Горизонтальные (разделительные) линии

```markdown
---

***
```

## Вставка изображения

```markdown
![alt-текст](/путь/к/изображению.jpg "Необязательная подсказка")
[![alt-текст](адрес до картинку)](ссылка на страничку)

[PlDb]: <http://путь/к/изображению.jpg>

[id]: ../assets/pattern1.png  <-- использовать как переменную
....текст....
![Альтернативный текст][id]  <-- а тут она вызывается

В сноске:  
![alt-текст][logo]

[logo]: https://.... "Текст заголовка"
```

```html

<figure>
    <img src="https://thepracticaldev.s3.amazonaws.com/i/g84et7kjgp2phal89140.jpg" alt="Swat Kats" style="width:100%">
    <figcaption>какие то коты</figcaption>
</figure>

// изменить размер фотографии
<p>
    <img src="https://thepracticaldev.s3.amazonaws.com/i/g84et7kjgp2phal89140.jpg" width="100" height="111"
         alt="Винни-Пух">
</p>
```

## Ссылки

```markdown
[ссылка с title](http://example.com/ "Необязательная подсказка")

[Введение](#введение)

[Pattern](/theory/Pattern.md) <-- относительная ссылка (можно ходить по репозиторию)

[Pattern](/../theory/Pattern.md)

[Ссылка со сноской][id]
[id]: https://www.mozilla.org

Можно вставить ссылку в конце файла [текст ссылки]
....текст....
[текст ссылки]: http://www.yandex.ru
```

## Бейджики

```markdown
Language: ![https://img.shields.io/badge/Python-3.7.5-blue](https://img.shields.io/badge/Python-3.7.5-blue)
```

Language: ![https://img.shields.io/badge/Python-3.7.5-blue](https://img.shields.io/badge/Python-3.7.5-blue)

Сложно вставлять свои или готовые бейджик. Источник:  https://shields.io/category/build

## Таблица

```markdown
| LEFT | CENTER | RIGHT |
|----------------|:---------:|----------------:|  <-- двоеточие
| По левому краю | По центру| По правому краю | определеяет позицию
| текст | текст | текст |

```

Результат:

| LEFT           |         CENTER         |           RIGHT |
|----------------|:----------------------:|----------------:|
| По левому краю | По центру лишний текст | По правому краю |
| текст          |         текст          |           текст |

Внимание:
Если в тексте таблицы нужно использовать символ "вертикальная черта - `|`", то в место него необходимо написать замену
на комбинацию HTML-кода* `&#124;`, это нужно для того, что бы таблица не потеряла ориентации.

## Emoji

```markdown
:white_check_mark: Это уже сделано
:negative_squared_cross_mark: Я не буду это делать
:black_square_button: делать или не делать, вот в чем вопрос?

- [X] Придумать внешний вид резюме
- [ ] Написать основные категории
```

Полный список разметки смайликов github markdown https://gist.github.com/rxaviers/7360908

## ❗❓⁉❕💡📌👀🐞👇👆📢💻🖥💾🔒📚📰📒📃📜📄📑📦✏📝⏳📋

Результат:

:white_check_mark: Это уже сделано

:negative_squared_cross_mark: Я не буду это делать

:black_square_button: делать или не делать, вот в чем вопрос?

- [X] Придумать внешний вид резюме
- [ ] Написать основные категории

```
> **Warning**: This is a waring

> **Note** : This is a note
```

> **Warning**: This is a waring

> **Note** : This is a note

## Дополнительные элементы

Изображает прогресс

```html

<progress value="63" max="100">
</progress>
```

<progress value="63" max="100">
</progress>

Имитация клавиатуры

```html

<pre>
    <kbd>Ctrl</kbd>+<kbd>Shift</kbd>+<kbd>Esc</kbd> - Вызов диспетчера задач.
</pre>
```

<pre>
    <kbd>Ctrl</kbd>+<kbd>Shift</kbd>+<kbd>Esc</kbd> - Вызов диспетчера задач.
</pre>

Рисуем диаграммы : https://habr.com/ru/post/652867/

## Сноски

Всегда будут внизу!

```markdown
Here is a simple footnote[^1].

A footnote can also have multiple lines[^2].

You can also use words, to fit your writing style more closely[^note].

[^1]: My reference.
[^2]: Every new line should be prefixed with 2 spaces.  
This allows you to have a footnote with multiple lines.
[^note]:
Named footnotes will still render with numbers instead of the text but allow 
```

[⏏ К содержанию](#содержание)

Результат:

---
Here is a simple footnote[^1].

A footnote can also have multiple lines[^2].

You can also use words, to fit your writing style more closely[^note].

[^1]: My reference.
[^2]: Every new line should be prefixed with 2 spaces.  
This allows you to have a footnote with multiple lines.
[^note]:
---

