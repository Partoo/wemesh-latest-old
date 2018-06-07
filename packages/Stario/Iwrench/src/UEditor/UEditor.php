<?php
namespace Stario\Iwrench\UEditor;

use Illuminate\Http\Request;

/**
 * UEditor
 */
class UEditor
{
    public function __construct()
    {
        $this->app->singleton('ueditor.storage', function ($app) {
            return new StorageManager(Storage::disk($app['config']->get('ueditor.disk', 'public')));
        });
    }
    use UEConfig;

    public static function serve()
    {
        $upload = static::get('upload');
        // $storage = static::get('storage');

        return request()->get('action');
    }
}
