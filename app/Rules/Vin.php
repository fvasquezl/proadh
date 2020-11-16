<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Vin implements Rule
{
    /** @var string */
    private $message = 'cars.validation.invalidVIN';

    /** @var int */
    private $vinLength = 17;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $vin
     * @return bool
     */
    public function passes($attribute, $vin)
    {
        if ($this->invalidLength($vin)) {
            $this->message = 'cars.validation.invalidVINLength';
            return false;
        }

        if ($this->containsForbiddenCharacters($vin)) {
            $this->message = 'cars.validation.invalidVINForbiddenChars';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans($this->message);
    }

    private function invalidLength($vin):bool
    {
        return strlen($vin) !== $this->vinLength;
    }

    private function containsForbiddenCharacters($vin): bool
    {
        preg_match('/([OIQ])/', $vin, $invalidLetters);
        preg_match('/([^A-Z0-9])/', $vin, $invalidChars);

        return (count($invalidLetters) > 0) || count($invalidChars) > 0;
    }

}
