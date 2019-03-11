<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['roles'];

    // 只要給transform方法傳入一個模型實例，然後返回一個數據即可，這個陣列就是返回給Client的回應數據
    // 這個UserTransform是可以重複使用的，當前使用者訊息、發佈主題使用者訊息、主題回覆使用者訊息都可使用這一個transform
    // 這樣所有有關使用者的資源都會返回相同的訊息，但是像是使用者手機、微信的union_id等敏感訊息，可以使用另外的欄位返回
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'introduction' => $user->introduction,
            // 是否綁定手機
            'bound_phone' => $user->phone ? true : false,
            // 是否綁定微信
            'bound_wechat' => ($user->weixin_unionid || $user->weixin_openid) ? true : false,
            'last_actived_at' => $user->last_actived_at->toDateTimeString(),
            'created_at' => $user->created_at->toDateTimeString(),
            'updated_at' => $user->updated_at->toDateTimeString(),
        ];
    }

    // 使用者與角色的關係是一對多的，透過$this->collection返回使用者權限
    public function includeRoles(User $user)
    {
        return $this->collection($user->roles, new RoleTransformer());
    }
}
