<?php
namespace Tests\Commands;

use Tests\TestCase;
use React\Promise\Promise;
use ArrayObject;

class GreetingCommandTest extends TestCase
{

    private $command;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../commands/Greeting/Greeting.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

     
        $this->command = $commandCreate($this->client);

        parent::setUp();
    }

    public function testGreetingBasics()
    {
       $this->assertEquals($this->command->name, 'greeting');
       $this->assertEquals($this->command->description, 'User greeting command');
       $this->assertEquals($this->command->groupID, 'utils');
    }

    public function testGreetingArguments()
    {
       $this->assertEquals(sizeof($this->command->args), 0);
    }

    public function testSimpleResponseToTheDiscord(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () { });
        $author = $this->createMock('CharlotteDunois\Yasmin\Models\User');
        
        $author->expects($this->once())->method('__get')->with('id')->willReturn('AUTHOR_ID');
        $commandMessage->expects($this->once())->method('__get')->with('author')->willReturn($author);
        $commandMessage->expects($this->once())->method('say')->with('<@AUTHOR_ID>, салют!')->willReturn($promise);
        
        $this->command->run($commandMessage, new ArrayObject(), false);
    }

    public function __sleep()
    {
        $this->command = null;
    }
}