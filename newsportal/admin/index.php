<?php
session_start();
require_once '../config/database.php';
require_once 'includes/header.php';

// Check if user is logged in and is admin/subadmin
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'subadmin'])) {
    header('Location: login.php');
    exit();
}

// Handle post submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $category_id = (int)$_POST['category_id'];
    $subcategory_id = isset($_POST['subcategory_id']) ? (int)$_POST['subcategory_id'] : null;
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Create uploads directory if it doesn't exist
        $target_dir = "../uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Only allow certain image file formats
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_extension, $allowed_types)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Store the relative path in database
                $image = 'uploads/' . $new_filename;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    // Create slug from title
    function createSlug($string) {
        // For non-Latin scripts (like Bengali), use timestamp
        if (preg_match('/[\x{0980}-\x{09FF}]/u', $string)) {
            return 'post-' . time() . '-' . rand(1000, 9999);
        }
        
        // For Latin characters
        $string = str_replace(' ', '-', $string); // Replace spaces with dashes
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Remove special characters
        $string = strtolower(trim($string, '-')); // Convert to lowercase and trim dashes
        return $string;
    }

    // Create unique slug
    $base_slug = createSlug($title);
    $slug = $base_slug;
    $counter = 1;

    // Check if slug exists and append number if it does
    while (true) {
        $check_query = "SELECT id FROM posts WHERE slug = '$slug' LIMIT 1";
        $check_result = $conn->query($check_query);
        if ($check_result->num_rows == 0) {
            break;
        }
        $slug = $base_slug . '-' . $counter;
        $counter++;
    }

    // Insert post (only if no error occurred)
    if (!isset($error)) {
        $query = "INSERT INTO posts (title, slug, content, category_id, subcategory_id, author_id, image) 
                  VALUES ('$title', '$slug', '$content', $category_id, " . 
                  ($subcategory_id ? $subcategory_id : "NULL") . ", {$_SESSION['user_id']}, '$image')";
        
        if ($conn->query($query)) {
            $success = "Post created successfully!";
        } else {
            $error = "Error creating post: " . $conn->error;
        }
    }
}

// Fetch categories for dropdown
$categories = $conn->query("SELECT id, name FROM categories WHERE is_deleted = 0");
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <p class="mb-4">Role: <?php echo htmlspecialchars($_SESSION['role']); ?></p>
        
        <!-- Quick Upload Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Quick Post Upload</h2>
            
            <?php if (isset($success)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
                    <input type="text" id="title" name="title" required
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label for="category_id" class="block text-gray-700 font-medium mb-2">Category</label>
                    <select id="category_id" name="category_id" required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">Select Category</option>
                        <?php while ($category = $categories->fetch_assoc()): ?>
                            <option value="<?php echo $category['id']; ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div>
                    <label for="subcategory_id" class="block text-gray-700 font-medium mb-2">Subcategory</label>
                    <select id="subcategory_id" name="subcategory_id"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">Select Subcategory</option>
                    </select>
                </div>

                <div>
                    <label for="content" class="block text-gray-700 font-medium mb-2">Content</label>
                    <textarea id="content" name="content" rows="6" required
                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"></textarea>
                </div>

                <div>
                    <label for="image" class="block text-gray-700 font-medium mb-2">Image</label>
                    <input type="file" id="image" name="image" accept="image/*"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <button type="submit" 
                        class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                    Upload Post
                </button>
            </form>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="posts.php" class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <h3 class="font-semibold mb-2">Manage Posts</h3>
                <p class="text-gray-600">View, edit, and delete posts</p>
            </a>
            <a href="categories.php" class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <h3 class="font-semibold mb-2">Manage Categories</h3>
                <p class="text-gray-600">Organize your content</p>
            </a>
        </div>
    </div>
</div>

<script>
// Add JavaScript to handle subcategory loading
document.getElementById('category_id').addEventListener('change', function() {
    const categoryId = this.value;
    const subcategorySelect = document.getElementById('subcategory_id');
    
    // Clear current options
    subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';
    
    if (categoryId) {
        // Fetch subcategories
        fetch(`get_subcategories.php?category_id=${categoryId}`)
            .then(response => response.json())
            .then(subcategories => {
                subcategories.forEach(subcategory => {
                    const option = document.createElement('option');
                    option.value = subcategory.id;
                    option.textContent = subcategory.name;
                    subcategorySelect.appendChild(option);
                });
            });
    }
});
</script>

<?php require_once 'includes/footer.php'; ?> 