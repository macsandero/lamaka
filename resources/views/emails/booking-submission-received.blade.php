<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>Nuova richiesta prenotazione</title>
</head>
<body style="margin: 0; padding: 24px; background: #f4efe6; color: #2f2a25; font-family: Arial, sans-serif;">
    <div style="max-width: 680px; margin: 0 auto; background: #fffdf9; border: 1px solid #d8cbb8; padding: 28px;">
        <p style="margin: 0 0 8px; color: #7b725e; font-size: 12px; letter-spacing: 3px; text-transform: uppercase;">LAMAKA</p>
        <h1 style="margin: 0 0 16px; font-size: 28px; font-weight: 400;">Nuova richiesta prenotazione</h1>

        <p style="margin: 0 0 20px; line-height: 1.6;">
            Riferimento <strong>{{ $submission->reference }}</strong><br>
            Ricevuta il {{ $submission->receivedAtFormatted() }}
        </p>

        <table style="width: 100%; border-collapse: collapse;">
            @foreach ($submission->flatData() as $label => $value)
                <tr>
                    <th style="width: 38%; padding: 10px 0; border-top: 1px solid #e3d8c9; text-align: left; color: #7b725e; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; vertical-align: top;">
                        {{ $label }}
                    </th>
                    <td style="padding: 10px 0; border-top: 1px solid #e3d8c9; line-height: 1.5; vertical-align: top;">
                        {{ $value }}
                    </td>
                </tr>
            @endforeach
        </table>

        <p style="margin: 24px 0 0;">
            <a href="{{ url('/admin/booking-submissions/'.$submission->getKey()) }}" style="color: #747248;">
                Apri la richiesta nell'area admin
            </a>
        </p>
    </div>
</body>
</html>
