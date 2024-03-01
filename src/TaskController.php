<?php

namespace App;


use App\Models\Task;

class TaskController
{
    public function __construct(private Task $task, private TaskRequest $taskRequest)
    {
    }

    public function index(): void
    {
        print_r($this->task->all());
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