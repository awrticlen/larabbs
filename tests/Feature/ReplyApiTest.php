<?php

namespace Tests\Feature;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JPush\Client;
use JPush\Exceptions\APIRequestException;
use JPush\PushPayload;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use Tests\Traits\ActingJWTUser;

class ReplyApiTest extends TestCase
{
    use RefreshDatabase;
    use ActingJWTUser;

    public function test_creating_a_reply_succeeds_when_jpush_rejects_a_stale_registration_id(): void
    {
        $topicAuthor = User::factory()->create([
            'registration_id' => 'test_registration_id',
        ]);
        $replyAuthor = User::factory()->create();
        $topic = Topic::factory()->create([
            'user_id' => $topicAuthor->id,
            'category_id' => 1,
        ]);
        $payload = Mockery::mock(PushPayload::class);

        $payload->shouldReceive('setPlatform')->once()->with('all')->andReturnSelf();
        $payload->shouldReceive('addRegistrationId')->once()->with('test_registration_id')->andReturnSelf();
        $payload->shouldReceive('setNotificationAlert')->once()->withArgs(
            fn (string $content): bool => $content === 'Reply content'
        )->andReturnSelf();
        $payload->shouldReceive('send')->once()->andThrow(new APIRequestException([
            'http_code' => 400,
            'headers' => [],
            'body' => json_encode([
                'error' => [
                    'code' => 1003,
                    'message' => 'The registration_id test_registration_id is invalid!',
                ],
            ], JSON_THROW_ON_ERROR),
        ]));
        $this->mock(Client::class, function (MockInterface $client) use ($payload): void {
            $client->shouldReceive('push')->once()->andReturn($payload);
        });

        $response = $this->JWTActingAs($replyAuthor)->postJson(
            "/api/v1/topics/{$topic->id}/replies",
            ['content' => 'Reply content']
        );

        $response
            ->assertCreated()
            ->assertJsonPath('topic_id', $topic->id)
            ->assertJsonPath('user_id', $replyAuthor->id)
            ->assertJsonPath('content', '<p>Reply content</p>');
        $this->assertDatabaseHas('replies', [
            'topic_id' => $topic->id,
            'user_id' => $replyAuthor->id,
            'content' => '<p>Reply content</p>',
        ]);
        $this->assertNull($topicAuthor->fresh()->registration_id);
    }
}