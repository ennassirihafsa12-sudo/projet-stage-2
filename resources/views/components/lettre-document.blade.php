@props([
    'marche',
    'type',
    'entreprise',
    'header' => [],
    'prefix' => 'main',
    'id' => null,
    'editable' => true,
    'forPdf' => false,
    'logoSrc' => null,
])

@php
    use App\Enums\LettreType;
    use App\Http\Controllers\LettreController;

    $isAcceptation = $type === LettreType::Acceptation
        || $type === LettreType::Acceptation->value
        || $type === 'acceptation';

    $dateVal    = $header['date'] ?? now()->format('Y-m-d');
    $timeVal    = $header['meeting_time'] ?? '10:00';
    $subjectVal = $header['subject'] ?? ($marche?->objet ?? '');
    $entrepriseVal = $entreprise ?: ($marche?->entreprise ?? 'STE ASWAK MACRO NEGOCE S.A.R.L');
    $fieldPrefix   = $prefix;
    $logoUrl = $logoSrc ?: ($forPdf
        ? App\Http\Controllers\LettreController::pdfLogoSrc()
        : asset('images/logo.png'));

    $arabic = fn (string $text): string => LettreController::shape($text);

    // Corps acceptation — date et heure dynamiques
    $acceptationBodyChunks = [
        'وبعد، علاقة بالموضوع المشار إليه أعلاه،',
        'يشرفني ان اخبركم',
        'أن لجنة طلب العروض المجتمعة',
        "يوم {$dateVal}",
        "على الساعة {$timeVal} صباحا،",
        'في قاعة الاجتماعات',
        'بالمديرية الإقليمية للعدل بورزازات،',
        'قد قبلت العرض المالي',
        'الذي تقدمتم به',
        'في انتظار المصادقة عليه',
        'من طرف السلطات المختصة.',
    ];
    $acceptationFollowUp = 'لذا يتعين عليكم الاتصال بنا قصد تتميم الإجراءات الإدارية المتعلقة بملفكم.';

    $refusBodyChunks = [
        'وبعد، علاقة بالموضوع المشار إليه أعلاه،',
        'يؤسفنا ان اخبركم',
        'أن لجنة طلب العروض المجتمعة',
        "يوم {$dateVal}",
        "على الساعة {$timeVal} صباحا،",
        'في قاعة الاجتماعات',
        'بالمديرية الإقليمية للعدل بورزازات،',
        'لم تقبل العرض المالي الذي تقدمتم به.',
    ];
    $refusFollowUp = 'نشكركم على اهتمامكم بطلب العروض هذا.';
@endphp

