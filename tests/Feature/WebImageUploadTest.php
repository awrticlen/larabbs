<?php

namespace Tests\Feature;

use App\Handlers\ImageUploadHandler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mockery\MockInterface;
use Tests\TestCase;

class WebImageUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_upload_topic_image_for_editor(): void
    {
        $user = User::factory()->createOne();

        if (! $user instanceof User) {
            throw new \LogicException('用户工厂必须创建 User 模型');
        }

        $path = 'http://larabbs.test/uploads/images/topics/202608/test.png';

        $this->mock(ImageUploadHandler::class, function (MockInterface $mock) use ($path, $user): void {
            $mock->shouldReceive('save')
                ->once()
                ->withArgs(fn ($file, $folder, $userId, $maxWidth) => (
                    $file instanceof UploadedFile
                    && $folder === 'topics'
                    && $userId === $user->id
                    && $maxWidth === 1024
                ))
                ->andReturn(['path' => $path]);
        });

        $response = $this->actingAs($user)->post(route('images.store'), [
            'type' => 'topic',
            'image' => UploadedFile::fake()->image('topic.png'),
        ], [
            'Accept' => 'application/json',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('path', $path)
            ->assertJsonPath('file_path', $path)
            ->assertJsonPath('type', 'topic');

        $this->assertDatabaseHas('images', [
            'user_id' => $user->id,
            'type' => 'topic',
            'path' => $path,
        ]);
    }
}