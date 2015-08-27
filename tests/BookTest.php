<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Book.php";
    require_once "src/Author.php";
    // require_once "src/Restaurant.php";
    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class BookTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Book::deleteAll();
            Author::deleteAll();
        }

        function test_save()
        {
            //Arrange
            $title = "History of Epicodus";
            $test_book = new Book($title);

            //Act
            $test_book->save();


            //Assert
            $result = Book::getAll();
            $this->assertEquals($test_book, $result[0]);
        }

        function testGetAll()
        {
            //Arrange
            $book_name = "Intro to Art";
            $test_book = new Book($book_name);
            $test_book->save();

            $book_name2 = "Intro to Spanish";
            $test_book2 = new Book($book_name2);
            $test_book2->save();

            //Act
            $result = Book::getAll();

            //Assert
            $this->assertEquals([$test_book, $test_book2], $result);

        }

        function testDeleteAll()
        {
            //Arrange
            $book_name = "Intro to Art";
            $test_book = new Book($book_name);
            $test_book->save();
            $book_name2 = "Intro to Spanish";
            $book_author2 = "SPN101";
            $test_book2 = new Book($book_name2);
            $test_book2->save();

            //Act
            Book::deleteAll();
            $result = Book::getAll();

            //Assert
            $this->assertEquals([], $result);

        }

        function testFind()
        {
            //Arrange
            $book_name = "Intro to Art";
            $test_book = new Book($book_name);
            $test_book->save();

            $book_name2 = "Intro to Spanish";
            $test_book2 = new Book($book_name2);
            $test_book2->save();

            //Act
            $search_id = $test_book->getId();
            $result = Book::find($search_id);

            //Assert
            $this->assertEquals($test_book, $result);
        }

        function testUpdate()
        {
            //Arrange
            $book_name = "Intro to Art";
            $test_book = new Book($book_name);
            $test_book->save();

            //Act
            $new_book_name = "Intro to Fine Arts";
            $test_book->update($new_book_name);

            //Assert
            $this->assertEquals("Intro to Fine Arts", $test_book->getTitle());

        }

        function testAddAuthor()
        {
            //Arrange
            $book_name = "Intro to Art";
            $test_book = new Book($book_name);
            $test_book->save();

            $name = "Ben";
            $test_author = new Author($name);
            $test_author->save();

            //Act
            $test_book->addAuthor($test_author);

            //Assert
            $this->assertEquals($test_book->getAuthors(), [$test_author]);
        }


        function testDelete()
        {
            //Arrange
            $book_name = "Intro to Art";
            $test_book = new Book($book_name);
            $test_book->save();

            $book_name2 = "Everybody Poops";
            $test_book2 = new Book($book_name2);
            $test_book2->save();

            $name = "Arty";
            $test_author = new Author($name);
            $test_author->save();


            //Act
            $test_book->addAuthor($test_author);
            $test_book->delete();

            //Assert
            $this->assertEquals([], $test_author->getBooks());
        }


    }
?>
