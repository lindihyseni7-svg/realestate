<?php

namespace App\Controllers;

use App\Helper\Session;
use App\Models\Agent;
use App\Models\Listing;
use App\Models\Location;
use App\Models\PropertyType;
use \Core\View;
use \Core\Controller;


class HomeController extends Controller
{
    public function __construct($route_params = [])
    {
        parent::__construct($route_params);
    }

    public function index()
    {
        $session = Session::getInstance();
        $keyword = trim($_GET['location'] ?? $_GET['q'] ?? '');
        $typeId = trim($_GET['type'] ?? '');
        $budget = trim($_GET['budget'] ?? '');

        $query = Listing::with(['agent', 'propertyType']);

        if ($keyword !== '') {
            $query->where(function ($innerQuery) use ($keyword) {
                $innerQuery
                    ->where('address', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }

        if ($typeId !== '' && ctype_digit($typeId)) {
            $query->where('type_id', (int) $typeId);
        }

        if ($budget !== '') {
            $numbersOnly = preg_replace('/[^0-9.]/', '', $budget);
            if ($numbersOnly !== '') {
                $query->where('price', '<=', (float) $numbersOnly);
            }
        }

        $listings = $query->orderBy('id', 'desc')->get();

        View::renderTemplate('landing.php', [
            'listings' => $listings,
            'agents_count' => Agent::count(),
            'listings_count' => Listing::count(),
            'locations_count' => Location::count(),
            'property_types' => PropertyType::orderBy('name')->get(),
            'is_logged_in' => $session->isSignedIn(),
            'search' => [
                'location' => $keyword,
                'type' => $typeId,
                'budget' => $budget,
            ],
            'property_images' => [
                '/realestate/public/images/card1.jpg',
                '/realestate/public/images/card2.jpg',
                '/realestate/public/images/card3.jpg',
                '/realestate/public/images/section1.jpg',
                '/realestate/public/images/section5.jpg',
                '/realestate/public/images/bigsection.jpg',
            ],
        ]);
    }

    public function buy()
    {
        $this->index();
    }
}
