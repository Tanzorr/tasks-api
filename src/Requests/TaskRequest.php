<?php

namespace App\Requests;

use App\Core\Request;
use App\Validators\TaskValidator;

class TaskRequest extends Request
{

    public function __construct()
    {}

    /**
     * @throws \Exception
     */
    public function validated()
    {
        $requestData = $this->getBody();



        $validator = new TaskValidator($requestData);
        if ($validator->validate()) {
            return $requestData;
        } else {
            $errors = $validator->getErrors();

            foreach ($errors as $field => $fieldErrors) {
                foreach ($fieldErrors as $error) {
                    echo "Validation error for $field: $error\n";
                }
            }
        }
    }
}