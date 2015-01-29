Data storage for Fenom
=======

# Install

Use composer:
```json
{  
    "require": {
        "fenom/storage": "1.*"
    }
}
```

# Setup

Add trait into your Fenom class:

```php

class MyFenom extends Fenom {
    use Fenom\Storage;
}
```

# Use

```php
// set variable
$fenom->assign("var_name", $value);
// set variable by references
$fenom->assignByRef("var_name", $value);
// append variable to tail of array 'var_name'
$fenom->append("var_name", $value);
// prepend variable to head of array 'var_name'
$fenom->prepend("var_name", $value);
$vars = $fenom->getVars();
// set all variables as single array (rewrite previous array)
$fenom->assignAll($vars);
// remove all variables
$fenom->resetVars();

$fenom->pipe($template_name, $callback);
$fenom->fetch($template_name);
$fenom->display($template_name);
```
