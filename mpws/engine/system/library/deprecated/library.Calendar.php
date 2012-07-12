<?

// PHP Calendar Class Version 1.4 (5th March 2001)
//
// Copyright David Wilkinson 2000 - 2001. All Rights reserved.
//
// This software may be used, modified and distributed freely
// providing this copyright notice remains intact at the head
// of the file.
//
// This software is freeware. The author accepts no liability for
// any loss or damages whatsoever incurred directly or indirectly
// from the use of this script. The author of this software makes
// no claims as to its fitness for any purpose whatsoever. If you
// wish to use this software you should first satisfy yourself that
// it meets your requirements.
//
// URL:   http://www.cascade.org.uk/software/php/calendar/
// Email: davidw@cascade.org.uk

class Calendar
{
	/*
        The start day of the week. This is the day that appears in the first column
        of the calendar. Sunday = 0.
    */
    var $startDay = 1;

    /*
        The start month of the year. This is the month that appears in the first slot
        of the calendar in the year view. January = 1.
    */
    var $startMonth = 1;

    /*
        The labels to display for the days of the week. The first entry in this array
        represents Sunday.
    */
    var $dayNames = array("��", "��", "��", "��", "��", "��", "��");

    /*
        The labels to display for the months of the year. The first entry in this array
        represents January.
    */
    var $monthNames = array("ѳ����", "�����", "��������", "������", "�������", "�������",
                            "������", "�������", "��������", "�������", "��������", "�������");

    /*
        The number of days in each month. You're unlikely to want to change this...
        The first entry in this array represents January.
    */
    var $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    /*
        Constructor for the Calendar class
    */
    function __construct()
    {
    	//echo 'claendar constructor';
    }

    /*
        Destructor for the Calendar class
    */
    function __destruct()
    {
    	//echo 'claendar destructor';
    }

    /*
        Get the array of strings used to label the days of the week. This array contains seven
        elements, one for each day of the week. The first entry in this array represents Sunday.
    */
    function getDayNames()
    {
        return $this->dayNames;
    }

    /*
        Set the array of strings used to label the days of the week. This array must contain seven
        elements, one for each day of the week. The first entry in this array represents Sunday.
    */
    function setDayNames($names)
    {
        $this->dayNames = $names;
    }

    /*
        Get the array of strings used to label the months of the year. This array contains twelve
        elements, one for each month of the year. The first entry in this array represents January.
    */
    function getMonthNames()
    {
        return $this->monthNames;
    }

    /*
        Set the array of strings used to label the months of the year. This array must contain twelve
        elements, one for each month of the year. The first entry in this array represents January.
    */
    function setMonthNames($names)
    {
        $this->monthNames = $names;
    }

    /*
        Gets the start day of the week. This is the day that appears in the first column
        of the calendar. Sunday = 0.
    */
      function getStartDay()
    {
        return $this->startDay;
    }

    /*
        Sets the start day of the week. This is the day that appears in the first column
        of the calendar. Sunday = 0.
    */
    function setStartDay($day)
    {
        $this->startDay = $day;
    }

    /*
        Gets the start month of the year. This is the month that appears first in the year
        view. January = 1.
    */
    function getStartMonth()
    {
        return $this->startMonth;
    }

    /*
        Sets the start month of the year. This is the month that appears first in the year
        view. January = 1.
    */
    function setStartMonth($month)
    {
        $this->startMonth = $month;
    }

    /*
        Return the URL to link to in order to display a calendar for a given month/year.
        You must override this method if you want to activate the "forward" and "back"
        feature of the calendar.

        Note: If you return an empty string from this function, no navigation link will
        be displayed. This is the default behaviour.

        If the calendar is being displayed in "year" view, $month will be set to zero.
    */
    function getCalendarLink($month, $year, $clink)
    {
    	$clink = str_replace('$month', sprintf("%02d", $month), $clink);
    	$clink = str_replace('$year', sprintf("%02d", $year), $clink);

    	return $clink;
    }

