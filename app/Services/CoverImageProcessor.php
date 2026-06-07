<?php

namespace App\Services;

use GdImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CoverImageProcessor
{
    public function store(UploadedFile $file, string $directory = 'covers'): string
    {
        if (! extension_loaded('gd')) {
            return $file->store($directory, 'public');
        }

        $mime = $file->getMimeType() ?? '';

        if ($mime === 'image/gif') {
            return $file->store($directory, 'public');
        }

        $image = $this->loadImage($file->getRealPath(), $mime);
        if ($image === false) {
            return $file->store($directory, 'public');
        }

        $maxEdge = (int) config('blog.cover.max_edge', 1920);
        $width = imagesx($image);
        $height = imagesy($image);

        if (max($width, $height) <= $maxEdge) {
            imagedestroy($image);

            return $file->store($directory, 'public');
        }

        $resized = $this->resizeDown($image, $maxEdge);
        $filename = Str::uuid().'.'.$this->extensionForMime($mime);
        $relativePath = $directory.'/'.$filename;

        Storage::disk('public')->makeDirectory($directory);
        $this->saveImage($resized, Storage::disk('public')->path($relativePath), $mime);
        imagedestroy($resized);

        return $relativePath;
    }

    private function loadImage(string $path, string $mime): GdImage|false
    {
        return match ($mime) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };
    }

    private function resizeDown(GdImage $image, int $maxEdge): GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $ratio = $maxEdge / max($width, $height);
        $newWidth = (int) round($width * $ratio);
        $newHeight = (int) round($height * $ratio);

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);

        imagecopyresampled(
            $resized,
            $image,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );

        imagedestroy($image);

        return $resized;
    }

    private function saveImage(GdImage $image, string $path, string $mime): void
    {
        match ($mime) {
            'image/jpeg', 'image/jpg' => imagejpeg($image, $path, (int) config('blog.cover.jpeg_quality', 92)),
            'image/png' => imagepng($image, $path, (int) config('blog.cover.png_compression', 6)),
            'image/webp' => imagewebp($image, $path, (int) config('blog.cover.webp_quality', 90)),
            default => imagejpeg($image, $path, (int) config('blog.cover.jpeg_quality', 92)),
        };
    }

    private function extensionForMime(string $mime): string
    {
        return match ($mime) {
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => 'jpg',
        };
    }
}
