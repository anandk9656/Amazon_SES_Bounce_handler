<?php

require 'vendor/autoload.php';

// DynamoDB Class object.
use Aws\DynamoDb\DynamoDbClient;

// Access key for accessing AWS resources through API call.
$key = 'XXXXXXXXXXXXXX';

// Secret access key for accessing AWS resources through API call.
$secret = 'XXXXXXXXXXXXXXXXXXXXXXXXXX';

// Region you are in.
define('REGION','us-east-1');

// Table name in DynamoDB
$table = 'SnS_notifications';

// Column name used to filter items i.e. bounce, complaint, delivery.
$filter = 'sender';

// @param $clientdb : Object of DynamoDB class.
 $clientdb = DynamoDbClient::factory( array(
   'version'=> 'latest',     
    'region' => REGION,
    'credentials' => array(
    'key'       => $key,
    'secret'    => $secret, 
)));
 
// Calling function for handling bounce.
if($_GET['type']=='b') {
    handlereport($clientdb, $table, $filter,'Bounce');
}    

// Calling function for Complaint bounce.
if($_GET['type'] == 'c') {
    handlereport($clientdb, $table, $filter,'Complaint');   
}

// Calling function for Delivery bounce.
if($_GET['type'] == 'd') {
    handlereport($clientdb, $table, $filter,'Delivery');
}

// Function definition
function handlereport($client, $table, $filter, $type) {
    
    // Getting sender email from URL.
    $senderEmail = $_GET['email'];
    
    // Using Scan operation on DynamoDB table for getting items.
    $result = $client->scan( array(
                'TableName' => $table,
                'AttributesToGet' => array('receiver','deliveryTimestamp','mailTimestamp'),
                'ScanFilter' => array(
                    $filter => array(
                        'AttributeValueList' => array(
                                array('S' => $senderEmail,
                                     
                                     )
                            ),
                        'ComparisonOperator' => 'CONTAINS'
                    ),
                    'notificationType' => array(
                        'AttributeValueList' => array(
                                array('S' => $type,
                                                                  
                                     )
                            ),
                        'ComparisonOperator' => 'CONTAINS'
                    ),
                    
                )
            )
        );
    ?>

<html>
    <head>
        <meta charset="UTF-8">
        <title> Sub Report </title>
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="aws.js"></script>
        <noscript>Java Script is not supported or is disable.</noscript>
    </head>
    <body>
        <h1 align = 'center' style='color:green'> 
            
         <!-- Showing heading according to report-->   
        <?php if($_GET['type']=='b') {echo 'Bounce Report';} 
              if($_GET['type']=='c') {echo 'Complaint report';} 
              if($_GET['type']=='d') {echo 'Delivery report';}
        
        ?> </h1>
        <hr/>
        <h4 align="center"> <?php  echo 'Sender Emai address :- '.$senderEmail; ?> </h4>
        <a href="index.php">Click here for Main Report<a/>
            
            <!-- Here starts the table definition -->
            <table border='1' align='center' style="border-collapse: collapse" cellpadding="10" >
           <tr>
               <th> <input type="checkbox" id="check"> Receiver Email Address</th>
               <th> Mail Time </th>
               <th> Delivery Time </th>
           </tr> 
    </body>
    <?php
   
    if($result['Items']) {
    foreach($result['Items'] as $info) { 
        ?>
            <tr>
               <td><input type="checkbox" class="senderEmail" value=" <?php echo $info['receiver']['S']; ?>"> <?php echo $info['receiver']['S'];?> </td>
               <td><?php echo $info['mailTimestamp']['S'];?> </td>
               <td><?php echo $info['deliveryTimestamp']['S'];?> </td>
           </tr> 
<?php
        }
    }
?>              
            </table>
            
            <!-- Single button for copy the selected check-box for blocking the emails. -->
            <input type="button" value="COPY" onclick="getEmails(); selectText('data');">  <br/> <br/>       
            <div id="data" style="position: absolute; top: 0; left: -2000; color:#ffffff; " ></div>
    </body>
</html>
<?php
}