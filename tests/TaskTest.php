<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Task.php";

    $DB = new PDO('pgsql:host=localhost;dbname=to_do_test');

    class TaskTest extends PHPUnit_Framework_TestCase
    {
        function test_save()
        {

        //Arrange
        $description = "Wash the dog";
        $test_task = new Task($description);

        //Act
        $test_task->save();

        //Assert
        $result = Task::getAll();
        $this->assertEquals($test_task, $result[0]);
        }

    }

?>
