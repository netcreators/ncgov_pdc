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

namespace Netcreators\NcgovPdc\Domain\Model;

use Netcreators\NcExtbaseLib\Domain\Model\Base;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;

/**
 * Model
 *
 * @package NcgovPdc
 * @subpackage Model
 */
class Registration extends Base
{

    /**
     * @var boolean
     */
    protected $hidden = false;

    /**
     * @var string
     */
    protected $subject = '';

    /**
     * @var \DateTime
     */
    protected $startTime = '';

    /**
     * @var \DateTime
     */
    protected $endTime = '';

    /**
     * @var boolean
     */
    protected $closed = false;

    /**
     * @var RegistrationResult
     */
    protected $result = null;

    /**
     * @var string
     */
    protected $remark = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\RegistrationAction>
     * @lazy
     * @cascade remove
     */
    protected $actions = null;

    /**
     * @var Registration
     */
    protected $nextRegistration = '';

    /**
     * @var FrontendUser
     */
    protected $user = null;

    public function initializeObject()
    {


        $this->actions = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->startTime = date_create();
        $this->endTime = $this->startTime;
    }

    /**
     * Returns property hidden
     * @return boolean $hidden
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Sets property hidden
     * @param boolean $hidden the property
     * @return self for chaining
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
        return $this;
    }

    /**
     * Returns property subject
     * @return string $subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Sets property subject
     * @param string $subject the property
     * @return self for chaining
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Returns property startTime
     * @return \DateTime $startTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Sets property startTime
     * @param \DateTime $startTime the property
     * @return self for chaining
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
        return $this;
    }

    /**
     * Returns property endTime
     * @return \DateTime $endTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Sets property endTime
     * @param \DateTime $endTime the property
     * @return self for chaining
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
        return $this;
    }

    /**
     * Returns the duration of the registration
     * @return integer  timestamp the duration of this registration in seconds
     */
    public function getDuration()
    {
        return $this->getEndTime()->format('U') - $this->getStartTime()->format('U');
    }

    /**
     * Returns property closed
     * @return boolean $closed
     */
    public function getClosed()
    {
        return $this->closed;
    }

    /**
     * Sets property closed
     * @param boolean $closed the property
     * @return self for chaining
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;
        return $this;
    }

    /**
     * Returns property result
     * @return RegistrationResult
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Sets property result
     * @param RegistrationResult $result the property
     * @return self for chaining
     */
    public function setResult(RegistrationResult $result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Returns property remark
     * @return string $remark
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * Sets property remark
     * @param string $remark the property
     * @return self for chaining
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;
        return $this;
    }

    /**
     * Returns property actions
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\RegistrationAction>
     */
    public function getActions()
    {
        return clone $this->actions;
    }

    /**
     * Sets property actions
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Netcreators\NcgovPdc\Domain\Model\RegistrationAction> $actions the property
     * @return self for chaining
     */
    public function setActions(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $actions)
    {
        $this->actions = $actions;
        return $this;
    }

    /**
     * Adds an action to the list
     * @param RegistrationAction $action
     * @return self for chaining
     */
    public function addRegistrationAction(RegistrationAction $action)
    {
        $this->actions->attach($action);
        return $this;
    }

    /**
     * Returns property nextRegistration
     * @return Registration the following registration
     */
    public function getNextRegistration()
    {
        return $this->nextRegistration;
    }

    /**
     * Sets property nextRegistration
     * @param Registration $nextRegistration the property
     * @return self for chaining
     */
    public function setNextRegistration(Registration $nextRegistration)
    {
        $this->nextRegistration = $nextRegistration;
        return $this;
    }

    /**
     * Sets the user who is running the registration
     * @param FrontendUser $user
     * @return self for chaining
     */
    public function setUser(FrontendUser $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Returns the user which created this record.
     * @return FrontendUser the user
     */
    public function getUser()
    {
        return $this->user;
    }
}

