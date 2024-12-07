<!DOCTYPE html>
<html lang="en">

<head>

    <title>Bella Fusión</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/templatemo.css">
    <link rel="stylesheet" href="assets/css/custom.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        .custom-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #4caf50;
            color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            font-size: 18px;
            text-align: center;
        }

        .custom-modal.show {
            display: block;
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-body {
            text-align: center;
        }

        .modal-footer {
            border-top: none;
            justify-content: center;
        }

        .modal-footer .btn {
            background-color: #833576;
            color: white;
        }

        /* Estilos para mostrar el corazón y el carrito */
        .product-img {
            position: relative;
            display: inline-block;
        }

        .product-icons {
            position: absolute;
            top: 10px;
            left: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-img:hover .product-icons {
            opacity: 1;
        }

        .product-icons i {
            font-size: 30px;
            color: #833576;
            margin-right: 10px;
        }
    </style>

</head>

<body>

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container d-flex justify-content-between align-items-center">

            <a class="navbar-brand text-success logo h1 align-self-center" href="index.html">
                Bella Fusión
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="align-self-center collapse navbar-collapse flex-fill d-lg-flex justify-content-lg-end" id="templatemo_main_nav">
    <!-- Contenedor con el botón Menú y los íconos alineados a la derecha -->
    <div class="d-flex align-items-center ms-auto">
        <!-- Botón Menú -->
        <button type="button" onclick="location.href='MenuBotones.php'" class="btn btn-custom me-3">Menú</button>

        <!-- Íconos de Manual Técnico y Usuario -->
        <!-- Ícono Manual Técnico -->
        <a href="http://localhost/proyectoweb/Manuales/Manual-Tecnico-Bella-Fusion.pdf" target="_blank" title="Manual Técnico" class="me-3">
            <i class="fas fa-book" style="font-size: 24px; color: #833576; cursor: pointer;"></i>
        </a>
        <!-- Ícono Manual Usuario -->
        <a href="http://localhost/proyectoweb/Manuales/Manual de Usuario (2).pdf" target="_blank" title="Manual de Usuario">
            <i class="fas fa-user-circle" style="font-size: 24px; color: #833576; cursor: pointer;"></i>
        </a>
    </div>
</div>
      <style>
                    button[type="button1"] {
                        background-color: #833576;
                        color: white;
                        float: right;
                    }
                    </style>
                </div>

    </nav>
    <!-- Close Header -->

    <!-- Modal -->
    <div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="w-100 pt-1 mb-5 text-right">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="get" class="modal-content modal-body border-0 p-0">
                <div class="input-group mb-2">
                    <input type="text" class="form-control" id="inputModalSearch" name="q" placeholder="Search ...">
                    <button type="submit" class="input-group-text bg-success text-light">
                        <i class="fa fa-fw fa-search text-white"></i>
                    </button>
                </div>

            </form>
        </div>
    </div>



    <!-- Start Banner Hero -->
    <div id="template-mo-zay-hero-carousel" class="carousel slide" data-bs-ride="carousel">
        <ol class="carousel-indicators">
            <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="0" class="active"></li>
            <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="1"></li>
            <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="container">
                    <div class="row p-5">
                        <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                            <img class="img-fluid" src="./assets/img1/bf01.png" alt="">
                        </div>
                        <div class="col-lg-6 mb-0 d-flex align-items-center">
                            <div class="text-align-left align-self-center">
                                <h3 class="h2">¿Qué secretos de belleza esconden otros países? Descúbrelos aquí.</h3>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="container">
                    <div class="row p-5">
                        <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                            <img class="img-fluid" src="./assets/img1/bf02.png" alt="">
                        </div>
                        <div class="col-lg-6 mb-0 d-flex align-items-center">
                            <div class="text-align-left">
                                <h3 class="h2">¿Como tener piel de porceana? Descúbrelos aquí.</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="container">
                    <div class="row p-5">
                        <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                            <img class="img-fluid" src="./assets/img1/bf09.png" alt="">
                        </div>
                        <div class="col-lg-6 mb-0 d-flex align-items-center">
                            <div class="text-align-left">
                                <h3 class="h2">¿Quieres tener una cabellera sedosa? Descúbrelo aqui</h3>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev text-decoration-none w-auto ps-3" href="#template-mo-zay-hero-carousel" role="button" data-bs-slide="prev">
            <i class="fas fa-chevron-left"></i>
        </a>
        <a class="carousel-control-next text-decoration-none w-auto pe-3" href="#template-mo-zay-hero-carousel" role="button" data-bs-slide="next">
            <i class="fas fa-chevron-right"></i>
        </a>
    </div>
    <!-- End Banner Hero -->

     <!-- Modal for success message -->
     <div class="custom-modal" id="successModal">
        Producto agregado con éxito al carrito o a favoritos!
    </div>

 <!-- Start Categories of The Month -->
    <section class="container py-5">
        <div class="row text-center pt-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1">Imprescindible tener</h1>
                <p></p>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-4 p-5 mt-3">
                <div class="product-img">
                    <a href="#"><img src="./assets/img1/bf03.jpg" class="rounded-circle img-fluid border"></a>
                    <div class="product-icons">
                        <i class="fas fa-heart"></i>
                        <i class="fas fa-shopping-cart" onclick="window.location.href='GenerarFactura.php';"></i>
                    </div>
                </div>
                <h5 class="text-center mt-3 mb-3">Rare Beauty Soft Pinch Blush Mini</h5>
               
            </div>
            <div class="col-12 col-md-4 p-5 mt-3">
                <div class="product-img">
                    <a href="#"><img src="./assets/img1/bf04.jpeg" class="rounded-circle img-fluid border"></a>
                    <div class="product-icons">
                        <i class="fas fa-heart"></i>
                        <i class="fas fa-shopping-cart" onclick="window.location.href='GenerarFactura.php';"></i>
                    </div>
                </div>
                <h2 class="h5 text-center mt-3 mb-3">Legit Lashes Mascara</h2>
                
            </div>
            <div class="col-12 col-md-4 p-5 mt-3">
                <div class="product-img">
                    <a href="#"><img src="./assets/img1/bf05.jpg" class="rounded-circle img-fluid border"></a>
                    <div class="product-icons">
                        <i class="fas fa-heart"></i>
                        <i class="fas fa-shopping-cart" onclick="window.location.href='GenerarFactura.php';"></i>
                    </div>
                </div>
                <h2 class="h5 text-center mt-3 mb-3">Gloss Maybelline</h2>
               
            </div>
        </div>
    </section>
<style>
    .producto:hover .iconos {
        display: block;
    }
</style>
<section class="bg-light">
    <div class="container py-5">
        <div class="row text-center py-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1">Los Favoritos</h1>
                <p></p>
            </div>
        </div>
        <div class="row">
            <!-- Primer Producto -->
            <div class="col-12 col-md-4 mb-4">
                <div class="card h-100">
                    <a href="CrearPedido.php">
                        <div class="product-img">
                            <img src="./assets/img1/bf06.jpeg" class="card-img-top" alt="...">
                            <div class="product-icons">
                                <i class="fas fa-heart" onclick="agregarAFavoritos('Paleta Discovery Rare Beauty')"></i>
                                <i class="fas fa-shopping-cart" onclick="window.location.href='CrearPedido.php';"></i>
                            </div>
                        </div>
                    </a>
                    <div class="card-body">
                        <ul class="list-unstyled d-flex justify-content-between">
                            <li>
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-muted fa fa-star"></i>
                                <i class="text-muted fa fa-star"></i>
                            </li>
                            <li class="text-muted text-right">L1040.00</li>
                        </ul>
                        <a href="CrearPedido.php" class="h2 text-decoration-none text-dark">Paleta Discovery Rare Beauty</a>
                        <p class="card-text">
                            Paleta con diferentes acabados (mattes, metálicos y glitter) en un empaque de tamaño compacto, elegante y minimalista
                        </p>
                        <p class="text-muted">Reviews (24)</p>
                    </div>
                </div>
            </div>

            <!-- Segundo Producto -->
            <div class="col-12 col-md-4 mb-4">
                <div class="card h-100">
                    <a href="CrearPedido.php">
                        <div class="product-img">
                            <img src="./assets/img1/bf07.jpeg" class="card-img-top" alt="...">
                            <div class="product-icons">
                                <i class="fas fa-heart" onclick="agregarAFavoritos('DUO LIP NICE Y NEUTRAL DE Rare beauty')"></i>
                                <i class="fas fa-shopping-cart" onclick="window.location.href='CrearPedido.php';"></i>
                            </div>
                        </div>
                    </a>
                    <div class="card-body">
                        <ul class="list-unstyled d-flex justify-content-between">
                            <li>
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-muted fa fa-star"></i>
                                <i class="text-muted fa fa-star"></i>
                            </li>
                            <li class="text-muted text-right">L900.00</li>
                        </ul>
                        <a href="CrearPedido.php" class="h2 text-decoration-none text-dark">DUO LIP NICE Y NEUTRAL DE Rare beauty</a>
                        <p class="card-text">
                            Un dúo de labios de edición limitada de tamaño completo con aceite de labio tintado Soft Pinch y Kind Words Matte Lip Liner en el marrón rosa universal perfecto.
                        </p>
                        <p class="text-muted">Reviews (48)</p>
                    </div>
                </div>
            </div>

            <!-- Tercer Producto -->
            <div class="col-12 col-md-4 mb-4">
                <div class="card h-100">
                    <a href="CrearPedido.php">
                        <div class="product-img">
                            <img src="./assets/img1/bf08.jpg" class="card-img-top" alt="...">
                            <div class="product-icons">
                                <i class="fas fa-heart" onclick="agregarAFavoritos('SHEGLAM Líquid Contour')"></i>
                                <i class="fas fa-shopping-cart" onclick="window.location.href='CrearPedido.php';"></i>
                            </div>
                        </div>
                    </a>
                    <div class="card-body">
                        <ul class="list-unstyled d-flex justify-content-between">
                            <li>
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-warning fa fa-star"></i>
                                <i class="text-warning fa fa-star"></i>
                            </li>
                            <li class="text-muted text-right">L360.00</li>
                        </ul>
                        <a href="CrearPedido.php" class="h2 text-decoration-none text-dark">SHEGLAM Líquid Contour</a>
                        <p class="card-text">
                            Sorpréndase con la perfección bañada por el sol con nuestro Sun Sculpt Liquid Contour. Obtenga una apariencia esculpida y arrebatada con nuestra fórmula altamente pigmentada, súper mezclable y de larga duración que hará que todos pregunten "¿Acabas de venir de vacaciones?"
                        </p>
                        <p class="text-muted">Reviews (74)</p>
                    </div>
                </div>
            </div>

           
        </div>
    </div>
</section>

<!-- Estilos para los iconos -->
<style>
    .product-img {
        position: relative;
        display: inline-block;
    }

    .product-icons {
        position: absolute;
        top: 10px;
        left: 10px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .product-img:hover .product-icons {
        opacity: 1;
    }

    .product-icons i {
        font-size: 30px;
        color: #833576;
        margin-right: 10px;
        cursor: pointer;
    }

    .product-icons i:hover {
        color: #ff6b81;
    }
</style>

<!-- Script para mostrar el mensaje -->
<script>
    function agregarAFavoritos(producto) {
        alert('¡Se agregó "' + producto + '" a tus favoritos! 💖');
    }
</script>

        <div class="w-100 bg-black py-3">
            <div class="container">
                <div class="row pt-2">
                    <div class="col-12">
                        <p class="text-left text-light">
                            Copyright &copy; 2024 bella fusión 
                            | Designed by STE_FER
                        </p>
                    </div>
                </div>
            </div>
        </div>
<!-- Success Message -->
<script>
        function showSuccessMessage() {
            // Mostrar el mensaje de éxito
            document.getElementById('successModal').classList.add('show');
            
            // Ocultar el mensaje después de 3 segundos
            setTimeout(function() {
                document.getElementById('successModal').classList.remove('show');
            }, 3000);
        }
    </script>
    </footer>

    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/templatemo.js"></script>
    <script src="assets/js/custom.js"></script>
    <!-- End Script -->
</body>

</html>