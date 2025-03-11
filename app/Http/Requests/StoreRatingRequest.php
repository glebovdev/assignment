<?php

namespace App\Http\Requests;

use App\Models\Rating;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class StoreRatingRequest extends FormRequest
{
    public function authorize()
    {
        if (app()->environment() === 'production') {
            Log::debug("request made for" . auth()->user()?->getAuthIdentifier());
        }
        return auth()->user()?->can('create', Rating::class);
    }

    public function rules(): array
    {
        return [
            'rating' => 'required',
            'comment' => 'required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        // Customize the JSON response:
        $response = response()->json([
            'success' => false,
            'message' => 'Validation failed. Please fix the errors below.',
            'errors'  => $errors,
        ], 422);

        throw new HttpResponseException($response);
    }
}
