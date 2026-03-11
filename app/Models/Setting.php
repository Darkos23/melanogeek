<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting.{$key}", 300, function () use ($key, $default) {
            return static::where('key', $key)->value('value') ?? $default;
        });
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting.{$key}");
    }

    /**
     * Retourne la liste des niches sous forme de tableau [{emoji, label}].
     * Utilisé partout : ticker homepage, grille profil, select inscription.
     */
    public static function getNiches(): array
    {
        $default = [
            ['emoji' => '🎵', 'label' => 'Musique'],
            ['emoji' => '📸', 'label' => 'Photographie'],
            ['emoji' => '👗', 'label' => 'Mode & Style'],
            ['emoji' => '💄', 'label' => 'Beauté & Soins'],
            ['emoji' => '🍽️', 'label' => 'Cuisine'],
            ['emoji' => '🎬', 'label' => 'Vidéo & Vlog'],
            ['emoji' => '🎨', 'label' => 'Art & Illustration'],
            ['emoji' => '💃', 'label' => 'Danse'],
            ['emoji' => '😂', 'label' => 'Comédie & Humour'],
            ['emoji' => '💼', 'label' => 'Business'],
            ['emoji' => '🌍', 'label' => 'Voyage & Culture'],
            ['emoji' => '⚽', 'label' => 'Sport & Fitness'],
            ['emoji' => '🪡', 'label' => 'Artisanat'],
            ['emoji' => '📚', 'label' => 'Éducation'],
            ['emoji' => '🎙️', 'label' => 'Podcast'],
            ['emoji' => '✨', 'label' => 'Lifestyle'],
        ];

        $stored = static::get('niches_list');
        if (!$stored) return $default;

        $decoded = json_decode($stored, true);
        return (is_array($decoded) && count($decoded) > 0) ? $decoded : $default;
    }
}
