<!DOCTYPE html>
<html lang="en">

<head>
    <?=
    $Layout->loadHead();
    ?>
</head>

<body>
    <?=
    $Layout->loadHeader();
    ?>
    <!-- Content -->
    <main id="content">
        <!-- ========== HERO ========== -->
        <? include('hero.php'); ?>
        <!-- ========== END HERO ========== -->

        <!-- Khách sạn phổ biến nhất -->
        <? include('popular.php'); ?>

        <!-- Huyện phổ biến nhất -->
        <? include('district_popular.php'); ?>

        <!-- Recommended hotel -->

        <? include('recommended.php'); ?>

        <!-- Banner -->
        <? include('banner.php'); ?>

    </main>
    <?=
    $Layout->loadfooter();
    ?>
</body>

</html>