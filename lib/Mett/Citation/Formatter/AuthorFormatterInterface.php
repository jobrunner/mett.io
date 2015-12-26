<?php
namespace Mett\Citation\Formatter;

use Mett\Citation\Author;

interface AuthorFormatterInterface
{
    public function format(Author $authorToFormat);
}