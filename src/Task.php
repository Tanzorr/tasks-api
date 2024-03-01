<?php

namespace App;

class Task
{public function __construct(
        private readonly Filesystem $filesystem,
        protected string            $tasksPatch
    )
    {
    }

    public function all(): array
    {
        return $result = $this->readTaskFile();
    }

    /**
     * @throws \Exception
     */
    public function add(array $taskData): void
    {
        $tasks = $this->readTaskFile();

        $title= $taskData['title'];

        if (isset($tasks[$title])) {
            throw new \Exception("Task with title '{$title}' already exists. Please choose another one.");
        }

        $tasks[$title][] = $taskData;
        $this->filesystem->put($this->tasksPatch, json_encode($tasks));
    }

    public function delete(string $id): void
    {
        $tasks = $this->readTaskFile();

        if (!isset($tasks[$id])) {
            throw new \Exception("Task with ID '{$id}' does not exist.");
        }

        unset($tasks[$id]);

        $this->filesystem->put($this->tasksPatch, json_encode($tasks));
    }

    private function readTaskFile(): array
    {
        if (!$this->filesystem->exists($this->tasksPatch)) {
            $this->filesystem->put($this->tasksPatch, json_encode([]));

            return [];
        }

        $tasks = $this->filesystem->get($this->tasksPatch);

        if ($tasks === '') {
            return [];
        }

        return json_decode($tasks, true);
    }
}