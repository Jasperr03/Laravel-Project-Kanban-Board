<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function store()
    {
        request()->validate([
            'file' => ['file', 'max:512000']
          ], [
              'max' => 'File cannot be larger than 512MB.'
          ]);
  
          $file = request()->file('file');
          $cardId = request('card_id'); 


          
          $media = Media::create([
              'name' => $file->getClientOriginalName(),
              'file_name' => $file->getClientOriginalName(),
              'mime_type' => $file->getMimeType(),
              'size' => $file->getSize(),
              'user_id' => auth()->id(),
              'card_id' => $cardId,
          ]);
  
          // media/year/month/day/id
          $directory = "media/{$media->created_at->format('Y/m/d')}/{$media->id}";
          $file->storeAs($directory, $media->file_name, 'public');
          
          return [
            'id' => $media->id,
            'preview_url' => $media->preview_url
        ];
    }
}
