This component supports storing logs with dynamic number of attributes.<br>
You define what attributes your log will have.<br>
These attributes are grouped in "log templates".<br>
 For this component to work(if we choose database storage)<br>
 we need to set database first.<br>
 ## Idea
 The initial idea was that Event Class can have properties such as $name, $type, $performer etc...
 That means we also need fixed number of columns in logs table. <br>
 But what if we wanted additional columns or to remove from existing.<br>
 With this aproach, we have dynamic number of "columns" - attributes.
 ## Getting Started
First step is to initialize storage.<br>
Then we can pass required parameters in storage contstructor

// Parameters:<br>
host, database name, username, password, table name of logs, table name of log templates<br><br>
###Logging event:
```
$storage = new DatabaseStorage('localhost', 'event_logger', 'root', '', 'logs', 'log_templates');
```
Then define new log template structure<br>
For this case we will define ones from test<br>
```
$storage->defineLogTemplate('warning', [
    'name', 'performer', 'subject', 'addition'
]);
```
First parameter is template name, second is array with attributes that will be "fillable" by event, <br>
Your are free to add as many attributes as you want.

Next step is to initialize Event and pass it array with parameters<br>
```
$event = new Event();
$event->setType('warning');
$event->setData([
    'name' => 'Obavestenje',
    'performer' => 'User',
    'subject' => 'Login',
    'addition' => 'Uspesno logovanje',
]);
```
Or you can pass all with set() method
```
$event->set('warning', [
   'name' => 'Obavestenje',
   'performer' => 'User',
   'subject' => 'Login',
   'addition' => 'Uspesno logovanje',
]);
```
Parameters for event must match attributes defined for log template, otherwise exception will be thrown. <br>

To log event, we need to initialize logger and pass storage and event instaces.
```
$logger = new Logger($storage);
$logger->log($event); // event is logged
```
Changing storage on initialized logger is also supported
```
$logger->changeStorage(new FileStorage());
```
###Fetching log
We can fetch array with logs filtered by log type and date when log is created<Br>
Example:
```
$logger->getLog('warning', [
    'created:<' => date('Y-m-d H:i:s'),
]);

// Returned array
array(1) {
  [0]=>
  array(3) {
    ["data"]=>
    string(86) "{"name":"Obavestenje","performer":"User","subject":"Login","addition":"Uspesno logovanje"}"
    ["created"]=>
    string(19) "2019-01-27 00:41:46"
    ["name"]=>
    string(7) "warning"
  }
}
```
Notice ":<", we can also use ":>" or ":>=". If you need =, then simple...
```
'created' => date('Y-m-d H:i:s')
```
Most of the errors will be logged in the log file.
