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
Router set ( string $pattern, string $classmethod [, array $aliases=null ] )
Router get ( string $pattern, Clousure $function)
```
Example:
```php
Router::factory()
->set('~^/$~', 'Index@index') // yourdomain.com
->set('~^/id/([0-9]+)$~', 'Index@index', array('id')) // yourdomain.com/id/7
->get('~^/func$~', function () {
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
->set('main', 'Index', 'index') // yourdomain.com?mod=main&id=7
->run();
```

## Model
All models live here ```/src/models/``` and must have prefix ```.class```, example: ```Model.class.php```

Model example:
```php
class Userlist extends Model {

  protected static $table = 'userslist';
  
}
```
Model migration example:
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

Work with model:
```php
class IndexController extends Controller {

  public function Index () {   
    $data = Pages::getById(7);
    print_r($data);
  }
  
}
```

## View
All views live here ```/src/views/``` and must have prefix ```.view```, example: ```layout.view.php```

View example:
```html
<h1><?=$title?></h1>
<p><?=$content?></p>
```
Render view example:
```php
Description:
Void|string render( string $file [, boolean $return = false ] )

Example:
View::factory()->render('layout');
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
