<?php

namespace TurbineTest\Service\Sentry;

use Codeception\Test\Unit;
use Exception;
use Sentry\EventId;
use Sentry\State\HubInterface;
use Sentry\Tracing\Transaction;
use Sentry\Tracing\TransactionContext;
use TurbineKreuzberg\Service\Sentry\SentryDependencyProvider;
use TurbineKreuzberg\Service\Sentry\PackageNameServiceInterface;
use TurbineKreuzberg\Shared\Sentry\SentryConstants;

/**
 * Auto-generated group annotations
 *
 * @group VendorName
 * @group Client
 * @group PackageName
 * Add your own group annotations below this line
 * @property \VendorName\Service\PackageName\PackageNameServiceTester $tester
 */
class PackageNameServiceTest extends Unit
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tester->setupProjectConfigs();
    }
}
