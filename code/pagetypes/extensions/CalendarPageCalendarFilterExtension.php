<?php
/**
 * Provides relation between CalendarPage and Calendar to allow filtering by calendar
 *
 * @package calendar
 * @subpackage pagetypes
 */
class CalendarPageCalendarFilterExtension extends DataExtension
{

    public static $many_many = array(
        'Calendars' => 'PublicCalendar',
    );

    
    public function updateCMSFields(FieldList $fields)
    {
        
    }
}
