<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfilePhoto
{
    private const BASE_DIR = 'profile-photos';

    public static function getDefaultUrl(): string
    {
        return 'data:image/svg+xml;base64,' . base64_encode(
            '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">' .
            '<rect width="200" height="200" fill="#e5e7eb"/>' .
            '<circle cx="100" cy="80" r="35" fill="#9ca3af"/>' .
            '<path d="M100 120 Q60 120 40 160 L160 160 Q140 120 100 120" fill="#9ca3af"/>' .
            '</svg>'
        );
    }

    public static function store(UploadedFile $file, int $userId): string
    {
        if ($userId <= 0) {
            throw new \InvalidArgumentException('User ID must be positive');
        }

        self::delete($userId);

        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $path = self::BASE_DIR . '/' . $userId . '/profile.' . $extension;
        
        Storage::disk('public')->putFileAs(
            self::BASE_DIR . '/' . $userId,
            $file,
            'profile.' . $extension
        );

        return $path;
    }

    public static function delete(int $userId): void
    {
        if ($userId <= 0) {
            return;
        }

        $directory = self::BASE_DIR . '/' . $userId;
        
        if (Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->deleteDirectory($directory);
        }
    }

    public static function getUrl(?string $photoPath): string
    {
        if (empty($photoPath)) {
            return self::getDefaultUrl();
        }

        if (filter_var($photoPath, FILTER_VALIDATE_URL)) {
            return $photoPath;
        }

        if (str_starts_with($photoPath, 'data:')) {
            return $photoPath;
        }

        $cleanPath = ltrim($photoPath, '/');
        
        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }

        if (Storage::disk('public')->exists($cleanPath)) {
            return Storage::disk('public')->url($cleanPath);
        }

        return self::getDefaultUrl();
    }
}
