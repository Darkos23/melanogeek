<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'username'   => ['required', 'string', 'min:3', 'max:30', 'regex:/^[a-zA-Z0-9_]+$/',
                              Rule::unique('users')->ignore($this->user()->id)],
            'email'            => ['required', 'string', 'email', 'max:255',
                                    Rule::unique('users')->ignore($this->user()->id)],
            'bio'        => ['nullable', 'string', 'max:200'],
            'location'   => ['nullable', 'string', 'max:100'],
            'website'    => ['nullable', 'url', 'max:255'],
            'instagram'  => ['nullable', 'string', 'max:60'],
            'tiktok'     => ['nullable', 'string', 'max:60'],
            'youtube'    => ['nullable', 'string', 'max:60'],
            'twitter'    => ['nullable', 'string', 'max:60'],
            'avatar'     => ['nullable', 'image', 'max:2048'],
            'cover_photo'=> ['nullable', 'image', 'max:5120'],
            'is_private' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.unique'      => 'Ce pseudo est déjà pris.',
            'username.regex'       => 'Le pseudo ne peut contenir que des lettres, chiffres et underscores.',
            'email.required'       => 'L\'adresse email est obligatoire.',
            'email.unique'         => 'Cette adresse email est déjà utilisée.',
            'website.url'          => 'L\'URL doit commencer par https://',
            'avatar.max'           => 'La photo de profil ne doit pas dépasser 2 Mo.',
            'cover_photo.max'      => 'La photo de couverture ne doit pas dépasser 5 Mo.',
        ];
    }
}
