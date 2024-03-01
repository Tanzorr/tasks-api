<?php

namespace App;

use App\Core\Validator;

class TaskValidator extends Validator
{

    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'has_deadline' => ['string'],
            'deadline' => ['deadline'],
            'author' => ['required', 'string'],
        ];
    }

    protected function validateRequired(string $field, $value): void
    {
        if ($value === null || $value === '') {
            $this->addError($field, "The $field field is required.");
        }
    }

    protected function validateString(string $field, $value): void
    {
        if (!is_string($value)) {
            $this->addError($field, "The $field field must be a string.");
        }
    }

    protected function validateBoolean(string $field, $value): void
    {
        if (!is_bool($value)) {
            $this->addError($field, "The $field field must be a boolean.");
        }
    }

    protected function validateNonEmptyString(string $field, $value): void
    {
        if (!is_string($value) || $value === '') {
            $this->addError($field, "The $field field must be a non-empty string.");
        }
    }

    protected function validateDeadline(string $field, $value): void
    {
        $hasDeadline = $this->data['has_deadline'] ?? null;

        if ($hasDeadline !== '' && $value === '') {
            $this->addError($field, "If has_deadline, then deadline should not be an empty string.");
        }
    }
}