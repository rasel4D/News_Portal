<?php
session_start();
require_once '../config/database.php';
require_once 'includes/header.php';

// Check if user is logged in and is admin/subadmin
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'subadmin'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $category_id = (int)$_POST['category_id'];
    $subcategory_id = (int)$_POST['subcategory_id'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = uniqid() . '.' . $ext;
            $upload_path = '../assets/images/posts/' . $new_filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image = $new_filename;
            }
        }
    }
    
    $query = "INSERT INTO posts (title, slug, content, category_id, subcategory_id, author_id, image) 
              VALUES ('$title', '$slug', '$content', $category_id, $subcategory_id, {$_SESSION['user_id']}, '$image')";
    
    if ($conn->query($query)) {
        header('Location: posts.php');
        exit();
    } else {
        $error = "Error creating post: " . $conn->error;
    }
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Add New Post</h2>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                        Title
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
                           id="title" 
                           name="title" 
                           type="text" 
                           required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="category_id">
                        Category
                    </label>
                    <select class="shadow border rounded w-full py-2 px-3 text-gray-700"
                            id="category_id" 
                            name="category_id" 
                            required>
                        <option value="">Select Category</option>
                        <?php
                        $categories = $conn->query("SELECT * FROM categories WHERE is_deleted = 0");
                        while ($cat = $categories->fetch_assoc()):
                        ?>
                            <option value="<?php echo $cat['id']; ?>">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="subcategory_id">
                        Subcategory
                    </label>
                    <select class="shadow border rounded w-full py-2 px-3 text-gray-700"
                            id="subcategory_id" 
                            name="subcategory_id">
                        <option value="">Select Subcategory</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                        Featured Image
                    </label>
                    <input type="file" 
                           id="image" 
                           name="image" 
                           accept="image/*"
                           class="w-full">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="content">
                        Content
                    </label>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
                              id="content" 
                              name="content" 
                              rows="10" 
                              required></textarea>
                </div>

                <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            type="submit">
                        Publish Post
                    </button>
                    <a href="posts.php" 
                       class="text-gray-600 hover:text-gray-800">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Add dynamic subcategory loading based on selected category
document.getElementById('category_id').addEventListener('change', function() {
    const categoryId = this.value;
    const subcategorySelect = document.getElementById('subcategory_id');
    
    // Clear current options
    subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';
    
    if (categoryId) {
        fetch(`get_subcategories.php?category_id=${categoryId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(subcategory => {
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