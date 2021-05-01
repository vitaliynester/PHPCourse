## Лабораторная работа №5 по PHP

### Задание:

1. Дана строка с целыми числами. Найдите числа, стоящие в кавычках и увеличьте их в два раза. Пример: из строки 2aaa'3'
   bbb'4' сделаем строку 2aaa'6'bbb'8'.

2. В БД хранятся новости, содержащие ссылки на внешнюю систему такого вида

http://asozd.duma.gov.ru/main.nsf/(Spravka)?OpenAgent&RN=<номер_законопроекта>&<целое_число>.

Например: https://pastebin.com/sNk6ntM4, https://pastebin.com/VmtfjiMb

Система переехала на новую платформу и ссылки стали иметь формат http://sozd.parlament.gov.ru/bill/<номер_законопроекта>
. Подготовить скрипт, который с помощью регулярного выражения заменит в БД ссылки старого формата на новые. Номер
законопроекта может содержать цифры и дефис.

### Ход работы:

Для каждого из задания был подготовлен файл `.php`: `task1.php` и `task2.php`.

#### Задание 1:

Для запуска первого задания необходимо в консоли выполнить следующую команду:

```bash
php task1.php "2aaa'3'bbb'4'"
```

После этого в консоли будет выведен ответ в следующем формате:

```bash
Исходная строка: 2aaa'3'bbb'4'
Полученная строка: 2aaa'6'bbb'8'
```

#### Задание 2:

Для запуска второго задания необходимо в консоли выполнить следующую команду:

```bash
php task2.php src_urls.txt result.txt
```

В данной команде `src_urls.txt` это список всех адресов в старом формате. Пример 5 первых строк представлен ниже:

```
http://asozd.duma.gov.ru/main.nsf/(Spravka)?OpenAgent&RN=99112505-2&12
http://asozd.duma.gov.ru/main.nsf/(Spravka)?OpenAgent&RN=99111546-2
http://asozd.duma.gov.ru/main.nsf/(Spravka)?OpenAgent&RN=99038827-2&32
http://asozd.duma.gov.ru/main.nsf/(Spravka)?OpenAgent&RN=99019846-2&11
http://asozd.duma.gov.ru/main.nsf/(Spravka)?OpenAgent&RN=98100138-2
```

Параметр `result.txt` это файл, в который необходимо поместить преобразованный результат. Пример 5 первых строк после
преобразования представлен ниже:

```
https://sozd.duma.gov.ru/bill/99112505-2
https://sozd.duma.gov.ru/bill/99111546-2
https://sozd.duma.gov.ru/bill/99038827-2
https://sozd.duma.gov.ru/bill/99019846-2
https://sozd.duma.gov.ru/bill/98100138-2
```