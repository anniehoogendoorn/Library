<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Book.php";
    //require_once __DIR__."/../src/Patron.php";
    require_once __DIR__."/../src/Author.php";


    $app = new Silex\Application();

    $server = 'mysql:host=localhost;dbname=library';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    // HOME PAGE - DISPLAYS ADMIN LINK AND PATRON LINK
    $app->get('/', function() use ($app){
        return $app['twig']->render('index.html.twig');
    });

    // ADMIN PAGE - DISPLAYS BOOK CATALOG
    $app->get("/main_admin", function() use ($app){
        $books = Book::getAll();
        $authors = Author::getAll();
        return $app['twig']->render("main_admin.html.twig", array('books' => $books, 'authors' => $authors));
    });

    $app->post("/book_added", function() use ($app){
        // create new book from user entry "add book"
        $title = $_POST['title'];
        $new_book = new Book($title);
        $new_book->save();
        // create new author from user entry "add book"
        // possibly check that the author is already in the database - NOT NOW
        $name = $_POST['name'];
        $new_author = new Author($name);
        $new_author->save();
        $new_book->addAuthor($new_author);
        $books = Book::getAll();
        $authors = Author::getAll();
        return $app['twig']->render("main_admin.html.twig", array('books' => $books, 'authors' => $authors));
    });








    return $app;
?>
