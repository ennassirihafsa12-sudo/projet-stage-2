<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $type->titreDocument() }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gris-clair p-8">
    <x-lettre-document
        :marche="$marche"
        :type="$type"
        :entreprise="$entreprise"
        :header="$header"
        :editable="false"
        :forPdf="true" />
</body>
</html>