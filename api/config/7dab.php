<?php

return [
    // Mails
    'no_reply_email' => env('APP_NO_REPLY_MAIL', 'noreply@app'),

    // Post image folders
    'post_images_folder' => storage_path('/app/public/post-images'),
    'post_original_images_folder' => storage_path('/app/public/post-images/original'),
    'post_desktop_thumbnail_images_folder' => storage_path('/app/public/post-images/desktop-thumbnail'),
    'post_mobile_thumbnail_images_folder' => storage_path('/app/public/post-images/mobile-thumbnail'),

    // Post images sizes
    'post_image_desktop_thumbnail_size' => 800,
    'post_image_mobile_thumbnail_size' => 400,
];
