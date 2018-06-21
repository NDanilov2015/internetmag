<?php

function setActiveCategory($category, $output = 'active')
{
    return request()->category == $category ? $output : '';
}

function productImage($path)
{
    //Приколы с символьными ссылками изучить:
	//return $path && file_exists('storage/app/public/'.$path) ? 'storage/app/public/'.$path : asset('img/not-found.jpg');
	
	//Времянка без символьных ссылок
	return asset('img/' . $path);
}

/**
 * Добавить условие: если функция не определена
 *
 * вводные параметры:  $number = money_format($internationalFormat ? '%i' : '%n', $number); 
 * а что они вообще означают? %i, %n ?
 */
if (!function_exists('money_format'))
{
	/*
	function money_format($val, $symbol='$', $r=2)
	{
		$n = (float)$val; 
		$c = is_float($n) ? 1 : number_format($n, $r);
		$d = '.';
		$t = ',';
		$sign = ($n < 0) ? '-' : '';
		$i = $n=number_format(abs($n),$r); //string
		$j = (($j = strlen($i)) > 3) ? $j % 3 : 0; //int 1
		
		//Будем ругаться на non-numeric
		return  $symbol . $sign .($j ? substr($i, 0, $j) + $t : '').preg_replace('/(\d{3})(?=\d)/',"$1" + $t,substr($i,$j)) ;
	}
	*/
	
	function money_format($format, $number) {
        $regex = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?' .
                '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
        if (setlocale(LC_MONETARY, 0) == 'C') {
            setlocale(LC_MONETARY, '');
        }
        $locale = localeconv();		
        preg_match_all($regex, $format, $matches, PREG_SET_ORDER);
        foreach ($matches as $fmatch) {
            $value = floatval($number);
            $flags = array(
                'fillchar' => preg_match('/\=(.)/', $fmatch[1], $match) ?
                        $match[1] : ' ',
                'nogroup' => preg_match('/\^/', $fmatch[1]) > 0,
                'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ?
                        $match[0] : '+',
                'nosimbol' => preg_match('/\!/', $fmatch[1]) > 0,
                'isleft' => preg_match('/\-/', $fmatch[1]) > 0
            );
            $width = trim($fmatch[2]) ? (int) $fmatch[2] : 0;
            $left = trim($fmatch[3]) ? (int) $fmatch[3] : 0;
            $right = trim($fmatch[4]) ? (int) $fmatch[4] : $locale['int_frac_digits'];
            $conversion = $fmatch[5];

            $positive = true;
            if ($value < 0) {
                $positive = false;
                $value *= -1;
            }
            $letter = $positive ? 'p' : 'n';

            $prefix = $suffix = $cprefix = $csuffix = $signal = '';

            $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];
            switch (true) {
                case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+':
                    $prefix = $signal;
                    break;
                case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+':
                    $suffix = $signal;
                    break;
                case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+':
                    $cprefix = $signal;
                    break;
                case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+':
                    $csuffix = $signal;
                    break;
                case $flags['usesignal'] == '(':
                case $locale["{$letter}_sign_posn"] == 0:
                    $prefix = '(';
                    $suffix = ')';
                    break;
            }
            if (!$flags['nosimbol']) {
                $currency = $cprefix .
                        ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) .
                        $csuffix;
            } else {
                $currency = $cprefix .$csuffix;
            }
            $space = $locale["{$letter}_sep_by_space"] ? ' ' : '';

            $value = number_format($value, $right, $locale['mon_decimal_point'], $flags['nogroup'] ? '' : $locale['mon_thousands_sep']);
            $value = @explode($locale['mon_decimal_point'], $value);

            $n = strlen($prefix) + strlen($currency) + strlen($value[0]);
            if ($left > 0 && $left > $n) {
                $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];
            }
            $value = implode($locale['mon_decimal_point'], $value);
            if ($locale["{$letter}_cs_precedes"]) {
                $value = $prefix . $currency . $space . $value . $suffix;
            } else {
                $value = $prefix . $value . $space . $currency . $suffix;
            }
            if ($width > 0) {
                $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ?
                                STR_PAD_RIGHT : STR_PAD_LEFT);
            }

            $format = str_replace($fmatch[0], $value, $format);
        }
		
		//При ошибках обернуть в utf8_encode()		
		return utf8_encode($format);
    }
	
} //end if


/**
 * Форматирует "страшненькую" цену вида 203301 в
 * нормальный формат 2,033.01 (две тысячи тридцать три доллара 1 цент)
 */
function priceStdFormat($price)
{
	return money_format('%i', $price / 100);
}


function getNumbers()
{
    $tax = config('laracart.tax') / 100;
	
	//Переписывать под нас остальное:
    $discount = session()->get('coupon')['discount'] ?? 0; //null coalescence
    $code = session()->get('coupon')['name'] ?? null;
	
    $newSubtotal = (LaraCart::subtotal() - $discount);
    if ($newSubtotal < 0) {
        $newSubtotal = 0;
    }
    $newTax = $newSubtotal * $tax;
    $newTotal = $newSubtotal * (1 + $tax);

    return collect([
        'tax' => $tax,
        'discount' => $discount,
        'code' => $code,
        'newSubtotal' => $newSubtotal,
        'newTax' => $newTax,
        'newTotal' => $newTotal,
    ]);
} 