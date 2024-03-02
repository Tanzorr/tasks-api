<?php

namespace App\Controllers;


use App\Models\Task;
use App\Requests\TaskRequest;

class TaskController
{
    public function __construct(private Task $task, private TaskRequest $taskRequest)
    {
    }

    public function index(): void
    {
        $result = $this->task->all();
        header('Content-Type: application/json');

        echo json_encode($result);
    }

    /**
     * @throws \Exception
     */
    public function add(): void
    {
        $validatedData = $this->taskRequest->validated();
        $this->task->add($validatedData);
    }

    public function delete($id): void
    {
        $this->task->delete($id);
    }
}