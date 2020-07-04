<?php

namespace App\Rules;

use App\Custom\Exceptions\SpamException;
use App\Custom\Spam;
use Illuminate\Contracts\Validation\Rule;

class SpamFree implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            return ! resolve(Spam::class)->detect($value);
        } catch (SpamException $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'the :attribute contains spam.';
    }
}
