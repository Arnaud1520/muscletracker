<?php
// src/Document/YourDocument.php
namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class YourDocument
{
    #[MongoDB\Id]
    private string $id;
}
