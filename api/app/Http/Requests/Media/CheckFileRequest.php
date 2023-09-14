<?php

namespace App\Http\Requests\Media;

use App\Data\Media\CreateMediaData;
use Illuminate\Foundation\Http\FormRequest;

class CheckFileRequest extends FormRequest
{
    use ChecksTrait;

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

        $this->mimeType = $this->getFileType($file);
        $this->checkMimeType($this->mimeType);

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
            $this->mimeType,
            $this->size
        );
    }

    private function checkFileSize(string $mimeType, int $size): void
    {
        $Mb = 1024 * 1024;
        $maxImageSize = 5 * $Mb;
        $maxGifSize = 50 * $Mb;
        $maxVideoSize = 100 * $Mb;

        $maxAllowedSize = match ($mimeType) {
            'image/jpeg', 'image/png', 'image/webp' => $maxImageSize,
            'image/gif' => $maxGifSize,
            'video/mp4', 'video/webm' => $maxVideoSize,
            default => throw new \Exception("Unknown mime type: ${mimeType}"),
        };

        if ($size > $maxAllowedSize) {
            $this->throwFileValidationException(trans('validation.file_is_too_large'));
        }
    }
}
