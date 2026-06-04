<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen Vocacional</title>
    
    <!-- Incluir el Web Component -->
    <script src="{{ asset('js/aviso-examenv.js') }}" defer></script>

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #222;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Aquí se renderiza el Web Component -->
    <aviso-examenv></aviso-examenv>

</body>     
</html>
