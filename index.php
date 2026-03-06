<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();

// Hero section
$hero = $db->query("SELECT * FROM hero_sections WHERE is_active = 1 ORDER BY order_position ASC LIMIT 1")->fetch();

// Gallery images
$galleryImages = $db->query("SELECT * FROM gallery_images WHERE is_active = 1 ORDER BY order_position ASC LIMIT 4")->fetchAll();

// Chambres
$rooms = $db->query("SELECT * FROM rooms WHERE is_active = 1 ORDER BY order_position ASC LIMIT 4")->fetchAll();

// Services
$services = $db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY order_position ASC LIMIT 3")->fetchAll();

// Témoignage
$testimonial = $db->query("SELECT * FROM testimonials WHERE is_active = 1 ORDER BY order_position ASC LIMIT 1")->fetch();

// Équipements
$amenities = $db->query("SELECT * FROM amenities WHERE is_active = 1 ORDER BY order_position ASC LIMIT 6")->fetchAll();

// Contact info
$contactInfo = getContactInfo();

$pageTitle = "Accueil";
include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-background">
        <?php if ($hero && $hero['background_image']): ?>
        <img src="<?= imageUrl($hero['background_image']) ?>" alt="Island Resort" class="hero-bg-image">
        <?php else: ?>
        <img src="<?= SITE_URL ?>/assets/images/sara-dubler-Koei_7yYtIo-unsplash.jpg" alt="Island Resort" class="hero-bg-image">
        <?php endif; ?>
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="container text-center text-white">
            <?php if ($hero): ?>
            <h1 class="hero-title"><?= e($hero['title']) ?></h1>
            <p class="hero-subtitle"><?= e($hero['subtitle']) ?></p>
            <?php else: ?>
            <h1 class="hero-title">Boutique Private<br>Island Resort</h1>
            <p class="hero-subtitle">The seaside haven of warmth, tranquility and restoration</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Booking Form -->
    <div class="booking-form-container">
        <div class="container">
            <form class="booking-form" action="<?= SITE_URL ?>/rooms.php" method="get">
                <div class="row g-0 align-items-center">
                    <div class="col-lg col-md-6 booking-field">
                        <label>Check In</label>
                        <input type="text" id="id_check_in" name="check_in" class="form-control datepicker" placeholder="Select date">
                    </div>
                    <div class="col-lg col-md-6 booking-field">
                        <label>Check Out</label>
                        <input type="text" id="id_check_out" name="check_out" class="form-control datepicker" placeholder="Select date">
                    </div>
                    <div class="col-lg col-md-6 booking-field">
                        <label>Rooms</label>
                        <select name="rooms" class="form-select">
                            <option value="1">1 Room</option>
                            <option value="2">2 Rooms</option>
                            <option value="3">3 Rooms</option>
                            <option value="4">4 Rooms</option>
                        </select>
                    </div>
                    <div class="col-lg col-md-6 booking-field">
                        <label>Guests</label>
                        <select name="guests" class="form-select">
                            <option value="1">1 Adult, 0 Children</option>
                            <option value="2">2 Adults, 0 Children</option>
                            <option value="3">2 Adults, 1 Child</option>
                            <option value="4">2 Adults, 2 Children</option>
                        </select>
                    </div>
                    <div class="col-lg-auto col-12 booking-submit">
                        <button type="submit" class="btn btn-check-availability">Check Availability</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Welcome Section -->
