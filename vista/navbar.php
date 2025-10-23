<?php
session_start();
require_once __DIR__ . '/../controlador/UsuarioControlador.php';
$controlador = new UsuarioControlador();
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensaje = $controlador->login($_POST['usuario_login'], $_POST['password']);
}
?>

<div class="navbar bg-primary shadow-sm px-5">
    <div class="flex-1">
        <a class="text-2xl font-gabarito font-bold flex items-center text-white gap-1"><svg class="h-7 w-7" viewBox="0 0 31 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M13.7386 25.288C14.3166 26.6265 14.7822 28.0105 15.1305 29.4261C16.1462 33.5642 12.3467 34.4483 12.3467 34.4483L8.11072 27.4172L0.673386 25.3218C0.673386 25.3218 1.33549 21.3191 4.66103 21.4621C6.01909 21.5185 7.5201 21.6615 8.82173 21.8119C9.79231 20.3486 11.0563 18.4262 12.4144 16.4286L0 12.1702C0 12.1702 0.752385 6.05333 7.18529 7.44148C9.89012 8.02458 14.047 8.84844 17.1958 9.46164C18.2679 7.95687 19.2272 6.64395 19.9608 5.7298C23.0004 1.96411 27.7894 -1.01534 29.4672 0.327671C31.145 1.67068 29.7719 7.18191 26.721 10.9927C24.5052 13.7916 17.4065 21.3906 13.7386 25.288ZM28.1919 23.8547C30.5243 28.7038 24.8814 31.9052 24.8814 31.9052L19.3777 23.2528L24.7497 16.8086C25.9084 19.156 27.1724 21.7593 28.1919 23.8697V23.8547Z" fill="white" />
            </svg>Aterrizar</a>
    </div>
    <div class="flex-none">
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                <div class="w-10 rounded-full">
                    <img
                        alt="Tailwind CSS Navbar component"
                        src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                </div>
            </div>
            <ul
                tabindex="-1"
                class="menu menu-sm dropdown-content bg-base-100 rounded-box z-20 mt-3 w-52 p-2 shadow">
                <li>
                    <button class="btn" onclick="modal_login.showModal()">login</button>
                </li>
            </ul>
        </div>
    </div>
    <dialog id="modal_login" class="modal">
        <div class="modal-box flex flex-col items-center rounded-xl">
            <div class="flex gap-1 items-center">
                <svg class="h-7 w-7" viewBox="0 0 31 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.7386 25.288C14.3166 26.6265 14.7822 28.0105 15.1305 29.4261C16.1462 33.5642 12.3467 34.4483 12.3467 34.4483L8.11072 27.4172L0.673386 25.3218C0.673386 25.3218 1.33549 21.3191 4.66103 21.4621C6.01909 21.5185 7.5201 21.6615 8.82173 21.8119C9.79231 20.3486 11.0563 18.4262 12.4144 16.4286L0 12.1702C0 12.1702 0.752385 6.05333 7.18529 7.44148C9.89012 8.02458 14.047 8.84844 17.1958 9.46164C18.2679 7.95687 19.2272 6.64395 19.9608 5.7298C23.0004 1.96411 27.7894 -1.01534 29.4672 0.327671C31.145 1.67068 29.7719 7.18191 26.721 10.9927C24.5052 13.7916 17.4065 21.3906 13.7386 25.288ZM28.1919 23.8547C30.5243 28.7038 24.8814 31.9052 24.8814 31.9052L19.3777 23.2528L24.7497 16.8086C25.9084 19.156 27.1724 21.7593 28.1919 23.8697V23.8547Z" fill="#0082CE" />
                </svg>
                <h3 class="text-4xl font-bold font-gabarito text-primary">Aterrizar</h3>
            </div>
            <p class="pt-2 font-roboto">Iniciar Sesión</p>
            <div class="w-full flex flex-col mt-1 items-center">
                <form class="w-full flex flex-col items-center gap-3" method="POST">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                    <div>
                        <label class="label">Usuario</label>
                        <input type="text" class="input focus:outline-none" name="usuario_login" placeholder="Ingrese su usuario" />
                    </div>
                    <div>
                        <label class="label">Contrasña</label>
                        <input type="text" class="input focus:outline-none" name="password" placeholder="Ingrese su contraseña" />
                    </div>
                    <button class="btn btn-primary w-full" type="submit" name="btn_login">Iniciar Sesión</button>
                </form>
            </div>
            <a class="text-accent cursor-pointer mt-2 text-sm text-center" href="registro.php">¿No tienes cuenta? Registrate</a>
        </div>
    </dialog>
</div>