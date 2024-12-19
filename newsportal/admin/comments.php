<?php
session_start();
require_once '../config/database.php';
require_once 'includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle comment status updates
if (isset($_POST['action']) && isset($_POST['comment_id'])) {
    $comment_id = (int)$_POST['comment_id'];
    $action = $_POST['action'];
    
    if (in_array($action, ['approve', 'reject'])) {
        $status = ($action === 'approve') ? 'approved' : 'rejected';
        $query = "UPDATE comments SET status = '$status' WHERE id = $comment_id";
        $conn->query($query);
    }
}

// Fetch comments with post information
$query = "SELECT c.*, p.title as post_title 
          FROM comments c 
          LEFT JOIN posts p ON c.post_id = p.id 
          ORDER BY c.created_at DESC";
$comments = $conn->query($query);
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Comments</h1>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Post</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while ($comment = $comments->fetch_assoc()): ?>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <?php echo htmlspecialchars($comment['comment']); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php echo htmlspecialchars($comment['post_title']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?php echo htmlspecialchars($comment['name']); ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?php echo htmlspecialchars($comment['email']); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php echo $comment['status'] === 'approved' ? 'bg-green-100 text-green-800' : 
                                        ($comment['status'] === 'rejected' ? 'bg-red-100 text-red-800' : 
                                        'bg-yellow-100 text-yellow-800'); ?>">
                                    <?php echo ucfirst($comment['status']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo date('M d, Y', strtotime($comment['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form method="POST" class="inline">
                                    <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                    <?php if ($comment['status'] !== 'approved'): ?>
                                        <button type="submit" name="action" value="approve" 
                                                class="text-green-600 hover:text-green-900">
                                            Approve
                                        </button>
                                    <?php endif; ?>
                                    <?php if ($comment['status'] !== 'rejected'): ?>
                                        <button type="submit" name="action" value="reject" 
                                                class="text-red-600 hover:text-red-900 ml-4">
                                            Reject
                                        </button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 