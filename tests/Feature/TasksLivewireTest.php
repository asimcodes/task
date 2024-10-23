<?php

namespace Tests\Feature;

use App\Livewire\Tasks;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TasksLivewireTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_task()
    {
        Livewire::test(Tasks::class)
            ->set('title', 'New Task')
            ->set('description', 'Task Description')
            ->set('status', 'To Do')
            ->set('priority', 'Medium')
            ->set('due_date', '2024-10-30')
            ->call('createTask');

        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'description' => 'Task Description',
        ]);
    }

    /** @test */
    public function it_can_update_a_task()
    {
        $task = Task::create([
            'title' => 'Old Task',
            'description' => 'Old Description',
            'status' => 'To Do',
            'priority' => 'Medium',
            'due_date' => '2024-10-30',
        ]);

        Livewire::test(Tasks::class)
            ->set('taskId', $task->id)
            ->set('title', 'Updated Task')
            ->set('description', 'Updated Description')
            ->set('status', 'Done')
            ->set('priority', 'High')
            ->set('due_date', '2024-11-30')
            ->call('updateTask');

        $this->assertDatabaseHas('tasks', [
            'title' => 'Updated Task',
            'description' => 'Updated Description',
        ]);
    }

    /** @test */
    public function it_can_delete_a_task()
    {
        $task = Task::create([
            'title' => 'Task to Delete',
            'description' => 'This task will be deleted.',
            'status' => 'To Do',
            'priority' => 'Medium',
            'due_date' => '2024-10-30',
        ]);

        Livewire::test(Tasks::class)
            ->call('deleteTask', $task->id);

        // Check if the task is soft deleted
        // Assert that the task is soft deleted
        $this->assertSoftDeleted('tasks', [
            'id' => $task->id,
        ]);
    }


    /** @test */
    public function it_can_restore_a_trashed_task()
    {
        $task = Task::create([
            'title' => 'Task to Trash',
            'description' => 'This task will be trashed.',
            'status' => 'To Do',
            'priority' => 'Medium',
            'due_date' => '2024-10-30',
        ]);

        // Soft delete the task
        $task->delete();

        Livewire::test(Tasks::class)
            ->call('restoreTask', $task->id);
        // Check if the task is back in the database
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'deleted_at' => null, // Ensure the task is restored
        ]);
    }
}
