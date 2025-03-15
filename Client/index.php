<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Anon E-commerce</title>
  <link rel="stylesheet" href="style.css"/>
  <!-- Optional Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet"/>
</head>
<body>
  <!-- Top Bar -->
  <div class="top-bar">
    <p>You deserve the best – and we’ve got it!</p>
  </div>

  <!-- Header / Navigation -->
  <header>
    <div class="logo">Lira</div>
    <div class="header-icons">
      <select name="currency" id="currency">
        <option value="USD">USD $</option>
        <option value="EUR">EUR €</option>
        <option value="MAD">MAD د.م.</option>
      </select>
      <select name="language" id="language">
        <option value="ENG">English</option>
        <option value="FR">Français</option>
        <option value="AR">العربية</option>
      </select>
      <a href="#"><i class="fas fa-user"></i></a>
      <a href="#"><i class="fas fa-shopping-cart"></i></a>
      <a href="login.html">
        <img src="pngegg.png" alt="Profile Logo" class="profile-logo">
      </a>
      <a href="basket.html">
        <img src="pngegg (1).png" alt="Profile Logo" class="profile-logo">
      </a>
    </div>
  </header>
  <!-- Main Navigation Links -->
  <nav class="main-nav">
    <div class="search-bar">
      <input type="text" placeholder="Enter your product name...">
      <button>Search</button>
    </div>
    <div class="list">
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">Categories</a></li>
        <li><a href="#">Dolls</a></li>
        <li><a href="#">Clothing</a></li>
        <li><a href="#">Small</a></li>
        <li><a href="#">Patterns</a></li>
      </ul>
    </div>
  </nav>

  <!-- Hero Banner -->
  <section class="hero">
    <div class="hero-text">
      <h2>Trenng Accessories</h2>
      <h1>Modern Sunglasses</h1>
      <p>starting at $15.00</p>
      <button>Shop Now</button>
    </div>
    <div class="hero-image">
      <img src="crch.png" alt="Hero Banner"/>
    </div>
  </section>

  <!-- Categories / Featured Items -->
  <section class="featured-categories">
    <h2>Shop by Category</h2>
    <div class="categories-container">
      <div class="category-item">
        <img src="https://via.placeholder.com/150?text=Dress+%26+Frock" alt="Dress & Frock">
        <p>Dress & Frock</p>
      </div>
      <div class="category-item">
        <img src="https://via.placeholder.com/150?text=Winter+Wear" alt="Winter Wear">
        <p>Winter Wear</p>
      </div>
      <div class="category-item">
        <img src="https://via.placeholder.com/150?text=Glasses+%26+Lens" alt="Glasses & Lens">
        <p>Glasses & Lens</p>
      </div>
      <div class="category-item">
        <img src="https://via.placeholder.com/150?text=Shoes+%26+Jeans" alt="Shoes & Jeans">
        <p>Shoes & Jeans</p>
      </div>
      <!-- Add more categories as needed -->
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Anon. All Rights Reserved.</p>
  </footer>

  <!-- Font Awesome for icons (optional) -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
