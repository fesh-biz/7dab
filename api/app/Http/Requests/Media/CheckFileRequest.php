<?php

namespace App\Http\Requests\Media;

use App\Data\Media\CreateMediaData;
use App\Plugins\MimeDetector\MimeDetector;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CheckFileRequest extends FormRequest
{
    private string $mimeType;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $file = $this->file('file_chunk');

        if (!$file) {
            throw new \Exception('Chunk File is missing');
        }

        if ($file->getSize() > 100) {
            throw new \Exception('The given file chunk is too large');
        }

        $this->mimeType = $this->checkMimeType($file);

        $this->checkFileSize($this->mimeType, $this->size);

        return [
            'file_chunk' => 'required',
            'name' => ['required', 'string']
        ];
    }

    public function dto(): CreateMediaData
    {
        return new CreateMediaData(
            auth()->id(),
            $this->name,
            $this->mimeType
        );
    }

    private function checkFileSize(string $mimeType, int $size): bool
    {
        $Mb = 1024 * 1024;
        $maxImageSize = 5 * $Mb;
        $maxGifSize = 50 * $Mb;
        $maxVideoSize = 10 * $Mb;

        $isFileLarge = false;

        $maxAllowedSize = 0;
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/png':
            case 'image/webp':
                $maxAllowedSize = $maxImageSize;
                break;
            case 'image/gif':
                $maxAllowedSize = $maxGifSize;
                break;
            case 'video/mp4':
            case 'video/webm':
                $maxAllowedSize = $maxVideoSize;
                break;
            default:
                throw new \Exception("Unknown mime type: ${mimeType}");
        }

        if ($size > $maxAllowedSize) {
            $this->throwFileValidationException(trans('validation.file_is_too_large'));
        }

        ddh([
            'mime' => $mimeType,
            'size' => $size
        ]);
    }

    private function checkMimeType($file): string
    {
        $imageMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $videoMimeTypes = ['video/mp4', 'video/webm'];

        $mimeDetector = new MimeDetector();
        $mimeDetector->setFile($file);
        $mimeType = $mimeDetector->getMimeType();

        if (!in_array($mimeType, $imageMimeTypes) && !in_array($mimeType, $videoMimeTypes)) {
            $this->throwFileValidationException(trans('validation.wrong_file_type'));
        }

        return $mimeType;
    }

    private function throwFileValidationException(string $message)
    {
        throw ValidationException::withMessages(['file_chunk' => $message]);
    }
}
