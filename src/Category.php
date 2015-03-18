<?php
    class Category
    {
        //create properties
        private $name;
        private $id;

        //construct gets called when objects are instantiated
        //set id to a default to null, allows it to know where to start
        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        //Sets and can modify the value of $name, a private property
        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        //Gets the value of a private variable $name, a private property
        function getName()
        {
            return $this->name;
        }

        //Gets the value of the private variable $id out of the object.
        function getId()
        {
            return $this->id;
        }

        //Sets and can modify value of $id, like generating a new id.
        function setId($new_id)
        {
            $this->id = (int) $new_id;
        }

        //Storing all info from our object into database as a row. Assigns an id number that corresponds to the database row. An associative array is created that has id as the key value pair for the row number.
        function save()
        {
            $statement = $GLOBALS['DB']->query("INSERT INTO categories (name) VALUES ('{$this->getName()}') RETURNING id;");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);
        }

        function getTasks()
        {
            $tasks = Array();
            $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks WHERE category_id = {$this->getId()};");
            foreach($returned_tasks as $task) {
                $description = $task['description'];
                $id = $task['id'];
                $category_id = $task['category_id'];
                $new_Task = new Task($description, $id, $category_id);
                array_push($tasks, $new_Task);
            }
            return $tasks;
        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE categories SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);

        }

        //Returns a list of all of our tasks by looping through all of the saved tasks, and creates a new object with an array called $categories.
        static function getAll()
        {
            $returned_categories = $GLOBALS['DB']->query("SELECT * FROM categories;");
            $categories = array();
            foreach($returned_categories as $category)
            {
                $name = $category['name'];
                $id = $category['id'];
                $new_category = new Category($name, $id);
                array_push($categories, $new_category);
            }
            //Show us what's in the array $tasks after running through the foreach loop and extracting what's in the database
            return $categories;
        }

        //Clears all the categories in our list using the Clear button and with the delete_categories twig page.
        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM categories *;");
        }

        //Allows us to find a particular category from our database.
        static function find($search_id)
        {
            $found_category = null;
            $categories = Category::getAll();
            foreach($categories as $current_category) {
                $category_id = $current_category->getId();
                if ($category_id == $search_id) {
                    //if the id number in the current_category matches the input $search_id value (the id of the category we are looking for), then assign $current_category value (which is the  current_category object) into $found_category container.
                  $found_category = $current_category;
                }
            }
            return $found_category;
        }
    }
?>
