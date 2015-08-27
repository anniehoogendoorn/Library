<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Book.php";
    require_once __DIR__."/../src/Patron.php";
    require_once __DIR__."/../src/Copy.php";
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
        $new_book->addCopies($_POST['copies']);
        $books = Book::getAll();
        $authors = Author::getAll();
        return $app['twig']->render("main_admin.html.twig", array('books' => $books, 'authors' => $authors));
    });


    //INDIVIDUAL BOOK PAGE - DISPLAYS BOOK INFORMATION
    $app->get("/book/{id}", function($id) use($app){
        $book = Book::find($id);
        $book_authors = $book->getAuthors();
        return $app['twig']->render("book.html.twig", array('book' => $book, 'authors' => $book_authors, 'copies' => count($book->getCopies())));
        });

    //ADD AUTHOR TO INDIVIDUAL BOOK PAGE
    $app->post("/book/{id}/add_author", function($id) use($app) {
        $find_book = Book::find($id);
        $name = $_POST['name'];
        $new_author = new Author($name);
        $new_author->save();
        $find_book->addAuthor($new_author);
        $authors = $find_book->getAuthors();
        return $app['twig']->render("book.html.twig", array('book' => $find_book, 'authors' => $authors, 'copies' => count($book->getCopies())));
    });

    //update book info
    $app->patch("/book/{id}", function($id) use ($app){
        $book = Book::find($id);
        $book_copies = $book->getCopies();
        $new_copies = $_POST['new_copies'];
        if($new_copies < 1000){
            foreach($book_copies as $copy){
                $copy->delete();
            }
            $book->addCopies($new_copies);
        }
        $book->update($_POST['title']);
        $authors = $book->getAuthors();
        return $app['twig']->render("book.html.twig", array('book' => $book, 'authors' => $authors, 'copies' => count($book->getCopies())));
    });

    //delete book info
    $app->delete("/book/{id}", function($id) use ($app){
        $book = Book::find($id);
        $book->delete();
        return $app['twig']->render("main_admin.html.twig", array('books' => Book::getAll()));
    });

    //INDIVIDUAL AUTHOR PAGE
    $app->get("/author/{id}", function($id) use ($app) {
        $author = Author::find($id);
        $books = $author->getBooks();
        return $app['twig']->render('author.html.twig', array('author' => $author, "books" => $books));
    });

    //Add book on the individual author page
    $app->post("/author/{id}/add_book", function($id) use ($app) {
        $find_author = Author::find($id);
        $title = $_POST['title'];
        $new_book = new Book($title);
        $new_book->save();
        $find_author->addBook($new_book);
        $books = $find_author->getBooks();
        return $app['twig']->render('author.html.twig', array('author' => $find_author, 'books' => $books));
    });

    //Update author's name
    $app->patch("/author/{id}", function($id) use ($app) {
        $author = Author::find($id);
        $author->update($_POST['title']);
        $books = $author->getBooks();
        return $app['twig']->render('author.html.twig', array('author' => $author, 'books' => $books));
    });

    $app->delete("/author/{id}", function($id) use ($app){
        $author = Author::find($id);
        $author->delete();
        return $app['twig']->render('main_admin.html.twig', array('author' => $author, 'books' => Book::getAll()));
    });

    return $app;
?>
