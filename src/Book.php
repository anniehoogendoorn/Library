<?php
    class Book
    {
        private $title;
        private $author;
        private $id;

        function __construct($title, $author, $id = null)
        {
            $this->title = $title;
            $this->author = $author;
            $this->id = $id;
        }

        function setTitle($new_title)
        {
            $this->title = (string) $new_title;
        }

        function getTitle()
        {
            return $this->title;
        }

        function setAuthor($new_author)
        {
            $this->author = (string) $new_author;
        }

        function getAuthor()
        {
            return $this->author;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO books (title, author) VALUES ('{$this->getTitle()}', '{$this->getAuthor()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();

        }

        static function getAll()
        {
            $returned_books = $GLOBALS['DB']->query("SELECT * FROM books;");
            $books = array();
            foreach($returned_books as $book) {
                $book_title = $book['title'];
                $author = $book['author'];
                $id = $book['id'];
                $new_book = new Book($book_title, $author, $id);
                array_push($books, $new_book);
            }
            return $books;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM books;");
        }

        static function find($search_id)
        {
            $found_book = null;
            $books = Book::getAll();
            foreach($books as $book) {
                $book_id = $book->getId();
                if ($book_id == $search_id) {
                    $found_book = $book;
                }
            }
            return $found_book;
        }

        function update($new_book_name)
        {
            $GLOBALS['DB']->exec("UPDATE books SET title = '{$new_book_name}' WHERE id = {$this->getId()};");
            $this->setTitle($new_book_name);
        }

    }

 ?>
