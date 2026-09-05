<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MessageCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var Conversation $conversation */
        $conversation = $this->route('conversation');

        return $this->user()->can('reply', $conversation);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => ['nullable', 'string', 'max:5000', 'required_without:attachments'],
            'attachments' => ['sometimes', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,pdf,mp4,webm,mov', 'max:10240'],
        ];
    }
}
