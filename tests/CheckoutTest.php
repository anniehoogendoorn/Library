<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Checkout.php";
    require_once "src/Book.php";
    require_once "src/Copy.php";
    require_once "src/Patron.php";

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CheckoutTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Copy::deleteAll();
            Book::deleteAll();
            Checkout::deleteAll();
        }

        function test_save()
        {
            //Arrange
            $checked_in_status = 1;
            $due_date = "8015-08-27";
            $copy_id = 1;
            $patron_id = 1;
            $test_checkout = new Checkout($checked_in_status, $due_date, $copy_id, $patron_id);
            $test_checkout->save();

            //Act
            $result = Checkout::getAll();

            //Assert
            $this->assertEquals([$test_checkout], $result);
        }

        function test_getAll()
        {
            //Arrange
            $checked_in_status = 1;
            $due_date = "8015-08-27";
            $copy_id = 1;
            $patron_id = 1;
            $test_checkout = new Checkout($checked_in_status, $due_date, $copy_id, $patron_id);
            $test_checkout->save();

            $checked_in_status2 = 0;
            $due_date2 = "9015-08-27";
            $copy_id2 = 2;
            $patron_id2 = 2;
            $test_checkout2 = new Checkout($checked_in_status2, $due_date2, $copy_id2, $patron_id2);
            $test_checkout2->save();

            //Act
            $result = Checkout::getAll();

            //Assert
            $this->assertEquals([$test_checkout, $test_checkout2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $checked_in_status = 1;
            $due_date = "8015-08-27";
            $copy_id = 1;
            $patron_id = 1;
            $test_checkout = new Checkout($checked_in_status, $due_date, $copy_id, $patron_id);
            $test_checkout->save();

            $checked_in_status2 = 0;
            $due_date2 = "9015-08-27";
            $copy_id2 = 2;
            $patron_id2 = 2;
            $test_checkout2 = new Checkout($checked_in_status2, $due_date2, $copy_id2, $patron_id2);
            $test_checkout2->save();

            //Act
            Checkout::deleteAll();
            $result = Checkout::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_update()
        {
            //Arrange
            $checked_in_status = 0;
            $due_date = "2000 BC";
            $copy_id = 4;
            $patron_id = 4;
            $test_checkout = new Checkout($checked_in_status, $due_date, $copy_id, $patron_id);
            $test_checkout->save();

            $checked_in_status2 = 1;
            $due_date2 = "2015-27-08";
            $copy_id2 = 5;
            $patron_id2 = 5;
            $test_checkout2 = new Checkout($checked_in_status2, $due_date2, $copy_id2, $patron_id2);
            $test_checkout2->save();

            //Act
            $test_checkout->update($checked_in_status2, $due_date2);

            //Assert
            $this->assertEquals($test_checkout2->getDueDate(), $test_checkout->getDueDate());
        }

        function test_delete()
        {
            //Arrange
            $checked_in_status = 0;
            $due_date = "2000 BC";
            $copy_id = 4;
            $patron_id = 4;
            $test_checkout = new Checkout($checked_in_status, $due_date, $copy_id, $patron_id);
            $test_checkout->save();

            //Act
            $test_checkout->delete();
            $result = Checkout::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_getCopy()
        {
            //Arrange
            $book_id = 1;
            $test_copy = new Copy($book_id);
            $test_copy->save();

            $checked_in_status = 0;
            $due_date = "2000 BC";
            $patron_id = 1;
            $test_checkout = new Checkout($checked_in_status, $due_date, $test_copy->getId(), $patron_id);
            $test_checkout->save();

            //Act
            $result = $test_checkout->getCopy();

            //Assert
            $this->assertEquals($test_copy, $result);
        }

        function test_getPatron()
        {
            //Arrange
            $name = "Phyllis the kind Grandma";
            $test_patron = new Patron($name);
            $test_patron->save();

            $checked_in_status = 0;
            $due_date = "2000 BC";
            $copy_id = 1;
            $test_checkout = new Checkout($checked_in_status, $due_date, $copy_id, $test_patron->getId());
            $test_checkout->save();

            //Act
            $result = $test_checkout->getPatron();

            //Assert
            $this->assertEquals($test_patron, $result);
        }
    }
?>
