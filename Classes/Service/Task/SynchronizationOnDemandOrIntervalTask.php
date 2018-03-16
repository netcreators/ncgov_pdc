<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Leonie Philine Bitto [Netcreators] <extensions@netcreators.nl>
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
 * ************************************************************* */

namespace Netcreators\NcgovPdc\Service\Task;

/**
 * Synchronization task that runs on demand or starts on a given interval
 * Will reset after midnight
 *
 * Note: Each Extension using \Netcreators\NcExtbaseLib\Service\Task\SynchronizationOnDemandOrIntervalTask
 *       needs to derive its own subclass, to avoid clashes in cases where several extensions synchronize using the same storagePid.
 *
 * @package NcgovPdc
 * @subpackage Service
 */
class SynchronizationOnDemandOrIntervalTask extends \Netcreators\NcExtbaseLib\Service\Task\SynchronizationOnDemandOrIntervalTask
{
    protected $runnerTaskName = SynchronizationRunnerTask::class;
}

