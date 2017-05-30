<?php

	/**
    * Query a transaction details
	**/
function devx_requery_payment( $txnref, $total ){

$product_id 	= "6207";
$mac_key="CEF793CBBE838AA0CBB29B74D571113B4EA6586D3BA77E7CFA0B95E278364EFC4526ED7BD255A366CDDE11F1F607F0F844B09D93B16F7CFE87563B2272007AB3";


$testmode = "yes";

if ( 'yes' == $testmode ) {
	$query_url = 'https://sandbox.interswitchng.com/webpay/api/v1/gettransaction.json';
} else {
	$query_url = 'https://webpay.interswitchng.com/paydirect/api/v1/gettransaction.json';
}
$url 	= "$query_url?productid=$product_id&transactionreference=$txnref&amount=$total";
$hashi 	= $product_id.$txnref.$mac_key;
$thash 	= hash("sha512", $hashi);

/*$headers = array(
	'Hash' => $hash
);*/
/*$args = array(
	'timeout'	=> 30,
	'headers' 	=> $headers
);*/
			
    $headers = array( "Hash: $thash " );
                        
        $ch = curl_init($url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,1);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,120);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER ,false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);

		//$response 		= wp_remote_get( $url, $args );
		$response  = curl_exec($ch); 
		//$response= json_decode($response['body'], true);
        $err = curl_error($ch);
		curl_close($ch);
		//$response = json_decode($response);
        
        if($err){
            echo $err;
        }
        else{
        
	    echo $response;
	  
        }
		}

//ADM40426310817
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Interswitch Payment Requery</title>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  
</head>
<body>
    <div class="row">
    <div class="col-md-6 col-md-offset-3" style="padding-top:100px">
            <h4 class="text-primary text-center" style="padding:15px 0px"> Provide Transaction Reference And Total Payment to get Payment Details</h4>
            <form action="<?php $_SERVER['PHP_SELF'];?>" method="POST" role="form">

                <!-- transaction reference field-->
                <div class="form-group">
                    <label for="txnref">Transaction Reference</label>
                    <input type="text" class="form-control" name="txnref" id="txnref" placeholder="Provide TransRef">
                </div>
                <!-- transaction reference field -->
    
                <!--total of transaction field -->
                  <div class="form-group">
                    <label for="total">Total Payment</label>
                    <input type="text" class="form-control"  name="total" id="total" placeholder="Provide TransTotal">
                </div>
                <!-- total of transaction field-->
                
                <input type="submit" name="fetch_details" class="btn btn-primary btn-block" value="ReQuery Payment" />
            </form>
            
            <?php 

  //query the api when call is made 
   if(!empty($_POST)){
            
        devx_requery_payment($_POST['txnref'], $_POST['total']);
       
    }

    ?>
    
    </div>
       
    </div>
</body>
</html>

