# 🆙 FileUpload plugin for CakePHP

Cakephp plugin to upload files to storage and download them. Currently are supported:

- Local storage
- S3 Compatible storage

And supported actions are upload and download.

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

## 🐘 From packagist

```
composer require maymeow/file-upload
```

## Usage

To load plugin addd to `Application.php`

```php
$this->addPlugin('FileUpload');
```

Next load it in controllers where you want to use it. To use default config

```php
public function initialize(): void
{
    parent::initialize();
    $this->loadComponent('FileUpload.Upload');
    $this->loadComponent('FileUpload.Dowmload'); // in case you wan to download files
}
```

## Using local storage

Commands above are loaded with default configuration:
- Using Local filesystem: type is set to `local`
- Data are stored in `sotrage` folder in root of your application - This folder is not public and cannot be served directly from webserver. You need to use `DownloadComponent` to get files
- Field name for uploading files is `uploaded_file`
- Allowed are all file types

```php
public function initialize(): void
{
    parent::initialize();
    $this->loadComponent('FileUpload.Upload', [
        'fieldName' => 'your_form_file_field',
        'storagePath' => 'path_to_storage',
        'allowedFileTypes' => '*'
    ]);
}
```

For `allowedFileTypes` use `'allowedFileTypes' => '*'` for all file types or `'allowedFileTypes' => ['type1', 'type2']` for your expected file types. If file have not allowed type Component will throw `Cake\Http\Exception\HttpException`.

Using local storage (default option) you can use default config but do not forge to change allowed file types and form field nam from which you uploading file to server.

## Using S3 storage

For S3 storage configuration is pretty same as configuration above, but you have to change type to `s3` instead of `local` which is default as follows

```php
public function initialize(): void
{
    parent::initialize();
    $this->loadComponent('FileUpload.Upload', [
        'fieldName' => 'your_form_file_field',
        'storagePath' => 'bucket_name',
        'storage_type' => 's3'
    ]);
}
```

:exclamation: Dont forget to change configuration for `DownloadComponent` too if you planing using it.

Next add configuration for s3 server to your config file `app_local.php` for example as follows:

```php
'S3' => [
    'version' => 'latest',
    'region'  => 'us-east-1',
    'endpoint' => 'http://cake_minio:9000',
    'use_path_style_endpoint' => true,
    'credentials' => [
        'key'    => 'minioadmin',
        'secret' => 'minioadmin',
    ],
]
```

Config above is for using minio with my [CakePHP starte kit](https://github.com/MayMeow/cakephp-starter-kit). Change it for your needs.


## Uploading files


```php
/**
 * @throws \HttpException
 * @return \Cake\Http\Response|null|void
 */
public function upload()
{
    $uploadForm = new UploadForm();

    if ($this->request->is('post')) {
        $uploadedFile = $this->Upload->getFile($this->request);

        // Create new Entity and store info about uploaded file
        $file = $this->Files->newEmptyEntity();

        $file->name = $uploadedFile->getFileName();
        $file->path = $uploadedFile->getPath();

        $this->Files->save($file);

        return $this->redirect($this->referer());
    }

    $this->set(compact('uploadForm'));
}
```

## Downloading files


```php
/**
 * @param string $fileName
 * @return \Cake\Http\Response|void
 */
public function download($fileName)
{
    $downloadedFile = $this->Download->getFile($fileName);

    $response = $this->response;
    $response = $response->withStringBody($downloadedFile->getFileContent());
    $response = $response->withType($downloadedFile->getFileType());

    if ($this->request->getParam('?.download') == true) {
        $response = $response->withDownload($fileName);
    }

    return $response;
}
```

## 🎯 Direction

* [x] Configurable field name
* [x] Configurable path to storage
* [x] Allowed file types
* [x] Add S3 Support
* [ ] Multiple file upload
* [ ] File Size

💡 If you have more ideas then you can post issue on porect's GitHub page.

## License

MIT