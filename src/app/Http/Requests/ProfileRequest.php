<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'profile_img'=>['nullable','mimes:jpeg,png'],
            'name'=>['required','string','max:20'],
            'post_code'=>['required','string','regex:/^\d{3}-\d{4}$/'],
            'address'=>['required','string'],
        ];
    }

    public function messages()
    {
        return[
            'profile_img.mimes' => 'プロフィール画像はJPEGまたはPNG形式でアップロードしてください',
            'name.required'=>'名前を入力してください',
            'name.string'=>'名前を文字列で入力してください',
            'name.max'=>'名前を20文字以下で入力してください',
            'post_code.required'=> '郵便番号を入力してください',
            'post_code.regex'=> '郵便番号は「123-4567」の形式で入力してください',
            'address.required'  => '住所を入力してください',
        ];
    }
}
