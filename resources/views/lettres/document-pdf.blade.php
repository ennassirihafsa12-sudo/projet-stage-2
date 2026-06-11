<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>وثيقة رسمية</title>
    @php
        use App\Http\Controllers\LettreController;
        use App\Enums\LettreType;

        $arabic = fn (string $text): string => LettreController::shape($text);

        $isAcceptation = $type === LettreType::Acceptation
            || $type === LettreType::Acceptation->value
            || $type === 'acceptation';

        $dateVal       = $header['date'] ?? now()->format('Y-m-d');
        $timeVal       = $header['meeting_time'] ?? '10:00';
        $entrepriseVal = $entreprise ?: ($marche?->entreprise ?? 'STE ASWAK MACRO NEGOCE S.A.R.L');
        $numeroMarche  = $marche?->numero ?? '2025/01';

        $months = [
            1=>'يناير', 2=>'فبراير', 3=>'مارس',   4=>'أبريل',
            5=>'ماي',   6=>'يونيو',  7=>'يوليوز', 8=>'غشت',
            9=>'شتنبر', 10=>'أكتوبر',11=>'نونبر', 12=>'دجنبر',
        ];

        try {
            $dt         = \Carbon\Carbon::parse($dateVal);
            $dateArabic = $dt->day . ' ' . $months[$dt->month] . ' ' . $dt->year;
        } catch (\Exception $e) {
            $dateArabic = $dateVal;
        }

        [$h]     = explode(':', $timeVal);
        $periode = (int)$h < 12 ? 'صباحا' : 'مساء';

        $dateLineText = 'ورزازات في: ' . $dateArabic;

        if ($isAcceptation) {
            $bodyText     = 'وبعد، علاقة بالموضوع المشار إليه أعلاه، يشرفني ان اخبركم أن لجنة طلب العروض المجتمعة يوم '
                . $dateArabic
                . ' على الساعة '
                . $timeVal
                . ' ' . $periode
                . '، في قاعة الاجتماعات بالمديرية الإقليمية للعدل بورزازات، قد قبلت العرض المالي الذي تقدمتم الله في انتظار المصادقة عليه من طرف السلطات المختصة لذا يتعين عليكم الاتصال بنا قصد تتميم الإجراءات الإدارية المتعلقة بملفكم.';
            $followUpText = '';
        } else {
            $bodyText     = 'وبعد، علاقة بالموضوع المشار إليه أعلاه، يؤسفنا ان اخبركم أن لجنة طلب العروض المجتمعة يوم '
                . $dateArabic
                . ' على الساعة '
                . $timeVal
                . ' ' . $periode
                . '، في قاعة الاجتماعات بالمديرية الإقليمية للعدل بورزازات، لم تقبل العرض المالي الذي تقدمتم به.';
            $followUpText = 'نشكركم على اهتمامكم بطلب العروض هذا.';
        }
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
            padding: 0 6px;
        }
        p {
            margin: 3px 0;
        }
    </style>
</head>
<body>

    {{-- ═══ EN-TÊTE ═══ --}}
    <table style="margin-bottom: 8px;">
        <tr>
            <td style="width: 33%; text-align: left; direction: ltr;">
                <p style="font-weight: bold; font-size: 10px; margin: 2px 0;">ⵜⴰⵎⵇⴰⵔⵜ ⵏ ⵍⵎⵖⵔⵉⴱ</p>
                <p style="font-weight: bold; font-size: 10px; margin: 2px 0;">ⵜⴰⵎⵉⵙⵜⵔ ⵏ ⵢⴰⵢⵖⵓⵔ</p>
                <p style="font-size: 9px; margin: 2px 0;">ⵜⴰⴹⵢⵉⵏⵜ ⵏ ⴰⵢⵢⵍⴰⵏ ⵏ ⵏⴰⵢⵉ</p>
            </td>
            <td style="width: 34%; text-align: center;">
                <img src="{{ LettreController::pdfLogoSrc() }}" alt="Logo" style="height: 70px; width: auto;">
            </td>
            <td style="width: 33%; text-align: right;">
                <p style="font-weight: bold; font-size: 12px; margin: 2px 0;">{!! $arabic('المملكة المغربية') !!}</p>
                <p style="font-weight: bold; font-size: 12px; margin: 2px 0;">{!! $arabic('وزارة العدل') !!}</p>
                <p style="font-weight: bold; font-size: 12px; margin: 2px 0;">{!! $arabic('المديرية الإقليمية بورزازات') !!}</p>
            </td>
        </tr>
    </table>

    {{-- ═══ DATE ═══ --}}
    <div style="margin: 10px 0 18px; text-align: right;">
        <span style="display: inline-block; border-bottom: 1px solid #111; padding-bottom: 2px; font-size: 12px;">
            {!! $arabic($dateLineText) !!}
        </span>
    </div>

    {{-- ═══ DESTINATAIRE ═══ --}}
    <div style="text-align: center; margin-bottom: 20px;">
        <p style="font-size: 13px; font-weight: bold; margin: 5px 0;">
            {!! $arabic('المديرية الإقليمية للعدل بورزازات') !!}
        </p>
        <p style="font-size: 13px; font-weight: bold; margin: 5px 0;">
            {!! $arabic('إلى السيد مدير شركة') !!}
        </p>
        <p style="font-size: 14px; font-weight: bold; margin: 5px 0; direction: ltr;">
            {{ $entrepriseVal }}
        </p>
        <p style="font-size: 12px; margin: 5px 0; direction: ltr;">
            Nº 28 Rue Al Kawakibi-Cité Dakhla Agadir.
        </p>
    </div>

    {{-- ═══ SUJET ═══ --}}
    <div style="margin-bottom: 16px; font-size: 13px; line-height: 1.7; text-align: right;">
        <span style="font-weight: bold;">{!! $arabic('الموضوع') !!}</span>
        {!! $arabic('اخبار مخصوص طلب العروض المحتوم المبسطة رقم') !!}
        <span style="direction: ltr; unicode-bidi: embed;">{{ $numeroMarche }}</span>
        {!! $arabic('الخاص بشراء التوريدات الاستهلاكية المعلوماتية اللازية السير مصالح الدائرة القضائية بورزازات حصة') !!}
    </div>

    {{-- ═══ SALUTATION ═══ --}}
    <p style="text-align: center; font-weight: bold; font-size: 14px; margin: 18px 0;">
        {!! $arabic('سلام تام بوجود مولانا الإمام،') !!}
    </p>

    {{-- ═══ CORPS ═══ --}}
    <div style="font-size: 13px; line-height: 1.8; text-align: right; margin-bottom: 16px;">
        <p style="margin-bottom: 10px;">{!! $arabic($bodyText) !!}</p>
        @if ($followUpText)
            <p>{!! $arabic($followUpText) !!}</p>
        @endif
    </div>

    {{-- ═══ CLÔTURE ═══ --}}
    <p style="text-align: center; font-weight: bold; font-size: 13px; margin-top: 24px;">
        {!! $arabic('وتقبلوا سيدي فائق الاحترام والتقدير.') !!}
    </p>

    {{-- ═══ SIGNATURE ═══ --}}
    <p style="text-align: right; font-weight: bold; font-size: 13px; margin-top: 12px;">
        {!! $arabic('والسلام./.') !!}
    </p>

</body>
</html>