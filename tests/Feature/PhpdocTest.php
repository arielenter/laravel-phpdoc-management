<?php

namespace Tests\Feature;

use Arielenter\LaravelPhpdocManagement\FilePhpdocManager;
use Arielenter\LaravelPhpdocManagement\ServiceProvider;
use Arielenter\LaravelTestTranslations\TestTranslations;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PhpdocTest extends TestCase
{
    use TestTranslations;
    
    #[Test]
    public function phpdoc_update(): void
    {
        $filePhpdocManager = new FilePhpdocManager('src/FilePhpdocManager.php');
        $trans = $this->tryGetTrans(
            ServiceProvider::TRANSLATIONS . '::phpdoc', locale: 'en'
        );
        $phpdoc = $filePhpdocManager->translateKeys($trans);
        $filePhpdocManager->update($phpdoc);
    }
}
