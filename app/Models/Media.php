<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getPreviewUrlAttribute()
    {
        $urls = collect([
            'image' => [
                'mimes' => [
                    'image/gif',
                    'image/avif',
                    'image/apng',
                    'image/png',
                    'image/svg+xml',
                    'image/webp',
                    'image/jpeg'
                ],
                'preview_url' => url("storage/media/{$this->created_at->format('Y/m/d')}/{$this->id}/{$this->file_name}")
            ],
            'audio' => [
                'mimes' => [
                    'audio/mpeg',
                    'audio/aac',
                    'audio/wav',
                ],
                'preview_url' => asset('images/file-type-audio.svg'),
            ],
            'video' => [
                'mimes' => [
                    'video/mp4',
                    'video/webm',
                    'video/mpeg',
                    'video/x-msvideo',
                ],
                'preview_url' => asset('images/file-type-video.svg'),
            ],
            'document' => [
                'mimes' => [
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/pdf'
                ],
                'preview_url' => asset('images/file-type-document.svg'),
            ],
            'archive' => [
                'mimes' => [
                    'application/zip',
                    'application/x-7z-compressed',
                    'application/gzip',
                    'application/vnd.rar',
                ],
                'preview_url' => asset('images/file-type-archive.svg'),
            ],
        ]);
 
        $fileType = $urls->first(function ($item) {
            return in_array($this->mime_type, $item['mimes']);
        });

        return $fileType['preview_url'] ?? asset("image/file-type-other.svg");
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
