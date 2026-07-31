<?php

namespace App\Controllers;

use App\Helper\Session;
use App\Models\Agent;
use \Core\View;
use \Core\Controller;


class AgentController extends Controller
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
        $agents = Agent::orderBy('id', 'desc')->get();

        View::renderTemplate('Agents/index.html', ['agents' => $agents]);
    }

    public function create()
    {
        View::renderTemplate('Agents/create.html');
    }

    public function store()
    {
        $agent = new Agent();
        $agent->name = $_POST['name'];
        $agent->email = $_POST['email'];
        $agent->phone = $_POST['phone'];
        $agent->agency = $_POST['agency'];
        $agent->bio = $_POST['bio'];
        $agent->save();
        header('Location: ' . \Core\View::url('agents'));
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'];
        $agent = Agent::findOrFail($id);

        View::renderTemplate('Agents/edit.html',['agent' => $agent]);
    }

    public function update()
    {
        $id = $_POST['id'];
        $agent = Agent::findOrFail($id);
        $agent->name = $_POST['name'];
        $agent->email = $_POST['email'];
        $agent->phone = $_POST['phone'];
        $agent->agency = $_POST['agency'];
        $agent->bio = $_POST['bio'];
        $agent->save();
        header('Location: ' . \Core\View::url('agents'));
        exit;
    }

    public function destroy()
    {
        $id = $_POST['id'];
        $agent = Agent::findOrFail($id);
        $agent->delete();
        header('Location: ' . \Core\View::url('agents'));
        exit;
    }
}
