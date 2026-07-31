SET NAMES utf8mb4;
INSERT INTO `users`
  (`id`, `first_name`, `last_name`, `country`, `city`, `address`, `phone`, `status`, `email`, `password`, `created_at`, `updated_at`)
VALUES
  (1, 'Admin', 'User', 'Kosovo', 'Prishtine', 'Real Estate HQ', '+38344111222', 1, 'admin@realestate.test', '$2y$10$EBCEBnmN.jVAcnpD5QxiP.UNYsz9ON0lM4Mm3xtZI5viHVFMHYL6C', NOW(), NOW()),
  (2, 'Demo', 'Agent', 'Kosovo', 'Prishtine', 'Mother Teresa Boulevard', '+38344111333', 1, 'demo@realestate.test', '$2y$10$p20DX4IX0yMRoWkVzJ/kWuICrjVFStK7vOz3WHhn2./rbKOpgbgaG', NOW(), NOW());
INSERT INTO `agents`
  (`id`, `name`, `email`, `phone`, `agency`, `bio`, `created_at`, `updated_at`)
VALUES
  (1, 'Arben Krasniqi', 'arben@realestate.test', '+38344100101', 'Prishtina Prime Realty', 'Residential specialist focused on apartments and family homes in Prishtine.', NOW(), NOW()),
  (2, 'Elira Berisha', 'elira@realestate.test', '+38344100102', 'Urban Nest', 'Rental and relocation consultant with fast viewing coordination.', NOW(), NOW()),
  (3, 'Jon Gashi', 'jon@realestate.test', '+38344100103', 'Commercial Point', 'Commercial property advisor for offices and retail spaces.', NOW(), NOW()),
  (4, 'Nora Hoxha', 'nora@realestate.test', '+38344100104', 'Invest Kosovo Homes', 'Investment consultant for premium and long-term value properties.', NOW(), NOW());
INSERT INTO `property_types`
  (`id`, `name`, `created_at`, `updated_at`)
VALUES
  (1, 'Apartment', NOW(), NOW()),
  (2, 'House', NOW(), NOW()),
  (3, 'Villa', NOW(), NOW()),
  (4, 'Commercial', NOW(), NOW()),
  (5, 'Studio', NOW(), NOW()),
  (6, 'Land', NOW(), NOW());
INSERT INTO `locations`
  (`id`, `name`, `type`, `created_at`, `updated_at`)
VALUES
  (1, 'Prishtine', 'City', NOW(), NOW()),
  (2, 'Peje', 'City', NOW(), NOW()),
  (3, 'Prizren', 'City', NOW(), NOW()),
  (4, 'Germia', 'Neighborhood', NOW(), NOW()),
  (5, 'Arberi', 'Neighborhood', NOW(), NOW()),
  (6, 'Bregu i Diellit', 'Neighborhood', NOW(), NOW());
INSERT INTO `amenities`
  (`id`, `name`, `created_at`, `updated_at`)
VALUES
  (1, 'Parking', NOW(), NOW()),
  (2, 'Balcony', NOW(), NOW()),
  (3, 'Elevator', NOW(), NOW()),
  (4, 'Central Heating', NOW(), NOW()),
  (5, 'Garden', NOW(), NOW()),
  (6, 'Security', NOW(), NOW()),
  (7, 'Furnished', NOW(), NOW()),
  (8, 'City View', NOW(), NOW());
INSERT INTO `listings`
  (`id`, `address`, `price`, `bedrooms`, `bathrooms`, `description`, `agent_id`, `type_id`, `created_at`, `updated_at`)
VALUES
  (1, 'Rr. Nena Tereze 12, Prishtine', 85000.00, 2, 1, 'Apartament modern ne qender te qytetit me qasje te shpejte ne kafene, shkolla dhe transport publik.', 1, 1, NOW(), NOW()),
  (2, 'Lagjja Arberia, Prishtine', 145000.00, 3, 2, 'Banese premium me pamje nga qyteti, parking privat dhe organizim shume funksional.', 1, 1, NOW(), NOW()),
  (3, 'Germia Hills, Prishtine', 320000.00, 5, 3, 'Shtepi familjare me kopsht, privatësi dhe hapesira te medha per jetese.', 4, 2, NOW(), NOW()),
  (4, 'Qendra e Prizrenit', 98000.00, 2, 1, 'Apartament i rinovuar afer qendres historike, ideal per banim ose investim.', 2, 1, NOW(), NOW()),
  (5, 'Peje, Rruga Mbreteresha Teute', 76000.00, 2, 1, 'Banese komode me ndriçim natyral dhe qasje te lehte ne sherbimet kryesore.', 2, 5, NOW(), NOW()),
  (6, 'Bregu i Diellit, Prishtine', 220000.00, 4, 3, 'Duplex modern me dy terasa, parking dhe orientim te shkelqyer.', 4, 3, NOW(), NOW()),
  (7, 'Rr. Luan Haradinaj, Prishtine', 180000.00, 0, 2, 'Hapesire komerciale e pershtatshme per zyre, showroom ose studio profesionale.', 3, 4, NOW(), NOW()),
  (8, 'Veternik, Prishtine', 120000.00, 3, 2, 'Apartament i ri me planimetri te hapur, ashensor dhe parking te perfshire.', 1, 1, NOW(), NOW());
INSERT INTO `amenity_listing`
  (`amenity_id`, `listing_id`, `created_at`, `updated_at`)
VALUES
  (1, 1, NOW(), NOW()),
  (2, 1, NOW(), NOW()),
  (1, 2, NOW(), NOW()),
  (3, 2, NOW(), NOW()),
  (8, 2, NOW(), NOW()),
  (5, 3, NOW(), NOW()),
  (6, 3, NOW(), NOW()),
  (2, 4, NOW(), NOW()),
  (7, 5, NOW(), NOW()),
  (1, 6, NOW(), NOW()),
  (2, 6, NOW(), NOW()),
  (6, 7, NOW(), NOW()),
  (1, 8, NOW(), NOW()),
  (3, 8, NOW(), NOW());
INSERT INTO `contacts`
  (`first_name`, `last_name`, `department`, `message`, `email`, `created_at`, `updated_at`)
VALUES
  ('Arian', 'Demo', 'Prishtine', 'Jam i interesuar per nje apartament me dy dhoma ne Prishtine.', 'arian.demo@example.com', NOW(), NOW());

