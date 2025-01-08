<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - News Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="index.php" class="text-xl font-bold text-gray-800">Admin Panel</a>
                </div>
                <div class="flex space-x-4">
                    <?php
                    $current_page = basename($_SERVER['PHP_SELF']);
                    $nav_items = [
                        'posts.php' => 'Posts',
                        'categories.php' => 'Categories',
                        'comments.php' => 'Comments',
                        'pages.php' => 'Pages'
                    ];
                    
                    foreach ($nav_items as $url => $title) {
                        $active = ($current_page === $url) ? 'text-blue-500' : 'text-gray-600 hover:text-blue-500';
                        echo "<a href='$url' class='$active'>$title</a>";
                    }
                    ?>
                    <a href="logout.php" class="text-red-600 hover:text-red-800">Logout</a>
                </div>
            </div>
        </div>
    </nav>
</body>
</html> 