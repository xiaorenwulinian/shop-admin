<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleEditStoresRequest extends FormRequest
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
            'id'             => 'required|numeric',
            'type_id'        => 'required|numeric',
            'art_title'      => 'required|max:255',
//            'art_desc'   => 'required',
            'art_content'    => 'required'
        ];
    }
    public function messages()
    {
        return [
            'id.required'          => 'id必填！',
            'type_id.required'     => '文章分类必传',
            'art_title.id'         => '文章标题应必传！',
            'art_title.max'        => '文章标题应小于255个字！',
        ];
    }
}
