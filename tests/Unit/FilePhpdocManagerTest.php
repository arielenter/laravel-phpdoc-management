<?php

namespace Tests\Unit;

use Arielenter\ArrayToPhpdoc\DocBlockCreator;
use Arielenter\LaravelPhpdocManagement\FilePhpdocManager;
use Arielenter\LaravelPhpdocManagement\ServiceProvider;
use Arielenter\LaravelTestTranslations\TestTranslations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FilePhpdocManagerTest extends TestCase
{
    use WithFaker;
    use TestTranslations;
    
    public string $file = 'tests/ExampleClass.php';
    public array $content = [
        'start' => "<?php\n\n", 'namespace' => "namespace Tests;\n\n",
        'class' => "class ExampleClass\n{\n",
        'property_one' => '    public string|array $propertyOne;' . "\n\n",
        'property_two' => '    protected array|\Object $propertyTwo = [];'
            . "\n\n",
        'constant' => "    public const string MY_CONSTANT = 'example';\n\n",
        'method_one' => '    public function methodOne(string $text, '
            . 'int $number): string' . "\n    {\n        "
            . "return 'I'm a string';\n    }\n\n",
        'method_two' => '    protected function methodTwo(array $arr): void'
            . "\n    {\n        return;\n    }\n}"
    ];

    public function setUp(): void
    {
        parent::setUp();
        App::setLocale('en');
    }

    public function tearDown(): void
    {
        File::delete($this->file);
        parent::tearDown();
    }

    #[Test]
    public function insert_doc_blocks_if_they_do_not_exist_already(): void
    {
        File::put($this->file, join('', $this->content));
        $examplePhpdoc = $this->phpdoc();
        $expected = $this->addPhpdocToContent($examplePhpdoc);
        (new FilePhpdocManager($this->file))->update($examplePhpdoc);
        $this->assertStringEqualsFile($this->file, $expected);
    }
    
    /**
     * @return array
     */
    public function phpdoc(): array
    {
        return [
            'file' => $this->filePhpdoc(),
            'class' => [ $this->faker->sentence() ],
            'properties' => $this->propertiesPhpdoc(),
            'constants' => $this->constantPhpdoc(),
            'methods' => $this->methodsPhpdocs()
        ];
    }

    public function filePhpdoc(): array
    {
        return [
            $this->faker->text(),
            [
                [
                    '@author',
                    "{$this->faker->firstName} {$this->faker->lastName} "
                    . "<{$this->faker->email()}>"
                ],
                [ '@link', $this->faker->url() ],
                [
                    '@license', 'http://www.gnu.org/licenses/gpl-3.0.html GNU '
                    . 'General Public License (GPL) version 3'
                ]
            ]
        ];
    }

    public function propertiesPhpdoc(): array
    {
        return [
            '$propertyOne' => [
                [
                    '@var', 'string|array', '$propertyOne',
                    $this->faker->sentence()
                ]
            ],
            '$propertyTwo' => [
                [
                    '@var', 'array|\Object', '$propertyTwo',
                    $this->faker->sentence()
                ]
            ]
        ];
    }

    public function constantPhpdoc(): array
    {
        return [
            'MY_CONSTANT' => [
                [ '@var', 'string', 'MY_CONSTANT', $this->faker->sentence() ]
            ]
        ];
    }

    public function methodsPhpdocs(): array
    {
        return [
            'methodOne' => [
                $this->faker->sentence(), $this->faker->text(),
                [
                    [ '@param', 'string', '$text', $this->faker->sentence() ],
                    [ '@param', 'int', '$number', $this->faker->text() ],
                ],
                [ '@return', 'string', $this->faker->sentence() ]
            ],
            'methodTwo' => [
                $this->faker->sentence(),
                [ '@param', 'array', '$arr', $this->faker->text() ],
                [ '@return', 'void' ]
            ]
        ];
    }

    #[Test]
    public function updates_phpdoc_if_it_already_exists(): void
    {
        File::put($this->file, $this->addPhpdocToContent($this->phpdoc()));
        
        $secondPhpdoc = $this->phpdoc();
        $expected = $this->addPhpdocToContent($secondPhpdoc);
        (new FilePhpdocManager($this->file))->update($secondPhpdoc);
        $this->assertStringEqualsFile($this->file, $expected);
    }

    #[Test]
    public function properties_can_be_given_without_money_sight(): void
    {
        File::put($this->file, join('', $this->content));
        
        $phpdoc = $this->phpdoc();
        [$v1, $v2] = array_values($phpdoc['properties']);
        $phpdoc['properties'] = [ 'propertyOne' => $v1, 'propertyTwo' => $v2 ];
        $expected = $this->addPhpdocToContent($phpdoc);
        (new FilePhpdocManager($this->file))->update($phpdoc);
        $this->assertStringEqualsFile($this->file, $expected);
    }

    // #[Test]
    // public function a_diferent_width_can_be_given_for_indentation(): void
    // {
    //     $this->exampleDiferentWidthOrTab();
    // }

    // public function exampleDiferentWidthOrTab(bool $useTab = false): void
    // {
    //     $indentWidth = 6;
    //     $indentString = str_repeat(' ', $indentWidth);
    //     if ($useTab) {
    //         $indentString = "\t";
    //     }
        
    //     $content = $this->replaceContentOriginalIndentation($indentString);
    //     File::put($this->file, join('', $content));
    //     $phpdoc = $this->phpdoc();
    //     $expected = $this->addPhpdocToContent(
    //         $phpdoc, $content, $indentWidth, $useTab
    //     );
    //     (new FilePhpdocManager($this->file))->update(
    //         $phpdoc, $indentWidth, $useTab
    //     );
    //     $this->assertStringEqualsFile($this->file, $expected);
    // }

    // public function replaceContentOriginalIndentation(string $newIndent): array
    // {
    //     $newContent = [];
    //     foreach ($this->content as $key => $val) {
    //         $newContent[$key] = preg_replace('/ {4}/', $newIndent, $val);
    //     }
    //     return $newContent;
    // }

    // #[Test]
    // public function a_tab_can_be_used_for_indent_insted_of_spaces(): void
    // {
    //     $this->exampleDiferentWidthOrTab(true);
    // }

    #[Test]
    public function if_file_phpdoc_is_the_same_the_file_is_not_rewritten(): void
    {
        $phpdoc = $this->phpdoc();
        File::put($this->file, $this->addPhpdocToContent($phpdoc));
        $firstTimestamp = File::lastModified($this->file);
        
        sleep(1);
        (new FilePhpdocManager($this->file))->update($phpdoc);
        $secondTimestamp = File::lastModified($this->file);

        $this->assertEquals($firstTimestamp, $secondTimestamp);
    }

    #[Test]
    public function single_desc_doc_block_can_be_a_string(): void
    {
        $content = "<?php\n\nnamespace Test;\n\n:doc" . "class SomeName {\n}";
        File::put($this->file, __($content, [ 'doc' => '' ]));
        $desc = 'Class description.';
        $expected = __($content, [ 'doc' => "/** $desc */\n" ]);
        
        $singleDesc = [ 'class' => $desc ];
        (new FilePhpdocManager($this->file))->update($singleDesc);
        $this->assertStringEqualsFile($this->file, $expected);        
    }

    #[Test]
    public function a_doc_block_can_be_given_for_any_line_starting_with_word(
    ): void {
        $this->testMultiScenarios();
        $this->testMultiScenarios("/** Original short description. */\n");
    }

    public function testMultiScenarios(string $originalDocBlock = ''): void
    {
        $template = "<?php\n\nnamespace Test;\n\n:doc:start SomeName {\n}";
        $wordStart = [
            'class', 'interface', 'trait', 'abstract' => 'abstract class',
            'final' => 'final class'
        ];
        $filePhpdocManager = new FilePhpdocManager($this->file);
        $docBlockGen = new DocBlockCreator;
        foreach ($wordStart as $key => $start) {
            $word = (is_string($key)) ? $key : $start;
            $doc = $originalDocBlock;
            File::put($this->file, __($template, compact('start', 'doc')));
            
            $shortDesc = $this->faker->sentence();
            $filePhpdocManager->update([ $word => [ $shortDesc ] ]);
            
            $doc = $docBlockGen->fromArray([ $shortDesc ]) . "\n";
            $expected = __($template, compact('start', 'doc'));
            
            $this->assertStringEqualsFile($this->file, $expected);
        }
    }

    #[Test]
    public function a_key_translator_is_available(): void
    {
        $example = [
            'methods' => [ 'method_one' => 'a' ],
            'properties' => [ 'property_one' => 'b' ],
            'constants' => [ 'constant_one' => 'c' ]
        ];
        $expected = [
            'methods' => [ 'methodOne' => 'a' ],
            'properties' => [ '$propertyOne' => 'b' ],
            'constants' => [ 'CONSTANT_ONE' => 'c' ]
        ];
        $actual = (new FilePhpdocManager($this->file))->translateKeys($example);
        $this->assertEquals($expected, $actual);
    }

    #[Test]
    public function names_starting_with_an_uderscore_are_left_untouched(): void
    {
        $example = [
            'methods' => [ '__untouched_name' => 'Desc.' ],
            'properties' => [
                '_untouched_name' => 'Desc.' , '$also_untouched' => 'Desc.'
            ]
        ];
        $actual = (new FilePhpdocManager($this->file))->translateKeys($example);
        $expected = $example;
        $expected['properties']['$_untouched_name'] = 'Desc.';
        unset($expected['properties']['_untouched_name']);
        $this->assertEquals($expected, $actual);
    }

    #[Test]
    public function fails_if_a_line_starting_with_a_given_word_cant_be_found(
    ): void
    {
        $content = "<?php\n\nnamespace Test;\n\nclass SomeName {\n}";
        File::put($this->file, $content);
        $notFoundExamples = [
            'interface', 'trait', 'abstract', 'SomeName', $this->faker->word()
        ];
        foreach ($notFoundExamples as $word) {
            $phpdoc = [ $word => 'Example short description.' ];
            $this->assertThrows(
                fn() => (new FilePhpdocManager($this->file))->update($phpdoc),
                \Exception::class,
                $this->getErrorMsg(
                    'fail_to_find_a_line_starting_with', compact('word')
                )
            );
        }
    }

    public function getErrorMsg(string $errorKey, array $replace): string
    {
        $replace['file'] = $this->file;
        return $this->getTranslation("errors.$errorKey", $replace);
    }

    public function getTranslation(string $transKey, array $replace = []): string
    {
        return $this->tryGetTrans(
            ServiceProvider::TRANSLATIONS . "::$transKey", $replace
        );
    }

    #[Test]
    public function fails_if_a_definition_for_a_given_element_cant_be_found(
    ): void {
        File::put($this->file, join('', $this->content));
        $docBlocks = [ 'random_name' => 'Some short description.' ];
        $filePhpdocManager = new FilePhpdocManager($this->file);
        
        foreach ([ 'methods', 'properties', 'constants' ] as $type) {
            $phpdoc = [ $type => $docBlocks ];
            $trans = $filePhpdocManager->translateKeys($phpdoc);
            $what = $this->getTranslation("singular.$type");
            $name = array_key_first($trans[$type]);

            $this->assertThrows(
                fn() => $filePhpdocManager->update($trans),
                \Exception::class,
                $this->getErrorMsg(
                    'fail_to_find_definition', compact('name', 'what')
                )
            );
        }
    }

    #[Test]
    public function spanish_trans(): void
    {
        App::setLocale('es');
        $this->fails_if_a_line_starting_with_a_given_word_cant_be_found();
        $this->fails_if_a_definition_for_a_given_element_cant_be_found();
    }
}
