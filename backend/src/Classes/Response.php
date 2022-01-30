<?php

namespace Eli\Vacation;

use JetBrains\PhpStorm\NoReturn;

class Response
{
    private bool $success = false;
    private string $message = '';
    private int $code = 200;
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