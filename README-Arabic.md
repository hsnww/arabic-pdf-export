# حزمة تصدير PDF العربي

حزمة Laravel شاملة لإنشاء ملفات PDF مع دعم كامل للنص العربي، بما في ذلك اتجاه النص من اليمين إلى اليسار (RTL) وعرض الخطوط العربية.

## المميزات

- **دعم كامل للنص العربي**: اتجاه النص الصحيح من اليمين إلى اليسار وعرض الخطوط العربية
- **محركات PDF متعددة**: دعم لمحركي TCPDF و DomPDF
- **خطوط عربية مدمجة**: عائلات خطوط أميري والقاهرة بأوزان متعددة
- **دعم النص القرآني**: تنسيق خاص للنص القرآني
- **تكامل Laravel**: تكامل سهل مع تطبيقات Laravel
- **توجيهات Blade**: توجيهات Blade مخصصة لإنشاء PDF
- **تكوين مرن**: خيارات تكوين شاملة
- **أوامر Console**: أوامر Artisan لتثبيت الخطوط

## التثبيت

1. تثبيت الحزمة عبر Composer:

```bash
composer require arabic-pdf-export/arabic-pdf-export
```

2. نشر ملف التكوين:

```bash
php artisan vendor:publish --provider="ArabicPdfExport\ServiceProvider" --tag="config"
```

3. تثبيت الخطوط العربية في المجلد العام:

```bash
php artisan arabic-pdf:install-fonts
```

## التكوين

يقع تكوين الحزمة في `config/arabic-pdf.php`. يمكنك تخصيص:

- محرك PDF (TCPDF أو DomPDF)
- تنسيق الصفحة والاتجاه
- الهوامش
- إعدادات الخطوط
- فئات CSS الافتراضية

## الاستخدام

### الاستخدام الأساسي

```php
use ArabicPdfExport\Facades\ArabicPdf;

// إنشاء مستند عربي بسيط
$html = '<div class="arabic-title">عنوان المستند</div>
         <div class="arabic-paragraph">هذا نص باللغة العربية</div>';

$pdf = ArabicPdf::generate($html);

// تحميل PDF
ArabicPdf::download($html, 'document.pdf');

// حفظ في ملف
ArabicPdf::save($html, storage_path('app/document.pdf'));
```

### استخدام Service Container

```php
use ArabicPdfExport\ArabicPdfService;

$arabicPdf = app('arabic-pdf');

$html = '<div class="arabic-text">مرحباً بالعالم</div>';
$pdf = $arabicPdf->generate($html);
```

### توجيهات Blade

```blade
{{-- إنشاء محتوى PDF --}}
@arabicPdf('<div class="arabic-title">عنوان</div><div class="arabic-text">محتوى</div>')

{{-- تحميل PDF --}}
@arabicPdfDownload('<div class="arabic-text">محتوى للتحميل</div>', 'document.pdf')

{{-- حفظ PDF --}}
@arabicPdfSave('<div class="arabic-text">محتوى للحفظ</div>', storage_path('app/saved.pdf'))
```

### إنشاء المستندات

#### مستند بسيط

```php
$pdf = ArabicPdf::createSimpleDocument(
    'عنوان المستند',
    'هذا محتوى المستند باللغة العربية',
    ['title' => 'My Document']
);
```

#### مستند قرآني

```php
$pdf = ArabicPdf::createQuranDocument(
    'البقرة',
    '255',
    'اللَّهُ لَا إِلَٰهَ إِلَّا هُوَ الْحَيُّ الْقَيُّومُ',
    ['title' => 'آية الكرسي']
);
```

### HTML مخصص مع فئات CSS

```php
$html = '
<div class="arabic-title">عنوان رئيسي</div>
<div class="arabic-subtitle">عنوان فرعي</div>
<div class="arabic-paragraph">هذه فقرة عربية مع محاذاة صحيحة للنص.</div>
<div class="quran-text">نص قرآني بخط خاص</div>
<div class="arabic-text">نص عادي باللغة العربية</div>
';

$pdf = ArabicPdf::generate($html);
```

### خيارات التكوين

```php
// تعيين تكوين مخصص
ArabicPdf::setConfig([
    'engine' => 'tcpdf',
    'page_format' => 'A4',
    'orientation' => 'P',
    'margin_top' => 20,
    'default_font' => 'Cairo-Regular'
]);

// الحصول على التكوين الحالي
$config = ArabicPdf::getConfig();

// تعيين الخط الافتراضي
ArabicPdf::setDefaultFont('Amiri-Bold');

// الحصول على الخطوط المتاحة
$fonts = ArabicPdf::getAvailableFonts();
```

## الخطوط المتاحة

