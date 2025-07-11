<?php
$pageTitle = $pageTitle ?? 'Sistema de Reservas';
$includeAnimations = $includeAnimations ?? false;
$includeTailwindConfig = $includeTailwindConfig ?? false;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <?php if ($includeAnimations): ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <?php endif; ?>

    <link rel="stylesheet" href="<?php echo isset($isRootPage) && $isRootPage ? 'app/assets/css/animations.css' : 'assets/css/animations.css'; ?>">

    <link rel="icon" href="https://avantfiscal.com.br/wp-content/uploads/2022/03/favicon-avant-fiscal-2022.svg"
        sizes="32x32" />
    <link rel="icon" href="https://avantfiscal.com.br/wp-content/uploads/2022/03/favicon-avant-fiscal-2022.svg"
        sizes="192x192" />
    <link rel="apple-touch-icon"
        href="https://avantfiscal.com.br/wp-content/uploads/2022/03/favicon-avant-fiscal-2022.svg" />
    <meta name="msapplication-TileImage"
        content="https://avantfiscal.com.br/wp-content/uploads/2022/03/favicon-avant-fiscal-2022.svg" />

    <script src="https://cdn.tailwindcss.com"></script>
    
    <?php if ($includeTailwindConfig): ?>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'orange': {
                            500: '#FB6206',
                            600: '#E55A00'
                        }
                    }
                }
            }
        }
    </script>
    <?php endif; ?>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/air-datepicker@3.3.5/air-datepicker.css">
    <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.3.5/air-datepicker.min.js"></script>
</head> 