<?php

namespace App\Services\Content;

use App\Models\Content\PostImage;
use App\Repository\Content\PostImageRepository;
use File;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Intervention\Image\Image as MaidImage;

class PostImageService
{
    protected PostImageRepository $repo;

    protected string $storageBasePath;
    protected string $publicBasePath;

    protected int $desktopThumbWidth;
    protected int $mobileThumbWidth;

    protected array $imageAttributes;

    protected array $imageTypesToExtension = [
        'image/jpg' => 'jpg',
        'image/jpeg' => 'jpeg',
        'image/png' => 'png'
    ];

    public function __construct(PostImageRepository $repo)
    {
        $this->repo = $repo;

        $this->storageBasePath = config('7dab.post_image_storage_base_path');
        $this->publicBasePath = config('7dab.post_image_public_base_path');

        $this->desktopThumbWidth = config('7dab.post_image_desktop_thumbnail_size');
        $this->mobileThumbWidth = config('7dab.post_image_mobile_thumbnail_size');
    }

    public function getModel(): PostImage
    {
        return $this->repo->getModel();
    }

    public function create(int $postId, int $order, array $content): PostImage
    {
        $this->imageAttributes['url'] = $content['url'];
        $this->imageAttributes['title'] = $content['title'];

        /** @var UploadedFile $file */
        $file = $content['file'];

        $this->imageAttributes['original_filename'] = $file->getClientOriginalName();

        $this->saveImageFile($file->getRealPath());

        return $this->repo->create($postId, $order, $this->imageAttributes);
    }

    public function update(int|PostImage $postImage, array $data): PostImage
    {
        if (is_int($postImage)) {
            $postImage = $this->repo->getModel()->findOrFail($postImage);
        }

        if (!in_array('file', $data)) {
            return $this->repo->update($postImage->id, $data);
        }
    }

    public function saveImageFile(string $filePath): array
    {
        $image = Image::make($filePath);
        $imageQuality = $this->getImageQuality($image);

        $extension = $this->imageTypesToExtension[$image->mime] ?? null;
        if (!$extension) {
            throw new \Exception('Wrong image file type');
        }

        $fileName = $this->getFileName($extension);

        $filePath = $this->getImageFolderPath() . '/' . $fileName;
        $image->save($filePath, $imageQuality);

        $this->imageAttributes['data'] = [
            'original' => [
                'size' => intval($image->filesize() / 1024),
                'width' => $image->getWidth(),
                'height' => $image->getHeight()
            ]
        ];

        $this->imageAttributes['original_file_path'] = $this->getPublicPath($filePath);

        $this->maybeResizeAndSaveResizedImage($image, $fileName, $imageQuality);

        $this->maybeResizeAndSaveResizedImage($image, $fileName, $imageQuality, 'mobile');

        return $this->imageAttributes;
    }

    public function delete(int|PostImage $postImage): bool
    {
        if (is_int($postImage)) {
            $postImage = $this->repo->getModel()->findOrFail($postImage);
        }

        $this->deleteFiles($postImage);

        return $postImage->delete();
    }

    public function deleteFiles(int|PostImage $postImage): void
    {
        if (is_int($postImage)) {
            $postImage = $this->repo->getModel()->findOrFail($postImage);
        }

        $originalFilePath = $this->storageBasePath . '/' . str_replace('storage/post-images/', '', $postImage->original_file_path);
        $desktopFilePath = $this->storageBasePath . '/' . str_replace('storage/post-images/', '', $postImage->desktop_file_path);
        $mobileFilePath = $this->storageBasePath . '/' . str_replace('storage/post-images/', '', $postImage->mobile_file_path);

        File::delete([$originalFilePath, $desktopFilePath, $mobileFilePath]);
    }

    private function maybeResizeAndSaveResizedImage(
        MaidImage $image,
        string    $fileName,
        int       $imageQuality,
        string    $imageType = 'desktop'
    ): void
    {
        $imageWidth = $imageType === 'desktop' ? $this->desktopThumbWidth : $this->mobileThumbWidth;

        if ($this->maybeResizeImage($image, $imageWidth)) {
            $filePath = $this->getImageFolderPath($imageType) . '/' . $fileName;

            $image->save($filePath, $imageQuality);

            $this->imageAttributes[$imageType . '_file_path'] = $this->getPublicPath($filePath);
            $this->imageAttributes['data'][$imageType] = [
                'size' => intval($image->filesize() / 1024),
                'width' => $image->getWidth(),
                'height' => $image->getHeight()
            ];
        }
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
        list($milliseconds, $seconds) = explode(' ', microtime());
        $milliseconds = str_replace('0.', '', $milliseconds);

        return $seconds . $milliseconds . '.' . $extension;
    }

    private function maybeCreateFolder(string $imageFolderPath): void
    {
        if (!is_dir($imageFolderPath)) {
            mkdir($imageFolderPath, 755, true);
        }
    }

    public function getImageFolderPath(string $imageType = 'original'): string
    {
        $dateFolderPath = $this->getDateFolderPath();

        $dateFolderPath = $this->storageBasePath . '/' . $dateFolderPath . '/' . $imageType;
        $this->maybeCreateFolder($dateFolderPath);

        return $dateFolderPath;
    }

    private function getDateFolderPath(): string
    {
        return now()->format('Y/m/d');
    }

    private function getImageQuality(MaidImage $image): int
    {
        $quality = 101 - (($image->getWidth() * $image->getHeight()) * 3) / intval($image->filesize());

        return round($quality);
    }

    private function getPublicPath(string $storageFilePath): string
    {
        return $this->publicBasePath . explode($this->storageBasePath, $storageFilePath)[1];
    }
}
