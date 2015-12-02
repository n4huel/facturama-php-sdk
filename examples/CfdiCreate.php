<?php

require_once realpath(dirname(__FILE__)) . '/../lib/facturama.php';
require_once realpath(dirname(__FILE__)) . '/config.php';



if(isset($_POST['Subtotal']) and 
   isset($_POST['Total'])){

    $cfdi = array();
    $cfdi['CfdiType'] = 'ingreso';
    $cfdi['IdBranchOffice'] = 'KI2C2GeiviBPKVZRxX6GLg2';
    $cfdi['IdClient'] = 'MdkB7dsxLN8XmQGjKHHF4g2';
    $cfdi['PaymentMethod'] = 'TRANSFERENCIA ELECTRONICA DE FONDOS';
    $cfdi['PaymentAccountNumber'] = '5143';
    $cfdi['Currency'] = 'MXN';
    $cfdi['Subtotal'] = $_POST["Subtotal"];
    $cfdi['Discount'] = 0.0;
    $cfdi['Total'] = $_POST["Total"];

    try {
    $response = Facturama_Cfdi::create($cfdi);
    if($response->getStatus() == 'SUCCESS'){
        echo '<div class="alert alert-success">SUCCESS: '.$status_desc;
        echo '</div>';
    }else{
        echo '<div class="alert alert-warning">'.$response->getStatus().': '.$response->getMessage();
        echo ' ('.$response->getError().')';
        echo '</div>';
    }
    }catch (Facturama_Exception $e){
        echo '<pre>';
        var_dump((string)$e);
        echo '</pre>';
    }
}
?>



<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Create Cfdi - Facturama v1</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>Create Cfdi - Facturama v1</h1>
    </div>
    <div>
        <form action="CfdiCreate.php" method="post">
            <label>NameId</label>
            <input type='text' name='NameId' placeholder="Introduce"></br></br>
            <label>CfdiType</label>
            <input type='text' name='CfdiType'></br></br>
            <label>IdBranchOffice:</label> 
            <input type='text' name='IdBranchOffice'></br></br>
            <label>IdClient:</label>   
            <input type='text' name='IdClient'></br></br>
            <label>PaymentMethod:</label>  
            <input type='text' name='PaymentMethod'></br></br>
            <label>PaymentAccountNumber:</label>   
            <input type='text' name='PaymentAccountNumber'></br></br>
            <label>Currency:</label>   
            <input type='text' name='Currency'></br></br>
            <label>IdProduct:</label>  
            <input type='text' name='IdProduct'></br></br>
            <label>Quantity:</label>  
            <input type='text' name='Quantity'></br></br>
            <label>Tax:</label>   
            <input type='text' name='Tax'></br></br>
            <label>Subtotal:</label>  
            <input type='text' name='Subtotal'></br></br>
            <label>UnitPrice:</label> 
            <input type='text' name='UnitPrice'></br></br>
            <label>Total: </label> 
            <input type='text' name='Total'></br></br>
            <label>Name: </label>  
            <input type='text' name='Name'></br></br>
            <label>Rate: </label>  
            <input type='text' name='Rate'></br></br>
            <label>Type: </label>  
            <input type='text' name='Type'></br></br>
            <input type="submit" value="Enviar">
        </form>
    </div>
</div>
</html>