<section class="welcome-section py-5">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <p class="section-subtitle">WELCOME TO COZYSTAY RESORT</p>
                <h2 class="section-title">In the Heart of the South Pacific,<br>Outstanding Views</h2>
                <p class="section-text mt-4">
                    Nestled in the heart of the Pacific Islands resort, on the edge of a tranquil and beautiful Garden Island,
                    CozyStay is a haven of warmth, tranquility and rejuvenation. Bathed in brilliant sunshine and clear skies, it
                    offers stunning views of palm-lined beaches and gorgeous coral reefs.
                </p>
            </div>
        </div>

        <!-- Gallery Carousel -->
        <div class="gallery-carousel-container">
            <div class="gallery-carousel" id="galleryCarousel">
                <div class="gallery-track">
                    <?php if (!empty($galleryImages)): ?>
                        <?php foreach ($galleryImages as $image): ?>
                        <div class="gallery-slide">
                            <img src="<?= imageUrl($image['image']) ?>" alt="<?= e($image['title']) ?>" class="gallery-image">
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="gallery-slide">
                            <img src="<?= SITE_URL ?>/assets/images/aman-mzEyEp3syMI-unsplash.jpg" alt="Beach sunset" class="gallery-image">
                        </div>
                        <div class="gallery-slide">
                            <img src="<?= SITE_URL ?>/assets/images/sasha-kaunas-xEaAoizNFV8-unsplash.jpg" alt="Palm trees" class="gallery-image">
                        </div>
                        <div class="gallery-slide">
                            <img src="<?= SITE_URL ?>/assets/images/nathan-cima-4aqH2utAPAs-unsplash.jpg" alt="Boardwalk" class="gallery-image">
                        </div>
                        <div class="gallery-slide">
                            <img src="<?= SITE_URL ?>/assets/images/paulo-evangelista-iJelGtuc52g-unsplash.jpg" alt="Ocean view" class="gallery-image">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <button class="gallery-nav gallery-prev" onclick="moveGallery(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="gallery-nav gallery-next" onclick="moveGallery(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

<!-- Accommodations Section -->
<section class="accommodations-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <p class="section-subtitle">Inspired by our history, surrounded by nature and designed to</p>
            <h2 class="section-title">offer a different experience</h2>
            <h3 class="accommodations-subtitle mt-4">The Accommodations</h3>
        </div>

        <div class="row g-4">
            <?php if (!empty($rooms)): ?>
                <?php foreach ($rooms as $room): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="room-card">
                        <div class="room-image-wrapper">
                            <a href="<?= SITE_URL ?>/room.php?slug=<?= e($room['slug']) ?>">
                                <img src="<?= imageUrl($room['image']) ?>" alt="<?= e($room['name']) ?>" class="room-image">
                            </a>
                            <div class="room-price">
                                <span class="price-from">from</span>
                                <span class="price-amount">$<?= number_format($room['price_per_night'], 0) ?></span>
                                <span class="price-period">/ night</span>
                            </div>
                        </div>
                        <div class="room-content">
                            <h4 class="room-name">
                                <a href="<?= SITE_URL ?>/room.php?slug=<?= e($room['slug']) ?>"><?= e($room['name']) ?></a>
                            </h4>
                            <div class="room-features">
                                <span><i class="fas fa-expand-arrows-alt"></i> <?= (int)$room['size'] ?> m²</span>
                                <span><i class="fas fa-user"></i> <?= (int)$room['guests'] ?> Guests</span>
                                <span><i class="fas fa-bed"></i> <?= (int)$room['beds'] ?> <?= e($room['bed_type']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
            <div class="col-12 text-center">
                <p class="text-muted">Aucune chambre disponible pour le moment.</p>
            </div>
            <?php endif; ?>
        </div>

        <div class="text-center mt-5">
            <a href="<?= SITE_URL ?>/rooms.php" class="btn btn-outline-dark btn-lg">View All Rooms</a>
        </div>
    </div>
</section>

<!-- Location Section -->
<section class="location-section">
    <div class="row g-0">
        <div class="col-lg-6 location-image">
            <img src="<?= SITE_URL ?>/assets/images/cheesum-hoo-wz01NYpXBuY-unsplash.jpg" alt="Resort Location" class="img-fluid">
        </div>
        <div class="col-lg-6 location-content d-flex align-items-center">
            <div class="location-text p-5">
                <p class="section-subtitle text-white-50">One of the World's Most Desirable Locations</p>
                <h2 class="section-title text-white">A superior, 5-star resort</h2>
                <p class="text-white-50 mt-4">
                    Embodying the very best of Fiji Islands luxury, tranquility & sophistication.
                    Our resort offers an unparalleled experience with pristine beaches, crystal-clear waters,
                    and world-class amenities designed for the discerning traveler.
                </p>
                <a href="<?= SITE_URL ?>/about.php" class="btn btn-outline-light mt-4">Discover More</a>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <p class="section-subtitle">Exclusive Experiences</p>
            <h2 class="section-title">Resort Services</h2>
        </div>

        <div class="row g-4">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $service): ?>
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-image-wrapper">
                            <a href="<?= SITE_URL ?>/service.php?slug=<?= e($service['slug']) ?>">
                                <img src="<?= imageUrl($service['image']) ?>" alt="<?= e($service['title']) ?>" class="service-image">
                            </a>
                        </div>
                        <div class="service-content text-center">
                            <h4 class="service-title"><?= e($service['title']) ?></h4>
                            <p class="service-description"><?= truncateWords($service['description'], 20) ?></p>
                            <a href="<?= SITE_URL ?>/service.php?slug=<?= e($service['slug']) ?>" class="btn btn-link">Learn More <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-image-wrapper">
                            <img src="<?= SITE_URL ?>/assets/images/huy-nguyen-I__khoHttww-unsplash.jpg" alt="Spa & Wellness" class="service-image">
                        </div>
                        <div class="service-content text-center">
                            <h4 class="service-title">Spa & Wellness</h4>
                            <p class="service-description">Rejuvenate your body and mind with our world-class spa treatments.</p>
                            <a href="#" class="btn btn-link">Learn More <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-image-wrapper">
                            <img src="<?= SITE_URL ?>/assets/images/job-savelsberg-e9hRCg9wwdA-unsplash.jpg" alt="Island Activities" class="service-image">
                        </div>
                        <div class="service-content text-center">
                            <h4 class="service-title">Island Activities</h4>
                            <p class="service-description">Explore the island with snorkeling, diving, and water sports.</p>
                            <a href="#" class="btn btn-link">Learn More <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-image-wrapper">
                            <img src="<?= SITE_URL ?>/assets/images/julio-samudra-yqPQsUUswQ8-unsplash.jpg" alt="Gastronomic Dining" class="service-image">
                        </div>
                        <div class="service-content text-center">
                            <h4 class="service-title">Gastronomic Dining</h4>
                            <p class="service-description">Experience culinary excellence with our gourmet restaurants.</p>
                            <a href="#" class="btn btn-link">Learn More <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Testimonial Section -->
<section class="testimonial-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="testimonial-rating mb-4">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <?php if ($testimonial): ?>
                <blockquote class="testimonial-quote">
                    <p>"<?= e($testimonial['content']) ?>"</p>
                </blockquote>
                <div class="testimonial-author">
                    <span class="author-name"><?= e($testimonial['author']) ?></span>
                    <span class="author-source">– <?= e($testimonial['source']) ?></span>
                </div>
                <?php else: ?>
                <blockquote class="testimonial-quote">
                    <p>"An absolutely magical experience. The resort exceeded all our expectations with its stunning views, impeccable service, and attention to every detail. We can't wait to return!"</p>
                </blockquote>
                <div class="testimonial-author">
                    <span class="author-name">Sarah & James</span>
                    <span class="author-source">– TripAdvisor</span>
                </div>
                <?php endif; ?>
                <div class="testimonial-source mt-4">
                    <span class="tripadvisor-text"><i class="fab fa-tripadvisor me-2"></i>TripAdvisor</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Amenities Section -->
<section class="amenities-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <p class="section-subtitle">Inspired by our history, surrounded by nature to offer a different experience</p>
            <h2 class="section-title">All the Essentials for a Cozy<br>and Comfortable Stay</h2>
        </div>

        <div class="row g-4 justify-content-center">
            <?php if (!empty($amenities)): ?>
                <?php foreach ($amenities as $amenity): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="amenity-card text-center">
                        <div class="amenity-icon"><i class="<?= e($amenity['icon']) ?>"></i></div>
                        <h5 class="amenity-name"><?= e($amenity['name']) ?></h5>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="amenity-card text-center">
                        <div class="amenity-icon"><i class="fas fa-plane-arrival"></i></div>
                        <h5 class="amenity-name">Airport Pick-up</h5>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="amenity-card text-center">
                        <div class="amenity-icon"><i class="fas fa-broom"></i></div>
                        <h5 class="amenity-name">Housekeeping</h5>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="amenity-card text-center">
                        <div class="amenity-icon"><i class="fas fa-wifi"></i></div>
                        <h5 class="amenity-name">Free WiFi</h5>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="amenity-card text-center">
                        <div class="amenity-icon"><i class="fas fa-tshirt"></i></div>
                        <h5 class="amenity-name">Laundry</h5>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="amenity-card text-center">
                        <div class="amenity-icon"><i class="fas fa-coffee"></i></div>
                        <h5 class="amenity-name">Breakfast in Bed</h5>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="amenity-card text-center">
                        <div class="amenity-icon"><i class="fas fa-swimming-pool"></i></div>
                        <h5 class="amenity-name">Swimming Pool</h5>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
$extraJs = '<script>
    flatpickr(".datepicker", {
        dateFormat: "Y-m-d",
        minDate: "today",
        disableMobile: true
    });

    let galleryPosition = 0;
    const track = document.querySelector(".gallery-track");
    const slides = document.querySelectorAll(".gallery-slide");
    const slideWidth = slides[0] ? slides[0].offsetWidth + 20 : 400;

    function moveGallery(direction) {
        const maxPosition = -(slides.length - 3) * slideWidth;
        galleryPosition += direction * slideWidth;
        if (galleryPosition > 0) galleryPosition = 0;
        if (galleryPosition < maxPosition) galleryPosition = maxPosition;
        if (track) track.style.transform = "translateX(" + galleryPosition + "px)";
    }
</script>';
include __DIR__ . '/includes/footer.php';
?>
