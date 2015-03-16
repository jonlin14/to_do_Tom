<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Task.php";

    $DB = new PDO('pgsql:host=localhost;dbname=to_do_test');

    class TaskTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Task::deleteAll();
        }


        function test_getId()
        {
            //Arrange
            $description = "Wash the dog";
            $id = 1;
            $test_Task = new Task($description, $id);

            //Act
            $result = $test_Task->getId();

            //Assert
            $this->assertEquals(1, $result);

        }

        function test_setId()
        {
            //Arrange
            $description = "Wash the dog";
            $id = null;
            $test_Task = new Task($description, $id);

            //Act
            $test_Task->setId(2);

            //Assert
            $result = $test_Task->getId();
            $this->assertEquals(2, $result);
        }

        function test_save()
        {

            //Arrange
            $description = "Wash the dog";
            $id = null;
            $test_Task = new Task($description, $id);

            //Act
            $test_Task->save();

            //Assert
            $result = Task::getAll();
            $this->assertEquals($test_Task, $result[0]);


            function test_getAll()
            {

            //Arrange
            $description = "Wash the dog";
            $description2 = "Water the lawn";
            $test_Task = new Task($description);
            $test_Task->save();
            $test_Task2 = new Task($description2);
            $test_Task2->save();


            //Act
            $result = Task::getAll();

            //Assert
            $this->assertEquals([$test_Task, $test_Task2], $result);
            }

            function test_deleteAll()
            {

                //Arrange
                $description = "Wash the dog";
                $description2 = "Water the lawn";
                $id = null;
                $test_Task = new Task($description, $id);
                $test_Task->save();
                $test_Task2 = new Task($description2, $id);
                $test_Task2->save();

                //Act
                Task::deleteAll();

                //Assert
                $result = Task::getAll();
                $this->assertEquals([], $result);

            }

        }

    }

?>
