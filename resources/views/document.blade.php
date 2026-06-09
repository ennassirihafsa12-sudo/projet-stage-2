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
            line-height: 1.6;
        }

        .table-full {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .header-table td {
            vertical-align: top;
            padding: 0 6px;
        }

        .header-title {
            margin: 0 0 3px;
            font-size: 12px;
            font-weight: bold;
        }

        .tifinagh {
            font-family: 'Noto Sans Tifinagh', sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }

        .crest-cell {
            text-align: center;
        }

        .crest-box {
            width: 90px;
            height: 90px;
            display: inline-block;
        }

        .date-line {
            display: inline-block;
            padding-bottom: 2px;
            border-bottom: 1px solid #bbb;
            font-size: 12px;
            color: #111827;
            margin-bottom: 14px;
        }

        .receiver-box {
            display: inline-block;
            width: 100%;
            padding: 12px 16px;
            margin: 14px 0;
            font-size: 13px;
            line-height: 1.55;
            text-align: center;
        }

        .receiver-box span {
            display: block;
            margin-bottom: 4px;
        }

        .subject-box {
            padding: 12px 14px;
            margin-bottom: 16px;
            font-size: 13px;
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
            font-weight: bold;
            margin: 0 4px;
        }

        .underline {
            text-decoration: underline;
            text-decoration-color: #c00;
            text-decoration-thickness: 1.6px;
            text-decoration-skip-ink: none;
        }

        .greeting {
            text-align: center;
            margin: 18px 0;
            font-weight: bold;
            font-size: 14px;
        }

        .body-text {
            margin: 0 0 18px;
            text-align: right;
            font-size: 13px;
            line-height: 1.7;
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
    <table class="table-full header-table">
        <tr>
            <td style="width: 32%; text-align:left;">
                <p class="header-title tifinagh">ⵜⴰⵎⵇⴰⵔⵜ ⵏ ⵍⵎⵖⵔⵉⴱ</p>
                <p class="header-title tifinagh">ⵜⴰⵎⵉⵙⵜⵔ ⵏ ⵢⴰⵢⵖⵓⵔ</p>
                <p class="header-title tifinagh">ⵜⴰⴹⵢⵉⵏⵜ ⵏ ⴰⵢⵢⵍⴰⵏ ⵏ ⵏⴰⵢⵉ</p>
            </td>
            <td class="crest-cell" style="width: 36%;">
                <div class="crest-box">
                    <img src="{{ App\Http\Controllers\LettreController::pdfLogoSrc() }}" alt="Logo" style="height: 90px; width: auto; display: inline-block;">
                </div>
            </td>
            <td style="width: 32%; text-align:right;">
                <p class="header-title">{!! $arabic('المملكة المغربية') !!}</p>
                <p class="header-title">{!! $arabic('وزارة العدل') !!}</p>
                <p class="header-title">{!! $arabic('المديرية الإقليمية بورزازات') !!}</p>
            </td>
        </tr>
    </table>

    <div class="date-line">{!! $arabic('ورزازات في:') !!} {{ $dateLine }}</div>

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
