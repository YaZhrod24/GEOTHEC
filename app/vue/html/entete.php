<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titre) ? $titre : 'NomDuProjet'; ?></title>

    <!-- Lien local de Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <?php if (defined('AFFICHER_LOADER') && AFFICHER_LOADER === true): ?>
        <style>
            #page-loader {
                position: fixed;
                inset: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
                transition: opacity .4s ease;
                background: var(--bs-body-bg);
            }

            .spinner {
                border: 6px solid rgba(0, 0, 0, .1);
                border-top: 6px solid var(--bs-primary);
                border-radius: 50%;
                width: 50px;
                height: 50px;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }

            .spinner {
                border: 6px solid var(--bs-border-color);
                border-top: 6px solid var(--bs-primary);
            }
        </style>

    <?php endif; ?>
</head>

<body>

    <?php if (defined('AFFICHER_LOADER') && AFFICHER_LOADER === true): ?>
        <div id="page-loader">
            <div class="spinner"></div>
        </div>

        <script>
            window.addEventListener('load', function () {
                const loader = document.getElementById('page-loader');
                if (loader) {
                    loader.style.opacity = '0';
                    setTimeout(() => {
                        loader.style.display = 'none';
                    }, 400);
                }
            });
        </script>
    <?php endif; ?>

    <script src="js/bootstrap.bundle.min.js"></script>