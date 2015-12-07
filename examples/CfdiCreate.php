<?php

require_once realpath(dirname(__FILE__)) . '/../lib/facturama.php';
require_once realpath(dirname(__FILE__)) . '/config.php';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    $cfdi = array();
    $cfdi['CfdiType'] = 'ingreso';
    $cfdi['IdBranchOffice'] = isset($_POST['IdBranchOffice']) ? $_POST['IdBranchOffice'] : '';
    $cfdi['IdClient'] = isset($_POST['IdClient']) ? $_POST['IdClient'] : '';
    $cfdi['PaymentMethod'] = isset($_POST['PaymentMethod']) ? $_POST['PaymentMethod'] : '';
    $cfdi['PaymentAccountNumber'] = isset($_POST['PaymentAccountNumber']) ? $_POST['PaymentAccountNumber'] : '';
    $cfdi['Currency'] = isset($_POST['Currency']) ? $_POST['Currency'] : '';
    $cfdi['Subtotal'] = isset($_POST['Subtotal']) ? $_POST['Subtotal'] : '';
    $cfdi['Discount'] = isset($_POST['Discount']) ? $_POST['Discount'] : '';
    $cfdi['Total'] = isset($_POST['Total']) ? $_POST['Total'] : '';

    $cfdi['Items'][0]['IdProduct'] = isset($_POST['IdProduct']) ? $_POST['IdProduct'] : ''; 
    $cfdi['Items'][0]['Quantity'] = isset($_POST['Quantity']) ? $_POST['Quantity'] : '';
    $cfdi['Items'][0]['Total'] = isset($_POST['Total']) ? $_POST['Total'] : '';
    $cfdi['Items'][0]['Tax'] = isset($_POST['Tax']) ? $_POST['Tax'] : '';
    $cfdi['Items'][0]['Subtotal'] = isset($_POST['Subtotal']) ? $_POST['Subtotal'] : '';
    $cfdi['Items'][0]['UnitPrice'] = isset($_POST['UnitPrice']) ? $_POST['UnitPrice'] : '';


    $cfdi['Taxes'][0]['Total'] = isset($_POST['Total']) ? $_POST['Total'] : '';
    $cfdi['Taxes'][0]['Name'] = isset($_POST['Name']) ? $_POST['Name'] : '';
    $cfdi['Taxes'][0]['Rate'] = isset($_POST['Rate']) ? $_POST['Rate'] : '';
    $cfdi['Taxes'][0]['Type'] = isset($_POST['Type']) ? $_POST['Type'] : '';

    try {
    $cfdi_client = new Facturama_Cfdi();
    $response = $cfdi_client->create($cfdi);
    if($response->getStatus() == 201){
        var_dump($response);
        echo '<div class="alert alert-success">Cfdi Creado exitosamente';
        echo '</div>';
    }else{
        echo '<div class="alert alert-warning">'.$response->getStatus().': '.$response->getMessage();
        echo '<ul>';
        foreach ($response->getErrors() as $error) {
            echo '<li>'.$error.'</li>';
        }
        echo '</ul>';
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
            <input type='text' name='CfdiType' value="ingreso"></br></br>
            <label>IdBranchOffice:</label> 
            <input type='text' name='IdBranchOffice' value="KI2C2GeiviBPKVZRxX6GLg2"></br></br>
            <label>IdClient:</label>   
            <input type='text' name='IdClient' value="MdkB7dsxLN8XmQGjKHHF4g2"></br></br>
            <label>PaymentMethod:</label>  
            <input type='text' name='PaymentMethod' value="TRANSFERENCIA ELECTRONICA DE FONDOS"></br></br>
            <label>PaymentAccountNumber:</label>   
            <input type='text' name='PaymentAccountNumber' value="5143"></br></br>
            <label>Currency:</label>   
            <input type='text' name='Currency' value="MXN"></br></br>
            <label>Discount:</label>   
            <input type='text' name='Discount' value="0"></br></br>

            <label>IdProduct:</label>  
            <input type='text' name='IdProduct' value="0WV1zSDPmulwL3OGEIbbPw2"></br></br>
            <label>Quantity:</label>  
            <input type='text' name='Quantity' value="2"></br></br>
            <label>Tax:</label>   
            <input type='text' name='Tax' value="16"></br></br>
            <label>Subtotal:</label>  
            <input type='text' name='Subtotal' value="950.66"></br></br>
            <label>UnitPrice:</label> 
            <input type='text' name='UnitPrice'></br></br>

            <label>Total: </label> 
            <input type='text' name='Total' value="105.2"></br></br>
            <label>Name: </label>  
            <input type='text' name='Name' value="IVA"></br></br>
            <label>Rate: </label>  
            <input type='text' name='Rate' value="16"></br></br>
            <label>Type: </label>  
            <input type='text' name='Type' value="1"></br></br>

            <input type="submit" value="Enviar">
        </form>
    </div>
</div>
</html>