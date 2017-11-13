<?php
    $advert = $params;
    if (!empty($advert)) :
        $color = $advert->getColor();

?>
<div class="visual-adverts-item" data-id="<?php echo $advert->getId();?>"<?php echo !empty($color) && !$advert->getLink() ? ' style="color: '.$color.';"' : '';?>>

        <?php if ($advert->getLink()) : ?>
            <a href="<?php echo $advert->getLink();?>" title="<?php echo $advert->getTitle();?>" target="<?php echo $advert->getLinkTarget();?>"<?php echo !empty($color) ? ' style="color: '.$color.';"' : '';?>>
        <?php endif; ?>

            <?php if ($advert->getTitle()) : ?>
                <h3 class="visual-adverts-item-title"><?php echo $advert->getTitle();?></h3>
            <?php endif; ?>

            <?php if ($advert->getImage()) : ?>
                <div class="visual-adverts-item-image">
                    <img src="<?php echo $advert->getImage();?>" alt="<?php echo $advert->getTitle();?>">
                </div>
            <?php endif; ?>

            <?php if ($advert->getDescription()) : ?>
                <div class="visual-adverts-item-content">
                    <?php echo $advert->getDescription();?>
                </div>
            <?php endif; ?>


        <?php if ($advert->getLink()) : ?>
            </a>
        <?php endif; ?>

    </div>
<?php
    endif;

