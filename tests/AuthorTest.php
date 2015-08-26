<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Author.php";
    require_once "src/Book.php";
    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class AuthorTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Author::deleteAll();
            Book::deleteAll();
        }

        function test_save()
        {
            //Arrange
            $name = "History of Epicodus";
            $test_author = new Author($name);

            //Act
            $test_author->save();

            //Assert
            $result = Author::getAll();
            $this->assertEquals($test_author, $result[0]);
        }

        function testGetAll()
        {
            //Arrange
            $name = "Intro to Art";
            $test_author = new Author($name);
            $test_author->save();

            $name2 = "Intro to Spanish";
            $test_author2 = new Author($name2);
            $test_author2->save();

            //Act
            $result = Author::getAll();

            //Assert
            $this->assertEquals([$test_author, $test_author2], $result);

        }


        function testDeleteAll()
        {
            //Arrange
            $name = "Intro to Art";
            $test_author = new Author($name);
            $test_author->save();

            $name2 = "Intro to Spanish";
            $test_author2 = new Author($name2);
            $test_author2->save();

            //Act
            Author::deleteAll();
            $result = Author::getAll();

            //Assert
            $this->assertEquals([], $result);

        }

        function testAddBook()
        {
            //Arrange
            $name = "Ben";
            $test_author = new Author($name);
            $test_author->save();

            $book_name = "Intro to Art";
            $test_book = new Book($book_name);
            $test_book->save();

            //Act
            $test_author->addBook($test_book);

            //Assert
            $this->assertEquals($test_author->getBooks(), [$test_book]);
        }

    }

?>
