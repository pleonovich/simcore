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
    const DB_USER = 'root';
    const DB_PASS = '';
    const DB_NAME = 'simCore';
    const DB_CHARSET = 'utf8';
}
```
## Routing
Routes config settings in index file ```index.php```
### For search engine friendly urls
Description:
```php
Router set ( string $pattern, string $class, string $method [, array $aliases=null ] )
```
Example:
```php
Router::factory()
->set('~^/$~', 'Index', 'index') // yourdomain.com
->set('~^/id/([0-9]+)$~', 'Index', 'index', array('id')) // yourdomain.com/id/7
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
## Controller
All controllers live here ```/simcore/controllers/```

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

## View
All views live here ```/simcore/views/``` and must have prefix ```.view```, example: ```main.view.php```

View example:
```html
<h1><?=$title?></h1>
<p><?=$content?></p>
```

## Model
All models live here ```/simcore/models/``` and must have prefix ```.class```, example: ```Model.class.php```

Model example:
```php
class Model extends abstractModel {

  function __construct ( $table ) {
    parent::__construct($table);
  }
  
}
```

Work with model:
```php
class IndexController extends Controller {

  public function Index () {
    $MODEL = new Model('data_table');
    $data = $MODEL->getById(7);
    $this->title = $data['title'];
    $this->content = $data['content'];
    // MAIN RENDER
    $this->render();
  }
  
}
```
