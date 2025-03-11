<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Task Assigned</title>
</head>
<body>
    <h1>Task Assigned: {{ $task->title }}</h1>
    <p>You have been assigned a new task.</p>
    <p><strong>Priority:</strong> {{ $task->priority }}</p>
    <p><strong>Status:</strong> {{ $task->status }}</p>
    <p><strong>Start Date:</strong> {{ $task->start_date->format('d M Y') }}</p>
    <p><strong>End Date:</strong> {{ $task->end_date->format('d M Y') }}</p>

    {{-- <p><a href="{{ route('tasks.show', $task->id) }}">View Task Details</a></p> --}}
</body>
</html>
