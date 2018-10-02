![simcore](/images/simCore.gif)

Simple MVC Framework for PHP

## Requirements:
<https://github.com/colshrapnel/safemysql>

## Configuration
App name and database connection settings in config file ```/simcore/config/Config.class.php```
```php
class Config
{
    // MAIN
    const APPID = 'simCore';
    const APPNAME = 'simCore';
    
    // DB CONNECTION
    const DB_HOST = 'localhost';
    const DB_USER = 'root';
    const DB_PASS = '';
    const DB_NAME = 'simCore';
    const DB_CHARSET = 'utf8';
    const DB_PORT = '3307';
    const DB_SOCKET = NULL;
}
```
## Routing
Routes config settings in index file ```index.php```
### For search engine friendly urls
Description:
```php
Router get ( string $pattern, string $classmethod | Clousure $function [, array $aliases=null ] )
```
Example:
```php
Router::factory()
->set('~^/$~', 'Index@index') // yourdomain.com
->set('~^/id/([0-9]+)$~', 'Index@index', array('id')) // yourdomain.com/id/7
->get('~^/func$~', function ($request, $response) {
	echo "Hello world!";
})
->run();
```
### For simple urls 
Description:
```php
SimpleRouter setDefault ( string $class, string $method )
SimpleRouter set ( string $mod, string $class, string $method )
```
Example:
```php
SimpleRouter::factory()
->setDefault('Index', 'index') // yourdomain.com
->get('main', 'Index', 'index') // yourdomain.com?mod=main&id=7
->run();
```

## Model
All models live here ```/src/models/``` and must have prefix ```.class```, example: ```Model.class.php```

### Basic model example:
```php
class Userlist extends Model {

  protected static $table = 'userslist';
  
}
```
### Model migration example:
Setup migration example:
```php
class Userlist extends Model {

  protected static $table = 'userslist';
  
  protected static function schema($create) {
        $create
        ->id()
        ->varchar('user_name')
        ->varchar('user_login')
        ->varchar('email')
        ->text('secret')
        ->int('manager')
        ->int('moderator');
   }
}
```
Run migration example:
```php
Userlist::migrate();
```

Insert data example:
```php
class Userlist extends Model {

 protected static $table = 'userslist';
  
 protected static function insert($insert) {
        $insert
        ->set('user_name', 'Admin')
        ->set('user_login', 'admin')
        ->set('secret', '12345')
        ->set('manager', '1')
        ->set('moderator', '1');
	}
}
```
Run data insert:
```php
Userlist::insertData();
```

### Work with model:
Description:
```php
// returns all data from table
Array all () 
// returns only specific columns from table
Array getNames ( mix ... ) 
// returns one specific column
Array column ( string $name ) 
// return one specific row by id
Array getById ( int $id ) 
// return one row by id specific column and its value
Array getByValue ( string $name, string $value ) 
// return specific rows by id specific column and its value
Array namesByValue ( Array $names, string $name, string $value ) 
// updates data in db from POST, inserts if id not exist.
Boolean save () 
// removes data by id
Boolean remove ( string $name, string $value ) 
```
Example:
```php
Model
class IndexController {

  public function Index () {   
    $data = Pages::getById(7);
    print_r($data);
  }
  
}
```

## View
All views live here ```/src/views/``` and must have prefix ```.view```, example: ```layout.view.php```

### Basic:
```html
// Set variables
{{var myvar='hello'}}
//The same as:
<?php $name='hello'?>

// Print variables
<h1>{{$title}}</h1>
//The same as:
<h1><?=$title?></h1>

// Arrays
{{$array[index]}}
//The same as:
<?=$array['index']}}

// Objects
{{$object->var}}
// Or
{{$object.var}}
//The same as:
<?=$object->var?>

// Call static constants
{{Class::const}}
//The same as:
<?php Class::const ?>
```
### Foreach loops
```html
{{foreach $array as $one}}
<p>{{$one}}</p>
{{/foreach}}

// The same as:
<?php foreach ($array as $one): ?>
<p><?=$one?></p>
<?php endforeach; ?>
```
### For loops
```html
{{for ($a=1;$a<7;$a++)}}
<p>{{$a}}</p>
{{/for}}

// The same as:
<?php for($a=1;$a<7;$a++): ?>
<p><?=$a?></p>
<?php endfor; ?>
```
### If statement
```html
{{if $var}}
<p>true</p>
{{else}}
<p>false</p>
{{/if}}

// The same as:
<?php if($var): ?>
<p>true</p>
<?php else: ?>
<p>false</p>
<?php endif; ?>
```
### Include view
```php
{{include viewname}}
```
### Blocks and extends
```php
#src/views/layout.view.php
<h1>Title</h1>
{{include block content}}

#src/views/home.view.php
{{extends layout}}
{{block content}}
<p>Here some text</p>
{{/block content}}
```

### Render view example:
```php
Description:
Void|string render( string $file [, boolean $return = false ] )

Example:
View::factory()->render('home');

Output:
<h1>Title</h1>
<p>Here some text</p>
```

## Controller
All controllers live here ```/src/controllers/```

Main controller example:
```php
class Controller extends abstractController {

  protected $title;
  protected $content;
	
  public function __construct () {
    parent::__construct();
  }	
  // PAGE WHEN ACCESS DENIED
  protected function accessDenied () {
    View::factory()->render('accessdenied');
  }
  // PAGE FOR 404
  protected function action404 () {
    View::factory()->render('action404');
  }
  // LAYOUT VIEW
  protected function render () {
    View::factory()
    ->bind("title",$this->title)
    ->bind("content",$this->content)
    ->render('layout'); // view name without prefix
  }

}
```
Page controller example:
```php
class IndexController extends Controller {

  public function Index () {
    $this->title = "Hello world!";
    $this->content = "Here is some text for our first page";
    // MAIN RENDER
    $this->render();
  }
  
}
```
