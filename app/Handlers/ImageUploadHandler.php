<?php

namespace App\Handlers;

use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImageUploadHandler
{
    // 只允许以下后缀名的图片文件上传
    protected $allowed_ext = ["png", "jpg", "gif", 'jpeg'];

    public function save($file, $folder, $file_prefix, $max_width = false)
    {
        // 构建存储的文件夹规则，值如：uploads/images/avatars/201709/21/
        // 文件夹切割能让查找效率更高。
        $folder_name = "uploads/images/$folder/" . date("Ym/d", time());

        // 文件具体存储的物理路径，`public_path()` 获取的是 `public` 文件夹的物理路径。
        // 值如：/home/vagrant/Code/larabbs/public/uploads/images/avatars/201709/21/
        $upload_path = public_path() . '/' . $folder_name;

        // 获取文件的后缀名，因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 拼接文件名，加前缀是为了增加辨析度，前缀可以是相关数据模型的 ID
        // 值如：1_1493521050_7BVc9v9ujP.png
        $filename = $file_prefix . '_' . time() . '_' . Str::random(10) . '.' . $extension;

        // 如果上传的不是图片将终止操作
        if (! in_array($extension, $this->allowed_ext)) {
            return false;
        }

        if (! is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $filepath = $upload_path . '/' . $filename;

        // 如果限制了图片宽度，先读取临时文件再保存；否则直接 move
        if ($max_width && $extension != 'gif') {
            $this->reduceSize($file, $filepath, $max_width);
        } else {
            $file->move($upload_path, $filename);
        }

        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];
    }

    public function reduceSize($file, $filepath, $max_width)
    {
        $image = Image::decode($file);
        $image->scaleDown(width: $max_width);
        $image->save($filepath);
    }
}
