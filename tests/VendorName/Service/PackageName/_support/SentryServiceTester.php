<?php

namespace TurbineTest\Service\Sentry;

use Codeception\Actor;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class SentryServiceTester extends Actor
{
    use _generated\SentryServiceTesterActions;

    /**
     * @return void
     */
    public function setupProjectConfigs(): void
    {
        if (!defined('APPLICATION_STORE')) {
            define('APPLICATION_STORE', 'DE');
        }

        $this->setConfig('PROJECT_NAMESPACES', ['VendorName']);
    }
}
