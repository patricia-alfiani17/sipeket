<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CloudinaryService
{
    /**
     * @return array{path: string, url: string}
     */
    public function upload(UploadedFile $file, string $folder): array
    {
        $cloudinary = app(Cloudinary::class);

        $result = $cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => $folder,
            'resource_type' => 'auto',
            'use_filename' => false,
            'unique_filename' => true,
        ]);

        $path = $result['public_id'];

        if (! empty($result['format']) && ! str_ends_with($path, '.'.$result['format'])) {
            $path .= '.'.$result['format'];
        }

        return [
            'path' => $path,
            'url' => $result['secure_url'],
        ];
    }

    public function delete(?string $publicId): void
    {
        if (! $publicId) {
            return;
        }

        $this->disk()->delete($publicId);
    }

    public function url(string $publicId): string
    {
        return $this->disk()->url($publicId);
    }

    private function disk(): Cloud
    {
        /** @var Cloud $disk */
        $disk = Storage::disk('cloudinary');

        return $disk;
    }
}
