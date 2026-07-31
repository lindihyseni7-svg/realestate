<?php

namespace App\Controllers;

use App\Models\Contact;
use Core\Controller;

class ContactController extends Controller
{
    public function __construct($route_params = [])
    {
        parent::__construct($route_params);
    }

    public function store()
    {
        $contact = new Contact();
        $contact->first_name = $_POST['first_name'];
        $contact->last_name = $_POST['last_name'];
        $contact->email = $_POST['email'];
        $contact->department = $_POST['location'];
        $contact->message = $_POST['message'];
        $contact->save();

        header('Location: ' . \Core\View::url('') . '#contact');
        exit;
    }
}
