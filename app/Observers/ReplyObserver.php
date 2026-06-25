<?php

namespace App\Observers;

use App\Models\Reply;

class ReplyObserver
{
    /**
     * 回复创建成功后调用
     */
    public function created(Reply $reply): void
    {
        $reply->topic->reply_count = $reply->topic->replies->count();
        $reply->topic->save();
    }
    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content, 'user_topic_body');
    }
}
