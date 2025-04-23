// Mobile menu toggle
document.querySelector('.menu-toggle').addEventListener('click', function() {
    document.getElementById('nav-menu').classList.toggle('show');
});

// Load blog data from the server
async function loadBlogData() {
    try {
        const response = await fetch('get_posts.php');
        const posts = await response.json();
        
        if (posts && posts.length > 0) {
            displayBlogPosts(posts);
        } else {
            document.getElementById('blog-grid').innerHTML = 
                '<p class="no-posts">No blog posts found.</p>';
        }
    } catch (error) {
        console.error('Error loading blog data:', error);
        document.getElementById('blog-grid').innerHTML = 
            '<p class="error">Error loading blog posts. Please try again later.</p>';
    }
}

// Display blog posts in the grid
function displayBlogPosts(posts) {
    const blogGrid = document.getElementById('blog-grid');
    
    posts.forEach(post => {
        // Format date
        const postDate = new Date(post.date);
        const formattedDate = `${postDate.getFullYear()}/${postDate.getMonth() + 1}/${postDate.getDate()}`;
        
        // Create URL-friendly slug from title
        const slug = post.title.toLowerCase()
            .replace(/[^\w\s]/g, '')
            .replace(/\s+/g, '-')
            .substring(0, 50);
        
        // Create post URL
        const postUrl = `post.php?slug=${encodeURIComponent(slug)}&date=${formattedDate}`;
        
        // Create blog card HTML
        const blogCard = document.createElement('article');
        blogCard.className = 'blog-card';
        blogCard.innerHTML = `
            <div class="card-image">
                <a href="${postUrl}">
                    <img src="${post.image}" alt="${post.title}">
                </a>
            </div>
            <div class="card-content">
                <div class="post-meta">
                    <span class="author">By ${post.author}</span>
                    <span class="date">${postDate.toLocaleDateString()}</span>
                </div>
                <h3><a href="${postUrl}">${post.title}</a></h3>
                <p>${post.excerpt}</p>
                <a href="${postUrl}" class="read-more">Read More →</a>
            </div>
        `;
        
        blogGrid.appendChild(blogCard);
    });
}

// Initialize the blog
document.addEventListener('DOMContentLoaded', function() {
    loadBlogData();
});
