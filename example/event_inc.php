<?php

require("../config.php");

?>
<!doctype>
<html>

<head>
    <script type='text/javascript'>
    // Keep above all other script
    var proxy_url = "//<?=$proxy_server?>";
    </script>
    <script type="text/javascript" src="../js/web3/dist/web3.js"></script>
    <script type="text/javascript">
    var Web3 = require('web3');
    var web3 = new Web3();
        web3.setProvider(new web3.providers.HttpProvider(proxy_url));

        var source = "" +
        "contract Contract { " +
        "   event Incremented(bool indexed odd, uint x); " +
        "   function Contract() { " +
        "        x = 70; " +
        "    } " +
        "    function inc() { " +
        "        ++x; " +
        "        Incremented(x % 2 == 1, x); " +
        "    } " +
        "    uint x; " +
        "}";

        var compiled = web3.eth.compile.solidity(source);
        var code = compiled.Contract.code;
        var abi = compiled.Contract.info.abiDefinition;

        var address;
        var contract;
        var inc;

        var update = function (err, x) {
            document.getElementById('result').textContent = JSON.stringify(x, null, 2);
        };
    
        var createContract = function () {
            // let's assume that we have a private key to coinbase ;)
            web3.eth.defaultAccount = web3.eth.coinbase;
            
            document.getElementById('create').style.visibility = 'hidden';
            document.getElementById('status').innerText = "transaction sent, waiting for confirmation";

            web3.eth.contract(abi).new({data: code}, function (err, c) {
                if (err) {
                    console.error(err);
                    return;

                // callback fires twice, we only want the second call when the contract is deployed
                } else if(c.address){

                    contract = c;
                    console.log('address: ' + contract.address);
                    document.getElementById('status').innerText = 'Mined!';
                    document.getElementById('call').style.visibility = 'visible';

                    inc = contract.Incremented({odd: true}, update);
                }
            });
        };

        var counter = 0;
        var callContract = function () {
            counter++;
            var all = 70 + counter;
            document.getElementById('count').innerText = 'Transaction sent ' + counter + ' times. ' + 
                'Expected x value is: ' + (all - (all % 2 ? 0 : 1)) + ' ' +
                'Waiting for the blocks to be mined...';
                 
            contract.inc();
        };


    </script>
    </head>

    <body>
        <div id="status"></div>
        <div>
            <button id="create" type="button" onClick="createContract();">create contract</button>
        </div>
        <div>
            <button id="call" style="visibility: hidden;" type="button" onClick="callContract();">test1</button>
        </div>
        <div id='count'></div>
        <div id="result">
        </div>
    </body>
</html>
