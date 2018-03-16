<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009-2010 Frans van der Veen [Netcreators] <extensions@netcreators.com>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace Netcreators\NcgovPdc\Domain\Factory;

use Netcreators\NcgovPdc\Domain\Model\Registration;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * Registration ActionFactory
 *
 * @package NcgovPdc
 * @subpackage Factory
 */
class RegistrationFactory implements SingletonInterface
{

    /**
     * @var \Netcreators\NcExtbaseLib\Utility\FrontendUserUtility
     * @inject
     */
    protected $frontendUserUtility;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     * @inject
     */
    protected $objectManager = null;

    /**
     * Builds a registration object
     * @param string $subject the subject for the registration
     * @throws Exception\NotAuthorisedException
     * @return Registration
     */
    public function build($subject)
    {
        if (!$this->frontendUserUtility->isLoggedIn()) {
            throw new Exception\NotAuthorisedException();
        }
        $user = $this->frontendUserUtility->findLoggedInUser();
        $time = date_create();
        $registration = $this->objectManager->get(Registration::class);
        $registration->setSubject($subject)
            ->setStartTime($time)
            ->setEndTime($time)
            ->setHidden(false)
            ->setClosed(false)
            ->setNextRegistration(null)
            ->setUser($user);
        return $registration;
    }
}

