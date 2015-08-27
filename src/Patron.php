<?php
class Patron
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
        $GLOBALS['DB']->exec("INSERT INTO patrons (name) VALUES ('{$this->getName()}');");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    static function getAll()
    {
        $returned_patrons = $GLOBALS['DB']->query("SELECT * FROM patrons;");
        $patrons = array();
        foreach($returned_patrons as $patron) {
            $patron_name = $patron['name'];
            $id = $patron['id'];
            $new_patron = new Patron($patron_name, $id);
            array_push($patrons, $new_patron);
        }
        return $patrons;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM patrons;");
    }

    static function find($search_id)
    {
        $found_patron = null;
        $patrons = Patron::getAll();
        foreach($patrons as $patron){
            $patron_id = $patron->getId();
            if ($patron_id == $search_id){
                $found_patron = $patron;
            }
        }
        return $found_patron;
    }

    function addCheckout($copy)
    {
        $GLOBALS['DB']->exec("INSERT INTO checkouts (checked_in_status, due_date, copy_id, patron_id)
            VALUES (0, ADDDATE(CURDATE(), 7), {$copy->getId()}, {$this->getId()});");
    }

    function getCheckouts()
    {
        $returned_checkouts = $GLOBALS['DB']->query("SELECT * FROM checkouts WHERE patron_id = {$this->getId()};");
        $checkouts = array();
        foreach($returned_checkouts as $checkout) {
            $checked_in_status = $checkout['checked_in_status'];
            $due_date = $checkout['due_date'];
            $copy_id = $checkout['copy_id'];
            $patron_id = $checkout['patron_id'];
            $id = $checkout['id'];
            $new_checkout = new Checkout($checked_in_status, $due_date, $copy_id, $patron_id, $id);
            array_push($checkouts, $new_checkout);
        }
        return $checkouts;
    }

}
 ?>
