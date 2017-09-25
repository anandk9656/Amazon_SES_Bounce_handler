# Amazon_SES_Bounce_handler
Variable used : 
# $key = "YOUR ACCESS KEY FROM AWS CONSOLE";
# $secret = "YOUR SECRET ACCESS KEY FROM AWS CONSOLE";

# In DynamoDB :
1. Create a table named as 'SnS_notifications' with primary key as 'messageID'.
2. Columns name : 'messageId', 'type', 'sender', 'receiver', 'deliveryTimestamp', 'mailTimestamp' all of string type.

# In lambda :
1. Create new function .
2. Select SES. 
3. Add SNS trigger.
4. Select Nodejs as platform.
5. Create function with role access to SNS.

# In SNS :
1. Create a topic with endpoint as email.
2. Create subscription to the topic.
