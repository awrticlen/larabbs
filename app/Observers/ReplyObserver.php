<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

class ReplyObserver
{
    /**
     * 回复创建成功后调用
     */
    public function created(Reply $reply): void
    {
        $reply->topic->updateReplyCount();
        // 通知话题作者有新的评论
        $reply->topic->user->notify(new TopicReplied($reply));
    }
    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content, 'user_topic_body');
    }
    public function deleted(Reply $reply)
    {
        $reply->topic->updateReplyCount();
    }
}
