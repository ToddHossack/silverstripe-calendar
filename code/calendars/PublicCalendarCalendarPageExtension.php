<?php
/**
 * Calendar / Calendar Page Extension
 * Allowing events to have calendars
 *
 * @package calendar
 * @subpackage calendars
 */
class PublicCalendarCalendarPageExtension extends DataExtension
{

    public static $belongs_many_many = array(
        'CalendarPages' => 'CalendarPage',
    );

}
