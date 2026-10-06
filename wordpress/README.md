# เพิ่มหน้า M-CIN เข้า WordPress บนโฮสต์

คู่มือฉบับออนไลน์: https://claude.ai/code/artifact/74500a5b-5765-4366-96bb-23a5980c7b97

งานนี้ถูกแพ็กเป็น**ปลั๊กอิน WordPress** ไฟล์เดียว (`mcn-landing.zip`) แค่อัปโหลดผ่านหลังบ้าน WordPress แล้วสร้างหน้าใหม่ ไม่ต้องแก้ธีม ไม่ต้องใช้ FTP และหน้าอื่นของเว็บไม่เปลี่ยน

## สรุป: ทำที่ไหนบ้าง

| ที่ไหน | ทำอะไร | ใช้เวลา |
| --- | --- | --- |
| **เครื่อง Mac** (โฟลเดอร์โปรเจค `mcn`) | สร้าง/หาไฟล์ `dist/mcn-landing.zip` | 1 นาที |
| **หลังบ้าน WordPress บนโฮสต์** (`https://โดเมน/wp-admin`) | อัปโหลดปลั๊กอิน → เปิดใช้ → สร้างหน้า → เลือก template | 5–10 นาที |

บนโฮสต์ไม่ต้องพิมพ์คำสั่งหรือแก้โค้ดเลย ทุกอย่างคลิกทำในหลังบ้าน

## ไฟล์ในโฟลเดอร์นี้

| ไฟล์ | หน้าที่ |
| --- | --- |
| `mcn-landing.php` | ตัวปลั๊กอิน: เพิ่ม Page Template "M-CIN Landing", โหลด CSS เฉพาะหน้านี้, ปิด CSS ของธีม |
| `build.sh` | สร้าง `dist/mcn-landing.zip` จาก `index.html` + `style.css` + `assets/` (`dist/` ไม่เข้า git) |

## ส่วนที่ 1: บนเครื่อง Mac

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/mcn
bash wordpress/build.sh
```

จะได้ `dist/mcn-landing.zip` (ประมาณ 9.7 MB) ไฟล์นี้คือสิ่งเดียวที่ต้องเอาขึ้นโฮสต์ **ห้ามแตก zip** เพราะ WordPress รับไฟล์ .zip ตรงๆ

## ส่วนที่ 2: บนหลังบ้าน WordPress ของโฮสต์

ล็อกอินที่ `https://โดเมนจริง/wp-admin` ด้วยบัญชี Administrator แล้วทำตามลำดับ

### 2.1 อัปโหลดและเปิดใช้ปลั๊กอิน

1. เมนูซ้าย **Plugins → Add New Plugin**
2. กดปุ่ม **Upload Plugin** ด้านบน
3. กด Choose File → เลือก `mcn-landing.zip` → กด **Install Now**
4. กด **Activate Plugin**

ถ้าขึ้นว่า *"The uploaded file exceeds the upload_max_filesize"* ให้อัปผ่าน File Manager ของโฮสติ้งแทน:

1. แผงควบคุมโฮสติ้ง (cPanel / Plesk / DirectAdmin) → **File Manager**
2. เข้าโฟลเดอร์ `public_html/wp-content/plugins/` (บางโฮสต์ชื่อ `httpdocs` หรือ `www`)
3. Upload `mcn-landing.zip` → คลิกขวา → **Extract** แล้วลบไฟล์ .zip ทิ้ง
4. หลังบ้าน WP → **Plugins** → M-CIN Landing → **Activate**

### 2.2 สร้างหน้าใหม่

1. **Pages → Add New Page**
2. ชื่อหน้าใส่อะไรก็ได้ เนื้อหาปล่อยว่าง (หน้าตาเว็บมาจากปลั๊กอิน)
3. แถบขวา แท็บ **Page** → **Template** → เลือก **M-CIN Landing**
4. **URL / Slug** → ตั้งตามต้องการ
5. **Publish** → View Page

### 2.3 ตั้งค่า SEO (ถ้าเว็บมี Yoast หรือ Rank Math)

