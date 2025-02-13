<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tam Roi Review</title>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Styles */
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
        }

        /* Header */
        header {
            background-color: #FFD700;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        header .logo img {
            height: 100px; /* ปรับค่าความสูงตามที่คุณต้องการ */
            width: auto; /* ให้ความกว้างปรับตามอัตราส่วน */
        }
        

        .header-icons {
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
        }
        

        .header-icons i {
            font-size: 24px;
            color: #333;
            cursor: pointer;
        }

        /* Navbar */
        nav {
            background-color: #f8e5a6;
            display: flex;
            justify-content: center;
            padding: 10px;
        }

        nav a {
            margin: 0 15px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
            font-size: 16px;
        }

        nav a:hover {
            color: #FFD700;
        }

        /* Posts Grid */
        .posts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .post {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
        }
        
        

        .profile-pic {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-right: 10px;/* ระยะห่างจากข้อมูลตัวหนังสือ */
        }

        .post-user-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-left: 10px;
        }
        

         .username {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 2px; /* เพิ่มช่องว่างระหว่างชื่อและเวลา */
         }

         .post-time {
              font-size: 12px;
               color: #777;
        }
        .post-header .actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .post-header i.fa-ellipsis-h {
            font-size: 18px;
            color: #333;
            cursor: pointer;
        }

        .post-header i.fa-ellipsis-h:hover {
            color: #FFD700;
        }



        .post-image {
            width: 100%;
            height: auto;
        }

        .post-content {
            padding: 15px;
        }

        .post-content h3 {
            font-size: 16px;
            color: #333;
            margin: 0 0 10px;
        }

        .post-content p {
            font-size: 14px;
            color: #555;
        }
        .post-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            border-top: 1px solid #ddd;
        }

        .post-footer button {
            display: flex;
            align-items: center;
            gap: 5px;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 14px;
            color: #333;
        }

        .post-footer button:hover {
            color: #FFD700;
        }
        

        /* Floating Button */
        .floating-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #FFD700;
            color: #fff;
            border: none;
            padding: 15px 20px;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .floating-button:hover {
            background-color: #ffc107;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: #fff;
            border-radius: 20px;
            padding: 30px;
            width: 90%;
            max-width: 600px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .modal-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .modal-body img {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }

        .modal-body label {
            color: #007BFF;
            cursor: pointer;
            font-size: 16px;
            text-decoration: underline;
        }

        .modal-body input[type="file"] {
            display: none;
        }

        .modal-body input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            margin-top: 10px;
        }

        .modal-footer {
            margin-top: 20px;
        }

        .modal-footer button {
            padding: 12px 20px;
            background-color: #FFD700;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
        }

        .modal-footer button:hover {
            background-color: #FFC107;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            color: #333;
            cursor: pointer;
        }
        /* ปรับตำแหน่งของ search-container */

        .search-container {
             position: absolute;
             top: 50%;
             transform: translateY(-50%);
             left: 60px; /* ระบุตำแหน่งด้านซ้าย */
             width: 0; /* ซ่อนแถบค้นหา */
             background: #fff;
             border-radius: 20px;
             padding: 10px 15px;
             display: none; /* ซ่อนโดยเริ่มต้น */
             box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
             overflow: hidden;
             transition: width 0.3s ease-in-out; /* เพิ่มการแอนิเมชัน */
        }

        .search-container.active {
            display: block;
            width: 300px; /* ขนาดแถบค้นหาเมื่อแสดง */
        }

        .search-container input {
            width: 100%;
            border: none;
            outline: none;
            font-size: 16px;
        }
        button.like-btn.liked i {
            color: red; /* สีเมื่อถูกกด */
        }
        


    </style>
</head>

<body>
    <?PHP require_once("connect.php");
    $sqll = "SELECT * FROM tag";
    $resultl = $conn->query($sqll);
    $rowl = $resultl->fetch_assoc();


    echo $rowl['Tag_Name'] ;
    ?>




    <!-- Header -->
    <header>
        <div class="logo">
            <img src="โลโก้เว็บตามรอยรีวิว.png" alt="Tam Roi Review Logo">
        </div>
        <div class="search-container" id="search-container">
            <input type="text" placeholder="ค้นหา...">
        </div>
        <div class="header-icons">
            <i class="fas fa-home"></i>
            <i class="fas fa-search" id="search-icon" onclick="toggleSearchBar()"></i>
            <div class="search-container" id="search-container">
                <input type="text" placeholder="ค้นหา...">
            </div>
            <i class="fas fa-camera" onclick="openModal()"></i>
            <i class="fas fa-bell"></i>
            <a href="login.html"><i class="fas fa-user"></i></a>
            <i class="fas fa-bars"></i>
        </div>
    </header>
    
     <!-- Tabs -->
     <!-- Tabs -->
