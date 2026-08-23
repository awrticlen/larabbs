<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use Tests\Traits\ActingJWTUser;

class NotificationApiTest extends TestCase
{
    use RefreshDatabase;
    use ActingJWTUser;

    public function test_authenticated_user_can_paginate_notifications(): void
    {
        $user = User::factory()->create();
        $user->forceFill(['notification_count' => 1])->save();
        $user->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => 'App\\Notifications\\TopicReplied',
            'data' => [
                'user_id' => 2,
                'user_name' => '回复用户',
                'user_avatar' => 'http://larabbs.test/images/avatar.png',
                'topic_id' => 3,
                'topic_title' => '测试话题',
                'reply_content' => '<p>新的回复</p>',
            ],
        ]);

        $response = $this->JWTActingAs($user)->getJson('/api/v1/notifications?page=1');

        $response
            ->assertOk()
            ->assertJsonStructure(['data', 'meta'])
            ->assertJsonPath('data.0.data.topic_id', 3)
            ->assertJsonPath('data.0.data.reply_content', '<p>新的回复</p>');
    }

    public function test_put_marks_notifications_as_read_and_resets_count(): void
    {
        $user = User::factory()->create();
        $user->forceFill(['notification_count' => 1])->save();
        $notification = $user->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => 'App\\Notifications\\TopicReplied',
            'data' => [],
        ]);

        $response = $this->JWTActingAs($user)->putJson('/api/v1/user/read/notifications');

        $response->assertNoContent();
        $this->assertSame(0, $user->fresh()->notification_count);
        $this->assertNotNull($notification->fresh()->read_at);
    }
}