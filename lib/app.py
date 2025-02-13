from flask import Flask, request, jsonify, render_template
from flask_pymongo import PyMongo
import logging

# ตั้งค่าการแสดง Log
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')

# กำหนดค่า Flask app
app = Flask(__name__)

# กำหนดค่า MongoDB โดยตรง
app.config['MONGO_URI'] = "mongodb+srv://wannapaba:RGuj6SpDJ82OzzwN@webtamroidb.uwbym.mongodb.net/webtamroidb?retryWrites=true&w=majority"
mongo = PyMongo(app)

db = mongo.db  # ใช้ `mongo.db` แทนการเรียก `mongo.db.posts`
logging.info(f"เชื่อมต่อกับ MongoDB: {app.config['MONGO_URI']}")

@app.route('/')
def home():
    logging.info("หน้าแรกถูกเรียกใช้งาน")
    posts = list(db['posts'].find({}, {"_id": 0}))  # เข้าถึง collection อย่างถูกต้อง
    return render_template('Home.html', posts=posts)

@app.route('/post')
def post_page():
    logging.info("หน้าโพสต์ถูกเรียกใช้งาน")
    return render_template('post.html')

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

@app.route('/get_posts', methods=['GET'])
def get_posts():
    logging.info("ดึงข้อมูลโพสต์ทั้งหมด")
    posts = list(db['posts'].find({}, {"_id": 0}))  # ใช้ `db['posts']` แทน `db.posts`
    return jsonify(posts)

@app.route('/post/<post_id>')
def post_detail(post_id):
    from bson.objectid import ObjectId

    logging.info(f"เปิดดูโพสต์ ID: {post_id}")
    post = db['posts'].find_one({"_id": ObjectId(post_id)}, {"_id": 0})  # ค้นหาโพสต์ที่ตรงกับ ID
    
    if not post:
        return "ไม่พบโพสต์", 404
    
    return render_template('post_detail.html', post=post)


if __name__ == '__main__':
    logging.info("เริ่มรันเซิร์ฟเวอร์ Flask")
    app.run(debug=True)
