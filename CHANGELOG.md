v 1.1.2

Bugfixes of release 1.1.0

* FIX Convert stream to string before setting it to the filebody in S3StorageManager when downloading files

# v 1.1.1

Allow to use cakephp 4.x instead only 4.1.x

# v 1.1.0

Added support for S3 Compatible storage, Upload and download are now handling StorageManagers.

* ADD Support for S3 Compatible storage
* ADD new Component for Download files from storage
* ADD new `storage_type` key into configuration for components
* ADD AWS SDK to requirements
* ADD `FileUpload\Exceptions\FileContentException`
* ADD `LocalStorageManager` and `S3StorageManager` which handling upload and download files from storage
* ADD New configuration for `S3` client (for more informations check README)
* ADD `StorageConfigInterface`, `StorageManagerInterface` and `StoredFileInterface`
* UPDATE Return type for `getFile()` was changed to`\FileUpload\File\StoredFileInterface` from `\Psr\Http\Message\UploadedFileInterface` Which returning more informations about file
* UPDATE `UploadComponent::getFile()` from now can throw `HttpException` in case there is `Not supported storage type` in configuration
