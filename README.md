# Task Management System

## Project Overview
This is a simple web-based task management system built using Laravel. The system allows users to create, view, update, and delete tasks. It utilizes TailwindCSS for styling and Laravel Livewire for interactivity, communicating with a MySQL database to store and retrieve task information.

## Features
- **Task CRUD Operations**: Create, read, update, and delete tasks with a title, description, and status (To Do, In Progress, Done).
- **Task Listing Page**: A clean and organized page to display a list of tasks using TailwindCSS for styling.
- **Livewire Interactivity**: Users can interact with the task management system seamlessly, marking tasks as complete without a full-page refresh.
- **Database Integration**: MySQL is used to store task data, with necessary tables designed for task management.
- **Validation**: All required fields are validated for task creation and updates.
- **Testing**: Includes at least one unit test and one Livewire test to showcase critical functionality.
- **Scheduled Job**: A scheduled job that deletes tasks marked as deleted and older than 30 days.

## Requirements
- PHP >= 7.3
- Laravel >= 8
- MySQL
- Composer
- Node.js and npm (for asset management with TailwindCSS)

## Installation
Follow these steps to set up the project locally:

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/yourusername/task-management-system.git
   cd task-management-system
2. **Install Dependencies:**:
     ```bash
    composer install
    npm install
    npm run dev

3. **Set Up Environment:**:
Copy the .env.example file to .env:
     ```bash
    cp .env.example .env
    php artisan key:generate
    php artisan migrate
    php artisan serve

## Testing
**To run the tests for the application, use the following command:**:


    php artisan test
## Bonus Features
Scheduled Deletion of Old Tasks: This feature will automatically delete tasks that have been marked as deleted and are older than 30 days. This is done through a scheduled job running daily (example idea)
