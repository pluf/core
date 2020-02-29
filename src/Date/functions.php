<?php
namespace Pluf\Date;

/**
 * مقایسه دو تاریخ با یکدیگر
 *
 * Compare two date and returns the number of seconds between the
 * first and the second.
 * If only the date is given without time, the
 * end of the day is used (23:59:59).
 *
 * @param
 *            string Date to compare for ex: '2006-09-17 18:42:00'
 * @param
 *            string Second date to compare if null use now (null)
 * @return int Number of seconds between the two dates. Negative
 *         value if the second date is before the first.
 */
function compare($date1, $date2 = null)
{
    if (strlen($date1) == 10) {
        $date1 .= ' 23:59:59';
    }
    if (is_null($date2)) {
        $date2 = time();
    } else {
        if (strlen($date2) == 10) {
            $date2 .= ' 23:59:59';
        }
        $date2 = strtotime(str_replace('-', '/', $date2));
    }
    $date1 = strtotime(str_replace('-', '/', $date1));
    return $date2 - $date1;
}

/**
 * نمایش تاریخ به فرمت دلخواه
 *
 * Display a date in the format:
 * X days Y hours ago
 * X hours Y minutes ago
 * X hours Y minutes left
 *
 * "resolution" is year, month, day, hour, minute.
 *
 * If not time is given, only the day, the end of the day is
 * used: 23:59:59.
 *
 * @param
 *            string Date to compare with ex: '2006-09-17 18:42:00'
 * @param
 *            string Reference date to compare with by default now (null)
 * @param
 *            int Maximum number of elements to show (2)
 * @param
 *            string If no delay between the two dates display ('now')
 * @param
 *            bool Show ago/left suffix
 * @return string Formatted date
 */
function easy($date, $ref = null, $blocks = 2, $notime = 'now', $show = true)
{
    if (strlen($date) == 10) {
        $date .= ' 23:59:59';
    }
    if (is_null($ref)) {
        $ref = date('Y-m-d H:i:s');
        $tref = time();
    } else {
        if (strlen($ref) == 10) {
            $ref .= ' 23:59:59';
        }
        $tref = strtotime(str_replace('-', '/', $ref));
    }
    $tdate = strtotime(str_replace('-', '/', $date));
    $past = true;
    if ($tref < $tdate) {
        // date in the past
        $past = false;
        $_tmp = $ref;
        $ref = $date;
        $date = $_tmp;
    }
    $ref = str_replace(array(
        ' ',
        ':'
    ), '-', $ref);
    $date = str_replace(array(
        ' ',
        ':'
    ), '-', $date);
    $refs = explode('-', $ref);
    $dates = explode('-', $date);
    // Modulo on the month is dynamically calculated after
    $modulos = array(
        365,
        12,
        31,
        24,
        60,
        60
    );
    // day in month
    $month = $refs[1] - 1;
    $modulos[2] = date('t', mktime(0, 0, 0, $month, 1, $refs[0]));
    $diffs = array();
    for ($i = 0; $i < 6; $i ++) {
        $diffs[$i] = $refs[$i] - $dates[$i];
    }
    $retain = 0;
    for ($i = 5; $i > - 1; $i --) {
        $diffs[$i] = $diffs[$i] - $retain;
        $retain = 0;
        if ($diffs[$i] < 0) {
            $diffs[$i] = $modulos[$i] + $diffs[$i];
            $retain = 1;
        }
    }
    $res = '';
    $total = 0;
    for ($i = 0; $i < 5; $i ++) {
        if ($diffs[$i] > 0) {
            $total ++;
            $res .= $diffs[$i] . ' ';
            switch ($i) {
                case 0:
                    $res .= _n('year', 'years', $diffs[$i]);
                    break;
                case 1:
                    $res .= _n('month', 'months', $diffs[$i]);
                    break;
                case 2:
                    $res .= _n('day', 'days', $diffs[$i]);
                    break;
                case 3:
                    $res .= _n('hour', 'hours', $diffs[$i]);
                    break;
                case 4:
                    $res .= _n('minute', 'minutes', $diffs[$i]);
                    break;
                case 5:
                    $res .= _n('second', 'seconds', $diffs[$i]);
                    break;
            }
            $res .= ' ';
        }
        if ($total >= $blocks)
            break;
    }
    if (strlen($res) == 0) {
        return $notime;
    }
    if ($show) {
        if ($past) {
            $res = sprintf('%s ago', $res);
        } else {
            $res = sprintf('%s left', $res);
        }
    }
    return $res;
}