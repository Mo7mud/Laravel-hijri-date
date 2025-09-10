<?php

namespace Mo7mud\HijriDate\Converters;

use Mo7mud\HijriDate\Converters\Contracts\GregorianToHijriConverter;
use Mo7mud\HijriDate\HijriDate;
use Carbon\Carbon;
use IntlDateFormatter;
use IntlCalendar;

class MakkaEstimateG2HConverter implements GregorianToHijriConverter
{
    /**
     * Get the HijriDate object from a Gregorian date.
     * 
     * @param \Carbon\Carbon $gregorian
     * @return \Mo7mud\HijriDate\HijriDate
     */
    public function getHijriFromGregorian(Carbon $gregorian): HijriDate
    {
        $gregorian->setTimezone('+5:00');   // Ensure it is in MVT
        $gregorian->startOfDay();           // Ensure it is at midnight (so time does not affect diffInDays())
        $formatter = IntlDateFormatter::create(
            'en_US',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            'Indian/Maldives',
            IntlCalendar::createInstance('Indian/Maldives', "en_US@calendar=islamic"),
            'yyyy-MM-dd'
        );
        return HijriDate::parse($formatter->format($gregorian));
    }

    /**
     * Get the Gregorian date from a HijriDate object.
     * 
     * @param \Mo7mud\HijriDate\HijriDate $hijri
     * @return \Carbon\Carbon
     */
    public function getGregorianFromHijri(HijriDate $hijri): Carbon
    {
        $hijriYear = $hijri->getYear();
        $hijriMonth = $hijri->getMonth();
        $hijriDay = $hijri->getDay();

        // Hijri to Julian
        $julianDay = floor((11 * $hijriYear + 3) / 30) + floor(354 * $hijriYear) + floor(30 * $hijriMonth)
            - floor(($hijriMonth - 1) / 2) + $hijriDay + 1948440 - 386;

        // Julian to Gregorian
        $b = 0;
        if ($julianDay > 2299160) {
            $a = floor(($julianDay - 1867216.25) / 36524.25);
            $b = 1 + $a - floor($a / 4.0);
        }

        $bb = $julianDay + $b + 1524;
        $cc = floor(($bb - 122.1) / 365.25);
        $dd = floor(365.25 * $cc);
        $ee = floor(($bb - $dd) / 30.6001);

        $gregorianDay = ($bb - $dd) - floor(30.6001 * $ee);
        $gregorianMonth = $ee - 1;

        if ($ee > 13) {
            $cc += 1;
            $gregorianMonth = $ee - 13;
        }

        $gregorianYear = $cc - 4716;

        return Carbon::create($gregorianYear, $gregorianMonth, $gregorianDay, 0, 0, 0, '+5:00');
    }
}
