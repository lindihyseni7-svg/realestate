<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Real Estate | Find your next space</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="/realestate/public/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/realestate/public/style/landing-modern.css">
</head>
<body>
<nav class="site-nav navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand brand-mark" href="/realestate/public/">
            <span class="brand-icon"><i class="fa-solid fa-house-chimney"></i></span>
            Real Estate
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavigation">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="/realestate/public/">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/realestate/public/buy#featured">Buy</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#agents">Agents</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                {% if is_logged_in %}
                <li class="nav-item"><a class="nav-link" href="/realestate/public/listings">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/realestate/public/logout">Logout</a></li>
                {% else %}
                <li class="nav-item"><a class="nav-link" href="/realestate/public/login">Login</a></li>
                {% endif %}
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-nav" href="#contact">Book a tour</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<header class="hero-section" id="home">
    <div class="hero-shape hero-shape-one"></div>
    <div class="hero-shape hero-shape-two"></div>
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 hero-copy reveal">
                <span class="eyebrow"><i class="fa-solid fa-location-dot"></i> Kosovo premium property platform</span>
                <h1>Find a home that feels right before you even visit.</h1>
                <p class="hero-lead">
                    Browse curated apartments, houses, rentals and commercial spaces with trusted agents, clean details and fast contact flow.
                </p>
                <div class="hero-actions">
                    <a class="btn btn-primary-modern" href="#featured">Explore listings</a>
                    <a class="btn btn-ghost" href="#services">How it works</a>
                </div>
                <div class="hero-stats">
                    <div>
                        <strong>{{ listings_count|default(0) }}+</strong>
                        <span>Listings</span>
                    </div>
                    <div>
                        <strong>{{ agents_count|default(0) }}+</strong>
                        <span>Agents</span>
                    </div>
                    <div>
                        <strong>{{ locations_count|default(0) }}+</strong>
                        <span>Locations</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 reveal reveal-delay">
                <div class="hero-card">
                    <img src="/realestate/public/images/hero1.jpg" alt="Modern property interior">
                    <div class="floating-card floating-card-top">
                        <i class="fa-solid fa-chart-line"></i>
                        <div>
                            <span>Market insight</span>
                            <strong>Smart pricing</strong>
                        </div>
                    </div>
                    <div class="floating-card floating-card-bottom">
                        <i class="fa-solid fa-key"></i>
                        <div>
                            <span>Tour-ready</span>
                            <strong>Fast appointments</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<section class="search-panel-section">
    <div class="container">
        <form class="search-panel reveal" method="get" action="/realestate/public/buy#featured">
            <div class="search-field">
                <label>Location</label>
                <input type="text" name="location" value="{{ search.location|default('') }}" placeholder="Prishtine, Peje, Prizren">
            </div>
            <div class="search-field">
                <label>Property Type</label>
                <select name="type">
                    <option value="">Any type</option>
                    {% for type in property_types %}
                    <option value="{{ type.id }}" {% if search.type|default('') == type.id %}selected{% endif %}>{{ type.name }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="search-field">
                <label>Budget</label>
                <input type="text" name="budget" value="{{ search.budget|default('') }}" placeholder="Max price, e.g. 150000">
            </div>
            <button class="btn btn-search" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
        </form>
    </div>
</section>

<section class="section-space" id="featured">
    <div class="container">
        <div class="section-heading reveal">
            <span class="eyebrow">Featured properties</span>
            <h2>Fresh listings picked for serious buyers.</h2>
            {% if search.location|default('') or search.type|default('') or search.budget|default('') %}
            <p>Showing search results from your database. Clear the fields and search again to see every property.</p>
            {% else %}
            <p>Fresh cards in the right place — after hero/search — using real listing data from your database.</p>
            {% endif %}
        </div>

        {% if listings|length > 0 %}
        <div class="property-grid">
            {% for listing in listings %}
            {% set image = property_images[loop.index0 % property_images|length] %}
            <article class="property-card reveal">
                <div class="property-image">
                    <img src="{{ image }}" alt="{{ listing.address }}">
                    <span class="badge-soft">{{ listing.propertyType.name|default('Property') }}</span>
                </div>
                <div class="property-body">
                    <div class="property-price">${{ listing.price|number_format(2, '.', ',') }}</div>
                    <h3>{{ listing.address }}</h3>
                    <p>{{ listing.description|default('Beautiful property ready for viewing.') }}</p>
                    <div class="property-meta">
                        <span><i class="fa-solid fa-bed"></i> {{ listing.bedrooms|default(0) }} beds</span>
                        <span><i class="fa-solid fa-bath"></i> {{ listing.bathrooms|default(0) }} baths</span>
                    </div>
                </div>
                <div class="property-footer">
                    <span><i class="fa-solid fa-user-tie"></i> {{ listing.agent.name|default('Agent') }}</span>
                    <a href="#contact">Request tour</a>
                </div>
            </article>
            {% endfor %}
        </div>
        {% else %}
        <div class="empty-state reveal">
            <i class="fa-solid fa-house-circle-exclamation"></i>
            <h3>No listings yet</h3>
            <p>No property matched this search. Try another location, type or budget.</p>
            <a class="btn btn-primary-modern" href="/realestate/public/login">Open dashboard</a>
        </div>
        {% endif %}
    </div>
</section>

<section class="section-space services-section" id="services">
    <div class="container">
        <div class="section-heading reveal">
            <span class="eyebrow">What we do</span>
            <h2>A complete real estate workflow, not only a listing page.</h2>
        </div>
        <div class="service-grid">
            <div class="service-card reveal">
                <i class="fa-solid fa-house-laptop"></i>
                <h3>Smart discovery</h3>
                <p>Clean property cards, fast navigation, visible pricing, bedroom/bathroom details and agent info.</p>
            </div>
            <div class="service-card reveal">
                <i class="fa-solid fa-calendar-check"></i>
                <h3>Tour requests</h3>
                <p>Visitors can request a viewing through the contact form and every request is saved in MySQL.</p>
            </div>
            <div class="service-card reveal">
                <i class="fa-solid fa-user-shield"></i>
                <h3>Admin dashboard</h3>
                <p>Manage agents, users, locations, property types, amenities and listings from one protected area.</p>
            </div>
        </div>
    </div>
</section>

<section class="split-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 reveal">
                <img class="split-image" src="/realestate/public/images/section1.jpg" alt="Beautiful living space">
            </div>
            <div class="col-lg-6 reveal reveal-delay">
                <span class="eyebrow">Designed for trust</span>
                <h2>Every listing has a human behind it.</h2>
                <p>Agents, location data and property types are connected through Eloquent relations, so the UI shows meaningful data instead of placeholder blocks.</p>
                <ul class="check-list">
                    <li><i class="fa-solid fa-check"></i> Verified agents and contact details</li>
                    <li><i class="fa-solid fa-check"></i> Clear price and property specifications</li>
                    <li><i class="fa-solid fa-check"></i> Fast admin CRUD for future updates</li>
                </ul>
                <a class="btn btn-primary-modern" href="/realestate/public/buy#featured">See properties</a>
            </div>
        </div>
    </div>
</section>

<section class="section-space process-section">
    <div class="container">
        <div class="section-heading reveal">
            <span class="eyebrow">Process</span>
            <h2>From browsing to keys in three calm steps.</h2>
        </div>
        <div class="process-grid">
            <div class="process-step reveal">
                <span>01</span>
                <h3>Discover</h3>
                <p>Explore curated listings with modern cards and quick details.</p>
            </div>
            <div class="process-step reveal">
                <span>02</span>
                <h3>Connect</h3>
                <p>Send a request through the contact form or continue in the dashboard.</p>
            </div>
            <div class="process-step reveal">
                <span>03</span>
                <h3>Visit</h3>
                <p>Schedule a tour and make the final decision with your agent.</p>
            </div>
        </div>
    </div>
</section>

<section class="section-space agents-section" id="agents">
    <div class="container">
        <div class="section-heading reveal">
            <span class="eyebrow">Expert team</span>
            <h2>Agents who know the market.</h2>
        </div>
        <div class="agent-grid">
            <div class="agent-card reveal">
                <img src="/realestate/public/images/team-1.jpg" alt="Agent">
                <h3>Arben Krasniqi</h3>
                <p>Residential advisor</p>
            </div>
            <div class="agent-card reveal">
                <img src="/realestate/public/images/team-2.jpg" alt="Agent">
                <h3>Elira Berisha</h3>
                <p>Rental specialist</p>
            </div>
            <div class="agent-card reveal">
                <img src="/realestate/public/images/team-3.jpg" alt="Agent">
                <h3>Jon Gashi</h3>
                <p>Commercial spaces</p>
            </div>
            <div class="agent-card reveal">
                <img src="/realestate/public/images/team-4.jpg" alt="Agent">
                <h3>Nora Hoxha</h3>
                <p>Investment consultant</p>
            </div>
        </div>
    </div>
</section>

<section class="contact-section-modern" id="contact">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5 reveal">
                <span class="eyebrow">Contact us</span>
                <h2>Tell us what kind of property you want.</h2>
                <p>We will store your request in the `contacts` table and follow up with the best matching options.</p>
                <div class="contact-info">
                    <a href="mailto:info@realestate.test"><i class="fa-solid fa-envelope"></i> info@realestate.test</a>
                    <a href="tel:+38344111222"><i class="fa-solid fa-phone"></i> +383 44 111 222</a>
                    <span><i class="fa-solid fa-location-dot"></i> Prishtine, Kosovo</span>
                </div>
            </div>
            <div class="col-lg-7 reveal reveal-delay">
                <form class="contact-card" method="post" action="/realestate/public/contact-store">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="first_name">First name</label>
                            <input id="first_name" name="first_name" type="text" required>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name">Last name</label>
                            <input id="last_name" name="last_name" type="text" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="location">Preferred location</label>
                            <input id="location" name="location" type="text" placeholder="Prishtine, Peje..." required>
                        </div>
                        <div class="col-12">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" rows="5" placeholder="Budget, property type, move-in date..." required></textarea>
                        </div>
                    </div>
                    <button class="btn btn-primary-modern w-100 mt-4" type="submit">Send request</button>
                </form>
            </div>
        </div>
    </div>
</section>

<footer class="footer-modern">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <a class="brand-mark footer-brand" href="/realestate/public/">
                    <span class="brand-icon"><i class="fa-solid fa-house-chimney"></i></span>
                    Real Estate
                </a>
                <p>Modern MVC real estate platform with Twig views, Eloquent models and a clean public landing experience.</p>
            </div>
            <div class="col-lg-2 col-md-4">
                <h4>Explore</h4>
                <a href="#featured">Listings</a>
                <a href="#services">Services</a>
                <a href="#agents">Agents</a>
            </div>
            <div class="col-lg-2 col-md-4">
                <h4>Dashboard</h4>
                {% if is_logged_in %}
                <a href="/realestate/public/listings">Listings</a>
                <a href="/realestate/public/agents">Agents</a>
                <a href="/realestate/public/users">Users</a>
                {% else %}
                <a href="/realestate/public/login">Admin login</a>
                <a href="#contact">Request access</a>
                {% endif %}
            </div>
            <div class="col-lg-4 col-md-4">
                <h4>Ready to move?</h4>
                <p>Start with the listings or send a request and let an agent guide you.</p>
                <a class="btn btn-footer" href="#contact">Book a tour</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const nav = document.querySelector('.site-nav');
    const revealItems = document.querySelectorAll('.reveal');

    window.addEventListener('scroll', () => {
        nav.classList.toggle('is-scrolled', window.scrollY > 20);
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            }
        });
    }, { threshold: 0.14 });

    revealItems.forEach((item) => observer.observe(item));
</script>
</body>
</html>
