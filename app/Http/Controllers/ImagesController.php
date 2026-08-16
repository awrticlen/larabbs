<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\ImageRequest;
use App\Services\ImageStoreService;
use Illuminate\Http\JsonResponse;

class ImagesController extends Controller
{
    public function store(ImageRequest $request, ImageStoreService $imageStoreService): JsonResponse
    {
        $image = $imageStoreService->store(
            $request->file('image'),
            $request->user(),
            $request->input('type'),
        );

        return response()->json([
            'id' => $image->id,
            'path' => $image->path,
            'file_path' => $image->path,
            'type' => $image->type,
        ]);
    }
}