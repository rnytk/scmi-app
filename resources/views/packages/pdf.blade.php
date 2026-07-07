<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Guía de Envío SCMI</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333333;
            margin: 0;
            padding: 0;
            font-size: 14px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .logo-title {
            font-size: 24px;
            font-weight: bold;
            color: #071d49;
        }
        .subtitle {
            font-size: 11px;
            color: #63666a;
            margin-top: 3px;
        }
        .tracking-box {
            background-color: #f4f4f5;
            border: 1px solid #e4e4e7;
            padding: 15px;
            text-align: right;
            border-radius: 10px;
        }
        .tracking-label {
            font-size: 10px;
            font-weight: bold;
            color: #71717a;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .tracking-id {
            font-size: 22px;
            font-weight: bold;
            color: #4c8c2b;
            margin-top: 4px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .info-header {
            font-size: 10px;
            font-weight: bold;
            color: #a1a1aa;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding-bottom: 5px;
        }
        .info-value {
            font-size: 15px;
            font-weight: bold;
            color: #111827;
        }
        .info-subvalue {
            font-size: 12px;
            color: #4b5563;
            margin-top: 2px;
        }
        .divider {
            border-top: 1px dashed #e4e4e7;
            margin-top: 20px;
            margin-bottom: 25px;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .qr-container {
            width: 1%;
            white-space: nowrap;
            padding-right: 20px;
        }
        .qr-box {
            border: 1px solid #e4e4e7;
            padding: 8px;
            border-radius: 12px;
            display: inline-block;
            background-color: #fff;
        }
        .meta-label {
            font-size: 10px;
            font-weight: bold;
            color: #a1a1aa;
            text-transform: uppercase;
        }
        .meta-value {
            font-size: 13px;
            font-weight: bold;
            color: #374151;
            margin-top: 2px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <div class="logo-title">Control de Envíos SCMI</div>
                <div class="subtitle">Sistema de Control de Mensajería Interna</div>
            </td>
            <td style="width: 40%;">
                <div class="tracking-box">
                    <div class="tracking-label">ID del Paquete</div>
                    <div class="tracking-id">#PKG-{{ str_pad($package->id, 4, '0', STR_PAD_LEFT) }}</div>
                </div>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td style="width: 50%; padding-right: 15px; vertical-align: top;">
                <div class="info-header">Remitente</div>
                <div class="info-value">{{ $package->sender->name ?? 'N/A' }}</div>
                <div class="info-subvalue">Agencia: {{ $package->originAgency->name ?? 'N/A' }}</div>
            </td>
            <td style="width: 50%; padding-left: 15px; vertical-align: top;">
                <div class="info-header">Destinatario</div>
                <div class="info-value">{{ $package->recipient->name ?? 'N/A' }}</div>
                <div class="info-subvalue">Agencia Destino: {{ $package->destinationAgency->name ?? 'N/A' }}</div>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td style="width: 50%; padding-right: 15px; vertical-align: top;">
                <div class="info-header">Agencia Destino Final</div>
                <div class="info-value" style="color: #071d49;"> {{ $package->destinationAgency->name ?? 'N/A' }}</div>
            </td>
            <td style="width: 50%; padding-left: 15px; vertical-align: top;">
                <div class="info-header">Tipo de Paquete / Referencia</div>
                <div class="info-value">{{ $package->packageType->name ?? 'N/A' }}</div>
                <div class="info-subvalue">{{ $package->description ?? 'Sin descripción adicional.' }}</div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="footer-table">
        <tr>
            <td class="qr-container">
                <div class="qr-box">
                    <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" width="130" height="130" alt="QR Tracking" style="display: block; margin: 0 auto;">
                </div>
            </td>
            <td style="vertical-align: middle;">
                <div class="meta-label">Escanea para seguimiento operativo</div>
                <div class="meta-value" style="font-family: monospace; letter-spacing: 0.5px; color: #4b5563;">
                    LOG-SCMI-{{ date('Y') }}-{{ $package->id }}
                </div>
                
                <div class="meta-label" style="margin-top: 15px;">Fecha y Hora de Registro</div>
                <div class="meta-value">{{ $package->created_at->format('d M, Y - h:i A') }}</div>
            </td>
            <td style="text-align: right; vertical-align: bottom; font-size: 10px; color: #9ca3af;">
                Documento de Control Interno<br>Kato-Ki R.L.
            </td>
        </tr>
    </table>

</body>
</html>