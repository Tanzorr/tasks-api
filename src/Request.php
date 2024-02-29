<?php

namespace App;

class Request
{
    private array $input;

    public function __construct()
    {
        $this->input = array_merge($_GET, $_POST);
    }

    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getBody(): array
    {
        $body = [];

        if ($this->method() === 'post' && strtolower($_SERVER['CONTENT_TYPE']) === 'application/json') {
            $json = file_get_contents('php://input');
            $body = json_decode($json, true);

            if ($body === null) {
                throw new \Exception('Body is ampty');
            }

            $body = array_map('htmlspecialchars', $body);
        }

        return $body;
    }

    public function get(string $key, $default = null)
    {
        return $this->input[$key] ?? $default;
    }

    public function only(array $keys): array
    {
        return array_intersect_key($this->input, array_flip($keys));
    }

    public function has(string $key): bool
    {
        return isset($this->input[$key]);
    }
}