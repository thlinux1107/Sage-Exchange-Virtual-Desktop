<?php
    
    require('shared.php');
    
    $vaultToken = $_REQUEST['vaultToken'];
    
    // you (or your client's) merchant credentials.
    // grab a test account from us for development!
    $merchant_id = $sharedCredentials["MID"];
    $merchant_key = $sharedCredentials["MKEY"];

    // configuring the transaction
    $amount = "25.00";
    $transaction_type = $transactionTypes["NonUI"]["Sale"];
    $vaultOperation = "RETRIEVE";

    // some arbitrary values for this demo
    $order_number = "Invoice " . rand(0, 1000);
    $transaction_id = uniqid();

    // and then piecing together our XML request
    $xmlRequest = "<?xml version=\"1.0\" encoding=\"utf-16\"?>
    <Request_v1 xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\">
        <Application>
            <ApplicationID>DEMO</ApplicationID>
            <LanguageID>EN</LanguageID>
        </Application>
        <Payments>
            <PaymentType>
            <Merchant>
                <MerchantID>$merchant_id</MerchantID>
                <MerchantKey>$merchant_key</MerchantKey>
            </Merchant>
            <TransactionBase>
                <TransactionID>$transaction_id</TransactionID>
                <TransactionType>$transaction_type</TransactionType>
                <Reference1>$order_number</Reference1>
                <Amount>$amount</Amount>
            </TransactionBase>
            <VaultStorage>
                <GUID>$vaultToken</GUID>
                <Service>$vaultOperation</Service>
            </VaultStorage>
            <Customer>
                <Name>
                    <FirstName>John</FirstName>
                    <MI></MI>
                    <LastName>Smith</LastName>
                </Name>
                <Address>
                    <AddressLine1>123 Address St</AddressLine1>
                    <AddressLine2>Apt Z</AddressLine2>
                    <City>Cityville</City>
                    <State>VA</State>
                    <ZipCode>12345</ZipCode>
                    <Country>USA</Country>
                    <EmailAddress>john.smith@example.com</EmailAddress>
                    <Telephone>999-555-1234</Telephone>
                    <Fax>999-555-4321</Fax>
                </Address>
            </Customer>
            </PaymentType>
        </Payments>
    </Request_v1>";

    // since no user interaction is required, this request can be done
    // directly from the server. and since it's a server-side request,
    // there's no need to tokenize the xml.
    $url = "https://www.sageexchange.com/sevd/frmPayment.aspx";
    $body = "request=" . urlencode($xmlRequest);
    $response = makePostRequest($body, $url);
    
    // work with it as a simplexml object...
    $xmlResponse = simplexml_load_string($response);
    
    // or as json...
    $jsonResponse = json_encode($xmlResponse);
    
    // ... or as an array.
    $arrayResponse = json_decode($jsonResponse, true);
    
    echo '<pre>';
    print_r($arrayResponse);
    echo '</pre>';

?>