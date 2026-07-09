<?php

namespace App\Filament\Resources\Topics\Pages;

use App\Filament\Resources\Topics\TopicResource;
use App\Models\Topic;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTopic extends CreateRecord
{
    protected static string $resource = TopicResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $topic = new Topic();
        $topic->forceFill($data)->save();

        return $topic;
    }
}