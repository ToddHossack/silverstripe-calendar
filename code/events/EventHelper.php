<?php
/**
 * Event Helper
 * Helper class for event related calculations and formatting
 *
 * @package calendar
 */
class EventHelper
{


    /**
     * Formatted Dates
     * Returns either the event's date or both start and end date if the event spans more than
     * one date
     *
     * Format:
     * Jun 7th - Jun 10th
     *
     * @param SS_Datetime $startObj
     * @param SS_Datetime $endObj
     * @return string
     */
    public static function formatted_dates($startObj, $endObj)
    {
        //Checking if end date is set
        $endDateIsset = true;
        if (isset($endObj->value)) {
            $endDateIsset = false;
        }

        $startTime = strtotime($startObj->value);
        $endTime = strtotime($endObj->value);

        $startMonth = date('M', $startTime);
        $startDayOfMonth = $startObj->DayOfMonth(true);
        $str = $startMonth . ' ' . $startDayOfMonth;

        if (date('Y-m-d', $startTime) == date('Y-m-d', $endTime)) {
            //one date - str. has already been written
        } else {
            //two dates

            if ($endDateIsset) {
                $endMonth = date('M', $endTime);
                $endDayOfMonth = $endObj->DayOfMonth(true);

                if ($startMonth == $endMonth) {
                    $str .= ' - ' . $endDayOfMonth;
                } else {
                    $str .= ' - ' . $endMonth . ' ' . $endDayOfMonth;
                }
            }
        }
        return $str;
    }

    public static function formatted_alldates($startObj, $endObj)
    {
        $dateFormat = CalendarConfig::subpackage_setting('events', 'date_format');
        $timeFormat = CalendarConfig::subpackage_setting('events', 'time_format');

        $startDate = date("Y-m-d", strtotime($startObj->value));
        $endDate = date("Y-m-d", strtotime($endObj->value));

        // Check if event covers only single day (use formatted_timeframe instead)
        if (($startDate && !$endObj->value) || ($startDate == $endDate)) {
            return false;
        }

        $startTime = strtotime($startObj->value);
        $endTime = strtotime($endObj->value);
        
        if (date('g:ia', $startTime) == '12:00am') {
            $startDate = date($dateFormat, $startTime);     // No start time, so do not include
        } else {
            $startDate = date($dateFormat .' '.$timeFormat, $startTime);
        }
        
        if(!$endTime) {
            $endDate = '';
        } elseif (date('g:ia', $endTime) == '12:00am') {
            $endDate = date($dateFormat, $endTime);         
        } else {
            $endDate = date($dateFormat .' '.$timeFormat, $endTime);
        }
 
        return $startDate . ($endDate ? " &ndash; " . $endDate : '');
    }

    /**
     * Formatted time frame
     * Returns either a string or null
     * Time frame is only applicable if both start and end time is on the same day
     * @param string $startStr
     * @param string $endStr
     * @return string|null
     */
    public static function formatted_timeframe($startObj, $endObj)
    {
        $timeFormat = CalendarConfig::subpackage_setting('events', 'time_format');
        $allDayEnabled = CalendarConfig::subpackage_setting('events', 'enable_allday_events');
        $str = null;

        $startTime = strtotime($startObj->value);
        $endTime = strtotime($endObj->value);
        
        if (!$allDayEnabled && date('g:ia', $startTime) == '12:00am' && !$endObj->value) {
            return _t('Event.TBA','TBA');
        }
        
        if ($startTime == $endTime) {
            return null;
        }
        
        if ($endObj->value) {
            //time frame is only applicable if both start and end time is on the same day
            if (date('Y-m-d', $startTime) == date('Y-m-d', $endTime)) {
                $str = date($timeFormat, $startTime) . ' &ndash; ' . date($timeFormat, $endTime);
            }
        } else {
            $str = date($timeFormat, $startTime);
        }

        return $str;
    }
}
