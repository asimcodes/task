<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class Tasks extends Component
{
    public $tasks;
    public $trashedTasks; // To hold trashed tasks
    public $title;
    public $description;
    public $status = 'To Do'; // Default status
    public $priority = 'Medium'; // Default priority
    public $due_date; // Due date for the task
    public $taskId; // For editing
    public $showTrashed = false; // To toggle visibility of trashed tasks

    // Define the status options
    public $statusOptions = [
        'To Do' => 'To Do',
        'In Progress' => 'In Progress',
        'Done' => 'Done',
    ];

    // Define the priority options
    public $priorityOptions = [
        'Low' => 'Low',
        'Medium' => 'Medium',
        'High' => 'High',
    ];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'status' => 'required|in:To Do,In Progress,Done',
        'priority' => 'required|in:Low,Medium,High', // Validation for priority
        'due_date' => 'nullable|date', // Validation for due date
    ];

    public function mount()
    {
        $this->loadTasks();
        $this->loadTrashedTasks();
    }

    private function loadTasks()
    {
        $this->tasks = Task::orderBy('due_date') // Order by due_date first
        ->orderBy('created_at', 'desc') // Then by created_at in descending order
        ->get();
    }

    private function loadTrashedTasks()
    {
        $this->trashedTasks = Task::onlyTrashed()
            ->orderBy('due_date') // Order by due_date first
            ->orderBy('created_at', 'desc') // Then by created_at in descending order
            ->get();
    }


    public function createTask()
    {
        $this->validate();

        Task::create([
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority, // Save priority
            'due_date' => $this->due_date, // Save due date
        ]);

        $this->resetInputFields();
        $this->loadTasks(); // Refresh tasks list
    }

    public function editTask($id)
    {
        $task = Task::findOrFail($id);
        $this->taskId = $task->id;
        $this->title = $task->title;
        $this->description = $task->description;
        $this->status = $task->status;
        $this->priority = $task->priority; // Set priority for editing
        $this->due_date = $task->due_date; // Set due date for editing
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
                'priority' => $this->priority, // Update priority
                'due_date' => $this->due_date, // Update due date
            ]);
            $this->resetInputFields();
            $this->loadTasks(); // Refresh tasks list
        }
    }

    public function deleteTask($id)
    {
        Task::find($id)->delete();
        session()->flash('message', 'Task deleted successfully.'); // Flash message for feedback
        $this->loadTasks(); // Refresh tasks list
    }

    // Toggle visibility of trashed tasks
    public function toggleTrashed()
    {
        $this->showTrashed = !$this->showTrashed;
        if ($this->showTrashed) {
            $this->loadTrashedTasks(); // Load trashed tasks only when shown
        } else {
            $this->trashedTasks = []; // Clear trashed tasks when not showing
        }
    }

    // Restore a trashed task
    public function restoreTask($id)
    {
        $task = Task::onlyTrashed()->find($id);
        if ($task) {
            $task->restore();
            session()->flash('message', 'Task restored successfully.'); // Flash message for feedback
            $this->loadTasks(); // Refresh tasks list to include restored task
            $this->loadTrashedTasks(); // Refresh trashed tasks list
        }
    }

    private function resetInputFields()
    {
        $this->title = '';
        $this->description = '';
        $this->status = 'To Do';
        $this->priority = 'Medium'; // Reset to default priority
        $this->due_date = null; // Reset due date
        $this->taskId = null;
    }

    public function render()
    {
        $this->loadTasks(); // Always load tasks when rendering to ensure they're up-to-date
        if ($this->showTrashed) {
            $this->loadTrashedTasks(); // Load trashed tasks if visibility is toggled
        }

        return view('livewire.tasks', [
            'tasks' => $this->tasks,
            'trashedTasks' => $this->trashedTasks,
            'showTrashed' => $this->showTrashed,
        ]);
    }
}
