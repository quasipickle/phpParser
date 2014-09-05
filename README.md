#phpParser


Phile plugin that allows for execution of arbitrary PHP files within content files.


##Usage

Inside your content Markdown file, include the following text:

```javascript
  [php]
  {
    "vars":{
      "foo":"bar"
    },
    "file":"path/to/file.php"
  }
  [/php]
```  

Your resulting page will have the output of `file.php` replace everything between the `[php][/php]` tags, including the tags.


###Vars
The `vars` property is optional, but must be an object.  Any properties defined in the object will be `extract()`ed into the local variable space when the file is executed.  For example, using the text above, file.php could look like this:

```php
  <?php
    echo $foo;
  ?>
```
the result of which would be `bar` being output.

###File
The `file` property is required - otherwise you'll get a big fat error saying a required file could not be included.  The path to the file is relative to the install directory of Phile.  So, if Phile is installed in `/var/www/html/`, and you have a file `include_files/foo.php`, then your JSON string would look like:

```javascript
  {
    "file":"include_files/foo.php"
  }

