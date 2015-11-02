# Событийная модель

Для добавления событийной модели взаимодействия классу достаточно использовать 
trait _Publisher_. Метод _on_ этого решения позволяет установить обработчик 
события экземпляра класса, а метод _off_ удаляет обработчик:

```php
namespace Bricks\Structure\Event\Publisher;

class My{
  use Publisher;
}

$obj = new My;
$obj->on('event', function($event, $data, $publisher){
  ...
});
...
$obj->off('event');
```

Метод _off_ позволяет удалить конкретный обработчик, если передан второй, либо 
все обработчики данного события. Если вызвать метод без параметров, будут 
удалены все обработчики событий объекта.

Для генерации события и оповещения обработчиков, используется метод _trigger_, 
который принимает имя события, а так же может принимать дополнительную 
информацию о событии в качестве второго параметра:

```php
namespace Bricks\Structure\Event\Publisher;

class My{
  use Publisher;

  public function method(){
    ...
    $this->trigger('method_run');
  }
}

$obj = new My;
$obj->on('method_run', 'listener', 'OtherClass');
```
