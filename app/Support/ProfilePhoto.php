<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfilePhoto
{
    private const BASE_DIR = 'profile-photos';

    private static function normalizePath(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $path = preg_replace('#/+#', '/', $path) ?? $path;

        return $path;
    }

    public static function relativePath(int $userId): string
    {
        return self::BASE_DIR . '/' . $userId . '/profile.png';
    }

    public static function relativePathWithExtension(int $userId, string $extension): string
    {
        $extension = ltrim(strtolower($extension), '.');
        $extension = $extension !== '' ? $extension : 'png';

        return self::BASE_DIR . '/' . $userId . '/profile.' . $extension;
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

        $relativePath = self::normalizePath(ltrim((string) $fotoValue, '/'));

        if (Str::startsWith($relativePath, 'storage/')) {
            $relativePath = ltrim(Str::after($relativePath, 'storage/'), '/');
        }

        return $relativePath !== '' && Storage::disk('public')->exists($relativePath);
    }

    public static function resolveUrl(?string $fotoValue, ?int $userId = null): ?string
    {
        if (blank($fotoValue)) {
            return null;
        }

        if (self::isRemote($fotoValue)) {
            return $fotoValue;
        }

        $relativePath = self::normalizePath(ltrim((string) $fotoValue, '/'));

        if (Str::startsWith($relativePath, 'storage/')) {
            $relativePath = ltrim(Str::after($relativePath, 'storage/'), '/');
        }

        if ($relativePath === '') {
            return null;
        }

        // Return URL even if file doesn't exist in local check
        // This allows Railway volumes or other persistent storage to serve the file
        return Storage::url($relativePath);
    }

    public static function blackDataUrl(): string
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128"><rect width="128" height="128" fill="#000000"/></svg>';

        return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
    }

    public static function transparentDataUrl(): string
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128"><rect width="128" height="128" fill="transparent"/></svg>';

        return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
    }

    public static function storeUploadedAsPng(UploadedFile $file, int $userId): string
    {
        return self::storeUploaded($file, $userId);
    }

    public static function storeUploaded(UploadedFile $file, int $userId): string
    {
        $storageDir = self::BASE_DIR . '/' . $userId;
        $storagePng = $storageDir . '/profile.png';

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

        $storageRelative = $storageDir . '/profile.' . $ext;
        self::deleteExisting($userId);
        Storage::disk('public')->putFileAs($storageDir, $file, 'profile.' . $ext);

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

        self::deleteExisting($userId);
    }

    private static function deleteExisting(int $userId): void
    {
        if ($userId <= 0) {
            return;
        }

        $dir = self::BASE_DIR . '/' . $userId;
        $files = Storage::disk('public')->files($dir);

        foreach ($files as $path) {
            if (Str::startsWith(basename($path), 'profile.')) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}
