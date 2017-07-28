<?php
namespace Other\Model;

/**
 * 获取时间相关的操作
 */
class dateTool{
    /**
     * 静态函数，处理时间格式
     * @param string $val 未格式化的时间
     *                  形式1：100234132[时间戳]
     *                  形式2：2017-04-12 10:29:10[用户输入的时间]
     *                  形式3：false[返回当前的时间戳]
     * @param string $value 要将时间格式化成什么，默认格式：Y-m-d H:i:00
     * @return string 格式化后的时间
     */
    public static function date($val = false,$value = 'Y-m-d H:i:00'){
        if($val){
            if(!is_numeric($val)){
                return date($value, strtotime($val));
            }
            return date($value,$val);
        }
        return date($value);
    }
    
    /**
     * 静态函数，将时间格式化成时间戳
     * @param string $val 未格式化的时间
     *                  形式1：100234132[时间戳]
     *                  形式2：2017-04-12 10:29:10[用户输入的时间]
     *                  形式3：false[返回当前的时间戳]
     * @param string $value 要将时间格式化成什么，默认格式：Y-m-d H:i:00
     * @return number 时间戳
     */
    public static function getIntTime($val = false,$value = 'Y-m-d H:i:00'){
        return strtotime(self::date($val,$value));
    }

    /**
     * 计算时间相差天数
     * @param  string $start 开始时间，格式：Y-m-d
     * @param  string $end   结束时间，格式：Y-m-d
     * @return intval        相差的天数，有可能为负数值
     */
    public static function dayTimeDifferent($start = false, $end = false) {
        return floor( ( self::getIntTime($end, 'Y-m-d') - self::getIntTime($start, 'Y-m-d') ) / 86400 ) + 1;
    }

    /**
     * 还款月份生成器
     * @param  string $start_time 开始时间，格式：Y-m-d
     * @param  string $endtime    结束时间，格式：Y-m-d
     * @param  string $times      时间步长单位，默认月month，可选项：日day，月month，年year
     * @param  string $day        还款日，默认借款日
     * @return array              生成的还款计划表数组
     *          $d[x]['start_times']    开始时间
     *          $d[x]['end_times']      结束时间
     *          $d[x]['pay_times']      还款时间
     *          $d[x]['pay_days']       还款天数
     */
    public static function repaymentMonth($start_times, $endtimes, $times = 'month', $day = false) {
        $start_time = self::date($start_times,'Y-m-d');
        $endtime = self::date($endtimes,'Y-m-d');
        // 初始化还款日
        if (!$day) {
            $day = date('d', strtotime($start_time));
        }
        $return_array = array();
        for ($i = 1; $i <= 36; $i ++) {
            $next_time = date('Y-m-' . $day, strtotime('+1' . $times, strtotime($start_time)));
            if (strtotime($next_time) <= strtotime($endtime)) {
                $return_array[] = array(
                    'start_times' => $start_time,
                    'end_times' => date('Y-m-d', strtotime('-1 day', strtotime($next_time))),
                    'pay_times' => $next_time,
                    'pay_days' => self::dayTimeDifferent($start_time, date('Y-m-d', strtotime('-1 day', strtotime($next_time)))),
                );
                $start_time = $next_time;
            } else {
                if (strtotime($start_time) < strtotime($endtime)) {
                    $return_array[] = array(
                        'start_times' => $start_time,
                        'end_times' => date('Y-m-d', strtotime('-1 day', strtotime($endtime))),
                        'pay_times' => $endtime,
                        'pay_days' => self::dayTimeDifferent($start_time, date('Y-m-d', strtotime('-1 day', strtotime($endtime)))),
                    );
                }
                return $return_array;
            }
        }
    }

    /**
     * 还款月份生成器
     * @param  string $start_time 开始时间，格式：Y-m-d
     * @param  integer $count     计算周期，一个月为1，两个月为2...[如果步长为月]
     * @param  string $times      时间步长单位，默认月month，可选项：日day，月month，年year
     * @return array              生成的还款计划表数组
     *          $d[x]['start_times']    开始时间
     *          $d[x]['end_times']      结束时间
     *          $d[x]['pay_days']       计时天数
     */
    public static function countMonthList($start_times,$count = 1, $times = 'month') {
        $intTime = self::getIntTime($start_times);
        $return_data = array();
        for($i = 1 ; $i <= $count ; $i ++){
            $start = self::date(strtotime('+'.($i-1).' '.$times,$intTime),'Y-m-d');
            $end = self::date(strtotime('+'.$i.' '.$times,$intTime),'Y-m-d');
            $end = self::date(strtotime('-1 day', self::getIntTime($end)),'Y-m-d');
            $return_data[] = array(
                'start_times' => $start,
                'end_times' => $end,
                'pay_days' => self::dayTimeDifferent($start, $end),
            );
        }
        return $return_data;
    }
}