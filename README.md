# 🆙 FileUpload plugin for CakePHP

:warning: This is readme for version 2.x. For 1.x go to [v1.1.2](https://github.com/MayMeow/cakephp-fileupload/tree/v1.1.2) release.

### :stop_sign: Breaking changes (read before use)

Version 2.x is not compatibile with cakephp lower than 5.x and it is not backward compatibile with previous releases of this plugins.

- S3 storage support was removed
- Bunny CND storage support was added
- All components and Managers was rewritten.

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


## License

MIT