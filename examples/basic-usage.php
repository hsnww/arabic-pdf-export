<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ArabicPdfExport\ArabicPdfService;

// Create a new instance
$arabicPdf = new ArabicPdfService();

// Example 1: Simple Arabic document
$html1 = '
<div class="arabic-title">مثال على المستند العربي</div>
<div class="arabic-paragraph">
    هذا مثال على مستند PDF باللغة العربية. النص يظهر بالاتجاه الصحيح من اليمين إلى اليسار.
</div>
<div class="arabic-subtitle">عنوان فرعي</div>
<div class="arabic-paragraph">
    يمكن استخدام هذا الحزمة لإنشاء مستندات PDF مع دعم كامل للنص العربي.
</div>
';

// Generate PDF
$pdf1 = $arabicPdf->generate($html1);
$pdf1->Output('arabic-document.pdf', 'D'); // Download

// Example 2: Quran verse
$html2 = '
<div class="arabic-title">سورة البقرة - الآية 255</div>
<div class="quran-text">
    اللَّهُ لَا إِلَٰهَ إِلَّا هُوَ الْحَيُّ الْقَيُّومُ ۚ لَا تَأْخُذُهُ سِنَةٌ وَلَا نَوْمٌ ۚ لَّهُ مَا فِي السَّمَاوَاتِ وَمَا فِي الْأَرْضِ ۗ مَن ذَا الَّذِي يَشْفَعُ عِندَهُ إِلَّا بِإِذْنِهِ ۚ يَعْلَمُ مَا بَيْنَ أَيْدِيهِمْ وَمَا خَلْفَهُمْ ۖ وَلَا يُحِيطُونَ بِشَيْءٍ مِّنْ عِلْمِهِ إِلَّا بِمَا شَاءَ ۚ وَسِعَ كُرْسِيُّهُ السَّمَاوَاتِ وَالْأَرْضَ ۖ وَلَا يَئُودُهُ حِفْظُهُمَا ۚ وَهُوَ الْعَلِيُّ الْعَظِيمُ
</div>
';

// Generate Quran PDF
$pdf2 = $arabicPdf->generate($html2);
$pdf2->Output('quran-verse.pdf', 'D'); // Download

// Example 3: Using the simple document method
$pdf3 = $arabicPdf->createSimpleDocument(
    'عنوان المستند',
    'هذا محتوى المستند باللغة العربية مع دعم كامل للنص العربي.',
    ['title' => 'Simple Arabic Document']
);
$pdf3->Output('simple-document.pdf', 'D'); // Download

// Example 4: Using the Quran document method
$pdf4 = $arabicPdf->createQuranDocument(
    'البقرة',
    '255',
    'اللَّهُ لَا إِلَٰهَ إِلَّا هُوَ الْحَيُّ الْقَيُّومُ',
    ['title' => 'آية الكرسي']
);
$pdf4->Output('quran-ayat-kursi.pdf', 'D'); // Download

echo "PDF examples generated successfully!\n";
