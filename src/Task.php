<?php
class Task
{
        //create properties
        private $description;
        private $category_id;
        private $id;

        //construct objects
        //set id to null, allows it to know where to start
        function __construct($description, $id = null, $category_id)
        {
            $this->description = $description;
            $this->id = $id;
            $this->category_id = $category_id;
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


        function setCategoryId($new_category_id)
        {
            $this->category_id = (int) $new_category_id;
        }


        function getCategoryId()
        {
            return $this->category_id;
        }


        //Saves the content from each row of the database table and stores it by id number.
        function save()
        {
            $statement = $GLOBALS['DB']->query("INSERT INTO tasks (description, category_id) VALUES ('{$this->getDescription()}', {$this->getCategoryId()}) RETURNING id;");
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
                $category_id = $task['category_id'];
                $new_task = new Task($description, $id, $category_id);
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

        static function searchTasks($search_term)
        {
            $search_results = $GLOBALS['DB']->query("SELECT * FROM tasks WHERE description LIKE '%{$search_term}%';");
            $tasks = array();

            foreach($search_results as $task)
            {
                $desc = $task['description'];
                $id = $task['id'];
                $category_id = $task['category_id'];
                $new_task = new Task($desc, $id, $category_id);
                array_push($tasks, $new_task);
            }
            return $tasks;
        }
}

?>
