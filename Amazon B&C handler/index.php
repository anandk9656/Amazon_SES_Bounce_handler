<?php

require 'vendor/autoload.php';

// DynamoDB API object
use Aws\DynamoDb\DynamoDbClient;

// Access key for accessing AWS resources through API call.
$key = 'XXXXXXXXXXXXXX';

// Secret access key for accessing AWS resources through API call.
$secret = 'XXXXXXXXXXXXXXXXXXXXXXXXXX';

// Region you are in
define('REGION','us-east-1');

// Table name in DynamoDB
$table = 'SnS_notifications';

// Column Name Containing items of Notification type
$attribute = 'notificationType'; 

// @param $clientdb : Object of DynamoDB class.
 $clientdb = DynamoDbClient::factory( array(
   'version'=> 'latest',     
    'region' => REGION,
    'credentials' => array(
    'key'       => $key,
    'secret'    => $secret, 
)));

// Function fetching bounce, complaint, and delivery data.
function getdatafromDB($type) {
	
	$data = $clientdb->scan( array(
                'TableName' => $table,
                'AttributesToGet' => array('sender'),
                'ScanFilter' => array(
                    $attribute => array(
                        'AttributeValueList' => array(
                                array('S' => $type)
                            ),
                        'ComparisonOperator' => 'CONTAINS'
                    ),
                )
            )
        );    
        return $data;
}  

function Numbers($table, $filter, $filtervalue, $type) {
$key = 'XXXXXXXXXXXXXXX';
$secret = 'XXXXXXXXXXXXXXXXXXXXXXX';
    $clientdb = DynamoDbClient::factory( array(
   'version'=> 'latest',     
    'region' => REGION,
    'credentials' => array(
    'key'       => $key,
    'secret'    => $secret, 
)));

    $result = $clientdb->scan( array(
                'TableName' => $table,
                'AttributesToGet' => array('sender'),
                'ScanFilter' => array(
                    $filter => array(
                        'AttributeValueList' => array(
                                array('S' => $filtervalue,
                                     
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
                ),
            )
        );
     return $result;
}

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Main Report </title>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src= "aws.js"></script>
    <noscript>Java Script is not supported or is disable.</noscript>
    </head>
    <body>
        <h1 align = 'center' style='color:green'>Main Report</h1>
        <hr/>
            <table border='1' align='center' style="border-collapse: collapse" cellpadding="10">
           <tr>
               <th> <input type="checkbox" id="check" style="margin-left: -3px"> Sender Email Address</th>
               <th> Bounce </th>
               <th> Complaint </th>
               <th> Delivery </th>
           </tr> 
<?php      

// Geting unique items(sender email addresses) from the array using array_unique and array_map function.
$bouncedata = array_map("unserialize", array_unique(array_map("serialize", getdatafromDB('Bounce')['Items'])));
$complaintdata = array_map("unserialize", array_unique(array_map("serialize", getdatafromDB('Complaint')['Items'])));
$deliverydata = array_map("unserialize", array_unique(array_map("serialize", getdatafromDB('Delivery')['Items'])));

    if($bouncedata) {
        foreach ($bouncedata as $bounce){  
            $nobounce = Numbers($table, 'sender',$bounce['sender']['S'],'Bounce');
            if($complaintdata) {
                foreach($complaintdata as $complaint) {
                    $nocomplaint = Numbers($table, 'sender',$bounce['sender']['S'],'Complaint');
                }
                if($deliverydata) {
                    foreach($deliverydata as $delivery) {
                        $nodelivery = Numbers($table, 'sender',$bounce['sender']['S'],'Delivery');
                    }
                }
            }
?>
            <!-- Binding data in table -->
           <tr>
               <td><input type="checkbox" class="senderEmail" value=" <?php echo $bounce['sender']['S']; ?>"> <?php echo $bounce['sender']['S'];?> </td>
               <td><?php echo '<a target = "_blank" href="subreport.php?type=b&email='.$bounce['sender']['S'].'">'.$nobounce['Count'].'</a>';?> </td>
               <td><?php echo '<a target = "_blank" href="subreport.php?type=c&email='.$bounce['sender']['S'].'">'.$nocomplaint['Count'].'</a>';?> </td>
               <td><?php echo '<a target = "_blank" href="subreport.php?type=d&email='.$bounce['sender']['S'].'">'.$nodelivery['Count'].'</a>';?> </td>
           </tr> 
        <?php           
            }      
    }
  ?>      
           </table>
            <input type="button" value="COPY" onclick="getEmails(); selectText('data');">  <br/> <br/>       
            <div id="data" style="position: absolute; top: 0; left: -2000; color:#ffffff; " ></div>
    </body>
</html>
