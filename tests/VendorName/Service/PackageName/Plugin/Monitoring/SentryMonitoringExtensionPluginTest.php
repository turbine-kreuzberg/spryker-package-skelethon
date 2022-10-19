<?php

namespace TurbineTest\Service\Sentry\Plugin\Monitoring;

use Codeception\Test\Unit;
use Exception;
use TurbineKreuzberg\Service\Sentry\Plugin\Monitoring\SentryMonitoringExtensionPlugin;
use TurbineKreuzberg\Service\Sentry\SentryService;
use TurbineKreuzberg\Service\Sentry\PackageNameServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group VendorName
 * @group Client
 * @group PackageName
 * @group Plugin
 * @group Monitoring
 * @group SentryMonitoringExtensionPlugin
 * Add your own group annotations below this line
 * @property \VendorName\Service\PackageName\SentryServiceTester $tester
 */
class SentryMonitoringExtensionPluginTest extends Unit
{
    /**
     * @var \TurbineKreuzberg\Service\Sentry\Plugin\Monitoring\SentryMonitoringExtensionPlugin
     */
    protected $plugin;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\TurbineKreuzberg\Service\Sentry\SentryService
     */
    protected $serviceMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceMock = $this->createServiceMock();
        $this->plugin = $this->createPlugin($this->serviceMock);
    }

    /**
     * @return void
     */
    public function testSetErrorCallsCaptureExceptionOnService(): void
    {
        $exception = new Exception();

        $this->serviceMock
            ->expects($this->once())
            ->method('captureException')
            ->with($exception);

        $this->plugin->setError('', $exception);
    }

    /**
     * @param \TurbineKreuzberg\Service\Sentry\PackageNameServiceInterface $sentryService
     *
     * @return \TurbineKreuzberg\Service\Sentry\Plugin\Monitoring\SentryMonitoringExtensionPlugin
     */
    private function createPlugin(PackageNameServiceInterface $sentryService): SentryMonitoringExtensionPlugin
    {
        $plugin = new SentryMonitoringExtensionPlugin();
        $plugin->setService($sentryService);

        return $plugin;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\TurbineKreuzberg\Service\Sentry\SentryService
     */
    private function createServiceMock(): SentryService
    {
        return $this->createMock(SentryService::class);
    }
}
