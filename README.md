# 🆙 FileUpload plugin for CakePHP

> [!NOTE]
> This is readme for version 2.x. For 1.x go to [v1.1.2](https://github.com/MayMeow/cakephp-fileupload/tree/v1.1.2) release.

> [!CAUTION]
> :stop_sign: Breaking changes (read before use)
> 
> Version 2.x is not compatibile with cakephp lower than 5.x and it is not backward compatibile with previous releases of this plugins.
>
> - S3 storage support was removed (will be added in future relases)
> - Bunny CND storage support was added
> - All components and Managers was rewritten.
> 
> And supported actions are upload and download.

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

## 🐘 From packagist

```
composer require maymeow/file-upload "^2.0.0"
```

## Usage

Add configuration somewhere to your config files

```php
'Storage' => [
    'defaultStorageType' => env('STORAGE_DEFAULT_STORAGE_TYPE', 'local'),
    'local' => [
        'managerClass' => LocalStorageManager::class,
        'storagePath' => env('STORAGE_LOCAL_STORAGE_PATH', ROOT . DS . 'storage' . DS),
    ],
    'bunny' => [
        'managerClass' => BunnyStorageManager::class,
        'cdnDomain' => env('BUNNY_STORAGE_CDN_DOMAIN', ''), // your cnd url
        'region' => env('BUNNY_STORAGE_REGION', ''), // region, empty is DE
        'baseHostName' => 'storage.bunnycdn.com', // base host name not changeable
        'storageZone' => env('BUNNY_STORAGE_ZONE', ''), // your storage zone name
        'storageZonePath' => env('BUNNY_STORAGE_ZONE_PATH', ''), // folder in zono
        'accessKey' => env('BUNNY_STORAGE_ACCESS_KEY', ''), // API key for write access
    ]
]
```

For bunny cdn minimal configuration is to have folowing keys configured

```ini
BUNNY_STORAGE_ACCESS_KEY=
BUNNY_STORAGE_CDN_DOMAIN=
BUNNY_STORAGE_ZONE=
```

If you need/want nginx to server your static files without PHP set `STORAGE_LOCAL_STORAGE_PATH` location to whe webroot folder.

Load plugin with adding

```php
$this->addPlugin('FileUpload'); // in your Application.php bootstrap function
```

or you can add your plugin with `plugins.php` config file

```php
return [
    // .. your other plugins
    'FileUpload' => [],
];
```

Loading components

```php
$config = Configure::read('Storage.local'); // or Storage.bunny

// or by setting it with .env STORAGE_DEFAULT_STORAGE_TYPE
$storageType = Configure::read('Storage.defaultStorageType');
$config = Configure::read('Storage.' . $storageType); 

$this->loadComponent('FileUpload.Upload', $config);
$this->loadComponent('FileUpload.Download', $config);
```

Uploading files

```php
$file = $this->Upload->getFile($this);
// do something with file

// store your file name in database
$file->getFileName(); // sanitized file name - with removed restricted characters in the name
```

```php
$file = $this->Download->getFile($resource->name);
// do something with file
```

:memo: Note that the function above will read content of file and you then need to use response to push it to the viewer. If you want to do it without using PHP you will need to get URL of file and if you using local storage manager your folder need to be in webrootfolder.

```php
$file->get('storagePath'); // local: /patht/to/sorage or bunny: https://cdn.your.tld/path/to/folder/
// combine it with filename from your database go download it
```


## License

MIT
