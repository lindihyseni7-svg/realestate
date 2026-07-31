<?php
declare(strict_types=1);

session_start();

function app_config(): array
{
    $configFile = __DIR__ . '/App/database.php';

    if (is_file($configFile)) {
        return require $configFile;
    }

    return [
        'host' => '127.0.0.1',
        'database' => 'realestate',
        'username' => 'root',
        'password' => '',
        'port' => 3306,
        'charset' => 'utf8mb4',
    ];
}

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $config = app_config();
    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=%s',
        $config['host'],
        (int) ($config['port'] ?? 3306),
        $config['database'],
        $config['charset'] ?? 'utf8mb4'
    );

    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}

function base_url(): string
{
    $dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

    return ($dir === '' || $dir === '/') ? '' : $dir;
}

function url(string $path = ''): string
{
    return rtrim(base_url(), '/') . '/' . ltrim($path, '/');
}

function asset_url(string $path): string
{
    return url('public/' . ltrim($path, '/'));
}

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect_to(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function route_path(): string
{
    $query = $_SERVER['QUERY_STRING'] ?? '';
    $path = strtok($query, '&') ?: '';

    if ($path === '' && isset($_GET['route'])) {
        $path = (string) $_GET['route'];
    }

    return trim($path, '/');
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        redirect_to('login');
    }
}

function counts(): array
{
    $tables = ['listings', 'agents', 'locations'];
    $counts = [];

    foreach ($tables as $table) {
        $counts[$table] = (int) db()->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
    }

    return $counts;
}

function property_types(): array
{
    return db()->query('SELECT id, name FROM property_types ORDER BY name')->fetchAll();
}

function listings(bool $limit = false): array
{
    $where = [];
    $params = [];

    $location = trim($_GET['location'] ?? $_GET['q'] ?? '');
    $type = trim($_GET['type'] ?? '');
    $budget = trim($_GET['budget'] ?? '');

    if ($location !== '') {
        $where[] = '(l.address LIKE :keyword OR l.description LIKE :keyword)';
        $params['keyword'] = '%' . $location . '%';
    }

    if ($type !== '' && ctype_digit($type)) {
        $where[] = 'l.type_id = :type_id';
        $params['type_id'] = (int) $type;
    }

    if ($budget !== '') {
        $number = preg_replace('/[^0-9.]/', '', $budget);
        if ($number !== '') {
            $where[] = 'l.price <= :budget';
            $params['budget'] = (float) $number;
        }
    }

    $sql = 'SELECT l.*, a.name AS agent_name, pt.name AS property_type
        FROM listings l
        LEFT JOIN agents a ON a.id = l.agent_id
        LEFT JOIN property_types pt ON pt.id = l.type_id';

    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }

    $sql .= ' ORDER BY l.id DESC';

    if ($limit) {
        $sql .= ' LIMIT 8';
    }

    $stmt = db()->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

