<?php

namespace App\Controllers;

use App\Helper\Session;
use App\Models\Amenity;
use \Core\View;
use \Core\Controller;

class AmenityController extends Controller
{
    public function __construct($route_params = [])
    {
        parent::__construct($route_params);
        $session = Session::getInstance();
        if (!$session->isSignedIn()){
            header('Location: ' . \Core\View::url('login'));
            exit;
        }
    }
    
    public function index()
    {
        $amenities = Amenity::orderBy('id', 'desc')->get();

        View::renderTemplate('Amenities/index.html', ['amenities' => $amenities]);
    }

    public function create()
    {
        View::renderTemplate('Amenities/create.html');
    }

    public function store()
    {
        $amenity = new Amenity();
        $amenity->name = $_POST['name'];
        $amenity->save();
        header('Location: ' . \Core\View::url('amenities'));
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'];
        $amenity = Amenity::findOrFail($id);

        View::renderTemplate('Amenities/edit.html',['amenity' => $amenity]);
    }

    public function update()
    {
        $id = $_POST['id'];
        $amenity = Amenity::findOrFail($id);
        $amenity->name = $_POST['name'];
        $amenity->save();
        header('Location: ' . \Core\View::url('amenities'));
        exit;
    }

    public function destroy()
    {
        $id = $_POST['id'];
        $amenity = Amenity::findOrFail($id);
        $amenity->delete();
        header('Location: ' . \Core\View::url('amenities'));
        exit;
    }
}



