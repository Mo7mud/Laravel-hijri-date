<?php

use Mo7mud\HijriDate\HijriDate;

if (!function_exists('today_hijri')) {
    /**
     * Get today's Hijri date.
     *
     * @return \Mo7mud\HijriDate\HijriDate
     */
    function today_hijri(): HijriDate
    {
        return HijriDate::createFromGregorian(today());
    }
}
