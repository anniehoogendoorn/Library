<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Copy.php";
    // require_once "src/Restaurant.php";
    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CopyTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Copy::deleteAll();
        }

        function test_save()
        {
            //Arrange
            $book_id = 1;
            $test_copy = new Copy($book_id);

            //Act
            $test_copy->save();
            var_dump($test_copy);

            //Assert
            $result = Copy::getAll();
            $this->assertEquals($test_copy, $result[0]);
        }

        function testGetAll()
        {
            //Arrange
            $book_id = 1;
            $test_copy = new Copy($book_id);
            $test_copy->save();

            $book_id2 = 2;
            $test_copy2 = new Copy($book_id2);
            $test_copy2->save();

            //Act
            $result = Copy::getAll();

            //Assert
            $this->assertEquals([$test_copy, $test_copy2], $result);

        }

        function testDeleteAll()
        {
            //Arrange
            $book_id = 1;
            $test_copy = new Copy($book_id);
            $test_copy->save();

            $book_id2 = 2;
            $test_copy2 = new Copy($book_id2);
            $test_copy2->save();

            //Act
            Copy::deleteAll();
            $result = Copy::getAll();

            //Assert
            $this->assertEquals([], $result);

        }
    }
?>
