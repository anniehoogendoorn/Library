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

    $app->post("/delete_books", function() use ($app){
        $GLOBALS['DB']->exec("DELETE FROM books;");
        Book::deleteAll();
        return $app['twig']->render('main_admin.html.twig', array('books' => Book::getAll()));
    });

    //add new book with author
    $app->post("/book_added", function() use ($app){
        // create new book from user entry "add book"
        $title = $_POST['title'];
        $new_book = new Book($title);
        $new_book->save();
        // create new author from user entry "add book"
        // possibly check that the author is already in the database - NOT NOW
        $name_array = explode(',', $_POST['name']);
        foreach($name_array as $name){
            $new_author = new Author($name);
            $new_author->save();
            $new_book->addAuthor($new_author);
        }
        $books = Book::getAll();
        $authors = Author::getAll();
        return $app['twig']->render("main_admin.html.twig", array('books' => $books, 'authors' => $authors));
    });


    //INDIVIDUAL BOOK PAGE - DISPLAYS BOOK INFORMATION
    $app->get("/book/{id}", function($id) use($app){
        $book = Book::find($id);
        $book_authors = $book->getAuthors();
        return $app['twig']->render("book.html.twig", array('book' => $book, 'authors' => $book_authors));
        });

    //ADD AUTHOR TO INDIVIDUAL BOOK PAGE
    $app->post("/book/{id}/add_author", function($id) use($app) {
        $find_book = Book::find($id);
        $name = $_POST['name'];
        $new_author = new Author($name);
        $new_author->save();
        $find_book->addAuthor($new_author);
        $authors = $find_book->getAuthors();
        return $app['twig']->render("book.html.twig", array('book' => $find_book, 'authors' => $authors));
    });

    //update book info
    $app->patch("/book/{id}", function($id) use ($app){
        $book = Book::find($id);
        $book->update($_POST['title']);
        $authors = $book->getAuthors();
        return $app['twig']->render("book.html.twig", array('book' => $book, 'authors' => $authors));
    });

    //delete book info
    $app->delete("/book/{id}", function($id) use ($app){
        $book = Book::find($id);
        $book->delete();
        return $app['twig']->render("main_admin.html.twig", array('books' => Book::getAll()));
    });










    return $app;
?>
