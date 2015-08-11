<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Setup\Model;

use Magento\Framework\Filesystem;
use Magento\Setup\Model\Cron\Queue;

/**
 * Class Updater passes information to the updater application
 */
class Updater
{
    /**#@+
     * Task types
     */
    const TASK_TYPE_UPDATE = 'update';
    const TASK_TYPE_UNINSTALL = 'uninstall_component';
    /**#@-*/

    /**
     * Task types array
     *
     * @var array
     */
    static private $taskTypes = [Updater::TASK_TYPE_UPDATE, Updater::TASK_TYPE_UNINSTALL];

    /**
     * @var Queue
     */
    private $queue;

    /**
     * Constructor
     *
     * @param Queue $queue
     */
    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    /**
     * Create an update task for Updater app
     *
     * @param array $packages
     * @param string $type
     * @param array $additionalOptions
     * @return string
     */
    public function createUpdaterTask(array $packages, $type, array $additionalOptions = [])
    {
        try {
            if (in_array($type, self::$taskTypes)) {
                // write to .update_queue.json file
                $this->queue->addJobs(
                    [['name' => $type, 'params' => array_merge(['components' => $packages], $additionalOptions)]]
                );
                return '';
            } else {
                throw new \Exception('Unknown Updater task type');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
