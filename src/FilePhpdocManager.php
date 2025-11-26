<?php

/**
 * Part of the arielenter/laravel-phpdoc-management package.
 *
 * PHP version 8+
 *
 * @category  Phpdoc
 * @package   Arielenter\Laravel\Phpdoc\Management
 * @author    Ariel Del Valle Lozano <arielmazatlan@gmail.com>
 * @copyright 2025 Ariel Del Valle Lozano
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public
 *            License (GPL) version 3
 * @link      https://github.com/arielenter/laravel-phpdoc-management
 */

namespace Arielenter\LaravelPhpdocManagement;

use Arielenter\ArrayToPhpdoc\DocBlockCreator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


/** Manages the phpdoc comments of a given file. */
class FilePhpdocManager
{
    protected string $file;
    protected string $originalContent;
    protected string $newContent;
    protected DocBlockCreator $docBlockCreator;
    protected DocBlockCreator $crtrWithIndent;
    protected int $defaultIndentWidth = 4;
    protected bool $defaultUseTab = false;
    protected int $defaultMaxLineWidth = 80;
    protected int $defaultMinLastColumnWidth = 25;

    /**
     * @var string $docBlockPattern Pattern used to identified doc blocks in
     *                              order to be replaced by new ones.
     */
    protected string $docBlockPattern = '[\t ]*\/\*\*.*((\n[\t ]*\*.*)*\n)?'
        . '[\t ]*\*\/[\t ]*';

    /**
     * @var string $methods Regex pattern that will be used to look for given
     *                      method.
     */
    protected string $methods = '/.*function :name\(/';

    /**
     * @var string $methods Regex pattern that will be used to look for given
     *                      property.
     */
    protected string $properties = '/^[\t ]*(public|protected|private)? *'
        . '[\\\a-zA-Z\|]* *:name *[=;]/m';

    /**
     * @var string $methods Regex pattern that will be used to look for given
     *                      constant.
     */
    protected string $constants = '/.*const [\\\a-zA-Z\|]* *:name *=/';

    /**
     * @param string $file Route of the file whose phpdoc wants to be manage.
     */
    public function __construct(string $file) {
        $this->file = $file;
        $this->docBlockCreator = new DocBlockCreator;
        $this->crtrWithIndent = new DocBlockCreator;
        $this->setMaxLineLength($this->defaultMaxLineWidth)
            ->setMinLastColumnWidth($this->defaultMinLastColumnWidth);
        $this->setIndentWidth($this->defaultIndentWidth)
            ->setUseTabForIndentation($this->defaultUseTab);
    }

    /**
     * Inserts or replaces the phpdoc of the file.
     *
     * @param array $phpdoc Doc blocks that will be inserted or replaced. Keys
     *                      ‘methods’, ‘properties’ and ‘constants’
     *                      should hold the doc blocks for every corresponding
     *                      element. For instance, if methods ‘update’ and
     *                      ‘delete’ exist, the value of the ‘methods’
     *                      key would be an array containing keys ‘update’
     *                      and ‘delete’, while their values should be their
     *                      corresponding doc blocks. Key ‘file’ would
     *                      contain the file’s doc block. Every other key
     *                      would be used for any line that starts with such
     *                      key, this could include the words ‘class’,
     *                      ‘interface’, ‘trail’, ‘final’,
     *                      ‘abstract’ or any other word you might need.
     *                      Lastly, every doc block should be given as an array
     *                      or a string in accordance to the
     *                      arielenter/array-to-phpdoc package.
     *
     * @return string The new content of the file.
     */
    public function update(array $phpdoc): string {
        $this->originalContent = $this->newContent = File::get($this->file);
        foreach ($phpdoc as $key => $value) {
            $value = (is_string($value)) ? [ $value ] : $value;
            switch ($key) {
            case 'file':
                $this->file($value);
                break;
            case 'methods': case 'properties': case 'constants':
                $this->multiple($key, $value);
                break;
            default:
                $this->lineStartingWithKey($key, $value);
            }
        }
        $this->writeToFileIfChangesWereMade();
        return $this->newContent;
    }

    /**
     * @param array $array
     */
    protected function file(array $array): void
    {
        $pattern = "/^.*\s*({$this->docBlockPattern})?\s*/";
        $docBlock = $this->docBlockCreator->fromArray($array);
        $replace = "<?php\n\n$docBlock\n\n";

        $this->updateContent($pattern, $replace);
    }

    protected function updateContent(string $pattern, string $replace): void
    {
        $this->newContent = preg_replace($pattern, $replace, $this->newContent);
    }

    /**
     * @param array $array
     */
    protected function multiple(string $type, array $array): void
    {
        foreach ($array as $name => $value) {
            $value = (is_string($value)) ? [ $value ] : $value;
            if ($type == 'properties' && !Str::startsWith($name, '$')) {
                $name = '$' . $name;
            }
            $name = preg_quote($name, '/');
            $error = $this->getFailToFindDefinitionErrorMsg($name, $type);
            $pattern = __($this->$type, compact('name'));
            $subject = $this->getSubject($pattern, $error);
            $docBlock = $this->crtrWithIndent->fromArray($value);
            $this->replaceDocBlock($docBlock, $subject);
        }
    }

