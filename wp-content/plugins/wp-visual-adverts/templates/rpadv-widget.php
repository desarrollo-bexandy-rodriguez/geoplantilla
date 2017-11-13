<?php if (RPAdv()->getAdverts()) : ?>
    <div class="main-visual-adverts" data-id="<?php echo $params['id'];?>">
        <?php echo RPAdv()->getTemplate('rpadv-widget-list', $params); ?>
    </div>
<?php endif;
