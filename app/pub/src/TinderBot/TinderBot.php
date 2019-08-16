<?php
/**
 * TinderBot
 *
 * @copyright Copyright © 2019 InComm. All rights reserved.
 * @author    ydmytrunets@incomm.com
 */

namespace App\TinderBot;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

/**
 * Class TinderBot
 */
class TinderBot
{
    private const URL_PROFILE = 'https://tinder.com/';

    /** @var RemoteWebDriver  */
    private $driver;

    /**
     * @var array Format ['login' => '', 'password' => '']
     */
    private $cred;

    /**
     * Instagram constructor.
     *
     * @param RemoteWebDriver $driver
     * @param array           $cred
     */
    public function __construct(RemoteWebDriver $driver = null, array $cred = null)
    {
        $this->driver = $driver;
        $this->cred = $cred;
    }

    /**
     * Open user profile
     *
     * @return bool
     */
    public function openUserProfile(): bool
    {
        $this->driver->navigate()->to(static::URL_PROFILE);

        return true;
    }


    public function login2()
    {
        $this->driver->navigate()->to(static::URL_PROFILE);
        sleep(2);
        //*[@id="modal-manager"]/div/div/div/div/div/div[1]/button
    }

    /**
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function login()
    {
        $this->driver->navigate()->to(static::URL_PROFILE);

        $this->driver->wait(5, 2000)->until(
            WebDriverExpectedCondition::titleContains('Tinder')
        );

        $tinderWindowId = $this->driver->getWindowHandle();
        sleep(2);
        // click login using facebook
        $this->driver->findElement(WebDriverBy::xpath('//*[@id="modal-manager"]/div/div/div/div/div/div[1]/button'))->sendKeys('1')->click();
        sleep(2);
        $windowHandles = $this->driver->getWindowHandles();
        $facebookWindowId = end($windowHandles);
        $this->driver->switchTo()->window($facebookWindowId);

        $this->driver->findElement(WebDriverBy::xpath('//*[@id="email"]'))->sendKeys("<my login>")->click();
        $this->driver->findElement(WebDriverBy::xpath("//input[@id='pass']"))->sendKeys("my password")->click();
        $this->driver->findElement(WebDriverBy::xpath("//input[@id='u_0_0']"))->sendKeys('1')->click();

        $this->driver->switchTo()->window($tinderWindowId);
        sleep(3);

        $this->driver->executeScript("localStorage.setItem('homeScreenTooltipShown', true)");

        return true;
    }

    /**
     */
    public function addMessage()
    {
        // click on 'Message' tab
        $this->driver->findElement(WebDriverBy::xpath('//*[@id="content"]/span/div/div[1]/div/aside/nav/div/div/div/div[1]/div/div[3]/span'))->click();

        // find chats
        $chats = $this->driver->findElements(WebDriverBy::className("messageListItem"));
        foreach ($chats as $chat) {
            $chat->click();

            // messages blocks
            $messages = $this->driver->findElements(WebDriverBy::xpath('//*[@id="content"]/span/div/div[1]/div/main/div[1]/div/div/div/div[1]/div/div/div/span/div'));
            foreach ($messages as $message) {
                try {
                    // http://software-testing.ru/forum/index.php?/topic/27980-problema-s-parentfindelement/
                    $x = $message->findElement(WebDriverBy::xpath('//div[2]'));

                    if (strpos($x->getAttribute('class'), 'Bgc($c-bg-blue)') === false) {
                        $this->log('Her: ' . $message->getText());
                    } else {
                        $this->log('My: ' . $message->getText());
                    }
                } catch (\Exception $e) {
                    echo "\n Error: " . $e->getMessage();
                }
            }

//        foreach (file('routine/r1.txt') as $line) {
//            $line = trim($line);
//
//            try {
//                $driver->findElement(WebDriverBy::className('sendMessageForm '))
//                    ->findElement(WebDriverBy::tagName('textarea'))
//                    ->sendKeys($line);
//            } catch (\Exception $e) {
//                $x = $e;
//            }
//
//            sleep(5);
////            $driver->findElement(WebDriverBy::xpath($elmId))->clear();
//        }
//            $this->driver->findElement(WebDriverBy::className('sendMessageForm '))
//                ->findElement(WebDriverBy::tagName('textarea'))
//                ->sendKeys('Test');
        }
    }

    /**
     * @param $message
     */
    private function log($message)
    {
        $message = date('H:i:s') . " - $message - ".PHP_EOL;
        print($message);
        flush();
        ob_flush();
    }

    /**
     * Like
     */
    public function like()
    {
        $this->driver->findElement(WebDriverBy::xpath('//*[@id="content"]/span/div/div[1]/div/main/div[1]/div/div/div[1]/div/div[2]/button[3]'))->click();

        try {
//            $itsAMatch = $this->driver->findElement(WebDriverBy::xpath('//*[contains(@class,"itsAMatch")]'));
            $this->driver->findElement(WebDriverBy::xpath('//*[contains(@class,"itsAMatch")]/button/span/span'))->sendKeys('1')->click();
        } catch (\Exception $e) { }

    }
}
