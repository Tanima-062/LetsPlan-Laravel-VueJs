<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageTrait
{
    public function image($image, $imageName): string
    {
        $destinationPath = storage_path('app/public/images');
        if ( !file_exists($destinationPath)){
            Storage::makeDirectory($destinationPath);
        }
        $img = \Image::make($image->path());
        $img->resize(500, 500);
        $img->save($destinationPath . DIRECTORY_SEPARATOR . $imageName);

        return '/storage/images/' . $imageName;
    }
}
