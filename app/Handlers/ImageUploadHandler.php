<?php

namespace App\Handlers;

use Image;

class ImageUploadHandler
{
    // 只允許以下副檔名的圖片文件上傳
    protected $allowed_ext = ["png", "jpg", "gif", "jpeg"];

    public function save($file, $folder, $file_prefix, $max_width= false)
    {
        // 建立儲存的文件夾規則，例如：uploads/images/avatars/201901/01/
        // 文件夾切割能讓搜尋效率更高
        $folder_name = "uploads/images/$folder/" . date("Ym/d", time());

        // 文件具體儲存的物理路徑，`public_path()`取得的是`public`文件夾的物理路徑
        // 例如：/home/vagrant/Code/laravel-forum/public/uploads/images/201901/01/
        $upload_path = public_path() . '/' . $folder_name;

        // 取得文件的副檔名，因圖片從複製貼上時副檔名為空，所以此處確保副檔名一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 拼接文件名，加上前綴字增加辨識度，可以是相關數據模型的ID
        // 例如：1_1493521050_7BVc9v9ujP.png
        $filename = $file_prefix . '_' . time() . '_' . str_random(10) . '.' . $extension;

        // 如果上傳的不是圖片將終止操作
        if (! in_array($extension, $this->allowed_ext)) {
            return false;
        }

        // 將圖片移動到我們的目標儲存路徑中
        $file->move($upload_path, $filename);

        // 如果超過圖片寬度，就進行裁剪
        if ($max_width && $extension != 'git') {
            // 此class封裝的函數，用於裁剪圖片
            $this->reduceSize($upload_path . '/' . $filename, $max_width);
        }

        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];
    }

    public function reduceSize($file_path, $max_width)
    {
        // 先實例化，傳遞參數是文件的硬碟物理路徑
        $image = Image::make($file_path);

        // 進行大小調整的操作
        $image->resize($max_width, null, function($constraint) {
            // 設定寬度是 $max_width，高度等比例雙方縮放
            $constraint->aspectRatio();

            //防止裁圖時圖片尺寸變大
            $constraint->upsize();
        });

        // 對圖片修改後進行保存
        $image->save();
    }
}
