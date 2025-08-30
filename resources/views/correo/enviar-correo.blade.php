<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Cuenta</title>
    <style>
        body {
            background-color: #ffffff; 
            padding: 20px; 
            text-align: center; 
            font-family: Arial, sans-serif;
        }

        .contenedor {
            max-width: 500px; 
            margin: auto; 
            background: #eef4fc; 
            padding: 20px; 
            border-radius: 10px; 
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .tit {
            background-color: #007bff;
            color: white; 
            padding: 20px; 
            border-top-left-radius: 10px; 
            border-top-right-radius: 10px;
        }


        .p1 {
            text-align: left; 
            padding: 10px;
        }

        .p2 {
            font-size: 18px; 
            font-weight: bold; 
            color: #333;
        }

        .p3 {
            font-size: 16px;
            color: #555; 
        }

        .cod{
            background: #f9b233; 
            color: white; 
            display: inline-block;
            padding: 10px 20px; 
            border-radius: 5px;
        }

        .pie{
            text-align: center; 
            margin-top: 20px; 
            color: #6c757d; 
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <div class="tit">
            <h1>
                Verifica que eres tú
            </h1>
        </div>
        <div class="p1">
            <div class="p2">
                <p>
                    {{ $request['usuario']}}
                </p>
            </div>
        

            <div class="p3">
                <p>
                    Solicitaste un cambio en tu cuenta. 
                    Para continuar, usa la siguente contraseña:
                </p>
            </div>

            <div>
                <h2 class="cod">
                    {{ $request['password']}}
                </h2>
            </div>

        </div>

        <hr>

        <div class="pie">
            <p>Si no realizaste esta solicitud, ignora este mensaje.</p>
            <p>TallerUp by SaveCar</p>            
        </div>
    </div>
</body>
</html>
