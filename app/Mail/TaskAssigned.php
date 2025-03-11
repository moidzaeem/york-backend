<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskAssigned extends Mailable
{
    use SerializesModels;

    public $task;
    
    /**
     * Create a new message instance.
     *
     * @param Task $task
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("New Task Assigned: " . $this->task->title)
                    ->view('emails.tasks.assigned'); // Use a blade view for the email content
    }
}
