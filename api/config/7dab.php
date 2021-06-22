<?php

return [
    'no_reply_email' => env('APP_NO_REPLY_MAIL', 'noreply@app'),
    'post_images_folder' => storage_path('/app/public/post-images'),
    'post_original_images_folder' => storage_path('/app/public/post-images/original')
];