| ช่อง | ค่า |
| --- | --- |
| SEO title | M-CIN ยาสเปรย์ เอ็ม-ซิน บรรเทาปวด บวม อักเสบ เอ็น ข้อ กล้ามเนื้อ |
| Meta description | ยาสเปรย์ เอ็ม-ซิน (M-CIN) ตัวยาอินโดเมทาซิน 8 mg/mL บรรเทาอาการปวด บวม อักเสบ ของเอ็น ข้อ และกล้ามเนื้อ ฉีดพ่นได้ไม่ต้องถูนวด ไม่เหนียวเหนอะหนะ ดูดซึมเร็ว 1 ขวดฉีดได้มากกว่า 300 ครั้ง หาซื้อได้ที่ร้านขายยาชั้นนำใกล้บ้านคุณ |
| รูปตอนแชร์ Facebook/LINE | รูป 1200×630 px อัปเข้า Media Library |

### 2.4 เช็กว่าหน้าใช้งานได้ (เปิดแบบ incognito ทั้งมือถือและคอม)

- [ ] หน้าตาเหมือนที่เปิดบนเครื่อง (`localhost/mcn`)
- [ ] รูปครบ: โลโก้, ไอคอน 4 อัน, ขวดยา, โลโก้ร้าน 11 ร้าน, พื้นหลัง
- [ ] ฟอนต์ไทยเป็น PSL
- [ ] วิดีโอเต็มกรอบ เล่นเองเมื่อเลื่อนถึง
- [ ] ปุ่ม LINE / Facebook กดได้
- [ ] หน้าอื่นของเว็บยังหน้าตาเหมือนเดิม

## ตอนแก้หน้าเว็บทีหลัง

1. แก้บนเครื่อง (`index.html`, `style.css`, `assets/`) แล้วรัน `bash wordpress/build.sh`
2. หลังบ้าน WP: Plugins → Add New Plugin → Upload Plugin → เลือก zip ใหม่ → Install Now → **Replace current with uploaded**
3. ล้าง cache (ถ้ามีปลั๊กอิน cache)

อย่าแก้ไฟล์บนโฮสต์ตรงๆ เพราะตอนอัป zip ครั้งต่อไปจะถูกทับหาย ถ้าแก้ `mcn-landing.php` ให้เพิ่มเลข `Version` ด้วย

## สิ่งที่เจอบนเว็บจริง (mcin.macrophar.com, ธีม Healer) และแก้ไว้แล้ว

| อาการ | สาเหตุ | แก้ที่ |
| --- | --- | --- |
| หน้าชิดซ้าย พื้นหลังขาว | WP ใส่ class `page` ให้ `<body>` ชนกับ `.page` ใน `style.css` | `mcn-landing.php` เอา class `page` ออก ใส่ `mcn-landing` แทน |
| ตัวหนังสือ/ไอคอนเลื่อน | ธีม Healer enqueue CSS ช้ากว่า `wp_enqueue_scripts` | `mcn-landing.php` dequeue ตอน `wp_print_styles` และ `wp_footer` + ปิด Additional CSS ของ Customizer |
| ปุ่ม Facebook/LINE มีป้ายข้อความซ้อน | `.contact-widget` ที่ใส่ไว้ทุกหน้าของเว็บ | `mcn-landing.php` ซ่อน `.contact-widget` และปุ่ม scroll-to-top ของธีมในหน้านี้ |
| วิดีโอมี "Video Player" พื้นดำ ไม่เต็มกรอบ | JS ของธีมครอบ `<video>` ด้วย MediaElement | `build.sh` ใส่ `class="inited"` ให้วิดีโอ ธีมจะข้าม |

## ถ้าเจอปัญหา

| อาการ | วิธีแก้ |
| --- | --- |
| อัป zip ไม่ผ่าน เพราะไฟล์ใหญ่เกิน | ใช้ File Manager ตามหัวข้อ 2.1 หรือขอให้โฮสติ้งเพิ่ม upload_max_filesize เป็น 20 MB ขึ้นไป |
| ไม่เห็น M-CIN Landing ในตัวเลือก Template | เช็กว่ากด Activate ปลั๊กอินแล้ว แล้วรีเฟรชหน้า editor |
| เปิดหน้าแล้วเจอ 404 | Settings → Permalinks → กด Save Changes หนึ่งครั้ง |
| หน้าขึ้นแต่ไม่มีสไตล์ หรือยังเป็นหน้าตาธีมเดิม | เช็กว่าเลือก Template เป็น M-CIN Landing แล้วกด Update แล้ว จากนั้นล้าง cache |
| ตอนล็อกอินมีแถบดำด้านบน | แถบ admin ของ WordPress เห็นเฉพาะคนที่ล็อกอิน ไม่ต้องแก้ |
| อยากเอาหน้าออก | Pages → ลบหน้า แล้ว Plugins → Deactivate → Delete เว็บส่วนอื่นไม่กระทบ |
