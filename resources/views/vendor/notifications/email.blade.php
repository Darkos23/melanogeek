<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'MelanoGeek' }}</title>
</head>
<body style="margin:0;padding:0;background:#0F0F0F;font-family:'Helvetica Neue',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#0F0F0F;padding:40px 20px;">
    <tr>
        <td align="center">
            <table width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;">

                {{-- Logo --}}
                <tr>
                    <td align="center" style="padding-bottom:32px;">
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="padding-right:10px;vertical-align:middle;">
                                    <svg width="36" height="36" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M21 2L38.3 12V32L21 42L3.7 32V12L21 2Z" fill="#1A1208" stroke="#D4A843" stroke-width="0.8"/>
                                        <path d="M10 28V14L16.5 22L21 16L25.5 22L32 14V28" stroke="#C8522A" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                                        <circle cx="21" cy="9" r="2.5" fill="#D4A843"/>
                                    </svg>
                                </td>
                                <td style="vertical-align:middle;">
                                    <span style="font-size:1.3rem;font-weight:800;color:#F5EFE6;letter-spacing:-.01em;">Melano<span style="color:#D4A843;">Geek</span></span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- Card --}}
                <tr>
                    <td style="background:#1A1410;border:1px solid rgba(212,168,67,.15);border-radius:16px;padding:40px 36px;">

                        {{-- Greeting --}}
                        <p style="font-size:1.25rem;font-weight:700;color:#F5EFE6;margin:0 0 20px;">{{ $greeting ?? 'Bonjour !' }}</p>

                        {{-- Intro lines --}}
                        @foreach ($introLines as $line)
                            <p style="font-size:.95rem;color:rgba(245,239,230,.75);line-height:1.7;margin:0 0 16px;">{{ $line }}</p>
                        @endforeach

                        {{-- Action button --}}
                        @isset($actionText)
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin:28px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $actionUrl }}"
                                           style="display:inline-block;background:#C8522A;color:#ffffff;text-decoration:none;font-weight:700;font-size:.95rem;padding:14px 32px;border-radius:10px;letter-spacing:.01em;">
                                            {{ $actionText }}
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        @endisset

                        {{-- Outro lines --}}
                        @foreach ($outroLines as $line)
                            <p style="font-size:.85rem;color:rgba(245,239,230,.5);line-height:1.7;margin:0 0 12px;">{{ $line }}</p>
                        @endforeach

                        {{-- Divider --}}
                        <hr style="border:none;border-top:1px solid rgba(212,168,67,.1);margin:24px 0;">

                        {{-- Fallback URL --}}
                        @isset($actionText)
                            <p style="font-size:.75rem;color:rgba(245,239,230,.35);line-height:1.6;margin:0;word-break:break-all;">
                                Si le bouton ne fonctionne pas, copie ce lien dans ton navigateur :<br>
                                <a href="{{ $actionUrl }}" style="color:#D4A843;text-decoration:none;">{{ $actionUrl }}</a>
                            </p>
                        @endisset

                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td align="center" style="padding-top:24px;">
                        <p style="font-size:.75rem;color:rgba(245,239,230,.25);margin:0;line-height:1.7;">
                            © {{ date('Y') }} MelanoGeek · La communauté des créateurs sénégalais<br>
                            Si tu n'as pas demandé cet email, ignore-le simplement.
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
