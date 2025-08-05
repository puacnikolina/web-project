<?php
$defaultHero = '../images/hero_home_default.jpg';
$heroImage = $defaultHero;
$title = '';


if ($exhibition && $exhibition->getHeroImage()) {
    $heroImage = '../' . htmlspecialchars($exhibition->getHeroImage());
    $title = htmlspecialchars($exhibition->getTitle() ?? 'Unknown');
}
?>
<section class="hero-section" style="background-image: url('<?php echo $heroImage; ?>');">
    <div class="hero-info">
        <?php if ($exhibition) { ?>
            <p class="hero-title"><?php echo $title; ?></p>
            <br>
        <?php } else { ?>
            <p>No exhibition information available.</p>
        <?php } ?>
    </div>
</section>
