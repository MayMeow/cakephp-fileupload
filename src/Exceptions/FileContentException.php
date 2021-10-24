<?php
declare(strict_types=1);

namespace FileUpload\Exceptions;

use Throwable;

class FileContentException extends \Exception
{
    /**
     * @param string $message Message
     * @param int $code Exception code
     * @param \Throwable|null $previous The previous throwable used for the exception chaining.
     */
    public function __construct($message = 'Cannot load content of file', $code = 1, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
