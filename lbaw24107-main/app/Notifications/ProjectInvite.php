<?php
namespace App\Notifications;

use App\Models\Project;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;

class ProjectInvite extends Notification
{
    protected $project;
    protected $inviter;
    protected $notificationId;

    public function __construct(Project $project,$notificationId, $inviter)
    {
        $this->project = $project;
        $this->notificationId = $notificationId;
        $this->inviter = $inviter;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {   
        // Generate a URL to accept/decline the invitation
        $acceptUrl = URL::route('notifications.emailRespond', ['projectId' => $this->project->id,'notificationId' => $this->notificationId, 'response' => 'accept', 'token' => $notifiable->generateInviteToken()]);
        $declineUrl = URL::route('notifications.emailRespond', ['projectId' => $this->project->id,'notificationId'=> $this->notificationId, 'response' => 'decline', 'token' => $notifiable->generateInviteToken()]);


        return (new MailMessage)
        ->greeting("Hello, {$notifiable->name}")
        ->line("You have been invited to join the project {$this->project->name} by {$this->inviter->username}.")
        ->line("Accept the invitation by clicking [this link]({$acceptUrl}).")
        ->line("Decline the invitation by clicking [this link]({$declineUrl}).")
        ->line('If you did not expect this invitation, you can ignore this email.');

    }
}
