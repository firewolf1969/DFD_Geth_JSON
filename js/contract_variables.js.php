

<script type="text/javascript">
 
    // Solidity source code example
    var solidity_example;
    $.ajax({
            url: '<?=( $_POST['sol_file'] ? $_POST['sol_file'] : 'sol/greeter-default.sol' )?>',
            type: 'get',
            async: false,
            success: function(sol_code) {
             //console.log(sol_code);
             solidity_example = sol_code;
            }
    });
    
    
    

    var contract_source_code = <?=( $_POST['source_code'] ? "\$_POST['source_code']" : 'solidity_example' )?>;
    
    //console.log(contract_source_code);
    

// Only set compiler-related vars if compilers exist
if ( window.compilers.length > 0 ) {

                           
    // Solidity compiled source code array
    var solidity_compiled = web3.eth.compile.solidity(contract_source_code);
    
                            if ( solidity_compiled ) {
                                
                            // Bytecode
                            var compiled_array_bytecode = solidity_compiled[get_key(solidity_compiled)]['code'];
                            
                            // contract json abi, this is autogenerated using solc CLI
                            var compiled_array_abi = solidity_compiled[get_key(solidity_compiled)]['info']['abiDefinition'];
                            
                            // Get estimated Gas fee for delpoyment of currently edited contract
                            var gas_estimate = web3.eth.estimateGas({
                                                    from: coin_base, 
                                                    data: compiled_array_bytecode
                                                    });
                        
                            }
    

}

</script>