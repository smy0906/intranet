<?php
namespace Intra\Service\Cron\Interfacer;

abstract class CronInterface
{
    /**
     * @return string
     */
    abstract public function getUniqueName();

    /**
     * @param $last_executed_datetime \DateTime
     * @return bool
     */
    abstract public function isTimeToRun($last_executed_datetime);

    /**
     * @return bool
     */
    abstract public function run();
}
