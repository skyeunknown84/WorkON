<?php

// get ID
$data = $_GET;

$page = 'secure';

$encrypt_1 = (($data*123456789*5678)/956783);

// encrypt data with base64 
$url_link = "&id=".urlencode(base64_encode($encrypt_1));

// decrypt data with base64
foreach($_GET as $key => $data) {

    $data2 = $_GET[$key] = base64_decode(urldecode($data));

    // then return back the data value to original data
    echo $encrypt_2 = ((($data2*956783)/5678)/123456789);
}

?>
<a href="<?=$url_link;?>">
    <input type="button" name="btn" value="Click URL">
</a>