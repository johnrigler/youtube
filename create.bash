#!/bin/bash

CMD="/usr/local/sbin/digibyte-cli"

cd /var/www/html/youtube

while true
do

ls *.input | while read input
do
	output=$(echo $input | sed 's/.input//')
	$CMD createrawtransaction "$(cat $input)" "$(cat $output)" > $$.create
	$CMD fundrawtransaction $(cat $$.create) | jq .hex | xargs > $$.fund
	rm $$.create
	$CMD signrawtransactionwithwallet $(cat $$.fund) | jq .hex | xargs > $$.signed
	rm $$.fund
	txid=$($CMD decoderawtransaction $(cat $$.signed) | head -2 | tail -1 | cut -c 12-75)
	txid2=$($CMD sendrawtransaction $(cat $$.signed))
	rm $$.signed
	echo $txid $txid2
	if [[ $txid == $txid2 ]]
	then
	echo "Done"
	$CMD getrawtransaction $txid 1 > /var/www/html/dgb/txids/$txid
        ln -s /var/www/html/dgb/txids/$txid $output.final
	chown www-data:www-data $output.final
#	rm *.input $output

	fi
done

sleep 5

done
