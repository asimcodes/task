<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Task List</h1>

    {{-- Session Message --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    {{-- Editing Alert --}}
    @if ($isEditing)
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Editing Mode!</strong>
            <span class="block sm:inline">You are currently editing a task.</span>
        </div>
    @endif

    {{-- Form to Create or Edit Tasks --}}
    <div class="mb-4">
        <form wire:submit.prevent="{{ $taskId ? 'updateTask' : 'createTask' }}">
            <div>
                <label for="title" class="block">Title</label>
                <input type="text" wire:model="title" id="title" class="border rounded p-2 w-full" />
                @error('title') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="description" class="block">Description</label>
                <textarea wire:model="description" id="description" class="border rounded p-2 w-full"></textarea>
                @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="status" class="block">Status</label>
                <select wire:model="status" id="status" class="border rounded p-2 w-full">
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('status') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="priority" class="block">Priority</label>
                <select wire:model="priority" id="priority" class="border rounded p-2 w-full">
                    @foreach($priorityOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('priority') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="due_date" class="block">Due Date</label>
                <input type="date" wire:model="due_date" id="due_date" class="border rounded p-2 w-full" />
                @error('due_date') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
                {{ $taskId ? 'Update Task' : 'Create Task' }}
            </button>
            @if ($isEditing)
                <button type="button" wire:click="cancelEdit" class="mt-4 bg-red-500 text-white px-4 py-2 rounded">
                    Cancel Edit
                </button>
            @endif
        </form>
    </div>

    {{-- Tasks List --}}
    <div>
        <h2 class="text-xl font-semibold">Tasks ({{ $normalTaskCount }})</h2>
        @if ($tasks->isEmpty())
            <p>No tasks available.</p>
        @else
            <table class="min-w-full border">
                <thead>
                <tr>
                    <th class="border px-4 py-2">Title</th>
                    <th class="border px-4 py-2">Description</th>
                    <th class="border px-4 py-2">Status</th>
                    <th class="border px-4 py-2">Priority</th>
                    <th class="border px-4 py-2">Due Date</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td class="border px-4 py-2">{{ $task->title }}</td>
                        <td class="border px-4 py-2">{{ $task->description }}</td>
                        <td class="border px-4 py-2">{{ $task->status }}</td>
                        <td class="border px-4 py-2">{{ $task->priority }}</td>
                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($task->due_date)->format('d-m-Y') }}</td>
                        <td class="border px-4 py-2">
                            <button wire:click="editTask({{ $task->id }})" class="bg-blue-600 text-white px-2 py-1 rounded">Edit</button>
                            <button wire:click="deleteTask({{ $task->id }})" class="bg-red-600 text-white px-2 py-1 rounded">Delete</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Trashed Tasks --}}
    @if ($showTrashed)
        <div class="mt-4">
            <h2 class="text-xl font-semibold">Trashed Tasks</h2>
            @if ($trashedTasks->isEmpty())
                <p>No trashed tasks available.</p>
            @else
                <table class="min-w-full border">
                    <thead>
                    <tr>
                        <th class="border px-4 py-2">Title</th>
                        <th class="border px-4 py-2">Description</th>
                        <th class="border px-4 py-2">Status</th>
                        <th class="border px-4 py-2">Priority</th>
                        <th class="border px-4 py-2">Due Date</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($trashedTasks as $trashedTask)
                        <tr class="bg-gray-200">
                            <td class="border px-4 py-2">{{ $trashedTask->title }}</td>
                            <td class="border px-4 py-2">{{ $trashedTask->description }}</td>
                            <td class="border px-4 py-2">{{ $trashedTask->status }}</td>
                            <td class="border px-4 py-2">{{ $trashedTask->priority }}</td>
                            <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($trashedTask->due_date)->format('d-m-Y') }}</td>
                            <td class="border px-4 py-2">
                                <button wire:click="restoreTask({{ $trashedTask->id }})" class="bg-green-600 text-white px-2 py-1 rounded">Restore</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif

    <button wire:click="toggleTrashed" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded">
        {{ $showTrashed ? 'Hide Trashed Tasks (Count: ' . $trashedTaskCount . ')' : 'Show Trashed Tasks (Count: ' . $trashedTaskCount . ')' }}
    </button>
</div>
