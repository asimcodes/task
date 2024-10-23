<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class Tasks extends Component
{
    public $tasks;
    public $title;
    public $description;
    public $status = 'To Do'; // Default status
    public $taskId; // For editing

    // Define the status options
    public $statusOptions = [
        'To Do' => 'To Do',
        'In Progress' => 'In Progress',
        'Done' => 'Done',
    ];
    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'status' => 'required|in:To Do,In Progress,Done',
    ];

    public function mount()
    {
        $this->tasks = Task::all(); // Load existing tasks
    }

    public function createTask()
    {
        $this->validate();

        Task::create([
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        $this->resetInputFields();
        $this->tasks = Task::all(); // Refresh tasks list
    }

    public function editTask($id)
    {
        $task = Task::findOrFail($id);
        $this->taskId = $task->id;
        $this->title = $task->title;
        $this->description = $task->description;
        $this->status = $task->status;
    }

    public function updateTask()
    {
        $this->validate();

        if ($this->taskId) {
            $task = Task::find($this->taskId);
            $task->update([
                'title' => $this->title,
                'description' => $this->description,
                'status' => $this->status,
            ]);
            $this->resetInputFields();
            $this->tasks = Task::all(); // Refresh tasks list
        }
    }

    public function deleteTask($id)
    {
        Task::find($id)->delete();
        $this->tasks = Task::all(); // Refresh tasks list
    }

    private function resetInputFields()
    {
        $this->title = '';
        $this->description = '';
        $this->status = 'To Do';
        $this->taskId = null;
    }

    public function render()
    {
        return view('livewire.tasks');
    }
}
