<?php

namespace local_userexpirer\task;

use local_userexpirer\event\user_expired;

/**
 * A Moodle task that expires users who have been suspended for more than a
 * year.
 *
 * @author Brendan Anderson <brendan_anderson@hcpss.org>
 */
class user_expirer extends \core\task\scheduled_task {

    /**
     * @var \moodle_database
     */
    private $db;

    public function __construct() {
        global $DB;
        $this->db = $DB;
    }

    /**
     * (non-PHPdoc)
     * @see \core\task\scheduled_task::get_name()
     */
    public function get_name() {
        return get_string('user_expirer', 'local_userexpirer');
    }

    /**
     * Task execution which expires user.
     *
     * @see \core\task\task_base::execute()
     */
    public function execute() {
        global $CFG;
        require_once $CFG->dirroot.'/user/lib.php';

        $lastyear = time() - (3600 * 24 * 365);

        $users = $this->db
            ->get_records_select('user', '
                suspended = ?
                AND deleted != ?
                AND lastaccess < ?
                AND timecreated < ?
            ', [1, 1, $lastyear, $lastyear]);

        foreach ($users as $user) {
            if (!is_siteadmin($user)) {
                user_delete_user($user);

                user_expired::create([
                    'objectid' => $user->id,
                    'other' => [
                        'username'    => $user->username,
                        'suspended'   => $user->suspended,
                        'lastaccess'  => $user->lastaccess,
                        'timecreated' => $user->timecreated,
                    ],
                ])->trigger();
            }
        }

        mtrace(count($users) . ' users expired.');
    }
}
