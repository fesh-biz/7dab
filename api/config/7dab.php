<?php

return [
    // Mails
    'no_reply_email' => env('APP_NO_REPLY_MAIL', 'noreply@app'),

    // Post image folders
    'post_image_storage_base_path' => storage_path('app/public/post-images'),
    'post_image_public_base_path' => 'storage/post-images',

    // Post images sizes
    'post_image_desktop_thumbnail_size' => 800,
    'post_image_mobile_thumbnail_size' => 400,
    
    // Hash
    'salt' => 'sTKw$@3d093sdlfkjSDFjlk21412'
];
