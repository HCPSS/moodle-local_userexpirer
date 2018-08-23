<?php

defined('MOODLE_INTERNAL') || die();

$tasks = [
    // This task deletes users who have expired.
    [
        'classname' => 'local_userexpirer\task\user_expirer',
        'blocking'  => 0,

        // Run this task everyday at 1810.
        'minute'    => '10',
        'hour'      => '18',
        'day'       => '*',
        'dayofweek' => '*',
        'month'     => '*',
    ],
];