<div dir="rtl" @if($id) id="{{ $id }}" @endif
     class="lettre-document bg-white {{ $forPdf ? '' : 'p-6' }}"
     style="font-family: {{ $forPdf ? "'DejaVu Sans', sans-serif" : "'Segoe UI', Tahoma, sans-serif" }}; direction: rtl; text-align: right;">

    {{-- ═══ EN-TÊTE ═══ --}}
    <table style="width: 100%; border-collapse: collapse; margin-bottom: {{ $forPdf ? '8px' : '16px' }}; {{ $forPdf ? '' : 'border-bottom: 1px solid #1a2744;' }}">
        <tr>
            {{-- Tifinagh (gauche) --}}
            <td style="width: 33%; text-align: left; padding: 0 6px; direction: ltr; vertical-align: top;">
                @if ($forPdf)
                    {{-- Pour le PDF on utilise DejaVu qui ne supporte pas Tifinagh → on affiche en latin --}}
                    <p style="font-weight: bold; font-size: 10px; margin: 2px 0; color: #111827;">ⵜⴰⵎⵇⴰⵔⵜ ⵏ ⵍⵎⵖⵔⵉⴱ</p>
                    <p style="font-weight: bold; font-size: 10px; margin: 2px 0; color: #111827;">ⵜⴰⵎⵉⵙⵜⵔ ⵏ ⵢⴰⵢⵖⵓⵔ</p>
                    <p style="font-size: 9px; margin: 2px 0; color: #111827;">ⵜⴰⴹⵢⵉⵏⵜ ⵏ ⴰⵢⵢⵍⴰⵏ ⵏ ⵏⴰⵢⵉ</p>
                @else
                    <p style="font-weight: bold; font-size: 13px; margin: 2px 0; color: #111827;">ⵜⴰⵎⵇⴰⵔⵜ ⵏ ⵍⵎⵖⵔⵉⴱ</p>
                    <p style="font-weight: bold; font-size: 13px; margin: 2px 0; color: #111827;">ⵜⴰⵎⵉⵙⵜⵔ ⵏ ⵢⴰⵢⵖⵓⵔ</p>
                    <p style="font-size: 12px; margin: 2px 0; color: #111827;">ⵜⴰⴹⵢⵉⵏⵜ ⵏ ⴰⵢⵢⵍⴰⵏ ⵏ ⵏⴰⵢⵉ</p>
                @endif
            </td>

            {{-- Logo (centre) --}}
            <td style="width: 34%; text-align: center; padding: 0 6px; vertical-align: top;">
                <img src="{{ $logoUrl }}" alt="Logo" style="height: {{ $forPdf ? '70px' : '80px' }}; width: auto; object-fit: contain;">
            </td>

            {{-- Arabe (droite) --}}
            <td style="width: 33%; text-align: right; padding: 0 6px; vertical-align: top;">
                <p style="font-weight: bold; font-size: {{ $forPdf ? '11px' : '14px' }}; margin: 2px 0; color: #111827;">{!! $arabic('المملكة المغربية') !!}</p>
                <p style="font-weight: bold; font-size: {{ $forPdf ? '11px' : '14px' }}; margin: 2px 0; color: #111827;">{!! $arabic('وزارة العدل') !!}</p>
                <p style="font-weight: bold; font-size: {{ $forPdf ? '11px' : '14px' }}; margin: 2px 0; color: #111827;">{!! $arabic('المديرية الإقليمية بورزازات') !!}</p>
            </td>
        </tr>
    </table>

    {{-- ═══ DATE ═══ --}}
    <div style="margin: {{ $forPdf ? '8px 0 16px' : '10px 0 20px' }};">
        @if ($editable)
            <span style="font-size: 13px; font-weight: bold; color: #111827; border-bottom: 1px solid #111; padding-bottom: 2px;">
                {!! $arabic('ورزازات في:') !!}
                <input type="text"
                       id="header_date"
                       name="header_date"
                       data-sync-field="header_date"
                       value="{{ $dateVal }}"
                       style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px; padding: 2px 8px; font-size: 13px; width: 120px; margin-right: 4px; direction: ltr;">
            </span>
        @else
            <span style="font-size: {{ $forPdf ? '12px' : '13px' }}; border-bottom: 1px solid #111; padding-bottom: 2px; display: inline-block;">
                {!! $arabic('ورزازات في:') !!} {{ $dateVal }}
            </span>
        @endif
    </div>

    {{-- ═══ DESTINATAIRE ═══ --}}
    <div style="text-align: center; margin-bottom: {{ $forPdf ? '20px' : '28px' }}; padding: {{ $forPdf ? '10px' : '15px' }};">
        <p style="font-size: {{ $forPdf ? '13px' : '16px' }}; font-weight: bold; color: #111827; margin: 4px 0;">
            {!! $arabic('المديرية الإقليمية للعدل بورزازات') !!}
        </p>
        <p style="font-size: {{ $forPdf ? '13px' : '16px' }}; font-weight: bold; color: #111827; margin: 4px 0;">
            {!! $arabic('إلى السيد مدير شركة') !!}
        </p>
        @if ($editable)
            <input type="text"
                   name="{{ $fieldPrefix }}_entreprise"
                   data-sync-field="entreprise"
                   value="{{ $entrepriseVal }}"
                   style="text-align: center; font-weight: bold; font-size: 16px; color: #111827; background: #fff; border: 2px solid #d1d5db; border-radius: 8px; padding: 6px 12px; width: 80%; margin-top: 6px;">
        @else
            <p style="font-weight: bold; font-size: {{ $forPdf ? '14px' : '18px' }}; color: #111827; margin: 6px 0; direction: ltr;" data-preview-entreprise>
                {{ $entrepriseVal }}
            </p>
        @endif
        <p style="font-size: {{ $forPdf ? '12px' : '14px' }}; color: #374151; margin: 4px 0; direction: ltr;">
            Nº 28 Rue Al Kawakibi-Cité Dakhla Agadir.
        </p>
    </div>

    {{-- ═══ SUJET ═══ --}}
    <div style="margin-bottom: {{ $forPdf ? '16px' : '24px' }}; padding: {{ $forPdf ? '10px' : '15px' }}; {{ $forPdf ? '' : 'background: #f9fafb; border-radius: 8px; border-right: 4px solid #1a2744;' }}">
        @if ($editable)
            <span style="font-weight: bold; font-size: 15px; display: block; margin-bottom: 6px; color: #020617;">
                {!! $arabic('الموضوع:') !!}
            </span>
            <textarea name="{{ $fieldPrefix }}_header_subject"
                      data-sync-field="header_subject"
                      rows="2"
                      style="width: 100%; background: #fff; border: 1px solid #d1d5db; border-radius: 6px; padding: 8px; font-size: 14px; line-height: 1.6; direction: rtl; text-align: right;">{{ $subjectVal }}</textarea>
        @else
            <span style="font-weight: bold; font-size: {{ $forPdf ? '13px' : '15px' }}; display: inline; color: #020617;">
                {!! $arabic('الموضوع:') !!}
            </span>
            <span style="font-size: {{ $forPdf ? '13px' : '14px' }};">
                {!! $arabic($subjectVal) !!}
            </span>
        @endif
    </div>

    {{-- ═══ SALUTATION ═══ --}}
    <div style="text-align: center; margin-bottom: {{ $forPdf ? '14px' : '22px' }}; font-weight: bold; font-size: {{ $forPdf ? '14px' : '16px' }}; color: #111827;">
        {!! $arabic('سلام تام بوجود مولانا الإمام،') !!}
    </div>

    {{-- ═══ CORPS ═══ --}}
    <div style="font-size: {{ $forPdf ? '13px' : '15px' }}; line-height: {{ $forPdf ? '1.7' : '1.9' }}; text-align: right; color: #111827; margin-bottom: {{ $forPdf ? '14px' : '28px' }}; padding: {{ $forPdf ? '10px' : '18px' }}; {{ $forPdf ? '' : 'background: #fff; border: 1px solid #e5e7eb; border-radius: 8px;' }}">
        @if ($isAcceptation)
            @if ($editable)
                <p style="margin-bottom: 12px;">
                    {!! $arabic('وبعد، علاقة بالموضوع المشار إليه أعلاه، يشرفني ان اخبركم أن لجنة طلب العروض المجتمعة يوم') !!}
                    <input type="text"
                           name="{{ $fieldPrefix }}_header_date_body"
                           data-sync-field="header_date"
                           value="{{ $dateVal }}"
                           style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px; text-align: center; padding: 2px 6px; font-size: 13px; width: 100px; margin: 0 4px; direction: ltr;">
                    {!! $arabic('على الساعة') !!}
                    <input type="text"
                           name="{{ $fieldPrefix }}_header_meeting_time"
                           data-sync-field="header_meeting_time"
                           value="{{ $timeVal }}"
                           style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px; text-align: center; padding: 2px 6px; font-size: 13px; width: 60px; margin: 0 4px; direction: ltr;">
                    {!! $arabic('صباحا، في قاعة الاجتماعات بالمديرية الإقليمية للعدل بورزازات، قد قبلت العرض المالي الذي تقدمتم به في انتظار المصادقة عليه من طرف السلطات المختصة.') !!}
                </p>
            @else
                <p style="margin-bottom: 10px;">
                    {!! $arabic("وبعد، علاقة بالموضوع المشار إليه أعلاه، يشرفني ان اخبركم أن لجنة طلب العروض المجتمعة يوم {$dateVal} على الساعة {$timeVal} صباحا، في قاعة الاجتماعات بالمديرية الإقليمية للعدل بورزازات، قد قبلت العرض المالي الذي تقدمتم به في انتظار المصادقة عليه من طرف السلطات المختصة.") !!}
                </p>
            @endif
            <p>{!! $arabic($acceptationFollowUp) !!}</p>
        @else
            @if ($editable)
                <p style="margin-bottom: 12px;">
                    {!! $arabic('وبعد، علاقة بالموضوع المشار إليه أعلاه، يؤسفنا ان اخبركم أن لجنة طلب العروض المجتمعة يوم') !!}
                    <input type="text"
                           name="{{ $fieldPrefix }}_header_date_body"
                           data-sync-field="header_date"
                           value="{{ $dateVal }}"
                           style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px; text-align: center; padding: 2px 6px; font-size: 13px; width: 100px; margin: 0 4px; direction: ltr;">
                    {!! $arabic('على الساعة') !!}
                    <input type="text"
                           name="{{ $fieldPrefix }}_header_meeting_time"
                           data-sync-field="header_meeting_time"
                           value="{{ $timeVal }}"
                           style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px; text-align: center; padding: 2px 6px; font-size: 13px; width: 60px; margin: 0 4px; direction: ltr;">
                    {!! $arabic('صباحا، في قاعة الاجتماعات بالمديرية الإقليمية للعدل بورزازات، لم تقبل العرض المالي الذي تقدمتم به.') !!}
                </p>
            @else
                <p style="margin-bottom: 10px;">
                    {!! $arabic("وبعد، علاقة بالموضوع المشار إليه أعلاه، يؤسفنا ان اخبركم أن لجنة طلب العروض المجتمعة يوم {$dateVal} على الساعة {$timeVal} صباحا، في قاعة الاجتماعات بالمديرية الإقليمية للعدل بورزازات، لم تقبل العرض المالي الذي تقدمتم به.") !!}
                </p>
            @endif
            <p>{!! $arabic($refusFollowUp) !!}</p>
        @endif
    </div>

    {{-- ═══ CLÔTURE ═══ --}}
    <div style="text-align: center; margin-bottom: {{ $forPdf ? '16px' : '28px' }}; font-weight: bold; color: #111827; font-size: {{ $forPdf ? '13px' : '15px' }};">
        {!! $arabic('وتقبلوا سيدي فائق الاحترام والتقدير.') !!}
    </div>

    {{-- ═══ SIGNATURE ═══ --}}
    <div style="text-align: right; margin-top: {{ $forPdf ? '10px' : '40px' }};">
        <p style="font-weight: bold; color: #111827; font-size: {{ $forPdf ? '13px' : '15px' }}; margin: 0;">
            {!! $arabic('والسلام./.') !!}
        </p>
    </div>

</div>