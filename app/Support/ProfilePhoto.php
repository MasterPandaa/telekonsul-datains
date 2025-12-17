<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfilePhoto
{
    private static function toPublicUrl(string $relativePath): string
    {
        return '/' . ltrim($relativePath, '/');
    }

    public static function relativePath(int $userId): string
    {
        return 'storage/profil/' . $userId . '/fotoprofil.png';
    }

    public static function relativePathWithExtension(int $userId, string $extension): string
    {
        $extension = ltrim(strtolower($extension), '.');
        $extension = $extension !== '' ? $extension : 'png';

        return 'storage/profil/' . $userId . '/fotoprofil.' . $extension;
    }

    public static function publicPath(string $relativePath): string
    {
        return public_path(ltrim($relativePath, '/'));
    }

    public static function isRemote(?string $value): bool
    {
        return !empty($value) && Str::startsWith($value, ['http://', 'https://']);
    }

    public static function resolveUrl(?string $fotoValue, ?int $userId = null): ?string
    {
        if (blank($fotoValue)) {
            return null;
        }

        if (self::isRemote($fotoValue)) {
            return $fotoValue;
        }

        $relativePath = ltrim($fotoValue, '/');

        if (Str::startsWith($relativePath, 'storage/')) {
            $publicCandidate = public_path($relativePath);
            if (file_exists($publicCandidate)) {
                return self::toPublicUrl($relativePath);
            }

            $storageRelative = ltrim(Str::after($relativePath, 'storage/'), '/');
            if ($storageRelative !== '' && Storage::disk('public')->exists($storageRelative)) {
                return self::toPublicUrl('storage/' . $storageRelative);
            }

            return self::blackDataUrl();
        }

        if (Str::startsWith($relativePath, ['dokters/', 'dosen/', 'dosen\/', 'img/dosen/'])) {
            $storageRelative = $relativePath;
            $storageRelative = Str::startsWith($storageRelative, 'img/dosen/')
                ? Str::after($storageRelative, 'img/')
                : $storageRelative;

            if (Storage::disk('public')->exists($storageRelative)) {
                return self::toPublicUrl('storage/' . ltrim($storageRelative, '/'));
            }
        }

        if (file_exists(self::publicPath($relativePath))) {
            return self::toPublicUrl($relativePath);
        }

        if (Str::startsWith($relativePath, 'img/profil/')) {
            $storageRelative = Str::after($relativePath, 'img/');
            if ($storageRelative !== '' && Storage::disk('public')->exists($storageRelative)) {
                return self::toPublicUrl('storage/' . ltrim($storageRelative, '/'));
            }
        }

        return self::blackDataUrl();
    }

    public static function blackDataUrl(): string
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128"><rect width="128" height="128" fill="#000000"/></svg>';

        return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
    }

    public static function storeUploadedAsPng(UploadedFile $file, int $userId): string
    {
        $storageDir = 'profil/' . $userId;
        $storagePng = $storageDir . '/fotoprofil.png';
        $returnPng = 'storage/' . $storagePng;

        // Some servers (e.g. certain Railway images) may not have GD enabled.
        $hasGd = function_exists('imagecreatefromstring') && function_exists('imagepng');

        if ($hasGd) {
            $image = @imagecreatefromstring(file_get_contents($file->getRealPath()));
            if ($image !== false) {
                imagealphablending($image, true);
                imagesavealpha($image, true);

                ob_start();
                imagepng($image);
                $png = ob_get_clean();
                imagedestroy($image);

                if ($png !== false) {
                    Storage::disk('public')->put($storagePng, $png);
                    return $returnPng;
                }
            }
        }

        $ext = $file->getClientOriginalExtension() ?: $file->extension() ?: 'png';
        $ext = ltrim(strtolower($ext), '.');
        $ext = $ext !== '' ? $ext : 'png';

        $storageRelative = $storageDir . '/fotoprofil.' . $ext;
        Storage::disk('public')->putFileAs($storageDir, $file, 'fotoprofil.' . $ext);

        return 'storage/' . $storageRelative;
    }
}
