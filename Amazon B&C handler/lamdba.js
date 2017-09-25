'use strict';
console.log('Loading function');
var AWS = require('aws-sdk');
exports.handler = (event, context, callback) => {
    //console.log('Received event:', JSON.stringify(event, null, 2));
    const message = JSON.parse(event.Records[0].Sns.Message);
    
    switch(message.notificationType) {
        case 'Bounce':
            handle(message);
            break;
        case 'Complaint':
            handle(message);
            break;
        case 'Delivery':
            handle(message);
            break;
        default:
            callback(`Unknown notification type: ${message.notificationType}`);
    }
};

// Function handling response of email and store data into db.
function handle(message) {
	if(message.notificationType == 'Bounce') {
		const deliveryTimestamp = message.bounce.timestamp;
} else if(message.notificationType == 'Complaint') {
		const deliveryTimestamp = message.complaint.timestamp;
} else if(message.notificationType == 'Delivery') {
		const deliveryTimestamp = message.delivery.timestamp;
} else {
	console.log('Unknown notification type');
}
    const messageId = message.mail.messageId;
    const type = message.notificationType;
    const mailTimestamp = message.mail.timestamp;
    const sourceEmail = message.mail.commonHeaders["from"];
    const destinationEmail = message.mail.commonHeaders["to"]; 
    var dynamodb = new AWS.DynamoDB();
    var params = {
        Item : {
              "messageId" : { S: messageId },
              "type" : { S : type},
              "sender" : { S: sourceEmail },
              "receiver" : { S: destinationEmail },
              "deliveryTimestamp" : { S: deliveryTimestamp },
              "mailTimestamp" : { S : mailTimestamp }
    },
    TableName: "SnS_notifications"  // Table name
};

 dynamodb.putItem(params, function(err, data) {
  if (err) console.log(err, err.stack); // an error occurred
  else     console.log(data);   
 });
 
    console.log(`Message ${messageId} bounced when sending to ${addresses.join(', ')}. Bounce type: ${bounceType}`);
}
