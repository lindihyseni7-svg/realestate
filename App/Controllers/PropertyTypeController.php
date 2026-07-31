<?php

namespace App\Controllers;

use App\Helper\Session;
use App\Models\PropertyType;
use \Core\View;
use \Core\Controller;

class PropertyTypeController extends Controller
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
        $property_types = PropertyType::orderBy('id', 'desc')->get();

        View::renderTemplate('Property_types/index.html', ['property_types' => $property_types]);
    }

    public function create()
    {
        View::renderTemplate('Property_types/create.html');
    }

    public function store()
    {
        $propertyType = new PropertyType();
        $propertyType->name = $_POST['name'];
        $propertyType->save();
        header('Location: ' . \Core\View::url('property_types'));
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'];
        $propertyType = PropertyType::findOrFail($id);

        View::renderTemplate('Property_types/edit.html',['property_type' => $propertyType]);
    }

    public function update()
    {
        $id = $_POST['id'];
        $propertyType = PropertyType::findOrFail($id);
        $propertyType->name = $_POST['name'];
        $propertyType->save();
        header('Location: ' . \Core\View::url('property_types'));
        exit;
    }

    public function destroy()
    {
        $id = $_POST['id'];
        $propertyType = PropertyType::findOrFail($id);
        $propertyType->delete();
        header('Location: ' . \Core\View::url('property_types'));
        exit;
    }
}
