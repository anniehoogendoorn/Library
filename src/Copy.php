<?php
class Copy
{
    private $book_id;
    private $id;

    function __construct($book_id, $id = null)
    {
        $this->book_id = $book_id;
        $this->id = $id;
    }

    function setBookId($new_book_id)
    {
        $this->book_id = (string) $new_book_id;
    }

    function getBookId()
    {
        return $this->book_id;
    }

    function getId()
    {
        return $this->id;
    }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO copies (book_id) VALUES ('{$this->getBookId()}');");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    static function getAll()
    {
        $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies;");
        $copies = array();
        foreach($returned_copies as $copy) {
            $book_id = $copy['book_id'];
            $id = $copy['id'];
            $new_copy = new Copy($book_id, $id);
            array_push($copies, $new_copy);
        }
        return $copies;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM copies;");
    }

    static function find($search_id)
    {
        $found_copy = null;
        $copies = Copy::getAll();
        foreach($copies as $copy){
            $copy_id = $copy->getId();
            if($copy_id == $search_id){
                $found_copy = $copy;
            }
        }
        return $found_copy;
    }

    function delete()
    {
        $GLOBALS['DB']->exec("DELETE FROM copies WHERE id = {$this->getId()};");
        $GLOBALS['DB']->exec("DELETE FROM checkouts where copy_id = {$this->getId()};");
    }

    function getBook()
    {
        $query = $GLOBALS['DB']->query("SELECT * FROM books WHERE id = {$this->getBookId()};");
        $book = $query->fetchAll(PDO::FETCH_ASSOC);
        $title = $book[0]['title'];
        $id = $book[0]['id'];
        $new_book = new Book($title, $id);

        return $new_book;
    }

    function getCheckout()
    {
        $query = $GLOBALS['DB']->query("SELECT * FROM checkouts WHERE copy_id = {$this->getId()};");
        $checkout = $query->fetchAll(PDO::FETCH_ASSOC);
        $checked_in_status = $checkout[0]['checked_in_status'];
        $due_date = $checkout[0]['due_date'];
        $copy_id = $checkout[0]['copy_id'];
        $patron_id = $checkout[0]['patron_id'];
        $id = $checkout[0]['id'];
        $new_checkout = new Checkout($checked_in_status, $due_date, $copy_id, $patron_id, $id);

        return $new_checkout;
    }

    function addCheckout($patron)
    {
        $GLOBALS['DB']->exec("INSERT INTO checkouts (checked_in_status, due_date, copy_id, patron_id)
            VALUES (0, ADDDATE(CURDATE(), 7), {$this->getId()}, {$patron->getId()});");
    }

}
 ?>
