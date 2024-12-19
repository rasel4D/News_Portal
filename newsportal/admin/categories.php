<?php
session_start();
require_once '../config/database.php';
require_once 'includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle category creation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    
    $query = "INSERT INTO categories (name, slug) VALUES ('$name', '$slug')";
    if ($conn->query($query)) {
        $success = "Category created successfully!";
    } else {
        $error = "Error creating category: " . $conn->error;
    }
}

// Fetch all categories
$categories = $conn->query("SELECT * FROM categories WHERE is_deleted = 0 ORDER BY created_at DESC");
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Categories</h1>
            <button onclick="document.getElementById('addCategoryModal').classList.remove('hidden')"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Add Category
            </button>
        </div>

        <?php if (isset($success)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while ($category = $categories->fetch_assoc()): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($category['name']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($category['slug']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php echo date('M d, Y', strtotime($category['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="#" class="text-blue-600 hover:text-blue-900">Edit</a>
                                <a href="#" class="text-red-600 hover:text-red-900 ml-4">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div id="addCategoryModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-8 max-w-md w-full">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Add New Category</h2>
                <button onclick="document.getElementById('addCategoryModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-500">&times;</button>
            </div>
            <form method="POST">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-medium mb-2">Category Name</label>
                    <input type="text" id="name" name="name" required
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Add Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 