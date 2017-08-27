# userFunc
TYPO3 TYPOSCRIPT userFunc

## Ab TYPO3 7.6

```PHP
<?php 
namespace Myvendor\Myincludes;
class Example {
    public function myExampleFunction($content, $conf) {
        $color = $conf['userFunc.']['color'];
        return '<p style="color:' . $color. ';">Dynamic time: ' . date('H:i:s') . '</p><br />';
    }
}
```

### TypoScript

```TypoScript
page.30 = USER_INT
page.30 {
  userFunc = Myvendor\Myincludes\Example->myExampleFunction
  userFunc.color = #ff0000
}

```

## ExtBase Extension mit userFunc Action

* TypoScript

```TypoScript
10 = USER_INT
10 {
    userFunc       = TYPO3\CMS\Extbase\Core\Bootstrap->run
    vendorName     = MyVendor\Myincludes
    extensionName  = MyExtension
    pluginName     = Pi1
    switchableControllerActions {
        Example {
            1 = myUserFunc
        }
    }
}
```
* Controller

```PHP
<?php
namespace MyVendor\MyExtension\Controller;

class ExampleController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    public function listAction() {
        ...
    }
    
    public function myUserFuncAction() {
        return '<p style="color: red;">Dynamic time: ' . date('H:i:s') . '</p><br />';
    }

}

```





