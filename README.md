Loader tags and modifiers for Fenom from FS
=======

Just like Smarty.

# Setup

Add traits into your Fenom class:

```php

class MyFenom extends Fenom {
    use Fenom\Storage;
}
```

# Use

```php
$fenom->assign("var_name", $value);
$fenom->assignByRef("var_name", $value);
$fenom->append("var_name", $value);
$fenom->prepend("var_name", $value);
$vars = $fenom->getVars();
$fenom->assignVars($vars);
$fenom->resetVars();

$fenom->pipe($template_name, $callback);
$fenom->fetch($template_name);
$fenom->display($template_name);
```
# Format
