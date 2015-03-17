<?php
class Task
{
        //create properties
        private $description;
        private $id;

        //construct objects
        //set id to null, allows it to know where to start
        function __construct($description, $id = null)
        {
            $this->description = $description;
            $this->id = $id;
        }

        //Sets and can modify the value of $description
        function setDescription($new_description)
        {
            $this->description = (string) $new_description;

        }

        //Gets the value of a private variable $description
        function getDescription()
        {
            return $this->description;
        }


        //Saves the content from each row of the database table and stores it by id number.
        function save()
        {
            $statement = $GLOBALS['DB']->query("INSERT INTO tasks (description) VALUES ('{$this->getDescription()}') RETURNING id;");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);
        }

        //Returns a list of all of our tasks by looping through all of the saved tasks
        static function getAll()
        {

            $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks;");
            $tasks = array();
            foreach($returned_tasks as $task)
            {
                $description = $task['description'];
                $id = $task['id'];
                $new_task = new Task($description, $id);
                array_push($tasks, $new_task);

        }

        //Show us what's in the array $tasks after running through the foreach loop and extracting what's in the database
        return $tasks;

    }
        //Clears all the tasks in our list using the Clear button and with the delete_tasks twig page.
        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM tasks *;");
        }

         //Gets the value of the private variable $id, which referrs to the id number of the task in the database.
         function getId()
         {
             return $this->id;
         }

         //Sets and can modify value of $id, like generating a new id.
         function setId($new_id)
         {
             $this->id = (int) $new_id;
         }

         //Allows us to find a particular task from our database.
         static function find($search_id)
         {
             $found_task = null;
             $tasks = Task::getAll();
             foreach($tasks as $task) {
                 $task_id = $task->getId();
                 if ($task_id == $search_id) {
                     $found_task = $task;
                 }
             }
          return $found_task;

        }
}

?>
