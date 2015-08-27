<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Patron.php";
    require_once "src/Checkout.php";
    require_once "src/Copy.php";

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class PatronTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Patron::deleteAll();
            Checkout::deleteAll();
            Copy::deleteAll();
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

        function test_find()
        {
            //Arrange
            $name = "Intro to Art";
            $test_patron = new Patron($name);
            $test_patron->save();

            $name2 = "Intro to Spanish";
            $test_patron2 = new Patron($name2);
            $test_patron2->save();

            //Act
            $result = Patron::find($test_patron->getId());

            //Assert
            $this->assertEquals($test_patron, $result);
        }

        function test_getCheckouts()
        {
            //Arrange
            $name = "Jerald the crotchety grandpa";
            $test_patron = new Patron($name);
            $test_patron->save();

            $checked_in_status = 0;
            $due_date = "1234-12-12";
            $copy_id = 5;
            $new_checkout = new Checkout($checked_in_status, $due_date, $copy_id, $test_patron->getId());
            $new_checkout->save();


            //Act
            $result = $test_patron->getCheckouts();

            //Assert
            $this->assertEquals([$new_checkout], $result);
        }

        function test_addCheckout()
        {
            //Arrange
            $name = "Jerald the crotchety grandpa";
            $test_patron = new Patron($name);
            $test_patron->save();

            $book_id = 8;
            $test_copy = new Copy($book_id);
            $test_copy->save();

            //Act
            $test_patron->addCheckout($test_copy);
            $result = $test_patron->getCheckouts();

            //Assert
            $this->assertEquals($test_patron->getId(), $result[0]->getPatronId());
        }
    }
?>
