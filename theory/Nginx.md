# Nginx

---

## Содержание

- [Основа]()
- [Команды]()
- [Иерархия каталогов]()
- [Как в NGINX указать размер и время, переменные]()
- [Синтаксис переменных и интерполяция]()
- [Наследование и типы директив]()
- [Примеры с объяснением]()
- [Активация базовой аунтификации]()
- [Варианты балансировки нагрузки]()
- [Обратный прокси сервер]()
- [Повышаем безопасность]()
- [Как заблокировать по IP в NGINX]()
- [Защита SSL настроек]()
- [Перенаправление и Редирект]()
- [Настройка отладки в NGINX]()
- [Ошибки nginx и их устранение]()
- [Разница с Apache]()

---

### Ссылки:

[Использовал//]: # (https://sheensay.ru/nginx#kcmenu)

[Несколько виртуальных хостов с Nginx](https://antonlytvynov.medium.com/nginx-sites-available-sites-enabled-3bd025bc4d25)

[Настройка LEMP сервера для простых проектов](https://habr.com/ru/company/nixys/blog/646023/)

[Огромный git справочник](https://github.com/trimstray/nginx-admins-handbook)

[Документация](https://docs.nginx.com/nginx/)

[Установка и настройка nginx](https://selectel.ru/blog/install-nginx/)

## Основа

`nginx` - это веб-сервер, прокси-сервер, обратный прокси-сервер, smtp-сервер и балансировщик нагрузки

- `Веб-сервер` — это программа, которая принимает и обрабатывает запросы от клиентов по протоколам HTTP и HTTPS и
  возвращает им ответ в виде HTML-страницы.
- `Прокси-сервер` принимает и обрабатывает запросы клиентов, а затем передает их дальше, другим программам.
- Обратный прокси-сервер — принимает результат работы других серверов и отдаёт его клиентам.
- `Smtp-сервер `— это сервер почтовой службы.
- `Балансировщик нагрузки` — программа, которая распределяет сетевые запросы между серверами, следуя настройкам
  балансировки.

## Команды

`sudo apt -y install nginx` - установить Nginx (Ubuntu)

`sudo service nginx start` - Запуск nginx

- `start` - запуск
- `stop` - остановить
- `restart` - перезапустить (остановить и запустить, обрывает работу сервера резко)
- `reload` - перезагрузка конфигов (Вам необходимо перезагрузить или перезапустить Nginx всякий раз, когда вы вносите
  изменения в его конфигурацию, `reload` - загружает новую конфигурацию, запускает новые рабочие процессы с новой
  конфигурацией и корректно завершает работу старых рабочих процессов)
- `status` - статус
- `force-reload` - принудительная перезагрузка
- `configtest` - конфигурационный тест
- `rotate`
- `upgrade` - обновить

`netstat -tulpn | grep nginx` / `netstat -tulpn` / `netstat -an | grep -i listen` - какой порт включен

`sudo nginx -h` — вывод справки по параметрам командной строки.

- `-v `— вывод версии nginx.
- `-t` - тестирование конфигурационного файла: nginx проверяет синтаксическую правильность конфигурации, а затем
  пытается открыть файлы, описанные в конфигурации.
- `-T` — то же, что и -t, а также вывод конфигурационных файлов в стандартный поток вывода (покажет conf файл который
  активный)
- `-c <filename>` — использование альтернативного конфигурационного файла файл вместо файла по умолчанию.
- `-e <filename>` — использование альтернативного лог-файла ошибок файл вместо файла по умолчанию
- `-g <directive>` — задание глобальных директив конфигурации, например

```
nginx -g "pid /var/run/nginx.pid; worker_processes `sysctl -n hw.ncpu`;"
```

- `-p <directory>` (префикс) — задание префикса пути nginx, т.е. каталога, в котором будут находиться файлы сервера (по
  умолчанию —
  каталог /usr/local/nginx).
- `-q`— вывод только сообщений об ошибках при тестировании конфигурации.

- `-s <signal>` — отправка сигнала главному процессу. Аргументом сигнал может быть:
    - `stop` — быстрое завершение
    - `quit` — плавное завершение
    - `reload `— перезагрузка конфигурации, старт нового рабочего процесса с новой конфигурацией, плавное завершение
      старых рабочих процессов. (Перезагрузить конфигурационный файл без перезагрузки NGINX)
    - `reopen` — переоткрытие лог-файлов

`nginx -V 2>&1 | grep --color -o -e '--prefix=[^[:space:]]\+'` - веб корень по умолчанию

[⏏ К содержанию](#содержание)

## Иерархия каталогов

- `/var/www/html` – директория, где располагается начальная страница Nginx.

- `/etc/nginx` – директория, в которой находятся основные файлы настроек Nginx
    - другие места: `/usr/local/etc/nginx`, `/usr/local/nginx/conf`

- `/etc/nginx/nginx.conf`– файл, содержащий главные настройки конфигурации Nginx.
    - другие места: `/usr/local/etc/nginx/nginx.conf`, `/usr/local/nginx/conf/nginx.conf`

- `/usr/share/nginx` - это корневой каталог по умолчанию для запросов, содержит `html` каталог и основные статические
  файлы
    - другие места: `html/` в корневом каталоге

- `/etc/nginx/sites-enabled`– каталог с конфигурациями виртуальных хостов, т.е. каждый файл, находящийся в этом
  каталоге,
  содержит информацию о конкретном сайте – его имени, IP адресе, рабочей директории и многое другое

- `/etc/nginx/sites-available` – в этом каталоге содержаться конфигурации сайтов, обслуживаемых nginx, т.е. активных,
  как
  правило, это символические ссылки sites-available конфигураций, что очень удобно для оперативного включения и
  отключения
  сайтов.

- `/etc/nginx/snippets` – каталог, содержащий так называемые сниппеты, которые можно при необходимости подключать к
  основной
  конфигурации сервера Nginx.

- `/var/log/nginx` – директория, которая содержит журналы событий работы Nginx.

- `/var/cache/nginx`- это расположение временных файлов по умолчанию для NGINX.
    - другие локации: `/var/lib/nginx`

`NGINX` работает с двумя уровнями конфигурационных файлов.

- Первый уровень — глобальный, к нему относится конфигурационный файл `etc/nginx/nginx.conf`.
- Второй уровень — локальный, к нему относятся конфигурационные файлы конкретных сайтов, расположенных, как правило,
  в `/etc/nginx/site-available` или в `/etc/nginx/conf.d/`

См.
также [Параметры установки и компиляции — файлы и разрешения](https://www.nginx.com/resources/wiki/start/topics/tutorials/installoptions/#files-and-permissions)

[⏏ К содержанию](#содержание)

## Как в NGINX указать размер и время, переменные

Размеры:

- Байты указываются без суффикса
- Килобайты указываются с суффиксом `k` или `K`
- Мегабайты указываются с суффиксом `m` или `M`
- Гигабайты указываются с суффиксом `g` или `G`

Пример: client_max_body_size 1G;

Время:

- `ms` — миллисекунды
- `s` — секунды
- `m` — минуты
- `h` — часы
- `d` — дни
- `w` — недели
- `M` — месяцы, 30 дней
- `Y` — годы, 365 дней

## Синтаксис переменных и интерполяция

Конфигурации Nginx переменные могут содержать только один тип значений, то есть строки (исключение: сторонний модуль
ngx_array_var расширяет переменные Nginx для хранения массивов, реализованных путем кодирования указателя C).

```nginx
set $a "hello"; # установка
set $b "$a, $a";
```

Давайте посмотрим еще один пример:

```nginx
server {
  listen 8080;
  
  location /text {
    set $foo "hello";
    echo "foo: $foo";a
    
    # set $first "hello ";
    # echo "${first}world";
  }
}
```

Чтобы запросить этот /test интерфейс через curl, мы получаем:

```
$ curl 'http://localhost:8080/test'
foo: hello
```

После создания переменной Nginx она становится видимой для всей конфигурации, даже для разных блоков конфигурации
виртуального сервера.

```nginx
location /foo {
  echo "foo = [$foo]";
}

location /bar {
  set $foo 32;
  echo "foo = [$foo]";
}
```

Можно использовать if конструкции.

```nginx
location /proxy {
  proxy_pass http://127.0.0.1:8080/$a;
  
  set $a 32;
  
  if ($a = 32) {
    set $a 56;
  }
  
  set $a 76;
}
```

[⏏ К содержанию](#содержание)

## Наследование и типы директив

Существует два вида директив – простые и блочные.

- `Простая директива` состоит из имени и параметров, разделённых пробелами, и в конце строки ставится точкой с запятой (
  `;`).
- `Блочная директива` (контекст) устроена так же, как и простая директива, но вместо точки с запятой после имени и
  параметров следует набор дополнительных инструкций, помещённых внутри фигурных скобок (`{` и `}`).
  Области которые определены скобками, называются `контекстами`. Фигурные скобки
  фактически обозначают новый контекст конфигурации. Рассмотрим те, которые
  пригодятся нам для примера:

Поскольку контексты могут накладываться друг на друга, Nginx поддерживает наследование директив. Наследование позволяет
писать простые и понятные конфиги.

Наследование идет от родителя к потомку, от http -> server -> location, но не всегда идет прямо, все зависит от типа
директивы.

- Стандартные - присоединено только одно значение или набор значений
- Директивы массивы - которые передают несколько отдельных значений в массиве.
- Директивы действия (try_files, rewrite) - обычно вообще не наследуются

```php
error_log  logs/access.log; # Директивы массивы
error_log  /var/log/nginx/error.log warn;

http {
    gzip on; # стандартные
    
    server {
    
      location /home {
          gzip off;
          rewrite ^ /index.html; # при нажатии перенаправим 
      }
      
      location /test{
        error_log  var/test/error.log warn; # перезаписалась оба error_log
        try_files $uri $uri =404; # выполнит несколько попыток запроса 
      }
    }
}
```

Список основных контекстов и их значений:

- `global` - содержит директивы глобальной конфигурации; используется для глобальной настройки NGINX и является
  единственным контекстом, который не заключен в фигурные скобки.

- `events` - конфигурация модуля событий; используется для установки глобальных параметров обработки соединения;
  содержит директивы, влияющие на обработку соединения.

- `http` - контролирует все аспекты работы с HTTP-модулем и содержит директивы для обработки HTTP- и HTTPS-трафика;
  директивы в этом контексте можно сгруппировать в:
    - директивы HTTP-клиента
    - Директивы ввода-вывода файлов HTTP
    - Хэш-директивы HTTP
    - директивы HTTP-сокетов

- `server` - определяет настройки виртуального хоста и описывает логическое разделение набора ресурсов, связанных с
  конкретным доменом или IP-адресом

- `location` - определить директивы для обработки клиентского запроса и указать URI, который поступает либо от клиента,
  либо от внутреннего перенаправления

- `upstream` - определить пул внутренних серверов, на которые NGINX может проксировать запрос; обычно используется для
  определения кластера веб-серверов для балансировки нагрузки

- `mail` - чаще всего используется как веб-сервер или обратный прокси-сервер, однако он также может функционировать как
  высокопроизводительный почтовый прокси-сервер.

- `limit_except` - используется для ограничения использования определенных HTTP-методов в контексте location.

Как правило, если директива действительна в нескольких вложенных областях, объявление в более широком контексте будет
передано в любые дочерние контексты в качестве значений по умолчанию. Дочерние контексты могут переопределять эти
значения по желанию.

[⏏ К содержанию](#содержание)

## Примеры с объяснением

Простейшая конфигурация для `nginx.conf`.

```nginx
server {
    listen 80;
    
    server_name test.ru www.test.ru;
    access_log /var/log/nginx/test.access.log main;
    
    # Задаёт корневой каталог для запросов, тут лежит сайт 
    root /var/www/test.ru/public_html/;
    index index.html index.htm;
    
    # конфигурации в зависимости от url, поведение, перхвачик запросов
    location / {
    	# попробуй получить доступ к ним через uri если не получится то 404
        try_files $uri $uri/ =404;
    }
}
```

Примеры:

```nginx

location / { 
	# путь к коревой папке для html (можно вынести под server_name)
	index index.html index.htm;  
	# в порядке важности, какой файл будет использоваться в качестве индекса
	root /public/html;  
}

# совпадение префиксов, наименьший приоритет, для /great и /great/user/
location /great { 
    # return 200 'hello!'; # выведет на экран

	root /public; # за пределами /html
	# попробуй получить доступ к ним а потом к index если не получится то 404
	try_files $uri index.html =404; 
																					
}

# в порядке убывания приоритета  
location = /great { # полное совпадение
location  ^~ /great[0-9] { # самый высокий приоритет чем ругулярное выражение 
location  ~ /great[0-9] { # совпадение c регулярными выражениями, чувствительная к регистру
location  *~ /great[0-9] { # не чувствительная к регистру
location /great {

# Защититься от запросов к несуществующим файлам
location ~ \.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar)$ { 
    try_files $uri =404; 
}
```

Простой шаблон для быстрой и легкой установки PHP, FPM или CGI на ваш сайт.

```nginx
location ~ \.php$ {
  try_files $uri =404;
  client_max_body_size 64m;
  client_body_buffer_size 128k;
  include fastcgi_params; # показывате где nginx может работать с php
  fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
  fastcgi_pass unix:/path/to/php.sock;
}   
```

Для докер:

```nginx
server {

    root /var/www/public;

    location / {
        try_files $uri /index.php;
    }

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php:9000;  // какой файл открыть
        fastcgi_index index.php;  
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
}
```

<details>
<summary>Пример рабочей конфигурации NGINX в роли кеширующего проксирующего сервера с Apache в бекенде: Proxy, Информация о файлах, Gzip Settings</summary>

```nginx
# Определяем пользователя, под которым работает nginx
user www-data;
 
 # Определяем количество рабочих процессов автоматически
 # Параметр auto – сервер определит автоматически
worker_processes auto;
 
 # Определяем, куда писать лог ошибок и уровень логирования
error_log  /var/log/nginx/error.log warn;
 
 # Задаём файл, в котором будет храниться номер (PID) основного процесса
pid /var/run/nginx.pid;
 
# Блок директив, определяющих работу с сетевыми соединениями;
events {
    # Устанавливает максимальное количество соединений одного рабочего процесса. Следует выбирать значения от 1024 до 4096.
    # Как правило, число устанавливают в зависимости от числа ядер процессора по принципу n * 1024. Например, 2 ядра дадут worker_connections 2048.
    worker_connections  1024;
 
    # Метод обработки соединений. Наличие того или иного метода определяется платформой.
    # Как правило, NGINX сам умеет определять оптимальный метод, однако, его можно указать явно.
    # use epoll - используется в Linux 2.6+
    # use kqueue - FreeBSD 4.1+, OpenBSD 2.9+, NetBSD 2.0 и Mac OS X
    use epoll;
 
    # Будет принимать максимально возможное количество соединений 
    multi_accept on;
}
 
# Блок директив http сервера;
http {
    # types { # указывается расширения для файлов, без него ссылки будут не коректные 
	#  text/html html;
	#  text/css css;
	# }
	
	# вместо того что наверху
    # подключаемый файл или файлы конфигурации
    include       /etc/nginx/mime.types;
    # указывает тип MIME ответа по умолчанию
    #default_type application/xml;
    default_type  application/octet-stream;
    
   
 
    log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
                      '$status $body_bytes_sent "$http_referer" '
                      '"$http_user_agent" "$http_x_forwarded_for"';
 
    # Куда писать лог доступа и уровень логирования
    # при выставлении значения в off, запись в журнал доступа будет отключена
    access_log  /var/log/nginx/access.log  main;
 
    # Для нормального ответа 304 Not Modified;
    if_modified_since before;
 
    # Включаем поддержку WebP
    map $http_accept $webp_ext {
        default "";
        "~*webp" ".webp";
    }
 
    ##
    # Basic Settings
    ##
   
    # Максимальный размер хэш таблиц типов
    # используется, если количество имен серверов большое
    #server_names_hash_max_size 1200;
    #server_names_hash_bucket_size 64;
 
 
    ### Обработка запросов ###
 
    # Метод отправки данных sendfile более эффективен, чем стандартный метод read+write 
    sendfile on;
    # Будет отправлять заголовки и и начало файла в одном пакете 
    # Параметры, положительно влияющие на производительность, оставляем значение on;
    tcp_nopush on;
    tcp_nodelay on;
 
 
    ### Информация о файлах ###
 
    # Максимальное количество файлов, информация о которых будет содержаться в кеше
    open_file_cache max=200000 inactive=20s;
    # Через какое время информация будет удалена из кеша
    open_file_cache_valid 30s;
    # Кеширование информации о тех файлах, которые были использованы хотя бы 2 раза
    open_file_cache_min_uses 2;
    # Кеширование информации об отсутствующих файлах
    open_file_cache_errors on;
 
 
    # Удаляем информацию об nginx в headers
    server_tokens off;
 
    # Будет ждать 30 секунд перед закрытием keepalive соединения 
    keepalive_timeout 30s;    
    ## Максимальное количество keepalive запросов от одного клиента 
    keepalive_requests 100;
 
    # Разрешает или запрещает сброс соединений по таймауту 
    reset_timedout_connection on;
    # Будет ждать 30 секунд тело запроса от клиента, после чего сбросит соединение 
    client_body_timeout 30s;
    # В этом случае сервер не будет принимать запросы размером более 200Мб 
    client_max_body_size 200m;
    # Если клиент прекратит чтение ответа, Nginx подождет 30 секунд и сбросит соединение 
    send_timeout 30s;
 
    # Proxy #
    # Задаёт таймаут для установления соединения с проксированным сервером. 
    # Необходимо иметь в виду, что этот таймаут обычно не может превышать 75 секунд. 
    proxy_connect_timeout 30s;
    # Задаёт таймаут при передаче запроса проксированному серверу. 
    # Таймаут устанавливается не на всю передачу запроса, а только между двумя операциями записи. 
    # Если по истечении этого времени проксируемый сервер не примет новых данных, соединение закрывается. 
    proxy_send_timeout 30s;
    # Задаёт таймаут при чтении ответа проксированного сервера. 
    # Таймаут устанавливается не на всю передачу ответа, а только между двумя операциями чтения. 
    # Если по истечении этого времени проксируемый сервер ничего не передаст, соединение закрывается. 
    proxy_read_timeout 30s;
 
     
   
    ##
    # Gzip Settings
    ##
 
    # Включаем сжатие gzip
    gzip on;
    # Для IE6 отключить (для браузера майкрософт)
    gzip_disable "msie6";
     # Добавляет Vary: Accept-Encoding в Headers
     gzip_vary on;
     # Cжатие для всех проксированных запросов (для работы NGINX+Apache)
     gzip_proxied any;
     # Устанавливает степень сжатия ответа методом gzip. Допустимые значения находятся в диапазоне от 1 до 9 (от 2-4 лучше всего)
     gzip_comp_level 3;
     # Задаёт число и размер буферов, в которые будет сжиматься ответ
     gzip_buffers 16 8k;
     # Устанавливает минимальную HTTP-версию запроса, необходимую для сжатия ответа. Значение по умолчанию
     gzip_http_version 1.1;
     # MIME-типы файлов в дополнение к text/html, которые нужно сжимать
     gzip_types text/plain text/css application/json application/x-javascript text/xml application/xml application/xml+rss text/javascript application/javascript image/svg+xml;
     # Минимальная размер файла, которую нужно сжимать (меньше 100байт не будем пропущен)
     gzip_min_length 100;
 
  # Подключаем конфиги конкретных сайтов 
  include /etc/nginx/conf.d/*.conf;
  # указывает на поддиректорию vhosts, в которой содержатся файлы конфигураций конкретно под каждый домен.
  # Пример того, что может содержаться там — example.com.conf
  include /etc/nginx/vhosts/*/*;
 
  ### Далее определяем localhost
  ### Сюда отправляются запросы, для которых не был найден свой конкретный блок server в /vhosts/
  server {
     server_name localhost; # имя домена или ip
     disable_symlinks if_not_owner;
     listen 80 default_server; # Указываем, что это сервер по умолчанию на порту 80
 
     ### Возможно, понадобится чётко указать IP сервера
     # listen      192.168.1.1:80 default_server;
 
     ### Можно сбрасывать соединения с сервером по умолчанию, а не отправлять запросы в бекенд
     #return      444;
 
    include /etc/nginx/vhosts-includes/*.conf;

    location @fallback {
       error_log /dev/null crit;
       proxy_pass http://127.0.0.1:8080;
       proxy_redirect http://127.0.0.1:8080 /;
       proxy_set_header Host $host;
       proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
       proxy_set_header X-Forwarded-Proto $scheme;
       access_log off ;
    }
  }
}
```

</details>

<details>
<summary>Ниже пример для Apache в бекенде:</summary>

```nginx
server {
  # Домен сайта и его алиасы через пробел
  server_name example.com www.example.com;
  # Кодировка сайта. Содержимое заголовка "Content-Type". off отключает этот заголовок. Можно указать стандартные uft-8, windows-1251, koi8-r, либо же использовать свою.
  charset off;
  disable_symlinks if_not_owner from=$root_path;
  index index.html;
  root $root_path;
  set $root_path /var/www/example/data/www/example.com;
  access_log /var/www/httpd-logs/example.com.access.log ;
  error_log /var/www/httpd-logs/example.com.error.log notice;
  #IP:Port сервера NGINX
  listen 192.168.1.1:80;
  include /etc/nginx/vhosts-includes/*.conf;
  location / {
    location ~ [^/]\.ph(p\d*|tml)$ {
      try_files /does_not_exists @fallback;
    }
    # WebP
    location ~* ^.+\.(png|jpe?g)$ {
    # разрешить браузерам кэшировать статические содержимое, и количесвто памяти выделенные
      expires 365d;
      add_header Vary Accept;
      try_files $uri$webp_ext $uri =404;
    }
    location ~* ^.+\.(gif|svg|js|css|mp3|ogg|mpe?g|avi|zip|gz|bz2?|rar|swf)$ {
      expires 365d;
      try_files $uri =404;
    }
    location / {
      try_files /does_not_exists @fallback;
    }
  }
  
  # @fallback - перенаправляет большые запросы на новый_сервер, контролируя вес
  location @fallback {
    proxy_pass http://127.0.0.1:8080;
    proxy_redirect http://127.0.0.1:8080 /;
    proxy_set_header Host $host;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    access_log off ;
  }
}
```

</details>

<details>
<summary>PHP-FPM:</summary>

1 Вариант.

```nginx
server {
  # Домен сайта и его алиасы через пробел
  server_name example.com www.example.com;
  # Кодировка сайта. Содержимое заголовка "Content-Type". off отключает этот заголовок. Можно указать стандартные uft-8, windows-1251, koi8-r, либо же использовать свою.
  charset off;
  disable_symlinks if_not_owner from=$root_path;
  index index.html;
  root $root_path;
  set $root_path /var/www/example/data/www/example.com;
  access_log /var/www/httpd-logs/example.com.access.log ;
  error_log /var/www/httpd-logs/example.com.error.log notice;
  #IP:Port сервера NGINX
  listen 192.168.1.1:80;
  include /etc/nginx/vhosts-includes/*.conf;
  location / {
    location ~ [^/]\.ph(p\d*|tml)$ {
      try_files /does_not_exists @php;
    }
    # WebP
    location ~* ^.+\.(png|jpe?g)$ {
      expires 365d;
      add_header Vary Accept;
      try_files $uri$webp_ext $uri =404;
    }
    location ~* ^.+\.(gif|svg|js|css|mp3|ogg|mpe?g|avi|zip|gz|bz2?|rar|swf)$ {
      expires 365d;
      try_files $uri =404;
    }
    location / {
      try_files $uri $uri/ @php;
    }
  }
  location @php {
    try_files $uri =404;
    include fastcgi_params;
    fastcgi_index index.php;
    fastcgi_param PHP_ADMIN_VALUE "sendmail_path = /usr/sbin/sendmail -t -i -f webmaster@example.com";
    # Путь к сокету php-fpm сайта
    fastcgi_pass unix:/var/www/php-fpm/example.sock;
    fastcgi_split_path_info ^((?U).+\.ph(?:p\d*|tml))(/?.+)$;
  }
}
```

2 Вариант.

```nginx
$ apt-get install php5 # для 5 версии, не думаю что для 8 как-то подругому
$ apt-get install php5-fpm
$ apt-get install php5-mysql
$ apt-get install php5-cgi
/etc/php/8.0/fpm/pool.d/www.conf

user www-data www-data;  # Задаёт пользователя и группу, с правами которого будут работать рабочие процессы
																
events {}

http {
	include /etc/nginx/mime.types;
	server {
			listen 80;
			server_name 46.101.19.11;

			root /sites/wordpress;
			index index.php index.html;

			location / {
					try_files $uri $uri/ /index.php?$args;
			}

			# Передайте все файлы .php на сервер php-fpm/php-fcgi.. 
			location ~ \.php$ { # имеет приоритет так как регулярные выражения
					include fastcgi_params; # показывате где nginx может работать с php
					include fastcgi.conf; # передает кое-какие переменные сервистные

					fastcgi_pass 127.0.0.1:9000; # или тут поменять на /var/run/php5-fpm.sock
			}
	}
}

$ ls -1 /etc/php5/fpm/pool.d/ # ищем www.conf

# строка 33
lisen = /var/run/php5-fpm.sock  # это unix sock а нудно поменять на tcp sock 127.0.0.1:9000, или оставить, главное чтобы были одинаковы

# после вего нудно перезагрузить и 
$ nginx -s reload
$ service php5-fpm restsart
```

</details>


<details>
<summary>Ниже конфигурация под WordPress Multisite с сайтами в поддиректориях:</summary>

```nginx
#user 'example' virtual host 'example.com' configuration file
server {
  server_name example.com www.example.com;
  charset off;
  disable_symlinks if_not_owner from=$root_path;
  index index.html index.php;
  root $root_path;
  set $root_path /var/www/example/data/www/example.com;
  access_log /var/www/httpd-logs/example.com.access.log ;
  error_log /var/www/httpd-logs/example.com.error.log notice;
  listen 1.2.3.4:80;
  include /etc/nginx/vhosts-includes/*.conf;
 
  # А вот тут блок специально для MU subdirectories
  if (!-e $request_filename) {
      rewrite /wp-admin$ $scheme://$host$uri/ permanent;
      rewrite ^(/[^/]+)?(/wp-.*) $2 last;
      rewrite ^(/[^/]+)?(/.*\.php) $2 last;
  }
 
  location / {
      try_files $uri $uri/ /index.php?$args ;
  }
 
  location ~ \.php {
    try_files $uri =404;
    include fastcgi_params;
    fastcgi_index index.php;
    fastcgi_param PHP_ADMIN_VALUE "sendmail_path = /usr/sbin/sendmail -t -i -f webmaster@example.com";
    fastcgi_pass unix:/var/www/php-fpm/example.sock;
    fastcgi_split_path_info ^((?U).+\.ph(?:p\d*|tml))(/?.+)$;
  }
}
```

</details>

<details>
<summary>Получить /конец каждого URL-адреса: example.com/art -> example.com/art/</summary>

```nginx
server {
  listen *:80;
  server_name 3much.schnickschnack.info;
  access_log /data/plone/deamon/var/log/main-plone-access.log;
  
  # запросы, изменённые директивой
  # Если указанное регулярное выражение соответствует URI запроса, URI изменяется в соответствии со строкой замены.
  rewrite ^([^.]*[^/])$ $1/ permanent;
  
  location / {
    ...
  }
}
```

</details>

[⏏ К содержанию](#содержание)

## Активация базовой аунтификации

Включает проверку имени и пароля пользователя по протоколу “HTTP Basic Authentication”.

Задаёт файл, в котором хранятся имена и пароли пользователей.

```
# комментарий
имя1:пароль1
имя2:пароль2:комментарий
имя3:пароль3
```

Затем установить настройки для server/location блока, который необходимо защитить:

```nginx
location / {
    auth_basic  "This is Protected";
    auth_basic_user_file /path/to/password-file; # путь где лежит пороль
}
```

[⏏ К содержанию](#содержание)

## Варианты балансировки нагрузки

```nginx
events {}

http {
	server {
		listen 8888;
		location / {
			return 200 " Hello from NGINX\n";
		}
	}
}

$ nginx -c /User/halon/.../project/nginx.conf # полный путь 
$ curl http://localhost:8888
		'Hello from NGINX'

$ php -S localhost:9999
# попытаемся открыть в браузере, но выдаст ошибку
# при этом в корне есть logo.png, и localhost:9999 //logo.png выведет лого

$ touch resp.txt  #  будет содержать " Hello from PHP\n";
$ php -S localhost:9999 resp.txt

$ curl http://localhost:9999 
		'Hello from PHP'
$ curl http://localhost:8888  # все еще работает
		'Hello from NGINX'
```

```nginx
http {
	upstream php_servers {
		ip_hash;   # отвечать на ip только один сервер (избыточность сервера)
		# least_conn;  # нименьшем количество подключений 
		server localhost:10001;
		server localhost: 10002;
		server localhost: 10003;
	}
	server {
		listen 8888;
		location / {
			proxy_pass http://php_servers;
		}

		location =/php {
			proxy_pass http://localhost:9000/;
		}
	}
}

$ php -S localhost:10001 s1  # s1 название серверов (файл php)
$ php -S localhost:10002 s2
$ php -S localhost:10003 s3

$ while sleep 1; 
do curl http://localhost:8888; 
done  # для проверки
```

[⏏ К содержанию](#содержание)

## Обратный прокси сервер

Обратный прокси-сервер является службой, которая работает на каждом узле и обрабатывает разрешение конечных точек,
автоматический повтор операций и другие сбои подключения от имени служб клиента.

```nginx
events {}

http {
	server {
		listen 8888;
		location / {
			return 200 " Hello from NGINX\n";
		}
		location /php {
	        # Добавляет указанное поле в конец ответа 
			# add_header proxie nginx;     # только клиенту
			prox_set_header	proxie nginx;		# передает header проксированому серверу (php server)
			proxy_pass http://localhost:9999/;  # / очень важен в конце
		}
	}
}

$ curl http://localhost:8888/php  # получили доступ к php server, nginx был посредником
		'Hello from PHP'
```

[⏏ К содержанию](#содержание)

## Повышаем безопасность

- удалить ненужный функционал

```nginx 
./configure --without-http_autoindex_module  # запрещает сборку модуля ngx_http_autoindex_module,
```

- отключение серверных токенов. Отключение показа версии nginx.

```nginx
server_tokens off;
```

- переполнение буфера. Лучше указать явно.

```nginx
# Configure buffer sizes
client_body_buffer_size 16k;
client_header_buffer_size 1k; 
client_max_body_size 8m; 
clarge_client_header_buffers 2 1k;
```

- блокировку user agents.

```ninx
if ($http_user_agent ~* badbot) { 
  return 403;
}
# хорошо работае для блокировки спама
if ($http_referer ~* badbot) { 
  return 403;
}
```

- настройки X-Frame-Options. Заголовок ответа HTTP можно использовать, чтобы указать, разрешено ли браузеру отображать
  страницу в файлах

[⏏ К содержанию](#содержание)

## Как заблокировать по IP в NGINX

`allow` - Разрешает доступ для указанной сети или адреса. Если указано специальное значение unix, разрешает доступ для
всех UNIX-сокетов

`deny` - Запрещает доступ для указанной сети или адреса.

Правила обработки таковы, что поиск идёт сверху вниз. Если IP совпадает с одним из правил, поиск прекращается.
Таким образом, вы можете как забанить все IP, кроме своих, так и заблокировать определённый IP:

```nginx
deny 1.2.3.4 ; # Здесь мы вводим IP нарушителя
deny 192.168.1.1/23 ; # А это пример того, как можно добавить подсеть в бан
deny 2001:0db8::/32 ; # Пример заблокированного IPv6
allow all ; # Всем остальным разрешаем доступ
```

Приведу пример конфигурации, как можно закрыть доступ к панели администратора WordPress по IP:

```nginx
### https://sheensay.ru/?p=408
################################
 
location = /wp-admin/admin-ajax.php { # Открываем доступ к admin-ajax.php. Это нужно, чтобы проходили ajax запросы в WordPress
  try_files $uri/ @php ;
}
  
location = /adminer.php { # Закрываем Adminer, если он есть
  try_files /does_not_exists @deny ;
}
  
location = /wp-login.php { # Закрываем /wp-login.php
  try_files /does_not_exists @deny ;
}
   
location ~* /wp-admin/.+\.php$ { # Закрываем каталог /wp-admin/
  try_files /does_not_exists @deny ;
}
  
  
location @deny { # В этом location мы определяем правила, какие IP пропускать, а какие забанить
 
  allow 1.2.3.4 ; # Здесь мы вводим свой IP из белого списка
  allow 192.168.1.1/23 ; # А это пример того, как можно добавить подсеть IP
  allow 2001:0db8::/32 ; # Пример IPv6
 
  deny all ; # Закрываем доступ всем, кто не попал в белый список
  
  try_files /does_not_exists @php; # Отправляем на обработку php в бекенд
}
  
location ~ \.php$ { ### Файлы PHP обрабатываем в location @php
    try_files /does_not_exist @php;  
}
 
location @php{ 
    ### Обработчик php, PHP-FPM или Apache
}
```

Ещё один неплохой вариант. Правда, по умолчанию определяются только статичные IP. А чтобы разрешить подсеть, придётся
использовать дополнительный модуль GEO:

```nginx
### Задаём таблицу соответствий
map $remote_addr $allowed_ip {
 
   ### Перечисляете разрешённые IP
   1.2.3.4 1; ### 1.2.3.4 - это разрешённый IP
   4.3.2.1 1; ### 4.3.2.1 - это ещё один разрешённый IP
 
   default 0; ### По умолчанию, запрещаем доступ
}
  
server {
 
   ### Объявляем переменную, по которой будем проводить проверку доступа
   set $check '';
  
   ### Если IP не входит в список разрешённых, отмечаем это
   if ( $allowed_ip = 0  ) {
      set $check "A";
   }
  
   ### Если доступ идёт к wp-login.php, отмечаем это
   if ( $request_uri ~ ^/wp-login\.php ) {
      set $check "${check}B";
   }
 
   ### Если совпали правила запрета доступа - отправляем соответствующий заголовок ответа 
   if ( $check = "AB" ) {
      return 444; ### Вместо 444 можно использовать стандартный 403 Forbidden, чтобы отслеживать попытки доступа в логах
 
   ### Остальные правила server ####
}
```

Это применит внутри контекста директивы, предназначенные для ограничения доступа, при получении HTTP-методов, не
перечисленных в заголовке контекста. Благодаря этой настройке любой клиент может использовать GET и HEAD, но
использовать другие методы могут только клиенты, входящие в подсеть 192.168.1.1/24.

```nginx
# server or location context
location /restricted-write {
  # location context
  limit_except GET HEAD {
    # limit_except context
    allow 192.168.1.1/24;
    deny all;
  }
}

```

[⏏ К содержанию](#содержание)

## Защита SSL настроек

Получение SSL сертификата необходимо для использования протокола HTTPS. Данный протокол защищает соединение между
сервером и клиентом, особенно критично для чувствительных данных, таких как логины, пароли, данные по банковским картам,
переписка и так далее.

```nginx
server {
        listen 443 ssl http2; # слушает 443 работат с ssl через http2
        server_name security.dmosk.ru;
        ssl on;
        #  необходимо указать путь до файла, содержащего в себе все ключи
        ssl_certificate /etc/nginx/ssl/fullchain.pem;
        ssl_certificate_key /etc/nginx/ssl/cert.key;
        ...
}
```

Отключение устаревших протоколов. Внутри раздела http добавим:

```nginx
http {
    ...
    ssl_protocols TLSv1.2 TLSv1.3;
    ..
}
```

Задаем приоритет для серверных шифров. Нам необходимо указать, чтобы при использовании протокола TLS серверные шифры
были приоритетнее, чем клиентские.

```nginx
http {
    ...
    ssl_prefer_server_ciphers on;
    ..
}
```

[⏏ К содержанию](#содержание)

## Перенаправление и Редирект

Стоит добавить настройку для перенаправления запроса с http на https

```nginx
server {
    listen 80;
    server_name security.dmosk.ru;
    # http-запросы на домен security.dmosk.ru будет перенаправляться на https.
    return 301 https://$host$request_uri;
}

// 2 способо
server {
  listen 80;
  return 301 https://$host$request_uri;
}

server {
  listen 443 ssl;
  # let the browsers know that we only accept HTTPS
  add_header Strict-Transport-Security max-age=2592000;
}
```

Редирект на основной сайт, перенаправление на www.

```nginx
server {
    listen       80;
    server_name  example.org;
    return       301 http://www.example.org$request_uri;
}

server {
    listen       80;
    server_name  www.example.org;
    ...
}
```

Перенаправление на определенный путь в URI

```nginx
location /old-site {
    rewrite ^/old-site/(.*) http://example.org/new-site/$1 permanent;
}
```

[⏏ К содержанию](#содержание)

## Настройка отладки в NGINX

В целях отладки настройки NGINX вы можете писать данные в логи, но я советую воспользоваться директивой add_header. С её
помощью вы можете выводить различные данные в http headers.

```nginx
# https://sheensay.ru/wp-content/uploads/2015/06/nginx.png
location ~ [^/]\.ph(p\d*|tml)$ {
  try_files /does_not_exists @fallback;
  add_header X-debug-message "This is php" always;
}
 
location ~* ^.+\.(jpe?g|gif|png|svg|js|css|mp3|ogg|mpe?g|avi|zip|gz|bz2?|rar|swf)$ {
  access_log off;
  # кеширование оперделенные ресурсы, и количество дней для хранения
  expires 365d; 
  
  # как именно кэшировать (установили как любым способом)
  add_header Pragma pablic;
  add_header Cache-Control public;
  add_header Vary Accept-Encoding;
  
  log_not_found off; 
  try_files $uri $uri/ @fallback;
  add_header X-debug-message "This is static file" always;  строка будет в header
}
```

Модуль `ngx_http_stub_status_module` предоставляет доступ к базовой информации о состоянии сервера. По умолчанию этот
модуль не собирается, его сборку необходимо разрешить с помощью конфигурационного параметра
`--with-http_stub_status_module`.

```nginx
location /status {
  stub_status on;
  access_log off;
}
```

Данная настройка позволит вам получать статус в обычном текстовом формате по общему количеству запросов и клиентским
подключениям (принятым, обработанным, активным).

[⏏ К содержанию](#содержание)

## Ошибки nginx и их устранение

Код ответа (состояния) HTTP показывает, был ли успешно выполнен определённый HTTP запрос.

- Информационные 100 - 199
- Успешные 200 - 299
- Перенаправления 300 - 399
- Клиентские ошибки 400 - 499
- Серверные ошибки 500 - 599
    - `500` - "Внутренняя ошибка сервера". Сервер столкнулся с ситуацией, которую он не знает как обработать.
    - `501` - "Не реализовано". Метод запроса не поддерживается сервером и не может быть обработан. Единственные методы,
      которые сервера должны поддерживать (и, соответственно, не должны возвращать этот код) - GET и HEAD.
    - `502` - "Плохой шлюз". Эта ошибка означает что сервер, во время работы в качестве шлюза для получения ответа,
      нужного для обработки запроса, получил недействительный (недопустимый) ответ.
    - `503` - "Сервис недоступен". Сервер не готов обрабатывать запрос. Зачастую причинами являются отключение сервера
      или
      то, что он перегружен. Обратите внимание, что вместе с этим ответом удобная для пользователей(user-friendly)
      страница должна отправлять объяснение проблемы.
    - `504` - Этот ответ об ошибке предоставляется, когда сервер действует как шлюз и не может получить ответ вовремя.
    - `505` - "HTTP-версия не поддерживается". HTTP-версия, используемая в запросе, не поддерживается сервером.

[⏏ К содержанию](#содержание)

### Глупые

Если не хватает прав:

```
$ service nginx stop

 * Stopping nginx nginx
   start-stop-daemon: warning: failed to kill 645: Operation not permitted
```

### 502 Bad Gateway

Ошибка означает, что NGINX не может получить ответ от одного из сервисов на сервере. Довольно часто эта ошибка
появляется, когда NGINX работает в связке с `Apache`, `Varnish`, `Memcached` или иным сервисом, а также обрабатывает
запросы `PHP-FPM`.
Как правило, проблема возникает из-за отключенного сервиса (в этом случае нужно проверить состояние напарника и при
необходимости перезапустить его) либо, если они находятся на разных серверах, проверить пинг между ними, так как,
возможно, отсутствует связь между ними.

Также, для `PHP-FPM` нужно проверить права доступа к сокету.
Для этого убедитесь, что в `/etc/php-fpm.d/www.conf` прописаны правильные права

```nginx
listen = /tmp/php5-fpm.sock 
listen.group = www-data
listen.owner = www-data
```

[⏏ К содержанию](#содержание)

### 504 Gateway Time-out

Ошибка означает, что nginx долгое время не может получить ответ от какого-то сервиса. Такое происходит, если Apache, с
которым NGINX работает в связке, отдаёт ответ слишком медленно.

```nginx
server {
...
send_timeout 800;
proxy_send_timeout 800;
proxy_connect_timeout 800;  
proxy_read_timeout 800;  
...
}
```

### Upstream timed out

Причиной может быть сложная и потому долгая обработка php в работе PHP-FPM.
Здесь тоже можно увеличить время ожидания таймаута

```nginx
location ~ \.php$ { 
   include fastcgi_params;
   fastcgi_index index.php; 
   fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; 
   fastcgi_pass unix:/tmp/php5-fpm.sock;  
   fastcgi_read_timeout 800;
}
```

Это лишь временные меры, так как при увеличении нагрузки на сайт ошибка снова станет появляться. Устраните узкие места,
оптимизируйте работу скриптов php

[⏏ К содержанию](#содержание)

### 413 Request Entity Too Large

Ошибка означает, что вы пытались загрузить слишком большой файл. В настройках nginx по умолчанию стоит ограничение в
1Mb.
Для устранения ошибки в nginx.conf нужно найти строку
`client_max_body_size 1m` и увеличить размер загружаемых файлов до 100Mb.

### Вместо 404 ошибки делать 301 редирект на главную

```nginx
error_page 404 = @gotomain;
 
location @gotomain {
  return 301 /;
}
```

[⏏ К содержанию](#содержание)

### Директива error_log off

Распространенная ошибка заключается в убежденности, что директива `error_log off` отключает ведение журнала. Фактически,
в отличие от директивы `access_log`, `error_log` не включает режим `off`. Если вы включаете директиву error_log off в
конфигурацию, NGINX создает файл лога ошибок с именем `off` в каталоге по умолчанию для файлов конфигурации NGINX (
обычно это `/etc/nginx`).

Мы не рекомендуем отключать журнал ошибок, поскольку он является жизненно важным источником информации при отладке любых
проблем связанных с NGINX. Однако, если объем хранилища ограничен настолько, что его дисковое пространство может быть
вот-вот исчерпано общим количеством данных, отключение ведения журнала ошибок вполне имеет смысл. Включите эту директиву
в основной контекст конфигурации:

```
error_log /dev/null emerg;
```

- `emerg`: критическая ситуация, аварийный сбой, система находится в нерабочем состоянии.
- `alert`: сложная предаварийная ситуация, необходимо срочно принять меры.
- `crit`: критические проблемы, которые необходимо решить.
- `error`: произошла ошибка.
- `warn`: предупреждение; в системе что-то произошло, но причин для беспокойства нет.
- `notice`: система в норме, но стоит обратить внимание на её состояние.
- `info`: важная информация, которую следует принять к сведению.
- `Debug`: информация для отладки, которая может помочь определить проблему.

Чтобы директива error_log не фиксировала никаких данных, отправьте её вывод в /dev/null.

```nginx
error_log /dev/null crit;
```

Обратите внимание, что эта директива работает только после проверки данной конфигурации со стороны NGINX. Таким образом,
каждый раз при запуске NGINX или перезагрузке конфигурации, она будет по умолчанию зарегистрирована в журнале ошибок (
обычно это `/var/log /nginx/error.log`) до тех пор, пока конфигурация не будет проверена. Чтобы изменить каталог
журнала, включите параметр `-e <error_log_location>` в команде nginx.

Практике оставляют лог ошибок и запрещают лог доступа, так как nginx записывает информацию о клиентских запросах в
журнал доступа сразу после обработки запроса (например статического).

[Как избежать 10 частых ошибок в настройке NGINX](https://habr.com/ru/company/nixys/blog/661233/)

### При запуске localhost, открывается стартовая страница Apache2?

Apache при установке создаёт папку `/var/www/html` и создаёт там файл `index.html`
В настройках nginx у вас дефолтный конфиг смотрит именно туда

```
root /var/www/html;

# Add index.php to the list if you are using PHP
index index.html index.htm index.nginx-debian.html;
```

Поменяйте путь `root /var/www/html`; на тот, который вы хотите. Но я просто добавил новый файл `index.nginx-debian.html`
, теперь в `\\wsl$\Ubuntu\var\www\html` лежит 2 файл, один Apache2 `index.html`, а второй для nginx.

[⏏ К содержанию](#содержание)

## Разница с Apache

- в Apache за основу берется путь в файловой системе, а в Nginx - url.
- Nginx состоит из master-процесса и нескольких дочерних процессов, a Apache на каждый запрос от клиента создает
  отдельный процесс.
- Nginx — отдает только статику и из коробки генерировать динамический контент не умеет. Apache — может генерировать как
  статический контент, так и динамический.

[Apache против Nginx: практические соображения](https://www.codeflow.site/ru/article/apache-vs-nginx-practical-considerations)

[⏏ К содержанию](#содержание)