<?php

namespace local_userexpirer\event;

class user_expired extends \core\event\base {

    /**
     * {@inheritDoc}
     * @see \core\event\base::init()
     */
    protected function init() {
        $this->data['crud'] = 'd';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'user';

        $this->context = \context_system::instance();
    }

    /**
     * Event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('event_user_expired', 'local_userexpirer');
    }

    /**
     * {@inheritDoc}
     * @see \core\event\base::get_description()
     */
    public function get_description() {
        return get_string('event_user_expired_description', 'local_userexpirer', $this);
    }
}
