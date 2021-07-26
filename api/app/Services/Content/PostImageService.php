<?php

namespace App\Services\Content;

use Intervention\Image\Facades\Image;
use Intervention\Image\Image as MaidImage;
use JetBrains\PhpStorm\ArrayShape;

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

    #[ArrayShape([
        'imageSizesKb' => 'array',
        'fileName' => 'string',
        'originalDimensions' => 'array'
    ])]
    public function saveImageFile(string $filePath): array
    {
        $image = Image::make($filePath);

        $imageExt = $image->extension;
        $fileName = $this->getFileName($imageExt);

        $imageData['imageSizesKb'] = [
            'original' => intval($image->filesize() / 1024),
            'desktop' => null,
            'mobile' => null
        ];
        $imageData['fileName'] = $fileName;
        $imageData['originalDimensions'] = [
            'width' => $image->getWidth(),
            'height' => $image->getHeight()
        ];

        $image->save($this->getFilePath($this->originalFolder, $fileName), 100);

        if ($this->maybeResizeImage($image, $this->desktopThumbWidth)) {
            $image->save($this->getFilePath($this->desktopThumbnailFolder, $fileName), 100);
            $imageData['imageSizesKb']['desktop'] = intval($image->filesize() / 1024);
        }

        if ($this->maybeResizeImage($image, $this->mobileThumbWidth)) {
            $image->save($this->getFilePath($this->mobileThumbnailFolder, $fileName), 100);
            $imageData['imageSizesKb']['mobile'] = intval($image->filesize() / 1024);
        }

        return $imageData;
    }

    private function maybeResizeImage(MaidImage $image, int $width): ?bool
    {
        if ($image->getWidth() > $width) {
            $thumb = $image;
            $thumb->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            return true;
        }

        return null;
    }

    private function getFileName(string $extension): string
    {
        return str_replace(['0.', ' '], ['', ''], microtime()) . '.' . $extension;
    }

    private function getFilePath(string $folder, string $fileName): string
    {
        $datePath = $this->getDateFolderPath();
        $dirName = "$folder/$datePath";

        if (!is_dir($dirName)) {
            mkdir($dirName, 755, true);
        }

        return "$dirName/$fileName";
    }

    private function getDateFolderPath(): string
    {
        $now = now();

        return "$now->year/$now->month/$now->day";
    }
}
