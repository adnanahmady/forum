<?php

namespace App\Http\Requests;

use App\Exceptions\ThrottleException;
use App\Reply;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class CreateReplyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', new Reply);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ['body' => 'required|min:1|spamfree'];
    }

    protected function failedAuthorization()
    {
        throw new ThrottleException(
            __('you reply too frequently, please wait for a minute'),
            429
        );
    }
}
