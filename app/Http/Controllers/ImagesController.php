<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Glide\ServerFactory;
use Illuminate\Contracts\Filesystem\Filesystem;
use League\Glide\Responses\LaravelResponseFactory;

class ImagesController extends Controller
{
    public function show(Filesystem $filesystem, Request $request, $path)
    {
        try {
            ini_set('memory_limit', '-1');
            $server = ServerFactory::create([
                'response' => new LaravelResponseFactory($request),
                'source' => $filesystem->getDriver(),
                'cache' => $filesystem->getDriver(),
                'cache_path_prefix' => '.glide-cache',
            ]);

            return $server->getImageResponse($path, $request->all());
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
