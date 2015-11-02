<?php
namespace Bricks\Structure\Event;

/**
 * Реализует логику генерации и обработки событий.
 *
 * @author Artur Sh. Mamedbekov
 */
trait Publisher{
  /**
   * @var array Слушатели.
   */
  private $listeners;

  /**
   * Регистрирует слушателя события.
   *
   * @param string $event Имя прослушиваемого события.
   * @param string|callable $callback Имя метода-обработчика события или 
   * анонимная функция-обработчик.
   * При вызове обработчику будет передано имя события, дополнительная 
   * информация о событии, а так же объект, сгенерировавший событие.
   * @param string|object $context [optional] Контекст вызова обработчика в виде 
   * объекта или имени класса, который будет инстанциирован.
   */
  public function on($event, $callback, $context = null){
    if(!isset($this->listeners[$event])){
      $this->listeners[$event] = [];
    }

    if(!is_null($context)){
      if(is_string($context)){
        $context = new $context;
      }
      $callback = [$context, $callback];
    }

    array_push($this->listeners[$event], $callback);
  }

  /**
   * Удаляет слушателя события.
   *
   * @param string $event [optional] Имя удаляемого события. Если параметр не 
   * передан, удаляются все слушатели, зарегистрированные у вызываемого объекта.
   * @param string|callable $callback [optional] Удаляемый обработчик. Если 
   * параметр не передан, удаляются все обработчики указанного события.
   * @param string|object $context [optional] Контекст обработчика.
   */
  public function off($event = null, $callback = null, $context = null){
    if(is_null($event)){
      $this->listeners = [];
      return;
    }

    if(is_null($callback)){
      $this->listeners[$event] = [];
      return;
    }

    foreach($this->listeners[$event] as $i => $listener){
      if(is_array($listener)){
        if(is_null($context)){
          continue;
        }
    
        $listenerContext = is_string($context)? get_class($listener[0]) : $listener[0];
        if($listenerContext === $context && $listener[1] === $callback){
          unset($this->listeners[$event][$i]);
        }
      }
      else{
        if($listener == $callback){
          unset($this->listeners[$event][$i]);
        }
      }
    }
  }

  /**
   * Генерирует событие и оповещает об этом все зарегистрированные слушатели.
   *
   * @param string $event Генерируемое событие.
   * @param mixed $data [optional] Дополнительная информация о событии, которая 
   * будет передана обработчику в качестве второго параметра.
   */
  public function trigger($event, $data = null){
    if(isset($this->listeners[$event])){
      foreach($this->listeners[$event] as $listener){
        call_user_func_array($listener, [$event, $data, $this]);
      }
    }
  }
}
