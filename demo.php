<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mojani Project</title>


    <link rel="stylesheet" type="text/css" href="index.css">
<!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<!-- fontawsome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js" integrity="sha512-GWzVrcGlo0TxTRvz9ttioyYJ+Wwk9Ck0G81D+eO63BaqHaJ3YZX9wuqjwgfcV/MrB2PhaVX9DkYVhbFpStnqpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
     <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
    </head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">GeoPulse</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            
                <li class="nav-item dropdown p-1">
                
                    <button class="nav-link dropdown-toggle bg-primary text-light p-2 px-2" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                        style="border:0; font-size:15px ; border-radius:10px;" name="" type="" title="Upload PDF files">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span class="d-none d-sm-inline"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Survey Map PDF <span class="text-danger fs-3">*</span></a></li>
                        <li><a class="dropdown-item" href="#">Village Map PDF</a></li>
                        <li><a class="dropdown-item" href="#">7/12 PDF</a></li>
                    </ul>
                </li>
                <li class="nav-item p-1" >
                    <button class="tablinks bg-success "style="border:0; font-size:15px ; border-radius:10px;"><a class="nav-link disabled text-light fw-bold" aria-disabled="true">Maharastra</a></button>
                </li>

                <li class="p-1">
                    <form action="Logout.php" method="post">
                        <button class="tablinks bg-danger text-light p-2 px-2"
                            style="border:0; font-size:15px ; border-radius:10px;" name="Logout" type="submit">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span class="d-none d-sm-inline"> logout</span>
                        </button>
                    </form>
                </li>
            </ul>
            
            </div>
        </div>
    </nav>
    <section>
        <div id="map"></div>
    </section>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="index.js"></script>
</body>
</html>

