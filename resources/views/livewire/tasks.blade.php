<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Task List</h1>

    {{-- Session Message --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    {{-- Form to Create or Edit Tasks --}}
    <div class="mb-4">
        <form wire:submit.prevent="{{ $taskId ? 'updateTask' : 'createTask' }}">
            <div class="mb-2">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" wire:model="title" class="border rounded p-2 w-full">
                @error('title') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mb-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" wire:model="description" class="border rounded p-2 w-full"></textarea>
                @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mb-2">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" wire:model="status" class="border rounded p-2 w-full">
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('status') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mb-2">
                <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                <select id="priority" wire:model="priority" class="border rounded p-2 w-full">
                    @foreach($priorityOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('priority') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mb-2">
                <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                <input type="date" id="due_date" wire:model="due_date" class="border rounded p-2 w-full">
                @error('due_date') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                {{ $taskId ? 'Update Task' : 'Create Task' }}
            </button>
        </form>
    </div>

    {{-- Task List --}}
    <div class="mt-4">
        @if ($tasks->isEmpty())
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                <p class="font-bold">No Tasks Available</p>
                <p>It looks like you haven't created any tasks yet.</p>
            </div>
        @else
            <table class="min-w-full bg-white">
                <thead>
                <tr>
                    <th class="px-4 py-2">Title</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Priority</th>
                    <th class="px-4 py-2">Due Date</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td class="border px-4 py-2">{{ $task->title }}</td>
                        <td class="border px-4 py-2">{{ $task->description }}</td>
                        <td class="border px-4 py-2">{{ $task->status }}</td>
                        <td class="border px-4 py-2">{{ $task->priority }}</td>
                        <td class="border px-4 py-2">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : 'N/A' }}</td>
                        <td class="border px-4 py-2">
                            <button wire:click="editTask({{ $task->id }})" class="bg-yellow-500 text-white px-4 py-2 rounded">Edit</button>
                            <button wire:click="deleteTask({{ $task->id }})" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Show Trashed Tasks Button --}}
    <div class="mt-4">
        <button wire:click="toggleTrashed" class="bg-gray-500 text-white px-4 py-2 rounded">
            {{ $showTrashed ? 'Hide' : 'Show' }} Trashed Tasks ({{ $trashedTasks->count() }})
        </button>

        @if ($showTrashed)
            <h2 class="text-xl font-bold mt-4">Trashed Tasks</h2>
            @if ($trashedTasks->isEmpty())
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                    <p class="font-bold">No Trashed Tasks Available</p>
                </div>
            @else
                <table class="min-w-full bg-white mt-2">
                    <thead>
                    <tr>
                        <th class="px-4 py-2">Title</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($trashedTasks as $trashedTask)
                        <tr>
                            <td class="border px-4 py-2">{{ $trashedTask->title }}</td>
                            <td class="border px-4 py-2">{{ $trashedTask->description }}</td>
                            <td class="border px-4 py-2">{{ $trashedTask->priority }}</td>
                            <td class="border px-4 py-2">{{ $trashedTask->due_date ? \Carbon\Carbon::parse($trashedTasks->due_date)->format('Y-m-d') : 'N/A' }}</td>
                            <td class="border px-4 py-2">
                                <button wire:click="restoreTask({{ $trashedTask->id }})" class="bg-green-500 text-white px-4 py-2 rounded">Restore</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        @endif
    </div>
</div>
