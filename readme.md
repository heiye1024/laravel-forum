## 簡介

該專案使用Laravel建立論壇系統，功能將包含不同角色的使用者權限系統、管理員後台、註冊驗證碼、圖片上傳、圖片裁減、XSS防禦、自定義命令行、自定義中間件、任務調度、佇列系統的使用、應用緩存、Redis、模型事件監控、表單驗證、消息通知、郵件通知、模型修改器等。

## 管理程式開發項目進度
- https://github.com/webcodedemo/laravel-forum/projects

## 開發筆記
- https://github.com/webcodedemo/laravel-forum/wiki

## 使用情境

|角色|功能|
|-------------|-------------|
|訪客|沒有註冊的使用者|
|使用者|註冊使用者，但沒有多餘權限|
|管理員|輔助超級管理員做論壇的內容管理|
|站長|權限最高的使用者角色|

|結構|說明|
|-------------|-------------|
|使用者|Model: User 論壇為UGC產品，所有內容都圍繞使用者來進行|
|主題|Model: Topic 論壇應用的最核心資料|
|分類|Model: Category 主題的分類，每一個主題必須對應一個分類，分類由管理員建立|
|回覆|Model: Reply 針對某個主題的討論，一個主題可以有很多的回覆|

## 功能
- 使用者認證：註冊、登入、登出
- 個人中心：使用者個人中心、編輯資料
- 使用者授權：作者才能刪除自己的內容
- 上傳圖片：修改頭像和編輯主題時候上傳圖片
- 表單驗證：使用表單驗證
- 文章發佈時自動Slug翻譯，支持使用Queue方式以提高回應
- 站內活躍使用者計算，一小時計算一次
- 多角色權限管理：允許站長、管理員權限的存在
- 後台管理：後台數據模型管理
- 郵件通知：發送新回覆郵件通知，使用Queue發送郵件
- 站內通知：主題有新回覆時，會有通知
- 自定義Artisan命令：自定義活躍使用者計算命令
- 自定義Trait：活躍使用者的業務邏輯實作
- 自定義中間件：記錄使用者的最後登入時間
- XSS安全防禦

## 套件使用情況
|套件|功能描述|應用場景|
|-------------|-------------|-------------|
|Intervention/image|圖片處理功能|用於圖片裁切|
|guzzlehttp/guzzle|HTTP請求套件|請求百度翻譯API|
|predis/predis|Redis官方首推的PHP Client端開發套件|緩存驅動Redis基礎套件|
|barryvdh/laravel-debugbar|對PHP Debugbar的封裝|開發環境中的Debug|
|spatie/laravel-permission|角色權限管理|角色和權限控制|
|mewebstudio/Purifier|使用者提交的HTML白名單過濾|對發表內容的HTML安全過濾，防止XSS攻擊|
|hieu-le/active|選中狀態|頂部導航選單選中狀態|
|summerblue/administrator|管理後台|模型管理後台、配置資訊管理後台|
|viacreative/sudo-su|使用者切換|開發環境中快速切換登入帳號|
|laravel/horizon|Queue監控|Queue監控命令與頁面控制台/horizon|

## 自定義 Artisan 命令
|命令名稱|說明|Cron|
|-------------|-------------|-------------|
|laravalforum:calculate-active-user|生成活躍使用者|一小時運作一次|
|laravelforum:sync-user-actived-at|從Redis中同步最後登入時間到資料庫中|每天早上0點準時|

## Queue 清單
|名稱|說明|使用時機|
|-------------|-------------|-------------|
|TranslateSlug.php|將主題標題翻譯為Slug|TopicObserver 事件 saved()|
|TopicReplied.php|通知作者主題有新回覆|主題被回覆之後|
