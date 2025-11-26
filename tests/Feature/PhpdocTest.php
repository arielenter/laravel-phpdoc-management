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
        $phpdoc = $this->tryGetTrans(
            ServiceProvider::TRANSLATIONS . '::phpdoc', locale: 'en'
        );
        $trans = $filePhpdocManager->translateKeys($phpdoc);
        $filePhpdocManager->update($trans);
    }
}
