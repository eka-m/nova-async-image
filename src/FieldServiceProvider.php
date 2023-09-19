<?php

namespace Eka\AsyncImage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('async-image', __DIR__ . '/../dist/js/field.js');
            Nova::style('async-image', __DIR__ . '/../dist/css/field.css');
        });
        Route::post('/eka/async/image/upload', fn(Request $request) => $this->upload($request));
        Route::post('/eka/async/image/remove', fn(Request $request) => $this->remove($request));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function upload(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $path = $request->file('image')->store($request->post('path'), $request->post('disk'));
        return response()->json(['status' => 'OK', 'path' => $path]);
    }

    private function remove(Request $request): \Illuminate\Http\JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'image' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $image = $request->post('image');
        $disk = $request->post('disk');
        if (Storage::disk($disk)->exists($image)) {
            Storage::disk($disk)->delete($image);
            return response()->json(['status' => 'OK', 'message' => 'Deleted']);
        }
        return response()->json(['status' => 'ERR', 'message' => 'File not found']);
    }
}
