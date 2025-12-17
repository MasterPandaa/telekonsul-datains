<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfilePhoto
{
    public static function relativePath(int $userId): string
    {
        return 'profil/' . $userId . '/fotoprofil.png';
    }

    public static function relativePathWithExtension(int $userId, string $extension): string
    {
        $extension = ltrim(strtolower($extension), '.');
        $extension = $extension !== '' ? $extension : 'png';

        return 'profil/' . $userId . '/fotoprofil.' . $extension;
    }

    public static function publicPath(string $relativePath): string
    {
        return public_path(ltrim($relativePath, '/'));
    }

    public static function isRemote(?string $value): bool
    {
        return !empty($value) && Str::startsWith($value, ['http://', 'https://']);
    }

    public static function exists(?string $fotoValue): bool
    {
        if (blank($fotoValue)) {
            return false;
        }

        if (self::isRemote($fotoValue)) {
            return true;
        }

        $relativePath = ltrim((string) $fotoValue, '/');

        if (Str::startsWith($relativePath, 'storage/')) {
            $relativePath = ltrim(Str::after($relativePath, 'storage/'), '/');
        }

        if (Str::startsWith($relativePath, 'img/profil/')) {
            $candidate = ltrim(Str::after($relativePath, 'img/'), '/');
            if (Storage::disk('public')->exists($candidate)) {
                return true;
            }
        }

        if (Storage::disk('public')->exists($relativePath)) {
            return true;
        }

        return file_exists(self::publicPath($relativePath));
    }

    public static function resolveUrl(?string $fotoValue, ?int $userId = null): ?string
    {
        if (blank($fotoValue)) {
            return null;
        }

        if (self::isRemote($fotoValue)) {
            return $fotoValue;
        }

        $relativePath = ltrim((string) $fotoValue, '/');

        if (Str::startsWith($relativePath, 'storage/')) {
            $relativePath = ltrim(Str::after($relativePath, 'storage/'), '/');
        }

        if (Str::startsWith($relativePath, 'img/profil/')) {
            $candidate = ltrim(Str::after($relativePath, 'img/'), '/');
            if ($candidate !== '' && Storage::disk('public')->exists($candidate)) {
                return Storage::url($candidate);
            }
        }

        if ($relativePath !== '' && Storage::disk('public')->exists($relativePath)) {
            return Storage::url($relativePath);
        }

        if (file_exists(self::publicPath($relativePath))) {
            return asset($relativePath);
        }

        return null;
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
                    self::deleteExisting($userId);
                    Storage::disk('public')->put($storagePng, $png);
                    return $storagePng;
                }
            }
        }

        $ext = $file->getClientOriginalExtension() ?: $file->extension() ?: 'png';
        $ext = ltrim(strtolower($ext), '.');
        $ext = $ext !== '' ? $ext : 'png';

        $storageRelative = $storageDir . '/fotoprofil.' . $ext;
        self::deleteExisting($userId);
        Storage::disk('public')->putFileAs($storageDir, $file, 'fotoprofil.' . $ext);

        return $storageRelative;
    }

    public static function deleteByValue(?string $fotoValue, int $userId): void
    {
        if (blank($fotoValue)) {
            return;
        }

        if (self::isRemote($fotoValue)) {
            return;
        }

        $relativePath = ltrim((string) $fotoValue, '/');

        if (Str::startsWith($relativePath, 'storage/')) {
            $relativePath = ltrim(Str::after($relativePath, 'storage/'), '/');
        }

        self::deleteExisting($userId);

        $allowedPrefixes = array_filter([
            $userId > 0 ? ('profil/' . $userId . '/') : null,
            'dokters/',
            'dosens/',
        ]);

        foreach ($allowedPrefixes as $prefix) {
            if (Str::startsWith($relativePath, $prefix) && Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
                break;
            }
        }

        if ($userId > 0 && Str::startsWith($relativePath, 'img/profil/' . $userId . '/')) {
            $absolute = self::publicPath($relativePath);
            if (is_file($absolute)) {
                @unlink($absolute);
            }
        }
    }

    private static function deleteExisting(int $userId): void
    {
        if ($userId <= 0) {
            return;
        }

        $dir = 'profil/' . $userId;
        $files = Storage::disk('public')->files($dir);

        foreach ($files as $path) {
            if (Str::startsWith(basename($path), 'fotoprofil.')) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}
