// Load blog data and initialize
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on the post page
    if (window.location.pathname.includes('post.html')) {
        loadSinglePost();
    } else {
        loadBlogPosts();
    }
});

// Load all blog posts
async function loadBlogPosts() {
    try {
        const response = await fetch('blog_data.json');
        const posts = await response.json();
        
        const grid = document.getElementById('blog-grid');
        if (!grid) return;
        
        grid.innerHTML = posts.map(post => `
            <article class="blog-card">
                <div class="card-image">
                    <a href="post.html?id=${post.id}">
                        <img src="${post.image}" alt="${post.title}">
                    </a>
                </div>
                <div class="card-content">
                    <h2><a href="post.html?id=${post.id}">${post.title}</a></h2>
                    <p>${post.excerpt}</p>
                    <small>By ${post.author} • ${new Date(post.date).toLocaleDateString()}</small>
                </div>
            </article>
        `).join('');
    } catch (error) {
        console.error('Error loading posts:', error);
    }
}

// Load single post
async function loadSinglePost() {
    const urlParams = new URLSearchParams(window.location.search);
    const postId = urlParams.get('id');
    
    if (!postId) {
        window.location.href = 'index.html';
        return;
    }
    
    try {
        const response = await fetch('blog_data.json');
        const posts = await response.json();
        const post = posts.find(p => p.id == postId);
        
        if (!post) {
            window.location.href = 'index.html';
            return;
        }
        
        document.title = `${post.title} | My Blog`;
        document.getElementById('post-content').innerHTML = `
            <h1>${post.title}</h1>
            <div class="post-meta">
                <span>By ${post.author}</span>
                <span>•</span>
                <span>${new Date(post.date).toLocaleDateString()}</span>
            </div>
            <img src="${post.image}" class="featured-image">
            <div class="post-body">${post.description}</div>
            <a href="index.html" class="back-link">← Back to Blog</a>
        `;
    } catch (error) {
        console.error('Error loading post:', error);
    }
}
