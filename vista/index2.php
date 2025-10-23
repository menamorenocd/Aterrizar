<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en" data-theme="corporate">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Prueba</title>
  <link rel="stylesheet" href="../src/output.css">
  <style>
    .hero-bg {
      background-image: url(../assets/images/bali.jpg);
      background-color: hsla(0, 100%, 100%, 0.2);
      backdrop-filter: blur(10px);
      clip-path: ellipse(130% 70% at 50% 0%);
    }
  </style>
</head>

<body>
  <div class="bg-[url(./assets/images/bali.jpg)] bg-cover w-full h-56 absolute top-0 z-0"></div>
  <?php
  include 'navbar.php';
  ?>
  <div class="hero-bg absolute inset-0 top-0 h-[400px] -z-10"></div>
  </div>
  <div class="w-full flex flex-col items-center justify-center py-10">
    <h1 class="font-gabarito font-bold text-2xl mb-4 text-white text-shadow-lg">Escoge en dónde quieres aterrizar tu próximo viaje</h1>
    <div class="relative z-10 flex flex-col gap-6 w-10/12 max-w-5xl mx-auto bg-white p-8 rounded-2xl shadow-xl">
  <!-- Fila superior -->
  <div class="flex justify-between items-center">
    <h2 class="text-2xl font-semibold text-gray-800">Escoge en dónde quieres aterrizar tu próximo viaje</h2>
    <div class="flex gap-2">
      <button class="px-4 py-2 rounded-full bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition">Ida y vuelta</button>
      <button class="px-4 py-2 rounded-full bg-blue-600 text-white font-medium hover:bg-blue-700 transition">Solo ida</button>
    </div>
  </div>

  <!-- Fila de inputs -->
  <div class="grid grid-cols-5 gap-3">
    <!-- Origen -->
    <div class="col-span-1">
      <label class="block text-sm font-medium text-gray-700 mb-1">Origen</label>
      <input type="text" placeholder="Bogotá" list="cities" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-gray-700" />
    </div>

    <!-- Destino -->
    <div class="col-span-1">
      <label class="block text-sm font-medium text-gray-700 mb-1">Destino</label>
      <input type="text" placeholder="Pereira" list="cities" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-gray-700" />
    </div>

    <!-- Fecha ida -->
    <div class="col-span-1">
      <label class="block text-sm font-medium text-gray-700 mb-1">Ida</label>
      <input type="date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-gray-700" />
    </div>

    <!-- Fecha vuelta -->
    <div class="col-span-1">
      <label class="block text-sm font-medium text-gray-700 mb-1">Vuelta</label>
      <input type="date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-gray-700" />
    </div>

    <!-- Pasajeros -->
    <div class="col-span-1">
      <label class="block text-sm font-medium text-gray-700 mb-1">Pasajeros</label>
      <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-gray-700">
        <option selected>1 pasajero</option>
        <option>2 pasajeros</option>
        <option>3 pasajeros</option>
      </select>
    </div>
  </div>

  <!-- Botón buscar -->
  <div class="flex justify-end">
    <button class="px-8 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition">Buscar</button>
  </div>

  <datalist id="cities">
    <option value="Bogotá"></option>
    <option value="Medellín"></option>
    <option value="Cali"></option>
    <option value="Pereira"></option>
    <option value="Manizales"></option>
  </datalist>
</div>

  </div>
  <div class="mx-14 my-4">
    <h2 class="font-gabarito text-xl mb-4">Echa un vistazo a las recomendaciones <span class="font-bold text-primary">Aterrizar</span> de esta temporada</h2>
    <div class="flex flex-wrap gap-10 justify-between">
      <div class="card bg-base-100 w-80 shadow-xl rounded-2xl">
        <figure>
          <img
            src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
            alt="Shoes" />
        </figure>
        <div class="card-body">
          <h3 class="card-title">Cartagena de Indias</h2>
            <div class="card-actions justify-end">
              <p class="text-right text-md font-bold text-gray-400">Desde <span class="text-xl text-neutral">180$ USD</span></p>
            </div>
        </div>
      </div>
      <div class="card bg-base-100 w-80 shadow-xl rounded-2xl">
        <figure>
          <img
            src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
            alt="Shoes" />
        </figure>
        <div class="card-body">
          <h3 class="card-title">Cartagena de Indias</h2>
            <div class="card-actions justify-end">
              <p class="text-right text-md font-bold text-gray-400">Desde <span class="text-xl text-neutral">180$ USD</span></p>
            </div>
        </div>
      </div>
      <div class="card bg-base-100 w-80 shadow-xl rounded-2xl">
        <figure>
          <img
            src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
            alt="Shoes" />
        </figure>
        <div class="card-body">
          <h3 class="card-title">Cartagena de Indias</h2>
            <div class="card-actions justify-end">
              <p class="text-right text-md font-bold text-gray-400">Desde <span class="text-xl text-neutral">180$ USD</span></p>
            </div>
        </div>
      </div>
      <div class="card bg-base-100 w-80 shadow-xl rounded-2xl">
        <figure>
          <img
            src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
            alt="Shoes" />
        </figure>
        <div class="card-body">
          <h3 class="card-title">Cartagena de Indias</h2>
            <div class="card-actions justify-end">
              <p class="text-right text-md font-bold text-gray-400">Desde <span class="text-xl text-neutral">180$ USD</span></p>
            </div>
        </div>
      </div>
      <div class="card bg-base-100 w-80 shadow-xl rounded-2xl">
        <figure>
          <img
            src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
            alt="Shoes" />
        </figure>
        <div class="card-body">
          <h3 class="card-title">Cartagena de Indias</h2>
            <div class="card-actions justify-end">
              <p class="text-right text-md font-bold text-gray-400">Desde <span class="text-xl text-neutral">180$ USD</span></p>
            </div>
        </div>
      </div>
      <div class="card bg-base-100 w-80 shadow-xl rounded-2xl">
        <figure>
          <img
            src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
            alt="Shoes" />
        </figure>
        <div class="card-body">
          <h3 class="card-title">Cartagena de Indias</h2>
            <div class="card-actions justify-end">
              <p class="text-right text-md font-bold text-gray-400">Desde <span class="text-xl text-neutral">180$ USD</span></p>
            </div>
        </div>
      </div>
    </div>
  </div>
  <?php
  include 'footer.php';
  ?>
</body>

</html>