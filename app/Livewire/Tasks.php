<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class Tasks extends Component
{
    public $tasks;
    public $trashedTasks;
    public $title;
    public $description;
    public $status;
    public $priority;
    public $due_date;
    public $taskId;
    public $showTrashed = false;
    public $isEditing = false;

    // Public properties for counts
    public $normalTaskCount = 0;
    public $trashedTaskCount = 0;

    public $statusOptions = [
        'To Do' => 'To Do',
        'In Progress' => 'In Progress',
        'Done' => 'Done',
    ];

    public $priorityOptions = [
        'Low' => 'Low',
        'Medium' => 'Medium',
        'High' => 'High',
    ];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'status' => 'required|in:To Do,In Progress,Done',
        'priority' => 'required|in:Low,Medium,High',
        'due_date' => 'nullable|date',
    ];

    public function mount()
    {
        $this->resetInputFields();
        $this->loadTasks();
        $this->loadTrashedTasks();
    }

    private function loadTasks()
    {
        $this->tasks = Task::orderBy('due_date')->orderBy('created_at', 'desc')->get();
        $this->normalTaskCount = $this->tasks->count(); // Set normal task count
    }

    private function loadTrashedTasks()
    {
        $this->trashedTasks = Task::onlyTrashed()->orderBy('due_date')->orderBy('created_at', 'desc')->get();
        $this->trashedTaskCount = $this->trashedTasks->count(); // Set trashed task count
    }

    public function createTask()
    {
        $this->validate();

        Task::create([
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date,
        ]);

        session()->flash('message', 'Task created successfully.');
        $this->resetInputFields();
        $this->loadTasks(); // Refresh tasks list and counts
    }

    public function editTask($id)
    {
        $task = Task::find($id);
        if ($task) {
            $this->taskId = $task->id;
            $this->title = $task->title;
            $this->description = $task->description;
            $this->status = $task->status;
            $this->priority = $task->priority;
            $this->due_date = $task->due_date;
            $this->isEditing = true;
        } else {
            session()->flash('message', 'Task not found.');
        }
    }

    public function updateTask()
    {
        $this->validate();

        $task = Task::find($this->taskId);
        if ($task) {
            $task->update([
                'title' => $this->title,
                'description' => $this->description,
                'status' => $this->status,
                'priority' => $this->priority,
                'due_date' => $this->due_date,
            ]);
            session()->flash('message', 'Task updated successfully.');
            $this->resetInputFields();
            $this->loadTasks(); // Refresh tasks list and counts
        } else {
            session()->flash('message', 'Task not found.');
        }

        $this->isEditing = false;
    }

    public function deleteTask($id)
    {
        $task = Task::find($id);
        if ($task) {
            $task->delete();
            session()->flash('message', 'Task deleted successfully.');
            $this->loadTasks(); // Refresh tasks list and counts
            $this->loadTrashedTasks(); // Update trashed tasks count
        } else {
            session()->flash('message', 'Task not found.');
        }
    }

    public function cancelEdit()
    {
        $this->resetInputFields();
        $this->isEditing = false;
    }

    public function toggleTrashed()
    {
        $this->showTrashed = !$this->showTrashed;
        if ($this->showTrashed) {
            $this->loadTrashedTasks();
        } else {
            $this->trashedTasks = [];
        }
    }

    public function restoreTask($id)
    {
        $task = Task::onlyTrashed()->find($id);
        if ($task) {
            $task->restore();
            session()->flash('message', 'Task restored successfully.');
            $this->loadTasks(); // Refresh tasks list and counts
            $this->loadTrashedTasks(); // Update trashed tasks count
        }
    }

    private function resetInputFields()
    {
        $this->title = '';
        $this->description = '';
        $this->status = 'To Do';
        $this->priority = 'Medium';
        $this->due_date = null;
        $this->taskId = null;
        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.tasks', [
            'tasks' => $this->tasks,
            'trashedTasks' => $this->trashedTasks,
            'showTrashed' => $this->showTrashed,
            'isEditing' => $this->isEditing,
            'normalTaskCount' => $this->normalTaskCount,
            'trashedTaskCount' => $this->trashedTaskCount,
        ]);
    }
}
