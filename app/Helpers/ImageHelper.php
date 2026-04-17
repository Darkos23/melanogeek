<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Recadre et redimensionne une image stockée en 16:9 centré.
     * Écrase le fichier d'origine. Sauvegarde en JPEG qualité 88.
     *
     * @param  string  $storagePath  Chemin relatif dans le disque 'public' (ex: posts/thumbnails/xyz.jpg)
     * @param  int     $targetW      Largeur cible (défaut 1280)
     * @param  int     $targetH      Hauteur cible (défaut 720 → 16:9)
     */
    public static function cropAndResize(string $storagePath, int $targetW = 1280, int $targetH = 720): void
    {
        $fullPath = Storage::disk('public')->path($storagePath);

        if (! file_exists($fullPath)) return;

        // ── Charger selon le type MIME ──
        $mime = mime_content_type($fullPath);
        $src  = match ($mime) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($fullPath),
            'image/png'               => @imagecreatefrompng($fullPath),
            'image/webp'              => @imagecreatefromwebp($fullPath),
            'image/gif'               => @imagecreatefromgif($fullPath),
            default                   => null,
        };

        if (! $src) return;

        $origW = imagesx($src);
        $origH = imagesy($src);

        // ── Calculer la zone de crop centrée en 16:9 ──
        $origRatio   = $origW / $origH;
        $targetRatio = $targetW / $targetH;

        if ($origRatio > $targetRatio) {
            // Image trop large → crop sur les côtés
            $cropH = $origH;
            $cropW = (int) round($origH * $targetRatio);
            $cropX = (int) round(($origW - $cropW) / 2);
            $cropY = 0;
        } else {
            // Image trop haute → crop en haut et en bas
            $cropW = $origW;
            $cropH = (int) round($origW / $targetRatio);
            $cropX = 0;
            $cropY = (int) round(($origH - $cropH) / 2);
        }

        // ── Créer l'image de destination ──
        $dst = imagecreatetruecolor($targetW, $targetH);

        // Conserver la transparence pour PNG/WebP
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefilledrectangle($dst, 0, 0, $targetW, $targetH, $transparent);

        imagecopyresampled($dst, $src, 0, 0, $cropX, $cropY, $targetW, $targetH, $cropW, $cropH);

        // ── Sauvegarder en JPEG (plus léger, universel) ──
        // Remplacer l'extension par .jpg
        $newPath = preg_replace('/\.(png|webp|gif|jpeg)$/i', '.jpg', $storagePath);

        imagejpeg($dst, Storage::disk('public')->path($newPath), 88);

        imagedestroy($src);
        imagedestroy($dst);

        // Si l'extension a changé, supprimer l'original et retourner le nouveau path
        if ($newPath !== $storagePath) {
            Storage::disk('public')->delete($storagePath);
        }

        // Mettre à jour le chemin en base si besoin (retourner le nouveau path)
        // → Le controller doit utiliser self::cropAndResizePath() pour ça
    }

    /**
     * Version qui retourne le chemin final (potentiellement renommé en .jpg).
     */
    public static function cropAndResizePath(string $storagePath, int $targetW = 1280, int $targetH = 720): string
    {
        $fullPath = Storage::disk('public')->path($storagePath);

        if (! file_exists($fullPath)) return $storagePath;

        $mime = mime_content_type($fullPath);
        $src  = match ($mime) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($fullPath),
            'image/png'               => @imagecreatefrompng($fullPath),
            'image/webp'              => @imagecreatefromwebp($fullPath),
            'image/gif'               => @imagecreatefromgif($fullPath),
            default                   => null,
        };

        if (! $src) return $storagePath;

        $origW = imagesx($src);
        $origH = imagesy($src);

        $origRatio   = $origW / $origH;
        $targetRatio = $targetW / $targetH;

        if ($origRatio > $targetRatio) {
            $cropH = $origH;
            $cropW = (int) round($origH * $targetRatio);
            $cropX = (int) round(($origW - $cropW) / 2);
            $cropY = 0;
        } else {
            $cropW = $origW;
            $cropH = (int) round($origW / $targetRatio);
            $cropX = 0;
            $cropY = (int) round(($origH - $cropH) / 2);
        }

        $dst = imagecreatetruecolor($targetW, $targetH);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);

        imagecopyresampled($dst, $src, 0, 0, $cropX, $cropY, $targetW, $targetH, $cropW, $cropH);

        // Toujours sauvegarder en JPEG
        $newPath = preg_replace('/\.(png|webp|gif|jpeg)$/i', '.jpg', $storagePath);
        $newFullPath = Storage::disk('public')->path($newPath);

        // Créer les dossiers intermédiaires si besoin
        if (! is_dir(dirname($newFullPath))) {
            mkdir(dirname($newFullPath), 0755, true);
        }

        imagejpeg($dst, $newFullPath, 88);
        imagedestroy($src);
        imagedestroy($dst);

        // Supprimer l'original si extension différente
        if ($newPath !== $storagePath) {
            Storage::disk('public')->delete($storagePath);
        }

        return $newPath;
    }
}
