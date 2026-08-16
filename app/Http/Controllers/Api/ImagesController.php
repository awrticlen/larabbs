<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ImageRequest;
use App\Http\Resources\ImageResource;
use App\Services\ImageStoreService;

class ImagesController extends Controller
{
    public function store(ImageRequest $request, ImageStoreService $imageStoreService)
    {
        $image = $imageStoreService->store(
            $request->file('image'),
            $request->user(),
            $request->input('type'),
        );

        return new ImageResource($image);
    }
}
