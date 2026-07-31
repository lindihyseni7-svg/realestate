<?php
namespace App\Controllers;

use App\Helper\Session;
use App\Models\Agent;
use App\Models\Amenity;
use App\Models\Contact;
use App\Models\Listing;
use App\Models\Location;
use App\Models\PropertyType;
use App\Models\User;
use Core\Controller;
use Core\View;

class ListingController extends Controller
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
        $keyword = trim($_GET['q'] ?? '');
        $query = Listing::with(['agent', 'propertyType']);

        if ($keyword !== '') {
            $query->where(function ($innerQuery) use ($keyword) {
                $innerQuery
                    ->where('address', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%')
                    ->orWhereHas('agent', function ($agentQuery) use ($keyword) {
                        $agentQuery->where('name', 'like', '%' . $keyword . '%');
                    })
                    ->orWhereHas('propertyType', function ($typeQuery) use ($keyword) {
                        $typeQuery->where('name', 'like', '%' . $keyword . '%');
                    });
            });
        }

        $listings = $query->orderBy('id', 'desc')->get();

        View::renderTemplate('Listings/index.html', [
            'listings' => $listings,
            'q' => $keyword,
            'stats' => [
                'listings' => Listing::count(),
                'agents' => Agent::count(),
                'users' => User::count(),
                'locations' => Location::count(),
                'property_types' => PropertyType::count(),
                'amenities' => Amenity::count(),
                'contacts' => Contact::count(),
            ],
        ]);
    }

    public function create()
    {
        $agents = Agent::orderBy('name')->get();
        $property_types = PropertyType::orderBy('name')->get();
        View::renderTemplate('Listings/create.html', [
            'agents' => $agents,
            'property_types' => $property_types
        ]);
    }

    public function store()
    {
        $listing = new Listing();
        $listing->address = $_POST['address'];
        $listing->price = $_POST['price'];
        $listing->bedrooms = $_POST['bedrooms'];
        $listing->bathrooms = $_POST['bathrooms'];
        $listing->description = $_POST['description'];
        $listing->agent_id = $_POST['agent_id'];
        $listing->type_id = $_POST['type_id'];
        $listing->save();
        header('Location: ' . \Core\View::url('listings'));
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'];
        $listing = Listing::findOrFail($id);
        $agents = Agent::orderBy('name')->get();
        $property_types = PropertyType::orderBy('name')->get();
        View::renderTemplate('Listings/edit.html', [
            'listing' => $listing,
            'agents' => $agents,
            'property_types' => $property_types
        ]);
    }

    public function update()
    {
        $id = $_POST['id'];
        $listing = Listing::findOrFail($id);
        $listing->address = $_POST['address'];
        $listing->price = $_POST['price'];
        $listing->bedrooms = $_POST['bedrooms'];
        $listing->bathrooms = $_POST['bathrooms'];
        $listing->description = $_POST['description'];
        $listing->agent_id = $_POST['agent_id'];
        $listing->type_id = $_POST['type_id'];
        $listing->save();
        header('Location: ' . \Core\View::url('listings'));
        exit;
    }

    public function destroy()
    {
        $id = $_POST['id'];
        $listing = Listing::findOrFail($id);
        $listing->delete();
        header('Location: ' . \Core\View::url('listings'));
        exit;
    }
}
