<?php
    // resources
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Task.php";


    // for cookies to work
    session_start();
    if (empty($_SESSION['list_of_tasks'])) {
        $_SESSION['list_of_tasks'] = array();
    }

    $app = new Silex\Application();
    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function() use ($app) {

        return $app['twig']->render('tasks.html.twig', array(
            'tasks' => Task::getAll()
            ));
    });

    $app->post("/tasks", function() use ($app) {
        $task = new Task($_POST['description']);
        $task->save();
        return $app['twig']->render('tasks.html.twig', array(
            'tasks' => Task::getAll(),
            'message' => array(
                'text' => 'You created a new task!',
                'type' => 'success'
            )
            ));
    });

    $app->post("/delete_tasks", function() use ($app) {
        Task::deleteAll();

        return $app['twig']->render('tasks.html.twig', array(
            'tasks' => Task::getAll(),
            'message' => array(
                'text' => 'You deleted your tasks!',
                'type' => 'danger'
            )
            ));
    });

    return $app;
?>
