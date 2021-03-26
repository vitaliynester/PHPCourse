<?php

require_once "BaseTaskClass.php";

class TaskA extends BaseTaskClass
{
    /**
     * Проверить полученное решение с тестовым
     * @param string $inputPath (путь до входных данных теста)
     * @param string $outputPath (путь до правильного результата теста)
     */
    public function checkTask(string $inputPath, string $outputPath)
    {
        try {
            $outputData = $this->openFile($outputPath);
        } catch (Exception $e) {
            echo $e;
            die();
        }
        $this->solve($inputPath);
        $result = (string)$outputData[0];
        if ($this->getResult() == $result) {
            echo '<span style="color:green;text-align:center;">PASSED!</span>';
        } else {
            echo '<span style="color:red;text-align:center;">FAILED! ' . $result . ' BUT ' . $this->getResult() . '</span>';
        }
    }

    /**
     * Читает данные из файла переданного в $filePath
     * @param string $filePath (путь до файла)
     * @return array (ассоциативный массив с номером строки и её содержимым)
     * @throws Exception (ошибки чтения файла)
     */
    private function openFile(string $filePath): array
    {
        $handle = fopen($filePath, "r");
        if ($handle) {
            $result = array();
            while (($buffer = fgets($handle, 4096)) !== false) {
                array_push($result, $buffer);
            }
            if (!feof($handle)) {
                throw new Exception("Ошибка: Не удалось прочитать данные из файла!\n");
            }
            fclose($handle);
            return $result;
        } else {
            throw new Exception("Ошибка: Не удалось открыть файл!\n");
        }
    }

    /**
     * Решение задания А с входными данными из $file
     * @param string $filePath (путь до входного файла)
     */
    public function solve(string $filePath)
    {
        // Получение данных файла
        try {
            $inputData = $this->openFile($filePath);
        } catch (Exception $e) {
            /**
             * Если не получилось прочитать данные из файла,
             * то выводим ошибку и завершаем работу
             */
            echo $e;
            die();
        }
        // Получаем количество ставок человека
        $betCount = (int)$inputData[0];
        // Получаем список всех ставок человека
        $bets = $this->getBets(1, $betCount, $inputData);
        // Получаем количество игр
        $gameCount = (int)$inputData[$betCount + 1];
        // Получаем список всех игр
        $games = $this->getBets(2 + $betCount, $gameCount, $inputData);
        // Устанавливаем полученный результат в переменную result
        $this->result = $this->calculateResultBalance($bets, $games);
    }

    /**
     * Получает срез массива по указанным параметрам
     * @param int $startPosition (начальная позиция среза)
     * @param int $betCount (количество ставок/игр)
     * @param array $fileData (данные считанные из файла)
     * @return array (массив с играми)
     */
    private function getBets(int $startPosition, int $betCount, array $fileData): array
    {
        return array_slice($fileData, $startPosition, $betCount);
    }

    /**
     * Считает сумму выигрыша человека
     * @param array $bets (массив со ставками человека)
     * @param array $games (массив со состоявшимися играми)
     * @return float (результирующий баланс человека)
     */
    private function calculateResultBalance(array $bets, array $games): float
    {
        // Ассоциативные массивы с результатами ставок и игр
        $betWithGame = array();
        $gameResult = array();
        // Профит от всех ставок
        $profit = 0.0;

        // Проходимся по всем ставкам игрока
        foreach ($bets as $bet) {
            $betData = explode(" ", $bet);
            array_push($betWithGame, array(
                "gameId" => trim($betData[0]),
                "betValue" => trim($betData[1]),
                "team" => trim($betData[2])
            ));
        }

        // Проходимся по всем результатам игры
        foreach ($games as $game) {
            $gameData = explode(" ", $game);
            array_push($gameResult, array(
                "gameId" => trim($gameData[0]),
                "coefficientLTeam" => trim($gameData[1]),
                "coefficientRTeam" => trim($gameData[2]),
                "coefficientDTeam" => trim($gameData[3]),
                "teamWinner" => trim($gameData[4])
            ));
        }

        // Проходимся по всем ставкам и определеяем итоговый профит
        foreach ($betWithGame as $bet) {
            $profit += $this->getBetProfit($bet, $gameResult);
        }

        return $profit;
    }

    /**
     * Получает результат ставки на игру
     * @param array $bet (инфомрация о ставке на игру)
     * @param array $games (информация о всех играх)
     * @return float (результат ставки на игру)
     */
    private function getBetProfit(array $bet, array $games): float
    {
        $game = $games[$bet['gameId'] - 1];
        if ($bet['team'] == $game['teamWinner']) {
            $betProfit = $bet['betValue'] * $game['coefficient' . $bet['team'] . 'Team'] - $bet['betValue'];
        } else {
            $betProfit = -$bet['betValue'];
        }
        return $betProfit;
    }

    /**
     * Возвращает установленный результат работы функции
     * @return mixed (результат вычислений задания)
     */
    public function getResult()
    {
        return parent::getResult();
    }
}