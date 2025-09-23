# 🆙 CakePHP 用 FileUpload プラグイン

> [!NOTE]
> これはバージョン 2.x 向けの README です。1.x 系については [v1.1.2](https://github.com/MayMeow/cakephp-fileupload/tree/v1.1.2) のリリースをご確認ください。

> [!CAUTION]
> :stop_sign: 破壊的変更（使用前に必ずお読みください）
>
> バージョン 2.x は CakePHP 5.x 未満には対応しておらず、また本プラグインの過去バージョンとの後方互換性もありません。
>
> - S3 ストレージ対応を削除しました（将来のリリースで再追加予定）
> - Bunny CDN ストレージ対応を追加しました
> - すべての Component と Manager を書き直しました
>
> 現在サポートしているアクションは upload と download のみです。

## インストール

このプラグインは [composer](https://getcomposer.org) を使用して CakePHP アプリケーションへインストールできます。

Composer パッケージの推奨インストール方法は次のとおりです。

## 🐘 Packagist から

```
composer require maymeow/file-upload "^2.0.0"
```

## 使い方

設定を config 配下のいずれかのファイルに追加してください。

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

Bunny CDN を利用する場合、最低限以下のキーを設定してください。

```ini
BUNNY_STORAGE_ACCESS_KEY=
BUNNY_STORAGE_CDN_DOMAIN=
BUNNY_STORAGE_ZONE=
```

PHP を介さずに nginx で静的ファイルを配信したい場合は、`STORAGE_LOCAL_STORAGE_PATH` を webroot フォルダ内の場所に設定してください。

プラグインの読み込みは次を追加します。

```php
$this->addPlugin('FileUpload'); // in your Application.php bootstrap function
```

あるいは `plugins.php` の設定でプラグインを追加できます。

```php
return [
    // .. your other plugins
    'FileUpload' => [],
];
```

Component の読み込み

```php
$config = Configure::read('Storage.local'); // or Storage.bunny

// or by setting it with .env STORAGE_DEFAULT_STORAGE_TYPE
$storageType = Configure::read('Storage.defaultStorageType');
$config = Configure::read('Storage.' . $storageType); 

$this->loadComponent('FileUpload.Upload', $config);
$this->loadComponent('FileUpload.Download', $config);
```

ファイルのアップロード

```php
$file = $this->Upload->getFile($this);
// do something with file

// store your file name in database
$file->getFileName(); // sanitized file name - with removed restricted characters in the name
```

ファイルのダウンロード

```php
$file = $this->Download->getFile($resource->name);
// do something with file
```

:memo: 上記の関数はファイル内容を読み込みます。閲覧者へ返却するには、レスポンスを使って出力してください。PHP を使わずに配信したい場合はファイルの URL を取得します。ローカルストレージマネージャーを使用している場合は、対象フォルダを webroot 配下に置く必要があります。

```php
$file->get('storagePath'); // local: /patht/to/sorage or bunny: https://cdn.your.tld/path/to/folder/
// combine it with filename from your database go download it
```

## ライセンス

MIT