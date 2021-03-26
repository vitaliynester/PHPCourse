<?php

abstract class BaseTaskClass
{
    // Полученный результат выполнения задания
    protected $result;

    /**
     * Решить данное задание со всходными параметрами из $filePath
     * @param string $filePath (путь до файла с исходными данными)
     */
    abstract public function solve(string $filePath);

    /**
     * Проверить полученное решение с тестовым
     * @param string $inputPath (путь до входных данных теста)
     * @param string $outputPath (путь до правильного результата теста)
     */
    abstract public function checkTask(string $inputPath, string $outputPath);

    /**
     * Возвращаем результат полученный при выполнеии функции solve
     * @return mixed (результат функции solve)
     */
    public function getResult()
    {
        return $this->result;
    }
}