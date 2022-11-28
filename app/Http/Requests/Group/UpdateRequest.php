<?php

namespace App\Http\Requests\Group;

use App\Models\Group;
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
        $except = $this->route('group')->name;

        return [
            'name' => "nullable|string|max:255|unique:groups,name,$except,name"
        ];
    }
}
