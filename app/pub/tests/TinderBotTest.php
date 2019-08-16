<?php
/**
 * TinderBotTest
 */

namespace App\Tests;

use App\TinderBot\TinderBot;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions;

/**
 * Class TinderBotTest
 */
class TinderBotTest extends \PHPUnit\Framework\TestCase
{
    private static $driver;
    private static $cred;

    public static function setUpBeforeClass()
    {

        $options = new ChromeOptions();
        $options->addArguments(array('--disable-notifications'));

        $caps = DesiredCapabilities::chrome();
        $caps->setCapability(ChromeOptions::CAPABILITY, $options);
        $host = 'http://localhost:4444/wd/hub';
        self::$driver = RemoteWebDriver::create($host, $caps);


        // TODO: extract into file
        self::$cred['username'] = 'my login';
        self::$cred['password'] = 'my password';
    }

    /**
     * @var TinderBot
     */
    private $app;

    protected function setUp()
    {
        $this->app = new TinderBot(self::$driver, self::$cred);
    }

    /**
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function testLogin()
    {
        $this->assertEquals(true, $this->app->login());
    }

    /**
     * @depends testLogin
     */
    public function testAddMessage()
    {
        $this->assertEquals(true, $this->app->addMessage(0));
    }

    /**
     * @depends testLogin
     */
    public function testLikeAll()
    {
        while (1) {
            $this->app->like();
            sleep(2);
        }
    }
}