function render_header(string $title = 'Real Estate'): void
{
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . h($title) . '</title>';
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    echo '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">';
    echo '<link href="' . h(asset_url('css/bootstrap.min.css')) . '" rel="stylesheet">';
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">';
    echo '<link rel="stylesheet" href="' . h(asset_url('style/landing-modern.css')) . '">';
    echo '<style>
        :root{--admin-bg:#f4f7fb;--admin-ink:#172033;--admin-muted:#667085;--admin-line:#dbe3ef;--admin-primary:#1f6feb;--admin-deep:#101828;--admin-soft:#eaf2ff}
        body.admin-body{background:var(--admin-bg);color:var(--admin-ink)}
        .admin-shell{padding:128px 0 56px;background:linear-gradient(180deg,#eef5ff 0,#f8fafc 260px,#f4f7fb 100%)}
        .admin-hero{display:flex;justify-content:space-between;gap:24px;align-items:flex-end;margin-bottom:22px}
        .admin-hero h1{font-size:clamp(32px,4vw,54px);font-weight:900;letter-spacing:0;margin:0;color:#101828}
        .admin-hero p{margin:8px 0 0;color:var(--admin-muted)}
        .admin-grid{display:grid;grid-template-columns:260px minmax(0,1fr);gap:22px;align-items:start}
        .admin-side,.admin-panel,.stat-card,.quick-form{background:#fff;border:1px solid var(--admin-line);box-shadow:0 18px 50px rgba(16,24,40,.08);border-radius:18px}
        .admin-side{position:sticky;top:104px;padding:14px}
        .admin-side a{display:flex;align-items:center;gap:10px;padding:12px 14px;border-radius:12px;color:#344054;text-decoration:none;font-weight:800}
        .admin-side a.active,.admin-side a:hover{background:var(--admin-soft);color:var(--admin-primary)}
        .admin-panel{overflow:hidden}
        .admin-panel-head{display:flex;justify-content:space-between;gap:16px;align-items:center;padding:22px 24px;border-bottom:1px solid var(--admin-line)}
        .admin-panel-head h2{font-size:24px;font-weight:900;margin:0}
        .admin-panel-head span{color:var(--admin-muted);font-weight:700}
        .stats-row{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin-bottom:22px}
        .stat-card{padding:18px}
        .stat-card strong{display:block;font-size:30px;line-height:1;font-weight:900;color:var(--admin-deep)}
        .stat-card span{display:block;margin-top:8px;color:var(--admin-muted);font-weight:800}
        .admin-table{margin:0}
        .admin-table th{background:#f8fafc;color:#475467;text-transform:uppercase;font-size:12px;letter-spacing:.04em;border-bottom:1px solid var(--admin-line)}
        .admin-table td{vertical-align:middle;color:#253047;border-color:#edf2f7}
        .quick-form{padding:20px;margin-bottom:18px}
        .quick-form h3{font-size:18px;font-weight:900;margin-bottom:14px}
        .quick-form label{font-size:12px;text-transform:uppercase;color:#667085;font-weight:900;margin-bottom:6px}
        .quick-form input,.quick-form select,.quick-form textarea{width:100%;border:1px solid var(--admin-line);border-radius:10px;padding:11px 12px;background:#fff}
        .btn-admin{border:0;border-radius:12px;background:var(--admin-primary);color:#fff;font-weight:900;padding:11px 16px}
        .btn-danger-soft{border:0;border-radius:10px;background:#fee4e2;color:#b42318;font-weight:900;padding:8px 12px}
        @media (max-width: 900px){.admin-grid{grid-template-columns:1fr}.admin-side{position:static}.stats-row{grid-template-columns:repeat(2,1fr)}.admin-hero{display:block}}
        @media (max-width: 560px){.stats-row{grid-template-columns:1fr}}
    </style>';
    echo '</head><body>';
    echo '<nav class="site-nav navbar navbar-expand-lg fixed-top"><div class="container">';
    echo '<a class="navbar-brand brand-mark" href="' . h(url('')) . '"><span class="brand-icon"><i class="fa-solid fa-house-chimney"></i></span>Real Estate</a>';
    echo '<div class="collapse navbar-collapse show"><ul class="navbar-nav ms-auto align-items-lg-center">';
    echo '<li class="nav-item"><a class="nav-link" href="' . h(url('')) . '">Home</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="' . h(url('buy')) . '#featured">Buy</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>';
    echo is_logged_in()
        ? '<li class="nav-item"><a class="nav-link" href="' . h(url('dashboard')) . '">Dashboard</a></li><li class="nav-item"><a class="nav-link" href="' . h(url('logout')) . '">Logout</a></li>'
        : '<li class="nav-item"><a class="nav-link" href="' . h(url('login')) . '">Login</a></li>';
    echo '</ul></div></div></nav>';
}

function render_footer(): void
{
    echo '<footer class="footer-modern"><div class="container"><div class="row g-4">';
    echo '<div class="col-lg-5"><a class="brand-mark footer-brand" href="' . h(url('')) . '"><span class="brand-icon"><i class="fa-solid fa-house-chimney"></i></span>Real Estate</a>';
    echo '<p>Modern real estate platform connected directly to MySQL with a clean public landing experience.</p>';
    echo '<p class="mt-3 mb-0">Zhvilluar nga <strong>Arlind Hyseni</strong><br><a href="https://www.arlindhyseni.online" target="_blank" rel="noopener">www.arlindhyseni.online</a><br><a href="https://github.com/lindihyseni7-svg" target="_blank" rel="noopener">GitHub: lindihyseni7-svg</a></p></div>';
    echo '<div class="col-lg-3"><h4>Explore</h4><a href="' . h(url('buy')) . '#featured">Listings</a><a href="#contact">Contact</a><a href="' . h(url('login')) . '">Admin login</a></div>';
    echo '<div class="col-lg-4"><h4>Ready to move?</h4><p>Send a request and let an agent guide you.</p><a class="btn btn-footer" href="#contact">Book a tour</a></div>';
    echo '</div></div></footer>';
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>';
    echo '</body></html>';
}

function render_home(): void
{
    $counts = counts();
    $listings = listings();
    $types = property_types();
    $images = ['card1.jpg', 'card2.jpg', 'card3.jpg', 'section1.jpg', 'section5.jpg', 'bigsection.jpg'];

    render_header('Real Estate | Find your next space');
    echo '<header class="hero-section" id="home"><div class="container"><div class="row align-items-center g-5">';
    echo '<div class="col-lg-6 hero-copy reveal is-visible"><span class="eyebrow"><i class="fa-solid fa-location-dot"></i> Kosovo premium property platform</span>';
    echo '<h1>Find a home that feels right before you even visit.</h1>';
    echo '<p class="hero-lead">Browse curated apartments, houses, rentals and commercial spaces with trusted agents, clean details and fast contact flow.</p>';
    echo '<div class="hero-actions"><a class="btn btn-primary-modern" href="#featured">Explore listings</a><a class="btn btn-ghost" href="#contact">Book a tour</a></div>';
    echo '<div class="hero-stats"><div><strong>' . $counts['listings'] . '+</strong><span>Listings</span></div><div><strong>' . $counts['agents'] . '+</strong><span>Agents</span></div><div><strong>' . $counts['locations'] . '+</strong><span>Locations</span></div></div></div>';
    echo '<div class="col-lg-6 reveal is-visible"><div class="hero-card"><img src="' . h(asset_url('images/hero1.jpg')) . '" alt="Modern property interior"></div></div>';
    echo '</div></div></header>';

    echo '<section class="search-panel-section"><div class="container"><form class="search-panel reveal is-visible" method="get" action="' . h(url('buy')) . '#featured">';
    echo '<div class="search-field"><label>Location</label><input type="text" name="location" value="' . h($_GET['location'] ?? $_GET['q'] ?? '') . '" placeholder="Prishtine, Peje, Prizren"></div>';
    echo '<div class="search-field"><label>Property Type</label><select name="type"><option value="">Any type</option>';
    foreach ($types as $type) {
        $selected = (string) ($_GET['type'] ?? '') === (string) $type['id'] ? ' selected' : '';
        echo '<option value="' . h((string) $type['id']) . '"' . $selected . '>' . h($type['name']) . '</option>';
    }
    echo '</select></div><div class="search-field"><label>Budget</label><input type="text" name="budget" value="' . h($_GET['budget'] ?? '') . '" placeholder="Max price"></div>';
    echo '<button class="btn btn-search" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button></form></div></section>';

    echo '<section class="section-space" id="featured"><div class="container"><div class="section-heading reveal is-visible"><span class="eyebrow">Featured properties</span><h2>Fresh listings picked for serious buyers.</h2></div>';
    echo '<div class="property-grid">';
    foreach ($listings as $index => $listing) {
        $image = $images[$index % count($images)];
        echo '<article class="property-card reveal is-visible"><div class="property-image"><img src="' . h(asset_url('images/' . $image)) . '" alt="' . h($listing['address']) . '"><span class="badge-soft">' . h($listing['property_type'] ?? 'Property') . '</span></div>';
        echo '<div class="property-body"><div class="property-price">$' . number_format((float) $listing['price'], 2) . '</div><h3>' . h($listing['address']) . '</h3><p>' . h($listing['description']) . '</p>';
        echo '<div class="property-meta"><span><i class="fa-solid fa-bed"></i> ' . h((string) $listing['bedrooms']) . ' beds</span><span><i class="fa-solid fa-bath"></i> ' . h((string) $listing['bathrooms']) . ' baths</span></div></div>';
        echo '<div class="property-footer"><span><i class="fa-solid fa-user-tie"></i> ' . h($listing['agent_name'] ?? 'Agent') . '</span><a href="#contact">Request tour</a></div></article>';
    }
    echo '</div></div></section>';

    echo '<section class="contact-section-modern" id="contact"><div class="container"><div class="row g-5 align-items-center"><div class="col-lg-5"><span class="eyebrow">Contact us</span><h2>Tell us what kind of property you want.</h2><p>Your request is saved in the contacts table.</p></div>';
    echo '<div class="col-lg-7"><form class="contact-card" method="post" action="' . h(url('contact-store')) . '"><div class="row g-3">';
    echo '<div class="col-md-6"><label>First name</label><input name="first_name" required></div><div class="col-md-6"><label>Last name</label><input name="last_name" required></div>';
    echo '<div class="col-md-6"><label>Email</label><input name="email" type="email" required></div><div class="col-md-6"><label>Preferred location</label><input name="location" required></div>';
    echo '<div class="col-12"><label>Message</label><textarea name="message" rows="5" required></textarea></div></div><button class="btn btn-primary-modern w-100 mt-4" type="submit">Send request</button></form></div></div></div></section>';
    render_footer();
}

function render_login(): void
{
    render_header('Login | Real Estate');
    echo '<section class="section-space" style="padding-top:140px"><div class="container" style="max-width:520px"><div class="section-heading"><span class="eyebrow">Admin</span><h2>Login</h2></div>';
    if (!empty($_GET['error'])) {
        echo '<p class="text-danger">Email ose password gabim.</p>';
    }
    echo '<form class="contact-card" method="post" action="' . h(url('login-store')) . '">';
    echo '<label>Email</label><input name="email" type="email" required><label>Password</label><input name="password" type="password" required>';
    echo '<button class="btn btn-primary-modern w-100 mt-4" type="submit">Login</button></form></div></section>';
    render_footer();
}

function login_store(): void
{
    $stmt = db()->prepare('SELECT * FROM users WHERE email = :email AND status = 1 LIMIT 1');
    $stmt->execute(['email' => $_POST['email'] ?? '']);
    $user = $stmt->fetch();

    if ($user && password_verify((string) ($_POST['password'] ?? ''), $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = trim($user['first_name'] . ' ' . $user['last_name']);
        redirect_to('listings');
    }

    redirect_to('login?error=1');
}

function contact_store(): void
{
    $stmt = db()->prepare('INSERT INTO contacts (first_name, last_name, department, message, email, created_at, updated_at)
        VALUES (:first_name, :last_name, :department, :message, :email, NOW(), NOW())');
    $stmt->execute([
        'first_name' => $_POST['first_name'] ?? '',
        'last_name' => $_POST['last_name'] ?? '',
        'department' => $_POST['location'] ?? '',
        'message' => $_POST['message'] ?? '',
        'email' => $_POST['email'] ?? '',
    ]);

    redirect_to('#contact');
}

function admin_sections(): array
{
    return [
        'dashboard' => ['Dashboard', 'fa-gauge-high'],
        'listings' => ['Listings', 'fa-building'],
        'agents' => ['Agents', 'fa-user-tie'],
        'contacts' => ['Contacts', 'fa-envelope'],
        'locations' => ['Locations', 'fa-location-dot'],
        'property-types' => ['Property types', 'fa-layer-group'],
        'amenities' => ['Amenities', 'fa-star'],
        'users' => ['Users', 'fa-users'],
    ];
}

function table_count(string $table): int
{
    return (int) db()->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
}

function rows(string $sql, array $params = []): array
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

function render_admin_nav(string $active): void
{
    echo '<aside class="admin-side">';
    foreach (admin_sections() as $key => [$label, $icon]) {
        $class = $key === $active ? ' class="active"' : '';
        echo '<a' . $class . ' href="' . h(url($key)) . '"><i class="fa-solid ' . h($icon) . '"></i>' . h($label) . '</a>';
    }
    echo '<a href="' . h(url('')) . '"><i class="fa-solid fa-arrow-left"></i>Website</a>';
    echo '</aside>';
}

function render_admin_start(string $active, string $title, string $subtitle): void
{
    echo '<script>document.body.classList.add("admin-body");</script>';
    echo '<section class="admin-shell"><div class="container">';
    echo '<div class="admin-hero"><div><span class="eyebrow">Real Estate Admin</span><h1>' . h($title) . '</h1><p>' . h($subtitle) . '</p></div>';
    echo '<a class="btn btn-primary-modern" href="' . h(url('logout')) . '">Logout</a></div>';
    echo '<div class="stats-row">';
    foreach ([
        'Listings' => table_count('listings'),
        'Agents' => table_count('agents'),
        'Contacts' => table_count('contacts'),
        'Users' => table_count('users'),
    ] as $label => $count) {
        echo '<div class="stat-card"><strong>' . $count . '</strong><span>' . h($label) . '</span></div>';
    }
    echo '</div><div class="admin-grid">';
    render_admin_nav($active);
    echo '<main>';
}

function render_admin_end(): void
{
    echo '</main></div></div></section></body></html>';
}

function render_panel(string $title, string $subtitle, string $tableHtml): void
{
    echo '<div class="admin-panel"><div class="admin-panel-head"><div><h2>' . h($title) . '</h2><span>' . h($subtitle) . '</span></div></div>';
    echo $tableHtml;
    echo '</div>';
}

function render_delete_button(string $table, int $id, string $section): string
{
    return '<form method="post" action="' . h(url('admin-delete')) . '" onsubmit="return confirm(\'Delete this item?\')" style="display:inline">'
        . '<input type="hidden" name="table" value="' . h($table) . '">'
        . '<input type="hidden" name="id" value="' . $id . '">'
        . '<input type="hidden" name="section" value="' . h($section) . '">'
        . '<button class="btn-danger-soft" type="submit">Delete</button></form>';
}

function render_listing_form(): void
{
    $agents = rows('SELECT id, name FROM agents ORDER BY name');
    $types = property_types();

    echo '<form class="quick-form" method="post" action="' . h(url('admin-save')) . '"><h3>Add listing</h3><input type="hidden" name="table" value="listings">';
    echo '<div class="row g-3"><div class="col-md-6"><label>Address</label><input name="address" required></div>';
    echo '<div class="col-md-3"><label>Price</label><input name="price" type="number" step="0.01" required></div>';
    echo '<div class="col-md-3"><label>Type</label><select name="type_id">';
    foreach ($types as $type) {
        echo '<option value="' . h((string) $type['id']) . '">' . h($type['name']) . '</option>';
    }
    echo '</select></div><div class="col-md-3"><label>Bedrooms</label><input name="bedrooms" type="number" value="2"></div>';
    echo '<div class="col-md-3"><label>Bathrooms</label><input name="bathrooms" type="number" value="1"></div>';
    echo '<div class="col-md-6"><label>Agent</label><select name="agent_id">';
    foreach ($agents as $agent) {
        echo '<option value="' . h((string) $agent['id']) . '">' . h($agent['name']) . '</option>';
    }
    echo '</select></div><div class="col-12"><label>Description</label><textarea name="description" rows="3"></textarea></div>';
    echo '<div class="col-12"><button class="btn-admin" type="submit">Save listing</button></div></div></form>';
}

function render_simple_form(string $table, array $fields, string $title): void
{
    echo '<form class="quick-form" method="post" action="' . h(url('admin-save')) . '"><h3>' . h($title) . '</h3><input type="hidden" name="table" value="' . h($table) . '">';
    echo '<div class="row g-3">';
    foreach ($fields as $name => $label) {
        echo '<div class="col-md-6"><label>' . h($label) . '</label><input name="' . h($name) . '" required></div>';
    }
    echo '<div class="col-12"><button class="btn-admin" type="submit">Save</button></div></div></form>';
}

function render_admin_section(string $section = 'dashboard'): void
{
    require_login();
    render_header('Admin | Real Estate');

    $titles = admin_sections();
    $title = $titles[$section][0] ?? 'Dashboard';
    render_admin_start($section, $title, 'Manage the live website data directly from MySQL.');

    if ($section === 'dashboard') {
        $latest = rows('SELECT first_name, last_name, email, department, created_at FROM contacts ORDER BY id DESC LIMIT 5');
        $html = '<div class="table-responsive"><table class="table admin-table"><thead><tr><th>Name</th><th>Email</th><th>Location</th><th>Date</th></tr></thead><tbody>';
        foreach ($latest as $row) {
            $html .= '<tr><td>' . h($row['first_name'] . ' ' . $row['last_name']) . '</td><td>' . h($row['email']) . '</td><td>' . h($row['department']) . '</td><td>' . h((string) $row['created_at']) . '</td></tr>';
        }
        $html .= '</tbody></table></div>';
        render_panel('Latest contacts', 'Recent requests from the public contact form.', $html);
    } elseif ($section === 'listings') {
        render_listing_form();
        $data = listings();
        $html = '<div class="table-responsive"><table class="table admin-table"><thead><tr><th>ID</th><th>Address</th><th>Type</th><th>Agent</th><th>Price</th><th>Beds</th><th>Baths</th><th></th></tr></thead><tbody>';
        foreach ($data as $row) {
            $html .= '<tr><td>' . h((string) $row['id']) . '</td><td>' . h($row['address']) . '</td><td>' . h($row['property_type'] ?? '') . '</td><td>' . h($row['agent_name'] ?? '') . '</td><td>$' . number_format((float) $row['price'], 2) . '</td><td>' . h((string) $row['bedrooms']) . '</td><td>' . h((string) $row['bathrooms']) . '</td><td>' . render_delete_button('listings', (int) $row['id'], 'listings') . '</td></tr>';
        }
        $html .= '</tbody></table></div>';
        render_panel('Listings', 'Properties shown on the website.', $html);
    } elseif ($section === 'agents') {
        render_simple_form('agents', ['name' => 'Name', 'email' => 'Email', 'phone' => 'Phone', 'agency' => 'Agency'], 'Add agent');
        $data = rows('SELECT * FROM agents ORDER BY id DESC');
        $html = '<div class="table-responsive"><table class="table admin-table"><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Agency</th><th></th></tr></thead><tbody>';
        foreach ($data as $row) {
            $html .= '<tr><td>' . h((string) $row['id']) . '</td><td>' . h($row['name']) . '</td><td>' . h($row['email']) . '</td><td>' . h($row['phone']) . '</td><td>' . h($row['agency']) . '</td><td>' . render_delete_button('agents', (int) $row['id'], 'agents') . '</td></tr>';
        }
        $html .= '</tbody></table></div>';
        render_panel('Agents', 'People connected to listings.', $html);
    } elseif ($section === 'contacts') {
        $data = rows('SELECT * FROM contacts ORDER BY id DESC');
        $html = '<div class="table-responsive"><table class="table admin-table"><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Location</th><th>Message</th><th></th></tr></thead><tbody>';
        foreach ($data as $row) {
            $html .= '<tr><td>' . h((string) $row['id']) . '</td><td>' . h($row['first_name'] . ' ' . $row['last_name']) . '</td><td>' . h($row['email']) . '</td><td>' . h($row['department']) . '</td><td>' . h($row['message']) . '</td><td>' . render_delete_button('contacts', (int) $row['id'], 'contacts') . '</td></tr>';
        }
        $html .= '</tbody></table></div>';
        render_panel('Contacts', 'Requests submitted from the website.', $html);
    } elseif ($section === 'locations') {
        render_simple_form('locations', ['name' => 'Name', 'type' => 'Type'], 'Add location');
        $data = rows('SELECT * FROM locations ORDER BY id DESC');
        $html = '<div class="table-responsive"><table class="table admin-table"><thead><tr><th>ID</th><th>Name</th><th>Type</th><th></th></tr></thead><tbody>';
        foreach ($data as $row) {
            $html .= '<tr><td>' . h((string) $row['id']) . '</td><td>' . h($row['name']) . '</td><td>' . h($row['type']) . '</td><td>' . render_delete_button('locations', (int) $row['id'], 'locations') . '</td></tr>';
        }
        $html .= '</tbody></table></div>';
        render_panel('Locations', 'Cities and neighborhoods.', $html);
    } elseif ($section === 'property-types') {
        render_simple_form('property_types', ['name' => 'Name'], 'Add property type');
        $data = rows('SELECT * FROM property_types ORDER BY id DESC');
        $html = '<div class="table-responsive"><table class="table admin-table"><thead><tr><th>ID</th><th>Name</th><th></th></tr></thead><tbody>';
        foreach ($data as $row) {
            $html .= '<tr><td>' . h((string) $row['id']) . '</td><td>' . h($row['name']) . '</td><td>' . render_delete_button('property_types', (int) $row['id'], 'property-types') . '</td></tr>';
        }
        $html .= '</tbody></table></div>';
        render_panel('Property types', 'Apartment, house, villa and more.', $html);
    } elseif ($section === 'amenities') {
        render_simple_form('amenities', ['name' => 'Name'], 'Add amenity');
        $data = rows('SELECT * FROM amenities ORDER BY id DESC');
        $html = '<div class="table-responsive"><table class="table admin-table"><thead><tr><th>ID</th><th>Name</th><th></th></tr></thead><tbody>';
        foreach ($data as $row) {
            $html .= '<tr><td>' . h((string) $row['id']) . '</td><td>' . h($row['name']) . '</td><td>' . render_delete_button('amenities', (int) $row['id'], 'amenities') . '</td></tr>';
        }
        $html .= '</tbody></table></div>';
        render_panel('Amenities', 'Feature labels used for properties.', $html);
    } elseif ($section === 'users') {
        render_simple_form('users', ['first_name' => 'First name', 'last_name' => 'Last name', 'email' => 'Email', 'password' => 'Password'], 'Add user');
        $data = rows('SELECT id, first_name, last_name, email, status, created_at FROM users ORDER BY id DESC');
        $html = '<div class="table-responsive"><table class="table admin-table"><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Status</th><th></th></tr></thead><tbody>';
        foreach ($data as $row) {
            $html .= '<tr><td>' . h((string) $row['id']) . '</td><td>' . h($row['first_name'] . ' ' . $row['last_name']) . '</td><td>' . h($row['email']) . '</td><td>' . ((int) $row['status'] === 1 ? 'Active' : 'Inactive') . '</td><td>' . render_delete_button('users', (int) $row['id'], 'users') . '</td></tr>';
        }
        $html .= '</tbody></table></div>';
        render_panel('Users', 'Admin login accounts.', $html);
    }

    render_admin_end();
}

function admin_save(): void
{
    require_login();
    $table = $_POST['table'] ?? '';

    if ($table === 'listings') {
        $stmt = db()->prepare('INSERT INTO listings (address, price, bedrooms, bathrooms, description, agent_id, type_id, created_at, updated_at) VALUES (:address, :price, :bedrooms, :bathrooms, :description, :agent_id, :type_id, NOW(), NOW())');
        $stmt->execute([
            'address' => $_POST['address'] ?? '',
            'price' => (float) ($_POST['price'] ?? 0),
            'bedrooms' => (int) ($_POST['bedrooms'] ?? 0),
            'bathrooms' => (int) ($_POST['bathrooms'] ?? 0),
            'description' => $_POST['description'] ?? '',
            'agent_id' => (int) ($_POST['agent_id'] ?? 1),
            'type_id' => (int) ($_POST['type_id'] ?? 1),
        ]);
        redirect_to('dashboard');
    }

    if ($table === 'agents') {
        $stmt = db()->prepare('INSERT INTO agents (name, email, phone, agency, bio, created_at, updated_at) VALUES (:name, :email, :phone, :agency, "", NOW(), NOW())');
        $stmt->execute(['name' => $_POST['name'] ?? '', 'email' => $_POST['email'] ?? '', 'phone' => $_POST['phone'] ?? '', 'agency' => $_POST['agency'] ?? '']);
        redirect_to('agents');
    }

    if ($table === 'locations') {
        $stmt = db()->prepare('INSERT INTO locations (name, type, created_at, updated_at) VALUES (:name, :type, NOW(), NOW())');
        $stmt->execute(['name' => $_POST['name'] ?? '', 'type' => $_POST['type'] ?? 'City']);
        redirect_to('locations');
    }

    if ($table === 'property_types' || $table === 'amenities') {
        $stmt = db()->prepare("INSERT INTO `$table` (name, created_at, updated_at) VALUES (:name, NOW(), NOW())");
        $stmt->execute(['name' => $_POST['name'] ?? '']);
        redirect_to($table === 'property_types' ? 'property-types' : 'amenities');
    }

    if ($table === 'users') {
        $stmt = db()->prepare('INSERT INTO users (first_name, last_name, email, password, status, created_at, updated_at) VALUES (:first_name, :last_name, :email, :password, 1, NOW(), NOW())');
        $stmt->execute([
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => password_hash((string) ($_POST['password'] ?? 'admin123'), PASSWORD_DEFAULT),
        ]);
        redirect_to('users');
    }

    redirect_to('listings');
}

function admin_delete(): void
{
    require_login();
    $allowed = ['listings', 'agents', 'contacts', 'locations', 'property_types', 'amenities', 'users'];
    $table = $_POST['table'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);
    $section = $_POST['section'] ?? 'listings';

    if (in_array($table, $allowed, true) && $id > 0) {
        $stmt = db()->prepare("DELETE FROM `$table` WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    redirect_to($section);
}

try {
    $route = route_path();

    if ($route === '' || $route === 'buy') {
        render_home();
    } elseif ($route === 'login') {
        render_login();
    } elseif ($route === 'login-store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        login_store();
    } elseif ($route === 'logout') {
        session_destroy();
        redirect_to('');
    } elseif ($route === 'contact-store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        contact_store();
    } elseif ($route === 'admin-save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        admin_save();
    } elseif ($route === 'admin-delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        admin_delete();
    } elseif (in_array($route, ['dashboard', 'listings', 'agents', 'contacts', 'locations', 'property-types', 'amenities', 'users'], true)) {
        render_admin_section($route);
    } else {
        http_response_code(404);
        render_header('404 | Real Estate');
        echo '<section class="section-space" style="padding-top:140px"><div class="container"><h1>404</h1><p>Page not found.</p></div></section>';
        render_footer();
    }
} catch (Throwable $exception) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Standalone app error\n";
    echo get_class($exception) . ': ' . $exception->getMessage() . "\n";
    echo $exception->getFile() . ':' . $exception->getLine() . "\n";
}
