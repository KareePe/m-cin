#!/bin/bash
# สร้าง dist/mcn-landing.zip จาก index.html + style.css + assets/ สำหรับอัปโหลดเป็นปลั๊กอิน WordPress
# ใช้: bash wordpress/build.sh  (รันซ้ำได้ทุกครั้งที่แก้หน้าเว็บ)
set -e
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
OUT="$ROOT/dist/mcn-landing"
rm -rf "$ROOT/dist"
mkdir -p "$OUT"

cp "$ROOT/wordpress/mcn-landing.php" "$OUT/"
cp "$ROOT/style.css" "$OUT/mcn.css"
cp -R "$ROOT/assets" "$OUT/"
find "$OUT" -name .DS_Store -delete

T="$OUT/page-mcn.php"
cat > "$T" <<'EOF'
<?php
defined( 'ABSPATH' ) || exit;
$mcn = esc_url( plugins_url( 'assets', __FILE__ ) );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#0d5a4a">
  <?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
  <title>M-CIN ยาสเปรย์ เอ็ม-ซิน บรรเทาปวด บวม อักเสบ เอ็น ข้อ กล้ามเนื้อ</title>
  <?php endif; ?>

  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": ["Product", "Drug"],
    "name": "M-CIN ยาสเปรย์ เอ็ม-ซิน (Indometacin 8 mg/mL)",
    "alternateName": ["เอ็ม-ซิน", "M-CIN Spray"],
    "brand": { "@type": "Brand", "name": "M-CIN" },
    "image": "<?php echo $mcn; ?>/img/product-bottle.png",
    "description": "ยาสเปรย์ตัวยาอินโดเมทาซิน 8 mg/mL บรรเทาอาการปวด บวม อักเสบ ของเอ็น ข้อ และกล้ามเนื้อ ฉีดพ่นได้ไม่ต้องถูนวด ไม่เหนียวเหนอะหนะ ดูดซึมเร็ว 1 ขวดฉีดได้มากกว่า 300 ครั้ง",
    "activeIngredient": "Indometacin 8 mg/mL"
  }
  </script>

  <link rel="preload" as="image" href="<?php echo $mcn; ?>/img/mcin-hero.svg">
  <link rel="preload" as="image" href="<?php echo $mcn; ?>/img/bg-daily.jpg">
  <?php wp_head(); ?>
</head>
<body <?php body_class( 'is-loading' ); ?>>
<?php wp_body_open(); ?>
EOF
# เนื้อหาระหว่าง <body> กับ </body> ของ index.html โดยเปลี่ยน "assets/ เป็น path ในปลั๊กอิน
sed -n '/^<body/,/^<\/body>/p' "$ROOT/index.html" | sed '1d;$d' \
  | sed 's#"assets/#"<?php echo $mcn; ?>/#g' \
  | sed 's#<video id="salepage-video"#<video id="salepage-video" class="inited"#' >> "$T"
# class "inited" กันธีม (เช่น Healer) เอา MediaElement player มาครอบวิดีโอ
printf '<?php wp_footer(); ?>\n</body>\n</html>\n' >> "$T"

if grep -q '"assets/' "$T"; then echo "ERROR: ยังมี path assets/ ที่ไม่ได้แปลง" >&2; exit 1; fi

(cd "$ROOT/dist" && zip -qr mcn-landing.zip mcn-landing)
echo "เสร็จ: $ROOT/dist/mcn-landing.zip ($(du -h "$ROOT/dist/mcn-landing.zip" | cut -f1))"
