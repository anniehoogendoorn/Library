<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Patron.php";
    // require_once "src/Restaurant.php";
    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class PatronTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Patron::deleteAll();
        }

        function test_save()
        {
            //Arrange
            $name = "History of Epicodus";
            $test_patron = new Patron($name);

            //Act
            $test_patron->save();
            

            //Assert
            $result = Patron::getAll();
            $this->assertEquals($test_patron, $result[0]);
        }

        function testGetAll()
        {
            //Arrange
            $name = "Intro to Art";
            $test_patron = new Patron($name);
            $test_patron->save();

            $name2 = "Intro to Spanish";
            $test_patron2 = new Patron($name2);
            $test_patron2->save();

            //Act
            $result = Patron::getAll();

            //Assert
            $this->assertEquals([$test_patron, $test_patron2], $result);

        }

        function testDeleteAll()
        {
            //Arrange
            $name = "Intro to Art";
            $test_patron = new Patron($name);
            $test_patron->save();

            $name2 = "Intro to Spanish";
            $test_patron2 = new Patron($name2);
            $test_patron2->save();

            //Act
            Patron::deleteAll();
            $result = Patron::getAll();

            //Assert
            $this->assertEquals([], $result);

        }
    }
?>
