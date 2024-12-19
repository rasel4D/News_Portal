<?php
require_once 'config/database.php';
require_once 'includes/header.php';

// Fetch posts with all related information
$query = "SELECT p.*, c.name as category_name, u.username as author_name 
          FROM posts p 
          LEFT JOIN categories c ON p.category_id = c.id 
          LEFT JOIN users u ON p.author_id = u.id 
          WHERE p.is_deleted = 0 
          ORDER BY p.created_at DESC";
$result = $conn->query($query);
?>

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($post = $result->fetch_assoc()): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <?php if ($post['image']): ?>
                    <img src="<?php echo htmlspecialchars($post['image']); ?>" 
                         alt="<?php echo htmlspecialchars($post['title']); ?>"
                         class="w-full h-48 object-cover">
                <?php endif; ?>
                
                <div class="p-4">
                    <h2 class="text-xl font-bold mb-2">
                        <a href="#" onclick="openModal(<?php echo $post['id']; ?>); return false;"
                           class="text-gray-900 hover:text-blue-600">
                            <?php echo htmlspecialchars($post['title']); ?>
                        </a>
                    </h2>
                    
                    <div class="text-sm text-gray-600 mb-4">
                        <span>By <?php echo htmlspecialchars($post['author_name']); ?></span>
                        <span class="mx-2">•</span>
                        <span><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                    </div>
                    
                    <p class="text-gray-700 mb-4">
                        <?php echo substr(strip_tags($post['content']), 0, 150) . '...'; ?>
                    </p>
                    
                    <button onclick="openModal(<?php echo $post['id']; ?>)"
                            class="text-blue-500 hover:text-blue-700">
                        Read More →
                    </button>
                </div>
            </div>

            <!-- Modal for this post -->
            <div id="modal-<?php echo $post['id']; ?>" 
                 class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden overflow-y-auto">
                <div class="min-h-screen px-4 text-center">
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>

                    <!-- Modal content -->
                    <div class="inline-block w-full max-w-4xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                        <div class="absolute top-0 right-0 pt-4 pr-4">
                            <button onclick="closeModal(<?php echo $post['id']; ?>)" 
                                    class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <span class="text-2xl">&times;</span>
                            </button>
                        </div>

                        <?php if ($post['image']): ?>
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($post['title']); ?>"
                                 class="w-full h-96 object-cover rounded-lg mb-6">
                        <?php endif; ?>

                        <h2 class="text-3xl font-bold text-gray-900 mb-4">
                            <?php echo htmlspecialchars($post['title']); ?>
                        </h2>

                        <div class="mb-4 text-sm text-gray-600">
                            <span>By <?php echo htmlspecialchars($post['author_name']); ?></span>
                            <span class="mx-2">•</span>
                            <span><?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                            <span class="mx-2">•</span>
                            <span>Category: <?php echo htmlspecialchars($post['category_name']); ?></span>
                        </div>

                        <div class="prose max-w-none">
                            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Add this JavaScript at the bottom of the file -->
<script>
function openModal(postId) {
    document.getElementById(`modal-${postId}`).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal(postId) {
    document.getElementById(`modal-${postId}`).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('bg-opacity-50')) {
        const modals = document.querySelectorAll('[id^="modal-"]');
        modals.forEach(modal => {
            modal.classList.add('hidden');
        });
        document.body.style.overflow = 'auto';
    }
});

// Close modal on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modals = document.querySelectorAll('[id^="modal-"]');
        modals.forEach(modal => {
            modal.classList.add('hidden');
        });
        document.body.style.overflow = 'auto';
    }
});
</script>

<?php require_once 'includes/footer.php'; ?> 