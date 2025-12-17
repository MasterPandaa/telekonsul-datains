<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfilePhoto
{
    public static function relativePath(int $userId): string
    {
        return 'img/profil/' . $userId . '/fotoprofil.png';
    }

    public static function relativePathWithExtension(int $userId, string $extension): string
    {
        $extension = ltrim(strtolower($extension), '.');
        $extension = $extension !== '' ? $extension : 'png';

        return 'img/profil/' . $userId . '/fotoprofil.' . $extension;
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
                return asset($relativePath);
            }

            $storageRelative = ltrim(Str::after($relativePath, 'storage/'), '/');
            if ($storageRelative !== '' && Storage::disk('public')->exists($storageRelative)) {
                return asset('storage/' . $storageRelative);
            }

            return self::blackDataUrl();
        }

        if (Str::startsWith($relativePath, ['dokters/', 'dosen/', 'dosen\/', 'img/dosen/'])) {
            $storageRelative = $relativePath;
            $storageRelative = Str::startsWith($storageRelative, 'img/dosen/')
                ? Str::after($storageRelative, 'img/')
                : $storageRelative;

            if (Storage::disk('public')->exists($storageRelative)) {
                return asset('storage/' . ltrim($storageRelative, '/'));
            }
        }

        if (file_exists(self::publicPath($relativePath))) {
            return asset($relativePath);
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
        $relativePng = self::relativePath($userId);
        $dir = dirname($relativePng);
        File::ensureDirectoryExists(public_path($dir));

        $targetPng = public_path($relativePng);

        // Some servers (e.g. certain Railway images) may not have GD enabled.
        $hasGd = function_exists('imagecreatefromstring') && function_exists('imagepng');

        if ($hasGd) {
            $image = @imagecreatefromstring(file_get_contents($file->getRealPath()));
            if ($image !== false) {
                imagealphablending($image, true);
                imagesavealpha($image, true);
                imagepng($image, $targetPng);
                imagedestroy($image);

                return $relativePng;
            }
        }

        $ext = $file->getClientOriginalExtension() ?: $file->extension() ?: 'png';
        $relative = self::relativePathWithExtension($userId, $ext);
        $file->move(public_path($dir), basename($relative));

        return $relative;
    }
}
