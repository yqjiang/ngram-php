<?php
function distance($array1, $array2) {
	$inarray = array();
	foreach($array1 as $key1 => $value1) {
		foreach($array2 as $key2 => $value2) {
			if(strcmp($key1,$key2)==0)
			{
				$distance = 2.0*($value1-$value2)/($value1+$value2);
				print "$key1 $key2 2*($value1 - $value2)/($value1 + $value2) $distance\n";
				$distance *= $distance;
				$inarray[$key1] = $distance;
				print "$key1 $key2 2*($value1 - $value2)/($value1 + $value2) $distance\n";
			}
		}
	}
	$total = 0;
	foreach($inarray as $value) {
		$total += $value;
	}
	print_r($inarray);
	$notin = sizeof($array1)+sizeof($array2)-2*sizeof($inarray);
	$total += $notin*4;
	return $total;
}
?>