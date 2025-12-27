<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateMessageRequest extends FormRequest
{
    public function authorize()
    {
        // additional authorization handled in controller
        return Auth::check();
    }

    public function rules()
    {
        return [
            'body' => ['required', 'string', 'max:1000'],
            'attachment' => ['nullable', 'file', 'max:10240', 'mimes:jpg,jpeg,png,gif,pdf,doc,docx,zip'],
        ];
    }
}
