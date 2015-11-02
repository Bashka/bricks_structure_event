<?php
namespace Bricks\Structure\Event;
require_once('Publisher.php');

/**
 * @author Artur Sh. Mamedbekov
 */
class PublisherTest extends \PHPUnit_Framework_TestCase{
  /**
   * @var Publisher Объект, способный генерировать события.
	 */
	private $publisher;

	public function setUp(){
    $this->publisher = new PublisherMock;
  }

  /**
   * Метод для тестирования вызова обработчика события.
   */
  public function listen(){
  }

  /**
   * Должен удалять обработчик события.
   */
  public function testOff(){
    $listener = $this->getMock(get_class($this));
    $listener->expects($this->never())
      ->method('listen');

    $this->publisher->on('event', 'listen', $listener);
    $this->publisher->off();
    $this->publisher->trigger('event', 'test');

    $this->publisher->on('event', 'listen', $listener);
    $this->publisher->off('event');
    $this->publisher->trigger('event', 'test');

    $this->publisher->on('event', 'listen', $listener);
    $this->publisher->off('event', 'listen', $listener);
    $this->publisher->trigger('event', 'test');
  }

  /**
   * Должен оповещать подписчиков о возникновении события.
   */
  public function testTrigger(){
    $listener = $this->getMock(get_class($this));
    $listener->expects($this->once())
      ->method('listen')
      ->with($this->equalTo('event'), $this->equalTo('test'), $this->equalTo($this->publisher));

    $this->publisher->on('event', 'listen', $listener);
    $this->publisher->trigger('event', 'test');
  }
}
