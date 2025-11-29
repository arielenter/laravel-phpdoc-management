<?php

namespace Tests\Feature;

use Facades\Arielenter\LaravelCodeSnippets\ComposerJson;
use Arielenter\LaravelPhpdocManagement\ServiceProvider;
use Arielenter\LaravelTestTranslations\TestTranslations;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ComposerJsonTest extends TestCase
{
    use TestTranslations;
    
    #[Test]
    public function composer_json(): void
    {
        $description = $this->tryGetTrans(
            ServiceProvider::TRANSLATIONS . '::phpdoc.class', locale: 'en'
        );

        ComposerJson::editKey('description', $description);
    }

}
