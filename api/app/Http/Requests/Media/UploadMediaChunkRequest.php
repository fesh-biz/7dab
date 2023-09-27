<?php

namespace App\Http\Requests\Media;

use App\Data\Media\UploadMediaChunkData;
use App\Services\Media\MediaException;
use Illuminate\Foundation\Http\FormRequest;

class UploadMediaChunkRequest extends FormRequest
{
    use ChecksTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $file = $this->file('file_chunk');

        if (!$file) {
            throw new MediaException('File Chunk is missing');
        }

        $mb = 1024 * 1024;
        $maxAllowedMb = getUploadMaxFilesize();
        if ($file->getSize() > $maxAllowedMb * $mb) {
            throw new MediaException('The given file chunk is too large');
        }

        if ($this->chunk_index < 1) {
            $this->mimeType = $this->getFileType($file);
            $this->checkMimeType($this->mimeType);
        }

        return [
            'media_id' => ['integer', 'required'],
            'chunk_index' => ['integer', 'required'],
            'total_chunks' => ['integer', 'required']
        ];
    }

    public function dto(): UploadMediaChunkData
    {
        return UploadMediaChunkData::from($this);
    }
}
