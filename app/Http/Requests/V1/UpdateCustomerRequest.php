<?php

namespace App\Http\Requests\V1;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user != null && $user->tokenCan('update');
    }

    public function rules(): array
    {
        $method = $this->method();
        if($method == 'PUT'){
            return [
                'name' => ['required'],
                'type' => ['required',Rule::in(['I','B','i','b'])],
                'email' => ['required','email',Rule::unique('customers')->ignore($this->id, 'id')],
                'address' => ['required'],
                'city' => ['required'],
                'state' => ['required'],
                'postalCode' => ['required'],
            ];
        }else{
            return [
                'name' => ['sometimes','required'],
                'type' => ['sometimes','required',Rule::in(['I','B','i','b'])],
                'email' => ['sometimes','required','email','unique:customers,email,except,id'],
                'address' => ['sometimes','required'],
                'city' => ['sometimes','required'],
                'state' => ['sometimes','required'],
                'postalCode' => ['sometimes','required'],
            ];
        }
    }
    protected function prepareForValidation(){
        if($this->postalCode){
            $this->merge([
                'postal_code' => $this->postalCode,
            ]);
        }
    }
}