<?php
function getPermissionDefault($level)
{
    $roles = array();
    if (!in_array($level, ['SA', 'SSA'])) {
        $level = 'default';
    }
    $roles['SA'] = array(
        'dmchucvu' => array(
            'view' => 1,
            'create' => 1,
            'edit' => 1,
            'delete' => 1
        ),
        'dmphucap' => array(
            'view' => 1,
            'create' => 0,
            'edit' => 1,
            'delete' => 0
        ),
        'dmnguonkp' => array(
            'view' => 1,
            'create' => 0,
            'edit' => 1,
            'delete' => 0
        ),
        'dmlinhvuchd' => array(
            'view' => 1,
            'create' => 0,
            'edit' => 1,
            'delete' => 0
        ),
        'dmngachluong' => array(
            'view' => 1,
            'create' => 1,
            'edit' => 1,
            'delete' => 0
        ),
        'dmphanloaidv' => array(
            'view' => 1,
            'create' => 0,
            'edit' => 1,
            'delete' => 0
        ),
        'dmphanloaict' => array(
            'view' => 1,
            'create' => 1,
            'edit' => 1,
            'delete' => 0
        ),
        'dmttqd' => array(
            'view' => 1,
            'create' => 1,
            'edit' => 1,
            'delete' => 0
        ),
        'congthucmtm' => array(
            'view' => 1,
            'create' => 1,
            'edit' => 1,
            'delete' => 0
        ),
        'qltaikhoan' => array(
            'view' => 1,
            'create' => 1,
            'edit' => 1,
            'delete' => 1
        ),
        'qldonvi' => array(
            'view' => 1,
            'create' => 1,
            'edit' => 1,
            'delete' => 1,
            'data' => 1
        ),
    );

    $roles['SSA'] = array(
        'dmchucvu' => array(
            'view' => 1,
            'create' => 1,
            'edit' => 1,
            'delete' => 1
        ),
        'dmphucap' => array(
            'view' => 1,
            'create' => 1,
            'edit' => 1,
            'delete' => 1
        ),
        'dmnguonkp' => array(
            'view' => 1,
            'create' => 1,
            'edit' => 1,
            'delete' => 1
        ),
        'dmlinhvuchd' => array(
            'view' => 1,
            'create' => 1,
            'edit' => 1,
            'delete' => 1
        ),
        'dmngachluong' => array(
            'view' => 1,
            'create' => 1,
            'edit' => 1,
            'delete' => 1
        ),
        'dmphanloaidv' => array(
            'view' => 1,
            'create' => 1,
            'edit' => 1,
            'delete' => 1
        ),
        'dmphanloaict' => array(
            'view' => 1,
            'create' => 1,
            'edit' => 1,
            'delete' => 1
        ),
        'dmttqd' => array(
            'view' => 1,
            'create' => 1,
            'edit' => 1,
            'delete' => 1
        ),
        'congthucmtm' => array(
            'view' => 1,
            'create' => 1,
            'edit' => 1,
            'delete' => 1
        ),
        'qltaikhoan' => array(
            'view' => 1,
            'create' => 1,
            'edit' => 1,
            'delete' => 1
        ),
        'qldonvi' => array(
            'view' => 1,
            'create' => 1,
            'edit' => 1,
            'delete' => 1,
            'data' => 1
        ),
    );

    $roles['default'] = array(
        'dmchucvu' => array(
            'view' => 1,
            'create' => 0,
            'edit' => 0,
            'delete' => 0
        ),
        'dmphucap' => array(
            'view' => 1,
            'create' => 0,
            'edit' => 0,
            'delete' => 0
        ),
        'dmnguonkp' => array(
            'view' => 1,
            'create' => 0,
            'edit' => 0,
            'delete' => 0
        ),
        'dmlinhvuchd' => array(
            'view' => 1,
            'create' => 0,
            'edit' => 0,
            'delete' => 0
        ),
        'dmngachluong' => array(
            'view' => 1,
            'create' => 0,
            'edit' => 0,
            'delete' => 0
        ),
        'dmphanloaidv' => array(
            'view' => 1,
            'create' => 0,
            'edit' => 0,
            'delete' => 0
        ),
        'dmphanloaict' => array(
            'view' => 1,
            'create' => 0,
            'edit' => 0,
            'delete' => 0
        ),
        'dmttqd' => array(
            'view' => 1,
            'create' => 0,
            'edit' => 0,
            'delete' => 0
        ),
        'congthucmtm' => array(
            'view' => 0,
            'create' => 0,
            'edit' => 0,
            'delete' => 0
        ),
        'qltaikhoan' => array(
            'view' => 0,
            'create' => 0,
            'edit' => 0,
            'delete' => 0
        ),
        'qldonvi' => array(
            'view' => 0,
            'create' => 0,
            'edit' => 0,
            'delete' => 0,
            'data' => 0
        ),
    );

    return json_encode($roles[$level]);
}

