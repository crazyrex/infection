<?php
/**
 * Copyright © 2017-2018 Maks Rafalko
 *
 * License: https://opensource.org/licenses/BSD-3-Clause New BSD License
 */

declare(strict_types=1);

namespace Infection\Mutator\Cast;

use PhpParser\Node;

/**
 * @internal
 */
final class CastString extends AbstractCastMutator
{
    protected function mutatesNode(Node $node): bool
    {
        return $node instanceof Node\Expr\Cast\String_;
    }
}
