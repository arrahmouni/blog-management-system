<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewCommentNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $comment;
    public $post;

    public function __construct($comment, $post)
    {
        $this->comment = $comment;
        $this->post    = $post;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Comment on Your Post')
            ->line('A new comment has been added to your post: ' . $this->post->title)
            ->action('View Comment', url('/posts/' . $this->post->id))
            ->line('Thank you for using our platform!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'comment_id'        => $this->comment->id,
            'post_id'           => $this->post->id,
            'post_title'        => $this->post->title,
            'comment_author'    => $this->comment->user->full_name,
            'message'           => 'New comment: ' . $this->comment->body,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'comment_id'        => $this->comment->id,
            'post_id'           => $this->post->id,
            'post_title'        => $this->post->title,
            'comment_author'    => $this->comment->user->full_name,
            'message'           => 'New comment: ' . substr($this->comment->body, 0, 50) . '...',
            'created_at'        => now()->toDateTimeString(),
        ]);
    }

    public function broadcastType()
    {
        return 'new-comment';
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->post->user_id);
    }
}
