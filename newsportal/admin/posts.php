<?php
session_start();
require_once '../config/database.php';
require_once 'includes/header.php';

// Check if user is logged in and is admin/subadmin
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'subadmin'])) {
    header('Location: index.php');
    exit();
}

// Handle post deletion
if (isset($_GET['delete'])) {
    $post_id = (int)$_GET['delete'];
    $conn->query("UPDATE posts SET is_deleted = 1 WHERE id = $post_id");
    header('Location: posts.php');
    exit();
}

// Handle post restoration
if (isset($_GET['restore'])) {
    $post_id = (int)$_GET['restore'];
    $conn->query("UPDATE posts SET is_deleted = 0 WHERE id = $post_id");
    header('Location: posts.php?trash=1');
    exit();
}

// Determine if we're viewing trash
$is_trash = isset($_GET['trash']) && $_GET['trash'] == 1;
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manage Posts</h1>
        <div class="space-x-2">
            <?php if ($is_trash): ?>
                <a href="posts.php" class="bg-gray-500 text-white px-4 py-2 rounded">View Active Posts</a>
            <?php else: ?>
                <a href="posts.php?trash=1" class="bg-gray-500 text-white px-4 py-2 rounded">View Trash</a>
                <a href="add_post.php" class="bg-blue-500 text-white px-4 py-2 rounded">Add New Post</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-6 py-3 text-left">Title</th>
                    <th class="px-6 py-3 text-left">Category</th>
                    <th class="px-6 py-3 text-left">Author</th>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT p.*, c.name as category_name, u.username as author_name 
                          FROM posts p 
                          LEFT JOIN categories c ON p.category_id = c.id 
                          LEFT JOIN users u ON p.author_id = u.id 
                          WHERE p.is_deleted = " . ($is_trash ? '1' : '0') . " 
                          ORDER BY p.created_at DESC";
                $result = $conn->query($query);

                while ($row = $result->fetch_assoc()):
                ?>
                <tr class="border-t">
                    <td class="px-6 py-4">
                        <?php echo htmlspecialchars($row['title']); ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php echo htmlspecialchars($row['category_name']); ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php echo htmlspecialchars($row['author_name']); ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php if ($is_trash): ?>
                            <a href="posts.php?restore=<?php echo $row['id']; ?>" 
                               class="text-blue-500 hover:underline mr-2">Restore</a>
                        <?php else: ?>
                            <a href="edit_post.php?id=<?php echo $row['id']; ?>" 
                               class="text-blue-500 hover:underline mr-2">Edit</a>
                            <a href="posts.php?delete=<?php echo $row['id']; ?>" 
                               class="text-red-500 hover:underline" 
                               onclick="return confirm('Are you sure you want to move this post to trash?')">
                                Delete
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 