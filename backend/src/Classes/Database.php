<?php

namespace Eli\Vacation;


use JetBrains\PhpStorm\Pure;
use PDO;
use PDOStatement;

abstract class Database
{
    /**
     * @var Database| PDO
     */
    protected Database | PDO $database;
    /**
     * @var bool
     */
    protected bool $debug = false;

    /**
     * @var LogWriter
     */
    private LogWriter $log;

    protected static ?Database $instance = null;

    #[Pure] protected function __construct ()
    {
        $this->log = new LogWriter();
    }

    /**
     * @return static
     */
    public static function getInstance (): Database
    {
        if (is_null(static::$instance) || !(static::$instance instanceof static)) {
            static::$instance = new static();
            static::$instance->databaseConnect();
            return static::$instance;
        }

        return static::$instance;
    }

    abstract public function databaseConnect (): void;

    /**
     * @param bool $debug
     */
    public function setDebug (bool $debug): void
    {
        $this->debug = $debug;
    }

    /**
     * @param string              $msg
     * @param false               $dieOnError
     * @param string              $sql
     * @param  PDOStatement|null  $stmt
     *
     * @return bool
     */
    public function checkError (string $msg = '', bool $dieOnError = false, string $sql = '', ?PDOStatement $stmt = null): bool
    {
        $dbErrorInfo = is_array($this->database->errorInfo()) ? implode(' ', $this->database->errorInfo()) : $this->database->errorInfo();
        $dbErrorCode = $this->database->errorCode();
        $context = [
            'query'        => $sql,
            'query_string' => $stmt->queryString ?? 'No query string provided.',
            'error_info'   => $dbErrorInfo,
            'error_code'   => $dbErrorCode,
        ];
        if ($dieOnError) {
            $bt = debug_backtrace();
            $ut = [];
            foreach ($bt as $t) {
                $ut[] = ['file' => $t['file'], 'line' => $t['line'], 'function' => $t['function']];
            }

            var_dump($dbErrorInfo);
            var_dump($dbErrorCode);
            var_dump($ut);

            $this->log->error(
                "Database query error error " . $msg . "->[" . $dbErrorCode . "]" . $dbErrorInfo . var_export($ut),
                $context
            );
        } else {
            $this->log->error("Database query error " . $msg . "->[" . $dbErrorCode . "]", $context);
        }
        return false;
    }

    /**
     * @param string $sql
     * @param array  $params
     * @param bool   $dieOnError
     * @param string $msg
     *
     * @return false|PDOStatement|null
     */
    abstract public function preparedQuery (string $sql, array $params = [], bool $dieOnError = false, string $msg = ''): bool | PDOStatement | null;

    abstract public function prepare ($sql);

    public function flatten_array ($input, $output = null)
    {
        if (empty($input)) {
            return null;
        }
        if (empty($output)) {
            $output = [];
        }
        foreach ($input as $value) {
            if (is_array($value)) {
                $output = $this->flatten_array($value, $output);
            } else {
                $output[] = $value;
            }
        }
        return $output;
    }

    /**
     * @param string $query
     * @param array  $data
     * @param bool   $die
     *
     * @return array|string|string[]|null
     * Replace placeholders with the provided values.
     * TODO: this method is not yet 100 correct, so need to improve it.
     */
    public function toSql (string $query, array $data, bool $die = false): array | string | null
    {
        $keys = [];
        $values = $data;
        $named_params = true;
        // build a regular expression for each parameter
        foreach ($data as $key => $value) {
            if (is_string($key)) {
                $keys[] = '/:' . $key . '/';
            } else {
                $keys[] = '/[?]/';
                $named_params = false;
            }

            if (is_string($value)) {
                $values[$key] = "'" . $value . "'";
            }

            if (is_array($value)) {
                $values[$key] = "'" . implode("','", $value) . "'";
            }

            if (is_null($value)) {
                $values[$key] = 'NULL';
            }
        }

        if ($named_params) {
            $query = preg_replace($keys, $values, $query);
        } else {
            $query = $query . ' ';
            $bits = explode('?', $query);

            $query = '';
            for ($i = 0; $i < count($bits); $i++) {
                $query .= $bits[$i];

                if (isset($values[$i])) {
                    $query .= ' ' . $values[$i] . ' ';
                }
            }
        }

        if ($die) {
            die($query);
        }

        return $query;
    }
}
