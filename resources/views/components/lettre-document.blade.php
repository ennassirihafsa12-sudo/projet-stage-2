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

    $dateVal = $header['date'] ?? ($forPdf ? now()->format('d/m/Y') : now()->format('Y-m-d'));
    $timeVal = $header['meeting_time'] ?? '10:00';
    $subjectVal = $header['subject'] ?? ($marche?->objet ?? '');
    $entrepriseVal = $entreprise ?: ($marche?->entreprise ?? 'STE ASWAK MACRO NEGOCE S.A.R.L');
    $fieldPrefix = $prefix;
    $logoUrl = $logoSrc ?: ($forPdf ? App\Http\Controllers\LettreController::pdfLogoSrc() : asset('images/logo.png'));
    $wrapperDir = 'rtl';
    $arabic = fn (string $text): string => $forPdf ? LettreController::shape($text) : LettreController::shape($text);

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

<div dir="{{ $wrapperDir }}" @if($id) id="{{ $id }}" @endif class="lettre-document bg-white {{ $forPdf ? '' : 'p-6' }}" style="font-family: {{ $forPdf ? "'DejaVu Sans', sans-serif" : "'Segoe UI', Tahoma, sans-serif" }}; {{ $forPdf ? 'direction: rtl; text-align: right;' : '' }}">
    <table class="letter-table" style="margin-bottom: {{ $forPdf ? '10px' : '20px' }}; {{ $forPdf ? '' : 'border-bottom: 1px solid #1a2744;' }} width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 33%; text-align: left; padding: 0 6px; direction: ltr;">
                <p style="font-weight: bold; font-size: {{ $forPdf ? '11px' : '14px' }}; margin: 3px 0; color: #111827;">Royaume du Maroc</p>
                <p style="font-weight: bold; font-size: {{ $forPdf ? '11px' : '14px' }}; margin: 3px 0; color: #111827;">MINISTÈRE DE LA JUSTICE</p>
                <p style="font-size: {{ $forPdf ? '10px' : '13px' }}; margin: 3px 0; color: #111827;">DIRECTION RÉGIONALE DE LA JUSTICE OUARZAZATE</p>
            </td>
            <td style="width: 34%; text-align: center; padding: 0 6px;">
                <img src="{{ $logoUrl }}" alt="Logo" style="height: 70px; width: auto; object-fit: contain;">
            </td>
            <td style="width: 33%; text-align: right; padding: 0 6px;">
                <p style="font-weight: bold; font-size: {{ $forPdf ? '11px' : '14px' }}; margin: 3px 0; color: #111827;">{!! $arabic('المملكة المغربية') !!}</p>
                <p style="font-weight: bold; font-size: {{ $forPdf ? '11px' : '14px' }}; margin: 3px 0; color: #111827;">{!! $arabic('وزارة العدل') !!}</p>
                <p style="font-weight: bold; font-size: {{ $forPdf ? '11px' : '14px' }}; margin: 3px 0; color: #111827;">{!! $arabic('المديرية الإقليمية بورزازات') !!}</p>
            </td>
        </tr>
    </table>

    <!-- RECIPIENT BLOCK -->
    <div style="text-align: center; margin-bottom: 30px; padding: 15px; {{ $forPdf ? '' : 'border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb;' }}">
        <p style="font-size: 16px; font-weight: bold; color: #111827; margin: 5px 0;">{!! $arabic('المديرة الإقليمية للعدل بورزازات') !!}</p>
        <p style="font-size: 16px; font-weight: bold; color: #111827; margin: 5px 0;">{!! $arabic('إلى السيد مدير شركة:') !!}</p>
        <div style="display: inline-block; min-width: 300px; margin-top: 10px;">
            @if ($editable)
                <input type="text"
                       name="{{ $fieldPrefix }}_entreprise"
                       data-sync-field="entreprise"
                       value="{{ $entrepriseVal }}"
                       style="width: 100%; text-align: center; font-weight: bold; font-size: 18px; color: #111827; background: #ffffff; border: 2px solid #d1d5db; border-radius: 8px; padding: 8px 12px;">
            @else
                <p style="font-weight: bold; font-size: 18px; color: #111827; margin: 5px 0; direction: ltr;" data-preview-entreprise>{{ $entrepriseVal }}</p>
            @endif
            <p style="font-size: 14px; color: #6b7280; margin: 5px 0; direction: ltr;">Nº 28 Rue Al Kawakibi-Cité Dakhla Agadir.</p>
        </div>
    </div>

    <div style="margin-bottom: 14px; padding: 12px 14px; {{ $forPdf ? '' : 'border: 1px solid #e5e7eb; border-radius: 8px; background: #f8fafb;' }}">
        <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
            <div style="min-width: 180px; direction: ltr;">
                <span style="font-weight: bold; font-size: 12px; color: #111827;">Entreprise :</span>
                <span style="display: block; font-size: 13px; color: #111827; margin-top: 3px;">{{ $entrepriseVal }}</span>
            </div>
            <div style="min-width: 180px; direction: ltr;">
                <span style="font-weight: bold; font-size: 12px; color: #111827;">N° du marché :</span>
                <span style="display: block; font-size: 13px; color: #111827; margin-top: 3px;">{{ $marche?->numero ?? '—' }}</span>
            </div>
        </div>
    </div>

    <!-- SUBJECT ROW -->
    <div style="margin-bottom: 25px; padding: 15px; {{ $forPdf ? '' : 'background: #f9fafb; border-radius: 8px; border-left: 4px solid #1a2744;' }}">
        @if ($editable)
            <span style="font-weight: bold; color: #020617; font-size: 16px; white-space: nowrap; display: block; margin-bottom: 8px;">{!! $arabic('الموضوع:') !!}</span>
            <textarea name="{{ $fieldPrefix }}_header_subject"
                      data-sync-field="header_subject"
                      rows="2"
                      style="width: 100%; background: #ffffff; border: 1px solid #d1d5db; border-radius: 6px; padding: 10px; font-size: 14px; line-height: 1.6; direction: rtl; text-align: right;">{{ $subjectVal }}</textarea>
        @elseif ($forPdf)
            <span style="font-size: 16px; font-weight: bold; display: block; margin-bottom: 8px;">{!! $arabic('الموضوع:') !!}</span>
            <span style="font-size: 14px; display: block; direction: ltr;">{{ $subjectVal }}</span>
        @else
            <span style="font-weight: bold; color: #020617; font-size: 16px; white-space: nowrap; display: block; margin-bottom: 8px;">{!! $arabic('الموضوع:') !!}</span>
            <span style="font-size: 14px; display: block;">{{ $subjectVal }}</span>
        @endif
    </div>

    <!-- SALUTATION -->
    <div style="text-align: center; margin-bottom: {{ $forPdf ? '14px' : '25px' }}; font-weight: bold; font-size: {{ $forPdf ? '14px' : '16px' }}; color: #111827; padding: 10px; {{ $forPdf ? '' : 'background: #f9fafb; border-radius: 6px;' }}">
        {!! $arabic('سلام تام بوجود مولانا الإمام،') !!}
    </div>

    <!-- BODY TEXT WITH TIME INPUT -->
    <div style="font-size: {{ $forPdf ? '13px' : '16px' }}; line-height: {{ $forPdf ? '1.6' : '1.8' }}; text-align: {{ $forPdf ? 'right' : 'justify' }}; color: #111827; margin-bottom: {{ $forPdf ? '12px' : '30px' }}; padding: {{ $forPdf ? '12px' : '20px' }}; {{ $forPdf ? '' : 'background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px;' }}">
        @if ($isAcceptation)
            @if ($editable)
                <p style="margin-bottom: 15px;">
                    {!! $arabic('وبعد، علاقة بالموضوع المشار إليه أعلاه، يشرفني ان اخبركم أن لجنة طلب العروض المجتمعة يوم 04 مارس 2025 على الساعة ') !!}
                    <input type="text"
                           name="{{ $fieldPrefix }}_header_meeting_time"
                           data-sync-field="header_meeting_time"
                           value="{{ $timeVal }}"
                           style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px; text-align: center; padding: 2px 8px; font-size: 14px; width: 64px; margin: 0 4px;">
                    {!! $arabic(' صباحا، في قاعة الاجتماعات بالمديرية الإقليمية للعدل بورزازات، قد قبلت العرض المالي الذي تقدمتم به في انتظار المصادقة عليه من طرف السلطات المختصة.') !!}
                </p>
            @elseif ($forPdf)
                @foreach ($acceptationBodyChunks as $chunk)
                    <div style="text-align: right; margin-bottom: 3px; line-height: 1.7;">{!! $arabic($chunk) !!}</div>
                @endforeach
            @else
                <p style="margin-bottom: 15px;">
                    {!! $arabic("وبعد، علاقة بالموضوع المشار إليه أعلاه، يشرفني ان اخبركم أن لجنة طلب العروض المجتمعة يوم 04 مارس 2025 على الساعة {$timeVal} صباحا، في قاعة الاجتماعات بالمديرية الإقليمية للعدل بورزازات، قد قبلت العرض المالي الذي تقدمتم به في انتظار المصادقة عليه من طرف السلطات المختصة.") !!}
                </p>
            @endif
            <p>{!! $arabic($acceptationFollowUp) !!}</p>
        @else
            @if ($editable)
                <p style="margin-bottom: 15px;">
                    {!! $arabic('وبعد، علاقة بالموضوع المشار إليه أعلاه، يؤسفنا ان اخبركم أن لجنة طلب العروض المجتمعة يوم 04 مارس 2025 على الساعة ') !!}
                    <input type="text"
                           name="{{ $fieldPrefix }}_header_meeting_time"
                           data-sync-field="header_meeting_time"
                           value="{{ $timeVal }}"
                           style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px; text-align: center; padding: 2px 8px; font-size: 14px; width: 64px; margin: 0 4px;">
                    {!! $arabic(' صباحا، في قاعة الاجتماعات بالمديرية الإقليمية للعدل بورزازات، لم تقبل العرض المالي الذي تقدمتم به.') !!}
                </p>
            @elseif ($forPdf)
                @foreach ($refusBodyChunks as $chunk)
                    <div style="text-align: right; margin-bottom: 3px; line-height: 1.7;">{!! $arabic($chunk) !!}</div>
                @endforeach
            @else
                <p style="margin-bottom: 15px;">
                    {!! $arabic("وبعد، علاقة بالموضوع المشار إليه أعلاه، يؤسفنا ان اخبركم أن لجنة طلب العروض المجتمعة يوم 04 مارس 2025 على الساعة {$timeVal} صباحا، في قاعة الاجتماعات بالمديرية الإقليمية للعدل بورزازات، لم تقبل العرض المالي الذي تقدمتم به.") !!}
                </p>
            @endif
            <p>{!! $arabic($refusFollowUp) !!}</p>
        @endif
    </div>

    <!-- CLOSING AND SIGNATURE -->
    <div style="text-align: center; margin-bottom: {{ $forPdf ? '18px' : '30px' }}; font-weight: bold; color: #111827; padding: 8px; {{ $forPdf ? '' : 'background: #f9fafb; border-radius: 6px;' }}">
        {!! $arabic('وتقبلوا سيدي فائق الاحترام والتقدير.') !!}
    </div>

    <div style="margin-top: {{ $forPdf ? '12px' : '50px' }}; padding-left: {{ $forPdf ? '0' : '50px' }};">
        <div style="text-align: center; padding: 8px; {{ $forPdf ? '' : 'background: #f9fafb; border-radius: 6px;' }} display: inline-block;">
            <p style="font-weight: bold; color: #111827; font-size: {{ $forPdf ? '14px' : '16px' }}; margin: 0;">{!! $arabic('والسلام./.') !!}</p>
        </div>
    </div>
</div>