function can($module = null, $action = null)
{
    //$permission = !empty(session('admin')->permission) ? session('admin')->permission : getPermissionDefault(session('admin')->level);
    $per = getPermissionDefault(session('admin')->sadmin);
    $per = json_decode($per, true);
    //dd($per);
    if (isset($per[$module][$action]) && $per[$module][$action] == 1) {
        return true;
    } else {
        return false;
    }
}

function getDayVn($date)
{
    if ($date == NULL || $date == null || $date == '' || $date == '0000-00-00') {
        return '';
    } else {
        return date('d/m/Y', strtotime($date));
    }
}

function Date2Str($date)
{
    if ($date == NULL || $date == null || $date == '' || $date == '0000-00-00') {
        return 'ngày ... tháng ... năm ...';
    } else {
        $day = strtotime($date);
        return 'ngày ' . date('d', $day) . ' tháng ' . date('m', $day) . ' năm ' . date('Y', $day);
    }
}

function getDateTime($date)
{
    if ($date != '')
        return $date;
    else
        return NULL;
}

function getDateToDb($value)
{
    if ($value == '') {
        return null;
    }

    //file excel để format Date 01/12/2018 => 01-12-18
    if (strpos($value, '-') > -1) {
        //định dạng 01-06-17 m-d-Y
        $a_val = explode('-', $value);
        $ngay = $a_val[1];
        $thang = $a_val[0];
        $nam = isset($a_val[2]) ? $a_val[2] : date('Y');
        $nam = $nam < 2000 ? $nam + 2000 : $nam;
        return date('Y-m-d', strtotime($ngay . '-' . $thang . '-' . $nam));
    }

    if (strpos($value, '/') > -1) {
        //định dạng06/01/2017: d-m-Y
        $a_val = explode('/', $value);
        $ngay = $a_val[0];
        $thang = $a_val[1];
        $nam = isset($a_val[2]) ? $a_val[2] : date('Y');
        $nam = $nam < 2000 ? $nam + 2000 : $nam;
        return date('Y-m-d', strtotime($ngay . '-' . $thang . '-' . $nam));
    }
    //$str = strtotime(str_replace('/', '-', $value));
    //return date('Y-m-d', $str);
    return null;
}

function getDbl($obj)
{
    $obj = str_replace(',', '', $obj);
    $obj = str_replace('.', '', $obj);
    if (is_numeric($obj)) {
        return $obj;
    } else {
        return 0;
    }
}

function chkDbl($obj)
{
    $obj = str_replace(',', '', $obj);
    $obj = str_replace('%', '', $obj);
    if (is_numeric($obj)) {
        return $obj;
    } else {
        return 0;
    }
}

function canGeneral($module = null, $action = null)
{
    $model = \App\GeneralConfigs::first();
    $setting = json_decode($model->setting, true);

    if (isset($setting[$module][$action]) && $setting[$module][$action] == 1)
        return true;
    else
        return false;
}

function getDouble($str)
{
    $sKQ = 0;
    $str = str_replace(',', '', $str);
    $str = str_replace('.', '', $str);
    //if (is_double($str))
    $sKQ = $str;
    return floatval($sKQ);
}

function chuyenkhongdau($str)
{
    if (!$str) return false;
    $utf8 = array(
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        'd' => 'đ|Đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        'i' => 'í|ì|ỉ|ĩ|ị|Í|Ì|Ỉ|Ĩ|Ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ|Ý|Ỳ|Ỷ|Ỹ|Ỵ',
    );
    foreach ($utf8 as $ascii => $uni) $str = preg_replace("/($uni)/i", $ascii, $str);
    return $str;
}

function chuanhoachuoi($text)
{
    $text = strtolower(chuyenkhongdau($text));
    $text = str_replace("ß", "ss", $text);
    $text = str_replace("%", "", $text);
    $text = preg_replace("/[^_a-zA-Z0-9 -]/", "", $text);
    $text = str_replace(array('%20', ' '), '-', $text);
    $text = str_replace("----", "-", $text);
    $text = str_replace("---", "-", $text);
    $text = str_replace("--", "-", $text);
    return $text;
}

