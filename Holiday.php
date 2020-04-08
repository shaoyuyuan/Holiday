<?php
/**
 * 判断日期是否为节假日，一个/多个月份的节假日
 * 采用第三方api：http://www.easybots.cn/holiday_api.html
 * *检查具体日期是否为节假日，工作日对应结果为 0, 休息日对应结果为 1, 节假日对应的结果为 2；
 * * 在需要控制器中 use 该 trait
 */

namespace Common\Traits;

ini_set('default_socket_timeout', -1);

/**
 * 获取节假日功能
 */
trait Holiday
{
    //api配置
    private $apiConfig = [
        'day'   => 'http://www.easybots.cn/api/holiday.php?d=',
        'month' => 'http://www.easybots.cn/api/holiday.php?m='
    ];
    //最大调用次数
    private $max = 10;
    //调用次数
    private static $num = 1;

    /**
     * 判断一个日期是否为节假日，日期可以是一个或多个
     * http://www.easybots.cn/api/holiday.php?d=20200401,20200403,20200405,20200501
     * @param $days 日期可以是一个或多个,用“,”分割
     */
    private function chekcDay($days){

    }
    /**
     * 判断一个日期是否为节假日，日期可以是一个或多个
     * http://www.easybots.cn/api/holiday.php?m=202004,202005
     */
    private function chekcMonth(){
        //文件地址
        $data_path = dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'wap' . DIRECTORY_SEPARATOR . 'data';

        //文件名
        $file_name = $data_path . DIRECTORY_SEPARATOR . date('Y') . '.json';
        //检测本地是否存储了假期数据
        if (file_exists($file_name)) {
            //存在获取数据
            $jsonData = file_get_contents($jsonFile);

            $jsonData = json_decode($jsonData, true);
        } else {
            //data文件夹不存在创建并给权限
            if (!file_exists($data_path)) {
                mkdir($data_path, 0777, true);
            }
            //不存在调用api获取数据，存储到文件中
            $year = $this->getMonth(date('Y'));
            $jsonData = $this->getRequest($this->apiConfig['month'] . $year);
            //返回数据为空递归调用获取
            if (empty($jsonData)) {
                
                if ($this->max > self::$num) {
                    //注意顺序，先++，在调用方法，否则死循环
                    self::$num++;
                    $this->chekcMonth();
                }
            } else {
                //存储到文件中
                file_put_contents($file_name, $jsonData);
            }
        }
        return $jsonData;
    }

    /**
     * 获取当年的月份
     * @param $year 年份 2020
     */
    private function getMonth($year){
        if (empty($year)) {
            $year = date('Y');
        }
        $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        $res = '';
        foreach ($months as $key => $val) {
            $res[] = $year . $val;
        }
        return join(',', $res);
    }

    /**
     * get请求
     * 
     */
    private function getRequest($url){

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 

        $output = curl_exec($ch);

        return $output;
    }
}
