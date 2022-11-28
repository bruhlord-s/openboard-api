<?php

namespace App\Http\Requests\Workspace;

use App\Models\Workspace;
use Exception;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $except = $this->route('workspace')->name;

        return [
            'name' => "nullable|string|max:255|unique:workspaces,name,$except,name"
        ];
    }
}
