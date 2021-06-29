<?php

namespace App\Services\Content;

use Intervention\Image\Image;

class PostImageService
{
    protected string $originalFolder;
    protected string $desktopThumbnailFolder;
    protected string $mobileThumbnailFolder;

    protected int $desktopThumbWidth;
    protected int $mobileThumbWidth;

    public function __construct()
    {
        $this->originalFolder = config('7dab.post_original_images_folder');
        $this->desktopThumbnailFolder = config('7dab.post_desktop_thumbnail_images_folder');
        $this->mobileThumbnailFolder = config('7dab.post_mobile_thumbnail_images_folder');

        $this->desktopThumbWidth = config('7dab.post_image_desktop_thumbnail_size');
        $this->mobileThumbWidth = config('7dab.post_image_mobile_thumbnail_size');
    }

    public function saveImage(Image $image, string $filename)
    {
        $imageExt = $image->extension;

        //$originalImageSizeKb = intval($image->filesize() / 1024);

        //$image->save(config('7dab.post_original_images_folder') . '/' . $filename . '.' . $imageExt, 100);

        $desktopThumb = $this->maybeResizeImage($image, $this->desktopThumbWidth);
        dump($image->width());
        dd($desktopThumb->width());
    }

    private function maybeResizeImage(Image $image, int $width):? Image
    {
        if ($image->getWidth() > $width) {
            $thumb = $image;
            return $thumb->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        return null;
    }
}
