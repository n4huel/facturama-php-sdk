<?php

require_once realpath(dirname(__FILE__)) . '/../lib/facturama.php';
require_once realpath(dirname(__FILE__)) . '/config.php';


$product = array();


$product['Name'] = 'Coca Cola';
$product['Unit'] = 10;
$product['IdentificationNumber']= 'CC';
$product['Description'] = "Coca Cola 2lt";
$product['Price'] = 6.0;


?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Create Product - Facturama v2</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>

<body>
<div class="container">
    <div class="page-header">
        <h1>Create Product - Facturama v2</h1>
    </div>
    <?php try {
        $response = Facturama_Product::create($product);
        if($response->getStatus() == 'SUCCESS'){
            echo '<div class="alert alert-success">SUCCESS: '.$status_desc;
            echo '</div>';
        }else{
            echo '<div class="alert alert-warning">'.$response->getStatus().': '.$response->getMessage();
            echo '</div>';
        }
    }catch (Facturama_Exception $e){
        echo '<pre>';
        var_dump((string)$e);
        echo '</pre>';
    }
    ?>
    <h1>Request</h1>

    <div>
        <?php var_dump($product); ?>
    </div>
    <h1>Response</h1>

    <div>
        <?php var_dump($response); ?>
    </div>
</div>
</html>