<div class="tabs">
    <button id="tab-for-you" class="active" onclick="filterPosts('forYou')">สำหรับคุณ</button>
    <button id="tab-following" onclick="filterPosts('following')">ติดตาม</button>
</div>

<style>
    /* Tabs Section */
    .tabs {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #fff;
        padding: 10px 0;
        border-bottom: 2px solid #f0f0f0;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .tabs button {
        background: none;
        border: none;
        padding: 10px 20px;
        margin: 0 10px;
        font-size: 16px;
        font-weight: bold;
        color: #333;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: color 0.3s, border-bottom 0.3s;
    }

    .tabs button.active {
        color: #FFD700;
        border-bottom: 3px solid #FFD700;
    }

    .tabs button:hover {
        color: #FFD700;
    }
</style>

<script>
    function filterPosts(tab) {
        const allPosts = document.querySelectorAll('.post');
        const tabForYou = document.getElementById('tab-for-you');
        const tabFollowing = document.getElementById('tab-following');

        // Highlight the active tab
        if (tab === 'forYou') {
            tabForYou.classList.add('active');
            tabFollowing.classList.remove('active');
        } else if (tab === 'following') {
            tabFollowing.classList.add('active');
            tabForYou.classList.remove('active');
        }

        // Filter posts logic
        allPosts.forEach(post => {
            const category = post.getAttribute('data-category');
            if (tab === 'forYou') {
                post.style.display = 'block'; // Show all posts
            } else if (tab === 'following') {
                if (category === 'following') {
                    post.style.display = 'block'; // Show only following posts
                } else {
                    post.style.display = 'none'; // Hide other posts
                }
            }
        });
    }
