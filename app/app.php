<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Task.php";
    require_once __DIR__."/../src/Category.php";

    $app = new Silex\Application();

    $app['debug'] = true;

    $DB = new PDO('pgsql:host=localhost;dbname=to_do');


    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();


    //load homepage and display all the categories from the database
    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.twig', array('category_array' => Category::getAll()));
    });

    //Add a category to the database by posting and then go to the index.twig
    //file and list all the available categories
    $app->post("/categories", function() use ($app) {
        $category = new Category($_POST['name']);
        $category->save();
        return $app['twig']->render('index.twig', array('category_array' => Category::getAll()));
    });

    //Once a category has been clicked in the index.twig, go to that
    //individual category by finding its id in the database and list
    //the tasks that are associated with that specific category
    $app->get("/categories/{id}", function($id) use ($app) {
        $category = Category::find($id);
        return $app['twig']->render('category_task.twig', array('category' => $category, 'tasks' => $category->getTasks()));
    });

    //If we are at a specific category we can add a task to that category only
    //if we do a post through the form, sends to the /tasks which displays
    //the specifc category and all associated tasks
    $app->post("/tasks", function() use ($app) {
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];
        $task = new Task($description, $id = null, $category_id);
        $task->save();
        $category = Category::find($category_id);
        return $app['twig']->render('category_task.twig', array('category' => $category, 'tasks' => $category->getTasks()));
    });

    //This directs us to the home page twig file index.twig but it uses the
    //delete_tasks route to delete all your tasks. It uses this route instead of
    //loading the ("/") address. Uses clear button.
    $app->post("/delete_tasks", function() use ($app) {
        Task::deleteAll();
        return $app['twig']->render('index.twig', array('category_array' => Category::getAll()));
    });


    $app->post("/delete_categories", function() use ($app) {
        Category::deleteAll();
        return $app['twig']->render('index.twig', array('category_array' => Category::getAll()));
    });


    //Links to category_edit.twig to retrieve category using the find method and displays this on category_edit.twig
    $app->get("/categories/{id}/edit", function($id) use ($app) {
        $category = Category::find($id);
        return $app['twig']->render('category_edit.twig', array('category' => $category));
    });

    //Update the database via the update() method in the Category class.
    $app->patch("/categories/{id}", function($id) use ($app) {
        $name = $_POST['name'];
        $category = Category::find($id);
        $category->update($name);
        return $app['twig']->render('category_task.twig', array('category' => $category, 'tasks' => $category->getTasks()));

    });

    return $app;
?>
