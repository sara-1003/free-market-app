<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name'=>['required','string'],
            'brand'=>['nullable','string'],
            'description'=>['required','string','max:255'],
            'condition'=>['required','string'],
            'img'=>['required','file','mimes:jpeg,png'],
            'price'=>['required','numeric','min:0'],
            'category_ids' => ['required', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
        ];
    }

    public function messages()
    {
        return[
            'name.required'=>'商品名を入力してください',
            'name.string'=>'商品名を文字列で入力してください',
            'description.required'=>'商品の説明を入力してください',
            'description.max'=>'255文字以下で入力してください',
            'condition.required'=>'商品の状態を選択してください',
            'img.required'=>'商品画像をアップロードしてください',
            'img.mimes'=>'商品画像はJPEGまたはPNG形式でアップロードしてください',
            'price.required'=>'販売価格を入力してください',
            'price.numeric'=>'販売価格を数値で入力してください',
            'price.min'=>'販売価格は０円以上で設定してください',
            'category_ids.required'=>'商品名のカテゴリを選択してください',
        ];
    }
}
