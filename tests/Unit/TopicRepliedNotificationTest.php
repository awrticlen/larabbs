<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Models\User;
use App\Notifications\Channels\JPushChannel;
use App\Notifications\TopicReplied;
use Tests\TestCase;

class TopicRepliedNotificationTest extends TestCase
{
    public function test_notification_skips_jpush_without_a_valid_registration_id(): void
    {
        $notification = new TopicReplied(new Reply());

        $missingRegistrationId = new User(['registration_id' => null]);
        $blankRegistrationId = new User(['registration_id' => '   ']);
        $validRegistrationId = new User(['registration_id' => ' registration-id ']);

        $this->assertSame(['database', 'mail'], $notification->via($missingRegistrationId));
        $this->assertSame(['database', 'mail'], $notification->via($blankRegistrationId));
        $this->assertSame(
            ['database', 'mail', JPushChannel::class],
            $notification->via($validRegistrationId)
        );
    }

    public function test_registration_id_normalization_accepts_only_non_empty_strings(): void
    {
        $this->assertNull(JPushChannel::registrationIdFor(new User(['registration_id' => null])));
        $this->assertNull(JPushChannel::registrationIdFor(new User(['registration_id' => 123])));
        $this->assertNull(JPushChannel::registrationIdFor(new User(['registration_id' => '  '])));
        $this->assertSame(
            'registration-id',
            JPushChannel::registrationIdFor(new User(['registration_id' => ' registration-id ']))
        );
    }
}