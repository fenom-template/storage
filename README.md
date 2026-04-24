Data storage for Fenom
======

# Install

Use composer:
```json
{
    "require": {
        "fenom/storage": "^3.1.0"
    }
}
```

# Setup

Add trait into your Fenom class:

```php
class MyFenom extends \Fenom {
    use \Fenom\StorageTrait;
}
```

# Use

```php
// set variable
$fenom->assign('var_name', $value);

// set variable by reference
$fenom->assignByRef('var_name', $value);

// set value by key in array
$fenom->assignKey('matrix', 'row1', 'value1');

// append variable to tail of array
$fenom->append('var_name', $value);

// prepend variable to head of array
$fenom->prepend('var_name', $value);

// get all stored variables
$vars = $fenom->getVars();

// set all variables as single array (merge with existing)
$fenom->assignAll($vars);

// set all variables (replace existing)
$fenom->assignAll($vars, false);

// remove all variables
$fenom->resetVars();

// fetch template to string
$html = $fenom->fetch($template_name);

// display template
$fenom->display($template_name);

// pipe template output through callback
$fenom->pipe($template_name, function ($buffer) {
    return strtoupper($buffer);
});
```
