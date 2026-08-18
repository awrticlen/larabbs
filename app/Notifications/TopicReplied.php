<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Reply;
use App\Models\User;
use JPush\PushPayload;
use App\Notifications\Channels\JPushChannel;

class TopicReplied extends Notification implements ShouldQueue
{
    use Queueable;

    public Reply $reply;

    public function __construct(Reply $reply)
    {
        // 注入回复实体，方便 toDatabase 方法中的使用
        $this->reply = $reply;
    }

    public function via(User $notifiable): array
    {
        $channels = ['database', 'mail'];

        if (JPushChannel::registrationIdFor($notifiable) !== null) {
            $channels[] = JPushChannel::class;
        }

        return $channels;
    }

    public function toJPush(User $notifiable, PushPayload $payload): PushPayload
    {
        $registrationId = JPushChannel::registrationIdFor($notifiable);

        if ($registrationId === null) {
            return $payload;
        }

        return $payload
            ->setPlatform('all')
            ->addRegistrationId($registrationId)
            ->setNotificationAlert(strip_tags($this->reply->content));
    }

    public function toDatabase(User $_notifiable): array
    {
        $topic = $this->reply->topic;
        $link =  $topic->link(['#reply' . $this->reply->id]);

        // 存入数据库里的数据
        return [
            'reply_id' => $this->reply->id,
            'reply_content' => $this->reply->content,
            'user_id' => $this->reply->user->id,
            'user_name' => $this->reply->user->name,
            'user_avatar' => $this->reply->user->avatar,
            'topic_link' => $link,
            'topic_id' => $topic->id,
            'topic_title' => $topic->title,
        ];
    }
    public function toMail(User $_notifiable): MailMessage
    {
        $url = $this->reply->topic->link(['#reply' . $this->reply->id]);

        return (new MailMessage)
            ->line('你的话题有新回复！')
            ->action('查看回复', $url);
    }
}
