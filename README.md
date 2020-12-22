# SubstringLibrary

SubstringLibrary is library that can search for a substring in a file or compare local or remote files' hash-sums

## Usage

```php
$library = new SubstringLibrary('lorem.txt');

// find a line and position of provided string in file
$library->findString('feugiat'); 

// check if hash-sum of provided file is the same as the first one
$library->compareHashSums('lorem-different.txt');
```