function chuanhoatruong($text)
{
    $text = strtolower(chuyenkhongdau($text));
    $text = str_replace("ß", "ss", $text);
    $text = str_replace("%", "", $text);
    $text = preg_replace("/[^_a-zA-Z0-9 -]/", "", $text);
    $text = str_replace(array('%20', ' '), '_', $text);
    $text = str_replace("____", "_", $text);
    $text = str_replace("___", "_", $text);
    $text = str_replace("__", "_", $text);
    return $text;
}

function removespace($text)
{
    $text = trim($text);
    $text = str_replace(array('%20', ' '), '_', $text);
    $text = str_replace("____", "_", $text);
    $text = str_replace("___", "_", $text);
    $text = str_replace("__", "_", $text);
    $text = str_replace("_", " ", $text);
    return $text;
}

function getPhanTram1($giatri, $thaydoi)
{
    $kq = 0;
    if ($thaydoi == 0 || $giatri == 0) {
        return '';
    }
    if ($giatri < $thaydoi) {
        $kq = round((($thaydoi - $giatri) / $giatri) * 100, 2) . '%';
    } else {
        $kq = '-' . round((($giatri - $thaydoi) / $giatri) * 100, 2) . '%';
    }
    return $kq;
}

function getPhanTram2($giatri, $thaydoi)
{
    if ($thaydoi == 0 || $giatri == 0) {
        return '';
    }
    return round(($thaydoi / $giatri) * 100, 2) . '%';
}

function getConditions($inputs, $exists, $table)
{
    $b_dk = false;
    $s_sql = '';
    if (!is_array($inputs)) return $s_sql;

    foreach ($inputs as $key => $value) {
        if (in_array($key, $exists) || $value == '') continue;
        if ($b_dk) {
            $s_sql .= ' and ';
        }
        if (strtotime($value)) {
            if ($key == 'tungay') {
                $s_sql .= $table . '.' . $key . ">='" . $value . "'";
            } else {
                $s_sql .= $table . '.' . $key . "<='" . $value . "'";
            }
        } else {
            $s_sql .= $table . '.' . $key . "='" . $value . "'";
        }
        $b_dk = true;
    }
    return $s_sql;
}

function convert2date($ngaythang)
{
    if ($ngaythang == '') {
        return null;
    }
    return date('Y-m-d', strtotime(str_replace('/', '-', $ngaythang)));
}

function convert2str($number)
{
    if (!is_numeric($number)) {
        return '';
    } else {
        if ($number < 10) {
            return '0' . strval($number);
        } else {
            return strval($number);
        }
    }
}

function convert2Roman($num)
{
    $n = intval($num);
    $res = '';

    //array of roman numbers
    $romanNumber_Array = array(
        'M'  => 1000,
        'CM' => 900,
        'D'  => 500,
        'CD' => 400,
        'C'  => 100,
        'XC' => 90,
        'L'  => 50,
        'XL' => 40,
        'X'  => 10,
        'IX' => 9,
        'V'  => 5,
        'IV' => 4,
        'I'  => 1
    );

    foreach ($romanNumber_Array as $roman => $number) {
        //divide to get  matches
        $matches = intval($n / $number);

        //assign the roman char * $matches
        $res .= str_repeat($roman, $matches);

        //substract from the number
        $n = $n % $number;
    }

    // return the result
    return $res;
}

//$unit = 1 => đơn vị tính đồng
//$unit = 2 => đơn vị tính nghìn đồng
//$unit = 3 => đơn vị tính triệu đồng
function dinhdangso($number, $decimals = 0, $unit = '1', $dec_point = ',', $thousands_sep = '.')
{
    if (!is_numeric($number) || $number == 0) {
        return '';
    }
    $r = $unit;
    //dd($unit);
    switch ($unit) {
        case 2: {
                $decimals = 3;
                $r = 1000;
                break;
            }
        case 3: {
                $decimals = 5;
                $r = 1000000;
                break;
            }
    }

    $number = round($number / $r, $decimals);
    //kết quả dạng 985,40000 => dùng là duyệt ngược nếu = 0 => loại bỏ
    return number_format($number, $decimals, $dec_point, $thousands_sep);
}

