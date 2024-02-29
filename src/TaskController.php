<?php

namespace App;


class TaskController
{
    public function __construct(private Task $task, private TaskRequest $taskRequest)
    {
    }

    public function index()
    {
        print_r($this->task->all());
    }

    public function add(): void
    {
        $validatedData = $this->taskRequest->validated();
        $this->task->add($validatedData);
    }

    public function delete($id)
    {
        $this->task->delete($id);
    }
}