<?php

namespace Tests\Feature;

use Arielenter\ArrayToPhpdoc\DocBlockCreator;
use Arielenter\LaravelCodeSnippets\CreateReadme;
use Arielenter\LaravelPhpdocManagement\ServiceProvider;
use Arielenter\LaravelTestTranslations\TestTranslations;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReadmeTest extends TestCase
{
    use TestTranslations;
    use CreateReadme;

    public array $availableLocales = [ 'en' => 'English', 'es' => 'Español' ];
    public DocBlockCreator $phpdocGenerator;
    public string $content = "<?php\n\n" . "namespace Tests;\n\n"
        . "class :class_name\n{\n"
        . '    public string $:property_one_name;' . "\n\n"
        . '    public array $:property_two_name;' . "\n\n"
        . "    public const string :constant_name = 'xyz';\n\n"
        . '    public function :method_one_name(array $:argument_one_name, '
        . 'string|array $:argument_two_name): string' . "\n    {\n"
        . "        return 'I’m a string';\n    }\n\n"
        . '    public function :method_two_name(int $:argument_three_name'
        . "): void\n    {\n        return;\n    }\n}";

    public function setUp(): void
    {
        $this->phpdocGenerator ??= new DocBlockCreator;

        parent::setUp();
    }
    
    #[Test]
    public function readme_file(): void
    {
        $locales = $this->availableLocales;
        foreach ($locales as $locale => $name) {
            $replaces = [
                [ 'class_desc' => $this->getPhpdocTrans('class', $locale) ],
                $this->methodsPhpdocs($locale),
                $this->getTrans('readme', $locale),
                $this->exampleFile($locale),
                $this->arrayTranslarionExample($locale),
                [ 'locale_links' => $this->getLocaleLinks($locales, $locale) ]
            ];
            $this->createReadmeFromTemplate(
                'resources/README.template.md', $locale, ...$replaces
            );
        }
    }

    public function getPhpdocTrans(string $key, string $locale): string|array
    {
        return $this->getTrans("phpdoc.$key", $locale);
    }

    public function getTrans(
        string $transKey, string $locale, array $replace = []
    ): string|array {
        return $this->tryGetTrans(
            ServiceProvider::TRANSLATIONS . "::$transKey", $replace, $locale
        );
    }

    /**
     * @return array
     */
    public function methodsPhpdocs(string $locale): array
    {
        return array_map(
            fn($phpdoc) => $this->phpdocGenerator->fromArray($phpdoc),
            $this->getPhpdocTrans('methods', $locale)
        );
    }
    
    /**
     * @return void
     */
    public function exampleFile(string $locale): array
    {
        $exampleFile = $this->getTrans('test.array_values.file', $locale);
        $originalContent = $this->getContent($locale);
        File::put($exampleFile, $originalContent);

        $codeContent = $this->getUpdateCode($locale);
        $exampleCodeFile = 'tests/update.php';
        File::put($exampleCodeFile, $codeContent);

        include $exampleCodeFile;
        $newContent = File::get($exampleFile);

        File::delete($exampleFile);
        File::delete($exampleCodeFile);

        return [
            'example_file_original_content' => $originalContent,
            'code_example' => $codeContent,
            'example_file_new_content' => $newContent
        ];
    }

    public function getContent(string $locale): string
    {
        $content = $this->content;
        $replace = $this->getTrans('test.content_placeholders', $locale);
        return $this->checkReplaceAndPlaceholders($content, $replace);
    }

    public function checkReplaceAndPlaceholders(string $content, array $replace)
    {
        $discarted = $this->assertAllReplaceKeysExistAndReturnDiscarted(
            $content, $replace
        );
        $this->assertTransLacksPlaceholders($discarted);

        return __($content, $replace);
    }

    public function getUpdateCode(string $locale): string
    {
        $r1 = $this->getTrans('test.update_values', $locale);
        $r2 = $this->getTrans('test.content_placeholders', $locale);
        unset($r2['class_name']);
        $replace = array_merge($r1, $r2);

        $content = File::get('resources/update.template.php');

        return $this->checkReplaceAndPlaceholders($content, $replace);
    }

    public function arrayTranslarionExample(string $locale): array
    {
        $template = File::get('resources/array.template.php');
        $replace = $this->getTrans('test.array_values', $locale);
        $content = $this->checkReplaceAndPlaceholders($template, $replace);
        $exampleFile = 'tests/array.php';
        File::put($exampleFile, $content);

        include $exampleFile;
        File::delete($exampleFile);

        $varName = $replace['new'];
        return [
            'array_example' => $content,
            'var_dump' => print_r($$varName, true)
        ];
    }
}
