<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ProfilePhoto
{
    private const DISK = 'public';
    private const DIR = 'profile-photos';

    public static function getDefaultUrl(): string
    {
        return asset('img/BW_ASSRI.png');
    }

    public static function url(?string $path): string
    {
        if (empty($path)) {
            return self::getDefaultUrl();
        }

        return url('public-files/' . ltrim($path, '/'));
    }

    public static function storeForUser(UploadedFile $file, int $userId): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $fileName = 'u' . $userId . '_' . Str::uuid()->toString() . '.' . $extension;

        return $file->storeAs(self::DIR, $fileName, self::DISK);
    }

    public static function deleteIfExists(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        \Illuminate\Support\Facades\Storage::disk(self::DISK)->delete($path);
    }
}
