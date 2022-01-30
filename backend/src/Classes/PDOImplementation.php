<?php

namespace Eli\Vacation;

use PDO;
use PDOException;
use PDOStatement;

class PDOImplementation extends Database
{

    /**
     * @var false|PDOStatement
     */
    public PDOStatement | false $stmt;
    private bool $transactionInProgress = false;

    /**
     * @param  string  $sql
     * @param  array   $params
     * @param  bool    $dieOnError
     * @param  string  $msg
     *
     * @return false|PDOStatement|null
     */
    public function preparedQuery (string $sql, array $params = [], bool $dieOnError = false, string $msg = ''): bool | PDOStatement | null
    {
        $this->checkConnection();
        try {
            if ($this->debug) {
                echo $this->toSql($sql, $params, $dieOnError);
            }
            if (empty($params)) {
                return $this->query($sql);
            }

            $stmt = $this->prepare($sql);
            if (isset($params[0])) {
                // Question marks
                $paramIdx = 1;
                $params = $this->flatten_array($params);
                foreach ($params as $param) {
                    $type = $this->paramType($param);
                    $stmt->bindValue($paramIdx, $param, $type);
                    $paramIdx++;
                }

                $res = $stmt->execute();
                if (!$res && $dieOnError) {
                    if ($this->debug) {
                        echo $this->toSql($sql, $params, true);
                    }
                    die('Query failed');
                }
                return $stmt;
            }

            // Named params
            foreach ($params as $param => $value) {
                $type = $this->paramType($value);
                $stmt->bindValue(":$param", $value, $type);
            }
            $res = $stmt->execute();
            if (!$res && $dieOnError) {
                if ($this->debug) {
                    echo $this->toSql($sql, $params, true);
                }
                die('Query failed');
            }
            return $stmt;
        } catch (PDOException $e) {
            $formatted = $this->toSql($sql, $params);
            $this->checkError($msg . ' Query Failed:' . $formatted . '::' . $e->getMessage(), $dieOnError, $sql, $stmt);
            return null;
        }
    }

    private function checkConnection ()
    {
        if (!($this->database instanceof PDO)) {
            $this->databaseConnect();
        }
    }

    /**
     * Singleton DB connection
     */
    public function databaseConnect (): void
    {
        if (!($this->database instanceof PDO)) {
            $dbHost = getenv('DB_HOST') ?? '127.0.0.1';
            $dbPort = getenv('DB_PORT') ?? '3306';
            $dbName = getenv('DB_NAME') ?? '';
            $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName";
            $options = [
                PDO::ATTR_PERSISTENT         => true,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            try {
                $this->database = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'), $options);
            } catch (PDOException $e) {
                echo $e->getMessage();
                die();
            }
        }
    }

    /**
     * @param  string  $sql
     *
     * @return false|PDOStatement
     */
    public function query (string $sql): bool | PDOStatement
    {
        return $this->database->query($sql);
    }

    public function startTransaction (): bool | static
    {
        if ($this->transactionInProgress) {
            return true;
        }

        $this->database->beginTransaction();
        $this->transactionInProgress = true;
        return $this;
    }

    public function completeTransaction ()
    {
        if (!$this->transactionInProgress) {
            return true;
        }
        $this->database->commit();
        $this->transactionInProgress = false;
        return $this;
    }

    public function rollbackTransaction ()
    {
        $this->database->rollBack();
    }

    /**
     * @param $sql
     *
     * @return false|PDOStatement
     */
    public function prepare ($sql): bool | PDOStatement
    {
        return $this->database->prepare($sql);
    }

    private function paramType ($value): int
    {
        return match (true) {
            is_int($value)  => PDO::PARAM_INT,
            is_bool($value) => PDO::PARAM_BOOL,
            is_null($value) => PDO::PARAM_NULL,
            default         => PDO::PARAM_STR,
        };
    }
}