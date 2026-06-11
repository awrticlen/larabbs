<?php

namespace App\Http\Requests;

class TopicRequest extends Request
{
    public function rules(): array
    {
        switch ($this->method()) {
            case 'POST':
                return [];
            case 'PUT':
            case 'PATCH':
                return [];
            default:
                return [];
        }
    }
}
