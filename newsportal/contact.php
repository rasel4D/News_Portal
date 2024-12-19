<?php
require_once 'config/database.php';
require_once 'includes/header.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);
    
    // Insert into database
    $query = "INSERT INTO contact_messages (name, email, message, status) 
              VALUES ('$name', '$email', '$message', 'new')";
              
    if ($conn->query($query)) {
        $success = "Your message has been sent successfully!";
    } else {
        $error = "Error sending message: " . $conn->error;
    }
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-indigo-500 rounded-lg shadow-md p-6">
            <h1 class="text-3xl font-bold mb-6 text-white">Contact Us</h1>
            
            <?php if (isset($success)) : ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error)) : ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Contact Information -->
            <div class="bg-white rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Get in Touch</h2>
                <p class="text-gray-600 mb-4">
                    Email: <a href="mailto:ques@newsportal.com" class="text-blue-500 hover:text-blue-700">
                        ques@newsportal.com
                    </a>
                </p>
            </div>

            <form method="POST" class="bg-white rounded-lg p-6">
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2" for="name">
                        Name
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
                           id="name" 
                           name="name" 
                           type="text" 
                           required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
                           id="email" 
                           name="email" 
                           type="email" 
                           required>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-bold mb-2" for="message">
                        Message
                    </label>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
                              id="message" 
                              name="message" 
                              rows="6" 
                              required></textarea>
                </div>
                
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 