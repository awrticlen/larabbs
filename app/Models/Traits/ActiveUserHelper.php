<?php

namespace App\Models\Traits;

use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

trait ActiveUserHelper
{
    // 用于存放临时用户数据
    protected $users = [];

    // 配置信息
    protected $topic_weight = 4; // 话题权重
    protected $reply_weight = 1; // 回复权重
    protected $pass_days = 7;    // 多少天内发表过内容
    protected $user_number = 6; // 取出来多少用户

    // 缓存相关配置
    protected $cache_key = 'larabbs_active_users';
    protected int $cache_expire_in_seconds = 65 * 60;

    public function getActiveUsers()
    {
        $userIds = Cache::get($this->cache_key);

        if (! is_array($userIds)) {
            $userIds = $this->calculateActiveUserIds();
            $this->cacheActiveUserIds($userIds);
        }

        return User::query()
            ->whereKey($userIds)
            ->get()
            ->sortBy(fn (User $user): int => array_search($user->getKey(), $userIds, true))
            ->values();
    }

    public function calculateAndCacheActiveUsers()
    {
        $this->cacheActiveUserIds($this->calculateActiveUserIds());
    }

    private function calculateActiveUserIds(): array
    {
        $this->users = [];
        $this->calculateTopicScore();
        $this->calculateReplyScore();

        $users = Arr::sort($this->users, fn (array $user): int => $user['score']);
        $users = array_reverse($users, true);

        return array_map('intval', array_keys(array_slice($users, 0, $this->user_number, true)));
    }

    private function calculateTopicScore()
    {
        // 从话题数据表里取出限定时间范围（$pass_days）内，有发表过话题的用户
        // 并且同时取出用户此段时间内发布话题的数量
        $topic_users = Topic::query()->select(DB::raw('user_id, count(*) as topic_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();
        // 根据话题数量计算得分
        foreach ($topic_users as $value) {
            $this->users[$value->user_id]['score'] = $value->topic_count * $this->topic_weight;
        }
    }

    private function calculateReplyScore()
    {
        // 从回复数据表里取出限定时间范围（$pass_days）内，有发表过回复的用户
        // 并且同时取出用户此段时间内发布回复的数量
        $reply_users = Reply::query()->select(DB::raw('user_id, count(*) as reply_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();
        // 根据回复数量计算得分
        foreach ($reply_users as $value) {
            $reply_score = $value->reply_count * $this->reply_weight;
            if (isset($this->users[$value->user_id])) {
                $this->users[$value->user_id]['score'] += $reply_score;
            } else {
                $this->users[$value->user_id]['score'] = $reply_score;
            }
        }
    }

    private function cacheActiveUserIds(array $userIds): void
    {
        Cache::put($this->cache_key, $userIds, $this->cache_expire_in_seconds);
    }
}
