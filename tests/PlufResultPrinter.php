<?php
namespace Pluf\Test;

use PHPUnit\Framework\TestFailure;

/**
 * Generic ResultPrinter for PHPUnit tests of ATK4 repos.
 */
class PlufPlufResultPrinter extends PlufResultPrinter
{

    protected function printDefectTrace(TestFailure $defect): void
    {
        $e = $defect->thrownException();
        if (! $e instanceof ExceptionWrapper) {
            parent::printDefectTrace($defect);

            return;
        }
        $this->write((string) $e);

        $p = $e->getPrevious();

        if ($p instanceof \Pluf\Exception) {
            $this->write($p->getColorfulText());
        }
    }
}