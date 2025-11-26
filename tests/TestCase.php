<?php

namespace Tests;

use Arielenter\ArrayToPhpdoc\DocBlockCreator;
use Arielenter\LaravelCodeSnippets\ServiceProvider as CSServiceProvider;
use Arielenter\LaravelPhpdocManagement\ServiceProvider;
use Arielenter\LaravelTestTranslations\ServiceProvider as TServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class, TServiceProvider::class,
            CSServiceProvider::class
        ];
    }
    
    /**
     * @param array $phpdoc
     */
    public function addPhpdocToContent(array $phpdoc, ?array $content = null, int $indentWidth = 4, bool $useTab = false): string {
        $content ??= $this->content;
        $docBlocks = $this->getDocBlocks($phpdoc, $indentWidth, $useTab);
        $start = [
            $content['start'] . $docBlocks[0] . "\n\n" . $content['namespace']
        ];
        unset($content['start'], $content['namespace'], $docBlocks[0]);
        $end = array_map(fn($a, $b) => "$a\n$b", $docBlocks, $content);
        $expected = array_merge($start, $end);
        return join('', $expected);
    }
    
    /**
     * @param array $phpdoc
     * @return array
     */
    public function getDocBlocks(
        array $phpdoc, int $indentWidth, bool $useTab
    ): array {
        $phpdoc = array_values($phpdoc);
        $docBlockGen = new DocBlockCreator;
        $genWithIndent = (new DocBlockCreator)->setIndentWidth($indentWidth)
            ->setUseTabForIndentation($useTab);

        $docBlocks = [];
        foreach ($phpdoc as $key => $lv1) {
            $lv1 = (is_string($lv1)) ? [ $lv1 ] : $lv1;
            if ($key < 2) {
                $docBlocks[] = $docBlockGen->fromArray($lv1);
                continue;
            }
            foreach ($lv1 as $lv2) {
                $docBlocks[] = $genWithIndent->fromArray($lv2);
            }
        }

        return $docBlocks;
    }
}