    public function getFailToFindDefinitionErrorMsg(
        string $name, string $type
    ): string {
        $what = __(ServiceProvider::TRANSLATIONS . "::singular.$type");
        if ($type == 'properties') {
            $name = preg_replace(
                '/^' . preg_quote('\$', '/') . '/', '$', $name
            );
        }
        return $this->getErrorMsg(
            "fail_to_find_definition", compact('name', 'what')
        );
    }

    /**
     * @param array $replace
     */
    protected function getErrorMsg(string $transKey, array $replace): string
    {
        $replace['file'] = $this->file;
        return __(
            ServiceProvider::TRANSLATIONS . "::errors.$transKey", $replace
        );
    }

    protected function getSubject(string $pattern, string $error): string
    {
        if (!preg_match($pattern, $this->newContent, $matches)) {
            throw new \Exception($error);
        }
        return $matches[0];
    }

    /**
     * @param array $array
     */
    protected function replaceDocBlock(
        string $docBlock, string $subject
    ): void {
        $pregQuote = preg_quote($subject);
        $pattern = "/^({$this->docBlockPattern}\n)?$pregQuote/m";
        $replace = "$docBlock\n$subject";

        $this->updateContent($pattern, $replace);
    }

    /**
     * @param array $array
     */
    protected function lineStartingWithKey(string $key, array $array): void
    {
        $word = preg_quote($key);
        $error = $this->getFailToFindLineStartingWithErrorMsg($word);
        $subject = $this->getSubject("/^$word/m", $error);
        $docBlock = $this->docBlockCreator->fromArray($array);
        $this->replaceDocBlock($docBlock, $subject);
    }

    public function getFailToFindLineStartingWithErrorMsg(string $word): string
    {
        return $this->getErrorMsg(
            'fail_to_find_a_line_starting_with', compact('word')
        );
    }

    protected function writeToFileIfChangesWereMade(): void
    {
        if ($this->originalContent == $this->newContent) {
            return;
        }
        File::put($this->file, $this->newContent);
    }
    
    /**
     * Converts keys to their correct naming convention: camel for methods and
     * properties, and snake with uppercase for constants.
     *
     * A money sight is added to the begging of properties if they don’t have
     * it already. Methods and properties starting with an underscore are left
     * untouched. Properties starting with a money sight are also untouched.
     *
     * @param array $phpdoc Array holding the keys that will be converted.
     *
     * @return array The new array with the converted keys.
     */
    public function translateKeys(array $phpdoc): array
    {
        foreach (['properties', 'methods', 'constants'] as $type) {
            if (!isset($phpdoc[$type])) {
                continue;
            }
            $newArray = [];
            foreach ($phpdoc[$type] as $key => $value) {
                $newKey = $this->translateKey($key, $type);
                $newArray[$newKey] = $value;
            }
            $phpdoc[$type] = $newArray;
        }

        return $phpdoc;
    }

    protected function translateKey(string $key, string $type): string
    {
        switch ($type) {
        case 'methods':
            if (Str::startsWith($key, '_')) {
                return $key;
            }
            return Str::camel($key);
        case 'properties':
            if (Str::startsWith($key, '$')) {
                return $key;
            }
            if (Str::startsWith($key, '_')) {
                return '$' . $key;
            }
            return '$' . Str::camel($key);
        case 'constants':
            return Str::of($key)->snake()->upper()->toString();
        }
    }

    /**
     * Gives access to the ‘$docBlockCreator’ property individually.
     *
     * @return DocBlockCreator
     */
    public function getDocBlockCreator(): DocBlockCreator
    {
        return $this->docBlockCreator;        
    }

    /**
     * Gives access to the ‘$crtrWithIndent’ property individually.
     *
     * @return DocBlockCreator
     */
    public function getCrtrWithIndent(): DocBlockCreator
    {
        return $this->crtrWithIndent;
    }

    /**
     * Sets the max line width for both the doc block creators
     * ‘$docBlockCreator’ and ‘$crtrWithIndent’ properties, which are
     * used to create doc bocks from arrays.
     *
     * @return self
     */
    public function setMaxLineLength(int $length): self
    {
        $this->docBlockCreator->setMaxLineLength = $length;
        $this->crtrWithIndent->setMaxLineLength = $length;
        return $this;
    }

    /**
     * Sets the min last column width for both the doc block creators
     * ‘$docBlockCreator’ and ‘$crtrWithIndent’ properties, which are
     * used to create doc bocks from arrays.
     *
     * @return self
     */
    public function setMinLastColumnWidth(int $length): self
    {
        $this->docBlockCreator->setMinLastColumnWidth = $length;
        $this->crtrWithIndent->setMinLastColumnWidth = $length;
        return $this;
    }

    /**
     * Sets an indentation width for the doc block creator property
     * ‘$crtrWithIndent’ exclusively.
     *
     * @return self
     */
    public function setIndentWidth(int $width): self
    {
        $this->crtrWithIndent->setIndentWidth($width);
        return $this;
    }

    /**
     * Sets wether or not a tab is used instead of spaces for the doc block
     * creator property ‘$crtrWithIndent’ exclusively.
     *
     * @return self
     */
    public function setUseTabForIndentation(bool $bool): self
    {
        $this->crtrWithIndent->setUseTabForIndentation = $bool;
        return $this;
    }
}
