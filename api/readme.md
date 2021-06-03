# Antares PHP

### Usage
```php    
include('antares-php.php');

$antares = new antares_php();

$antares->set_key('your-access-key-here');

$yourdata = '{"sensor":"value","sensor":"value"}';

$antares->send($yourdata, 'your-device-name', 'your-application-name');  

$Viewdata = $antares->get('your-device-name', 'your-application-name');

$Viewdata_encode = json_encode($Viewdata);
``` 


### Reference

```php
$antares = new antares_php(); 
```
- All methods and properties need to be insantiated in order to use them

<br/>

```php 
set_key('your-access-key-here');
``` 		
- Set the  `your-access-key-here` parameter to your Antares access key.

<br/>

```php 
send($yourdata, 'your-device-name', 'your-application-name'); 
``` 		
- Set the  `yourdata` parameter to your data with JSON Format.
- Set the  `your-device-name` parameter to your Antares device name.
- Set the  `your-application-name` parameter to your Antares application name.

<br/>

```php 
$yourdata  =  $antares->get('your-device-name', 'your-application-name');
``` 		
- Get your data from Antares. return : JSON format
- Set the  `your-device-name` parameter to your Antares device name.
- Set the  `your-application-name` parameter to your Antares application name.

