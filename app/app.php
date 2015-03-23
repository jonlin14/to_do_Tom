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

    $app->get('/', function() use ($app) {

        return $app['twig']->render("index.twig");
    });

    $app->get('/tasks', function() use ($app) {

        return $app['twig']->render("tasks.html.twig", array('tasks' => Task::getAll()));
    });

    $app->post('/tasks', function() use ($app) {
        $new_task = new Task($_POST['task_name']);
        $new_task->save();

        return $app['twig']->render("tasks.html.twig", array('tasks' => Task::getAll()));
    });

    $app->get('/tasks/{id}', function($id) use ($app) {
        $task = Task::find($id);

        return $app['twig']->render("task.html.twig", array('task' => $task, 'categories' => $task->categories(), 'all_categories' => Category::getAll()));
    });

    $app->post('/tasks/{id}', function($id) use ($app) {
        $task = Task::find($id);
        $category = Category::find($_POST['category_name']);
        $task->addCategory($category);

        return $app['twig']->render("task.html.twig", array('task' => $task, 'categories' => $task->categories(), 'all_categories' => Category::getAll()));
    });

    $app->patch('/tasks/{id}', function($id) use ($app) {
        $task = Task::find($id);
        $task->update($_POST['change_desc']);

        return $app['twig']->render("task.html.twig", array('task' => $task));
    });

    $app->delete('/tasks', function() use ($app) {
        $task = Task::find($_POST['task_delete']);
        $task->delete();

        return $app['twig']->render("tasks.html.twig", array('tasks' => Task::getAll()));
    });


    $app->post('/delete_tasks', function() use ($app) {
        Task::deleteAll();

        return $app['twig']->render('tasks.html.twig', array('tasks' => Task::getAll()));
    });

    $app->get('/categories', function() use ($app) {
        return $app['twig']->render("categories.html.twig", array ('categories' => Category::getAll()));
    });

    $app->post('/categories', function() use ($app) {
        $name = $_POST['category_name'];
        $category = new Category($name);
        $category->save();

        return $app['twig']->render('categories.html.twig', array ('categories' => Category::getAll()));

    });

    $app->post('/categories/{id}', function($id) use ($app) {
        $category = Category::find($id);
        $task = Task::find($_POST['task_name']);
        $category->addTask($task);
        return $app['twig']->render('category.html.twig', array ('category' => $category, 'all_tasks' => Task::getAll(), 'tasks' => $category->tasks()));
    });

    $app->get('/categories/{id}', function($id) use ($app) {
        $category = Category::find($id);
        return $app['twig']->render('category.html.twig', array ('category' => $category, 'tasks' => $category->tasks(), 'all_tasks' => Task::getAll()));
    });

    $app->patch('/categories/{id}', function($id) use ($app) {
        $category = Category::find($id);
        $new_name = $_POST['change_name'];
        $category->update($new_name);
        return $app['twig']->render('category.html.twig', array ('category' => $category, 'tasks' => $category->tasks(), 'all_tasks' => Task::getAll()));
    });

    $app->delete('/categories', function() use ($app) {
        $category = Category::find($_POST['category_delete']);
        $category->delete();
        return $app['twig']->render('categories.html.twig', array ('categories' => Category::getAll()));
    });

    $app->post('/delete_categories', function() use ($app){
        Category::deleteAll();
        return $app['twig']->render('categories.html.twig', array ('categories' => Category::getAll()));
    });

    return $app;
?>
