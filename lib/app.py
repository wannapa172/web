from flask import Flask, request, jsonify, render_template
from flask_pymongo import PyMongo
from flask_jwt_extended import JWTManager, create_access_token, jwt_required, get_jwt_identity
import bcrypt
import logging

# ตั้งค่าการแสดง Log
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')

# กำหนดค่า Flask app
app = Flask(__name__)

# กำหนดค่า MongoDB
app.config['MONGO_URI'] = "mongodb+srv://wannapaba:RGuj6SpDJ82OzzwN@webtamroidb.uwbym.mongodb.net/webtamroidb?retryWrites=true&w=majority"
app.config['JWT_SECRET_KEY'] = "supersecretkey"  # คีย์สำหรับ JWT Token
mongo = PyMongo(app)
jwt = JWTManager(app)

db = mongo.db  # ใช้ `mongo.db` แทนการเรียก `mongo.db.posts`
logging.info(f"เชื่อมต่อกับ MongoDB: {app.config['MONGO_URI']}")

### ✅ **1. Route สำหรับหน้าแรก**
@app.route('/')
def home():
    logging.info("หน้าแรกถูกเรียกใช้งาน")
    posts = list(db['posts'].find({}, {"_id": 0}))  # เข้าถึง collection อย่างถูกต้อง
    return render_template('Home.html', posts=posts)

### ✅ **2. Route สำหรับแสดงหน้าโพสต์**
@app.route('/post')
def post_page():
    logging.info("หน้าโพสต์ถูกเรียกใช้งาน")
    return render_template('post.html')

### ✅ **3. Route สำหรับแสดงหน้า Login**
@app.route('/login_page')
def login_page():
    logging.info("หน้า Login ถูกเรียกใช้งาน")
    return render_template('login.html')

@app.route('/register_page')
def register_page():
    return render_template('register.html')

### ✅ **4. Route สำหรับลงทะเบียนผู้ใช้ (Register)**
@app.route('/register', methods=['POST'])
def register():
    data = request.json
    username = data.get('username')
    email = data.get('email')
    password = data.get('password')

    if not username or not email or not password:
        return jsonify({"error": "กรุณากรอกข้อมูลให้ครบถ้วน"}), 400

    existing_user = db['register'].find_one({"email": email})
    if existing_user:
        return jsonify({"error": "อีเมลนี้ถูกใช้ไปแล้ว"}), 400

    # ✅ เข้ารหัสรหัสผ่านก่อนเก็บลง MongoDB
    hashed_password = bcrypt.hashpw(password.encode('utf-8'), bcrypt.gensalt())

    user_data = {
        "username": username,
        "email": email,
        "password": hashed_password.decode('utf-8')  # เก็บ bcrypt hash เป็น string
    }
    db['register'].insert_one(user_data)
    return jsonify({"message": "สมัครสมาชิกสำเร็จ!"}), 201

### ✅ **5. Route สำหรับล็อกอิน (Login)**
@app.route('/login', methods=['POST'])
def login():
    try:
        data = request.json
        email = data.get('email')
        password = data.get('password')

        if not email or not password:
            return jsonify({"error": "กรุณากรอกอีเมลและรหัสผ่านให้ครบถ้วน"}), 400

        user = db['register'].find_one({"email": email})

        if not user:
            return jsonify({"error": "ไม่พบอีเมลนี้ในระบบ"}), 400

        stored_password = user.get('password')

        # ✅ ตรวจสอบว่า stored_password เป็น bcrypt hash หรือไม่
        if not stored_password.startswith("$2b$"):
            return jsonify({"error": "บัญชีนี้มีรหัสผ่านที่ไม่ได้เข้ารหัส กรุณาสมัครใหม่"}), 400

        if bcrypt.checkpw(password.encode('utf-8'), stored_password.encode('utf-8')):
            access_token = create_access_token(identity={"email": user['email'], "username": user['username']})
            return jsonify({
                "message": "เข้าสู่ระบบสำเร็จ!",
                "token": access_token,
                "user": {"username": user["username"], "email": user["email"]}
            }), 200
        else:
            return jsonify({"error": "รหัสผ่านไม่ถูกต้อง"}), 400

    except Exception as e:
        return jsonify({"error": f"เกิดข้อผิดพลาดภายในเซิร์ฟเวอร์: {str(e)}"}), 500


