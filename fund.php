<pre><?php

echo "<h1>Send Digibyte to this address to complete transaction</h1>";

$target = $_GET[target];
$image = "$target.png";

$exploded = explode("-",$target);

// This part runs after an external script send the transaction
//
$final=explode("-> ",`ls -l $target.final`);

if(isset($final[1]))
{
	echo "Done";
	// All finished 
	$basename=`basename $final[1]`;
	`rm $basename*`;
	echo "<meta http-equiv='refresh' content='0; url=../dgb/txids/index.php?txid=$final[1]'>";

}

echo "<img src=$image>";
echo "<br><h2>$target</h2>";


$txids = json_decode(file_get_contents("/var/www/html/dgb/unspent.json"));

foreach($txids as $txid)
{

	$magic = "6.000" . $exploded[1];
	$magic2 = "6.0000". $exploded[1];

//	$magic = "5.99976196";

//	echo $magic . "\n";

	if(($txid->amount == $magic2) || ($txid->amount == $magic))
		if($txid->address == "DDDE9cfLpaaQ4uyQioXKFSLXE9BPFw1bq9")
		{
		$input="$exploded[0]-$exploded[1].input";
		file_put_contents($input,"[ { \"txid\":\"$txid->txid\",\"vout\":$txid->vout } ]");
		echo "<h2>Funds Received!</h2>";
		print_r($txid);
	}
}	

echo "<meta http-equiv='refresh' content='10; url=fund.php?target=$_GET[target]'>";

echo "<a target=_target href=http://ledger.church/dgb/unspent.json>Check to see if funds have been received</a>";

