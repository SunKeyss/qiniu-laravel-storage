<?php namespace zgldh\QiniuStorage;

use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use zgldh\QiniuStorage\Plugins\DownloadUrl;
use zgldh\QiniuStorage\Plugins\Fetch;
use zgldh\QiniuStorage\Plugins\ImageExif;
use zgldh\QiniuStorage\Plugins\ImageInfo;
use zgldh\QiniuStorage\Plugins\AvInfo;
use zgldh\QiniuStorage\Plugins\ImagePreviewUrl;
use zgldh\QiniuStorage\Plugins\LastReturn;
use zgldh\QiniuStorage\Plugins\PersistentFop;
use zgldh\QiniuStorage\Plugins\PersistentStatus;
use zgldh\QiniuStorage\Plugins\PrivateDownloadUrl;
use zgldh\QiniuStorage\Plugins\Qetag;
use zgldh\QiniuStorage\Plugins\UploadToken;
use zgldh\QiniuStorage\Plugins\PrivateImagePreviewUrl;
use zgldh\QiniuStorage\Plugins\VerifyCallback;
use zgldh\QiniuStorage\Plugins\WithUploadToken;

class QiniuFilesystemServiceProvider extends ServiceProvider
{

    public function boot()
    {
        Storage::extend('qiniu', function ($app, $config) {
            $config['access_key'] = env('QINIU_ACCESS_KEY');
            $config['secret_key'] = env('QINIU_SECRET_KEY');
            $config['bucket'] = env('QINIU_BUCKET');
            $config['domains']['default'] = env('QINIU_DOMAIN');

            if (isset($config['domains'])) {
                $domains = $config['domains'];
            } else {
                $domains = [
                    'default' => $config['domain'],
                    'https' => null,
                    'custom' => null
                ];
            }
            $qiniu_adapter = new QiniuAdapter(
                $config['access_key'],
                $config['secret_key'],
                $config['bucket'],
                $domains,
                isset($config['notify_url']) ? $config['notify_url'] : null,
                isset($config['access']) ? $config['access'] : 'public',
                isset($config['hotlink_prevention_key']) ? $config['hotlink_prevention_key'] : null
            );
            $file_system = new Filesystem($qiniu_adapter);
            $file_system->addPlugin(new PrivateDownloadUrl());
            $file_system->addPlugin(new DownloadUrl());
            $file_system->addPlugin(new AvInfo());
            $file_system->addPlugin(new ImageInfo());
            $file_system->addPlugin(new ImageExif());
            $file_system->addPlugin(new ImagePreviewUrl());
            $file_system->addPlugin(new PersistentFop());
            $file_system->addPlugin(new PersistentStatus());
            $file_system->addPlugin(new UploadToken());
            $file_system->addPlugin(new PrivateImagePreviewUrl());
            $file_system->addPlugin(new VerifyCallback());
            $file_system->addPlugin(new Fetch());
            $file_system->addPlugin(new Qetag());
            $file_system->addPlugin(new WithUploadToken());
            $file_system->addPlugin(new LastReturn());

            return $file_system;
        }
        );
    }

    public function register()
    {
        //
    }
}
