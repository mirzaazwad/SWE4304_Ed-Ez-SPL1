

# URLFinder Documentation

First include the required library files as basically a header using 

```php
$root_path='../' //something that basically takes you to root directory using a series of '../'
include $root_path.'LibraryFiles/URLFinder/URLPath.php'; //The path may change depending on location of Library Files as a relative path to the current working file
```

Finding the URL of current file

```php
$url = URLPath::getURL(); //It stores the URL of working file to $url
```


Finding the current directory

```php
$url = URLPath::getDirectoryURL(); //It stores the URL of current directory to $url
```
