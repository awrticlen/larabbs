<?php

namespace App\Http\Requests;

class ReplyRequest extends Request
{
    public function rules()
    {
        return [
            'content'  => 'required|min:2',
            'topic_id' => 'required|exists:topics,id',
        ];
    }

    public function messages()
    {
        return [
            'content.required' => '评论内容不能为空',
            'content.min'      => '评论内容必须至少两个字符',
            'topic_id.required' => '话题不存在',
            'topic_id.exists'   => '话题不存在',
        ];
    }
}
