<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'sections' => [function ($attribute, $value, $fail) {
                $this->checkSections($attribute, $value, $fail);
            }]
        ];
    }

    private function checkSections($attribute, $sections, $fail): void
    {
        $errors = [];

        foreach ($sections as $section) {
            if ($section['type'] === 'text' && !$this->checkTextSection($section['content'])) {
                $errors['sections'][$section['order']] = trans('errors.can_not_be_empty');
            }

            if ($section['type'] === 'image') {
                $checkRes = $this->checkImageSection($section['content']);
                if ($checkRes) {
                    $errors['sections'][$section['order']] = $checkRes;
                }
            }
        }

        if (count($errors) > 0) {
            $validator = $this->getValidatorInstance();
            $validator->errors()->merge($errors);
        }
    }

    private function checkImageSection ($content):? string
    {
        $title = $content['title'] ?? null;
        if ($title && strlen($title) > 255) {
            return trans('errors.max_255_symbols');
        }
    }

    private function checkTextSection($content): bool
    {
        return !!$content;
    }
}
