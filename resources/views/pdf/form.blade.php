<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Buscar CUIL en PDF</title>
</head>

<body>
  <h1>Buscar CUIL en PDF</h1>

  @if (session('success'))
    <p>{{ session('success') }}</p>
  @endif

  <form action="{{ route('buscar') }}" method="post" enctype="multipart/form-data">
    @csrf

    <label for="pdfFile">Seleccionar archivo PDF:</label>
    <input type="file" name="pdfFile" id="pdfFile" accept=".pdf" required>
    <br>

    <label for="cuil">Número de CUIL:</label>
    <input type="text" name="cuil" id="cuil" required>
    <br>

    <input type="submit" value="Buscar">
  </form>
</body>

</html>
