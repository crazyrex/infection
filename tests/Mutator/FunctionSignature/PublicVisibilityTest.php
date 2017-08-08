<?php
/**
 * Copyright © 2017 Maks Rafalko
 *
 * License: https://opensource.org/licenses/BSD-3-Clause New BSD License
 */
declare(strict_types=1);

namespace Infection\Tests\Mutator\FunctionSignature;

use Infection\Mutator\FunctionSignature\PublicVisibility;
use Infection\Mutator\Mutator;
use Infection\Tests\Mutator\AbstractMutator;

class PublicVisibilityTest extends AbstractMutator
{
    public function test_changes_public_to_protected_method_visibility()
    {
        $code = <<<'CODE'
<?php

class Test
{
    public function foo(int $param, $test = 1): bool
    {
        echo 1;
        return false;
    }
}
CODE;
        $mutatedCode = $this->mutate($code);

        $expectedMutatedCode = <<<'CODE'
<?php

class Test
{
    protected function foo(int $param, $test = 1) : bool
    {
        echo 1;
        return false;
    }
}
CODE;

        $this->assertSame($expectedMutatedCode, $mutatedCode);
    }

    /**
     * @dataProvider blacklistedProvider
     */
    public function test_it_does_not_modify_blacklisted_functions(string $functionName)
    {
        $code = <<<"CODE"
<?php

class Test
{
    public function {$functionName}() {}
}
CODE;
        $mutatedCode = $this->mutate($code);

        $expectedMutatedCode = <<<"CODE"
<?php

class Test
{
    public function {$functionName}()
    {
    }
}
CODE;

        $this->assertSame($expectedMutatedCode, $mutatedCode);
    }

    public function test_it_replaces_visibility_if_not_set()
    {
        $code = <<<'CODE'
<?php

class Test
{
    function foo() {}
}
CODE;
        $mutatedCode = $this->mutate($code);

        $expectedMutatedCode = <<<'CODE'
<?php

class Test
{
    protected function foo()
    {
    }
}
CODE;

        $this->assertSame($expectedMutatedCode, $mutatedCode);
    }

    public function blacklistedProvider()
    {
        return [
            ['__construct'],
            ['__invoke'],
            ['__call'],
            ['__callStatic'],
            ['__get'],
            ['__set'],
            ['__isset'],
            ['__unset'],
            ['__toString'],
            ['__debugInfo'],
        ];
    }

    protected function getMutator(): Mutator
    {
        return new PublicVisibility();
    }
}