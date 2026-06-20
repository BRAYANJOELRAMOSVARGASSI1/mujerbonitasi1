<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>¡Nueva Promoción!</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #CC5CB8;
            padding: 30px 20px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
            color: #333333;
            line-height: 1.6;
        }
        .discount-badge {
            display: block;
            width: fit-content;
            margin: 20px auto;
            background-color: #f8e1f4;
            color: #CC5CB8;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }
        .details-box {
            background-color: #f8f9fa;
            border-left: 4px solid #CC5CB8;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .details-box ul {
            margin: 10px 0 0 0;
            padding-left: 20px;
        }
        .btn {
            display: block;
            width: 200px;
            margin: 30px auto;
            padding: 12px 20px;
            background-color: #17a2b8;
            color: #ffffff;
            text-decoration: none;
            text-align: center;
            border-radius: 8px;
            font-weight: bold;
        }
        .footer {
            background-color: #343a40;
            color: #ffffff;
            text-align: center;
            padding: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>¡Hola {{ $cliente->nombre }}!</h1>
        <p style="margin-top: 10px; font-size: 16px;">Mujer Bonita tiene algo especial para ti</p>
    </div>

    <div class="content">
        <h2 style="color: #CC5CB8; text-align: center;">{{ $promocion->nombre }}</h2>
        
        <div class="discount-badge">
            {{ number_format($promocion->porcentaje_descuento, 0) }}% DE DESCUENTO
        </div>

        <p>{{ $promocion->descripcion }}</p>

        <div class="details-box">
            <p style="margin: 0 0 10px 0;"><strong>Válido desde:</strong> {{ $promocion->fecha_inicio->format('d/m/Y') }} <strong>hasta:</strong> {{ $promocion->fecha_fin->format('d/m/Y') }}</p>
            
            @if($promocion->servicios->count() > 0)
                <p style="margin: 0;"><strong>Servicios incluidos:</strong></p>
                <ul>
                    @foreach($promocion->servicios as $servicio)
                        <li>{{ $servicio->nombre }}</li>
                    @endforeach
                </ul>
            @else
                <p style="margin: 0;"><strong>Servicios incluidos:</strong> Válido en servicios seleccionados.</p>
            @endif
        </div>

        <p style="text-align: center; color: #6c757d; font-size: 14px;">
            ¡No dejes pasar esta oportunidad! Agenda tu cita hoy mismo para aprovechar este descuento exclusivo.
        </p>

        <a href="{{ url('/') }}" class="btn">Agendar Cita</a>
    </div>

    <div class="footer">
        <p style="margin: 0;">&copy; {{ date('Y') }} Salón Mujer Bonita. Todos los derechos reservados.</p>
        <p style="margin: 5px 0 0 0; color: #adb5bd;">Si no deseas recibir más correos, por favor ignora este mensaje.</p>
    </div>
</div>

</body>
</html>
