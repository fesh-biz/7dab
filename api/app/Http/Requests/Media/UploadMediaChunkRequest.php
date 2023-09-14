<?php

namespace App\Http\Requests\Media;

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
            throw new \Exception('File Chunk is missing');
        }

        $mb = 1024 * 1024;
        if ($file->getSize() > 2 * $mb) {
            throw new \Exception('The given file chunk is too large');
        }

        if ($this->chunk_index < 1) {
            $this->mimeType = $this->getFileType($file);
            $this->checkMimeType($this->mimeType);
        }

        return [
            'chunk_index' => ['integer', 'required'],
            'total_chunks' => ['integer', 'required']
        ];
    }
}
