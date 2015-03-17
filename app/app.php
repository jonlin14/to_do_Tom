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


    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.twig', array('category' => Category::getAll()));
    });


    $app->get("/tasks", function() use ($app) {
        return $app['twig']->render('tasks.twig', array('tasks' => Task::getAll()));
    });


    $app->get("/categories", function() use ($app) {
        return $app['twig']->render('categories.twig', array('category' => Category::getAll()));
    });


    $app->post("/tasks", function() use ($app) {
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];
        $task = new Task($description, $id = null, $category_id);
        $task->save();
        $category = Category::find($category_id);
        return $app['twig']->render('categories.twig', array('category' => $category, 'tasks' => Task::getAll()));
    });


    $app->post("/delete_tasks", function() use ($app) {
        Task::deleteAll();
        return $app['twig']->render('index.twig');
    });


    $app->post("/categories", function() use ($app) {
        $category = new Category($_POST['name']);
        $category->save();
        return $app['twig']->render('index.twig', array('category' => Category::getAll()));
    });

    $app->get("/categories/{id}", function($id) use ($app) {
        $category = Category::find($id);
        return $app['twig']->render('categories.twig', array('category' => $category, 'tasks' => $category->getTasks()));
    });


    $app->post("/delete_categories", function() use ($app) {
        Category::deleteAll();
        return $app['twig']->render('index.twig');
    });


    return $app;
?>