### ✅ **6. Route สำหรับเข้าถึงข้อมูลผู้ใช้ที่ล็อกอิน**
@app.route('/profile', methods=['GET'])
@jwt_required()
def profile():
    current_user = get_jwt_identity()
    return jsonify({
        "username": current_user["username"],
        "email": current_user["email"]
    }), 200


### ✅ **7. Route สำหรับโพสต์เนื้อหา**
@app.route('/submit', methods=['POST'])
def submit_post():
    data = request.json
    logging.info(f"รับข้อมูลจากไคลเอนต์: {data}")
    
    category = data.get('category')
    title = data.get('title')
    content = data.get('content')
    image_url = data.get('image_url')  # ลิงก์ไฟล์รูปภาพ (อาจใช้ Cloud Storage)
    
    if not category or not title or not content:
        logging.warning("ข้อมูลไม่ครบถ้วน")
        return jsonify({"error": "กรุณากรอกข้อมูลให้ครบถ้วน"}), 400
    
    # บันทึกลง MongoDB
    post_data = {
        "category": category,
        "title": title,
        "content": content,
        "image_url": image_url
    }
    post_id = db['posts'].insert_one(post_data).inserted_id  # ใช้ `db['posts']` แทน `db.posts`
    logging.info(f"บันทึกข้อมูลลง MongoDB ID: {post_id}")
    
    return jsonify({"message": "โพสต์สำเร็จ", "post_id": str(post_id)})

### ✅ **8. Route สำหรับดึงข้อมูลโพสต์ทั้งหมด**
@app.route('/get_posts', methods=['GET'])
def get_posts():
    logging.info("ดึงข้อมูลโพสต์ทั้งหมด")
    posts = list(db['posts'].find({}, {"_id": 0}))  # ใช้ `db['posts']` แทน `db.posts`
    return jsonify(posts)

### ✅ **9. Route สำหรับดึงข้อมูลโพสต์ตาม ID**
@app.route('/post/<post_id>')
def post_detail(post_id):
    from bson.objectid import ObjectId

    logging.info(f"เปิดดูโพสต์ ID: {post_id}")
    post = db['posts'].find_one({"_id": ObjectId(post_id)}, {"_id": 0})  # ค้นหาโพสต์ที่ตรงกับ ID
    
    if not post:
        return "ไม่พบโพสต์", 404
    
    return render_template('post_detail.html', post=post)

### ✅ **10. Route สำหรับดึงข้อมูลโพสต์ตามหมวดหมู่**
@app.route('/get_posts_by_category', methods=['GET'])
def get_posts_by_category():
    category = request.args.get('category')  # รับค่าหมวดหมู่จาก Query Parameter
    if not category:
        return jsonify({"error": "กรุณาระบุ category"}), 400

    logging.info(f"ดึงโพสต์ในหมวดหมู่: {category}")

    posts = list(db['posts'].find({"category": category}, {"_id": 0}))  # ดึงเฉพาะโพสต์ที่ตรงกับ category
    if not posts:
        return jsonify({"message": "ไม่มีโพสต์ในหมวดหมู่นี้"}), 404

    return jsonify(posts)




@app.route('/check_login', methods=['GET'])
@jwt_required(optional=True)
def check_login():
    current_user = get_jwt_identity()
    if current_user:
        return jsonify({"logged_in": True, "user": current_user}), 200
    else:
        return jsonify({"logged_in": False}), 200
    
from flask_jwt_extended import get_jwt

# ✅ API สำหรับออกจากระบบ (Blacklist JWT Token)
@app.route('/logout', methods=['POST'])
@jwt_required()
def logout():
    jti = get_jwt()["jti"]  # ดึง Token ID (JWT ID)
    
    # 🚀 ถ้าต้องการเก็บ Token ที่ถูกออกจากระบบไปยัง Database (Blacklist)
    # db['token_blacklist'].insert_one({"jti": jti})

    return jsonify({"message": "ออกจากระบบสำเร็จ!"}), 200





if __name__ == '__main__':
    logging.info("เริ่มรันเซิร์ฟเวอร์ Flask")
    app.run(debug=True)
