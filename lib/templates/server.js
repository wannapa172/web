require('dotenv').config();
const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors');
const bodyParser = require('body-parser');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');

const app = express();
const PORT = process.env.PORT || 5000;

// Middleware
app.use(cors());
app.use(bodyParser.json());

// เชื่อมต่อกับ MongoDB
mongoose.connect('mongodb://localhost:27017/webtamroidb', {
    useNewUrlParser: true,
    useUnifiedTopology: true
}).then(() => console.log('✅ เชื่อมต่อ MongoDB สำเร็จ'))
  .catch(err => console.error('❌ ไม่สามารถเชื่อมต่อ MongoDB:', err));

// สร้าง Schema สำหรับผู้ใช้
const UserSchema = new mongoose.Schema({
    username: { type: String, required: true },
    email: { type: String, required: true, unique: true },
    password: { type: String, required: true }
});

const User = mongoose.model('register', UserSchema);

// ✅ API สมัครสมาชิก
app.post('/register', async (req, res) => {
    try {
        const { username, email, password } = req.body;

        // ตรวจสอบว่ามีผู้ใช้ที่ใช้ email นี้แล้วหรือไม่
        const existingUser = await User.findOne({ email });
        if (existingUser) {
            return res.status(400).json({ success: false, message: "อีเมลนี้ถูกใช้ไปแล้ว" });
        }

        // เข้ารหัสรหัสผ่าน
        const hashedPassword = await bcrypt.hash(password, 10);
        const newUser = new User({ username, email, password: hashedPassword });

        await newUser.save();
        res.json({ success: true, message: "สมัครสมาชิกสำเร็จ!" });
    } catch (err) {
        console.error(err);
        res.status(500).json({ success: false, message: "เกิดข้อผิดพลาด" });
    }
});

// ✅ API ล็อกอิน
app.post('/login', async (req, res) => {
    try {
        const { email, password } = req.body;
        const user = await User.findOne({ email });

        if (!user) {
            return res.status(400).json({ success: false, message: "ไม่พบผู้ใช้งาน" });
        }

        const isMatch = await bcrypt.compare(password, user.password);
        if (!isMatch) {
            return res.status(400).json({ success: false, message: "รหัสผ่านไม่ถูกต้อง" });
        }

        // สร้าง Token
        const token = jwt.sign({ id: user._id, username: user.username }, 'SECRET_KEY', { expiresIn: '1h' });

        res.json({
            success: true,
            message: "เข้าสู่ระบบสำเร็จ",
            token,
            user: { id: user._id, username: user.username, email: user.email }
        });
    } catch (err) {
        console.error(err);
        res.status(500).json({ success: false, message: "เกิดข้อผิดพลาดในเซิร์ฟเวอร์" });
    }
});

// เริ่มเซิร์ฟเวอร์
app.listen(PORT, () => console.log(`✅ เซิร์ฟเวอร์ทำงานที่ http://localhost:${PORT}`));
