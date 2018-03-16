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

namespace Netcreators\NcgovPdc\Controller;

/**
 * RegistrationController
 *
 * @package NcgovPdc
 * @subpackage Controller
 */
class RegistrationController extends BaseController
{

    /**
     * @var \Netcreators\NcgovPdc\Domain\Repository\RegistrationResultRepository
     * @inject
     */
    protected $registrationResultRepository = null;

    /**
     * Initializes the current action
     *
     * @return void
     */
    public function initializeAction()
    {
        $this->initializeBase();
    }

    /**
     * Starts the registration
     * @param string $subject subject for the registraion
     * @return void
     */
    public function startRegistrationAction($subject)
    {
        $this->view->assign(
            'registrationTrackingAllowed',
            $this->registrationManager->getRegistrationTrackingAllowed()
        );
        $this->registrationManager->startNewRegistration($subject);
        $this->redirect('registrationStatus');
    }

    /**
     * Shows the close registration form
     * @return void
     */
    public function closeRegistrationAction()
    {
        $this->view->assign(
            'registrationTrackingAllowed',
            $this->registrationManager->getRegistrationTrackingAllowed()
        );
        $this->view->assign('registration', $this->registrationManager->getRegistration());
        $this->view->assign('registrationIsRunning', $this->registrationManager->isRegistrationRunning());
        $this->view->assign(
            'registrationResults',
            $this->registrationResultRepository->findAllExcept(
                array((int)$this->localConfigurationManager->get('registration.expiredResult'))
            )
        );
    }

    /**
     * Registration result
     * @param \Netcreators\NcgovPdc\Domain\Model\RegistrationResult $result
     * @param string $remark
     * @return void
     */
    public function updateRegistrationDoneAction(
        \Netcreators\NcgovPdc\Domain\Model\RegistrationResult $result = null,
        $remark = ''
    ) {
        $this->view->assign(
            'registrationTrackingAllowed',
            $this->registrationManager->getRegistrationTrackingAllowed()
        );
        if ($result != null && $this->registrationManager->isRegistrationRunning()) {
            $this->registrationManager->stopRegistration($result, $remark);
            $this->view->assign('registrationClosed', true);
        } else {
            $this->view->assign('registrationClosed', true);
        }
    }

    /**
     * Displays the form which gives registration actions
     * @return void
     */
    public function registrationStatusAction()
    {
        $this->view->assign(
            'registrationTrackingAllowed',
            $this->registrationManager->getRegistrationTrackingAllowed()
        );
        if ($this->registrationManager->isRegistrationRunning()) {
            $this->view->assign('registrationIsRunning', true);
            $justStarted = $this->registrationManager->registrationJustStarted();
            $this->view->assign('registrationJustStarted', $justStarted);
            if (!$justStarted) {
                $this->view->assign('duration', round($this->registrationManager->getRunningTime() / 60));
            }
        }
    }
}

