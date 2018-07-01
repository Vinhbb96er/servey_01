<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
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
            'content' => 'required',
            'name' => 'required|max:255',
            'email' => 'required|max:255',
        ];
    }

    public function messages()
    {
        return [
            'content.required' => trans('validation.required', [
                'attribute' => trans('lang.feedback_content'),
            ]),
            'name.required' => trans('validation.required', [
                'attribute' => trans('lang.name'),
            ]),
            'name.max' => trans('validation.max.string', [
                'attribute' => trans('lang.name'),
            ]),
            'email.required' => trans('validation.required', [
                'attribute' => trans('lang.email'),
            ]),
            'email.max' => trans('validation.max.string', [
                'attribute' => trans('lang.email'),
            ]),
        ];
    }
}