function trim_zeros($str)
{
    if (!is_string($str)) return $str;
    return preg_replace(array('`\.0+$`', '`(\.\d+?)0+$`'), array('', '$1'), $str);
}

function dinhdangsothapphan($number, $decimals = 0)
{
    if (!is_numeric($number) || $number == 0) {
        return '';
    }
    $number = round($number, $decimals);
    $str_kq = trim_zeros(number_format($number, $decimals));
    for ($i = 0; $i < strlen($str_kq); $i++) {
        if ($str_kq[$i] == '.') {
            $str_kq[$i] = ',';
        } elseif ($str_kq[$i] == ',') {
            $str_kq[$i] = '.';
        }
    }
    //$a_so = str_split($str_kq);

    //$str_kq = str_replace(",", ".", $str_kq);
    //$str_kq = str_replace(".", ",", $str_kq);
    return $str_kq;
    //return number_format($number, $decimals ,$dec_point, $thousands_sep);
    //làm lại hàm chú ý đo khi các số thập phân nếu làm tròn thi ko bỏ dc số 0 đằng sau dấu ,
    // round(5.4,4) = 5,4000
}

function unset_key($data, $array_key)
{
    $a_kq = array();
    foreach ($data as $dt) {
        foreach ($array_key as $value) {
            if (array_key_exists($value, $dt)) {
                unset($dt[$value]);
            }
        }
        $a_kq[] = $dt;
    }

    return $a_kq;
}

function Dbl2Str($amount, $upcase = true, $low = false)
{
    if ($low) {
        $amount = abs($amount);
    }

    if ($amount <= 0) {
        return '';
    }
    $amount = (int)chkDbl($amount);
    $Text = array("không", "một", "hai", "ba", "bốn", "năm", "sáu", "bảy", "tám", "chín");
    $TextLuythua = array("", "nghìn", "triệu", "tỷ", "ngàn tỷ", "triệu tỷ", "tỷ tỷ");
    $textnumber = "";
    $length = strlen($amount);

    for ($i = 0; $i < $length; $i++)
        $unread[$i] = 0;

    for ($i = 0; $i < $length; $i++) {
        $so = substr($amount, $length - $i - 1, 1);

        if (($so == 0) && ($i % 3 == 0) && ($unread[$i] == 0)) {
            for ($j = $i + 1; $j < $length; $j++) {
                $so1 = substr($amount, $length - $j - 1, 1);
                if ($so1 != 0)
                    break;
            }

            if (intval(($j - $i) / 3) > 0) {
                for ($k = $i; $k < intval(($j - $i) / 3) * 3 + $i; $k++)
                    $unread[$k] = 1;
            }
        }
    }

    for ($i = 0; $i < $length; $i++) {
        $so = substr($amount, $length - $i - 1, 1);
        if ($unread[$i] == 1)
            continue;

        if (($i % 3 == 0) && ($i > 0))
            $textnumber = $TextLuythua[$i / 3] . " " . $textnumber;

        if ($i % 3 == 2)
            $textnumber = 'trăm ' . $textnumber;

        if ($i % 3 == 1)
            $textnumber = 'mươi ' . $textnumber;


        $textnumber = $Text[$so] . " " . $textnumber;
    }

    //Phai de cac ham replace theo dung thu tu nhu the nay
    $textnumber = str_replace("không mươi", "lẻ", $textnumber);
    $textnumber = str_replace("lẻ không", "", $textnumber);
    $textnumber = str_replace("mươi không", "mươi", $textnumber);
    $textnumber = str_replace("một mươi", "mười", $textnumber);
    $textnumber = str_replace("mươi năm", "mươi lăm", $textnumber);
    $textnumber = str_replace("mươi một", "mươi mốt", $textnumber);
    $textnumber = str_replace("mười năm", "mười lăm", $textnumber);

    return $upcase ? ucfirst($textnumber . " đồng chẵn") : $textnumber . " đồng chẵn";
}
function toAlpha($data)
{
    $alphabet =   array('', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
    $alpha_flip = array_flip($alphabet);
    if ($data <= 25) {
        return $alphabet[$data];
    } elseif ($data > 25) {
        $dividend = ($data + 1);
        $alpha = '';
        $modulo = '';
        while ($dividend > 0) {
            $modulo = ($dividend - 1) % 26;
            $alpha = $alphabet[$modulo] . $alpha;
            $dividend = floor((($dividend - $modulo) / 26));
        }
        return $alpha;
    }
}
