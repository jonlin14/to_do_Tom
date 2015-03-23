<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Task.php";
    require_once "src/Category.php";

    $DB = new PDO('pgsql:host=localhost;dbname=to_do_test');

    class TaskTest extends PHPUnit_Framework_TestCase
    {

        //Clears the database after each test.
        protected function tearDown()
        {
            Task::deleteAll();
        }

        //Searches for an ID with which to associate each task.
        function test_getId()
        {
            //Arrange
            $id = null;
            $description = "Wash the dog";
            $test_task = new Task($description, $id);
            $test_task->save();

            //Act
            $result = $test_task->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));

        }

        //Instatiates each new Task object with an ID so that we can find each task by its ID.
        function test_setId()
        {
            //Arrange
            $id = null;
            $description = "Wash the dog";
            $test_task = new Task($description, $id);
            $test_task->save();

            //Act
            $test_task->setId(2);

            //Assert
            $result = $test_task->getId();
            $this->assertEquals(2, $result);
        }

        function test_setIdDatabase()
        {
            //arrange
            $test_task = new Task("Wash the dog");
            $test_task->save();
            //act
            $result = Task::getAll();

            //assert
            $this->assertEquals(true, is_numeric($result[0]->getId()));
        }

        function test_getDescription()
        {
            //arrange
            $test_task = new Task("Mow the lawn");
            $test_task->save();

            //act
            $result = $test_task->getDescription();

            //assert
            $this->assertEquals("Mow the lawn", $result);
        }

        function test_getDescriptionDB()
        {
            //arrange
            $test_task = new Task("Mow the lawn");
            $test_task->save();

            //act
            $result = Task::getAll();

            //assert
            $this->assertEquals("Mow the lawn", $result[0]->getDescription());
        }

        function test_setDescription()
        {
            //arrange
            $test_task = new Task("Mow the lawn");
            $test_task->save();

            //act
            $test_task->setDescription("Feed the dog");

            //assert
            $this->assertEquals("Feed the dog", $test_task->getDescription());
        }

        function test_update()
        {
            //arrange
            $test_task = new Task("Milk the cows");
            $test_task->save();

            //act
            $test_task->update("Feed the hens");

            //assert
            $this->assertEquals("Feed the hens", $test_task->getDescription());
        }

        function test_updateDatabase()
        {
            //arrange
            $test_task = new Task("Fuel the rocketship");
            $test_task->save();

            //act
            $test_task->update("Blastoff!");
            $result = Task::getAll();

            //assert
            $this->assertEquals("Blastoff!", $result[0]->getDescription());
        }

        //Updates the value of $id variable to reflect the new ID that the database has assigned.
        function test_save()
        {

            //Arrange
            $id = null;
            $description = "Wash the dog";
            $test_task = new Task($description, $id);

            //Act
            $test_task->save();

            //Assert
            $result = Task::getAll();
            $this->assertEquals($test_task, $result[0]);

        }

        function test_getAll()
        {

            //Arrange
            $id = null;
            $description = "Wash the dog";
            $test_task = new Task($description, $id);
            $test_task->save();


            $description2 = "Water the lawn";
            $test_task2 = new Task($description2, $id);
            $test_task2->save();


            //Act
            $result = Task::getAll();

            //Assert
            $this->assertEquals([$test_task, $test_task2], $result);
        }

        function test_deleteAll()
        {

            //Arrange
            $id = null;
            $description = "Wash the dog";
            $test_task = new Task($description, $id);
            $test_task->save();

            $description2 = "Water the lawn";
            $test_task2 = new Task($description2, $id);
            $test_task2->save();

            //Act
            Task::deleteAll();

            //Assert
            $result = Task::getAll();
            $this->assertEquals([], $result);

        }

        function test_find()
        {
            //Arrange
            $id = null;
            $description = "Wash the dog";
            $test_task = new Task($description, $id);
            $test_task->save();

            $description2 = "Water the lawn";
            $test_task2 = new Task($description2, $id);
            $test_task2->save();

            //Act
            $result = Task::find($test_task->getId());

            //Assert
            $this->assertEquals($test_task, $result);
        }

        function test_searchTasks()
        {
            //arrange
            $desc = "Water the lawn";
            $desc2 = "Feed the dog";
            $test_task = new Task($desc, 1);
            $test_task2 = new Task($desc2, 2);
            $test_task->save();
            $test_task2->save();

            $search = "Water";

            //act
            $found_tasks = Task::searchTasks($search);

            //assert
            $this->assertEquals([$test_task], $found_tasks);

        }
    }

?>
