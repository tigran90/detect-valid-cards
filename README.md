# Valid and Dedect bank cards #

Utility to valid and determine bank card type by stgit90

### Installation ###
```
composer require 
```


### Usage ###

```
require "/vendor/autoload.php";
use CardValidDetect\ValidDetect;

$detector = new ValidDetect();
$card = '4916847576752405';
if($detector->detect('4916847576752405')->isValid()){
    echo $detector->getType() //Visa
}
```