    /*
        Return the URL to link to  for a given date.
        You must override this method if you want to activate the date linking
        feature of the calendar.

        Note: If you return an empty string from this function, no navigation link will
        be displayed. This is the default behaviour.
    */
    function getDateLink($day, $month, $year, $dlink)
    {
    	$dlink = str_replace('$day', sprintf("%02d", $day), $dlink);
    	$dlink = str_replace('$month', sprintf("%02d", $month), $dlink);
    	$dlink = str_replace('$year', sprintf("%04d", $year), $dlink);

        return $dlink;
    }

    /*
        Return the HTML for the current month
    */
    function getCurrentMonthView($data, $actions, $dateColumn, $dateMask)
    {
        $d = getdate(time());
        return $this->getMonthView($data, $actions, $dateColumn, $d['mon'], $d['year'], $dateMask);
    }

    /*
        Return the HTML for a specified month
    */
    function getMonthView($data, $actions, $dateColumn, $month, $year, $dateMask)
    {
        return $this->getMonthHTML($data, $actions, $dateColumn, $month, $year, $dateMask);
    }

    /*
        Return the HTML for the current year
    */
    function getCurrentYearView()
    {
        $d = getdate(time());
        return $this->getYearView($d["year"]);
    }

    /*
        Return the HTML for a specified year
    */
    function getYearView($year)
    {
        return $this->getYearHTML($year);
    }



    /********************************************************************************

        The rest are private methods. No user-servicable parts inside.

        You shouldn't need to call any of these functions directly.

    *********************************************************************************/


    /*
        Calculate the number of days in a month, taking into account leap years.
    */
    function getDaysInMonth($month, $year)
    {
        if ($month < 1 || $month > 12)
        {
            return 0;
        }

        $d = $this->daysInMonth[$month - 1];

        if ($month == 2)
        {
            // Check for leap year
            // Forget the 4000 rule, I doubt I'll be around then...

            if ($year%4 == 0)
            {
                if ($year%100 == 0)
                {
                    if ($year%400 == 0)
                    {
                        $d = 29;
                    }
                }
                else
                {
                    $d = 29;
                }
            }
        }

        return $d;
    }


