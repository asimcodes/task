<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Task List</h1>

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
                    <th class="px-4 py-2">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td class="border px-4 py-2">{{ $task->title }}</td>
                        <td class="border px-4 py-2">{{ $task->description }}</td>
                        <td class="border px-4 py-2">{{ $task->status }}</td>
                        <td class="border px-4 py-2">
                            <button wire:click="editTask({{ $task->id }})" class="bg-yellow-500 text-white px-4 py-2 rounded">Edit</button>
                            <button wire:click="deleteTask({{ $task->id }})" class="bg-red-500 text-white px-4 py-2 rounded" onclick="return confirm('Are you sure?')">Delete</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
