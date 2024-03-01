<?php

namespace App\Core;

abstract class Validator
{
    protected array $errors = [];

    public function __construct(protected array $data)
    {
    }

    abstract public function rules(): array;

    public function validate(): bool
    {
        $rules = $this->rules();

        foreach ($rules as $field => $fieldRules) {
            $value = $this->data[$field] ?? null;

            foreach ($fieldRules as $rule) {
                $this->validateRule($field, $value, $rule);
            }
        }

        return empty($this->errors);
    }

    private function validateRule(int|string $field, mixed $value, mixed $rule): void
    {
        $ruleName = $rule;
        $parameters = [];

        if (str_contains($rule, ':')) {
            list($ruleName, $parameters) = explode(':', $rule, 2);
            $parameters = explode(',', $parameters);
        }

        $methodName = 'validate' . ucfirst($ruleName);

        if (method_exists($this, $methodName)) {
            $this->$methodName($field, $value, ...$parameters);
        }
    }

    protected function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}