    /*
        Generate the HTML for a given month
    */
    function getMonthHTML($data, $actions, $dateColumn, $m, $y, $dateMask, $showYear = 1)
    {
        $s = "";

		// ������������ ����� �� ����
        $a = $this->adjustDate($m, $y);
        // ̳����
        $month = $a[0];
        // г�
        $year = $a[1];
        // ����
        $day = $this->startDay;
        // ���� �� �������� ������ �� �����
        $date = getdate(mktime(12, 0, 0, $month, 1, $year));
        // ���������� ����
        $today = getdate(time());
        // ʳ������ ��� � �����
    	$daysInMonth = $this->getDaysInMonth($month, $year);
    	// ����� ��� � �����
    	$first = $date["wday"];
    	// ����� �����
    	$monthName = $this->monthNames[$month - 1];
        // ��������� ����� �� ��
    	$prev = $this->adjustDate($month - 1, $year);
    	// ��������� ����� �� ��
    	$next = $this->adjustDate($month + 1, $year);
    	// ������� �� ��������� �����
    	$prevMonth = "";
    	// ������� �� ��������� �����
    	$nextMonth = "";
    	// ĳ� ��� ����� ����� � �����
    	$monthLink = "";
    	// ĳ� ��� ������ ���� � �����
    	$yearLink = "";
    	// ����� ���������
    	$header = "";

    	if ($showYear == 1)
    	{
    	    $prevMonth = $this->getCalendarLink($prev[0], $prev[1], $actions['MONTH']);
    	    $nextMonth = $this->getCalendarLink($next[0], $next[1], $actions['MONTH']);
    	}

    	$monthLink = $this->getCalendarLink($month, $year, $actions['MONTH']);
    	if ($monthLink != "")
    		$header = "<a href=\"$monthLink\" class=\"navigator\">$monthName</a>";
    	if ($showYear > 0)
    		$yearLink = $this->getCalendarLink($month, $year, $actions['YEAR']);
    	if ($yearLink != "")
    		$header = $header." "."<a href=\"$yearLink\" class=\"navigator\">$year</a>";

    	$s .= "<table class=\"calendar\">\n";
    	$s .= "<tr>\n";
    	$s .= "<td align=\"center\" valign=\"top\">".(($prevMonth == "") ? "&nbsp;" : "<a href=\"$prevMonth\" class=\"navigator\">&lt;&lt;</a>")."</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" colspan=\"5\" class=\"mothCaption\">$header</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\">".(($nextMonth == "") ? "&nbsp;" : "<a href=\"$nextMonth\" class=\"navigator\">&gt;&gt;</a>")."</td>\n";
    	$s .= "</tr>\n";

    	$s .= "<tr>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"dayCaption\">".$this->dayNames[($this->startDay)%7]."</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"dayCaption\">".$this->dayNames[($this->startDay+1)%7]."</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"dayCaption\">".$this->dayNames[($this->startDay+2)%7]."</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"dayCaption\">".$this->dayNames[($this->startDay+3)%7]."</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"dayCaption\">".$this->dayNames[($this->startDay+4)%7]."</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"dayCaption\">".$this->dayNames[($this->startDay+5)%7]."</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"dayCaption\">".$this->dayNames[($this->startDay+6)%7]."</td>\n";
    	$s .= "</tr>\n";

    	// We need to work out what date to start at so that the first appears in the correct column
    	$day += 1 - $first;
    	while ($day > 1)
    	    $day -= 7;

    	// Get data to array
    	$dArr = Array();
    	foreach ($data as $v)
    	{
    		if (is_long($v[$dateColumn]))
    			$_date = date("Y-m-d", $v[$dateColumn]);
    		else
    			$_date = date("Y-m-d", strtotime($v[$dateColumn]));
    		if (!isset($dArr[$_date]))
    			$dArr[$_date] = "";
    	}

        // Make sure we know when today is, so that we can use a different CSS style
    	while ($day <= $daysInMonth)
    	{
    	    $s .= "<tr>\n";

    	    for ($i = 0; $i < 7; $i++)
    	    {
        	    $class = ($year == $today["year"] && $month == $today["mon"] && $day == $today["mday"]) ? "todayCell" : "dayCell";
    	        $s .= "<td class=\"$class\" align=\"right\" valign=\"top\">";
    	        if ($day > 0 && $day <= $daysInMonth)
    	        {
    	        	$link = "";
    	          	if (isset($dArr[sprintf($dateMask, $year, $month, $day)]))
    	          		$link = $this->getDateLink($day, $month, $year, $actions['DAY']);
    	            $s .= (($link == "") ? $day : "<a href=\"$link\" class=\"usedDayLink\">$day</a>");
    	        }
    	        else
    	        {
    	            $s .= "&nbsp;";
    	        }
      	        $s .= "</td>\n";
        	    $day++;
    	    }
    	    $s .= "</tr>\n";
    	}

    	$s .= "</table>\n";

    	return $s;
    }


    /*
        Generate the HTML for a given year
    */
    function getYearHTML($year)
    {
        $s = "";
    	$prev = $this->getCalendarLink(0, $year - 1);
    	$next = $this->getCalendarLink(0, $year + 1);

        $s .= "<table class=\"calendar\" border=\"0\">\n";
        $s .= "<tr>";
    	$s .= "<td align=\"center\" valign=\"top\" align=\"left\">" . (($prev == "") ? "&nbsp;" : "<a href=\"$prev\">&lt;&lt;</a>")  . "</td>\n";
        $s .= "<td class=\"calendarHeader\" valign=\"top\" align=\"center\">" . (($this->startMonth > 1) ? $year . " - " . ($year + 1) : $year) ."</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" align=\"right\">" . (($next == "") ? "&nbsp;" : "<a href=\"$next\">&gt;&gt;</a>")  . "</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(0 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(1 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(2 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(3 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(4 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(5 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(6 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(7 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(8 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(9 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(10 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(11 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "</table>\n";

        return $s;
    }

    /*
        Adjust dates to allow months > 12 and < 0. Just adjust the years appropriately.
        e.g. Month 14 of the year 2001 is actually month 2 of year 2002.
    */
    function adjustDate($month, $year)
    {
    	$a = array();
        $a[0] = $month;
        $a[1] = $year;

        while ($a[0] > 12)
        {
            $a[0] -= 12;
            $a[1]++;
        }

        while ($a[0] <= 0)
        {
            $a[0] += 12;
            $a[1]--;
        }

        return $a;
    }

}

?>
