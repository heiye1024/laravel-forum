<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Link extends Model
{
    protected $fillable = ['title', 'link'];

    public $cache_key = 'laravelforum_links';
    protected $cache_expire_in_minutes = 1440;

    public function getAllCached()
    {
        // 嘗試從緩存中取出 cache_key 對應的數據。如果能取到，便直接返回數據
        // 否則使用匿名函數中的程式碼來取出 links 表中的所有數據，返回的同時做了緩存
        return Cache::remember($this->cache_key, $this->cache_expire_in_minutes, function(){
            return $this->all();
        });
    }
}
