<?php

namespace App;

class Task
{
    private array $attributes = [
        'title' => '',
        'description' => '',
        'has_deadline' => false,
        'deadline' => null,
        'author' => ''
    ];

    public function __construct(
        private readonly Filesystem $filesystem,
        protected string            $tasksPatch
    )
    {}

    public function all(): array
    {
        return $result = $this->readTaskFile();
    }

    public function add(array $dataTask): void
    {
        $tasks = $this->readTaskFile();

        if (isset($tasks[$dataTask['title']])) {
            echo "Tis task title already exits. Please chose another one.";
            return;
        }

        $tasks[$dataTask['title']][] = $dataTask;
        $this->filesystem->put($this->tasksPatch, json_encode($tasks));
    }

    public function delete(string $id): void
    {
        $tasks = $this->readTaskFile();
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