<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    
    public function rules(): array
    {
        $rules = [
            'body' => 'required|string'
        ];
        
        if ($this->route()->getName() === 'content.comments.create') {
            $rules = array_merge($rules, [
                'commentable_type' => 'required|in:post,comment',
                'commentable_id' => 'required|integer',
                'post_id' => 'required|integer'
            ]);
        }
        
        return $rules;
    }
}