تتضمن الحزمة الخطوط العربية التالية:

- **عائلة أميري**:
  - Amiri-Regular
  - Amiri-Bold
  - Amiri-Italic
  - Amiri-BoldItalic
  - AmiriQuran (للنص القرآني)
  - AmiriQuranColored (النص القرآني الملون)

- **عائلة القاهرة**:
  - Cairo-Regular
  - Cairo-Bold

## فئات CSS

توفر الحزمة فئات CSS محددة مسبقاً للنص العربي:

- `.arabic-title`: للعناوين الرئيسية
- `.arabic-subtitle`: للعناوين الفرعية
- `.arabic-paragraph`: للفقرات العادية
- `.arabic-text`: للنص العربي العام
- `.quran-text`: للنص القرآني

## الاستخدام المتقدم

### محرك PDF مخصص

```php
// استخدام محرك TCPDF
$pdf = ArabicPdf::generateWithTcpdf($html);

// استخدام محرك DomPDF
$pdf = ArabicPdf::generateWithDompdf($html);
```

### تكوين مخصص

```php
$arabicPdf = new ArabicPdfService([
    'engine' => 'tcpdf',
    'page_format' => 'A4',
    'orientation' => 'L', // عرضي
    'margin_top' => 25,
    'margin_right' => 20,
    'margin_bottom' => 25,
    'margin_left' => 20,
    'default_font' => 'Cairo-Bold'
]);
```

## المتطلبات

- PHP 8.0 أو أعلى
- Laravel 9.0 أو أعلى
- TCPDF 6.4 أو أعلى
- DomPDF 2.0 أو أعلى

## الترخيص

هذه الحزمة برنامج مفتوح المصدر مرخص تحت [رخصة MIT](https://opensource.org/licenses/MIT).

## المساهمة

المساهمات مرحب بها! يرجى إرسال Pull Request.

## الدعم

للحصول على الدعم، يرجى فتح issue في مستودع GitHub.

## سجل التغييرات

### الإصدار 1.0.0
- الإصدار الأول
- دعم النص العربي مع اتجاه RTL
- محركات PDF متعددة (TCPDF، DomPDF)
- خطوط عربية مدمجة
- تكامل Laravel
- توجيهات Blade
- أوامر Console

## أمثلة الاستخدام

### مثال 1: مستند عربي بسيط

```php
use ArabicPdfExport\Facades\ArabicPdf;

$html = '
<div class="arabic-title">تقرير المبيعات</div>
<div class="arabic-subtitle">الربع الأول 2024</div>
<div class="arabic-paragraph">
    هذا تقرير شامل عن أداء المبيعات في الربع الأول من عام 2024.
    تم تحقيق نمو ملحوظ في جميع المؤشرات الرئيسية.
</div>
<div class="arabic-paragraph">
    النتائج تشير إلى نجاح استراتيجية التسويق الجديدة
    ورضا العملاء عن الخدمات المقدمة.
</div>
';

$pdf = ArabicPdf::generate($html);
$pdf->Output('sales-report.pdf', 'D'); // تحميل
```

### مثال 2: مستند قرآني

```php
$html = '
<div class="arabic-title">سورة الفاتحة</div>
<div class="quran-text">
    بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ
    الْحَمْدُ لِلَّهِ رَبِّ الْعَالَمِينَ
    الرَّحْمَٰنِ الرَّحِيمِ
    مَالِكِ يَوْمِ الدِّينِ
    إِيَّاكَ نَعْبُدُ وَإِيَّاكَ نَسْتَعِينُ
    اهْدِنَا الصِّرَاطَ الْمُسْتَقِيمَ
    صِرَاطَ الَّذِينَ أَنْعَمْتَ عَلَيْهِمْ غَيْرِ الْمَغْضُوبِ عَلَيْهِمْ وَلَا الضَّالِّينَ
</div>
';

$pdf = ArabicPdf::generate($html);
$pdf->Output('al-fatiha.pdf', 'D');
```

### مثال 3: فاتورة عربية

```php
$html = '
<div class="arabic-title">فاتورة</div>
<div class="arabic-subtitle">رقم الفاتورة: INV-2024-001</div>
<div class="arabic-text">
    <p><strong>العميل:</strong> شركة التقنية المتقدمة</p>
    <p><strong>التاريخ:</strong> ' . date('Y-m-d') . '</p>
    <p><strong>المبلغ الإجمالي:</strong> 1,500.00 ريال</p>
</div>
<div class="arabic-paragraph">
    شكراً لاختياركم خدماتنا. نقدّر ثقتكم فينا.
</div>
';

$pdf = ArabicPdf::generate($html);
$pdf->Output('invoice.pdf', 'D');
```
