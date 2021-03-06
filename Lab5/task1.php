<?php

/**
 * Функция для обработки строки над которой применяется регулярное выражение
 * @param array $matches (массив совпадений элементов в соответствии с регулярным выражением)
 * @return string (строка после произведения замен)
 */
function multiplyByTwo(array $matches): string
{
    /**
     * $matches[0] - полное вхождение шаблона
     * $matches[1] - вхождение первой подмаски (в данном случае - число в кавычках)
     */
    return '\'' . $matches[1] * 2 . '\'';
}

/**
 * Проверяем количество аргументов строки
 * $argv[1] - строка с которой будем работать
 */
if ($argc == 2) {
    // Регулярное выражение для нахождения числа в кавычках
    $pattern = "/'(\d+)'/";
    // Получаем входную строку с консоли
    $input = $argv[1];
    // Получаем итоговую строку после применения регулярного выражения
    $result = preg_replace_callback($pattern, 'multiplyByTwo', $input);
    // Выводим результат в консоль
    echo "Исходная строка: $input \r\n";
    echo "Полученная строка: $result \r\n";
} else {
    // Если не указан аргумент, то выводим сообщение
    echo "Укажите строку с которой необходимо работать!\r\n";
}

