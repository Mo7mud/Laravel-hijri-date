<?php

namespace Mo7mud\HijriDate\Converters\Contracts;

use Mo7mud\HijriDate\HijriDate;
use Carbon\Carbon;

interface GregorianToHijriConverter
{
    /**
     * Get the HijriDate object from a Gregorian date.
     * 
     * @param \Carbon\Carbon $gregorian
     * @return \Mo7mud\HijriDate\HijriDate
     */
    public function getHijriFromGregorian(Carbon $gregorian): HijriDate;

    /**
     * Get the Gregorian date from a HijriDate object.
     * 
     * @param \Mo7mud\HijriDate\HijriDate $hijri
     * @return \Carbon\Carbon
     */
    public function getGregorianFromHijri(HijriDate $hijri): Carbon;
}
