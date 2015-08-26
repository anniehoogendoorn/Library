<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Book.php";
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
        }

        function test_save()
        {
            //Arrange
            $title = "History of Epicodus";
            $author = "The Students";
            $test_book = new Book($title, $author);

            //Act
            $test_book->save();

            //Assert
            $result = Book::getAll();
            $this->assertEquals($test_book, $result[0]);
        }

        function testDeleteAll()
        {
            //Arrange
            $book_name = "Intro to Art";
            $book_author = "ART101";
            $test_book = new Book($book_name, $book_author);
            $test_book->save();

            $book_name2 = "Intro to Spanish";
            $book_author2 = "SPN101";
            $test_book2 = new Book($book_name2, $book_author2);
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
            $book_author = "ART101";
            $test_book = new Book($book_name, $book_author);
            $test_book->save();

            $book_name2 = "Intro to Spanish";
            $book_author2 = "SPN101";
            $test_book2 = new Book($book_name2, $book_author2);
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
            $book_author = "ART101";
            $test_book = new Book($book_name, $book_author);
            $test_book->save();

            //Act
            $new_book_name = "Intro to Fine Arts";
            $test_book->update($new_book_name);

            //Assert
            $this->assertEquals("Intro to Fine Arts", $test_book->getTitle());

        }
        // function testDelete()
        // {
        //     //Arrange
        //     $course_name = "Intro to Art";
        //     $course_number = "ART101";
        //     $test_course = new Course($course_name, $course_number);
        //     $test_course->save();
        //
        //     $name = "Ben";
        //     $enroll_date = "0000-00-00";
        //     $test_student = new Student($name, $enroll_date);
        //     $test_student->save();
        //
        //     //Act
        //     $test_course->addStudent($test_student);
        //     $test_course->delete();
        //
        //     //Assert
        //     $this->assertEquals([], $test_student->getCourses());
        // }

    }
?>
