<?php

namespace App\Http\Controllers;

use App\Enums\LettreType;
use App\Models\Lettre;
use App\Models\Marche;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LettreController extends Controller
{
    public function index(Request $request): View
    {
        $marches = Marche::orderBy('numero')->get();
        $marche = null;

        if ($request->filled('marche_id')) {
            $marche = Marche::find($request->marche_id);
        }

        $type = LettreType::tryFrom($request->get('type', 'acceptation')) ?? LettreType::Acceptation;
        $entreprise = $request->get('entreprise', $marche?->entreprise ?? '');

        $headerDefaults = [
            'date'         => now()->format('Y-m-d'),
            'meeting_time' => '10:00',
            'subject'      => $marche ? $marche->objet : '',
        ];

        $header = array_merge($headerDefaults, array_filter([
            'date'         => $request->get('header_date'),
            'meeting_time' => $request->get('header_meeting_time'),
            'subject'      => $request->get('header_subject'),
        ], fn ($v) => $v !== null && $v !== ''));

        return view('lettres.index', compact('marches', 'marche', 'type', 'entreprise', 'header'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'marche_id'  => 'required|exists:marches,id',
            'type'       => 'required|in:acceptation,refus',
            'entreprise' => 'required|string|max:255',
            'format'     => 'required|in:pdf',
        ]);

        $lettre = Lettre::create($validated);

        $query = [
            'marche_id'  => $lettre->marche_id,
            'type'       => $lettre->type->value,
            'entreprise' => $lettre->entreprise,
        ];

        foreach ([
            'header_date', 'header_meeting_time', 'header_subject',
        ] as $field) {
            if ($request->filled($field)) {
                $query[$field] = $request->input($field);
            }
        }

        return redirect()
            ->route('lettres.index', $query)
            ->with('success', __('Document généré avec succès.'));
    }

    public function preview(Request $request): View
    {
        $marche     = Marche::findOrFail($request->marche_id);
        $type       = LettreType::from($request->type);
        $entreprise = $request->entreprise ?? $marche->entreprise ?? 'Entreprise';

        $header = [
            'date'         => $request->get('header_date', now()->format('Y-m-d')),
            'meeting_time' => $request->get('header_meeting_time', '10:00'),
            'subject'      => $request->get('header_subject', $marche->objet),
        ];

        return view('lettres.preview', compact('marche', 'type', 'entreprise', 'header'));
    }

    public function exportOfficialPdf(Marche $marche)
    {
        $data = [
            'entreprise'   => $marche->entreprise ?: 'STE ASWAK MACRO NEGOCE S.A.R.L',
            'dateLine'     => optional($marche->date_publication)
                ? $marche->date_publication->locale('fr')->translatedFormat('j MMMM YYYY')
                : '10 مارس 2025',
            'tenderNumber' => '01 / 2025',
            'decisionDate' => '04 مارس 2025',
            'decisionTime' => 'العاشرة',
        ];

        $pdf = PDF::loadView('document', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf->stream('lettre-officielle.pdf');
    }

    public function download(Lettre $lettre)
    {
        $marche     = $lettre->marche;
        $type       = $lettre->type;
        $entreprise = $marche->entreprise ?? $lettre->entreprise;

        // Heure avec période arabe
        $heureFormatee = $this->formatArabicTime('10:00');

        $data = [
            'marche'     => $marche,
            'type'       => $type,
            'entreprise' => $entreprise,
            'header'     => [
                'date'         => $this->formatArabicDate(now()->format('Y-m-d')),
                'meeting_time' => $heureFormatee,
                'subject'      => $marche->objet ?? '',
            ],
        ];

        $pdf = PDF::loadView('lettres.document-pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);

        $fileName = 'lettre-' . $marche->numero . '-' . $type->value . '.pdf';
        $fileName = str_replace(['/', '\\'], '-', $fileName);

        return $pdf->download($fileName);
    }

    public function downloadPdf(Request $request)
    {
        $request->validate([
            'marche_id'           => 'required|exists:marches,id',
            'type'                => 'required|string',
            'entreprise'          => 'nullable|string|max:255',
            'header_date'         => 'required|string',
            'header_meeting_time' => 'required|string',
            'header_subject'      => 'required|string',
        ]);

        $marche     = Marche::findOrFail($request->marche_id);
        $type       = LettreType::from($request->type);
        $entreprise = $request->entreprise ?? $marche->entreprise ?? 'Entreprise';

        $data = [
            'marche'     => $marche,
            'type'       => $type,
            'entreprise' => $entreprise,
            'header'     => [
                'date'         => $this->formatArabicDate($request->header_date),
                'meeting_time' => $this->formatArabicTime($request->header_meeting_time),
                'subject'      => $request->header_subject,
            ],
        ];

        $pdf = PDF::loadView('lettres.document-pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);

        $fileName = 'lettre-' . $marche->numero . '-' . $type->value . '.pdf';
        $fileName = str_replace(['/', '\\'], '-', $fileName);

        return $pdf->download($fileName);
    }

    // ─── Helpers ────────────────────────────────────────────────

    /**
     * Formate une date en arabe  Ex: "10 يونيو 2026"
     */
    private function formatArabicDate(string $date): string
    {
        try {
            Carbon::setLocale('ar');
            return Carbon::parse($date)->translatedFormat('j F Y');
        } catch (\Exception $e) {
            return $date;
        }
    }

    /**
     * Formate une heure avec période arabe  Ex: "10:00 صباحا"
     */
    private function formatArabicTime(string $time): string
    {
        try {
            [$heure] = explode(':', $time);
            $periode = (int)$heure < 12 ? 'صباحا' : 'مساء';
            return $time . ' ' . $periode;
        } catch (\Exception $e) {
            return $time;
        }
    }

    /**
     * Formate une date en français  Ex: "10 juin 2026"
     * Gardée pour exportOfficialPdf si besoin
     */
    private function formatFrenchDate(string $date): string
    {
        try {
            return Carbon::parse($date)
                ->locale('fr')
                ->translatedFormat('j MMMM YYYY');
        } catch (\Exception $e) {
            return $date;
        }
    }

    // ─── Static helpers (PDF rendering) ─────────────────────────

    public static function shape(string $text, int $maxChars = 2000): string
    {
        if (class_exists(\ArPHP\I18N\Arabic::class)) {
            try {
                $arabic = new \ArPHP\I18N\Arabic('Glyphs');
                return $arabic->utf8Glyphs($text, $maxChars);
            } catch (\Exception $e) {
                return $text;
            }
        }
        return $text;
    }

    public static function pdfLogoSrc(): string
    {
        $path = public_path('images/logo.png');

        if (! is_file($path)) {
            return '';
        }

        $bytes = (string) file_get_contents($path);
        $mime  = str_starts_with($bytes, "\xFF\xD8\xFF") ? 'image/jpeg' : 'image/png';

        return 'data:' . $mime . ';base64,' . base64_encode($bytes);
    }
}