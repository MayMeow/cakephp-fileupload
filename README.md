# FileUpload plugin for CakePHP

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

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
}
```

Or you can change them

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

Usage in function

```php
public function add()
{
    $file = $this->Files->newEmptyEntity();

    if ($this->request->is('post')) {
        
        // you can use try catch to show Flash instead of error page
        try {
            // Get Uploaded file details
            $fileObject = $this->Upload->getFile($this->request);   

            $file = $this->Files->patchEntity($file, $this->request->getData());

            // Update Model and write it
            $file->name = $fileObject->getClientFilename();
            $file->size = $fileObject->getSize();
            $file->path = $fileObject->getClientFilename();
            $file->updated = Date::now();

            if ($this->Files->save($file)) {
                $this->Flash->success(__('The file has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The file could not be saved. Please, try again.'));

        } catch (HttpException $e) {
            $this->Flash->error($e->getMessage());
        }
    }
    $this->set(compact('file'));
}
```