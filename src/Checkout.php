<?php
    class Checkout
    {
        private $checked_in_status;
        private $due_date;
        private $copy_id;
        private $patron_id;
        private $id;

        function __construct($checked_in_status, $due_date, $copy_id, $patron_id, $id = null)
        {
            $this->checked_in_status = $checked_in_status;
            $this->due_date = $due_date;
            $this->copy_id = $copy_id;
            $this->patron_id = $patron_id;
            $this->id = $id;
        }

        //Setters
        function setCheckedInStatus($new_checked_in_status)
        {
            $this->checked_in_status = (int) $new_checked_in_status;
        }

        function setDueDate($new_due_date)
        {
            $this->due_date = (string) $new_due_date;
        }

        //Getters
        function getCheckedInStatus()
        {
            return $this->checked_in_status;
        }

        function getDueDate()
        {
            return $this->due_date;
        }

        function getCopyId()
        {
            return $this->copy_id;
        }

        function getPatronId()
        {
            return $this->patron_id;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO checkouts (checked_in_status, due_date, copy_id, patron_id) VALUES (
                {$this->getCheckedInStatus()},
                '{$this->getDueDate()}',
                {$this->getCopyId()},
                {$this->getPatronId()}
            );");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_checkouts = $GLOBALS['DB']->query("SELECT * FROM checkouts;");
            $checkouts = array();
            foreach($returned_checkouts as $checkout){
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

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM checkouts;");
        }

        function update($new_checked_in_status, $new_due_date)
        {
            $GLOBALS['DB']->exec("UPDATE checkouts SET checked_in_status = {$new_checked_in_status}, due_date = '{$new_due_date}'
                WHERE id = {$this->getId()};");
            $this->setCheckedInStatus($new_checked_in_status);
            $this->setDueDate($new_due_date);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE * FROM checkouts WHERE id = {$this->getId()};");
        }

        function getCopy()
        {
            $query = $GLOBALS['DB']->query("SELECT * FROM copies WHERE id = {$this->getCopyId()};");
            $returned_copy = $query->fetchAll(PDO::FETCH_ASSOC);
            $book_id = $returned_copy[0]['book_id'];
            $id = $returned_copy[0]['id'];
            $copy = new Copy($book_id, $id);
            return $copy;
        }
    }
?>
