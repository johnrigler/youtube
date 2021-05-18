<pre>
<?php

include "/var/www/html/src/lib.php";

$address = "digibyte:DDDE9cfLpaaQ4uyQioXKFSLXE9BPFw1bq9";

$address = "dgb1q4rdplx7k92y4llzq6v6qf9mgpr5zey2j8wp767";
$full_address = "digibyte:$address";

$subject = rtrim(unspendable("DAx",$_POST[subject]));
$description = rtrim(unspendable("DCx",$_POST[description]));
$url = str2hex(substr($_POST[url],32));
$url = str2hex($_POST[url]);
echo $url;

$string = "{\n";
$string = $string . "\"$subject\":0.00001,\n";
$string = $string . "\"$_POST[type]\":0.00001,\n";
$string = $string . "\"$description\":0.00001,\n";
$string = $string . "\"data\":\"$url\"\n";
$string = $string . "}\n";

file_put_contents($subject,$string);
$sum = explode(" ",`cat $subject | sum`);
$ext = $sum[0];
`mv $subject $subject-$ext`;
echo $string;

$amount = "6.000" . $ext;

$checkout="$full_address?amount=6.000$ext";

echo $checkout;

`echo -n '$checkout' | qr > $subject-$ext.png`;

echo "<hr><img width=200px height=200px src='$subject-$ext.png'>";

//file_put_contents($subject.input,"$input");

/// You will need to sleep and recheck normally
// 
//
$count = 0;
$input = "";

	global $input;
	$txids = json_decode(file_get_contents("/var/www/html/dgb/unspent.json"));
	foreach($txids as $txid)
	{ 
	//	print_r($txid);
		if($txid->amount == $amount)
			if($txid->address == $address)
		{
			$input = "[ { \"txid\":\"$txid->txid\" , \"vout\":$txid->vout } ]";
			break;
		}
	}

	echo "$input";
	echo "<meta http-equiv='refresh' content='0; url=fund.php?target=$subject-$ext'>";

