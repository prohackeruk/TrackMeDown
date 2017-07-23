<!-- A function for creating cryptographically secure tokens. Stolen (I mean 'open-sourced') from here: https://stackoverflow.com/questions/4356289/php-random-string-generator/31107425#31107425 -->
<?php
	function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
	{
    	$str = '';
    	$max = mb_strlen($keyspace, '8bit') - 1;
    	for ($i = 0; $i < $length; ++$i) {
        	$str .= $keyspace[random_int(0, $max)];
    	}
    	return $str;
	}
?>
