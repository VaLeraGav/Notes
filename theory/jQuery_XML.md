# jQuery and AJAX

[документация API jQuery](https://jquery-docs.ru/)

# jQuery

[jQuery.ajax()](https://api.jquery.com/jquery.ajax/)

[jQuery для начинающих. Часть 3. AJAX](https://habr.com/ru/post/42426/)

[Передача значений переменных из JavaScript в PHP и наоборот](https://webformyself.com/peredacha-znachenij-peremennyx-iz-javascript-v-php-i-naoborot/)

[PHP и JavaScript](https://addphp.ru/materials/base/1_20.php)

# AJAX

[Документация #](https://developer.mozilla.org/ru/docs/Web/Guide/AJAX)

Совершает асинхронные запросы, у сервера получает недостающую информацию и добавляет её на страницу. А сама страница не
перезагружается.

Основа AJAX — объект XMLHttpRequest.

✅ `Удобство для посетителя и быстрый интерфейс.`

✅ `Меньше трафика.` Если нужно показать что-то новое, браузер получает с сервера не всю страницу целиком, а только то,
чего нет на исходной странице.

✅ `Можно снизить нагрузку на сервер.` Если сайт формирует все страницы на лету, например, с помощью PHP, то можно один
раз загрузить стандартные части страницы (шапку, меню и подвал), а остальное подгружать по необходимости. Меньше данных
в запросе — быстрее ответ от сервера и базы.

❌ `Нужен включённый JavaScript.` Если в браузере выключить JS, то ничего не сработает — данные не придут с сервера и
интерактивной страницы не получится.

❌ `Поисковые движки не видят AJAX-контент.` Если содержимое страницы формируется на AJAX-запросах, то поисковые роботы
не смогут его увидеть. Смысл в том, что поисковики смотрят на исходный код, а не на то, что приходит с сервера.
Поисковая оптимизация таких страниц — головная боль для сеошника.

❌ `Растёт сложность проекта`. Работа с такими запросами требует определённой квалификации от программиста, чтобы он мог
предусмотреть разные нештатные ситуации и обработать их заранее. А ещё нужно продумать бэкенд — поведение сервера в
ответ на разные запросы.

❌ `Ошибки при нестабильной связи`. Если интернет работает с перебоями, то AJAX может не дождаться ответа от сервера или
не сможет отправить свой запрос. В итоге логика работы страницы может нарушиться — в итоге придётся перезагружать её
полностью и начинать работу с нуля.

### Как послать HTTP запрос XMLHttpRequest

```html
<!--Из документации-->
<!--выведет содержимое файла в диалоговом окне.-->
<script type="text/javascript" language="javascript">
    function makeRequest(url) {
        var httpRequest = false;

        if (window.XMLHttpRequest) { // Mozilla, Safari, ...
            httpRequest = new XMLHttpRequest();

            if (httpRequest.overrideMimeType) {
                // JavaScript в Firefox 1.5 и ниже, вызовут ошибки в консоли
                // если ответ сервера не содержит заголовка XML mime-type
                httpRequest.overrideMimeType('text/xml');
            }
        } else if (window.ActiveXObject) { // IE
            try {
                httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                }
            }
        }

        if (!httpRequest) {
            alert('Не вышло :( Невозможно создать экземпляр класса XMLHTTP ');
            return false;
        }
        // httpRequest.onreadystatechange = alertContents(httpRequest); // (не работает)
        // httpRequest.onreadystatechange = alertContents;  // сработает если глобальная переменная
        httpRequest.onreadystatechange = function () {
            alertContents(httpRequest);
        };

        httpRequest.open('GET', url, true);
        // если вы хотите отправить данные методом POST, вы должны изменить MIME-тип запроса с помощью следующей строки:
        //  httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        httpRequest.send(null);

    }

    function alertContents(httpRequest) {
        // В случае ошибки взаимодействия (например, если сервер упал), при попытке доступа 
        // к переменной .status метода onreadystatechange будет сгенерировано исключение.
        try {
            if (httpRequest.readyState == 4) {
                if (httpRequest.status == 200) {
                    alert(httpRequest.responseText);
                } else {
                    alert('С запросом возникла проблема.');
                }
            }
        } catch (e) {
            alert('Произошло исключение: ' + e.description);
        }
    }
</script>
<span
        style="cursor: pointer; text-decoration: underline"
        onclick="makeRequest('test.html')">
    Сделать запрос
</span>
```

```js
const xhr = new XMLHttpRequest();
xhr.open('GET', requestURL);
// после получения какого-либо ответа от сервера
xhr.onload = () => {
    if (xhr.status !== 200) {
        // выводим ошибку в консоль
        console.log(`Ошибка ${xhr.status}: ${xhr.statusText}`);
        return;
    }
    // получаем ответ сервера
    const response = xhr.response;
    // responseText – возвращает ответ сервера в виде строки текста.
    // responseXML – возвращает ответ сервера в виде объекта XMLDocument, который вы можете обходить используя функции JavaScript DOM
    // желаемый его тип установить посредством xhr.responseType

    console.log(response);
};
// срабатывает, когда запрос не может быть выполнен (например, нет соединения или не корректный URL)
xhr.onerror = () => { // происходит, только когда запрос совсем не получилось выполнить
    console.log(`Ошибка при выполнении запроса`);
};
xhr.send();
```

После получения ответа, вам необходимо сделать запрос. Вы должны
вызвать методы класса open() и send():

- `method` – метод отправки запроса на сервер (GET, POST, PUT, PATCH, DELETE);
- `url` – URL для отправки запроса;
- `async` – определяет, как следует выполнять запрос: асинхронно (true – по умолчанию) или нет (false);
- `user` и `password` – имя пользователя и пароль, использующиеся для аутентификации (по умолчанию имеют значение null).

Статусы кодов `readyState`:

- 0 – создан объект XMLHttpRequest, но метод open() ещё не вызывался;
- 1 – открыто новое соединение с помощью open() (этот этап также включает установку значений HTTP заголовкам с помощью
  setRequestHeader());
- 2 – отправлен (вызван send() и получены заголовки ответа);
- 3 – получена часть ответа;
- 4 – завершён.

Пример AJAX запроса для получения текстовых данных с сервера

```html

<button type="button" id="get">Получить текст с сервера</button>
<div id="result"></div>

<script>
    function getData() {
        // URL на который будем отправлять GET запрос
        const requestURL = '/examples/ajax/01.html';
        const xhr = new XMLHttpRequest();
        xhr.open('GET', requestURL);
        xhr.onload = () => {
            if (xhr.status !== 200) {
                return;
            }
            document.querySelector('#result').innerHTML = xhr.response;
        }
        xhr.send();
    }

    // при нажатию на кнопку
    document.querySelector('#get').addEventListener('click', () => {
        getData();
    });
</script>
```

<details>
<summary>Пример AJAX GET запрос c параметром в PHP</summary>

```php
$nums = (int) $_GET['nums'];
$arr = ['Audi', 'BMW', 'Ford', 'Hyundai', 'Mazda', 'Mercedes-Benz', 'Toyota', 'Volkswagen'];
shuffle($arr);
exit(json_encode(array_slice($arr, 0, $nums)));
```

```html
<input type="number" id="nums" min="1" max="8" value="3">
<button type="button" id="get">Получить список</button>
<ul id="list"></ul>

<script>
    function getData() {
        const nums = parseInt(document.querySelector('#nums').value);
        // URL на который будем отправлять GET запрос
        const requestURL = `/examples/ajax/01.php?nums=${nums}`;
        const xhr = new XMLHttpRequest();
        xhr.open('GET', requestURL);
        xhr.onload = function () {
            if (xhr.status !== 200) {
                return;
            }
            const response = JSON.parse(xhr.response);
            let html = [];
            for (let i = 0, length = response.length; i < length; i++) {
                html.push(`<li>${response[i]}</li>`);
            }
            document.querySelector('#list').innerHTML = html.join('');
        }
        xhr.send();
    }

    // при нажатию на кнопку
    document.querySelector('#get').addEventListener('click', () => {
        getData();
    });
</script>
```

</details>

<details>
<summary>Пример синхронного AJAX запроса</summary>

```php
$q = filter_var($_GET['q'], FILTER_SANITIZE_STRING);
// сформируем ответ
echo 'Ответ на вопрос «' . $q . '» ...';
```

````html
<input type="text" id="q" value="Что Такое AJAX и как он работает" style="width: 100%;">
<button type="button" id="send">Отправить вопрос и получить ответ</button>
<div id="result">...</div>

<script>
    document.querySelector('#send').addEventListener('click', () => {
        let requestURL = new URL('https://localhost:8080/ajax.php');
        requestURL.searchParams.set('q', document.querySelector('#q').value);
        // создание экземпляра объекта XMLHttpRequest
        const xhr = new XMLHttpRequest();
        // настройка запроса (false - синхронный запрос)
        xhr.open('GET', requestURL, false);
        // отправка запроса
        xhr.send();
        // получение ответа и отображение его в #result
        document.querySelector('#result').innerHTML = xhr.response;
    });
</script>
````

</details>

<details>
<summary>Пример AJAX POST запроса</summary>

```php
// получим POST данные
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
// сформируем ответ
$output = ['firstname' => $firstname, 'lastname' => $lastname];
exit(json_encode($output));
```

```html

<form name="user" action="/examples/ajax/02.php" method="post">
    <label for="firstname">Имя:</label><input type="text" name="firstname" id="firstname">
    <label for="lastname">Фамилия:</label><input type="text" name="lastname" id="lastname">
    <button type="submit">Отправить</button>
</form>
<div class="result-wrapper">
    Полученные данные:
    <ul id="result">...</ul>
</div>

<script>
    const elForm = document.querySelector('[name="user"]');
    const elFirstname = elForm.querySelector('[name="firstname"]');
    const elLastname = elForm.querySelector('[name="lastname"]');
    const elResult = document.querySelector('#result');
    const requestURL = elForm.action;

    function sendForm() {
        const firstname = encodeURIComponent(elFirstname.value);
        const lastname = encodeURIComponent(elLastname.value);
        const formData = 'firstname=' + firstname + '&lastname=' + lastname;
        const xhr = new XMLHttpRequest();
        xhr.open('POST', requestURL);
        xhr.responseType = 'json';
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = () => {
            if (xhr.status !== 200) {
                return;
            }
            const response = xhr.response;
            elResult.innerHTML = `<ul><li>Имя: <b>${response.firstname}</b></li><li>Фамилия: <b>${response.lastname}</b></li></ul>`;
        }
        xhr.send(formData);
        elResult.textContent = '...';
    }

    // при отправке формы
    elForm.addEventListener('submit', (e) => {
        e.preventDefault();
        sendForm();
    });
</script>
```

Более простой способ получить данные формы – это воспользоваться FormData().

```php
// получим POST данные
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];

$ip = $_SERVER['REMOTE_ADDR'];

// сформируем ответ
$output = ['firstname' => $firstname, 'lastname' => $lastname, 'ip' => $ip];
exit(json_encode($output));
```

```html

<form name="user" action="/examples/ajax/03.php" method="post">
    <label for="firstname">Имя:</label><input type="text" name="firstname">
    <label for="lastname">Фамилия:</label><input type="text" name="lastname">
    <button type="submit">Отправить</button>
</form>
<div class="result-wrapper">
    Полученные данные:
    <ul id="result">...</ul>
</div>

<script>
    function sendForm() {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', document.forms.user.action);
        xhr.responseType = 'json';
        xhr.onload = () => {
            if (xhr.status !== 200) {
                return;
            }
            const response = xhr.response;
            document.querySelector('#result').innerHTML = `<li>Имя: <b>${response.firstname}</b></li><li>Фамилия: <b>${response.lastname}</b><li>IP адрес: <b>${response.ip}</b></li>`;
        }
        let formData = new FormData(document.forms.user);
        xhr.send(formData);
        document.querySelector('#result').textContent = '...';
    }

    // при отправке формы
    document.forms.user.addEventListener('submit', (e) => {
        e.preventDefault();
        sendForm();
    });
</script>
```

</details>

## Работа с XML

`Meta` - цель, конечный пункт, предел, край и данные (латинское значение)

`Данные` - поддающееся многократной интерпретации представление информации в
формализованном виде, пригодном для передачи, связи или обработки (ISO/IEC 2382:2015) Метаданные - информация о другой
информации, или данные, относящиеся к
дополнительной информации о содержимом или объекте.

`Язык разметки` (markup language) - средство описания данных и метаданных, хранящихся в документе.
Назначение языков разметки заключается в описании структурированных документов,
причем в этом случае интерпретируется содержимое документа.

`XML` - eXtensible Markup Language ( Расширяемый язык разметки ) XML метаязык язык для описания языков разметки, xml не
привязан ни к одному языку программирования, операционной системе. XML обеспечивает доступ к большому количеству
технологий по манипулированию, структурированию, трансформированию и запрашиванию данных.

```xml
<!-- нотация -->
<?xml version='1.0' encoding='UTF-8'?>
<!--не обязательная часть, подключение стилей -->
<?xml-stylesheet type="text/xsl" href="books.xsl"?>
<!-- корневой тег -->
<worker>
    <!-- всегда есть закрывающиеся тег -->
    <!-- всегда имя начинаеться с буквы -->
    <name>Коля</name>
    <age>25</age>
    <salary>1000</salary>
    <!--  name="Коля" (атрибут) -->
    <!--  <worker>...</worker> (узел) -->
    <worker name="Коля" age="25" salary="1000">Номер 1</worker>
    <workers>
        <first-name>Сергей</first-name>
        <last-name>Иванов</last-name>
    </workers>
    <!--  раздел содержимого элемента, который помечен парсером для интерпретации -->
    <!-- только как символьные данные, а не как разметка -->
    <![CDATA[
        </fwf/afdf > : @ ' "" >< 
        читаеться как текст
    ]]>
</worker>

```

Давайте получим имя, возраст и зарплату работника:

```php
$xml = simplexml_load_file(путь к файлу или урл);
$xml->name; // 'Коля'
$xml->age; // 25

$xml->worker['name']; // 'Коля'

$xml->workers->{first-name}; // Сергей

var_dump(json_decode(json_encode($xml), true)); // Из объекта в массив
```

Пространство имён в XML (англ. XML namespace) — это стандарт, описывающий именованную группу имён элементов и атрибутов,
служащую для обеспечения их уникальности в XML-документе.

Решает проблему конфликтов имен.

```xml

<univers:books xmlns:univer=" http://www.tnu.crimea.ua/ ">
    <univer:book>
        <univer:id>ASD345</univer:id>
        <univer:title>XSLT. Programmer's Reference
        </univer:title>
        <univer:name>Michael Kay</univer:name>
        <univer:year>2002
        </univer: year>
        <univer:descr>short book description</univer:descr>
    </univer:book>
</univer:books>
```

Сокращенная запись, чтобы не менять каждый тег.

````xml

<wrapper xmlns=" http://www.tnu.crimea.ua/ ">
    <books>
        <book>
            <id>ASD345</id>
            <title>XSLT. Programmer's Reference</title>
            <name>Michael Kay</name>
            <year>2002</year>
            <descr>short book description</descr>
        </book>
    </books>
</wrapper>
````

### DTD

DTD - Заранее определённый свод правил, задающий связи между элементами и атрибутами.

```xml
<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" " http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd ">

<!-- books какое угодно количество book -->
<!ELEMENT books (book*)>
        <!-- book обязательно имеет name, не обязательно author и year -->
<!ELEMENT book (name, author?, year?)>
        <!-- name имеет какие то данные -->
<!ELEMENT name (#PCDATA) >
<!ELEMENT author (#PCDATA) >
<!ELEMENT year (#PCOATA) >
```

## XML Schema

Язык XML схем также называют Определение схемы XML или XSD (от анг. `XML Schema Definition`).

Ниже приведен пример файла XML схемы "note.xsd", который определяет элементы XML документа, показанного ("note.xml"):

```xml
<?xml version="1.0"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema"
           targetNamespace="http://msiter.ru" xmlns="http://msiter.ru" elementFormDefault="qualified">
    <xs:element name="note">
        <xs:complexType>
            <xs:sequence>
                <xs:element name="to" type="xs:string"/>
                <xs:element name="from" type="xs:string"/>
                <xs:element name="heading" type="xs:string"/>
                <xs:element name="body" type="xs:string"/>
            </xs:sequence>
        </xs:complexType>
    </xs:element>
</xs:schema>
```

В следующем XML документе есть указание на подключение файла XML схемы:

```xml

<note xmlns="http://msiter.ru"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://msiter.ru note.xsd">
    <to>Tove</to>
    <from>Jani</from>
    <heading>Напоминание</heading>
    <body>Не забудь обо мне в эти выходные!</body>
</note>
```

## XPath

Для навигации, вычисление выражений и поиска внутри XML используется язык запросов XPath.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<bookstore>
    <book>
        <title lang="en">Harry Potter</title>
        <price>29.99</price>
    </book>
    <book>
        <title lang="en">Learning XML</title>
        <price>39.95</price>
    </book>
</bookstore>
```

## Поиск

| Выражение | Описание                                                                                                        |
|-----------|-----------------------------------------------------------------------------------------------------------------|
| имя_узла  | 	Выбирает все узлы с именем имя_узла                                                                            |
| /         | 	Выбирает от корневого узла                                                                                     |
| //        | Выбирает узлы в документе от текущего узла, который соответствует выбору, независимо от того, где они находятся |
| .         | Выбирает текущий узел                                                                                           |
| ..        | Выбирает родителя текущего узла                                                                                 |
| @         | Выбирает атрибуты                                                                                               |

| Выражение       | XPath	Результат                                                                                                                                            |
|-----------------|------------------------------------------------------------------------------------------------------------------------------------------------------------|
| bookstore       | Выбирает все узлы с именем "bookstore"                                                                                                                     |
| /bookstore      | Выбирает корневой элемент книжного магазинаПримечание: Если путь начинается с косой черты (/), он всегда представляет собой абсолютный путь к элементу!    |
| bookstore/book  | Выбирает все элементы «книга» (book), которые являются потомками элемента «книжный магазин» (bookstore)                                                    |
| //book          | Выбирает все элементы «книга» независимо от того, где они находятся в документе                                                                            |
| bookstore//book | Выбирает все элементы «книга», которые являются потомком элемента «книжный магазин», независимо от того, где они находятся под элементом «книжный магазин» |
| //@lang         | Выбирает все атрибуты, которые называются "lang"                                                                                                           |

### Операторы

Выражения XPath возвращают как набор узлов, строки, булевы или числовые значения. Ниже представлен список операторов, используемых в выражениях XPath:

| Оператор | Описание                   | Пример                    |
|----------|----------------------------|---------------------------|
| &#124;   | Вычисляет два набора узлов | //book &#124; //cd        |
| +	       | Сложение                   | 6 + 4                     |
| -	       | Вычитание                  | 6 - 4                     |
| *	       | Умножение                  | 6 * 4                     |
| div      | Деление                    | 8 div 4                   |
| =        | Равенство	                 | price=9.80                |
| !=       | Неравенство                | price!=9.80               |
| <        | Меньше, чем                | price<9.80                |
| <=       | Меньше или равно           | price≤9.80                |
| 	&lt;    | Больше, чем	               | price>9.80                |
| 	&lt;=	  | Больше или равно           | price≤9.80                |
| or	      | Или                        | price=9.80 or price=9.70  |
| and	     | И	                         | price>9.00 and price<9.90 |
| mod	     | Остаток от деления         | 5 mod 2                   |


### Предикаты

Предикаты используются для поиска (фильтрации) специфического узла или узла, который содержит специфическое значение. Предикаты
всегда обрамляются квадратными скобками.

| Выражения XPath                    | Результат                                                                                                                                                                                                                                                                                                                                    |
|------------------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| /bookstore/book[1]                 | Выбирает первый элемент «книга», который является потомком элемента «книжный магазин». Примечание: В IE 5,6,7,8,9 первый узел имеет индекс [0], но в соответствии с рекомендациями W3C, это [1]. Для решения этой проблемы в IE, задаётся опция "SelectionLanguage" для XPath: На JavaScript: xml.setProperty("SelectionLanguage", "XPath"); |
|
| /bookstore/book[last()]            | Выбирает последний элемент «книга» (book), который является дочерним элементом элемента «книжный магазин» (bookstore)                                                                                                                                                                                                                        |
| /bookstore/book[last()-1]          | Выбирает предпоследний элемент «книга», который является дочерним элементом элемента «книжный магазин»                                                                                                                                                                                                                                       |
| /bookstore/book[position()<3]      | Выбор первых двух элементов «книга», которые являются потомками элемента «книжный магазин»                                                                                                                                                                                                                                                   |
| //title[@lang]                     | Выбирает все элементы «название» (title), которые имеют атрибут с именем "lang"                                                                                                                                                                                                                                                              |
| //title[@lang='en']                | Выбирает все элементы «название», которые имеют атрибут «язык» со значением "en"                                                                                                                                                                                                                                                             |
| /bookstore/book[price>35.00]       | Выбирает все элементы «книга» после элемента «книжный магазин», которые имеют элемент «цена» со значением больше, чем 35.00                                                                                                                                                                                                                  |
| /bookstore/book[price>35.00]/title | Выбирает все элементы «название» книги элемента «книжный магазин», которые имеют элемент «цена» со значением больше, чем 35.00                                                                                                                                                                                                               |
| /bookstore/book[@class='list']     | Выбирает все элементы c классов list                                                                                                                                                                                                                                                                                                         |

В XPath индексы элементов начинаются с единицы, а не с нуля, как в принятых стандартах программирования. Если вы уже программируете, это может немного запутать.

### Выбор неизвестных узлов

| Wildcard | Описание                              |
|----------|---------------------------------------|
| *	       | Соответствует любому узлу             |
| @*       | Соответствует узлу-атрибуту           |
| node()   | Соответствует любому узлу любого типа |

В приведённой ниже таблице мы перечислили некоторые пути выражения и результаты выражений:

| Выражение пути | Результат                                                                                        |
|----------------|--------------------------------------------------------------------------------------------------|
| /bookstore/*   | Выбирает все дочерние узлы элемента «книжный магазин» (bookstore)                                |
| //*            | Выбирает все элементы в документе                                                                |
| //title[@*]    | Выбирает все элементы «название» (title), которые имеют по крайней мере один атрибут любого вида |

### Выбор нескольких путей

С помощью оператора | в выражениях XPath вы можете выбрать несколько путей. В таблице ниже перечислены несколько
выражений путей и результаты их применения:

| Выражение пути                       | Результат                                                                                                      |
|--------------------------------------|----------------------------------------------------------------------------------------------------------------|
| //book/title &#124; //book/price     | Выбирает все элементы «название» (title) И «цена» (price) всех элементов «книга» (book)                        |
| //title &#124; //price               | Выбирает все элементы «название» (title) И «цена» (price) в документе                                          |
| /bookstore/book/title &#124; //price | Выбирает все элементы «название» элемента «книга» элемента «книжный магазин» И все элементы «цена» в документе |

### Оси

Для навигации по иерархическому дереву узлов в XML-документе XPath использует концепцию осей. Спецификация XPath
определяет в общей сложности 13 различных осей, которые мы изучим в этом разделе.

Ось `XPath` — это набор узлов, удовлетворяющих текущим критериям навигации.

Мы будем использовать следующий XML документ далее в примере.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<bookstore>
    <book>
        <title lang="en">Harry Potter</title>
        <price>29.99</price>
    </book>
    <book>
        <title lang="en">Learning XML</title>
        <price>39.95</price>
    </book>
</bookstore>
```

| Название оси       | Результат                                                                                                                               |
|--------------------|-----------------------------------------------------------------------------------------------------------------------------------------|
| ancestor           | Выбирает всех предков (родителей, прародителей и т.д.) текущего узла                                                                    |
| ancestor-or-self   | Выбирает всех предков (родителей, прародителей и т.д.) текущего узла и сам текущий узел                                                 |
| attribute          | Выбирает все атрибуты текущего узла                                                                                                     |
| child              | Выбирает всех потомков текущего узла                                                                                                    |
| descendant         | Выбирает всех потомков (детей, внуков и т.д.) текущего узла                                                                             |
| descendant-or-self | Выбирает всех потомков (детей, внуков и т.д.) текущего узла и сам текущий узел                                                          |
| following          | Выбирает всё в документе после закрытия тэга текущего узла                                                                              |
| following-sibling  | Выбирает все узлы одного уровня после текущего узла                                                                                     |
| namespace          | Выбирает все узлы в данном пространстве имён (namespace) текущего узла                                                                  |
| parent             | Выбирает родителя текущего узла                                                                                                         |
| preceding          | Выбирает все узлы, которые появляются перед текущим узлом в документе, за исключением предков, узлов атрибутов и узлы пространства имён |
| preceding-sibling  | Выбирает всех братьев и сестёр до текущего узла                                                                                         |
| self               | Выбирает текущий узел                                                                                                                   |

```
Syntax:
  //preceding::tagName
```

### Выражения

Путь определения местоположения может быть абсолютным или относительным. Абсолютный путь расположения начинается с косой
черты (/), а относительный – нет. В обоих случаях путь выборки состоит из одного или нескольких шагов, разделённых косой
чертой:

`/step/step/` - Абсолютный путь расположения

`step/step/...` - Относительный путь выборки расположения

Каждый шаг оценивается по узлам в текущем наборе узлов. Шаг состоит из:

- ось (определяет древовидную связь между выбранными узлами и текущим узлом);
- проверка узла (идентифицирует узел в пределах оси);
- ноль или более предикатов (для дальнейшего уточнения выбранного набор узлов)

| Пример                 | Результат                                                                                         |
|------------------------|---------------------------------------------------------------------------------------------------|
| child::book            | Выбирает все узлы «книга» (book), которые являются потомками текущего узла                        |
| attribute::lang        | Выбирает атрибут «язык» (lang) текущего узла                                                      |
| child::*               | Выбирает всех потомков текущего узла                                                              |
| attribute::*           | Выбирает все атрибуты текущего узла                                                               |
| child::text()          | Выбирает все текстовые узлы текущего узла                                                         |
| child::node()          | Выбирает всех ближайших потомков текущего узла                                                    |
| descendant::book       | Выбирает всех потомков текущего узла                                                              |
| ancestor::book         | Выбирает всех предков «книга» (books) текущего узла                                               |
| ancestor-or-self::book | Выбирает всех предков «книга» (book) текущего узла – и текущий узел, если он также «книга» (book) |
| child::*/child::price  | Выбирает все потомки «цена» (price) через один уровень от текущего узл                            |


https://guides.hexlet.io/ru/xpath/

https://habr.com/ru/post/114772/

## XML+XSLT

```xml
<!-- index. xml -->
<?xml version="1.0"?>
<?xml-stylesheet type="text/xsl" href="index.xsl"?>
<page>Page Title</page>

        <!-- index.xsl-->
        <?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="..." version="1.0">
<xsl:template match="/">
    <h1>
        <xsl:value-of select="page"/>
    </h1>
</xsl:template>
</xsl:stylesheet>
```