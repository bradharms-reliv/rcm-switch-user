<?php

namespace Rcm\SwitchUser\Test\Service;

require_once(__DIR__ . '/../autoload.php');

use Rcm\SwitchUser\Model\SuProperty;
use Rcm\SwitchUser\Restriction\Result;
use Rcm\SwitchUser\Service\SwitchUserLogService;
use Rcm\SwitchUser\Service\SwitchUserService;
use Rcm\SwitchUser\Switcher\Switcher;
use RcmUser\User\Entity\User;

class SwitchUserServiceTest extends \PHPUnit_Framework_TestCase
{
    public $rcmUserServiceMock;

    public $configMock
        = [
            'Rcm\\SwitchUser' => [
                'acl' => [
                    'resourceId' => 'switchuser',
                    'privilege' => 'execute',
                    'providerId' => 'Rcm\SwitchUser\Acl\ResourceProvider'
                ],
            ],
        ];

    /**
     * getUnit HAPPY PATH
     *
     * @return SwitchUserService
     */
    public function getUnit()
    {
        /** @var \RcmUser\User\Entity\User $rcmUserMock */
        $this->rcmUserMock
            = $this->getMockBuilder('RcmUser\User\Entity\User')
            ->disableOriginalConstructor()
            ->getMock();

        $this->rcmUSerAuthenticationServiceMock = $this->getMockBuilder(
            'RcmUser\Authentication\Service\UserAuthenticationService'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->rcmUSerAuthenticationServiceMock->method('setIdentity')
            ->will($this->returnValue(null));

        /** @var \RcmUser\Service\RcmUserService $this->rcmUserServiceMock */
        $this->rcmUserServiceMock = $this->getMockBuilder(
            'RcmUser\Service\RcmUserService'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->rcmUserServiceMock->method('getCurrentUser')
            ->will($this->returnValue($this->rcmUserMock));
        $this->rcmUserServiceMock->method('getUserByUsername')
            ->will($this->returnValue($this->rcmUserMock));
        $this->rcmUserServiceMock->method('getUserAuthService')
            ->will($this->returnValue($this->rcmUSerAuthenticationServiceMock));

        /** @var Result $this->restrictionResultMock */
        $this->restrictionResultMock = $this->getMockBuilder(
            'Rcm\SwitchUser\Restriction\Result'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->restrictionResultMock->method('isAllowed')
            ->will($this->returnValue(true));
        $this->restrictionResultMock->method('getMessage')
            ->will($this->returnValue(''));

        /** @var \Rcm\SwitchUser\Restriction\Restriction $this->restrictionMock */
        $this->restrictionMock = $this->getMockBuilder(
            'Rcm\SwitchUser\Restriction\Restriction'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->restrictionMock->method('allowed')
            ->will($this->returnValue($this->restrictionResultMock));

        $this->switcherResult = $this->getMockBuilder(
            'Rcm\SwitchUser\Result'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->switcherResult->method('isSuccess')
            ->will($this->returnValue(true));
        $this->switcherResult->method('getMessage')
            ->will($this->returnValue(''));


        /** @var Switcher switcherMock */
        $this->switcherMock = $this->getMockBuilder(
            'Rcm\SwitchUser\Switcher\Switcher'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->switcherMock->method('getName')
            ->will($this->returnValue('MOCKNAME'));
        $this->switcherMock->method('switchTo')
            ->will($this->returnValue($this->switcherResult));
        $this->switcherMock->method('switchBack')
            ->will($this->returnValue($this->switcherResult));

        /** @var SwitchUserLogService switchUserLogService */
        $this->switchUserLogService = $this->getMockBuilder(
            'Rcm\SwitchUser\Service\SwitchUserLogService'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $unit = new SwitchUserService(
            $this->configMock,
            $this->rcmUserServiceMock,
            $this->restrictionMock,
            $this->switcherMock,
            $this->switchUserLogService
        );

        return $unit;
    }

    /**
     * getUnit HAPPY PATH
     *
     * @return SwitchUserService
     */
    public function getUnitIsImpersonating()
    {
        /** @var \RcmUser\User\Entity\User $rcmUserMock */
        $this->rcmUserMock
            = $this->getMockBuilder('RcmUser\User\Entity\User')
            ->disableOriginalConstructor()
            ->getMock();
        $suPropertyMock = new SuProperty(new User('321'));
        $this->rcmUserMock->method('getProperty')
            ->will($this->returnValue($suPropertyMock));

        $this->rcmUSerAuthenticationServiceMock = $this->getMockBuilder(
            'RcmUser\Authentication\Service\UserAuthenticationService'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->rcmUSerAuthenticationServiceMock->method('setIdentity')
            ->will($this->returnValue(null));

        /** @var \RcmUser\Service\RcmUserService $this->rcmUserServiceMock */
        $this->rcmUserServiceMock = $this->getMockBuilder(
            'RcmUser\Service\RcmUserService'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->rcmUserServiceMock->method('getCurrentUser')
            ->will($this->returnValue($this->rcmUserMock));
        $this->rcmUserServiceMock->method('getUserByUsername')
            ->will($this->returnValue($this->rcmUserMock));
        $this->rcmUserServiceMock->method('getUserAuthService')
            ->will($this->returnValue($this->rcmUSerAuthenticationServiceMock));

        /** @var Result $this->restrictionResultMock */
        $this->restrictionResultMock = $this->getMockBuilder(
            'Rcm\SwitchUser\Restriction\Result'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->restrictionResultMock->method('isAllowed')
            ->will($this->returnValue(true));
        $this->restrictionResultMock->method('getMessage')
            ->will($this->returnValue(''));

        /** @var \Rcm\SwitchUser\Restriction\Restriction $this->restrictionMock */
        $this->restrictionMock = $this->getMockBuilder(
            'Rcm\SwitchUser\Restriction\Restriction'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->restrictionMock->method('allowed')
            ->will($this->returnValue($this->restrictionResultMock));

        $this->switcherResult = $this->getMockBuilder(
            'Rcm\SwitchUser\Result'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->switcherResult->method('isSuccess')
            ->will($this->returnValue(true));
        $this->switcherResult->method('getMessage')
            ->will($this->returnValue(''));


        /** @var Switcher switcherMock */
        $this->switcherMock = $this->getMockBuilder(
            'Rcm\SwitchUser\Switcher\Switcher'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->switcherMock->method('getName')
            ->will($this->returnValue('MOCKNAME'));
        $this->switcherMock->method('switchTo')
            ->will($this->returnValue($this->switcherResult));
        $this->switcherMock->method('switchBack')
            ->will($this->returnValue($this->switcherResult));

        /** @var SwitchUserLogService switchUserLogService */
        $this->switchUserLogService = $this->getMockBuilder(
            'Rcm\SwitchUser\Service\SwitchUserLogService'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $unit = new SwitchUserService(
            $this->configMock,
            $this->rcmUserServiceMock,
            $this->restrictionMock,
            $this->switcherMock,
            $this->switchUserLogService
        );

        return $unit;
    }

    /**
     * getUnitBasicSwitchBack HAPPY PATH
     *
     * @return SwitchUserService
     */
    public function getUnitBasicSwitchBack()
    {
        $config = $this->configMock;

        $config['Rcm\\SwitchUser']['switchBackMethod'] = 'basic';

        /** @var \RcmUser\User\Entity\User $rcmUserMock */
        $this->rcmUserMock
            = $this->getMockBuilder('RcmUser\User\Entity\User')
            ->disableOriginalConstructor()
            ->getMock();

        $this->rcmUSerAuthenticationServiceMock = $this->getMockBuilder(
            'RcmUser\Authentication\Service\UserAuthenticationService'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->rcmUSerAuthenticationServiceMock->method('setIdentity')
            ->will($this->returnValue(null));

        /** @var \RcmUser\Service\RcmUserService $rcmUserServiceMock */
        $this->rcmUserServiceMock = $this->getMockBuilder(
            'RcmUser\Service\RcmUserService'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->rcmUserServiceMock->method('getCurrentUser')
            ->will($this->returnValue($this->rcmUserMock));

        $this->rcmUserServiceMock->method('getUserByUsername')
            ->will($this->returnValue($this->rcmUserMock));

        $this->rcmUserServiceMock->method('getUserAuthService')
            ->will($this->returnValue($this->rcmUSerAuthenticationServiceMock));

        /** @var Result $this ->restrictionResultMock */
        $this->restrictionResultMock = $this->getMockBuilder(
            'Rcm\SwitchUser\Restriction\Result'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->restrictionResultMock->method('isAllowed')
            ->will($this->returnValue(true));

        $this->restrictionResultMock->method('getMessage')
            ->will($this->returnValue(''));

        /** @var \Rcm\SwitchUser\Restriction\Restriction $this ->restrictionMock */
        $this->restrictionMock = $this->getMockBuilder(
            'Rcm\SwitchUser\Restriction\Restriction'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->restrictionMock->method('allowed')
            ->will($this->returnValue($this->restrictionResultMock));



        $this->switcherResult = $this->getMockBuilder(
            'Rcm\SwitchUser\Result'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->switcherResult->method('isSuccess')
            ->will($this->returnValue(true));
        $this->switcherResult->method('getMessage')
            ->will($this->returnValue(''));


        /** @var Switcher switcherMock */
        $this->switcherMock = $this->getMockBuilder(
            'Rcm\SwitchUser\Switcher\Switcher'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->switcherMock->method('getName')
            ->will($this->returnValue('MOCKNAME'));
        $this->switcherMock->method('switchTo')
            ->will($this->returnValue($this->switcherResult));
        $this->switcherMock->method('switchBack')
            ->will($this->returnValue($this->switcherResult));

        /** @var SwitchUserLogService switchUserLogService */
        $this->switchUserLogService = $this->getMockBuilder(
            'Rcm\SwitchUser\Service\SwitchUserLogService'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $unit = new SwitchUserService(
            $this->configMock,
            $this->rcmUserServiceMock,
            $this->restrictionMock,
            $this->switcherMock,
            $this->switchUserLogService
        );

        return $unit;
    }

    /**
     * getUnitNoCurrentUser
     *
     * @return SwitchUserService
     */
    public function getUnitNoCurrentUser()
    {
        /** @var \RcmUser\User\Entity\User $rcmUserMock */
        $this->rcmUserMock
            = $this->getMockBuilder('RcmUser\User\Entity\User')
            ->disableOriginalConstructor()
            ->getMock();

        $this->rcmUSerAuthenticationServiceMock = $this->getMockBuilder(
            'RcmUser\Authentication\Service\UserAuthenticationService'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->rcmUSerAuthenticationServiceMock->method('setIdentity')
            ->will($this->returnValue(null));

        /** @var \RcmUser\Service\RcmUserService $rcmUserServiceMock */
        $this->rcmUserServiceMock = $this->getMockBuilder(
            'RcmUser\Service\RcmUserService'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->rcmUserServiceMock->method('getCurrentUser')
            ->will($this->returnValue(null));

        $this->rcmUserServiceMock->method('getUserByUsername')
            ->will($this->returnValue($this->rcmUserMock));

        $this->rcmUserServiceMock->method('getUserAuthService')
            ->will($this->returnValue($this->rcmUSerAuthenticationServiceMock));

        /** @var Result $this ->restrictionResultMock */
        $this->restrictionResultMock = $this->getMockBuilder(
            'Rcm\SwitchUser\Restriction\Result'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->restrictionResultMock->method('isAllowed')
            ->will($this->returnValue(true));

        $this->restrictionResultMock->method('getMessage')
            ->will($this->returnValue(''));

        /** @var \Rcm\SwitchUser\Restriction\Restriction $this ->restrictionMock */
        $this->restrictionMock = $this->getMockBuilder(
            'Rcm\SwitchUser\Restriction\Restriction'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->restrictionMock->method('allowed')
            ->will($this->returnValue($this->restrictionResultMock));

        $this->switcherResult = $this->getMockBuilder(
            'Rcm\SwitchUser\Result'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->switcherResult->method('isSuccess')
            ->will($this->returnValue(true));
        $this->switcherResult->method('getMessage')
            ->will($this->returnValue(''));


        /** @var Switcher switcherMock */
        $this->switcherMock = $this->getMockBuilder(
            'Rcm\SwitchUser\Switcher\Switcher'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->switcherMock->method('getName')
            ->will($this->returnValue('MOCKNAME'));
        $this->switcherMock->method('switchTo')
            ->will($this->returnValue($this->switcherResult));
        $this->switcherMock->method('switchBack')
            ->will($this->returnValue($this->switcherResult));

        /** @var SwitchUserLogService switchUserLogService */
        $this->switchUserLogService = $this->getMockBuilder(
            'Rcm\SwitchUser\Service\SwitchUserLogService'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $unit = new SwitchUserService(
            $this->configMock,
            $this->rcmUserServiceMock,
            $this->restrictionMock,
            $this->switcherMock,
            $this->switchUserLogService
        );

        return $unit;
    }

    /**
     * getUnitResticted
     *
     * @return SwitchUserService
     */
    public function getUnitResticted()
    {

        /** @var \RcmUser\User\Entity\User $rcmUserMock */
        $this->rcmUserMock
            = $this->getMockBuilder('RcmUser\User\Entity\User')
            ->disableOriginalConstructor()
            ->getMock();

        $this->rcmUSerAuthenticationServiceMock = $this->getMockBuilder(
            'RcmUser\Authentication\Service\UserAuthenticationService'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->rcmUSerAuthenticationServiceMock->method('setIdentity')
            ->will($this->returnValue(null));

        /** @var \RcmUser\Service\RcmUserService $rcmUserServiceMock */
        $this->rcmUserServiceMock = $this->getMockBuilder(
            'RcmUser\Service\RcmUserService'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->rcmUserServiceMock->method('getCurrentUser')
            ->will($this->returnValue($this->rcmUserMock));

        $this->rcmUserServiceMock->method('getUserByUsername')
            ->will($this->returnValue($this->rcmUserMock));

        $this->rcmUserServiceMock->method('getUserAuthService')
            ->will($this->returnValue($this->rcmUSerAuthenticationServiceMock));

        /** @var Result $this ->restrictionResultMock */
        $this->restrictionResultMock = $this->getMockBuilder(
            'Rcm\SwitchUser\Restriction\Result'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->restrictionResultMock->method('isAllowed')
            ->will($this->returnValue(false));

        $this->restrictionResultMock->method('getMessage')
            ->will($this->returnValue('RESTRICTED'));

        /** @var \Rcm\SwitchUser\Restriction\Restriction $this ->restrictionMock */
        $this->restrictionMock = $this->getMockBuilder(
            'Rcm\SwitchUser\Restriction\Restriction'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->restrictionMock->method('allowed')
            ->will($this->returnValue($this->restrictionResultMock));

        $this->switcherResult = $this->getMockBuilder(
            'Rcm\SwitchUser\Result'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->switcherResult->method('isSuccess')
            ->will($this->returnValue(true));
        $this->switcherResult->method('getMessage')
            ->will($this->returnValue(''));

        /** @var Switcher switcherMock */
        $this->switcherMock = $this->getMockBuilder(
            'Rcm\SwitchUser\Switcher\Switcher'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->switcherMock->method('getName')
            ->will($this->returnValue('MOCKNAME'));
        $this->switcherMock->method('switchTo')
            ->will($this->returnValue($this->switcherResult));
        $this->switcherMock->method('switchBack')
            ->will($this->returnValue($this->switcherResult));

        /** @var SwitchUserLogService switchUserLogService */
        $this->switchUserLogService = $this->getMockBuilder(
            'Rcm\SwitchUser\Service\SwitchUserLogService'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $unit = new SwitchUserService(
            $this->configMock,
            $this->rcmUserServiceMock,
            $this->restrictionMock,
            $this->switcherMock,
            $this->switchUserLogService
        );

        return $unit;
    }

    /**
     * testGetSwitchBackMethod
     *
     * @return void
     */
    public function testGetSwitchBackMethod()
    {
        $unit = $this->getUnit();

        $result = $unit->getSwitchBackMethod();

        $this->assertEquals('MOCKNAME', $result);
    }

    /**
     * testGetUser
     *
     * @return void
     */
    public function testGetUser()
    {
        $unit = $this->getUnit();

        $result = $unit->getUser('something');

        $this->assertInstanceOf('\RcmUser\User\Entity\User', $result);
    }

    /**
     * testSwitchToUser
     *
     * @return void
     */
    public function testSwitchToUser()
    {
        /* HAPPY PATH */
        $unit = $this->getUnit();

        $targetUser = new User('123');

        $result = $unit->switchToUser($targetUser);

        $this->assertInstanceOf('\Rcm\SwitchUser\Result', $result);
        $this->assertTrue($result->isSuccess());

        /* NO USER */
        $unit = $this->getUnitNoCurrentUser();

        $result = $unit->switchToUser($targetUser);

        $this->assertInstanceOf('\Rcm\SwitchUser\Result', $result);
        $this->assertFalse($result->isSuccess());

        /* RESTRICTION */
        $unit = $this->getUnitResticted();

        $result = $unit->switchToUser($targetUser);

        $this->assertInstanceOf('\Rcm\SwitchUser\Result', $result);
        $this->assertFalse($result->isSuccess());
    }

    /**
     * testSwitchToUser
     *
     * @return void
     */
    public function testSwitchBack()
    {
        /* HAPPY PATH */
        $unit = $this->getUnitIsImpersonating();

        $result = $unit->switchBack([]);

        $this->assertInstanceOf('\Rcm\SwitchUser\Result', $result);
        $this->assertTrue($result->isSuccess());

        /* NO USER */
        $unit = $this->getUnitNoCurrentUser();

        $result = $unit->switchBack([]);

        $this->assertInstanceOf('\Rcm\SwitchUser\Result', $result);
        $this->assertFalse($result->isSuccess());
    }
}