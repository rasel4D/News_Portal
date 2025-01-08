<?php
require_once 'config/database.php';
require_once 'includes/header.php';

// Fetch about page content from database
$query = "SELECT * FROM pages WHERE slug = 'about' AND status = 'published' LIMIT 1";
$result = $conn->query($query);
$about = $result->fetch_assoc();
?>

<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">About Us</h1>
            <div class="w-20 h-1 bg-blue-500 mx-auto"></div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-8">
                <?php if ($about): ?>
                    <div class="prose max-w-none">
                        <?php echo $about['content']; ?>
                    </div>
                <?php else: ?>
                    <!-- Fallback Content -->
                    <div class="space-y-6">
                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Our Mission</h2>
                            <p class="text-gray-600 leading-relaxed">
                                We are dedicated to delivering accurate, timely, and engaging news coverage to our readers. 
                                Our team of experienced journalists works around the clock to bring you the most important 
                                stories from around the world.
                            </p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Our Values</h2>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="bg-gray-50 p-6 rounded-lg">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Accuracy</h3>
                                    <p class="text-gray-600">We verify all information before publication to ensure accuracy.</p>
                                </div>
                                <div class="bg-gray-50 p-6 rounded-lg">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Integrity</h3>
                                    <p class="text-gray-600">We maintain high ethical standards in our reporting.</p>
                                </div>
                                <div class="bg-gray-50 p-6 rounded-lg">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Independence</h3>
                                    <p class="text-gray-600">We remain independent and unbiased in our coverage.</p>
                                </div>
                                <div class="bg-gray-50 p-6 rounded-lg">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Innovation</h3>
                                    <p class="text-gray-600">We embrace new technologies to deliver better news coverage.</p>
                                </div>
                            </div>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Our Team</h2>
                            <p class="text-gray-600 leading-relaxed">
                                Our diverse team of journalists, editors, and content creators brings together decades of 
                                experience in news reporting. We are committed to providing you with comprehensive coverage 
                                across various topics including politics, business, technology, sports, and entertainment.
                            </p>
                        </section>
                    </div>
                <?php endif; ?>

                <!-- Contact Section -->
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Get in Touch</h2>
                    <p class="text-gray-600 mb-6">
                        Have questions or feedback? We'd love to hear from you. Contact us at 
                        <a href="mailto:ques@newsportal.com" class="text-blue-500 hover:text-blue-600">
                          2125051072@uits.edu.bd
                        </a>
                    </p>
                    <a href="contact.php" 
                       class="inline-block bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>