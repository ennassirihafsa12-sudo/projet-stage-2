<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>وثيقة رسمية</title>
    @php
        use App\Http\Controllers\LettreController;
        $arabic = fn (string $text): string => LettreController::shape($text);
    @endphp
    <style>
        @page {
            size: A4;
            margin: 15mm 12mm;
        }

        body {
            margin: 0;
            padding: 0;
            direction: rtl;
            text-align: right;
            font-family: 'DejaVu Sans', sans-serif;
            color: #111827;
            font-size: 13px;
            line-height: 1.55;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
        }

        .header-table td {
            padding: 0 6px;
        }

        .header-title {
            margin: 0 0 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .tifinagh {
            font-family: 'Noto Sans Tifinagh', sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }

        .crest {
            text-align: center;
        }

        .crest img {
            height: 82px;
            width: auto;
            display: inline-block;
        }

        .date-line {
            display: inline-block;
            padding-bottom: 3px;
            border-bottom: 1px solid #111;
            font-size: 12px;
            margin: 10px 0 18px;
        }

        .receiver-box {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 18px;
            text-align: center;
            font-size: 13px;
            line-height: 1.5;
        }

        .receiver-box span {
            display: block;
            margin-bottom: 4px;
        }

        .subject-box {
            padding: 12px 14px;
            margin-bottom: 16px;
            font-size: 13px;
            line-height: 1.5;
        }

        .subject-title {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .tender-number {
            display: inline-block;
            padding: 1px 8px;
            margin: 0 4px;
            font-weight: bold;
        }

        .underline {
            text-decoration: underline;
            text-decoration-color: #111;
            text-decoration-thickness: 1px;
            text-decoration-skip-ink: none;
        }

        .greeting {
            text-align: center;
            margin: 18px 0 16px;
            font-weight: bold;
            font-size: 14px;
        }

        .body-text {
            margin: 0;
            font-size: 13px;
            line-height: 1.7;
            text-align: right;
        }

        .footer {
            text-align: center;
            font-weight: bold;
            margin-top: 24px;
            font-size: 13px;
        }

        .signoff {
            text-align: left;
            margin-top: 12px;
            font-weight: bold;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 32%; text-align: left;">
                <p class="header-title tifinagh">ⵜⴰⵎⵇⴰⵔⵜ ⵏ ⵍⵎⵖⵔⵉⴱ</p>
                <p class="header-title tifinagh">ⵜⴰⵎⵉⵙⵜⵔ ⵏ ⵢⴰⵢⵖⵓⵔ</p>
                <p class="header-title tifinagh">ⵜⴰⴹⵢⵉⵏⵜ ⵏ ⴰⵢⵢⵍⴰⵏ ⵏ ⵏⴰⵢⵉ</p>
            </td>
            <td class="crest" style="width: 36%;">
                <img src="{{ App\Http\Controllers\LettreController::pdfLogoSrc() }}" alt="Logo">
            </td>
            <td style="width: 32%; text-align: right;">
                <p class="header-title">{!! $arabic('المملكة المغربية') !!}</p>
                <p class="header-title">{!! $arabic('وزارة العدل') !!}</p>
                <p class="header-title">{!! $arabic('المديرية الإقليمية بورزازات') !!}</p>
            </td>
        </tr>
    </table>

    <div class="date-line">{!! $arabic('ورزازات في:') !!} {{ $header['date'] ?? '10 مارس 2025' }}</div>

    <div class="receiver-box">
        <span>{!! $arabic('المديرية الإقليمية للعدل بورزازات') !!}</span>
        <span>{!! $arabic('إلى السيد مدير شركة') !!}</span>
        <span>{{ $entreprise }}</span>
        <span>N° 28 Rue Al Kawakibi-Cité Dakhla Agadir.</span>
    </div>

    <div class="subject-box">
        <span class="subject-title">{!! $arabic('الموضوع:') !!}</span>
        <div>{!! $arabic('اخبار مخصوص طلب العروض المحتوم المبسطة رقم 2025/01 الخاص بشراء التوريدات الاستهلاكية المعلوماتية اللازية السير مصالح الدائرة القضائية بورزازات حصة') !!}</div>
    </div>

    <p class="greeting">{!! $arabic('سلام تام بوجود مولانا الإمام،') !!}</p>

    <p class="body-text">{!! $arabic('2025 على الساعة العاشرة صباحا، في قاعة الاجتماعات بالمديرية الإقليمية للعدل بورزازات، قد قبلت العرض المالي الذي تقدمتم الله في انتظار المصادقة عليه من طرف السلطات المختصة لذا يتعين عليكم الاتصال بنا قصد تتميم الإجراءات الإدارية المتعلقة بملفكم') !!}</p>

    <p class="footer">{!! $arabic('وتقبلوا سيدي فائق الاحترام والتقدير') !!}</p>
    <p class="signoff">{!! $arabic('والسلام ./.') !!}</p>
</body>
</html>
