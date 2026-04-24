<?php

namespace Fenom;

use PHPUnit\Framework\TestCase;

class StorageTraitTest extends TestCase
{
    private TemplaterStorage $fenom;

    protected function setUp(): void
    {
        $this->fenom = new TemplaterStorage(new Provider(__DIR__ . '/../tpl'));
    }

    public function testAssign(): void
    {
        $this->fenom->assign('var1', 'string1');
        $this->assertSame('string1', $this->fenom->getVars()['var1']);
    }

    public function testAssignByRef(): void
    {
        $var = 'original';
        $this->fenom->assignByRef('ref_var', $var);
        $var = 'modified';
        $this->assertSame('modified', $this->fenom->getVars()['ref_var']);
    }

    public function testAppend(): void
    {
        $this->fenom->append('arr', 'first');
        $this->fenom->append('arr', 'second');
        $this->assertSame(['first', 'second'], $this->fenom->getVars()['arr']);
    }

    public function testPrepend(): void
    {
        $this->fenom->prepend('arr', 'first');
        $this->fenom->prepend('arr', 'zero');
        $this->assertSame(['zero', 'first'], $this->fenom->getVars()['arr']);
    }

    public function testPrependAppendNonArray(): void
    {
        $this->fenom->assign('mixed', 'scalar');
        $this->fenom->append('mixed', 'appended');
        $this->fenom->prepend('mixed', 'prepended');
        $this->assertSame(['prepended', 'scalar', 'appended'], $this->fenom->getVars()['mixed']);
    }

    public function testAssignKey(): void
    {
        $this->fenom->assignKey('matrix', 'row1', 'value1');
        $this->assertSame('value1', $this->fenom->getVars()['matrix']['row1']);
    }

    public function testAssignKeyNonArray(): void
    {
        $this->fenom->assign('existing', 'string');
        $this->fenom->assignKey('existing', 'key', 'nested');
        $this->assertSame(['string', 'key' => 'nested'], $this->fenom->getVars()['existing']);
    }

    public function testAssignAllMerge(): void
    {
        $this->fenom->assign('one', 1);
        $this->fenom->assignAll(['two' => 2, 'three' => 3], true);
        $vars = $this->fenom->getVars();
        $this->assertSame(1, $vars['one']);
        $this->assertSame(2, $vars['two']);
        $this->assertSame(3, $vars['three']);
    }

    public function testAssignAllNoMerge(): void
    {
        $this->fenom->assign('one', 1);
        $this->fenom->assign('two', 2);
        $this->fenom->assignAll(['three' => 3], false);
        $vars = $this->fenom->getVars();
        $this->assertArrayNotHasKey('one', $vars);
        $this->assertSame(3, $vars['three']);
    }

    public function testResetVars(): void
    {
        $this->fenom->assign('var', 'value');
        $this->fenom->resetVars();
        $this->assertEmpty($this->fenom->getVars());
    }

    public function testFetch(): void
    {
        $this->fenom->assign('title', 'Test Title');
        $this->fenom->assign('items', ['Alpha', 'Beta']);
        $html = $this->fenom->fetch('test.tpl');
        $this->assertStringContainsString('<h1>Test Title</h1>', $html);
        $this->assertStringContainsString('<li>Alpha</li>', $html);
    }

    public function testFetchWithVars(): void
    {
        $this->fenom->assign('title', 'Stored Title');
        $html = $this->fenom->fetch('test.tpl', ['title' => 'Passed Title', 'items' => ['X']]);
        $this->assertStringContainsString('<h1>Passed Title</h1>', $html);
        $this->assertStringContainsString('<li>X</li>', $html);
    }

    public function testDisplay(): void
    {
        $this->fenom->assign('title', 'Display Title');
        $this->fenom->assign('items', ['One']);
        ob_start();
        $this->fenom->display('test.tpl');
        $output = ob_get_clean();
        $this->assertStringContainsString('<h1>Display Title</h1>', $output);
    }

    public function testPipe(): void
    {
        $result = '';
        $this->fenom->assign('title', 'Pipe Test');
        $this->fenom->assign('items', ['A', 'B']);
        $this->fenom->pipe('test.tpl', function ($buffer) use (&$result) {
            $result = $buffer;
            return strlen($buffer);
        });
        $this->assertStringContainsString('<h1>Pipe Test</h1>', $result);
    }

    public function testChaining(): void
    {
        $fenom = $this->fenom
            ->assign('a', 1)
            ->assign('b', 2)
            ->append('c', 3)
            ->prepend('c', 2)
            ->resetVars();
        $this->assertEmpty($fenom->getVars());
    }
}

class TemplaterStorage extends \Fenom
{
    use StorageTrait;
}