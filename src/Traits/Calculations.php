<?php

namespace Mo7mud\HijriDate\Traits;

use Mo7mud\HijriDate\HijriDate;
use OutOfRangeException;

trait Calculations
{
    /**
     * Add specified amount of days.
     * 
     * @param int $daysToAdd
     * @param bool $useGregorian Use converted Gregorian date to return a more accurate result.
     * @return \Mo7mud\HijriDate\HijriDate
     */
    public function addDays(int $daysToAdd = 1, bool $useGregorian = true): HijriDate
    {
        if ($useGregorian)
            return $this->addDaysExact($daysToAdd);

        if ($daysToAdd < 0)
            return $this->subDays(abs($daysToAdd));

        // Work with copies
        $hYear = $this->year;
        $hMonth = $this->month;
        $hDay = $this->day;

        $yearsToAdd = intdiv($daysToAdd, self::DAYS_PER_YEAR);
        $daysToAdd -= $yearsToAdd * self::DAYS_PER_YEAR;
        $monthsToAdd = intdiv($daysToAdd, self::DAYS_PER_MONTH);
        $daysToAdd -= $monthsToAdd * self::DAYS_PER_MONTH;

        $hDay += $daysToAdd;
        if ($hDay > self::DAY_MAX) {
            $hDay -= self::DAY_MAX;
            $hMonth++;
        }
        $hMonth += $monthsToAdd;
        if ($hMonth > self::MONTH_MAX) {
            $hMonth -= self::MONTH_MAX;
            $hYear++;
        }
        $hYear += $yearsToAdd;
        if ($hYear > config('hijri.year_max', self::FALLBACK_YEAR_MAX))
            throw new OutOfRangeException("Date value out of acceptable range.");

        $this->year = $hYear;
        $this->month = $hMonth;
        $this->day = $hDay;
        $this->resetGregorianDate();
        return $this;
    }

    /**
     * Subtract specified amount of days.
     *
     * @param int $daysToSubtract
     * @param bool $useGregorian Use converted Gregorian date to return a more accurate result.
     * @return \Mo7mud\HijriDate\HijriDate
     */
    public function subDays(int $daysToSubtract = 1, bool $useGregorian = true): HijriDate
    {
        if ($useGregorian)
            return $this->subDaysExact($daysToSubtract);

        if ($daysToSubtract < 0)
            return $this->addDays(abs($daysToSubtract));

        // Work with copies
        $hYear = $this->year;
        $hMonth = $this->month;
        $hDay = $this->day;

        $yearsToSubtract = intdiv($daysToSubtract, self::DAYS_PER_YEAR);
        $daysToSubtract -= $yearsToSubtract * self::DAYS_PER_YEAR;
        $monthsToSubtract = intdiv($daysToSubtract, self::DAYS_PER_MONTH);
        $daysToSubtract -= $monthsToSubtract * self::DAYS_PER_MONTH;

        $hDay -= $daysToSubtract;
        if ($hDay < self::DAY_MIN) {
            $hDay += self::DAY_MAX;
            $hMonth--;
        }
        $hMonth -= $monthsToSubtract;
        if ($hMonth < self::MONTH_MIN) {
            $hMonth += self::MONTH_MAX;
            $hYear--;
        }
        $hYear -= $yearsToSubtract;
        if ($hYear < config('hijri.year_min', self::FALLBACK_YEAR_MIN))
            throw new OutOfRangeException("Date value out of acceptable range.");

        $this->year = $hYear;
        $this->month = $hMonth;
        $this->day = $hDay;
        $this->resetGregorianDate();
        return $this;
    }

    /**
     * Get the difference in days between this and another HijriDate.
     * 
     * @param \Mo7mud\HijriDate\HijriDate $other
     * @param bool $absolute Get absolute value of the difference
     * @param bool $useGregorian Use converted Gregorian date to return a more accurate result.
     * @return int
     */
    public function diffInDays(HijriDate $other, bool $absolute = true, bool $useGregorian = true): int
    {
        if ($useGregorian)
            return $this->diffInDaysExact($other, $absolute);

        $comparison = $this->compareWith($other);
        if ($comparison === 0) {
            // The dates are equal
            return 0;
        } else if ($comparison === -1) {
            // $this is earlier than $other
            $days = 0;
            $days += ($other->year - $this->year) * self::DAYS_PER_YEAR;
            $days += ($other->month - $this->month) * self::DAYS_PER_MONTH;
            $days += $other->day - $this->day;
            return $days;
        } else if ($comparison === 1) {
            // $this is later than $other
            $days = 0;
            $days += ($this->year - $other->year) * self::DAYS_PER_YEAR;
            $days += ($this->month - $other->month) * self::DAYS_PER_MONTH;
            $days += $this->day - $other->day;
            if (!$absolute) $days *= -1;
            return $days;
        }
    }
}
