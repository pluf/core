<?php
namespace Pluf\Test;

use PHPUnit\Framework\TestFailure;
use PHPUnit\TextUI\ResultPrinter;

/**
 * Generic ResultPrinter for PHPUnit tests of ATK4 repos.
 */
class PlufResultPrinter extends ResultPrinter
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