<?php

/**
** 两个日期之间的所有日期
**/
function betweenDates($startDate, $endDate)
{
    $alldates = array();
    $starTime = strtotime($startDate);
    $endTime  = strtotime($endDate);
    do { 
        //将 Timestamp 转成 ISO Date 输出
        $alldates[] = date('Ymd', $starTime);
    } while (($starTime += 86400) <= $endTime);
	return $alldates;
}

/**
** 两个日期之间的所有交易日的天数/非交易天数
**/
function getTradayNum($startDate, $endDate, $isTraday)
{
    //2015法定非交易日
    $holidays = array(
                '20150101',
                '20150102',
                '20150218',
                '20150219',
                '20150220',
                '20150223',
                '20150224',
                '20150406',
                '20150501',
                '20150622',
                '20150903',
                '20150904',
                '20151001',
                '20151002',
                '20151005',
                '20151006',
                '20151007'
                );
    
    if (strtotime($startDate) > strtotime($endDate)) list($startDate, $endDate) = array($endDate, $startDate);
    $startReduce = $endAdd = 0;
    //星期中的第几天
    $startWeekDayNum = date('N', strtotime($startDate));
    $startReduce     = ($startWeekDayNum == 7) ? 1 : 0;
    $endWeekDayNum   = date('N', strtotime($endDate));
    in_array($endWeekDayNum, array(6,7)) && $endAdd = ($endWeekDayNum == 7) ? 2 : 1;
    $alldaysNum      = abs(strtotime($endDate) - strtotime($startDate))/86400 + 1;
    $notradaysNum    = floor(($alldaysNum + $startWeekDayNum - 1 - $endWeekDayNum) / 7) * 2 - $startReduce + $endAdd;
    if ($isTraday) {
        $alldates   = betweenDates($startDate, $endDate);
        $tradaysNum = $alldaysNum - $notradaysNum;
        foreach($holidays as $hd)
        {
            if(in_array($hd, $alldates)){
                $tradaysNum--;
            }
        }
        return $tradaysNum;
    }
    return $notradaysNum;
}

echo getTradayNum(20151101,20151107,0);
