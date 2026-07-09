<?php

namespace App\Filament\Resources\Replies\Pages;

use App\Filament\Resources\Replies\ReplyResource;
use App\Models\Reply;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateReply extends CreateRecord
{
    protected static string $resource = ReplyResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $reply = new Reply();
        $reply->forceFill($data)->save();

        return $reply;
    }
}