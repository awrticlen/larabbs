<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected $showSensitiveFields = false;

    protected $showEmail = false;

    public function toArray($request)
    {
        if (!$this->showSensitiveFields) {
            $hiddenFields = ['phone'];

            if (!$this->showEmail) {
                $hiddenFields[] = 'email';
            }

            $this->resource->makeHidden($hiddenFields);
        }

        $data = parent::toArray($request);

        $data['bound_phone'] = $this->resource->phone ? true : false;
        $data['bound_wechat'] = ($this->resource->weixin_unionid || $this->resource->weixin_openid) ? true : false;
        $data['roles'] = RoleResource::collection($this->whenloaded('roles'));

        return $data;
    }

    public function showEmail()
    {
        $this->showEmail = true;

        return $this;
    }

    public function showSensitiveFields()
    {
        $this->showSensitiveFields = true;

        return $this;
    }
}
