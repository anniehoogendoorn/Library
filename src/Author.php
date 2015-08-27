<?php
    class Author
    {
        private $name;
        private $id;

        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO authors (name) VALUES ('{$this->getName()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////

        function addBook($book)
        {
            $GLOBALS['DB']->exec("INSERT INTO books_authors (book_id, author_id) VALUES ({$book->getId()}, {$this->getId()});");
        }

        function getBooks()
        {
            $returned_books = $GLOBALS['DB']->query("SELECT books.* FROM
                authors JOIN books_authors ON (authors.id = books_authors.author_id)
                         JOIN books ON (books_authors.book_id = books.id)
                         WHERE authors.id = {$this->getId()};");
            $books = array();
            foreach($returned_books as $book) {
                $title = $book['title'];
                $id = $book['id'];
                $new_book = new Book($title, $id);
                array_push($books, $new_book);
            }
            return $books;
        }
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////


        static function getAll()
        {
            $returned_authors = $GLOBALS['DB']->query("SELECT * FROM authors;");
            $authors = array();
            foreach($returned_authors as $author) {
                $author_name = $author['name'];
                $id = $author['id'];
                $new_author = new Author($author_name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors;");
        }

        static function find($search_id)
        {
            $found_author = null;
            $authors = Author::getAll();
            foreach($authors as $author){
                $author_id = $author->getId();
                if($author_id == $search_id){
                    $found_author = $author;
                }
            }
            return $found_author;
        }

        function update($new_author_name)
        {
            $GLOBALS['DB']->exec("UPDATE authors SET name = '{$new_author_name}' WHERE id ={$this->getId()};");
            $this->setName($new_author_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM books_authors WHERE author_id = {$this->getId()};");
        }

    }
?>
