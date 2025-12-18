<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class ProfilePhoto
{
    private const BASE_DIR = 'profile';

    private static function getPersistPath(): ?string
    {
        $persistPath = env('PROFILE_PHOTO_PERSIST_PATH');
        if (empty($persistPath)) {
            return null;
        }

        return rtrim($persistPath, "\\/ ");
    }

    private static function getRelativePathInsideBaseDir(string $cleanPath): string
    {
        if (str_starts_with($cleanPath, self::BASE_DIR . '/')) {
            return substr($cleanPath, strlen(self::BASE_DIR) + 1);
        }

        return $cleanPath;
    }

    private static function ensurePersistentLink(): void
    {
        $persistPath = env('PROFILE_PHOTO_PERSIST_PATH');
        if (empty($persistPath)) {
            return;
        }

        if (!File::exists($persistPath)) {
            File::makeDirectory($persistPath, 0755, true);
        }

        $publicProfileDir = public_path(self::BASE_DIR);
        $publicExists = File::exists($publicProfileDir);
        $publicIsLink = $publicExists && is_link($publicProfileDir);

        if ($publicIsLink) {
            $currentTarget = readlink($publicProfileDir);
            if ($currentTarget !== $persistPath) {
                File::delete($publicProfileDir);
                File::link($persistPath, $publicProfileDir);
            }
            return;
        }

        if ($publicExists && File::isDirectory($publicProfileDir)) {
            File::copyDirectory($publicProfileDir, $persistPath);
            File::deleteDirectory($publicProfileDir);
        } elseif ($publicExists) {
            throw new \RuntimeException('PROFILE_PHOTO_PERSIST_PATH is set but public/profile is not a directory or symlink.');
        }

        File::link($persistPath, $publicProfileDir);
    }

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

        self::ensurePersistentLink();

        $persistPath = self::getPersistPath();

        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $userDir = $persistPath
            ? ($persistPath . DIRECTORY_SEPARATOR . $userId)
            : public_path(self::BASE_DIR . '/' . $userId);
        
        if (!File::exists($userDir)) {
            File::makeDirectory($userDir, 0755, true);
        } else {
            self::delete($userId);
        }

        $fileName = 'profile.' . $extension;
        $file->move($userDir, $fileName);

        return self::BASE_DIR . '/' . $userId . '/' . $fileName;
    }

    public static function delete(int $userId): void
    {
        if ($userId <= 0) {
            return;
        }

        self::ensurePersistentLink();

        $persistPath = self::getPersistPath();

        $userDir = $persistPath
            ? ($persistPath . DIRECTORY_SEPARATOR . $userId)
            : public_path(self::BASE_DIR . '/' . $userId);
        
        if (File::exists($userDir)) {
            $files = File::glob($userDir . '/profile.*');
            foreach ($files as $file) {
                if (File::isFile($file)) {
                    File::delete($file);
                }
            }
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
        $fullPath = public_path($cleanPath);

        if (File::exists($fullPath)) {
            return asset($cleanPath);
        }

        $persistPath = self::getPersistPath();
        if (!empty($persistPath)) {
            $relativeInsideBase = self::getRelativePathInsideBaseDir($cleanPath);
            $persistFullPath = $persistPath . DIRECTORY_SEPARATOR . $relativeInsideBase;

            if (File::exists($persistFullPath)) {
                return url($cleanPath);
            }
        }

        return self::getDefaultUrl();
    }
}
