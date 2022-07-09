<?php

namespace Armincms\Api\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Zareismail\Gutenberg\Gutenberg;

class AuthRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * Get the login field.
     *
     * @return string
     */
    public function credentialKey()
    {
        return $this->input('credential', 'name');
    }
}