</script>


    <!-- Navbar -->
    <nav>
        <a href="#">อาหาร</a>
        <a href="#">แฟชั่น</a>
        <a href="#">การท่องเที่ยว</a>
        <a href="#">สุขภาพ</a>
        <a href="#">การตกแต่งบ้าน</a>
        <a href="#">สกินแคร์</a>
        <a href="#">ถ่ายรูป</a>
        <a href="#">สัตว์เลี้ยง</a>
        <a href="#">อาชีพ</a>
        <a href="#">ลดน้ำหนัก</a>
    </nav>

    <!-- Posts Grid -->
    <section class="posts">
        <!-- โพสต์จะถูกสร้างด้วย JavaScript -->
    </section>

    <!-- Floating Button -->
    <div class="floating-button" onclick="location.href='post.html'">+</div>


    <!-- Modal -->
    <div class="modal" id="imageSearchModal">
        <div class="modal-content">
            <div class="close-btn" onclick="closeModal()">×</div>
            <div class="modal-header">ค้นหาด้วย Lens</div>
            <div class="modal-body">
                <img src="https://via.placeholder.com/80" alt="Upload Icon">
                <label for="fileUpload">ลากรูปภาพมาที่นี่หรือ <u>อัปโหลดไฟล์</u></label>
                <input type="file" id="fileUpload" accept="image/*">
                <div>หรือ</div>
                <input type="text" placeholder="วางลิงก์รูปภาพ">
            </div>
            <div class="modal-footer">
                <button onclick="alert('เริ่มการค้นหา!')">ค้นหา</button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        const posts = [
            {
                title: "สูตรหมูทอดทำง่าย",
                content: "แนะนำสูตรอาหารยอดนิยม ทำง่ายได้ที่บ้าน",
                img: "https://via.placeholder.com/300x200",
                likes: "1.4k",
                comments: "17"
            },
            {
                title: "รวมเมนูสุขภาพดี",
                content: "อาหารเพื่อสุขภาพ ที่ทำได้ง่ายและอร่อย",
                img: "https://via.placeholder.com/300x200",
                likes: "2.1k",
                comments: "34"
            },
            {
                title: "ไอเดียจัดจานอาหาร",
                content: "เพิ่มความน่าสนใจให้อาหารของคุณ",
                img: "https://via.placeholder.com/300x200",
                likes: "900",
                comments: "12"
            }
        ];
        function handleLike(button) {
            const likeCount = button.querySelector('span'); // ค้นหา element span ภายในปุ่ม
            let likes = parseInt(likeCount.textContent.replace('k', '')) * 1000 || 0; // แปลง k เป็นตัวเลข
            const isLiked = button.classList.toggle('liked');
        
            if (isLiked) {
                likes += 1; // เพิ่มไลก์
            } else {
                likes -= 1; // ลดไลก์
            }
        
            likeCount.textContent = likes >= 1000 ? `${(likes / 1000).toFixed(1)}k` : likes; // แปลงกลับเป็น k
        }
        

        function renderPosts() {
            const postsContainer = document.querySelector('.posts');
            posts.forEach(post => {
                const postHTML = `
                     <div class="post">
        <div class="post-header">
            <a href="profile.html">
            <img src="https://via.placeholder.com/300x200" alt="Profile" class="profile-pic">
            <div class="post-user-info">
                <a href="profile.html" class="username">Sale Here</a>
                <span class="post-time">5 นาทีที่แล้ว</span>
            </div>
            <button class="follow-btn">ติดตาม</button>
            <i class="fas fa-ellipsis-h"></i>
        </div>
        <img src="https://via.placeholder.com/300x200" alt="Post Image" class="post-image">
        <div class="post-content">
            <h3>สูตรหมูทอดทำง่าย</h3>
            <p>แนะนำสูตรอาหารยอดนิยม ทำง่ายได้ที่บ้าน</p>
        </div>
        <div class="post-footer">
    <button class="like-btn" onclick="handleLike(this)"><i class="fas fa-heart"></i> <span>1.4k</span></button>
    <button><i class="fas fa-comment"></i> 17</button>
    <button><i class="fas fa-bookmark"></i> บันทึก</button>
</div>

    </div>
    <div class="post">
        <div class="post-header">
            <img src="https://via.placeholder.com/40" alt="Profile" class="profile-pic">
            <div class="post-user-info">
                <span class="username">Sale Here</span>
                <span class="post-time">5 นาทีที่แล้ว</span>
            </div>
            <button class="follow-btn">ติดตาม</button>
            <i class="fas fa-ellipsis-h"></i>
        </div>
        <img src="https://via.placeholder.com/300x200" alt="Post Image" class="post-image">
        <div class="post-content">
            <h3>สูตรหมูทอดทำง่าย</h3>
            <p>แนะนำสูตรอาหารยอดนิยม ทำง่ายได้ที่บ้าน</p>
        </div>
        <div class="post-footer">
    <button class="like-btn" onclick="handleLike(this)"><i class="fas fa-heart"></i> <span>1.4k</span></button>
    <button><i class="fas fa-comment"></i> 17</button>
    <button><i class="fas fa-bookmark"></i> บันทึก</button>
</div>

    </div>
                `;
                postsContainer.innerHTML += postHTML;
            });
        }

        function toggleLike(button) {
            const liked = button.classList.toggle('liked');
            let likes = parseInt(button.textContent.split(' ')[1].replace('k', '')) * 1000;
            if (liked) {
                likes += 1;
            } else {
                likes -= 1;
            }
            button.textContent = `❤️ ${likes / 1000}k`;
        }

        function toggleBookmark(button) {
            const bookmarked = button.classList.toggle('bookmarked');
            button.textContent = bookmarked ? '⭐ บันทึกแล้ว' : '⭐ บันทึก';
        }

        function toggleOptions(button) {
            const menu = button.nextElementSibling;
            const isVisible = menu.style.display === 'block';
            menu.style.display = isVisible ? 'none' : 'block';
            document.addEventListener('click', (e) => {
                if (!button.contains(e.target) && !menu.contains(e.target)) {
                    menu.style.display = 'none';
                }
            }, { once: true });
        }

        function sharePost(platform) {
            alert(`แชร์ไปยัง ${platform}`);
        }

        function openModal() {
            document.getElementById('imageSearchModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('imageSearchModal').style.display = 'none';
        }

        renderPosts();
        function toggleSearchBar() {
            const searchContainer = document.getElementById('search-container');
            searchContainer.classList.toggle('active');
        }             
        document.querySelectorAll('.follow-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                if (btn.textContent === 'ติดตาม') {
                    btn.textContent = 'ติดตามแล้ว';
                    btn.style.backgroundColor = '#ccc';
                } else {
                    btn.textContent = 'ติดตาม';
                    btn.style.backgroundColor = '#FFD700';
                }
            });
        });
        
    </script>

</body>

</html>
