<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\ValidUniqueModel;

class ValidUnique implements Rule
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function passes($attribute, $value)
    {
        $validUniqueModel = new ValidUniqueModel();
        return $validUniqueModel->Validate_Unique($this->data);
    }

    public function message()
    {
        return 'The :attribute is not unique.';
    }
}
