<?php

namespace App\Services;

use App\Handlers\ImageUploadHandler;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class ImageStoreService
{
    public function __construct(private ImageUploadHandler $uploader)
    {
    }

    public function store(UploadedFile $file, User $user, string $type): Image
    {
        $maxWidth = $type === 'avatar' ? 416 : 1024;
        $result = $this->uploader->save($file, Str::plural($type), $user->id, $maxWidth);

        if (! $result) {
            throw ValidationException::withMessages([
                'image' => ['图片上传失败，请重试'],
            ]);
        }

        $image = new Image();
        $image->path = $result['path'];
        $image->type = $type;
        $image->user_id = $user->id;
        $image->save();

        return $image;
    }
}