<?php

namespace Eli\Vacation;

use Exception;
use JetBrains\PhpStorm\NoReturn;

class Response
{
    private bool $success = false;
    private string $message = '';
    private int $code = 200;
    protected bool $silent = true;
    protected int $responseCode = 200;
    protected ?string $title = null;
    private array $data = [];

    public function setStatusCode (int $code)
    {
        http_response_code($code);
    }

    public function redirect (string $location)
    {
        header("Location:$location");
    }

    /**
     * @param  bool  $success
     *
     * @return Response
     */
    public function setSuccess (bool $success): Response
    {
        $this->success = $success;
        return $this;
    }

    /**
     * @param  string  $message
     *
     * @return Response
     */
    public function setMessage (string $message): Response
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param  int  $code
     *
     * @return Response
     */
    public function setCode (int $code): Response
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param  array  $data
     *
     * @return Response
     */
    public function setData (array $data): Response
    {
        $this->data = $data;
        return $this;
    }

    final public function __toString ()
    {
        $data['success'] = $this->success;
        $data['message'] = $this->message;
        $data['data'] = $this->data;
        if (!is_null($this->title)) {
            $data['title'] = $this->title;
        }
        $res = json_encode(static::convertEncoding($data));
        if (!$this->silent && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception(json_last_error_msg());
        }
        return $res;
    }

    public static function convertEncoding ($input)
    {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                $input[$key] = static::convertEncoding($value);
            }
        } elseif (is_string($input)) {
            return mb_convert_encoding($input, 'UTF-8', 'UTF-8');
        }
        return $input;
    }

    public function setSilent (bool $value): static
    {
        $this->silent = $value;
        return $this;
    }


    public function setTitle (string $value): static
    {
        $this->title = $value;
        return $this;
    }

    public function getSuccess (): bool
    {
        return $this->success;
    }

    public function getMessage (): string
    {
        return $this->message;
    }

    public function getData (): array
    {
        return $this->data;
    }

    public function getCode (): int
    {
        return $this->responseCode;
    }

    #[NoReturn] public function sendResponse ()
    {
        $this->setStatusCode($this->code);
        die(
        json_encode([
            'success' => $this->success,
            'message' => $this->message,
            'data'    => $this->data,
        ])
        );
    }
}