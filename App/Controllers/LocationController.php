<?php

namespace App\Controllers;

use App\Helper\Session;
use App\Models\Location;
use \Core\View;
use \Core\Controller;

class LocationController extends Controller
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
        $locations = Location::orderBy('id', 'desc')->get();

        View::renderTemplate('Locations/index.html', ['locations' => $locations]);
    }

    public function create()
    {
        View::renderTemplate('Locations/create.html');
    }

    public function store()
    {
        $location = new Location();
        $location->name = $_POST['name'];
        $location->type = $_POST['type'];
        $location->save();
        header('Location: ' . \Core\View::url('locations'));
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'];
        $location = Location::findOrFail($id);

        View::renderTemplate('Locations/edit.html',['location' => $location]);
    }

    public function update()
    {
        $id = $_POST['id'];
        $location = Location::findOrFail($id);
        $location->name = $_POST['name'];
        $location->type = $_POST['type'];
        $location->save();
        header('Location: ' . \Core\View::url('locations'));
        exit;
    }

    public function destroy()
    {
        $id = $_POST['id'];
        $location = Location::findOrFail($id);
        $location->delete();
        header('Location: ' . \Core\View::url('locations'));
        exit;
    }
}
