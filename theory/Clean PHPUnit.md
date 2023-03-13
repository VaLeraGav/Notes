# Чистый PHPUnit

[ Спасибо//]: # (https://habr.com/ru/users/AloneCoder/)

## Содержание

---

1. [PHPUnit](#phpunit)
2. [Вступление](#вступление)
3. [Структура теста](#1-структура-теста)
4. [Порядок утверждений для одного значения](#2-порядок-утверждений-для-одного-значения)
5. [Тест не должен зависеть от других тестов](#3-тест-не-должен-зависеть-от-других-тестов)
6. [Проверка утверждений для числовых значений](#4-проверка-утверждений-для-числовых-значений)
7. [Делай правильно!](#5-делай-правильно)
8. [Советы по модульному тестированию на примерах в PHP](#советы-по-модульному-тестированию-на-примерах-в-php)
9. [Виды тестирования и Доп информация](#виды-тестирования-и-доп-информация)

---

## PHPUnit

`composer require --dev phpunit/phpunit` подключение к composer

`./vendor/bin/phpunit tests` запуск тестов

`./vendor/bin/phpunit tests --testdox`  запустит тест и покажет блоки которые прошли тесты

`./vendor/bin/phpunit --configuration phpunit.xml --coverage-html coverage` - Tests coverage, создание
build/coverage.html

`./vendor/bin/phpunit --filter {TestMethodName} {FilePath}` - запустить один или определенные модульные тесты (phpunit --filter '/testSave$/' - те которые начинаються с testSave)

`composer exec --verbose phpunit tests -- --coverage-html coverage` создаст html файл

`composer exec --verbose phpunit tests -- --coverage-text` тоже самое но в текстовом режиме

`composer exec`- Выполняет бинарный binary/script поставщика (Composer bin-dir)

<details>
<summary>Makefile:</summary>

```php
install:
    composer install
	
lint:
    composer run-script phpcs -- --standard=PSR12 src tests

lint-fix:
    composer run-script phpcbf -- --standard=PSR12 src tests

test:
    # ./vendor/bin/phpunit tests
    # composer exec --verbose phpunit tests
    # composer exec -v phpunit tests
    composer run-script test
    
autoload:
	composer dump-

test-coverage-2 (laravel):
	XDEBUG_MODE=coverage php artisan test --coverage-clover build/logs/clover.xml
```

</details>

<details>
<summary>Makefile для Laravel:</summary>

```php
env-prepare: # создать .env-файл для секретов
	cp -n .env.example .env

sqlite-prepare: # подготовить локальную БД
	touch database/database.sqlite

install: # установить зависимости
	composer install
	npm install

key: # сгенерировать ключи
	php artisan key:generate

db-prepare: # загрузить данные в БД
	php artisan migrate --seed

start: # запустить приложение
	heroku local -f Procfile.dev

log:
	tail -f storage/logs/laravel.log

setup:
	composer install
	cp -n .env.example .env || true
	php artisan key:gen --ansi
	touch database/database.sqlite
	php artisan migrate
	php artisan db:seed
	npm ci
	npm run build

update db:
	rm database/database.sqlite
	touch database/database.sqlite
	php artisan migrate
	php artisan db:seed

test:
	php artisan test
```

</details>

<details>
<summary>composer.json:</summary>

```php
{
    "name": "name/name-tasks",
    "description": "Learnin PHP ",
    "bin": [
        "bin/brain-games", # <-- в папке bin, чтобы запускать скрипты 
    ]
    "type": "project",
		"config": {
		    "platform": {
		        "php": "8.0.7"
			   }
		},
    "authors": [
        {
            "name": "VaLeraGav",
            "email": "email@yandex.ru"
        }
    ],
    "autoload": {
        "files": [
            "src/PhpArrays/00_creatingFiles.php",  # <-- namespace App\PhpArrays;
            "src/PhpArrays/02_Arrays.php",
        ],
        "psr-4": {
            "App\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    },
    "require": {
        "funct/funct": "*",          // функции полезные
        "webmozart/assert": "^1.11", // для проверки ввода и вывода методов
        "php-ds/php-ds": "^1.3",     // структуры данных для PHP 7
        "tightenco/collect": "^6.0", // для колекции
        "nesbot/carbon": "^2.24",    // расширение для DateTime
        "symfony/string": "^5.4"     // Строковый компонент, для различных языков
    },
    "require-dev": {
        "phpunit/phpunit": "^8.3"
    }
}
```

</details>

<details>
<summary>phpunit.xml:</summary>

```html
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="/composer/vendor/autoload.php"
         
<!-- Этот атрибут настраивает эту операцию для всех тестов -->
         backupGlobals="false"
         backupStaticAttributes="false"

         colors="true" <!-- используются ли цвета в выводе -->

<!-- будут ли события E_ERROR, E_USER_ERROR, E_STRICT, E_NOTICE, E_WARNING --> <!-- E_USER_WARNING преобразовываться в исключение (и помечать тест как ошибку) -->
         convertErrorsToExceptions="true"
         convertNoticesToExceptions="true"
         convertWarningsToExceptions="true"

<!-- определяет, должен ли каждый тест выполняться в отдельном процессе PHP для повышения изоляции --> 
         processIsolation="false"
<!-- должно ли выполнение набора тестов быть остановлено после завершения первого теста со статусом --> 
        stopOnFailure="true">

    <testsuites> <!-- использовать для составления набора тестов -->
        <testsuite name="tasks"> <!-- дочерний элемент -->
            <directory>tests</directory>
            // или
            <file> tests/MoneyTest.php</file>
            <file> тесты/CurrencyTest.php</file>
        </testsuite>
    </testsuites>

<!-- Установка INI-настроек, констант и глобальных переменных PHP -->
    <php>
        <includePath>.</includePath>
        <ini name="foo" value="bar"/>
        <const name="foo" value="bar"/>
        <var name="foo" value="bar"/>
<!--        ini_set('foo', 'bar');      -->
<!--        define('foo', 'bar');       -->
<!--        $GLOBALS['foo'] = 'bar';    -->
    </php>

<!-- можете увидеть, почему PHPUnit не работает -->
    <php>
        <ini name="display_errors" value="true"/>
    </php>

    //---------------laravel---------------
    <testsuites>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>
    </testsuites>
    <coverage processUncoveredFiles="true">
        <include>
            <directory suffix=".php">./app</directory>
        </include>
    </coverage>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="TELESCOPE_ENABLED" value="false"/>
    </php>

</phpunit>
```

</details>

[⏏ К содержанию](#содержание)

---

## Вступление

Для чего проводится тестирование ПО?

- Для проверки соответствия требованиям.
- Для обнаружение проблем на более ранних этапах разработки и предотвращение повышения стоимости продукта.
- Обнаружение вариантов использования, которые не были предусмотрены при разработке. А также взгляд на продукт со
  стороны пользователя.
- Повышение лояльности к компании и продукту, т.к. любой обнаруженный дефект негативно влияет на доверие пользователей.

Выводы консоли:

- `.` - Выводится, если тест успешно пройден.
- `F` - Выводится, когда утверждение не проходит во время выполнения тестового метода.
- `E` - Выводится, когда возникает ошибка во время запуска тестового метода.
- `R` - Выводится, когда тест был отмечен как рискованный (см. Рискованные тесты).
- `S` - Выводится, когда тест был пропущен (см. Неполные и пропущенные тесты).
- `I` - Выводится, когда тест отмечен как незавершённый или ещё не реализован (см. Неполные и пропущенные тесты).

<details>
<summary>Пример, хорошего (возможно) теста:</summary>

```php
<?php
// Service/UserRegistrator.php 

declare(strict_types = 1);

namespace Vendor\Project\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRegistrator
{
/** @var PasswordEncoderInterface */
private $passwordEncoder;

    /** @var LoggerInterface */
    private $logger;

    /** @var string */
    private $salt;

    public function __construct(PasswordEncoderInterface $passwordEncoder, LoggerInterface $logger, string $salt)
    {
        if (empty($salt)) {
            throw new \InvalidArgumentException('Variable "$salt" must be not empty');
        }

        $this->passwordEncoder = $passwordEncoder;
        $this->logger          = $logger;
        $this->salt            = $salt;
    }

    public function register(EntityManagerInterface $entityManager, string $userName, string $password): UserInterface
    {
        if (preg_match('/^[a-z][a-z0-9_]{5,254}$/i', $userName)) {
            throw new \InvalidArgumentException(
                sprintf('Variable "$userName" is invalid, actual value: "%s"', $userName)
            );
        } elseif (empty($password)) {
            throw new \InvalidArgumentException('Variable "$password" must be not empty');
        }

        $this->logger->info(sprintf('Register user: "%s"', $userName));

        try {
            $encodedPassword = $this->passwordEncoder->encodePassword($password, $this->salt);
            $user            = new User($userName, $encodedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            return $user;
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);

            throw $exception;
        }
    }
}
```

```php
<?php
// Tests/Service/UserRegistratorTest.php 

declare(strict_types = 1);

namespace Vendor\Project\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Vendor\Project\Service\UserRegistrator;

class UserRegistratorTest extends TestCase
{
    public function testConstructorWithEmptySalt(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Variable "$salt" must be not empty');

        $salt = '';

        /** @var PasswordEncoderInterface|MockObject $passwordEncoder */
        $passwordEncoder = $this->createMock(PasswordEncoderInterface::class);
        /** @var LoggerInterface|MockObject $logger */
        $logger = $this->createMock(LoggerInterface::class);

        new UserRegistrator($passwordEncoder, $logger, $salt);
    }

    public function testRegister(): void
    {
        $salt            = 'salt';
        $userName        = 'userName';
        $password        = 'password';
        $encodedPassword = 'encodedPassword';

        /** @var PasswordEncoderInterface|MockObject $passwordEncoder */
        $passwordEncoder = $this->createMock(PasswordEncoderInterface::class);
        /** @var LoggerInterface|MockObject $logger */
        $logger = $this->createMock(LoggerInterface::class);

        /** @var EntityManagerInterface|MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $userRegistrator = new UserRegistrator($passwordEncoder, $logger, $salt);

        $entityManagerIncrement = 0;

        $logger
            ->expects($this->once())
            ->method('info')
            ->with(sprintf('Register user: "%s"', $userName));

        $passwordEncoder
            ->expects($this->once())
            ->method('encodePassword')
            ->with($password, $salt)
            ->willReturn($encodedPassword);

        $entityManager
            ->expects($this->at($entityManagerIncrement++))
            ->method('persist')
            ->with(
                $this->callback(
                    function (UserInterface $user) use ($encodedPassword, $userName): bool {
                        $this->assertSame($userName, $user->getUsername());
                        $this->assertSame($encodedPassword, $user->getPassword());

                        return true;
                    }
                )
            );

        /** @noinspection PhpUnusedLocalVariableInspection */
        $entityManager
            ->expects($this->at($entityManagerIncrement++))
            ->method('flush');

        $logger
            ->expects($this->never())
            ->method('error');

        $user = $userRegistrator->register($entityManager, $userName, $password);

        $this->assertSame($userName, $user->getUsername());
        $this->assertSame($encodedPassword, $user->getPassword());
    }

    public function testRegisterWithInvalidUserName(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Variable "$userName" is invalid, actual value: "*"');

        $salt     = 'salt';
        $userName = '*';
        $password = 'password';

        /** @var PasswordEncoderInterface|MockObject $passwordEncoder */
        $passwordEncoder = $this->createMock(PasswordEncoderInterface::class);
        /** @var LoggerInterface|MockObject $logger */
        $logger = $this->createMock(LoggerInterface::class);

        /** @var EntityManagerInterface|MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $userRegistrator = new UserRegistrator($passwordEncoder, $logger, $salt);

        $logger
            ->expects($this->never())
            ->method('info');

        $passwordEncoder
            ->expects($this->never())
            ->method('encodePassword');

        $entityManager
            ->expects($this->never())
            ->method('persist');

        $entityManager
            ->expects($this->never())
            ->method('flush');

        $logger
            ->expects($this->never())
            ->method('error');

        $userRegistrator->register($entityManager, $userName, $password);
    }

    public function testRegisterWithInvalidPassword(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Variable "$password" must be not empty');

        $salt     = 'salt';
        $userName = 'userName';
        $password = '';

        /** @var PasswordEncoderInterface|MockObject $passwordEncoder */
        $passwordEncoder = $this->createMock(PasswordEncoderInterface::class);
        /** @var LoggerInterface|MockObject $logger */
        $logger = $this->createMock(LoggerInterface::class);

        /** @var EntityManagerInterface|MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $userRegistrator = new UserRegistrator($passwordEncoder, $logger, $salt);

        $logger
            ->expects($this->never())
            ->method('info');

        $passwordEncoder
            ->expects($this->never())
            ->method('encodePassword');

        $entityManager
            ->expects($this->never())
            ->method('persist');

        $entityManager
            ->expects($this->never())
            ->method('flush');

        $logger
            ->expects($this->never())
            ->method('error');

        $userRegistrator->register($entityManager, $userName, $password);
    }

    public function testRegisterWithUnexpectedDbException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('UnexpectedException');

        $salt            = 'salt';
        $userName        = 'userName';
        $password        = 'password';
        $encodedPassword = 'encodedPassword';
        $exception       = new \Exception('UnexpectedException');
        $logContext      = ['exception' => $exception];

        /** @var PasswordEncoderInterface|MockObject $passwordEncoder */
        $passwordEncoder = $this->createMock(PasswordEncoderInterface::class);
        /** @var LoggerInterface|MockObject $logger */
        $logger = $this->createMock(LoggerInterface::class);

        /** @var EntityManagerInterface|MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $userRegistrator = new UserRegistrator($passwordEncoder, $logger, $salt);

        $entityManagerIncrement = 0;

        $logger
            ->expects($this->once())
            ->method('info')
            ->with(sprintf('Register user: "%s"', $userName));

        $passwordEncoder
            ->expects($this->once())
            ->method('encodePassword')
            ->with($password, $salt)
            ->willReturn($encodedPassword);

        $entityManager
            ->expects($this->at($entityManagerIncrement++))
            ->method('persist')
            ->with(
                $this->callback(
                    function (UserInterface $user) use ($encodedPassword, $userName): bool {
                        $this->assertSame($userName, $user->getUsername());
                        $this->assertSame($encodedPassword, $user->getPassword());

                        return true;
                    }
                )
            );

        /** @noinspection PhpUnusedLocalVariableInspection */
        $entityManager
            ->expects($this->at($entityManagerIncrement++))
            ->method('flush')
            ->willThrowException($exception);

        $logger
            ->expects($this->once())
            ->method('error')
            ->with($exception->getMessage(), $logContext);

        $userRegistrator->register($entityManager, $userName, $password);
    }
}
```

</details>

[⏏ К содержанию](#содержание)

---

## 1. Структура теста

Каждый тест ТРЕБУЕТСЯ оформлять согласно структуре, описанной ниже (каждый блок отделяется от остальных пустой строкой).

1. Ожидаемый тип исключения.
2. Ожидаемое сообщение исключения.
3. Переменные, используемые в тесте.
4. Mock-объекты аргументов конструктора тестируемого класса.
5. Mock-объекты аргументов тестируемого метода.
6. Создание тестируемого объекта.
7. Инкременты вызовов mock-объектов.
8. Поведение методов mock-объектов согласно порядку их вызова.
9. Вызов тестируемого метода.
10. Проверка результатов.

ДОПУСКАЕТСЯ отклонение от данной структуры, в случае, если полное следование ей невозможно. Например, когда значение
переменной [3] определяется только после вызова конструктора тестируемого класса [6].

Характеристики хорошего и удобочитаемого теста:

1. Содержит только необходимые вызовы метода assert (желательно один).
2. Он очень понятно объясняет, что должно произойти при заданных условиях.
3. Он тестирует только одну ветку исполнения метода.
4. Он не делает заглушку для целой вселенной ради какого-то утверждения.

[⏏ К содержанию](#содержание)

---

## 2 Порядок утверждений для одного значения

В случае когда для проверки одного значения требуется несколько утверждений, эти утверждения ДОЛЖНЫ быть описаны в
порядке от максимально информативных до минимально информативных, далее от общих к частным.

```php
// Правильно
/** @var JsonResponse|Response $response */
$response = $action->run(/* ... */);

$this->assertSame($expectedContent, $response->getContent());
$this->assertSame(Response::HTTP_OK, $response->getStatusCode());
$this->assertInstanceOf(JsonResponse::class, $response);
```

```php
// Неправильно
// В случае возникновения ошибки не будет ясно, что же за ошибка произошла, вместо этого получим только несоответствие
// типа $response, или статус кода.
/** @var JsonResponse|Response $response */
$response = $action->run(/* ... */);

$this->assertInstanceOf(JsonResponse::class, $response);
$this->assertSame(Response::HTTP_OK, $response->getStatusCode());
$this->assertSame($expectedContent, $response->getContent());
```

```php
// Правильно
$exceptionContext = $object->method(/* ... */);

$this->assertIsArray($exceptionContext);
$this->assertNotEmpty($exceptionContext);
$this->assertArrayHasKey('exception', $exceptionContext);
$this->assertInstanceOf(\Exception::class, $exceptionContext['exception']);
$this->assertSame($expectedExceptionMessage, $exceptionContext['exception']->getMessage());
```

```php 
// Неправильно
$exceptionContext = $object->method(/* ... */);

$this->assertSame($expectedExceptionMessage, $exceptionContext['exception']->getMessage());
```

[⏏ К содержанию](#содержание)

---

## 3. Проверка утверждений на основании результатов собственных проверок

Проверка утверждений на основании результатов собственных проверок ДОПУСКАЕТСЯ только в случае, когда отсутствует
assert* метод, включающий эту проверку. Во всех остальных случая НЕОБХОДИМО использовать assert* метод.

> Распространенной ошибкой является "ручная" проверка значения и утверждение о ее ложном, или положительном результате.
> В следствии такого подхода, срабатывание утверждения не покажет информацию о том, что же пошло не так.

```php
// Правильно
$this->assertSame($expectedString, $actualString);

// Неправильно
$this->assertSame(true, $expectedString === $actualString);

// Неправильно
$this->assertTrue($expectedString === $actualString);
```

```php
// Правильно
$this->assertStringStartsWith($expectedPrefix, $actualString);

// Неправильно
$this->assertSame(0, strpos($actualString, $expectedPrefix));
```

```php
// Правильно
$this->assertTrue($actualBool);

// Неправильно
$this->assertTrue($actualBool === true);

// Неправильно
$this->assertSame(true, $actualBool);
```

[⏏ К содержанию](#содержание)

---

## 4. Проверка утверждений для числовых значений

Для проверок числовых значений без учета погрешности ДОЛЖЕН использоваться метод `assertSame`.

```php
// Правильно
$expected = 5;
$actual   = 5;
$this->assertSame($expected, $actual);

// Неправильно
$expected = 5;
$actual   = 5;
$this->assertEqual($expected, $actual);
```

Для проверок числовых значений с учетом погрешности ДОЛЖЕН использоваться метод `assertEqual` с обязательным указанием
погрешности.

```php
// Правильно
$expected = 5;
$actual   = 4.5;

$this->assertEqual($expected, $actual, '', 1);
```

[⏏ К содержанию](#содержание)

---

## 5. Делай правильно!

Правила:

### 1. Тесты не должны содержать операций ввода-вывода

**Основная причина**: операции ввода-вывода медленные и ненадёжные.

```php
public function getPeople(): array
{
  $rawPeople = file_get_contents(
    'people.json'
  ) ?? '[]';

  return json_decode(
    $rawPeople,
    true
  );
}
```

При начале тестирования с помощью этого метода будет создан локальный файл, и время от времени будут создаваться его
снимки:

```php
public function testGetPeopleReturnsPeopleList(): void
{
  $people = $this->peopleService
    ->getPeople();

  // assert it contains people
}
```

Для этого нам нужно настроить предварительные условия запуска тестов. На первый взгляд всё выглядит разумно, но на самом
деле это ужасно.

Пропуск теста из-за того, что не выполнены предварительные условия, не обеспечивает качество нашего ПО. Это лишь скроет
баги!

Исправляем ситуацию: **изолируем операции ввода-вывода, переложив ответственность на интерфейс.**

```php
// extract the fetching
// logic to a specialized
// interface
interface PeopleProvider
{
  public function getPeople(): array;
}

// create a concrete implementation
class JsonFilePeopleProvider
  implements PeopleProvider
{
  private const PEOPLE_JSON =
    'people.json';

  public function getPeople(): array
  {
    $rawPeople = file_get_contents(
      self::PEOPLE_JSON
    ) ?? '[]';

    return json_decode(
      $rawPeople,
      true
    );
  }
}

class PeopleService
{
  // inject via __construct()
  private PeopleProvider $peopleProvider;

  public function getPeople(): array
  {
    return $this->peopleProvider
      ->getPeople();
  }
}
```

Вместо `file_get_contents()` можно использовать слой абстракции вроде файловой системы Flysystem, для которой легко
сделать заглушки.

А тогда зачем нам `PeopleService`? Хороший вопрос. Для этого и нужны тесты: поставить архитектуру под сомнение и убрать
бесполезный код.

### 2. Тесты должны быть осознанными и осмысленными

```php
public function testCanFly(): void
{
  $noWings = new Person(0);
  $this->assertEquals(
    false,
    $noWings->canFly()
  );

  $singleWing = new Person(1);
  $this->assertTrue( // Использование конкретных утверждений сделает ваш тест гораздо удобочитаемее
    !$singleWing->canFly()
  );

  $twoWings = new Person(2);
  $this->assertTrue(
    $twoWings->canFly()
  );
}
```

Давайте адаптируем формат «дано, когда, тогда» и посмотрим, что получится:

```php
public function testCanFly(): void
{
  // Given
  $person = $this->givenAPersonHasNoWings();

  // Then
  $this->assertEquals(
    false,
    $person->canFly()
  );

  // Further cases...
}

private function givenAPersonHasNoWings(): Person
{
  return new Person(0);
}
```

Теперь раздел «Further cases», который дважды появляется в нашем тексте, является ярким свидетельством того, что тест
делает слишком много утверждений. При этом метод `testCanFly()` совершенно бесполезен.

```php
public function testCanFlyIsFalsyWhenPersonHasNoWings(): void
{
  $person = $this->givenAPersonHasNoWings();
  $this->assertFalse(
    $person->canFly()
  );
}

public function testCanFlyIsTruthyWhenPersonHasTwoWings(): void
{
  $person = $this->givenAPersonHasTwoWings();
  $this->assertTrue(
    $person->canFly()
  );
}
```

### 3. Тест не должен зависеть от других тестов

```php
public function testGenerateJWTToken(): void
{
  // ... $token
  $this->token = $token;
}

// @depends  testGenerateJWTToken
public function testExecuteAnAmazingFeature(): void
{
  // Execute using $this->token
}

// @depends  testExecuteAnAmazingFeature
public function testStateIsBlah(): void
{
  // Poll for state changes on
  // Logged-in interface
}
```

- PHPUnit не может гарантировать такой порядок исполнения.
- Тесты должны уметь исполняться независимо.
- Параллельные тесты могут сбоить случайным образом.

```php
public function testAmazingFeatureChangesState(): void
{
  // Given
  $token = $this->givenImAuthenticated();

  // When
  $this->whenIExecuteMyAmazingFeature(
    $token
  );
  $newState = $this->pollStateFromInterface(
    $token
  );

  // Then
  $this->assertEquals(
    'my-state',
    $newState
  );
}
```

### 4. Всегда внедряйте зависимости

**Основная причина**: очень дурной тон — создавать заглушку для глобального состояния. Отсутствие возможности создавать
заглушки для зависимостей не позволяет тестировать функцию.

> Совершенно нормально менять архитектуру, чтобы облегчить тестирование! А создавать методы для облегчения тестирования
> — не нормально.

```php
class FeatureToggle
{
  public function isActive(
    Id $feature
  ): bool {
    $cookieName = $feature->getCookieName();

    // Early return if cookie
    // override is present
    if (Cookies::exists(
      $cookieName
    )) {
      return Cookies::get(
        $cookieName
      );
    }

    // Evaluate feature toggle...
  }
}
```

Для его тестирования нам нужно понимать поведение класса Cookies и быть уверенными в том, что можем воспроизвести всё
связанное с этим окружение, в результате получив определённые ответы.

Ситуацию можно исправить, если внедрить экземпляр Cookies в качестве зависимости. Тест будет выглядеть так:

```php
// Test class...
private Cookies $cookieMock;

private FeatureToggle $service;

// Preparing our service and dependencies
public function setUp(): void
{
  $this->cookieMock = $this->prophesize(
    Cookies::class
  );

  $this->service = new FeatureToggle(
    $this->cookieMock->reveal()
  );
}

public function testIsActiveIsOverriddenByCookies(): void
{
  // Given
  $feature = $this->givenFeatureXExists();

  // When
  $this->whenCookieOverridesFeatureWithTrue(
    $feature
  );

  // Then
  $this->assertTrue(
    $this->service->isActive($feature)
  );
  // additionally we can assert
  // no other methods were called
}

private function givenFeatureXExists(): Id
{
  // ...
  return $feature;
}

private function whenCookieOverridesFeatureWithTrue(
  Id $feature
): void {
  $cookieName = $feature->getCookieName();
  $this->cookieMock->exists($cookieName)
    ->shouldBeCalledOnce()
    ->willReturn(true);

  $this->cookieMock->get($cookieName)
    ->shouldBeCalledOnce()
    ->willReturn(true);
}
```

### 5. Никогда не тестируйте защищённые/приватные методы

**Основная причина**: они влияют на то, как мы тестируем функции, определяя сигнатуру поведения: при таком-то условии,
когда я ввожу А, то ожидаю получить Б. **Приватные/защищённые методы не являются частью сигнатур функций.**

### 6. Никогда не тестируйте защищённые/приватные методы

В идеале, каждый класс должен иметь один тест. Этот тест должен покрывать все публичные методы в этом классе, а также
каждое условное выражение или оператор перехода…

Можно считать примерно так:

- Один класс = один тестовый случай.
- Один метод = один или несколько тестов.
- Одна альтернативная ветка (if/switch/try-catch/exception) = один тест.

```php
// class Person
public function eatSlice(Pizza $pizza): void
{
  // test exception
  if ([] === $pizza->slices()) {
    throw new LogicException('...');
  }

  // test exception
  if (true === $this->isFull()) {
    throw new LogicException('...');
  }

  // test default path (slices = 1)
  $slices = 1;
  // test alternative path (slices = 2)
  if (true === $this->isVeryHungry()) {
    $slices = 2;
  }

  $pizza->removeSlices($slices);
}
```

### 7. Поддерживайте набор тестов для решения проблем с регрессией

Рассмотрим функцию:

```php
function findById(string $id): object
{
    return fromDb((int) $id);
}
```

Вы думаете, что кто-то передаёт «10», но на самом деле передаётся «10 bananas». То есть приходят два значения, но одно
лишнее. У вас баг.

Что вы сделаете в первую очередь? Напишете тест, который обозначит такое поведение ошибочным!!!

```php
public function testFindByIdAcceptsOnlyNumericIds(): void
{
  $this->expectException(InvalidArgumentException::class);
  $this->expectExceptionMessage(
    'Only numeric IDs are allowed.'
  );

  findById("10 bananas");
}
```

Конечно, тесты ничего не передают. Но теперь вы знаете, что нужно сделать, чтобы они передавали. Исправьте ошибку,
сделайте тесты зелёными, разверните приложение и будьте счастливы.

[⏏ К содержанию](#содержание)

---

# Советы по модульному тестированию на примерах в PHP

[Ссылка на git анг](https://github.com/sarven/unit-testing-tips#mock-vs-stub)

[habr перевод на русский](https://habr.com/ru/company/vk/blog/549698/)

## 1. Тестовые дубли

Это фальшивые зависимости, используемые в тестах.

### Заглушки (Stub)

1. Имитатор (Dummy) - всего лишь простая реализация, которая ничего не делает.

```php
final class Mailer implements MailerInterface
{
    public function send(Message $message): void
    {
    }
}
```

2. Фальшивка (Fake)- это упрощённая реализация, эмулирующая нужное поведение.
3. Заглушка (Stub) - это простейшая реализация с прописанным в коде поведением.

```php
final class UniqueEmailSpecificationStub implements UniqueEmailSpecificationInterface
{
    public function isUnique(Email $email): bool
    {
        return true;
    }
}
$specificationStub = $this->createStub(UniqueEmailSpecificationInterface::class);
$specificationStub->method('isUnique')->willReturn(true);
```

### Моки (Mock)

1. Шпион (Spy) - реализация для проверки конкретного поведения.

```php
final class Mailer implements MailerInterface
{
    /**
     * @var Message[]
     */
    private array $messages;
    
    public function __construct()
    {
        $this->messages = [];
    }

    public function send(Message $message): void
    {
        $this->messages[] = $message;
    }

    public function getCountOfSentMessages(): int
    {
        return count($this->messages);
    }
}
```

2. Мок (Mock) - сконфигурированная имитация для проверки вызовов взаимодействующих объектов.

```php
$message = new Message('test@test.com', 'Test', 'Test test test');
$mailer = $this->createMock(MailerInterface::class);
$mailer
    ->expects($this->once())
    ->method('send')
    ->with($this->equalTo($message));
```

❗ Для проверки входящий взаимодействий используйте заглушку, а для проверки исходящих взаимодействий — мок.

[⏏ К содержанию](#содержание)

---

## Виды тестирования и Доп информация

![](https://hsto.org/webt/uw/-j/ul/uw-juldey34nxrwrf0kdbxztls4.jpeg)

- Виды-методы-уровни тестирования
  подробно (https://github.com/VladislavEremeev/QA_bible/blob/master/vidy-metody-urovni-testirovaniya/README.md)

- Фундаментальная теория тестирования (https://habr.com/ru/post/151926/)

`Верификация и валидация` — два понятия тесно связаны с процессами тестирования и обеспечения качества. К сожалению, их
часто путают, хотя отличия между ними достаточно существенны.

`Верификация (verification)` — это процесс оценки системы, чтобы понять, удовлетворяют ли результаты текущего этапа
разработки условиям, которые были сформулированы в его начале.

`Валидация (validation)` — это определение соответствия разрабатываемого ПО ожиданиям и потребностям пользователя, его
требованиям к системе.

### Дефект

`Дефект(bug)` — отклонение фактического результата от ожидаемого.

Отчёт о дефекте (bug report) — документ, который содержит отчет о любом недостатке в компоненте или системе, который
потенциально может привести компонент или систему к невозможности выполнить требуемую функцию.

Атрибуты отчета о дефекте:

1. Уникальный идентификатор (ID) — присваивается автоматически системой при создании баг-репорта.
2. Тема (краткое описание, Summary) — кратко сформулированный смысл дефекта, отвечающий на вопросы: Что? Где? Когда(при
   каких условиях)?
3. Подробное описание (Description) — более широкое описание дефекта (указывается опционально).
4. Шаги для воспроизведения (Steps To Reproduce) — описание четкой последовательности действий, которая привела к
   выявлению дефекта. В шагах воспроизведения должен быть описан каждый шаг, вплоть до конкретных вводимых значений,
   если они играют роль в воспроизведении дефекта.
5. Фактический результат (Actual result) — описывается поведение системы на момент обнаружения дефекта в ней. чаще
   всего, содержит краткое описание некорректного поведения(может совпадать с темой отчета о дефекте).
6. Ожидаемый результат (Expected result) — описание того, как именно должна работать система в соответствии с
   документацией.
7. Вложения (Attachments) — скриншоты, видео или лог-файлы.
8. Серьёзность дефекта (важность, Severity) — характеризует влияние дефекта на работоспособность приложения.
9. Приоритет дефекта (срочность, Priority) — указывает на очерёдность выполнения задачи или устранения дефекта.
10. Статус (Status) — определяет текущее состояние дефекта. Статусы дефектов могут быть разными в разных баг-трекинговых
    системах.
11. Окружение (Environment) – окружение, на котором воспроизвелся баг.

Градация Приоритета дефекта (Priority):

- P1 Высокий (`High`)
  Критическая для проекта ошибка. Должна быть исправлена как можно быстрее.
- P2 Средний (`Medium`)
  Не критичная для проекта ошибка, однако требует обязательного решения.
- P3 Низкий (`Low`)
  Наличие данной ошибки не является критичным и не требует срочного решения. Может быть исправлена, когда у команды
  появится время на ее устранение.

Существует шесть базовых типов задач:

- Эпик (`epic`) — большая задача, на решение которой команде нужно несколько спринтов.
- Требование (`requirement`) — задача, содержащая в себе описание реализации той или иной фичи.
- История (`story`) — часть большой задачи (эпика), которую команда может решить за 1 спринт.
- Задача (`task`) — техническая задача, которую делает один из членов команды.
- Под-задача (`sub-task`) — часть истории / задачи, которая описывает минимальный объем работы члена команды.
- Баг (`bug`) — задача, которая описывает ошибку в системе.

Тестовые среды

- Среда разработки (`Development Env`) – за данную среду отвечают разработчики, в ней они пишут код, проводят отладку,
  исправляют ошибки
- Среда тестирования (`Test Env`) – среда, в которой работают тестировщики (проверяют функционал, проводят smoke и
  регрессионные тесты, воспроизводят.
- Интеграционная среда (`Integration Env`) – среда, в которой проводят тестирование взаимодействующих друг с другом
  модулей, систем, продуктов.
- Предпрод (`Preprod Env`) – среда, которая максимально приближена к продакшену. Здесь проводится заключительное
  тестирование функционала.
- Продакшн среда (`Production Env`) – среда, в которой работают пользователи.

### Основные виды тестирования ПО

1. Классификация по запуску кода на исполнение:
    - `Статическое тестирование` - процесс тестирования, который проводится для верификации практически любого артефакта
      разработки: программного кода компонент, требований, системных спецификаций, функциональных спецификаций,
      документов
      проектирования и архитектуры программных систем и их компонентов.
    - `Динамическое тестирование` - тестирование проводится на работающей системе, не может быть осуществлено без
      запуска
      программного кода приложения.
2. Классификация по доступу к коду и архитектуре:
    - `Тестирование белого ящика` - метод тестирования ПО, который предполагает полный
      доступ к коду проекта.
    - `Тестирование серого ящика` - метод тестирования ПО, который предполагает частичный доступ к коду проекта (
      комбинация
      White Box и Black Box методов).
    - `Тестирование чёрного ящика` - метод тестирования ПО, который не предполагает доступа (полного или частичного) к
      системе. Основывается на работе исключительно с внешним интерфейсом тестируемой системы.
3. Классификация по уровню детализации приложения:
    - `Модульное тестирование` - проводится для тестирования какого-либо одного логически выделенного и изолированного
      элемента (модуля) системы в коде. Проводится самими разработчиками, так как предполагает полный доступ к коду.
    - `Интеграционное тестирование` - тестирование, направленное на проверку корректности взаимодействия нескольких
      модулей, объединенных в единое целое.
    - `Системное тестирование` - процесс тестирования системы, на котором проводится не только функциональное
      тестирование, но и оценка характеристик качества системы - ее устойчивости, надежности, безопасности и
      производительности.
    - `Приёмочное тестирование` - проверяет соответствие системы потребностям, требованиям и бизнес-процессам
      пользователя.
4. Классификация по степени автоматизации:
    - Ручное тестирование.
    - Автоматизированное тестирование.
5. Классификация по принципам работы с приложением
    - `Позитивное тестирование` - тестирование, при котором используются только
      корректные данные.
    - `Негативное тестирование` - тестирование приложения, при котором используются некорректные данные и выполняются
      некорректные операции.
6. Классификация по уровню функционального тестирования:
    - `Дымовое тестирование` (smoke test) -- тестирование, выполняемое на новой сборке, с целью подтверждения того, что
      программное обеспечение стартует и выполняет основные для бизнеса функции.
    - `Тестирование критического пути` (critical path) - направлено для проверки функциональности, используемой обычными
      пользователями во время их повседневной
      деятельности.
    - `Расширенное тестирование` (extended) - направлено на исследование всей заявленной в требованиях функциональности.
7. Классификация в зависимости от исполнителей:
    - `Альфа-тестирование` - является ранней версией программного продукта. Может выполняться внутри
      организации-разработчика с возможным частичным привлечением конечных пользователей.
    - `Бета-тестирование` - программное обеспечение, выпускаемое для ограниченного количества пользователей. Главная
      цель - получить отзывы клиентов о продукте и внести соответствующие изменения.
8. Классификация в зависимости от целей тестирования:
    - `Функциональное тестирование` (functional testing) - направлено на проверку корректности работы функциональности
      приложения.
    - `Нефункциональное тестирование` (non-functional testing) - тестирование атрибутов компонента или системы, не
      относящихся к функциональности.

        - `Тестирование производительности` (performance testing) - определение стабильности и потребления ресурсов в
          условиях различных сценариев
          использования и нагрузок.

        - `Нагрузочное тестирование` (load testing) - определение или сбор показателей производительности и времени
          отклика
          программно-технической системы или устройства в ответ на внешний запрос с целью установления соответствия
          требованиям, предъявляемым к данной системе (устройству).
        - `Тестирование масштабируемости` (scalability testing) - тестирование, которое измеряет производительность сети
          или
          системы, когда количество пользовательских запросов увеличивается или уменьшается.

        - `Объёмное тестирование` (volume testing) - это тип тестирования программного обеспечения, которое
          проводится для
          тестирования программного приложения с определенным объемом данных.
        - `Стрессовое тестирование` (stress testing) - тип тестирования направленный для проверки, как система
          обращается с
          нарастающей нагрузкой (количеством одновременных пользователей).
        - `Инсталляционное тестирование` (installation testing) - тестирование, направленное на проверку успешной
          установки
          и
          настройки, обновления или удаления приложения.
        - `Тестирование интерфейса` (GUI/UI testing) - проверка требований к пользовательскому интерфейсу.
        - `Тестирование удобства использования` (usability testing) - это метод тестирования, направленный на
          установление
          степени удобства использования, понятности и привлекательности для пользователей разрабатываемого продукта
          в
          контексте заданных условий.
        - `Тестирование локализации` (localization testing) - проверка адаптации программного обеспечения для
          определенной
          аудитории в соответствии с ее культурными особенностями.
        - `Тестирование безопасности` (security testing) - это стратегия тестирования, используемая для проверки
          безопасности системы, а также для анализа рисков, связанных с обеспечением целостного подхода к защите
          приложения, атак хакеров, вирусов, несанкционированного доступа к конфиденциальным данным.
        - `Тестирование надёжности` (reliability testing) - один из видов нефункционального тестирования ПО, целью
          которого
          является проверка работоспособности приложения при длительном тестировании с ожидаемым уровнем нагрузки.
        - `Регрессионное тестирование` (regression testing) - тестирование уже
          проверенной ранее функциональности после внесения изменений в код приложения, для уверенности в том, что
          эти
          изменения не внесли ошибки в областях, которые не
          подверглись изменениям.
        - `Повторное/подтверждающее тестирование` (re-testing/confirmation testing) - тестирование, во время
          которого
          исполняются тестовые сценарии, выявившие ошибки во время последнего запуска, для подтверждения успешности
          исправления
          этих ошибок.

> ❓ Юнит тесты Vs функциональные тесты ? - Функциональные тесты полностью определяют (по крайней мере должны)
> работоспособность продукта. И прежде всего нужны заказчику/руководителю разработки. Юнит тестирование прежде всего
> нужно самим разработчикам, для быстрого нахождения ошибок или проверки последствий рефакторинга.
> `Модульные тесты` написаны с точки зрения программиста . Они сделаны для того, чтобы тот или иной метод (или модуль )
> класса выполнял набор определенных задач.
> `Функциональные тесты` пишутся с точки зрения пользователя . Они гарантируют, что система работает так, как ожидают
> пользователи.

[⏏ К содержанию](#содержание)